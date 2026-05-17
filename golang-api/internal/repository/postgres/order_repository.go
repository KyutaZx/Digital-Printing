package postgres

import (
	"context"
	"database/sql"
	"fmt"
	"time"

	"golang-api/internal/domain/order"
)

type orderRepository struct {
	db *sql.DB
}

func NewOrderRepository(db *sql.DB) order.Repository {
	return &orderRepository{db: db}
}

// =========================================================================
// CREATE
// =========================================================================
func (r *orderRepository) Create(ctx context.Context, o *order.Order, items []order.OrderItem) error {
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return err
	}
	defer tx.Rollback()

	var total float64
	// 1. Validasi Variant & Hitung Total Harga murni dari DB
	for i := range items {
		var price float64
		err := tx.QueryRowContext(ctx, "SELECT price FROM product_variants WHERE id = $1", items[i].VariantID).Scan(&price)
		if err == sql.ErrNoRows {
			return fmt.Errorf("variant id %d tidak ditemukan", items[i].VariantID)
		}
		if err != nil {
			return err
		}
		items[i].Price = price
		total += price * float64(items[i].Quantity)
	}

	o.TotalPrice = total

	// 2. Simpan Header Order
	queryOrder := `
		INSERT INTO orders (user_id, order_code, total_price, status, created_at)
		VALUES ($1, $2, $3, $4, NOW())
		RETURNING id
	`
	err = tx.QueryRowContext(ctx, queryOrder, o.UserID, o.OrderCode, o.TotalPrice, o.Status).Scan(&o.ID)
	if err != nil {
		return err
	}

	// 3. Simpan Item Order
	for _, item := range items {
		queryItem := `
			INSERT INTO order_items (order_id, product_id, variant_id, quantity, price, notes)
			VALUES ($1, $2, $3, $4, $5, $6)
		`
		_, err = tx.ExecContext(ctx, queryItem, o.ID, item.ProductID, item.VariantID, item.Quantity, item.Price, item.Notes)
		if err != nil {
			return err
		}
	}

	return tx.Commit()
}

// =========================================================================
// CHECKOUT (TRANSACTION SAFE)
// =========================================================================
func (r *orderRepository) Checkout(ctx context.Context, userID int) (int, string, float64, error) {
	// Memulai Transaksi dengan Context
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return 0, "", 0, err
	}

	defer func() {
		if err != nil {
			tx.Rollback()
		}
	}()

	// 1. Ambil ID Keranjang User
	var cartID int
	err = tx.QueryRowContext(ctx, "SELECT id FROM carts WHERE user_id = $1", userID).Scan(&cartID)
	if err == sql.ErrNoRows {
		return 0, "", 0, fmt.Errorf("keranjang tidak ditemukan")
	}
	if err != nil {
		return 0, "", 0, err
	}

	// 2. Ambil Item Keranjang Beserta Detail Spesifikasi Cetak
	rows, err := tx.QueryContext(ctx, `
		SELECT ci.product_id, ci.quantity, pv.price, 
		       ci.variant_id, ci.notes, pv.material_id, pv.material_usage
		FROM cart_items ci
		JOIN product_variants pv ON pv.id = ci.variant_id
		WHERE ci.cart_id = $1
	`, cartID)
	if err != nil {
		return 0, "", 0, err
	}
	defer rows.Close()

	type item struct {
		productID     int
		quantity      int
		price         float64
		variantID     int
		notes         sql.NullString
		materialID    sql.NullInt64
		materialUsage sql.NullFloat64
	}

	var items []item
	var total float64
	materialUsages := make(map[int]float64)

	for rows.Next() {
		var i item
		err = rows.Scan(&i.productID, &i.quantity, &i.price, &i.variantID, &i.notes, &i.materialID, &i.materialUsage)
		if err != nil {
			return 0, "", 0, err
		}
		total += i.price * float64(i.quantity)
		
		if i.materialID.Valid && i.materialUsage.Valid {
			materialUsages[int(i.materialID.Int64)] += i.materialUsage.Float64 * float64(i.quantity)
		}
		
		items = append(items, i)
	}
	rows.Close() // Pastikan ditutup sebelum mengeksekusi query lain di transaksi ini

	// 2.5. ROW-LEVEL LOCKING & POTONG STOK
	for matID, usage := range materialUsages {
		var stock float64
		// Gunakan FOR UPDATE untuk mengunci baris bahan baku ini dari transaksi lain
		err = tx.QueryRowContext(ctx, "SELECT stock FROM materials WHERE id = $1 FOR UPDATE", matID).Scan(&stock)
		if err != nil {
			return 0, "", 0, fmt.Errorf("gagal mengecek stok material ID %d: %v", matID, err)
		}
		if stock < usage {
			return 0, "", 0, fmt.Errorf("stok bahan baku tidak mencukupi, sisa: %.2f, butuh: %.2f", stock, usage)
		}
		// Potong stok langsung di keranjang
		_, err = tx.ExecContext(ctx, "UPDATE materials SET stock = stock - $1 WHERE id = $2", usage, matID)
		if err != nil {
			return 0, "", 0, err
		}
	}

	if len(items) == 0 {
		return 0, "", 0, fmt.Errorf("keranjang kosong")
	}

	// 3. Buat Order Utama
	orderCode := fmt.Sprintf("ORD-%d%d", userID, time.Now().Unix())
	var orderID int
	queryOrder := `
		INSERT INTO orders (user_id, order_code, total_price, status, created_at)
		VALUES ($1, $2, $3, 'waiting_payment', NOW())
		RETURNING id
	`
	err = tx.QueryRowContext(ctx, queryOrder, userID, orderCode, total).Scan(&orderID)
	if err != nil {
		return 0, "", 0, err
	}

	// 4. Pindahkan Item Keranjang ke Order Items (Beserta Spesifikasi)
	for _, i := range items {
		queryItems := `
			INSERT INTO order_items (order_id, product_id, quantity, price, variant_id, notes)
			VALUES ($1, $2, $3, $4, $5, $6)
		`
		_, err = tx.ExecContext(ctx, queryItems,
			orderID, i.productID, i.quantity, i.price, i.variantID, i.notes,
		)
		if err != nil {
			return 0, "", 0, err
		}
	}

	// 5. Kosongkan Keranjang Setelah Checkout Berhasil
	_, err = tx.ExecContext(ctx, "DELETE FROM cart_items WHERE cart_id = $1", cartID)
	if err != nil {
		return 0, "", 0, err
	}

	// Selesaikan Transaksi
	if err = tx.Commit(); err != nil {
		return 0, "", 0, err
	}

	return orderID, orderCode, total, nil
}

// =========================================================================
// FIND BY ID
// =========================================================================
func (r *orderRepository) FindByID(ctx context.Context, orderID int) (*order.Order, error) {
	var o order.Order
	query := `SELECT id, user_id, status FROM orders WHERE id = $1`

	err := r.db.QueryRowContext(ctx, query, orderID).Scan(&o.ID, &o.UserID, &o.Status)
	if err == sql.ErrNoRows {
		return nil, nil
	}
	if err != nil {
		return nil, err
	}

	return &o, nil
}

// =========================================================================
// FIND DETAIL BY ID (Invoice)
// =========================================================================
func (r *orderRepository) FindDetailByID(ctx context.Context, orderID int) (*order.OrderDetail, error) {
	// 1. Query header order + info customer + items (multi-join)
	queryDetail := `
		SELECT
			o.id, o.order_code, o.status, o.total_price,
			o.estimated_finish_date, o.created_at, o.updated_at,
			u.id as customer_id, u.name as customer_name, u.formatted_id as customer_formatted_id,
			u.email as customer_email, COALESCE(u.phone, '') as customer_phone,
			oi.id as item_id, oi.product_id,
			p.name as product_name,
			COALESCE(oi.variant_id, 0) as variant_id,
			COALESCE(pv.variant_name, '-') as variant_name,
			COALESCE(pv.sku, '-') as sku,
			oi.quantity, oi.price as unit_price,
			(oi.quantity * oi.price) as subtotal,
			COALESCE(oi.notes, '') as notes
		FROM orders o
		JOIN v_users u ON u.id = o.user_id
		JOIN order_items oi ON oi.order_id = o.id
		JOIN products p ON p.id = oi.product_id
		LEFT JOIN product_variants pv ON pv.id = oi.variant_id
		WHERE o.id = $1
		ORDER BY oi.id ASC
	`

	rows, err := r.db.QueryContext(ctx, queryDetail, orderID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var detail *order.OrderDetail

	for rows.Next() {
		var item order.OrderItemDetail
		var d order.OrderDetail

		err := rows.Scan(
			&d.ID, &d.OrderCode, &d.Status, &d.TotalPrice,
			&d.EstimatedFinishDate, &d.CreatedAt, &d.UpdatedAt,
			&d.CustomerID, &d.CustomerName, &d.CustomerFormattedID, &d.CustomerEmail, &d.CustomerPhone,
			&item.ID, &item.ProductID, &item.ProductName,
			&item.VariantID, &item.VariantName, &item.SKU,
			&item.Quantity, &item.UnitPrice, &item.Subtotal, &item.Notes,
		)
		if err != nil {
			return nil, err
		}

		if detail == nil {
			detail = &d
		}
		detail.Items = append(detail.Items, item)
	}

	if err := rows.Err(); err != nil {
		return nil, err
	}

	if detail == nil {
		return nil, nil // Pesanan tidak ditemukan
	}

	// 1b. Query designs untuk setiap order item
	queryDesigns := `
		SELECT df.id, df.order_item_id, df.file_path, df.version, 
		       COALESCE((SELECT status FROM design_reviews WHERE design_file_id = df.id ORDER BY created_at DESC LIMIT 1), '') as status,
		       df.created_at
		FROM design_files df
		WHERE df.order_item_id IN (
			SELECT id FROM order_items WHERE order_id = $1
		)
		ORDER BY df.order_item_id ASC, df.version ASC
	`
	fmt.Printf("[DEBUG] Querying designs for order_id=%d\n", orderID)
	designRows, err := r.db.QueryContext(ctx, queryDesigns, orderID)
	if err != nil {
		fmt.Printf("[ERROR] QueryDesigns for Order %d: %v\n", orderID, err)
		// Tetap lanjut, designs akan kosong
	} else {
		defer designRows.Close()
		designMap := make(map[int][]order.DesignFile)
		rowCount := 0
		for designRows.Next() {
			var df order.DesignFile
			scanErr := designRows.Scan(&df.ID, &df.OrderItemID, &df.FilePath, &df.Version, &df.Status, &df.UploadedAt)
			if scanErr != nil {
				fmt.Printf("[ERROR] Scan design row for order %d: %v\n", orderID, scanErr)
				continue
			}
			fmt.Printf("[DEBUG] Design found: id=%d order_item_id=%d file=%s version=%d status=%s\n",
				df.ID, df.OrderItemID, df.FilePath, df.Version, df.Status)
			designMap[df.OrderItemID] = append(designMap[df.OrderItemID], df)
			rowCount++
		}
		if rowsErr := designRows.Err(); rowsErr != nil {
			fmt.Printf("[ERROR] designRows iteration error for order %d: %v\n", orderID, rowsErr)
		}
		fmt.Printf("[DEBUG] Total design rows found for order %d: %d\n", orderID, rowCount)
		// Attach designs ke masing-masing item
		for i, item := range detail.Items {
			if designs, ok := designMap[item.ID]; ok {
				detail.Items[i].Designs = designs
				fmt.Printf("[DEBUG] Item %d (id=%d) -> %d designs\n", i, item.ID, len(designs))
			} else {
				detail.Items[i].Designs = []order.DesignFile{}
				fmt.Printf("[DEBUG] Item %d (id=%d) -> 0 designs\n", i, item.ID)
			}
		}
	}


	// 2. Query info pembayaran dari tabel payment_transactions (opsional, bisa nil)
	queryPayment := `
		SELECT id, payment_method_id, COALESCE(transaction_code, ''),
			amount, payment_proof, payment_status,
			verified_by, verified_at, created_at
		FROM payment_transactions
		WHERE order_id = $1
		ORDER BY created_at DESC
		LIMIT 1
	`
	var pay order.PaymentInfo
	err = r.db.QueryRowContext(ctx, queryPayment, orderID).Scan(
		&pay.ID, &pay.MethodID, &pay.TransactionCode,
		&pay.Amount, &pay.Proof, &pay.Status,
		&pay.VerifiedBy, &pay.VerifiedAt, &pay.CreatedAt,
	)
	if err != nil && err != sql.ErrNoRows {
		return nil, err
	}
	if err == nil {
		detail.Payment = &pay
	}

	return detail, nil
}

// =========================================================================
// CANCEL ORDER
// =========================================================================
func (r *orderRepository) Cancel(ctx context.Context, orderID int, userID int) error {
	// 1. Cek status order saat ini
	var currentStatus string
	var err error
	if userID > 0 {
		err = r.db.QueryRowContext(ctx, "SELECT status FROM orders WHERE id = $1 AND user_id = $2", orderID, userID).Scan(&currentStatus)
	} else {
		err = r.db.QueryRowContext(ctx, "SELECT status FROM orders WHERE id = $1", orderID).Scan(&currentStatus)
	}
	
	if err == sql.ErrNoRows {
		return fmt.Errorf("pesanan tidak ditemukan atau akses ditolak")
	}
	if err != nil {
		return err
	}

	// 2. Cegah pembatalan jika order sudah masuk tahap produksi atau selesai
	if currentStatus == "printing" || currentStatus == "ready" || currentStatus == "completed" || currentStatus == "cancelled" {
		return fmt.Errorf("tidak dapat membatalkan pesanan karena status saat ini adalah: %s", currentStatus)
	}

	// 3. Lakukan pembatalan
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return err
	}
	defer tx.Rollback()

	_, err = tx.ExecContext(ctx, "UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = $1", orderID)
	if err != nil {
		return err
	}
	
	// 4. Logika pengembalian stok (Refund Stock) untuk status 'paid' dan 'waiting_payment'
	// (karena stok sudah dipotong sejak Checkout)
	if currentStatus == "paid" || currentStatus == "waiting_payment" {
		queryUsage := `
			SELECT oi.quantity, pv.material_id, pv.material_usage 
			FROM order_items oi
			JOIN product_variants pv ON oi.variant_id = pv.id
			WHERE oi.order_id = $1 AND pv.material_id IS NOT NULL AND pv.material_usage > 0
		`
		rows, err := tx.QueryContext(ctx, queryUsage, orderID)
		if err != nil {
			return err
		}
		
		type usageData struct {
			Qty           int
			MaterialID    int
			MaterialUsage float64
		}
		var usages []usageData
		for rows.Next() {
			var u usageData
			if err := rows.Scan(&u.Qty, &u.MaterialID, &u.MaterialUsage); err != nil {
				rows.Close()
				return err
			}
			usages = append(usages, u)
		}
		rows.Close()

		for _, u := range usages {
			totalUsage := float64(u.Qty) * u.MaterialUsage
			
			// Kembalikan stok
			_, err = tx.ExecContext(ctx, "UPDATE materials SET stock = stock + $1 WHERE id = $2", totalUsage, u.MaterialID)
			if err != nil {
				return err
			}

			// Catat ke log
			ref := fmt.Sprintf("Order Cancelled #%d (Refund)", orderID)
			_, err = tx.ExecContext(ctx, "INSERT INTO material_stock_logs (material_id, change_type, quantity, reference, created_at) VALUES ($1, 'in', $2, $3, NOW())", u.MaterialID, totalUsage, ref)
			if err != nil {
				return err
			}
		}
	}

	return tx.Commit()
}

// =========================================================================
// UPDATE STATUS
// =========================================================================
func (r *orderRepository) UpdateStatus(ctx context.Context, orderID int, status string) error {
	query := `UPDATE orders SET status = $1, updated_at = NOW() WHERE id = $2`
	res, err := r.db.ExecContext(ctx, query, status, orderID)
	if err != nil {
		return err
	}

	rows, _ := res.RowsAffected()
	if rows == 0 {
		return fmt.Errorf("pesanan tidak ditemukan")
	}
	return nil
}

// =========================================================================
// GET ORDERS BY USER ID (Customer Dashboard)
// =========================================================================
func (r *orderRepository) GetOrdersByUserID(ctx context.Context, userID int) ([]order.Order, error) {
	query := `
		SELECT o.id, o.user_id, u.name as customer_name, u.formatted_id as customer_formatted_id, 
		       o.order_code, o.total_price, o.status, o.created_at
		FROM orders o
		JOIN v_users u ON u.id = o.user_id
		WHERE o.user_id = $1
		ORDER BY o.created_at DESC
	`
	return r.scanOrders(ctx, query, userID)
}

// =========================================================================
// GET ALL ORDERS (Owner/Admin Dashboard)
// =========================================================================
func (r *orderRepository) GetAllOrders(ctx context.Context, limit int, offset int) ([]order.Order, error) {
	query := `
		SELECT o.id, o.user_id, u.name as customer_name, u.formatted_id as customer_formatted_id,
		       o.order_code, o.total_price, o.status, o.created_at
		FROM orders o
		JOIN v_users u ON u.id = o.user_id
		ORDER BY o.created_at DESC
		LIMIT $1 OFFSET $2
	`
	return r.scanOrders(ctx, query, limit, offset)
}

// =========================================================================
// CRON: FIND UNPAID ORDERS
// =========================================================================
func (r *orderRepository) FindUnpaidOrdersOlderThan(ctx context.Context, duration string) ([]int, error) {
	// duration format contoh: '24 HOURS'
	query := fmt.Sprintf(`
		SELECT id FROM orders 
		WHERE status = 'waiting_payment' 
		AND created_at < NOW() - INTERVAL '%s'
	`, duration)

	rows, err := r.db.QueryContext(ctx, query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var ids []int
	for rows.Next() {
		var id int
		if err := rows.Scan(&id); err != nil {
			return nil, err
		}
		ids = append(ids, id)
	}
	return ids, nil
}

// scanOrders adalah helper internal untuk scan rows orders dengan args variadic
func (r *orderRepository) scanOrders(ctx context.Context, query string, args ...interface{}) ([]order.Order, error) {
	rows, err := r.db.QueryContext(ctx, query, args...)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var orders []order.Order
	for rows.Next() {
		var o order.Order
		if err := rows.Scan(&o.ID, &o.UserID, &o.CustomerName, &o.CustomerFormattedID, &o.OrderCode, &o.TotalPrice, &o.Status, &o.CreatedAt); err != nil {
			return nil, err
		}
		orders = append(orders, o)
	}
	if err := rows.Err(); err != nil {
		return nil, err
	}
	return orders, nil
}

// =========================================================================
// COMPLETE ORDER (Customer mengonfirmasi barang diterima)
// =========================================================================
func (r *orderRepository) CompleteOrder(ctx context.Context, orderID int, userID int) error {
	query := `
		UPDATE orders 
		SET status = 'completed', updated_at = NOW() 
		WHERE id = $1 AND user_id = $2 AND status = 'ready'
	`
	res, err := r.db.ExecContext(ctx, query, orderID, userID)
	if err != nil {
		return err
	}

	rows, _ := res.RowsAffected()
	if rows == 0 {
		return fmt.Errorf("pesanan tidak ditemukan, bukan milik anda, atau statusnya bukan 'ready'")
	}
	return nil
}

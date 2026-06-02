# Deskripsi Class Diagram
# Jaya Mandiri — Digital Printing Management System

---

| | |
|---|---|
| **Dokumen** | Deskripsi Class Diagram |
| **Versi** | `v1.0.0 — FINAL` |
| **Status** | ✅ **APPROVED / FINAL** |
| **Tanggal Dibuat** | 02 Juni 2026 |
| **Terakhir Diperbarui** | 02 Juni 2026 |
| **Penulis** | Tim Pengembang Jaya Mandiri |
| **Referensi** | Class Diagram Jaya Mandiri Digital Printing |
| **Total Class** | 20 Class + 5 Enumeration |

> [!IMPORTANT]
> Dokumen ini adalah **versi FINAL** yang telah disetujui. Setiap perubahan wajib melalui proses review dan pembaruan versi.

---

## Tabel Deskripsi Class Diagram

| Class | Attribute | Method | Deskripsi |
|-------|-----------|--------|-----------|
| **User** | - id: integer<br>- role_id: integer<br>- name: varchar(100)<br>- email: varchar(100)<br>- password: varchar(255)<br>- phone: varchar(20)<br>- is_active: boolean<br>- created_at: DateTime<br>- updated_at: DateTime<br>- deleted_at: DateTime | + login(email, password): token<br>+ register(name, email, password, phone): User<br>+ getProfile(user_id): User<br>+ updateProfile(user_id, name, phone, password): User<br>+ getAllUsers(): []User<br>+ getUserById(user_id): User<br>+ createStaff(name, email, password): User<br>+ toggleUserActive(user_id): boolean<br>+ deleteUser(user_id): boolean | Kelas untuk merepresentasikan pengguna sistem, mencakup customer, staff, dan owner |
| **Role** | - id: integer<br>- name: varchar(50) | + getRoles(): []Role<br>+ getRoleById(id): Role | Kelas untuk mendefinisikan peran pengguna dalam sistem |
| **Category** | - id: integer<br>- name: varchar(100)<br>- created_at: DateTime | + getCategories(): []Category<br>+ createCategory(name): Category<br>+ updateCategory(id, name): Category<br>+ deleteCategory(id): boolean | Kelas untuk mengelola kategori produk percetakan |
| **Product** | - id: integer<br>- category_id: integer<br>- name: varchar(150)<br>- description: text<br>- base_price: decimal<br>- estimated_days: integer<br>- is_active: boolean<br>- image: varchar(255)<br>- created_at: DateTime<br>- updated_at: DateTime<br>- deleted_at: DateTime | + getAllProducts(): []Product<br>+ getProductById(id): Product<br>+ createProduct(category_id, name, description, base_price, estimated_days): Product<br>+ updateProduct(id, name, description, base_price, estimated_days): Product<br>+ deleteProduct(id): boolean<br>+ toggleProductActive(id): boolean | Kelas untuk merepresentasikan produk percetakan yang tersedia dalam katalog |
| **ProductVariant** | - id: integer<br>- product_id: integer<br>- sku: varchar(100)<br>- variant_name: varchar(255)<br>- price: decimal<br>- stock: integer<br>- is_active: boolean<br>- material_id: integer<br>- material_usage: decimal<br>- created_at: DateTime<br>- updated_at: DateTime | + getVariantsByProduct(product_id): []ProductVariant<br>+ createVariant(product_id, sku, variant_name, price, material_id, material_usage): ProductVariant<br>+ updateVariant(id, sku, variant_name, price, material_id, material_usage): ProductVariant<br>+ deleteVariant(id): boolean<br>+ toggleVariantActive(id): boolean | Kelas untuk mengelola varian dari setiap produk, seperti jenis finishing dan material |
| **Material** | - id: integer<br>- name: varchar(100)<br>- stock: decimal<br>- unit: varchar(20)<br>- created_at: DateTime | + getMaterials(): []Material<br>+ getMaterialById(id): Material<br>+ createMaterial(name, stock, unit): Material<br>+ updateMaterial(id, name, unit): Material<br>+ adjustStock(material_id, change_type, quantity, reference): Material | Kelas untuk mengelola bahan baku material yang digunakan dalam proses produksi percetakan |
| **Cart** | - id: integer<br>- user_id: integer<br>- created_at: DateTime | + getCartByUser(user_id): Cart<br>+ createCart(user_id): Cart<br>+ clearCart(cart_id): boolean | Kelas untuk merepresentasikan keranjang belanja milik customer |
| **CartItem** | - id: integer<br>- cart_id: integer<br>- product_id: integer<br>- variant_id: integer<br>- quantity: integer<br>- notes: text | + addItem(cart_id, product_id, variant_id, quantity, notes): CartItem<br>+ updateItem(id, quantity, notes): CartItem<br>+ removeItem(id): boolean<br>+ getItemsByCart(cart_id): []CartItem | Kelas untuk merepresentasikan setiap item yang ada di dalam keranjang belanja |
| **Order** | - id: integer<br>- user_id: integer<br>- order_code: varchar(50)<br>- total_price: decimal<br>- status: status_order<br>- estimated_finish_date: date<br>- created_at: DateTime<br>- updated_at: DateTime | + checkout(user_id): Order<br>+ getOrdersByUser(user_id): []Order<br>+ getOrderById(id): Order<br>+ getAllOrders(): []Order<br>+ cancelOrder(id): boolean<br>+ confirmOrderComplete(id): boolean<br>+ updateOrderStatus(id, status, changed_by, notes): Order<br>+ generateOrderCode(): string | Kelas untuk merepresentasikan pesanan yang telah dibuat oleh customer melalui proses checkout |
| **OrderItem** | - id: integer<br>- order_id: integer<br>- product_id: integer<br>- variant_id: integer<br>- quantity: integer<br>- price: decimal<br>- notes: text | + getItemsByOrder(order_id): []OrderItem<br>+ getOrderItemById(id): OrderItem | Kelas untuk merepresentasikan setiap item produk yang ada dalam satu pesanan |
| **PaymentMethod** | - id: integer<br>- name: varchar(100) | + getPaymentMethods(): []PaymentMethod<br>+ getPaymentMethodById(id): PaymentMethod | Kelas untuk mendefinisikan metode pembayaran yang tersedia (BCA Transfer, Mandiri Transfer, QRIS) |
| **PaymentTransaction** | - id: integer<br>- order_id: integer<br>- payment_method_id: integer<br>- transaction_code: varchar(100)<br>- amount: decimal<br>- payment_proof: varchar(255)<br>- payment_status: status_payment<br>- verified_by: integer<br>- verified_at: DateTime<br>- created_at: DateTime | + createPayment(order_id, payment_method_id, amount, payment_proof): PaymentTransaction<br>+ approvePayment(id, verified_by): PaymentTransaction<br>+ rejectPayment(id): PaymentTransaction<br>+ getPaymentByOrder(order_id): PaymentTransaction<br>+ getAllPayments(): []PaymentTransaction | Kelas untuk mengelola transaksi pembayaran dari customer, termasuk proses verifikasi oleh staff |
| **DesignFile** | - id: integer<br>- order_item_id: integer<br>- file_path: varchar(255)<br>- version: integer<br>- uploaded_by: integer<br>- created_at: DateTime | + uploadDesign(order_item_id, file_path, uploaded_by): DesignFile<br>+ getDesignsByOrderItem(order_item_id): []DesignFile<br>+ getLatestDesign(order_item_id): DesignFile<br>+ checkBlurQuality(file_path): BlurResult | Kelas untuk mengelola file desain yang diupload oleh customer untuk setiap item pesanan, termasuk versioning |
| **DesignReview** | - id: integer<br>- design_file_id: integer<br>- reviewed_by: integer<br>- status: status_review<br>- notes: text<br>- created_at: DateTime | + approveDesign(design_file_id, reviewed_by): DesignReview<br>+ requestRevision(design_file_id, reviewed_by, notes): DesignReview<br>+ getReviewByDesign(design_file_id): DesignReview<br>+ getAllDesignReviews(): []DesignReview | Kelas untuk mengelola proses review desain yang dilakukan oleh staff terhadap file desain customer |
| **OrderStatusLog** | - id: integer<br>- order_id: integer<br>- status: status_order<br>- changed_by: integer<br>- notes: text<br>- created_at: DateTime | + logStatusChange(order_id, status, changed_by, notes): OrderStatusLog<br>+ getStatusLogsByOrder(order_id): []OrderStatusLog | Kelas untuk mencatat seluruh riwayat perubahan status pada setiap pesanan secara kronologis |
| **ProductionLog** | - id: integer<br>- order_id: integer<br>- staff_id: integer<br>- start_time: DateTime<br>- end_time: DateTime<br>- notes: text<br>- created_at: DateTime | + startProduction(order_id, staff_id): ProductionLog<br>+ finishProduction(id, notes): ProductionLog<br>+ getProductionLogs(): []ProductionLog<br>+ getProductionLogByOrder(order_id): ProductionLog | Kelas untuk mencatat aktivitas proses produksi cetak, termasuk waktu mulai, selesai, dan staff yang bertanggung jawab |
| **MaterialStockLog** | - id: integer<br>- material_id: integer<br>- change_type: type_change<br>- quantity: decimal<br>- reference: varchar(100)<br>- created_at: DateTime | + logStockChange(material_id, change_type, quantity, reference): MaterialStockLog<br>+ getStockLogsByMaterial(material_id): []MaterialStockLog<br>+ getAllStockLogs(): []MaterialStockLog | Kelas untuk mencatat seluruh perubahan stok material, baik penambahan (in) maupun pengurangan (out) |
| **AuditLog** | - id: integer<br>- user_id: integer<br>- role: varchar(50)<br>- action: varchar(255)<br>- entity_type: varchar(50)<br>- entity_id: integer<br>- ip_address: varchar(50)<br>- user_agent: text<br>- created_at: DateTime | + logAction(user_id, role, action, entity_type, entity_id, ip_address): AuditLog<br>+ getAuditLogs(): []AuditLog<br>+ getAuditLogsByUser(user_id): []AuditLog | Kelas untuk mencatat seluruh aksi penting yang dilakukan pengguna dalam sistem untuk keperluan keamanan dan akuntabilitas |
| **LoginLog** | - id: integer<br>- user_id: integer<br>- activity_type: type_activity<br>- ip_address: varchar(50)<br>- user_agent: text<br>- created_at: DateTime | + logActivity(user_id, activity_type, ip_address, user_agent): LoginLog<br>+ getLoginLogs(): []LoginLog<br>+ getLoginLogsByUser(user_id): []LoginLog | Kelas untuk mencatat aktivitas login dan logout seluruh pengguna sistem beserta informasi perangkat |
| **LoginAttempt** | - id: integer<br>- email: varchar(100)<br>- ip_address: varchar(50)<br>- success: boolean<br>- created_at: DateTime | + recordAttempt(email, ip_address, success): LoginAttempt<br>+ getAttemptsByEmail(email): []LoginAttempt<br>+ getAttemptsByIP(ip_address): []LoginAttempt | Kelas untuk mencatat setiap percobaan login ke sistem, baik yang berhasil maupun gagal, untuk mencegah brute force |

---

## Tabel Deskripsi Enumeration

| Class | Attribute | Method | Deskripsi |
|-------|-----------|--------|-----------|
| **\<\<enumeration\>\>**<br>status_order | - waiting_payment<br>- payment_verification<br>- paid<br>- design_review<br>- printing<br>- production<br>- ready<br>- completed<br>- cancelled | — | Enum untuk mendefinisikan seluruh status yang mungkin terjadi pada sebuah pesanan, dari awal hingga selesai |
| **\<\<enumeration\>\>**<br>status_payment | - pending<br>- approved<br>- rejected | — | Enum untuk mendefinisikan status transaksi pembayaran yang dikirimkan oleh customer |
| **\<\<enumeration\>\>**<br>status_review | - approved<br>- revision_requested | — | Enum untuk mendefinisikan hasil review desain yang dilakukan oleh staff |
| **\<\<enumeration\>\>**<br>type_activity | - login<br>- logout | — | Enum untuk mendefinisikan jenis aktivitas yang dicatat pada login log |
| **\<\<enumeration\>\>**<br>type_change | - in<br>- out | — | Enum untuk mendefinisikan jenis perubahan stok material, apakah penambahan (in) atau pengurangan (out) |

---

## Ringkasan Relasi Antar Class

| Relasi | Class Asal | Class Tujuan | Jenis Relasi | Keterangan |
|--------|-----------|--------------|-------------|-----------|
| User memiliki Role | User | Role | Association (Many-to-One) | Setiap user memiliki satu role |
| User memiliki Cart | User | Cart | Association (One-to-One) | Setiap customer memiliki satu keranjang |
| User membuat Order | User | Order | Association (One-to-Many) | Satu user dapat memiliki banyak pesanan |
| Cart memiliki CartItem | Cart | CartItem | Composition (One-to-Many) | Keranjang terdiri dari banyak item |
| CartItem merujuk Product | CartItem | Product | Association (Many-to-One) | Setiap item keranjang merujuk ke satu produk |
| CartItem merujuk ProductVariant | CartItem | ProductVariant | Association (Many-to-One) | Setiap item keranjang merujuk ke satu varian |
| Order memiliki OrderItem | Order | OrderItem | Composition (One-to-Many) | Pesanan terdiri dari banyak item |
| Order memiliki PaymentTransaction | Order | PaymentTransaction | Association (One-to-One) | Setiap pesanan memiliki satu transaksi pembayaran |
| Order memiliki OrderStatusLog | Order | OrderStatusLog | Association (One-to-Many) | Pesanan memiliki banyak riwayat perubahan status |
| Order memiliki ProductionLog | Order | ProductionLog | Association (One-to-One) | Setiap pesanan memiliki satu catatan produksi |
| OrderItem memiliki DesignFile | OrderItem | DesignFile | Association (One-to-Many) | Setiap item pesanan dapat memiliki banyak versi desain |
| DesignFile memiliki DesignReview | DesignFile | DesignReview | Association (One-to-One) | Setiap file desain memiliki satu hasil review |
| Product memiliki ProductVariant | Product | ProductVariant | Composition (One-to-Many) | Produk terdiri dari banyak varian |
| Product dimiliki Category | Product | Category | Association (Many-to-One) | Setiap produk termasuk dalam satu kategori |
| ProductVariant menggunakan Material | ProductVariant | Material | Association (Many-to-One) | Setiap varian menggunakan satu jenis material |
| Material memiliki MaterialStockLog | Material | MaterialStockLog | Association (One-to-Many) | Material memiliki banyak riwayat perubahan stok |
| PaymentTransaction menggunakan PaymentMethod | PaymentTransaction | PaymentMethod | Association (Many-to-One) | Setiap transaksi menggunakan satu metode pembayaran |
| User memiliki AuditLog | User | AuditLog | Association (One-to-Many) | Setiap user memiliki banyak catatan audit |
| User memiliki LoginLog | User | LoginLog | Association (One-to-Many) | Setiap user memiliki banyak riwayat login/logout |

---

> 📌 Dokumen ini mencakup **20 Class** dan **5 Enumeration** yang mengacu pada Class Diagram sistem Jaya Mandiri Digital Printing Management System.

---

<div align="center">

*Dibuat oleh Tim Pengembang Jaya Mandiri*
*Deskripsi Class Diagram v1.0.0 — FINAL | 02 Juni 2026*
*© 2026 Jaya Mandiri. All rights reserved.*

</div>

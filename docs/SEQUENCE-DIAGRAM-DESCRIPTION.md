# Deskripsi Sequence Diagram
# Jaya Mandiri — Digital Printing Management System

---

| | |
|---|---|
| **Dokumen** | Deskripsi Sequence Diagram |
| **Versi** | `v1.0.0 — FINAL` |
| **Status** | ✅ **APPROVED / FINAL** |
| **Tanggal Dibuat** | 02 Juni 2026 |
| **Penulis** | Tim Pengembang Jaya Mandiri |
| **Total Sequence Diagram** | 21 Sequence Diagram |

---

## 4.2.1.5 Sequence Diagram

### 1. Register

Terlampir di LAMPIRAN Sequence Diagram – *Register*

Proses *register* dimulai ketika *Customer* memanggil fungsi **registerCustomer(name, email, phone, pass)** pada sistem (objek `:User`). Selanjutnya, objek `:User` akan melakukan validasi mandiri dengan memanggil fungsi **registerValidation(name, email, phone, pass)**. Setelah itu, terdapat percabangan (*alt*) berdasarkan hasil validasi. Jika data tidak valid, sistem akan menampilkan pesan error (**tampilkan pesan error**) kembali ke *Customer*. Sedangkan jika data valid, objek `:User` akan menyimpan data akun baru (**simpan data akun baru**), kemudian sistem memanggil fungsi **submitAuditLog(new_user_id, "register", "users")** ke objek `:AuditLogs`, dan terakhir sistem akan mengarahkan *Customer* dengan pesan **alihkan ke halaman login**.

---

### 2. Login

Terlampir di LAMPIRAN Sequence Diagram – *Login*

Proses *login* dimulai ketika *Customer, Staff, Manager* memanggil fungsi **handleLogin(email, pass)** pada sistem (objek `:User`). Selanjutnya, objek `:User` akan melakukan validasi dengan memanggil fungsi **loginValidation(email, pass)**. Terdapat percabangan (*alt*) berdasarkan hasil validasi. Jika email dan sandi tidak sesuai, sistem akan menampilkan pesan error (**tampilkan pesan error**) kembali ke *user*. Sedangkan jika email dan sandi sesuai, sistem akan menyimpan sesi *login* (**simpan sesi login**) secara internal pada objek `:User`, kemudian sistem mencatat aktivitas dengan memanggil **submitSessionLog(user_id, login, ip)** pada objek `:LoginLogs` dan **submitAuditLog(user_id, "login", "users")** pada objek `:AuditLogs`. Selanjutnya di dalam proses yang valid tersebut, terdapat percabangan (*alt*) lanjutan untuk mengarahkan halaman berdasarkan *role*. Jika *role* adalah *owner*, sistem mengarahkan *user* dengan pesan **menuju dashboard Owner**. Jika *role* adalah *staff*, sistem mengarahkan *user* dengan pesan **menuju dashboard Staff**. Jika *role* adalah *customer*, sistem mengarahkan *user* dengan pesan **menuju dashboard Customer**.

---

### 3. Logout

Terlampir di LAMPIRAN Sequence Diagram – *Logout*

*Catatan: Mengacu pada diagram dengan judul "Logout", namun alur proses mendeskripsikan pembaruan profil.*

Proses dimulai ketika *Customer, Staff, Manager* membuka halaman profil (**membuka halaman Profil**) yang diteruskan ke objek `:User`. Objek `:User` kemudian memberikan respon **Menampilkan data profil saat ini**. Selanjutnya terdapat percabangan (*alt*) untuk konfirmasi pembaruan. Jika pengguna memilih tidak, sistem akan merespon dengan **Kembali ke halaman sebelumnya**. Jika pengguna memilih ya, *user* memanggil fungsi **updateProfile(name, email, phone)** pada objek `:User`. Objek `:User` kemudian melakukan validasi dengan memanggil fungsi **profileValidation(name, phone)**. Di dalam percabangan "ya" ini, terdapat percabangan (*alt*) validasi tambahan. Jika data tidak valid atau kosong, objek `:User` akan **Menampilkan pesan error** kepada *user*. Sedangkan jika data valid, objek `:User` akan melakukan proses **Update data akun di Database**, setelah itu memanggil fungsi **submitAuditLog(user_id, "update_profile", "users")** ke objek `:AuditLogs`, dan terakhir sistem **Menampilkan pesan "Profil berhasil diperbarui"** kepada *user*.

---

### 4. Kelola Profil

Terlampir di LAMPIRAN Sequence Diagram – *Kelola Profil*

Proses kelola profil dimulai ketika *Customer* mengirim pesan **fetchProductCatalog(filterKategori, keywordCari)** ke objek `:User`, yang kemudian membalas dengan **Menampilkan data profil saat ini**. Terdapat percabangan (*alt*) jika *Customer* ingin mengubah data. Jika tidak ingin mengubah data, sistem akan memberikan respon **Kembali kehalaman sebelumnya** kepada *Customer*. Jika ya, *Customer* memanggil fungsi **updateProfileData(user_id, updatedData)** ke objek `:User`. Di dalam percabangan "ya" ini, terdapat percabangan (*alt*) validasi tambahan. Jika data valid dan tidak kosong bernilai tidak, objek `:User` akan **Menampilkan pesan error** kepada *Customer*. Sedangkan jika data valid dan tidak kosong bernilai ya, objek `:User` akan melakukan aksi **Update data akun di database**, kemudian merespon dengan **Menampilkan pesan 'Profil berhasil di perbarui'** kepada *Customer*.

---

### 5. Menampilkan Produk

Terlampir di LAMPIRAN Sequence Diagram – *Menampilkan Produk*

Proses menampilkan produk dimulai ketika *Customer* memanggil fungsi **fetchProductCatalog(filterKategori, keywordCari)** ke objek `:Products`. Setelah itu, terdapat percabangan (*alt*). Jika produk tidak tersedia, objek `:Products` akan memberikan respon **Tampilkan pesan 'Produk tidak ditemukan'** kepada *Customer*. Sedangkan jika produk tersedia, objek `:Products` memberikan respon **Menampilkan daftar produk & harga** kepada *Customer*.

---

### 6. Aksi Keranjang

Terlampir di LAMPIRAN Sequence Diagram – *Aksi Keranjang*

Proses kelola keranjang dimulai ketika *Customer* melakukan aksi **klik "Tambah ke Keranjang" (pilih produk & varian)** ke objek `:User`. Objek `:User` kemudian memanggil fungsi **checkVariantStock(variant_id)** ke objek `:ProductVariant` dan mendapatkan respon **data varian valid & stok tersedia**. Setelah itu, objek `:User` mengirim pesan **itemValidation(product_id, variant_id, qty)** ke objek `:CartItem`. Selanjutnya, terdapat percabangan (*alt*). Jika data item tidak valid atau stok kurang, objek `:User` akan memberikan respon **tampilkan pesan error** kepada *Customer*. Sedangkan jika data item valid, objek `:User` memanggil fungsi **getOrCreateCart(user_id)** ke objek `:Cart`, yang kemudian membalas dengan **cart_id ditemukan/dibuat**. Objek `:User` lalu memanggil fungsi **addProductToCart(cart_id, product_id, variant_id, qty)** ke objek `:CartItem`, dan diakhiri dengan memberikan respon **Menampilkan pesan "Produk berhasil ditambahkan ke keranjang"** kepada *Customer*. Pada alur lain, ketika *Customer* melakukan aksi **membuka halaman Keranjang Belanja**, objek `:User` akan memanggil fungsi **fetchCartItems(cart_id)** ke objek `:CartItem`. Objek `:CartItem` mengembalikan **data daftar item keranjang beserta detail produk**, lalu objek `:User` memberikan respon **Menampilkan daftar produk di dalam keranjang** kepada *Customer*. Terakhir, jika *Customer* melakukan aksi **mengubah jumlah produk / klik hapus**, sistem akan memprosesnya melalui percabangan (*alt*). Jika jumlah produk sama dengan 0 atau *Customer* klik tombol hapus, objek `:User` memanggil fungsi **removeCartItem(cart_item_id)** ke objek `:CartItem`, lalu merespon dengan **Menampilkan daftar keranjang terbaru (item terhapus)** kepada *Customer*. Sedangkan jika jumlah produk lebih dari 0, objek `:User` memanggil fungsi **itemValidation(product_id, variant_id, new_qty)** dilanjutkan dengan **updateCartQty(cart_item_id, new_qty)** ke objek `:CartItem`, dan terakhir merespon dengan **Menampilkan daftar keranjang dengan total harga ter-update** kepada *Customer*.

---

### 7. Checkout

Terlampir di LAMPIRAN Sequence Diagram – *Checkout*

Proses *checkout* dimulai ketika *Customer* melakukan aksi **menuju halaman Checkout** ke objek `:User`. Objek `:User` kemudian memanggil fungsi **fetchCartItems(cart_id)** ke objek `:CartItem` dan menerima balasan berupa **data rincian item, biaya, & total harga**. Setelah itu, objek `:User` merespon dengan **Menampilkan rincian item, biaya, dan total harga** kepada *Customer*. Selanjutnya, *Customer* melakukan aksi **Klik tombol "Buat Pesanan"** ke objek `:User`, yang diteruskan dengan memanggil fungsi **checkoutValidation(cart_id, user_id)** ke objek `:Orders`. Setelah validasi, terdapat percabangan (*alt*). Jika keranjang kosong atau tidak valid (Jalur Tidak), objek `:User` akan memberikan respon **Menampilkan pesan "Keranjang kosong"** kepada *Customer*. Sedangkan jika keranjang valid dan berisi barang (Jalur Ya), objek `:User` akan memanggil fungsi **orderValidation(orderAttributes)** dilanjutkan dengan aksi **Generate Kode Pesanan & Simpan ke tabel Orders** ke objek `:Orders`. Objek `:Orders` kemudian membalas dengan status **order_id berhasil dibuat**. Selanjutnya, objek `:User` mengirim instruksi **Simpan detail barang ke tabel OrderItem(order_id, cart_id)** ke objek `:OrderItem` yang dibalas dengan status **detail barang berhasil disimpan**. Objek `:User` lalu memanggil aksi **Kosongkan keranjang item dari database(cart_id)** ke objek `:CartItem` yang dibalas dengan status **keranjang berhasil dikosongkan**. Setelah itu, objek `:User` memanggil fungsi **submitAuditLog(user_id, "checkout", "orders")** ke objek `:AuditLogs`. Terakhir, sistem memberikan respon **Menampilkan pesan "Pesanan berhasil dibuat"** dan **Diarahkan ke halaman Upload Desain / Pembayaran** kepada *Customer*.

---

### 8. Upload File Desain

Terlampir di LAMPIRAN Sequence Diagram – *Upload File Desain*

Proses *upload* file desain dimulai ketika *Customer* melakukan aksi **Menuju halaman detail pesanan** ke objek `:User (Sistem)`, dilanjutkan dengan aksi **Pilih file desain & masukkan catatan**, dan terakhir **Klik tombol 'Upload File'**. Objek `:User (Sistem)` kemudian memanggil fungsi **fileValidation(file_format, file_size)** pada dirinya sendiri. Terdapat percabangan (*alt*) berdasarkan hasil validasi format dan ukuran file. Jika format atau ukuran file tidak valid, objek `:User (Sistem)` akan memberikan respon **Menampilkan pesan error file** kepada *Customer*. Sedangkan jika format dan ukuran file valid, objek `:User (Sistem)` mengirim pesan **Simpan data ke tabel DesignFiles (status="Menunggu Review")** ke objek `:DesignFiles`. Objek `:DesignFiles` membalas dengan **data berhasil disimpan**. Setelah itu, objek `:User (Sistem)` memanggil fungsi **submitAuditLog(user_id, "upload_design", "design_files")** ke objek `:AuditLogs`. Terakhir, objek `:User (Sistem)` merespon dengan **Menampilkan pesan 'File berhasil diupload, menunggu review'** kepada *Customer*.

---

### 9. Upload Pembayaran

Terlampir di LAMPIRAN Sequence Diagram – *Upload Pembayaran*

Proses *upload* pembayaran dimulai ketika *Customer* melakukan aksi **Menuju halaman Pembayaran** ke objek `:User`, dilanjutkan dengan aksi **Pilih metode & upload bukti transfer**. Objek `:User` kemudian memanggil fungsi **paymentValidation(method, file)** pada dirinya sendiri, dan mengirimkan pesan **itemValidation(product_id, variant_id, qty)** ke objek `:Orders`. Setelah itu, terdapat percabangan (*alt*) berdasarkan hasil validasi. Jika file dan input tidak valid, objek `:User` akan **Menampilkan pesan error** kepada *Customer*. Sedangkan jika file dan input valid, objek `:User` mengirim instruksi **Simpan ke tabel PaymentTransactions (status="Pending")** ke objek `PaymentTransaction`. Objek `PaymentTransaction` kemudian memberikan respon **Data pembayaran berhasil disimpan** kembali ke `:User`. Kemudian objek `:User` mengirim pesan **updateOrderStatus(order_id, "Menunggu Verifikasi Pembayaran")** ke objek `:Orders`, lalu memanggil **submitAuditLog(user_id, "create_payment", "payment_transactions")** ke objek `:AuditLogs`, dan terakhir sistem **Menampilkan pesan "Bukti pembayaran berhasil diupload, menunggu verifikasi"** kepada *Customer*.

---

### 10. Lihat Status Pesanan

Terlampir di LAMPIRAN Sequence Diagram – *Lihat Status Pesanan*

Proses melihat status pesanan dimulai ketika *Customer* melakukan aksi **menuju halaman riwayat pesanan** ke objek `:User`. Objek `:User` memanggil fungsi **fetchUserOrder(user_id)** ke objek `:Orders`, yang membalas dengan **data daftar pesanan(ID, tanggal, Status, total)**. Objek `:User` kemudian **Menampilkan daftar pesanan dari tabel Orders** kepada *Customer*. Selanjutnya, terdapat percabangan (*alt*) untuk melihat detail pesanan atau mengunduh invoice. Pada bagian melihat detail pesanan, *Customer* melakukan aksi **Klik salah satu baris pesanan** ke objek `:User`. Objek `:User` memanggil **getOrderDetail(order_id)** ke objek `:Orders` dan menerima **Data pesanan**. Objek `:User` juga memanggil **fetchOrderItem(order_id)** ke objek `:OrderItem` dan menerima **Rincian produk yang di pesan**. Objek `:User` juga memanggil **fetchStatusLogs(order_id)** ke objek `:AuditLogs` dan menerima **Data riwayat perubahan status**. Terakhir, objek `:User` **menampilkan detail pesanan dan riwayat status** kepada *Customer*. Pada bagian unduh invoice, *Customer* melakukan aksi **klik tombol 'lihat invoice'** ke objek `:User`, yang direspon dengan **mengunduh/ menampilkan dokumen invoice**. Proses diakhiri ketika *Customer* melakukan aksi **klik tombol 'kembali'** ke objek `:User`, dan sistem merespon dengan **Diarahkan ke halaman dashboard utama** kepada *Customer*.

---

### 11. Membatalkan Pesanan

Terlampir di LAMPIRAN Sequence Diagram – *Membatalkan Pesanan*

Proses membatalkan pesanan dimulai ketika *Customer* melakukan aksi **Menuju Halaman Detail Pesanan** ke objek `:User (Sistem)`, yang kemudian memberikan respon **Menampilkan halaman detail pesanan** kepada *Customer*. Setelah itu, *Customer* melakukan aksi **Klik Tombol "Batalkan Pesanan"** ke objek `:User (Sistem)`. Objek `:User (Sistem)` kemudian memanggil fungsi **validateStatusIsPending(status)** pada dirinya sendiri. Terdapat percabangan (*alt*) berdasarkan status pesanan tersebut. Jika status pesanan tidak pending, sistem akan memberikan respon **Tampilkan Pesan "Pesanan sudah di proses, Tidak Bisa Dibatalkan"** kepada *Customer*. Sedangkan jika status pesanan masih pending, sistem akan memberikan respon **Tampilkan dialog konfirmasi "Yakin ingin Membatalkan?"** kepada *Customer*. Di dalam kondisi ini, terdapat percabangan (*alt*) lanjutan. Jika *Customer* tidak yakin dan melakukan aksi **Pilih "Tidak" / Tutup dialog**, sistem akan merespon dengan **Batal & Kembali Ke Detail Pesanan**. Namun jika *Customer* yakin membatalkan dan melakukan aksi **Pilih "Ya"**, objek `:User (Sistem)` akan memanggil fungsi **updateOrderStatus(order_id, "Dibatalkan")** ke objek `:Orders` yang kemudian mengembalikan respon pembaruan status. Selanjutnya, objek `:User (Sistem)` memanggil fungsi **insertStatusLog(order_id, "Dibatalkan")** ke objek `:OrderStatusLogs` yang dibalas dengan status **log riwayat berhasil dicatat**. Terakhir, objek `:User (Sistem)` memberikan respon **Tampilkan Notifikasi "Pesanan Berhasil Dibatalkan"** kepada *Customer*.

---

### 12. Konfirmasi Pesanan Selesai

Terlampir di LAMPIRAN Sequence Diagram – *Konfirmasi Pesanan Selesai*

Proses konfirmasi selesai pesanan dimulai ketika *Customer* melakukan aksi **Menuju Halaman Detail Pesanan** ke objek `:User (Sistem)`. Objek `:User (Sistem)` memanggil fungsi **getOrderDetails(order_id)** ke objek `:Orders`, dan menerima respon **data detail & status pesanan saat ini**. Kemudian, objek `:User (Sistem)` memanggil fungsi **checkStatusValidity(status)** pada dirinya sendiri. Terdapat percabangan (*alt*) berdasarkan status tersebut. Jika status bukan 'dikirim' atau 'siap Diambil', objek `:User (Sistem)` merespon dengan **Tombol Konfirmasi Ditampilkan Disabled / Hidden** kepada *Customer*. Sedangkan jika status 'dikirim' atau 'siap Diambil', objek `:User (Sistem)` merespon dengan **Menampilkan Tombol 'Pesanan Diterima / Selesai'** kepada *Customer*. Selanjutnya, *Customer* melakukan aksi **Klik Tombol 'Pesanan Diterima / Selesai'** ke objek `:User (Sistem)`. Objek `:User (Sistem)` membalas dengan **Tampilkan Dialog 'Pesanan Sudah Diterima & Sesuai?'**. Terdapat percabangan (*alt*) lanjutan. Jika *Customer* memilih tidak dengan aksi **Pilih 'Tidak'**, sistem merespon dengan **Batal & Kembali Ke Detail Pesanan**. Namun jika *Customer* memilih ya dengan aksi **Pilih 'Ya'**, objek `:User (Sistem)` memanggil fungsi **updateOrderStatus(order_id, "Selesai")** ke objek `:Orders` yang membalas dengan **status berhasil diperbarui**. Kemudian, objek `:User (Sistem)` memanggil fungsi **insertStatusLog(order_id, "Selesai")** ke objek `:OrderStateLogs`, yang merespon dengan **log riwayat berhasil dicatat**. Terakhir, objek `:User (Sistem)` memberikan respon **Menampilkan Pesan 'Pesanan Telah Selesai, Terima Kasih'** kepada *Customer*.

---

### 13. Verifikasi Pembayaran

Terlampir di LAMPIRAN Sequence Diagram – *Verifikasi Pembayaran*

Proses verifikasi pembayaran dimulai ketika *Staff* membuka halaman daftar pesanan dan memilih pesanan yang berstatus "payment_verification". Sistem memanggil fungsi **getOrderById(id)** untuk mengambil data lengkap pesanan. Sistem kemudian memanggil fungsi **getPaymentByOrder(order_id)** untuk mengambil data transaksi pembayaran beserta gambar bukti bayar yang diupload oleh *customer*. *Staff* memeriksa kesesuaian jumlah transfer dengan total tagihan dan keaslian bukti bayar. Apabila bukti pembayaran sesuai, *Staff* menekan tombol "Setujui Pembayaran". Sistem memanggil fungsi **approvePayment(id, verified_by)** untuk mengubah status transaksi pembayaran menjadi "approved". Sistem kemudian memanggil fungsi **updateOrderStatus(order_id, "design_review", staff_id, "Pembayaran diverifikasi")** untuk menggerakkan pesanan ke status "design_review". Apabila bukti pembayaran tidak sesuai atau tidak valid, *Staff* menekan tombol "Tolak Pembayaran". Sistem memanggil fungsi **rejectPayment(id)** untuk mengubah status transaksi menjadi "rejected", sementara status pesanan tetap "payment_verification" agar *customer* dapat mengupload ulang bukti bayar. Setiap keputusan yang diambil dicatat ke *order_status_logs* dan sistem mengirimkan notifikasi *real-time* kepada *Customer* melalui *WebSocket*.

---

### 14. Review Desain

Terlampir di LAMPIRAN Sequence Diagram – *Review Desain*

Proses *review* desain dimulai ketika *Staff* membuka pesanan yang berstatus "design_review". Sistem memanggil fungsi **getItemsByOrder(order_id)** untuk mendapatkan seluruh item pesanan. Untuk setiap item, sistem memanggil fungsi **getLatestDesign(order_item_id)** guna mengambil file desain terbaru beserta hasil pengecekan AI (*skor confidence* dan status *blur/sharp*). *Staff* memeriksa setiap desain secara visual dan mempertimbangkan hasil pengecekan AI. Apabila desain dinilai layak cetak, *Staff* menekan tombol "Approve". Sistem memanggil fungsi **approveDesign(design_file_id, reviewed_by)** untuk mencatat hasil *review* dengan status "approved" ke tabel *design_reviews*. Apabila semua item dalam pesanan sudah diapprove, sistem memanggil fungsi **updateOrderStatus(order_id, "printing", staff_id, "Semua desain disetujui")** untuk menggerakkan pesanan ke status "printing". Apabila desain tidak layak, *Staff* menekan tombol "Minta Revisi" dan mengisi catatan alasan revisi yang wajib diisi. Sistem memanggil fungsi **requestRevision(design_file_id, reviewed_by, notes)** untuk mencatat permintaan revisi dengan status "revision_requested". Sistem mengirimkan notifikasi beserta catatan revisi kepada *Customer* melalui *WebSocket* agar *Customer* dapat mengupload file desain yang diperbaiki.

---

### 15. Tambah Produk

Terlampir di LAMPIRAN Sequence Diagram – *Tambah Produk*

Proses tambah produk dimulai ketika *Owner* membuka halaman manajemen produk dan menekan tombol "Tambah Produk Baru". Sistem menampilkan halaman *form* penginputan data produk. *Owner* mengisi data produk meliputi nama produk, kategori, deskripsi, harga dasar, estimasi hari selesai, dan gambar produk. *Owner* juga menambahkan minimal satu varian produk dengan mengisi SKU, nama varian, harga, material yang digunakan, dan jumlah penggunaan material. Setelah semua data diisi, *Owner* menekan tombol "Simpan". Sistem memanggil fungsi **createProduct(category_id, name, description, base_price, estimated_days)** untuk menyimpan data produk baru ke *database* dengan status aktif. Sistem kemudian memanggil fungsi **createVariant(product_id, sku, variant_name, price, material_id, material_usage)** untuk setiap varian yang ditambahkan. Apabila SKU varian sudah digunakan oleh produk lain, sistem menampilkan pesan *error* "SKU sudah digunakan". Ketika semua data berhasil disimpan, sistem memanggil fungsi **logAction(user_id, "owner", "create_product", "products", product_id, ip_address)** untuk mencatat aktivitas ke *audit_logs*. Sistem menampilkan pesan "Produk berhasil ditambahkan" kepada *Owner*.

---

### 16. Selesai Produksi

Terlampir di LAMPIRAN Sequence Diagram – *Selesai Produksi*

Proses selesai produksi dimulai ketika *Staff* melakukan aksi **Menuju halaman Antrean Produksi** ke objek `:User (Sistem)`. Objek `:User (Sistem)` memanggil fungsi **fetchActiveProductionOrders()** ke objek `:Orders` dan menerima balasan **data daftar pesanan berstatus 'Sedang Diproduksi'**. Objek `:User (Sistem)` kemudian memberikan respon **Menampilkan pesanan berstatus "Sedang Diproduksi"** kepada *Staff*. *Staff* lalu melakukan aksi **Pilih pesanan & klik tombol 'Selesai Produksi'** ke objek `:User (Sistem)`. Objek `:User (Sistem)` memanggil fungsi **confirmProductionCompletion()** pada dirinya sendiri. Terdapat percabangan (*alt*) konfirmasi. Jika proses cetak benar-benar selesai adalah tidak, sistem merespon dengan **Batal & Kembali ke daftar produksi** kepada *Staff*. Sedangkan jika proses cetak benar-benar selesai adalah ya, objek `:User (Sistem)` memanggil fungsi **updateProductionEndTime(order_id)** ke objek `:ProductionLogs`, yang membalas dengan **'end time' berhasil dicatat ke ProductionLogs**. Selanjutnya, objek `:User (Sistem)` memanggil fungsi **updateOrderStatus(order_id, "Siap Diambil / Dikirim")** ke objek `:Orders`, yang membalas dengan **status pesanan diperbarui menjadi 'Siap Diambil / Dikirim'**. Objek `:User (Sistem)` lalu memanggil fungsi **insertStatusLog(order_id, "Siap Diambil / Dikirim")** ke objek `:OrderStateLogs`, yang membalas dengan **perubahan dicatat ke tabel OrderStateLogs**. Terakhir, objek `:User (Sistem)` memberikan respon **Kirim notifikasi ke Customer & tampilkan pesan sukses** kepada *Staff*.

---

### 17. Kelola Pesanan Staff

Terlampir di LAMPIRAN Sequence Diagram – *Kelola Pesanan Staff*

Proses kelola pesanan oleh *Staff* dimulai ketika *Staff* membuka halaman manajemen pesanan. Sistem memanggil fungsi **getAllOrders()** untuk mengambil seluruh data pesanan dari *database* yang diurutkan berdasarkan tanggal terbaru. Sistem menampilkan daftar pesanan lengkap beserta kode pesanan, nama *customer*, total harga, dan status terkini setiap pesanan. *Staff* dapat memfilter pesanan berdasarkan status untuk mempermudah pengelolaan. Sistem memanggil fungsi **getOrdersByStatus(status)** sesuai filter yang dipilih oleh *Staff*. Ketika *Staff* memilih pesanan tertentu, sistem memanggil fungsi **getOrderById(id)** untuk menampilkan detail lengkap pesanan. Apabila tidak ada pesanan yang masuk, sistem menampilkan pesan "Belum ada pesanan masuk".

---

### 18. Update dan Hapus Produk

Terlampir di LAMPIRAN Sequence Diagram – *Update dan Hapus Produk*

Proses *update* produk dimulai ketika *Owner* membuka halaman manajemen produk dan menekan tombol "Edit" pada produk yang ingin diubah. Sistem memanggil fungsi **getProductById(id)** untuk menampilkan data produk saat ini beserta variannya dalam *form* edit. *Owner* mengubah data yang perlu diperbarui seperti nama, harga, deskripsi, estimasi, atau varian. Setelah selesai, *Owner* menekan tombol "Simpan Perubahan". Sistem memanggil fungsi **updateProduct(id, name, description, base_price, estimated_days)** untuk menyimpan perubahan data produk. Apabila varian diubah, sistem memanggil fungsi **updateVariant(id, sku, variant_name, price, material_id, material_usage)** untuk setiap varian yang dimodifikasi. Proses hapus produk dimulai ketika *Owner* menekan tombol "Hapus" pada produk. Sistem menampilkan konfirmasi penghapusan. Ketika *Owner* mengkonfirmasi, sistem memanggil fungsi **deleteProduct(id)** yang melakukan *soft delete* dengan mengisi kolom *deleted_at* dan mengubah *is_active* menjadi *false*. Produk yang dihapus tidak lagi ditampilkan di katalog *customer*. Setiap perubahan dicatat ke *audit_logs* melalui fungsi **logAction(user_id, "owner", action, "products", product_id, ip_address)**. Sistem menampilkan pesan keberhasilan kepada *Owner*.

---

### 19. Kelola Material

Terlampir di LAMPIRAN Sequence Diagram – *Kelola Material*

Proses kelola material dimulai ketika *Owner* membuka menu "Manajemen Material" pada *dashboard*. Sistem memanggil fungsi **getMaterials()** untuk mengambil seluruh data material dari *database* beserta stok terkini. Sistem menampilkan daftar material kepada *Owner*. Ketika *Owner* ingin menambahkan material baru, *Owner* menekan tombol "Tambah Material Baru" dan mengisi nama material, stok awal, serta satuan (Meter/Rim/Lembar). Sistem memanggil fungsi **createMaterial(name, stock, unit)** untuk menyimpan material baru. Apabila nama material sudah terdaftar, sistem menampilkan peringatan duplikasi. Ketika *Owner* ingin menyesuaikan stok material, *Owner* menekan tombol "Sesuaikan Stok" pada material yang diinginkan, kemudian memilih tipe penyesuaian "Masuk (in)" atau "Keluar (out)", mengisi jumlah dan keterangan. Sistem memanggil fungsi **adjustStock(material_id, change_type, quantity, reference)** untuk memperbarui stok material di *database*. Sistem juga memanggil fungsi **logStockChange(material_id, change_type, quantity, reference)** untuk mencatat perubahan stok ke tabel *material_stock_logs*. Apabila pengurangan stok melebihi stok yang tersedia, sistem menampilkan pesan *error* "Stok tidak mencukupi". Setiap aksi dicatat ke *audit_logs* dan sistem menampilkan pesan keberhasilan kepada *Owner*.

---

### 20. Laporan Pendapatan

Terlampir di LAMPIRAN Sequence Diagram – *Laporan Pendapatan*

Proses laporan pendapatan dimulai ketika *Owner* membuka menu "Laporan Pendapatan" pada *dashboard*. Sistem secara otomatis menampilkan laporan pendapatan untuk periode bulan berjalan sebagai tampilan awal. Sistem mengambil data pesanan berstatus "completed" dari *database* melalui fungsi **getOrdersByStatus("completed")**, kemudian melakukan agregasi data untuk menghitung total *revenue*, jumlah pesanan selesai, dan rata-rata nilai pesanan. *Owner* dapat mengubah periode laporan dengan memilih filter Harian, Mingguan, Bulanan, atau *Custom Range* tanggal. Sistem memanggil kembali fungsi pengambilan data sesuai periode yang dipilih dan memperbarui tampilan laporan. Sistem juga memanggil fungsi **getAllProducts()** yang dikombinasikan dengan data *order_items* untuk menghitung statistik produk terlaris berdasarkan jumlah unit terjual dan total *revenue* per produk. Apabila tidak ada pesanan selesai pada periode yang dipilih, sistem menampilkan pesan "Tidak ada data pendapatan pada periode ini".

---

### 21. Melihat Log Sistem

Terlampir di LAMPIRAN Sequence Diagram – *Melihat Log Sistem*

Proses melihat log sistem dimulai ketika *Owner* membuka menu log pada *dashboard*. Terdapat tiga jenis log yang dapat diakses oleh *Owner*. Pertama, *Audit Log* yang dapat diakses melalui menu "Audit Log". Sistem memanggil fungsi **getAuditLogs()** untuk mengambil seluruh data aksi penting dari tabel *audit_logs*, kemudian menampilkan informasi berupa nama *user*, *role*, aksi yang dilakukan, entitas terkait, *IP address*, dan waktu kejadian. Kedua, *Login Log* yang dapat diakses melalui menu "Login Log". Sistem memanggil fungsi **getLoginLogs()** untuk mengambil data dari tabel *login_logs*, kemudian menampilkan aktivitas *login* dan *logout* seluruh pengguna beserta *IP address* dan informasi perangkat (*user agent*). Ketiga, *Production Log* yang dapat diakses melalui menu "Production Log". Sistem memanggil fungsi **getProductionLogs()** untuk mengambil data dari tabel *production_logs*, kemudian menampilkan riwayat produksi seluruh pesanan termasuk nama *staff* yang mengerjakan, waktu mulai (*start_time*), waktu selesai (*end_time*), durasi pengerjaan, dan catatan produksi. Pada setiap jenis log, *Owner* dapat melakukan filter berdasarkan tanggal, pengguna, atau kriteria lain yang tersedia. Apabila tidak ada data log yang ditemukan, sistem menampilkan pesan "Belum ada data tercatat".

---

> 📌 Dokumen ini mencakup deskripsi **21 Sequence Diagram** yang mengacu pada *Sequence Diagram* sistem Jaya Mandiri Digital Printing Management System.

---

<div align="center">

*Dibuat oleh Tim Pengembang Jaya Mandiri*
*Deskripsi Sequence Diagram v1.0.0 — FINAL | 02 Juni 2026*
*© 2026 Jaya Mandiri. All rights reserved.*

</div>

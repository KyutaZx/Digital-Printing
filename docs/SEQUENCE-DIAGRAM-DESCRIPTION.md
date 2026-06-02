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

Proses kelola profil dimulai ketika *Customer* membuka halaman profil dan menekan tombol "Edit Profil". Sistem memanggil fungsi **getProfile(user_id)** untuk mengambil data profil terkini dari *database* berdasarkan JWT *token* yang aktif. Sistem menampilkan data profil yang sudah ada kepada *Customer*. *Customer* kemudian mengubah data yang diinginkan seperti nama, nomor telepon, atau *password*, lalu menekan tombol "Simpan". Sistem memanggil fungsi **updateProfile(user_id, name, phone, password)** yang menampung data baru. Data tersebut akan melalui tahap validasi dengan memanggil fungsi **updateProfileValidation(name, phone, password)**. Ketika data tidak valid, seperti *password* baru kurang dari 8 karakter, maka sistem akan menampilkan pesan *error*. Sedangkan ketika semua data valid, sistem menyimpan perubahan ke *database* dan menampilkan pesan "Profil berhasil diperbarui" kepada *Customer*.

---

### 5. Menampilkan Produk

Terlampir di LAMPIRAN Sequence Diagram – *Menampilkan Produk*

Proses menampilkan produk dimulai ketika *Customer* membuka halaman katalog produk. Sistem memanggil fungsi **getAllProducts()** untuk mengambil seluruh data produk yang berstatus aktif (*is_active = true*) dari *database*. Sistem mengembalikan daftar produk lengkap beserta nama, harga dasar, estimasi hari selesai, dan gambar produk. Sistem kemudian menampilkan daftar produk tersebut kepada *Customer*. Ketika *Customer* menekan salah satu produk untuk melihat detail, sistem memanggil fungsi **getProductById(id)** yang mengambil data lengkap produk beserta seluruh variannya melalui fungsi **getVariantsByProduct(product_id)**. Apabila tidak ada produk aktif di sistem, maka sistem menampilkan pesan "Belum ada produk tersedia".

---

### 6. Aksi Keranjang

Terlampir di LAMPIRAN Sequence Diagram – *Aksi Keranjang*

Proses aksi keranjang mencakup tiga operasi utama yaitu menambah, mengubah, dan menghapus item dari keranjang belanja. Proses dimulai ketika *Customer* memilih produk beserta variannya dari halaman detail produk. Sistem memanggil fungsi **getCartByUser(user_id)** untuk memastikan keranjang milik *Customer* sudah tersedia. Saat *Customer* menekan "Tambah ke Keranjang", sistem memanggil fungsi **addItem(cart_id, product_id, variant_id, quantity, notes)** yang menyimpan item baru ke dalam keranjang. Sistem memvalidasi bahwa produk dan varian masih aktif sebelum menyimpan. Ketika *Customer* ingin mengubah jumlah item, sistem memanggil fungsi **updateItem(id, quantity, notes)** dengan kuantitas baru. Apabila kuantitas yang dimasukkan kurang dari satu, sistem menampilkan pesan validasi "Jumlah minimal 1". Ketika *Customer* ingin menghapus item, sistem memanggil fungsi **removeItem(id)** setelah *Customer* mengkonfirmasi penghapusan. Setiap operasi berhasil dilakukan, sistem menghitung ulang total harga keranjang dan memperbarui tampilan.

---

### 7. Checkout

Terlampir di LAMPIRAN Sequence Diagram – *Checkout*

Proses *checkout* dimulai ketika *Customer* menekan tombol "Checkout" pada halaman keranjang. Sistem memanggil fungsi **getItemsByCart(cart_id)** untuk memastikan keranjang tidak kosong. Apabila keranjang kosong, sistem menampilkan pesan "Keranjang masih kosong". Ketika keranjang memiliki item, sistem memvalidasi ketersediaan stok material untuk setiap item melalui pengecekan stok pada *database*. Apabila stok material tidak mencukupi, sistem menampilkan pesan "Stok tidak mencukupi untuk produk tertentu". Ketika semua validasi berhasil, sistem memanggil fungsi **generateOrderCode()** untuk menghasilkan kode pesanan unik dengan format ORD-{timestamp}. Sistem kemudian memanggil fungsi **checkout(user_id)** yang membuat pesanan baru dengan status "waiting_payment" dan mengurangi stok material sesuai varian yang dipesan melalui fungsi **adjustStock(material_id, "out", quantity, order_code)**. Sistem juga memanggil fungsi **logStatusChange(order_id, "waiting_payment", user_id, "")** untuk mencatat status awal pesanan ke *order_status_logs*. Setelah pesanan berhasil dibuat, sistem mengosongkan keranjang dan mengarahkan *Customer* ke halaman detail pesanan baru.

---

### 8. Upload File Desain

Terlampir di LAMPIRAN Sequence Diagram – *Upload File Desain*

Proses *upload* file desain dimulai ketika *Customer* membuka halaman detail pesanan dan menekan tombol "Upload Desain" pada salah satu item. *Customer* memilih file gambar berformat JPG atau PNG dari perangkat. Sistem memanggil fungsi **uploadDesign(order_item_id, file_path, uploaded_by)** untuk memproses file yang dipilih. Sistem terlebih dahulu memvalidasi format dan ukuran file. Ketika format file bukan JPG atau PNG, sistem menampilkan pesan *error* "Format file tidak didukung". Ketika ukuran file melebihi 10 MB, sistem menampilkan pesan *error* "Ukuran file terlalu besar". Ketika file valid, sistem mengirimkan file ke layanan AI (*Python AI Service*) untuk pengecekan kualitas gambar melalui fungsi **checkBlurQuality(file_path)** yang menggunakan model *MobileNetV2* untuk mendeteksi apakah gambar *blur* atau tajam (*sharp*). Apabila layanan AI tidak tersedia, sistem tetap menyimpan file dengan status *sharp* secara *default*. Hasil pengecekan AI dikembalikan ke sistem, kemudian file disimpan ke *server* dan data desain dicatat ke *database* dengan nomor versi yang bertambah secara otomatis. Sistem menampilkan hasil pengecekan AI kepada *Customer*, beserta peringatan apabila desain terdeteksi *blur*.

---

### 9. Upload Pembayaran

Terlampir di LAMPIRAN Sequence Diagram – *Upload Pembayaran*

Proses *upload* pembayaran dimulai ketika *Customer* melakukan aksi **Menuju halaman Pembayaran** ke objek `:User`, dilanjutkan dengan aksi **Pilih metode & upload bukti transfer**. Objek `:User` kemudian memanggil fungsi **paymentValidation(method, file)** pada dirinya sendiri, dan mengirimkan pesan **itemValidation(product_id, variant_id, qty)** ke objek `:Orders`. Setelah itu, terdapat percabangan (*alt*) berdasarkan hasil validasi. Jika file dan input tidak valid, objek `:User` akan **Menampilkan pesan error** kepada *Customer*. Sedangkan jika file dan input valid, objek `:User` mengirim instruksi **Simpan ke tabel PaymentTransactions (status="Pending")** ke objek `PaymentTransaction`. Objek `PaymentTransaction` kemudian memberikan respon **Data pembayaran berhasil disimpan** kembali ke `:User`. Kemudian objek `:User` mengirim pesan **updateOrderStatus(order_id, "Menunggu Verifikasi Pembayaran")** ke objek `:Orders`, lalu memanggil **submitAuditLog(user_id, "create_payment", "payment_transactions")** ke objek `:AuditLogs`, dan terakhir sistem **Menampilkan pesan "Bukti pembayaran berhasil diupload, menunggu verifikasi"** kepada *Customer*.

---

### 10. Lihat Detail Pesanan

Terlampir di LAMPIRAN Sequence Diagram – *Lihat Detail Pesanan*

Proses melihat detail pesanan dimulai ketika *Customer* membuka halaman riwayat pesanan. Sistem memanggil fungsi **getOrdersByUser(user_id)** untuk mengambil seluruh data pesanan milik *Customer* dari *database*, diurutkan dari yang terbaru. Apabila *Customer* belum memiliki pesanan, sistem menampilkan pesan "Belum ada pesanan". Ketika *Customer* memilih salah satu pesanan, sistem memanggil fungsi **getOrderById(id)** untuk mengambil data lengkap pesanan. Sistem juga memanggil fungsi **getItemsByOrder(order_id)** untuk mendapatkan seluruh item pesanan, **getDesignsByOrderItem(order_item_id)** untuk status desain setiap item, dan **getPaymentByOrder(order_id)** untuk mendapatkan data transaksi pembayaran. Sistem menampilkan informasi lengkap pesanan kepada *Customer* meliputi kode pesanan, total harga, status pesanan, daftar item, status desain per item, status pembayaran, dan estimasi tanggal selesai. *Customer* dapat menekan tombol "Lihat Invoice" untuk mengakses tampilan invoice resmi dari pesanan tersebut.

---

### 11. Membatalkan Pesanan

Terlampir di LAMPIRAN Sequence Diagram – *Membatalkan Pesanan*

Proses membatalkan pesanan dimulai ketika *Customer* membuka halaman detail pesanan yang berstatus "waiting_payment" dan menekan tombol "Batalkan Pesanan". Sistem menampilkan konfirmasi "Yakin ingin membatalkan pesanan ini?". Apabila *Customer* membatalkan konfirmasi, tidak ada perubahan yang terjadi. Ketika *Customer* mengkonfirmasi, sistem memanggil fungsi **getOrderById(id)** untuk memverifikasi status pesanan saat ini. Apabila pesanan sudah melewati status "waiting_payment", sistem menampilkan pesan *error* "Pesanan tidak dapat dibatalkan karena sudah diproses". Ketika pesanan masih berstatus "waiting_payment", sistem memanggil fungsi **cancelOrder(id)** untuk mengubah status pesanan menjadi "cancelled". Sistem kemudian mengembalikan stok material yang sebelumnya dikurangi melalui fungsi **adjustStock(material_id, "in", quantity, order_code)**. Sistem memanggil fungsi **logStatusChange(order_id, "cancelled", user_id, "Dibatalkan oleh customer")** untuk mencatat perubahan status. Terakhir, sistem menampilkan notifikasi "Pesanan berhasil dibatalkan" kepada *Customer*.

---

### 12. Konfirmasi Pesanan Selesai

Terlampir di LAMPIRAN Sequence Diagram – *Konfirmasi Pesanan Selesai*

Proses konfirmasi pesanan selesai dimulai ketika *Customer* menerima notifikasi bahwa pesanannya sudah siap diambil. *Customer* kemudian datang ke toko untuk mengambil pesanan. Setelah mengambil pesanan, *Customer* membuka halaman detail pesanan yang berstatus "ready" dan menekan tombol "Konfirmasi Selesai". Sistem memanggil fungsi **getOrderById(id)** untuk memverifikasi bahwa status pesanan adalah "ready". Apabila pesanan belum berstatus "ready", tombol konfirmasi tidak ditampilkan kepada *Customer*. Ketika status pesanan valid, sistem memanggil fungsi **confirmOrderComplete(id)** untuk mengubah status pesanan menjadi "completed". Sistem memanggil fungsi **logStatusChange(order_id, "completed", user_id, "Dikonfirmasi selesai oleh customer")** untuk mencatat perubahan status ke *order_status_logs*. Sistem menampilkan notifikasi "Terima kasih! Pesanan telah selesai" kepada *Customer*.

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

### 16. Produksi (Mulai dan Selesai Produksi)

Terlampir di LAMPIRAN Sequence Diagram – *Produksi*

Proses produksi terdiri dari dua tahap utama yaitu memulai dan menyelesaikan produksi. Proses dimulai ketika *Staff* membuka pesanan yang berstatus "printing" pada halaman daftar pesanan. *Staff* menekan tombol "Mulai Cetak" untuk memulai proses produksi. Sistem memanggil fungsi **startProduction(order_id, staff_id)** yang mencatat waktu mulai (*start_time*) dan identitas *Staff* yang mengerjakan ke tabel *production_logs*. Sistem menampilkan konfirmasi "Produksi dimulai" kepada *Staff*. Setelah proses pencetakan selesai secara fisik, *Staff* kembali membuka detail pesanan dan menambahkan catatan hasil produksi apabila diperlukan, kemudian menekan tombol "Selesai Cetak". Sistem memanggil fungsi **finishProduction(id, notes)** untuk mencatat waktu selesai (*end_time*) dan catatan produksi ke tabel *production_logs*. Sistem kemudian memanggil fungsi **updateOrderStatus(order_id, "ready", staff_id, "Produksi selesai")** untuk mengubah status pesanan menjadi "ready". Terakhir, sistem mengirimkan notifikasi *real-time* kepada *Customer* melalui *WebSocket* dengan pesan "Pesanan Anda siap diambil!" dan menampilkan konfirmasi keberhasilan kepada *Staff*.

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

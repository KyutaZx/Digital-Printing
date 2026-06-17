### 4.1.1.2 Fungsi-Fungsi Software

Berdasarkan deskripsi yang telah dijabarkan di atas, berikut adalah daftar fungsionalitas mendetail yang dimiliki oleh website OMS Jaya Mandiri:

**Register**  
Fitur ini digunakan oleh calon pelanggan (Customer) untuk membuat akun baru pada website Jaya Mandiri. Pengguna diwajibkan memasukkan data diri dasar seperti nama, alamat email, nomor telepon, dan password. Sistem akan melakukan validasi untuk memastikan email belum terdaftar sebelumnya sebelum menyimpan data tersebut ke dalam database.

**Login**  
Fitur ini digunakan oleh seluruh entitas user (Customer, Staff, dan Owner) untuk melakukan *authentication* dan otorisasi sesi sebelum mengakses sistem. Sistem dibekali dengan *Role-Based Access Control* (RBAC) di mana setelah login berhasil, user akan diarahkan (*redirect*) secara otomatis ke halaman dashboard yang berbeda sesuai dengan hak akses atau perannya masing-masing.

**Melihat Katalog Produk**  
Fitur ini dapat digunakan oleh pelanggan yang telah masuk ke dalam sistem untuk mengeksplorasi etalase digital percetakan. Pelanggan dapat melihat daftar produk cetak beserta detail spesifikasi, pilihan varian, kategori produk, dan harga dasar sebelum memutuskan untuk melakukan pemesanan.

**Manajemen Keranjang Belanja**  
Fitur ini bertindak sebagai tempat penyimpanan pesanan sementara (*cart*) bagi pelanggan. Pelanggan dapat menambahkan beberapa produk sekaligus, melakukan update (perubahan) jumlah kuantitas item yang ingin dicetak, melihat kalkulasi total harga sementara, atau menghapus item dari keranjang sebelum memprosesnya ke tahap *checkout*.

**Membuat Pesanan (Checkout)**  
Fitur ini dapat digunakan oleh pelanggan untuk mengonversi item di keranjang belanja menjadi pesanan resmi (atau menggunakan fitur *buy now* untuk pembelian langsung). Pada tahap ini, pelanggan juga dapat menentukan varian atau opsi spesifikasi penyelesaian (seperti jenis bahan/finishing). Sistem kemudian akan men-*generate* Kode Pesanan (Order Code) yang unik dan menyimpan detail transaksi ke dalam tabel `orders` dan `order_items`.

**Upload File Desain**  
Fitur esensial ini digunakan oleh pelanggan untuk melampirkan file desain cetak per item pesanan. Sistem tidak hanya mengizinkan proses unggah, tetapi juga dilengkapi dengan *Artificial Intelligence* (AI) berbasis Python untuk memvalidasi tingkat ketajaman (*blur detection*) serta memvalidasi kesesuaian format dan ukuran batas maksimal file. Setelah berhasil, status pesanan otomatis berubah menjadi 'Menunggu Review'.

**Upload Bukti Pembayaran**  
Fitur ini digunakan oleh pelanggan untuk memilih metode pembayaran yang disediakan (transfer bank). Pelanggan diwajibkan untuk mengunggah foto atau dokumen bukti transfer. Setelah berkas berhasil dikirim, sistem akan menyimpan data tersebut dan mengubah status pesanan menjadi 'Verifikasi Pembayaran'.

**Melihat Riwayat dan Detail Pesanan**  
Fitur ini memfasilitasi pelanggan untuk memantau status pesanan (*tracking*) mereka secara *real-time*. Pelanggan dapat melihat histori perjalanan pesanan yang terekam secara sistematis, memantau riwayat pesanan yang sudah selesai atau dibatalkan, serta melihat dan mengunduh (*download*) dokumen Invoice tagihan berformat PDF yang di-*generate* secara dinamis oleh sistem Golang API.

**Membatalkan Pesanan**  
Fitur ini dapat digunakan oleh pelanggan untuk melakukan pembatalan transaksi. Sistem memberlakukan aturan bisnis (*business rule*) di mana pembatalan hanya dapat dieksekusi jika status pesanan masih berada pada tahap awal (belum diverifikasi atau belum diproses). Jika dibatalkan, sistem akan memperbarui status dan merekam jejak pembatalan tersebut.

**Mengkonfirmasi Pesanan Selesai**  
Fitur ini digunakan oleh pelanggan sebagai validasi akhir (*end-point*) transaksi. Tombol konfirmasi penyelesaian ini dapat digunakan oleh pengguna untuk menandakan bahwa pesanan telah diterima dengan baik. Setelah ditekan, status pesanan akan resmi ditutup menjadi 'Selesai'.

**Verifikasi Pembayaran**  
Fitur ini merupakan ruang kerja bagi Staff operasional atau Admin untuk meninjau bukti transfer yang diunggah oleh pelanggan. Staff berhak menyetujui (*approve*) yang akan meneruskan pesanan ke antrean produksi/review, atau menolak (*reject*) pembayaran jika bukti buram atau nominal tidak sesuai, agar pelanggan dapat mengunggah ulang bukti yang valid.

**Review Desain**  
Fitur ini memberikan antarmuka bagi Staff (Bagian Desain) untuk memverifikasi apakah resolusi dan format file desain pelanggan sudah layak cetak. Staff dapat menyetujui desain (pesanan otomatis masuk ke antrean cetak) atau meminta revisi. Jika meminta revisi, Staff akan memasukkan catatan perbaikan yang akan tersimpan di tabel `design_reviews` dan mengembalikan status desain kepada pelanggan.

**Manajemen Produksi**  
Fitur operasional kritikal bagi Staff mesin cetak. Melalui panel antrean pesanan, Staff dapat memperbarui status pesanan menjadi "Sedang Diproses/Printing" untuk menandakan bahwa mesin cetak sedang bekerja. Setelah proses cetak fisik rampung, Staff kembali memperbarui status pesanan tersebut agar siap dikirim atau siap diambil oleh pelanggan.

**Manajemen Produk**  
Fitur administratif tingkat atas yang khusus digunakan oleh Owner. Melalui fitur ini, pemilik dapat menambah produk cetak baru ke dalam katalog, mengubah spesifikasi, foto, dan harga dasar, atau menyembunyikan (*soft delete*) produk yang sedang tidak tersedia sehingga tidak tampil di halaman utama pelanggan.

**Manajemen Material**  
Fitur operasional ini digunakan oleh Owner atau Admin untuk mengelola inventaris pabrik. Pemilik dapat melihat daftar persediaan material bahan baku, menambahkan jenis material baru, serta melakukan pembaruan penyesuaian stok (*restock* atau pengurangan manual) yang rekam jejaknya akan dicatat dengan aman pada tabel `material_stock_logs`.

**Manajemen Pengguna**  
Fitur kontrol akses yang digunakan oleh Owner untuk memantau seluruh entitas akun yang terdaftar pada sistem (baik pelanggan maupun staf). Pemilik memiliki wewenang eksklusif untuk mendaftarkan kredensial akun Staff baru dan menonaktifkan (*ban*) akun pengguna yang melanggar ketentuan.

**Laporan Pendapatan dan Log Sistem**  
Fitur manajerial ini dirancang khusus untuk memfasilitasi kebutuhan audit Owner. Pemilik dapat memasukkan parameter rentang tanggal tertentu untuk mengekstrak laporan pendapatan yang diakumulasi dari transaksi berstatus disetujui. Data ini dapat diekspor menjadi dokumen berformat tabular (seperti Excel/CSV). Selain itu, fitur ini difasilitasi dengan Audit Log yang mencatat rekam jejak tindakan seluruh user secara transparan (siapa melakukan apa dan kapan) melalui tabel `audit_logs`.

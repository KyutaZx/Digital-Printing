### 4.4 Prosedur Penggunaan Aplikasi

Bagian ini menguraikan langkah-langkah atau prosedur operasional standar bagi setiap aktor di dalam sistem OMS Jaya Mandiri. Penggunaan aplikasi dibagi berdasarkan hak akses (*role-based*) yang dimiliki oleh masing-masing entitas.

#### 4.4.1 Prosedur Penggunaan Aplikasi Sebagai Guest
*Guest* adalah pengguna publik atau calon pelanggan yang belum masuk (*login*) ke dalam sistem. Prosedur penggunaannya adalah sebagai berikut:
1. **Akses Sistem:** Pengguna membuka URL website aplikasi Jaya Mandiri melalui *web browser*.
2. **Eksplorasi Katalog:** Pengguna dapat menelusuri halaman Beranda dan menu Katalog untuk melihat daftar produk percetakan yang ditawarkan.
3. **Melihat Detail Produk:** Pengguna dapat menekan salah satu produk untuk membaca informasi detail, mengecek spesifikasi/varian yang tersedia, serta melihat estimasi harga dasar.
4. **Mendaftar (Registrasi):** Jika *Guest* berminat untuk melakukan pemesanan (mencoba memasukkan barang ke keranjang belanja), sistem akan mengarahkannya ke halaman *Login*. Dari sini, *Guest* diharuskan mengisi *form* Registrasi (Nama, Email, No. Telepon, Password) untuk mengubah statusnya menjadi *Customer* (User).

#### 4.4.2 Prosedur Penggunaan Aplikasi Sebagai Admin (Staff)
*Admin/Staff* adalah pekerja operasional percetakan (seperti *pre-press* atau operator mesin). Prosedur penggunaannya adalah sebagai berikut:
1. **Autentikasi:** Staff melakukan *login* dengan kredensial yang telah didaftarkan oleh Owner. Setelah berhasil, Staff akan diarahkan ke *Dashboard* Operasional.
2. **Memantau Daftar Pesanan:** Staff mengakses menu Pesanan untuk melihat antrean transaksi baru yang masuk dari pelanggan.
3. **Verifikasi Pembayaran:** Staff membuka detail pesanan yang berstatus 'Menunggu Verifikasi', mengecek foto bukti transfer yang diunggah pelanggan, lalu memberikan aksi **Setuju** (*Approve*) atau **Tolak** (*Reject*).
4. **Review Desain:** Untuk pesanan yang pembayarannya telah sah, Staff mengunduh atau meninjau file desain. Staff kemudian memberikan keputusan apakah desain "Disetujui/Layak Cetak" atau "Perlu Revisi" (disertai dengan catatan perbaikan untuk pelanggan).
5. **Manajemen Produksi:** Staff memperbarui status pesanan menjadi "Sedang Diproses/Printing" saat mesin cetak mulai memproduksi barang, dan mengubahnya menjadi "Siap Diambil/Selesai" ketika proses cetak telah rampung secara fisik.

#### 4.4.3 Prosedur Penggunaan Aplikasi Sebagai User (Customer)
*User* (Customer) adalah pelanggan sah yang telah terdaftar di dalam sistem. Prosedur penggunaannya adalah sebagai berikut:
1. **Login:** Pelanggan masuk menggunakan email dan *password* yang telah terdaftar.
2. **Pemesanan Produk:** Pelanggan memilih produk dari katalog, menentukan varian spesifikasi (contoh: jenis kertas/finishing), mengatur jumlah (*quantity*), lalu menekan tombol Tambah ke Keranjang.
3. **Checkout Transaksi:** Pelanggan mengonversi isi keranjang belanja menjadi pesanan resmi melalui proses *checkout*. Sistem akan merilis Kode Pesanan (Order ID).
4. **Upload File & Otomatisasi AI:** Pelanggan mengunggah file desain untuk pesanan tersebut. Sistem AI secara instan akan memvalidasi ketajaman gambar (*blur detection*). Jika lolos, status diteruskan ke Staff. Jika file dinilai bermasalah/buram, sistem akan langsung memberi peringatan *error*.
5. **Pembayaran:** Pelanggan mengunggah foto bukti transfer pembayaran ke sistem.
6. **Revisi & Pelacakan:** Pelanggan memantau status pesanan (Order Tracking). Jika Staff meminta revisi desain, pelanggan dapat membaca catatan revisi dan mengunggah ulang desain yang telah diperbaiki (maksimal 3 kali revisi).
7. **Penyelesaian:** Setelah barang selesai diproduksi dan diterima, pelanggan menekan tombol Konfirmasi Selesai, lalu dapat mengunduh dokumen Invoice tagihan resmi berformat PDF.

#### 4.4.4 Prosedur Penggunaan Aplikasi Sebagai Owner
*Owner* adalah pemilik bisnis yang memiliki hak akses tertinggi (*Superadmin*) untuk mengelola inventaris, produk, dan memantau finansial. Prosedur penggunaannya adalah sebagai berikut:
1. **Login Superadmin:** Owner masuk menggunakan akun utama (kredensial *owner*).
2. **Manajemen Pengguna:** Melalui menu *User Management*, Owner mendaftarkan akun baru untuk karyawan (Staff) serta berhak memblokir (*banned*) atau membuka blokir akun pelanggan jika terjadi pelanggaran aturan.
3. **Manajemen Produk:** Owner memperbarui katalog digital dengan menambah produk cetak baru, mengedit informasi produk (deskripsi/gambar/harga), atau menyembunyikan (*soft delete*) produk yang sedang tidak tersedia.
4. **Manajemen Material:** Owner memantau sisa stok bahan baku percetakan. Saat barang datang dari pemasok, Owner dapat melakukan *restock* atau penyesuaian (*adjustment*) stok, di mana aktivitas ini akan dicatat rapi pada tabel log material.
5. **Laporan Pendapatan & Audit Log:** Untuk keperluan manajerial, Owner memasukkan filter tanggal (tanggal mulai s/d tanggal akhir) untuk mengekstrak laporan pendapatan. Selain itu, Owner dapat memantau *Audit Log* untuk mengawasi seluruh aktivitas dan tindakan transparan yang dilakukan oleh Staff maupun Pelanggan di dalam sistem.

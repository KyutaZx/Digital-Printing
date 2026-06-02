# Use Case Description
# Jaya Mandiri — Digital Printing Management System

---

**Dokumen** : Use Case Description  
**Versi** : 1.0.0  
**Tanggal** : 02 Juni 2026  
**Referensi** : Use Case Diagram Jaya Mandiri Digital Printing

---

## Daftar Use Case

| ID | Use Case | Aktor |
|----|----------|-------|
| UC-01 | Login | Customer, Staff, Owner |
| UC-02 | Logout | Customer, Staff, Owner |
| UC-03 | Registrasi | Customer |
| UC-04 | Update Profil | Customer |
| UC-05 | Melihat Profil | Customer |
| UC-06 | Melihat Semua Produk | Customer |
| UC-07 | Menambah Item ke Keranjang | Customer |
| UC-08 | Melihat Keranjang | Customer |
| UC-09 | Mengupdate Jumlah Item | Customer |
| UC-10 | Menghapus Item dari Keranjang | Customer |
| UC-11 | Mencekout Pesanan | Customer |
| UC-12 | Memilih Finishing Produk | Customer |
| UC-13 | Melihat Detail Pesanan | Customer |
| UC-14 | Melihat History Pesanan | Customer |
| UC-15 | Membatalkan Pesanan | Customer |
| UC-16 | Mengkonfirmasi Pesanan Selesai | Customer |
| UC-17 | Mengupload File Desain | Customer |
| UC-18 | Mengupload Bukti Pembayaran | Customer |
| UC-19 | Melihat Invoice | Customer *(extend UC-13)* |
| UC-20 | Mengupload File Revisi Desain | Customer *(extend UC-17)* |
| UC-21 | Menampilkan Daftar Pesanan | Staff |
| UC-22 | Verifikasi Pembayaran | Staff |
| UC-23 | Approve Pembayaran | Staff *(extend UC-22)* |
| UC-24 | Reject Pembayaran | Staff *(extend UC-22)* |
| UC-25 | Review Desain | Staff |
| UC-26 | Approve Desain | Staff *(extend UC-25)* |
| UC-27 | Revisi Desain | Staff *(extend UC-25)* |
| UC-28 | Memulai Proses Produksi | Staff |
| UC-29 | Menyelesaikan Produksi | Staff |
| UC-30 | Mendaftarkan Akun Staff | Owner |
| UC-31 | Menampilkan Daftar Pengguna | Owner |
| UC-32 | Ban / Unban Akun User | Owner |
| UC-33 | Menambahkan Produk Baru | Owner |
| UC-34 | Update Produk | Owner |
| UC-35 | Hapus Produk | Owner |
| UC-36 | Menampilkan Daftar Material | Owner |
| UC-37 | Menambahkan Material Baru | Owner |
| UC-38 | Menyesuaikan Stok Material | Owner |
| UC-39 | Menampilkan Laporan Pendapatan | Owner |
| UC-40 | Menampilkan Statistik Produk Terlaris | Owner |
| UC-41 | Menampilkan Audit Log Sistem | Owner |
| UC-42 | Menampilkan Login Log | Owner |
| UC-43 | Menampilkan Production Log | Owner |

---

## 1) Login

**Tabel UC-01 Use Case Description Login**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Login |
| **ID** | UC-01 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer, Staff, Owner — Dapat masuk ke dalam sistem dan mengakses fitur sesuai role masing-masing |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer, Staff, dan Owner dapat masuk ke dalam sistem menggunakan email dan password yang telah terdaftar, kemudian mendapatkan akses fitur sesuai role-nya. |
| **Trigger** | Customer, Staff, atau Owner membuka aplikasi dan memasukkan email serta password lalu menekan tombol "Login" |
| **Type** | External |
| **Relationships — Association** | Customer, Staff, Owner |
| **Relationships — Include** | Validasi kredensial, Generate JWT Token |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). User membuka halaman login (2). User memasukkan email (3). User memasukkan password (4). User menekan tombol "Login" (5). Sistem memvalidasi email dan password (6). Sistem menghasilkan JWT token (7). Sistem mencatat aktivitas login ke login_logs (8). Sistem mengarahkan user ke halaman utama sesuai role |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). User memasukkan email atau password yang salah → Sistem menampilkan pesan "Email atau password salah" (2'). Akun user tidak aktif (is_active = false) → Sistem menampilkan pesan "Akun Anda telah dinonaktifkan" (3'). User gagal login berkali-kali → Sistem mencatat ke login_attempts |

---

## 2) Logout

**Tabel UC-02 Use Case Description Logout**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Logout |
| **ID** | UC-02 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer, Staff, Owner — Dapat keluar dari sistem dengan aman |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer, Staff, dan Owner dapat keluar dari sistem. Sistem akan mencatat aktivitas logout dan menghapus sesi aktif pengguna. |
| **Trigger** | User menekan tombol "Logout" pada aplikasi |
| **Type** | External |
| **Relationships — Association** | Customer, Staff, Owner |
| **Relationships — Include** | — |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). User menekan tombol "Logout" (2). Sistem mencatat aktivitas logout ke login_logs (3). Sistem menghapus JWT token di sisi client (4). Sistem mengarahkan user kembali ke halaman login |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Koneksi internet terputus saat logout → Sistem tetap menghapus token di sisi client dan mengarahkan ke halaman login |

---

## 3) Registrasi

**Tabel UC-03 Use Case Description Registrasi**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Registrasi |
| **ID** | UC-03 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mendaftarkan diri ke dalam sistem untuk menggunakan layanan percetakan digital |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer baru dapat mendaftarkan diri ke dalam sistem dengan mengisi data diri berupa nama, email, password, dan nomor telepon. Setelah berhasil, Customer langsung dapat menggunakan sistem. |
| **Trigger** | Customer menekan tombol "Daftar" atau "Register" pada halaman awal aplikasi |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Validasi data, Generate akun baru |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman registrasi (2). Customer memasukkan nama lengkap (3). Customer memasukkan email (4). Customer memasukkan password (5). Customer memasukkan nomor telepon (opsional) (6). Customer menekan tombol "Daftar" (7). Sistem memvalidasi data yang dimasukkan (8). Sistem menyimpan data akun baru dengan role "customer" (9). Sistem mencatat aktivitas register ke audit_logs (10). Sistem mengarahkan Customer ke halaman login |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Email yang dimasukkan sudah terdaftar → Sistem menampilkan pesan "Email sudah digunakan" (2'). Password kurang dari 8 karakter → Sistem menampilkan pesan validasi (3'). Format email tidak valid → Sistem menampilkan pesan "Format email tidak valid" |

---

## 4) Update Profil

**Tabel UC-04 Use Case Description Update Profil**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Update Profil |
| **ID** | UC-04 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat memperbarui data diri agar informasi akun selalu akurat |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat memperbarui informasi profil dirinya seperti nama, nomor telepon, dan password. |
| **Trigger** | Customer menekan tombol "Edit Profil" pada halaman profil |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Validasi data, Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman profil (2). Customer menekan tombol "Edit Profil" (3). Customer mengubah data yang ingin diperbarui (nama / telepon / password) (4). Customer menekan tombol "Simpan" (5). Sistem memvalidasi data baru (6). Sistem menyimpan perubahan ke database (7). Sistem menampilkan pesan "Profil berhasil diperbarui" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Password baru kurang dari 8 karakter → Sistem menampilkan pesan validasi (2'). Customer tidak mengubah data apapun lalu menekan simpan → Sistem tidak melakukan perubahan |

---

## 5) Melihat Profil

**Tabel UC-05 Use Case Description Melihat Profil**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Melihat Profil |
| **ID** | UC-05 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat melihat informasi akun pribadi |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat melihat informasi profil dirinya seperti nama, email, dan nomor telepon yang tersimpan di sistem. |
| **Trigger** | Customer menekan menu "Profil" pada aplikasi |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer menekan menu "Profil" (2). Sistem mengambil data profil dari database berdasarkan token JWT (3). Sistem menampilkan informasi nama, email, nomor telepon, dan tanggal bergabung |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Token JWT expired → Sistem mengarahkan Customer ke halaman login |

---

## 6) Melihat Semua Produk

**Tabel UC-06 Use Case Description Melihat Semua Produk**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Melihat Semua Produk |
| **ID** | UC-06 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat melihat seluruh produk percetakan yang tersedia beserta harga dan variannya |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat melihat katalog produk yang tersedia di sistem, termasuk nama produk, deskripsi, harga dasar, estimasi waktu selesai, dan varian yang tersedia. |
| **Trigger** | Customer membuka halaman katalog atau halaman utama aplikasi |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | — |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman katalog produk (2). Sistem mengambil daftar produk aktif dari database (3). Sistem menampilkan daftar produk beserta nama, harga dasar, dan estimasi selesai (4). Customer dapat menekan produk untuk melihat detail dan varian |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Tidak ada produk aktif di sistem → Sistem menampilkan pesan "Belum ada produk tersedia" |

---

## 7) Menambah Item ke Keranjang

**Tabel UC-07 Use Case Description Menambah Item ke Keranjang**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menambah Item ke Keranjang |
| **ID** | UC-07 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat menyimpan produk pilihan ke keranjang sebelum melakukan checkout |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat menambahkan produk beserta varian dan kuantitas yang diinginkan ke dalam keranjang belanja. Customer juga dapat menambahkan catatan khusus per item. |
| **Trigger** | Customer menekan tombol "Tambah ke Keranjang" pada halaman detail produk |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, Validasi produk aktif |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer memilih produk dari katalog (2). Customer memilih varian produk (3). Customer memasukkan jumlah (kuantitas) yang diinginkan (4). Customer menambahkan catatan khusus (opsional, misal: ukuran custom) (5). Customer menekan tombol "Tambah ke Keranjang" (6). Sistem memvalidasi produk dan varian masih aktif (7). Sistem menyimpan item ke keranjang milik Customer (8). Sistem menampilkan notifikasi "Produk berhasil ditambahkan ke keranjang" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Produk atau varian tidak aktif → Sistem menampilkan pesan "Produk tidak tersedia" (2'). Kuantitas yang dimasukkan kurang dari 1 → Sistem menampilkan pesan validasi (3'). Customer belum login → Sistem mengarahkan ke halaman login |

---

## 8) Melihat Keranjang

**Tabel UC-08 Use Case Description Melihat Keranjang**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Melihat Keranjang |
| **ID** | UC-08 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat melihat seluruh item yang telah ditambahkan ke keranjang beserta total harga |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat melihat daftar item yang ada di keranjang belanja, termasuk nama produk, varian, jumlah, harga satuan, catatan, dan total keseluruhan harga. |
| **Trigger** | Customer menekan ikon atau menu "Keranjang" pada aplikasi |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer menekan ikon keranjang (2). Sistem mengambil data keranjang milik Customer dari database (3). Sistem menghitung total harga dari semua item (total = Σ harga × kuantitas) (4). Sistem menampilkan daftar item beserta total harga |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Keranjang kosong → Sistem menampilkan pesan "Keranjang Anda masih kosong" |

---

## 9) Mengupdate Jumlah Item

**Tabel UC-09 Use Case Description Mengupdate Jumlah Item**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mengupdate Jumlah Item |
| **ID** | UC-09 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mengubah jumlah item di keranjang sesuai kebutuhan |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat mengubah kuantitas atau catatan dari item yang sudah ada di keranjang belanja. |
| **Trigger** | Customer mengubah angka kuantitas item pada halaman keranjang |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman keranjang (2). Customer mengubah jumlah (kuantitas) pada item yang diinginkan (3). Customer menekan tombol "Update" atau konfirmasi perubahan (4). Sistem memperbarui data item di database (5). Sistem menghitung ulang total harga (6). Sistem menampilkan keranjang yang telah diperbarui |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Kuantitas diubah menjadi 0 atau negatif → Sistem menampilkan pesan validasi "Jumlah minimal 1" |

---

## 10) Menghapus Item dari Keranjang

**Tabel UC-10 Use Case Description Menghapus Item dari Keranjang**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menghapus Item dari Keranjang |
| **ID** | UC-10 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat menghapus item yang tidak diinginkan dari keranjang |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat menghapus satu atau lebih item dari keranjang belanja. |
| **Trigger** | Customer menekan tombol "Hapus" atau ikon tempat sampah pada item di keranjang |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman keranjang (2). Customer menekan tombol "Hapus" pada item yang ingin dihapus (3). Sistem menampilkan konfirmasi "Yakin ingin menghapus item ini?" (4). Customer mengkonfirmasi penghapusan (5). Sistem menghapus item dari keranjang (6). Sistem menghitung ulang total harga (7). Sistem menampilkan keranjang yang telah diperbarui |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Customer membatalkan konfirmasi penghapusan → Item tidak dihapus, keranjang tetap sama |

---

## 11) Mencekout Pesanan

**Tabel UC-11 Use Case Description Mencekout Pesanan**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mencekout Pesanan |
| **ID** | UC-11 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mengubah isi keranjang menjadi pesanan resmi yang tercatat di sistem |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat melakukan checkout dari keranjang belanja untuk membuat pesanan resmi. Sistem akan mengurangi stok material, membuat order dengan kode unik, dan mengosongkan keranjang. |
| **Trigger** | Customer menekan tombol "Checkout" pada halaman keranjang |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, Validasi stok material, Generate order_code |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer menekan tombol "Checkout" pada halaman keranjang (2). Sistem memvalidasi semua item masih aktif dan tersedia (3). Sistem menghitung total harga keseluruhan (4). Sistem mengurangi stok material sesuai varian yang dipesan (5). Sistem membuat pesanan baru dengan status "waiting_payment" (6). Sistem menghasilkan kode pesanan unik (format: ORD-{timestamp}) (7). Sistem mengosongkan keranjang (8). Sistem menampilkan halaman detail pesanan baru dengan instruksi selanjutnya |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Keranjang kosong saat checkout → Sistem menampilkan pesan "Keranjang masih kosong" (2'). Stok material tidak mencukupi → Sistem menampilkan pesan "Stok tidak mencukupi untuk produk tertentu" |

---

## 12) Memilih Finishing Produk

**Tabel UC-12 Use Case Description Memilih Finishing Produk**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Memilih Finishing Produk |
| **ID** | UC-12 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat memilih jenis finishing atau varian produk yang diinginkan sebelum checkout |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat memilih spesifikasi finishing atau varian produk (misalnya: Glossy Premium, Matte 150gsm) ketika menambahkan produk ke keranjang. |
| **Trigger** | Customer memilih salah satu varian dari daftar varian produk yang tersedia |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, Lihat detail produk |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman detail produk (2). Sistem menampilkan daftar varian yang tersedia (SKU, nama varian, harga) (3). Customer memilih varian yang diinginkan (misalnya: "Glossy Premium" atau "Matte 150gsm") (4). Sistem menampilkan harga yang diperbarui sesuai varian pilihan (5). Customer melanjutkan untuk menambahkan ke keranjang |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Varian yang dipilih tidak aktif atau stok habis → Sistem menonaktifkan pilihan dan menampilkan keterangan "Tidak tersedia" |

---

## 13) Melihat Detail Pesanan

**Tabel UC-13 Use Case Description Melihat Detail Pesanan**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Melihat Detail Pesanan |
| **ID** | UC-13 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat memantau status dan detail lengkap pesanan yang sedang berjalan |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat melihat detail lengkap dari sebuah pesanan, meliputi item pesanan, status desain per item, status pembayaran, dan riwayat perubahan status. |
| **Trigger** | Customer menekan salah satu pesanan dari daftar history pesanan |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | Melihat Invoice (UC-19) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman history pesanan (2). Customer menekan pesanan yang ingin dilihat detailnya (3). Sistem mengambil data detail pesanan dari database (4). Sistem menampilkan informasi: kode pesanan, total harga, status pesanan, item pesanan, status desain per item, status pembayaran, dan estimasi selesai (5). Customer dapat menekan tombol "Lihat Invoice" untuk mengakses invoice (extend ke UC-19) |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Pesanan tidak ditemukan → Sistem menampilkan pesan "Pesanan tidak ditemukan" |

---

## 14) Melihat History Pesanan

**Tabel UC-14 Use Case Description Melihat History Pesanan**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Melihat History Pesanan |
| **ID** | UC-14 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat melihat seluruh riwayat pesanan yang pernah dibuat |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat melihat daftar semua pesanan yang pernah dibuat, beserta status terkini setiap pesanan. |
| **Trigger** | Customer menekan menu "Pesanan" atau "History Pesanan" pada aplikasi |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer menekan menu "Pesanan" (2). Sistem mengambil semua data pesanan milik Customer (3). Sistem menampilkan daftar pesanan diurutkan dari terbaru, beserta kode pesanan, tanggal, total harga, dan status terkini |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Customer belum pernah membuat pesanan → Sistem menampilkan pesan "Belum ada pesanan" |

---

## 15) Membatalkan Pesanan

**Tabel UC-15 Use Case Description Membatalkan Pesanan**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Membatalkan Pesanan |
| **ID** | UC-15 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat membatalkan pesanan yang belum diproses apabila tidak jadi memesan |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer dapat membatalkan pesanan yang masih berstatus "waiting_payment". Setelah dibatalkan, stok material akan dikembalikan dan pesanan tidak dapat diproses kembali. |
| **Trigger** | Customer menekan tombol "Batalkan Pesanan" pada halaman detail pesanan |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, Kembalikan stok material |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman detail pesanan (2). Customer menekan tombol "Batalkan Pesanan" (3). Sistem menampilkan konfirmasi "Yakin ingin membatalkan pesanan ini?" (4). Customer mengkonfirmasi pembatalan (5). Sistem memvalidasi status pesanan masih "waiting_payment" (6). Sistem mengubah status pesanan menjadi "cancelled" (7). Sistem mengembalikan stok material yang telah dikurangi (8). Sistem menampilkan notifikasi "Pesanan berhasil dibatalkan" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Pesanan sudah melewati status "waiting_payment" → Sistem menampilkan pesan "Pesanan tidak dapat dibatalkan karena sudah diproses" (2'). Customer membatalkan konfirmasi → Pesanan tidak dibatalkan |

---

## 16) Mengkonfirmasi Pesanan Selesai

**Tabel UC-16 Use Case Description Mengkonfirmasi Pesanan Selesai**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mengkonfirmasi Pesanan Selesai |
| **ID** | UC-16 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mengkonfirmasi bahwa pesanan telah diterima/diambil sehingga pesanan dinyatakan selesai |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer mengkonfirmasi bahwa pesanan yang berstatus "ready" telah berhasil diambil di toko. Setelah dikonfirmasi, status pesanan berubah menjadi "completed". |
| **Trigger** | Customer menekan tombol "Konfirmasi Selesai" pada halaman detail pesanan yang berstatus "ready" |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer menerima notifikasi bahwa pesanan siap diambil (2). Customer datang ke toko dan mengambil pesanan (3). Customer membuka halaman detail pesanan (4). Customer menekan tombol "Konfirmasi Selesai" (5). Sistem memvalidasi status pesanan adalah "ready" (6). Sistem mengubah status pesanan menjadi "completed" (7). Sistem menampilkan notifikasi "Terima kasih! Pesanan telah selesai" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Pesanan belum berstatus "ready" → Tombol konfirmasi tidak aktif / tidak ditampilkan |

---

## 17) Mengupload File Desain

**Tabel UC-17 Use Case Description Mengupload File Desain**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mengupload File Desain |
| **ID** | UC-17 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mengirimkan file desain yang akan dicetak untuk setiap item pesanan |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer mengupload file desain (JPG/PNG) untuk setiap item dalam pesanan. File yang diupload akan otomatis dicek kualitasnya oleh sistem AI untuk mendeteksi apakah gambar blur atau tajam. |
| **Trigger** | Customer menekan tombol "Upload Desain" pada item pesanan di halaman detail pesanan |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, AI Blur Detection, Validasi format file |
| **Relationships — Extend** | Mengupload File Revisi Desain (UC-20) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman detail pesanan (2). Customer menekan tombol "Upload Desain" pada item yang diinginkan (3). Customer memilih file gambar (JPG/PNG, maks. 10 MB) dari perangkat (4). Sistem memvalidasi format dan ukuran file (5). Sistem mengirimkan file ke AI Service untuk pengecekan kualitas (blur detection) (6). Sistem menyimpan file desain ke server (7). Sistem menampilkan hasil pengecekan AI ("Tajam/Sharp" atau "Blur") (8). Sistem mencatat versi desain baru di database |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Format file bukan JPG/PNG → Sistem menampilkan pesan "Format file tidak didukung. Gunakan JPG atau PNG" (2'). Ukuran file melebihi 10 MB → Sistem menampilkan pesan "Ukuran file terlalu besar. Maksimal 10 MB" (3'). AI Service tidak tersedia → Sistem tetap menyimpan file dan menganggap desain "sharp" (bypass mode) (4'). Hasil AI menunjukkan gambar blur → Sistem menampilkan peringatan "Desain terdeteksi blur. Disarankan upload ulang dengan kualitas lebih baik" |

---

## 18) Mengupload Bukti Pembayaran

**Tabel UC-18 Use Case Description Mengupload Bukti Pembayaran**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mengupload Bukti Pembayaran |
| **ID** | UC-18 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mengirimkan bukti transfer/QRIS sebagai konfirmasi pembayaran pesanan |
| **Brief Description** | Use case ini menjelaskan bagaimana Customer mengupload bukti transfer atau QRIS untuk pesanan yang sudah ada. Seluruh item pesanan wajib sudah memiliki desain sebelum pembayaran dapat dilakukan. |
| **Trigger** | Customer menekan tombol "Upload Bukti Bayar" pada halaman detail pesanan |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, Validasi desain semua item sudah diupload |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer memastikan semua desain sudah diupload (2). Customer menekan tombol "Upload Bukti Bayar" (3). Customer memilih metode pembayaran (BCA Transfer / Mandiri Transfer / QRIS) (4). Customer memasukkan jumlah yang ditransfer (5). Customer memilih file bukti transfer (JPG/PNG/PDF) (6). Customer menekan tombol "Kirim" (7). Sistem memvalidasi semua item sudah memiliki desain (8). Sistem menyimpan transaksi pembayaran (9). Sistem mengubah status pesanan menjadi "payment_verification" (10). Sistem menampilkan pesan "Bukti pembayaran berhasil dikirim. Menunggu verifikasi" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Belum semua item memiliki desain → Sistem menampilkan pesan "Upload desain untuk semua item terlebih dahulu" (2'). Format file tidak valid → Sistem menampilkan pesan validasi format file |

---

## 19) Melihat Invoice

**Tabel UC-19 Use Case Description Melihat Invoice**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Melihat Invoice |
| **ID** | UC-19 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat melihat dokumen invoice resmi dari pesanan yang telah dilakukan |
| **Brief Description** | Use case ini adalah perluasan (extend) dari UC-13 Melihat Detail Pesanan. Customer dapat melihat atau mengunduh invoice resmi yang berisi rincian item, harga, metode pembayaran, dan total tagihan. |
| **Trigger** | Customer menekan tombol "Lihat Invoice" pada halaman detail pesanan |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT |
| **Relationships — Extend** | Dipanggil dari UC-13 (Melihat Detail Pesanan) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer membuka halaman detail pesanan (UC-13) (2). Customer menekan tombol "Lihat Invoice" (3). Sistem mengambil data lengkap pesanan termasuk semua item dan transaksi pembayaran (4). Sistem menghasilkan tampilan invoice dengan informasi: kode pesanan, tanggal, nama customer, daftar item & harga, total, dan status pembayaran (5). Customer dapat melihat invoice di layar |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Data pesanan tidak lengkap → Sistem menampilkan pesan "Invoice belum tersedia" |

---

## 20) Mengupload File Revisi Desain

**Tabel UC-20 Use Case Description Mengupload File Revisi Desain**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mengupload File Revisi Desain |
| **ID** | UC-20 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Customer — Dapat mengupload ulang desain yang telah diminta revisi oleh staff |
| **Brief Description** | Use case ini adalah perluasan (extend) dari UC-17. Ketika staff meminta revisi desain, Customer dapat mengupload versi baru file desain. Setiap upload baru menambah nomor versi. Maksimal 3 versi per item. |
| **Trigger** | Customer menerima notifikasi revisi desain dan menekan tombol "Upload Revisi" pada item yang diminta revisi |
| **Type** | External |
| **Relationships — Association** | Customer |
| **Relationships — Include** | Autentikasi JWT, AI Blur Detection, Validasi batas versi |
| **Relationships — Extend** | Dipanggil dari UC-17 (Mengupload File Desain) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Customer menerima notifikasi "Desain perlu direvisi" beserta catatan dari staff (2). Customer membuka halaman detail pesanan (3). Customer melihat catatan revisi dari staff (4). Customer menyiapkan file desain yang sudah diperbaiki (5). Customer menekan tombol "Upload Revisi" pada item terkait (6). Proses selanjutnya sama dengan UC-17 langkah 3–8 (7). Nomor versi desain otomatis bertambah (version++) |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Versi desain sudah mencapai batas maksimal (3 versi) → Sistem menampilkan pesan "Batas maksimal revisi desain telah tercapai" |

---

## 21) Menampilkan Daftar Pesanan (Staff)

**Tabel UC-21 Use Case Description Menampilkan Daftar Pesanan**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Daftar Pesanan |
| **ID** | UC-21 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat melihat seluruh pesanan dari semua customer untuk diproses sesuai statusnya |
| **Brief Description** | Use case ini menjelaskan bagaimana Staff dapat melihat daftar semua pesanan yang masuk dari seluruh customer, termasuk pesanan yang perlu diverifikasi pembayaran, direview desain, atau diproduksi. |
| **Trigger** | Staff membuka halaman manajemen pesanan pada aplikasi |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Staff |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff membuka halaman "Daftar Pesanan" (2). Sistem mengambil semua data pesanan dari database (3). Sistem menampilkan daftar pesanan diurutkan berdasarkan tanggal, beserta kode pesanan, nama customer, total harga, dan status terkini (4). Staff dapat memfilter pesanan berdasarkan status |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Tidak ada pesanan → Sistem menampilkan pesan "Belum ada pesanan masuk" |

---

## 22) Verifikasi Pembayaran

**Tabel UC-22 Use Case Description Verifikasi Pembayaran**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Verifikasi Pembayaran |
| **ID** | UC-22 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat memeriksa bukti pembayaran yang dikirimkan customer dan memutuskan untuk menyetujui atau menolak |
| **Brief Description** | Use case ini menjelaskan bagaimana Staff memeriksa bukti pembayaran dari customer untuk pesanan yang berstatus "payment_verification". Staff dapat melihat detail transaksi dan gambar bukti bayar sebelum mengambil keputusan. |
| **Trigger** | Staff membuka pesanan yang berstatus "payment_verification" dan menekan tombol "Verifikasi Pembayaran" |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Staff |
| **Relationships — Extend** | Approve Pembayaran (UC-23), Reject Pembayaran (UC-24) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff membuka halaman daftar pesanan (2). Staff memilih pesanan berstatus "payment_verification" (3). Sistem menampilkan detail pembayaran: metode, jumlah, dan gambar bukti transfer (4). Staff memeriksa kesesuaian bukti bayar (5). Staff memilih untuk Approve (UC-23) atau Reject (UC-24) |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Gambar bukti bayar tidak dapat dibuka → Sistem menampilkan pesan error "File tidak dapat dimuat" |

---

## 23) Approve Pembayaran

**Tabel UC-23 Use Case Description Approve Pembayaran**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Approve Pembayaran |
| **ID** | UC-23 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat menyetujui pembayaran customer yang telah sesuai sehingga pesanan dapat berlanjut ke tahap review desain |
| **Brief Description** | Use case ini adalah perluasan (extend) dari UC-22. Ketika Staff menyetujui pembayaran, status transaksi berubah menjadi "approved" dan status pesanan otomatis bergerak ke "design_review". |
| **Trigger** | Staff menekan tombol "Setujui Pembayaran" setelah memeriksa bukti bayar |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Update status order |
| **Relationships — Extend** | Dipanggil dari UC-22 (Verifikasi Pembayaran) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff menekan tombol "Setujui Pembayaran" (2). Sistem mengubah status transaksi pembayaran menjadi "approved" (3). Sistem mengubah status pesanan menjadi "design_review" (4). Sistem mencatat perubahan status di order_status_logs (5). Sistem mengirim notifikasi WebSocket ke Customer: "Pembayaran disetujui. Desain Anda sedang direview" (6). Sistem menampilkan pesan sukses ke Staff |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Transaksi pembayaran tidak valid atau sudah diproses → Sistem menampilkan pesan error |

---

## 24) Reject Pembayaran

**Tabel UC-24 Use Case Description Reject Pembayaran**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Reject Pembayaran |
| **ID** | UC-24 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat menolak pembayaran yang tidak valid sehingga customer dapat mengupload ulang bukti bayar |
| **Brief Description** | Use case ini adalah perluasan (extend) dari UC-22. Ketika bukti pembayaran tidak valid atau tidak sesuai, Staff dapat menolak pembayaran. Status pesanan tetap "payment_verification" dan customer diarahkan untuk upload ulang. |
| **Trigger** | Staff menekan tombol "Tolak Pembayaran" setelah memeriksa bukti bayar yang tidak sesuai |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Update status transaksi |
| **Relationships — Extend** | Dipanggil dari UC-22 (Verifikasi Pembayaran) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff menekan tombol "Tolak Pembayaran" (2). Sistem mengubah status transaksi pembayaran menjadi "rejected" (3). Pesanan tetap berstatus "payment_verification" (4). Sistem mengirim notifikasi WebSocket ke Customer: "Pembayaran ditolak. Silakan upload ulang bukti pembayaran" (5). Sistem menampilkan pesan sukses ke Staff |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Penolakan gagal karena status sudah berubah → Sistem menampilkan pesan error |

---

## 25) Review Desain

**Tabel UC-25 Use Case Description Review Desain**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Review Desain |
| **ID** | UC-25 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat memeriksa kualitas dan kesesuaian file desain dari customer sebelum masuk ke proses produksi |
| **Brief Description** | Use case ini menjelaskan bagaimana Staff memeriksa desain yang diupload customer untuk setiap item pesanan. Staff dapat melihat file desain, hasil pengecekan AI (skor blur/sharp), dan memutuskan untuk menyetujui atau meminta revisi. |
| **Trigger** | Staff membuka pesanan berstatus "design_review" dan memeriksa desain per item |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Staff |
| **Relationships — Extend** | Approve Desain (UC-26), Revisi Desain (UC-27) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff membuka pesanan berstatus "design_review" (2). Sistem menampilkan daftar item beserta file desain terbaru dan hasil pengecekan AI (skor confidence, status sharp/blur) (3). Staff memeriksa setiap desain secara visual (4). Staff memilih untuk Approve (UC-26) atau Minta Revisi (UC-27) per item |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). File desain tidak dapat dibuka atau rusak → Sistem menampilkan pesan "File tidak dapat dimuat, minta customer upload ulang" |

---

## 26) Approve Desain

**Tabel UC-26 Use Case Description Approve Desain**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Approve Desain |
| **ID** | UC-26 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat menyetujui desain yang sudah memenuhi standar kualitas untuk dicetak |
| **Brief Description** | Use case ini adalah perluasan (extend) dari UC-25. Ketika Staff menyetujui desain satu item, status review item tersebut berubah menjadi "approved". Jika semua item dalam satu pesanan sudah approved, sistem otomatis menggerakkan pesanan ke status "printing". |
| **Trigger** | Staff menekan tombol "Approve Desain" pada item yang sudah diperiksa |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Cek status semua item |
| **Relationships — Extend** | Dipanggil dari UC-25 (Review Desain) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff menekan tombol "Approve" pada desain item (2). Sistem mengubah status review desain item menjadi "approved" (3). Sistem memeriksa apakah semua item dalam pesanan sudah diapprove (4). Jika semua item approved → Sistem mengubah status pesanan menjadi "printing" (5). Sistem mengirim notifikasi ke Customer bahwa desain disetujui dan pesanan masuk produksi (6). Sistem menampilkan pesan sukses ke Staff |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Masih ada item lain yang belum direview → Pesanan tetap di status "design_review" hingga semua item diproses |

---

## 27) Revisi Desain

**Tabel UC-27 Use Case Description Revisi Desain**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Revisi Desain |
| **ID** | UC-27 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat meminta customer untuk memperbaiki desain yang belum memenuhi standar kualitas cetak |
| **Brief Description** | Use case ini adalah perluasan (extend) dari UC-25. Ketika desain tidak memenuhi standar, Staff meminta revisi disertai catatan penjelasan. Customer kemudian dapat mengupload versi baru desain. |
| **Trigger** | Staff menekan tombol "Minta Revisi" pada desain item yang perlu diperbaiki |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Kirim notifikasi ke Customer |
| **Relationships — Extend** | Dipanggil dari UC-25 (Review Desain) |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff menekan tombol "Minta Revisi" pada desain item (2). Staff mengisi catatan/alasan revisi (wajib diisi) (3). Staff menekan tombol "Kirim Permintaan Revisi" (4). Sistem mengubah status review desain item menjadi "revision_requested" (5). Sistem menyimpan catatan revisi dari Staff (6). Sistem mengirim notifikasi WebSocket ke Customer beserta catatan revisi (7). Sistem menampilkan pesan sukses ke Staff |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Staff tidak mengisi catatan revisi → Sistem menampilkan validasi "Catatan revisi wajib diisi" |

---

## 28) Memulai Proses Produksi

**Tabel UC-28 Use Case Description Memulai Proses Produksi**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Memulai Proses Produksi |
| **ID** | UC-28 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat mencatat waktu mulai produksi pesanan yang siap dicetak |
| **Brief Description** | Use case ini menjelaskan bagaimana Staff mencatat dimulainya proses produksi (pencetakan) untuk pesanan yang semua desainnya sudah diapprove. Sistem akan mencatat waktu mulai dan identitas staff yang mengerjakan. |
| **Trigger** | Staff menekan tombol "Mulai Cetak" pada pesanan yang berstatus "printing" |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Catat production_logs |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff membuka halaman daftar pesanan (2). Staff memilih pesanan berstatus "printing" (3). Staff menekan tombol "Mulai Cetak" (4). Sistem mencatat waktu mulai (start_time) dan staff_id ke tabel production_logs (5). Sistem menampilkan konfirmasi "Produksi dimulai" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Pesanan belum berstatus "printing" → Tombol "Mulai Cetak" tidak aktif |

---

## 29) Menyelesaikan Produksi

**Tabel UC-29 Use Case Description Menyelesaikan Produksi**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menyelesaikan Produksi |
| **ID** | UC-29 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Staff — Dapat mencatat selesainya proses produksi sehingga pesanan dapat diambil oleh customer |
| **Brief Description** | Use case ini menjelaskan bagaimana Staff mencatat selesainya proses cetak. Status pesanan berubah menjadi "ready" dan customer mendapat notifikasi real-time bahwa pesanannya siap diambil. |
| **Trigger** | Staff menekan tombol "Selesai Cetak" setelah proses pencetakan selesai |
| **Type** | External |
| **Relationships — Association** | Staff |
| **Relationships — Include** | Autentikasi JWT, Update production_logs, Kirim notifikasi WebSocket |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Staff menyelesaikan proses pencetakan (2). Staff membuka detail pesanan yang sedang diproduksi (3). Staff menambahkan catatan hasil produksi (opsional) (4). Staff menekan tombol "Selesai Cetak" (5). Sistem mencatat waktu selesai (end_time) dan catatan ke production_logs (6). Sistem mengubah status pesanan menjadi "ready" (7). Sistem mengirim notifikasi WebSocket ke Customer: "Pesanan Anda siap diambil!" (8). Sistem menampilkan konfirmasi ke Staff |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Produksi belum dimulai (start_time belum ada) → Sistem menampilkan pesan "Mulai produksi terlebih dahulu" |

---

## 30) Mendaftarkan Akun Staff

**Tabel UC-30 Use Case Description Mendaftarkan Akun Staff**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Mendaftarkan Akun Staff |
| **ID** | UC-30 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat mendaftarkan karyawan baru sebagai Staff di sistem |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner mendaftarkan akun baru untuk karyawan yang akan bertugas sebagai Staff. Akun yang dibuat otomatis mendapat role "staff" dan langsung aktif. |
| **Trigger** | Owner menekan tombol "Tambah Staff" pada halaman manajemen pengguna |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen pengguna (2). Owner menekan tombol "Tambah Staff" (3). Owner mengisi data: nama, email, dan password (4). Owner menekan tombol "Simpan" (5). Sistem memvalidasi data (email unik, password valid) (6). Sistem membuat akun baru dengan role "staff" dan status aktif (7). Sistem mencatat aksi ke audit_logs (8). Sistem menampilkan pesan "Akun staff berhasil dibuat" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Email sudah terdaftar → Sistem menampilkan pesan "Email sudah digunakan" |

---

## 31) Menampilkan Daftar Pengguna

**Tabel UC-31 Use Case Description Menampilkan Daftar Pengguna**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Daftar Pengguna |
| **ID** | UC-31 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat melihat seluruh pengguna yang terdaftar di sistem untuk keperluan monitoring dan manajemen |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat daftar semua pengguna sistem (customer, staff, dan owner) beserta informasi role, status aktif, dan tanggal bergabung. |
| **Trigger** | Owner membuka menu "Manajemen Pengguna" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Manajemen Pengguna" (2). Sistem mengambil daftar semua pengguna dari database (3). Sistem menampilkan daftar pengguna beserta nama, email, role, status aktif, dan tanggal bergabung (4). Owner dapat mencari pengguna berdasarkan nama atau email |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Tidak ada pengguna selain owner → Sistem menampilkan daftar kosong |

---

## 32) Ban / Unban Akun User

**Tabel UC-32 Use Case Description Ban / Unban Akun User**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Ban / Unban Akun User |
| **ID** | UC-32 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat menonaktifkan atau mengaktifkan kembali akun pengguna yang bermasalah |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat menonaktifkan (ban) atau mengaktifkan kembali (unban) akun pengguna. Akun yang dinonaktifkan tidak dapat login ke sistem. |
| **Trigger** | Owner menekan tombol "Ban" atau "Aktifkan" pada baris pengguna di halaman manajemen pengguna |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen pengguna (2). Owner menemukan pengguna yang ingin diban/unban (3). Owner menekan tombol "Ban" (untuk menonaktifkan) atau "Aktifkan" (untuk mengaktifkan kembali) (4). Sistem menampilkan konfirmasi tindakan (5). Owner mengkonfirmasi (6). Sistem mengubah nilai is_active pengguna di database (7). Sistem mencatat aksi ke audit_logs (8). Sistem menampilkan pesan "Status akun berhasil diubah" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Owner mencoba menonaktifkan akun dirinya sendiri → Sistem menampilkan pesan "Anda tidak dapat menonaktifkan akun sendiri" |

---

## 33) Menambahkan Produk Baru

**Tabel UC-33 Use Case Description Menambahkan Produk Baru**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menambahkan Produk Baru |
| **ID** | UC-33 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat menambahkan produk percetakan baru beserta variannya ke dalam katalog sistem |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat menambahkan produk baru ke katalog, lengkap dengan kategori, deskripsi, harga dasar, estimasi pengerjaan, dan minimal satu varian produk. |
| **Trigger** | Owner menekan tombol "Tambah Produk" pada halaman manajemen produk |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen produk (2). Owner menekan tombol "Tambah Produk Baru" (3). Owner mengisi: nama produk, kategori, deskripsi, harga dasar, estimasi hari selesai (4). Owner menambahkan minimal satu varian (SKU, nama varian, harga, material) (5). Owner menekan tombol "Simpan" (6). Sistem memvalidasi data produk (7). Sistem menyimpan produk baru dengan status aktif (8). Sistem menampilkan pesan "Produk berhasil ditambahkan" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). SKU varian sudah digunakan oleh produk lain → Sistem menampilkan pesan "SKU sudah digunakan" (2'). Data wajib tidak diisi lengkap → Sistem menampilkan validasi per field |

---

## 34) Update Produk

**Tabel UC-34 Use Case Description Update Produk**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Update Produk |
| **ID** | UC-34 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memperbarui informasi produk yang sudah ada seperti harga, deskripsi, atau varian |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat memperbarui informasi produk yang sudah ada di katalog, termasuk nama, harga, deskripsi, estimasi, dan varian-variannya. |
| **Trigger** | Owner menekan tombol "Edit" pada produk yang ingin diubah di halaman manajemen produk |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen produk (2). Owner menekan tombol "Edit" pada produk yang diinginkan (3). Sistem menampilkan form edit berisi data produk saat ini (4). Owner mengubah data yang perlu diperbarui (5). Owner menekan tombol "Simpan Perubahan" (6). Sistem memvalidasi data baru (7). Sistem menyimpan perubahan dan mencatat waktu update (updated_at) (8). Sistem menampilkan pesan "Produk berhasil diperbarui" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Data yang diubah tidak valid → Sistem menampilkan pesan validasi |

---

## 35) Hapus Produk

**Tabel UC-35 Use Case Description Hapus Produk**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Hapus Produk |
| **ID** | UC-35 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat menghapus produk yang sudah tidak tersedia dari katalog |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat menghapus produk dari katalog. Penghapusan dilakukan secara soft delete (produk tidak benar-benar dihapus dari database, hanya ditandai tidak aktif) untuk menjaga integritas data pesanan lama. |
| **Trigger** | Owner menekan tombol "Hapus" pada produk yang ingin dihapus |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen produk (2). Owner menekan tombol "Hapus" pada produk yang diinginkan (3). Sistem menampilkan konfirmasi "Yakin ingin menghapus produk ini?" (4). Owner mengkonfirmasi penghapusan (5). Sistem melakukan soft delete: mengisi kolom deleted_at dan mengubah is_active menjadi false (6). Produk tidak lagi muncul di katalog customer (7). Sistem menampilkan pesan "Produk berhasil dihapus" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Owner membatalkan konfirmasi → Produk tidak dihapus |

---

## 36) Menampilkan Daftar Material

**Tabel UC-36 Use Case Description Menampilkan Daftar Material**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Daftar Material |
| **ID** | UC-36 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memantau semua material bahan cetak beserta stok yang tersisa |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat daftar semua material yang terdaftar di sistem beserta informasi stok terkini, satuan, dan riwayat perubahan stok. |
| **Trigger** | Owner membuka menu "Manajemen Material" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Manajemen Material" (2). Sistem mengambil daftar semua material dari database (3). Sistem menampilkan daftar material beserta nama, stok tersisa, satuan, dan tanggal terakhir diperbarui |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Belum ada material yang terdaftar → Sistem menampilkan pesan "Belum ada data material" |

---

## 37) Menambahkan Material Baru

**Tabel UC-37 Use Case Description Menambahkan Material Baru**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menambahkan Material Baru |
| **ID** | UC-37 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat mendaftarkan jenis material bahan cetak baru ke dalam sistem |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat menambahkan jenis material baru ke sistem, seperti kertas glossy, art carton, atau vinyl, beserta stok awal dan satuannya. |
| **Trigger** | Owner menekan tombol "Tambah Material" pada halaman manajemen material |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen material (2). Owner menekan tombol "Tambah Material Baru" (3). Owner mengisi: nama material, stok awal, dan satuan (Meter / Rim / Lembar) (4). Owner menekan tombol "Simpan" (5). Sistem menyimpan material baru ke database (6). Sistem mencatat aksi ke audit_logs (7). Sistem menampilkan pesan "Material berhasil ditambahkan" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Nama material sudah terdaftar → Sistem menampilkan peringatan duplikasi |

---

## 38) Menyesuaikan Stok Material

**Tabel UC-38 Use Case Description Menyesuaikan Stok Material**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menyesuaikan Stok Material |
| **ID** | UC-38 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memperbarui stok material secara manual ketika ada pembelian bahan baru atau pengurangan karena kerusakan |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melakukan penyesuaian stok material, baik penambahan (in) maupun pengurangan (out), beserta alasan dan referensi penyesuaian yang akan dicatat dalam log. |
| **Trigger** | Owner menekan tombol "Sesuaikan Stok" pada salah satu material |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner, Catat material_stock_logs |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka halaman manajemen material (2). Owner memilih material yang stoknya ingin disesuaikan (3). Owner menekan tombol "Sesuaikan Stok" (4). Owner memilih tipe penyesuaian: "Masuk (in)" atau "Keluar (out)" (5). Owner memasukkan jumlah penyesuaian (6). Owner mengisi keterangan/referensi (contoh: "Pembelian baru", "Rusak terkena air") (7). Owner menekan tombol "Simpan" (8). Sistem memperbarui stok material di database (9). Sistem mencatat perubahan ke material_stock_logs (10). Sistem menampilkan pesan "Stok berhasil disesuaikan" |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Penyesuaian "out" melebihi stok yang tersedia → Sistem menampilkan pesan "Stok tidak mencukupi untuk pengurangan sebesar ini" |

---

## 39) Menampilkan Laporan Pendapatan

**Tabel UC-39 Use Case Description Menampilkan Laporan Pendapatan**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Laporan Pendapatan |
| **ID** | UC-39 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memantau total pendapatan bisnis secara real-time berdasarkan periode waktu |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat laporan revenue dari pesanan yang sudah selesai, dengan pilihan filter harian, mingguan, bulanan, atau custom range tanggal. |
| **Trigger** | Owner membuka menu "Laporan Pendapatan" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Laporan Pendapatan" (2). Sistem menampilkan laporan default periode bulan berjalan (3). Owner dapat memilih filter: Harian / Mingguan / Bulanan / Custom Range (4). Sistem mengambil data pesanan completed dari database sesuai periode (5). Sistem menghitung dan menampilkan: total revenue, jumlah pesanan, dan rata-rata nilai pesanan |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Tidak ada pesanan selesai pada periode yang dipilih → Sistem menampilkan "Tidak ada data pendapatan pada periode ini" |

---

## 40) Menampilkan Statistik Produk Terlaris

**Tabel UC-40 Use Case Description Menampilkan Statistik Produk Terlaris**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Statistik Produk Terlaris |
| **ID** | UC-40 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat mengetahui produk mana yang paling banyak dipesan untuk mengambil keputusan bisnis |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat ranking produk berdasarkan jumlah unit terjual dan total revenue yang dihasilkan masing-masing produk. |
| **Trigger** | Owner membuka menu "Statistik Produk" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Statistik Produk" (2). Sistem mengambil data agregat dari order_items yang terkait pesanan berstatus completed (3). Sistem menghitung total unit terjual dan total revenue per produk (4). Sistem menampilkan daftar produk terurut dari yang paling banyak terjual |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Belum ada pesanan selesai → Sistem menampilkan "Belum ada data statistik produk" |

---

## 41) Menampilkan Audit Log Sistem

**Tabel UC-41 Use Case Description Menampilkan Audit Log Sistem**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Audit Log Sistem |
| **ID** | UC-41 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memantau seluruh aktivitas penting dalam sistem untuk keperluan keamanan dan akuntabilitas |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat riwayat semua aksi penting yang dilakukan oleh semua pengguna, seperti login, register, checkout, approve pembayaran, dan perubahan produk. |
| **Trigger** | Owner membuka menu "Audit Log" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Audit Log" (2). Sistem mengambil data dari tabel audit_logs (3). Sistem menampilkan daftar log beserta: nama user, role, aksi yang dilakukan, entitas terkait, IP address, dan waktu kejadian (4). Owner dapat memfilter berdasarkan tanggal, user, atau jenis aksi |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Tidak ada log → Sistem menampilkan "Belum ada aktivitas tercatat" |

---

## 42) Menampilkan Login Log

**Tabel UC-42 Use Case Description Menampilkan Login Log**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Login Log |
| **ID** | UC-42 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memantau aktivitas login dan logout seluruh pengguna untuk keamanan sistem |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat riwayat semua aktivitas login dan logout pengguna, termasuk informasi IP address dan perangkat yang digunakan. |
| **Trigger** | Owner membuka menu "Login Log" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Login Log" (2). Sistem mengambil data dari tabel login_logs (3). Sistem menampilkan daftar log beserta: nama user, tipe aktivitas (login/logout), IP address, user agent (perangkat/browser), dan waktu (4). Owner dapat memfilter berdasarkan tanggal atau pengguna |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Tidak ada log login → Sistem menampilkan "Belum ada aktivitas login tercatat" |

---

## 43) Menampilkan Production Log

**Tabel UC-43 Use Case Description Menampilkan Production Log**

| Field | Keterangan |
|-------|-----------|
| **Use case name** | Menampilkan Production Log |
| **ID** | UC-43 |
| **Use case type** | Detailed, Essential |
| **Stakeholders and interests** | Owner — Dapat memantau riwayat proses produksi semua pesanan untuk evaluasi performa staff dan efisiensi produksi |
| **Brief Description** | Use case ini menjelaskan bagaimana Owner dapat melihat riwayat log produksi seluruh pesanan, termasuk siapa staff yang mengerjakan, kapan dimulai, kapan selesai, dan catatan produksi. |
| **Trigger** | Owner membuka menu "Production Log" pada dashboard |
| **Type** | External |
| **Relationships — Association** | Owner |
| **Relationships — Include** | Autentikasi JWT, Otorisasi role Owner |
| **Relationships — Extend** | — |
| **Relationships — Generalization** | — |
| **Normal flow of events** | (1). Owner membuka menu "Production Log" (2). Sistem mengambil data dari tabel production_logs (3). Sistem menampilkan daftar log produksi beserta: kode pesanan, nama staff yang mengerjakan, waktu mulai (start_time), waktu selesai (end_time), durasi pengerjaan, dan catatan produksi (4). Owner dapat memfilter berdasarkan tanggal atau nama staff |
| **Subflow** | — |
| **Alternative/Exceptional flow** | (1'). Belum ada proses produksi → Sistem menampilkan "Belum ada data produksi tercatat" |

---

> 📌 Dokumen ini mencakup **43 Use Case Description** yang mengacu pada Use Case Diagram sistem Jaya Mandiri Digital Printing Management System.

---

*Jaya Mandiri Digital Printing | Use Case Description v1.0.0 | 02 Juni 2026*

# Panduan Perintah Dasar Git (Git Cheat Sheet)

Dokumen ini berisi "rumus" atau daftar perintah dasar Git yang sering digunakan dalam pengembangan proyek, lengkap dengan penjelasannya.

---

## 1. Memulai & Konfigurasi (Setup & Init)

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git clone <url_repo>` | Mengunduh (cloning) repository dari GitHub/GitLab ke komputer lokal. |
| `git init` | Membuat repository Git baru di dalam folder lokal yang belum terhubung dengan Git. |
| `git config --global user.name "Nama"` | Mengatur nama pengguna untuk setiap *commit* yang dibuat. |
| `git config --global user.email "email@mu"` | Mengatur email pengguna untuk setiap *commit*. |

## 2. Mengecek Status (Status & Log)

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git status` | Melihat status file saat ini (file apa saja yang diubah, ditambah, atau dihapus yang belum di-commit). |
| `git log` | Melihat riwayat *commit* (history) yang pernah dilakukan sebelumnya. |
| `git diff` | Melihat secara detail baris kode apa saja yang baru diubah tapi belum di-add (staged). |

## 3. Menyimpan Perubahan (Staging & Committing)

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git add .` | Menambahkan **semua** file yang berubah ke *staging area* (persiapan sebelum di-commit). |
| `git add <nama_file>` | Menambahkan **satu file spesifik** saja ke *staging area*. |
| `git commit -m "pesan"` | Menyimpan permanen perubahan yang ada di *staging area* ke dalam riwayat lokal dengan pesan/catatan tertentu. |

## 4. Sinkronisasi dengan GitHub (Push & Pull)

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git pull origin main` | **Mengambil (download)** pembaruan kode terbaru dari GitHub (branch `main`) ke komputer lokal dan langsung menggabungkannya. Sangat penting dilakukan sebelum mulai ngoding agar kode tidak bentrok (*conflict*). |
| `git fetch` | Hanya mengunduh informasi pembaruan dari GitHub tanpa langsung menggabungkannya ke kode lokal. |
| `git push origin main` | **Mengunggah (upload)** *commit* lokal yang sudah dibuat ke GitHub (di branch `main`). |

## 5. Percabangan (Branching & Merging)

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git branch` | Melihat daftar *branch* (cabang) yang ada di komputermu. |
| `git branch <nama_branch>` | Membuat *branch* baru. |
| `git checkout <nama_branch>` | Berpindah ke *branch* lain. |
| `git checkout -b <nama_branch>`| Membuat *branch* baru sekaligus langsung berpindah ke *branch* tersebut. |
| `git merge <nama_branch>` | Menggabungkan kode dari *branch* lain ke *branch* kamu saat ini. |

## 6. Menyimpan Sementara (Stashing)
Sangat berguna ketika kamu sedang ngoding tapi temanmu menyuruh melakukan `git pull`, padahal kodemu belum siap di-commit.

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git stash` | Menyimpan sementara semua perubahan kodemu ke tempat tersembunyi, sehingga kodemu kembali bersih sesuai commit terakhir. |
| `git stash pop` | Mengembalikan kodemu yang tadi disimpan sementara agar bisa dilanjutkan kembali. |
| `git stash list` | Melihat daftar kodingan yang sedang disimpan sementara. |

## 7. Membatalkan Perubahan (Undo)

| Perintah | Fungsi / Penjelasan |
|---|---|
| `git restore <nama_file>` | Membatalkan perubahan yang belum di-add pada satu file (kembali ke kondisi commit terakhir). |
| `git reset --soft HEAD~1` | Membatalkan *commit* terakhir, tapi file yang diubah tetap ada di *staging area* (tidak hilang). |
| `git reset --hard HEAD~1` | ⚠️ **BERBAHAYA**: Membatalkan *commit* terakhir dan **menghapus semua file** yang diubah pada commit tersebut secara permanen. |

---

### Alur Kerja Harian yang Sering Digunakan:
1. `git pull origin main` (Update kode terbaru dari teman)
2. *...mulai ngoding...*
3. `git add .` (Pilih semua perubahan)
4. `git commit -m "fitur: menambahkan halaman login"` (Beri label/catatan)
5. `git push origin main` (Kirim ke GitHub)

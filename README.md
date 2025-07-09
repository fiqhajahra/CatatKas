
# CatatKas – Aplikasi Pencatatan Keuangan Pribadi

## 📌 Deskripsi Proyek

**CatatKas** adalah aplikasi web berbasis Laravel yang dirancang untuk membantu pengguna, khususnya mahasiswa atau individu, dalam mencatat pemasukan dan pengeluaran pribadi mereka secara sederhana dan efisien. Aplikasi ini memungkinkan pengguna untuk memantau arus kas harian dan menjaga pengelolaan keuangan tetap terorganisir.

## 🎯 Tujuan Aplikasi

Tujuan utama dari pengembangan aplikasi ini adalah:

- Membantu pengguna dalam mencatat transaksi keuangan pribadi (pemasukan dan pengeluaran).
- Memberikan gambaran sederhana tentang total saldo pengguna.
- Menyediakan antarmuka yang mudah digunakan untuk semua kalangan.

## 👥 Pengguna Utama

Pengguna utama dari aplikasi ini adalah **individu atau mahasiswa** yang ingin mencatat dan memantau keuangan pribadinya secara mandiri.

## 📌 Latar Belakang Masalah

Banyak orang kesulitan mengingat atau mencatat transaksi keuangan kecil sehari-hari, seperti uang jajan, belanja kebutuhan, atau menerima uang dari orang tua. Tidak adanya catatan membuat mereka kesulitan mengontrol keuangan. Oleh karena itu, dibutuhkan aplikasi sederhana untuk mencatat dan memantau pemasukan serta pengeluaran dengan jelas.

## ✅ Spesifikasi Kebutuhan

### Fungsional

1. Menambahkan catatan keuangan baru (jenis, nominal, deskripsi, tanggal)
2. Menampilkan daftar semua transaksi
3. Mengedit data transaksi
4. Menghapus transaksi
5. Menampilkan total saldo (pemasukan - pengeluaran)
6. *(Opsional)* Login dan registrasi pengguna

### Non-Fungsional

- Aplikasi ringan dan cepat diakses
- Antarmuka sederhana dan ramah pengguna
- Keamanan data pengguna (jika pakai login)
- Responsif untuk digunakan di perangkat desktop atau mobile

## 🧭 Diagram Konteks

```sh
Pengguna (Mahasiswa)
        │
        ▼
   [Aplikasi CatatKas]
        │
        ├── Tambah Transaksi
        ├── Lihat Riwayat
        ├── Edit / Hapus Data
        └── Lihat Total Saldo
```

## 🔄 Flowchart Sistem

```sh
[Mulai]
   ↓
[Login/Register] (opsional)
   ↓
[Dashboard Keuangan]
   ↓
 ┌──────────────┬──────────────┬──────────────┐
 ↓              ↓              ↓              ↓
Tambah Data   Edit Data    Hapus Data   Lihat Total Saldo
   ↓              ↓              ↓              ↓
   └──────────────┴──────────────┴──────────────┘
                        ↓
                   [Selesai]
```

## 🗂️ Entity Relationship Diagram (ERD)

```sh
TABEL USERS
- id
- name
- email
- password

TABEL TRANSACTIONS
- id
- user_id (relasi ke users)
- type (pemasukan/pengeluaran)
- amount
- description
- transaction_date
- created_at
- updated_at
```

Relasi:

- Satu user memiliki banyak transaksi (One to Many)
- Setiap transaksi hanya dimiliki oleh satu user

## 📎 Penutup

Dokumentasi ini merangkum perencanaan awal dari aplikasi **CatatKas**. Dengan fitur yang sederhana namun berguna, aplikasi ini sangat cocok untuk individu atau mahasiswa yang ingin mulai belajar mengelola keuangan pribadi. Aplikasi ini juga menjadi contoh implementasi nyata dari framework Laravel dalam pengembangan aplikasi berbasis web.

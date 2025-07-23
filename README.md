# 🧾 Employee Attendance API (Backend)

Sistem absensi berbasis Laravel untuk mencatat kehadiran karyawan dan histori absensi berdasarkan ERD dan flowchart.

---

## ✅ Repository GitHub

```
https://github.com/zhabsnaziz/employee-attendance-backend
```

---

## 📁 Struktur Folder

```
employee-attendance-backend/
├── app/
│   └── Models/
│   └── Http/Controllers/
├── database/
│   └── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── public/
├── .env
├── composer.json
├── README.md
├── employee-attendance.postman_collection.json
└── ...
```

---

## 📘 Fitur Utama

- CRUD Karyawan
- CRUD Departemen
- Absen Masuk / Absen Keluar
- Log Absensi + Ketepatan waktu

---

## ⚙️ Instalasi

```bash
git clone https://github.com/zhabsnaziz/employee-attendance-backend.git
cd employee-attendance-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## 📬 Endpoint API

| Method | Endpoint                     | Deskripsi                       |
|--------|------------------------------|----------------------------------|
| GET    | /api/departments             | Ambil semua departemen          |
| POST   | /api/departments             | Tambah departemen               |
| PUT    | /api/departments/{id}        | Update departemen               |
| DELETE | /api/departments/{id}        | Hapus departemen                |
| GET    | /api/employees               | Ambil semua karyawan            |
| POST   | /api/employees               | Tambah karyawan                 |
| PUT    | /api/employees/{id}          | Update karyawan                 |
| DELETE | /api/employees/{id}          | Hapus karyawan                  |
| POST   | /api/attendance/clock-in     | Clock In karyawan               |
| PUT    | /api/attendance/clock-out    | Clock Out karyawan              |
| GET    | /api/attendance/logs         | Lihat log absensi dengan filter |

---

## 📌 Penjelasan attendance_type

| Value | Meaning   |
|-------|-----------|
| 1     | Clock In  |
| 2     | Clock Out |

---

## 📄 Postman Collection

File `employee-attendance.postman_collection.json` telah disertakan di dalam repository ini untuk memudahkan pengujian API.

### Cara Menggunakan:

1. Buka Postman.
2. Klik **Import** > **Upload Files**.
3. Pilih file: `employee-attendance.postman_collection.json`.
4. Jalankan semua request sesuai endpoint.

---

## 🛡️ Keamanan API

- Validasi input menggunakan Validator
- Response JSON konsisten
- Proteksi error dan status code
- autoincrement (integer) untuk `attendance_id`, format custom string (EMP001) untuk `employee_id`

---

## 📄 Info Tambahan

- Laravel 20.x
- PHP >= 8.2
- DB: MySQL

---

_Dikembangkan untuk keperluan ujian Fullstack Developer Challenge oleh PT Fleetify._

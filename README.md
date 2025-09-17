# 🌾 PTPN IV Regional 3 - Sistem Manajemen Pembelian CPO & PK

## 👥 Tim Pengembang

| Nama               | NIM             | Konsentrasi          |
|--------------------|-----------------|-----------------------|
| [Muhammad Nabil](https://github.com/Nabil17-alt)     | 2210031802043   | Full Stack Developer  |
| [Putri Kurnia Sari](https://github.com/putrikurniasari)  | 2210031802061   | Full Stack Developer  |

---

## 📝 Deskripsi Proyek

Sistem Informasi Manajemen Pembelian **CPO (Crude Palm Oil)** dan **PK (Palm Kernel)** dikembangkan untuk mendukung operasional **PTPN IV Regional 3**. Aplikasi berbasis web ini mempermudah pencatatan, validasi, dan visualisasi data pembelian secara efisien dan terstruktur.

Dibangun menggunakan **Laravel** dan **TailwindCSS** untuk tampilan antarmuka yang modern dan responsif.

---

## 🖼️ Preview Tampilan Antarmuka

### 🔐 Halaman Login  
![Login](public/images/login.png)

### 📊 Halaman Dashboard  
![Dashboard](public/images/layout_dashboard.png)

### 👤 Halaman Akun (Admin)  
![Akun](public/images/layout_akun.png)

### 👤 Halaman Akun (User)  
![Akun](public/images/layout_akunuser.png)

### 🛒 Halaman Pembelian (Admin)  
![Pembelian Admin](public/images/layout_pembelianadmin.png)

### 🛒 Halaman Pembelian (User)  
![Pembelian User](public/images/layout_pembelianuser.png)

---

## 🚀 Fitur Unggulan

- 🔐 Autentikasi pengguna (Login & Registrasi)
- 👥 Sistem multi-role: Admin, Unit, dll
- 📦 Manajemen pembelian CPO & PK secara real-time
- 📊 Visualisasi dan rekap data pembelian
- 🧾 Form input dinamis dengan validasi otomatis
- 📱 Tampilan modern, bersih, dan responsif (mobile-friendly)

---

## ⚙️ Teknologi yang Digunakan

- **Backend Framework:** Laravel 10  
- **Frontend:** Blade + TailwindCSS  
- **Database:** MySQL  
- **Library Tambahan:** Flowbite, Lucide Icons

---

## 🎓 Dokumentasi & Panduan

- 🖼️ **PowerPoint:** [Klik di sini](https://docs.google.com/presentation/d/1JxrYCg33Kk08NNOqulgO9ZhB58bCnEHb/edit?usp=sharing&ouid=116701076625364631463&rtpof=true&sd=true)  
- 🎥 **Video Panduan Penggunaan:** [Klik di sini](https://drive.google.com/file/d/1m4I4rdB8PDVkfwvC-yrFx38zFNp2JzMu/view?usp=sharing)  
- 🌐 **Flowchart Sistem:** [Klik di sini](https://drive.google.com/file/d/1DBDd1m3pu9i0gQQCx_qDm0u1NWat7L90/view?usp=sharing)  
- 🗂️ **Use Case Diagram:** [Klik di sini](https://drive.google.com/drive/folders/1KDurrYm1C77iZg6qsjrj4iOGh_UsCRnI?usp=sharing)

---

## 📦 Cara Instalasi & Menjalankan Aplikasi

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/Nabil17-alt/PTPN4Regional3.git
   cd PTPN4Regional3
   ```

2. **Install dependency PHP:**
   ```bash
   composer install
   ```

3. **Copy file environment dan sesuaikan:**
   ```bash
   cp .env.example .env
   ```

4. **Generate key aplikasi:**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi database di file `.env`, lalu jalankan migrasi:**
   ```bash
   php artisan migrate
   ```

6. **Install dependency frontend:**
   ```bash
   npm install && npm run dev
   ```

7. **Jalankan server Laravel:**
   ```bash
   php artisan serve
   ```

---

## 📫 Kontak & Kontribusi

Jika Anda ingin memberikan masukan atau kontribusi terhadap proyek ini, silakan buat _issue_ atau _pull request_ melalui repositori GitHub kami.

📌 GitHub: [Nabil17-alt/PTPN4Regional3](https://github.com/Nabil17-alt/PTPN4Regional3)
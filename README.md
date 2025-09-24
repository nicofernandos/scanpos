# 📌 Barcode Scan for Sale Order & Reservation

## 📖 Overview
Aplikasi **Barcode Scan** adalah sistem berbasis web yang dibangun menggunakan **Laravel** dan **Bootstrap**.  
Aplikasi ini menyediakan dua fitur utama:  

1. **Sale Order**  
   - Digunakan ketika pelanggan sudah duduk di meja dan ingin memesan produk.  
   - Bisa melakukan **order pertama** atau **repeat order** langsung dari meja yang dipindai QR Code-nya.  

2. **Reservasi**  
   - Digunakan untuk pelanggan yang ingin melakukan **pemesanan dari jarak jauh**.  
   - Pelanggan dapat memilih produk sekaligus menentukan **tanggal dan jam reservasi**.  

Sistem ini juga terintegrasi dengan **Payment Gateway Midtrans** untuk memproses pembayaran secara online.  

---

## ⚙️ Cara Instalasi & Menjalankan Aplikasi

1. **Clone Repository**
   ```bash
   git clone <repo-url>
   cd <nama-folder-project>

composer install

npm install && npm run dev

APP_NAME=BarcodeScan
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scanpos
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key


## 🛠️ Tech Stack yang Digunakan

- **Laravel** → Framework backend PHP  
- **Bootstrap** → Styling front-end  
- **Midtrans** → Payment Gateway  
- **Cron Job** → Untuk pengecekan otomatis status pembayaran pending  

---

## 🔄 Flow Sale Order

1. Pelanggan **scan QR Code meja**.  
2. Sistem mengarahkan pelanggan ke **URL sesuai dengan nomor meja**.  
3. Jika pelanggan mencoba akses tanpa meja → sistem otomatis redirect ke halaman **pilih meja**.  
4. Setelah memilih meja → pelanggan masuk ke halaman **Sale Order**.  
5. Pelanggan mengisi **nama** + memilih **produk** yang diinginkan.  
6. Sistem membuat transaksi sale order dan menampilkan **total nominal**.  
7. Pelanggan dapat langsung melakukan **pembayaran via Midtrans**.  

---

## 🔄 Flow Reservasi

1. Pelanggan masuk ke halaman **Reservasi**.  
2. Pertama, pelanggan harus mengisi **nomor handphone**:  
   - Jika **sudah terdaftar** → sistem otomatis mengisi data diri.  
   - Jika **belum terdaftar** → pelanggan wajib mengisi form biodata.  
3. Setelah data terisi → pelanggan memilih **tanggal & jam reservasi**.  
4. Pelanggan memilih **produk** yang ingin dipesan.  
5. Sistem menampilkan **ringkasan pesanan + total nominal**.  
6. Pelanggan melakukan **konfirmasi & pembayaran melalui Midtrans**.  
7. Data reservasi tersimpan, dan pelanggan akan mendapatkan **konfirmasi reservasi berhasil**.  

# 🎯 Pertemuan 1: Pengenalan Object-Oriented Programming (OOP) di PHP

## 📚 Tujuan Pembelajaran
Setelah mengikuti pertemuan ini, mahasiswa diharapkan dapat:
1. Memahami konsep dasar Object-Oriented Programming (OOP)
2. Membedakan antara paradigma prosedural dan object-oriented
3. Mendefinisikan class dan membuat object di PHP
4. Memahami konsep properti dan method dalam class
5. Menggunakan keyword `$this` untuk mengakses properti dan method

## 💡 Konsep Dasar OOP

### Apa itu Object-Oriented Programming?
Object-Oriented Programming (OOP) adalah paradigma pemrograman yang mengorganisir kode dalam bentuk objek-objek yang berinteraksi satu sama lain. Berbeda dengan pemrograman prosedural yang berfokus pada fungsi, OOP berfokus pada objek yang memiliki data (properti) dan perilaku (method).

### Paradigma Prosedural vs Object-Oriented

**Paradigma Prosedural:**
- Kode diorganisir dalam bentuk fungsi-fungsi
- Data dan fungsi terpisah
- Alur eksekusi dari atas ke bawah
- Cocok untuk program sederhana

**Paradigma Object-Oriented:**
- Kode diorganisir dalam bentuk objek-objek
- Data dan fungsi dibungkus dalam satu kesatuan (class)
- Fokus pada interaksi antar objek
- Cocok untuk aplikasi yang kompleks dan besar

### ✨ Keuntungan Menggunakan OOP

1. **Modularitas (Modularity)**
   - Kode lebih terorganisir dan mudah dikelola
   - Setiap class memiliki tanggung jawab yang jelas

2. **Reusability (Dapat Digunakan Ulang)**
   - Class yang sudah dibuat dapat digunakan kembali
   - Mengurangi duplikasi kode

3. **Extensibility (Dapat Diperluas)**
   - Mudah menambahkan fitur baru tanpa mengubah kode yang sudah ada
   - Mendukung prinsip inheritance

4. **Maintainability (Mudah Dipelihara)**
   - Lebih mudah untuk debugging dan maintenance
   - Perubahan pada satu class tidak mempengaruhi class lain

5. **Encapsulation (Enkapsulasi)**
   - Data dan method dapat disembunyikan dari akses eksternal
   - Meningkatkan keamanan kode

## 🏗️ Class dan Object

### Class
- **Definisi:** Blueprint atau template untuk membuat objek
- **Fungsi:** Mendefinisikan struktur data (properti) dan perilaku (method) yang akan dimiliki objek
- **Analogi:** Seperti cetakan kue yang mendefinisikan bentuk kue yang akan dibuat

### Object
- **Definisi:** Instance atau realisasi dari sebuah class
- **Karakteristik:** Memiliki state (nilai properti) dan behavior (method yang dapat dipanggil)
- **Analogi:** Seperti kue yang dibuat dari cetakan, setiap kue bisa memiliki rasa yang berbeda

### Sintaks Dasar di PHP

```php
// Mendefinisikan class
class NamaClass {
    // Properti
    public $properti1;
    public $properti2;

    // Method
    public function namaMethod() {
        // Kode method
    }
}

// Membuat object
$namaObject = new NamaClass();
```

## 🔧 Properti dan Method

### Properti (Properties)
- Variabel yang didefinisikan dalam class
- Menyimpan data atau state dari object
- Dapat memiliki visibility: public, private, protected

### Method
- Fungsi yang didefinisikan dalam class
- Mendefinisikan perilaku atau aksi yang dapat dilakukan object
- Dapat mengakses dan memodifikasi properti class

### Keyword `$this`
- Digunakan untuk mengakses properti dan method dari object yang sedang aktif
- Menunjuk pada instance object saat ini
- Hanya dapat digunakan di dalam method class

## 💻 Contoh Implementasi
Lihat file `example.php` untuk berbagai contoh implementasi class dan object di PHP.

## 📝 Latihan
1. Buat class `Mahasiswa` dengan properti nama, nim, dan jurusan
2. Tambahkan method untuk menampilkan informasi mahasiswa
3. Buat beberapa object mahasiswa dengan data yang berbeda
4. Panggil method untuk menampilkan informasi setiap mahasiswa

## 🏠 Tugas Rumah
Buat class `BukuPerpustakaan` dengan:
- Properti: judul, pengarang, tahunTerbit, statusPinjam
- Method: pinjamBuku(), kembalikanBuku(), tampilkanInfo()
- Buat minimal 3 object buku dan demonstrasikan penggunaan semua method

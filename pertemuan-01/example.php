<?php

/**
 * Pertemuan 1: Pengenalan OOP di PHP
 * Contoh implementasi Class dan Object
 */

echo "<h2>Contoh 1: Class Mobil Sederhana</h2>";

// Mendefinisikan sebuah class bernama 'Mobil'
class Mobil
{
    // Properti (variabel) - data yang dimiliki object
    public $warna;
    public $merk;
    public $tahunProduksi;
    public $kecepatanMaksimal;

    // Method (fungsi) untuk menampilkan informasi mobil
    public function getInfo()
    {
        return "Mobil {$this->merk} ({$this->tahunProduksi}) berwarna {$this->warna} dengan kecepatan maksimal {$this->kecepatanMaksimal} km/jam.";
    }

    // Method untuk menjalankan mobil
    public function nyalakan()
    {
        return "Mobil {$this->merk} dinyalakan. Vroooom!";
    }

    // Method untuk menghitung usia mobil
    public function hitungUsia()
    {
        $tahunSekarang = date('Y');
        return $tahunSekarang - $this->tahunProduksi;
    }
}

// Membuat objek (instance) dari class Mobil
$mobilSaya = new Mobil();

// Mengatur nilai properti
$mobilSaya->warna = "Merah";
$mobilSaya->merk = "Toyota Avanza";
$mobilSaya->tahunProduksi = 2020;
$mobilSaya->kecepatanMaksimal = 160;

// Memanggil method untuk mendapatkan informasi
echo $mobilSaya->getInfo() . "<br>";
echo $mobilSaya->nyalakan() . "<br>";
echo "Usia mobil: " . $mobilSaya->hitungUsia() . " tahun<br>";

echo "<br>";

// Membuat objek lain dari class yang sama
$mobilTeman = new Mobil();
$mobilTeman->warna = "Biru";
$mobilTeman->merk = "Honda Jazz";
$mobilTeman->tahunProduksi = 2018;
$mobilTeman->kecepatanMaksimal = 180;

echo $mobilTeman->getInfo() . "<br>";
echo $mobilTeman->nyalakan() . "<br>";
echo "Usia mobil: " . $mobilTeman->hitungUsia() . " tahun<br>";

echo "<hr>";

echo "<h2>Contoh 2: Class Mahasiswa</h2>";

class Mahasiswa
{
    // Properti mahasiswa
    public $nama;
    public $nim;
    public $jurusan;
    public $ipk;
    public $semester;

    // Method untuk menampilkan biodata
    public function tampilkanBiodata()
    {
        return "Nama: {$this->nama}<br>" .
            "NIM: {$this->nim}<br>" .
            "Jurusan: {$this->jurusan}<br>" .
            "IPK: {$this->ipk}<br>" .
            "Semester: {$this->semester}<br>";
    }

    // Method untuk menentukan status kelulusan
    public function statusKelulusan()
    {
        if ($this->ipk >= 2.75) {
            return "Lulus dengan baik";
        } elseif ($this->ipk >= 2.00) {
            return "Lulus dengan syarat";
        } else {
            return "Tidak lulus";
        }
    }

    // Method untuk menghitung sisa semester
    public function sisaSemester()
    {
        return 8 - $this->semester;
    }
}

// Membuat object mahasiswa
$mahasiswa1 = new Mahasiswa();
$mahasiswa1->nama = "Budi Santoso";
$mahasiswa1->nim = "12345678";
$mahasiswa1->jurusan = "Teknik Informatika";
$mahasiswa1->ipk = 3.45;
$mahasiswa1->semester = 6;

echo "<h3>Mahasiswa 1:</h3>";
echo $mahasiswa1->tampilkanBiodata();
echo "Status: " . $mahasiswa1->statusKelulusan() . "<br>";
echo "Sisa semester: " . $mahasiswa1->sisaSemester() . "<br><br>";

$mahasiswa2 = new Mahasiswa();
$mahasiswa2->nama = "Siti Aminah";
$mahasiswa2->nim = "87654321";
$mahasiswa2->jurusan = "Sistem Informasi";
$mahasiswa2->ipk = 3.78;
$mahasiswa2->semester = 4;

echo "<h3>Mahasiswa 2:</h3>";
echo $mahasiswa2->tampilkanBiodata();
echo "Status: " . $mahasiswa2->statusKelulusan() . "<br>";
echo "Sisa semester: " . $mahasiswa2->sisaSemester() . "<br>";

echo "<hr>";

echo "<h2>Contoh 3: Class Kalkulator Sederhana</h2>";

class Kalkulator
{
    public $angka1;
    public $angka2;

    // Method untuk penjumlahan
    public function tambah()
    {
        return $this->angka1 + $this->angka2;
    }

    // Method untuk pengurangan
    public function kurang()
    {
        return $this->angka1 - $this->angka2;
    }

    // Method untuk perkalian
    public function kali()
    {
        return $this->angka1 * $this->angka2;
    }

    // Method untuk pembagian
    public function bagi()
    {
        if ($this->angka2 != 0) {
            return $this->angka1 / $this->angka2;
        } else {
            return "Error: Tidak bisa dibagi dengan nol!";
        }
    }

    // Method untuk menampilkan hasil semua operasi
    public function tampilkanSemuaOperasi()
    {
        echo "Angka 1: {$this->angka1}<br>";
        echo "Angka 2: {$this->angka2}<br>";
        echo "Penjumlahan: " . $this->tambah() . "<br>";
        echo "Pengurangan: " . $this->kurang() . "<br>";
        echo "Perkalian: " . $this->kali() . "<br>";
        echo "Pembagian: " . $this->bagi() . "<br>";
    }
}

$kalkulator = new Kalkulator();
$kalkulator->angka1 = 15;
$kalkulator->angka2 = 3;

$kalkulator->tampilkanSemuaOperasi();

echo "<hr>";

echo "<h2>Perbedaan Object yang Berbeda dari Class yang Sama</h2>";

// Membuat beberapa object mobil dengan karakteristik berbeda
$mobil1 = new Mobil();
$mobil1->merk = "Ferrari F40";
$mobil1->warna = "Merah";
$mobil1->tahunProduksi = 2021;
$mobil1->kecepatanMaksimal = 324;

$mobil2 = new Mobil();
$mobil2->merk = "Volkswagen Beetle";
$mobil2->warna = "Kuning";
$mobil2->tahunProduksi = 2015;
$mobil2->kecepatanMaksimal = 140;

$mobil3 = new Mobil();
$mobil3->merk = "Tesla Model S";
$mobil3->warna = "Putih";
$mobil3->tahunProduksi = 2022;
$mobil3->kecepatanMaksimal = 250;

echo "<h3>Koleksi Mobil:</h3>";
echo "1. " . $mobil1->getInfo() . "<br>";
echo "2. " . $mobil2->getInfo() . "<br>";
echo "3. " . $mobil3->getInfo() . "<br>";

echo "<h3>Kesimpulan:</h3>";
echo "Dari contoh di atas, kita dapat melihat bahwa:<br>";
echo "- Satu class dapat menghasilkan banyak object dengan karakteristik berbeda<br>";
echo "- Setiap object memiliki data (properti) dan perilaku (method) yang sama, tetapi nilai datanya bisa berbeda<br>";
echo "- Keyword \$this digunakan untuk mengakses properti dan method dari object yang sedang aktif<br>";

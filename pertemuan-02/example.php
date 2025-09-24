<?php

/**
 * Pertemuan 2: Properti dan Method dalam OOP
 * Contoh implementasi berbagai jenis properti dan method
 */

echo "<h1>Pertemuan 2: Properti dan Method</h1>";

echo "<h2>Contoh 1: Instance Properties vs Static Properties</h2>";

class Mahasiswa
{
    // Instance properties - setiap object memiliki nilai yang berbeda
    public $nama;
    public $nim;
    public $jurusan;
    private $ipk;

    // Static property - shared oleh semua instance
    public static $totalMahasiswa = 0;
    public static $namaUniversitas = "Universitas Teknologi Indonesia";

    public function __construct($nama, $nim, $jurusan)
    {
        $this->nama = $nama;
        $this->nim = $nim;
        $this->jurusan = $jurusan;

        // Increment static property setiap kali object dibuat
        self::$totalMahasiswa++;
    }

    // Instance method - dapat mengakses instance properties
    public function tampilkanInfo()
    {
        return "Nama: {$this->nama}, NIM: {$this->nim}, Jurusan: {$this->jurusan}";
    }

    // Getter method
    public function getIpk()
    {
        return $this->ipk;
    }

    // Setter method dengan validasi
    public function setIpk($ipk)
    {
        if ($ipk >= 0 && $ipk <= 4.0) {
            $this->ipk = $ipk;
            return true;
        } else {
            echo "Error: IPK harus antara 0 dan 4.0<br>";
            return false;
        }
    }

    // Static method - tidak dapat mengakses instance properties
    public static function getTotalMahasiswa()
    {
        return self::$totalMahasiswa;
    }

    public static function getInfoUniversitas()
    {
        return "Universitas: " . self::$namaUniversitas . ", Total Mahasiswa: " . self::$totalMahasiswa;
    }
}

// Membuat beberapa object mahasiswa
$mahasiswa1 = new Mahasiswa("Budi Santoso", "001", "Teknik Informatika");
$mahasiswa1->setIpk(3.5);

$mahasiswa2 = new Mahasiswa("Siti Rahma", "002", "Sistem Informasi");
$mahasiswa2->setIpk(3.8);

$mahasiswa3 = new Mahasiswa("Ahmad Yani", "003", "Teknik Elektro");
$mahasiswa3->setIpk(3.2);

echo "<h3>Informasi Mahasiswa:</h3>";
echo "1. " . $mahasiswa1->tampilkanInfo() . ", IPK: " . $mahasiswa1->getIpk() . "<br>";
echo "2. " . $mahasiswa2->tampilkanInfo() . ", IPK: " . $mahasiswa2->getIpk() . "<br>";
echo "3. " . $mahasiswa3->tampilkanInfo() . ", IPK: " . $mahasiswa3->getIpk() . "<br>";

echo "<h3>Static Properties dan Methods:</h3>";
echo "Total mahasiswa yang terdaftar: " . Mahasiswa::getTotalMahasiswa() . "<br>";
echo Mahasiswa::getInfoUniversitas() . "<br>";

echo "<hr>";

echo "<h2>Contoh 2: Method dengan Parameter dan Return Value</h2>";

class Kalkulator
{
    private $hasil = 0;
    private $riwayatOperasi = [];

    // Method dengan multiple parameters dan default value
    public function operasi($angka1, $operator, $angka2, $simpanRiwayat = true)
    {
        switch ($operator) {
            case '+':
                $hasil = $angka1 + $angka2;
                break;
            case '-':
                $hasil = $angka1 - $angka2;
                break;
            case '*':
                $hasil = $angka1 * $angka2;
                break;
            case '/':
                if ($angka2 != 0) {
                    $hasil = $angka1 / $angka2;
                } else {
                    return "Error: Pembagian dengan nol!";
                }
                break;
            default:
                return "Error: Operator tidak valid!";
        }

        $this->hasil = $hasil;

        if ($simpanRiwayat) {
            $this->riwayatOperasi[] = "$angka1 $operator $angka2 = $hasil";
        }

        return $hasil;
    }

    // Method untuk operasi berurutan
    public function tambah($angka)
    {
        $this->hasil += $angka;
        $this->riwayatOperasi[] = "Hasil sebelumnya + $angka = {$this->hasil}";
        return $this->hasil;
    }

    public function kurang($angka)
    {
        $this->hasil -= $angka;
        $this->riwayatOperasi[] = "Hasil sebelumnya - $angka = {$this->hasil}";
        return $this->hasil;
    }

    public function getHasil()
    {
        return $this->hasil;
    }

    public function getRiwayat()
    {
        return $this->riwayatOperasi;
    }

    public function resetKalkulator()
    {
        $this->hasil = 0;
        $this->riwayatOperasi = [];
        return "Kalkulator direset";
    }
}

$calc = new Kalkulator();

echo "<h3>Operasi Kalkulator:</h3>";
echo "15 + 5 = " . $calc->operasi(15, '+', 5) . "<br>";
echo "10 * 3 = " . $calc->operasi(10, '*', 3) . "<br>";
echo "20 / 4 = " . $calc->operasi(20, '/', 4) . "<br>";

echo "<h3>Operasi Berurutan:</h3>";
echo "Mulai dari hasil terakhir: " . $calc->getHasil() . "<br>";
echo "Tambah 10: " . $calc->tambah(10) . "<br>";
echo "Kurang 3: " . $calc->kurang(3) . "<br>";

echo "<h3>Riwayat Operasi:</h3>";
foreach ($calc->getRiwayat() as $operasi) {
    echo "- $operasi<br>";
}

echo "<hr>";

echo "<h2>Contoh 3: Method Chaining</h2>";

class PembangunString
{
    private $string = "";

    public function tambah($text)
    {
        $this->string .= $text;
        return $this; // Return $this untuk method chaining
    }

    public function hurufBesar()
    {
        $this->string = strtoupper($this->string);
        return $this;
    }

    public function hurufKecil()
    {
        $this->string = strtolower($this->string);
        return $this;
    }

    public function balik()
    {
        $this->string = strrev($this->string);
        return $this;
    }

    public function spasi()
    {
        $this->string .= " ";
        return $this;
    }

    public function barisBar()
    {
        $this->string .= "<br>";
        return $this;
    }

    public function dapatkan()
    {
        return $this->string;
    }

    public function reset()
    {
        $this->string = "";
        return $this;
    }
}

$builder = new PembangunString();

echo "<h3>Method Chaining Example:</h3>";

// Method chaining - memanggil beberapa method dalam satu baris
$hasil1 = $builder->tambah("Hello")->spasi()->tambah("World")->hurufBesar()->dapatkan();
echo "Hasil 1: $hasil1<br>";

$hasil2 = $builder->reset()
    ->tambah("PHP")
    ->spasi()
    ->tambah("OOP")
    ->spasi()
    ->tambah("Course")
    ->hurufKecil()
    ->dapatkan();
echo "Hasil 2: $hasil2<br>";

$hasil3 = $builder->reset()
    ->tambah("Belajar")
    ->spasi()
    ->tambah("Programming")
    ->balik()
    ->dapatkan();
echo "Hasil 3: $hasil3<br>";

echo "<hr>";

echo "<h2>Contoh 4: Getter dan Setter dengan Validasi</h2>";

class Produk
{
    private $nama;
    private $harga;
    private $stok;
    private $kategori;
    private $diskon = 0;

    public function __construct($nama, $harga, $stok, $kategori)
    {
        $this->setNama($nama);
        $this->setHarga($harga);
        $this->setStok($stok);
        $this->setKategori($kategori);
    }

    // Getter methods
    public function getNama()
    {
        return $this->nama;
    }

    public function getHarga()
    {
        return $this->harga;
    }

    public function getStok()
    {
        return $this->stok;
    }

    public function getKategori()
    {
        return $this->kategori;
    }

    public function getDiskon()
    {
        return $this->diskon;
    }

    // Setter methods dengan validasi
    public function setNama($nama)
    {
        if (empty($nama)) {
            throw new Exception("Nama produk tidak boleh kosong");
        }
        $this->nama = $nama;
    }

    public function setHarga($harga)
    {
        if (!is_numeric($harga) || $harga < 0) {
            throw new Exception("Harga harus berupa angka positif");
        }
        $this->harga = $harga;
    }

    public function setStok($stok)
    {
        if (!is_numeric($stok) || $stok < 0) {
            throw new Exception("Stok harus berupa angka positif");
        }
        $this->stok = $stok;
    }

    public function setKategori($kategori)
    {
        $kategoriValid = ['elektronik', 'pakaian', 'makanan', 'buku', 'olahraga'];
        if (!in_array(strtolower($kategori), $kategoriValid)) {
            throw new Exception("Kategori tidak valid. Pilihan: " . implode(', ', $kategoriValid));
        }
        $this->kategori = $kategori;
    }

    public function setDiskon($diskon)
    {
        if (!is_numeric($diskon) || $diskon < 0 || $diskon > 100) {
            throw new Exception("Diskon harus antara 0-100 persen");
        }
        $this->diskon = $diskon;
    }

    // Method tambahan
    public function getHargaSetelahDiskon()
    {
        return $this->harga - ($this->harga * $this->diskon / 100);
    }

    public function tambahStok($jumlah)
    {
        if ($jumlah > 0) {
            $this->stok += $jumlah;
            return "Stok berhasil ditambah. Stok sekarang: {$this->stok}";
        }
        return "Jumlah harus lebih dari 0";
    }

    public function kurangiStok($jumlah)
    {
        if ($jumlah > 0 && $jumlah <= $this->stok) {
            $this->stok -= $jumlah;
            return "Stok berhasil dikurangi. Stok sekarang: {$this->stok}";
        }
        return "Stok tidak mencukupi atau jumlah tidak valid";
    }

    public function tampilkanInfo()
    {
        return "Produk: {$this->nama}<br>" .
            "Kategori: {$this->kategori}<br>" .
            "Harga: Rp " . number_format($this->harga, 0, ',', '.') . "<br>" .
            "Diskon: {$this->diskon}%<br>" .
            "Harga setelah diskon: Rp " . number_format($this->getHargaSetelahDiskon(), 0, ',', '.') . "<br>" .
            "Stok: {$this->stok}<br>";
    }
}

try {
    $produk1 = new Produk("Laptop Gaming", 15000000, 5, "elektronik");
    $produk1->setDiskon(10);

    echo "<h3>Informasi Produk:</h3>";
    echo $produk1->tampilkanInfo();

    echo "<h3>Operasi Stok:</h3>";
    echo $produk1->tambahStok(3) . "<br>";
    echo $produk1->kurangiStok(2) . "<br>";
    echo "Stok akhir: " . $produk1->getStok() . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 5: Static Methods untuk Utility Functions</h2>";

class MathHelper
{
    // Static methods tidak memerlukan instance untuk dipanggil
    public static function faktorial($n)
    {
        if ($n <= 1) return 1;
        return $n * self::faktorial($n - 1);
    }

    public static function isPrima($n)
    {
        if ($n <= 1) return false;
        if ($n <= 3) return true;
        if ($n % 2 == 0 || $n % 3 == 0) return false;

        for ($i = 5; $i * $i <= $n; $i += 6) {
            if ($n % $i == 0 || $n % ($i + 2) == 0) {
                return false;
            }
        }
        return true;
    }

    public static function fibonacci($n)
    {
        if ($n <= 1) return $n;
        return self::fibonacci($n - 1) + self::fibonacci($n - 2);
    }

    public static function konversiSuhu($nilai, $dari, $ke)
    {
        // Konversi ke Celsius dulu
        switch (strtolower($dari)) {
            case 'f':
            case 'fahrenheit':
                $celsius = ($nilai - 32) * 5 / 9;
                break;
            case 'k':
            case 'kelvin':
                $celsius = $nilai - 273.15;
                break;
            case 'c':
            case 'celsius':
                $celsius = $nilai;
                break;
            default:
                return "Unit suhu tidak valid";
        }

        // Konversi dari Celsius ke unit yang diminta
        switch (strtolower($ke)) {
            case 'f':
            case 'fahrenheit':
                return ($celsius * 9 / 5) + 32;
            case 'k':
            case 'kelvin':
                return $celsius + 273.15;
            case 'c':
            case 'celsius':
                return $celsius;
            default:
                return "Unit suhu tidak valid";
        }
    }
}

echo "<h3>Static Methods Example:</h3>";
echo "Faktorial 5: " . MathHelper::faktorial(5) . "<br>";
echo "Apakah 17 prima? " . (MathHelper::isPrima(17) ? "Ya" : "Tidak") . "<br>";
echo "Fibonacci ke-8: " . MathHelper::fibonacci(8) . "<br>";
echo "100°F ke Celsius: " . MathHelper::konversiSuhu(100, 'f', 'c') . "°C<br>";
echo "0°C ke Kelvin: " . MathHelper::konversiSuhu(0, 'c', 'k') . "K<br>";

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Dari contoh-contoh di atas, kita dapat melihat:</p>";
echo "<ul>";
echo "<li><strong>Instance Properties:</strong> Setiap object memiliki nilai yang independen</li>";
echo "<li><strong>Static Properties:</strong> Dibagi oleh semua instance dari class yang sama</li>";
echo "<li><strong>Instance Methods:</strong> Dapat mengakses instance properties menggunakan \$this</li>";
echo "<li><strong>Static Methods:</strong> Tidak dapat mengakses instance properties, cocok untuk utility functions</li>";
echo "<li><strong>Getter/Setter:</strong> Memberikan kontrol akses dan validasi terhadap properties</li>";
echo "<li><strong>Method Chaining:</strong> Memungkinkan pemanggilan method berurutan dalam satu baris</li>";
echo "<li><strong>Parameter dan Return Value:</strong> Methods dapat menerima input dan mengembalikan output</li>";
echo "</ul>";

<?php

/**
 * Pertemuan 3: Constructor dan Destructor
 * Contoh implementasi constructor dan destructor dalam OOP PHP
 */

echo "<h1>Pertemuan 3: Constructor dan Destructor</h1>";

echo "<h2>Contoh 1: Constructor Dasar</h2>";

class Mobil
{
    private $merk;
    private $model;
    private $tahun;
    private $warna;
    private $harga;

    // Constructor dengan parameter dan default values
    public function __construct($merk, $model, $tahun, $warna = "putih", $harga = 0)
    {
        echo "Membuat object mobil baru...<br>";

        // Validasi parameter
        if (empty($merk) || empty($model)) {
            throw new InvalidArgumentException("Merk dan model tidak boleh kosong");
        }

        if ($tahun < 1900 || $tahun > date('Y') + 1) {
            throw new InvalidArgumentException("Tahun tidak valid");
        }

        if ($harga < 0) {
            throw new InvalidArgumentException("Harga tidak boleh negatif");
        }

        // Inisialisasi properti
        $this->merk = $merk;
        $this->model = $model;
        $this->tahun = $tahun;
        $this->warna = $warna;
        $this->harga = $harga;

        echo "Mobil {$this->merk} {$this->model} berhasil dibuat!<br>";
    }

    // Destructor
    public function __destruct()
    {
        echo "Mobil {$this->merk} {$this->model} dihancurkan dari memori.<br>";
    }

    // Getter methods
    public function getInfo()
    {
        return "{$this->merk} {$this->model} ({$this->tahun}) - {$this->warna} - Rp " .
            number_format($this->harga, 0, ',', '.');
    }

    public function getMerk()
    {
        return $this->merk;
    }

    public function getModel()
    {
        return $this->model;
    }
}

echo "<h3>Membuat Object Mobil:</h3>";

try {
    $mobil1 = new Mobil("Toyota", "Avanza", 2022, "silver", 250000000);
    echo "Info: " . $mobil1->getInfo() . "<br><br>";

    $mobil2 = new Mobil("Honda", "Jazz", 2021); // Menggunakan default values
    echo "Info: " . $mobil2->getInfo() . "<br><br>";

    // Contoh error handling
    // $mobil3 = new Mobil("", "Civic", 2023); // Akan throw exception

} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 2: Static Factory Methods (Constructor Overloading Simulation)</h2>";

class User
{
    private $id;
    private $name;
    private $email;
    private $role;
    private $createdAt;

    // Private constructor untuk mengontrol instantiation
    private function __construct($name, $email, $role = "user")
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->createdAt = date('Y-m-d H:i:s');

        echo "User '{$this->name}' dibuat dengan role '{$this->role}'<br>";
    }

    public function __destruct()
    {
        echo "User '{$this->name}' (ID: {$this->id}) dihancurkan<br>";
    }

    // Factory method untuk membuat user dari array
    public static function createFromArray($userData)
    {
        $name = $userData['name'] ?? '';
        $email = $userData['email'] ?? '';
        $role = $userData['role'] ?? 'user';

        if (empty($name) || empty($email)) {
            throw new InvalidArgumentException("Name dan email wajib diisi");
        }

        return new self($name, $email, $role);
    }

    // Factory method untuk membuat admin
    public static function createAdmin($name, $email)
    {
        return new self($name, $email, "admin");
    }

    // Factory method untuk membuat guest user
    public static function createGuest()
    {
        return new self("Guest User", "guest@example.com", "guest");
    }

    // Factory method untuk membuat user dari database (simulasi)
    public static function createFromDatabase($userId)
    {
        // Simulasi data dari database
        $userData = [
            '1' => ['name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'user'],
            '2' => ['name' => 'Jane Admin', 'email' => 'jane@example.com', 'role' => 'admin'],
        ];

        if (!isset($userData[$userId])) {
            throw new Exception("User dengan ID $userId tidak ditemukan");
        }

        $data = $userData[$userId];
        echo "Loading user dari database...<br>";
        return new self($data['name'], $data['email'], $data['role']);
    }

    public function getInfo()
    {
        return "ID: {$this->id}<br>" .
            "Name: {$this->name}<br>" .
            "Email: {$this->email}<br>" .
            "Role: {$this->role}<br>" .
            "Created: {$this->createdAt}<br>";
    }
}

echo "<h3>Berbagai Cara Membuat User:</h3>";

try {
    // Menggunakan factory method dari array
    $user1 = User::createFromArray([
        'name' => 'Alice Johnson',
        'email' => 'alice@example.com',
        'role' => 'moderator'
    ]);
    echo $user1->getInfo() . "<br>";

    // Menggunakan factory method untuk admin
    $admin = User::createAdmin("Bob Smith", "bob@admin.com");
    echo $admin->getInfo() . "<br>";

    // Menggunakan factory method untuk guest
    $guest = User::createGuest();
    echo $guest->getInfo() . "<br>";

    // Menggunakan factory method dari database
    $dbUser = User::createFromDatabase('1');
    echo $dbUser->getInfo() . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 3: Constructor dengan Dependency Injection</h2>";

// Simulasi database connection
class DatabaseConnection
{
    private $host;
    private $database;

    public function __construct($host, $database)
    {
        $this->host = $host;
        $this->database = $database;
        echo "Koneksi database ke {$host}/{$database} dibuat<br>";
    }

    public function __destruct()
    {
        echo "Koneksi database ke {$this->host}/{$this->database} ditutup<br>";
    }

    public function query($sql)
    {
        return "Executing: $sql on {$this->database}";
    }
}

// Logger class
class Logger
{
    private $logFile;
    private $level;

    public function __construct($logFile, $level = "INFO")
    {
        $this->logFile = $logFile;
        $this->level = $level;
        echo "Logger dibuat untuk file: {$logFile}<br>";
    }

    public function __destruct()
    {
        echo "Logger untuk {$this->logFile} ditutup<br>";
    }

    public function log($message, $level = null)
    {
        $level = $level ?? $this->level;
        return "[{$level}] " . date('Y-m-d H:i:s') . " - $message";
    }
}

// Service class dengan dependency injection
class UserService
{
    private $database;
    private $logger;
    private $cacheEnabled;

    public function __construct(DatabaseConnection $db, Logger $logger, $cacheEnabled = true)
    {
        $this->database = $db;
        $this->logger = $logger;
        $this->cacheEnabled = $cacheEnabled;

        echo "UserService dibuat dengan dependencies<br>";
        echo $this->logger->log("UserService initialized") . "<br>";
    }

    public function __destruct()
    {
        echo $this->logger->log("UserService shutting down") . "<br>";
        echo "UserService dihancurkan<br>";
    }

    public function findUser($id)
    {
        $query = "SELECT * FROM users WHERE id = $id";
        $result = $this->database->query($query);
        echo $this->logger->log("User $id found") . "<br>";
        return $result;
    }

    public function createUser($name, $email)
    {
        $query = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
        $result = $this->database->query($query);
        echo $this->logger->log("User $name created") . "<br>";
        return $result;
    }
}

echo "<h3>Dependency Injection Example:</h3>";

// Membuat dependencies
$db = new DatabaseConnection("localhost", "myapp_db");
$logger = new Logger("/var/log/app.log", "DEBUG");

// Inject dependencies ke service
$userService = new UserService($db, $logger, true);

// Menggunakan service
echo $userService->findUser(123) . "<br>";
echo $userService->createUser("Charlie Brown", "charlie@example.com") . "<br>";

echo "<hr>";

echo "<h2>Contoh 4: Constructor Chaining dengan Inheritance</h2>";

// Parent class
class Vehicle
{
    protected $brand;
    protected $year;
    protected $fuelType;

    public function __construct($brand, $year, $fuelType = "gasoline")
    {
        echo "Vehicle constructor called<br>";

        if (empty($brand)) {
            throw new InvalidArgumentException("Brand tidak boleh kosong");
        }

        $this->brand = $brand;
        $this->year = $year;
        $this->fuelType = $fuelType;

        echo "Vehicle {$brand} ({$year}) dibuat<br>";
    }

    public function __destruct()
    {
        echo "Vehicle {$this->brand} dihancurkan<br>";
    }

    public function getBasicInfo()
    {
        return "{$this->brand} ({$this->year}) - {$this->fuelType}";
    }
}

// Child class
class Car extends Vehicle
{
    private $doors;
    private $transmission;

    public function __construct($brand, $year, $doors = 4, $transmission = "manual", $fuelType = "gasoline")
    {
        echo "Car constructor called<br>";

        // Memanggil constructor parent
        parent::__construct($brand, $year, $fuelType);

        // Inisialisasi properti child
        $this->doors = $doors;
        $this->transmission = $transmission;

        echo "Car dengan {$doors} pintu dan transmisi {$transmission} siap<br>";
    }

    public function __destruct()
    {
        echo "Car {$this->brand} dengan {$this->doors} pintu dihancurkan<br>";
        // Parent destructor akan dipanggil otomatis
    }

    public function getDetailInfo()
    {
        return $this->getBasicInfo() . " - {$this->doors} doors, {$this->transmission}";
    }
}

// Grandchild class
class ElectricCar extends Car
{
    private $batteryCapacity;
    private $chargingTime;

    public function __construct($brand, $year, $batteryCapacity, $chargingTime = 8, $doors = 4)
    {
        echo "ElectricCar constructor called<br>";

        // Memanggil constructor parent dengan fuel type electric
        parent::__construct($brand, $year, $doors, "automatic", "electric");

        $this->batteryCapacity = $batteryCapacity;
        $this->chargingTime = $chargingTime;

        echo "Electric car dengan baterai {$batteryCapacity}kWh siap<br>";
    }

    public function __destruct()
    {
        echo "ElectricCar {$this->brand} dengan baterai {$this->batteryCapacity}kWh dihancurkan<br>";
    }

    public function getElectricInfo()
    {
        return $this->getDetailInfo() . " - Battery: {$this->batteryCapacity}kWh, Charging: {$this->chargingTime}h";
    }
}

echo "<h3>Constructor Chaining Example:</h3>";

try {
    echo "<strong>Membuat Vehicle:</strong><br>";
    $vehicle = new Vehicle("Generic", 2020, "diesel");
    echo $vehicle->getBasicInfo() . "<br><br>";

    echo "<strong>Membuat Car:</strong><br>";
    $car = new Car("Honda", 2022, 4, "automatic", "gasoline");
    echo $car->getDetailInfo() . "<br><br>";

    echo "<strong>Membuat ElectricCar:</strong><br>";
    $electricCar = new ElectricCar("Tesla", 2023, 75, 6, 4);
    echo $electricCar->getElectricInfo() . "<br><br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 5: Resource Management dengan Constructor/Destructor</h2>";

class FileManager
{
    private $filePath;
    private $fileHandle;
    private $mode;
    private $isOpen = false;

    public function __construct($filePath, $mode = 'r')
    {
        $this->filePath = $filePath;
        $this->mode = $mode;

        echo "FileManager dibuat untuk file: {$filePath}<br>";

        // Validasi file path
        if (empty($filePath)) {
            throw new InvalidArgumentException("File path tidak boleh kosong");
        }

        // Untuk mode read, cek apakah file exists
        if ($mode === 'r' && !file_exists($filePath)) {
            throw new Exception("File tidak ditemukan: {$filePath}");
        }

        // Buka file
        $this->openFile();
    }

    public function __destruct()
    {
        echo "FileManager destructor called<br>";
        $this->closeFile();
        echo "FileManager untuk {$this->filePath} dihancurkan<br>";
    }

    private function openFile()
    {
        if (!$this->isOpen) {
            $this->fileHandle = fopen($this->filePath, $this->mode);
            if ($this->fileHandle === false) {
                throw new Exception("Gagal membuka file: {$this->filePath}");
            }
            $this->isOpen = true;
            echo "File {$this->filePath} berhasil dibuka dengan mode {$this->mode}<br>";
        }
    }

    public function closeFile()
    {
        if ($this->isOpen && $this->fileHandle) {
            fclose($this->fileHandle);
            $this->isOpen = false;
            echo "File {$this->filePath} ditutup<br>";
        }
    }

    public function readLine()
    {
        if (!$this->isOpen) {
            throw new Exception("File belum dibuka");
        }

        $line = fgets($this->fileHandle);
        return $line !== false ? trim($line) : null;
    }

    public function writeLine($content)
    {
        if (!$this->isOpen) {
            throw new Exception("File belum dibuka");
        }

        if (strpos($this->mode, 'w') === false && strpos($this->mode, 'a') === false) {
            throw new Exception("File tidak dibuka dalam mode write");
        }

        fwrite($this->fileHandle, $content . "\n");
        echo "Data berhasil ditulis ke file<br>";
    }

    public function getFileInfo()
    {
        return "File: {$this->filePath}, Mode: {$this->mode}, Status: " .
            ($this->isOpen ? "Open" : "Closed");
    }
}

echo "<h3>File Management Example:</h3>";

// Membuat file temporary untuk demo
$tempFile = sys_get_temp_dir() . '/demo_file.txt';
file_put_contents($tempFile, "Line 1\nLine 2\nLine 3\n");

try {
    echo "<strong>Membaca file:</strong><br>";
    $fileReader = new FileManager($tempFile, 'r');
    echo $fileReader->getFileInfo() . "<br>";

    // Baca beberapa baris
    for ($i = 0; $i < 3; $i++) {
        $line = $fileReader->readLine();
        if ($line !== null) {
            echo "Baca: $line<br>";
        }
    }

    echo "<br><strong>Menulis ke file:</strong><br>";
    $fileWriter = new FileManager($tempFile, 'a');
    echo $fileWriter->getFileInfo() . "<br>";
    $fileWriter->writeLine("Line 4 - Added by FileManager");
    $fileWriter->writeLine("Line 5 - Added by FileManager");

    // File akan otomatis ditutup saat destructor dipanggil

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

// Cleanup
if (file_exists($tempFile)) {
    unlink($tempFile);
    echo "<br>Temporary file dihapus<br>";
}

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Dari contoh-contoh di atas, kita dapat melihat:</p>";
echo "<ul>";
echo "<li><strong>Constructor:</strong> Method khusus untuk inisialisasi object</li>";
echo "<li><strong>Parameter Validation:</strong> Constructor adalah tempat yang baik untuk validasi</li>";
echo "<li><strong>Factory Methods:</strong> Alternatif untuk constructor overloading</li>";
echo "<li><strong>Dependency Injection:</strong> Constructor menerima dependencies dari luar</li>";
echo "<li><strong>Constructor Chaining:</strong> Child class memanggil parent constructor</li>";
echo "<li><strong>Destructor:</strong> Method untuk cleanup saat object dihancurkan</li>";
echo "<li><strong>Resource Management:</strong> Constructor/destructor untuk mengelola resources</li>";
echo "</ul>";

// Demonstrasi bahwa destructor dipanggil saat script berakhir
echo "<br><strong>Script berakhir - semua object akan dihancurkan...</strong><br>";

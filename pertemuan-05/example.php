<?php

/**
 * Pertemuan 5: Visibility (Public, Private, Protected)
 * Contoh implementasi visibility modifiers dalam OOP PHP
 */

echo "<h1>Pertemuan 5: Visibility (Public, Private, Protected)</h1>";

echo "<h2>Contoh 1: Basic Visibility Modifiers</h2>";

class VisibilityExample
{
    // Public - dapat diakses dari mana saja
    public $publicProperty = "Ini public property";

    // Private - hanya bisa diakses dari dalam class ini
    private $privateProperty = "Ini private property";

    // Protected - bisa diakses dari class ini dan child class
    protected $protectedProperty = "Ini protected property";

    public function __construct()
    {
        echo "VisibilityExample object dibuat<br>";
    }

    // Public method
    public function publicMethod()
    {
        return "Ini adalah public method";
    }

    // Private method
    private function privateMethod()
    {
        return "Ini adalah private method";
    }

    // Protected method
    protected function protectedMethod()
    {
        return "Ini adalah protected method";
    }

    // Method untuk mengakses semua properties dari dalam class
    public function accessAllProperties()
    {
        echo "<h4>Akses dari dalam class:</h4>";
        echo "Public: " . $this->publicProperty . "<br>";
        echo "Private: " . $this->privateProperty . "<br>";
        echo "Protected: " . $this->protectedProperty . "<br>";
    }

    // Method untuk memanggil semua methods dari dalam class
    public function callAllMethods()
    {
        echo "<h4>Panggil methods dari dalam class:</h4>";
        echo $this->publicMethod() . "<br>";
        echo $this->privateMethod() . "<br>";
        echo $this->protectedMethod() . "<br>";
    }
}

// Child class untuk demonstrasi protected access
class ChildVisibility extends VisibilityExample
{
    public function __construct()
    {
        parent::__construct();
        echo "ChildVisibility object dibuat<br>";
    }

    public function accessFromChild()
    {
        echo "<h4>Akses dari child class:</h4>";
        echo "Public: " . $this->publicProperty . "<br>";
        // echo "Private: " . $this->privateProperty . "<br>"; // Error! Private tidak bisa diakses
        echo "Protected: " . $this->protectedProperty . "<br>";
    }

    public function callMethodsFromChild()
    {
        echo "<h4>Panggil methods dari child class:</h4>";
        echo $this->publicMethod() . "<br>";
        // echo $this->privateMethod() . "<br>"; // Error! Private tidak bisa dipanggil
        echo $this->protectedMethod() . "<br>";
    }
}

echo "<h3>Demonstrasi Visibility:</h3>";

$obj = new VisibilityExample();

// Akses dari luar class
echo "<h4>Akses dari luar class:</h4>";
echo "Public property: " . $obj->publicProperty . "<br>";
// echo $obj->privateProperty; // Error! Private tidak bisa diakses
// echo $obj->protectedProperty; // Error! Protected tidak bisa diakses

echo "Public method: " . $obj->publicMethod() . "<br>";
// echo $obj->privateMethod(); // Error! Private tidak bisa dipanggil
// echo $obj->protectedMethod(); // Error! Protected tidak bisa dipanggil

// Akses dari dalam class
$obj->accessAllProperties();
$obj->callAllMethods();

echo "<br>";

// Child class demonstration
$child = new ChildVisibility();
$child->accessFromChild();
$child->callMethodsFromChild();

echo "<hr>";

echo "<h2>Contoh 2: Encapsulation dengan Getter dan Setter</h2>";

class BankAccount
{
    // Private properties - tidak bisa diakses langsung dari luar
    private $accountNumber;
    private $accountHolder;
    private $balance;
    private $isActive;
    private $transactionHistory;

    public function __construct($accountNumber, $accountHolder, $initialBalance = 0)
    {
        $this->accountNumber = $accountNumber;
        $this->accountHolder = $accountHolder;
        $this->balance = 0;
        $this->isActive = true;
        $this->transactionHistory = [];

        if ($initialBalance > 0) {
            $this->deposit($initialBalance, "Initial deposit");
        }

        echo "Account {$accountNumber} untuk {$accountHolder} berhasil dibuat<br>";
    }

    // Getter methods - akses read-only ke private properties
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function getAccountHolder()
    {
        return $this->accountHolder;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    // Controlled access methods - dengan business logic
    public function deposit($amount, $description = "Deposit")
    {
        if (!$this->isActive) {
            return $this->createErrorResponse("Account is not active");
        }

        if ($amount <= 0) {
            return $this->createErrorResponse("Amount must be positive");
        }

        $this->balance += $amount;
        $this->addTransaction("DEPOSIT", $amount, $description);

        return $this->createSuccessResponse("Deposit successful", $this->balance);
    }

    public function withdraw($amount, $description = "Withdrawal")
    {
        if (!$this->isActive) {
            return $this->createErrorResponse("Account is not active");
        }

        if ($amount <= 0) {
            return $this->createErrorResponse("Amount must be positive");
        }

        if ($amount > $this->balance) {
            return $this->createErrorResponse("Insufficient balance");
        }

        $this->balance -= $amount;
        $this->addTransaction("WITHDRAWAL", $amount, $description);

        return $this->createSuccessResponse("Withdrawal successful", $this->balance);
    }

    public function transfer($targetAccount, $amount, $description = "Transfer")
    {
        $withdrawResult = $this->withdraw($amount, "Transfer to " . $targetAccount->getAccountNumber());

        if ($withdrawResult['success']) {
            $depositResult = $targetAccount->deposit($amount, "Transfer from " . $this->accountNumber);

            if ($depositResult['success']) {
                return $this->createSuccessResponse("Transfer successful", $this->balance);
            } else {
                // Rollback jika deposit gagal
                $this->deposit($amount, "Transfer rollback");
                return $this->createErrorResponse("Transfer failed: " . $depositResult['message']);
            }
        }

        return $withdrawResult;
    }

    public function getTransactionHistory($limit = 10)
    {
        return array_slice($this->transactionHistory, -$limit);
    }

    public function deactivateAccount()
    {
        $this->isActive = false;
        $this->addTransaction("SYSTEM", 0, "Account deactivated");
        return "Account deactivated";
    }

    // Private helper methods - implementation details
    private function addTransaction($type, $amount, $description)
    {
        $this->transactionHistory[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'balance_after' => $this->balance
        ];
    }

    private function createSuccessResponse($message, $balance)
    {
        return [
            'success' => true,
            'message' => $message,
            'balance' => $balance
        ];
    }

    private function createErrorResponse($message)
    {
        return [
            'success' => false,
            'message' => $message,
            'balance' => $this->balance
        ];
    }
}

echo "<h3>Bank Account System:</h3>";

$account1 = new BankAccount("001-123-456", "John Doe", 1000000);
$account2 = new BankAccount("001-123-457", "Jane Smith", 500000);

echo "<br><strong>Initial State:</strong><br>";
echo "Account 1 Balance: Rp " . number_format($account1->getBalance(), 0, ',', '.') . "<br>";
echo "Account 2 Balance: Rp " . number_format($account2->getBalance(), 0, ',', '.') . "<br>";

echo "<br><strong>Transactions:</strong><br>";

// Deposit
$result = $account1->deposit(250000, "Salary deposit");
echo $result['message'] . " - New balance: Rp " . number_format($result['balance'], 0, ',', '.') . "<br>";

// Withdrawal
$result = $account1->withdraw(100000, "ATM withdrawal");
echo $result['message'] . " - New balance: Rp " . number_format($result['balance'], 0, ',', '.') . "<br>";

// Transfer
$result = $account1->transfer($account2, 200000, "Monthly allowance");
echo $result['message'] . "<br>";
echo "Account 1 Balance: Rp " . number_format($account1->getBalance(), 0, ',', '.') . "<br>";
echo "Account 2 Balance: Rp " . number_format($account2->getBalance(), 0, ',', '.') . "<br>";

// Error cases
echo "<br><strong>Error Handling:</strong><br>";
$result = $account1->withdraw(2000000); // Insufficient balance
echo $result['message'] . "<br>";

$result = $account1->deposit(-50000); // Negative amount
echo $result['message'] . "<br>";

echo "<hr>";

echo "<h2>Contoh 3: Protected dalam Inheritance</h2>";

// Base class dengan protected members
class Vehicle
{
    protected $brand;
    protected $model;
    protected $year;
    protected $engineStatus;

    // Protected constants - bisa diakses child class
    protected const MAX_SPEED_LIMIT = 200;

    public function __construct($brand, $model, $year)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->engineStatus = false;
        echo "Vehicle {$brand} {$model} ({$year}) dibuat<br>";
    }

    public function getBasicInfo()
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    // Protected method - bisa di-override dan dipanggil child class
    protected function startEngine()
    {
        if (!$this->engineStatus) {
            $this->engineStatus = true;
            return "Engine started";
        }
        return "Engine already running";
    }

    protected function stopEngine()
    {
        if ($this->engineStatus) {
            $this->engineStatus = false;
            return "Engine stopped";
        }
        return "Engine already stopped";
    }

    protected function validateSpeed($speed)
    {
        return $speed > 0 && $speed <= self::MAX_SPEED_LIMIT;
    }

    // Public method yang menggunakan protected methods
    public function drive($speed)
    {
        if (!$this->engineStatus) {
            $startResult = $this->startEngine();
            echo $startResult . "<br>";
        }

        if ($this->validateSpeed($speed)) {
            return "Driving {$this->getBasicInfo()} at {$speed} km/h";
        } else {
            return "Invalid speed: {$speed} km/h";
        }
    }
}

class Car extends Vehicle
{
    private $doors;
    private $airCondition;

    public function __construct($brand, $model, $year, $doors = 4)
    {
        parent::__construct($brand, $model, $year);
        $this->doors = $doors;
        $this->airCondition = false;
        echo "Car dengan {$doors} pintu siap<br>";
    }

    // Override protected method dengan implementasi khusus
    protected function startEngine()
    {
        $result = parent::startEngine(); // Panggil parent method
        if ($result === "Engine started") {
            return "Car engine started with ignition key";
        }
        return $result;
    }

    public function toggleAirCondition()
    {
        $this->airCondition = !$this->airCondition;
        return "Air condition " . ($this->airCondition ? "ON" : "OFF");
    }

    // Method yang menggunakan protected properties dan methods
    public function getDetailedInfo()
    {
        // Bisa akses protected properties dari parent
        return "Car: {$this->brand} {$this->model} ({$this->year}) - {$this->doors} doors";
    }

    public function performMaintenance()
    {
        echo "<h4>Car Maintenance for {$this->getBasicInfo()}:</h4>";

        // Stop engine untuk maintenance
        echo $this->stopEngine() . "<br>";

        // Simulate maintenance tasks
        echo "Checking oil level...<br>";
        echo "Inspecting tires...<br>";
        echo "Testing air condition...<br>";

        // Restart engine
        echo $this->startEngine() . "<br>";

        return "Maintenance completed";
    }
}

class Motorcycle extends Vehicle
{
    private $helmets;

    public function __construct($brand, $model, $year, $helmets = 1)
    {
        parent::__construct($brand, $model, $year);
        $this->helmets = $helmets;
        echo "Motorcycle dengan {$helmets} helm siap<br>";
    }

    // Override protected method
    protected function startEngine()
    {
        $result = parent::startEngine();
        if ($result === "Engine started") {
            return "Motorcycle engine started with kick/electric start";
        }
        return $result;
    }

    // Override dengan logic khusus motorcycle
    protected function validateSpeed($speed)
    {
        // Motorcycle bisa lebih cepat dari MAX_SPEED_LIMIT
        return $speed > 0 && $speed <= 300;
    }

    public function wheelie()
    {
        if ($this->engineStatus) {
            return "Performing wheelie with {$this->getBasicInfo()}!";
        }
        return "Engine must be started first";
    }

    public function getMotorcycleInfo()
    {
        return "Motorcycle: {$this->brand} {$this->model} ({$this->year}) - {$this->helmets} helmet(s)";
    }
}

echo "<h3>Protected Members dalam Inheritance:</h3>";

$car = new Car("Toyota", "Camry", 2023, 4);
echo $car->getDetailedInfo() . "<br>";
echo $car->drive(80) . "<br>";
echo $car->toggleAirCondition() . "<br>";
$car->performMaintenance();

echo "<br>";

$motorcycle = new Motorcycle("Honda", "CBR600RR", 2023, 2);
echo $motorcycle->getMotorcycleInfo() . "<br>";
echo $motorcycle->drive(150) . "<br>";
echo $motorcycle->wheelie() . "<br>";

// Demonstrasi bahwa protected tidak bisa diakses dari luar
echo "<br><strong>Error demonstration (commented out):</strong><br>";
echo "// \$car->brand; // Error! Protected property<br>";
echo "// \$car->startEngine(); // Error! Protected method<br>";

echo "<hr>";

echo "<h2>Contoh 4: Magic Methods untuk Property Access</h2>";

class FlexibleData
{
    // Private array untuk menyimpan data
    private $data = [];
    private $readOnlyProperties = ['id', 'created_at'];
    private $requiredProperties = ['name', 'email'];

    public function __construct($initialData = [])
    {
        // Set read-only properties
        $this->data['id'] = uniqid();
        $this->data['created_at'] = date('Y-m-d H:i:s');

        // Set initial data
        foreach ($initialData as $key => $value) {
            $this->$key = $value; // Menggunakan __set magic method
        }

        echo "FlexibleData object created with ID: {$this->data['id']}<br>";
    }

    // Magic method untuk get property
    public function __get($property)
    {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }

        throw new Exception("Property '{$property}' not found");
    }

    // Magic method untuk set property
    public function __set($property, $value)
    {
        // Check read-only properties
        if (in_array($property, $this->readOnlyProperties)) {
            throw new Exception("Property '{$property}' is read-only");
        }

        // Validation untuk specific properties
        if ($property === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        if ($property === 'age' && (!is_numeric($value) || $value < 0)) {
            throw new Exception("Age must be a positive number");
        }

        $this->data[$property] = $value;
        echo "Property '{$property}' set to '{$value}'<br>";
    }

    // Magic method untuk isset
    public function __isset($property)
    {
        return isset($this->data[$property]);
    }

    // Magic method untuk unset
    public function __unset($property)
    {
        if (in_array($property, $this->readOnlyProperties)) {
            throw new Exception("Cannot unset read-only property '{$property}'");
        }

        if (in_array($property, $this->requiredProperties)) {
            throw new Exception("Cannot unset required property '{$property}'");
        }

        unset($this->data[$property]);
        echo "Property '{$property}' removed<br>";
    }

    public function getAllData()
    {
        return $this->data;
    }

    public function validate()
    {
        foreach ($this->requiredProperties as $property) {
            if (!isset($this->data[$property]) || empty($this->data[$property])) {
                throw new Exception("Required property '{$property}' is missing or empty");
            }
        }
        return true;
    }
}

echo "<h3>Magic Methods for Property Access:</h3>";

try {
    $user = new FlexibleData([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'age' => 30
    ]);

    echo "<br><strong>Reading properties:</strong><br>";
    echo "ID: " . $user->id . "<br>";
    echo "Name: " . $user->name . "<br>";
    echo "Email: " . $user->email . "<br>";
    echo "Age: " . $user->age . "<br>";
    echo "Created: " . $user->created_at . "<br>";

    echo "<br><strong>Setting new properties:</strong><br>";
    $user->phone = "+62-123-456-789";
    $user->address = "Jakarta, Indonesia";

    echo "<br><strong>Checking if property exists:</strong><br>";
    echo "Has phone? " . (isset($user->phone) ? "Yes" : "No") . "<br>";
    echo "Has salary? " . (isset($user->salary) ? "Yes" : "No") . "<br>";

    echo "<br><strong>All data:</strong><br>";
    foreach ($user->getAllData() as $key => $value) {
        echo "{$key}: {$value}<br>";
    }

    echo "<br><strong>Error handling:</strong><br>";

    // Try to set invalid email
    try {
        $user->email = "invalid-email";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }

    // Try to modify read-only property
    try {
        $user->id = "new-id";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }

    // Try to access non-existent property
    try {
        echo $user->nonExistentProperty;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 5: Static Visibility</h2>";

class Counter
{
    // Static properties dengan berbagai visibility
    private static $instances = 0;
    protected static $globalCount = 0;
    public static $publicCount = 0;

    private $instanceId;
    private $localCount = 0;

    public function __construct()
    {
        self::$instances++;
        $this->instanceId = self::$instances;
        echo "Counter instance #{$this->instanceId} created<br>";
    }

    public function increment()
    {
        $this->localCount++;
        self::$globalCount++;
        self::$publicCount++;

        return "Instance #{$this->instanceId}: local={$this->localCount}, global=" . self::$globalCount;
    }

    // Public static method
    public static function getTotalInstances()
    {
        return self::$instances;
    }

    public static function getGlobalCount()
    {
        return self::$globalCount;
    }

    // Protected static method
    protected static function resetGlobalCount()
    {
        self::$globalCount = 0;
        echo "Global count reset to 0<br>";
    }

    // Private static method
    private static function validateInstance()
    {
        return self::$instances > 0;
    }

    public static function getStats()
    {
        if (self::validateInstance()) {
            return [
                'instances' => self::$instances,
                'global_count' => self::$globalCount,
                'public_count' => self::$publicCount
            ];
        }
        return ['error' => 'No instances created'];
    }
}

class ExtendedCounter extends Counter
{
    public static function resetCountFromChild()
    {
        // Bisa akses protected static method dari parent
        self::resetGlobalCount();

        // Tidak bisa akses private static method
        // self::validateInstance(); // Error!
    }

    public static function getParentStats()
    {
        return [
            'instances' => self::getTotalInstances(),
            'global_count' => self::getGlobalCount(),
            'public_count' => self::$publicCount // Bisa akses public static property
        ];
    }
}

echo "<h3>Static Visibility:</h3>";

echo "<strong>Creating counter instances:</strong><br>";
$counter1 = new Counter();
$counter2 = new Counter();
$counter3 = new ExtendedCounter();

echo "<br><strong>Incrementing counters:</strong><br>";
echo $counter1->increment() . "<br>";
echo $counter2->increment() . "<br>";
echo $counter1->increment() . "<br>";

echo "<br><strong>Static method calls:</strong><br>";
echo "Total instances: " . Counter::getTotalInstances() . "<br>";
echo "Global count: " . Counter::getGlobalCount() . "<br>";
echo "Public count: " . Counter::$publicCount . "<br>";

$stats = Counter::getStats();
echo "<br><strong>Statistics:</strong><br>";
foreach ($stats as $key => $value) {
    echo "{$key}: {$value}<br>";
}

echo "<br><strong>Extended counter operations:</strong><br>";
ExtendedCounter::resetCountFromChild();

$parentStats = ExtendedCounter::getParentStats();
echo "Parent stats from child:<br>";
foreach ($parentStats as $key => $value) {
    echo "{$key}: {$value}<br>";
}

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Dari contoh-contoh di atas, kita dapat melihat:</p>";
echo "<ul>";
echo "<li><strong>Public:</strong> Dapat diakses dari mana saja - gunakan untuk API public</li>";
echo "<li><strong>Private:</strong> Hanya bisa diakses dari dalam class - gunakan untuk implementation details</li>";
echo "<li><strong>Protected:</strong> Bisa diakses dari class dan child class - gunakan untuk inheritance</li>";
echo "<li><strong>Encapsulation:</strong> Menggunakan private properties dengan public getters/setters</li>";
echo "<li><strong>Data Validation:</strong> Setter methods dapat melakukan validasi sebelum mengubah data</li>";
echo "<li><strong>Magic Methods:</strong> __get, __set, __isset, __unset untuk flexible property access</li>";
echo "<li><strong>Static Visibility:</strong> Static members juga memiliki visibility rules yang sama</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Default ke private atau protected, buat public hanya jika diperlukan</li>";
echo "<li>Gunakan getter/setter untuk kontrol akses dan validasi</li>";
echo "<li>Protected untuk properties/methods yang perlu diakses child class</li>";
echo "<li>Private untuk implementation details yang tidak boleh diakses dari luar</li>";
echo "</ul>";

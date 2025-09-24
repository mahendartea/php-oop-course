<?php

/**
 * Pertemuan 9: Static Properties dan Methods
 * Contoh implementasi static dalam OOP PHP
 */

echo "<h1>Pertemuan 9: Static Properties dan Methods</h1>";

echo "<h2>Contoh 1: Basic Static Usage</h2>";

// Counter class untuk mendemonstrasikan static property
class Counter
{
    private static int $totalInstances = 0;
    private static array $instances = [];
    private int $instanceId;
    private string $name;

    public function __construct(string $name = "Unnamed")
    {
        $this->name = $name;
        $this->instanceId = ++self::$totalInstances;
        self::$instances[] = $this;

        echo "Counter instance #{$this->instanceId} '{$name}' created<br>";
        echo "Total instances: " . self::$totalInstances . "<br><br>";
    }

    // Static methods untuk mengakses static properties
    public static function getTotalInstances(): int
    {
        return self::$totalInstances;
    }

    public static function getInstancesList(): array
    {
        $list = [];
        foreach (self::$instances as $instance) {
            $list[] = [
                'id' => $instance->instanceId,
                'name' => $instance->name
            ];
        }
        return $list;
    }

    public static function resetCounter(): void
    {
        self::$totalInstances = 0;
        self::$instances = [];
        echo "Counter reset to zero<br>";
    }

    // Instance methods
    public function getInstanceId(): int
    {
        return $this->instanceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInfo(): string
    {
        return "Instance #{$this->instanceId}: {$this->name}";
    }

    public function __destruct()
    {
        echo "Counter instance #{$this->instanceId} '{$this->name}' destroyed<br>";
    }
}

echo "<h3>Testing Counter Class:</h3>";

// Test static property dan method
echo "Initial total instances: " . Counter::getTotalInstances() . "<br><br>";

$counter1 = new Counter("First Counter");
$counter2 = new Counter("Second Counter");
$counter3 = new Counter("Third Counter");

echo "Current total: " . Counter::getTotalInstances() . "<br>";

$instances = Counter::getInstancesList();
echo "Instances list:<br>";
foreach ($instances as $instance) {
    echo "- ID: {$instance['id']}, Name: {$instance['name']}<br>";
}

echo "<hr>";

echo "<h2>Contoh 2: Utility Classes dengan Static Methods</h2>";

// String utility class
class StringHelper
{
    // Static methods untuk string manipulation
    public static function slugify(string $text): string
    {
        // Convert to lowercase
        $text = strtolower($text);

        // Replace non-alphanumeric characters with hyphens
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        // Remove leading/trailing hyphens
        $text = trim($text, '-');

        return $text;
    }

    public static function truncate(string $text, int $length, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length - strlen($suffix)) . $suffix;
    }

    public static function camelCase(string $text): string
    {
        // Replace hyphens and underscores with spaces
        $text = str_replace(['-', '_'], ' ', $text);

        // Capitalize each word
        $text = ucwords($text);

        // Remove spaces
        $text = str_replace(' ', '', $text);

        // Make first letter lowercase
        return lcfirst($text);
    }

    public static function pascalCase(string $text): string
    {
        return ucfirst(self::camelCase($text));
    }

    public static function snakeCase(string $text): string
    {
        // Convert camelCase to snake_case
        $text = preg_replace('/([a-z])([A-Z])/', '$1_$2', $text);

        // Replace non-alphanumeric with underscores
        $text = preg_replace('/[^a-zA-Z0-9]+/', '_', $text);

        // Convert to lowercase and clean up
        return strtolower(trim($text, '_'));
    }

    public static function randomString(int $length = 10): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function wordCount(string $text): int
    {
        return str_word_count($text);
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) === 0;
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        return $length === 0 || substr($haystack, -$length) === $needle;
    }
}

echo "<h3>Testing StringHelper Class:</h3>";

$testString = "Hello World - PHP Programming!";
echo "Original: {$testString}<br>";
echo "Slugify: " . StringHelper::slugify($testString) . "<br>";
echo "Truncate(20): " . StringHelper::truncate($testString, 20) . "<br>";
echo "CamelCase: " . StringHelper::camelCase("hello-world-php") . "<br>";
echo "PascalCase: " . StringHelper::pascalCase("hello-world-php") . "<br>";
echo "SnakeCase: " . StringHelper::snakeCase("HelloWorldPHP") . "<br>";
echo "Random String: " . StringHelper::randomString(12) . "<br>";
echo "Word Count: " . StringHelper::wordCount($testString) . "<br>";
echo "Contains 'PHP': " . (StringHelper::contains($testString, 'PHP') ? 'Yes' : 'No') . "<br>";
echo "Starts with 'Hello': " . (StringHelper::startsWith($testString, 'Hello') ? 'Yes' : 'No') . "<br>";
echo "Ends with '!': " . (StringHelper::endsWith($testString, '!') ? 'Yes' : 'No') . "<br>";

echo "<hr>";

echo "<h2>Contoh 3: Singleton Pattern</h2>";

// Database connection singleton
class Database
{
    private static ?Database $instance = null;
    private string $connection;
    private array $config;
    private int $queryCount = 0;

    // Private constructor prevents direct instantiation
    private function __construct()
    {
        $this->config = [
            'host' => 'localhost',
            'database' => 'test_db',
            'username' => 'user',
            'password' => 'password'
        ];

        $this->connection = "Connected to {$this->config['database']} at {$this->config['host']}";
        echo "Database connection established<br>";
    }

    // Get the singleton instance
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query(string $sql): array
    {
        $this->queryCount++;
        echo "Executing query #{$this->queryCount}: {$sql}<br>";

        // Simulate query result
        return [
            'query' => $sql,
            'result' => "Query executed successfully",
            'query_number' => $this->queryCount,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function getConnection(): string
    {
        return $this->connection;
    }

    public function getQueryCount(): int
    {
        return $this->queryCount;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    private function __wakeup() {}

    // Prevent serialization
    private function __sleep() {}
}

echo "<h3>Testing Singleton Pattern:</h3>";

// Get database instances
$db1 = Database::getInstance();
echo "DB1 Connection: " . $db1->getConnection() . "<br>";

$db2 = Database::getInstance();
echo "DB2 Connection: " . $db2->getConnection() . "<br>";

// Verify they are the same instance
echo "Are DB1 and DB2 the same instance? " . ($db1 === $db2 ? 'Yes' : 'No') . "<br><br>";

// Execute some queries
$db1->query("SELECT * FROM users");
$db2->query("SELECT * FROM products");
$db1->query("INSERT INTO logs (message) VALUES ('test')");

echo "Total queries executed: " . $db1->getQueryCount() . "<br>";

echo "<hr>";

echo "<h2>Contoh 4: Late Static Binding (self vs static)</h2>";

// Base class demonstrating self vs static
abstract class Model
{
    protected static string $table = 'models';
    protected static array $columns = ['id'];

    // Using self:: - early binding
    public static function getTableSelf(): string
    {
        return self::$table;  // Always returns 'models'
    }

    // Using static:: - late static binding
    public static function getTableStatic(): string
    {
        return static::$table;  // Returns the actual child class table
    }

    public static function find(int $id): string
    {
        $table = static::$table;  // Late static binding
        return "SELECT * FROM {$table} WHERE id = {$id}";
    }

    public static function getColumns(): array
    {
        return static::$columns;
    }

    public static function create(array $data): string
    {
        $table = static::$table;
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";

        return "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
    }

    public static function getModelInfo(): array
    {
        return [
            'table_self' => self::$table,      // Always 'models'
            'table_static' => static::$table,  // Actual child table
            'columns' => static::$columns,
            'class' => static::class
        ];
    }
}

class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['id', 'name', 'email', 'created_at'];
}

class Product extends Model
{
    protected static string $table = 'products';
    protected static array $columns = ['id', 'name', 'price', 'category', 'stock'];
}

class Order extends Model
{
    protected static string $table = 'orders';
    protected static array $columns = ['id', 'user_id', 'total', 'status', 'created_at'];
}

echo "<h3>Testing Late Static Binding:</h3>";

echo "<h4>Self vs Static Comparison:</h4>";
echo "User::getTableSelf(): " . User::getTableSelf() . "<br>";
echo "User::getTableStatic(): " . User::getTableStatic() . "<br>";
echo "Product::getTableSelf(): " . Product::getTableSelf() . "<br>";
echo "Product::getTableStatic(): " . Product::getTableStatic() . "<br><br>";

echo "<h4>Model Operations:</h4>";
echo "User::find(1): " . User::find(1) . "<br>";
echo "Product::find(5): " . Product::find(5) . "<br>";
echo "Order::find(10): " . Order::find(10) . "<br><br>";

echo "<h4>Create Operations:</h4>";
$userData = ['name' => 'John Doe', 'email' => 'john@example.com'];
echo "User::create(): " . User::create($userData) . "<br>";

$productData = ['name' => 'Laptop', 'price' => '999.99', 'category' => 'Electronics'];
echo "Product::create(): " . Product::create($productData) . "<br><br>";

echo "<h4>Model Information:</h4>";
$models = [User::class, Product::class, Order::class];
foreach ($models as $model) {
    $info = $model::getModelInfo();
    echo "{$model}:<br>";
    echo "- Table (self): {$info['table_self']}<br>";
    echo "- Table (static): {$info['table_static']}<br>";
    echo "- Columns: " . implode(', ', $info['columns']) . "<br>";
    echo "- Class: {$info['class']}<br><br>";
}

echo "<hr>";

echo "<h2>Contoh 5: Factory Pattern dengan Static Methods</h2>";

// Abstract Vehicle class
abstract class Vehicle
{
    protected string $brand;
    protected string $model;
    protected string $type;

    public function __construct(string $brand, string $model)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->type = static::class;
    }

    abstract public function start(): string;
    abstract public function stop(): string;

    public function getInfo(): string
    {
        return "{$this->type}: {$this->brand} {$this->model}";
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}

class Car extends Vehicle
{
    private int $doors;

    public function __construct(string $brand, string $model, int $doors = 4)
    {
        parent::__construct($brand, $model);
        $this->doors = $doors;
    }

    public function start(): string
    {
        return "Car {$this->brand} {$this->model} engine started with key";
    }

    public function stop(): string
    {
        return "Car {$this->brand} {$this->model} engine stopped";
    }

    public function getDoors(): int
    {
        return $this->doors;
    }
}

class Motorcycle extends Vehicle
{
    private int $engineCC;

    public function __construct(string $brand, string $model, int $engineCC = 150)
    {
        parent::__construct($brand, $model);
        $this->engineCC = $engineCC;
    }

    public function start(): string
    {
        return "Motorcycle {$this->brand} {$this->model} started with kick/button";
    }

    public function stop(): string
    {
        return "Motorcycle {$this->brand} {$this->model} engine stopped";
    }

    public function getEngineCC(): int
    {
        return $this->engineCC;
    }
}

class Truck extends Vehicle
{
    private float $loadCapacity;

    public function __construct(string $brand, string $model, float $loadCapacity = 1000)
    {
        parent::__construct($brand, $model);
        $this->loadCapacity = $loadCapacity;
    }

    public function start(): string
    {
        return "Truck {$this->brand} {$this->model} diesel engine started";
    }

    public function stop(): string
    {
        return "Truck {$this->brand} {$this->model} engine stopped";
    }

    public function getLoadCapacity(): float
    {
        return $this->loadCapacity;
    }
}

// Vehicle Factory with static methods
class VehicleFactory
{
    private static array $vehicleTypes = [
        'car' => Car::class,
        'motorcycle' => Motorcycle::class,
        'truck' => Truck::class
    ];

    public static function create(string $type, string $brand, string $model, ...$args): Vehicle
    {
        $type = strtolower($type);

        if (!isset(self::$vehicleTypes[$type])) {
            throw new InvalidArgumentException("Unknown vehicle type: {$type}");
        }

        $className = self::$vehicleTypes[$type];
        return new $className($brand, $model, ...$args);
    }

    public static function createCar(string $brand, string $model, int $doors = 4): Car
    {
        return new Car($brand, $model, $doors);
    }

    public static function createMotorcycle(string $brand, string $model, int $engineCC = 150): Motorcycle
    {
        return new Motorcycle($brand, $model, $engineCC);
    }

    public static function createTruck(string $brand, string $model, float $loadCapacity = 1000): Truck
    {
        return new Truck($brand, $model, $loadCapacity);
    }

    public static function getSupportedTypes(): array
    {
        return array_keys(self::$vehicleTypes);
    }

    public static function registerVehicleType(string $type, string $className): void
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class {$className} does not exist");
        }

        if (!is_subclass_of($className, Vehicle::class)) {
            throw new InvalidArgumentException("Class {$className} must extend Vehicle");
        }

        self::$vehicleTypes[strtolower($type)] = $className;
    }
}

echo "<h3>Testing Vehicle Factory:</h3>";

try {
    // Create vehicles using generic factory method
    $car = VehicleFactory::create('car', 'Toyota', 'Camry', 4);
    $bike = VehicleFactory::create('motorcycle', 'Honda', 'CBR600RR', 600);
    $truck = VehicleFactory::create('truck', 'Volvo', 'FH16', 2500);

    $vehicles = [$car, $bike, $truck];

    echo "<h4>Created Vehicles:</h4>";
    foreach ($vehicles as $vehicle) {
        echo $vehicle->getInfo() . "<br>";
        echo "- " . $vehicle->start() . "<br>";
        echo "- " . $vehicle->stop() . "<br>";

        // Show specific properties
        if ($vehicle instanceof Car) {
            echo "- Doors: " . $vehicle->getDoors() . "<br>";
        } elseif ($vehicle instanceof Motorcycle) {
            echo "- Engine: " . $vehicle->getEngineCC() . "CC<br>";
        } elseif ($vehicle instanceof Truck) {
            echo "- Load capacity: " . $vehicle->getLoadCapacity() . "kg<br>";
        }
        echo "<br>";
    }

    // Create vehicles using specific factory methods
    echo "<h4>Using Specific Factory Methods:</h4>";
    $sportsCar = VehicleFactory::createCar('Ferrari', 'F40', 2);
    $sportsBike = VehicleFactory::createMotorcycle('Kawasaki', 'Ninja ZX-10R', 998);
    $heavyTruck = VehicleFactory::createTruck('Mercedes', 'Actros', 5000);

    echo $sportsCar->getInfo() . " - Doors: " . $sportsCar->getDoors() . "<br>";
    echo $sportsBike->getInfo() . " - Engine: " . $sportsBike->getEngineCC() . "CC<br>";
    echo $heavyTruck->getInfo() . " - Capacity: " . $heavyTruck->getLoadCapacity() . "kg<br><br>";

    echo "Supported vehicle types: " . implode(', ', VehicleFactory::getSupportedTypes()) . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 6: Configuration dan Registry Pattern</h2>";

// Configuration class dengan static properties
class Config
{
    private static array $config = [];
    private static bool $loaded = false;

    // Default configuration
    private static array $defaults = [
        'app' => [
            'name' => 'My PHP App',
            'version' => '1.0.0',
            'debug' => false,
            'timezone' => 'UTC'
        ],
        'database' => [
            'host' => 'localhost',
            'port' => 3306,
            'username' => 'root',
            'password' => '',
            'database' => 'app_db'
        ],
        'cache' => [
            'driver' => 'file',
            'ttl' => 3600,
            'path' => '/tmp/cache'
        ]
    ];

    public static function load(array $config = []): void
    {
        if (self::$loaded) {
            return;
        }

        // Merge with defaults
        self::$config = array_merge_recursive(self::$defaults, $config);
        self::$loaded = true;

        echo "Configuration loaded<br>";
    }

    public static function get(string $key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public static function set(string $key, $value): void
    {
        if (!self::$loaded) {
            self::load();
        }

        $keys = explode('.', $key);
        $config = &self::$config;

        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
    }

    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    public static function all(): array
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config;
    }

    public static function reset(): void
    {
        self::$config = [];
        self::$loaded = false;
    }
}

// Registry pattern for storing objects
class Registry
{
    private static array $instances = [];
    private static array $singletons = [];

    public static function set(string $key, $value): void
    {
        self::$instances[$key] = $value;
        echo "Registry: '{$key}' stored<br>";
    }

    public static function get(string $key, $default = null)
    {
        return self::$instances[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset(self::$instances[$key]);
    }

    public static function remove(string $key): void
    {
        unset(self::$instances[$key]);
        echo "Registry: '{$key}' removed<br>";
    }

    public static function singleton(string $key, callable $factory)
    {
        if (!isset(self::$singletons[$key])) {
            self::$singletons[$key] = $factory();
            echo "Registry: Singleton '{$key}' created<br>";
        }

        return self::$singletons[$key];
    }

    public static function all(): array
    {
        return self::$instances;
    }

    public static function keys(): array
    {
        return array_keys(self::$instances);
    }

    public static function clear(): void
    {
        self::$instances = [];
        self::$singletons = [];
        echo "Registry cleared<br>";
    }

    public static function count(): int
    {
        return count(self::$instances);
    }
}

echo "<h3>Testing Configuration:</h3>";

// Load custom configuration
$customConfig = [
    'app' => [
        'name' => 'PHP OOP Course',
        'debug' => true
    ],
    'database' => [
        'username' => 'admin',
        'password' => 'secret123'
    ]
];

Config::load($customConfig);

echo "App name: " . Config::get('app.name') . "<br>";
echo "Debug mode: " . (Config::get('app.debug') ? 'Enabled' : 'Disabled') . "<br>";
echo "Database host: " . Config::get('database.host') . "<br>";
echo "Database username: " . Config::get('database.username') . "<br>";
echo "Cache TTL: " . Config::get('cache.ttl') . " seconds<br>";
echo "Non-existent key: " . (Config::get('nonexistent.key', 'DEFAULT') ?: 'DEFAULT') . "<br><br>";

// Set new configuration
Config::set('api.key', 'abc123456');
Config::set('api.url', 'https://api.example.com');

echo "API key: " . Config::get('api.key') . "<br>";
echo "API URL: " . Config::get('api.url') . "<br><br>";

echo "<h3>Testing Registry:</h3>";

// Store various objects in registry
Registry::set('database', Database::getInstance());
Registry::set('config', ['app' => 'test']);
Registry::set('version', '2.0.0');

echo "Registry count: " . Registry::count() . "<br>";
echo "Registry keys: " . implode(', ', Registry::keys()) . "<br>";
echo "Has database: " . (Registry::has('database') ? 'Yes' : 'No') . "<br>";
echo "Version: " . Registry::get('version') . "<br><br>";

// Singleton pattern in registry
$logger = Registry::singleton('logger', function () {
    return new class {
        private array $logs = [];

        public function log(string $message): void
        {
            $this->logs[] = date('Y-m-d H:i:s') . ': ' . $message;
        }

        public function getLogs(): array
        {
            return $this->logs;
        }
    };
});

$logger->log('First log entry');

// Get same singleton instance
$sameLogger = Registry::singleton('logger', function () {
    return "This won't be called";
});

$sameLogger->log('Second log entry');

echo "Logger logs: " . implode(', ', $logger->getLogs()) . "<br>";
echo "Are loggers the same? " . ($logger === $sameLogger ? 'Yes' : 'No') . "<br>";

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Static properties dan methods memberikan cara untuk:</p>";
echo "<ul>";
echo "<li><strong>Shared State:</strong> Data yang dibagi semua instance</li>";
echo "<li><strong>Utility Functions:</strong> Helper methods tanpa perlu instantiation</li>";
echo "<li><strong>Design Patterns:</strong> Singleton, Factory, Registry patterns</li>";
echo "<li><strong>Late Static Binding:</strong> Polymorphic behavior di static context</li>";
echo "<li><strong>Memory Efficiency:</strong> Satu copy untuk semua instance</li>";
echo "<li><strong>Global Access:</strong> Akses tanpa perlu object instance</li>";
echo "</ul>";

echo "<br><strong>Key Points:</strong><br>";
echo "<ul>";
echo "<li>Gunakan <code>self::</code> untuk early binding, <code>static::</code> untuk late binding</li>";
echo "<li>Static methods tidak bisa akses <code>\$this</code> dan instance members</li>";
echo "<li>Static properties shared oleh semua instance dari class yang sama</li>";
echo "<li>Cocok untuk utility functions, configuration, dan design patterns</li>";
echo "<li>Inheritance tetap berlaku untuk static members</li>";
echo "</ul>";

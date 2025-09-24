<?php

/**
 * Pertemuan 6: Abstract Class dan Method
 * Contoh implementasi abstract class dan abstract method dalam OOP PHP
 */

echo "<h1>Pertemuan 6: Abstract Class dan Method</h1>";

echo "<h2>Contoh 1: Basic Abstract Class dan Method</h2>";

// Abstract class untuk Shape
abstract class Shape
{
    protected $name;
    protected $color;

    public function __construct($name, $color = "black")
    {
        $this->name = $name;
        $this->color = $color;
        echo "Shape '{$name}' dengan warna {$color} dibuat<br>";
    }

    // Concrete method - implementasi sudah ada
    public function getName()
    {
        return $this->name;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
        echo "Warna {$this->name} diubah menjadi {$color}<br>";
    }

    // Abstract methods - harus diimplementasikan di child class
    abstract public function calculateArea();
    abstract public function calculatePerimeter();
    abstract public function draw();

    // Template method menggunakan abstract methods
    public function getShapeInfo()
    {
        return "Shape: {$this->getName()}<br>" .
            "Color: {$this->getColor()}<br>" .
            "Area: " . $this->calculateArea() . "<br>" .
            "Perimeter: " . $this->calculatePerimeter() . "<br>" .
            "Drawing: " . $this->draw() . "<br>";
    }
}

// Concrete class - implementasi Circle
class Circle extends Shape
{
    private $radius;

    public function __construct($radius, $color = "black")
    {
        parent::__construct("Circle", $color);
        $this->radius = $radius;
        echo "Circle dengan radius {$radius} siap<br>";
    }

    // Implementasi abstract method
    public function calculateArea()
    {
        return round(pi() * pow($this->radius, 2), 2);
    }

    public function calculatePerimeter()
    {
        return round(2 * pi() * $this->radius, 2);
    }

    public function draw()
    {
        return "Drawing a {$this->color} circle with radius {$this->radius}";
    }

    // Method khusus Circle
    public function getRadius()
    {
        return $this->radius;
    }

    public function getDiameter()
    {
        return $this->radius * 2;
    }
}

// Concrete class - implementasi Rectangle
class Rectangle extends Shape
{
    private $width;
    private $height;

    public function __construct($width, $height, $color = "black")
    {
        parent::__construct("Rectangle", $color);
        $this->width = $width;
        $this->height = $height;
        echo "Rectangle {$width}x{$height} siap<br>";
    }

    // Implementasi abstract method
    public function calculateArea()
    {
        return $this->width * $this->height;
    }

    public function calculatePerimeter()
    {
        return 2 * ($this->width + $this->height);
    }

    public function draw()
    {
        return "Drawing a {$this->color} rectangle {$this->width}x{$this->height}";
    }

    // Method khusus Rectangle
    public function isSquare()
    {
        return $this->width === $this->height;
    }

    public function getAspectRatio()
    {
        return round($this->width / $this->height, 2);
    }
}

// Concrete class - implementasi Triangle
class Triangle extends Shape
{
    private $side1;
    private $side2;
    private $side3;

    public function __construct($side1, $side2, $side3, $color = "black")
    {
        parent::__construct("Triangle", $color);

        // Validasi triangle
        if (!$this->isValidTriangle($side1, $side2, $side3)) {
            throw new InvalidArgumentException("Invalid triangle sides");
        }

        $this->side1 = $side1;
        $this->side2 = $side2;
        $this->side3 = $side3;
        echo "Triangle dengan sisi {$side1}, {$side2}, {$side3} siap<br>";
    }

    public function calculateArea()
    {
        // Menggunakan rumus Heron
        $s = $this->calculatePerimeter() / 2;
        $area = sqrt($s * ($s - $this->side1) * ($s - $this->side2) * ($s - $this->side3));
        return round($area, 2);
    }

    public function calculatePerimeter()
    {
        return $this->side1 + $this->side2 + $this->side3;
    }

    public function draw()
    {
        return "Drawing a {$this->color} triangle with sides {$this->side1}, {$this->side2}, {$this->side3}";
    }

    // Helper method
    private function isValidTriangle($a, $b, $c)
    {
        return ($a + $b > $c) && ($a + $c > $b) && ($b + $c > $a);
    }

    public function getTriangleType()
    {
        if ($this->side1 == $this->side2 && $this->side2 == $this->side3) {
            return "Equilateral";
        } elseif ($this->side1 == $this->side2 || $this->side2 == $this->side3 || $this->side1 == $this->side3) {
            return "Isosceles";
        } else {
            return "Scalene";
        }
    }
}

echo "<h3>Menggunakan Abstract Class:</h3>";

try {
    // Tidak bisa instantiate abstract class
    // $shape = new Shape("Generic", "red"); // Error!

    $circle = new Circle(5, "red");
    echo $circle->getShapeInfo() . "<br>";

    $rectangle = new Rectangle(4, 6, "blue");
    echo $rectangle->getShapeInfo() . "<br>";
    echo "Is square? " . ($rectangle->isSquare() ? "Yes" : "No") . "<br>";
    echo "Aspect ratio: " . $rectangle->getAspectRatio() . "<br><br>";

    $triangle = new Triangle(3, 4, 5, "green");
    echo $triangle->getShapeInfo() . "<br>";
    echo "Triangle type: " . $triangle->getTriangleType() . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 2: Template Method Pattern</h2>";

// Abstract class dengan Template Method Pattern
abstract class DataProcessor
{
    protected $processingName;

    public function __construct($processingName)
    {
        $this->processingName = $processingName;
        echo "Data processor '{$processingName}' initialized<br>";
    }

    // Template method - mendefinisikan algoritma umum
    public final function processData($data)
    {
        echo "<h4>Processing {$this->processingName}:</h4>";

        try {
            echo "1. Starting process...<br>";

            echo "2. Validating data...<br>";
            $validatedData = $this->validateData($data);

            echo "3. Transforming data...<br>";
            $transformedData = $this->transformData($validatedData);

            echo "4. Saving data...<br>";
            $savedData = $this->saveData($transformedData);

            echo "5. Formatting result...<br>";
            $result = $this->formatResult($savedData);

            echo "6. Process completed successfully<br>";
            return $result;
        } catch (Exception $e) {
            echo "Error during processing: " . $e->getMessage() . "<br>";
            return $this->handleError($e);
        }
    }

    // Abstract methods - harus diimplementasikan subclass
    abstract protected function validateData($data);
    abstract protected function transformData($data);
    abstract protected function saveData($data);

    // Hook methods - bisa di-override tapi ada default implementation
    protected function formatResult($data)
    {
        return [
            'success' => true,
            'processor' => $this->processingName,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    protected function handleError($exception)
    {
        return [
            'success' => false,
            'processor' => $this->processingName,
            'error' => $exception->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

// Concrete implementation - CSV Processor
class CSVProcessor extends DataProcessor
{
    public function __construct()
    {
        parent::__construct("CSV Processor");
    }

    protected function validateData($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException("Data must be an array for CSV processing");
        }

        if (empty($data)) {
            throw new InvalidArgumentException("Data cannot be empty");
        }

        echo "CSV data validation passed<br>";
        return $data;
    }

    protected function transformData($data)
    {
        // Transform array to CSV format
        $csvData = [];

        // Add header
        if (!empty($data)) {
            $csvData[] = implode(',', array_keys($data[0]));
        }

        // Add rows
        foreach ($data as $row) {
            $csvData[] = implode(',', array_values($row));
        }

        echo "Data transformed to CSV format<br>";
        return implode("\n", $csvData);
    }

    protected function saveData($data)
    {
        // Simulate saving to file
        $filename = "data_" . date('Y-m-d_H-i-s') . ".csv";
        echo "Saving CSV data to {$filename}<br>";

        // In real implementation, would save to actual file
        return [
            'filename' => $filename,
            'size' => strlen($data),
            'rows' => substr_count($data, "\n") + 1
        ];
    }
}

// Concrete implementation - JSON Processor
class JSONProcessor extends DataProcessor
{
    public function __construct()
    {
        parent::__construct("JSON Processor");
    }

    protected function validateData($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new InvalidArgumentException("Data must be array or object for JSON processing");
        }

        echo "JSON data validation passed<br>";
        return $data;
    }

    protected function transformData($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON encoding failed: " . json_last_error_msg());
        }

        echo "Data transformed to JSON format<br>";
        return $jsonData;
    }

    protected function saveData($data)
    {
        $filename = "data_" . date('Y-m-d_H-i-s') . ".json";
        echo "Saving JSON data to {$filename}<br>";

        return [
            'filename' => $filename,
            'size' => strlen($data),
            'pretty_formatted' => true
        ];
    }

    // Override format result untuk JSON-specific formatting
    protected function formatResult($data)
    {
        $result = parent::formatResult($data);
        $result['format'] = 'JSON';
        $result['encoding'] = 'UTF-8';
        return $result;
    }
}

// Concrete implementation - XML Processor
class XMLProcessor extends DataProcessor
{
    public function __construct()
    {
        parent::__construct("XML Processor");
    }

    protected function validateData($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException("Data must be an array for XML processing");
        }

        echo "XML data validation passed<br>";
        return $data;
    }

    protected function transformData($data)
    {
        $xml = new SimpleXMLElement('<root/>');
        $this->arrayToXml($data, $xml);

        echo "Data transformed to XML format<br>";
        return $xml->asXML();
    }

    protected function saveData($data)
    {
        $filename = "data_" . date('Y-m-d_H-i-s') . ".xml";
        echo "Saving XML data to {$filename}<br>";

        return [
            'filename' => $filename,
            'size' => strlen($data),
            'formatted' => true,
            'encoding' => 'UTF-8'
        ];
    }

    private function arrayToXml($data, &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }
}

echo "<h3>Template Method Pattern Implementation:</h3>";

$sampleData = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'age' => 30],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'age' => 25],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'age' => 35]
];

$processors = [
    new CSVProcessor(),
    new JSONProcessor(),
    new XMLProcessor()
];

foreach ($processors as $processor) {
    $result = $processor->processData($sampleData);
    echo "<strong>Result:</strong> " . ($result['success'] ? "Success" : "Failed") . "<br>";
    if (isset($result['data'])) {
        echo "File: " . $result['data']['filename'] . "<br>";
        echo "Size: " . $result['data']['size'] . " bytes<br>";
    }
    echo "<br>";
}

echo "<hr>";

echo "<h2>Contoh 3: Abstract Class untuk Database Connections</h2>";

// Abstract base class untuk database connection
abstract class DatabaseConnection
{
    protected $host;
    protected $database;
    protected $username;
    protected $password;
    protected $connection;
    protected $isConnected = false;

    public function __construct($host, $database, $username, $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        echo "Database connection parameters set for {$database}@{$host}<br>";
    }

    // Template method untuk connection process
    public final function establishConnection()
    {
        try {
            echo "Attempting to connect to {$this->database}...<br>";

            $this->beforeConnect();
            $this->connect();
            $this->afterConnect();

            $this->isConnected = true;
            echo "Successfully connected to {$this->database}<br>";
            return true;
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage() . "<br>";
            $this->handleConnectionError($e);
            return false;
        }
    }

    // Abstract methods - specific to each database type
    abstract protected function connect();
    abstract protected function buildConnectionString();
    abstract public function query($sql);
    abstract public function lastInsertId();
    abstract public function beginTransaction();
    abstract public function commit();
    abstract public function rollback();

    // Concrete methods with default implementation
    public function isConnected()
    {
        return $this->isConnected;
    }

    public function getConnectionInfo()
    {
        return [
            'host' => $this->host,
            'database' => $this->database,
            'username' => $this->username,
            'connected' => $this->isConnected
        ];
    }

    public function disconnect()
    {
        if ($this->isConnected && $this->connection) {
            $this->closeConnection();
            $this->isConnected = false;
            echo "Disconnected from {$this->database}<br>";
        }
    }

    // Hook methods - can be overridden
    protected function beforeConnect()
    {
        echo "Preparing connection to {$this->database}...<br>";
    }

    protected function afterConnect()
    {
        echo "Connection established, setting up environment...<br>";
    }

    protected function handleConnectionError($exception)
    {
        error_log("Database connection error: " . $exception->getMessage());
    }

    abstract protected function closeConnection();
}

// MySQL implementation
class MySQLConnection extends DatabaseConnection
{
    private $charset;

    public function __construct($host, $database, $username, $password, $charset = 'utf8mb4')
    {
        parent::__construct($host, $database, $username, $password);
        $this->charset = $charset;
        echo "MySQL connection initialized with charset {$charset}<br>";
    }

    protected function buildConnectionString()
    {
        return "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";
    }

    protected function connect()
    {
        $dsn = $this->buildConnectionString();

        // Simulate PDO connection
        echo "Connecting with DSN: {$dsn}<br>";

        // In real implementation:
        // $this->connection = new PDO($dsn, $this->username, $this->password);
        $this->connection = "MySQL PDO Connection Object";

        echo "MySQL connection established<br>";
    }

    public function query($sql)
    {
        if (!$this->isConnected) {
            throw new RuntimeException("Not connected to database");
        }

        echo "Executing MySQL query: {$sql}<br>";

        // Simulate query execution
        return "MySQL Query Result for: {$sql}";
    }

    public function lastInsertId()
    {
        return "MySQL Last Insert ID: " . rand(1, 1000);
    }

    public function beginTransaction()
    {
        echo "MySQL: BEGIN TRANSACTION<br>";
        return true;
    }

    public function commit()
    {
        echo "MySQL: COMMIT<br>";
        return true;
    }

    public function rollback()
    {
        echo "MySQL: ROLLBACK<br>";
        return true;
    }

    protected function closeConnection()
    {
        echo "Closing MySQL connection<br>";
        $this->connection = null;
    }

    // MySQL-specific methods
    public function showTables()
    {
        return $this->query("SHOW TABLES");
    }

    public function optimizeTable($tableName)
    {
        return $this->query("OPTIMIZE TABLE {$tableName}");
    }
}

// PostgreSQL implementation
class PostgreSQLConnection extends DatabaseConnection
{
    private $sslMode;

    public function __construct($host, $database, $username, $password, $sslMode = 'prefer')
    {
        parent::__construct($host, $database, $username, $password);
        $this->sslMode = $sslMode;
        echo "PostgreSQL connection initialized with SSL mode {$sslMode}<br>";
    }

    protected function buildConnectionString()
    {
        return "pgsql:host={$this->host};dbname={$this->database};sslmode={$this->sslMode}";
    }

    protected function connect()
    {
        $dsn = $this->buildConnectionString();

        echo "Connecting with DSN: {$dsn}<br>";

        // In real implementation:
        // $this->connection = new PDO($dsn, $this->username, $this->password);
        $this->connection = "PostgreSQL PDO Connection Object";

        echo "PostgreSQL connection established<br>";
    }

    public function query($sql)
    {
        if (!$this->isConnected) {
            throw new RuntimeException("Not connected to database");
        }

        echo "Executing PostgreSQL query: {$sql}<br>";

        return "PostgreSQL Query Result for: {$sql}";
    }

    public function lastInsertId()
    {
        // PostgreSQL uses sequences
        return "PostgreSQL Last Insert ID from sequence: " . rand(1, 1000);
    }

    public function beginTransaction()
    {
        echo "PostgreSQL: BEGIN<br>";
        return true;
    }

    public function commit()
    {
        echo "PostgreSQL: COMMIT<br>";
        return true;
    }

    public function rollback()
    {
        echo "PostgreSQL: ROLLBACK<br>";
        return true;
    }

    protected function closeConnection()
    {
        echo "Closing PostgreSQL connection<br>";
        $this->connection = null;
    }

    // PostgreSQL-specific methods
    public function listSchemas()
    {
        return $this->query("SELECT schema_name FROM information_schema.schemata");
    }

    public function vacuum($tableName)
    {
        return $this->query("VACUUM {$tableName}");
    }
}

echo "<h3>Database Connection Implementations:</h3>";

$connections = [
    new MySQLConnection("localhost", "myapp_db", "user", "password"),
    new PostgreSQLConnection("localhost", "myapp_db", "user", "password", "require")
];

foreach ($connections as $connection) {
    echo "<h4>Testing " . get_class($connection) . ":</h4>";

    if ($connection->establishConnection()) {
        echo "Connection info: <br>";
        $info = $connection->getConnectionInfo();
        foreach ($info as $key => $value) {
            echo "- {$key}: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "<br>";
        }

        $connection->beginTransaction();
        $result = $connection->query("SELECT * FROM users WHERE active = 1");
        echo "Query result: {$result}<br>";
        $connection->commit();

        echo "Last insert ID: " . $connection->lastInsertId() . "<br>";

        $connection->disconnect();
    }
    echo "<br>";
}

echo "<hr>";

echo "<h2>Contoh 4: Polymorphism dengan Abstract Class</h2>";

// Function yang menerima abstract class sebagai parameter
function processShape(Shape $shape)
{
    echo "<h4>Processing " . get_class($shape) . ":</h4>";
    echo "Name: " . $shape->getName() . "<br>";
    echo "Color: " . $shape->getColor() . "<br>";
    echo "Area: " . $shape->calculateArea() . "<br>";
    echo "Perimeter: " . $shape->calculatePerimeter() . "<br>";
    echo "Drawing: " . $shape->draw() . "<br>";
    echo "<br>";
}

function processMultipleShapes(array $shapes)
{
    $totalArea = 0;
    $totalPerimeter = 0;

    echo "<h4>Processing Multiple Shapes:</h4>";

    foreach ($shapes as $index => $shape) {
        echo ($index + 1) . ". " . $shape->getName() . " - ";
        echo "Area: " . $shape->calculateArea() . ", ";
        echo "Perimeter: " . $shape->calculatePerimeter() . "<br>";

        $totalArea += $shape->calculateArea();
        $totalPerimeter += $shape->calculatePerimeter();
    }

    echo "<br><strong>Summary:</strong><br>";
    echo "Total shapes: " . count($shapes) . "<br>";
    echo "Total area: {$totalArea}<br>";
    echo "Total perimeter: {$totalPerimeter}<br>";
    echo "Average area: " . round($totalArea / count($shapes), 2) . "<br>";
}

echo "<h3>Polymorphism Demonstration:</h3>";

$shapes = [
    new Circle(3, "red"),
    new Rectangle(4, 5, "blue"),
    new Triangle(3, 4, 5, "green"),
    new Circle(7, "yellow"),
    new Rectangle(10, 10, "purple") // Square
];

// Process each shape polymorphically
foreach ($shapes as $shape) {
    processShape($shape);
}

// Process all shapes together
processMultipleShapes($shapes);

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Dari contoh-contoh di atas, kita dapat melihat:</p>";
echo "<ul>";
echo "<li><strong>Abstract Class:</strong> Tidak bisa diinstansiasi, harus di-extend oleh child class</li>";
echo "<li><strong>Abstract Method:</strong> Method tanpa implementasi, harus diimplementasikan child class</li>";
echo "<li><strong>Template Method:</strong> Pattern untuk mendefinisikan algoritma umum dengan step-step yang bisa di-customize</li>";
echo "<li><strong>Concrete Methods:</strong> Method dengan implementasi lengkap yang bisa digunakan atau di-override</li>";
echo "<li><strong>Polymorphism:</strong> Object dari different child classes bisa diperlakukan sebagai parent type</li>";
echo "<li><strong>Code Reuse:</strong> Shared functionality di abstract class bisa digunakan semua child classes</li>";
echo "<li><strong>Consistency:</strong> Abstract methods memaksa child classes implement required functionality</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Gunakan abstract class ketika ada shared code dan enforced interface</li>";
echo "<li>Kombinasikan concrete dan abstract methods untuk flexibility</li>";
echo "<li>Gunakan template method pattern untuk common algorithms</li>";
echo "<li>Protected members untuk inheritance, private untuk internal logic</li>";
echo "<li>Final methods untuk prevent overriding critical functionality</li>";
echo "</ul>";

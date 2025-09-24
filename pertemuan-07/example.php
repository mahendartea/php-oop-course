<?php

/**
 * Pertemuan 7: Interface
 * Contoh implementasi interface dalam OOP PHP
 */

echo "<h1>Pertemuan 7: Interface</h1>";

echo "<h2>Contoh 1: Basic Interface Implementation</h2>";

// Basic interface definition
interface Drawable
{
    public function draw(): string;
    public function getArea(): float;
    public function getPerimeter(): float;
}

// Interface dengan constants
interface ColorConstants
{
    const RED = '#FF0000';
    const GREEN = '#00FF00';
    const BLUE = '#0000FF';
    const BLACK = '#000000';
    const WHITE = '#FFFFFF';
}

// Class yang implement interface
class Circle implements Drawable, ColorConstants
{
    private float $radius;
    private string $color;

    public function __construct(float $radius, string $color = self::BLACK)
    {
        $this->radius = $radius;
        $this->color = $color;
        echo "Circle dengan radius {$radius} dan warna {$color} dibuat<br>";
    }

    public function draw(): string
    {
        return "Menggambar lingkaran dengan radius {$this->radius} berwarna {$this->color}";
    }

    public function getArea(): float
    {
        return round(pi() * pow($this->radius, 2), 2);
    }

    public function getPerimeter(): float
    {
        return round(2 * pi() * $this->radius, 2);
    }

    // Method tambahan khusus Circle
    public function getRadius(): float
    {
        return $this->radius;
    }

    public function getDiameter(): float
    {
        return $this->radius * 2;
    }
}

class Rectangle implements Drawable, ColorConstants
{
    private float $width;
    private float $height;
    private string $color;

    public function __construct(float $width, float $height, string $color = self::BLACK)
    {
        $this->width = $width;
        $this->height = $height;
        $this->color = $color;
        echo "Rectangle {$width}x{$height} berwarna {$color} dibuat<br>";
    }

    public function draw(): string
    {
        return "Menggambar persegi panjang {$this->width}x{$this->height} berwarna {$this->color}";
    }

    public function getArea(): float
    {
        return $this->width * $this->height;
    }

    public function getPerimeter(): float
    {
        return 2 * ($this->width + $this->height);
    }

    // Method tambahan
    public function isSquare(): bool
    {
        return $this->width === $this->height;
    }

    public function getAspectRatio(): float
    {
        return round($this->width / $this->height, 2);
    }
}

class Triangle implements Drawable, ColorConstants
{
    private float $side1;
    private float $side2;
    private float $side3;
    private string $color;

    public function __construct(float $side1, float $side2, float $side3, string $color = self::BLACK)
    {
        if (!$this->isValidTriangle($side1, $side2, $side3)) {
            throw new InvalidArgumentException("Sisi-sisi tidak dapat membentuk segitiga");
        }

        $this->side1 = $side1;
        $this->side2 = $side2;
        $this->side3 = $side3;
        $this->color = $color;
        echo "Triangle dengan sisi {$side1}, {$side2}, {$side3} berwarna {$color} dibuat<br>";
    }

    public function draw(): string
    {
        return "Menggambar segitiga dengan sisi {$this->side1}, {$this->side2}, {$this->side3} berwarna {$this->color}";
    }

    public function getArea(): float
    {
        // Menggunakan rumus Heron
        $s = $this->getPerimeter() / 2;
        $area = sqrt($s * ($s - $this->side1) * ($s - $this->side2) * ($s - $this->side3));
        return round($area, 2);
    }

    public function getPerimeter(): float
    {
        return $this->side1 + $this->side2 + $this->side3;
    }

    private function isValidTriangle(float $a, float $b, float $c): bool
    {
        return ($a + $b > $c) && ($a + $c > $b) && ($b + $c > $a);
    }

    public function getTriangleType(): string
    {
        if ($this->side1 == $this->side2 && $this->side2 == $this->side3) {
            return "Sama sisi";
        } elseif ($this->side1 == $this->side2 || $this->side2 == $this->side3 || $this->side1 == $this->side3) {
            return "Sama kaki";
        } else {
            return "Sembarang";
        }
    }
}

// Function yang menggunakan interface sebagai type hint
function renderShape(Drawable $shape): void
{
    echo "<h4>" . get_class($shape) . ":</h4>";
    echo $shape->draw() . "<br>";
    echo "Area: " . $shape->getArea() . "<br>";
    echo "Perimeter: " . $shape->getPerimeter() . "<br>";
    echo "<br>";
}

function calculateTotalArea(array $shapes): float
{
    $totalArea = 0;
    foreach ($shapes as $shape) {
        if ($shape instanceof Drawable) {
            $totalArea += $shape->getArea();
        }
    }
    return $totalArea;
}

echo "<h3>Basic Interface Usage:</h3>";

try {
    // Tidak bisa instantiate interface
    // $drawable = new Drawable(); // Error!

    $shapes = [
        new Circle(3, ColorConstants::RED),
        new Rectangle(4, 6, ColorConstants::BLUE),
        new Triangle(3, 4, 5, ColorConstants::GREEN)
    ];

    // Polymorphic processing
    foreach ($shapes as $shape) {
        renderShape($shape);
    }

    echo "<strong>Total area semua shapes: " . calculateTotalArea($shapes) . "</strong><br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 2: Multiple Interface Implementation</h2>";

// Interface untuk berbagai capabilities
interface Readable
{
    public function read(): string;
    public function canRead(): bool;
}

interface Writable
{
    public function write(string $data): bool;
    public function canWrite(): bool;
}

interface Executable
{
    public function execute(): mixed;
    public function canExecute(): bool;
}

interface Deletable
{
    public function delete(): bool;
    public function canDelete(): bool;
}

// Class yang implement multiple interfaces
class TextFile implements Readable, Writable, Deletable
{
    private string $filename;
    private string $content;
    private array $permissions;
    private bool $exists;

    public function __construct(string $filename, array $permissions = ['read', 'write'])
    {
        $this->filename = $filename;
        $this->content = "";
        $this->permissions = $permissions;
        $this->exists = true;
        echo "TextFile '{$filename}' dibuat dengan permissions: " . implode(', ', $permissions) . "<br>";
    }

    // Readable interface implementation
    public function read(): string
    {
        if (!$this->canRead()) {
            throw new RuntimeException("File tidak dapat dibaca");
        }

        echo "Membaca file {$this->filename}<br>";
        return $this->content;
    }

    public function canRead(): bool
    {
        return $this->exists && in_array('read', $this->permissions);
    }

    // Writable interface implementation
    public function write(string $data): bool
    {
        if (!$this->canWrite()) {
            throw new RuntimeException("File tidak dapat ditulis");
        }

        $this->content = $data;
        echo "Menulis data ke file {$this->filename}: '{$data}'<br>";
        return true;
    }

    public function canWrite(): bool
    {
        return $this->exists && in_array('write', $this->permissions);
    }

    // Deletable interface implementation
    public function delete(): bool
    {
        if (!$this->canDelete()) {
            throw new RuntimeException("File tidak dapat dihapus");
        }

        $this->exists = false;
        $this->content = "";
        echo "File {$this->filename} berhasil dihapus<br>";
        return true;
    }

    public function canDelete(): bool
    {
        return $this->exists && in_array('delete', $this->permissions);
    }

    // Additional methods
    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getSize(): int
    {
        return strlen($this->content);
    }

    public function exists(): bool
    {
        return $this->exists;
    }
}

class ExecutableFile implements Readable, Executable, Deletable
{
    private string $filename;
    private string $script;
    private array $permissions;
    private bool $exists;

    public function __construct(string $filename, string $script = "", array $permissions = ['read', 'execute'])
    {
        $this->filename = $filename;
        $this->script = $script;
        $this->permissions = $permissions;
        $this->exists = true;
        echo "ExecutableFile '{$filename}' dibuat dengan permissions: " . implode(', ', $permissions) . "<br>";
    }

    public function read(): string
    {
        if (!$this->canRead()) {
            throw new RuntimeException("File tidak dapat dibaca");
        }

        echo "Membaca executable file {$this->filename}<br>";
        return $this->script;
    }

    public function canRead(): bool
    {
        return $this->exists && in_array('read', $this->permissions);
    }

    public function execute(): mixed
    {
        if (!$this->canExecute()) {
            throw new RuntimeException("File tidak dapat dieksekusi");
        }

        echo "Mengeksekusi file {$this->filename}<br>";

        // Simulate script execution
        $result = "Hasil eksekusi: " . strtoupper($this->script);
        echo "Output: {$result}<br>";

        return $result;
    }

    public function canExecute(): bool
    {
        return $this->exists && in_array('execute', $this->permissions) && !empty($this->script);
    }

    public function delete(): bool
    {
        if (!$this->canDelete()) {
            throw new RuntimeException("File tidak dapat dihapus");
        }

        $this->exists = false;
        $this->script = "";
        echo "Executable file {$this->filename} berhasil dihapus<br>";
        return true;
    }

    public function canDelete(): bool
    {
        return $this->exists && in_array('delete', $this->permissions);
    }

    public function setScript(string $script): void
    {
        $this->script = $script;
        echo "Script untuk {$this->filename} diupdate<br>";
    }
}

// Functions untuk berbagai interface operations
function processReadableFiles(array $files): void
{
    echo "<h4>Processing Readable Files:</h4>";
    foreach ($files as $file) {
        if ($file instanceof Readable && $file->canRead()) {
            echo "Reading {$file->getFilename()}: '" . $file->read() . "'<br>";
        }
    }
}

function processWritableFiles(array $files, string $data): void
{
    echo "<h4>Processing Writable Files:</h4>";
    foreach ($files as $file) {
        if ($file instanceof Writable && $file->canWrite()) {
            $file->write($data);
        }
    }
}

function processExecutableFiles(array $files): void
{
    echo "<h4>Processing Executable Files:</h4>";
    foreach ($files as $file) {
        if ($file instanceof Executable && $file->canExecute()) {
            $file->execute();
        }
    }
}

echo "<h3>Multiple Interface Implementation:</h3>";

try {
    $textFile = new TextFile("document.txt", ['read', 'write', 'delete']);
    $execFile = new ExecutableFile("script.sh", "echo 'Hello World'", ['read', 'execute', 'delete']);
    $readOnlyFile = new TextFile("readonly.txt", ['read']);

    $files = [$textFile, $execFile, $readOnlyFile];

    // Write data to writable files
    processWritableFiles($files, "Sample content for testing");
    echo "<br>";

    // Read from readable files
    processReadableFiles($files);
    echo "<br>";

    // Execute executable files
    processExecutableFiles($files);
    echo "<br>";

    // Test capabilities
    echo "<h4>File Capabilities:</h4>";
    foreach ($files as $file) {
        echo "File: {$file->getFilename()}<br>";
        if ($file instanceof Readable) echo "- Can read: " . ($file->canRead() ? "Yes" : "No") . "<br>";
        if ($file instanceof Writable) echo "- Can write: " . ($file->canWrite() ? "Yes" : "No") . "<br>";
        if ($file instanceof Executable) echo "- Can execute: " . ($file->canExecute() ? "Yes" : "No") . "<br>";
        if ($file instanceof Deletable) echo "- Can delete: " . ($file->canDelete() ? "Yes" : "No") . "<br>";
        echo "<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 3: Interface Inheritance</h2>";

// Base interface
interface PaymentProcessor
{
    public function processPayment(float $amount): array;
    public function getProcessorName(): string;
}

// Extended interfaces
interface RefundablePayment extends PaymentProcessor
{
    public function refund(string $transactionId, float $amount): array;
    public function getRefundPolicy(): string;
}

interface RecurringPayment extends PaymentProcessor
{
    public function setupRecurring(float $amount, string $frequency): array;
    public function cancelRecurring(string $subscriptionId): array;
}

// Interface yang extend multiple interfaces
interface AdvancedPaymentProcessor extends RefundablePayment, RecurringPayment
{
    public function getTransactionHistory(int $limit = 10): array;
    public function generateReport(string $period): array;
}

// Implementation classes
class CreditCardProcessor implements AdvancedPaymentProcessor
{
    private string $processorName = "Credit Card Processor";
    private array $transactions = [];
    private array $subscriptions = [];

    public function processPayment(float $amount): array
    {
        $transactionId = 'cc_' . uniqid();
        $transaction = [
            'id' => $transactionId,
            'amount' => $amount,
            'status' => 'completed',
            'method' => 'credit_card',
            'timestamp' => date('Y-m-d H:i:s'),
            'fee' => $amount * 0.029 // 2.9% fee
        ];

        $this->transactions[] = $transaction;

        echo "Credit Card payment processed: \${$amount} (Fee: \${$transaction['fee']})<br>";
        return $transaction;
    }

    public function getProcessorName(): string
    {
        return $this->processorName;
    }

    public function refund(string $transactionId, float $amount): array
    {
        $refund = [
            'id' => 'rf_' . uniqid(),
            'original_transaction' => $transactionId,
            'amount' => $amount,
            'status' => 'completed',
            'timestamp' => date('Y-m-d H:i:s'),
            'processing_time' => '3-5 business days'
        ];

        echo "Credit Card refund processed: \${$amount} for transaction {$transactionId}<br>";
        return $refund;
    }

    public function getRefundPolicy(): string
    {
        return "Refunds processed within 3-5 business days. Refund fees may apply for transactions older than 30 days.";
    }

    public function setupRecurring(float $amount, string $frequency): array
    {
        $subscriptionId = 'sub_cc_' . uniqid();
        $subscription = [
            'id' => $subscriptionId,
            'amount' => $amount,
            'frequency' => $frequency,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'next_payment' => date('Y-m-d H:i:s', strtotime("+1 $frequency"))
        ];

        $this->subscriptions[] = $subscription;

        echo "Credit Card recurring payment setup: \${$amount} every {$frequency}<br>";
        return $subscription;
    }

    public function cancelRecurring(string $subscriptionId): array
    {
        echo "Credit Card recurring payment cancelled: {$subscriptionId}<br>";
        return [
            'subscription_id' => $subscriptionId,
            'status' => 'cancelled',
            'cancelled_at' => date('Y-m-d H:i:s')
        ];
    }

    public function getTransactionHistory(int $limit = 10): array
    {
        return array_slice($this->transactions, -$limit);
    }

    public function generateReport(string $period): array
    {
        $totalAmount = array_sum(array_column($this->transactions, 'amount'));
        $totalFees = array_sum(array_column($this->transactions, 'fee'));

        return [
            'period' => $period,
            'total_transactions' => count($this->transactions),
            'total_amount' => $totalAmount,
            'total_fees' => $totalFees,
            'net_amount' => $totalAmount - $totalFees,
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }
}

class BankTransferProcessor implements RefundablePayment
{
    private string $processorName = "Bank Transfer Processor";
    private array $transactions = [];

    public function processPayment(float $amount): array
    {
        $transactionId = 'bt_' . uniqid();
        $transaction = [
            'id' => $transactionId,
            'amount' => $amount,
            'status' => 'pending', // Bank transfers usually take time
            'method' => 'bank_transfer',
            'timestamp' => date('Y-m-d H:i:s'),
            'processing_time' => '1-3 business days'
        ];

        $this->transactions[] = $transaction;

        echo "Bank Transfer payment initiated: \${$amount} (Processing: 1-3 business days)<br>";
        return $transaction;
    }

    public function getProcessorName(): string
    {
        return $this->processorName;
    }

    public function refund(string $transactionId, float $amount): array
    {
        $refund = [
            'id' => 'rf_bt_' . uniqid(),
            'original_transaction' => $transactionId,
            'amount' => $amount,
            'status' => 'pending',
            'timestamp' => date('Y-m-d H:i:s'),
            'processing_time' => '5-7 business days'
        ];

        echo "Bank Transfer refund initiated: \${$amount} for transaction {$transactionId}<br>";
        return $refund;
    }

    public function getRefundPolicy(): string
    {
        return "Bank transfer refunds processed within 5-7 business days. No additional fees for refunds.";
    }
}

class PayPalProcessor implements RecurringPayment
{
    private string $processorName = "PayPal Processor";
    private array $transactions = [];
    private array $subscriptions = [];

    public function processPayment(float $amount): array
    {
        $transactionId = 'pp_' . uniqid();
        $transaction = [
            'id' => $transactionId,
            'amount' => $amount,
            'status' => 'completed',
            'method' => 'paypal',
            'timestamp' => date('Y-m-d H:i:s'),
            'fee' => $amount * 0.034 + 0.30 // PayPal fee structure
        ];

        $this->transactions[] = $transaction;

        echo "PayPal payment processed: \${$amount} (Fee: \${$transaction['fee']})<br>";
        return $transaction;
    }

    public function getProcessorName(): string
    {
        return $this->processorName;
    }

    public function setupRecurring(float $amount, string $frequency): array
    {
        $subscriptionId = 'sub_pp_' . uniqid();
        $subscription = [
            'id' => $subscriptionId,
            'amount' => $amount,
            'frequency' => $frequency,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'next_payment' => date('Y-m-d H:i:s', strtotime("+1 $frequency"))
        ];

        $this->subscriptions[] = $subscription;

        echo "PayPal recurring payment setup: \${$amount} every {$frequency}<br>";
        return $subscription;
    }

    public function cancelRecurring(string $subscriptionId): array
    {
        echo "PayPal recurring payment cancelled: {$subscriptionId}<br>";
        return [
            'subscription_id' => $subscriptionId,
            'status' => 'cancelled',
            'cancelled_at' => date('Y-m-d H:i:s')
        ];
    }
}

// Payment processing functions
function processPayments(array $processors, float $amount): void
{
    echo "<h4>Processing payments of \${$amount}:</h4>";
    foreach ($processors as $processor) {
        echo "Using {$processor->getProcessorName()}:<br>";
        $result = $processor->processPayment($amount);
        echo "Transaction ID: {$result['id']}, Status: {$result['status']}<br><br>";
    }
}

function setupRecurringPayments(array $processors, float $amount, string $frequency): void
{
    echo "<h4>Setting up recurring payments of \${$amount} every {$frequency}:</h4>";
    foreach ($processors as $processor) {
        if ($processor instanceof RecurringPayment) {
            echo "Using {$processor->getProcessorName()}:<br>";
            $result = $processor->setupRecurring($amount, $frequency);
            echo "Subscription ID: {$result['id']}, Status: {$result['status']}<br><br>";
        } else {
            echo "{$processor->getProcessorName()} does not support recurring payments<br><br>";
        }
    }
}

echo "<h3>Interface Inheritance Implementation:</h3>";

$processors = [
    new CreditCardProcessor(),
    new BankTransferProcessor(),
    new PayPalProcessor()
];

// Process regular payments
processPayments($processors, 100.00);

// Setup recurring payments
setupRecurringPayments($processors, 29.99, "month");

// Test refund capabilities
echo "<h4>Testing refund capabilities:</h4>";
foreach ($processors as $processor) {
    if ($processor instanceof RefundablePayment) {
        echo "Using {$processor->getProcessorName()}:<br>";
        $processor->refund('sample_transaction_id', 50.00);
        echo "Policy: " . $processor->getRefundPolicy() . "<br><br>";
    } else {
        echo "{$processor->getProcessorName()} does not support refunds<br><br>";
    }
}

// Advanced features for CreditCardProcessor
echo "<h4>Advanced features (Credit Card only):</h4>";
$ccProcessor = new CreditCardProcessor();
$ccProcessor->processPayment(150.00);
$ccProcessor->processPayment(200.00);

$history = $ccProcessor->getTransactionHistory(5);
echo "Transaction history (last 5):<br>";
foreach ($history as $transaction) {
    echo "- {$transaction['id']}: \${$transaction['amount']} at {$transaction['timestamp']}<br>";
}

$report = $ccProcessor->generateReport("monthly");
echo "<br>Monthly report:<br>";
foreach ($report as $key => $value) {
    echo "- {$key}: {$value}<br>";
}

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Dari contoh-contoh di atas, kita dapat melihat:</p>";
echo "<ul>";
echo "<li><strong>Interface:</strong> Kontrak yang mendefinisikan method yang harus diimplementasikan</li>";
echo "<li><strong>Multiple Implementation:</strong> Class bisa implement multiple interfaces</li>";
echo "<li><strong>Interface Inheritance:</strong> Interface bisa extend interface lain</li>";
echo "<li><strong>Polymorphism:</strong> Object bisa diperlakukan sebagai interface type</li>";
echo "<li><strong>Loose Coupling:</strong> Dependencies pada interface, bukan concrete class</li>";
echo "<li><strong>Flexibility:</strong> Mudah mengganti implementasi tanpa mengubah code yang menggunakan</li>";
echo "<li><strong>Testing:</strong> Interface memudahkan mocking dan unit testing</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Buat interface yang focused dan spesifik (Interface Segregation Principle)</li>";
echo "<li>Gunakan interface untuk dependency injection</li>";
echo "<li>Prefer interface over concrete classes dalam type hints</li>";
echo "<li>Gunakan meaningful names untuk interface</li>";
echo "<li>Kombinasikan interface untuk complex behaviors</li>";
echo "</ul>";

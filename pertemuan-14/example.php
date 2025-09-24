<?php

/**
 * Pertemuan 14: Design Patterns (Pola Desain)
 * Implementasi berbagai Design Patterns dalam PHP OOP
 */

echo "<h1>Pertemuan 14: Design Patterns</h1>";

echo "<h2>1. Creational Patterns</h2>";

echo "<h3>A. Singleton Pattern</h3>";

// Database Connection Singleton
class DatabaseConnection
{
    private static ?self $instance = null;
    private string $host;
    private string $database;
    private array $connections = [];

    private function __construct()
    {
        $this->host = 'localhost';
        $this->database = 'app_db';
        echo "Database connection initialized<br>";
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
            echo "New Database connection instance created<br>";
        } else {
            echo "Reusing existing Database connection instance<br>";
        }

        return self::$instance;
    }

    public function connect(): string
    {
        $connectionId = uniqid('conn_');
        $this->connections[] = $connectionId;
        echo "Connected to {$this->database} on {$this->host} (Connection ID: {$connectionId})<br>";
        return $connectionId;
    }

    public function getConnectionCount(): int
    {
        return count($this->connections);
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Logger Singleton
class Logger
{
    private static ?self $instance = null;
    private array $logs = [];
    private string $logFile;

    private function __construct()
    {
        $this->logFile = 'app.log';
        echo "Logger initialized with file: {$this->logFile}<br>";
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function log(string $level, string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$level}: {$message}";
        $this->logs[] = $logEntry;
        echo "LOG: {$logEntry}<br>";
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function getLogCount(): int
    {
        return count($this->logs);
    }

    private function __clone() {}
    public function __wakeup() {}
}

echo "<h4>Testing Singleton Pattern:</h4>";

// Test Database Singleton
$db1 = DatabaseConnection::getInstance();
$conn1 = $db1->connect();

$db2 = DatabaseConnection::getInstance();
$conn2 = $db2->connect();

echo "Are db1 and db2 the same instance? " . ($db1 === $db2 ? 'Yes' : 'No') . "<br>";
echo "Total connections: " . $db1->getConnectionCount() . "<br>";

echo "<br>";

// Test Logger Singleton
$logger1 = Logger::getInstance();
$logger1->log('INFO', 'Application started');

$logger2 = Logger::getInstance();
$logger2->log('ERROR', 'Something went wrong');

echo "Are logger1 and logger2 the same instance? " . ($logger1 === $logger2 ? 'Yes' : 'No') . "<br>";
echo "Total logs: " . $logger1->getLogCount() . "<br>";

echo "<br><hr>";

echo "<h3>B. Factory Pattern</h3>";

// Product interfaces
interface NotificationInterface
{
    public function send(string $recipient, string $message): void;
    public function getType(): string;
}

// Concrete products
class EmailNotification implements NotificationInterface
{
    private string $smtpServer;

    public function __construct(string $smtpServer = 'smtp.example.com')
    {
        $this->smtpServer = $smtpServer;
    }

    public function send(string $recipient, string $message): void
    {
        echo "Email sent to {$recipient} via {$this->smtpServer}: {$message}<br>";
    }

    public function getType(): string
    {
        return 'email';
    }
}

class SMSNotification implements NotificationInterface
{
    private string $gateway;

    public function __construct(string $gateway = 'twilio')
    {
        $this->gateway = $gateway;
    }

    public function send(string $recipient, string $message): void
    {
        echo "SMS sent to {$recipient} via {$this->gateway}: {$message}<br>";
    }

    public function getType(): string
    {
        return 'sms';
    }
}

class PushNotification implements NotificationInterface
{
    private string $service;

    public function __construct(string $service = 'firebase')
    {
        $this->service = $service;
    }

    public function send(string $recipient, string $message): void
    {
        echo "Push notification sent to {$recipient} via {$service}: {$message}<br>";
    }

    public function getType(): string
    {
        return 'push';
    }
}

// Factory
class NotificationFactory
{
    private static array $creators = [];

    public static function registerCreator(string $type, callable $creator): void
    {
        self::$creators[$type] = $creator;
        echo "Registered notification creator for type: {$type}<br>";
    }

    public static function create(string $type, array $config = []): NotificationInterface
    {
        if (!isset(self::$creators[$type])) {
            throw new InvalidArgumentException("Unknown notification type: {$type}");
        }

        return self::$creators[$type]($config);
    }

    public static function getAvailableTypes(): array
    {
        return array_keys(self::$creators);
    }
}

echo "<h4>Testing Factory Pattern:</h4>";

// Register notification creators
NotificationFactory::registerCreator('email', function($config) {
    return new EmailNotification($config['smtp'] ?? 'smtp.example.com');
});

NotificationFactory::registerCreator('sms', function($config) {
    return new SMSNotification($config['gateway'] ?? 'twilio');
});

NotificationFactory::registerCreator('push', function($config) {
    return new PushNotification($config['service'] ?? 'firebase');
});

echo "<br>";

// Create different notifications
$notifications = [
    NotificationFactory::create('email', ['smtp' => 'smtp.gmail.com']),
    NotificationFactory::create('sms', ['gateway' => 'nexmo']),
    NotificationFactory::create('push', ['service' => 'apns'])
];

foreach ($notifications as $notification) {
    $notification->send('user@example.com', 'Hello from ' . $notification->getType());
}

echo "Available types: " . implode(', ', NotificationFactory::getAvailableTypes()) . "<br>";

echo "<br><hr>";

echo "<h3>C. Builder Pattern</h3>";

// Complex product
class DatabaseQuery
{
    private string $table = '';
    private array $select = [];
    private array $where = [];
    private array $joins = [];
    private array $orderBy = [];
    private int $limit = 0;
    private int $offset = 0;

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function addSelect(array $fields): void
    {
        $this->select = array_merge($this->select, $fields);
    }

    public function addWhere(string $condition): void
    {
        $this->where[] = $condition;
    }

    public function addJoin(string $join): void
    {
        $this->joins[] = $join;
    }

    public function addOrderBy(string $field, string $direction = 'ASC'): void
    {
        $this->orderBy[] = "{$field} {$direction}";
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function toSQL(): string
    {
        $sql = "SELECT ";
        $sql .= empty($this->select) ? "*" : implode(", ", $this->select);
        $sql .= " FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(", ", $this->orderBy);
        }

        if ($this->limit > 0) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset > 0) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }
}

// Builder
class QueryBuilder
{
    private DatabaseQuery $query;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): self
    {
        $this->query = new DatabaseQuery();
        return $this;
    }

    public function table(string $table): self
    {
        $this->query->setTable($table);
        return $this;
    }

    public function select(array $fields): self
    {
        $this->query->addSelect($fields);
        return $this;
    }

    public function where(string $condition): self
    {
        $this->query->addWhere($condition);
        return $this;
    }

    public function join(string $table, string $on): self
    {
        $this->query->addJoin("JOIN {$table} ON {$on}");
        return $this;
    }

    public function leftJoin(string $table, string $on): self
    {
        $this->query->addJoin("LEFT JOIN {$table} ON {$on}");
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->query->addOrderBy($field, $direction);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->query->setLimit($limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->query->setOffset($offset);
        return $this;
    }

    public function build(): DatabaseQuery
    {
        $query = $this->query;
        $this->reset(); // Reset for next build
        return $query;
    }
}

echo "<h4>Testing Builder Pattern:</h4>";

$builder = new QueryBuilder();

// Simple query
$simpleQuery = $builder
    ->table('users')
    ->select(['name', 'email'])
    ->where('active = 1')
    ->orderBy('name')
    ->build();

echo "Simple Query: " . $simpleQuery->toSQL() . "<br>";

// Complex query
$complexQuery = $builder
    ->table('orders')
    ->select(['orders.id', 'orders.total', 'users.name', 'products.title'])
    ->join('users', 'users.id = orders.user_id')
    ->join('order_items', 'order_items.order_id = orders.id')
    ->join('products', 'products.id = order_items.product_id')
    ->where('orders.status = "completed"')
    ->where('orders.created_at >= "2024-01-01"')
    ->orderBy('orders.created_at', 'DESC')
    ->limit(10)
    ->offset(20)
    ->build();

echo "Complex Query: " . $complexQuery->toSQL() . "<br>";

echo "<br><hr>";

echo "<h2>2. Structural Patterns</h2>";

echo "<h3>A. Adapter Pattern</h3>";

// Old payment system (legacy)
class OldPaymentGateway
{
    public function makePayment(string $cardNumber, float $amount): bool
    {
        echo "Processing payment of $" . number_format($amount, 2) . " with card ****" . substr($cardNumber, -4) . " via Old Gateway<br>";
        return true;
    }
}

// New payment interface
interface PaymentProcessorInterface
{
    public function processPayment(array $paymentData): array;
}

// Adapter
class PaymentGatewayAdapter implements PaymentProcessorInterface
{
    private OldPaymentGateway $oldGateway;

    public function __construct(OldPaymentGateway $oldGateway)
    {
        $this->oldGateway = $oldGateway;
    }

    public function processPayment(array $paymentData): array
    {
        // Adapt new interface to old implementation
        $success = $this->oldGateway->makePayment(
            $paymentData['card_number'],
            $paymentData['amount']
        );

        return [
            'status' => $success ? 'success' : 'failed',
            'transaction_id' => uniqid('txn_'),
            'amount' => $paymentData['amount'],
            'message' => $success ? 'Payment processed successfully' : 'Payment failed'
        ];
    }
}

// Modern payment processor
class ModernPaymentProcessor implements PaymentProcessorInterface
{
    public function processPayment(array $paymentData): array
    {
        echo "Processing payment via Modern Gateway: $" . number_format($paymentData['amount'], 2) . "<br>";

        return [
            'status' => 'success',
            'transaction_id' => uniqid('modern_txn_'),
            'amount' => $paymentData['amount'],
            'message' => 'Payment processed via modern gateway'
        ];
    }
}

echo "<h4>Testing Adapter Pattern:</h4>";

// Using old gateway through adapter
$oldGateway = new OldPaymentGateway();
$adapter = new PaymentGatewayAdapter($oldGateway);

$paymentData = [
    'card_number' => '1234567890123456',
    'amount' => 99.99,
    'currency' => 'USD'
];

$result1 = $adapter->processPayment($paymentData);
echo "Adapter result: " . json_encode($result1) . "<br>";

// Using modern gateway
$modernGateway = new ModernPaymentProcessor();
$result2 = $modernGateway->processPayment($paymentData);
echo "Modern result: " . json_encode($result2) . "<br>";

echo "<br><hr>";

echo "<h3>B. Decorator Pattern</h3>";

// Base coffee interface
interface CoffeeInterface
{
    public function cost(): float;
    public function description(): string;
}

// Basic coffee
class BasicCoffee implements CoffeeInterface
{
    public function cost(): float
    {
        return 5.0;
    }

    public function description(): string
    {
        return "Basic Coffee";
    }
}

// Decorator base class
abstract class CoffeeDecorator implements CoffeeInterface
{
    protected CoffeeInterface $coffee;

    public function __construct(CoffeeInterface $coffee)
    {
        $this->coffee = $coffee;
    }

    public function cost(): float
    {
        return $this->coffee->cost();
    }

    public function description(): string
    {
        return $this->coffee->description();
    }
}

// Concrete decorators
class MilkDecorator extends CoffeeDecorator
{
    public function cost(): float
    {
        return $this->coffee->cost() + 1.5;
    }

    public function description(): string
    {
        return $this->coffee->description() . " + Milk";
    }
}

class SugarDecorator extends CoffeeDecorator
{
    public function cost(): float
    {
        return $this->coffee->cost() + 0.5;
    }

    public function description(): string
    {
        return $this->coffee->description() . " + Sugar";
    }
}

class VanillaDecorator extends CoffeeDecorator
{
    public function cost(): float
    {
        return $this->coffee->cost() + 2.0;
    }

    public function description(): string
    {
        return $this->coffee->description() . " + Vanilla";
    }
}

class WhippedCreamDecorator extends CoffeeDecorator
{
    public function cost(): float
    {
        return $this->coffee->cost() + 3.0;
    }

    public function description(): string
    {
        return $this->coffee->description() . " + Whipped Cream";
    }
}

echo "<h4>Testing Decorator Pattern:</h4>";

// Basic coffee
$coffee = new BasicCoffee();
echo "Order: " . $coffee->description() . " - $" . number_format($coffee->cost(), 2) . "<br>";

// Coffee with milk
$coffeeWithMilk = new MilkDecorator($coffee);
echo "Order: " . $coffeeWithMilk->description() . " - $" . number_format($coffeeWithMilk->cost(), 2) . "<br>";

// Coffee with milk and sugar
$coffeeWithMilkAndSugar = new SugarDecorator($coffeeWithMilk);
echo "Order: " . $coffeeWithMilkAndSugar->description() . " - $" . number_format($coffeeWithMilkAndSugar->cost(), 2) . "<br>";

// Premium coffee with all extras
$premiumCoffee = new WhippedCreamDecorator(
    new VanillaDecorator(
        new SugarDecorator(
            new MilkDecorator(
                new BasicCoffee()
            )
        )
    )
);

echo "Order: " . $premiumCoffee->description() . " - $" . number_format($premiumCoffee->cost(), 2) . "<br>";

echo "<br><hr>";

echo "<h2>3. Behavioral Patterns</h2>";

echo "<h3>A. Observer Pattern</h3>";

// Observer interface
interface ObserverInterface
{
    public function update(string $event, array $data): void;
}

// Subject interface
interface SubjectInterface
{
    public function attach(ObserverInterface $observer): void;
    public function detach(ObserverInterface $observer): void;
    public function notify(string $event, array $data): void;
}

// Concrete subject (User)
class User implements SubjectInterface
{
    private array $observers = [];
    private string $name;
    private string $email;
    private string $status;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->status = 'active';
    }

    public function attach(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
        echo "Observer attached to user: {$this->name}<br>";
    }

    public function detach(ObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
            echo "Observer detached from user: {$this->name}<br>";
        }
    }

    public function notify(string $event, array $data): void
    {
        echo "Notifying " . count($this->observers) . " observers about event: {$event}<br>";
        foreach ($this->observers as $observer) {
            $observer->update($event, $data);
        }
    }

    public function updateProfile(array $data): void
    {
        $oldData = [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status
        ];

        if (isset($data['name'])) $this->name = $data['name'];
        if (isset($data['email'])) $this->email = $data['email'];
        if (isset($data['status'])) $this->status = $data['status'];

        $this->notify('profile_updated', [
            'user' => $this->name,
            'old_data' => $oldData,
            'new_data' => [
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status
            ]
        ]);
    }

    public function login(): void
    {
        $this->notify('user_login', [
            'user' => $this->name,
            'email' => $this->email,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getStatus(): string { return $this->status; }
}

// Concrete observers
class EmailNotificationObserver implements ObserverInterface
{
    public function update(string $event, array $data): void
    {
        switch ($event) {
            case 'user_login':
                echo "EMAIL: Login notification sent to admin about user: {$data['user']}<br>";
                break;
            case 'profile_updated':
                echo "EMAIL: Profile update notification sent to: {$data['new_data']['email']}<br>";
                break;
        }
    }
}

class LoggerObserver implements ObserverInterface
{
    public function update(string $event, array $data): void
    {
        $timestamp = date('Y-m-d H:i:s');
        echo "LOG [{$timestamp}]: Event '{$event}' - " . json_encode($data) . "<br>";
    }
}

class SecurityObserver implements ObserverInterface
{
    public function update(string $event, array $data): void
    {
        switch ($event) {
            case 'user_login':
                echo "SECURITY: Checking login from user: {$data['user']} at {$data['timestamp']}<br>";
                break;
            case 'profile_updated':
                echo "SECURITY: Profile changes detected for user: {$data['user']}<br>";
                break;
        }
    }
}

echo "<h4>Testing Observer Pattern:</h4>";

$user = new User("John Doe", "john@example.com");

// Attach observers
$emailObserver = new EmailNotificationObserver();
$loggerObserver = new LoggerObserver();
$securityObserver = new SecurityObserver();

$user->attach($emailObserver);
$user->attach($loggerObserver);
$user->attach($securityObserver);

echo "<br>";

// Trigger events
echo "User login event:<br>";
$user->login();

echo "<br>User profile update event:<br>";
$user->updateProfile(['email' => 'john.doe@newdomain.com', 'status' => 'premium']);

echo "<br><hr>";

echo "<h3>B. Strategy Pattern</h3>";

// Strategy interface
interface PricingStrategyInterface
{
    public function calculatePrice(float $basePrice, array $context = []): float;
    public function getDescription(): string;
}

// Concrete strategies
class RegularPricingStrategy implements PricingStrategyInterface
{
    public function calculatePrice(float $basePrice, array $context = []): float
    {
        return $basePrice;
    }

    public function getDescription(): string
    {
        return "Regular pricing - no discount";
    }
}

class BulkDiscountStrategy implements PricingStrategyInterface
{
    private int $minQuantity;
    private float $discountPercent;

    public function __construct(int $minQuantity = 10, float $discountPercent = 0.1)
    {
        $this->minQuantity = $minQuantity;
        $this->discountPercent = $discountPercent;
    }

    public function calculatePrice(float $basePrice, array $context = []): float
    {
        $quantity = $context['quantity'] ?? 1;

        if ($quantity >= $this->minQuantity) {
            return $basePrice * (1 - $this->discountPercent);
        }

        return $basePrice;
    }

    public function getDescription(): string
    {
        return "Bulk discount - " . ($this->discountPercent * 100) . "% off for {$this->minQuantity}+ items";
    }
}

class SeasonalDiscountStrategy implements PricingStrategyInterface
{
    private float $discountPercent;
    private string $season;

    public function __construct(float $discountPercent = 0.2, string $season = 'holiday')
    {
        $this->discountPercent = $discountPercent;
        $this->season = $season;
    }

    public function calculatePrice(float $basePrice, array $context = []): float
    {
        return $basePrice * (1 - $this->discountPercent);
    }

    public function getDescription(): string
    {
        return ucfirst($this->season) . " discount - " . ($this->discountPercent * 100) . "% off";
    }
}

class VIPPricingStrategy implements PricingStrategyInterface
{
    private float $discountPercent;

    public function __construct(float $discountPercent = 0.15)
    {
        $this->discountPercent = $discountPercent;
    }

    public function calculatePrice(float $basePrice, array $context = []): float
    {
        return $basePrice * (1 - $this->discountPercent);
    }

    public function getDescription(): string
    {
        return "VIP pricing - " . ($this->discountPercent * 100) . "% off all items";
    }
}

// Context class
class PriceCalculator
{
    private PricingStrategyInterface $strategy;

    public function __construct(PricingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(PricingStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
        echo "Pricing strategy changed to: " . $strategy->getDescription() . "<br>";
    }

    public function calculatePrice(float $basePrice, array $context = []): array
    {
        $finalPrice = $this->strategy->calculatePrice($basePrice, $context);
        $discount = $basePrice - $finalPrice;

        return [
            'base_price' => $basePrice,
            'final_price' => $finalPrice,
            'discount' => $discount,
            'strategy' => $this->strategy->getDescription()
        ];
    }
}

echo "<h4>Testing Strategy Pattern:</h4>";

$basePrice = 100.0;
$calculator = new PriceCalculator(new RegularPricingStrategy());

// Test different strategies
$strategies = [
    new RegularPricingStrategy(),
    new BulkDiscountStrategy(5, 0.1),
    new SeasonalDiscountStrategy(0.25, 'winter'),
    new VIPPricingStrategy(0.2)
];

foreach ($strategies as $strategy) {
    $calculator->setStrategy($strategy);

    $result = $calculator->calculatePrice($basePrice, ['quantity' => 8]);

    echo "Base: $" . number_format($result['base_price'], 2) .
         ", Final: $" . number_format($result['final_price'], 2) .
         ", Discount: $" . number_format($result['discount'], 2) . "<br>";
    echo "<br>";
}

echo "<hr>";

echo "<h3>C. Command Pattern</h3>";

// Command interface
interface CommandInterface
{
    public function execute(): void;
    public function undo(): void;
    public function getDescription(): string;
}

// Receiver (the object that knows how to perform operations)
class Document
{
    private string $content;
    private array $history = [];

    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->history[] = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
        $this->history[] = $content;
    }

    public function appendText(string $text): void
    {
        $this->content .= $text;
        $this->history[] = $this->content;
    }

    public function replaceText(string $search, string $replace): void
    {
        $this->content = str_replace($search, $replace, $this->content);
        $this->history[] = $this->content;
    }

    public function restoreFromHistory(int $index): void
    {
        if (isset($this->history[$index])) {
            $this->content = $this->history[$index];
        }
    }

    public function getHistoryCount(): int
    {
        return count($this->history);
    }
}

// Concrete commands
class WriteCommand implements CommandInterface
{
    private Document $document;
    private string $text;
    private string $previousContent;

    public function __construct(Document $document, string $text)
    {
        $this->document = $document;
        $this->text = $text;
    }

    public function execute(): void
    {
        $this->previousContent = $this->document->getContent();
        $this->document->appendText($this->text);
        echo "Executed: Write '{$this->text}'<br>";
    }

    public function undo(): void
    {
        $this->document->setContent($this->previousContent);
        echo "Undone: Write '{$this->text}'<br>";
    }

    public function getDescription(): string
    {
        return "Write: '{$this->text}'";
    }
}

class ReplaceCommand implements CommandInterface
{
    private Document $document;
    private string $search;
    private string $replace;
    private string $previousContent;

    public function __construct(Document $document, string $search, string $replace)
    {
        $this->document = $document;
        $this->search = $search;
        $this->replace = $replace;
    }

    public function execute(): void
    {
        $this->previousContent = $this->document->getContent();
        $this->document->replaceText($this->search, $this->replace);
        echo "Executed: Replace '{$this->search}' with '{$this->replace}'<br>";
    }

    public function undo(): void
    {
        $this->document->setContent($this->previousContent);
        echo "Undone: Replace '{$this->search}' with '{$this->replace}'<br>";
    }

    public function getDescription(): string
    {
        return "Replace: '{$this->search}' → '{$this->replace}'";
    }
}

// Invoker
class DocumentEditor
{
    private array $history = [];
    private int $currentPosition = -1;

    public function executeCommand(CommandInterface $command): void
    {
        // Remove any commands after current position (for redo functionality)
        $this->history = array_slice($this->history, 0, $this->currentPosition + 1);

        $command->execute();
        $this->history[] = $command;
        $this->currentPosition++;
    }

    public function undo(): void
    {
        if ($this->currentPosition >= 0) {
            $command = $this->history[$this->currentPosition];
            $command->undo();
            $this->currentPosition--;
            echo "Undo successful<br>";
        } else {
            echo "Nothing to undo<br>";
        }
    }

    public function redo(): void
    {
        if ($this->currentPosition < count($this->history) - 1) {
            $this->currentPosition++;
            $command = $this->history[$this->currentPosition];
            $command->execute();
            echo "Redo successful<br>";
        } else {
            echo "Nothing to redo<br>";
        }
    }

    public function getHistory(): array
    {
        return array_map(function($command) {
            return $command->getDescription();
        }, $this->history);
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}

echo "<h4>Testing Command Pattern:</h4>";

$document = new Document("Hello ");
$editor = new DocumentEditor();

echo "Initial content: '" . $document->getContent() . "'<br><br>";

// Execute commands
$writeCommand1 = new WriteCommand($document, "World");
$editor->executeCommand($writeCommand1);
echo "Content: '" . $document->getContent() . "'<br><br>";

$writeCommand2 = new WriteCommand($document, "!");
$editor->executeCommand($writeCommand2);
echo "Content: '" . $document->getContent() . "'<br><br>";

$replaceCommand = new ReplaceCommand($document, "World", "PHP");
$editor->executeCommand($replaceCommand);
echo "Content: '" . $document->getContent() . "'<br><br>";

// Undo operations
echo "Performing undo operations:<br>";
$editor->undo();
echo "Content: '" . $document->getContent() . "'<br><br>";

$editor->undo();
echo "Content: '" . $document->getContent() . "'<br><br>";

// Redo operations
echo "Performing redo operations:<br>";
$editor->redo();
echo "Content: '" . $document->getContent() . "'<br><br>";

echo "Command history: " . implode(", ", $editor->getHistory()) . "<br>";
echo "Current position: " . $editor->getCurrentPosition() . "<br>";

echo "<hr>";

echo "<h2>4. Pattern Combination Example</h2>";

// E-commerce system using multiple patterns
echo "<h3>E-Commerce System with Multiple Patterns</h3>";

// Product with Builder pattern
class Product
{
    private int $id;
    private string $name;
    private float $price;
    private string $category;
    private array $features = [];
    private float $weight;

    public function __construct(int $id, string $name, float $price, string $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function addFeature(string $feature): void
    {
        $this->features[] = $feature;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getCategory(): string { return $this->category; }
    public function getFeatures(): array { return $this->features; }
    public function getWeight(): float { return $this->weight; }

    public function getInfo(): string
    {
        return "{$this->name} (#{$this->id}) - ${$this->price} - {$this->category}";
    }
}

// Order with Observer pattern
class Order implements SubjectInterface
{
    private int $id;
    private array $items = [];
    private float $total = 0;
    private string $status = 'pending';
    private array $observers = [];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function attach(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(ObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notify(string $event, array $data): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($event, $data);
        }
    }

    public function addItem(Product $product, int $quantity = 1): void
    {
        $this->items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $product->getPrice() * $quantity
        ];

        $this->calculateTotal();

        $this->notify('item_added', [
            'order_id' => $this->id,
            'product' => $product->getName(),
            'quantity' => $quantity
        ]);
    }

    public function updateStatus(string $status): void
    {
        $oldStatus = $this->status;
        $this->status = $status;

        $this->notify('status_changed', [
            'order_id' => $this->id,
            'old_status' => $oldStatus,
            'new_status' => $status
        ]);
    }

    private function calculateTotal(): void
    {
        $this->total = array_sum(array_column($this->items, 'subtotal'));
    }

    public function getId(): int { return $this->id; }
    public function getTotal(): float { return $this->total; }
    public function getStatus(): string { return $this->status; }
    public function getItems(): array { return $this->items; }
}

// Order observers
class InventoryObserver implements ObserverInterface
{
    public function update(string $event, array $data): void
    {
        switch ($event) {
            case 'item_added':
                echo "INVENTORY: Reducing stock for {$data['product']} (qty: {$data['quantity']})<br>";
                break;
            case 'status_changed':
                if ($data['new_status'] === 'cancelled') {
                    echo "INVENTORY: Restoring stock for order #{$data['order_id']}<br>";
                }
                break;
        }
    }
}

class ShippingObserver implements ObserverInterface
{
    public function update(string $event, array $data): void
    {
        switch ($event) {
            case 'status_changed':
                if ($data['new_status'] === 'confirmed') {
                    echo "SHIPPING: Preparing shipment for order #{$data['order_id']}<br>";
                } elseif ($data['new_status'] === 'shipped') {
                    echo "SHIPPING: Order #{$data['order_id']} has been shipped<br>";
                }
                break;
        }
    }
}

// Factory for creating different product types
class ProductFactory
{
    public static function createElectronics(int $id, string $name, float $price): Product
    {
        $product = new Product($id, $name, $price, 'Electronics');
        $product->addFeature('Warranty: 2 years');
        $product->addFeature('Free shipping');
        return $product;
    }

    public static function createBook(int $id, string $name, float $price): Product
    {
        $product = new Product($id, $name, $price, 'Books');
        $product->addFeature('Digital version included');
        $product->setWeight(0.5);
        return $product;
    }

    public static function createClothing(int $id, string $name, float $price): Product
    {
        $product = new Product($id, $name, $price, 'Clothing');
        $product->addFeature('30-day return policy');
        $product->addFeature('Size exchange available');
        return $product;
    }
}

echo "<h4>Testing E-Commerce System:</h4>";

// Create products using Factory
$laptop = ProductFactory::createElectronics(1, 'Gaming Laptop', 1299.99);
$book = ProductFactory::createBook(2, 'PHP Design Patterns', 49.99);
$shirt = ProductFactory::createClothing(3, 'Cotton T-Shirt', 19.99);

echo "Products created:<br>";
echo "- " . $laptop->getInfo() . "<br>";
echo "- " . $book->getInfo() . "<br>";
echo "- " . $shirt->getInfo() . "<br><br>";

// Create order with observers
$order = new Order(1001);
$order->attach(new InventoryObserver());
$order->attach(new ShippingObserver());
$order->attach(new LoggerObserver());

// Add items to order
echo "Adding items to order:<br>";
$order->addItem($laptop, 1);
$order->addItem($book, 2);
$order->addItem($shirt, 3);

echo "<br>Order total: $" . number_format($order->getTotal(), 2) . "<br><br>";

// Update order status
echo "Updating order status:<br>";
$order->updateStatus('confirmed');
$order->updateStatus('shipped');

echo "<hr>";

echo "<h2>Kesimpulan</h2>";
echo "<p><strong>Design Patterns memberikan:</strong></p>";
echo "<ul>";
echo "<li>Solusi yang teruji untuk masalah umum dalam software design</li>";
echo "<li>Vocabulary umum antar developer untuk komunikasi yang lebih efektif</li>";
echo "<li>Code yang lebih maintainable, extensible, dan reusable</li>";
echo "<li>Best practices yang sudah terbukti dalam industri</li>";
echo "<li>Framework untuk menyelesaikan masalah desain yang kompleks</li>";
echo "</ul>";

echo "<p><strong>Key Takeaways:</strong></p>";
echo "<ul>";
echo "<li><strong>Creational Patterns:</strong> Mengatur cara pembuatan objek</li>";
echo "<li><strong>Structural Patterns:</strong> Mengatur komposisi dan hubungan objek</li>";
echo "<li><strong>Behavioral Patterns:</strong> Mengatur interaksi dan komunikasi objek</li>";
echo "<li><strong>Pattern Combination:</strong> Multiple patterns dapat digunakan bersama</li>";
echo "<li><strong>Context Matters:</strong> Pilih pattern yang sesuai dengan kebutuhan</li>";
echo "</ul>";

echo "<p><strong>Best Practices:</strong></p>";
echo "<ul>";
echo "<li>Jangan over-engineer - gunakan pattern ketika benar-benar dibutuhkan</li>";
echo "<li>Pahami trade-offs dari setiap pattern</li>";
echo "<li>Kombinasikan dengan SOLID principles</li>";
echo "<li>Document design decisions untuk team</li>";
echo "<li>Test thoroughly dan maintain code quality</li>";
echo "</ul>";

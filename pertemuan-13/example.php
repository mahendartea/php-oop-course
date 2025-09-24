<?php

/**
 * Pertemuan 13: Prinsip SOLID
 * Contoh implementasi 5 prinsip SOLID dalam PHP OOP
 */

echo "<h1>Pertemuan 13: Prinsip SOLID</h1>";

echo "<h2>Contoh 1: Single Responsibility Principle (SRP)</h2>";

// ❌ BAD - Multiple responsibilities in one class
echo "<h3>Before SRP (Multiple Responsibilities):</h3>";

class UserManagerBad
{
    private array $users = [];
    private int $nextId = 1;

    // Responsibility 1: User data management
    public function createUser(string $name, string $email, string $password): int
    {
        // Responsibility 2: Validation
        if (empty($name)) {
            throw new InvalidArgumentException("Name is required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format");
        }

        if (strlen($password) < 6) {
            throw new InvalidArgumentException("Password must be at least 6 characters");
        }

        // Responsibility 3: Password hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Responsibility 4: Database operations
        $userId = $this->nextId++;
        $this->users[$userId] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Responsibility 5: Email sending
        $this->sendWelcomeEmail($email, $name);

        // Responsibility 6: Logging
        $this->logUserCreation($userId, $email);

        return $userId;
    }

    private function sendWelcomeEmail(string $email, string $name): void
    {
        echo "Sending welcome email to {$email}<br>";
        // Simulate email sending
    }

    private function logUserCreation(int $userId, string $email): void
    {
        echo "LOG: User {$userId} created with email {$email}<br>";
        // Simulate logging
    }

    public function getUser(int $id): ?array
    {
        return $this->users[$id] ?? null;
    }
}

echo "<h4>Testing Bad UserManager (Multiple Responsibilities):</h4>";

try {
    $badUserManager = new UserManagerBad();

    $userId1 = $badUserManager->createUser("John Doe", "john@example.com", "password123");
    echo "Created user with ID: {$userId1}<br>";

    $user = $badUserManager->getUser($userId1);
    echo "Retrieved user: " . $user['name'] . " (" . $user['email'] . ")<br>";
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
}

echo "<br>";

// ✅ GOOD - Separated responsibilities
echo "<h3>After SRP (Separated Responsibilities):</h3>";

// Responsibility 1: User data representation
class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $createdAt;

    public function __construct(int $id, string $name, string $email, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->createdAt
        ];
    }
}

// Responsibility 2: User validation
class UserValidator
{
    public function validate(string $name, string $email, string $password): void
    {
        $errors = [];

        if (empty($name)) {
            $errors[] = "Name is required";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException("Validation failed: " . implode(", ", $errors));
        }

        echo "Validation passed for user: {$name}<br>";
    }
}

// Responsibility 3: Password operations
class PasswordHasher
{
    public function hash(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo "Password hashed successfully<br>";
        return $hashedPassword;
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

// Responsibility 4: Database operations
class UserRepository
{
    private array $users = [];
    private int $nextId = 1;

    public function save(User $user): void
    {
        $this->users[$user->getId()] = $user;
        echo "User saved to database: {$user->getName()}<br>";
    }

    public function create(string $name, string $email, string $hashedPassword): User
    {
        $user = new User($this->nextId++, $name, $email, $hashedPassword);
        $this->save($user);
        return $user;
    }

    public function findById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }

    public function getAllUsers(): array
    {
        return $this->users;
    }
}

// Responsibility 5: Email operations
class EmailService
{
    public function sendWelcomeEmail(User $user): void
    {
        echo "Sending welcome email to {$user->getEmail()} for user {$user->getName()}<br>";
        // Simulate email sending
        echo "Email sent successfully<br>";
    }

    public function sendPasswordResetEmail(User $user, string $resetToken): void
    {
        echo "Sending password reset email to {$user->getEmail()}<br>";
        echo "Reset token: {$resetToken}<br>";
    }
}

// Responsibility 6: Logging operations
class Logger
{
    public function logUserCreation(User $user): void
    {
        echo "LOG: User {$user->getId()} ({$user->getName()}) created at {$user->getCreatedAt()}<br>";
    }

    public function logUserLogin(User $user): void
    {
        echo "LOG: User {$user->getId()} ({$user->getName()}) logged in at " . date('Y-m-d H:i:s') . "<br>";
    }

    public function logError(string $message): void
    {
        echo "ERROR LOG: {$message}<br>";
    }
}

// Service orchestrator
class UserService
{
    private UserValidator $validator;
    private PasswordHasher $passwordHasher;
    private UserRepository $repository;
    private EmailService $emailService;
    private Logger $logger;

    public function __construct(
        UserValidator $validator,
        PasswordHasher $passwordHasher,
        UserRepository $repository,
        EmailService $emailService,
        Logger $logger
    ) {
        $this->validator = $validator;
        $this->passwordHasher = $passwordHasher;
        $this->repository = $repository;
        $this->emailService = $emailService;
        $this->logger = $logger;
    }

    public function registerUser(string $name, string $email, string $password): User
    {
        try {
            $this->validator->validate($name, $email, $password);

            $hashedPassword = $this->passwordHasher->hash($password);

            $user = $this->repository->create($name, $email, $hashedPassword);

            $this->emailService->sendWelcomeEmail($user);

            $this->logger->logUserCreation($user);

            return $user;
        } catch (Exception $e) {
            $this->logger->logError("User registration failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->findById($id);
    }
}

echo "<h4>Testing Good UserService (Separated Responsibilities):</h4>";

// Create dependencies
$validator = new UserValidator();
$passwordHasher = new PasswordHasher();
$repository = new UserRepository();
$emailService = new EmailService();
$logger = new Logger();

// Create service
$userService = new UserService($validator, $passwordHasher, $repository, $emailService, $logger);

try {
    $user1 = $userService->registerUser("Alice Johnson", "alice@example.com", "securepass123");
    echo "User registered successfully: " . $user1->getName() . "<br>";

    $user2 = $userService->registerUser("Bob Smith", "bob@example.com", "password456");
    echo "User registered successfully: " . $user2->getName() . "<br>";

    echo "<br>";

    // Retrieve user
    $retrievedUser = $userService->getUserById(1);
    if ($retrievedUser) {
        echo "Retrieved user: " . $retrievedUser->getName() . " (" . $retrievedUser->getEmail() . ")<br>";
    }
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";

echo "<h2>Contoh 2: Open/Closed Principle (OCP)</h2>";

// ❌ BAD - Modification required for new functionality
echo "<h3>Before OCP (Requires Modification):</h3>";

class DiscountCalculatorBad
{
    public function calculateDiscount(string $customerType, float $amount): float
    {
        switch ($customerType) {
            case 'regular':
                return $amount * 0.05; // 5% discount
            case 'premium':
                return $amount * 0.10; // 10% discount
            case 'vip':
                return $amount * 0.15; // 15% discount
            default:
                return 0;
        }
    }
}

echo "<h4>Testing Bad Discount Calculator:</h4>";

$badCalculator = new DiscountCalculatorBad();

$customerTypes = ['regular', 'premium', 'vip'];
$amount = 1000;

foreach ($customerTypes as $type) {
    $discount = $badCalculator->calculateDiscount($type, $amount);
    echo "Customer type: {$type}, Amount: $" . number_format($amount, 2) . ", Discount: $" . number_format($discount, 2) . "<br>";
}

echo "<br>";

// ✅ GOOD - Open for extension, closed for modification
echo "<h3>After OCP (Extensible Design):</h3>";

interface DiscountStrategyInterface
{
    public function calculateDiscount(float $amount): float;
    public function getCustomerType(): string;
    public function getDescription(): string;
}

class RegularCustomerDiscount implements DiscountStrategyInterface
{
    public function calculateDiscount(float $amount): float
    {
        return $amount * 0.05; // 5% discount
    }

    public function getCustomerType(): string
    {
        return 'regular';
    }

    public function getDescription(): string
    {
        return 'Regular Customer - 5% discount';
    }
}

class PremiumCustomerDiscount implements DiscountStrategyInterface
{
    public function calculateDiscount(float $amount): float
    {
        return $amount * 0.10; // 10% discount
    }

    public function getCustomerType(): string
    {
        return 'premium';
    }

    public function getDescription(): string
    {
        return 'Premium Customer - 10% discount';
    }
}

class VipCustomerDiscount implements DiscountStrategyInterface
{
    public function calculateDiscount(float $amount): float
    {
        return $amount * 0.15; // 15% discount
    }

    public function getCustomerType(): string
    {
        return 'vip';
    }

    public function getDescription(): string
    {
        return 'VIP Customer - 15% discount';
    }
}

// New customer type can be added without modifying existing code
class CorporateCustomerDiscount implements DiscountStrategyInterface
{
    public function calculateDiscount(float $amount): float
    {
        // Corporate customers get bulk discounts
        if ($amount >= 5000) {
            return $amount * 0.25; // 25% for large orders
        } elseif ($amount >= 2000) {
            return $amount * 0.20; // 20% for medium orders
        } else {
            return $amount * 0.15; // 15% for small orders
        }
    }

    public function getCustomerType(): string
    {
        return 'corporate';
    }

    public function getDescription(): string
    {
        return 'Corporate Customer - Tiered bulk discount (15-25%)';
    }
}

class StudentDiscount implements DiscountStrategyInterface
{
    public function calculateDiscount(float $amount): float
    {
        return min($amount * 0.20, 100); // 20% discount, max $100
    }

    public function getCustomerType(): string
    {
        return 'student';
    }

    public function getDescription(): string
    {
        return 'Student Discount - 20% off (max $100)';
    }
}

class DiscountCalculator
{
    private array $strategies = [];

    public function addStrategy(DiscountStrategyInterface $strategy): void
    {
        $this->strategies[$strategy->getCustomerType()] = $strategy;
        echo "Added discount strategy: {$strategy->getDescription()}<br>";
    }

    public function calculateDiscount(string $customerType, float $amount): float
    {
        if (isset($this->strategies[$customerType])) {
            return $this->strategies[$customerType]->calculateDiscount($amount);
        }

        echo "No discount strategy found for customer type: {$customerType}<br>";
        return 0;
    }

    public function getAvailableStrategies(): array
    {
        return array_keys($this->strategies);
    }

    public function getStrategyDescription(string $customerType): string
    {
        if (isset($this->strategies[$customerType])) {
            return $this->strategies[$customerType]->getDescription();
        }

        return "Unknown customer type";
    }
}

echo "<h4>Testing Good Discount Calculator (OCP Compliant):</h4>";

$calculator = new DiscountCalculator();

// Add strategies
$calculator->addStrategy(new RegularCustomerDiscount());
$calculator->addStrategy(new PremiumCustomerDiscount());
$calculator->addStrategy(new VipCustomerDiscount());
$calculator->addStrategy(new CorporateCustomerDiscount());
$calculator->addStrategy(new StudentDiscount());

echo "<br>";

// Test different amounts and customer types
$testCases = [
    ['regular', 1000],
    ['premium', 1000],
    ['vip', 1000],
    ['corporate', 1500],
    ['corporate', 3000],
    ['corporate', 6000],
    ['student', 500],
    ['student', 800],
];

foreach ($testCases as [$type, $amount]) {
    $discount = $calculator->calculateDiscount($type, $amount);
    $finalAmount = $amount - $discount;

    echo "Customer: {$type}, Amount: $" . number_format($amount, 2) .
        ", Discount: $" . number_format($discount, 2) .
        ", Final: $" . number_format($finalAmount, 2) . "<br>";
}

echo "<br>Available strategies: " . implode(', ', $calculator->getAvailableStrategies()) . "<br>";

echo "<hr>";

echo "<h2>Contoh 3: Liskov Substitution Principle (LSP)</h2>";

// ❌ BAD - Subclass changes expected behavior
echo "<h3>Before LSP (Violating Substitutability):</h3>";

class RectangleBad
{
    protected float $width;
    protected float $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getArea(): float
    {
        return $this->width * $this->height;
    }

    public function getInfo(): string
    {
        return "Rectangle: {$this->width} x {$this->height} = {$this->getArea()}";
    }
}

class SquareBad extends RectangleBad
{
    public function __construct(float $side)
    {
        parent::__construct($side, $side);
    }

    // Violating LSP - changing behavior
    public function setWidth(float $width): void
    {
        $this->width = $width;
        $this->height = $width; // This breaks LSP
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
        $this->width = $height; // This breaks LSP
    }

    public function getInfo(): string
    {
        return "Square: {$this->width} x {$this->height} = {$this->getArea()}";
    }
}

function testRectangleBad(RectangleBad $rectangle): void
{
    echo "Testing: " . $rectangle->getInfo() . "<br>";

    echo "Setting width to 10 and height to 5...<br>";
    $rectangle->setWidth(10);
    $rectangle->setHeight(5);

    $expectedArea = 10 * 5; // 50
    $actualArea = $rectangle->getArea();

    echo "Expected area: {$expectedArea}, Actual area: {$actualArea}<br>";

    if ($expectedArea === $actualArea) {
        echo "<span style='color: green;'>✓ Test passed</span><br>";
    } else {
        echo "<span style='color: red;'>✗ Test failed - LSP violation!</span><br>";
    }

    echo "Final state: " . $rectangle->getInfo() . "<br><br>";
}

echo "<h4>Testing Bad Rectangle/Square (LSP Violation):</h4>";

$rectangle = new RectangleBad(4, 6);
$square = new SquareBad(4);

testRectangleBad($rectangle); // This will pass
testRectangleBad($square);    // This will fail due to LSP violation

// ✅ GOOD - Proper abstraction respecting LSP
echo "<h3>After LSP (Proper Substitutability):</h3>";

interface ShapeInterface
{
    public function getArea(): float;
    public function getPerimeter(): float;
    public function getDescription(): string;
}

class Rectangle implements ShapeInterface
{
    private float $width;
    private float $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getArea(): float
    {
        return $this->width * $this->height;
    }

    public function getPerimeter(): float
    {
        return 2 * ($this->width + $this->height);
    }

    public function getDescription(): string
    {
        return "Rectangle: {$this->width} x {$this->height}";
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }
}

class Square implements ShapeInterface
{
    private float $side;

    public function __construct(float $side)
    {
        $this->side = $side;
    }

    public function getArea(): float
    {
        return $this->side * $this->side;
    }

    public function getPerimeter(): float
    {
        return 4 * $this->side;
    }

    public function getDescription(): string
    {
        return "Square: {$this->side} x {$this->side}";
    }

    public function setSide(float $side): void
    {
        $this->side = $side;
    }

    public function getSide(): float
    {
        return $this->side;
    }
}

class Circle implements ShapeInterface
{
    private float $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    public function getArea(): float
    {
        return pi() * $this->radius * $this->radius;
    }

    public function getPerimeter(): float
    {
        return 2 * pi() * $this->radius;
    }

    public function getDescription(): string
    {
        return "Circle: radius {$this->radius}";
    }

    public function setRadius(float $radius): void
    {
        $this->radius = $radius;
    }

    public function getRadius(): float
    {
        return $this->radius;
    }
}

class ShapeCalculator
{
    public function calculateTotalArea(array $shapes): float
    {
        $totalArea = 0;

        foreach ($shapes as $shape) {
            if ($shape instanceof ShapeInterface) {
                $totalArea += $shape->getArea();
                echo "Added {$shape->getDescription()}: area = " . number_format($shape->getArea(), 2) . "<br>";
            }
        }

        return $totalArea;
    }

    public function calculateTotalPerimeter(array $shapes): float
    {
        $totalPerimeter = 0;

        foreach ($shapes as $shape) {
            if ($shape instanceof ShapeInterface) {
                $totalPerimeter += $shape->getPerimeter();
                echo "Added {$shape->getDescription()}: perimeter = " . number_format($shape->getPerimeter(), 2) . "<br>";
            }
        }

        return $totalPerimeter;
    }

    public function getShapesSummary(array $shapes): array
    {
        $summary = [];

        foreach ($shapes as $shape) {
            if ($shape instanceof ShapeInterface) {
                $summary[] = [
                    'description' => $shape->getDescription(),
                    'area' => $shape->getArea(),
                    'perimeter' => $shape->getPerimeter()
                ];
            }
        }

        return $summary;
    }
}

echo "<h4>Testing Good Shapes (LSP Compliant):</h4>";

$shapes = [
    new Rectangle(10, 5),
    new Square(4),
    new Circle(3),
    new Rectangle(8, 6),
    new Square(7)
];

$calculator = new ShapeCalculator();

echo "Calculating total area:<br>";
$totalArea = $calculator->calculateTotalArea($shapes);
echo "Total area: " . number_format($totalArea, 2) . "<br><br>";

echo "Calculating total perimeter:<br>";
$totalPerimeter = $calculator->calculateTotalPerimeter($shapes);
echo "Total perimeter: " . number_format($totalPerimeter, 2) . "<br><br>";

echo "Shapes summary:<br>";
$summary = $calculator->getShapesSummary($shapes);
foreach ($summary as $shapeInfo) {
    echo "- {$shapeInfo['description']}: Area = " . number_format($shapeInfo['area'], 2) .
        ", Perimeter = " . number_format($shapeInfo['perimeter'], 2) . "<br>";
}

echo "<hr>";

echo "<h2>Contoh 4: Interface Segregation Principle (ISP)</h2>";

// ❌ BAD - Fat interface forcing unused methods
echo "<h3>Before ISP (Fat Interface):</h3>";

interface WorkerInterfaceBad
{
    public function work(): void;
    public function eat(): void;
    public function sleep(): void;
    public function takeBreak(): void;
}

class HumanWorkerBad implements WorkerInterfaceBad
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function work(): void
    {
        echo "{$this->name} (Human) is working<br>";
    }

    public function eat(): void
    {
        echo "{$this->name} (Human) is eating<br>";
    }

    public function sleep(): void
    {
        echo "{$this->name} (Human) is sleeping<br>";
    }

    public function takeBreak(): void
    {
        echo "{$this->name} (Human) is taking a break<br>";
    }
}

class RobotWorkerBad implements WorkerInterfaceBad
{
    private string $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function work(): void
    {
        echo "{$this->model} (Robot) is working<br>";
    }

    // Forced to implement methods that don't make sense for robots
    public function eat(): void
    {
        throw new BadMethodCallException("Robots don't eat!");
    }

    public function sleep(): void
    {
        throw new BadMethodCallException("Robots don't sleep!");
    }

    public function takeBreak(): void
    {
        echo "{$this->model} (Robot) is in standby mode<br>";
    }
}

echo "<h4>Testing Bad Worker Interface (ISP Violation):</h4>";

$human = new HumanWorkerBad("John");
$robot = new RobotWorkerBad("R2D2");

echo "Human worker:<br>";
$human->work();
$human->eat();
$human->sleep();
$human->takeBreak();

echo "<br>Robot worker:<br>";
$robot->work();
$robot->takeBreak();

try {
    $robot->eat(); // This will throw an exception
} catch (BadMethodCallException $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
}

echo "<br>";

// ✅ GOOD - Segregated interfaces
echo "<h3>After ISP (Segregated Interfaces):</h3>";

interface WorkableInterface
{
    public function work(): void;
}

interface EatableInterface
{
    public function eat(): void;
}

interface SleepableInterface
{
    public function sleep(): void;
}

interface RestableInterface
{
    public function takeBreak(): void;
}

interface MaintenanceInterface
{
    public function performMaintenance(): void;
    public function checkBatteryLevel(): int;
}

class HumanWorker implements WorkableInterface, EatableInterface, SleepableInterface, RestableInterface
{
    private string $name;
    private int $energyLevel = 100;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function work(): void
    {
        $this->energyLevel -= 20;
        echo "{$this->name} (Human) is working (Energy: {$this->energyLevel}%)<br>";
    }

    public function eat(): void
    {
        $this->energyLevel = min(100, $this->energyLevel + 30);
        echo "{$this->name} (Human) is eating (Energy restored to {$this->energyLevel}%)<br>";
    }

    public function sleep(): void
    {
        $this->energyLevel = 100;
        echo "{$this->name} (Human) is sleeping (Energy fully restored)<br>";
    }

    public function takeBreak(): void
    {
        $this->energyLevel = min(100, $this->energyLevel + 10);
        echo "{$this->name} (Human) is taking a break (Energy: {$this->energyLevel}%)<br>";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEnergyLevel(): int
    {
        return $this->energyLevel;
    }
}

class RobotWorker implements WorkableInterface, RestableInterface, MaintenanceInterface
{
    private string $model;
    private int $batteryLevel = 100;
    private int $hoursUntilMaintenance = 100;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function work(): void
    {
        $this->batteryLevel -= 15;
        $this->hoursUntilMaintenance--;
        echo "{$this->model} (Robot) is working (Battery: {$this->batteryLevel}%, Maintenance in {$this->hoursUntilMaintenance}h)<br>";
    }

    public function takeBreak(): void
    {
        echo "{$this->model} (Robot) is in standby mode (conserving battery)<br>";
    }

    public function performMaintenance(): void
    {
        $this->hoursUntilMaintenance = 100;
        echo "{$this->model} (Robot) maintenance completed (Next maintenance in {$this->hoursUntilMaintenance}h)<br>";
    }

    public function checkBatteryLevel(): int
    {
        return $this->batteryLevel;
    }

    public function recharge(): void
    {
        $this->batteryLevel = 100;
        echo "{$this->model} (Robot) battery recharged to 100%<br>";
    }

    public function getModel(): string
    {
        return $this->model;
    }
}

class AIWorker implements WorkableInterface, MaintenanceInterface
{
    private string $name;
    private int $processingPower = 100;
    private int $updatesUntilMaintenance = 50;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function work(): void
    {
        $this->processingPower -= 5;
        $this->updatesUntilMaintenance--;
        echo "{$this->name} (AI) is processing tasks (Power: {$this->processingPower}%, Updates until maintenance: {$this->updatesUntilMaintenance})<br>";
    }

    public function performMaintenance(): void
    {
        $this->updatesUntilMaintenance = 50;
        $this->processingPower = 100;
        echo "{$this->name} (AI) system updated and optimized<br>";
    }

    public function checkBatteryLevel(): int
    {
        return $this->processingPower; // AI uses processing power instead of battery
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class WorkManager
{
    public function manageWork(WorkableInterface $worker): void
    {
        $worker->work();
    }

    public function manageMealTime(EatableInterface $worker): void
    {
        $worker->eat();
    }

    public function manageSleepTime(SleepableInterface $worker): void
    {
        $worker->sleep();
    }

    public function manageBreakTime(RestableInterface $worker): void
    {
        $worker->takeBreak();
    }

    public function performMaintenance(MaintenanceInterface $worker): void
    {
        echo "Performing maintenance...<br>";
        $batteryLevel = $worker->checkBatteryLevel();
        echo "Current power level: {$batteryLevel}%<br>";
        $worker->performMaintenance();
    }

    public function assignWork(array $workers): void
    {
        echo "Assigning work to all workers:<br>";
        foreach ($workers as $worker) {
            if ($worker instanceof WorkableInterface) {
                $this->manageWork($worker);
            }
        }
        echo "<br>";
    }

    public function organizeBreaks(array $workers): void
    {
        echo "Organizing breaks:<br>";
        foreach ($workers as $worker) {
            if ($worker instanceof RestableInterface) {
                $this->manageBreakTime($worker);
            }
        }
        echo "<br>";
    }
}

echo "<h4>Testing Good Worker Interfaces (ISP Compliant):</h4>";

$human = new HumanWorker("Alice");
$robot = new RobotWorker("WALL-E");
$ai = new AIWorker("ChatBot-3000");

$manager = new WorkManager();

$workers = [$human, $robot, $ai];

// Assign work to all workers
$manager->assignWork($workers);

// Organize breaks (only for workers that can take breaks)
$manager->organizeBreaks($workers);

// Feed humans (only for eatable workers)
echo "Meal time for humans:<br>";
if ($human instanceof EatableInterface) {
    $manager->manageMealTime($human);
}
echo "<br>";

// Sleep time for humans (only for sleepable workers)
echo "Sleep time for humans:<br>";
if ($human instanceof SleepableInterface) {
    $manager->manageSleepTime($human);
}
echo "<br>";

// Maintenance for machines (only for maintainable workers)
echo "Maintenance time for machines:<br>";
if ($robot instanceof MaintenanceInterface) {
    $manager->performMaintenance($robot);
}
if ($ai instanceof MaintenanceInterface) {
    $manager->performMaintenance($ai);
}

echo "<hr>";

echo "<h2>Contoh 5: Dependency Inversion Principle (DIP)</h2>";

// ❌ BAD - Direct dependency on concrete classes
echo "<h3>Before DIP (Tight Coupling):</h3>";

class MySQLConnectionBad
{
    private string $host;
    private string $database;

    public function __construct(string $host, string $database)
    {
        $this->host = $host;
        $this->database = $database;
        echo "Connected to MySQL database: {$database} on {$host}<br>";
    }

    public function save(array $data): void
    {
        echo "Saving to MySQL: " . json_encode($data) . "<br>";
    }

    public function find(int $id): ?array
    {
        echo "Finding in MySQL with ID: {$id}<br>";
        return ['id' => $id, 'data' => 'sample from MySQL'];
    }
}

class EmailServiceBad
{
    public function send(string $to, string $subject, string $message): void
    {
        echo "Sending email via SMTP to {$to}: {$subject}<br>";
        echo "Message: {$message}<br>";
    }
}

class UserServiceBad
{
    private MySQLConnectionBad $database;
    private EmailServiceBad $emailService;

    public function __construct()
    {
        // Direct dependency on concrete classes - violates DIP
        $this->database = new MySQLConnectionBad('localhost', 'app_db');
        $this->emailService = new EmailServiceBad();
    }

    public function createUser(array $userData): void
    {
        $this->database->save($userData);
        $this->emailService->send(
            $userData['email'],
            'Welcome',
            "Welcome {$userData['name']}!"
        );
        echo "User created successfully<br>";
    }

    public function getUser(int $id): ?array
    {
        return $this->database->find($id);
    }
}

echo "<h4>Testing Bad UserService (DIP Violation):</h4>";

$badUserService = new UserServiceBad();
echo "<br>";

$badUserService->createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

$user = $badUserService->getUser(1);
if ($user) {
    echo "Retrieved user: " . json_encode($user) . "<br>";
}

echo "<br>";

// ✅ GOOD - Dependency on abstractions
echo "<h3>After DIP (Loose Coupling):</h3>";

// Abstractions (interfaces)
interface DatabaseInterface
{
    public function save(array $data): bool;
    public function find(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}

interface EmailServiceInterface
{
    public function send(string $to, string $subject, string $message): bool;
}

interface LoggerInterface
{
    public function log(string $level, string $message, array $context = []): void;
}

interface CacheInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value, int $ttl = 3600): bool;
    public function delete(string $key): bool;
}

// Concrete implementations
class MySQLConnection implements DatabaseInterface
{
    private string $host;
    private string $database;

    public function __construct(string $host, string $database)
    {
        $this->host = $host;
        $this->database = $database;
        echo "Connected to MySQL database: {$database} on {$host}<br>";
    }

    public function save(array $data): bool
    {
        echo "Saving to MySQL: " . json_encode($data) . "<br>";
        return true;
    }

    public function find(int $id): ?array
    {
        echo "Finding in MySQL with ID: {$id}<br>";
        return ['id' => $id, 'data' => 'sample from MySQL', 'source' => 'mysql'];
    }

    public function update(int $id, array $data): bool
    {
        echo "Updating in MySQL ID {$id}: " . json_encode($data) . "<br>";
        return true;
    }

    public function delete(int $id): bool
    {
        echo "Deleting from MySQL ID: {$id}<br>";
        return true;
    }
}

class PostgreSQLConnection implements DatabaseInterface
{
    private string $host;
    private string $database;

    public function __construct(string $host, string $database)
    {
        $this->host = $host;
        $this->database = $database;
        echo "Connected to PostgreSQL database: {$database} on {$host}<br>";
    }

    public function save(array $data): bool
    {
        echo "Saving to PostgreSQL: " . json_encode($data) . "<br>";
        return true;
    }

    public function find(int $id): ?array
    {
        echo "Finding in PostgreSQL with ID: {$id}<br>";
        return ['id' => $id, 'data' => 'sample from PostgreSQL', 'source' => 'postgresql'];
    }

    public function update(int $id, array $data): bool
    {
        echo "Updating in PostgreSQL ID {$id}: " . json_encode($data) . "<br>";
        return true;
    }

    public function delete(int $id): bool
    {
        echo "Deleting from PostgreSQL ID: {$id}<br>";
        return true;
    }
}

class SMTPEmailService implements EmailServiceInterface
{
    private string $server;

    public function __construct(string $server = 'smtp.example.com')
    {
        $this->server = $server;
        echo "SMTP Email Service initialized with server: {$server}<br>";
    }

    public function send(string $to, string $subject, string $message): bool
    {
        echo "Sending email via SMTP ({$this->server}) to {$to}: {$subject}<br>";
        echo "Message: {$message}<br>";
        return true;
    }
}

class SendGridEmailService implements EmailServiceInterface
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = substr($apiKey, 0, 10) . '...';
        echo "SendGrid Email Service initialized with API key: {$this->apiKey}<br>";
    }

    public function send(string $to, string $subject, string $message): bool
    {
        echo "Sending email via SendGrid API to {$to}: {$subject}<br>";
        echo "Message: {$message}<br>";
        return true;
    }
}

class FileLogger implements LoggerInterface
{
    private string $logFile;

    public function __construct(string $logFile = 'app.log')
    {
        $this->logFile = $logFile;
        echo "File logger initialized: {$logFile}<br>";
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | ' . json_encode($context) : '';
        echo "LOG [{$timestamp}] {$level}: {$message}{$contextStr}<br>";
    }
}

class RedisCache implements CacheInterface
{
    private string $host;
    private array $cache = [];

    public function __construct(string $host = 'localhost')
    {
        $this->host = $host;
        echo "Redis Cache connected to: {$host}<br>";
    }

    public function get(string $key): mixed
    {
        if (isset($this->cache[$key])) {
            echo "Cache HIT for key: {$key}<br>";
            return $this->cache[$key];
        }

        echo "Cache MISS for key: {$key}<br>";
        return null;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        $this->cache[$key] = $value;
        echo "Cache SET for key: {$key} (TTL: {$ttl}s)<br>";
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->cache[$key]);
        echo "Cache DELETE for key: {$key}<br>";
        return true;
    }
}

// Service that depends on abstractions
class UserService
{
    private DatabaseInterface $database;
    private EmailServiceInterface $emailService;
    private LoggerInterface $logger;
    private CacheInterface $cache;

    // Dependency injection - depends on abstractions, not concretions
    public function __construct(
        DatabaseInterface $database,
        EmailServiceInterface $emailService,
        LoggerInterface $logger,
        CacheInterface $cache
    ) {
        $this->database = $database;
        $this->emailService = $emailService;
        $this->logger = $logger;
        $this->cache = $cache;

        $this->logger->log('INFO', 'UserService initialized');
    }

    public function createUser(array $userData): bool
    {
        try {
            $this->logger->log('INFO', 'Creating user', ['email' => $userData['email']]);

            $result = $this->database->save($userData);

            if ($result) {
                $this->emailService->send(
                    $userData['email'],
                    'Welcome to Our Platform',
                    "Hello {$userData['name']}, welcome to our platform!"
                );

                // Cache user data
                $this->cache->set("user_email_{$userData['email']}", $userData);

                $this->logger->log('INFO', 'User created successfully', ['email' => $userData['email']]);
                return true;
            }

            $this->logger->log('ERROR', 'Failed to save user to database');
            return false;
        } catch (Exception $e) {
            $this->logger->log('ERROR', 'User creation failed', [
                'error' => $e->getMessage(),
                'email' => $userData['email'] ?? 'unknown'
            ]);
            return false;
        }
    }

    public function getUser(int $id): ?array
    {
        $cacheKey = "user_id_{$id}";

        // Try cache first
        $cachedUser = $this->cache->get($cacheKey);
        if ($cachedUser) {
            $this->logger->log('INFO', 'User retrieved from cache', ['id' => $id]);
            return $cachedUser;
        }

        // Get from database
        $user = $this->database->find($id);

        if ($user) {
            // Cache for future requests
            $this->cache->set($cacheKey, $user);
            $this->logger->log('INFO', 'User retrieved from database and cached', ['id' => $id]);
        } else {
            $this->logger->log('WARNING', 'User not found', ['id' => $id]);
        }

        return $user;
    }

    public function updateUser(int $id, array $data): bool
    {
        $this->logger->log('INFO', 'Updating user', ['id' => $id]);

        $result = $this->database->update($id, $data);

        if ($result) {
            // Invalidate cache
            $this->cache->delete("user_id_{$id}");
            $this->logger->log('INFO', 'User updated successfully', ['id' => $id]);
        } else {
            $this->logger->log('ERROR', 'Failed to update user', ['id' => $id]);
        }

        return $result;
    }

    public function deleteUser(int $id): bool
    {
        $this->logger->log('WARNING', 'Deleting user', ['id' => $id]);

        $result = $this->database->delete($id);

        if ($result) {
            // Invalidate cache
            $this->cache->delete("user_id_{$id}");
            $this->logger->log('WARNING', 'User deleted successfully', ['id' => $id]);
        } else {
            $this->logger->log('ERROR', 'Failed to delete user', ['id' => $id]);
        }

        return $result;
    }
}

echo "<h4>Testing Good UserService (DIP Compliant):</h4>";

// Easy to swap implementations - just change the concrete classes
echo "<h5>Configuration 1: MySQL + SMTP + File Logger + Redis Cache</h5>";

$userService1 = new UserService(
    new MySQLConnection('localhost', 'app_db'),
    new SMTPEmailService('smtp.gmail.com'),
    new FileLogger('app.log'),
    new RedisCache('localhost')
);

echo "<br>";

$userService1->createUser([
    'name' => 'Alice Johnson',
    'email' => 'alice@example.com'
]);

echo "<br>";

$user1 = $userService1->getUser(1);
if ($user1) {
    echo "Retrieved user: " . json_encode($user1) . "<br>";
}

echo "<br>";

// Easily switch to different implementations
echo "<h5>Configuration 2: PostgreSQL + SendGrid + Same Logger & Cache</h5>";

$userService2 = new UserService(
    new PostgreSQLConnection('localhost', 'app_db'),
    new SendGridEmailService('sg.abc123def456'),
    new FileLogger('app.log'),
    new RedisCache('localhost')
);

echo "<br>";

$userService2->createUser([
    'name' => 'Bob Wilson',
    'email' => 'bob@example.com'
]);

echo "<br>";

$user2 = $userService2->getUser(2);
if ($user2) {
    echo "Retrieved user: " . json_encode($user2) . "<br>";
}

echo "<br>";

// Test update and delete
echo "<h5>Testing Update and Delete Operations:</h5>";

$userService1->updateUser(1, ['name' => 'Alice Johnson-Smith']);
$userService1->deleteUser(1);

echo "<hr>";

echo "<h2>Kesimpulan</h2>";
echo "<p>Prinsip SOLID memberikan:</p>";
echo "<ul>";
echo "<li><strong>Single Responsibility:</strong> Setiap class punya satu tanggung jawab yang jelas</li>";
echo "<li><strong>Open/Closed:</strong> Mudah extend tanpa modify existing code</li>";
echo "<li><strong>Liskov Substitution:</strong> Subclass dapat menggantikan parent class</li>";
echo "<li><strong>Interface Segregation:</strong> Interface yang focused dan specific</li>";
echo "<li><strong>Dependency Inversion:</strong> Depend on abstractions, bukan concretions</li>";
echo "</ul>";

echo "<br><strong>Key Benefits:</strong><br>";
echo "<ul>";
echo "<li>Code yang lebih maintainable dan extensible</li>";
echo "<li>Easier unit testing dengan dependency injection</li>";
echo "<li>Loose coupling between components</li>";
echo "<li>Better code reusability dan modularity</li>";
echo "<li>Cleaner architecture dan separation of concerns</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Start dengan interfaces untuk define contracts</li>";
echo "<li>Use dependency injection untuk loose coupling</li>";
echo "<li>Keep classes focused pada single responsibility</li>";
echo "<li>Design untuk extension dengan abstraction</li>";
echo "<li>Apply SOLID principles progressively dalam refactoring</li>";
echo "</ul>";

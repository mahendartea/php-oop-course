<?php

/**
 * Pertemuan 12: Error Handling dan Exception
 * Contoh implementasi error handling dan exception dalam PHP OOP
 */

echo "<h1>Pertemuan 12: Error Handling dan Exception</h1>";

echo "<h2>Contoh 1: Basic Exception Handling</h2>";

// Custom Exception Classes
class ValidationException extends Exception
{
    private array $errors = [];

    public function __construct(string $message, array $errors = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }
}

class UserNotFoundException extends Exception
{
    private $userId;

    public function __construct($userId, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->userId = $userId;
        $message = $message ?: "User with ID {$userId} not found";
        parent::__construct($message, $code, $previous);
    }

    public function getUserId()
    {
        return $this->userId;
    }
}

class InsufficientFundsException extends Exception
{
    private float $balance;
    private float $requestedAmount;

    public function __construct(float $balance, float $requestedAmount)
    {
        $this->balance = $balance;
        $this->requestedAmount = $requestedAmount;

        $message = sprintf(
            "Insufficient funds. Balance: $%.2f, Requested: $%.2f (Shortage: $%.2f)",
            $balance,
            $requestedAmount,
            $requestedAmount - $balance
        );

        parent::__construct($message);
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getRequestedAmount(): float
    {
        return $this->requestedAmount;
    }

    public function getShortage(): float
    {
        return $this->requestedAmount - $this->balance;
    }
}

// User class with validation
class User
{
    private int $id;
    private string $name;
    private string $email;
    private float $balance;
    private DateTime $createdAt;

    public function __construct(int $id, string $name, string $email, float $balance = 0.0)
    {
        $this->validateInput($name, $email, $balance);

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->balance = $balance;
        $this->createdAt = new DateTime();

        echo "User created: {$name} ({$email}) with balance $" . number_format($balance, 2) . "<br>";
    }

    private function validateInput(string $name, string $email, float $balance): void
    {
        $errors = [];

        if (empty(trim($name))) {
            $errors['name'] = 'Name is required';
        } elseif (strlen(trim($name)) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long';
        }

        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if ($balance < 0) {
            $errors['balance'] = 'Balance cannot be negative';
        }

        if (!empty($errors)) {
            throw new ValidationException("User validation failed", $errors);
        }
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

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Deposit amount must be positive, got: $" . number_format($amount, 2));
        }

        $this->balance += $amount;
        echo "Deposited $" . number_format($amount, 2) . " to {$this->name}'s account. New balance: $" . number_format($this->balance, 2) . "<br>";
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Withdrawal amount must be positive, got: $" . number_format($amount, 2));
        }

        if ($amount > $this->balance) {
            throw new InsufficientFundsException($this->balance, $amount);
        }

        $this->balance -= $amount;
        echo "Withdrew $" . number_format($amount, 2) . " from {$this->name}'s account. New balance: $" . number_format($this->balance, 2) . "<br>";
    }

    public function transfer(User $recipient, float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Transfer amount must be positive");
        }

        if ($recipient->getId() === $this->id) {
            throw new InvalidArgumentException("Cannot transfer money to yourself");
        }

        // This will throw InsufficientFundsException if balance is insufficient
        $this->withdraw($amount);
        $recipient->deposit($amount);

        echo "Transferred $" . number_format($amount, 2) . " from {$this->name} to {$recipient->getName()}<br>";
    }

    public function getInfo(): string
    {
        return sprintf(
            "User #%d: %s (%s) - Balance: $%.2f - Created: %s",
            $this->id,
            $this->name,
            $this->email,
            $this->balance,
            $this->createdAt->format('Y-m-d H:i:s')
        );
    }
}

echo "<h3>Testing Basic Exception Handling:</h3>";

// Test valid user creation
try {
    echo "<h4>Creating Valid Users:</h4>";
    $user1 = new User(1, "John Doe", "john@example.com", 1000.00);
    $user2 = new User(2, "Jane Smith", "jane@example.com", 500.00);
    echo "<br>";
} catch (ValidationException $e) {
    echo "<span style='color: red;'>Validation Error: " . $e->getMessage() . "</span><br>";
    if ($e->hasErrors()) {
        echo "Validation errors:<br>";
        foreach ($e->getErrors() as $field => $error) {
            echo "- {$field}: {$error}<br>";
        }
    }
} catch (Exception $e) {
    echo "<span style='color: red;'>Unexpected error: " . $e->getMessage() . "</span><br>";
}

// Test invalid user creation
try {
    echo "<h4>Creating Invalid User (should fail):</h4>";
    $invalidUser = new User(3, "", "invalid-email", -100);
} catch (ValidationException $e) {
    echo "<span style='color: red;'>Validation Error: " . $e->getMessage() . "</span><br>";
    if ($e->hasErrors()) {
        echo "Validation errors:<br>";
        foreach ($e->getErrors() as $field => $error) {
            echo "- {$field}: {$error}<br>";
        }
    }
} catch (Exception $e) {
    echo "<span style='color: red;'>Unexpected error: " . $e->getMessage() . "</span><br>";
}

echo "<br>";

// Test financial operations with exception handling
echo "<h4>Testing Financial Operations:</h4>";

try {
    // Valid deposit
    $user1->deposit(200);

    // Valid withdrawal
    $user1->withdraw(150);

    // Valid transfer
    $user1->transfer($user2, 300);
} catch (InvalidArgumentException $e) {
    echo "<span style='color: red;'>Invalid Argument: " . $e->getMessage() . "</span><br>";
} catch (InsufficientFundsException $e) {
    echo "<span style='color: red;'>Insufficient Funds: " . $e->getMessage() . "</span><br>";
    echo "Available balance: $" . number_format($e->getBalance(), 2) . "<br>";
    echo "Shortage: $" . number_format($e->getShortage(), 2) . "<br>";
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
}

echo "<br>";

// Test operations that will fail
echo "<h4>Testing Operations That Should Fail:</h4>";

try {
    echo "Attempting to withdraw $2000 from user with insufficient funds...<br>";
    $user2->withdraw(2000);  // This should fail

} catch (InsufficientFundsException $e) {
    echo "<span style='color: red;'>Expected Error: " . $e->getMessage() . "</span><br>";
    echo "User balance: $" . number_format($e->getBalance(), 2) . "<br>";
    echo "Requested amount: $" . number_format($e->getRequestedAmount(), 2) . "<br>";
}

echo "<br>";

try {
    echo "Attempting invalid deposit amount...<br>";
    $user1->deposit(-50);  // This should fail

} catch (InvalidArgumentException $e) {
    echo "<span style='color: red;'>Expected Error: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";

echo "<h2>Contoh 2: UserRepository dengan Database Exception Simulation</h2>";

// Simulate database exceptions
class DatabaseConnectionException extends Exception
{
    public function __construct(string $host, int $port, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = $message ?: "Failed to connect to database at {$host}:{$port}";
        parent::__construct($message, $code, $previous);
    }
}

class QueryExecutionException extends Exception
{
    private string $query;

    public function __construct(string $message, string $query = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}

// Mock database connection
class MockDatabase
{
    private bool $connected = true;
    private array $users = [];
    private int $nextId = 1;

    public function __construct(bool $simulateConnectionFailure = false)
    {
        if ($simulateConnectionFailure) {
            throw new DatabaseConnectionException("localhost", 3306, "Connection refused");
        }

        // Pre-populate with some test data
        $this->users = [
            1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'created_at' => '2023-01-01 10:00:00'],
            2 => ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'created_at' => '2023-01-02 11:00:00'],
            3 => ['id' => 3, 'name' => 'Bob Wilson', 'email' => 'bob@example.com', 'created_at' => '2023-01-03 12:00:00']
        ];
        $this->nextId = 4;

        echo "Mock database connected successfully<br>";
    }

    public function findById(int $id): ?array
    {
        $this->checkConnection();

        // Simulate random query failures
        if (rand(1, 10) === 1) {
            throw new QueryExecutionException("Query execution failed", "SELECT * FROM users WHERE id = {$id}");
        }

        return $this->users[$id] ?? null;
    }

    public function findByEmail(string $email): ?array
    {
        $this->checkConnection();

        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return null;
    }

    public function create(array $data): int
    {
        $this->checkConnection();

        // Simulate constraint violation
        if ($this->findByEmail($data['email'])) {
            throw new QueryExecutionException("Duplicate entry for email", "INSERT INTO users...");
        }

        $id = $this->nextId++;
        $this->users[$id] = array_merge($data, ['id' => $id, 'created_at' => date('Y-m-d H:i:s')]);

        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $this->checkConnection();

        if (!isset($this->users[$id])) {
            return false;
        }

        $this->users[$id] = array_merge($this->users[$id], $data);
        return true;
    }

    public function delete(int $id): bool
    {
        $this->checkConnection();

        if (!isset($this->users[$id])) {
            return false;
        }

        unset($this->users[$id]);
        return true;
    }

    private function checkConnection(): void
    {
        if (!$this->connected) {
            throw new DatabaseConnectionException("localhost", 3306, "Database connection lost");
        }
    }

    public function disconnect(): void
    {
        $this->connected = false;
        echo "Database connection closed<br>";
    }
}

// UserRepository with proper exception handling
class UserRepository
{
    private MockDatabase $db;

    public function __construct(MockDatabase $db)
    {
        $this->db = $db;
        echo "UserRepository initialized<br>";
    }

    public function findById(int $id): array
    {
        try {
            if ($id <= 0) {
                throw new InvalidArgumentException("User ID must be a positive integer, got: {$id}");
            }

            $user = $this->db->findById($id);

            if (!$user) {
                throw new UserNotFoundException($id);
            }

            echo "Found user: {$user['name']} ({$user['email']})<br>";
            return $user;
        } catch (QueryExecutionException $e) {
            echo "<span style='color: red;'>Database query failed: " . $e->getMessage() . "</span><br>";
            echo "Query: " . $e->getQuery() . "<br>";
            throw $e;
        } catch (DatabaseConnectionException $e) {
            echo "<span style='color: red;'>Database connection error: " . $e->getMessage() . "</span><br>";
            throw $e;
        }
    }

    public function findByEmail(string $email): array
    {
        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException("Invalid email format: {$email}");
            }

            $user = $this->db->findByEmail($email);

            if (!$user) {
                throw new UserNotFoundException($email, "User with email {$email} not found");
            }

            echo "Found user by email: {$user['name']} ({$user['email']})<br>";
            return $user;
        } catch (QueryExecutionException $e) {
            echo "<span style='color: red;'>Database query failed: " . $e->getMessage() . "</span><br>";
            throw $e;
        }
    }

    public function create(array $userData): int
    {
        try {
            $this->validateUserData($userData);

            $userId = $this->db->create($userData);
            echo "User created with ID: {$userId}<br>";

            return $userId;
        } catch (ValidationException $e) {
            echo "<span style='color: red;'>Validation failed: " . $e->getMessage() . "</span><br>";
            foreach ($e->getErrors() as $field => $error) {
                echo "- {$field}: {$error}<br>";
            }
            throw $e;
        } catch (QueryExecutionException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "<span style='color: red;'>Email already exists: {$userData['email']}</span><br>";
                throw new InvalidArgumentException("Email already exists: {$userData['email']}", 0, $e);
            }

            echo "<span style='color: red;'>Database error: " . $e->getMessage() . "</span><br>";
            throw $e;
        }
    }

    private function validateUserData(array $data): void
    {
        $errors = [];

        if (empty($data['name']) || strlen(trim($data['name'])) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        if (!empty($errors)) {
            throw new ValidationException("User data validation failed", $errors);
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            if ($id <= 0) {
                throw new InvalidArgumentException("User ID must be positive");
            }

            // Validate partial data (only provided fields)
            if (isset($data['name']) && strlen(trim($data['name'])) < 2) {
                throw new ValidationException("Name validation failed", ['name' => 'Name must be at least 2 characters long']);
            }

            if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Email validation failed", ['email' => 'Valid email is required']);
            }

            $success = $this->db->update($id, $data);

            if (!$success) {
                throw new UserNotFoundException($id);
            }

            echo "User {$id} updated successfully<br>";
        } catch (ValidationException $e) {
            echo "<span style='color: red;'>Update validation failed: " . $e->getMessage() . "</span><br>";
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        try {
            if ($id <= 0) {
                throw new InvalidArgumentException("User ID must be positive");
            }

            $success = $this->db->delete($id);

            if (!$success) {
                throw new UserNotFoundException($id);
            }

            echo "User {$id} deleted successfully<br>";
        } catch (DatabaseConnectionException $e) {
            echo "<span style='color: red;'>Cannot delete user - database connection lost</span><br>";
            throw $e;
        }
    }
}

echo "<h3>Testing UserRepository with Exception Handling:</h3>";

try {
    // Create database connection
    $database = new MockDatabase();
    $userRepo = new UserRepository($database);
    echo "<br>";

    // Test successful operations
    echo "<h4>Successful Operations:</h4>";

    // Find existing users
    $user1 = $userRepo->findById(1);
    $user2 = $userRepo->findByEmail('jane@example.com');

    echo "<br>";

    // Create new user
    $newUserId = $userRepo->create([
        'name' => 'Alice Johnson',
        'email' => 'alice@example.com'
    ]);

    echo "<br>";

    // Update user
    $userRepo->update($newUserId, ['name' => 'Alice Johnson-Smith']);

    echo "<br>";
} catch (Exception $e) {
    echo "<span style='color: red;'>Unexpected error: " . $e->getMessage() . "</span><br>";
}

// Test operations that should fail
echo "<h4>Operations That Should Fail:</h4>";

try {
    echo "Attempting to find non-existent user...<br>";
    $userRepo->findById(999);
} catch (UserNotFoundException $e) {
    echo "<span style='color: red;'>Expected Error: " . $e->getMessage() . "</span><br>";
    echo "User ID: " . $e->getUserId() . "<br>";
} catch (Exception $e) {
    echo "<span style='color: red;'>Unexpected error: " . $e->getMessage() . "</span><br>";
}

echo "<br>";

try {
    echo "Attempting to create user with invalid data...<br>";
    $userRepo->create([
        'name' => 'A',  // Too short
        'email' => 'invalid-email'  // Invalid format
    ]);
} catch (ValidationException $e) {
    echo "<span style='color: red;'>Expected Validation Error: " . $e->getMessage() . "</span><br>";
    foreach ($e->getErrors() as $field => $error) {
        echo "- {$field}: {$error}<br>";
    }
} catch (Exception $e) {
    echo "<span style='color: red;'>Unexpected error: " . $e->getMessage() . "</span><br>";
}

echo "<br>";

try {
    echo "Attempting to create user with duplicate email...<br>";
    $userRepo->create([
        'name' => 'John Duplicate',
        'email' => 'john@example.com'  // Already exists
    ]);
} catch (InvalidArgumentException $e) {
    echo "<span style='color: red;'>Expected Error: " . $e->getMessage() . "</span><br>";
} catch (Exception $e) {
    echo "<span style='color: red;'>Unexpected error: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";

echo "<h2>Contoh 3: Service Layer dengan Comprehensive Error Handling</h2>";

// Logger interface and implementation
interface LoggerInterface
{
    public function emergency(string $message, array $context = []): void;
    public function alert(string $message, array $context = []): void;
    public function critical(string $message, array $context = []): void;
    public function error(string $message, array $context = []): void;
    public function warning(string $message, array $context = []): void;
    public function notice(string $message, array $context = []): void;
    public function info(string $message, array $context = []): void;
    public function debug(string $message, array $context = []): void;
}

class ConsoleLogger implements LoggerInterface
{
    private string $dateFormat = 'Y-m-d H:i:s';

    public function emergency(string $message, array $context = []): void
    {
        $this->log('EMERGENCY', $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->log('ALERT', $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log('CRITICAL', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->log('NOTICE', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    private function log(string $level, string $message, array $context = []): void
    {
        $timestamp = date($this->dateFormat);
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';

        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}";

        // Color coding for different levels
        $colors = [
            'EMERGENCY' => 'background-color: red; color: white;',
            'ALERT' => 'background-color: orange; color: white;',
            'CRITICAL' => 'background-color: red; color: white;',
            'ERROR' => 'color: red;',
            'WARNING' => 'color: orange;',
            'NOTICE' => 'color: blue;',
            'INFO' => 'color: green;',
            'DEBUG' => 'color: gray;'
        ];

        $style = $colors[$level] ?? '';
        echo "<span style='{$style}'>[LOG] {$logEntry}</span><br>";
    }
}

// UserService with comprehensive error handling
class UserService
{
    private UserRepository $userRepository;
    private LoggerInterface $logger;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;

        $this->logger->info("UserService initialized");
    }

    public function registerUser(array $userData): array
    {
        $email = $userData['email'] ?? 'unknown';

        try {
            $this->logger->info("Attempting to register user", ['email' => $email]);

            $userId = $this->userRepository->create($userData);

            $this->logger->info("User registered successfully", [
                'user_id' => $userId,
                'email' => $email
            ]);

            return [
                'success' => true,
                'data' => ['user_id' => $userId],
                'message' => 'User registered successfully'
            ];
        } catch (ValidationException $e) {
            $this->logger->warning("User registration validation failed", [
                'email' => $email,
                'errors' => $e->getErrors()
            ]);

            return [
                'success' => false,
                'errors' => $e->getErrors(),
                'message' => 'Validation failed'
            ];
        } catch (InvalidArgumentException $e) {
            $this->logger->warning("User registration failed - duplicate email", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Email already exists'
            ];
        } catch (DatabaseConnectionException $e) {
            $this->logger->critical("Database connection failed during user registration", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Service temporarily unavailable. Please try again later.'
            ];
        } catch (QueryExecutionException $e) {
            $this->logger->error("Database query failed during user registration", [
                'email' => $email,
                'error' => $e->getMessage(),
                'query' => $e->getQuery()
            ]);

            return [
                'success' => false,
                'message' => 'Registration failed due to technical error'
            ];
        } catch (Exception $e) {
            $this->logger->critical("Unexpected error during user registration", [
                'email' => $email,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return [
                'success' => false,
                'message' => 'An unexpected error occurred'
            ];
        }
    }

    public function getUserProfile(int $userId): array
    {
        try {
            $this->logger->debug("Retrieving user profile", ['user_id' => $userId]);

            $user = $this->userRepository->findById($userId);

            $this->logger->debug("User profile retrieved successfully", [
                'user_id' => $userId,
                'email' => $user['email']
            ]);

            return [
                'success' => true,
                'data' => $user
            ];
        } catch (InvalidArgumentException $e) {
            $this->logger->warning("Invalid user ID provided", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Invalid user ID'
            ];
        } catch (UserNotFoundException $e) {
            $this->logger->info("User profile request for non-existent user", [
                'user_id' => $userId
            ]);

            return [
                'success' => false,
                'message' => 'User not found'
            ];
        } catch (Exception $e) {
            $this->logger->error("Error retrieving user profile", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve user profile'
            ];
        }
    }

    public function updateUser(int $userId, array $updateData): array
    {
        try {
            $this->logger->info("Attempting to update user", [
                'user_id' => $userId,
                'fields' => array_keys($updateData)
            ]);

            $this->userRepository->update($userId, $updateData);

            $this->logger->info("User updated successfully", ['user_id' => $userId]);

            return [
                'success' => true,
                'message' => 'User updated successfully'
            ];
        } catch (ValidationException $e) {
            $this->logger->warning("User update validation failed", [
                'user_id' => $userId,
                'errors' => $e->getErrors()
            ]);

            return [
                'success' => false,
                'errors' => $e->getErrors(),
                'message' => 'Validation failed'
            ];
        } catch (UserNotFoundException $e) {
            $this->logger->info("Update attempt for non-existent user", [
                'user_id' => $userId
            ]);

            return [
                'success' => false,
                'message' => 'User not found'
            ];
        } catch (Exception $e) {
            $this->logger->error("Error updating user", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to update user'
            ];
        }
    }

    public function deleteUser(int $userId): array
    {
        try {
            $this->logger->warning("Attempting to delete user", ['user_id' => $userId]);

            $this->userRepository->delete($userId);

            $this->logger->warning("User deleted successfully", ['user_id' => $userId]);

            return [
                'success' => true,
                'message' => 'User deleted successfully'
            ];
        } catch (UserNotFoundException $e) {
            $this->logger->info("Delete attempt for non-existent user", [
                'user_id' => $userId
            ]);

            return [
                'success' => false,
                'message' => 'User not found'
            ];
        } catch (Exception $e) {
            $this->logger->error("Error deleting user", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to delete user'
            ];
        }
    }
}

echo "<h3>Testing UserService with Comprehensive Error Handling:</h3>";

// Create dependencies
$database = new MockDatabase();
$userRepo = new UserRepository($database);
$logger = new ConsoleLogger();
$userService = new UserService($userRepo, $logger);

echo "<br>";

// Test successful operations
echo "<h4>Successful Operations:</h4>";

$result1 = $userService->registerUser([
    'name' => 'Charlie Brown',
    'email' => 'charlie@example.com'
]);

echo "Registration result: " . json_encode($result1) . "<br><br>";

$result2 = $userService->getUserProfile(1);
echo "Get profile result: " . json_encode($result2) . "<br><br>";

$result3 = $userService->updateUser(1, ['name' => 'John Doe Updated']);
echo "Update result: " . json_encode($result3) . "<br><br>";

// Test operations that will fail
echo "<h4>Operations That Should Fail:</h4>";

$result4 = $userService->registerUser([
    'name' => 'X',  // Too short
    'email' => 'invalid-email'  // Invalid format
]);
echo "Invalid registration result: " . json_encode($result4) . "<br><br>";

$result5 = $userService->registerUser([
    'name' => 'Duplicate User',
    'email' => 'john@example.com'  // Already exists
]);
echo "Duplicate email result: " . json_encode($result5) . "<br><br>";

$result6 = $userService->getUserProfile(999);
echo "Non-existent user result: " . json_encode($result6) . "<br><br>";

$result7 = $userService->deleteUser(999);
echo "Delete non-existent user result: " . json_encode($result7) . "<br><br>";

echo "<hr>";

echo "<h2>Contoh 4: Circuit Breaker Pattern</h2>";

// Circuit Breaker for handling external service failures
class CircuitBreaker
{
    private int $failureThreshold;
    private int $recoveryTimeout;
    private int $failureCount = 0;
    private ?int $lastFailureTime = null;
    private string $state = 'CLOSED'; // CLOSED, OPEN, HALF_OPEN
    private LoggerInterface $logger;

    public function __construct(int $failureThreshold = 3, int $recoveryTimeout = 60, LoggerInterface $logger = null)
    {
        $this->failureThreshold = $failureThreshold;
        $this->recoveryTimeout = $recoveryTimeout;
        $this->logger = $logger ?: new ConsoleLogger();

        $this->logger->info("Circuit breaker initialized", [
            'failure_threshold' => $failureThreshold,
            'recovery_timeout' => $recoveryTimeout
        ]);
    }

    public function call(callable $operation, string $operationName = 'unknown')
    {
        if ($this->state === 'OPEN') {
            if (time() - $this->lastFailureTime >= $this->recoveryTimeout) {
                $this->state = 'HALF_OPEN';
                $this->logger->info("Circuit breaker transitioning to HALF_OPEN", [
                    'operation' => $operationName
                ]);
            } else {
                $this->logger->warning("Circuit breaker is OPEN - blocking request", [
                    'operation' => $operationName,
                    'remaining_timeout' => $this->recoveryTimeout - (time() - $this->lastFailureTime)
                ]);
                throw new RuntimeException("Circuit breaker is OPEN for operation: {$operationName}");
            }
        }

        try {
            $this->logger->debug("Executing operation through circuit breaker", [
                'operation' => $operationName,
                'state' => $this->state
            ]);

            $result = $operation();

            if ($this->state === 'HALF_OPEN') {
                $this->reset();
                $this->logger->info("Circuit breaker reset to CLOSED after successful operation", [
                    'operation' => $operationName
                ]);
            }

            return $result;
        } catch (Exception $e) {
            $this->recordFailure($operationName);
            throw $e;
        }
    }

    private function recordFailure(string $operationName): void
    {
        $this->failureCount++;
        $this->lastFailureTime = time();

        $this->logger->warning("Circuit breaker recorded failure", [
            'operation' => $operationName,
            'failure_count' => $this->failureCount,
            'threshold' => $this->failureThreshold
        ]);

        if ($this->failureCount >= $this->failureThreshold) {
            $this->state = 'OPEN';
            $this->logger->error("Circuit breaker opened due to repeated failures", [
                'operation' => $operationName,
                'failure_count' => $this->failureCount
            ]);
        }
    }

    private function reset(): void
    {
        $this->failureCount = 0;
        $this->lastFailureTime = null;
        $this->state = 'CLOSED';
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getFailureCount(): int
    {
        return $this->failureCount;
    }

    public function getStats(): array
    {
        return [
            'state' => $this->state,
            'failure_count' => $this->failureCount,
            'failure_threshold' => $this->failureThreshold,
            'last_failure_time' => $this->lastFailureTime,
            'recovery_timeout' => $this->recoveryTimeout
        ];
    }
}

// Simulate external service that may fail
class ExternalApiService
{
    private bool $shouldFail;
    private int $callCount = 0;

    public function __construct(bool $shouldFail = false)
    {
        $this->shouldFail = $shouldFail;
    }

    public function makeApiCall(string $endpoint): array
    {
        $this->callCount++;

        echo "Making API call #{$this->callCount} to {$endpoint}...<br>";

        // Simulate network delay
        usleep(100000); // 0.1 seconds

        if ($this->shouldFail && $this->callCount <= 5) {
            throw new RuntimeException("API call failed: Connection timeout to {$endpoint}");
        }

        return [
            'status' => 'success',
            'data' => "Response from {$endpoint}",
            'call_count' => $this->callCount
        ];
    }

    public function setShouldFail(bool $shouldFail): void
    {
        $this->shouldFail = $shouldFail;
    }

    public function getCallCount(): int
    {
        return $this->callCount;
    }
}

echo "<h3>Testing Circuit Breaker Pattern:</h3>";

$logger = new ConsoleLogger();
$circuitBreaker = new CircuitBreaker(3, 5, $logger); // 3 failures, 5 seconds recovery
$apiService = new ExternalApiService(true); // Start with failing service

echo "<h4>Phase 1: Service Failing (Circuit Breaker should open):</h4>";

for ($i = 1; $i <= 5; $i++) {
    try {
        echo "<br>Attempt #{$i}:<br>";

        $result = $circuitBreaker->call(function () use ($apiService) {
            return $apiService->makeApiCall('/api/users');
        }, 'get_users');

        echo "Success: " . json_encode($result) . "<br>";
    } catch (RuntimeException $e) {
        if (strpos($e->getMessage(), 'Circuit breaker is OPEN') !== false) {
            echo "<span style='color: orange;'>Circuit breaker blocked request: " . $e->getMessage() . "</span><br>";
        } else {
            echo "<span style='color: red;'>API call failed: " . $e->getMessage() . "</span><br>";
        }
    }

    echo "Circuit breaker state: " . $circuitBreaker->getState() . " (failures: " . $circuitBreaker->getFailureCount() . ")<br>";
}

echo "<br><h4>Phase 2: Waiting for recovery timeout...</h4>";
echo "Waiting 6 seconds for circuit breaker to enter HALF_OPEN state...<br>";
sleep(6);

echo "<br><h4>Phase 3: Service recovered (Circuit Breaker should close):</h4>";
$apiService->setShouldFail(false); // Fix the service

for ($i = 1; $i <= 3; $i++) {
    try {
        echo "<br>Recovery attempt #{$i}:<br>";

        $result = $circuitBreaker->call(function () use ($apiService) {
            return $apiService->makeApiCall('/api/users');
        }, 'get_users');

        echo "Success: " . json_encode($result) . "<br>";
    } catch (RuntimeException $e) {
        echo "<span style='color: red;'>Call failed: " . $e->getMessage() . "</span><br>";
    }

    echo "Circuit breaker state: " . $circuitBreaker->getState() . " (failures: " . $circuitBreaker->getFailureCount() . ")<br>";
}

echo "<br><h4>Circuit Breaker Final Stats:</h4>";
$stats = $circuitBreaker->getStats();
echo "Final stats: " . json_encode($stats) . "<br>";
echo "Total API calls made: " . $apiService->getCallCount() . "<br>";

echo "<hr>";

echo "<h2>Kesimpulan</h2>";
echo "<p>Error Handling dan Exception memberikan:</p>";
echo "<ul>";
echo "<li><strong>Robust Applications:</strong> Handle unexpected conditions gracefully</li>";
echo "<li><strong>Better User Experience:</strong> Meaningful error messages dan fallbacks</li>";
echo "<li><strong>Debugging Support:</strong> Detailed logging dan error information</li>";
echo "<li><strong>System Reliability:</strong> Circuit breaker untuk external dependencies</li>";
echo "<li><strong>Clean Code:</strong> Separation of business logic dan error handling</li>";
echo "<li><strong>Monitoring:</strong> Comprehensive logging untuk production systems</li>";
echo "</ul>";

echo "<br><strong>Key Benefits:</strong><br>";
echo "<ul>";
echo "<li>Centralized error handling dengan consistent responses</li>";
echo "<li>Custom exceptions untuk domain-specific errors</li>";
echo "<li>Defensive programming dengan input validation</li>";
echo "<li>Production-ready logging dan monitoring</li>";
echo "<li>Circuit breaker pattern untuk external service reliability</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Gunakan specific exception types daripada generic Exception</li>";
echo "<li>Include context information dalam exception messages</li>";
echo "<li>Implement proper logging dengan different severity levels</li>";
echo "<li>Use try-catch-finally untuk resource cleanup</li>";
echo "<li>Design error responses yang user-friendly</li>";
echo "<li>Implement circuit breaker untuk external dependencies</li>";
echo "</ul>";

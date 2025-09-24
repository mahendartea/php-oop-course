<?php

/**
 * Pertemuan 10: Traits
 * Contoh implementasi Traits dalam OOP PHP
 */

echo "<h1>Pertemuan 10: Traits</h1>";

echo "<h2>Contoh 1: Basic Trait Usage</h2>";

// Basic trait dengan methods dan properties
trait Timestampable
{
    protected ?DateTime $createdAt = null;
    protected ?DateTime $updatedAt = null;

    public function touch(): void
    {
        $now = new DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = $now;
            echo "Created timestamp set: " . $this->createdAt->format('Y-m-d H:i:s') . "<br>";
        }

        $this->updatedAt = $now;
        echo "Updated timestamp set: " . $this->updatedAt->format('Y-m-d H:i:s') . "<br>";
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getAge(): ?DateInterval
    {
        if ($this->createdAt === null) {
            return null;
        }

        return $this->createdAt->diff(new DateTime());
    }

    public function getFormattedAge(): string
    {
        $age = $this->getAge();
        if ($age === null) {
            return "Not created yet";
        }

        if ($age->days > 0) {
            return $age->days . " days ago";
        } elseif ($age->h > 0) {
            return $age->h . " hours ago";
        } elseif ($age->i > 0) {
            return $age->i . " minutes ago";
        } else {
            return "Just now";
        }
    }

    public function isNew(): bool
    {
        if ($this->createdAt === null) {
            return false;
        }

        $now = new DateTime();
        $diff = $this->createdAt->diff($now);

        return $diff->days === 0 && $diff->h < 1; // Less than 1 hour
    }
}

// Trait untuk slugging functionality
trait Sluggable
{
    protected string $slug = '';

    public function generateSlug(string $text): string
    {
        // Convert to lowercase
        $slug = strtolower($text);

        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        // Remove leading/trailing hyphens
        $slug = trim($slug, '-');

        $this->slug = $slug;
        echo "Slug generated: '{$slug}' from '{$text}'<br>";

        return $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
        echo "Slug set manually: '{$slug}'<br>";
    }

    public function getUrl(string $baseUrl = ''): string
    {
        return $baseUrl . '/' . $this->slug;
    }
}

// Classes menggunakan traits
class Post
{
    use Timestampable, Sluggable;

    private string $title;
    private string $content;
    private string $author;

    public function __construct(string $title, string $content, string $author)
    {
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;

        $this->generateSlug($title);
        $this->touch();

        echo "Post '{$title}' created by {$author}<br>";
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
        $this->generateSlug($title);
        $this->touch(); // Update timestamp
        echo "Post title updated to: '{$title}'<br>";
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
        $this->touch(); // Update timestamp
        echo "Post content updated<br>";
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getInfo(): string
    {
        return sprintf(
            "Post: %s by %s\nSlug: %s\nCreated: %s\nAge: %s\nNew: %s",
            $this->title,
            $this->author,
            $this->slug,
            $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : 'Not set',
            $this->getFormattedAge(),
            $this->isNew() ? 'Yes' : 'No'
        );
    }
}

class User
{
    use Timestampable;

    private string $name;
    private string $email;
    private string $role;

    public function __construct(string $name, string $email, string $role = 'user')
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;

        $this->touch();
        echo "User '{$name}' created with email {$email} and role {$role}<br>";
    }

    public function updateProfile(string $name, string $email): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->touch();
        echo "User profile updated<br>";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}

echo "<h3>Testing Basic Traits:</h3>";

// Test Post with multiple traits
$post = new Post("Hello World - PHP Traits", "This is a comprehensive guide to PHP traits...", "John Doe");
echo "<br>";

sleep(1); // Simulate time passage

$post->setTitle("Advanced PHP Traits Guide");
echo "<br>";

echo "<pre>" . $post->getInfo() . "</pre>";
echo "Post URL: " . $post->getUrl("https://example.com/posts") . "<br>";

echo "<br>";

// Test User with Timestampable trait
$user = new User("Jane Smith", "jane@example.com", "admin");
echo "<br>";

sleep(1);
$user->updateProfile("Jane Doe", "jane.doe@example.com");
echo "User age: " . $user->getFormattedAge() . "<br>";

echo "<hr>";

echo "<h2>Contoh 2: Multiple Traits dengan Conflict Resolution</h2>";

// Trait dengan method conflicts
trait LoggerTrait
{
    protected array $logs = [];

    public function log(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $this->logs[] = "[$timestamp] $message";
        echo "Logged: $message<br>";
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
        echo "Logs cleared<br>";
    }

    // Conflicting method
    public function process(): string
    {
        return "Processing with logger";
    }
}

trait CacheableTrait
{
    private array $cache = [];
    private bool $cacheEnabled = true;

    protected function getCacheKey(string $method, array $args = []): string
    {
        return $method . ':' . md5(serialize($args));
    }

    protected function cache(string $method, array $args, callable $callback)
    {
        if (!$this->cacheEnabled) {
            return $callback();
        }

        $key = $this->getCacheKey($method, $args);

        if (isset($this->cache[$key])) {
            echo "Cache hit for key: $key<br>";
            return $this->cache[$key];
        }

        echo "Cache miss for key: $key<br>";
        $result = $callback();
        $this->cache[$key] = $result;

        return $result;
    }

    public function enableCache(): void
    {
        $this->cacheEnabled = true;
        echo "Cache enabled<br>";
    }

    public function disableCache(): void
    {
        $this->cacheEnabled = false;
        echo "Cache disabled<br>";
    }

    public function clearCache(): void
    {
        $this->cache = [];
        echo "Cache cleared<br>";
    }

    // Conflicting method
    public function process(): string
    {
        return "Processing with cache";
    }
}

trait ValidationTrait
{
    private array $errors = [];
    private array $rules = [];

    public function addRule(string $field, callable $rule, string $message = null): void
    {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }

        $this->rules[$field][] = [
            'rule' => $rule,
            'message' => $message ?? "Validation failed for {$field}"
        ];

        echo "Validation rule added for field: {$field}<br>";
    }

    public function validate(array $data): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $ruleData) {
                $rule = $ruleData['rule'];
                $message = $ruleData['message'];

                if (!$rule($value)) {
                    $this->errors[$field][] = $message;
                }
            }
        }

        $isValid = empty($this->errors);
        echo "Validation " . ($isValid ? "passed" : "failed") . "<br>";

        return $isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}

// Class dengan conflict resolution
class ComplexService
{
    use LoggerTrait, CacheableTrait, ValidationTrait {
        // Resolve method conflicts
        LoggerTrait::process insteadof CacheableTrait;
        CacheableTrait::process as cacheProcess;

        // Change visibility
        LoggerTrait::clearLogs as public clearAllLogs;
    }

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->log("Service '{$name}' initialized");

        // Setup validation rules
        $this->addRule('email', function ($value) {
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        }, 'Invalid email format');

        $this->addRule('age', function ($value) {
            return is_numeric($value) && $value >= 0 && $value <= 150;
        }, 'Age must be between 0 and 150');
    }

    public function expensiveCalculation(int $n): int
    {
        return $this->cache(__METHOD__, [$n], function () use ($n) {
            $this->log("Calculating fibonacci for {$n}");

            if ($n <= 1) {
                return $n;
            }

            // Simulate expensive calculation
            usleep(10000); // 10ms delay

            return $this->expensiveCalculation($n - 1) + $this->expensiveCalculation($n - 2);
        });
    }

    public function processUserData(array $userData): array
    {
        $this->log("Processing user data");

        if (!$this->validate($userData)) {
            $this->log("Validation failed: " . json_encode($this->getErrors()));
            return ['status' => 'error', 'errors' => $this->getErrors()];
        }

        $this->log("User data processed successfully");
        return ['status' => 'success', 'data' => $userData];
    }

    public function getName(): string
    {
        return $this->name;
    }

    // Method conflicts resolved
    public function process(): string
    {
        return "Main process: " . parent::process();
    }

    public function processingComparison(): array
    {
        return [
            'main_process' => $this->process(),
            'cache_process' => $this->cacheProcess()
        ];
    }
}

echo "<h3>Testing Multiple Traits with Conflicts:</h3>";

$service = new ComplexService("Multi-Trait Service");
echo "<br>";

// Test caching
echo "<h4>Testing Caching:</h4>";
$result1 = $service->expensiveCalculation(5);
echo "First calculation result: {$result1}<br>";

$result2 = $service->expensiveCalculation(5); // Should hit cache
echo "Second calculation result: {$result2}<br>";

$service->clearCache();
$result3 = $service->expensiveCalculation(5); // Should miss cache again
echo "Third calculation result: {$result3}<br><br>";

// Test validation
echo "<h4>Testing Validation:</h4>";
$validData = ['email' => 'test@example.com', 'age' => 25];
$result = $service->processUserData($validData);
echo "Valid data result: " . json_encode($result) . "<br>";

$invalidData = ['email' => 'invalid-email', 'age' => 200];
$result = $service->processUserData($invalidData);
echo "Invalid data result: " . json_encode($result) . "<br><br>";

// Test conflict resolution
echo "<h4>Testing Method Conflicts Resolution:</h4>";
$processes = $service->processingComparison();
foreach ($processes as $type => $result) {
    echo ucfirst(str_replace('_', ' ', $type)) . ": {$result}<br>";
}
echo "<br>";

// Test logging
echo "<h4>Service Logs:</h4>";
$logs = $service->getLogs();
foreach ($logs as $index => $logEntry) {
    echo ($index + 1) . ". {$logEntry}<br>";
}

echo "<hr>";

echo "<h2>Contoh 3: Trait Composition dan Inheritance</h2>";

// Base traits
trait DatabaseTrait
{
    protected string $connection = "mysql://localhost:3306/db";

    protected function connect(): string
    {
        echo "Connecting to database: {$this->connection}<br>";
        return "connection_handle";
    }

    protected function disconnect(): void
    {
        echo "Disconnecting from database<br>";
    }

    protected function query(string $sql): array
    {
        echo "Executing query: {$sql}<br>";
        return ['result' => 'success', 'data' => []];
    }
}

trait CRUDTrait
{
    use DatabaseTrait;

    protected string $table;

    public function create(array $data): int
    {
        $this->connect();
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";

        $result = $this->query($sql);
        $this->disconnect();

        $id = rand(1, 1000); // Simulate generated ID
        echo "Record created with ID: {$id}<br>";

        return $id;
    }

    public function read(int $id): ?array
    {
        $this->connect();
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";

        $result = $this->query($sql);
        $this->disconnect();

        // Simulate found record
        $record = ['id' => $id, 'name' => 'Sample Record', 'created_at' => date('Y-m-d H:i:s')];
        echo "Record found: " . json_encode($record) . "<br>";

        return $record;
    }

    public function update(int $id, array $data): bool
    {
        $this->connect();
        $updates = [];
        foreach ($data as $column => $value) {
            $updates[] = "{$column} = '{$value}'";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE id = {$id}";

        $result = $this->query($sql);
        $this->disconnect();

        echo "Record {$id} updated successfully<br>";
        return true;
    }

    public function delete(int $id): bool
    {
        $this->connect();
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";

        $result = $this->query($sql);
        $this->disconnect();

        echo "Record {$id} deleted successfully<br>";
        return true;
    }

    public function findAll(array $conditions = []): array
    {
        $this->connect();
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = '{$value}'";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $result = $this->query($sql);
        $this->disconnect();

        // Simulate multiple records
        $records = [
            ['id' => 1, 'name' => 'Record 1'],
            ['id' => 2, 'name' => 'Record 2'],
            ['id' => 3, 'name' => 'Record 3']
        ];

        echo "Found " . count($records) . " records<br>";
        return $records;
    }
}

// Advanced traits yang menggunakan composition
trait SearchableTrait
{
    use CRUDTrait;

    public function search(string $term, array $fields = ['name']): array
    {
        echo "Searching for '{$term}' in fields: " . implode(', ', $fields) . "<br>";

        $this->connect();
        $conditions = [];
        foreach ($fields as $field) {
            $conditions[] = "{$field} LIKE '%{$term}%'";
        }
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' OR ', $conditions);

        $result = $this->query($sql);
        $this->disconnect();

        // Simulate search results
        $searchResults = [
            ['id' => 1, 'name' => 'PHP ' . $term, 'relevance' => 0.9],
            ['id' => 2, 'name' => $term . ' Guide', 'relevance' => 0.8]
        ];

        echo "Search completed. Found " . count($searchResults) . " results<br>";
        return $searchResults;
    }

    public function fullTextSearch(string $query): array
    {
        echo "Full-text search for: '{$query}'<br>";

        $this->connect();
        $sql = "SELECT *, MATCH(content) AGAINST('{$query}') as score FROM {$this->table} WHERE MATCH(content) AGAINST('{$query}')";

        $result = $this->query($sql);
        $this->disconnect();

        return [
            ['id' => 1, 'title' => 'PHP Tutorial', 'score' => 1.5],
            ['id' => 2, 'title' => 'Advanced PHP', 'score' => 1.2]
        ];
    }
}

trait PaginationTrait
{
    use CRUDTrait;

    public function paginate(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        echo "Paginating: Page {$page}, {$perPage} items per page (offset: {$offset})<br>";

        $this->connect();
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $countResult = $this->query($countSql);

        $sql = "SELECT * FROM {$this->table} LIMIT {$perPage} OFFSET {$offset}";
        $result = $this->query($sql);
        $this->disconnect();

        // Simulate pagination data
        $total = 47; // Total records
        $totalPages = ceil($total / $perPage);

        $paginationData = [
            'data' => [
                ['id' => 1, 'name' => 'Item 1'],
                ['id' => 2, 'name' => 'Item 2'],
                ['id' => 3, 'name' => 'Item 3']
            ],
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];

        echo "Pagination info: Page {$page} of {$totalPages}, showing {$perPage}/{$total} items<br>";

        return $paginationData;
    }
}

// Model classes using composed traits
class Article
{
    use SearchableTrait, PaginationTrait {
        SearchableTrait::findAll insteadof PaginationTrait;
        PaginationTrait::findAll as paginatedFindAll;
    }

    protected string $table = 'articles';

    public function __construct()
    {
        echo "Article model initialized with table: {$this->table}<br>";
    }

    public function getPopularArticles(int $limit = 5): array
    {
        echo "Getting {$limit} popular articles<br>";

        $this->connect();
        $sql = "SELECT * FROM {$this->table} ORDER BY views DESC LIMIT {$limit}";
        $result = $this->query($sql);
        $this->disconnect();

        return [
            ['id' => 1, 'title' => 'Popular Article 1', 'views' => 1500],
            ['id' => 2, 'title' => 'Popular Article 2', 'views' => 1200],
            ['id' => 3, 'title' => 'Popular Article 3', 'views' => 1000]
        ];
    }
}

class Product
{
    use SearchableTrait, PaginationTrait;

    protected string $table = 'products';

    public function __construct()
    {
        echo "Product model initialized with table: {$this->table}<br>";
    }

    public function getByCategory(string $category): array
    {
        echo "Getting products by category: {$category}<br>";
        return $this->findAll(['category' => $category]);
    }

    public function getFeatured(): array
    {
        echo "Getting featured products<br>";
        return $this->findAll(['featured' => 1]);
    }
}

echo "<h3>Testing Trait Composition:</h3>";

// Test Article model
echo "<h4>Article Model:</h4>";
$article = new Article();
echo "<br>";

// Test CRUD operations
$articleId = $article->create(['title' => 'PHP Traits Guide', 'content' => 'Comprehensive guide...']);
$foundArticle = $article->read($articleId);
$article->update($articleId, ['title' => 'Advanced PHP Traits Guide']);

echo "<br>";

// Test search functionality
$searchResults = $article->search('PHP', ['title', 'content']);
$fullTextResults = $article->fullTextSearch('advanced traits');

echo "<br>";

// Test pagination
$paginatedResults = $article->paginate(2, 5);

echo "<br>";

// Test Product model
echo "<h4>Product Model:</h4>";
$product = new Product();
echo "<br>";

$productId = $product->create(['name' => 'Laptop', 'price' => 999.99, 'category' => 'Electronics']);
$categoryProducts = $product->getByCategory('Electronics');
$featuredProducts = $product->getFeatured();

echo "<br>";

// Test search on products
$productSearch = $product->search('Laptop');

echo "<hr>";

echo "<h2>Contoh 4: Singleton Trait Pattern</h2>";

// Singleton implementation as a trait
trait SingletonTrait
{
    private static array $instances = [];

    protected function __construct()
    {
        // Protected constructor prevents direct instantiation
    }

    public static function getInstance(): static
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
            echo "New {$class} instance created<br>";
        } else {
            echo "Returning existing {$class} instance<br>";
        }

        return self::$instances[$class];
    }

    public static function hasInstance(): bool
    {
        return isset(self::$instances[static::class]);
    }

    public static function destroyInstance(): void
    {
        $class = static::class;
        if (isset(self::$instances[$class])) {
            unset(self::$instances[$class]);
            echo "{$class} instance destroyed<br>";
        }
    }

    public static function getAllInstances(): array
    {
        return self::$instances;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    private function __wakeup() {}

    // Prevent serialization
    private function __sleep() {}
}

// Logger class using Singleton trait
class Logger
{
    use SingletonTrait;

    private array $logs = [];
    private string $logLevel = 'INFO';

    public function log(string $message, string $level = null): void
    {
        $level = $level ?? $this->logLevel;
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}";

        $this->logs[] = $logEntry;
        echo "Logged: {$logEntry}<br>";
    }

    public function setLogLevel(string $level): void
    {
        $this->logLevel = strtoupper($level);
        echo "Log level set to: {$this->logLevel}<br>";
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
        echo "All logs cleared<br>";
    }

    public function getLogCount(): int
    {
        return count($this->logs);
    }
}

// Configuration class using Singleton trait
class AppConfig
{
    use SingletonTrait;

    private array $config = [];

    protected function __construct()
    {
        parent::__construct();

        // Load default configuration
        $this->config = [
            'app_name' => 'PHP Traits Demo',
            'version' => '1.0.0',
            'debug' => true,
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
                'username' => 'root',
                'password' => '',
                'database' => 'traits_demo'
            ]
        ];

        echo "AppConfig initialized with default settings<br>";
    }

    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
        echo "Config set: {$key} = " . (is_array($value) ? json_encode($value) : $value) . "<br>";
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function all(): array
    {
        return $this->config;
    }
}

echo "<h3>Testing Singleton Trait:</h3>";

// Test Logger singleton
echo "<h4>Logger Singleton:</h4>";
$logger1 = Logger::getInstance();
$logger1->log("First log entry");
$logger1->setLogLevel("DEBUG");

$logger2 = Logger::getInstance(); // Should return same instance
$logger2->log("Second log entry", "ERROR");

echo "Are logger instances the same? " . ($logger1 === $logger2 ? "Yes" : "No") . "<br>";
echo "Total logs: " . $logger1->getLogCount() . "<br><br>";

// Test AppConfig singleton
echo "<h4>AppConfig Singleton:</h4>";
$config1 = AppConfig::getInstance();
$config1->set('api.key', 'abc123');
$config1->set('api.url', 'https://api.example.com');

$config2 = AppConfig::getInstance(); // Should return same instance
echo "API key from config2: " . $config2->get('api.key') . "<br>";
echo "App name: " . $config2->get('app_name') . "<br>";
echo "Database host: " . $config2->get('database.host') . "<br>";

echo "Are config instances the same? " . ($config1 === $config2 ? "Yes" : "No") . "<br><br>";

// Show all singleton instances
echo "<h4>All Singleton Instances:</h4>";
$instances = Logger::getAllInstances();
foreach ($instances as $class => $instance) {
    echo "- {$class}: " . get_class($instance) . "<br>";
}

echo "<hr>";

echo "<h2>Kesimpulan</h2>";
echo "<p>Traits memberikan solusi untuk:</p>";
echo "<ul>";
echo "<li><strong>Code Reuse:</strong> Berbagi functionality tanpa inheritance</li>";
echo "<li><strong>Multiple Inheritance:</strong> Menggunakan multiple traits dalam satu class</li>";
echo "<li><strong>Conflict Resolution:</strong> Mengatasi method name conflicts</li>";
echo "<li><strong>Composition:</strong> Membangun complex functionality dari simple traits</li>";
echo "<li><strong>Horizontal Reuse:</strong> Sharing behavior across unrelated classes</li>";
echo "<li><strong>Design Patterns:</strong> Implementasi Singleton, Observer, dll dengan traits</li>";
echo "</ul>";

echo "<br><strong>Key Benefits:</strong><br>";
echo "<ul>";
echo "<li>Mengurangi code duplication</li>";
echo "<li>Meningkatkan reusability</li>";
echo "<li>Memungkinkan flexible composition</li>";
echo "<li>Tetap mempertahankan single inheritance principle</li>";
echo "<li>Memudahkan testing dengan isolated functionality</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Gunakan trait untuk horizontal concerns (logging, caching, validation)</li>";
echo "<li>Beri nama trait dengan suffix -able atau prefix Can-</li>";
echo "<li>Hindari state yang complex dalam traits</li>";
echo "<li>Handle conflicts dengan explicit resolution</li>";
echo "<li>Test traits secara terpisah dari classes yang menggunakannya</li>";
echo "</ul>";

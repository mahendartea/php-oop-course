<?php

/**
 * Pertemuan 11: Namespaces dan Autoloading
 * Contoh implementasi namespaces dan autoloading dalam PHP
 */

echo "<h1>Pertemuan 11: Namespaces dan Autoloading</h1>";

echo "<h2>Contoh 1: Basic Namespace Usage</h2>";

// Namespace declaration harus di awal file (setelah <?php)
// Kita simulate dengan class definitions

// Simulate namespace App\Models
echo "<h3>Namespace: App\\Models</h3>";

class App_Models_User
{
    private string $name;
    private string $email;
    private DateTime $createdAt;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->createdAt = new DateTime();

        echo "User created in App\\Models namespace: {$name}<br>";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getInfo(): string
    {
        return sprintf(
            "User: %s (%s) - Created: %s",
            $this->name,
            $this->email,
            $this->createdAt->format('Y-m-d H:i:s')
        );
    }
}

class App_Models_Post
{
    private string $title;
    private string $content;
    private string $author;
    private DateTime $createdAt;

    public function __construct(string $title, string $content, string $author)
    {
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->createdAt = new DateTime();

        echo "Post created in App\\Models namespace: {$title}<br>";
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getSlug(): string
    {
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title));
    }

    public function getInfo(): string
    {
        return sprintf(
            "Post: %s by %s\nSlug: %s\nCreated: %s\nContent: %s",
            $this->title,
            $this->author,
            $this->getSlug(),
            $this->createdAt->format('Y-m-d H:i:s'),
            substr($this->content, 0, 100) . '...'
        );
    }
}

// Simulate namespace App\Controllers
echo "<h3>Namespace: App\\Controllers</h3>";

class App_Controllers_UserController
{
    private array $users = [];

    public function __construct()
    {
        echo "UserController initialized in App\\Controllers namespace<br>";
    }

    public function create(string $name, string $email): App_Models_User
    {
        $user = new App_Models_User($name, $email);
        $this->users[] = $user;

        echo "User controller: Created user {$name}<br>";
        return $user;
    }

    public function findByEmail(string $email): ?App_Models_User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                echo "User controller: Found user with email {$email}<br>";
                return $user;
            }
        }

        echo "User controller: No user found with email {$email}<br>";
        return null;
    }

    public function listAll(): array
    {
        echo "User controller: Listing " . count($this->users) . " users<br>";
        return $this->users;
    }

    public function getUserCount(): int
    {
        return count($this->users);
    }
}

class App_Controllers_PostController
{
    private array $posts = [];

    public function __construct()
    {
        echo "PostController initialized in App\\Controllers namespace<br>";
    }

    public function create(string $title, string $content, string $author): App_Models_Post
    {
        $post = new App_Models_Post($title, $content, $author);
        $this->posts[] = $post;

        echo "Post controller: Created post '{$title}'<br>";
        return $post;
    }

    public function findBySlug(string $slug): ?App_Models_Post
    {
        foreach ($this->posts as $post) {
            if ($post->getSlug() === $slug) {
                echo "Post controller: Found post with slug '{$slug}'<br>";
                return $post;
            }
        }

        echo "Post controller: No post found with slug '{$slug}'<br>";
        return null;
    }

    public function getPostsByAuthor(string $author): array
    {
        $authorPosts = [];
        foreach ($this->posts as $post) {
            if ($post->getAuthor() === $author) {
                $authorPosts[] = $post;
            }
        }

        echo "Post controller: Found " . count($authorPosts) . " posts by {$author}<br>";
        return $authorPosts;
    }

    public function listAll(): array
    {
        echo "Post controller: Listing " . count($this->posts) . " posts<br>";
        return $this->posts;
    }
}

echo "<h3>Testing Basic Namespace Usage:</h3>";

// Test User operations
$userController = new App_Controllers_UserController();
echo "<br>";

$user1 = $userController->create("John Doe", "john@example.com");
$user2 = $userController->create("Jane Smith", "jane@example.com");
$user3 = $userController->create("Bob Wilson", "bob@example.com");

echo "<br>";

// Find user by email
$foundUser = $userController->findByEmail("jane@example.com");
if ($foundUser) {
    echo "Found user info: " . $foundUser->getInfo() . "<br>";
}

echo "<br>";

// Test Post operations
$postController = new App_Controllers_PostController();
echo "<br>";

$post1 = $postController->create(
    "Introduction to PHP Namespaces",
    "Namespaces are a great way to organize your code and avoid naming conflicts...",
    "John Doe"
);

$post2 = $postController->create(
    "Advanced Autoloading Techniques",
    "Autoloading allows you to automatically load classes when they are needed...",
    "Jane Smith"
);

$post3 = $postController->create(
    "PSR-4 Standard Explained",
    "PSR-4 is a standard for autoloading classes from file paths...",
    "John Doe"
);

echo "<br>";

// Find post by slug
$foundPost = $postController->findBySlug("introduction-to-php-namespaces");
if ($foundPost) {
    echo "<pre>" . $foundPost->getInfo() . "</pre>";
}

// Get posts by author
$johnPosts = $postController->getPostsByAuthor("John Doe");
echo "John's posts count: " . count($johnPosts) . "<br>";

echo "<hr>";

echo "<h2>Contoh 2: Simple Autoloader Implementation</h2>";

// Simple autoloader class
class SimpleAutoloader
{
    private array $prefixes = [];
    private bool $debug = false;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
        echo "SimpleAutoloader initialized" . ($debug ? " (debug mode)" : "") . "<br>";
    }

    /**
     * Add a namespace prefix with base directory
     */
    public function addNamespace(string $prefix, string $baseDir): void
    {
        // Normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize base directory
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        // Initialize prefix array if needed
        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }

        // Add base directory to prefix
        $this->prefixes[$prefix][] = $baseDir;

        if ($this->debug) {
            echo "Namespace registered: {$prefix} -> {$baseDir}<br>";
        }
    }

    /**
     * Load class file
     */
    public function loadClass(string $className): bool
    {
        if ($this->debug) {
            echo "Autoloader: Trying to load class '{$className}'<br>";
        }

        // Work backwards through the namespace parts
        $prefix = $className;

        while (false !== $pos = strrpos($prefix, '\\')) {
            // Get namespace prefix
            $prefix = substr($className, 0, $pos + 1);

            // Get relative class name
            $relativeClass = substr($className, $pos + 1);

            // Attempt to load mapped file
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                if ($this->debug) {
                    echo "Autoloader: Successfully loaded '{$className}' from '{$mappedFile}'<br>";
                }
                return true;
            }

            // Remove trailing separator for next iteration
            $prefix = rtrim($prefix, '\\');
        }

        if ($this->debug) {
            echo "Autoloader: Failed to load class '{$className}'<br>";
        }

        return false;
    }

    /**
     * Load mapped file for namespace prefix and relative class
     */
    protected function loadMappedFile(string $prefix, string $relativeClass): ?string
    {
        if (!isset($this->prefixes[$prefix])) {
            return null;
        }

        foreach ($this->prefixes[$prefix] as $baseDir) {
            // Convert namespace separators to directory separators
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if ($this->debug) {
                echo "Autoloader: Checking file '{$file}'<br>";
            }

            if ($this->requireFile($file)) {
                return $file;
            }
        }

        return null;
    }

    /**
     * Require file if it exists
     */
    protected function requireFile(string $file): bool
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }

    /**
     * Register this autoloader with SPL
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
        echo "Autoloader registered with SPL<br>";
    }

    /**
     * Unregister this autoloader
     */
    public function unregister(): void
    {
        spl_autoload_unregister([$this, 'loadClass']);
        echo "Autoloader unregistered<br>";
    }

    /**
     * Get registered prefixes
     */
    public function getPrefixes(): array
    {
        return $this->prefixes;
    }
}

echo "<h3>Testing Simple Autoloader:</h3>";

// Create autoloader with debug mode
$autoloader = new SimpleAutoloader(true);
echo "<br>";

// Add namespace mappings
$autoloader->addNamespace('MyApp\\Models\\', __DIR__ . '/src/MyApp/Models/');
$autoloader->addNamespace('MyApp\\Services\\', __DIR__ . '/src/MyApp/Services/');
$autoloader->addNamespace('Vendor\\Logger\\', __DIR__ . '/vendor/Logger/');

echo "<br>";

// Show registered prefixes
echo "Registered prefixes:<br>";
foreach ($autoloader->getPrefixes() as $prefix => $dirs) {
    echo "- {$prefix}: " . implode(', ', $dirs) . "<br>";
}

echo "<br>";

// Simulate autoloading attempts
echo "Simulating autoload attempts:<br>";
$autoloader->loadClass('MyApp\\Models\\User');
$autoloader->loadClass('MyApp\\Services\\EmailService');
$autoloader->loadClass('Vendor\\Logger\\FileLogger');
$autoloader->loadClass('NonExistent\\Class\\Name');

echo "<hr>";

echo "<h2>Contoh 3: Name Collision Resolution</h2>";

// Simulate different namespaces with same class names
echo "<h3>Name Collision Examples:</h3>";

// App\Models\Logger
class App_Models_Logger
{
    private array $logs = [];
    private string $context = "App\\Models";

    public function __construct()
    {
        echo "Logger created in {$this->context} namespace<br>";
    }

    public function log(string $message): void
    {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $message,
            'context' => $this->context
        ];

        $this->logs[] = $entry;
        echo "[{$this->context}] Logged: {$message}<br>";
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function getContext(): string
    {
        return $this->context;
    }
}

// Vendor\Tools\Logger (different implementation)
class Vendor_Tools_Logger
{
    private string $logFile;
    private string $context = "Vendor\\Tools";

    public function __construct(string $logFile = 'app.log')
    {
        $this->logFile = $logFile;
        echo "File logger created in {$this->context} namespace (file: {$logFile})<br>";
    }

    public function writeLog(string $level, string $message): void
    {
        $entry = date('Y-m-d H:i:s') . " [{$level}] {$message} (from {$this->context})";
        echo "Writing to {$this->logFile}: {$entry}<br>";
    }

    public function info(string $message): void
    {
        $this->writeLog('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->writeLog('ERROR', $message);
    }

    public function getLogFile(): string
    {
        return $this->logFile;
    }

    public function getContext(): string
    {
        return $this->context;
    }
}

// Third\Party\Logger (another different implementation)
class Third_Party_Logger
{
    private array $handlers = [];
    private string $context = "Third\\Party";

    public function __construct()
    {
        echo "Advanced logger created in {$this->context} namespace<br>";
        $this->addHandler('console');
        $this->addHandler('file');
    }

    public function addHandler(string $handler): void
    {
        $this->handlers[] = $handler;
        echo "[{$this->context}] Handler added: {$handler}<br>";
    }

    public function record(string $level, string $message, array $context = []): void
    {
        $entry = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'namespace' => $this->context,
            'timestamp' => microtime(true)
        ];

        foreach ($this->handlers as $handler) {
            echo "[{$this->context}] {$handler}: [{$level}] {$message}<br>";
        }
    }

    public function debug(string $message, array $context = []): void
    {
        $this->record('DEBUG', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->record('INFO', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->record('WARNING', $message, $context);
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function getContext(): string
    {
        return $this->context;
    }
}

echo "<h3>Testing Name Collision Resolution:</h3>";

// Create different loggers (same class name, different namespaces)
echo "<h4>Creating loggers from different namespaces:</h4>";
$appLogger = new App_Models_Logger();
$vendorLogger = new Vendor_Tools_Logger('system.log');
$thirdPartyLogger = new Third_Party_Logger();

echo "<br>";

// Use each logger with their specific interfaces
echo "<h4>Using different logger implementations:</h4>";

// App logger
$appLogger->log("User created successfully");
$appLogger->log("Database connection established");

echo "<br>";

// Vendor logger
$vendorLogger->info("Application started");
$vendorLogger->error("Configuration file not found");

echo "<br>";

// Third party logger
$thirdPartyLogger->info("Request processed", ['user_id' => 123]);
$thirdPartyLogger->warning("Memory usage high", ['usage' => '80%']);
$thirdPartyLogger->debug("Query executed", ['sql' => 'SELECT * FROM users']);

echo "<br>";

echo "<h4>Logger information:</h4>";
$loggers = [
    'App Logger' => $appLogger,
    'Vendor Logger' => $vendorLogger,
    'Third Party Logger' => $thirdPartyLogger
];

foreach ($loggers as $name => $logger) {
    echo "- {$name}: Context = {$logger->getContext()}<br>";

    if ($logger instanceof App_Models_Logger) {
        echo "  Log entries: " . count($logger->getLogs()) . "<br>";
    } elseif ($logger instanceof Vendor_Tools_Logger) {
        echo "  Log file: {$logger->getLogFile()}<br>";
    } elseif ($logger instanceof Third_Party_Logger) {
        echo "  Handlers: " . implode(', ', $logger->getHandlers()) . "<br>";
    }
}

echo "<hr>";

echo "<h2>Contoh 4: Service Container dengan Namespaces</h2>";

// Dependency Injection Container
class ServiceContainer
{
    private array $services = [];
    private array $instances = [];
    private array $aliases = [];

    public function __construct()
    {
        echo "ServiceContainer initialized<br>";
    }

    /**
     * Register a service with the container
     */
    public function register(string $name, callable $factory, bool $singleton = false): void
    {
        $this->services[$name] = [
            'factory' => $factory,
            'singleton' => $singleton
        ];

        echo "Service registered: {$name}" . ($singleton ? " (singleton)" : "") . "<br>";
    }

    /**
     * Register an alias for a service
     */
    public function alias(string $alias, string $service): void
    {
        $this->aliases[$alias] = $service;
        echo "Alias registered: {$alias} -> {$service}<br>";
    }

    /**
     * Get a service from the container
     */
    public function get(string $name)
    {
        // Resolve alias
        $serviceName = $this->aliases[$name] ?? $name;

        if (!isset($this->services[$serviceName])) {
            throw new InvalidArgumentException("Service not found: {$name}");
        }

        $service = $this->services[$serviceName];

        // Return singleton instance if exists
        if ($service['singleton'] && isset($this->instances[$serviceName])) {
            echo "Returning singleton instance: {$serviceName}<br>";
            return $this->instances[$serviceName];
        }

        // Create new instance
        echo "Creating instance: {$serviceName}<br>";
        $instance = $service['factory']();

        // Store singleton instance
        if ($service['singleton']) {
            $this->instances[$serviceName] = $instance;
        }

        return $instance;
    }

    /**
     * Check if service exists
     */
    public function has(string $name): bool
    {
        $serviceName = $this->aliases[$name] ?? $name;
        return isset($this->services[$serviceName]);
    }

    /**
     * Get all registered services
     */
    public function getServices(): array
    {
        return array_keys($this->services);
    }

    /**
     * Get all aliases
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }
}

// Service classes for different namespaces
class App_Services_DatabaseService
{
    private string $connection;
    private array $queries = [];

    public function __construct(string $host = 'localhost', string $database = 'app')
    {
        $this->connection = "mysql://{$host}/{$database}";
        echo "DatabaseService connected to: {$this->connection}<br>";
    }

    public function query(string $sql): array
    {
        $this->queries[] = $sql;
        echo "DatabaseService: Executing query: {$sql}<br>";

        // Simulate query result
        return [
            'success' => true,
            'data' => ['id' => 1, 'name' => 'Sample Data'],
            'query' => $sql
        ];
    }

    public function getConnection(): string
    {
        return $this->connection;
    }

    public function getQueryCount(): int
    {
        return count($this->queries);
    }
}

class App_Services_CacheService
{
    private array $cache = [];
    private string $driver;
    private int $ttl;

    public function __construct(string $driver = 'memory', int $ttl = 3600)
    {
        $this->driver = $driver;
        $this->ttl = $ttl;
        echo "CacheService initialized with driver: {$driver}, TTL: {$ttl}s<br>";
    }

    public function set(string $key, $value, ?int $ttl = null): void
    {
        $expiry = time() + ($ttl ?? $this->ttl);
        $this->cache[$key] = [
            'value' => $value,
            'expiry' => $expiry
        ];

        echo "CacheService: Set key '{$key}' (expires: " . date('H:i:s', $expiry) . ")<br>";
    }

    public function get(string $key, $default = null)
    {
        if (!isset($this->cache[$key])) {
            echo "CacheService: Key '{$key}' not found<br>";
            return $default;
        }

        $item = $this->cache[$key];

        if (time() > $item['expiry']) {
            unset($this->cache[$key]);
            echo "CacheService: Key '{$key}' expired<br>";
            return $default;
        }

        echo "CacheService: Retrieved key '{$key}'<br>";
        return $item['value'];
    }

    public function has(string $key): bool
    {
        return isset($this->cache[$key]) && time() <= $this->cache[$key]['expiry'];
    }

    public function delete(string $key): void
    {
        unset($this->cache[$key]);
        echo "CacheService: Deleted key '{$key}'<br>";
    }

    public function clear(): void
    {
        $this->cache = [];
        echo "CacheService: Cache cleared<br>";
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getStats(): array
    {
        $active = 0;
        $expired = 0;
        $now = time();

        foreach ($this->cache as $item) {
            if ($now <= $item['expiry']) {
                $active++;
            } else {
                $expired++;
            }
        }

        return [
            'driver' => $this->driver,
            'total_keys' => count($this->cache),
            'active_keys' => $active,
            'expired_keys' => $expired
        ];
    }
}

class App_Services_LoggingService
{
    private array $logs = [];
    private string $level;
    private array $handlers;

    public function __construct(string $level = 'INFO', array $handlers = ['console'])
    {
        $this->level = $level;
        $this->handlers = $handlers;
        echo "LoggingService initialized with level: {$level}, handlers: " . implode(', ', $handlers) . "<br>";
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $entry = [
            'timestamp' => microtime(true),
            'level' => $level,
            'message' => $message,
            'context' => $context
        ];

        $this->logs[] = $entry;

        foreach ($this->handlers as $handler) {
            echo "[LoggingService-{$handler}] [{$level}] {$message}<br>";
        }
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function getLevel(): string
    {
        return $this->level;
    }
}

echo "<h3>Testing Service Container:</h3>";

// Create service container
$container = new ServiceContainer();
echo "<br>";

// Register services
$container->register('App\\Services\\DatabaseService', function () {
    return new App_Services_DatabaseService('localhost', 'production_db');
}, true); // Singleton

$container->register('App\\Services\\CacheService', function () {
    return new App_Services_CacheService('redis', 7200);
}, true); // Singleton

$container->register('App\\Services\\LoggingService', function () {
    return new App_Services_LoggingService('DEBUG', ['console', 'file', 'database']);
}, false); // New instance each time

echo "<br>";

// Register aliases
$container->alias('database', 'App\\Services\\DatabaseService');
$container->alias('cache', 'App\\Services\\CacheService');
$container->alias('logger', 'App\\Services\\LoggingService');

echo "<br>";

// Use services
echo "<h4>Using services through container:</h4>";

// Get database service (singleton)
$db1 = $container->get('database');
$db2 = $container->get('App\\Services\\DatabaseService');

echo "Database instances are same: " . ($db1 === $db2 ? 'Yes' : 'No') . "<br>";

$db1->query('SELECT * FROM users');
$db1->query('SELECT * FROM posts');

echo "<br>";

// Get cache service (singleton)
$cache1 = $container->get('cache');
$cache2 = $container->get('App\\Services\\CacheService');

echo "Cache instances are same: " . ($cache1 === $cache2 ? 'Yes' : 'No') . "<br>";

$cache1->set('user:123', ['name' => 'John Doe', 'email' => 'john@example.com']);
$cache1->set('posts:latest', ['post1', 'post2', 'post3'], 1800);

$userData = $cache2->get('user:123');
echo "Retrieved from cache: " . json_encode($userData) . "<br>";

echo "<br>";

// Get logging service (new instance each time)
$logger1 = $container->get('logger');
$logger2 = $container->get('App\\Services\\LoggingService');

echo "Logger instances are same: " . ($logger1 === $logger2 ? 'Yes' : 'No') . "<br>";

$logger1->info('Application started');
$logger1->debug('Debug information', ['user_id' => 123]);

echo "<br>";

// Container information
echo "<h4>Container Information:</h4>";
echo "Registered services: " . implode(', ', $container->getServices()) . "<br>";
echo "Aliases: " . json_encode($container->getAliases()) . "<br>";

// Service statistics
echo "<br><h4>Service Statistics:</h4>";
echo "Database queries executed: " . $db1->getQueryCount() . "<br>";
echo "Cache stats: " . json_encode($cache1->getStats()) . "<br>";
echo "Logger entries: " . count($logger1->getLogs()) . "<br>";

echo "<hr>";

echo "<h2>Kesimpulan</h2>";
echo "<p>Namespaces dan Autoloading memberikan:</p>";
echo "<ul>";
echo "<li><strong>Code Organization:</strong> Struktur hierarkis yang jelas</li>";
echo "<li><strong>Name Collision Prevention:</strong> Menghindari konflik nama class</li>";
echo "<li><strong>Automatic Loading:</strong> Load class saat dibutuhkan</li>";
echo "<li><strong>PSR-4 Compliance:</strong> Standard untuk file organization</li>";
echo "<li><strong>Third-party Integration:</strong> Mudah integrate library eksternal</li>";
echo "<li><strong>Maintainability:</strong> Code yang lebih mudah di-maintain</li>";
echo "</ul>";

echo "<br><strong>Key Benefits:</strong><br>";
echo "<ul>";
echo "<li>Eliminasi manual include/require statements</li>";
echo "<li>Lazy loading - class dimuat saat dibutuhkan</li>";
echo "<li>Clear separation of concerns</li>";
echo "<li>Better IDE support dengan auto-completion</li>";
echo "<li>Composer integration untuk dependency management</li>";
echo "</ul>";

echo "<br><strong>Best Practices:</strong><br>";
echo "<ul>";
echo "<li>Gunakan PSR-4 autoloading standard</li>";
echo "<li>Satu class per file dengan nama yang sesuai</li>";
echo "<li>Namespace hierarchy yang logis dan konsisten</li>";
echo "<li>Use statements yang terorganisir dengan baik</li>";
echo "<li>Leverage Composer untuk dependency management</li>";
echo "</ul>";

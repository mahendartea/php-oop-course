<?php

/**
 * Pertemuan 4: Inheritance (Pewarisan)
 * Contoh implementasi inheritance dalam OOP PHP
 */

echo "<h1>Pertemuan 4: Inheritance (Pewarisan)</h1>";

echo "<h2>Contoh 1: Basic Inheritance</h2>";

// Parent class (Base class)
class Animal
{
    protected $name;
    protected $species;
    protected $age;

    public function __construct($name, $species, $age = 0)
    {
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;
        echo "Animal '{$name}' dibuat<br>";
    }

    public function eat($food)
    {
        return "{$this->name} sedang makan {$food}";
    }

    public function sleep()
    {
        return "{$this->name} sedang tidur";
    }

    public function makeSound()
    {
        return "{$this->name} membuat suara";
    }

    public function getInfo()
    {
        return "Nama: {$this->name}, Spesies: {$this->species}, Umur: {$this->age} tahun";
    }

    // Protected method - bisa diakses child class
    protected function breathe()
    {
        return "{$this->name} bernapas";
    }
}

// Child class 1
class Dog extends Animal
{
    private $breed;
    private $isGoodBoy;

    public function __construct($name, $breed, $age = 0, $isGoodBoy = true)
    {
        // Memanggil constructor parent
        parent::__construct($name, "Canis lupus", $age);
        $this->breed = $breed;
        $this->isGoodBoy = $isGoodBoy;
        echo "Dog '{$name}' breed {$breed} dibuat<br>";
    }

    // Method overriding - mengganti implementasi parent method
    public function makeSound()
    {
        return "{$this->name} menggonggong: Woof! Woof!";
    }

    // Method tambahan khusus untuk Dog
    public function fetch($item)
    {
        return "{$this->name} mengambil {$item}";
    }

    public function wagTail()
    {
        return "{$this->name} menggoyang ekor" . ($this->isGoodBoy ? " dengan gembira" : "");
    }

    // Override method getInfo() dengan menambahkan info breed
    public function getInfo()
    {
        return parent::getInfo() . ", Breed: {$this->breed}";
    }

    // Menggunakan protected method dari parent
    public function showBreathing()
    {
        return $this->breathe(); // Bisa akses karena protected
    }
}

// Child class 2
class Cat extends Animal
{
    private $color;
    private $isIndoor;

    public function __construct($name, $color, $age = 0, $isIndoor = true)
    {
        parent::__construct($name, "Felis catus", $age);
        $this->color = $color;
        $this->isIndoor = $isIndoor;
        echo "Cat '{$name}' warna {$color} dibuat<br>";
    }

    // Method overriding
    public function makeSound()
    {
        return "{$this->name} mengeong: Meow! Meow!";
    }

    // Method tambahan khusus untuk Cat
    public function purr()
    {
        return "{$this->name} mendengkur dengan nyaman";
    }

    public function climbTree()
    {
        if ($this->isIndoor) {
            return "{$this->name} tidak bisa memanjat pohon karena kucing dalam ruangan";
        }
        return "{$this->name} memanjat pohon dengan lincah";
    }

    public function getInfo()
    {
        return parent::getInfo() . ", Warna: {$this->color}, Tipe: " .
            ($this->isIndoor ? "Dalam ruangan" : "Luar ruangan");
    }
}

echo "<h3>Membuat dan Menggunakan Objects:</h3>";

$dog = new Dog("Buddy", "Golden Retriever", 3);
echo $dog->getInfo() . "<br>";
echo $dog->makeSound() . "<br>";
echo $dog->eat("kibble") . "<br>";
echo $dog->fetch("bola") . "<br>";
echo $dog->wagTail() . "<br>";
echo $dog->showBreathing() . "<br><br>";

$cat = new Cat("Whiskers", "Orange", 2, false);
echo $cat->getInfo() . "<br>";
echo $cat->makeSound() . "<br>";
echo $cat->eat("ikan") . "<br>";
echo $cat->purr() . "<br>";
echo $cat->climbTree() . "<br>";

echo "<hr>";

echo "<h2>Contoh 2: Multilevel Inheritance</h2>";

// Base class
class Vehicle
{
    protected $brand;
    protected $model;
    protected $year;
    protected $fuelType;

    public function __construct($brand, $model, $year, $fuelType = "gasoline")
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->fuelType = $fuelType;
        echo "Vehicle {$brand} {$model} ({$year}) dibuat<br>";
    }

    public function start()
    {
        return "{$this->brand} {$this->model} dinyalakan";
    }

    public function stop()
    {
        return "{$this->brand} {$this->model} dimatikan";
    }

    public function getBasicInfo()
    {
        return "{$this->brand} {$this->model} ({$this->year}) - {$this->fuelType}";
    }
}

// Level 1 inheritance
class Car extends Vehicle
{
    protected $doors;
    protected $transmission;

    public function __construct($brand, $model, $year, $doors = 4, $transmission = "manual", $fuelType = "gasoline")
    {
        parent::__construct($brand, $model, $year, $fuelType);
        $this->doors = $doors;
        $this->transmission = $transmission;
        echo "Car dengan {$doors} pintu dan transmisi {$transmission} siap<br>";
    }

    public function drive()
    {
        return "Mengendarai {$this->brand} {$this->model} dengan transmisi {$this->transmission}";
    }

    public function getCarInfo()
    {
        return $this->getBasicInfo() . " - {$this->doors} pintu, {$this->transmission}";
    }
}

// Level 2 inheritance
class SportsCar extends Car
{
    private $topSpeed;
    private $acceleration;

    public function __construct($brand, $model, $year, $topSpeed, $acceleration, $doors = 2)
    {
        parent::__construct($brand, $model, $year, $doors, "automatic", "gasoline");
        $this->topSpeed = $topSpeed;
        $this->acceleration = $acceleration;
        echo "Sports car dengan kecepatan maksimal {$topSpeed} km/h siap<br>";
    }

    // Override parent method
    public function drive()
    {
        return "Mengendarai {$this->brand} {$this->model} dengan kecepatan tinggi!";
    }

    public function turboBoost()
    {
        return "{$this->brand} {$this->model} mengaktifkan turbo boost!";
    }

    public function getSportsCarInfo()
    {
        return $this->getCarInfo() . " - Top Speed: {$this->topSpeed} km/h, 0-100: {$this->acceleration}s";
    }
}

// Level 2 inheritance (berbeda branch)
class ElectricCar extends Car
{
    private $batteryCapacity;
    private $range;

    public function __construct($brand, $model, $year, $batteryCapacity, $range, $doors = 4)
    {
        parent::__construct($brand, $model, $year, $doors, "automatic", "electric");
        $this->batteryCapacity = $batteryCapacity;
        $this->range = $range;
        echo "Electric car dengan baterai {$batteryCapacity} kWh siap<br>";
    }

    // Override parent method
    public function start()
    {
        return "{$this->brand} {$this->model} dinyalakan tanpa suara (electric)";
    }

    public function charge($percentage)
    {
        return "Mengisi baterai {$this->brand} {$this->model} hingga {$percentage}%";
    }

    public function getElectricInfo()
    {
        return $this->getCarInfo() . " - Battery: {$this->batteryCapacity} kWh, Range: {$this->range} km";
    }
}

echo "<h3>Multilevel Inheritance Example:</h3>";

$sportsCar = new SportsCar("Ferrari", "F8 Tributo", 2023, 340, 2.9);
echo $sportsCar->getSportsCarInfo() . "<br>";
echo $sportsCar->start() . "<br>";
echo $sportsCar->drive() . "<br>";
echo $sportsCar->turboBoost() . "<br><br>";

$electricCar = new ElectricCar("Tesla", "Model S", 2023, 100, 600);
echo $electricCar->getElectricInfo() . "<br>";
echo $electricCar->start() . "<br>";
echo $electricCar->drive() . "<br>";
echo $electricCar->charge(80) . "<br>";

echo "<hr>";

echo "<h2>Contoh 3: Abstract Classes dan Methods</h2>";

// Abstract base class
abstract class Employee
{
    protected $name;
    protected $id;
    protected $salary;
    protected $department;

    public function __construct($name, $id, $salary, $department)
    {
        $this->name = $name;
        $this->id = $id;
        $this->salary = $salary;
        $this->department = $department;
        echo "Employee {$name} (ID: {$id}) dibuat<br>";
    }

    // Concrete method - implementasi sudah ada
    public function getBasicInfo()
    {
        return "ID: {$this->id}, Nama: {$this->name}, Dept: {$this->department}, Gaji: Rp " .
            number_format($this->salary, 0, ',', '.');
    }

    public function work()
    {
        return "{$this->name} sedang bekerja";
    }

    // Abstract methods - harus diimplementasikan di child class
    abstract public function calculateBonus();
    abstract public function getJobDescription();
    abstract public function getRequiredSkills();
}

class Developer extends Employee
{
    private $programmingLanguages;
    private $experience;

    public function __construct($name, $id, $salary, $languages, $experience)
    {
        parent::__construct($name, $id, $salary, "IT");
        $this->programmingLanguages = $languages;
        $this->experience = $experience;
        echo "Developer dengan pengalaman {$experience} tahun siap<br>";
    }

    // Implementasi abstract method
    public function calculateBonus()
    {
        $baseBonus = $this->salary * 0.15; // 15% dari gaji
        $experienceBonus = $this->experience * 500000; // 500k per tahun pengalaman
        return $baseBonus + $experienceBonus;
    }

    public function getJobDescription()
    {
        return "Mengembangkan aplikasi menggunakan " . implode(", ", $this->programmingLanguages);
    }

    public function getRequiredSkills()
    {
        return array_merge($this->programmingLanguages, ["Problem Solving", "Logic Thinking"]);
    }

    // Method khusus Developer
    public function writeCode($language)
    {
        if (in_array($language, $this->programmingLanguages)) {
            return "{$this->name} menulis kode {$language}";
        }
        return "{$this->name} tidak menguasai {$language}";
    }

    public function codeReview()
    {
        return "{$this->name} melakukan code review";
    }
}

class Manager extends Employee
{
    private $teamSize;
    private $managementLevel;

    public function __construct($name, $id, $salary, $department, $teamSize, $level = "middle")
    {
        parent::__construct($name, $id, $salary, $department);
        $this->teamSize = $teamSize;
        $this->managementLevel = $level;
        echo "Manager level {$level} dengan {$teamSize} anak buah siap<br>";
    }

    public function calculateBonus()
    {
        $baseBonus = $this->salary * 0.20; // 20% dari gaji
        $teamBonus = $this->teamSize * 1000000; // 1jt per anggota tim
        $levelMultiplier = $this->managementLevel === "senior" ? 1.5 : 1.0;
        return ($baseBonus + $teamBonus) * $levelMultiplier;
    }

    public function getJobDescription()
    {
        return "Mengelola tim {$this->department} dengan {$this->teamSize} anggota";
    }

    public function getRequiredSkills()
    {
        return ["Leadership", "Communication", "Project Management", "Decision Making"];
    }

    // Method khusus Manager
    public function conductMeeting($topic)
    {
        return "{$this->name} memimpin rapat tentang {$topic}";
    }

    public function evaluateEmployee($employeeName)
    {
        return "{$this->name} mengevaluasi performa {$employeeName}";
    }
}

class Designer extends Employee
{
    private $designTools;
    private $specialization;

    public function __construct($name, $id, $salary, $tools, $specialization)
    {
        parent::__construct($name, $id, $salary, "Creative");
        $this->designTools = $tools;
        $this->specialization = $specialization;
        echo "Designer spesialisasi {$specialization} siap<br>";
    }

    public function calculateBonus()
    {
        $baseBonus = $this->salary * 0.12; // 12% dari gaji
        $toolBonus = count($this->designTools) * 200000; // 200k per tool
        return $baseBonus + $toolBonus;
    }

    public function getJobDescription()
    {
        return "Membuat desain {$this->specialization} menggunakan " . implode(", ", $this->designTools);
    }

    public function getRequiredSkills()
    {
        return array_merge($this->designTools, ["Creativity", "Visual Communication", "Color Theory"]);
    }

    // Method khusus Designer
    public function createDesign($project)
    {
        return "{$this->name} membuat desain untuk project {$project}";
    }

    public function presentDesign($client)
    {
        return "{$this->name} mempresentasikan design kepada {$client}";
    }
}

echo "<h3>Abstract Classes Implementation:</h3>";

$developer = new Developer("Alice Johnson", "DEV001", 12000000, ["PHP", "JavaScript", "Python"], 5);
echo $developer->getBasicInfo() . "<br>";
echo $developer->getJobDescription() . "<br>";
echo "Skills: " . implode(", ", $developer->getRequiredSkills()) . "<br>";
echo "Bonus: Rp " . number_format($developer->calculateBonus(), 0, ',', '.') . "<br>";
echo $developer->writeCode("PHP") . "<br>";
echo $developer->codeReview() . "<br><br>";

$manager = new Manager("Bob Smith", "MGR001", 20000000, "IT", 8, "senior");
echo $manager->getBasicInfo() . "<br>";
echo $manager->getJobDescription() . "<br>";
echo "Skills: " . implode(", ", $manager->getRequiredSkills()) . "<br>";
echo "Bonus: Rp " . number_format($manager->calculateBonus(), 0, ',', '.') . "<br>";
echo $manager->conductMeeting("Sprint Planning") . "<br>";
echo $manager->evaluateEmployee("Alice Johnson") . "<br><br>";

$designer = new Designer("Carol Davis", "DES001", 10000000, ["Photoshop", "Illustrator", "Figma"], "UI/UX");
echo $designer->getBasicInfo() . "<br>";
echo $designer->getJobDescription() . "<br>";
echo "Skills: " . implode(", ", $designer->getRequiredSkills()) . "<br>";
echo "Bonus: Rp " . number_format($designer->calculateBonus(), 0, ',', '.') . "<br>";
echo $designer->createDesign("Mobile App") . "<br>";
echo $designer->presentDesign("Client ABC") . "<br>";

echo "<hr>";

echo "<h2>Contoh 4: Instanceof dan Type Checking</h2>";

// Function untuk demonstrasi polymorphism
function processEmployee(Employee $employee)
{
    echo "<h4>Processing Employee: {$employee->getBasicInfo()}</h4>";
    echo "Job: " . $employee->getJobDescription() . "<br>";
    echo "Work: " . $employee->work() . "<br>";
    echo "Bonus: Rp " . number_format($employee->calculateBonus(), 0, ',', '.') . "<br>";

    // Type checking dengan instanceof
    if ($employee instanceof Developer) {
        echo "Specific action: " . $employee->codeReview() . "<br>";
    } elseif ($employee instanceof Manager) {
        echo "Specific action: " . $employee->conductMeeting("Team Standup") . "<br>";
    } elseif ($employee instanceof Designer) {
        echo "Specific action: " . $employee->createDesign("New Logo") . "<br>";
    }

    echo "<br>";
}

echo "<h3>Polymorphism dan Instanceof:</h3>";

$employees = [
    new Developer("David Wilson", "DEV002", 11000000, ["Java", "Spring"], 3),
    new Manager("Emma Brown", "MGR002", 18000000, "Marketing", 5),
    new Designer("Frank Miller", "DES002", 9500000, ["After Effects", "Premiere"], "Motion Graphics")
];

foreach ($employees as $employee) {
    processEmployee($employee);
}

echo "<hr>";

echo "<h2>Contoh 5: Method Chaining dalam Inheritance</h2>";

class QueryBuilder
{
    protected $table;
    protected $select = [];
    protected $where = [];
    protected $orderBy = [];
    protected $limit;

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns)
    {
        $this->select = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->where[] = "{$column} {$operator} '{$value}'";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function build()
    {
        $sql = "SELECT " . (empty($this->select) ? "*" : implode(", ", $this->select));
        $sql .= " FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(", ", $this->orderBy);
        }

        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }
}

class AdvancedQueryBuilder extends QueryBuilder
{
    private $joins = [];
    private $groupBy = [];
    private $having = [];

    public function join($table, $condition)
    {
        $this->joins[] = "JOIN {$table} ON {$condition}";
        return $this;
    }

    public function leftJoin($table, $condition)
    {
        $this->joins[] = "LEFT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function groupBy($columns)
    {
        $this->groupBy = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function having($condition)
    {
        $this->having[] = $condition;
        return $this;
    }

    // Override parent method dengan functionality tambahan
    public function build()
    {
        $sql = "SELECT " . (empty($this->select) ? "*" : implode(", ", $this->select));
        $sql .= " FROM {$this->table}";

        // Add joins
        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY " . implode(", ", $this->groupBy);
        }

        if (!empty($this->having)) {
            $sql .= " HAVING " . implode(" AND ", $this->having);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(", ", $this->orderBy);
        }

        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }
}

echo "<h3>Method Chaining dengan Inheritance:</h3>";

$basicQuery = (new QueryBuilder())
    ->table('users')
    ->select(['name', 'email'])
    ->where('age', '>', 18)
    ->orderBy('name')
    ->limit(10)
    ->build();

echo "<strong>Basic Query:</strong><br>";
echo $basicQuery . "<br><br>";

$advancedQuery = (new AdvancedQueryBuilder())
    ->table('users')
    ->select(['u.name', 'u.email', 'p.title'])
    ->leftJoin('profiles p', 'u.id = p.user_id')
    ->where('u.active', '=', 1)
    ->where('u.age', '>', 21)
    ->groupBy('u.id')
    ->having('COUNT(p.id) > 0')
    ->orderBy('u.name')
    ->limit(20)
    ->build();

echo "<strong>Advanced Query:</strong><br>";
echo $advancedQuery . "<br>";

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<p>Dari contoh-contoh di atas, kita dapat melihat:</p>";
echo "<ul>";
echo "<li><strong>Basic Inheritance:</strong> Child class mewarisi properties dan methods dari parent</li>";
echo "<li><strong>Method Overriding:</strong> Child class dapat mengubah implementasi method parent</li>";
echo "<li><strong>Multilevel Inheritance:</strong> Inheritance dapat berlapis-lapis</li>";
echo "<li><strong>Abstract Classes:</strong> Memaksa child class implementasi method tertentu</li>";
echo "<li><strong>Polymorphism:</strong> Object dapat diperlakukan sebagai tipe parent-nya</li>";
echo "<li><strong>instanceof:</strong> Untuk mengecek tipe object secara runtime</li>";
echo "<li><strong>Method Chaining:</strong> Tetap berfungsi dalam inheritance hierarchy</li>";
echo "</ul>";

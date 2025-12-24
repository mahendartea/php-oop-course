# 🎓 Kursus Pemrograman Berorientasi Objek (OOP) dengan PHP

Selamat datang di kursus Pemrograman Berorientasi Objek (OOP) dengan PHP!

## 📖 Deskripsi Kursus

Kursus ini dirancang untuk memberikan pemahaman yang kuat tentang konsep-konsep dasar dan lanjutan dari Object-Oriented Programming (OOP) menggunakan bahasa pemrograman PHP. Peserta akan belajar bagaimana merancang, membangun, dan mengelola aplikasi PHP yang scalable, modular, dan mudah dipelihara dengan menerapkan prinsip-prinsip OOP.

Kursus ini terdiri dari 16 pertemuan, termasuk Ujian Tengah Semester (UTS) pada pertemuan ke-8 dan Ujian Akhir Semester (UAS) pada pertemuan ke-16.

## 🚀 Persiapan Sebelum Memulai

### 💻 Persyaratan Sistem
- **Sistem Operasi:** Windows 10/11, macOS 10.15+, atau Linux Ubuntu 18.04+
- **RAM:** Minimum 4GB (Rekomendasi 8GB)
- **Storage:** Minimum 5GB ruang kosong
- **Koneksi Internet:** Untuk download tools dan resources

### 🛠️ Tools yang Harus Diinstal

#### 1. Local Development Server
Pilih salah satu dari opsi berikut:

**Option A: XAMPP (Rekomendasi untuk Pemula)**
- Download dari: https://www.apachefriends.org/
- Include: Apache, MySQL, PHP, phpMyAdmin
- Platform: Windows, macOS, Linux

**Option B: WAMP (Windows only)**
- Download dari: https://www.wampserver.com/
- Include: Apache, MySQL, PHP

**Option C: MAMP (macOS/Windows)**
- Download dari: https://www.mamp.info/
- Include: Apache, MySQL, PHP

**Option D: Laravel Valet (macOS - Advanced)**
- Untuk developer yang sudah familiar dengan command line
- Require: Homebrew, PHP, Composer

#### 2. PHP (Minimum Version 8.0)
- Jika tidak menggunakan package di atas, install PHP secara terpisah
- Download dari: https://www.php.net/downloads
- Pastikan extension yang aktif: `mbstring`, `json`, `pdo`, `openssl`

#### 3. Code Editor
**Visual Studio Code (Highly Recommended)**
- Download dari: https://code.visualstudio.com/
- Extensions yang direkomendasikan:
  - PHP Intelephense
  - PHP Debug
  - PHP DocBlocker
  - Bracket Pair Colorizer
  - Auto Rename Tag
  - Live Server
  - GitLens

**Alternative Code Editors:**
- PHPStorm (Paid, very powerful)
- Sublime Text
- Atom
- Vim/Neovim (Advanced)

#### 4. Web Browser
- Google Chrome (Rekomendasi utama)
- Mozilla Firefox
- Microsoft Edge
- Safari (macOS)

#### 5. Database Management Tool
- phpMyAdmin (included in XAMPP/WAMP/MAMP)
- MySQL Workbench
- HeidiSQL
- DBeaver

#### 6. Version Control
- Git: https://git-scm.com/
- GitHub Desktop (Optional): https://desktop.github.com/

#### 7. Package Manager (Optional - untuk pertemuan lanjutan)
- Composer: https://getcomposer.org/
- Node.js & NPM: https://nodejs.org/

### 📝 Setup Langkah demi Langkah

#### Langkah 1: Install XAMPP
1. Download XAMPP dari website resmi
2. Jalankan installer dan ikuti petunjuk
3. Start Apache dan MySQL dari XAMPP Control Panel
4. Test dengan membuka http://localhost di browser

#### Langkah 2: Install Visual Studio Code
1. Download dan install VSCode
2. Install extensions yang direkomendasikan
3. Set PHP executable path di settings

#### Langkah 3: Setup Project Directory
```bash
# Buat folder project di htdocs (XAMPP)
C:\xampp\htdocs\php-oop-course\

# Atau di www (WAMP)
C:\wamp64\www\php-oop-course\

# Atau di htdocs (MAMP)
/Applications/MAMP/htdocs/php-oop-course/
```

#### Langkah 4: Test PHP Installation
Buat file `test.php` di folder project:
```php
<?php
phpinfo();
echo "<h1>PHP OOP Course - Ready to Start!</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
?>
```

Akses via: http://localhost/php-oop-course/test.php

#### Langkah 5: Install Git (Optional)
1. Download dan install Git
2. Konfigurasi:
```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

#### Langkah 6: Clone Course Repository
```bash
cd C:\xampp\htdocs\
git clone https://github.com/mahendartea/php-oop-course.git
```

### 📁 Struktur Folder Course
```
php-oop-course/
├── README.md
├── pertemuan-01/
│   ├── README.md
│   └── example.php
├── pertemuan-02/
│   ├── README.md
│   └── example.php
├── ...
├── pertemuan-16/
│   ├── README.md
│   └── example.php
├── assets/
│   ├── images/
│   └── docs/
└── final-project/
    └── crud-app/
```

### 🔧 Troubleshooting Common Issues

#### Apache tidak bisa start
- Port 80 sudah digunakan aplikasi lain (Skype, IIS)
- Solusi: Ganti port Apache ke 8080 atau stop aplikasi yang conflict

#### PHP tidak dikenali di Command Line
- PATH environment variable belum di-set
- Solusi: Tambahkan PHP directory ke PATH

#### MySQL tidak bisa connect
- Port 3306 sudah digunakan
- Password MySQL belum di-set
- Solusi: Check port di my.ini atau ganti port

#### Permission denied (Linux/macOS)
```bash
sudo chmod -R 755 /path/to/htdocs
sudo chown -R $USER:$USER /path/to/htdocs
```

### 📚 Resources Tambahan
- **PHP Manual:** https://www.php.net/manual/
- **W3Schools PHP OOP:** https://www.w3schools.com/php/php_oop_what_is.php
- **PHP The Right Way:** https://phptherightway.com/
- **Laracasts PHP OOP:** https://laracasts.com/series/object-oriented-principles-in-php

### 💬 Support & Bantuan
- Buat issue di GitHub repository
- Join Discord/Slack community (jika ada)
- Email instructor untuk bantuan lebih lanjut

## 📋 Silabus Kursus

### 🎯 Learning Path & Objectives

Kursus ini dibagi menjadi 4 modul utama dengan progression yang sistematis:

#### 📚 **Modul 1: Fondasi OOP (Pertemuan 1-4)**
Membangun pemahaman dasar tentang konsep Object-Oriented Programming

*   **Pertemuan 01:** 🎯 [Pengenalan OOP, Class, dan Object](./pertemuan-01/)
    - *Objektif:* Memahami paradigma OOP, membuat class pertama, dan instantiasi object
    - *Output:* Mampu membuat class sederhana dengan property dan method dasar

*   **Pertemuan 02:** 🔧 [Properti dan Method](./pertemuan-02/)
    - *Objektif:* Menguasai property dan method, parameter, return value
    - *Output:* Membuat class dengan method yang kompleks dan property yang terstruktur

*   **Pertemuan 03:** 🏗️ [Constructor dan Destructor](./pertemuan-03/)
    - *Objektif:* Memahami lifecycle object, initialization, dan cleanup
    - *Output:* Implementasi constructor untuk setup object dan destructor untuk cleanup

*   **Pertemuan 04:** 🧬 [Inheritance (Pewarisan)](./pertemuan-04/)
    - *Objektif:* Menguasai konsep pewarisan, parent-child relationship
    - *Output:* Membuat hierarchy class dengan inheritance yang efektif

#### 🔒 **Modul 2: Encapsulation & Abstraction (Pertemuan 5-7)**
Menguasai prinsip encapsulation dan abstraction untuk code yang robust

*   **Pertemuan 05:** 🔒 [Visibility (Public, Private, Protected)](./pertemuan-05/)
    - *Objektif:* Memahami access modifier, data hiding, encapsulation
    - *Output:* Implementasi proper encapsulation dengan getter/setter

*   **Pertemuan 06:** 🎨 [Abstract Class dan Method](./pertemuan-06/)
    - *Objektif:* Menguasai abstraction, template method pattern
    - *Output:* Membuat abstract class sebagai blueprint untuk inheritance

*   **Pertemuan 07:** 🔌 [Interface](./pertemuan-07/)
    - *Objektif:* Memahami contract programming, multiple inheritance alternative
    - *Output:* Implementasi interface untuk loose coupling

#### ✅ **Evaluasi Tengah**
*   **Pertemuan 08:** [Ujian Tengah Semester (UTS)](./pertemuan-08/)
    - *Cakupan:* Semua materi Modul 1 & 2 (Pertemuan 1-7)
    - *Format:* Teori + Praktik coding + Studi kasus

#### 🚀 **Modul 3: Advanced OOP Features (Pertemuan 9-11)**
Menguasai fitur-fitur advanced untuk aplikasi yang scalable

*   **Pertemuan 09:** ⚡ [Static Properties dan Methods](./pertemuan-09/)
    - *Objektif:* Memahami static context, utility methods, shared data
    - *Output:* Implementasi static untuk helper class dan shared resources

*   **Pertemuan 10:** 🧩 [Traits](./pertemuan-10/)
    - *Objektif:* Menguasai code reuse horizontal, mixin pattern
    - *Output:* Membuat trait untuk shared functionality across classes

*   **Pertemuan 11:** 📦 [Namespaces dan Autoloading](./pertemuan-11/)
    - *Objektif:* Organisasi code, menghindari name collision, lazy loading
    - *Output:* Struktur project dengan namespace dan autoloader

#### 🏗️ **Modul 4: Design Patterns & Best Practices (Pertemuan 12-15)**
Implementasi design patterns dan best practices untuk aplikasi real-world

*   **Pertemuan 12:** ⚠️ [Error Handling dan Exception](./pertemuan-12/)
    - *Objektif:* Menguasai exception handling, custom exception, debugging
    - *Output:* Robust error handling system untuk aplikasi production

*   **Pertemuan 13:** 🏛️ [Prinsip SOLID](./pertemuan-13/)
    - *Objektif:* Memahami 5 prinsip SOLID untuk clean architecture
    - *Output:* Refactoring code untuk compliance dengan SOLID principles

*   **Pertemuan 14:** 🎯 [Design Patterns (Creational & Structural)](./pertemuan-14/)
    - *Objektif:* Implementasi Singleton, Factory, Observer, Decorator patterns
    - *Output:* Aplikasi design patterns untuk solving common problems

*   **Pertemuan 15:** 💼 [Studi Kasus: Aplikasi CRUD dengan OOP](./pertemuan-15/)
    - *Objektif:* Integration semua konsep dalam aplikasi real-world
    - *Output:* Full-stack CRUD application dengan proper OOP architecture

#### 🎓 **Final Assessment**
*   **Pertemuan 16:** [Ujian Akhir Semester (UAS)](./pertemuan-16/)
    - *Cakupan:* Comprehensive - Semua materi course (Pertemuan 1-15)
    - *Format:* Project-based assessment + Teori mendalam + Code review

### 📈 **Learning Progression**

```
Basic OOP → Encapsulation → Advanced Features → Design Patterns → Real Application
    ↓            ↓              ↓                  ↓                ↓
Pertemuan    Pertemuan      Pertemuan          Pertemuan       Pertemuan
  1-4          5-7            9-11              12-14            15
    ↓            ↓              ↓                  ↓                ↓
  UTS (8)    Integration    Namespace &        SOLID &         Final Project
            & Practice      Autoloading       Patterns           & UAS (16)
```

### 🎯 **Expected Learning Outcomes**

Setelah menyelesaikan course ini, peserta akan mampu:

1. **Fundamental Mastery**
   - Menguasai semua konsep dasar OOP (Class, Object, Inheritance, Encapsulation, Polymorphism)
   - Menulis code PHP yang clean, maintainable, dan scalable

2. **Advanced Implementation**
   - Menggunakan design patterns untuk solving complex problems
   - Menerapkan SOLID principles dalam development
   - Membuat aplikasi dengan proper error handling

3. **Professional Development**
   - Mengorganisir project dengan namespace dan autoloading
   - Menggunakan modern PHP features dan best practices
   - Membuat aplikasi full-stack dengan OOP architecture

4. **Real-world Application**
   - Membangun aplikasi CRUD yang production-ready
   - Menerapkan semua konsep OOP dalam project nyata
   - Melakukan code review dan refactoring

### 📋 **Prerequisites**

**Wajib:**
- Pemahaman basic PHP (variables, functions, arrays, control structures)
- Pengalaman dengan HTML/CSS dasar
- Familiar dengan database concepts (MySQL)

**Recommended:**
- Pengalaman dengan web development
- Basic understanding of software design
- Familiar dengan command line operations

---

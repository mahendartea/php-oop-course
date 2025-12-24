# Setup VitePress untuk PHP OOP Course

Website ini dibangun menggunakan VitePress untuk dokumentasi yang modern dan cepat.

## 🚀 Quick Start

### Prerequisites
- Node.js 18+ 
- npm atau yarn

### Installation

```bash
# Install dependencies
npm install

# Run development server
npm run docs:dev

# Build for production
npm run docs:build

# Preview production build
npm run docs:preview
```

## 📁 Struktur Folder

```
php-oop-course/
├── .vitepress/
│   ├── config.mts          # Konfigurasi VitePress
│   └── dist/               # Build output (generated)
├── .github/
│   └── workflows/
│       └── deploy.yml      # GitHub Actions untuk auto-deploy
├── public/
│   └── logo.svg            # Logo website
├── pertemuan-01/
│   ├── README.md           # Konten pertemuan 1
│   └── example.php         # Contoh kode
├── pertemuan-02/
│   └── ...
├── index.md                # Homepage
├── package.json
└── README.md               # File ini
```

## 🌐 Deployment

Website ini otomatis di-deploy ke GitHub Pages setiap kali ada push ke branch `main`.

URL: `https://mahendartea.github.io/php-oop-course/`

### Setup GitHub Pages

1. Go to repository Settings
2. Navigate to Pages
3. Source: GitHub Actions
4. Push ke main branch akan trigger deployment

## 📝 Menambah Konten Baru

Setiap pertemuan memiliki folder sendiri dengan file `README.md`. Untuk menambah pertemuan baru:

1. Buat folder baru: `pertemuan-XX/`
2. Tambahkan `README.md` di dalamnya
3. Update sidebar di `.vitepress/config.mts`

## 🎨 Kustomisasi

Edit `.vitepress/config.mts` untuk:
- Mengubah title dan description
- Menambah/mengurangi item di sidebar
- Mengubah tema dan styling

## 📚 Resources

- [VitePress Documentation](https://vitepress.dev/)
- [GitHub Pages](https://pages.github.com/)

#!/bin/bash

# Script untuk menambahkan icon ke semua file pertemuan

# Pertemuan 02
sed -i '' '1s/^# Pertemuan 2:/# 🔧 Pertemuan 2:/' pertemuan-02/index.md
sed -i '' 's/^## Tujuan Pembelajaran/## 📚 Tujuan Pembelajaran/' pertemuan-02/index.md
sed -i '' 's/^## Properti (Properties)/## 📦 Properti (Properties)/' pertemuan-02/index.md
sed -i '' 's/^## Method$/## ⚙️ Method/' pertemuan-02/index.md
sed -i '' 's/^## Getter dan Setter Method/## 🔄 Getter dan Setter Method/' pertemuan-02/index.md
sed -i '' 's/^## Method Chaining/## 🔗 Method Chaining/' pertemuan-02/index.md
sed -i '' 's/^## Best Practices/## ✨ Best Practices/' pertemuan-02/index.md
sed -i '' 's/^## Contoh Implementasi/## 💻 Contoh Implementasi/' pertemuan-02/index.md
sed -i '' 's/^## Latihan$/## 📝 Latihan/' pertemuan-02/index.md
sed -i '' 's/^## Tugas Rumah/## 🏠 Tugas Rumah/' pertemuan-02/index.md

# Pertemuan 03
sed -i '' '1s/^# Pertemuan 3:/# 🏗️ Pertemuan 3:/' pertemuan-03/index.md
sed -i '' 's/^## Tujuan Pembelajaran/## 📚 Tujuan Pembelajaran/' pertemuan-03/index.md
sed -i '' 's/^## Constructor$/## 🎬 Constructor/' pertemuan-03/index.md
sed -i '' 's/^## Destructor$/## 🔚 Destructor/' pertemuan-03/index.md
sed -i '' 's/^## Best Practices/## ✨ Best Practices/' pertemuan-03/index.md
sed -i '' 's/^## Common Patterns/## 🎨 Common Patterns/' pertemuan-03/index.md
sed -i '' 's/^## Error Handling/## ⚠️ Error Handling/' pertemuan-03/index.md
sed -i '' 's/^## Contoh Implementasi/## 💻 Contoh Implementasi/' pertemuan-03/index.md
sed -i '' 's/^## Latihan$/## 📝 Latihan/' pertemuan-03/index.md
sed -i '' 's/^## Tugas Rumah/## 🏠 Tugas Rumah/' pertemuan-03/index.md

echo "Icons added successfully!"

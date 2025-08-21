# CSV Contact Importer

[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/Tests-Passing-brightgreen.svg)](https://github.com/yourusername/csv-importer)

A modern Laravel 12 + Vue 3 application for importing contacts from CSV files with automatic delimiter detection, data validation, and duplicate prevention.

## 🚀 Demo

- **Smart CSV Processing**: Automatic detection of comma (`,`) and semicolon (`;`) delimiters
- **Data Validation**: Real-time validation of emails and date formats
- **Duplicate Prevention**: Prevents duplicate emails both within files and against database
- **Responsive UI**: Modern interface built with TailwindCSS and Vue 3
- **Pagination**: Efficient contact listing with configurable page sizes

## ✨ Features

- 📁 **CSV Upload**: Web-based file upload with drag & drop support
- 🔍 **Smart Detection**: Automatic delimiter detection (comma/semicolon)
- ✅ **Data Validation**: Email validation and multiple date format parsing
- 🚫 **Duplicate Prevention**: Email deduplication within files and database
- 📊 **Processing Summary**: Real-time feedback with import statistics
- 📋 **Contact Listing**: Paginated contact display with search capabilities
- 📱 **Responsive Design**: Mobile-friendly interface with TailwindCSS
- ⚡ **Performance**: Chunked imports for large files (500 rows per batch)

## 🛠️ Tech Stack

### Backend
- **Laravel 12** - Modern PHP framework
- **SQLite** - Lightweight database
- **Carbon** - Date parsing and manipulation
- **PHP 8.2+** - Latest PHP features

### Frontend
- **Vue 3** - Progressive JavaScript framework
- **Composition API** - Modern Vue reactivity
- **TailwindCSS** - Utility-first CSS framework
- **Axios** - HTTP client for API requests

### Build Tools
- **Vite** - Fast build tool and dev server
- **Laravel Vite Plugin** - Laravel integration
- **Vue Plugin** - Vue.js support

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- npm

## 🚀 Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/csv-importer.git
cd csv-importer
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup database
```bash
touch database/database.sqlite
php artisan migrate
```

### 5. Install frontend dependencies
```bash
npm install
```

### 6. Build assets
```bash
npm run build
```

### 7. Start development server
```bash
php artisan serve
```

Then open [http://127.0.0.1:8000](http://127.0.0.1:8000)

## 📁 CSV Format Requirements

Your CSV file must include these columns (in any order):

### Required Columns
- **name** (or "full name", "fullname") - Contact's full name
- **email** (or "e-mail") - Valid email address

### Optional Columns
- **phone** (or "phone number", "telephone") - Phone number
- **birthdate** (or "birth date", "date of birth", "dob", "birthday") - Date of birth

### Example CSV
```csv
name,email,phone,birthdate
João Silva,joao.silva@email.com,(11) 99999-1111,1990-05-15
Maria Santos,maria.santos@email.com,(11) 99999-2222,1985-08-20
```

### Supported Date Formats
- `Y-m-d` (1990-05-15)
- `d/m/Y` (15/05/1990)
- `d-m-Y` (15-05-1990)
- `m/d/Y` (05/15/1990)
- `m-d-Y` (05-15-1990)
- `d.m.Y` (15.05.1990)

### Supported Delimiters
- **Comma** (`,`) - Standard CSV format
- **Semicolon** (`;`) - European CSV format

## 📖 Usage

1. **Upload CSV**: Choose a CSV file and click "Import CSV"
2. **Processing**: Watch real-time validation and processing
3. **Summary**: Review import statistics (total, imported, duplicates, invalid)
4. **Browse**: Navigate through imported contacts with pagination

## 📁 Sample Files

- `sample-contacts.csv` - Small sample (10 contacts)
- `contacts_sample_large.csv` - Large sample (50+ contacts)
- `sample-different-formats.csv` - Various date formats

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📝 Notes

- **Email Uniqueness**: Email has a unique index at the database level
- **Date Parsing**: Supports multiple international date formats
- **Performance**: Large files are processed in chunks of 500 rows
- **Validation**: Real-time validation with detailed error messages
- **Responsive**: Mobile-friendly interface design


## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [Vue.js](https://vuejs.org) - The Progressive JavaScript Framework
- [TailwindCSS](https://tailwindcss.com) - A utility-first CSS framework

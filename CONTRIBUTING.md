# Contributing to CSV Contact Importer

Thank you for your interest in contributing to this project!

## Development Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/csv-importer.git
   cd csv-importer
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

5. **Install frontend dependencies**
   ```bash
   npm install
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

## Testing

Run the test suite:
```bash
php artisan test
```

## CSV Format

The application accepts CSV files with the following columns (in any order):

- **name** (required) - Full name of the contact
- **email** (required) - Email address (must be valid)
- **phone** (optional) - Phone number
- **birthdate** (optional) - Date of birth in various formats

### Supported Date Formats
- `Y-m-d` (1990-05-15)
- `d/m/Y` (15/05/1990)
- `d-m-Y` (15-05-1990)
- `m/d/Y` (05/15/1990)
- `m-d-Y` (05-15-1990)
- `d.m.Y` (15.05.1990)

### Supported Delimiters
- Comma (`,`)
- Semicolon (`;`)

## Features

- CSV upload with automatic delimiter detection
- Data validation and error handling
- Duplicate email detection
- Pagination for contact listing
- Responsive UI with TailwindCSS
- Vue.js frontend

## Code Style

This project follows Laravel conventions and uses:
- Laravel Pint for PHP code formatting
- ESLint for JavaScript code formatting
- TailwindCSS for styling

## Pull Request Process

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests (`php artisan test`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

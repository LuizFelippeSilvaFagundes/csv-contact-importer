# CSV Contact Importer

A small Laravel 12 + Vue 3 app to import contacts from a CSV file and display a processing summary.

## Features
- CSV upload via web (columns can be in any order): `name`, `email`, `phone`, `birthdate`
- Validates rows, parses common date formats
- Deduplicates by email (within file and against DB)
- Summary: total rows read, imported, duplicates, invalid
- List of contacts with pagination

## Getting started

Requirements: PHP 8.2+, Composer, Node 18+.

```bash
# Install PHP dependencies
composer install

# Copy env and generate key
copy .env.example .env   # Windows PowerShell
php artisan key:generate

# Run migrations
php artisan migrate

# Install frontend dependencies
npm install

# Start dev servers (PHP + Vite)
composer run dev
```

Then open `http://127.0.0.1:8000`.

## CSV Format Requirements

Your CSV file must include these columns (in any order):

### Required Columns:
- **name** (or "full name", "fullname")
- **email** (or "e-mail") 
- **phone** (or "phone number", "telephone") - *optional*
- **birthdate** (or "birth date", "date of birth", "dob", "birthday") - *optional*

### Example CSV:
```csv
name,email,phone,birthdate
João Silva,joao.silva@email.com,(11) 99999-1111,1990-05-15
Maria Santos,maria.santos@email.com,(11) 99999-2222,1985-08-20
```

### Supported Date Formats:
- `Y-m-d` (1990-05-15)
- `d/m/Y` (15/05/1990)
- `d-m-Y` (15-05-1990)
- `m/d/Y` (05/15/1990)
- `m-d-Y` (05-15-1990)
- `d.m.Y` (15.05.1990)

## Usage
- Choose a CSV file and click "Import CSV".
- After processing, the summary cards show totals.
- The contacts table supports pagination size 10/25/50.

## Sample Files
- `sample-contacts.csv` - Small sample (10 contacts)
- `contacts_sample_large.csv` - Large sample (50+ contacts)

## Notes
- Email has a unique index at the database level.
- Birthdate accepted formats include: `Y-m-d`, `d/m/Y`, `m/d/Y`, etc.
- Large files are inserted in chunks of 500 rows.

## Tests
```bash
php artisan test
```

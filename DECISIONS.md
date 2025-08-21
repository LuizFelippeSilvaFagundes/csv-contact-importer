# Decisions

- Chosen stack: Laravel 12 + Vue 3 with Vite. Keeps the code modern and DX simple.
- Data model: `contacts` table with unique `email` to enforce deduplication at DB level.
- CSV parsing: native `fgetcsv` for simplicity and performance; handles BOM and flexible headers.
- Validation: requires `name` and a valid `email`; parses birthdate with several common formats.
- Deduplication: in-file dedupe by email and DB dedupe by querying existing emails; final safety via unique index.
- Performance: batch `insert` in chunks of 500 rows; fallback to row-by-row on constraint conflicts.
- UX: Single-page view with upload, summary cards, and paginated list (10/25/50).
- Security: CSRF protected via Axios header; file type restricted to CSV/TXT.
- Testing: Feature test stub included for importer endpoint.

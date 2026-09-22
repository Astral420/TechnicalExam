# Book & Author Management — Technical Exam

A book & author management system, mainly a technical exam showcase. 

## Getting Started

### Prerequisites
- **PHP** >= 8.2 with SQLite and PDO extensions enabled
- **Laravel 12**
- **Composer**
- **Node.js** (v18+) & **npm**


### Installation Steps

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd TechnicalExam
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies & build assets**:
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**:
   Ensure `.env` exists (copy from `.env.example` if needed):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Ensure the SQLite database file exists:
   ```bash
   touch database/database.sqlite
   ```

5. **Run Migrations & Seeders**:

   ```bash
   php artisan migrate:fresh --seed
   ```

   Without Database Seeding:
   ```bash
   php artisan migrate:fresh
   ```

6. **Start the Development Server**:
   ```bash
   php artisan serve
   ```
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## Running Tests

The test suite covers full CRUD operations, database cascade deletions, validations, search filters, and AJAX endpoints:

```bash
php artisan test
```

---

## 📁 Project Architecture

```
app/
├── Actions/                  # Single-responsibility DB transaction actions
│   ├── CreateAuthorAction.php
│   ├── UpdateAuthorAction.php
│   ├── DeleteAuthorAction.php
│   ├── CreateBookAction.php
│   ├── UpdateBookAction.php
│   └── DeleteBookAction.php
├── Http/
│   ├── Controllers/          # AuthorController & BookController (supports standard + AJAX)
│   └── Requests/             # Form Requests with validation rules
└── Models/                   # Author & Book Eloquent models with relationships

database/
├── factories/                # AuthorFactory & BookFactory
├── migrations/               # authors & books tables
└── seeders/                  # DatabaseSeeder

resources/
├── css/                      # Main Stylesheet with TailwindCSS
└── views/
    ├── layouts/app.blade.php # Master layout with dark spine sidebar
    ├── authors/              # index, show, create, edit
    ├── books/                # index, show, create, edit
    └── vendor/pagination/    # Custom ledger pagination
```

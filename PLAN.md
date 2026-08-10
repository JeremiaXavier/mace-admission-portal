# MACE B.Tech Spot Admission Portal — Project Plan

> **Institution:** Mar Athanasius College of Engineering (MACE), Kothamangalam
> **Framework:** PHP CodeIgniter 4 (Bare-metal optimized)
> **Environment:** Shared cPanel — 512 MB Total RAM (3 apps sharing)
> **Date:** August 2026

---

## Table of Contents

1. [High-Concurrency Strategy](#1-high-concurrency-strategy)
2. [Database Schema & Indexing](#2-database-schema--indexing)
3. [Clerk Query Logic](#3-clerk-query-logic)
4. [CodeIgniter 4 File & Directory Structure](#4-codeigniter-4-file--directory-structure)
5. [Step-by-Step Development Roadmap](#5-step-by-step-development-roadmap)

---

## 1. High-Concurrency Strategy

### The Core Problem

At peak load, 2,000+ students submit the registration form within a narrow time window (~30–60 minutes). On a shared server with ≤512 MB total RAM, the two primary failure modes are:

| Failure Mode | Cause | Consequence |
|---|---|---|
| **Memory Exhaustion** | PHP session files + ORM overhead per request | Server crash / 500 errors for all apps |
| **Disk File-Locking** | PHP default session handler writing to disk | Concurrent requests block, timeouts cascade |

### Mitigation Architecture

#### Rule 1 — Stateless Student Routes (Zero Session I/O)

- PHP sessions (`session_start()`, CodeIgniter's `session()` helper) are **completely disabled** on all student-facing routes (`/register`, `/register/submit`).
- No cookies of any kind are set during registration except what the browser sends.
- The registration result (success/failure) is rendered inline on the same POST response — no redirect-after-post that requires session flash messages.
- **Memory saving per request:** ~128 KB–512 KB (no session serialization, no file descriptor locks).

```
Student Browser  →  POST /register/submit
                          │
                    [RegistrationController]
                          │
                    Raw PDO / QueryBuilder INSERT
                          │
                    Render confirmation HTML (inline)
                          │
                    PHP process exits — NO session written
```

#### Rule 2 — Bare-Metal Inserts (No ORM Overhead)

- Student POST handler uses CI4's **Query Builder** (`$db->table()->insert()`) or raw PDO prepared statements — **not** CI4 Model with `save()` / `insert()` events.
- CI4 Model `$beforeInsert` / `$afterInsert` callbacks, `$useTimestamps`, `$returnType` casting — all **off** for the registration path.
- Validation is handled via CI4's lightweight `Services::validation()` without loading unnecessary libraries.
- **No auto-loading** of heavy libraries in `app/Config/Autoload.php` for the public-facing registration route group.

#### Rule 3 — Lightweight Frontend Stack

- **Tailwind CSS** loaded via Play CDN (`<script src="https://cdn.tailwindcss.com"></script>`) — zero static asset build tooling, zero disk writes on the server.
- No JavaScript bundlers. Any JS is vanilla inline script.
- **No Ajax polling** on student form. Single synchronous POST, single inline HTML response.

#### Rule 4 — PHP Process Footprint Control

| Setting | Recommended Value | Location |
|---|---|---|
| `memory_limit` | `64M` per process | `.htaccess` / `php.ini` override |
| `max_execution_time` | `15` seconds | `.htaccess` |
| `output_buffering` | `Off` | `.htaccess` |
| `session.auto_start` | `0` | `.htaccess` |
| CI4 Debug Toolbar | **Disabled** in `production` | `app/Config/Toolbar.php` |
| CI4 Logger threshold | `4` (errors only) | `app/Config/Logger.php` |

#### Rule 5 — Database Connection Pooling Emulation

- MySQL `CONNECT_TIMEOUT` set to `5` seconds in `app/Config/Database.php`.
- Use **persistent connections** (`pConnect = true`) in the DB config to reuse TCP connections across PHP-FPM workers (reduces TCP handshake overhead under concurrency).
- Set `strictOn = false` to avoid expensive strict-mode validation overhead per insert.

#### Concurrency Math (Sanity Check)

```
2,000 students in 30 min = ~66 req/min = ~1.1 req/sec

PHP-FPM typical worker: ~8–12 MB resident RAM
Workers available in 512 MB (shared): ~20–40 (after OS + MySQL + other apps)
At 1.1 req/sec with ~500 ms avg response → ~1 concurrent worker at any time

Verdict: Architecture is safe. Peak burst handling relies on fast MySQL inserts
(~5–15 ms each) keeping workers free quickly.
```

---

## 2. Database Schema & Indexing

### Single Table: `spot_registrations`

```sql
CREATE TABLE IF NOT EXISTS `spot_registrations` (
    `id`                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,

    -- Student Identity
    `full_name`           VARCHAR(150)    NOT NULL,
    `mobile_no`           VARCHAR(15)     NOT NULL,
    `email`               VARCHAR(150)    NULL DEFAULT NULL,
    `time_of_reporting`   TIME            NULL DEFAULT NULL,

    -- KEAM / Entrance Details
    `entrance_roll_no`    VARCHAR(30)     NOT NULL,
    `entrance_rank`       INT UNSIGNED    NOT NULL,
    `eligible_category`   ENUM(
                              'SM','EWS','EZ','MU','BH','LA',
                              'BX','KU','VK','DV','KN',
                              'SC','ST','OEC','XS','PI','PT'
                          )               NOT NULL,

    -- Current Admission Status
    `admitted_elsewhere`  TINYINT(1)      NOT NULL DEFAULT 0,   -- 0=No, 1=Yes
    `present_college`     VARCHAR(200)    NULL DEFAULT NULL,
    `present_branch`      VARCHAR(100)    NULL DEFAULT NULL,
    `has_noc`             TINYINT(1)      NULL DEFAULT NULL,    -- 1=Yes, 0=No
    `has_tc_cc`           TINYINT(1)      NULL DEFAULT NULL,    -- 1=Yes, 0=No

    -- Branch Preferences (NULL = not selected)
    `option_1`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,
    `option_2`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,
    `option_3`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,
    `option_4`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,
    `option_5`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,
    `option_6`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,
    `option_7`            ENUM('AI','CE','CSE','DS','EEE','ECE','ME') NULL DEFAULT NULL,

    -- Declaration
    `declaration`         TINYINT(1)      NOT NULL DEFAULT 0,

    -- Audit
    `registered_at`       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ip_address`          VARCHAR(45)     NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    -- Critical Indexes
    UNIQUE KEY `uq_entrance_roll_no`        (`entrance_roll_no`),
    INDEX      `idx_entrance_rank`          (`entrance_rank`     ASC),
    INDEX      `idx_eligible_category`      (`eligible_category`),
    INDEX      `idx_category_rank`          (`eligible_category`, `entrance_rank` ASC),
    INDEX      `idx_registered_at`          (`registered_at`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
```

### Index Rationale

| Index Name | Columns | Query Pattern Served |
|---|---|---|
| `PRIMARY` | `id` | Internal row lookup |
| `uq_entrance_roll_no` | `entrance_roll_no` | Duplicate prevention on INSERT; Roll No search |
| `idx_entrance_rank` | `entrance_rank ASC` | SM filter: `ORDER BY entrance_rank ASC` full scan |
| `idx_eligible_category` | `eligible_category` | Category equality filter |
| `idx_category_rank` | `(eligible_category, entrance_rank)` | **Composite** — Category filter + rank sort (covering index; avoids filesort) |
| `idx_registered_at` | `registered_at` | Chronological export / time-based queries |

> **Key insight:** The composite index `(eligible_category, entrance_rank)` is the most important clerk query optimization. MySQL can satisfy `WHERE eligible_category = 'EZ' ORDER BY entrance_rank ASC` entirely from the index without a filesort or full table scan.

---

## 3. Clerk Query Logic

### Filter Mode A — State Merit (SM) View

> Show **ALL** registered applicants regardless of category, sorted by rank.

```sql
-- SM View: all candidates, rank order
SELECT
    id, entrance_rank, entrance_roll_no, full_name, mobile_no,
    eligible_category, time_of_reporting,
    option_1, option_2, option_3, option_4, option_5, option_6, option_7
FROM spot_registrations
ORDER BY entrance_rank ASC
LIMIT 200 OFFSET 0;   -- paginate in batches of 200
```

**Index used:** `idx_entrance_rank` — full index scan in sorted order, no filesort.

---

### Filter Mode B — Reserved Category View (EZ, MU, SC, ST, EWS, …)

> Show **ONLY** candidates who registered under a specific category, sorted by rank.

```sql
-- Category View: e.g., Ezhava (EZ)
SELECT
    id, entrance_rank, entrance_roll_no, full_name, mobile_no,
    eligible_category, time_of_reporting,
    option_1, option_2, option_3, option_4, option_5, option_6, option_7
FROM spot_registrations
WHERE eligible_category = :category          -- e.g., 'EZ'
ORDER BY entrance_rank ASC
LIMIT 200 OFFSET 0;
```

**Index used:** `idx_category_rank (eligible_category, entrance_rank)` — index range scan, zero filesort.

---

### Search Query (Roll No or Rank)

```sql
-- By Roll No (exact match via UNIQUE index)
SELECT * FROM spot_registrations
WHERE entrance_roll_no = :roll_no
LIMIT 1;

-- By Rank (could be multiple if duplicate ranks exist — unlikely but handle gracefully)
SELECT * FROM spot_registrations
WHERE entrance_rank = :rank
ORDER BY registered_at ASC;
```

---

### CodeIgniter 4 Query Builder Implementation

```php
// SM Filter
$db->table('spot_registrations')
   ->select('id, entrance_rank, entrance_roll_no, full_name, mobile_no,
             eligible_category, time_of_reporting,
             option_1, option_2, option_3, option_4, option_5, option_6, option_7')
   ->orderBy('entrance_rank', 'ASC')
   ->get(200, $offset)
   ->getResultArray();

// Category Filter
$db->table('spot_registrations')
   ->select('...')
   ->where('eligible_category', $category)
   ->orderBy('entrance_rank', 'ASC')
   ->get(200, $offset)
   ->getResultArray();
```

---

## 4. CodeIgniter 4 File & Directory Structure

```
admission_mace/                          ← Project root (maps to public_html or subdirectory)
│
├── app/
│   ├── Config/
│   │   ├── App.php                      ← Base URL, charset, timezone
│   │   ├── Database.php                 ← DB credentials + pConnect, strictOn=false
│   │   ├── Routes.php                   ← All route definitions
│   │   ├── Filters.php                  ← AdminAuth filter for /admin/* routes
│   │   └── Logger.php                   ← threshold = 4 (errors only)
│   │
│   ├── Controllers/
│   │   ├── BaseController.php           ← CI4 default (keep minimal)
│   │   ├── RegistrationController.php   ← Handles GET /register, POST /register/submit
│   │   └── Admin/
│   │       ├── AuthController.php       ← GET/POST /admin/login, /admin/logout
│   │       └── AllotmentController.php  ← GET /admin/allotment (dashboard)
│   │
│   ├── Filters/
│   │   └── AdminAuthFilter.php          ← Session-based guard for /admin/* routes only
│   │
│   ├── Models/
│   │   └── AllotmentModel.php           ← Read queries for clerk dashboard ONLY
│   │                                       (No Model used on registration POST path)
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── student_layout.php       ← Minimal HTML shell + Tailwind CDN
│   │   │   └── admin_layout.php         ← Admin HTML shell + Tailwind CDN
│   │   │
│   │   ├── registration/
│   │   │   ├── form.php                 ← Student registration form (Tailwind)
│   │   │   └── confirmation.php         ← Inline success confirmation slip
│   │   │
│   │   ├── admin/
│   │   │   ├── login.php                ← Clerk login form
│   │   │   └── allotment.php            ← Clerk dashboard table + filters
│   │   │
│   │   └── errors/
│   │       └── error_404.php            ← Lightweight 404 page
│   │
│   └── Database/
│       └── Migrations/
│           └── 2026-08-08-000001_CreateSpotRegistrations.php
│
├── public/                              ← Document root (point cPanel here)
│   ├── index.php                        ← CI4 front controller
│   └── .htaccess                        ← URL rewriting + PHP ini overrides
│
├── writable/
│   ├── logs/                            ← CI4 error logs (errors only)
│   ├── cache/                           ← Disabled / empty
│   └── session/                         ← Used ONLY for admin clerk sessions
│
├── .env                                 ← Environment variables (DB creds, CI_ENVIRONMENT)
├── .htaccess                            ← Root-level redirect to public/
├── PLAN.md                              ← This file
└── spark                                ← CI4 CLI entry point
```

### Route Definitions (`app/Config/Routes.php`)

```php
// ── Student Routes (Stateless — NO session filter applied) ──
$routes->get('register',         'RegistrationController::index');
$routes->post('register/submit', 'RegistrationController::submit');

// ── Admin Routes (Session-protected via AdminAuthFilter) ──
$routes->group('admin', ['filter' => 'adminauth'], function($routes) {
    $routes->get('allotment',        'Admin\AllotmentController::index');
    $routes->get('allotment/export', 'Admin\AllotmentController::export');
});

// Auth (no filter — public)
$routes->get('admin/login',  'Admin\AuthController::index');
$routes->post('admin/login', 'Admin\AuthController::authenticate');
$routes->get('admin/logout', 'Admin\AuthController::logout');

// Root redirect
$routes->get('/', function() { return redirect()->to('/register'); });
```

### Filter Configuration (`app/Config/Filters.php`)

```php
// AdminAuthFilter is ONLY applied to /admin/* routes
// Student routes intentionally have NO filters to minimize middleware overhead
public $filters = [
    'adminauth' => ['before' => ['admin/*']],
];
```

---

## 5. Step-by-Step Development Roadmap

### Phase 0 — Environment Setup *(~30 min)*

- [ ] **0.1** Install/verify CodeIgniter 4 via Composer in `admission_mace/`
- [ ] **0.2** Configure `.env`: set `CI_ENVIRONMENT=production`, DB credentials, `app.baseURL`
- [ ] **0.3** Configure cPanel Document Root to point at `admission_mace/public/`
- [ ] **0.4** Set `public/.htaccess` with PHP ini overrides:
  ```apache
  php_value memory_limit 64M
  php_value max_execution_time 15
  php_value session.auto_start 0
  php_flag output_buffering Off
  ```
- [ ] **0.5** Disable CI4 debug toolbar in `app/Config/Toolbar.php` → `$collectors = []`
- [ ] **0.6** Set logger threshold to errors-only in `app/Config/Logger.php` → `'threshold' => 4`

---

### Phase 1 — Database Foundation *(~20 min)*

- [ ] **1.1** Create database `mace_admission` in cPanel MySQL
- [ ] **1.2** Write and run the migration: `php spark migrate`
  - Creates `spot_registrations` table with all columns and indexes (see §2)
- [ ] **1.3** Verify indexes in cPanel phpMyAdmin (`SHOW INDEX FROM spot_registrations`)
- [ ] **1.4** Configure `app/Config/Database.php`:
  ```php
  'pConnect'   => true,     // persistent connections
  'strictOn'   => false,    // skip strict mode overhead
  'DBCollat'   => 'utf8mb4_unicode_ci',
  'compress'   => false,
  ```

---

### Phase 2 — Student Registration Module *(~2 hrs)*

- [ ] **2.1** Create `RegistrationController.php`
  - `index()` → loads `registration/form.php` view (no session, no auth)
  - `submit()` → validates POST, bare-metal INSERT, renders `registration/confirmation.php`
- [ ] **2.2** Build `registration/form.php` view
  - Tailwind CDN in `<head>`
  - All form fields per functional spec
  - JS toggle for "Admitted Elsewhere" conditional fields
  - JS validation for mobile (10 digits), rank (integer), roll no (required)
  - 7 branch option dropdowns with duplicate-prevention JS
- [ ] **2.3** Build `registration/confirmation.php` view
  - Printable confirmation slip with student name, roll no, rank, registered options
  - "Print this page" button (JS `window.print()`)
- [ ] **2.4** Implement server-side validation in `submit()`
  - Use `Services::validation()` — do NOT load `\CodeIgniter\Model` on this path
  - Rules: required fields, mobile regex, integer rank, unique roll no (DB check)
  - On failure: re-render form with inline error messages (no flash/session)
- [ ] **2.5** Bare-metal INSERT:
  ```php
  $db = \Config\Database::connect();
  $db->table('spot_registrations')->insert($data);
  ```
- [ ] **2.6** Test under simulated load (Apache Bench):
  ```bash
  ab -n 500 -c 50 -p post_data.txt -T application/x-www-form-urlencoded \
     http://yourserver.com/register/submit
  ```

---

### Phase 3 — Admin Authentication *(~1 hr)*

- [ ] **3.1** Create `admin_users` table (seeded at deploy with bcrypt hashed passwords):
  ```sql
  CREATE TABLE admin_users (
      id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
      username VARCHAR(50)  NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      PRIMARY KEY (id)
  ) ENGINE=InnoDB;
  ```
- [ ] **3.2** Create `Admin\AuthController.php`
  - `index()` → login form
  - `authenticate()` → `password_verify()`, starts CI4 session (admin only), redirect to dashboard
  - `logout()` → destroys session, redirect to login
- [ ] **3.3** Create `AdminAuthFilter.php`
  - Checks `session()->get('admin_logged_in')` — if falsy, redirect to `/admin/login`
  - **Sessions are ONLY initiated here** — never on student routes
- [ ] **3.4** Register filter in `app/Config/Filters.php`
- [ ] **3.5** Build `admin/login.php` view (Tailwind, clean login card)

---

### Phase 4 — Clerk Dashboard *(~2 hrs)*

- [ ] **4.1** Create `AllotmentModel.php`
  - `getByCategory(string $category, int $limit, int $offset): array`
  - `getAll(int $limit, int $offset): array` (SM filter)
  - `searchByRollNo(string $rollNo): array`
  - `searchByRank(int $rank): array`
- [ ] **4.2** Create `Admin\AllotmentController.php`
  - `index()` → reads `?category=SM|EZ|...`, `?search=`, `?page=`
  - Returns paginated results via `AllotmentModel`
- [ ] **4.3** Build `admin/allotment.php` view
  - Category filter tabs/buttons (all 17 categories)
  - Search box (Roll No / Rank) with GET form
  - Applicant table: Rank | Roll No | Name | Mobile | Category | Report Time | Options 1–7
  - Pagination links
  - Print-friendly CSS (`@media print` — hide nav/filters, show table only)
- [ ] **4.4** Add "Export to CSV" action:
  - `export()` method streams CSV with `fputcsv()` — avoids buffering full result set in memory
  - Use `LIMIT 5000` with chunked output if needed

---

### Phase 5 — Hardening & Deployment *(~1 hr)*

- [ ] **5.1** Set `.env` → `CI_ENVIRONMENT = production` (disables debug output)
- [ ] **5.2** Exempt student registration from CSRF to avoid session cookie overhead:
  ```php
  // app/Config/Filters.php
  public $globals = [
      'before' => [
          'csrf' => ['except' => ['register/submit']],
      ],
  ];
  ```
- [ ] **5.3** Review `writable/` permissions: `755` for directory, logs writable by web user
- [ ] **5.4** Final end-to-end test checklist:
  - Student registration → confirmation slip renders ✓
  - Duplicate roll no → inline error without crash ✓
  - Admin login → session starts → dashboard loads with SM filter ✓
  - Category filter → correct subset ordered by rank ✓
  - Search by roll no and rank → correct results ✓
  - Print layout → clean table output ✓
  - CSV export → downloads correctly ✓

---

## Summary Checklist

| # | Deliverable | Status |
|---|---|---|
| 1 | `PLAN.md` (this file) | ✅ Done |
| 2 | CI4 project scaffolding + `.env` | ⬜ |
| 3 | Database migration (`spot_registrations`) | ⬜ |
| 4 | Student registration form & controller | ⬜ |
| 5 | Confirmation slip view | ⬜ |
| 6 | Admin login + session filter | ⬜ |
| 7 | Clerk allotment dashboard | ⬜ |
| 8 | CSV export | ⬜ |
| 9 | Load test & hardening | ⬜ |

---

*This plan is the single source of truth for the MACE Spot Admission Portal. Update status checkboxes as phases are completed.*

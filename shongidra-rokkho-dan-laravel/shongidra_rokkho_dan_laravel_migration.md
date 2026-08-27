# 🩸 Manab Kalyane Rokto Dan — Laravel 12 Migration Guide & Multi-Database Setup

This guide documents the complete migration of the **Manab Kalyane Rokto Dan** voluntary blood donation platform to **Laravel 12**.

---

## 🌟 Overview & Stack

- **Framework:** Laravel 12 (PHP 8.2+)
- **Frontend / UI:** Tailwind CSS (Rich Dark Mode, Glassmorphism, Responsive Grid) + Alpine.js
- **Database Engine Support:** Flexible runtime selection between **SQLite**, **MySQL/MariaDB**, or **Supabase (PostgreSQL)**
- **Authentication & Security:** Custom Session Guard, Role-Based Access Control (Admin, Member, Donor), and Visitor Inquiry Privacy Gate

---

## 🗄️ Multi-Database Configuration Options

The application is engineered with a modular database configuration in `config/database.php` allowing instant switching via `.env`.

### Option 1: SQLite (Default Local / Portable Setup)
No database server setup required. Uses local database file `database/database.sqlite`.

In `.env`:
```ini
DB_CONNECTION=sqlite
```

---

### Option 2: MySQL / MariaDB (XAMPP / WAMP / Local Server / cPanel)
For standard MySQL or MariaDB hosting environments.

In `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manob_kalyane_roktodan
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Run migration command after configuring `.env`:
```bash
php artisan migrate:fresh --seed
```

---

### Option 3: Supabase (PostgreSQL Direct / Connection Pooler)
For cloud hosting connected to your Supabase PostgreSQL instance.

In `.env`:
```ini
DB_CONNECTION=supabase
SUPABASE_DB_HOST=aws-0-asia-south-1.pooler.supabase.com
SUPABASE_DB_PORT=5432
SUPABASE_DB_DATABASE=postgres
SUPABASE_DB_USERNAME=postgres.YOUR_PROJECT_REF
SUPABASE_DB_PASSWORD=YOUR_SUPABASE_PASSWORD
SUPABASE_DB_SSLMODE=require
```

Run migration command:
```bash
php artisan migrate:fresh --seed
```

---

## 🚀 Application Structure & Features

| Feature Module | Controller | Blade View | Description |
| :--- | :--- | :--- | :--- |
| **Homepage** | `HomeController` | `resources/views/home.blade.php` | Dynamic Hero Slideshow Carousel, Live Counter Stats, Quick Blood Search Bar, Live Emergency Ticker, Board Committee Showcase. |
| **Donor Search** | `DonorController` | `resources/views/donors/search.blade.php` | Search active donors by Blood Group (`A+`, `O+`, `B+`, etc.), District, Block (`Bhagwangola-I`, `Bhagwangola-II`, `Lalgola`), Availability. |
| **Privacy Gate** | `DonorController@submitInquiry` | Modal in `donors/search.blade.php` | Logs visitor name, phone number, and IP address before unlocking donor phone contact numbers. |
| **Emergency Requests** | `RequestController` | `resources/views/requests/index.blade.php` | Patients can post urgent blood requirements. Features one-tap call & WhatsApp broadcast share links. |
| **Donation / Support** | `PledgeController` | `resources/views/donate.blade.php` | Financial support page with dynamic UPI QR code generator (`manobkalyan@upi`), quick amount selectors, & pledge logger. |
| **Committee Board** | `MemberController` | `resources/views/members.blade.php` | Board member cards with phone contact details and roles. |
| **Photo Gallery** | `GalleryController` | `resources/views/gallery.blade.php` | Blood donation camp photo gallery with category filter tabs and Lightbox modal previews. |
| **Donor Dashboard** | `DashboardController` | `resources/views/dashboard/index.blade.php` | Calculates 90-day donation eligibility countdown, logs past donation history, and links to Digital Donor Card. |
| **Digital Donor ID Card** | `DashboardController@card` | `resources/views/dashboard/card.blade.php` | Printable Digital Donor ID Badge with dynamic QR code & blood group badge. |
| **Certificate Generator** | `DashboardController@showCertificate` | `resources/views/dashboard/certificate.blade.php` | Printable Certificate of Appreciation with Certificate ID (`CERT-XXXXXX`) & official seal. |
| **Admin Control Panel** | `AdminController` | `resources/views/admin/index.blade.php` | Master control panel for moderating emergency requests, updating user roles (Admin/Member/Donor), issuing donation certificates, managing hero slides, gallery images, and site helpline settings. |

---

## 🔑 Default Credentials (After Seeding)

- **Admin Login:**
  - **Email:** `admin@manobkalyane.org`
  - **Password:** `admin123`

- **Demo Donor Logins:**
  - **Emails:** `rahim@gmail.com`, `subhashish@gmail.com`, `aslam@gmail.com`
  - **Password:** `password`

---

## 🛠️ How to Run Locally

1. Open terminal inside `shongidra-rokkho-dan-laravel`:
   ```bash
   cd f:\antigravityprojects\manob-kalyane-roktodan\shongidra-rokkho-dan-laravel
   ```
2. Run database migration and seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```
3. Start local development server:
   ```bash
   php artisan serve
   ```
4. Open `http://127.0.0.1:8000` in your web browser.

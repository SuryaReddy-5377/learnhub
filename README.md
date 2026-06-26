 A full-stack E-Learning Portal built as a **Capstone Project (Task 5 — Days 49–60)** for ApexPlanet Software Pvt. Ltd. internship program. Features user authentication with Email OTP, course enrollment, lesson tracking, AJAX-powered search, and an admin analytics dashboard.

---

## 🌐 Live Demo

🔗 **[View Live Site](http://learnhub-surya.rf.gd/)** 
📁 **[GitHub Repository](https://github.com/SuryaReddy-5377/learnhub.git)** 
---

## ✨ Features

### 👤 User Side
- **Register & Login** with secure password hashing
- **Email OTP Verification** on signup (via PHPMailer / SMTP)
- **Forgot Password** → OTP → Reset flow
- **Browse Courses** with real-time AJAX search & filtering
- **Enroll in Courses** and track progress
- **Lesson Viewer** with structured content
- **Profile Management** with profile picture upload
- **My Courses** dashboard to view enrolled courses

### 🛠️ Admin Panel
- **User Management** — view and manage all registered users
- **Course Management** — add, edit, and delete courses
- **Analytics Dashboard** — visual charts (Chart.js) for enrollments and user growth
- **Settings** — configure site options

### ⚡ Technical Highlights
- AJAX-powered real-time search (no page reload)
- OTP verification via AJAX (`verify-otp-ajax.php`)
- Responsive UI with custom CSS
- Secure file uploads for course thumbnails and profile pictures
- Modular code with reusable `includes/` (header, footer, functions)

---

## 🗂️ Project Structure

```
learnhub/
├── admin/                  # Admin panel pages
│   ├── add-course.php
│   ├── analytics.php       # Chart.js analytics dashboard
│   ├── courses.php
│   ├── users.php
│   └── index.php
├── assets/
│   ├── css/style.css       # Main stylesheet
│   ├── js/script.js        # AJAX & frontend logic
│   └── uploads/            # Course images & profile pictures
├── config/
│   └── database.php        # DB connection
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── functions.php       # Reusable utility functions
│   └── send-email.php      # PHPMailer email config
├── vendor/phpmailer/       # PHPMailer library
├── index.php               # Home page
├── register.php            # User registration
├── login.php               # User login
├── verify-otp.php          # OTP verification page
├── verify-otp-ajax.php     # AJAX OTP handler
├── forgot-password.php     # Forgot password flow
├── reset-password.php      # Reset password
├── dashboard.php           # User dashboard
├── courses.php             # Course listing
├── course-details.php      # Single course page
├── enroll.php              # Enrollment handler
├── lesson.php              # Lesson viewer
├── my-courses.php          # User's enrolled courses
├── profile.php             # User profile
├── edit-profile.php        # Edit profile form
├── upload-profile.php      # Profile picture upload handler
├── logout.php              # Session destroy & logout
└── database.sql            # Full database schema & seed data
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.x |
| Database | MySQL 8.x |
| Frontend | HTML5, CSS3, JavaScript (ES6) |
| AJAX | Vanilla JS + Fetch API / XMLHttpRequest |
| Charts | Chart.js |
| Email | PHPMailer (SMTP) |
| Hosting | InfinityFree |
| Version Control | Git & GitHub |

---

## ⚙️ Installation & Setup (Local)

### Prerequisites
- XAMPP / WAMP / Laragon (PHP + MySQL)
- Composer *(optional, PHPMailer included in `/vendor`)*

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/SuryaReddy-5377/learnhub.git
   cd learnhub
   ```

2. **Import the database**
   - Open **phpMyAdmin**
   - Create a new database: `learnhub`
   - Import `database.sql`

3. **Configure database connection**
   - Open `config/database.php`
   - Update with your local credentials:
   ```php
   $host = 'localhost';
   $dbname = 'learnhub';
   $username = 'root';
   $password = '';
   ```

4. **Configure email (OTP)**
   - Open `includes/send-email.php`
   - Update SMTP settings:
   ```php
   $mail->Host       = 'smtp.gmail.com';
   $mail->Username   = 'your-email@gmail.com';
   $mail->Password   = 'your-app-password';
   ```

5. **Run the project**
   - Place the project folder in `htdocs/` (XAMPP) or `www/` (WAMP)
   - Visit: `http://learnhub-surya.rf.gd/`

6. **Create Admin Account**
   - Visit: 'http://learnhub-surya.rf.gd/create-admin.php`
   - Run once, then delete the file for security

---

## 🎓 LearnHub — E-Learning Portal

**Capstone Project | Task 5 | Days 49–60**  
ApexPlanet Software Pvt. Ltd. Internship Program

---

## 🗃️ Database Schema

Key tables:

| Table | Description |
|---|---|
| `users` | Registered users with roles |
| `courses` | Course details and thumbnails |
| `lessons` | Lesson content per course |
| `enrollments` | User–course enrollment records |
| `otp_tokens` | OTP codes with expiry for verification |

Full schema available in [`database.sql`](./database.sql)

---

## 🚀 Deployment (InfinityFree)

1. Create a free account at [InfinityFree](https://infinityfree.net)
2. Create a subdomain and note the FTP credentials
3. Upload all project files via **FileZilla** (FTP)
4. Import `database.sql` via **Softaculous phpMyAdmin**
5. Update `config/database.php` with InfinityFree DB credentials
6. Visit your live subdomain URL

---

## 📦 Deliverables

- [x] Live-deployed website (InfinityFree)
- [x] GitHub repository with full source code
- [x] Project Report (PDF) — Features, DB Schema, Screenshots
- [x] 12-minute demo video for LinkedIn portfolio

---

## 👨‍💻 Author

**Your Name**  
🔗 [LinkedIn]https://www.linkedin.com/in/surya-manohar-reddy-goluguri-110299366/ | 🐙 [GitHub]https://github.com/SuryaReddy-5377
---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

> Built with ❤️ as part of the **ApexPlanet Software Pvt. Ltd.** Internship — Task 5 (Days 49–60)

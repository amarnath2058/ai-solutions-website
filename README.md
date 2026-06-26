# AI Solutions — Full-Stack Website with Admin Panel

> A complete AI solutions business website with customer inquiry management, email automation, analytics, and a full admin dashboard. Built with PHP, MySQL, HTML, CSS, and JavaScript.

---

## 📌 Features

### Frontend (Public Website)
- ✅ Homepage with hero section, features, and statistics
- ✅ Solutions page with AI services showcase
- ✅ Insights page with case studies and blog posts
- ✅ Testimonials page with client reviews and pagination
- ✅ Gallery page with company events and culture images
- ✅ Contact page with working form and database storage
- ✅ Interactive AI chatbot with quick replies

### Admin Panel
- ✅ Secure admin authentication (session-based)
- ✅ Dashboard with statistics overview
- ✅ Inquiry management (view, search, filter, delete)
- ✅ Email reply system using PHPMailer (SMTP)
- ✅ Analytics dashboard with Chart.js
- ✅ Account management (add/delete admin users)
- ✅ Export inquiries to CSV

---

## 🛠️ Tech Stack

| Category | Technologies |
|----------|--------------|
| **Backend** | PHP 8.2+ with PDO |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Libraries** | PHPMailer, Chart.js, Font Awesome |
| **Server** | Apache / XAMPP |
| **Version Control** | Git + GitHub |

---

## 🗄️ Database Schema

### `admins` – Admin accounts
| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK) | Auto-increment |
| username | VARCHAR(50) | Unique login username |
| password | VARCHAR(255) | Hashed password |
| full_name | VARCHAR(100) | Admin's full name |
| email | VARCHAR(100) | Admin email address |
| created_at | TIMESTAMP | Account creation date |

### `inquiries` – Customer inquiries
| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK) | Auto-increment |
| full_name | VARCHAR(100) | Customer's full name |
| email | VARCHAR(100) | Customer's email |
| phone | VARCHAR(20) | Customer's phone number |
| company | VARCHAR(100) | Company name (optional) |
| country | VARCHAR(100) | Customer's country |
| job_title | VARCHAR(100) | Job title (optional) |
| job_details | TEXT | Detailed inquiry message |
| status | ENUM('pending','replied') | Inquiry status |
| created_at | TIMESTAMP | Submission date |

---

## ⚙️ Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/ai-solutions-website.git
cd ai-solutions-website

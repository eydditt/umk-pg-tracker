# UMK Postgraduate Management System

A comprehensive, database-driven web application developed for Universiti Malaysia Kelantan (UMK) to streamline and manage postgraduate student data, applications, and academic tracking.

---

## 🛠 Tech Stack
* **Framework:** Laravel (PHP)
* **Database:** MySQL
* **Frontend:** HTML, CSS, JavaScript, Bootstrap
* **Infrastructure:** Docker 

---

## 🚀 Key Features
* **Applicant Tracking:** Automated status updates and data management for incoming postgraduate applications.
* **Data Visualization:** Translates complex student demographic and academic data into accessible graphical representations.
* **Secure Admin Dashboard:** Centralized management system with role-based access control.
* **Containerization:** Fully containerized using Docker for consistent cross-environment deployment.

---

## ⚙️ How to Run Locally

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/eydditt/umk-pg-tracker.git](https://github.com/eydditt/umk-pg-tracker.git)
   ```

2. **Navigate to the directory:**
   ```bash
   cd umk-pg-tracker
   ```

3. **Install dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

4. **Set up the environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run the application via Docker:**
   ```bash
   docker-compose up -d
   ```

---

> **Note:** This repository utilizes dummy data to protect university privacy and comply with data protection standards.

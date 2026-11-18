# 🏛️ Hadiya Zone Bureau of Women and Children Affairs Portal

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-PDO-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-4.6%20%7C%205-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-3498DB?style=for-the-badge)](LICENSE)

A robust, bilingual (English & Amharic) web-based portal and Content Management System (CMS) engineered for regional administrative bodies. The platform streamlines community engagement, publishes institutional news, manages organizational galleries, tracks office headquarters details, and provides secure role-based administrative controls.

---

## 📋 Table of Contents

1. [Key Features](#-key-features)
2. [Technology Stack](#-technology-stack)
3. [Project Directory Structure](#-project-directory-structure)
4. [Database Schema Overview](#-database-schema-overview)
5. [Installation & Local Setup](#-installation--local-setup)
6. [Security & Localization](#-security--localization)
7. [License](#-license)

---

## ✨ Key Features

* **Bilingual Localization (EN / አማ):** Dynamic language switcher storing user preferences via PHP sessions and browser cookies.
* **Dynamic Hero Slider:** Fully customizable hero background carousel managed via the database[cite: 9].
* **Organizational Gallery & Heroes Showcase:** Responsive grid layouts showcasing administrative personnel, sector leaders, and public highlights[cite: 9].
* **News & Operational Updates:** Multi-language news publishing module with timestamp tracking[cite: 9].
* **Public Contact & HQ Management:** Integrated inquiry form saving submissions directly to the database alongside dynamic office headquarters details[cite: 9].
* **Audit Logging:** Administrative action tracking system recording user login sessions, content updates, and configuration modifications[cite: 12].

---

## 🛠️ Technology Stack

* **Backend:** PHP (Native modular architecture, PHP Data Objects (`PDO`) for secure database transactions).
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3 (Custom animations, glassmorphism UI elements, responsive grid systems), Vanilla JavaScript[cite: 9, 11].
* **UI Frameworks:** Bootstrap 4.6.2, Font Awesome 6.4.2[cite: 9]

---

## 📁 Project Directory Structure

```text
hadiya-zone-women-children-affairs-portal/
├── admin/                  # Administrative management backend (dashboards, uploads)
├── Photos/                 # Uploaded media assets, slider banners, and gallery images
├── config/                 # Core configuration scripts
├── db.php                  # PDO database connection handler[cite: 8]
├── database.sql            # Initial database schema and default seed data
├── w_c_issues.sql          # Complete operational database dump & logs[cite: 12]
├── index.php               # Main public portal frontend view[cite: 9]
├── style.css               # Custom stylesheet for typography, sliders, and navigation[cite: 11]
└── README.md               # Project documentation

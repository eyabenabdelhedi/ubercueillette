# 🌾 UberCueillette

A web platform that connects **farmers** and **seasonal workers** for harvest work (olives, citrus, tomatoes and other fruit) in Tunisia. Inspired by the Uber model: farmers publish harvest offers, workers browse and apply, and the whole cycle is tracked from application to post-harvest rating.

> Team project built for the Web course at **ISG Tunis** (2025/2026), Business Information Systems.

## ✨ Features

**Farmers**
- Sign up and manage their profile
- Publish harvest offers: fruit type, governorate, address, start/end dates, application deadline, number of workers and daily pay (DT)
- Create new fruit types or governorates directly from the form
- Review applications per offer, then accept or refuse (blocked once the quota is reached)
- Rate workers and record their pay after the harvest

**Workers**
- Sign up with a profile photo and manage their profile
- Browse available offers with filters (fruit, governorate, price)
- Apply to an offer and follow the status of every application
- See ratings, comments and earnings per mission

**General**
- Role-based login (farmer / worker) with PHP sessions
- 24 Tunisian governorates pre-loaded
- Business rules on dates: acceptance before the deadline, rating after the end date
- Duplicate applications blocked by both the PHP code and a `UNIQUE` database key

## 🛠️ Tech stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 |
| Database | MySQL / MariaDB |
| Database access | PDO with prepared statements |
| Frontend | HTML5, CSS3, JavaScript |
| Local server | Apache (XAMPP) |

## 🗄️ Database

Database `uber_cueillette` with 6 tables linked by foreign keys (with `CASCADE` rules), indexes on frequent join columns and `UNIQUE` constraints:

`agriculteur` · `ouvrier` · `offre` · `candidature` · `gouvernorat` · `type_fruit`

The SQL file in `database/` contains the structure and the reference data only (governorates and fruit types). No user accounts are included, so register your own test users.

## 🔐 Security and validation

- PDO prepared statements against SQL injection
- Passwords hashed with bcrypt (`password_hash`)
- Output escaped with `htmlspecialchars`
- Validation in both PHP and JavaScript:
  - CIN: exactly 8 digits
  - Username: letters only
  - Password: at least 8 characters (letters and digits), ending with `$` or `#`
  - Dates: end date after start date, deadline before start date

## 🚀 Run it locally

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this folder into `htdocs/` (for example `C:\xampp\htdocs\ubercueillette`).
3. Open phpMyAdmin (`http://localhost/phpmyadmin`), create a database named `uber_cueillette`, then import `database/uber_cueillette.sql`.
4. Check `config/db.php`. The default XAMPP settings are used (`root`, no password). Change them if your setup is different.
5. Open `http://localhost/ubercueillette/` and create a farmer account and a worker account to try the full flow.

## 📁 Project structure

```
ubercueillette/
├── index.php            Home page
├── connexion.php        Login (role selector)
├── agriculteur/         Farmer pages: offers, applications, rating, profile
├── ouvrier/             Worker pages: applications, profile, ratings and earnings
├── offre/               Public list of available offers
├── config/              Database connection and navbar
├── css/                 Stylesheet
├── java/                JavaScript validation
├── database/            SQL structure and reference data
└── docs/                Project report (French)
```

## 🔭 Possible improvements

- Add CSRF tokens to the POST forms
- Add a password reset flow by email
- Harmonize the `decision` values between the database (`acceptation`, `refus`, `en cours`) and the PHP code

## 👩‍💻 Team

- Ghofrane Araar
- Eya Ibn Abdelhedi
- Amira Midani

The full project report (in French) is in [`docs/rapport-ubercueillette.pdf`](docs/rapport-ubercueillette.pdf).

<!--
Add screenshots: run the app, take screenshots, put them in a `screenshots/` folder,
then uncomment this block.

## 📸 Screenshots
![Home](screenshots/home.png)
![Farmer dashboard](screenshots/farmer-dashboard.png)
![Offers](screenshots/offers.png)
-->

# COVID-19 Testing Management System - Vulnerable Lab

Welcome to the COVID-19 Testing Management System Lab, developed by Global Health Corp.

## Purpose
This lab is designed for students to practice identifying and exploiting **SQL Injection** and **Prompt Injection** vulnerabilities in a real-world-like web application.

⚠️ **Important:**  
- The application contains hidden SQL Injection vulnerabilities that are NOT explicitly pointed out. Students must investigate and find them themselves.
- It also includes a vulnerable chatbot that is susceptible to Prompt Injection attacks.

## Features
- COVID-19 information page
- COVID-19 symptoms listing
- Testing registration system
- Live test result updates
- Chatbot for COVID-19 consultation (**vulnerable to Prompt Injection**)
- Admin login portal (**vulnerable to SQL Injection**)

## Instructions

1. Install a local web server (e.g., XAMPP, WAMP, Laragon).
2. Clone or copy this lab folder into your web server's `htdocs` directory.
3. Start Apache and MySQL services.
4. Import the provided `covid19_lab.sql` database into your MySQL server.
5. Access the system via `http://localhost/[your-folder-name]/index.html`.

## Challenge Objectives
- Find and exploit the **SQL Injection** vulnerability.
- Find and exploit the **Prompt Injection** vulnerability.
- Retrieve hidden sensitive information (flag).

## Hints
- Carefully observe **login forms**, **chatbot prompts**, and **GET/POST** requests.
- Not all vulnerabilities are visible at first glance!

---

© 2025 Global Health Corp. All rights reserved.
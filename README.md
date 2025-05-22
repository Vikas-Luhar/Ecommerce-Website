# Ecommerce Website 🛒

This is a full-stack Ecommerce Website project developed using **PHP**, **MySQL**, **HTML**, **CSS**, **JavaScript**, and **Bootstrap**. It includes user registration, login, product listing, cart system, and admin/seller panels.

---

## 📁 Project Structure

Ecommerce-Website/
│
├── Mini_Project/
│ ├── assets/ # Images, CSS, JS files
│ ├── admin/ # Admin panel files
│ ├── seller_panel/ # Seller panel files
│ ├── User/ # User interface
│ ├── connection.php # DB connection file
│ └── ... (all PHP pages)
│
├── swiss_collection.sql # ✅ MySQL database file
├── README.md # Project documentation
└── .gitattributes
---

## 🛠️ Features

- 🔐 User & Admin login
- 👤 User registration
- 🛍️ Product categories & details
- 🛒 Shopping cart
- 📦 Seller dashboard to manage orders
- 📊 Admin panel for managing users, sellers & reports

---

## 🧑‍💻 How to Run the Project

### 1. Clone the repository

git clone https://github.com/Vikas-Luhar/Ecommerce-Website.git

### 2. Setup XAMPP (or any PHP server)

Make sure **XAMPP** is installed on your system:  
👉 https://www.apachefriends.org/index.html

- Start **Apache** and **MySQL** from the XAMPP Control Panel.

### 3. Move project to `htdocs`

Copy the `Mini_Project` folder into:
C:/xampp/htdocs/

---

### 4. Import the Database

1. Go to `http://localhost/phpmyadmin`
2. Click **Import**
3. Select the file: `swiss_collection.sql`
4. Click **Go**

This will create the `swiss_collection` database.

---

### 5. Check Database Connection

Open `Mini_Project/connection.php` and confirm:

```php
$server = "localhost";
$user = "root";
$password = "";
$database = "swiss_collection";
🚀 Panel Access URLs
After setup, open these pages in your browser:

Panel	URL
🧍‍♂️ User Panel	http://localhost/Mini_Project/User/user.php
🛒 Seller Panel	http://localhost/Mini_Project/seller_panel/pages/sign-in.php
🛠️ Admin Panel	http://localhost/Mini_Project/admin/pages/dashboard.php


👨‍💻 Author
Vikas Luhar
BCA Student – C.K. Pithawalla College
GitHub: https://github.com/Vikas-Luhar

📄 License
This project is for educational purposes only.

---

✅ Now just go to your repository on GitHub → open the existing `README.md` → click the **pencil icon (✏️ Edit)** → paste the above content → scroll down and **commit changes**.

Let me know if you'd like a `.md` file version or want help uploading the SQL file as well!

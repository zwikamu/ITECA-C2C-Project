
# SHOPINK: READ ME

## Overview

ShopInk is a user-friendly PHP-based e-commerce platform that enables users to register, browse products, apply as sellers, and manage purchases securely. The platform includes admin tools, seller onboarding, a featured carousel, product categories, and an integrated modal-based login/register system.

## Installation

To set up and run the ShopInk project locally using XAMPP:  
  
1. Clone or download the repository into your “htdocs” folder.  
2. Start Apache and MySQL via the XAMPP Control Panel.  
3. Create a MySQL database (e.g. “shopink_db”).  
4. Import the SQL file provided in the “/database/” folder using phpMyAdmin.  
5. Configure database connection in “database/db.php” with your credentials.  
6. Visit “http://localhost/ShopInk/” in your browser.  
  
Note: Ensure “/uploads/” directory has write permissions for file uploads.

## Features:

User registration and login (modal-based)

Seller application and onboarding

Seller dashboard:

Add, edit, and delete products

Low-stock indicators

Financial summaries (total earned, platform fees)

View all orders related to seller’s products

Admin panel:

View users, orders, products, and financial data

Approve/reject seller applications

Buyer:

Add items to cart

Browse dynamic carousel (new, top-selling, discounted)

Product details with stock and pricing

## Security Features:

Passwords are hashed using `password_hash()` for secure storage.

Sessions are securely handled and validated for user authentication.

## Technologies Used:

PHP 8.2

MySQL (XAMPP for local testing)

HTML5, CSS3

Bootstrap 5

JavaScript

phpMyAdmin for database management

How to the application locally

1. Install [XAMPP](https://www.apachefriends.org/index.html)

2. Clone or Extract this folder to your /htdocs/ directory in XAMPP:

3. Start Apache & MySQL via XAMPP Control Panel

4. Import the Database:

Open http://localhost/phpmyadmin

Create a new database called shopink

Import the file shopink.sql

5. Run the Site:

Visit: http://localhost/ShopInk/index.php

## Demo Login Credentials

 - Buyer Account

Email: test@test.com

Password: test

 - Seller Account

Email: test2@test.com

Password: test2

 - Admin Account

Admin: admin@example.com

Password: adminpass

## Test Data Preloaded

- Sample users, products, orders, and financials have been inserted for testing.

- All data is connected and reflects real interactions between buyers and sellers.

## Limitations & Improvements

- Basic styling can be enhanced further.  
- Some validations are frontend-only.  
- No password reset or email verification implemented yet.  
- Admin panel can be expanded with more analytics.

- Checkout payment gateway not yet implemented

- Buyers cannot track order statuses from their profile (only sellers/admins)

- No email notifications on order placements or status changes

## Project Structure

ShopInk/  
│  
├── index.php # Homepage with modal login/register  
├── dashboard.php # Seller Dashboard  
├── seller_products.php # Manage seller products  
├── seller_orders.php # View seller-specific orders  
├── seller_financials.php # Seller financial breakdown  
├── apply_seller.php # Seller application form  
├── view_applications.php # Admin view of applications  
├── logout.php # Destroys session and redirects  
│  
├── /styling/  
│ └── styles.css # Custom styles  
│  
├── /uploads/ # Seller application documents  
├── /database/  
│ └── db.php # PDO connection  
└── shopink.sql # Full synced database export  


## Database Schema Overview

Key Tables:  
  
| Table  | Description  |  
|----------------------|------------------------------------|  
| users  | Registered users and their roles  |  
| products  | Items listed by sellers  |  
| orders  | Purchase records  |  
| order_items  | Items per order  |  
| seller_applications  | Onboarding data for sellers  |  
| financials  | Platform revenue, fees, payouts  |

## Developer

ZB Banda
EDUV4777744
Software Engineering Student
Mowbrary Campus
Cape Town, South Africa  


# Shop-a-Lot - an E-Commerce platform

https://github.com/adamhb710/cosc213-finalproject

## Team Members

- [Gian Tindogan]
- [Adam Hobson]
- [Stefan Wells]

## Project Description

A full-stack e-commerce web application built with the LAMP stack featuring user authentication, product management,
shopping cart functionality, and an admin panel.

## Technologies Used

- **FrontEnd:** HTML5, CSS
- **BackEnd:** PHP 8.4.3
- **DataBase:** MySQL
- **Server:** Apache (XAMPP)
- **Version Control:** Git

## Features Implemented

- Product catalog with images and prices
- Individual product detail pages
- Secure user registration and login (utilizing password hashing)
- Shopping cart functionality
- Admin panel with CRUD operations
- Multi-step checkout simulation

## Advanced Features

- Product categories with filtering
- Inventory management with stock tracking

## Installation Requirements

### Prerequisites

- XAMPP (or similar LAMP stack)
- Git
- Web Browser

### Setup Instructions

1. **Clone the repository**
   ```bash
      git clone https://github.com/[your-username]/cosc213-finalproject.git
      cd cosc213-finalproject
   ```
2. **Move to XAMPP directory**

    ```bash
       # Copy project to XAMPP htdocs
       cp -r cosc213-finalproject C:/xampp/htdocs/
    ```

3. **Start XAMPP**
    - Start Apache
    - Start MySQL


4. **Import Database**
    ```bash
       # Open phpMyAdmin (http://localhost/phpmyadmin)
       # Create database 'ecommerce_db'
       # Import database/ecommerce_db.sql
    ```

Or via command line:
    ```bash
       mysql -u root -p < database/ecommerce_db.sql
    ```
*Note: If you're running linux, you might need to sudo for admin privileges.

5. **Configure Database Connection**
    ```bash
       # Update php/config.php with your database credentials
       # Default credentials:
       # DB_USER: root
       # DB_PASS: (empty)
       # DB_NAME: ecommerce_db
    ```

6. **Access the Application**
    ```
       http://localhost/cosc213-finalproject
    ```

## Login Credentials
### Admin Account
- **Email:** admin@shop.com
- **Password:** admin123

### Regular User Account
- **Email:** user@shop.com
- **Password:** user123

## Project Structure
```
cosc213-finalproject/
├── css/
│   └── style.css                   # Main stylesheet (swamp green theme)
├── database/
│   └── dump.sql                    # Database schema and sample data
├── images/
│   └── placeholder.jpg             # Default product image
├── php/
│   ├── add_to_cart.php             # Add items to cart
│   ├── admin_delete_product.php    # Delete products
│   ├── checkout_process.php        # Simulates checkout
│   ├── config.php                  # Database connection & helper functions
│   ├── logout.php                  # Logout functionality   
│   ├── remove_from_cart.php        # Remove items from cart
|   ├── delete_account.php          # Delete account
|   └── edit_account.php            # Edits account information
├── README.md                       # This file
├── admin.php                       # Admin dashboard
├── admin_add_product.php           # Add new products
├── admin_edit_product.php          # Edit existing products
├── cart.php                        # Shopping cart
├── checkout.php                    # Checkout process
├── index.php                       # Product listing homepage
├── login.php                       # User login
├── products.php                    # Individual product details
├── signup.php                      # User registration
└── profile.php                     # Profile settings page
```

# E-Commerce Platform - COSC 213 Final Project

## Team Members
- [Gian Tindogan, Adam Hobson, Stefan Wells]

## Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/adamhb710/cosc213-finalproject.git
   cd cosc213-finalproject
   ```

2. **Import the database**
   ```bash
   mysql -u root -p < database/dump.sql
   ```

3. **Configure database connection**
   - Copy `php/config.example.php` to `php/config.php`
   - Edit `php/config.php` with your MySQL credentials

4. **Start the server**
   ```bash
   php -S localhost:8000
   ```
   Visit: `http://localhost:8000`

## Login Credentials
- **Admin**: admin@shop.com / admin123

## Features
- User registration and login
- Product catalog
- Shopping cart
- Checkout process
- Admin panel (CRUD operations)

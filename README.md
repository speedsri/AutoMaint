# AutoMaint
 Powerful, easy-to-use Multi-Vehicle Maintenance Program. it will streamline your operations, making vehicle tracking, maintenance scheduling, and history logging simpler . 
# 🚗 Vehicle Maintenance Management System

A powerful, user-friendly web application for managing multi-vehicle maintenance operations. This system helps organizations streamline their vehicle fleet management by providing comprehensive tracking, maintenance logging, and reporting capabilities.

![PHP Version](https://img.shields.io/badge/PHP-8.1.0-blue.svg)
![MySQL Version](https://img.shields.io/badge/MySQL-5.7.24-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## ✨ Key Features

- **Vehicle Management**
  - Detailed vehicle information tracking (make, model, year, registration numbers)
  - Engine and chassis number documentation
  - Custom vehicle descriptions and specifications

- **Maintenance Tracking**
  - Comprehensive maintenance record logging
  - Service history with dates and odometer readings
  - Cost tracking for all maintenance activities
  - Service type categorization
  - Image attachments for service documentation

- **Parts Management**
  - Track parts used in maintenance
  - Inventory of part numbers and quantities
  - Cost tracking for parts
  - Service type categorization (Seasonal, Preventive, Electrical, etc.)

- **User Management**
  - Secure user authentication system
  - Role-based access control
  - User activity logging
  - Password encryption for security
## 💡 Smart Features

- **Quick Actions**

- **One-click record editing**
Fast record deletion with confirmation
Instant image viewing
Quick export capabilities

- **Intelligent Navigation**
- Dropdown menus for common actions
- Breadcrumb navigation
- Context-aware menu system
- Quick access toolbar

## 📊 Smart Dashboard Features

- **Statistical Overview**
- Total records counter
- Cost calculations and summaries
- Vehicle-specific total cost tracking
- Average maintenance cost analytics

- **Responsive Data Display**
- Clean, organized table view
- Sort by any column
- Custom scrollbars for smooth navigation
- Mobile-friendly interface

## 🔍 Powerful Search & Filtering

- **Real-Time Search**

- Instant search across all maintenance records
- Search by any field: vehicle number, service type, date, or description
- Results update as you type
- Zero lag, even with thousands of records

- **Advanced Filtering System**
- Multi-criteria filtering
- Filter by date ranges
- Filter by vehicle registration
- Filter by service types
- Combine multiple filters for precise results

- **Visual Documentation**
- Image attachment support for each maintenance record
- Visual service history tracking
- Click-to-view image galleries
- Chronological image sorting by maintenance date

## 🚀 Installation

1. **Prerequisites**
   ```
   - PHP 8.1.0 or higher
   - MySQL 5.7.24 or higher
   - Apache/Nginx web server
   - Composer (PHP package manager)
   ```

2. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/vehicle-maintenance-system.git
   cd vehicle-maintenance-system
   ```
3. **Database Setup**
   ```bash
   # Create a new MySQL database
   mysql -u root -p
   CREATE DATABASE vehiclemaintenancedb;
  
   # Import the database schema
   mysql -u root -p vehiclemaintenancedb < vehiclemaintenancedb.sql
   ```
4. **Configuration**
   ```bash
   # Copy the example configuration file
   cp config.example.php config.php
   
   # Edit the configuration file with your database credentials
   nano config.php
   ```
5. **Web Server Configuration**
   - Point your web server's document root to the `public` directory
   - Ensure proper permissions are set:
     ```bash
     chmod -R 755 public/
     chmod -R 777 public/uploads/
     ```
6. **First Run**
   - Navigate to `http://yourdomain.com/install.php`
   - Follow the setup wizard to create the admin account
   - Delete the install.php file after setup is complete

## 💻 System Requirements

- **Server Requirements:**
  - PHP >= 8.1.0
  - MySQL >= 5.7.24
  - PHP Extensions:
    - PDO PHP Extension
    - MySQL PDO Driver
    - GD Library (for image processing)
    - FileInfo Extension

- **Browser Requirements:**
  - Modern browsers (Chrome, Firefox, Safari, Edge)
  - JavaScript enabled
  - Cookies enabled

## 🔒 Security Features

- Password hashing using bcrypt
- Protection against SQL injection
- XSS protection
- CSRF token verification
- Secure session handling
- Input validation and sanitization

## 📖 Documentation

Detailed documentation is available in the `docs` directory:
- User Guide
- Administrator Guide
- API Documentation
- Database Schema
- Troubleshooting Guide

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For support and queries, please create an issue in the GitHub repository or contact the maintainers at earsekanayaket@yandex.com

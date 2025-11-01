# Database & Authentication Setup Guide

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite enabled

## Installation Steps

### 1. Database Setup

#### Option A: Using Migration Tool (Recommended)
```bash
# Navigate to project directory
cd c:\xampp\htdocs\host\mod

# Run database migrations
php migrate.php

# This will:
# - Create the database if it doesn't exist
# - Create all required tables
# - Insert default admin user and sample data
```

#### Option B: Manual Setup
1. Create a MySQL database named `modernzone_db`
2. Import the schema from `database/schema.sql`
```bash
mysql -u root -p modernzone_db < database/schema.sql
```

### 2. Configuration

#### Create Environment File
1. Copy `.env.example` to `.env`
```bash
copy .env.example .env
```

2. Update database credentials in `.env`:
```env
DB_HOST=localhost
DB_DATABASE=modernzone_db
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

3. Generate a secure APP_KEY:
```php
// Generate a 32-character random key
echo base64_encode(random_bytes(32));
```

### 3. Default Credentials

After running migrations, you can login with:
- **Email**: admin@modernzonetrading.com
- **Password**: Admin@123

### 4. Testing the Setup

1. Navigate to: `http://localhost/host/mod/`
2. Click on "Login" in the header
3. Use the default admin credentials
4. You should be logged in successfully

## Features Implemented

### ✅ Priority 1: Database & Authentication
#### Database Layer
- **Database Connection Class**: PDO-based with MySQL/PostgreSQL support
- **Migration System**: Version control for database schema
- **Base Model Class**: ORM functionality with relationships
- **Query Builder**: Fluent interface for database queries

#### Authentication System
- **User Registration**: With validation and password hashing
- **User Login**: With rate limiting and remember me
- **Password Reset**: Token-based password recovery
- **Session Management**: Secure session handling with database storage
- **CSRF Protection**: Token validation for all forms
- **XSS Prevention**: Input sanitization and output escaping

#### Security Features
- **Password Hashing**: BCrypt with cost factor 10
- **CSRF Tokens**: Automatic generation and validation
- **Rate Limiting**: Prevents brute force attacks
- **Session Security**: Regeneration and secure cookies
- **Input Validation**: Email, password strength, etc.
- **SQL Injection Prevention**: Prepared statements

### ✅ Priority 2: E-commerce Core
#### Shopping Cart System
- **Session-based Cart**: For guest users with session persistence
- **Database Cart**: For logged-in users with database persistence
- **Cart Merge**: Automatic merging when user logs in
- **Stock Validation**: Real-time stock checking
- **Price Updates**: Automatic price synchronization
- **Cart Operations**: Add, update quantity, remove items, clear cart

#### Checkout & Orders
- **Order Processing**: Complete checkout flow with validation
- **Order Management**: Order creation with unique order numbers
- **Stock Management**: Automatic stock updates on order placement
- **Order Status**: Multiple status tracking (pending, processing, shipped, etc.)
- **Payment Methods**: Support for COD and online payments (ready for integration)
- **Order History**: Complete order tracking for users

#### Product Search & Filtering
- **Full-text Search**: Search by name, description, SKU
- **Advanced Filters**: Category, brand, price range, stock status
- **Sort Options**: By relevance, name, price, newest, rating
- **Search Suggestions**: AJAX-powered autocomplete
- **Pagination**: Efficient result pagination
- **Search Analytics**: View tracking for relevance

#### User Account Dashboard
- **Profile Management**: Update personal information
- **Order Management**: View orders, track status, cancel orders
- **Password Change**: Secure password update functionality
- **Address Book**: Manage shipping and billing addresses
- **Order Statistics**: Total orders, spending, pending orders
- **Wishlist**: Save products for later (structure ready)

### ✅ Priority 3: Admin Panel & Content Management (COMPLETED)
#### Admin Authentication
- **Role-Based Access Control**: Admin and Manager roles with different permissions
- **Admin Middleware**: Protects admin routes and checks permissions
- **Unified Authentication**: Uses main login page for all users (no separate admin login)
- **Permission System**: Granular permissions for different admin functions
- **Access Logging**: Tracks admin login/logout activities

#### Modern Admin UI
- **Component-Based Layout**: Modular sidebar, header, and main layout system
- **White Sidebar Design**: Clean white sidebar with company logo
- **Responsive Header**: Date display, notifications, and user dropdown menu
- **Functional Dropdowns**: Working notification bell and user menu with logout
- **Professional Styling**: Modern design with rounded corners, shadows, and hover effects

#### Product Management (CRUD)
- **Product Listing**: Paginated list (20 per page) with search and filters
- **Create Products**: Full product creation with all attributes and validation
- **Edit Products**: Update existing products with automatic slug generation
- **Delete Products**: Soft delete for products with orders, hard delete otherwise
- **Bulk Operations**: Status updates, featured flags
- **SKU Management**: Unique SKU validation and tracking
- **Category & Status Filters**: Filter products by category and status

#### Order Management
- **Order Dashboard**: View all orders with filters and search (20 per page)
- **Order Details**: Complete order information with items and customer details
- **Status Management**: Update order status with real-time tracking
- **Payment Management**: Update payment status and methods
- **Order Export**: Export orders to CSV for reporting
- **Invoice Generation**: Print-ready invoices for orders
- **Advanced Filters**: Filter by status, payment status, date range

#### Analytics Dashboard
- **Real-time Statistics**: Today's and monthly performance metrics (5 stat cards)
- **Sales Analytics**: Revenue tracking, order counts, trends by status and payment method
- **Product Analytics**: Top selling products (limited to 10), category performance
- **Customer Analytics**: Top customers (limited to 10), new registrations
- **Visual Charts**: 30-day sales trends and breakdowns with bar charts
- **Low Stock Alerts**: Products needing restock (limited to 10)
- **Performance Optimized**: All analytics queries use LIMIT for fast loading

## Database Schema

### Core Tables
- `users` - User accounts and profiles
- `user_sessions` - Active user sessions
- `password_resets` - Password reset tokens
- `categories` - Product categories
- `brands` - Product brands
- `products` - Product catalog
- `product_images` - Product gallery
- `product_reviews` - Customer reviews
- `cart_items` - Shopping cart
- `orders` - Customer orders
- `order_items` - Order line items

## Migration Commands

```bash
# Run all pending migrations
php migrate.php

# Rollback last batch
php migrate.php rollback

# Reset all migrations
php migrate.php reset

# Refresh database (reset + migrate + seed)
php migrate.php refresh

# Run seeders only
php migrate.php seed
```

## Troubleshooting

### Database Connection Error
- Verify MySQL/MariaDB is running
- Check database credentials in `.env` or `app/config/database.php`
- Ensure database exists or user has CREATE privilege

### Migration Fails
- Check PHP error logs for details
- Verify table doesn't already exist
- Ensure proper foreign key order

### Login Not Working
- Clear browser cookies and session
- Check if user exists in database
- Verify password hash is correct
- Check session directory is writable

### CSRF Token Error
- Clear browser cache
- Ensure session is started
- Check form includes CSRF field

## Security Best Practices

1. **Change Default Password**: Update admin password immediately
2. **Use HTTPS**: Enable SSL in production
3. **Update APP_KEY**: Generate unique key for production
4. **Restrict Database Access**: Use limited privileges
5. **Enable Error Logging**: But hide errors in production
6. **Regular Backups**: Backup database regularly

## Next Steps

### Priority 4: Advanced Features & Enhancements
1. **Category & Brand Management**: CRUD operations for categories and brands
2. **User Management**: Admin interface for managing customer accounts
3. **Email Integration**: SMTP configuration for order confirmations and notifications
4. **File Uploads**: Product image management with multiple images per product
5. **Payment Gateway Integration**: Stripe/PayPal for online payments
6. **Reviews & Ratings**: Customer product review system
7. **Inventory Management**: Stock alerts, reorder points, batch updates

### Priority 5: Performance & Scalability
1. **Caching Layer**: Redis/Memcached for improved performance
2. **Search Optimization**: Elasticsearch integration for advanced search
3. **Image Optimization**: CDN integration and lazy loading
4. **Database Optimization**: Query optimization and indexing
5. **API Development**: RESTful API for mobile apps
6. **Queue System**: Background job processing for emails and reports
7. **Load Balancing**: Multi-server setup for high availability

### Optional Enhancements
1. **Two-Factor Authentication**
2. **OAuth Integration** (Google, Facebook)
3. **Advanced Search** with Elasticsearch
4. **Caching Layer** with Redis
5. **Queue System** for background jobs

## Support

For issues or questions:
1. Check error logs in `storage/logs/`
2. Review this documentation
3. Contact development team

---

## Admin Panel Access

### Admin Login
Navigate to: `http://localhost/host/mod/login`

**Default Credentials:**
- Email: `admin@modernzonetrading.com`
- Password: `Admin@123`

**Note**: Admin users login through the main login page. After successful login, admins are automatically redirected to the admin dashboard at `/admin`.

### Admin Features Available
- **Modern Dashboard**: Real-time statistics with 5 key metrics displayed in compact cards
- **Product Management**: Full CRUD operations with pagination, search, and filters
- **Order Management**: View, update status, export to CSV, print invoices
- **Analytics**: Detailed sales, product, and customer analytics with visual charts
- **Profile Settings**: Update personal information and change password
- **Responsive UI**: Clean white sidebar with logo, functional dropdown menus

### Admin Panel Routes
- `/admin` - Main dashboard
- `/admin/products` - Product management
- `/admin/products/create` - Create new product
- `/admin/products/{id}/edit` - Edit product
- `/admin/orders` - Order management
- `/admin/orders/{id}` - Order details
- `/admin/analytics` - Analytics dashboard
- `/admin/logout` - Logout (redirects to main login)

**Version**: 1.0.0  
**Last Updated**: October 31, 2025

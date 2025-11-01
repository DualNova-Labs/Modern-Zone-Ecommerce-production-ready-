# Quick Start Guide - Modern Zone E-commerce

## ✅ Admin Login (FIXED)

### Login Credentials
```
URL:      http://localhost/host/Modern-Zone-Ecommerce-production-ready-/login
Email:    admin@modernzonetrading.com
Password: Admin@123
```

### After Login
- **Admin users** → Automatically redirected to `/admin` dashboard
- **Regular users** → Redirected to homepage

## 🔧 Setup Commands

### First Time Setup
```bash
# 1. Create database (if not exists)
mysql -u root -p -e "CREATE DATABASE modernzone_db;"

# 2. Import schema
mysql -u root -p modernzone_db < database/schema.sql

# 3. Setup admin user
c:\xampp\php\php.exe setup_admin.php
```

### Verify Setup
```bash
c:\xampp\php\php.exe verify_setup.php
```

### Reset Admin Password
```bash
c:\xampp\php\php.exe setup_admin.php
```

## 📝 What Was Fixed

1. **Password Hash Issue** - Admin password hash now correctly matches `Admin@123`
2. **Admin Redirect** - Admins now redirect to `/admin` after login (not homepage)
3. **Unified Login** - Single login page for both admin and regular users
4. **Setup Scripts** - Created automated scripts for easy admin setup

## 🔍 Troubleshooting

### Can't Login?
1. Run: `c:\xampp\php\php.exe setup_admin.php`
2. Clear browser cookies
3. Try again with credentials above

### Database Error?
1. Start XAMPP MySQL
2. Create database: `CREATE DATABASE modernzone_db;`
3. Import: `mysql -u root -p modernzone_db < database/schema.sql`

### Still Issues?
Check `ADMIN_LOGIN_FIX.md` for detailed troubleshooting guide.

## 📂 Important Files

- `setup_admin.php` - Creates/updates admin user
- `verify_setup.php` - Checks system configuration
- `ADMIN_LOGIN_FIX.md` - Detailed troubleshooting guide
- `.env` - Database configuration
- `database/schema.sql` - Database structure

## 🎯 Next Steps

After successful login:
1. Go to `/admin` - View dashboard
2. Go to `/admin/products` - Manage products
3. Go to `/admin/orders` - Manage orders
4. Go to `/admin/analytics` - View analytics

---
**Note**: The admin password is case-sensitive. Make sure to use `Admin@123` exactly as shown.

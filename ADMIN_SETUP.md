# Admin Setup Guide - Modern Zone Trading

## Default Admin Credentials

When you set up the database for the first time, an admin user is automatically created with these credentials:

- **Email:** `admin@modernzonetrading.com`
- **Password:** `Admin@123`

## Database Setup

### Option 1: Fresh Installation (Recommended)

If you're setting up the project for the first time:

1. **Create the database:**
   ```sql
   CREATE DATABASE modernzone_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import the complete schema:**
   ```bash
   mysql -u root -p modernzone_db < database/complete_schema.sql
   ```
   
   Or if using XAMPP on Windows:
   ```bash
   C:\xampp\mysql\bin\mysql.exe -u root -p modernzone_db < database\complete_schema.sql
   ```

3. **The admin user is created automatically** with the credentials mentioned above.

### Option 2: Update Existing Database

If you already have a database and just need to add/update the admin user:

```sql
-- Option A: If admin user doesn't exist, create it
INSERT INTO users (name, email, password, role, status, email_verified_at) VALUES
('Admin', 'admin@modernzonetrading.com', '$2y$10$kXF/zGcEUd4YcfW/cqlJHO8vNrd6f32jjyler/T44Kw5CEWc3Tc06', 'admin', 'active', NOW());

-- Option B: If admin user exists, update it
UPDATE users SET 
    password = '$2y$10$kXF/zGcEUd4YcfW/cqlJHO8vNrd6f32jjyler/T44Kw5CEWc3Tc06',
    role = 'admin',
    status = 'active',
    email_verified_at = NOW()
WHERE email = 'admin@modernzonetrading.com';
```

## Accessing the Admin Panel

1. **Go to login page:**
   ```
   http://localhost/host/Modern-Zone-Ecommerce-production-ready-/login
   ```
   
   Or on production:
   ```
   https://yourdomain.com/login
   ```

2. **Enter credentials:**
   - Email: `admin@modernzonetrading.com`
   - Password: `Admin@123`

3. **You'll be automatically redirected to:**
   ```
   /admin
   ```

## Troubleshooting

### Can't Login with Default Credentials?

If you can't login with the default credentials, the password hash in the database might be incorrect. Here's how to fix it:

**Option 1: Using MySQL Command Line**
```sql
-- Connect to your database
mysql -u root -p modernzone_db

-- Update the admin password
UPDATE users SET 
    password = '$2y$10$kXF/zGcEUd4YcfW/cqlJHO8vNrd6f32jjyler/T44Kw5CEWc3Tc06'
WHERE email = 'admin@modernzonetrading.com';
```

**Option 2: Using phpMyAdmin**
1. Open phpMyAdmin
2. Select `modernzone_db` database
3. Go to `users` table
4. Find the row with email `admin@modernzonetrading.com`
5. Edit the `password` field and replace it with:
   ```
   $2y$10$kXF/zGcEUd4YcfW/cqlJHO8vNrd6f32jjyler/T44Kw5CEWc3Tc06
   ```
6. Save changes

**Option 3: Re-import the Database**
```bash
# Drop and recreate the database
mysql -u root -p -e "DROP DATABASE IF EXISTS modernzone_db; CREATE DATABASE modernzone_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import the schema
mysql -u root -p modernzone_db < database/complete_schema.sql
```

### Admin User Doesn't Exist?

If the admin user doesn't exist in the database, run this SQL:

```sql
INSERT INTO users (name, email, password, role, status, email_verified_at) VALUES
('Admin', 'admin@modernzonetrading.com', '$2y$10$kXF/zGcEUd4YcfW/cqlJHO8vNrd6f32jjyler/T44Kw5CEWc3Tc06', 'admin', 'active', NOW());
```

## Security Best Practices

1. **Change the default password** after first login!
2. **Never commit** database credentials or sensitive information to Git
3. **Use strong passwords** for production environments
4. **Enable 2FA** (Two-Factor Authentication) if available
5. **Regularly update** passwords and review user access

## Changing Admin Password

### Via Admin Panel (Recommended)
1. Login to admin panel
2. Go to Profile Settings
3. Change your password
4. Save changes

### Via Database (If Locked Out)
1. Generate a new password hash:
   ```php
   <?php
   echo password_hash('YourNewPassword', PASSWORD_BCRYPT);
   ?>
   ```

2. Update the database:
   ```sql
   UPDATE users SET password = 'your_generated_hash_here' WHERE email = 'admin@modernzonetrading.com';
   ```

## Notes

- The password hash `$2y$10$kXF/zGcEUd4YcfW/cqlJHO8vNrd6f32jjyler/T44Kw5CEWc3Tc06` is for `Admin@123`
- Hashes are generated using PHP's `password_hash()` function with BCRYPT algorithm
- Each time you generate a hash, it will be different but still verify correctly
- The database schema includes `ON DUPLICATE KEY UPDATE` to prevent duplicate admin users

## Support

If you continue to experience issues:
1. Check `.env` file for correct database credentials
2. Ensure database server is running
3. Verify the `users` table exists
4. Check application logs in `logs/` directory

---

**Last Updated:** December 2, 2025

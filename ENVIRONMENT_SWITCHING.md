# Environment Switching Guide

## Quick Switch Instructions

Your `.env` file now contains both **LOCAL** and **PRODUCTION** configurations. To switch between environments:

### Switch to LOCAL Development
1. **Comment out** the PRODUCTION section (lines 15-25):
   ```
   # APP_ENV=production
   # APP_DEBUG=false
   # APP_URL=https://dev.hashcovet.shop/modernzone
   
   # Database Configuration - PRODUCTION
   # DB_DRIVER=mysql
   # DB_HOST=srv1404.hstgr.io
   # DB_PORT=3306
   # DB_DATABASE=u208353382_modernzonedb
   # DB_USERNAME=u208353382_modernzoneusr
   # DB_PASSWORD=Dev_2025
   ```

2. **Uncomment** the LOCAL section (lines 30-40):
   ```
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost/host/mod
   
   # Database Configuration - LOCAL
   DB_DRIVER=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=modernzone_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Switch to PRODUCTION
1. **Comment out** the LOCAL section (add `#` to lines 30-40)
2. **Uncomment** the PRODUCTION section (remove `#` from lines 15-25)

## Current Configuration
- **Active Environment**: PRODUCTION
- **Database**: Remote production database
- **Debug Mode**: Disabled
- **URL**: https://dev.hashcovet.shop/modernzone

## Environment Variables Reference

| Variable | Local Value | Production Value |
|----------|-------------|------------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost/host/mod` | `https://dev.hashcovet.shop/modernzone` |
| `DB_HOST` | `localhost` | `srv1404.hstgr.io` |
| `DB_DATABASE` | `modernzone_db` | `u208353382_modernzonedb` |
| `DB_USERNAME` | `root` | `u208353382_modernzoneusr` |
| `DB_PASSWORD` | *(empty)* | `Dev_2025` |

## Important Notes
- Always ensure only ONE environment is active at a time
- The `Environment.php` class will automatically load the active configuration
- Test your connection after switching environments
- Keep your local database name as `modernzone_db` for consistency

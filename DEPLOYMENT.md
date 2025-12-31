# Monitorbizz Deployment Guide for CloudPanel

## Server: portfolio3.lemmecode.in

### Step 1: Create Site in CloudPanel
1. Login to CloudPanel
2. Create new PHP site: `portfolio3.lemmecode.in`
3. PHP Version: 8.1 or higher
4. Create MySQL database and user

### Step 2: Upload Files
Upload entire project to: `/home/portfolio3.lemmecode.in/htdocs/`

### Step 3: Environment Configuration
Copy `.env.production` to `.env` and update:
```
APP_URL=https://portfolio3.lemmecode.in
DB_HOST=localhost
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### Step 4: Run Commands (via SSH or CloudPanel Terminal)
```bash
cd /home/portfolio3.lemmecode.in/htdocs/
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 5: Web Server Config
Document root should point to: `/home/portfolio3.lemmecode.in/htdocs/public`

### Test URLs:
- https://portfolio3.lemmecode.in (landing page)
- https://portfolio3.lemmecode.in/register (registration)
- https://portfolio3.lemmecode.in/login (login)
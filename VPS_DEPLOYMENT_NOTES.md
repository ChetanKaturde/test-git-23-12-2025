# VPS Deployment & Testing Guide

## 🚀 VPS Setup Commands

### 1. Clone Repository
```bash
git clone https://github.com/alatralanahitrnahi/Motorbizzzzz___20255223252.git
cd Motorbizzzzz___20255223252
```

### 2. Install Dependencies
```bash
# PHP Dependencies
composer install --no-dev --optimize-autoloader

# Node Dependencies
npm install
npm run build
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Database Setup
```bash
# Create SQLite database
touch database/database.sqlite
chmod 664 database/database.sqlite

# Run migrations
php artisan migrate --force

# Seed sample data
php artisan db:seed --class=SampleDataSeeder
```

### 5. Web Server Configuration

#### Apache Virtual Host
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/Motorbizzzzz/public
    
    <Directory /path/to/Motorbizzzzz/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/Motorbizzzzz/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🔧 Environment Variables (.env)

```env
APP_NAME="Monitorbizz"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/database/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## 🧪 Testing Checklist

### 1. Basic System Test
```bash
# Check if server responds
curl -I https://yourdomain.com
# Should return: HTTP/1.1 200 OK

# Check database connection
php artisan tinker
>>> App\Models\User::count()
# Should return: 1
```

### 2. Authentication Test
- **URL**: `https://yourdomain.com/login`
- **Credentials**: Use admin credentials from .env file (ADMIN_EMAIL and ADMIN_PASSWORD)
- **Expected**: Redirect to dashboard

### 3. Dashboard Test
- **URL**: `https://yourdomain.com/dashboard`
- **Check**: 
  - ✅ Stats show: 6 Materials, 3 Vendors, 3 Purchase Orders
  - ✅ Sidebar navigation visible
  - ✅ Business name: "Sample Manufacturing Workshop"

### 4. Core Features Test

#### Materials Management
- **URL**: `https://yourdomain.com/materials`
- **Test**: Should show 6 materials (Steel, Aluminum, etc.)
- **Action**: Try creating new material

#### Vendors Management  
- **URL**: `https://yourdomain.com/vendors`
- **Test**: Should show 3 vendors
- **Action**: Try creating new vendor

#### Purchase Orders
- **URL**: `https://yourdomain.com/purchase-orders`
- **Test**: Should show 3 purchase orders
- **Action**: Try creating new PO

#### Machines
- **URL**: `https://yourdomain.com/machines`
- **Test**: Should show empty state with "Add Machine" button
- **Action**: Create first machine (CNC, Lathe, etc.)

### 5. Multi-Tenant Test

#### New Business Registration
- **URL**: `https://yourdomain.com/register`
- **Test Data**:
  - Business Name: "Test Workshop"
  - Business Slug: "test-workshop"
  - Phone: "9876543210"
  - Address: "Test Address"
  - Owner Name: "Test User"
  - Email: "test@example.com"
  - Password: "password123"
- **Expected**: New business created, login successful

#### Data Isolation Test
- Login as new user
- Check dashboard shows 0 materials, 0 vendors (isolated data)
- Create materials/vendors
- Logout and login as admin@motorbizz.com
- Verify admin can't see new user's data

### 6. Mobile Responsiveness Test
- Test on mobile device or browser dev tools
- Check sidebar collapses properly
- Verify forms are mobile-friendly
- Test navigation on small screens

### 7. Performance Test
```bash
# Check page load times
curl -w "@curl-format.txt" -o /dev/null -s https://yourdomain.com/dashboard

# Create curl-format.txt:
echo "time_namelookup:  %{time_namelookup}\ntime_connect:     %{time_connect}\ntime_total:       %{time_total}" > curl-format.txt
```

## 🐛 Common Issues & Fixes

### Issue: 500 Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Database Connection Error
```bash
# Check SQLite file exists and is writable
ls -la database/database.sqlite
chmod 664 database/database.sqlite
```

### Issue: CSS/JS Not Loading
```bash
# Rebuild assets
npm run build

# Check public/build directory exists
ls -la public/build/
```

### Issue: Routes Not Working
```bash
# Clear caches
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

## 📊 Success Criteria

### ✅ System is Working When:
- [ ] Homepage loads without errors
- [ ] Login works with test credentials
- [ ] Dashboard shows correct stats
- [ ] All navigation links work
- [ ] Materials/Vendors/PO pages load
- [ ] New business registration works
- [ ] Data isolation between businesses works
- [ ] Mobile interface is responsive
- [ ] No 404 or 500 errors in browser console

### 🎯 Performance Targets:
- Page load time < 2 seconds
- Database queries < 50ms
- No N+1 query issues
- Mobile-friendly interface

## 📞 Support Information

**Repository**: https://github.com/alatralanahitrnahi/Motorbizzzzz___20255223252
**Documentation**: See SYSTEM_STATUS.md and AMAZON_Q_ACTIVITY_LOG.md
**Test Credentials**: See .env file for admin credentials
**Sample Business**: Sample Manufacturing Workshop

---

**Deployment Status**: Ready for production testing
**Last Updated**: October 2025
# Monitorbizz Docker Setup

This directory contains the Docker configuration for running Monitorbizz in both development and production environments.

## Architecture Overview

The Docker setup includes:
- **Laravel App**: PHP-FPM + Nginx in a single optimized container
- **MySQL**: Database server with persistent storage
- **Redis**: Caching and session storage
- **Mailpit**: Email testing service for development

## Quick Start

### Production Deployment

1. **Configure Environment**:
   ```bash
   cp .env.prod .env
   # Edit .env with your production values
   ```

2. **Build and Start**:
   ```bash
   docker-compose up -d
   ```

3. **Run Migrations**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

4. **Generate App Key** (if not set):
   ```bash
   docker-compose exec app php artisan key:generate
   ```

### Development Setup

1. **Configure Environment**:
   ```bash
   cp .env.dev .env
   ```

2. **Start Development Environment**:
   ```bash
   docker-compose -f docker-compose.dev.yml up -d
   ```

3. **Install Dependencies** (if needed):
   ```bash
   docker-compose -f docker-compose.dev.yml exec app composer install
   ```

## Services

### App Service (Laravel)
- **Image**: Custom multi-stage build
- **Ports**: 80 (prod), 8000 (dev)
- **Health Check**: HTTP endpoint at `/health`
- **Volumes**: Storage and logs persistence

### Database (MySQL)
- **Image**: mysql:8.0
- **Ports**: 3306
- **Health Check**: MySQL ping
- **Volume**: `db_data` for data persistence

### Redis
- **Image**: redis:7-alpine
- **Ports**: 6379
- **Health Check**: Redis ping
- **Volume**: `redis_data` for persistence

### Mailpit
- **Image**: axllent/mailpit:latest
- **Ports**: 8025 (web UI), 1025 (SMTP)
- **Health Check**: HTTP status check

## Asset Building

For production, assets are built during the Docker build process. For development:

```bash
# Build assets on-demand
docker-compose -f docker-compose.dev.yml run --rm node

# Or watch for changes
docker-compose -f docker-compose.dev.yml run --rm node npm run dev
```

## Environment Variables

### Required for Production
- `APP_KEY`: Laravel application key
- `DB_DATABASE`: Database name
- `DB_USERNAME`: Database user
- `DB_PASSWORD`: Database password
- `MYSQL_ROOT_PASSWORD`: MySQL root password

### Optional
- `APP_URL`: Application URL
- `MAIL_FROM_ADDRESS`: Email sender address
- AWS credentials for file storage

## Volumes

- `db_data`: MySQL data persistence
- `redis_data`: Redis data persistence
- `app_storage`: Laravel storage directory
- `app_logs`: Application logs

## Networks

- `monitorbizz_network`: Isolated network for service communication

## Health Checks

All services include health checks:
- **App**: HTTP request to `/health` endpoint
- **Database**: MySQL connection test
- **Redis**: PING command
- **Mailpit**: HTTP status check

## Troubleshooting

### Common Issues

1. **Port Conflicts**:
   ```bash
   # Check what's using ports
   lsof -i :80
   lsof -i :3306
   ```

2. **Permission Issues**:
   ```bash
   # Fix storage permissions
   docker-compose exec app chown -R www-data:www-data /var/www/html/storage
   ```

3. **Database Connection**:
   ```bash
   # Check database logs
   docker-compose logs db
   ```

4. **Build Issues**:
   ```bash
   # Rebuild without cache
   docker-compose build --no-cache
   ```

### Logs

```bash
# View all logs
docker-compose logs

# View specific service logs
docker-compose logs app
docker-compose logs db

# Follow logs
docker-compose logs -f app
```

## Security Considerations

- Use strong passwords for database
- Configure proper firewall rules
- Use HTTPS in production
- Regularly update base images
- Monitor logs for security issues

## Performance Optimization

- Multi-stage build reduces image size
- Opcache enabled for PHP
- Gzip compression in Nginx
- Static file caching headers
- Connection pooling for database

## Backup and Recovery

### Database Backup
```bash
# Create backup
docker-compose exec db mysqldump -u root -p monitorbizz_prod > backup.sql

# Restore backup
docker-compose exec -T db mysql -u root -p monitorbizz_prod < backup.sql
```

### Volume Backup
```bash
# Backup volumes
docker run --rm -v monitorbizz_db_data:/data -v $(pwd):/backup alpine tar czf /backup/db_backup.tar.gz -C /data .
```

## Monitoring

- Health checks provide basic monitoring
- Nginx access logs for request monitoring
- PHP-FPM status page available
- Redis MONITOR command for debugging

## Scaling

For production scaling:
- Use Docker Swarm or Kubernetes
- Implement load balancer
- Configure Redis cluster
- Use managed database services
- Implement CDN for static assets
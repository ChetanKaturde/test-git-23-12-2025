# Local Testing Instructions

## Prerequisites
- Docker & Docker Compose installed
- Git installed
- Port 8000 available

## Quick Start

### 1. Clone Repository
```bash
git clone https://github.com/ChetanKaturde/test-git-23-12-2025.git
cd test-git-23-12-2025
git checkout dockerization
```

### 2. Setup Environment
```bash
# Copy development environment file
cp .env.dev .env

# Start Docker containers
docker-compose -f docker-compose.dev.yml up -d
```

### 3. Initialize Database
```bash
# Run migrations and seed data
docker-compose -f docker-compose.dev.yml exec app php artisan migrate --seed
```

### 4. Access Application
- **Main App**: http://localhost:8000
- **Email Testing**: http://localhost:8025

## Test Features

### 1. Tooltips
- Hover over any element with `data-tooltip` attribute
- Check form fields, buttons, and navigation items

### 2. Onboarding
- Register new account or use existing
- Look for onboarding widgets on dashboard
- Test "Load Sample Data" button in materials

### 3. Mobile Responsiveness
- Open browser dev tools (F12)
- Toggle device toolbar
- Test on mobile viewport

### 4. Sample Data
- Go to Materials page
- Click "Load Sample Data" button
- Verify 6 sample materials are created

## Troubleshooting

### Port 8000 in use:
```bash
# Kill process on port 8000
sudo lsof -ti:8000 | xargs kill -9
```

### Containers not starting:
```bash
# Check Docker status
docker ps
docker-compose -f docker-compose.dev.yml logs
```

### Database issues:
```bash
# Reset database
docker-compose -f docker-compose.dev.yml exec app php artisan migrate:fresh --seed
```

## Stop Environment
```bash
docker-compose -f docker-compose.dev.yml down
```
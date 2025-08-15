# Docker Setup for APLPIL

This document explains how to set up and run the APLPIL Laravel application using Docker.

## 🐳 Prerequisites

- Docker Desktop installed
- Docker Compose installed
- Git (to clone the repository)
- PHP 8.2+ (for local development without Docker)

## 🚀 Quick Start

### 1. Clone and Setup

```bash
# Clone the repository
git clone <repository-url>
cd aplpil

# Make the setup script executable
chmod +x docker-setup.sh

# Run the setup script
./docker-setup.sh
```

### 2. Manual Setup (Alternative)

If you prefer to set up manually:

```bash
# Copy environment file
cp env.docker.example .env

# Build and start containers
docker-compose up -d --build

# Wait for containers to be ready, then run Laravel commands
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app npm install
docker-compose exec app npm run build
```

## 🌐 Access Points

Once setup is complete, you can access:

- **Main Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **MailHog (Email Testing)**: http://localhost:8025

## 📊 Database Configuration

- **Host**: localhost (or `db` from within containers)
- **Port**: 3306
- **Database**: aplpil
- **Username**: aplpil_user
- **Password**: aplpil_password

## 🔧 Useful Commands

### Container Management

```bash
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# Restart containers
docker-compose restart

# View logs
docker-compose logs -f

# View logs for specific service
docker-compose logs -f app
docker-compose logs -f db
```

### Laravel Commands

```bash
# Access the app container
docker-compose exec app bash

# Run Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan make:controller ExampleController
docker-compose exec app php artisan module:make NewModule

# Run Composer commands
docker-compose exec app composer install
docker-compose exec app composer update

# Run NPM commands
docker-compose exec app npm install
docker-compose exec app npm run dev
docker-compose exec app npm run build
```

### Database Commands

```bash
# Access MySQL directly
docker-compose exec db mysql -u aplpil_user -p aplpil

# Import database dump
docker-compose exec -T db mysql -u aplpil_user -p aplpil < database_dump.sql

# Export database
docker-compose exec db mysqldump -u aplpil_user -p aplpil > backup.sql
```

## 🏗️ Project Structure

```
aplpil/
├── docker-compose.yml          # Main Docker Compose configuration
├── docker-compose.override.yml # Development overrides
├── Dockerfile                  # Production Dockerfile
├── Dockerfile.dev             # Development Dockerfile
├── docker-setup.sh            # Setup script
├── .dockerignore              # Files to exclude from Docker build
├── env.docker.example         # Environment variables template
└── docker/                    # Docker configuration files
    ├── nginx/
    │   └── conf.d/
    │       └── app.conf       # Nginx configuration
    ├── php/
    │   └── local.ini         # PHP configuration
    └── mysql/
        └── my.cnf            # MySQL configuration
```

## 🔄 Development Workflow

### 1. Making Changes

The application code is mounted as a volume, so changes are reflected immediately:

```bash
# Edit files locally
# Changes are automatically reflected in the container
```

### 2. Adding New Dependencies

```bash
# PHP dependencies
docker-compose exec app composer require package-name

# Node.js dependencies
docker-compose exec app npm install package-name
```

### 3. Database Migrations

```bash
# Create new migration
docker-compose exec app php artisan make:migration create_example_table

# Run migrations
docker-compose exec app php artisan migrate

# Rollback migrations
docker-compose exec app php artisan migrate:rollback
```

### 4. Module Development

```bash
# Create new module
docker-compose exec app php artisan module:make NewModule

# Create module components
docker-compose exec app php artisan module:make-controller ControllerName NewModule
docker-compose exec app php artisan module:make-model ModelName NewModule
```

## 🐛 Troubleshooting

### Common Issues

1. **Port Already in Use**
   ```bash
   # Check what's using the port
   lsof -i :8000
   
   # Stop the conflicting service or change ports in docker-compose.yml
   ```

2. **Permission Issues**
   ```bash
   # Fix storage permissions
   docker-compose exec app chown -R www-data:www-data storage
   docker-compose exec app chmod -R 755 storage
   ```

3. **Database Connection Issues**
   ```bash
   # Check if database is running
   docker-compose ps
   
   # Restart database
   docker-compose restart db
   ```

4. **Composer Issues**
   ```bash
   # Clear Composer cache
   docker-compose exec app composer clear-cache
   
   # Reinstall dependencies
   docker-compose exec app composer install --no-cache
   ```

### Viewing Logs

```bash
# Application logs
docker-compose logs -f app

# Nginx logs
docker-compose logs -f webserver

# Database logs
docker-compose logs -f db

# All logs
docker-compose logs -f
```

## 🚀 Production Deployment

For production deployment, use the production Dockerfile:

```bash
# Build production image
docker build -t aplpil:production .

# Run with production environment
docker-compose -f docker-compose.prod.yml up -d
```

## 📝 Environment Variables

Key environment variables for Docker:

- `APP_ENV`: Set to `local` for development
- `DB_HOST`: Set to `db` (Docker service name)
- `REDIS_HOST`: Set to `redis` (Docker service name)
- `MAIL_HOST`: Set to `mailhog` for development

**Note**: This project requires PHP 8.2+. The Docker setup uses PHP 8.2-fpm image.

## 🔒 Security Notes

- Change default passwords in production
- Use environment variables for sensitive data
- Regularly update Docker images
- Enable SSL/TLS in production

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [Laravel Modules Documentation](https://nwidart.com/laravel-modules/)
- [Nginx Documentation](https://nginx.org/en/docs/) 
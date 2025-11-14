#!/bin/bash

# Quick Deployment Script untuk Linux Production
# Usage: bash deploy.sh

set -e

echo "=========================================="
echo "Laravel UKK - Deployment Script"
echo "=========================================="
echo ""

# Pull latest changes
echo "1. Pulling latest changes from Git..."
git pull origin main

# Install/update dependencies
echo ""
echo "2. Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Setup environment
if [ ! -f ".env" ]; then
    echo ""
    echo "3. Setting up .env file..."
    cp .env.example .env
    echo "   ⚠ Please update .env with your database credentials"
    echo "   Then run: php artisan key:generate"
else
    echo ""
    echo "3. .env file exists, skipping..."
fi

# Run migrations
echo ""
echo "4. Running database migrations..."
read -p "   Do you want to run migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
fi

# Create storage link
echo ""
echo "5. Creating storage symbolic link..."
php artisan storage:link

# Set permissions
echo ""
echo "6. Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Clear and cache
echo ""
echo "7. Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer
echo ""
echo "8. Optimizing Composer autoloader..."
composer dump-autoload --optimize

# Restart services
echo ""
echo "9. Restarting services..."
read -p "   Do you want to restart PHP-FPM and Nginx? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    sudo systemctl restart php8.1-fpm
    sudo systemctl restart nginx
fi

echo ""
echo "=========================================="
echo "✓ Deployment completed successfully!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "  1. Check application: https://yourdomain.com"
echo "  2. Monitor logs: tail -f storage/logs/laravel.log"
echo "  3. Test critical features"
echo ""

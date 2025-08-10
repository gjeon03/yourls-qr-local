#!/bin/bash
set -e

echo "🔧 Starting GD extension and dependencies installation..."

# Install GD extension
echo "📦 Installing GD extension..."
apt-get update
apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install -j$(nproc) gd

# Install Composer (if not available)
if ! command -v composer &> /dev/null; then
    echo "📚 Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

echo "✅ Dependencies installation completed!"

#!/bin/bash
set -e

PLUGIN_DIR="/var/www/html/user/plugins/qr-local"

echo "🚀 Starting YOURLS QR Plugin installation..."

# 1. Create plugin directory
echo "📁 Creating plugin directory..."
mkdir -p "$PLUGIN_DIR"

# 2. Copy files
echo "📄 Copying plugin files..."
cp plugin.php "$PLUGIN_DIR/"
cp composer.json "$PLUGIN_DIR/"

# 3. Install Composer dependencies
echo "📚 Installing dependencies..."
cd "$PLUGIN_DIR"
composer install --no-dev --optimize-autoloader

# 4. Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data "$PLUGIN_DIR"

echo "✅ Plugin installation completed!"
echo ""
echo "📋 Next steps:"
echo "1. Access your YOURLS admin panel"
echo "2. Activate 'Local QR Generator' plugin in the Plugins menu"
echo "3. Usage: http://yourdomain.com/shortcode.qr"

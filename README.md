# YOURLS Local QR Code Plugin

A YOURLS plugin that generates QR codes locally using the endroid/qr-code library. No external API dependencies required.

## ✨ Features

- 🔒 **Local Generation**: No external API dependencies
- ⚡ **Real-time**: QR codes automatically reflect link status
- 🎨 **Customizable**: Adjustable size, margin, and styling
- 🚀 **Easy Installation**: Automated setup scripts included

## 📋 Requirements

- YOURLS installation
- PHP GD extension
- Composer

## 🚀 Installation

### 1. Access YOURLS Container
```bash
docker exec -it [yourls-container-name] bash
```

### 2. Clone Repository
```bash
cd /tmp
git clone https://github.com/gjeon03/yourls-qr-local.git
cd yourls-qr-local
```

### 3. Install Dependencies (First Time Only)
```bash
./setup-dependencies.sh
```

### 4. Install Plugin
```bash
./install.sh
```

### 5. Activate Plugin
1. Access your YOURLS admin panel
2. Go to **Plugins** menu
3. Activate **"Local QR Generator"** plugin

## 📱 Usage

- **Regular Link**: `https://yourdomain.com/abc` → Redirects to original URL
- **QR Code**: `https://yourdomain.com/abc.qr` → Generates QR code image

### Example

1. Create a short URL in YOURLS: `https://your-domain.com/test`
2. Access the QR code by adding `.qr`: `https://your-domain.com/test.qr`
3. The QR code will contain the original long URL and can be scanned by any QR code reader

## ⚙️ Customization

You can modify the QR code settings in the `plugin.php` file:

```php
$qr = \Endroid\QrCode\Builder\Builder::create()
    ->data($url)
    ->size(300)        // QR code size
    ->margin(10)       // Margin size
    ->build();
```

## 🔧 Manual Installation

If you prefer not to use the automated scripts:

### 1. Install GD Extension
```bash
apt-get update
apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install -j$(nproc) gd
```

### 2. Copy Plugin Files
```bash
mkdir -p /var/www/html/user/plugins/qr-local
cp plugin.php /var/www/html/user/plugins/qr-local/
cp composer.json /var/www/html/user/plugins/qr-local/
```

### 3. Install Dependencies
```bash
cd /var/www/html/user/plugins/qr-local
composer install
```

### 4. Set Permissions
```bash
chown -R www-data:www-data /var/www/html/user/plugins/qr-local
```

## 📁 Project Structure

```
yourls-qr-local/
├── README.md               # Documentation
├── LICENSE                 # MIT License
├── composer.json           # PHP dependencies
├── plugin.php             # Main plugin file
├── install.sh             # Plugin installation script
└── setup-dependencies.sh  # System dependencies setup
```

## 🐛 Troubleshooting

### QR Code Not Generated
1. Check GD extension: `php -m | grep -i gd`
2. Verify plugin is activated in YOURLS admin panel
3. Check composer dependencies: `ls /var/www/html/user/plugins/qr-local/vendor`
4. Check logs: `/var/www/html/logs/qr_plugin.log`

### Permission Errors
```bash
chown -R www-data:www-data /var/www/html/user/plugins/qr-local
```

### 404 Not Found
- Ensure the short URL exists in YOURLS
- Check if the plugin is properly activated
- Verify the `.qr` extension is being handled by the plugin

## 🙏 Acknowledgments

This plugin uses the excellent [endroid/qr-code](https://github.com/endroid/qr-code) library for QR code generation.

## 📄 License

MIT License

**Note**: This project is licensed under MIT, but it depends on the [endroid/qr-code](https://github.com/endroid/qr-code) library which has its own license terms.

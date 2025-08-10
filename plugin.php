<?php
/*
Plugin Name: Local QR Generator
Description: Generate QR image locally using endroid/qr-code library
Version: 1.0
Author: YOURLS QR Local Plugin
Author URI: https://github.com/gjeon03/yourls-qr-local
License: MIT
License URI: https://opensource.org/licenses/MIT

This plugin uses the endroid/qr-code library (https://github.com/endroid/qr-code)
*/

$log_file = '/var/www/html/logs/qr_plugin.log';

file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Plugin loaded, checking REQUEST_URI immediately\n", FILE_APPEND);

// Check REQUEST_URI immediately when plugin is loaded
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] REQUEST_URI at load: $request_uri\n", FILE_APPEND);

if (substr($request_uri, -3) === '.qr') {
    file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] QR request detected immediately: $request_uri\n", FILE_APPEND);
    
    $keyword = basename($request_uri, '.qr');
    $keyword = ltrim($keyword, '/');
    
    file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Extracted keyword: $keyword\n", FILE_APPEND);
    
    // Load required files to use YOURLS functions
    if (!function_exists('yourls_get_keyword_longurl')) {
        require_once '/var/www/html/includes/functions.php';
    }
    
    $url = yourls_get_keyword_longurl($keyword);
    
    if (!$url) {
        file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] URL not found for: $keyword\n", FILE_APPEND);
        http_response_code(404);
        die('Short URL not found');
    }
    
    file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Found URL: $url\n", FILE_APPEND);
    
    $autoload_path = '/var/www/html/user/plugins/qr-local/vendor/autoload.php';
    require_once $autoload_path;
    
    try {
        file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Generating QR\n", FILE_APPEND);
        
        $qr = \Endroid\QrCode\Builder\Builder::create()
            ->data($url)
            ->size(300)
            ->margin(10)
            ->build();
        
        file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] QR generated successfully\n", FILE_APPEND);
        
        header('Content-Type: ' . $qr->getMimeType());
        echo $qr->getString();
        exit;
        
    } catch (Exception $e) {
        file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n", FILE_APPEND);
        http_response_code(500);
        die('Error: ' . $e->getMessage());
    }
}

// Register with multiple hooks for debugging
$hooks_to_try = ['init', 'plugins_loaded', 'pre_html_head', 'html_head', 'pre_redirect_shorturl'];

foreach ($hooks_to_try as $hook) {
    yourls_add_action($hook, function() use ($log_file, $hook) {
        file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Hook '$hook' called\n", FILE_APPEND);
    });
}

file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Action registered for multiple hooks\n", FILE_APPEND);

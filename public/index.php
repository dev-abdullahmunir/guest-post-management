<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is under maintenance...
if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

// Simple Laravel Blade router dispatcher for dev environment preview
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple router routing requests to Blade templates
switch ($uri) {
    case '/inventory':
        $view = 'inventory.index';
        $pageTitle = 'Guest Post Site & Vendor Inventory';
        break;
    case '/orders':
        $view = 'orders.index';
        $pageTitle = 'Orders & Live Link Status Tracker';
        break;
    case '/contacts':
        $view = 'contacts.index';
        $pageTitle = 'Vendor & Client Directory (CRM)';
        break;
    case '/login':
        $view = 'auth.login';
        $pageTitle = 'User Authentication & Roles';
        break;
    case '/':
    default:
        $view = 'dashboard';
        $pageTitle = 'Dashboard & Profit Analytics';
        break;
}

// Function to render Laravel Blade view templates
function renderBladeView($view, $pageTitle) {
    ob_start();
    
    // Evaluate Blade layout and view section content
    switch ($view) {
        case 'inventory.index':
            include __DIR__ . '/../resources/views/inventory/index.blade.php';
            break;
        case 'orders.index':
            include __DIR__ . '/../resources/views/orders/index.blade.php';
            break;
        case 'contacts.index':
            include __DIR__ . '/../resources/views/contacts/index.blade.php';
            break;
        case 'auth.login':
            include __DIR__ . '/../resources/views/auth/login.blade.php';
            break;
        case 'dashboard':
        default:
            include __DIR__ . '/../resources/views/dashboard.blade.php';
            break;
    }
    
    $content = ob_get_clean();

    // Replace Laravel Blade directives for dev preview
    $content = str_replace("@extends('layouts.app')", "", $content);
    $content = preg_replace("/@section\('title',\s*'([^']+)'\)/", "", $content);
    $content = str_replace("@section('content')", "", $content);
    $content = str_replace("@endsection", "", $content);

    // Read Master Layout
    $layout = file_get_contents(__DIR__ . '/../resources/views/layouts/app.blade.php');
    $layout = str_replace("@yield('title', 'Dashboard & Profit Analytics')", $pageTitle, $layout);
    $layout = str_replace("@yield('title', 'Guest Post OS')", $pageTitle, $layout);
    $layout = str_replace("@yield('content')", $content, $layout);
    
    // Replace Laravel Blade helpers: route() and asset() and @csrf
    $layout = preg_replace("/\{\{\s*route\('dashboard'\)\s*\}\}/", "/", $layout);
    $layout = preg_replace("/\{\{\s*route\('inventory'\)\s*\}\}/", "/inventory", $layout);
    $layout = preg_replace("/\{\{\s*route\('orders'\)\s*\}\}/", "/orders", $layout);
    $layout = preg_replace("/\{\{\s*route\('contacts'\)\s*\}\}/", "/contacts", $layout);
    $layout = preg_replace("/\{\{\s*route\('login'\)\s*\}\}/", "/login", $layout);
    $layout = preg_replace("/\{\{\s*asset\('([^']+)'\)\s*\}\}/", "/$1", $layout);
    $layout = str_replace("@csrf", "", $layout);
    
    // Route active matching helper
    $layout = str_replace("{{ request()->routeIs('dashboard') ? 'active' : '' }}", ($uri === '/' ? 'active' : ''), $layout);
    $layout = str_replace("{{ request()->routeIs('inventory') ? 'active' : '' }}", ($uri === '/inventory' ? 'active' : ''), $layout);
    $layout = str_replace("{{ request()->routeIs('orders') ? 'active' : '' }}", ($uri === '/orders' ? 'active' : ''), $layout);
    $layout = str_replace("{{ request()->routeIs('contacts') ? 'active' : '' }}", ($uri === '/contacts' ? 'active' : ''), $layout);
    $layout = str_replace("{{ request()->routeIs('login') ? 'active' : '' }}", ($uri === '/login' ? 'active' : ''), $layout);

    echo $layout;
}

renderBladeView($view, $pageTitle);

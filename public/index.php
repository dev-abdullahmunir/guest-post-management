<?php

define('LARAVEL_START', microtime(true));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Capsule\Manager as Capsule;

// Modular Controllers & Models
use Modules\AdminManagement\Http\Controllers\DashboardController;
use Modules\AdminManagement\Http\Controllers\PaymentController;
use Modules\AdminManagement\Http\Controllers\ExpenseController;
use Modules\AdminManagement\Http\Controllers\SettingController;
use Modules\AdminManagement\Http\Controllers\AuthController;
use Modules\AdminManagement\Models\User;
use Modules\AdminManagement\Models\PersonalAccessToken;
use Modules\ClientManagement\Http\Controllers\ClientController;
use Modules\ClientManagement\Http\Controllers\OrderController;
use Modules\ClientManagement\Http\Controllers\OrderItemController;
use Modules\SellerManagement\Http\Controllers\SellerController;
use Modules\SellerManagement\Http\Controllers\WebsiteController;

// Register Global Laravel View Helpers
if (!function_exists('route')) {
    function route($name, $parameters = [], $absolute = true) {
        $routes = [
            'dashboard' => '/',
            'clients' => '/clients',
            'contacts' => '/clients',
            'sellers' => '/sellers',
            'vendors' => '/sellers',
            'websites' => '/websites',
            'inventory' => '/websites',
            'orders' => '/orders',
            'payments' => '/payments',
            'transactions' => '/payments',
            'expenses' => '/expenses',
            'settings' => '/settings',
            'login' => '/login',
            'logout' => '/logout',
        ];
        return $routes[$name] ?? '/' . $name;
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        global $authenticatedUser;
        return $authenticatedUser ?? null;
    }
}

if (!function_exists('auth_check')) {
    function auth_check() {
        return auth_user() !== null;
    }
}

if (!function_exists('asset')) {
    function asset($path, $secure = null) {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('request')) {
    function request() {
        return new class {
            public function routeIs($pattern) {
                $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
                $map = [
                    'dashboard' => '/',
                    'clients' => '/clients',
                    'contacts' => '/clients',
                    'sellers' => '/sellers',
                    'vendors' => '/sellers',
                    'websites' => '/websites',
                    'inventory' => '/websites',
                    'orders' => '/orders',
                    'payments' => '/payments',
                    'transactions' => '/payments',
                    'expenses' => '/expenses',
                    'settings' => '/settings',
                ];
                return ($map[$pattern] ?? '') === $uri || 
                       ($pattern === 'clients' && $uri === '/contacts') ||
                       ($pattern === 'sellers' && $uri === '/vendors') ||
                       ($pattern === 'websites' && $uri === '/inventory') ||
                       ($pattern === 'payments' && $uri === '/transactions');
            }
        };
    }
}

// 1. Setup Eloquent Capsule Connection (for Models)
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'database' => getenv('DB_DATABASE') ?: 'guest_post_db',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// 2. Setup Laravel Blade Engine Container with Modular View Paths
$container = new Container();
Container::setInstance($container);
Facade::setFacadeApplication($container);

$filesystem = new Filesystem();
$cachePath = __DIR__ . '/../storage/framework/views';
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0777, true);
}

$bladeCompiler = new BladeCompiler($filesystem, $cachePath);

$engineResolver = new EngineResolver();
$engineResolver->register('blade', function () use ($bladeCompiler) {
    return new CompilerEngine($bladeCompiler);
});

// Modular View Discovery Roots (Modules own their views)
$viewPaths = [
    __DIR__ . '/../Modules/AdminManagement/Resources/views',
    __DIR__ . '/../Modules/ClientManagement/Resources/views',
    __DIR__ . '/../Modules/SellerManagement/Resources/views',
    __DIR__ . '/../Modules/Shared/Resources/views',
    __DIR__ . '/../resources/views',
];
$finder = new FileViewFinder($filesystem, $viewPaths);
$events = new Dispatcher($container);

$viewFactory = new Factory($engineResolver, $finder, $events);
$viewFactory->setContainer($container);
$container->instance('view', $viewFactory);

// 3. User Authentication Guard & Session / Sanctum Bearer Resolver
$authenticatedUser = null;

// A. Session Check
if (!empty($_SESSION['user_id'])) {
    $authenticatedUser = User::find($_SESSION['user_id']);
}

// B. Remember Cookie Check
if (!$authenticatedUser && !empty($_COOKIE['guestpost_auth'])) {
    $tokenInstance = PersonalAccessToken::findToken($_COOKIE['guestpost_auth']);
    if ($tokenInstance && !$tokenInstance->isExpired()) {
        $tokenInstance->update(['last_used_at' => date('Y-m-d H:i:s')]);
        $authenticatedUser = $tokenInstance->tokenable;
        if ($authenticatedUser) {
            $_SESSION['user_id'] = $authenticatedUser->id;
            $_SESSION['user_name'] = $authenticatedUser->name;
            $_SESSION['user_email'] = $authenticatedUser->email;
            $_SESSION['user_role'] = $authenticatedUser->role;
            $_SESSION['auth_token'] = $_COOKIE['guestpost_auth'];
        }
    }
}

// C. Sanctum Bearer Token Check (Authorization: Bearer <token>)
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
if (!$authenticatedUser && !empty($authHeader)) {
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $bearerToken = trim($matches[1]);
        $tokenInstance = PersonalAccessToken::findToken($bearerToken);
        if ($tokenInstance && !$tokenInstance->isExpired()) {
            $tokenInstance->update(['last_used_at' => date('Y-m-d H:i:s')]);
            $authenticatedUser = $tokenInstance->tokenable;
        }
    }
}

// Share authenticated user globally with all Blade views & layout
$viewFactory->share('currentUser', $authenticatedUser);

// 4. Router & Modular Dispatcher
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$rawInput = file_get_contents('php://input');
$jsonBody = json_decode($rawInput, true);
$body = !empty($_POST) ? $_POST : ($jsonBody ?? []);

// --- OPEN AUTHENTICATION ROUTES ---

// 1. Web Login Page (GET /login)
if ($uri === '/login' && $method === 'GET') {
    if ($authenticatedUser) {
        header('Location: /');
        exit;
    }
    $view = (new AuthController())->showLoginForm($viewFactory);
    echo $view->render();
    exit;
}

// 2. Web Login Form Submission (POST /login)
if ($uri === '/login' && $method === 'POST') {
    (new AuthController())->login($body, false);
    exit;
}

// 3. API Sanctum Login (POST /api/login)
if ($uri === '/api/login' && $method === 'POST') {
    header('Content-Type: application/json');
    echo json_encode((new AuthController())->login($body, true));
    exit;
}

// 4. Web Logout (GET or POST /logout)
if ($uri === '/logout') {
    (new AuthController())->logout(false);
    exit;
}

// 5. API Logout (POST /api/logout)
if ($uri === '/api/logout') {
    header('Content-Type: application/json');
    echo json_encode((new AuthController())->logout(true));
    exit;
}

// 6. Current User Info (GET /api/user or /api/me)
if ($uri === '/api/user' || $uri === '/api/me') {
    header('Content-Type: application/json');
    echo json_encode((new AuthController())->me($authenticatedUser));
    exit;
}

// --- STRICT AUTHENTICATION GUARD (Protects all dashboard & API endpoints) ---
if (!$authenticatedUser) {
    if (str_starts_with($uri, '/api/')) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthenticated. Valid Sanctum Bearer token or active session required.',
        ]);
        exit;
    }
    header('Location: /login');
    exit;
}

// --- PROTECTED API DISPATCHER TO SPECIFIC MODULE CONTROLLERS ---

// A. Client Management API
if (preg_match('#^/api/clients(/(\d+)(/(delete|update))?)?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $action = $matches[4] ?? null;
    $controller = new ClientController();

    try {
        if ($method === 'POST' && !$id) {
            echo json_encode($controller->store($body));
            exit;
        }
        if ($id && ($action === 'delete' || $method === 'DELETE')) {
            echo json_encode($controller->destroy($id));
            exit;
        }
        if ($id && ($action === 'update' || $method === 'PUT' || ($method === 'POST' && !$action))) {
            echo json_encode($controller->update($id, $body));
            exit;
        }
        if ($id && $method === 'GET') {
            echo json_encode($controller->show($id));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// B. Seller Management API
if (preg_match('#^/api/sellers(/(\d+)(/(delete|update))?)?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $action = $matches[4] ?? null;
    $controller = new SellerController();

    try {
        if ($method === 'POST' && !$id) {
            echo json_encode($controller->store($body));
            exit;
        }
        if ($id && ($action === 'delete' || $method === 'DELETE')) {
            echo json_encode($controller->destroy($id));
            exit;
        }
        if ($id && ($action === 'update' || $method === 'PUT' || ($method === 'POST' && !$action))) {
            echo json_encode($controller->update($id, $body));
            exit;
        }
        if ($id && $method === 'GET') {
            echo json_encode($controller->show($id));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// C. Website Inventory API
if ($uri === '/api/websites/bulk-import' && $method === 'POST') {
    header('Content-Type: application/json');
    $controller = new WebsiteController();
    echo json_encode($controller->bulkImport($body));
    exit;
}

if ($uri === '/api/websites/sample-template' && $method === 'GET') {
    $controller = new WebsiteController();
    $controller->sampleTemplate();
    exit;
}

if (preg_match('#^/api/websites(/(\d+)(/(delete|update))?)?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $action = $matches[4] ?? null;
    $controller = new WebsiteController();

    try {
        if ($method === 'POST' && !$id) {
            echo json_encode($controller->store($body));
            exit;
        }
        if ($id && ($action === 'delete' || $method === 'DELETE')) {
            echo json_encode($controller->destroy($id));
            exit;
        }
        if ($id && ($action === 'update' || $method === 'PUT' || ($method === 'POST' && !$action))) {
            echo json_encode($controller->update($id, $body));
            exit;
        }
        if ($id && $method === 'GET') {
            echo json_encode($controller->show($id));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// D. Orders API
if (preg_match('#^/api/orders(/(\d+)(/(delete|update))?)?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $action = $matches[4] ?? null;
    $controller = new OrderController();

    try {
        if ($method === 'POST' && !$id) {
            echo json_encode($controller->store($body));
            exit;
        }
        if ($id && ($action === 'delete' || $method === 'DELETE')) {
            echo json_encode($controller->destroy($id));
            exit;
        }
        if ($id && ($action === 'update' || $method === 'PUT' || ($method === 'POST' && !$action))) {
            echo json_encode($controller->update($id, $body));
            exit;
        }
        if ($id && $method === 'GET') {
            echo json_encode($controller->show($id));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// E. Order Items API
if (preg_match('#^/api/orders/(\d+)/items$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $orderId = (int)$matches[1];
    $controller = new OrderItemController();

    try {
        if ($method === 'GET') {
            $order = \Modules\Shared\Models\Order::findOrFail($orderId);
            $items = $order->items()->with(['website', 'vendor'])->latest()->get();
            echo json_encode(['status' => 'success', 'order' => $order, 'data' => $items]);
            exit;
        }
        if ($method === 'POST') {
            $order = \Modules\Shared\Models\Order::findOrFail($orderId);
            $body['order_id'] = $order->id;
            $item = \Modules\Shared\Models\OrderItem::create($body);
            $order->recalculateTotals();
            echo json_encode([
                'status' => 'success',
                'message' => 'Guest post added to order successfully!',
                'data' => $item->load(['website', 'vendor']),
                'order' => $order->fresh(),
            ]);
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

if (preg_match('#^/api/order-items/(\d+)(/(delete|update))?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = (int)$matches[1];
    $action = $matches[3] ?? null;

    try {
        $item = \Modules\Shared\Models\OrderItem::findOrFail($id);
        $order = $item->order;

        if ($action === 'delete' || $method === 'DELETE') {
            $item->delete();
            if ($order) { $order->recalculateTotals(); }
            echo json_encode(['status' => 'success', 'message' => 'Guest post item removed successfully!']);
            exit;
        }
        if ($action === 'update' || $method === 'POST' || $method === 'PUT') {
            $item->update($body);
            if ($order) { $order->recalculateTotals(); }
            echo json_encode(['status' => 'success', 'message' => 'Guest post item updated successfully!', 'data' => $item]);
            exit;
        }
        if ($method === 'GET') {
            echo json_encode(['status' => 'success', 'data' => $item->load(['website', 'vendor'])]);
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// F. Payments API
if (preg_match('#^/api/payments(/(\d+)(/(delete|update))?)?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $action = $matches[4] ?? null;
    $controller = new PaymentController();

    try {
        if ($method === 'POST' && !$id) {
            echo json_encode($controller->store($body));
            exit;
        }
        if ($id && ($action === 'delete' || $method === 'DELETE')) {
            echo json_encode($controller->destroy($id));
            exit;
        }
        if ($id && ($action === 'update' || $method === 'PUT' || ($method === 'POST' && !$action))) {
            echo json_encode($controller->update($id, $body));
            exit;
        }
        if ($id && $method === 'GET') {
            echo json_encode($controller->show($id));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// G. Expenses API
if (preg_match('#^/api/expenses(/(\d+)(/(delete|update))?)?$#', $uri, $matches)) {
    header('Content-Type: application/json');
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $action = $matches[4] ?? null;
    $controller = new ExpenseController();

    try {
        if ($method === 'POST' && !$id) {
            echo json_encode($controller->store($body));
            exit;
        }
        if ($id && ($action === 'delete' || $method === 'DELETE')) {
            echo json_encode($controller->destroy($id));
            exit;
        }
        if ($id && ($action === 'update' || $method === 'PUT' || ($method === 'POST' && !$action))) {
            echo json_encode($controller->update($id, $body));
            exit;
        }
        if ($id && $method === 'GET') {
            echo json_encode($controller->show($id));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// H. Settings API (White-Label Branding & System Configurations)
if (preg_match('#^/api/settings#', $uri)) {
    header('Content-Type: application/json');
    $controller = new SettingController();

    try {
        if ($method === 'POST') {
            echo json_encode($controller->update($body));
            exit;
        }
        if ($method === 'GET') {
            echo json_encode($controller->index(new \Illuminate\Http\Request()));
            exit;
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// --- WEB ROUTING TO SPECIFIC MODULE CONTROLLER VIEWS ---

switch ($uri) {
    case '/settings':
        $view = (new SettingController())->renderView($viewFactory);
        break;

    case '/expenses':
        $view = (new ExpenseController())->renderView($viewFactory);
        break;

    case '/payments':
    case '/transactions':
        $view = (new PaymentController())->renderView($viewFactory);
        break;

    case '/orders':
        $view = (new OrderController())->renderView($viewFactory);
        break;

    case '/websites':
    case '/inventory':
        $view = (new WebsiteController())->renderView($viewFactory);
        break;

    case '/sellers':
    case '/vendors':
        $view = (new SellerController())->renderView($viewFactory);
        break;

    case '/clients':
    case '/contacts':
        $view = (new ClientController())->renderView($viewFactory);
        break;

    case '/':
    default:
        $view = (new DashboardController())->renderView($viewFactory);
        break;
}

echo $view->render();

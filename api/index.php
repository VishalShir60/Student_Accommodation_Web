<?php
// api/index.php
// Vercel Serverless Entrypoint & Front Controller

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = trim($uri, '/');

// Direct API endpoint routing if matched
if (str_starts_with($path, 'api/')) {
    $api_file = __DIR__ . '/' . substr($path, 4);
    if (file_exists($api_file)) {
        require $api_file;
        exit;
    }
}

// Web Page Routing
switch ($path) {
    case 'login':
    case 'login.php':
        require __DIR__ . '/../login.php';
        break;

    case 'signup':
    case 'signup.php':
        require __DIR__ . '/../signup.php';
        break;

    case 'logout':
    case 'logout.php':
        require __DIR__ . '/../logout.php';
        break;

    case 'property-detail':
    case 'property-detail.php':
        require __DIR__ . '/../property-detail.php';
        break;

    case 'shortlist':
    case 'shortlist.php':
        require __DIR__ . '/../shortlist.php';
        break;

    case '':
    case 'index':
    case 'index.php':
    default:
        require __DIR__ . '/../index.php';
        break;
}

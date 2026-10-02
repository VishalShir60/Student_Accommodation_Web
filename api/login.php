<?php
// api/login.php
// REST API endpoint for user login authentication

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = isset($input['email']) ? trim($input['email']) : (isset($_POST['email']) ? trim($_POST['email']) : '');
$password = isset($input['password']) ? trim($input['password']) : (isset($_POST['password']) ? trim($_POST['password']) : '');

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please enter both email and password.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = :email");
    $stmt->execute([':email' => strtolower($email)]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
        exit;
    }

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];

    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful!',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Login failed: ' . $e->getMessage()]);
}

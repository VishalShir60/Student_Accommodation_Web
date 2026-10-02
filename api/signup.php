<?php
// api/signup.php
// REST API endpoint for user registration

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$input = json_decode(file_get_contents('php://input'), true);

$name = isset($input['name']) ? trim($input['name']) : (isset($_POST['name']) ? trim($_POST['name']) : '');
$email = isset($input['email']) ? trim($input['email']) : (isset($_POST['email']) ? trim($_POST['email']) : '');
$password = isset($input['password']) ? trim($input['password']) : (isset($_POST['password']) ? trim($_POST['password']) : '');
$phone = isset($input['phone']) ? trim($input['phone']) : (isset($_POST['phone']) ? trim($_POST['phone']) : '');

if (empty($name) || empty($email) || empty($password) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'All fields (name, email, password, phone) are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid email address.']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
    exit;
}

try {
    // Check existing email
    $check_stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $check_stmt->execute([':email' => strtolower($email)]);
    if ($check_stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'An account with this email address already exists.']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (:name, :email, :password, :phone)");
    $insert_stmt->execute([
        ':name' => $name,
        ':email' => strtolower($email),
        ':password' => $hashed_password,
        ':phone' => $phone
    ]);

    $user_id = $pdo->lastInsertId();

    // Auto log in after registration
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = strtolower($email);

    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful!',
        'user' => [
            'id' => $user_id,
            'name' => $name,
            'email' => strtolower($email)
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $e->getMessage()]);
}

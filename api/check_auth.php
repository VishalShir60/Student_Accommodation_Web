<?php
// api/check_auth.php
// REST API endpoint to check authentication session status

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    // Get shortlist count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM interested_users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $shortlist_count = intval($stmt->fetchColumn());

    echo json_encode([
        'authenticated' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email']
        ],
        'shortlist_count' => $shortlist_count
    ]);
} else {
    echo json_encode([
        'authenticated' => false,
        'user' => null,
        'shortlist_count' => 0
    ]);
}

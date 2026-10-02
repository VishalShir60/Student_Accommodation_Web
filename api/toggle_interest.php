<?php
// api/toggle_interest.php
// REST API endpoint to dynamically add or remove property from user's shortlist (AJAX)

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'unauthenticated',
        'message' => 'Please login to add properties to your shortlist.'
    ]);
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Get JSON or POST body
$input = json_decode(file_get_contents('php://input'), true);
$property_id = 0;

if (isset($input['property_id'])) {
    $property_id = intval($input['property_id']);
} elseif (isset($_POST['property_id'])) {
    $property_id = intval($_POST['property_id']);
}

if ($property_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid Property ID']);
    exit;
}

try {
    // Check if property exists
    $prop_check = $pdo->prepare("SELECT id FROM properties WHERE id = :id");
    $prop_check->execute([':id' => $property_id]);
    if (!$prop_check->fetch()) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Property does not exist.']);
        exit;
    }

    // Check existing shortlist status
    $check_stmt = $pdo->prepare("SELECT 1 FROM interested_users WHERE user_id = :user_id AND property_id = :property_id");
    $check_stmt->execute([':user_id' => $user_id, ':property_id' => $property_id]);
    $already_interested = $check_stmt->fetchColumn();

    $is_interested = false;
    $action = '';

    if ($already_interested) {
        // Remove interest
        $del_stmt = $pdo->prepare("DELETE FROM interested_users WHERE user_id = :user_id AND property_id = :property_id");
        $del_stmt->execute([':user_id' => $user_id, ':property_id' => $property_id]);
        $is_interested = false;
        $action = 'removed';
    } else {
        // Add interest
        $add_stmt = $pdo->prepare("INSERT INTO interested_users (user_id, property_id) VALUES (:user_id, :property_id)");
        $add_stmt->execute([':user_id' => $user_id, ':property_id' => $property_id]);
        $is_interested = true;
        $action = 'added';
    }

    // Get updated total interest count
    $cnt_stmt = $pdo->prepare("SELECT COUNT(*) FROM interested_users WHERE property_id = :property_id");
    $cnt_stmt->execute([':property_id' => $property_id]);
    $new_count = intval($cnt_stmt->fetchColumn());

    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'is_interested' => $is_interested,
        'interest_count' => $new_count,
        'message' => $is_interested ? 'Property added to your shortlist!' : 'Property removed from your shortlist.'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update shortlist: ' . $e->getMessage()
    ]);
}

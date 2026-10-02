<?php
// api/get_interested.php
// REST API endpoint to fetch properties shortlisted/interested by the current user

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'unauthenticated', 'message' => 'Please login to view shortlisted properties.']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

try {
    $sql = "
        SELECT 
            p.*,
            (SELECT COUNT(*) FROM interested_users iu WHERE iu.property_id = p.id) AS interest_count,
            1 AS is_interested
        FROM properties p
        INNER JOIN interested_users iu ON p.id = iu.property_id
        WHERE iu.user_id = :user_id
        ORDER BY iu.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $properties = $stmt->fetchAll();

    foreach ($properties as &$prop) {
        $prop['price'] = floatval($prop['price']);
        $prop['rating'] = floatval($prop['rating']);
        $prop['interest_count'] = intval($prop['interest_count']);
        $prop['is_interested'] = true;
    }

    echo json_encode([
        'status' => 'success',
        'count' => count($properties),
        'data' => $properties
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch shortlist: ' . $e->getMessage()]);
}

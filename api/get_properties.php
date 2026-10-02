<?php
// api/get_properties.php
// REST API endpoint to fetch properties based on filter criteria

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? floatval($_GET['max_price']) : null;
$gender = isset($_GET['gender']) ? trim($_GET['gender']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

try {
    $where_conditions = ["1=1"];
    $params = [];

    if (!empty($city) && strtolower($city) !== 'all') {
        $where_conditions[] = "LOWER(p.city) = LOWER(:city)";
        $params[':city'] = $city;
    }

    if ($max_price !== null && $max_price > 0) {
        $where_conditions[] = "p.price <= :max_price";
        $params[':max_price'] = $max_price;
    }

    if (!empty($gender) && strtolower($gender) !== 'all') {
        $where_conditions[] = "LOWER(p.gender) = LOWER(:gender)";
        $params[':gender'] = $gender;
    }

    if (!empty($search)) {
        $where_conditions[] = "(LOWER(p.name) LIKE :search OR LOWER(p.address) LIKE :search OR LOWER(p.city) LIKE :search)";
        $params[':search'] = '%' . strtolower($search) . '%';
    }

    $where_sql = implode(' AND ', $where_conditions);

    $sql = "
        SELECT 
            p.*,
            (SELECT COUNT(*) FROM interested_users iu WHERE iu.property_id = p.id) AS interest_count,
            EXISTS(SELECT 1 FROM interested_users iu_user WHERE iu_user.property_id = p.id AND iu_user.user_id = :current_user_id) AS is_interested
        FROM properties p
        WHERE {$where_sql}
        ORDER BY p.rating DESC, p.id DESC
    ";

    $params[':current_user_id'] = $current_user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $properties = $stmt->fetchAll();

    // Format fields
    foreach ($properties as &$prop) {
        $prop['price'] = floatval($prop['price']);
        $prop['rating'] = floatval($prop['rating']);
        $prop['interest_count'] = intval($prop['interest_count']);
        $prop['is_interested'] = boolval($prop['is_interested']);
    }

    echo json_encode([
        'status' => 'success',
        'count' => count($properties),
        'data' => $properties
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch properties: ' . $e->getMessage()
    ]);
}

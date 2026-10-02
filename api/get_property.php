<?php
// api/get_property.php
// REST API endpoint to fetch detailed information for a single property

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($property_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid Property ID']);
    exit;
}

try {
    // 1. Fetch main property details
    $sql = "
        SELECT 
            p.*,
            (SELECT COUNT(*) FROM interested_users iu WHERE iu.property_id = p.id) AS interest_count,
            EXISTS(SELECT 1 FROM interested_users iu_user WHERE iu_user.property_id = p.id AND iu_user.user_id = :current_user_id) AS is_interested
        FROM properties p
        WHERE p.id = :property_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':property_id' => $property_id, ':current_user_id' => $current_user_id]);
    $property = $stmt->fetch();

    if (!$property) {
        http_response_code(444);
        echo json_encode(['status' => 'error', 'message' => 'Property not found']);
        exit;
    }

    $property['price'] = floatval($property['price']);
    $property['rating'] = floatval($property['rating']);
    $property['interest_count'] = intval($property['interest_count']);
    $property['is_interested'] = boolval($property['is_interested']);

    // 2. Fetch gallery images
    $img_stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE property_id = :property_id");
    $img_stmt->execute([':property_id' => $property_id]);
    $gallery = $img_stmt->fetchAll(PDO::FETCH_COLUMN);

    // If gallery empty, use main_image
    if (empty($gallery)) {
        $gallery = [$property['main_image']];
    }
    $property['gallery'] = $gallery;

    // 3. Fetch amenities
    $amenity_sql = "
        SELECT a.id, a.name, a.icon 
        FROM amenities a
        INNER JOIN property_amenities pa ON a.id = pa.amenity_id
        WHERE pa.property_id = :property_id
    ";
    $am_stmt = $pdo->prepare($amenity_sql);
    $am_stmt->execute([':property_id' => $property_id]);
    $property['amenities'] = $am_stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'data' => $property
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch property details: ' . $e->getMessage()
    ]);
}

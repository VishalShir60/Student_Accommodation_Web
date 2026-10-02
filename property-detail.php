<?php
// property-detail.php
// Property Details Page with Gallery, Amenities, Description, and Interest Toggle

require_once __DIR__ . '/config/db.php';

$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($property_id <= 0) {
    header("Location: index.php");
    exit;
}

// Fetch property details
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
    header("Location: index.php");
    exit;
}

// Fetch property images for gallery
$img_stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE property_id = :property_id");
$img_stmt->execute([':property_id' => $property_id]);
$gallery = $img_stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($gallery)) {
    $gallery = [$property['main_image']];
}

// Fetch amenities
$am_sql = "
    SELECT a.name, a.icon 
    FROM amenities a
    INNER JOIN property_amenities pa ON a.id = pa.amenity_id
    WHERE pa.property_id = :property_id
";
$am_stmt = $pdo->prepare($am_sql);
$am_stmt->execute([':property_id' => $property_id]);
$amenities = $am_stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<main class="container py-4">
    <!-- Breadcrumb Nav -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?city=<?= urlencode($property['city']) ?>" class="text-decoration-none"><?= htmlspecialchars($property['city']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($property['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Column: Gallery & Info -->
        <div class="col-lg-8">
            <!-- Property Title Header -->
            <div class="mb-4">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="gender-badge position-relative top-0 left-0 <?= htmlspecialchars($property['gender']) ?>">
                        <i class="fa-solid <?= $property['gender'] === 'Female' ? 'fa-venus' : ($property['gender'] === 'Male' ? 'fa-mars' : 'fa-genderless') ?> me-1"></i>
                        <?= htmlspecialchars($property['gender']) ?> PG
                    </span>
                    <span class="badge bg-warning text-dark font-semibold px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-star me-1"></i><?= number_format($property['rating'], 1) ?> Rating
                    </span>
                </div>

                <h2 class="fw-bold display-6 mb-2"><?= htmlspecialchars($property['name']) ?></h2>
                <p class="text-muted fs-5 mb-0">
                    <i class="fa-solid fa-location-dot text-danger me-2"></i>
                    <?= htmlspecialchars($property['address']) ?>
                </p>
            </div>

            <!-- Image Gallery Viewer -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <img id="main-gallery-viewer" src="<?= htmlspecialchars($gallery[0]) ?>" class="main-gallery-img" alt="<?= htmlspecialchars($property['name']) ?>">
                
                <?php if (count($gallery) > 1): ?>
                    <div class="p-3 bg-light border-top">
                        <div class="row g-2">
                            <?php foreach ($gallery as $index => $img_url): ?>
                                <div class="col-3 col-sm-2">
                                    <img src="<?= htmlspecialchars($img_url) ?>" 
                                         class="thumb-img <?= $index === 0 ? 'active' : '' ?>" 
                                         onclick="changeGalleryImage('<?= htmlspecialchars($img_url) ?>', this)"
                                         alt="Thumbnail <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Description Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3">
                    <i class="fa-solid fa-align-left text-primary me-2"></i> About Property
                </h4>
                <p class="text-secondary lh-lg mb-0">
                    <?= nl2br(htmlspecialchars($property['description'])) ?>
                </p>
            </div>

            <!-- Amenities Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3">
                    <i class="fa-solid fa-list-check text-primary me-2"></i> Included Amenities
                </h4>
                
                <?php if (!empty($amenities)): ?>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($amenities as $amenity): ?>
                            <div class="amenity-pill">
                                <i class="fa-solid <?= htmlspecialchars($amenity['icon']) ?>"></i>
                                <span><?= htmlspecialchars($amenity['name']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Basic amenities (Wi-Fi, Water, Housekeeping) included.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Column: Pricing & Action Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 100px;">
                <div class="border-bottom pb-3 mb-3">
                    <span class="text-muted small fw-semibold">Monthly Rent</span>
                    <div class="d-flex align-items-baseline gap-1 mt-1">
                        <span class="display-6 fw-extrabold text-primary">₹<?= number_format($property['price'], 0, '.', ',') ?></span>
                        <span class="text-muted fw-semibold">/ month</span>
                    </div>
                    <small class="text-success"><i class="fa-solid fa-circle-check me-1"></i> No Brokerage Fee</small>
                </div>

                <!-- Interest Counter Badge -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 p-3 bg-light rounded-3 text-secondary">
                        <i class="fa-solid fa-users text-primary fs-5"></i>
                        <span id="detail-interest-count" class="fw-bold">
                            <?= intval($property['interest_count']) ?> Interested <?= intval($property['interest_count']) === 1 ? 'Student' : 'Students' ?>
                        </span>
                    </div>
                </div>

                <!-- Dynamic AJAX Shortlist / Mark Interest Button -->
                <button id="btn-toggle-interest-detail" 
                        class="btn <?= $property['is_interested'] ? 'btn-danger' : 'btn-outline-danger' ?> btn-lg w-100 rounded-pill fw-bold mb-3"
                        onclick="toggleInterest(<?= $property['id'] ?>, this)">
                    <?php if ($property['is_interested']): ?>
                        <i class="fa-solid fa-heart me-2"></i> Shortlisted
                    <?php else: ?>
                        <i class="fa-regular fa-heart me-2"></i> Mark as Interested
                    <?php endif; ?>
                </button>

                <div class="text-center">
                    <small class="text-muted">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Verified PG Listing
                    </small>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function changeGalleryImage(url, elem) {
    const mainImg = document.getElementById('main-gallery-viewer');
    if (mainImg) {
        mainImg.src = url;
    }
    document.querySelectorAll('.thumb-img').forEach(t => t.classList.remove('active'));
    if (elem) elem.classList.add('active');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

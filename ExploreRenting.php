<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function format_inr_price($price) {
    if (!is_numeric($price)) return '';
    $price = (float)$price;
    if ($price >= 10000000) return '₹' . number_format($price / 10000000, 2) . ' Cr';
    return '₹' . number_format($price / 100000, 1) . ' Lac';
}

function format_inr_per_sqft($price, $area) {
    if (!is_numeric($price) || !is_numeric($area) || (float)$area <= 0) return '';
    return '₹' . number_format(((float)$price / (float)$area), 0) . ' / sqft';
}

/* DATABASE CONNECTION */
$conn = new mysqli("127.0.0.1", "root", "", "searchbardatabase", 3306);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

/* FETCH HOUSES ABOVE 1 CRORE */
$sql = "SELECT * FROM houses WHERE listing_type = 'Rent' LIMIT 50";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Rental Properties</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-semibold brand-red" href="index123.php">FindMySpace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#rentNav" aria-controls="rentNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="rentNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="explorebuying.php">Buy</a></li>
                    <li class="nav-item"><a class="nav-link active" href="ExploreRenting.php">Rent</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Search</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="index.php">Back to Search</a>
                    <a class="btn btn-brand-red btn-sm" href="login.php">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="border-bottom bg-white">
        <div class="container py-3 d-flex align-items-center gap-2 text-secondary small">
            <span class="badge text-bg-light border">📍 Tip</span>
            <span>Add localities for more relevant results</span>
            <a href="#" class="ms-auto text-decoration-none brand-red fw-semibold">Add →</a>
        </div>
    </div>

    <header class="py-4">
        <div class="container">
            <div class="row align-items-end g-2">
                <div class="col-12 col-lg-8">
                    <h1 class="h3 mb-1">Premium rental listings</h1>
                    <div class="text-secondary">Hand-picked rentals with full details.</div>
                </div>
                <div class="col-12 col-lg-4 text-lg-end">
                    <div class="text-secondary small">
                        <span class="fw-semibold"><?php echo $result ? (int)$result->num_rows : 0; ?></span> properties found
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="pb-5">
        <div class="container">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="vstack gap-3">
                    <?php while ($property = $result->fetch_assoc()): ?>
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="row g-0">
                                <div class="col-12 col-md-4 position-relative">
                                    <img
                                        src="<?php echo htmlspecialchars($property['image_url_1']); ?>"
                                        alt="Property"
                                        class="img-fluid w-100 h-100"
                                        style="object-fit: cover; min-height: 220px;"
                                    >
                                    <span class="badge text-bg-dark position-absolute bottom-0 start-0 m-3 opacity-75">Photos</span>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="card-body p-3 p-md-4">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                            <div>
                                                <div class="d-flex gap-2 flex-wrap mb-1">
                                                    <span class="badge text-bg-success-subtle border border-success-subtle text-success-emphasis">✓ Zero brokerage</span>
                                                    <span class="badge text-bg-light border">Ready to move</span>
                                                </div>
                                                <div class="h5 mb-1">
                                                    <?php echo htmlspecialchars($property['bedrooms']); ?> BHK
                                                    <?php echo htmlspecialchars($property['flat_type']); ?>
                                                    <span class="text-secondary">in</span>
                                                    <?php echo htmlspecialchars($property['title']); ?>
                                                </div>
                                                <div class="text-secondary"><?php echo htmlspecialchars($property['location']); ?></div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-semibold fs-5"><?php echo htmlspecialchars(format_inr_price($property['price'])); ?></div>
                                                <?php $ppsf = format_inr_per_sqft($property['price'], $property['area']); ?>
                                                <?php if ($ppsf !== ''): ?>
                                                    <div class="text-secondary small"><?php echo htmlspecialchars($ppsf); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-6 col-lg-4">
                                                <div class="border rounded-3 p-2 h-100">
                                                    <div class="text-secondary small">Carpet area</div>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($property['area']); ?> sqft</div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-4">
                                                <div class="border rounded-3 p-2 h-100">
                                                    <div class="text-secondary small">Bathrooms</div>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($property['bathrooms']); ?></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <div class="border rounded-3 p-2 h-100">
                                                    <div class="text-secondary small">Furnishing</div>
                                                    <div class="fw-semibold">Furnished</div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($property['description'])): ?>
                                            <p class="text-secondary mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                <?php echo htmlspecialchars($property['description']); ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="text-secondary small">
                                                <span class="fw-semibold">Builder:</span> Evershine Builders
                                                <span class="mx-2">•</span>
                                                Since 1971
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="rentaloutput.php?id=<?php echo (int)$property['id']; ?>" class="btn btn-brand-red">Get Info</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="h5 mb-2">No properties found</div>
                        <div class="text-secondary">Try adjusting your search filters.</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
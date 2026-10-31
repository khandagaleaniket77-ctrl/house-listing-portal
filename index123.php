<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function format_inr_price($price) {
    if (!is_numeric($price)) return '';
    $price = (float)$price;
    if ($price >= 10000000) {
        return '₹' . number_format($price / 10000000, 2) . ' Cr';
    }
    return '₹' . number_format($price / 100000, 0) . ' Lac';
}

/* DATABASE CONNECTION */
$conn = new mysqli("127.0.0.1", "root", "", "searchbardatabase", 3306);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

/* FETCH SECTIONS */
$owner_sql = "SELECT * FROM houses WHERE location = 'Mumbai' LIMIT 4";
$owner_result = $conn->query($owner_sql);

$rent_sql = "SELECT * FROM houses WHERE listing_type = 'Rent' LIMIT 4";
$rent_result = $conn->query($rent_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindMySpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-semibold brand-red" href="index123.php">FindMySpace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Buy
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="premium_houses.php">Premium Homes</a></li>
                            <li><a class="dropdown-item" href="budget_homes.php">Budget Homes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Rent
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="bachelorfriendly.php">Bachelor Friendly Houses</a></li>
                            <li><a class="dropdown-item" href="immediatelyavailable.php">Immediately Available</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Help
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Help Center</a></li>
                            <li><a class="dropdown-item" href="#">Sales Enquiry</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="index.php">Search</a>
                    <a class="btn btn-brand-red btn-sm" href="login.php">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <header class="py-5 auth-bg">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-6">
                    <h1 class="display-6 fw-semibold mb-2">Welcome back!</h1>
                    <p class="text-secondary mb-4">Start your next journey—find better places to live, work, and wonder.</p>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3 p-md-4">
                            <form action="search.php" method="GET" class="row g-2 align-items-end">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="location">Location</label>
                                    <input class="form-control form-control-lg" type="text" id="location" name="location" placeholder="Search location" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="flat_type">Flat type</label>
                                    <select class="form-select form-select-lg" id="flat_type" name="flat_type" required>
                                        <option value="" selected disabled>Flat +1</option>
                                        <option value="1BHK">1 BHK</option>
                                        <option value="2BHK">2 BHK</option>
                                        <option value="3BHK">3 BHK</option>
                                        <option value="4BHK">4 BHK</option>
                                        <option value="Studio">Studio</option>
                                        <option value="Penthouse">Penthouse</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-2 d-grid">
                                    <button type="submit" class="btn btn-brand-red btn-lg">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-md-5">
                                        <img class="img-fluid h-100 object-fit-cover" alt="Buy a home" src="https://static.99acres.com/universalhp/img/d_hp_buy_xl.webp">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card-body">
                                            <div class="text-uppercase small text-secondary fw-semibold">Buy a home</div>
                                            <div class="h5 mt-1">Find, buy & own your dream home</div>
                                            <div class="text-secondary mb-3">Explore apartments, land, builder floors, villas and more.</div>
                                            <a class="btn btn-outline-secondary" href="explorebuying.php">Explore Buying</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-md-5">
                                        <img class="img-fluid h-100 object-fit-cover" alt="Rent a home" src="https://static.99acres.com/universalhp/img/d_hp_rent_xl.webp">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card-body">
                                            <div class="text-uppercase small text-secondary fw-semibold">Rent a home</div>
                                            <div class="h5 mt-1">Rental homes for everyone</div>
                                            <div class="text-secondary mb-3">Discover places that match your lifestyle and budget.</div>
                                            <a class="btn btn-outline-secondary" href="ExploreRenting.php">Explore Renting</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
                <div>
                    <h2 class="h4 mb-1">Exclusive Owner Properties in Mumbai</h2>
                    <div class="text-secondary">Hand-picked listings to get you started.</div>
                </div>
                <a class="btn btn-sm btn-outline-secondary" href="seeall.php">See all</a>
            </div>

            <div class="row g-3">
                <?php if ($owner_result && $owner_result->num_rows > 0): ?>
                    <?php while ($property = $owner_result->fetch_assoc()): ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card h-100 shadow-sm border-0">
                                <a href="rentaloutput.php?id=<?php echo (int)$property['id']; ?>" class="text-decoration-none text-dark">
                                    <img
                                        src="<?php echo htmlspecialchars($property['image_url_1']); ?>"
                                        class="card-img-top"
                                        alt="Property Image"
                                        style="height: 200px; object-fit: cover;"
                                    >
                                    <div class="card-body">
                                        <div class="text-secondary small mb-1"><?php echo htmlspecialchars($property['bedrooms']); ?> BHK Flat</div>
                                        <div class="fw-semibold fs-5 mb-1"><?php echo htmlspecialchars(format_inr_price($property['price'])); ?></div>
                                        <div class="text-secondary"><?php echo htmlspecialchars($property['title']); ?></div>
                                    </div>
                                    <div class="card-footer bg-white border-0 pt-0">
                                        <span class="badge text-bg-light border">Ready to Move</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">No properties found.</div>
                    </div>
                <?php endif; ?>
            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
                <div>
                    <h2 class="h4 mb-1">Exclusive Rental Properties</h2>
                    <div class="text-secondary">Popular rentals right now.</div>
                </div>
                <a class="btn btn-sm btn-outline-secondary" href="ExploreRenting.php">See all</a>
            </div>

            <div class="row g-3">
                <?php if ($rent_result && $rent_result->num_rows > 0): ?>
                    <?php while ($property = $rent_result->fetch_assoc()): ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card h-100 shadow-sm border-0">
                                <a href="rentaloutput.php?id=<?php echo (int)$property['id']; ?>" class="text-decoration-none text-dark">
                                    <img
                                        src="<?php echo htmlspecialchars($property['image_url_1']); ?>"
                                        class="card-img-top"
                                        alt="Property Image"
                                        style="height: 200px; object-fit: cover;"
                                    >
                                    <div class="card-body">
                                        <div class="text-secondary small mb-1"><?php echo htmlspecialchars($property['bedrooms']); ?> BHK Flat</div>
                                        <div class="fw-semibold fs-5 mb-1"><?php echo htmlspecialchars(format_inr_price($property['price'])); ?></div>
                                        <div class="text-secondary"><?php echo htmlspecialchars($property['title']); ?></div>
                                    </div>
                                    <div class="card-footer bg-white border-0 pt-0">
                                        <span class="badge text-bg-light border">Ready to Move</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">No properties found.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="border-top bg-white">
        <div class="container py-4">
            <div class="d-flex justify-content-between flex-wrap gap-2">
                <div class="text-secondary small">© <?php echo date('Y'); ?> FindMySpace</div>
                <div class="d-flex gap-3 small">
                    <a class="text-decoration-none text-secondary" href="#">Terms</a>
                    <a class="text-decoration-none text-secondary" href="#">Privacy</a>
                    <a class="text-decoration-none text-secondary" href="#">Help</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
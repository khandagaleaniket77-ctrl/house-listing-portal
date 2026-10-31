<?php
// Database connection
$host = 'localhost';
$dbname = 'searchbardatabase';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get search parameters
$location = isset($_GET['location']) ? $_GET['location'] : '';
$flat_type = isset($_GET['flat_type']) ? $_GET['flat_type'] : '';

// Prepare and execute query
$sql = "SELECT * FROM houses WHERE location LIKE :location AND flat_type = :flat_type";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':location' => "%$location%",
    ':flat_type' => $flat_type
]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .top-banner {
            background: #fff;
            padding: 12px 20px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #333;
        }

        .top-banner::before {
            content: '📍';
            font-size: 18px;
        }

        .add-localities {
            color: #c62828;
            text-decoration: none;
            margin-left: auto;
            font-weight: 600;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #fff;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
            margin-bottom: 15px;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: #f5f5f5;
        }

        .search-info {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .results-count {
            font-size: 14px;
            color: #999;
        }

        .property-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .property-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 320px 1fr;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .property-card:hover {
            box-shadow: 0 10px 16px rgba(0, 0, 0, 0.15);
             transform: translatey(-1px);
        }

        .property-image-section {
            position: relative;
            overflow: hidden;
        }

        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 280px;
        }

        .photo-count {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .image-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
        }

        .dot.active {
            background: #fff;
        }

        .property-content {
            padding: 20px 25px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .top-right-section {
            position: absolute;
            top: 20px;
            right: 25px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .zero-brokerage {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .property-title {
            font-size: 18px;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            padding-right: 200px;
        }

        .property-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .property-specs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
        }

        .spec-label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .spec-value {
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }

        .property-description {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .builder-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .builder-details {
            display: flex;
            flex-direction: column;
        }

        .builder-label {
            font-size: 12px;
            color: #999;
        }

        .builder-name {
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }

        .builder-since {
            font-size: 12px;
            color: #999;
        }

        .property-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #c62828;
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            color: #c62828;
            border: 1px solid #c62828;
        }

        .property-price-section {
            text-align: right;
        }

        .property-price {
            font-size: 24px;
            color: #333;
            font-weight: 700;
        }

        .price-per-sqft {
            font-size: 12px;
            color: #999;
            margin-top: 2px;
        }

        .favorite-icon {
            position: absolute;
            top: 20px;
            right: 100px;
            font-size: 24px;
            cursor: pointer;
            color: #ccc;
        }

        .share-icon {
            position: absolute;
            top: 20px;
            right: 140px;
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }

        .no-results {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 8px;
        }

        .no-results h2 {
            color: #666;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #999;
            font-size: 16px;
        }

        @media (max-width: 968px) {
            .property-card {
                grid-template-columns: 1fr;
            }

            .property-image {
                min-height: 250px;
            }

            .property-specs {
                grid-template-columns: repeat(2, 1fr);
            }

            .top-right-section {
                position: static;
                margin-bottom: 15px;
                align-items: flex-start;
            }

            .property-title {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="top-banner">
        Add Localities for more relevant results
        <a href="#" class="add-localities">→</a>
    </div>

    <div class="container">
        <div class="header">
            <a href="index123.php" class="back-btn">← Back to Search</a>
            <div class="search-info">
                Properties for <strong><?php echo htmlspecialchars($flat_type); ?></strong> in <strong><?php echo htmlspecialchars($location); ?></strong>
            </div>
            <div class="results-count"><?php echo count($results); ?> properties found</div>
        </div>

        <div class="property-list">
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $property): ?>
                    <div class="property-card">
                        <div class="property-image-section">
                            <img src="<?php echo htmlspecialchars($property['image_url_1']); ?>" alt="Property" class="property-image">
                           
                            
                        </div>

                        <div class="property-content">
                            
                            
                            <div class="top-right-section">
                                <span class="zero-brokerage">✓ ZERO BROKERAGE</span>
                                <div class="property-price-section">
                                    <div class="property-price">₹<?php echo number_format($property['price'] / 100000, 1); ?> Lac</div>
                                    <div class="price-per-sqft">₹<?php echo number_format($property['price'] / $property['area']); ?> per sqft</div>
                                </div>
                            </div>

                            <h3 class="property-title">
                                 <?php echo htmlspecialchars($property['title']); ?>
                            </h3>
                            <p class="property-subtitle"><?php echo htmlspecialchars($property['location']); ?></p>

                            <div class="property-specs">
                                <div class="spec-item">
                                    <span class="spec-label">CARPET AREA</span>
                                    <span class="spec-value"><?php echo htmlspecialchars($property['area']); ?> sqft</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">STATUS</span>
                                    <span class="spec-value">Ready to Move</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">TRANSACTION</span>
                                    <span class="spec-value">New Property</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">FURNISHING</span>
                                    <span class="spec-value">Furnished</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">CITY</span>
                                    <span class="spec-value"><?php echo htmlspecialchars($property['location']); ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">BATHROOM</span>
                                    <span class="spec-value"><?php echo htmlspecialchars($property['bathrooms']); ?></span>
                                </div>
                            </div>

                            <p class="property-description">
                                <?php echo htmlspecialchars($property['description']); ?>
                            </p>

                            <div class="builder-info">
                                <div class="builder-details">
                                    <span class="builder-label">Builder:</span>
                                    <span class="builder-name">Evershine Builders</span>
                                    <span class="builder-since">Operating Since 1971</span>
                                </div>
                                <div class="property-actions">
                                    
                                    <a href="http://localhost/login95/more_details.php/<?php echo $property['id']; ?>" class="btn btn-primary">Get Info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <h2>No properties found</h2>
                    <p>No properties found matching your search criteria.</p>
                    <p>Try different location or flat type.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
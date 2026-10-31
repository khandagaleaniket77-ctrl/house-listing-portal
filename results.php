<?php
require_once 'db_config.php';

// Get search parameters
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$flat_type = isset($_GET['flat_type']) ? trim($_GET['flat_type']) : '';

// Validate inputs
if (empty($location) || empty($flat_type)) {
    header('Location: index.html');
    exit();
}

// Get database connection
$pdo = getDBConnection();

// Prepare and execute search query
$sql = "SELECT * FROM properties WHERE location LIKE :location AND flat_type = :flat_type ORDER BY price ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':location' => "%$location%",
    ':flat_type' => $flat_type
]);
$properties = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Property Search</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 15px;
            font-size: 2.5em;
        }
        
        .header p {
            color: #666;
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: bold;
        }
        
        .back-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .no-results {
            background: white;
            padding: 60px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .no-results h2 {
            color: #dc3545;
            margin-bottom: 20px;
            font-size: 2em;
        }
        
        .no-results p {
            color: #666;
            font-size: 18px;
        }
        
        .property-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 30px rgba(0,0,0,0.3);
        }
        
        .property-image {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .property-header {
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        
        .property-name {
            font-size: 32px;
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .property-price {
            font-size: 28px;
            color: #dc3545;
            font-weight: bold;
        }
        
        .property-type {
            display: inline-block;
            background: #764ba2;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .property-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .detail-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #dc3545;
        }
        
        .detail-section h3 {
            color: #dc3545;
            margin-bottom: 15px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        
        .detail-value {
            color: #333;
            font-weight: bold;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }
        
        .feature-box {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
        }
        
        .feature-box.inactive {
            background: #e9ecef;
            color: #6c757d;
            box-shadow: none;
        }
        
        .amenities-section {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 20px;
            border-radius: 10px;
            margin-top: 25px;
        }
        
        .amenities-section h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .amenity-tag {
            background: #4caf50;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }
        
        .description-section {
            margin-top: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 10px;
            border-left: 4px solid #ffc107;
        }
        
        .description-section h3 {
            color: #856404;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .description-section p {
            color: #856404;
            line-height: 1.6;
            font-size: 16px;
        }
        
        .contact-section {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            padding: 20px;
            border-radius: 10px;
            margin-top: 25px;
            border-left: 4px solid #17a2b8;
        }
        
        .contact-section h3 {
            color: #0c5460;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .contact-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .contact-label {
            color: #0c5460;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .contact-value {
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔍 Search Results</h1>
        <p>Found <strong><?php echo count($properties); ?></strong> properties for <strong><?php echo htmlspecialchars($flat_type); ?></strong> in <strong><?php echo htmlspecialchars($location); ?></strong></p>
        <a href="index.html" class="back-btn">← Back to Search</a>
    </div>
    
    <?php if (count($properties) == 0): ?>
        <div class="no-results">
            <h2>😔 No Properties Found</h2>
            <p>Try searching with different criteria or location</p>
            <br>
            <a href="index.html" class="back-btn">Try Another Search</a>
        </div>
    <?php else: ?>
        <?php foreach ($properties as $property): ?>
            <div class="property-card">
                <!-- Property Image Placeholder -->
                <div class="property-image">
                    🏢
                </div>
                
                <!-- Property Header -->
                <div class="property-header">
                    <div class="property-name"><?php echo htmlspecialchars($property['property_name']); ?></div>
                    <div class="property-price">₹ <?php echo number_format($property['price']); ?></div>
                    <span class="property-type"><?php echo htmlspecialchars($property['flat_type']); ?></span>
                </div>
                
                <!-- Property Details Grid -->
                <div class="property-details">
                    <!-- Basic Details -->
                    <div class="detail-section">
                        <h3>🏠 Property Details</h3>
                        <div class="detail-item">
                            <span class="detail-label">Property Type:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($property['flat_type']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Area:</span>
                            <span class="detail-value"><?php echo number_format($property['area_sqft']); ?> sq.ft</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Bedrooms:</span>
                            <span class="detail-value"><?php echo $property['bedrooms']; ?> 🛏️</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Bathrooms:</span>
                            <span class="detail-value"><?php echo $property['bathrooms']; ?> 🚿</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Balconies:</span>
                            <span class="detail-value"><?php echo $property['balconies']; ?> 🌅</span>
                        </div>
                    </div>
                    <a href="http://localhost/sample project/more_details.php">More Details</a>
                    
                    <!-- Building Information -->
                    <div class="detail-section">
                        <h3>🏗️ Building Info</h3>
                        <div class="detail-item">
                            <span class="detail-label">Floor:</span>
                            <span class="detail-value"><?php echo $property['floor_number']; ?> of <?php echo $property['total_floors']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Furnishing:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($property['furnishing']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Parking Spaces:</span>
                            <span class="detail-value"><?php echo $property['parking']; ?> 🚗</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Property Age:</span>
                            <span class="detail-value"><?php echo $property['age_of_property']; ?> years</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Facing:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($property['facing']); ?> 🧭</span>
                        </div>
                    </div>
                    
                    <!-- Location Details -->
                    <div class="detail-section">
                        <h3>📍 Location</h3>
                        <div class="detail-item">
                            <span class="detail-label">City:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($property['location']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Full Address:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($property['address']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Available From:</span>
                            <span class="detail-value"><?php echo date('d M Y', strtotime($property['available_from'])); ?> 📅</span>
                        </div>
                    </div>
                </div>
                
                <!-- Features Grid -->
                <div class="features-grid">
                    <div class="feature-box <?php echo $property['gym'] ? '' : 'inactive'; ?>">
                        <?php echo $property['gym'] ? '✓' : '✗'; ?> Gymnasium
                    </div>
                    <div class="feature-box <?php echo $property['swimming_pool'] ? '' : 'inactive'; ?>">
                        <?php echo $property['swimming_pool'] ? '✓' : '✗'; ?> Swimming Pool
                    </div>
                    <div class="feature-box <?php echo $property['garden'] ? '' : 'inactive'; ?>">
                        <?php echo $property['garden'] ? '✓' : '✗'; ?> Garden
                    </div>
                    <div class="feature-box <?php echo $property['security_24x7'] ? '' : 'inactive'; ?>">
                        <?php echo $property['security_24x7'] ? '✓' : '✗'; ?> 24x7 Security
                    </div>
                    <div class="feature-box <?php echo $property['power_backup'] ? '' : 'inactive'; ?>">
                        <?php echo $property['power_backup'] ? '✓' : '✗'; ?> Power Backup
                    </div>
                    <div class="feature-box <?php echo $property['lift'] ? '' : 'inactive'; ?>">
                        <?php echo $property['lift'] ? '✓' : '✗'; ?> Lift Available
                    </div>
                </div>
                
                <!-- Amenities Section -->
                <div class="amenities-section">
                    <h3>✨ Amenities & Facilities</h3>
                    <div class="amenities-list">
                        <?php 
                        $amenities_array = explode(', ', $property['amenities']);
                        foreach ($amenities_array as $amenity): 
                        ?>
                            <span class="amenity-tag"><?php echo htmlspecialchars($amenity); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Description Section -->
                <div class="description-section">
                    <h3>📝 Property Description</h3>
                    <p><?php echo htmlspecialchars($property['description']); ?></p>
                </div>
                
                <!-- Contact Section -->
                <div class="contact-section">
                    <h3>📞 Contact Information</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-label">Owner Name</div>
                            <div class="contact-value"><?php echo htmlspecialchars($property['owner_name']); ?></div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-label">Contact Number</div>
                            <div class="contact-value"><?php echo htmlspecialchars($property['owner_contact']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="index.html" class="back-btn">← Back to Search</a>
    </div>
</body>
</html>


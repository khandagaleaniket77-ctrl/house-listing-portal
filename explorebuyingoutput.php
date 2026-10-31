<?php
/*************************
 DATABASE CONNECTION
**************************/
$conn = new mysqli("localhost", "root", "", "searchbardatabase");

if ($conn->connect_error) {
    die("Database connection failed");
}

/*************************
 GET ID FROM URL
**************************/
// Debug: Check what we're receiving
// echo "GET params: "; print_r($_GET); echo "<br>";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Property ID - No ID parameter found in URL");
}

$property_id = intval($_GET['id']);

if ($property_id <= 0) {
    die("Invalid Property ID - ID must be a positive number. Received: " . htmlspecialchars($_GET['id']));
}

/*************************
 FETCH PROPERTY DATA
**************************/
$sql = "SELECT * FROM houses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Property not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($data['title']); ?></title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: #f5f5f5;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
}

/* Header Section */
.header {
    padding: 20px 30px;
    background: #fff;
    border-bottom: 1px solid #e5e5e5;
}

.price-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.price {
    font-size: 28px;
    font-weight: 700;
    color: #333;
}

.emi-info {
    font-size: 13px;
    color: #666;
    margin-left: 15px;
}

.zero-brokerage {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.property-title {
    font-size: 18px;
    color: #333;
    font-weight: 400;
    margin-bottom: 5px;
}

/* Image Gallery */
.gallery {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 8px;
    padding: 0;
    background: #f6f4f4ff;
    cursor:pointer;
    
}

.main-image {
    position: relative;
}

.main-image img {
    width: 100%;
    height: 700px;
    object-fit: cover;
}

.thumbnail-grid {
    display: grid;
      height: 700px;
    gap: 5px;
}

.thumbnail {
    position: relative;
    overflow: hidden;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-count {
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.75);
    color: #fff;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
}

/* Property Details */
.details-section {
    padding: 25px 30px;
    border-bottom: 1px solid #e5e5e5;
}

.quick-details {
    display: flex;
    gap: 40px;
    margin-bottom: 25px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    color: #333;
}

.detail-item::before {
    content: '';
    width: 20px;
    height: 20px;
    background-size: contain;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.spec-item {
    padding: 15px 0;
}

.spec-label {
    font-size: 15px;
    color: #666;
    margin-bottom: 5px;
}

.spec-value {
    font-size: 16px;
    color: #333;
    font-weight: 600;
}

/* Contact Buttons */
.contact-section {
    padding: 20px 30px;
    background: #fafafa;
    border-bottom: 1px solid #e5e5e5;
    display: flex;
    gap: 35px;
}

.btn {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 25px;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    max-width: 300px;
    
}

.btn-primary {
    background: #c62828;
    color: #fff;
}

.btn-primary:hover {
    background: #c80606ff;
    color: #fff;
}


.btn-secondary {
    background: #fff;
    color: #c62828;
    border: 1px solid #c62828;
}

btn-secondary {
    background: #fff;
    color: #c41919ff;
}

/* More Details Section */
.more-details {
    padding: 30px;
}

.section-title {
    font-size: 22px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
}

.detail-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-label {
    font-size: 17px;
    color: #666;
}

.detail-value {
    font-size: 17px;
    color: #333;
    font-weight: 500;
}

/* Amenities */
.amenities-section {
    padding: 30px;
    background: #fafafa;
}

.amenities-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.amenity-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    color: #333;
}

.amenity-icon {
    width: 24px;
    height: 24px;
    color: #666;
}

/* Description */
.description-section {
    padding: 30px;
  border-bottom: 1px solid #f0f0f0;
   border-top: 1px solid #f0f0f0;
}

.description-text {
    font-size: 15px;
    line-height: 1.7;
    color: #555;
    ;
     
}

.view-more {
    color: #c62828;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    display: inline-block;
}

@media (max-width: 768px) {

    /* Stack gallery vertically */
    .gallery {
        grid-template-columns: 1fr;
    }

    /* Main image full width */
    .main-image img {
        width: 100%;
        height: 250px;
    }

    /* Thumbnails in a row below main image */
    .thumbnail-grid {
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: 1fr;
        height: auto;
    }

    .thumbnail {
        height: 80px;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }



    
    .specs-grid,
    .amenities-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-details {
        flex-wrap: wrap;
        gap: 20px;
    }
}
</style>
</head>

<body>

<div class="container">
    
    <!-- Header -->
    <div class="header">
        <div class="price-section">
            <div>
                <span class="price">₹<?= number_format($data['price'] / 100000, 1); ?> Lac</span>
                <span class="emi-info">EMI - ₹ 21k | How much loan can I get?</span>
            </div>
            <span class="zero-brokerage">✓ ZERO BROKERAGE</span>
        </div>
        <div class="property-title">
            <?= htmlspecialchars($data['bedrooms']); ?> BHK <?= htmlspecialchars($data['area']); ?> Sq-ft <?= htmlspecialchars($data['flat_type']); ?> For Sale
            <?= htmlspecialchars($data['location']); ?>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="gallery">
        <div class="main-image">
            <img src="<?= htmlspecialchars($data['image_url_1']); ?>" alt="Property Image" onerror="this.src='https://via.placeholder.com/800x840?text=Property+Image'">
        </div>
        <div class="thumbnail-grid">
            <div class="thumbnail">
                <img src="<?= htmlspecialchars($data['image_url_2']); ?>" alt="Property" onerror="this.src='https://via.placeholder.com/400x280?text=Property'">
            </div>
            <div class="thumbnail">
                <img src="<?= htmlspecialchars($data['image_url_3']); ?>" alt="Property" onerror="this.src='https://via.placeholder.com/400x280?text=Property'">
            </div>
            <div class="thumbnail">
                <img src="<?= htmlspecialchars($data['image_url_4']); ?>" alt="Property" onerror="this.src='https://via.placeholder.com/400x280?text=Property'">
                <div class="photo-count">+21 Photos</div>
            </div>
        </div>
    </div>

    <!-- Quick Details -->
    <div class="details-section">
        <div class="quick-details">
            <div class="detail-item">🛏️ <?= htmlspecialchars($data['bedrooms']); ?> Beds</div>
            <div class="detail-item">🚿 <?= htmlspecialchars($data['bathrooms']); ?> Baths</div>
            <div class="detail-item">📦 Unfurnished</div>
        </div>

        <div class="specs-grid">
            <div class="spec-item">
                <div class="spec-label">Carpet Area</div>
                <div class="spec-value"><?= htmlspecialchars($data['area']); ?> sqft</div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Transaction Type</div>
                <div class="spec-value">New Property</div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Status</div>
                <div class="spec-value">Ready to Move</div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Developer</div>
                <div class="spec-value">Evershine Builders</div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Furnished Status</div>
                <div class="spec-value">Unfurnished</div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Lifts</div>
                <div class="spec-value">2</div>
            </div>
        </div>
    </div>

    <!-- Contact Buttons -->


    <!-- More Details -->
    <div class="more-details">
        <h2 class="section-title">More Details</h2>
        
        <div class="detail-row">
            <div class="detail-label">Price Breakup</div>
            <div class="detail-value">₹<?= number_format($data['price'] / 100000, 1); ?> Lac</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Booking Amount</div>
            <div class="detail-value">₹<?= number_format(($data['price'] / 100000) * 0.1, 1); ?> Lac</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Address</div>
            <div class="detail-value"><?= htmlspecialchars($data['location']); ?></div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Furnishing</div>
            <div class="detail-value">Unfurnished</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Flooring</div>
            <div class="detail-value">Vitrified</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Water Availability</div>
            <div class="detail-value">24 Hours Available</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Status of Electricity</div>
            <div class="detail-value">No/Rare Powercut</div>
        </div>
    </div>

    <!-- Amenities -->
    <?php if(!empty($data['amenities'])): 
        $amenitiesArray = explode(',', $data['amenities']);
        $amenityIcons = [
            'Swimming Pool' => '🏊',
            'Gymnasium' => '🏋️',
            'Reserved Parking' => '🅿️',
            'Water Storage' => '💧',
            'Fire Sprinklers' => '🔥',
            'Kids play area' => '🎮',
            'Parking' => '🅿️',
            'Garden' => '🌳',
            'Security' => '🔒',
            'Elevator' => '🛗',
            'Power Backup' => '⚡',
            'Club House' => '🏛️',
            'CCTV' => '📹',
            'Playground' => '⚽'
        ];
    ?>
    <div class="amenities-section">
        <h2 class="section-title">Amenities</h2>
        
        <div class="amenities-grid">
            <?php foreach($amenitiesArray as $amenity): 
                $amenity = trim($amenity);
                // Find matching icon (case insensitive)
                $icon = '🏠'; // default icon
                foreach($amenityIcons as $key => $value) {
                    if(stripos($amenity, $key) !== false) {
                        $icon = $value;
                        break;
                    }
                }
            ?>
            <div class="amenity-item">
                <span class="amenity-icon"><?= $icon; ?></span>
                <?= htmlspecialchars($amenity); ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="view-more">View all Amenities(<?= count($amenitiesArray); ?>) ↓</div>
    </div>
    <?php endif; ?>

    <!-- Description -->
    <div class="description-section">
        <h2 class="section-title">Description</h2>
        <div class="description-text">
            <?= nl2br(htmlspecialchars($data['description'])); ?>
        </div>
    </div>

    <!-- Contact Buttons Bottom -->
    <div class="contact-section">
        <a href="appointments_booking.php" class="btn btn-primary">📞 Contact Builder</a>
       
    </div>

</div>


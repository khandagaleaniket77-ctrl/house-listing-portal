<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
        }

        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            transition: box-shadow 0.3s;
            cursor: pointer;
        }

        .property-card:hover {
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
            
        }

        .property-image {
            width: 300px;
            height: 265px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .property-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .property-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .property-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .property-location {
            color: #666;
            font-size: 14px;
        }

        .property-price {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .price-per-sqft {
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }

        .property-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .property-description {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
            flex: 1;
        }

        .property-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .amenity-tag {
            background: #f0f0f0;
            color: #555;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .property-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: auto;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-secondary {
            background: white;
            color: #e71714ff;
            border: 1px solid #e53935;
            padding: 14px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #ffebee;
        }

        .btn-primary {
            background: #d00303ff;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            font-size: 15px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            
        }

        .btn-primary:hover{
            background: #c62828;
        }

        .no-properties {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            color: #666;
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
}

    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Property Listings</h1>
            <p>Browse through our exclusive collection of properties</p>
        </div>

        <div id="properties-list">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root"; // Change this to your database username
            $password = ""; // Change this to your database password
            $dbname = "searchbardatabase";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("<div class='no-properties'>Connection failed: " . $conn->connect_error . "</div>");
            }

            // Fetch all houses from database
            $sql = "SELECT * FROM houses ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    // Calculate price per sqft
                    $pricePerSqft = '';
                    if($row['area'] > 0) {
                        $pricePerSqftValue = $row['price'] / $row['area'];
                        $pricePerSqft = '<div class="price-per-sqft">₹' . number_format($pricePerSqftValue, 0) . ' per sqft</div>';
                    }

                    // Format price in lakhs
                    $priceLakhs = $row['price'] / 100000;

                    echo '
                    <div class="property-card">
                        <img src="' . htmlspecialchars($row['image_url_1']) . '" 
                             alt="' . htmlspecialchars($row['title']) . '" 
                             class="property-image"
                             onerror="this.src=\'https://via.placeholder.com/300x250?text=Property+Image\'">
                        
                        <div class="property-content">
                            <div class="property-header">
                                <div>
                                    <h2 class="property-title">' . htmlspecialchars($row['title']) . '</h2>
                                    <p class="property-location">' . htmlspecialchars($row['location']) . '</p>
                                </div>
                                <div class="property-price">
                                    ₹' . number_format($priceLakhs, 2) . ' Lac
                                    ' . $pricePerSqft . '
                                </div>
                            </div>

                            <div class="property-details">
                                <div class="detail-item">
                                    <span class="detail-label">FLAT TYPE</span>
                                    <span class="detail-value">' . htmlspecialchars($row['flat_type']) . '</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">CARPET AREA</span>
                                    <span class="detail-value">' . htmlspecialchars($row['area']) . ' sqft</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">BEDROOMS</span>
                                    <span class="detail-value">' . htmlspecialchars($row['bedrooms']) . '</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">BATHROOMS</span>
                                    <span class="detail-value">' . htmlspecialchars($row['bathrooms']) . '</span>
                                </div>
                            </div>

                            <div class="property-description">
                                ' . htmlspecialchars($row['description']) . '
                            </div>';
                    
                    // Display amenities if available
                    if(!empty($row['amenities'])) {
                        $amenitiesArray = explode(',', $row['amenities']);
                        echo '<div class="property-amenities">';
                        foreach($amenitiesArray as $amenity) {
                            echo '<span class="amenity-tag">' . htmlspecialchars(trim($amenity)) . '</span>';
                        }
                        echo '</div>';
                    }
                    
                    echo '
                            <div class="property-footer">
                                <div class="action-buttons">
                                  
                                    <a href="explorebuyingoutput.php?id=' . $row['id'] . '" class="btn-primary">Get Info</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="no-properties">No properties found in the database.</div>';
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* DATABASE CONNECTION */
$conn = new mysqli("127.0.0.1", "root", "", "searchbardatabase", 3306);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

/* FETCH HOUSES FROM MUMBAI */
$sql = "SELECT * FROM houses WHERE location = 'Mumbai' LIMIT 4";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusive Owner Properties in Mumbai</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-section h1 {
            font-size: 32px;
            color: #2d3436;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }

        .header-section h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: #ffa500;
            border-radius: 2px;
        }

        .see-all-btn {
            color: #e74c3c;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .see-all-btn:hover {
            color: #c0392b;
            gap: 12px;
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            position: relative;
        }

        .property-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .property-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .property-image {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .property-card:hover .property-image img {
            transform: scale(1.1);
        }

        .image-count {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .property-details {
            padding: 20px;
        }

        .property-type {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .property-price {
            font-size: 26px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 12px;
        }

        .property-location {
            color: #636e72;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .property-status {
            color: #27ae60;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 0;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            right: -60px;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 24px;
            color: #2d3436;
        }

        .nav-arrow:hover {
            background: #e74c3c;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .properties-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .properties-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .nav-arrow {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-section h1 {
                font-size: 24px;
            }

            .properties-grid {
                grid-template-columns: 1fr;
            }

            .property-price {
                font-size: 22px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 15px;
            }

            .header-section h1 {
                font-size: 20px;
            }

            .property-details {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1>Exclusive Owner Properties in Mumbai</h1>
            <a href="all-properties.php" class="see-all-btn">
                See all Properties →
            </a>
        </div>

        <div class="properties-grid">
            <?php
            if ($result && $result->num_rows > 0) {
                while($property = $result->fetch_assoc()) {
                    // Format price
                    $price = $property['price'];
                    if ($price >= 10000000) {
                        $formatted_price = '₹' . number_format($price / 10000000, 2) . ' Cr';
                    } else {
                        $formatted_price = '₹' . number_format($price / 100000, 0) . ' Lac';
                    }
                    
                    // Get image count (default to 16 if not available)
                    $image_count = isset($property['image_count']) ? $property['image_count'] : '16';
            ?>
                <div class="property-card" onclick="window.location.href='rentaloutput.php?id=<?php echo $property['id']; ?>'">
                    <div class="property-image">
                        <img src="<?php echo htmlspecialchars($property['image_url_1']); ?>" alt="Property Image">
                        
                    </div>
                    <div class="property-details">
                        <div class="property-type"><?php echo htmlspecialchars($property['bedrooms']); ?> BHK Flat</div>
                        <div class="property-price"><?php echo $formatted_price; ?></div>
                        <div class="property-location">
                           <?php echo htmlspecialchars($property['flat_type']); ?> for Sale <?php echo htmlspecialchars($property['title']); ?>
                        </div>
                        <div class="property-status">Ready to Move</div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p style='text-align: center; color: #7f8c8d; grid-column: 1/-1;'>No properties found</p>";
            }
            ?>
            
           
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
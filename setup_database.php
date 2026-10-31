-- Create database
CREATE DATABASE IF NOT EXISTS searchbardatabase;
USE searchbardatabase;

-- Create houses table
CREATE TABLE IF NOT EXISTS houses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    flat_type VARCHAR(50) NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    area INT NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    description TEXT,
    amenities TEXT,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert 30 detailed house listings
INSERT INTO houses (title, location, flat_type, price, area, bedrooms, bathrooms, description, amenities, image_url) VALUES
('Luxury Apartment in Bandra West', 'Mumbai', '2BHK', 8500000, 1200, 2, 2, 'Spacious 2BHK apartment with stunning sea view, modern interiors and premium fittings. Located in prime Bandra location with easy access to restaurants and shopping.', 'Parking, Gym, Swimming Pool, Security, Power Backup, Lift', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800'),

('Modern 1BHK in Whitefield', 'Bangalore', '1BHK', 4500000, 650, 1, 1, 'Contemporary 1BHK with smart home features, premium kitchen appliances and balcony. Perfect for young professionals.', 'Gym, Security, Power Backup, Parking, Lift, Clubhouse', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800'),

('Spacious 3BHK in Gachibowli', 'Hyderabad', '3BHK', 12000000, 1800, 3, 3, 'Premium 3BHK apartment with modular kitchen, wooden flooring and attached balconies. Located in IT corridor.', 'Swimming Pool, Gym, Parking, Kids Play Area, Security, Lift, Clubhouse', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800'),

('Elegant Villa in Koramangala', 'Bangalore', 'Villa', 35000000, 3500, 4, 4, 'Luxurious independent villa with private garden, terrace and parking for 3 cars. Premium gated community.', 'Private Garden, Parking, Security, Power Backup, Clubhouse, Swimming Pool', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800'),

('Cozy 1BHK in Andheri East', 'Mumbai', '1BHK', 5200000, 580, 1, 1, 'Well-maintained 1BHK near metro station. Ideal for first-time home buyers with good connectivity.', 'Parking, Security, Lift, Power Backup', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800'),

('Premium 4BHK in Jubilee Hills', 'Hyderabad', '4BHK', 28000000, 3200, 4, 4, 'Ultra-luxurious 4BHK with imported fittings, marble flooring and panoramic city views from penthouse level.', 'Private Lift, Swimming Pool, Gym, Parking, Home Theater, Security, Power Backup', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800'),

('Beautiful 2BHK in Powai', 'Mumbai', '2BHK', 9500000, 1100, 2, 2, 'Lake-facing 2BHK with spacious rooms and modern amenities. Close to schools and hospitals.', 'Gym, Swimming Pool, Parking, Security, Lift, Clubhouse, Kids Play Area', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800'),

('Stylish Penthouse in Indiranagar', 'Bangalore', 'Penthouse', 45000000, 4000, 4, 5, 'Stunning penthouse with private terrace, jacuzzi and 360-degree city views. Ultra-modern design.', 'Private Terrace, Jacuzzi, Gym, Swimming Pool, Parking, Security, Power Backup, Lift', 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800'),

('Affordable 1BHK in Hadapsar', 'Pune', '1BHK', 3800000, 620, 1, 1, 'Budget-friendly 1BHK in developing area with good public transport connectivity. Ready to move.', 'Parking, Security, Lift, Power Backup', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800'),

('Spacious 3BHK in Viman Nagar', 'Pune', '3BHK', 11500000, 1650, 3, 2, 'Well-designed 3BHK near airport with excellent ventilation and natural lighting. Premium society.', 'Swimming Pool, Gym, Parking, Security, Lift, Clubhouse, Kids Play Area', 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800'),

('Luxurious Villa in Jubilee Hills', 'Hyderabad', 'Villa', 42000000, 4200, 5, 5, 'Grand villa with private pool, landscaped garden and entertainment area. Perfect for large families.', 'Private Pool, Garden, Parking, Security, Home Theater, Gym, Power Backup', 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800'),

('Modern 2BHK in Electronic City', 'Bangalore', '2BHK', 6800000, 1150, 2, 2, 'Tech-enabled 2BHK with video door phone and smart locks. Close to major IT parks.', 'Gym, Parking, Security, Lift, Power Backup, Clubhouse', 'https://images.unsplash.com/photo-1600607687644-c7171b42498b?w=800'),

('Compact 1BHK in Thane West', 'Mumbai', '1BHK', 4800000, 600, 1, 1, 'Efficient 1BHK with optimized space utilization. Good for investment or self-use.', 'Parking, Security, Lift, Gym, Power Backup', 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800'),

('Premium 3BHK in Banjara Hills', 'Hyderabad', '3BHK', 15500000, 2100, 3, 3, 'High-end 3BHK with Italian marble, designer lighting and imported fixtures. Prime locality.', 'Swimming Pool, Gym, Parking, Security, Lift, Clubhouse, Spa', 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800'),

('Elegant 2BHK in Hinjewadi', 'Pune', '2BHK', 7200000, 1280, 2, 2, 'Spacious 2BHK in IT hub with excellent amenities and green surroundings. Well-connected.', 'Gym, Swimming Pool, Parking, Security, Lift, Kids Play Area', 'https://images.unsplash.com/photo-1600585154363-67eb9e2e2099?w=800'),

('Charming Villa in Whitefield', 'Bangalore', 'Villa', 38000000, 3800, 4, 4, 'Beautiful villa with contemporary architecture, smart home automation and solar panels.', 'Private Garden, Parking, Security, Swimming Pool, Gym, Power Backup', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800'),

('Sophisticated 4BHK in Worli', 'Mumbai', '4BHK', 55000000, 3500, 4, 4, 'Ultra-luxury 4BHK with sea-facing views, imported wood work and premium Italian kitchen.', 'Private Lift, Swimming Pool, Gym, Parking, Concierge, Security, Power Backup', 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=800'),

('Cozy 1BHK in Marathahalli', 'Bangalore', '1BHK', 4200000, 640, 1, 1, 'Ready-to-move 1BHK with balcony and covered parking. Near metro station.', 'Parking, Security, Lift, Gym, Power Backup', 'https://images.unsplash.com/photo-1600607687644-c7171b42498b?w=800'),

('Stunning Penthouse in Bandra', 'Mumbai', 'Penthouse', 85000000, 5000, 5, 6, 'Iconic penthouse with private pool, home theater and panoramic Arabian Sea views. Ultimate luxury.', 'Private Pool, Terrace, Home Theater, Gym, Parking, Security, Lift, Power Backup', 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800'),

('Modern 2BHK in Kondapur', 'Hyderabad', '2BHK', 8200000, 1220, 2, 2, 'Contemporary 2BHK with vastu-compliant design and premium amenities. Excellent investment.', 'Swimming Pool, Gym, Parking, Security, Lift, Clubhouse', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800'),

('Spacious 3BHK in Kharadi', 'Pune', '3BHK', 10500000, 1750, 3, 2, 'Well-ventilated 3BHK with large balconies and storage space. Family-friendly community.', 'Swimming Pool, Gym, Parking, Security, Lift, Kids Play Area, Clubhouse', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800'),

('Affordable 1BHK in Malad West', 'Mumbai', '1BHK', 5500000, 590, 1, 1, 'Value-for-money 1BHK in developing suburb with metro connectivity planned.', 'Parking, Security, Lift, Power Backup', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800'),

('Luxurious 4BHK in Sarjapur Road', 'Bangalore', '4BHK', 24000000, 2800, 4, 4, 'Premium 4BHK with floor-to-ceiling windows, imported fittings and smart home features.', 'Swimming Pool, Gym, Parking, Security, Lift, Clubhouse, Tennis Court', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800'),

('Beautiful Villa in Banjara Hills', 'Hyderabad', 'Villa', 48000000, 4500, 5, 5, 'Magnificent villa with traditional architecture, fountain and landscaped gardens. Heritage design.', 'Private Pool, Garden, Parking, Security, Gym, Home Theater, Power Backup', 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800'),

('Compact 2BHK in Goregaon East', 'Mumbai', '2BHK', 7800000, 980, 2, 2, 'Smart 2BHK with efficient layout near film city. Good rental potential.', 'Gym, Parking, Security, Lift, Power Backup, Clubhouse', 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800'),

('Premium 3BHK in HSR Layout', 'Bangalore', '3BHK', 14500000, 1950, 3, 3, 'Elegant 3BHK with premium wood work, false ceiling and branded fittings. Prime location.', 'Swimming Pool, Gym, Parking, Security, Lift, Clubhouse, Kids Play Area', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800'),

('Stylish 1BHK in Pimple Saudagar', 'Pune', '1BHK', 4100000, 670, 1, 1, 'Modern 1BHK with good natural light and ventilation. Growing neighborhood.', 'Parking, Security, Lift, Gym, Power Backup', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800'),

('Grand Penthouse in Koramangala', 'Bangalore', 'Penthouse', 52000000, 4500, 5, 5, 'Spectacular penthouse with sky lounge, BBQ area and breathtaking city views. Designer interiors.', 'Private Terrace, Jacuzzi, Gym, Swimming Pool, Parking, Security, Lift, Power Backup', 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800'),

('Elegant 2BHK in Madhapur', 'Hyderabad', '2BHK', 8900000, 1300, 2, 2, 'Spacious 2BHK in IT corridor with excellent connectivity. High rental yield area.', 'Swimming Pool, Gym, Parking, Security, Lift, Clubhouse', 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800'),

('Luxurious Villa in Aundh', 'Pune', 'Villa', 32000000, 3300, 4, 4, 'Contemporary villa with minimalist design, rooftop garden and home automation. Eco-friendly.', 'Private Garden, Parking, Security, Solar Panels, Gym, Swimming Pool, Power Backup', 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800');
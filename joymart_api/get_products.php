<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

// Get all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = pg_query($conn, $sql);

if (!$result) {
    die(json_encode([
        "error" => true,
        "message" => "SQL Error: " . pg_last_error($conn)
    ]));
}

$products = [];

while ($row = pg_fetch_assoc($result)) {
    // Use product image if exists, otherwise default.png
    $imageName = !empty($row['image']) ? $row['image'] : "default.png";
    
    // Make the full URL for Flutter to load
    $imageUrl = "https://joymart-backend.onrender.com/uploads/" . $imageName;

    $products[] = [
        "id" => (int)$row['id'],
        "title" => $row['title'],
        "price" => (float)$row['price'],
        "description" => isset($row['description']) ? $row['description'] : "",
        "image" => $imageUrl,
        "category" => isset($row['category']) ? $row['category'] : "General"
    ];
}

// Send JSON back to Flutter
echo json_encode($products);

// Close the database
pg_close($conn);
?>
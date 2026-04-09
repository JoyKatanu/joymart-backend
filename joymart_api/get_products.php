<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

// SQL query
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = pg_query($conn, $sql);

// Check for SQL errors
if (!$result) {
    die(json_encode([
        "error" => true,
        "message" => "SQL Error: " . pg_last_error($conn)
    ]));
}

$products = [];

while ($row = pg_fetch_assoc($result)) {
    // Use image if exists, otherwise default
    $imageName = !empty($row['image']) ? $row['image'] : "default.png";
    
    // Update image URL if needed
    $imageUrl = "https://joymart-backend.onrender.com/uploads/" . $imageName;

    $products[] = [
        "id" => (int)$row['id'],
        "title" => $row['title'],            // Make sure column names match your PostgreSQL table
        "price" => (float)$row['price'],
        "description" => isset($row['description']) ? $row['description'] : "",
        "image" => $imageUrl,
        "category" => isset($row['category']) ? $row['category'] : "General"
    ];
}

// Return JSON
echo json_encode($products);

// Close connection
pg_close($conn);
?>

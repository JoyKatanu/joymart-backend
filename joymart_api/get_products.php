<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

// SQL query
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check for SQL errors
if (!$result) {
    die(json_encode([
        "error" => true,
        "message" => "SQL Error: " . $conn->error
    ]));
}

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imageName = !empty($row['image']) ? $row['image'] : "default.png";
        $imageUrl = "https://joymartweed.infinityfreeapp.com/joymart_api/uploads/" . $imageName;

        $products[] = [
            "id" => (int)$row['id'],
            "title" => $row['name'],
            "price" => (float)$row['price'],
            "description" => $row['description'],
            "image" => $imageUrl,
            "category" => "General"
        ];
    }
}

// Return JSON
echo json_encode($products);
$conn->close();
?>
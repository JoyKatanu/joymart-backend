<?php
header("Content-Type: application/json");
require __DIR__ . '/vendor/autoload.php'; // Composer autoload
include "db.php"; // your PostgreSQL connection

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

// --- Cloudinary configuration ---
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dqwwbww9a',
        'api_key'    => '836269397159339',
        'api_secret' => 'pIfAIto0gQMp7HTs9OGc1EmayHI',
    ],
    'url' => [
        'secure' => true
    ]
]);

// --- Check required POST fields ---
$requiredFields = ['name', 'description', 'price', 'stock'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode([
            "success" => false,
            "message" => "Field '{$field}' is required."
        ]);
        exit;
    }
}

// --- Sanitize and assign input ---
$title = trim($_POST['name']);
$description = trim($_POST['description']);
$price = floatval($_POST['price']);
$stock = intval($_POST['stock']);
$imageUrl = null;

// --- Handle image upload ---
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    try {
        $uploadResult = (new UploadApi())->upload($_FILES['image']['tmp_name'], [
            'folder' => 'joymart_products'
        ]);
        $imageUrl = $uploadResult['secure_url'];
    } catch (\Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Image upload failed: " . $e->getMessage()
        ]);
        exit;
    }
} else {
    // optional default image if no image uploaded
    $imageUrl = "https://res.cloudinary.com/dqwwbww9a/image/upload/vdefault/placeholder.png";
}

// --- Insert into PostgreSQL ---
$query = "INSERT INTO products (title, description, price, stock, image) VALUES ($1, $2, $3, $4, $5)";
$result = pg_query_params($conn, $query, [$title, $description, $price, $stock, $imageUrl]);

if ($result) {
    echo json_encode([
        "success" => true,
        "message" => "Product added successfully",
        "image" => $imageUrl
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . pg_last_error($conn)
    ]);
}

// Close the DB connection
pg_close($conn);
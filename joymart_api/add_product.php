
<?php
header("Content-Type: application/json");
require __DIR__ . '/vendor/autoload.php'; // Cloudinary
include "db.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Cloudinary config
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dqwwbww9a', 
        'api_key'    => '836269397159339',    
        'api_secret' => 'pIfAIto0gQMp7HTs9OGc1EmayHI', 
    ],
    'url' => ['secure' => true]
]);

$cloudinary = new Cloudinary();

if(isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'])) {

    $title = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $imageUrl = null;

    // Upload image to Cloudinary
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        try {
            $uploadResult = (new UploadApi())->upload($_FILES['image']['tmp_name'], [
                'folder' => 'joymart_products'
            ]);
            $imageUrl = $uploadResult['secure_url'];
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Image upload failed: " . $e->getMessage()
            ]);
            exit;
        }
    } else {
        // optional default image
        $imageUrl = "https://res.cloudinary.com/dqwwbww9a/image/upload/vdefault/placeholder.png";
    }

    // Insert into PostgreSQL
    $query = "INSERT INTO products (title, description, price, stock, image) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $query, [$title, $description, $price, $stock, $imageUrl]);

    if($result){
        echo json_encode([
            "success" => true,
            "message" => "Product added successfully",
            "image" => $imageUrl
        ]);
        exit;
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . pg_last_error($conn)
        ]);
        exit;
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

pg_close($conn);

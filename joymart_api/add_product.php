<?php
include "db.php"; // make sure this connects to your Render PostgreSQL DB

// Check if form is submitted
if(isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'])) {

    $title = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    // Handle image upload
    $imageName = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        if(!is_dir($targetDir)){
            mkdir($targetDir, 0755, true); // create uploads folder if it doesn't exist
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    // Insert into PostgreSQL
    $query = "INSERT INTO products (title, description, price, stock, image) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $query, [$title, $description, $price, $stock, $imageName]);

    if($result){
        // Redirect back to admin dashboard
        header("Location: admin_dashboard.php");
        exit(); // important to stop further execution
    } else {
        die("Error adding product: " . pg_last_error($conn));
    }

} else {
    die("All fields are required");
}

// Close connection
pg_close($conn);
?>

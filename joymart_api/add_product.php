<?php
header("Content-Type: application/json");
include "db.php";

if(isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'])) {

    $title = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle image upload
    $imageName = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    $query = "INSERT INTO products (title, description, price, stock, image) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $query, [$title, $description, $price, $stock, $imageName]);

    if($result){
        echo json_encode(["success"=>true, "message"=>"Product added successfully"]);
        header("Location: index.php");
        exit;
    } else {
        echo json_encode(["success"=>false, "message"=>"Error: " . pg_last_error($conn)]);
    }

} else {
    echo json_encode(["success"=>false, "message"=>"All fields are required"]);
}

pg_close($conn);
?>
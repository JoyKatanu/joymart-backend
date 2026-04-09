<?php
header("Content-Type: application/json");
include "db.php";

// Check if form is submitted
if(isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle image upload
    $image = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image_name = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image_name);
        $image = $image_name;
    }

    $sql = "INSERT INTO products (name, description, price, image, stock) VALUES ('$name', '$description', '$price', '$image', '$stock')";
    if($conn->query($sql)){
        echo json_encode(["success"=>true, "message"=>"Product added successfully"]);
    } else {
        echo json_encode(["success"=>false, "message"=>"Error: ".$conn->error]);
    }

} else {
    echo json_encode(["success"=>false, "message"=>"All fields are required"]);
}

$conn->close();
?>
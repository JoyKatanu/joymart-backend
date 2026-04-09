<?php
include "db.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM products WHERE id='$id'");
    if($res->num_rows == 0){
        die("Product not found");
    }
    $product = $res->fetch_assoc();
} else {
    die("ID missing");
}

if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock'";

    // Handle image
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $image_name = time()."_".$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image_name);
        $sql .= ", image='$image_name'";
    }

    $sql .= " WHERE id='$id'";
    $conn->query($sql);

    header("Location: admin_dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Product</title></head>
<body>
<h2>Edit Product</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?php echo $product['description']; ?></textarea><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br><br>

    <label>Image:</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit" name="update">Update Product</button>
</form>
</body>
</html>
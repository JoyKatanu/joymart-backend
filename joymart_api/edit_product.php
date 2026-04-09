<?php
include "db.php";

if(!isset($_GET['id'])) die("ID missing");
$id = $_GET['id'];

// Fetch product
$res = pg_query_params($conn, "SELECT * FROM products WHERE id=$1", [$id]);
if(pg_num_rows($res) == 0) die("Product not found");
$product = pg_fetch_assoc($res);

// Update product
if(isset($_POST['update'])){
    $title = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "UPDATE products SET title=$1, description=$2, price=$3, stock=$4";
    $params = [$title, $description, $price, $stock];

    // Image
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $imageName = time()."_".basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$imageName);
        $query .= ", image=$5";
        $params[] = $imageName;
    }

    $query .= " WHERE id=$".(count($params)+1);
    $params[] = $id;

    pg_query_params($conn, $query, $params);

    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Product</title></head>
<body>
<h2>Edit Product</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['title']); ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

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
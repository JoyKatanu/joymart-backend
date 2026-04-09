<?php
include "db.php";

// Fetch all products
$result = pg_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>JoyMart Admin Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { width: 50px; height: 50px; object-fit: cover; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
    <h2>Add New Product</h2>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <label>Image:</label><br>
        <input type="file" name="image"><br><br>

        <button type="submit">Add Product</button>
    </form>

    <h2>All Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>

        <?php
        if(pg_num_rows($result) > 0){
            while($row = pg_fetch_assoc($result)){
                $image = !empty($row['image']) ? $row['image'] : "default.png";
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['description']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td>".$row['stock']."</td>";
                echo "<td><img src='uploads/".$image."' /></td>";
                echo "<td>
                        <form action='delete_product.php' method='post' style='display:inline'>
                            <input type='hidden' name='id' value='".$row['id']."'>
                            <button type='submit'>Delete</button>
                        </form>
                        <form action='edit_product.php' method='get' style='display:inline'>
                            <input type='hidden' name='id' value='".$row['id']."'>
                            <button type='submit'>Edit</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No products found</td></tr>";
        }
        pg_close($conn);
        ?>
    </table>
</body>
</html>
<?php
include "db.php";

if(isset($_POST['id'])){
    $id = $_POST['id'];

    // Delete product image first
    $res = $conn->query("SELECT image FROM products WHERE id='$id'");
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        if($row['image'] && file_exists("uploads/".$row['image'])){
            unlink("uploads/".$row['image']);
        }
    }

    // Delete product from DB
    $conn->query("DELETE FROM products WHERE id='$id'");

    header("Location: admin_dashboard.php");
}
?>
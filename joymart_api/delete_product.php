<?php
include "db.php";

if(isset($_POST['id'])){
    $id = $_POST['id'];

    // Delete product image
    $res = pg_query_params($conn, "SELECT image FROM products WHERE id=$1", [$id]);
    if(pg_num_rows($res) > 0){
        $row = pg_fetch_assoc($res);
        if($row['image'] && file_exists("uploads/".$row['image'])){
            unlink("uploads/".$row['image']);
        }
    }

    // Delete product
    pg_query_params($conn, "DELETE FROM products WHERE id=$1", [$id]);

    header("Location: admin_dashboard.php");
    exit;
}
?>
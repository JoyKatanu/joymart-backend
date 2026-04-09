<?php
include "db.php";

if(isset($_POST['id'], $_POST['status'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    $conn->query("UPDATE orders SET status='$status' WHERE id='$id'");
    header("Location: orders_dashboard.php");
}
?>
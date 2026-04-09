<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['user_id'], $data['items']) || empty($data['items'])){
    echo json_encode(["success"=>false,"message"=>"Invalid order data"]);
    exit;
}

$user_id = $data['user_id'];
$items = $data['items']; // array of product_id + quantity
$total_amount = 0;

// Calculate total
foreach($items as $item){
    $prod_res = $conn->query("SELECT price, stock FROM products WHERE id=".$item['product_id']);
    $product = $prod_res->fetch_assoc();
    if($product['stock'] < $item['quantity']){
        echo json_encode(["success"=>false,"message"=>"Product ID ".$item['product_id']." out of stock"]);
        exit;
    }
    $total_amount += $product['price'] * $item['quantity'];
}

// Insert order
$conn->query("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES ($user_id, $total_amount, 'pending', NOW())");
$order_id = $conn->insert_id;

// Insert order items & update stock
foreach($items as $item){
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, ".$item['product_id'].", ".$item['quantity'].")");
    $conn->query("UPDATE products SET stock = stock - ".$item['quantity']." WHERE id=".$item['product_id']);
}

echo json_encode(["success"=>true,"message"=>"Order placed successfully","order_id"=>$order_id]);
?>
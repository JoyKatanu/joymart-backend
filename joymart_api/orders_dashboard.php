<?php
include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>JoyMart Orders Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
<h2>All Orders</h2>
<div style="margin-bottom:20px;">
    <strong>Total Orders:</strong> <?php echo $totalOrders; ?> | 
    <strong>Pending:</strong> <?php echo $pendingOrders; ?> | 
    <strong>Completed:</strong> <?php echo $completedOrders; ?> | 
    <strong>Total Revenue:</strong> $<?php echo number_format($totalRevenue, 2); ?>
</div>
<?php
// Total Orders
$totalOrdersRes = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$totalOrders = $totalOrdersRes->fetch_assoc()['total_orders'];

// Total Revenue (sum of completed orders)
$totalRevenueRes = $conn->query("SELECT SUM(total_amount) AS total_revenue FROM orders WHERE status='completed'");
$totalRevenue = $totalRevenueRes->fetch_assoc()['total_revenue'] ?? 0;

// Pending Orders
$pendingOrdersRes = $conn->query("SELECT COUNT(*) AS pending_orders FROM orders WHERE status='pending'");
$pendingOrders = $pendingOrdersRes->fetch_assoc()['pending_orders'];

// Completed Orders
$completedOrdersRes = $conn->query("SELECT COUNT(*) AS completed_orders FROM orders WHERE status='completed'");
$completedOrders = $completedOrdersRes->fetch_assoc()['completed_orders'];
?>
<table>
    <tr>
        <th>Order ID</th>
        <th>User</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Items</th>
        <th>Actions</th>
    </tr>

    <?php
    $ordersRes = $conn->query("SELECT orders.*, users.name AS user_name FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC");

    if($ordersRes->num_rows > 0){
        while($order = $ordersRes->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$order['id']."</td>";
            echo "<td>".$order['user_name']."</td>";
            echo "<td>".$order['total_amount']."</td>";
            echo "<td>".$order['status']."</td>";

            // Fetch order items
            $itemsRes = $conn->query("SELECT order_items.*, products.name AS product_name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id=".$order['id']);
            $itemsList = "";
            while($item = $itemsRes->fetch_assoc()){
                $itemsList .= $item['product_name']." (Qty: ".$item['quantity'].")<br>";
            }
            echo "<td>".$itemsList."</td>";

            // Actions to update status
            echo "<td>
                <form action='update_order_status.php' method='post' style='display:inline'>
                    <input type='hidden' name='id' value='".$order['id']."'>
                    <select name='status'>
                        <option value='pending' ".($order['status']=='pending'?'selected':'').">Pending</option>
                        <option value='completed' ".($order['status']=='completed'?'selected':'').">Completed</option>
                        <option value='cancelled' ".($order['status']=='cancelled'?'selected':'').">Cancelled</option>
                    </select>
                    <button type='submit'>Update</button>
                </form>
            </td>";

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No orders yet</td></tr>";
    }

    $conn->close();
    ?>
</table>
</body>
</html>
<?php
$host = "sql107.infinityfree.com";      // your host from InfinityFree
$user = "if0_41562839";                 // your DB username
$pass = "PbZQDRIFSMXRXL";                              // your DB password (leave empty if none)
$db   = "if0_41562839_JoyMartWeed";     // your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
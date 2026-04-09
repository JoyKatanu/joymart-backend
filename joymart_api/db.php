<?php

$host = "dpg-d7blgl1r0fns73bkoun0-a.oregon-postgres.render.com";
$port = "5432";
$db   = "joymart_db";
$user = "joymart_db_user";
$pass = "fX7r6uVICTNdimtSryN7E1kNdpR9UJ64";

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
    die("Connection failed.");
}

?>
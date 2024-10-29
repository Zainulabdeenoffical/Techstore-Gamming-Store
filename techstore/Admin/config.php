<?php

$host = 'sql103.infinityfree.com'; // or your server address
$db = 'if0_37406853_techstore'; // your database name
$user = 'if0_37406853'; // your database username
$pass = 'bE0pyDa57O2mONc'; // your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


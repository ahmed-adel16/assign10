<?php
$db = null;
try {
    $db = mysqli_connect("localhost", "root", "", "product_manager");
} catch(Exception $e) {
    echo "Database Error: " . $e->getMessage();
} 
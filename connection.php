<?php
try{
$connection = mysqli_connect("localhost", "root", "", "enrollment_system");
} catch(Exception $e){
    echo "Database Error: " . $e->getMessage();
}
?>

<?php
try{
    $db = mysqli_connect("localhost", "root", "", "task_manager");
} catch(Exception $e) {
    echo "Database Error: " . $e->getMessage();
}
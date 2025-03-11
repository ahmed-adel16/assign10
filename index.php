<?php
header("Content-Type: application/json");
require('connection.php');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "POST":
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $errors = [];

        if (empty($name) || strlen($name) < 3) {
            $errors[] = "Product name must be at least 3 characters";
        }

        if (!is_numeric($price) || $price <= 0) {
            $errors[] = "Price must be a positive number";
        }

        if (!is_numeric($stock) || $stock < 0) {
            $errors[] = "Stock must be a non-negative number";
        }

        if (empty($errors)) {
            $sql = $db->query("INSERT INTO products (name, price, stock) VALUES ('$name', '$price', '$stock')");
            echo json_encode(["status" => "success", "message" => "Product added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => implode(', ', $errors)]);
        }
        break;

    case "GET":
        $result = $db->query("SELECT * FROM products");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $_GET['id'] ?? null;
        if ($id && isset($data['name'], $data['price'], $data['stock'])) {
            $db->query("UPDATE products SET name='{$data['name']}', price='{$data['price']}', stock='{$data['stock']}' WHERE id = $id");
            echo json_encode(["status" => "success", "message" => "Product updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid input or missing ID"]);
        }
        break;

    case "DELETE":
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db->query("DELETE FROM products WHERE id = $id");
            echo json_encode(["status" => "success", "message" => "Product deleted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Product ID is required"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
} ;
?>

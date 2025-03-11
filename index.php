<?php
header("Content-Type: application/json");
require('database.php');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "POST":
        $title = $_POST['title'] ?? '';
        $priority = $_POST['priority'] ?? '';
        $status = $_POST['status'] ?? '';
        $errors = [];

        if (empty($title) || strlen($title) < 3) {
            $errors[] = "Task title must be at least 3 characters";
        }

        if (!in_array($priority, ['low', 'medium', 'high'])) {
            $errors[] = "Priority must be low, medium, or high";
        }

        if (!in_array($status, ['pending', 'completed'])) {
            $errors[] = "Status must be pending or completed";
        }

        if (empty($errors)) {
            $sql = $db->query("INSERT INTO tasks (title, priority, status) VALUES ('$title', '$priority', '$status')");
            echo json_encode(["status" => "success", "message" => "Task added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => implode(', ', $errors)]);
        }
        break;

    case "GET":
        $result = $db->query("SELECT * FROM tasks");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $_GET['id'] ?? null;
        if ($id && isset($data['title'], $data['priority'], $data['status'])) {
            $db->query("UPDATE tasks SET title='{$data['title']}', priority='{$data['priority']}', status='{$data['status']}' WHERE id = $id");
            echo json_encode(["status" => "success", "message" => "Task updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid input or missing ID"]);
        }
        break;

    case "DELETE":
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db->query("DELETE FROM tasks WHERE id = $id");
            echo json_encode(["status" => "success", "message" => "Task deleted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Task ID is required"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
} 
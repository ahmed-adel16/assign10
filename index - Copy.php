<?php
header("Content-type: application/json");
require("connection.php");
$method = $_SERVER["REQUEST_METHOD"];

switch ($method){ // CRUD
    case "POST": // C (Creat)
        $name = $_POST["name"];
        $credits = $_POST["credits"];
        $description = $_POST["description"];
        if(!empty($name) && !empty($credits) && !empty($description)){ // validation
            $sql = "INSERT INTO `courses`(`name`, `description`, `credits`) VALUES ('$name','$description','$credits')";
            $connection->query($sql);
            http_response_code(200); // request succeeded
            echo json_encode(["status"=>"success", "message" => "Posted"]);
        }else{
            http_response_code(404); // required data not found
            echo json_encode(["status"=>"error",  "message" => "Invalid data"]);
        }
        break;
    case "GET": // R (Read)
        echo json_encode($connection->query("SELECT * FROM courses")->fetch_all(MYSQLI_ASSOC));
        break;
    case "PUT": // U (Update)
        $id = $_GET["id"];
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data["name"];
        $credits = $data["credits"];
        $description = $data["description"];
        $sql = "UPDATE `courses` SET `name`='$name',`description`='$description',`credits`='$credits' WHERE id = $id";
        $connection->query($sql);
        if($connection->affected_rows > 0){
            echo json_encode(["status"=>"success", "message" => "Updated"]);
        }else{
            http_response_code(404); // required data not found
            echo json_encode(["status"=>"error", "message" => "This id isn't exists"]);
        }
        break;
    case "DELETE": // D (Delete)
        if(isset($_GET["id"])){
            $id = $_GET["id"];
            $sql = "DELETE FROM courses WHERE id = $id";
            $connection->query($sql);
            if($connection->affected_rows == 1){
                echo json_encode(["status"=>"success", "message" => "DELETED"]);
            }else{
                http_response_code(404); // data not found
                echo json_encode(["status"=>"error", "message" => "This id isn't exists"]);
            }
    }else{
        http_response_code(404); // data not found
        echo json_encode(["status"=>"error", "message" => "There's no id provided"]);
    }
        break;
    default:
        http_response_code(400); // invalid request from the client
        echo json_encode(["message" => "undefined request method"]);
}


?>
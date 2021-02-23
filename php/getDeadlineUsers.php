<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST['deadline_id'])) {
    $sql = "SELECT saved_users.id, saved_users.created_at, wrapper.first_name, wrapper.second_name, wrapper.last_name, wrapper.email from saved_users, (select * from students) wrapper where deadline_id = ? and saved_users.os_id = wrapper.os_id";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $id = $_POST['deadline_id'];
        if($stmt->execute()) {
            $t = [];
            $stmt->bind_result($id, $created_at, $first_name, $second_name, $last_name, $email);
            while($stmt->fetch()) {
                $e = [
                    "id" => $id,
                    "first_name" => $first_name,
                    "last_name" => $second_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "created_at" => $created_at
                ];
                array_push($t, $e);
            }
            echo json_encode($t);
        }
    }
} else {
    echo json_encode([]);
}
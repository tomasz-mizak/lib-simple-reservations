<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST['deadline_id'])) {
    $sql = "SELECT saved_users.id, saved_users.created_at, saved_users.verify_time, wrapper.first_name, wrapper.second_name, wrapper.last_name, wrapper.email from saved_users, (select * from students) wrapper where deadline_id = ? and saved_users.os_id = wrapper.os_id and saved_users.active = 1";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $id = $_POST['deadline_id'];
        if($stmt->execute()) {
            $t = [];
            $stmt->bind_result($id, $created_at, $verify_time, $first_name, $second_name, $last_name, $email);
            while($stmt->fetch()) {
                $e = [
                    "id" => $id,
                    "first_name" => $first_name,
                    "last_name" => $second_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "created_at" => $created_at,
                    "verify_time" => $verify_time
                ];
                array_push($t, $e);
            }
            echo json_encode($t);
        }
    }
} else {
    echo json_encode([]);
}
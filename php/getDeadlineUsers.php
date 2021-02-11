<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST['deadline_id'])) {
    $sql = "SELECT id, email, created_at FROM saved_users WHERE deadline_id = ?";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $id = $_POST['deadline_id'];
        if($stmt->execute()) {
            $t = [];
            $stmt->bind_result($id,$email, $created_at);
            while($stmt->fetch()) {
                $e = [
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
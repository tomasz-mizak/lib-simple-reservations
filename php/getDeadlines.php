<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST["day"]) && isset($_POST['month']) && isset($_POST['year'])) {
    $datetime = new DateTime($_POST["day"] . "-" . $_POST['month'] . "-" . $_POST['year'] . " 23:59:59");
    $sql = "SELECT * FROM deadlines WHERE date BETWEEN ? AND ?";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param('ss', $date_1, $date_2);
        $date_1 = $datetime->format("Y-m-d");
        $date_2 = $datetime->format("Y-m-d H:i:s");
        if($stmt->execute()) {
            $stmt->bind_result($id, $author_id, $date, $max_student_count, $created_at);
            $t = [];
            while($stmt->fetch()) {
                $e = [
                    "id" => $id,
                    "author_id" => $author_id,
                    "date" => $date,
                    "max_student_count" => $max_student_count,
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

?>
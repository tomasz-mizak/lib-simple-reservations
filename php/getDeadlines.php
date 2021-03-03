<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST["day"]) && isset($_POST['month']) && isset($_POST['year'])) {
    $datetime = new DateTime($_POST["day"] . "-" . $_POST['month'] . "-" . $_POST['year'] . " 23:59:59");
    $sql = "SELECT deadlines.id, deadlines.author_id, deadlines.date, deadlines.max_student_count, deadlines.created_at, users.first_name, users.last_name FROM deadlines, users WHERE deadlines.date BETWEEN ? AND ? AND users.id = deadlines.author_id ORDER BY deadlines.date ASC";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param('ss', $date_1, $date_2);
        $date_1 = $datetime->format("Y-m-d");
        $date_2 = $datetime->format("Y-m-d H:i:s");
        if($stmt->execute()) {
            $stmt->bind_result($id, $author_id, $date, $max_student_count, $created_at, $first_name, $last_name);
            $t = [];
            while($stmt->fetch()) {
                $e = [
                    "id" => $id,
                    "author_id" => $author_id,
                    "date" => $date,
                    "max_student_count" => $max_student_count,
                    "created_at" => $created_at,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "studentsCount" => -1
                ];
                array_push($t, $e);
            }
            echo json_encode($t);
        }
    }
} else {
    $sql = "SELECT * FROM deadlines";
    $t = [];
    if($stmt = $link->prepare($sql)) {
        if($stmt->execute()) {
            $stmt->bind_result($id, $author_id, $date, $max_student_count, $created_at);
            while($stmt->fetch()) {
                $e = [
                  'id' => $id,
                  'date' => $date
                ];
                array_push($t, $e);
            }
        }
    }
    echo json_encode($t);
}

$link->close();

?>
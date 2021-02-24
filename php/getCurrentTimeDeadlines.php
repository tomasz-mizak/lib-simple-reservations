<?php

require_once "dbconn.php";
$datetime1 = new DateTime();
$datetime2 = new DateTime();
$datetime2->add(new DateInterval('P10D'));
$datetime2->setTime(23,59,59);
$sql = "SELECT deadlines.*, (SELECT COUNT(*) FROM saved_users WHERE saved_users.deadline_id = deadlines.id and saved_users.active = 1) AS total FROM deadlines WHERE date BETWEEN '".$datetime1->format("Y-m-d H:i:s")."' AND '".$datetime2->format("Y-m-d H:i:s")."'";
if($stmt = $link->prepare($sql)) {
    if($stmt->execute()) {
        $stmt->bind_result($id, $author_id, $date, $max_student_count, $created_at, $total);
        $t = [];
        while($stmt->fetch()) {
            if($total<$max_student_count) {
                $e = [
                    'id' => $id,
                    'author_id' => $author_id,
                    'date' => $date,
                    'max_student_count' => $max_student_count,
                    'created_at' => $created_at
                ];
                array_push($t, $e);
            }
        }
        echo json_encode($t);
    }
}
$link->close();
?>
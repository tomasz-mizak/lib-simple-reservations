<?php

    function isDeadlineRequestSafe($deadline_id) {
        if(is_int($deadline_id)) {
            require_once "dbconn.php";
            $datetime1 = new DateTime();
            $datetime2 = new DateTime();
            $datetime2->add(new DateInterval('P10D'));
            $datetime2->setTime(23,59,59);
            $sql = "SELECT deadlines.*, (SELECT COUNT(*) FROM saved_users WHERE saved_users.deadline_id = deadlines.id) AS total FROM deadlines WHERE date BETWEEN '".$datetime1->format("Y-m-d H:i:s")."' AND '".$datetime2->format("Y-m-d H:i:s")."'";
            if($stmt = $link->prepare($sql)) {
                if($stmt->execute()) {
                    $stmt->bind_result($id, $author_id, $date, $max_student_count, $created_at, $total);
                    while($stmt->fetch()) {
                        if($id==$deadline_id) {
                            if($total<$max_student_count) return true;
                        }
                    }
                }
            }
            $link->close();
        }
    }

    function isEmailRequestSafe($email) {
        if(!empty(trim($email))) {
            require_once "dbconn.php";
            $sql = "SELECT * FROM students where email = ?";
            if($stmt = $link->prepare($sql)) {
                $param_email = trim($email);
                $stmt->bind_param('s', $param_email);
                if($stmt->execute()) {
                    $stmt->store_result();
                    if($stmt->num_rows()>0) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

?>
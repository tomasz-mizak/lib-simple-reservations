<?php

    require_once "sesscheck.php";
    require_once "dbconn.php";

    if(isset($_POST['deadline_id'])) {

        $param_id = $_POST['deadline_id'];

        // get deadline date
        $sql = "SELECT date FROM deadlines WHERE id=?";
        if($stmt = $link->prepare($sql)) {
            $stmt->bind_param('i', $param_id);
            if($stmt->execute()) {
                $stmt->bind_result($date);
                // delete deadline from table
                $sql = "DELETE FROM deadlines WHERE id=?";
                if($stmt = $link->prepare($sql)) {
                    $stmt->bind_param('i', $param_id);
                    if($stmt->execute()) {
                        // send notification to students
                        $sql = "SELECT * FROM saved_users WHERE deadline_id=?";
                        if($stmt = $link->prepare($sql)) {
                            $stmt->bind_param('i', $param_id);
                            if($stmt->execute()) {
                                $stmt->bind_result($id, $deadline_id, $email, $created_at);
                                while($stmt->fetch()) {
                                    echo $email;
                                }
                            }
                        }
                    }
                }
            }
        }




    } else {
        // TODO
    }

    $link->close();

?>
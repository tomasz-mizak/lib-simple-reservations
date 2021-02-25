<?php

    require_once "sesscheck.php";
    require_once "dbconn.php";
    require_once "sendMail.php";
    require_once "config.php";

    if(isset($_POST['deadline_id'])) {

        $param_id = $_POST['deadline_id'];

        // get deadline date
        $sql = "SELECT date FROM deadlines WHERE id=?";
        if($stmt = $link->prepare($sql)) {
            $stmt->bind_param('i', $param_id);
            if($stmt->execute()) {
                $stmt->store_result();
                $stmt->bind_result($date);
                $stmt->fetch();
                // delete deadline from table
                $sql = "DELETE FROM deadlines WHERE id=?";
                if($stmt = $link->prepare($sql)) {
                    $stmt->bind_param('i', $param_id);
                    if($stmt->execute()) {
                        // send notification to students
                        //$sql = "SELECT id, deadline_id, ema FROM saved_users WHERE deadline_id=? AND active = 1";
                        $sql = "SELECT saved_users.id, saved_users.deadline_id, students.email FROM saved_users, students WHERE saved_users.deadline_id=? AND saved_users.active = 1 AND saved_users.os_id = students.os_id";
                        if($stmt = $link->prepare($sql)) {
                            $stmt->bind_param('i', $param_id);
                            if($stmt->execute()) {
                                $stmt->bind_result($id, $deadline_id, $email);
                                $t = "";
                                while($stmt->fetch()) {
                                    $t .= $email . ', ';
                                    sendMail($email, "Usunięcie terminu!", "Termin na który byłeś zarejestrowany - ".$date.", został usunięty.<br><a href='".WEBSITE_URL."/enrollment.php'>Zapisz się na inny termin</a>");
                                }
                                // delete student saves
                                $sql = "DELETE FROM saved_users WHERE deadline_id=?";
                                if($stmt = $link->prepare($sql)) {
                                    $stmt->bind_param('i', $deadline_id);
                                    if($stmt->execute()) {
                                        if(strlen($t)>0) {
                                            echo json_encode([
                                                'condition' => true,
                                                'error_message' => "Usunięto termin oraz wysłano wiadomość informacyjną o usunięciu terminu do zarejestrowanych osób:" . $t
                                            ]);
                                        } else {
                                            echo json_encode([
                                                'condition' => true,
                                                'error_message' => "Usunięto termin, nie było na nim żadnych zarejestrowanych osób"
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    $link->close();

?>
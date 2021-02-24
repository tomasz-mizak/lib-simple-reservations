<?php

    require_once "dbconn.php";
    require_once "sendMail.php";

    if(isset($_POST['deadlines'])&&isset($_POST['email'])) {
        $posted_deadlines = $_POST['deadlines'];
        // get available deadlines
        $avaliable_deadlines = [];
        $datetime1 = new DateTime();
        $datetime2 = new DateTime();
        $datetime2->add(new DateInterval('P10D'));
        $datetime2->setTime(23,59,59);
        $sql = "SELECT deadlines.id, deadlines.max_student_count, (SELECT COUNT(*) FROM saved_users WHERE saved_users.deadline_id = deadlines.id AND active = 1) AS total FROM deadlines WHERE date BETWEEN '".$datetime1->format("Y-m-d H:i:s")."' AND '".$datetime2->format("Y-m-d H:i:s")."'";
        if($stmt = $link->prepare($sql)) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $max_student_count, $total);
                while ($stmt->fetch()) {
                    array_push($avaliable_deadlines, [
                        'id' => $id,
                        'max_student_count' => $max_student_count,
                        'total' => $total
                    ]);
                }
            }
        }
        // check is posted deadlines are safety
        $safe = 0;
        for($i = 0; $i<count($posted_deadlines); $i++) {
            if(intval($posted_deadlines[$i])) {
                $deadline_id = $posted_deadlines[$i];
                for($k = 0; $k<count($avaliable_deadlines); $k++) {
                    $obj = $avaliable_deadlines[$k];
                    if($obj['id']==$deadline_id) {
                        // deadline exist
                        if($obj['total']<$obj['max_student_count']) { // check limit
                            $safe++;
                            break;
                        }
                    }
                }
            }
        }
        if($safe==count($posted_deadlines)) {
            $email = $_POST['email'];
            if(!empty(trim($email))) {
                $sql = "SELECT os_id FROM students where email = ?";
                if($stmt = $link->prepare($sql)) {
                    $param_email = trim($email);
                    $stmt->bind_param('s', $param_email);
                    if($stmt->execute()) {
                        $stmt->store_result();
                        if($stmt->num_rows()>0) {
                            $stmt->bind_result($user_id);
                            $stmt->fetch();

                            // check is user exist on selected terms
                            $exist = false;
                            $sql = "SELECT * FROM saved_users WHERE deadline_id = ? AND os_id = (select os_id from students where email = ?)";
                            for($i=0;$i<count($posted_deadlines);$i++) {
                                $deadline_id = intval($posted_deadlines[$i]);
                                if($stmt = $link->prepare($sql)) {
                                    $stmt->bind_param('is', $deadline_id, $email);
                                    if($stmt->execute()) {
                                        $stmt->store_result();
                                        if($stmt->num_rows>0) {
                                            $exist = true;
                                            break;
                                        }
                                    }
                                }
                            }

                            if(!$exist) {

                                // check is not more than 4 hours peer day
                                $hourLimit = true;
                                for($i=0;$i<count($posted_deadlines);$i++) {
                                    $sql = "select date from deadlines where id = ?";
                                    $deadline_id = intval($posted_deadlines[$i]);
                                    if($stmt = $link->prepare($sql)) {
                                        $stmt->bind_param('i', $deadline_id);
                                        if($stmt->execute()) {
                                            $stmt->store_result();
                                            if($stmt->num_rows>0) {
                                                $stmt->bind_result($date);
                                                $stmt->fetch();
                                                $d1 = new DateTime($date);
                                                $d1->setTime(0,0,0);
                                                $d2 = new DateTime($date);
                                                $d2->setTime(23,59,59);
                                                $sql = "select count(*) as amount from saved_users, deadlines where saved_users.deadline_id = deadlines.id and saved_users.os_id = (select os_id from students where email = ?) and deadlines.date BETWEEN '".$d1->format("Y-m-d H:i:s")."' AND '".$d2->format("Y-m-d H:i:s")."'";
                                                if($stmt=$link->prepare($sql)) {
                                                    $stmt->bind_param('s',$email);
                                                    if($stmt->execute()) {
                                                        $stmt->store_result();
                                                        if($stmt->num_rows>0) {
                                                            $stmt->bind_result($amount);
                                                            $stmt->fetch();
                                                            if($amount>=4) {
                                                                $hourLimit = false;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                if($hourLimit) {

                                    // save user
                                    $added = true;
                                    $hash = md5(rand(0,1000));
                                    for ($i = 0; $i < count($posted_deadlines); $i++) {
                                        $sql = "INSERT INTO saved_users (deadline_id, os_id, hash) VALUES (?,(select os_id from students where email = ?),?)";
                                        $deadline_id = intval($posted_deadlines[$i]);
                                        if ($stmt->prepare($sql)) {
                                            $stmt->bind_param('iss', $deadline_id, $email, $hash);
                                            if (!$stmt->execute()) {
                                                $added = false;
                                                break;
                                            }
                                        }
                                    }

                                    if ($added) {
                                        $message = "<a href='http://localhost/biblioteka/verify.php?hash=".$hash."&os_id=".$user_id."'>Kliknij by zweryfikować rezerwację termiu/terminów</a>";
                                        sendMail($email, "Weryfikacja zapisu na termin", $message);
                                        echo json_encode([
                                            'status' => true,
                                            'info' => "Na uczelniany adres email została wysłana wiadomość aktywacyjna. W celu potwierdzenia rezerwacji terminu/terminów, kliknij w link w wiadomości weryfikacyjnej. Pamiętaj by jak najszybciej zweryfikować terminy, póki nie będą aktywne, ktoś może je zarezerwować wcześniej!"
                                        ]);
                                    } else {
                                        echo json_encode([
                                            'status' => false,
                                            'info' => "Ups... coś poszło nie tak, spróbuj jeszcze raz, jeżeli problem się powtarza, skontaktuj się z użyciem maila w stopce."
                                        ]);
                                    }

                                } else {
                                    echo json_encode([
                                        'status' => false,
                                        'info' => "Nie możesz zarezerwować więcej niż 4h w danym dniu."
                                    ]);
                                }

                            } else {
                                echo json_encode([
                                    'status' => false,
                                    'info' => "Wygląda na to, że zapisałeś się już na ten termin!"
                                ]);
                            }

                        } else {
                            echo json_encode([
                                'status' => false,
                                'info' => "Błąd rezerwacji terminu! (#2)"
                            ]);
                        }
                    }
                }
            }
        } else {
            echo json_encode([
                'status' => false,
                'info' => "Błąd rezerwacji terminu! (#1)"
            ]);
        }
    }
    $link->close();

?>
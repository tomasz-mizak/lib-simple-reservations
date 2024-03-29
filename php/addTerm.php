<?php

require_once "sesscheck.php";

$t = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];

require_once "dbconn.php";

if(isset($_POST['singleTerm_time']) && isset($_POST['maxStudentCount_1'])) {

    $time = $_POST['singleTerm_time'];
    $studentCount = $_POST['maxStudentCount_1'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $datetime = new DateTime($year . "-" . $month . "-" . $day . " " . $time);
    $date = $datetime->format("Y-m-d H:i:s");

    $condition = false;
    $sql = "SELECT * FROM deadlines WHERE date=?";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param("s", $param_date);
        $param_date = $date;
        if($stmt->execute()) {
            $stmt->store_result();
            if($stmt->num_rows > 0) {
                $condition = true;
            }
        }
    }

    if($condition) {
        echo json_encode([
            "condition" => false,
            "error_message" => "Wystąpił błąd podczas dodawania nowego terminu, taki termin już istnieje!"
        ]);
       return;
    }

    $sql = "INSERT INTO deadlines (author_id, date, max_student_count) VALUES (?,?,?)";

    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param("isi", $param_author_id, $param_date, $param_max_student_count);
        $param_author_id = $_SESSION['id'];
        $param_date = $date;
        $param_max_student_count = $studentCount;
        if($stmt->execute()) {
            echo json_encode([
                "condition" => true,
                "error_message" => "Sukces! dodano termin!"
            ]);
        } else {
            echo json_encode([
                "condition" => false,
                "error_message" => "Wysąpił błąd [#003]"
            ]);
        }
    }

} elseif(isset($_POST['multipleTermTime_Start']) && isset($_POST['multipleTermTime_End']) && isset($_POST['maxStudentCount_2'])) {

    $start_time = $_POST['multipleTermTime_Start'];
    $end_time = $_POST['multipleTermTime_End'];

    if($start_time>$end_time) {
        echo json_encode([
            "condition" => true,
            "error_message" => "Data początkowa nie może być późniejsza niż końcowa!"
        ]);
        return;
    }

    $studentCount = $_POST['maxStudentCount_2'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $execCondition = true;
    $repeatingDeadline = false;
    for($i = $start_time; $i<=$end_time; $i++) {
        $datetime = new DateTime($year . "-" . $month . "-" . $day . " " . $t[$i]);
        $date = $datetime->format("Y-m-d H:i:s");

        $condition = false;
        $sql = "SELECT * FROM deadlines WHERE date=?";
        if($stmt = $link->prepare($sql)) {
            $stmt->bind_param("s", $param_date);
            $param_date = $date;
            if($stmt->execute()) {
                $stmt->store_result();
                if($stmt->num_rows > 0) {
                    $condition = true;
                }
            }
        }

        if($condition) {
            $repeatingDeadline = true;
            continue;
        }

        $sql = "INSERT INTO deadlines (author_id, date, max_student_count) VALUES (?,?,?)";
        if($stmt = $link->prepare($sql)) {
            $stmt->bind_param("isi", $param_author_id, $param_date, $param_max_student_count);
            $param_author_id = $_SESSION['id'];
            $param_date = $date;
            $param_max_student_count = $studentCount;
            if(!$stmt->execute()) {
                $execCondition = false;
            }
        }
    }

    if(!$execCondition) {
        echo json_encode([
            "condition" => false,
            "error_message" => "Wystąpił błąd [#002]"
        ]);
        return;
    }

    if($repeatingDeadline) {
        echo json_encode([
            "condition" => true,
            "error_message" => "Terminy zostały stworzone, jednak niektóre już istniały - zostały pominięte."
        ]);
        return;
    }

    echo json_encode([
        "condition" => true,
        "error_message" => "Dodano terminy!"
    ]);

} else {
    echo json_encode([
       "condition" => false,
       "error_message" => "Wystąpił błąd [#001]"
    ]);
}

$link->close();

?>

<?php

    require_once "dbconn.php";
    require_once "php/trySaveUser.php";
    // reserved_deadline_id
    // student_email_addres

    if(isset($_POST['deadline_id'])&&isset($_POST['user_email'])) {

        if(!isDeadlineRequestSafe($_POST['deadline_id'])) echo json_encode([
            'status' => false,
            'info' => "Błąd rezerwacji terminu!"
        ]);

        if(!isEmailRequestSafe($_POST['user_email'])) echo json_encode([
            'status' => false,
            'info' => "Błąd rezerwacji terminu!"
        ]);

        echo json_encode([
            'status' => true,
            'info' => "bla bla"
        ]);

    }

    $link->close();

?>
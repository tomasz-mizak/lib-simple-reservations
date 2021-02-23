<?php

    require_once "dbconn.php";
    if(isset($_POST['emailAddress']) && !empty(trim($_POST['emailAddress']))) {
        $sql = "SELECT * FROM students where email = ?";
        if($stmt = $link->prepare($sql)) {
            $param_email = trim($_POST['emailAddress']);
            $stmt->bind_param('s', $param_email);
            if($stmt->execute()) {
                $stmt->store_result();
                if($stmt->num_rows()>0) {
                    echo json_encode([
                        'status' => true,
                        'info' => 'Udało się zweryfikować poprawność adresu!'
                    ]);
                } else {
                    echo json_encode([
                        'status' => false,
                        'info' => 'Wpisany adres email jest niepoprawny!'
                    ]);
                }
            }
        }
    }
    $link->close();

?>
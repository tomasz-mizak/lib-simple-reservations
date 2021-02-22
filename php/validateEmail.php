<?php

    require_once "dbconn.php";
    if(isset($_POST['emailAddress'])) {
        $x = $_POST['emailAddress'];
        if(empty($x)) {
            echo "empty";
        } else {
            echo "not empty";
        }
    }

?>
<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST["day"]) && isset($_POST['month']) && isset($_POST['year'])) {
    $datetime = new DateTime($_POST["day"] . "-" . $_POST['month'] . "-" . $_POST['year']);
    $date = $datetime->format("Y-m-d");
    $sql = "SELECT * FROM deadlines WHERE date = ?";
    if($stmt = $link->prepare($sql)) {
        $stmt->bind_param('s', $date);
        if($stmt->execute()) {
            $stmt->
        }
    }
} else {
    return json_encode([]);
}

?>
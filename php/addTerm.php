<?php

require_once "sesscheck.php";

$t = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];

$err = "";
$selForm = 0;

require_once "dbconn.php";

if(isset($_POST['singleTerm_time']) && isset($_POST['maxStudentCount'])) {
    $selForm = 1;

    $time =

} elseif(isset($_POST['multipleTerm_startTime']) && isset($_POST['multipleTerm_endTime']) && isset($_POST['maxStudentCount'])) {
    $selForm = 2;

}

$link->close();

?>

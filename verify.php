<?php

    require_once "php/config.php";

?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <h1><?= PAGE_TITLE ?></h1>
    <h4><?= PAGE_SUBTITLE ?></h4>
</header>
<section>
    <?php

    require_once "php/sendMail.php";

    if(isset($_GET['os_id'])&&!empty($_GET['os_id'])&&isset($_GET['hash'])&&!empty($_GET['hash'])) {

        require_once "php/dbconn.php";

        $user_id = $_GET['os_id'];
        $hash = $_GET['hash'];

        $sql = "SELECT saved_users.id, saved_users.deadline_id, deadlines.date, deadlines.max_student_count, students.email, saved_users.materials FROM saved_users, deadlines, students WHERE saved_users.active = 0 AND saved_users.hash = ? AND saved_users.os_id = ? AND deadlines.id = saved_users.deadline_id AND students.os_id = saved_users.os_id";
        $param_email = '';
        if($stmt=$link->prepare($sql)) {
            $stmt->bind_param('si',$hash,$user_id);
            if($stmt->execute()) {
                $stmt->bind_result($save_id,$deadline_id, $date, $max_student_count, $email, $materials);
                $t = [];
                while ($stmt->fetch()) {
                    $e = [
                        'save_id' => $save_id,
                        'deadline_id' => $deadline_id,
                        'date' => $date,
                        'max_student_count' => $max_student_count,
                        'materials' => $materials
                    ];
                    $param_email = $email;
                    $param_materials = $materials;
                    array_push($t,$e);
                }
                if(count($t)==0) {
                    echo '<h3>Wygląda na to, że ta rezerwacja musiała już zostać potwierdzona!</h3>';
                }
                // check deadline limit
                $success = 0;
                $datestring = '';
                for($i=0;$i<count($t);$i++) {
                    $obj = $t[$i];
                    $sql = "SELECT count(*) as amount FROM saved_users WHERE deadline_id = ? and active = 1";
                    if($stmt=$link->prepare($sql)) {
                        $stmt->bind_param('i',$obj['deadline_id']);
                        if($stmt->execute()) {
                            $stmt->store_result();
                            if($stmt->num_rows>0) {
                                $stmt->bind_result($amount);
                                $stmt->fetch();
                                if($amount<$obj['max_student_count']) {
                                    $sql = "UPDATE saved_users SET hash = '', active = 1, verify_time = NOW()  WHERE id = ?";
                                    if($stmt=$link->prepare($sql)) {
                                        $stmt->bind_param('i',$obj['save_id']);
                                        if($stmt->execute()) {
                                            echo '<h4>Zweryfikowano termin - '.$obj["date"].'</h4><br>';
                                            $success++;
                                            $datestring.=$obj['date'].'<br>';
                                        }
                                    }
                                } else {
                                    $sql = "UPDATE saved_users SET hash = 'never activated', active = 0 WHERE id = ?";
                                    if($stmt=$link->prepare($sql)) {
                                        $stmt->bind_param('i',$obj['save_id']);
                                        if($stmt->execute()) {
                                            echo '<h4>Zabrakło miejsc na termin - '.$obj["date"].'</h4><br>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if($success>0) {
                    // send to student
                    echo '<h4>Na adres '.$param_email.' zostanie wysłane potwierdzenie rezerwacji terminu/terminów.</h4>';
                    $mess = "Potwierdzenie rezerwacji poniższych terminów:<br>".$datestring;
                    sendMail($param_email,"Potwierdzenie rezerwacji terminu/terminów",$mess);
                    // send info to admins
                    $mess = $param_email." zapisał się na poniższe terminy:<br>".$datestring."<br>Wskazał poniższe materiały:<br><div style='display: flex;flex-flow: column wrap;'>".$param_materials."</div><br><a href='".WEBSITE_URL."/admin.php'>Kliknij tu by przejść do panelu administracyjnego</a>";
                    sendMail(REGISTRATION_EMAIL,"Ktoś się zapisał na termin/terminy!",$mess);
                }
                echo '<a href="enrollment.php">Przejdź do ponownej rejestracji terminu</a>';
            }
        }

        $link->close();

    } else {
        header("Location: enrollment.php");
    }

    ?>
</section>
<footer>
    <ul>
        <li><b>Ważne linki</b></li>
        <li><a href="https://www.wpia.uni.lodz.pl" target="_blank">Strona główna WPiA</a></li>
        <li><a href="https://www.wpia.uni.lodz.pl/struktura/biblioteka" target="_blank">Strona biblioteki WPiA</a></li>
    </ul>
    <ul>
        <li><b>Kontakt</b></li>
        <li><a href="tel:426354625">tel. 42 635 46 25</a></li>
        <li><a href="mailto:tomasz.mizak@wpia.uni.lodz.pl">tomasz.mizak@wpia.uni.lodz.pl</a></li>
    </ul>
</footer>
<script>

</script>
</body>
</html>

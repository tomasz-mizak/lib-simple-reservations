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
    <h1>System rezerwacji terminów</h1>
    <h4>Wydział Prawa i Administracji Uniwersytetu Łódzkiego</h4>
</header>
<section>
    <?php

    if(isset($_GET['os_id'])&&!empty($_GET['os_id'])&&isset($_GET['hash'])&&!empty($_GET['hash'])) {

        require_once "php/dbconn.php";

        $user_id = $_GET['os_id'];
        $hash = $_GET['hash'];

        $sql = "SELECT saved_users.id, saved_users.deadline_id, deadlines.date, deadlines.max_student_count FROM saved_users, deadlines WHERE active = 0 AND hash = ? AND os_id = ? AND deadlines.id = saved_users.deadline_id";
        if($stmt=$link->prepare($sql)) {
            $stmt->bind_param('si',$hash,$user_id);
            if($stmt->execute()) {
                $stmt->bind_result($save_id,$deadline_id, $date, $max_student_count);
                $t = [];
                while ($stmt->fetch()) {
                    $e = [
                        'save_id' => $save_id,
                        'deadline_id' => $deadline_id,
                        'date' => $date,
                        'max_student_count' => $max_student_count
                    ];
                    array_push($t,$e);
                }
                if(count($t)==0) {
                    echo '<h3>Wygląda na to, że ta rezerwacja musiała już zostać potwierdzona!</h3><a href="enrollment.php">Przejdź do ponownej rejestracji terminu</a>';
                }
                // check deadline limit
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
                                    $sql = "UPDATE saved_users SET hash = '', active = 1 WHERE id = ?";
                                    if($stmt=$link->prepare($sql)) {
                                        $stmt->bind_param('i',$obj['save_id']);
                                        if($stmt->execute()) {
                                            echo '<h4>Zweryfikowano termin - '.$obj["date"].'</h4><br>';
                                        }
                                    }
                                } else {
                                    $sql = "UPDATE saved_users SET hash = 'never activated', active = 0 WHERE id = ?";
                                    if($stmt=$link->prepare($sql)) {
                                        $stmt->bind_param('i',$obj['save_id']);
                                        if($stmt->execute()) {
                                            echo '<h4>Zabrakło miejsc na termin - '.$obj["date"].'</h4><br>';
                                            echo '<a href="enrollment.php">Przejdź do ponownej rejestracji terminu</a>';
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

    } else {
        header("Location: enrollment.php");
    }

    ?>
</section>
<footer>
    <ul>
        <b>Ważne linki</b>
        <li>Strona główna WPiA</li>
        <li>Strona biblioteki WPiA</li>
    </ul>
    <ul>
        <b>Kontakt</b>
        <li>tel. 42 635 46 25</li>
        <li>tomasz.mizak@wpia.uni.lodz.pl</li>
    </ul>
</footer>
<script>

</script>
</body>
</html>

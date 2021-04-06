<?php

    require_once "php/config.php";

    session_start();

    $login_error = "";
    if(isset($_SESSION['login_error'])) {
        $login_error = $_SESSION['login_error'];
    }

    $last_username = "";
    if(isset($_SESSION['last_username'])) {
        $last_username = $_SESSION['last_username'];
    }

?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>System rezerwacji terminów</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<header>
    <h1><?= PAGE_TITLE ?></h1>
    <h4><?= PAGE_SUBTITLE ?></h4>
</header>
<section>
    <div class="login">
        <h1>Logowanie do systemu</h1>
        <form action="php/signin.php" method="post">
            <div>
                <label for="logonName">Identyfikator:</label>
                <input type="email" id="logonName" name="logonName" value="<?= $last_username ?>">
            </div>
            <div>
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="passwd">
            </div>
            <div>
                <input type="submit">
            </div>
            <div>
                <label class="errorLabel"><?= $login_error; ?></label>
            </div>
        </form>
    </div>
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
        <li><a href="mailto:helpdesk@wpia.uni.lodz.pl">helpdesk@wpia.uni.lodz.pl</a></li>
    </ul>
</footer>
<script>

</script>
</body>
</html>
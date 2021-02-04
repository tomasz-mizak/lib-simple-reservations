<?php

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
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<header>
    <h1>System rezerwacji terminów</h1>
    <h4>Wydział Prawa i Administracji Uniwersytetu Łódzkiego</h4>
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
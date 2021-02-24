<?php

require_once "php/sesscheck.php";

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
    <link rel="stylesheet" href="assets/css/chpsswd.css">
</head>
<body>
<header>
    <h1>System rezerwacji terminów</h1>
    <h4>Wydział Prawa i Administracji Uniwersytetu Łódzkiego</h4>
</header>
<section>
    <div>
        <a href="admin.php">Wróć do poprzedniego widoku</a>
    </div>
    <div class="chpsswd">
        <h3>Zmiana hasła do konta <?= $_SESSION['username'] ?></h3>
        <div>
            <label for="oldPassword">Stare hasło:</label>
            <input id="oldPassword" type="password">
        </div>
        <div>
            <label for="newPassword">Nowe hasło:</label>
            <input id="newPassword" type="password">
        </div>
        <div>
            <label for="repeatNewPassword">Powtórz nowe hasło:</label>
            <input id="repeatNewPassword" type="password">
        </div>
        <div>
            <button>Zmień hasło</button>
        </div>
        <div>
            <label class="errorLabel"></label>
        </div>
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
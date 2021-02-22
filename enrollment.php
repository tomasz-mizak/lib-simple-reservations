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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/enrollment.css">
</head>
<body>
<header>
    <h1><?= PAGE_TITLE ?></h1>
    <h4><?= PAGE_SUBTITLE ?></h4>
</header>
<section>
    <div class="enrollment">
        <h1>Rezerwowanie terminu</h1>
        <div class="enrollment_view">
            <form method="post" action="php/validateEmail.php" id="vform">
                <label for="emailAddress">Podaj uczelniany adres email</label>
                <input type="email" id="emailAddress">
                <input type="submit" value="Dalej">
            </form>
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
    $('#vform').submit((event) => {
        event.preventDefault();
        $.ajax({
            type: 'post',
            url: 'php/validateEmail.php',
            data: {
                emailAddress:
            },
            success: (res) => {
                alert(res);
            }
        })
    })
</script>
</body>
</html>
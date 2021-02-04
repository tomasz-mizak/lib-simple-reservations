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
    <link rel="stylesheet" href="assets/css/calendar.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<header>
    <h1><?= PAGE_TITLE ?></h1>
    <h4><?= PAGE_SUBTITLE ?></h4>
</header>
<section>
    <div class="enrollment">
        <h1>Rezerwowanie terminu</h1>
        <form id="enrollmentForm" action="">
            <div>
                <label for="albumNumber">Numer indeksu:</label>
                <input type="text" id="albumNumber">
            </div>
            <div>
                <label for="emailAddress">Adres email:</label>
                <input type="email" id="emailAddress">
            </div>
            <div>
                <b>Wybierz stanowisko oraz termin z listy dostępnych terminów:</b>
                <div>
                    <label for="enrollmentForm">Dzień:</label>
                    <select name="" id="day" form="enrollmentForm">
                        <option value="1">12.01.2021</option>
                        <option value="2">02.02.2021</option>
                        <option value="3">3.01.2021</option>
                    </select>
                </div>
                <div>
                    <label for="enrollmentForm">Godzina:</label>
                    <select name="" id="day" form="enrollmentForm">
                        <option value="1">12:00</option>
                        <option value="2">11:00</option>
                        <option value="3">14:00</option>
                    </select>
                </div>
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
</body>
</html>
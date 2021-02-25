<?php

require_once "php/config.php";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/chpsswd.css">
</head>
<body>
<header>
    <h1><?= PAGE_TITLE ?></h1>
    <h4><?= PAGE_SUBTITLE ?></h4>
</header>
<section>
    <div>
        <a href="admin.php">Wróć do poprzedniego widoku</a>
    </div>
    <div class="chpsswd">
        <h3>Zmiana hasła</h3>
        <div>
            <label for="email">Adres email:</label>
            <input id="email" type="email">
        </div>
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
            <button onclick="sendRequest()">Zmień hasło</button>
        </div>
        <div>
            <label id="errorLabel" style="color: crimson;"></label>
        </div>
    </div>
</section>
<script>
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    const sendRequest = () => {
        const email = $('#email').val();
        const oldPassword = $('#oldPassword').val();
        const newPassword = $('#newPassword').val();
        const repeatNewPassword = $('#repeatNewPassword').val();
        if(isEmail(email)) {
            if(oldPassword.length>0) {
                if(newPassword.length>4) {
                    if(newPassword==repeatNewPassword) {
                        $.ajax({
                            type: 'post',
                            url: 'php/changePassword.php',
                            data: {
                                email: email,
                                oldPassword: oldPassword,
                                newPassword: newPassword
                            },
                            success: (res) => {
                                $('#errorLabel').html(res)
                            }
                        })
                    } else {
                        $('#errorLabel').html('Hasła się nie zgadzają!')
                    }
                } else {
                    $('#errorLabel').html('Minimalna ilość znaków nowego hasła to 4!')
                }
            } else {
                $('#errorLabel').html('Błędne stare hasło!')
            }
        } else {

            $('#errorLabel').html('Wpisz poprawny adres email!')
        }
    }
</script>
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
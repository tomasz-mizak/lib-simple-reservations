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
    <div class="selectTermCalendar">
        <div class="view">
            <button>Poniedziałek - 27.03.2022 (12/24)</button>
            <button>Wtorek - 27.03.2022 (2/6)</button>
            <button>Niedziela - 27.03.2022 (2/7)</button>
            <button>Sobota - 27.03.2022 (3/1)</button>
        </div>
        <div class="options">
            <button> << </button>
            <button> >> </button>
        </div>
    </div>
    <div class="enrollment">
        <h1>Rezerwowanie terminu</h1>
        <div class="enrollment_view" id="enrollment_start">
            <h3>Krok 1/3</h3>
            <label for="emailAddress">Podaj uczelniany adres email</label>
            <input type="text" id="emailAddress">
            <button onclick="verifyEmailAddress()">Przejdź dalej</button>
            <span class="error" id="emailAddressError"></span>
        </div>
        <div class="enrollment_view" id="enrollment_select_object">
            <h3>Krok 2/3</h3>
            <label for="">Wpisz tytuł książki/czasopisma</label>
            <input type="text" id="objectTitle">
            <label for="">Możesz wprowadzić dodatkową informację</label>
            <textarea rows="3" cols="40" id="additionalMessage"></textarea>
            <span class="error" id="objectError"></span>
            <button onclick="verifyObject()">Przejdź do wyboru terminu</button>
        </div>
        <div class="enrollment_view" id="enrollment_choose_deadline">
            <h3>Krok 3/3</h3>
            <label for="">Poniżej wybierz termin z listy dostępnych</label>

            <div class="selectTermCalendar">
                <button>Poniedziałek - 27.03.2022</button>
            </div>

            <div>
                <select name="" id="dselect">
                    <option value="" disabled selected>Wybierz dzień</option>

                </select>
                <select name="" id="hselect">
                    <option value="" disabled selected>Wybierz godzinę</option>
                    <option value="">9:00 (0/2)</option>
                    <option value="">10:00 (0/2)</option>
                    <option value="">11:00 (0/2)</option>
                    <option value="">12:00 (0/2)</option>
                    <option value="">13:00 (0/2)</option>
                </select>
            </div>
            <input type="submit" value="Prześlij rezerwację">
            <small >Na podany adres email, mogą przyjść informacje dotyczące zarezerwowanego terminu. Proszę obserwować w razie zmian.</small>
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
    let email;
    let objTitle;
    let addMessage;

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    const verifyEmailAddress = () => {
        if(isEmail($('#emailAddress').val())) {
            $.ajax({
                type: 'post',
                url: 'php/validateEmail.php',
                data: {
                    emailAddress: $('#emailAddress').val()
                },
                success: (res) => {
                    res = JSON.parse(res);
                    if(res.status) {
                        $('#enrollment_start').hide();
                        $('#enrollment_select_object').show();
                    } else {
                        $('#emailAddressError').html(res.info)
                    }
                }
            })
        } else {
            $('#emailAddressError').html('Został podany błędny adres email!')
        }
    }
    const verifyObject = () => {
        let title = $('#objectTitle').val();
        let mess = $('#additionalMessage').val();
        if(title.length>0) {
            $('#objectError').html('');
            $('#enrollment_select_object').hide();
            $('#enrollment_choose_deadline').show();
            $.ajax({
                type: 'post',
                url: 'php/getCurrentTimeDeadlines.php',
                success: (res) => {
                    res = JSON.parse(res);
                    res.forEach((v,i) => {
                        res[i].date = new Date(v.date)
                    });
                }
            })
        } else {
            $('#objectError').html('Uzupełnij tytuł książki/czasopisma!');
        }
    }
    $('#dselect').on('change', () => {
        $('#hselect').show();

    })
    // initialization
    $('#enrollment_select_object').hide();
    $('#enrollment_choose_deadline').hide();
    $('#hselect').hide();

</script>
</body>
</html>
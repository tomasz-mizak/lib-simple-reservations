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
        <div class="enrollment_view" id="enrollment_start">
            <h3>Krok 1/3</h3>
            <label for="emailAddress">Podaj uczelniany adres email</label>
            <input type="text" id="emailAddress">
            <button class="nbtn" onclick="verifyEmailAddress()">Przejdź dalej</button>
            <span class="error" id="emailAddressError"></span>
        </div>
        <div class="enrollment_view" id="enrollment_select_object">
            <h3>Krok 2/3</h3>
            <label for="">Wpisz tytuł książki/czasopisma</label>
            <input type="text" id="objectTitle">
            <label for="">Możesz wprowadzić dodatkową informację</label>
            <textarea rows="3" cols="40" id="additionalMessage"></textarea>
            <span class="error" id="objectError"></span>
            <button class="nbtn" onclick="verifyObject()">Przejdź do wyboru terminu</button>
        </div>
        <div class="enrollment_view" id="enrollment_choose_deadline">
            <h3>Krok 3/3</h3>
            <label for="">Poniżej wybierz termin z listy dostępnych</label>
            <div class="choose_deadline_view">
                <select name="" id="dselect">
                    <option value="" disabled selected>Wybierz dzień</option>
                </select>
                <select name="" id="hselect" multiple>
                    <option value="" disabled selected>Wybierz godzinę</option>
                </select>
                <small class="ctrlaria" id="hselect_aria">* przytrzymując przycisk CTRL, możesz wybrać kilka godzin na raz, maksymalna ilość rezerwacji na dzień to 4h.</small>
            </div>
            <button class="nbtn" onclick="sendRequest()">Prześlij rezerwację</button>
            <span class="error" id="sendRequest"></span>
        </div>
        <div class="enrollment_view" id="request_result"></div>
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
    let terms = [];
    let f_terms = [];
    const padLeadingZeros = (num, size) => {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }
    let dateExist = (date) => {
        for(let i = 0; i<f_terms.length; i++) {
            let obj = f_terms[i].date;
            if(date.getDate()==obj.getDate() && date.getMonth()==obj.getMonth() && date.getFullYear()==obj.getFullYear()) {
                return true;
            }
        }
    }
    let getDateHours = (date) => {
        let t = [];
        for(let i = 0; i<terms.length; i++) {
            let obj = terms[i].date;
            if(date.getDate()==obj.getDate() && date.getMonth()==obj.getMonth() && date.getFullYear()==obj.getFullYear()) {
                t.push(terms[i]);
            }
        }
        return t;
    }
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
                        email = $('#emailAddress').val();
                    } else {
                        $('#emailAddressError').html(res.info)
                    }
                }
            })
        } else {
            $('#emailAddressError').html('Został podany błędny adres email!')
        }
    }
    const weekDayNames = ['poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota', 'niedziela'];
    const verifyObject = () => {
        let title = $('#objectTitle').val();
        let mess = $('#additionalMessage').val();
        if(title.length>0) {
            objTitle = title;
            $('#objectError').html('');
            $('#enrollment_select_object').hide();
            $('#enrollment_choose_deadline').show();
            $.ajax({
                type: 'post',
                url: 'php/getCurrentTimeDeadlines.php',
                success: (res) => {
                    res = JSON.parse(res);
                    res.forEach((v,i) => {
                        res[i].date = new Date(v.date);
                    });
                    terms = res;
                    f_terms.push(terms[0]);
                    for(let i = 0; i<terms.length; i++) {
                        if(!dateExist(terms[i].date)) {
                            f_terms.push(terms[i]);
                        }
                    }
                    f_terms.sort((a,b) =>  a.date - b.date);
                    f_terms.forEach((v,i) => {
                        $('#dselect').append(`<option value="${i}">${weekDayNames[v.date.getDay()-1]} - ${v.date.getDate()}-${v.date.getMonth()+1}-${v.date.getFullYear()}</option>`);
                    });
                }
            });
        } else {
            $('#objectError').html('Uzupełnij tytuł książki/czasopisma!');
        }
    };
    let sendRequest = () => {
        let x = $('#hselect').val()
        if(x.length<=4) {
            $.ajax({
                type: 'post',
                url: 'php/trySaveUser.php',
                data: {
                    deadlines: x,
                    email: email
                },
                success: (res) => {
                    console.log(res)
                    res = JSON.parse(res);
                    if(res.status) {
                        $('#enrollment_choose_deadline').hide();
                        $('#request_result').show();
                        $('#request_result').html(res.info);
                    } else {
                        $('#sendRequest').html(res.info);
                    }
                }
            })
        } else {
            $('#sendRequest').html('Możesz wybrać maksymalnie 4 godziny z listy dostępnych!')
        }
    };
    $('#dselect').on('change', () => {
        $('#hselect').show();
        $('#hselect_aria').show();
        let i = $('#dselect').children("option:selected").val()
        let t = getDateHours(f_terms[i].date);
        t.sort((a,b) => a.date - b.date);
        $('#hselect').html('');
        t.forEach((v,k) => {
            $('#hselect').append(`
                <option value="${v.id}">${padLeadingZeros(v.date.getHours(),2)}:${padLeadingZeros(v.date.getMinutes(),2)}</option>
            `);
        });

    })
    // initialization
    $('#enrollment_select_object').hide();
    $('#enrollment_choose_deadline').hide();
    $('#hselect').hide();
    $('#hselect_aria').hide();
    $('#request_result').hide();


</script>
</body>
</html>
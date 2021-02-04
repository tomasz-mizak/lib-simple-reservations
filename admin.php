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
    <link rel="stylesheet" href="assets/css/calendar.css">
</head>
<body>
    <header>
        <h1><?= PAGE_TITLE ?></h1>
        <h4><?= PAGE_SUBTITLE ?></h4>
    </header>
    <section>
        <div class="message_box">
            Witaj <?= $_SESSION['first_name'] ?>, w tym panelu, możesz dodać nowe terminy do kalendarza.<br>
            Możesz również ustanowić ile terminów dziennie, może zarezerwować student.<br>Weryfikacja osób, odbywa się za pomocą podania numeru indeksu, oraz wysyłanego kodu na adres email, owy kod student musi wpisać podczas rezerwacji terminu.
        </div>
        <div class="calendar">
            <div class="calendar_view">
                <h2 id="currentMonthAndYear"></h2>
                <div class="table_wrapper">
                    <table>
                        <thead>
                            <tr class="header">
                                <th>pon</th>
                                <th>wt</th>
                                <th>śr</th>
                                <th>czw</th>
                                <th>pn</th>
                                <th>sob</th>
                                <th>niedz</th>
                            </tr>
                        </thead>
                        <tbody id="calendar_days">

                        </tbody>
                    </table>
                </div>
                <div class="calendar_view_options">
                    <button onclick="previousMonth()">Poprzedni miesiąc</button>
                    <button onclick="nextMonth()">Następny miesiąc</button>
                    <button>Dodaj termin</button>
                </div>
            </div>
            <div class="calendar_details">
                <h4>Planowane terminy rezerwacji na dzień 01.01.2021 (poniedziałek):</h4>
                <ul>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="deadline">
                            <div class="deadline_info">16:00 - 17:00 (wolny)</div>
                            <div class="deadline_options">
                                <button>Wyrezerwuj termin</button>
                                <button>Usuń termin</button>
                            </div>
                        </div>
                    </li>

                </ul>
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

        const capitalize = (s) => {
            if (typeof s !== 'string') return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        }

        const daysInMonth = (month, year) => {
            return new Date(year, month, 0).getDate();
        }

        const monthNames = ['styczeń', 'luty', 'marzec', 'kwiecień', 'maj',
                            'czerwiec', 'lipiec', 'sierpień', 'wrzesień',
                            'październik', 'listopad', 'grudzień'];

        const weekDayNames = ['poniedziałek', 'wtorek', 'środa',
                              'czwartek', 'piątek', 'sobota', 'niedziela'];

        const date = new Date();

        let currentMonth = date.getMonth()+1
        let currentYear = date.getFullYear()

        const loadDays = () => {
            $('#calendar_days').html('');
            const weeks = Math.ceil(daysInMonth(currentMonth, currentYear)/7)
            for(let i = 1; i<=weeks; i++) {
                let row = '<tr>';
                for(let k = 1; k<=7; k++) {
                    if(k+((i-1)*7)>daysInMonth(currentMonth, currentYear)) break;
                    row+=`<th><button>${k+((i-1)*7)}</button></th>`
                }
                row+='</tr>'
                $('#calendar_days').append(row);
            };
        }

        const updateViewData = () => {
            $('#currentMonthAndYear').html(`${capitalize(monthNames[currentMonth-1])}, ${currentYear}`);
            loadDays();
        }

        const nextMonth = () => {
            if(currentMonth==12) {
                currentYear++;
                currentMonth = 1;
            } else {
                currentMonth++;
            }
            updateViewData();
        }

        const previousMonth = () => {
            if(currentMonth==1) {
                currentYear--;
                currentMonth = 12;
            } else {
                currentMonth--;
            }
            updateViewData();
        }

        // first load
        $(document).ready(function () {
            loadDays();
            updateViewData();
        });
        //$('#calendar_days').append('<tr><th>hello world</th></tr>');


    </script>
</body>
</html>
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
            <br><br>
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
                        <tbody id="calendar_days"></tbody>
                    </table>
                </div>
                <div class="calendar_view_options">
                    <button onclick="previousMonth()">Poprzedni miesiąc</button>
                    <button onclick="nextMonth()">Następny miesiąc</button>
                </div>
            </div>
            <div class="calendar_details">
                <div class="calendar_display_options">
                    <h3 id="selectedDay"></h3>
                    <div>
                        <button onclick="">Dodaj termin</button>
                        <button onclick="displayCalendarView()">Wróć</button>
                    </div>
                </div>
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
    <?php
        require_once "php/dbconn.php";
        $sql = "SELECT * FROM deadlines";
        $deadlines = [];
        if($result = $link->query($sql)) {
            while($row = $result->fetch_assoc()) {
                array_push($deadlines, [ 'id' => $row['id'], 'date' => $row['date'] ]);
            }
        }
        $link->close();
    ?>
    <script>

        const loadedDeadlines = <?php echo json_encode($deadlines); ?>;

        loadedDeadlines.forEach((v,i) => {
            loadedDeadlines[i].date = new Date(v.date);
        })

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
        let selDay = 1

        const displayCalendarView = () => {
            $('.calendar_view').css({"display":"flex"});
            $('.calendar_details').css({"display":"none"});
        }

        const displayDayDescription = (day, weekday) => {
            selDay = day;
            $('.calendar_view').css({"display":"none"});
            $('.calendar_details').css({"display":"flex"});
            $('#selectedDay').html(`${weekDayNames[weekday-1]} - ${selDay}.${currentMonth}.${currentYear}`);
        }

        const loadDays = () => {
            $('#calendar_days').html('');
            const weeks = Math.ceil(daysInMonth(currentMonth, currentYear)/7)
            for(let i = 1; i<=weeks; i++) {
                let row = '<tr>';
                for(let k = 1; k<=7; k++) {
                    let day = k+((i-1)*7);
                    if(day>daysInMonth(currentMonth, currentYear)) break;
                    let is_tagged = false;
                    for(let f = 1; f<=loadedDeadlines.length; f++) {
                        const v = loadedDeadlines[f-1];
                        if(currentYear!=v.date.getFullYear()) break;
                        if(currentMonth!=v.date.getMonth()+1) break;
                        if(day == v.date.getDay()) {
                            is_tagged = true;
                            break;
                        };
                    }
                    if(is_tagged) {
                        row+=`<th><button class="tagged" onclick="displayDayDescription(${day}, ${k})">${day}</button></th>`
                    } else {
                        row+=`<th><button onclick="displayDayDescription(${day}, ${k})">${day}</button></th>`
                    }
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
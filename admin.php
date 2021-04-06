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
    <title>System rezerwacji terminów</title>
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
            Witaj w panelu administracyjnym, w tym miejscu możesz zarządzać terminami rezerwacji oraz swoim kontem.
        </div>
        <div id="accpanel">
            <p><b>Konto:</b> <?= $_SESSION['username'] ?></p>
            <a href="php/logout.php">Wyloguj</a>
            <a href="chpsswd.php">Zmień hasło</a>
        </div>
        <div class="calendar">
            <div class="calendar_view">
                <h2 id="currentMonthAndYear"></h2>
                <div class="table_wrapper">
                    <table>
                        <thead>
                            <!--<tr class="header">
                                
                                                                    <th>pon</th>
                                <th>wt</th>
                                <th>śr</th>
                                <th>czw</th>
                                <th>pt</th>
                                <th>sob</th>
                                <th>niedz</th>
                                   
                            </tr> -->
                        </thead>
                        <tbody id="calendar_days"></tbody>
                    </table>
                </div>
                <div class="calendar_view_options">
                    <button onclick="calendar_displayPreviousMonth()"> << </button>
                    <button onclick="calendar_displayNextMonth()"> >> </button>
                </div>
            </div>
            <div class="calendar_details">
                <div class="calendar_display_options">
                    <h3 id="selectedDay"></h3>
                    <div>
                        <button onclick="showTermCreation()">Dodaj termin</button>
                        <button onclick="displayCalendarView()">Wróć</button>
                    </div>
                </div>
                <ul id="availableDeadlines"></ul>
            </div>
            <div id="calendar_deadlineStudents">
                <button class="closeDeadlineStudents" onclick="hideStudentsView()">Wróć</button>
                <table class="students">
                    <thead>
                        <tr>
                            <th>Lp.</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Adres email</th>
                            <th>Czas rezerwacji</th>
                            <th>Czas weryfikacji</th>
                            <th>Materiały</th>
                            <th>Opcje</th>
                        </tr>
                    </thead>
                    <tbody id="students-container"></tbody>
                </table>
            </div>
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
    <div class="create_deadline">
        <div class="create_wrapper">
            <div class="create_selection">
                <button onclick="displaySingleTermForm()">Pojedyńczy termin</button>
                <button onclick="displayMultipleTermForm()">Seria terminów</button>
                <button onclick="cancelTermCreation()">Anuluj</button>
            </div>
            <div id="singleTermForm">
                <button onclick="backToCreationSelection()" class="backButton">Powrót</button>
                <form action="php/addTerm.php" method="POST" id="sform">
                    <div>
                        <label for="singleTermTime">Godzina</label>
                        <select name="singleTerm_time" id="singleTermTime"></select>
                    </div>
                    <div>
                        <label for="">Maks osób</label>
                        <select name="maxStudentCount_1" id="maxStudentCount_1"></select>
                    </div>
                    <div>
                        <input type="submit" value="Dodaj termin">
                    </div>
                </form>
            </div>
            <div id="multipleTermForm">
                <button onclick="backToCreationSelection()" class="backButton">Powrót</button>
                <form action="php/addTerm.php" method="POST" id="mform">
                    <div>
                        <label for="singleTermTime">Wybierz początkową godzinę</label>
                        <select name="multipleTerm_startTime" id="multipleTermTime_Start"></select>
                    </div>
                    <div>
                        <label for="singleTermTime">Wybierz końcową godzinę</label>
                        <select name="multipleTerm_endTime" id="multipleTermTime_End"></select>
                    </div>
                    <div>
                        <label for="">Maks osób</label>
                        <select name="maxStudentCount_2" id="maxStudentCount_2"></select>
                    </div>
                    <div>
                        <input type="submit" value="Dodaj termin">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

        'use strict';

        // initials
        const workHours = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];
        const monthNames = ['styczeń', 'luty', 'marzec', 'kwiecień', 'maj',
            'czerwiec', 'lipiec', 'sierpień', 'wrzesień',
            'październik', 'listopad', 'grudzień'];
        const weekDayNames = ['poniedziałek', 'wtorek', 'środa',
            'czwartek', 'piątek', 'sobota', 'niedziela'];

        const date = new Date();
        let currentMonth = date.getMonth()+1
        let currentYear = date.getFullYear()
        let selDay = 1

        // functions
        const displaySingleTermForm = () => {
            $('.create_selection').hide();
            $('#singleTermForm').show();
        };

        const displayMultipleTermForm = () => {
            $('.create_selection').hide();
            $('#multipleTermForm').show();
        };

        const backToCreationSelection = () => {
            $('#singleTermForm').hide();
            $('#multipleTermForm').hide();
            $('.create_selection').show();
        };

        const cancelTermCreation = () => {
            $('.create_deadline').hide();
        };

        const showTermCreation = () => {
            $('.create_deadline').show();
        };

        const showStudentsView = (id) => {
            $('.calendar_details').hide();
            $('#calendar_deadlineStudents').show();
            $('#students-container').html('');
            $.ajax({
                type: 'post',
                url: 'php/getDeadlineUsers.php',
                data: { deadline_id: id },
                success: (res) => {
                    res = JSON.parse(res);
                    res.forEach((v,i) => {
                        $('#students-container').append(`
                            <tr>
                                <td>${i+1}</td>
                                <td>${v.first_name}</td>
                                <td>${v.last_name}</td>
                                <td><a href="mailto:${v.email}">${v.email}</a></td>
                                <td>${v.created_at}</td>
                                <td>${v.verify_time}</td>
                                <td class="material-aligner">${v.materials}</td>
                                <td>
                                    <button onclick="alert('opcja nie jest dostępna w tej wersji')">usuń</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            });
        }

        const hideStudentsView = () => {
            $('.calendar_details').show();
            $('#calendar_deadlineStudents').hide();
        }

        const capitalize = (s) => {
            if (typeof s !== 'string') return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        };

        const daysInMonth = (month, year) => {
            return new Date(year, month, 0).getDate();
        };

        const displayCalendarView = () => {
            calendar_updateView();
            $('.calendar_view').show();
            $('.calendar_details').hide();
        };

        const padLeadingZeros = (num, size) => {
            var s = num+"";
            while (s.length < size) s = "0" + s;
            return s;
        }

        const loadDayDeadlines_html = (t) => {
            t.forEach((v,i) => {
                let d = new Date(v.date);
                $('#availableDeadlines').append(`
                    <li>
                        <div class="deadline">
                            <div class="deadline_info"><b>Godzina: ${padLeadingZeros(d.getHours(),2)}:${padLeadingZeros(d.getMinutes(),2)}, zapisane osoby: <span id="oslimit-${v.id}">ładowanie</span>/${v.max_student_count}</b><br>Stworzony przez: ${v.first_name} ${v.last_name} [${v.author_id}]</div>
                            <div class="deadline_options">
                                <button onclick="deleteDeadline(${v.id})">Usuń termin</button>
                                <button onclick="showStudentsView(${v.id})">Podgląd osób</button>
                            </div>
                        </div>
                    </li>`);
                });
        }

        const loadDayDeadlines = () => {
            $('#availableDeadlines').html('');
            $.ajax({
                type: 'post',
                url: 'php/getDeadlines.php',
                data: {
                    day: selDay,
                    month: currentMonth,
                    year: currentYear
                },
                success: (response) => {
                    response = JSON.parse(response);
                    loadDayDeadlines_html(response)
                    response.forEach((v,i) => {
                        $.ajax({
                            type: 'post',
                            url: 'php/getDeadlineUsers.php',
                            data: { deadline_id: v.id },
                            success: function(resp) {
                                resp = JSON.parse(resp);
                                let oslim = resp.length;
                                $('#oslimit-'+v.id).html(oslim);
                            }
                        });
                    });
                    if(response.length==0) {
                        $('#availableDeadlines').append(`<li>Brak terminów na wybrany dzień</li>`)
                    }
                },
                error: function() {
                    alert("Wystąpił błąd!");
                }
            });
        };

        const displayDayDescription = (day, weekday) => {
            selDay = day;
            $('.calendar_view').hide();
            $('.calendar_details').show();
            const weekDayNames2 = ['niedziela', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota'];
            let d = new Date(currentYear, currentMonth-1, selDay)
            $('#selectedDay').html(`${weekDayNames2[d.getDay()]} ${d.getDate()}-${d.getMonth()}-${d.getFullYear()}`);
            $('#availableDeadlines').html('');
            loadDayDeadlines()
        };

        function retraction() {
            let x = new Date(currentYear, currentMonth);
            let y = x.getDay()
            return y;
        }

        const loadDays = async () => {
            $.ajax({
                type: 'post',
                url: 'php/getDeadlines.php',
                success: (res) => {
                    res = JSON.parse(res);
                    res.forEach((v,i) => { res[i].date = new Date(Date.parse(v.date.replace(/[-]/g,'/'))); });
                    let viewDeadlines = res;
                    $('#calendar_days').html('');

                    let r = retraction();
                    const dim = daysInMonth(currentMonth, currentYear)
                    const weeks = Math.ceil(dim / 7)

                    console.log(`weeks: ${weeks}`)
                    console.log(`retraction: ${r}`);
                    console.log(`days in month + retraction: ${dim}`)
                    

                    for (let i = 1; i <= weeks; i++) {
                        let row = '<tr>';
                        for (let k = 1; k <= 7; k++) {
                            let day = k + ((i - 1) * 7);
                            if (day > dim) break;
                            let is_tagged = false;
                            let is_current = false;
                            for (let index = 1; index <= viewDeadlines.length; index++) {
                                const object = viewDeadlines[index - 1];
                                let _day = object.date.getDate();
                                let _month = object.date.getMonth() + 1;
                                let _year = object.date.getFullYear();
                                if (_year != currentYear) continue;
                                if (_month != currentMonth) continue;
                                if (_day == day) {
                                    is_tagged = true;
                                }
                            }

                            let currDate = new Date();
                            if(currDate.getDate() == day && (currDate.getMonth()+1)==currentMonth) {
                                is_current = true;
                            }
                            if(is_tagged && is_current) {
                                row += `<th><button class="current tagged" onclick="displayDayDescription(${day}, ${k})">${day}</button></th>`;
                            } else if(is_current) {
                                row += `<th><button class="current" onclick="displayDayDescription(${day}, ${k})">${day}</button></th>`;
                            } else if(is_tagged) {
                                row += `<th><button class="tagged" onclick="displayDayDescription(${day}, ${k})">${day}</button></th>`;
                            } else {
                                row += `<th><button onclick="displayDayDescription(${day}, ${k})">${day}</button></th>`
                            }
                        }
                        row += '</tr>'
                        $('#calendar_days').append(row);
                    };
                }
            });
        };

        const calendar_updateView = () => {
            $('#currentMonthAndYear').html(`${capitalize(monthNames[currentMonth-1])}, ${currentYear}`);
            loadDays();
        };

        const calendar_displayNextMonth = () => {
            if(currentMonth==12) {
                currentYear++;
                currentMonth = 1;
            } else {
                currentMonth = currentMonth + 1;
            }
            calendar_updateView();
        };

        const calendar_displayPreviousMonth = () => {
            if(currentMonth==1) {
                currentYear--;
                currentMonth = 12;
            } else {
                currentMonth = currentMonth - 1;
            }
            calendar_updateView();
        };

        const deleteDeadline = (id) => {
            let condition = confirm("Czy na pewno chcesz usunąć wybrany termin?");
            if(condition) {
                $.ajax({
                    type: 'post',
                    url: 'php/deleteDeadline.php',
                    data: { deadline_id: id },
                    success: (res) => {
                        console.log(res);
                        res = JSON.parse(res);
                        if(res.condition) {
                            alert(res.error_message);
                            displayCalendarView();
                        } else {
                            alert(res.error_message);
                        }
                    }
                });
            }
        };

        // event handling
        $('#sform').submit(function(event) {

            event.preventDefault();

            let data = {
                singleTerm_time: $('#singleTermTime option:selected').text(),
                maxStudentCount_1: $('#maxStudentCount_1 option:selected').text(),
                day: selDay,
                month: currentMonth,
                year: currentYear
            }

            $.ajax({
                type: 'post',
                url: 'php/addTerm.php',
                data: data,
                success: function(response) {
                    response = JSON.parse(response);
                    if(response.condition) {
                        alert(response.error_message);
                        backToCreationSelection();
                        cancelTermCreation();
                        loadDayDeadlines();
                    } else {
                        alert(response.error_message);
                    }
                },
                error: function() {
                    alert("Błąd dodawania terminu!");
                }
            })

        });

        $('#mform').submit(function(event) {

            event.preventDefault();

            let data = {
                multipleTermTime_Start: $('#multipleTermTime_Start').val(),
                multipleTermTime_End: $('#multipleTermTime_End').val(),
                maxStudentCount_2: $('#maxStudentCount_2 option:selected').text(),
                day: selDay,
                month: currentMonth,
                year: currentYear
            }

            $.ajax({
                type: 'post',
                url: 'php/addTerm.php',
                data: data,
                success: function(response) {
                    response = JSON.parse(response);
                    if(response.condition) {
                        alert(response.error_message);
                        backToCreationSelection();
                        cancelTermCreation();
                        loadDayDeadlines();
                    } else {
                        alert(response.error_message);
                    }
                },
                error: function() {
                    alert("Błąd dodawania terminu!");
                }
            })

        });

        // main initialization
        $('.create_deadline').hide();
        $('.calendar_details').hide();
        $('#singleTermForm').hide();
        $('#multipleTermForm').hide();
        $('#calendar_deadlineStudents').hide();

        workHours.forEach((v,i) => {
            $('#singleTermTime').append(`<option value="${i}">${v}</option>`)
            $('#multipleTermTime_Start').append(`<option value="${i}">${v}</option>`)
            $('#multipleTermTime_End').append(`<option value="${i}">${v}</option>`)
        });

        for(let i = 1; i<=30; i++) {
            $('#maxStudentCount_1').append(`<option value="${i}">${i}</option>`)
            $('#maxStudentCount_2').append(`<option value="${i}">${i}</option>`)
        };

        calendar_updateView();

    </script>
</body>
</html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <script>
        $.ajax({
            type: 'post',
            url: 'php/getDeadlines.php',
            data: {
                day: 6,
                month: 2,
                year: 2021
            },
            success: function(response) {
                alert(response);
            },
            error: function() {
                alert("Błąd dodawania terminu!");
            }
        })
    </script>

</body>
</html>
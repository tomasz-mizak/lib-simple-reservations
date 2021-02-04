<?php

    require_once "dbconn.php";

    session_start();

    $username = $password = "";
    $err = "";

    if($_SERVER["REQUEST_METHOD"]=="POST") {

        $canVerify = true;
        $success = false;

        if(empty(trim($_POST['logonName']))) {

            $err = "Proszę wpisać identyfikator.";
            $canVerify = false;

        } else {

            $username = trim($_POST['logonName']);

        }

        if($canVerify && empty(trim($_POST['passwd']))) {

            $err = "Proszę wpisać hasło.";
            $canVerify = false;

        } else {

            $password = trim($_POST['passwd']);

        }

        if($canVerify) {

            $sql = "SELECT id, email, password, first_name, last_name FROM users WHERE email=?";

            if($stmt = $link->prepare($sql)) {

                $stmt->bind_param("s", $param_username);
                $param_username = $username;

                if($stmt->execute()) {

                    $stmt->store_result();

                    if($stmt->num_rows == 1) {

                        $stmt->bind_result($id, $username, $hashed_password, $first_name, $last_name);

                        $stmt->fetch();

                        if(password_verify($password, $hashed_password)) {

                            $success = true;

                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;
                            $_SESSION['first_name'] = $first_name;
                            $_SESSION['last_name'] = $last_name;

                            header("location: ../admin.php");

                        } else {

                            $err = "Wprowadzone hasło, jest nieprawidłowe!";

                        }

                    } else {

                        $err = "Wprowadzona nazwa użytkownika, jest nieprawidłowa.";

                    }

                } else {

                    $err = "Coś poszło nie tak, spróbuj ponownie!";

                }

            }

            $stmt->close();

        }

        $link->close();

        if(!$success) {
            $_SESSION['last_username'] = $username;
            $_SESSION['login_error'] = $err;
            header("location: ../login.php");
        }

    }

?>
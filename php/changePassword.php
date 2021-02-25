<?php

require_once "sesscheck.php";
require_once "dbconn.php";

if(isset($_POST['email'])&&!empty(trim($_POST['email']))&&isset($_POST['oldPassword'])&&!empty(trim($_POST['oldPassword']))&&isset($_POST['newPassword'])&&!empty(trim($_POST['newPassword']))) {
    $email = trim($_POST['email']);
    $oldPassword = trim($_POST['oldPassword']);
    $sql = "SELECT id, password FROM users WHERE email=?";
    if($stmt=$link->prepare($sql)) {
        $stmt->bind_param('s', $email);
        if($stmt->execute()) {
            $stmt->store_result();
            if($stmt->num_rows>0) {
                $stmt->store_result();
                $stmt->bind_result($id,$hashedPassword);
                $stmt->fetch();
                if(password_verify($oldPassword,$hashedPassword)) {
                    $newPassword = password_hash(trim($_POST['newPassword']), PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password=? WHERE id=?";
                    if($stmt=$link->prepare($sql)) {
                        $stmt->bind_param('ss',$newPassword,$id);
                        if($stmt->execute()) {
                            echo "Hasło zostało zaktualizowane!";
                        } else {
                            echo "Błąd podczas zmiany hasła!";
                        }
                    }
                } else {
                    echo 'Stare hasło jest błędne!';
                }
            }
        }
    }
} else {
    echo 'Nie podano wszystkich parametrów!';
}

?>
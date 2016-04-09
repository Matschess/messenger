<?php
include("db_connect.php");

$email = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST["email"])));
$firstname = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST["firstname"])));
$lastname = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST["lastname"])));
$username = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST["username"])));
$password = mysqli_real_escape_string($db, htmlspecialchars($_POST["password"]));

include("user_exists.php");
if (emailExistsCheck($email) == "available" && $firstname && $lastname && usernameExistsCheck($username) == "available" && passwordCheck($password)) {
    $password_hashed = "pw" . substr(md5($password), 2);
    $insert = mysqli_query($db, "INSERT INTO users (email, username, firstname, lastname, password) VALUES ('$email', '$username', '$firstname', '$lastname', '$password_hashed')");
    if ($insert) {
        echo 'registered';
    } else {
        echo 'error';
    }
} else {
    echo "error";
}

function passwordCheck($password)
{
    $length = strlen($password);
    $minChars = 6; // minimum Characters of password
    if (!trim($password) || $length < $minChars) {
        $points = 0;
    } else {
        if (($length - $minChars) > 5) {
            $points = 5;
        } else {
            $points = $length - $minChars;
        }

        $upperChars = preg_match("/[A-ZÄÖÜ]/", $password);
        if ($upperChars) {
            if ($upperChars) {
                $points += 3;
            } else {
                $points = $points + length($upperChars) / 2;
            }
        }
        $numericChars = preg_match("/[0-9]/", $password);
        if ($numericChars) {
            $points++;
        }
        $specialChars = preg_match("/\W[^äöüÄÖÜ]/", $password);
        if ($specialChars) {
            $points++;
        }
    }
    if ($points >= 5) {
        return true;
    } else {
        return false;
    }
}
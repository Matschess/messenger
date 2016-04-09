<?php
include "db_connect.php";

$job = $_POST["job"];

if ($job == "emailCheck") {
    $email = $_POST["email"];
    echo emailExistsCheck($email);
}

if ($job == "usernameCheck") {
    $username = $_POST["username"];
    echo usernameExistsCheck($username);
}

function emailExistsCheck($email)
{
    global $db;

    $charAt = strpos($email, "@");
    $charDot = strripos($email, ".");
    if ($charAt !== false && $charDot !== false && $charDot > $charAt + 1 && $charDot < strlen($email) - 1) { // Check if format is valid
        $emailExistsQuery = mysqli_query($db, "SELECT id FROM users WHERE email = '$email'");
        if (!mysqli_num_rows($emailExistsQuery)) {
            return 'available';
        } else {
            return 'used';
        }
    } else {
        return 'formatError';
    }
}

function usernameExistsCheck($username)
{
    global $db;

    if (strlen($username) >= 6) {
        $usernameExistsQuery = mysqli_query($db, "SELECT id FROM users WHERE username = '$username'");
        if (!mysqli_num_rows($usernameExistsQuery)) {
            return 'available';
        } else {
            return 'used';
        }
    } else {
        return 'formatError';
    }
}
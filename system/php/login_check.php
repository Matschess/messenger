<?php
// Maximum tries to account lock
$maxTries = 5;

$job = $_POST["job"];

if($job == "check") {
    include("db_connect.php");

    $username = mysqli_real_escape_string($db, $_POST["username"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);

// Is user locked?
    $lockedQuery = mysqli_query($db, "SELECT id, lockedLogin, triesLogin FROM users WHERE username = '$username' || email = '$username'");
    $lockedRows = mysqli_fetch_object($lockedQuery);
    $user_id = $lockedRows->id;
    $lockedLogin = $lockedRows->lockedLogin;
    $triesLogin = $lockedRows->triesLogin;
    if ($lockedLogin || $triesLogin >= $maxTries) {
        echo "locked";
    } else {
        $password_hashed = "pw" . substr(md5($password), 2);
        $existingQuery = mysqli_query($db, "SELECT id FROM users WHERE ((username = '$username' || email = '$username') && password = '$password_hashed') && not lockedLogin");
        $existingRows = mysqli_fetch_object($existingQuery);
        if ($existingRows) {
            // Reset user
            $resetQuery = mysqli_query($db, "UPDATE users SET lockedPin = 0, triesPin = 0, pin = '', lockedLogin = 0, triesLogin = 0, ipLogin = '' WHERE id = $user_id");
            if ($resetQuery) {
                startSession($user_id);
                echo "access: " . $user_id;
            }
        } else {
            if ($triesLogin == $maxTries - 1) {
                $ip = $_SERVER["REMOTE_ADDR"];
                $lockQuery = mysqli_query($db, "UPDATE users SET lockedLogin = 1, triesLogin = triesLogin + 1, ipLogin = '$ip' WHERE id = '$user_id'");
                if ($lockQuery) {
                    echo "locked";
                }
            } else {
                $rememberQuery = mysqli_query($db, "UPDATE users SET triesLogin = triesLogin + 1 WHERE id = '$user_id'");
                if ($rememberQuery) {
                    echo "denied";
                }
            }
        }
    }
}
elseif($job == "recreateSession") {
    $user_id = $_POST["user_id"];
    startSession($user_id);
    echo "access";
}

function startSession($user_id) {
    session_start();
    $_SESSION["user_id"] = $user_id;
}
<?php
$friend_id = $_GET["friend_id"];
$chat_id = $_GET["chat_id"];
// clear other cookie to avoid if-crash
unset($_COOKIE['friend_id']);
setcookie('friend_id', 0, time() - 3600, '/');
// Set chat as current chat
setcookie("chat_id", $chat_id, 0, '/');
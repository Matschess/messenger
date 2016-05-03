<?php
$host = 'localhost'; //host
$port = '1414'; //port
$null = NULL; //null var

$db = mysqli_connect("localhost", "root", "root", "messenger");

//Create TCP/IP sream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//bind socket to specified host
socket_bind($socket, 0, $port);

//listen to port
socket_listen($socket);

//create & add listning socket to the list
$clients = array($socket);
$userToClients = array();
$idToClients = array();

//start endless loop, so that our script doesn't stop
while (true) {
    //manage multipal connections
    $changed = $clients;
    //returns the socket resources in $changed array
    socket_select($changed, $null, $null, 0, 10);

    //check for new socket
    if (in_array($socket, $changed)) {
        $socket_new = socket_accept($socket); //accpet new socket
        $lastPosition = count($clients);
        $clients[] = $socket_new; //add socket to client array

        $header = socket_read($socket_new, 1024); //read data sent by the socket
        perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake

        socket_getpeername($socket_new, $ip); //get ip address of connected socket
        //make room for new socket
        $found_socket = array_search($socket, $changed);
        unset($changed[$found_socket]);
    }

    //loop through all connected sockets
    foreach ($changed as $changed_socket) {

        //check for any incomming data
        while (socket_recv($changed_socket, $buf, 1024, 0) >= 1) {
            $received_text = unmask($buf); //unmask data
            $tst_msg = json_decode($received_text); //json decode
            if (isset($tst_msg)) {
                if ($tst_msg->type === "user_id") {
                    $user_id = $tst_msg->message;
                    $idToClients[$user_id] = $lastPosition;
                    $userToClients[$lastPosition] = $user_id;
                    $response = mask(json_encode(array('type' => 'system', 'message' => $ip))); //prepare json data
                    send_message($response); //notify all users about new connection

                } elseif ($tst_msg->type === "message") {
                    $chat_id = $tst_msg->chat_id;
                    $message = $tst_msg->message;

                    $socketPosition = array_search($changed_socket, $clients);
                    $user_id = $userToClients[$socketPosition];

                    $result = mysqli_query($db, "SELECT groupname, user_left_id, user_right_id FROM chats WHERE id = $chat_id");
                    $row = mysqli_fetch_object($result);
                    if ($row) {
                        $groupname = $row->groupname;
                        if (isset($groupname)) {
                            $groupMemberQuery = mysqli_query($db, "SELECT user_id FROM groupmembers WHERE chat_id = $chat_id");
                            if (mysqli_num_rows($groupMemberQuery)) {
                                $lastuser_id;
                                while ($groupMemberRows = mysqli_fetch_object($groupMemberQuery)) {
                                    $member_id = $groupMemberRows->user_id;
                                    if ($member_id != $user_id) {
                                        $friend_id = $member_id;
                                        if (isset($idToClients[$friend_id])) {
                                            $receiverPos = $idToClients[$friend_id];
                                            echo $receiverPos;
                                            $response = mask(json_encode(array('type' => 'message', 'message' => $message, 'chat_id' => $chat_id))); //prepare json data
                                            @socket_write($clients[$receiverPos], $response, strlen($response));
                                        }
                                    }
                                }
                            }
                        } else {
                            $user_left_id = $row->user_left_id;
                            if ($user_left_id != $user_id) {
                                $friend_id = $user_left_id;
                            } else {
                                $friend_id = $row->user_right_id;
                            }
                            if (isset($idToClients[$friend_id])) {
                                $receiverPos = $idToClients[$friend_id];
                                echo $receiverPos;
                                $response = mask(json_encode(array('type' => 'message', 'message' => $message, 'chat_id' => $chat_id))); //prepare json data
                                @socket_write($clients[$receiverPos], $response, strlen($response));
                            }
                        }
                        $insert = mysqli_query($db, "INSERT INTO messages (chat_id, user_id, message) VALUES ($chat_id, $user_id, '$message')");
                    }
				} elseif ($tst_msg->type === "read")
					{
                    $chat_id = $tst_msg->chat_id;
					
					$socketPosition = array_search($changed_socket, $clients);
					$user_id = $userToClients[$socketPosition];
					echo "User: " . $user_id;
					$result = mysqli_query($db, "SELECT user_left_id, user_right_id FROM chats WHERE id = $chat_id");
                    $row = mysqli_fetch_object($result);
                    if ($row) {
						$user_left_id = $row->user_left_id;
						echo $user_left_id;
                            if ($user_left_id != $user_id) {
                                $friend_id = $user_left_id;
                            } else {
                                $friend_id = $row->user_right_id;
                            }
                            if (isset($idToClients[$friend_id])) {
                                $receiverPos = $idToClients[$friend_id];
                                echo $receiverPos;
                                $response = mask(json_encode(array('type' => 'read', 'chat_id' => $chat_id))); //prepare json data
                                @socket_write($clients[$receiverPos], $response, strlen($response));
                            }
					}
                } elseif ($tst_msg->type === "addContact") {
                    $friend_id = $tst_msg->friend_id;
                    echo $friend_id;
                    if (isset($idToClients[$friend_id])) {
                        $receiverPos = $idToClients[$friend_id];
                        $response = mask(json_encode(array('type' => 'note', 'message' => 'newFriendRequest'))); //prepare json data
                        @socket_write($clients[$receiverPos], $response, strlen($response));
                    }
                }
            }
            break 2;
            /*
            $user_name = $tst_msg->name; //sender name
            $user_message = $tst_msg->message; //message text
            $user_color = $tst_msg->color; //color


            //prepare data to be sent to client
            $response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color)));
            send_message($response_text); //send data
            break 2; //exist this loop
            */
        }

        $buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
        if ($buf === false) { // check disconnected client
            // remove client for $clients array
            $found_socket = array_search($changed_socket, $clients);
            socket_getpeername($changed_socket, $ip);
            unset($clients[$found_socket]);

            //notify all users about disconnected connection
            $response = mask(json_encode(array('type' => 'system', 'message' => $ip . ' disconnected')));
            send_message($response);
        }
    }
}
// close the listening socket
socket_close($socket);

function send_message($msg)
{
    global $clients;
    foreach ($clients as $changed_socket) {
        @socket_write($changed_socket, $msg, strlen($msg));
    }
    return true;
}


//Unmask incoming framed message
function unmask($text)
{
    $length = ord($text[1]) & 127;
    if ($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    } elseif ($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    } else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }
    $text = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }
    return $text;
}

//Encode message for transfer to client.
function mask($text)
{
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);

    if ($length <= 125)
        $header = pack('CC', $b1, $length);
    elseif ($length > 125 && $length < 65536)
        $header = pack('CCn', $b1, 126, $length);
    elseif ($length >= 65536)
        $header = pack('CCNN', $b1, 127, $length);
    return $header . $text;
}

//handshake new client.
function perform_handshaking($receved_header, $client_conn, $host, $port)
{
    $headers = array();
    $lines = preg_split("/\r\n/", $receved_header);
    foreach ($lines as $line) {
        $line = chop($line);
        if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
            $headers[$matches[1]] = $matches[2];
        }
    }

    $secKey = $headers['Sec-WebSocket-Key'];
    $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
    //hand shaking header
    $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "WebSocket-Origin: $host\r\n" .
        "WebSocket-Location: ws://$host:$port/demo/shout.php\r\n" .
        "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
    socket_write($client_conn, $upgrade, strlen($upgrade));
}

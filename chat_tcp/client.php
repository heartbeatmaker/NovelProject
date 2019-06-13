<?
//채팅 서버와 통신하는 클라이언트
error_reporting(E_ALL);

$address = "18.191.197.32"; // 접속할 IP
$port = 8000; // 접속할 PORT
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // TCP 통신용 소켓 생성
if ($socket === false) {
    echo "socket_create() Failed: " . socket_strerror(socket_last_error()) . "\n";
    echo "<br>";
} else {
    echo "Successfully created a socket\n";
    echo "<br>";
}

echo "Connecting to Server; ip '$address' via Port '$port'...";
echo "<BR>";
$result = socket_connect($socket, $address, $port); // 소켓 연결 및 $result에 접속값 지정
if ($result === false) {
    echo "socket_connect() Failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    echo "<br>";
} else {
    echo "Successfully connected to $address.\n";
    echo "<br>";
}
$message = "enter/asetsetsaetow!!!"; //보내고자 하는 전문 //
echo "서버로 보내는 전문 : $message.\n";

socket_write($socket, $message, strlen($message)); //서버로 메시지를 보냄
echo "<br>";


//while(true) {
//    $input = socket_read($socket, 50) or die("Could not read from Socket\n"); // 서버로부터 받은 REQUEST 정보를 $input에 지정 //
//    echo "<br>";
//    echo "message received from the server: " . $input;
//    ob_flush();
//    flush();
//}

//echo "socket_close";
//socket_close($socket);//소켓 연결을 끊는다
?>


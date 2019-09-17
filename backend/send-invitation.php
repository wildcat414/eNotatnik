<?php
require_once("./config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

$conn->query("SET collation_connection = utf8_unicode_ci");

$userData = file_get_contents('php://input');
$userDataArr = json_decode($userData, true);

$email = $userDataArr['email'];
$login = $userDataArr['login'];
$userId = $userDataArr['userId'];

$to = $email;
$subject = "Zaproszenie do biblioteki";

$message = "
<html>
<h1>HLMS</h1>
<h2>System zarządzania domową biblioteką</h2>
<p>Witaj!</p>
<p>Uzytkownik ". $login ." zaprosił Cię do wspólnego korzystania z Home Library Mangement System.</p>
<p>Aby założyć nowe konto, powiązane z kontem ". $login .", kliknij w poniższy link rejestracyjny:<br>
<a target=\"_blank\" href=\"http://hlmstest.hekko24.pl/#/register-by-reference/" . $userId ."/" . $email ."\">http://hlmstest.hekko24.pl/#/register-by-reference/" . $userId ."/" . $email ."</a></p>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: Biblioteka <noreply@hlmstest.hekko24.pl>' . "\r\n";

$mailstatus = mail($to,$subject,$message,$headers);

if($mailstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "Invitation e-mail has been sent.";
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Invitation e-mail could not be sent.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

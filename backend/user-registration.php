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
$password = $userDataArr['password'];
$today = time();

$query = 'INSERT INTO Users(id, login, password, email, registeredAt, token) VALUES (\'\', \''. $login .'\', \''. $password .'\', \''. $email .'\', '. $today .', NULL)';

$result = $conn->query($query);
$conn->close();

if($result != FALSE) {
    $dbstatus = TRUE;
} else {
    $dbstatus = FALSE;
}

if($dbstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "User has been successfully added to database.";
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Error occurred while creating user. Could not save user to database.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

<?php
require_once("./config.php");

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

$conn->query("SET collation_connection = utf8_unicode_ci");

$inputData = file_get_contents('php://input');
$inputDataArr = json_decode($inputData, true);

$login = $inputDataArr['login'];
$password = $inputDataArr['password'];

$query = "SELECT * FROM Users WHERE login = '$login' AND password = '$password' AND active = 1";
$result = $conn->query($query);

$rows = $result->num_rows;
$tempArr = $result->fetch_array(MYSQLI_ASSOC);
$resultArray["userId"] = $tempArr["id"];
$resultArray["reference"] = $tempArr["reference"];

$result->free();

if($rows > 0) {
    $dbstatus = TRUE;
    $resultArray["userToken"] = generateRandomSequence();
    $tempToken = $resultArray["userToken"];
    $query = "UPDATE Users SET token = '$tempToken' WHERE login = '$login'";
    $result = $conn->query($query);
} else {
    $dbstatus = FALSE;
}

$conn->close();

if($dbstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "User has been successfully authorized.";
    $return_arr["result"] = $resultArray;
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Could not authorize user. Probably invaild login data.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

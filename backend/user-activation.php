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

$activation_code = $inputDataArr['code'];

$query = "UPDATE Users SET active = 1 WHERE activation_code = '$activation_code'";
$result = $conn->query($query);
$conn->close();

if($result != FALSE) {
    $dbstatus = TRUE;
} else {
    $dbstatus = FALSE;
}

if($dbstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "User has been successfully activated.";
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Could not activate user in database.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

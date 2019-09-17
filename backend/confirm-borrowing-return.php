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

$borrowing_id = $inputDataArr['borrowing_id'];
$userToken = $inputDataArr['userToken'];

$query = "SELECT reference FROM Users WHERE token = '$userToken'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);
$result->free();
$reference = $row['reference'];

if($reference != "" && $reference != NULL) {
    $userId = $reference;
}

$query = "UPDATE Borrowings SET return_confirmed = 1 WHERE id = $borrowing_id";
//echo($query);
$result = $conn->query($query);
$conn->close();

if($result != FALSE) {
    $dbstatus = TRUE;
} else {
    $dbstatus = FALSE;
}

if($dbstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "Borrowing return has been successfully confirmed.";
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Could not confirm borrowing return in database.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

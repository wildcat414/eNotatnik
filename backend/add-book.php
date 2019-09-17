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

$author_forename = $inputDataArr['author_forename'];
$author_surname = $inputDataArr['author_surname'];
$title = $inputDataArr['title'];
$publish_year = $inputDataArr['publish_year'];
$isbn = $inputDataArr['isbn'];
$publishing_house = $inputDataArr['publishing_house'];
$userToken = $inputDataArr['userToken'];

$query = "SELECT id, reference FROM Users WHERE token = '$userToken'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);
$result->free();
$reference = $row['reference'];

if($reference != "" && $reference != NULL) {
    $userId = $reference;
} else {
    $userId = $row['id'];
}

$query = 'INSERT INTO Books(id, author_forename, author_surname, title, publish_year, isbn, publishing_house, date_added, owner) VALUES (\'\', \''. $author_forename .'\', \''. $author_surname .'\', \''. $title .'\', \''. $publish_year .'\', \''. $isbn .'\', \''. $publishing_house .'\', CURRENT_DATE, ' . $userId .')';
$result = $conn->query($query);

$conn->close();

if($result != FALSE) {
    $dbstatus = TRUE;
} else {
    $dbstatus = FALSE;
}

if($dbstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "New book has been successfully added.";
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Could not save new book into database.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

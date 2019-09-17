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

$borrower = $inputDataArr['borrower'];
$borrower_external = $inputDataArr['borrower_external'];
$book = $inputDataArr['book'];
$date_returned = $inputDataArr['date_returned'];
$userToken = $inputDataArr['userToken'];

$query = "SELECT id FROM Borrowings WHERE book = $book AND return_confirmed = 0";
$result = $conn->query($query);
$number_of_rows = $result->num_rows;
$result->free();

$query = "SELECT reference FROM Users WHERE token = '$userToken'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);
$result->free();
$reference = $row['reference'];

if($reference != "" && $reference != NULL) {
    $userId = $reference;
}

if($number_of_rows > 0) {
    //echo("num rows " + $number_of_rows);
    $result = FALSE;
} else {
    if($borrower_external != "") {
        $query = 'INSERT INTO Borrowings(id, book, borrower, borrower_external, date_borrowed, date_returned, return_confirmed) VALUES (\'\', \''. $book .'\', NULL, \''. $borrower_external .'\', CURRENT_DATE, \''. $date_returned .'\', 0)';
    } else {
        $query = 'INSERT INTO Borrowings(id, book, borrower, borrower_external, date_borrowed, date_returned, return_confirmed) VALUES (\'\', \''. $book .'\', \''. $borrower .'\', NULL, CURRENT_DATE, \''. $date_returned .'\', 0)';
    }    
    //echo($query);
    $result = $conn->query($query);    
}

$conn->close();

if($result != FALSE) {
    $dbstatus = TRUE;
} else {
    $dbstatus = FALSE;
}

if($dbstatus == TRUE) {
    $return_arr["status"] = "ok";
    $return_arr["details"] = "New borrowing has been successfully added.";
} else {
    $return_arr["status"] = "error";
    $return_arr["details"] = "Could not save new borrowing into database.";
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

?>

<?php
require_once("./config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

/* check connection */
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

$conn->query("SET collation_connection = utf8_unicode_ci");

$inputData = file_get_contents('php://input');
$inputDataArr = json_decode($inputData, true);

$userId = $inputDataArr['userId'];
$userToken = $inputDataArr['userToken'];
$borrowedToOthers = $inputDataArr['borrowedToOthers'];

$query = "SELECT reference FROM Users WHERE token = '$userToken'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);
$result->free();
$reference = $row['reference'];

if($reference != "" && $reference != NULL) {
    $userId = $reference;
}

if($borrowedToOthers == TRUE) {
    $query = "SELECT bk.*, br.*, us.login AS borrower_name, bk.id AS book_id, br.id AS borrowing_id FROM Books bk JOIN Borrowings br ON bk.id = br.book LEFT JOIN Users us ON br.borrower = us.id WHERE bk.owner = $userId AND br.return_confirmed = 0 ORDER BY bk.id";
} else {
    $query = "SELECT bk.*, br.*, us.login AS borrower_name, bk.id AS book_id, br.id AS borrowing_id FROM Books bk JOIN Borrowings br ON bk.id = br.book LEFT JOIN Users us ON bk.owner = us.id WHERE bk.owner != $userId AND br.borrower = $userId AND br.return_confirmed = 0 ORDER BY bk.id";
}

//echo($query);
$result = $conn->query($query);

/* associative array */
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $array[] = $row;
}

/* free result set */
$result->free();

/* close connection */
$conn->close();

$return_arr["status"] = "ok";
$return_arr["details"] = "Books list has been successfully acquired from database.";
if(isset($array)) {
    $return_arr["result"] = $array;
} else {
    $return_arr["result"] = null;
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE );
?>

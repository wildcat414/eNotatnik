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

$query = "SELECT reference FROM Users WHERE token = '$userToken'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);
$reference = $row['reference'];
$result->free();

//echo($reference);

if($reference != "" && $reference != NULL) {
    $userId = $reference;
    //echo("reference used");
} else {
    //echo("reference not used");
}

$query = "SELECT *, id AS book_id FROM Books WHERE owner = $userId ORDER BY book_id";
$result = $conn->query($query);

while($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $book = $row['book_id'];
    $query = "SELECT * FROM Borrowings WHERE book = $book AND return_confirmed = 0";
    $result2 = $conn->query($query);
    $number_of_rows = $result2->num_rows;
    if($number_of_rows == 0) {
        $array[] = $row;
    }    
}

$result->free();

$query = "SELECT *, bk.id AS book_id, us.login AS owner_name FROM Borrowings br JOIN Books bk ON br.book = bk.id JOIN Users us ON bk.owner = us.id WHERE br.borrower = $userId ORDER BY book_id";
$result = $conn->query($query);
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $book = $row['book'];
    $query = "SELECT * FROM Borrowings WHERE book = $book AND borrower = $userId AND return_confirmed = 0";
    $result2 = $conn->query($query);
    $number_of_rows = $result2->num_rows;
    if($number_of_rows > 0) {
        $array[] = $row;
    }    
}

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

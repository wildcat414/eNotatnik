<?php
require_once("./config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

/* check connection */
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

$conn->query("SET collation_connection = utf8_unicode_ci");

$query = "SELECT us.login, us.id, us.reference FROM Users us";
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
$return_arr["details"] = "Users list has been successfully acquired from database.";
if(isset($array)) {
    $return_arr["result"] = $array;
} else {
    $return_arr["result"] = null;
}

echo json_encode($return_arr, JSON_UNESCAPED_UNICODE );
?>

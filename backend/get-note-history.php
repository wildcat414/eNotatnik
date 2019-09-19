<?php
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $conn = new mysqli($servername, $username, $password, $dbname);

    /* check connection */
    if ($conn->connect_errno) {
        printf("Connect failed: %s\n", $conn->connect_error);
        exit();
    }

    $conn->query("SET collation_connection = utf8_unicode_ci");

    $headers = apache_request_headers();
    $userToken = $headers['X-Authorization-Token'];

    $query = "SELECT * FROM users WHERE token = '$userToken'";
    $result = $conn->query($query);
    $users = $result->num_rows;
    $tempArr = $result->fetch_array(MYSQLI_ASSOC);
    $userId = $tempArr["id"];
    $result->free();

    if($users == 1) {
        $array = array();
        $query = "SELECT * FROM history_logs WHERE userId = '$userId' ORDER BY editedAt DESC;";
        $result = $conn->query($query);
        $rows = $result->num_rows;
        $i = 0;
        if($rows >= 1) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $array[$i] = $row;
                $i++;
            }            
            $result->free();
        }

        $return_arr["status"] = "ok";
        $return_arr["details"] = "Note history has been successfully acquired from database.";
        $return_arr["result"] = $array;
    } else {
        $return_arr["status"] = "error";
        $return_arr["details"] = "Unauthorized! Invalid token.";
        $return_arr["result"] = NULL;
    }

    $conn->close();

    echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);


} else {
    $return_arr["status"] = "ok";
    echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);
}

?>

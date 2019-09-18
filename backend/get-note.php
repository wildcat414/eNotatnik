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

    //$inputData = file_get_contents('php://input');
    //$inputDataArr = json_decode($inputData, true);
    //$userToken = $inputDataArr['userToken'];

    $headers = apache_request_headers();
    $userToken = $headers['X-Authorization-Token'];


    $query = "SELECT * FROM users WHERE token = '$userToken'";
    $result = $conn->query($query);
    $users = $result->num_rows;
    $result->free();

    if($users == 1) {
        $query = "SELECT * FROM notes n JOIN users u ON n.userId = u.id WHERE u.token = '$userToken';";
        $result = $conn->query($query);
        $rows = $result->num_rows;
        if($rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $content = base64_decode($row['content']);
            $editedAt = $row['editedAt'];
            $result->free();
        } else {
            $content = "";
            $editedAt = NULL;
        }

        $array["content"] = $content;
        $array["editedAt"] = $editedAt;

        $return_arr["status"] = "ok";
        $return_arr["details"] = "Notes content has been successfully acquired from database.";
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

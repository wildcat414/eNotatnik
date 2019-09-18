<?php
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $conn = new mysqli($servername, $username, $password, $dbname);

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
        $inputData = file_get_contents('php://input');
        $inputDataArr = json_decode($inputData, true);

        $content = base64_encode($inputDataArr['noteContent']);
        $time = (string) time();

        $query = "SELECT * FROM notes WHERE userId = '$userId'";
        $result = $conn->query($query);
        $notesPresent = $result->num_rows;
        $result->free();

        if($notesPresent == 1) {
            $query = "UPDATE notes SET content = '$content', editedAt = '$time' WHERE userId = '$userId';";
        } else {
            $query = "INSERT INTO notes(userId, content, editedAt) VALUES ('$userId', '$content', '$time');";
        }
        
        $result = $conn->query($query);

        if($result != FALSE) {
            $dbstatus = TRUE;
            $return_arr["status"] = "ok";
            $return_arr["details"] = "Notes content has been successfully updated.";
            $return_arr["result"] = NULL;
        } else {
            $dbstatus = FALSE;
            $return_arr["status"] = "error";
            $return_arr["details"] = "Notes content could not be updated.";
            $return_arr["result"] = NULL;
        }        
        $conn->close();
    } else {
        $return_arr["status"] = "error";
        $return_arr["details"] = "Unauthorized! Invalid token.";
        $return_arr["result"] = NULL;
    }

    echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);

} else {
    $return_arr["status"] = "ok";
    echo json_encode($return_arr, JSON_UNESCAPED_UNICODE);
}

?>

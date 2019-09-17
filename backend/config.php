<?php
$servername = "localhost";
$username = "barth1_admin";
$password = "eTzSGUgArsrU";
$dbname = "barth1_enotatnik";

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-type: application/json;');

function generateRandomSequence() {
    $seq = "";
    for($i = 0; $i < 64; $i++) {
        $randomNum = rand(0, 15);
        $randomHex = base_convert($randomNum, 10, 16);
        $seq .= $randomHex;
    }
    return $seq;
}
?>

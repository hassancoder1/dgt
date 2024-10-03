<?php session_start();
// For Local Use
$localhost = "localhost";
$username = "root";
$dbname = "dgt_new";
$password = "";

// For live Site
// $localhost = "fdb1029.awardspace.net";
// $username = "3742499_dgtllc";
// $dbname = "3742499_dgtllc";
// $password = "3@.S9wreHaLchnB";
/*accounts2.dgt.llc*/
$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection Failed : " . $connect->connect_error);
}
require_once("functions.php");
date_default_timezone_set("Asia/Dubai");
?>
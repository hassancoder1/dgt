<?php session_start();

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "dgt";
// $username = "u152432976_dgt";
// $password = "U?abH[IA6En6";
// $dbname = "u152432976_dgt";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection Failed : " . $connect->connect_error);
}
require_once("functions.php");
date_default_timezone_set("Asia/Dubai");
?>
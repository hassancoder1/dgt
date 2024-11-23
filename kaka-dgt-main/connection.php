<?php session_start();
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "dgt_bismillah";

$username = "u152432976_kaka";
$dbname = "u152432976_kaka";
$password = "Q>OsNqob4";
/*kgt.dgt.llc*/


$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection Failed : " . $connect->connect_error);
}
require_once("functions.php");
date_default_timezone_set("Asia/Karachi"); ?>
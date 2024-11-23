<?php
//fetch.php
include("../../connection.php");
if (isset($_POST["id"])) {
    $result = fetch('branches');
    $row = mysqli_fetch_array($result);
    echo json_encode($row);
}
?>

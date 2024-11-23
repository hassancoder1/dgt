<?php

require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$broker_id = $_POST['broker_id'];
$sql = "SELECT mobile, email, city, name, address, more_details FROM brokers WHERE id = '$broker_id'";

$result = $connect->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    $valid['success'] = true;
    $valid['messages'] = $row;
} else {
    $valid['success'] = false;
    $valid['messages'] = "Error ..";
}
$connect->close();

echo json_encode($valid);
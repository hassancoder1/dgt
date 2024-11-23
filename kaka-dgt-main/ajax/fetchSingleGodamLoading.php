<?php

require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$godam_loading_id = $_POST['godam_loading_id'];
$sql = "SELECT mobile1, munshi, address FROM godam_loading_forms WHERE id = '$godam_loading_id'";

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
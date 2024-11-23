<?php

require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$godam_empty_id= $_POST['godam_empty_id'];
$sql = "SELECT mobile1, munshi, address FROM godam_empty_forms WHERE id = '$godam_empty_id'";

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
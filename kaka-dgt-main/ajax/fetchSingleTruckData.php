<?php

require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$truck_no = $_POST['truck_no'];

$sql = "SELECT id as tl_id, truck_name, driver_name, d_mobile1
        FROM truck_loadings WHERE truck_no = '$truck_no'";

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
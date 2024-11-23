<?php

require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
/*clearing agent id*/
$ca_id = $_POST['ca_id'];
$sql = "SELECT ca_name, ca_mobile, ca_email, ca_city, ca_license, ca_license_address, ca_license_no, ca_details 
FROM clearing_agents WHERE id = '$ca_id'";

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
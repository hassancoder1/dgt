<?php

require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$importer_id = $_POST['importer_id'];
$sql = "SELECT mobile, email, city, comp_name, comp_address, comp_ntn, comp_tax_no, kansani_name, rebock_id, passport, rec_date FROM importers WHERE id = '$importer_id'";

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
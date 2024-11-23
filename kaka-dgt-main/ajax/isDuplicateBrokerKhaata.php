<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$khaata_no = $_POST['khaata_no'];
$khaata_id = $_POST['khaata_id'];
$result = $connect->query("SELECT * FROM brokers WHERE khaata_no = '$khaata_no' AND khaata_id != '$khaata_id' ");
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
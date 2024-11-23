<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$id = $_POST['dt_receiver_id'];
$sql = "SELECT mobile, whatsapp, comp_name,comp_owner_name, address FROM receivers WHERE id = '$id'";

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
<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$goods_id = $_POST['goods_id'];
$sql = "SELECT name, brand, origin,size_name, size_no FROM goods WHERE id = '$goods_id'";
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
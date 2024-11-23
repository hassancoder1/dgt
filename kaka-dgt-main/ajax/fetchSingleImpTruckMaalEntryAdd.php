<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'json1' => array());
$id = $_POST['id'];
/*if (isset($_POST['maal2id'])) {*/

$sql = "SELECT * FROM imp_truck_maals WHERE id = '$id'";
$result = $connect->query($sql);
if ($result->num_rows > 0) {
    $row1 = mysqli_fetch_assoc($result);
    $valid['messages'] = json_decode($row1['json_data']);
    $valid['success'] = true;
} else {
    $valid['success'] = false;
    $valid['messages'] = "Error ..";
}
$connect->close();

echo json_encode($valid, JSON_PRETTY_PRINT);
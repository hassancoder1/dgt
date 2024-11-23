<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$staff_id = $_POST['staff_id'];
$sql = "SELECT staffs.*, branches.b_name FROM staffs LEFT JOIN branches ON staffs.branch_id = branches.id WHERE staffs.id = '$staff_id'";
//$sql = "SELECT branch_id, city, license_name, father_name, cnic, caste,address,mobile,email,salary,details,image FROM staffs WHERE id = '$staff_id'";

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
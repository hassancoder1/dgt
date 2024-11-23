<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$staff_id = $_POST['staff_id'];
$staffQuery = fetch('staffs', array('id' => $staff_id));
$staffData = mysqli_fetch_assoc($staffQuery);
$salary_month = $_POST['salary_month'];
$salary_year = $_POST['salary_year'];
$sql = "SELECT * FROM r_munshi_exp WHERE staff_id = '$staff_id' AND salary_month = '$salary_month' AND salary_year = '$salary_year'";
$result = $connect->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    $valid['success'] = true;
    $str = monthNameURByNumber($salary_month) . " ";
    $str .= $salary_year . " میں ";
    $str .= $staffData['staff_name'] . " کا ";
    $str .= " ریکارڈ پہلے سے موجود ہے۔ ";
    $valid['messages'] = $str;
} else {
    $valid['success'] = false;
    $valid['messages'] = "";
}
$connect->close();

echo json_encode($valid);
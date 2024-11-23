<?php require_once '../connection.php';
$branch_id = $_POST['branch_id'];
$r_type = $_POST['r_type'];

$serial = fetch('roznamchaas', array('branch_id' => $branch_id, 'r_type' => $r_type));
$branch_serial = mysqli_num_rows($serial);
$branch_serial = $branch_serial + 1;
echo $branch_serial;
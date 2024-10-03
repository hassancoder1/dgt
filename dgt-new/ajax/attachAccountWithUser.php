<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$khaata_no = mysqli_real_escape_string($connect, $_POST['khaata_no']);
$khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);

$sql = "SELECT khaata.id as khaata_id, khaata.acc_for, khaata.khaata_name, khaata.khaata_no, khaata.email, khaata.phone, 
       khaata.is_active, khaata.cat_id, khaata.branch_id, khaata.created_at, 
       khaata.contact_details, khaata.bank_details, 
        khaata.image, cats.name, branches.b_name, branches.b_code
        FROM khaata
        LEFT JOIN cats ON cats.id = khaata.cat_id
        LEFT JOIN branches ON branches.id = khaata.branch_id
        WHERE khaata.id = '$khaata_id' AND khaata.khaata_no = '$khaata_no' ";

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
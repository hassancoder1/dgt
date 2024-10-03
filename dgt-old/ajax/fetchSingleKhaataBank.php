<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());
$bank_khaata_id = $_POST['bank_khaata_id'];

$sql = "SELECT khaata.id as khaata_id, khaata.khaata_name, khaata.city, khaata.comp_name,khaata.business_name, khaata.address, khaata.mobile, khaata.whatsapp,khaata.phone, khaata.email,khaata.image,khaata.cnic_name,khaata.cnic,khaata.details, 
cats.c_name, branches.b_name
        FROM khaata
        LEFT JOIN cats ON cats.id = khaata.cat_id
        LEFT JOIN branches ON branches.id = khaata.branch_id
        WHERE khaata.id = '$bank_khaata_id'";

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
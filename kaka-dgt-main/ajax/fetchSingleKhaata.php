<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'messages' => array());

if (isset($_POST['khaata_no'])){
    $khaata_no = $_POST['khaata_no'];
    $sql = "SELECT khaata.id as khaata_id, khaata.khaata_name, khaata.city, khaata.comp_name,khaata.business_name, khaata.address, khaata.mobile, khaata.whatsapp,khaata.phone, khaata.email,khaata.image, 
cats.c_name, branches.b_name
        FROM khaata
        LEFT JOIN cats ON cats.id = khaata.cat_id
        LEFT JOIN branches ON branches.id = khaata.branch_id
        WHERE khaata.khaata_no = '$khaata_no'";
}
if (isset($_POST['khaata_id'])){
    $khaata_id= $_POST['khaata_id'];
    $sql = "SELECT khaata.id as khaata_id, khaata.khaata_name, khaata.khaata_no, khaata.city, khaata.comp_name,khaata.business_name, khaata.address, khaata.mobile, khaata.whatsapp,khaata.phone, khaata.email,khaata.image, 
cats.c_name, branches.b_name
        FROM khaata
        LEFT JOIN cats ON cats.id = khaata.cat_id
        LEFT JOIN branches ON branches.id = khaata.branch_id
        WHERE khaata.id = '$khaata_id'";
}


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
<?php require_once '../connection.php';
$khaata_id = $_POST['khaata_id'];
//$result = fetch('khaata_details', array('khaata_id' => $khaata_id, 'static_type' => 'Extra')); Warehouse
$result = fetch('khaata_details', array('khaata_id' => $khaata_id));/*, 'static_type' => 'Warehouse'*/
//echo '<option value="">Select</option>';
while ($kh = mysqli_fetch_assoc($result)) {
    if ($kh['comp_name'] != '') {
        echo '<option value="' . $kh['id'] . '">' . $kh['comp_name'] . '</option>';
        //echo '<option value="' . $kh['id'] . '">' .  $kh['comp_name'] . '</option>';
    }
}
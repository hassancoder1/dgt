<?php require_once '../connection.php';

//$static_types = fetch('static_types', array('type_for' => 'khaata_details'));
$static_types = mysqli_query($connect,"SELECT * FROM `static_types` WHERE type_for = 'purchase_add'");
while ($static_type = mysqli_fetch_assoc($static_types)) {
    echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
}

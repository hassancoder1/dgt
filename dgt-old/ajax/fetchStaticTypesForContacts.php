<?php require_once '../connection.php';

$static_types = fetch('static_types', array('type_for' => 'contacts'));
while ($static_type = mysqli_fetch_assoc($static_types)) {
    echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
}

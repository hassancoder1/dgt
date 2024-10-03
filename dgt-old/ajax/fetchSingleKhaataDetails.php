<?php require_once '../connection.php';
$kd_id = $_POST['kd_id'];
$result = fetch('khaata_details', array('id' => $kd_id));
$output = '<div>';
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    $output .= '<div class="row gx-0 text-uppercase small">';
    $output .= '<div class="col-md-4">';
    $output .= '<b>TYPE </b>' . $row['static_type'];
    $output .= '<br><b>NAME </b>' . $row['comp_name'];
    $output .= '</div>';
    $output .= '<div class="col-md-4">';
    $output .= '<b>COUNTRY </b>' . countryName($row['country_id']);
    $output .= '<br><b>CITY </b>' . $row['city'];
    $output .= '</div>';
    $output .= '<div class="col-md-4">';
    $output .= '<b>ADDRESS </b>' . $row['address'];
    if ($row['report']!=''){
        $output .= '<br><b>REPORT </b>' . $row['report'];
    }
    $output .= '</div>';//col
    $output .= '<input name="comp_name" type="hidden" value="' . $row['comp_name'] . '">';
    $output .= '<input name="country" type="hidden" value="' . countryName($row['country_id']) . '">';
    $output .= '<input name="city" type="hidden" value="' . $row['city'] . '">';
    $output .= '<input name="address" type="hidden" value="' . $row['address'] . '">';
    $output .= '<input name="report" type="hidden" value="' . $row['report'] . '">';
    $output .= '</div>';//row
}
$output .= '</div>';
echo $output;
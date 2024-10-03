<?php require_once '../connection.php';
$khaata_id = $_POST['khaata_id'];
$result = fetch('khaata', array('id' => $khaata_id));
$output = '<div>';
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    $output .= '<div class="row gx-0 text-uppercase small">';
    $output .= '<div class="col-md-6">';
    $output .= '<b>NAME</b>' . $row['khaata_name'];
    $output .= '<br><b>COMP.</b>' . $row['comp_name'];
    $output .= '<br><b>B.</b>' . branchName($row['branch_id']);
    $output .= '<br><b>CAT.</b>' . catName($row['cat_id']);
    $output .= '<br><b>BUSINESS</b>' . $row['business_name'];
    $output .= '</div>';//col
    $output .= '<div class="col-md-6">';
    $output .= '<b>COUNTRY</b>' . countryName($row['country_id']);
    $output .= '<br><b>CITY</b>' . $row['city'];
    $output .= '<br><b>ADDRESS</b>' . $row['address'];
    $output .= '<br><b>DETAILS</b>' . $row['details'];
    $output .= '</div>';//col
    $output .= '</div>';//row
}
$output .= '</div>';
echo $output;
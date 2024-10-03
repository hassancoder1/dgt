<?php include('../connection.php');
$row = '';
if (isset($_POST["khaata_details_id"])) {
    $khaata_details_id = $_POST['khaata_details_id'];
    $run_query = fetch('khaata_details', array('id' => $khaata_details_id));
    if (mysqli_num_rows($run_query) > 0) {
        $row = mysqli_fetch_array($run_query);
    }
}
echo json_encode($row);
?>
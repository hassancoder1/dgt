<?php include('../connection.php');
$row = '';
if (isset($_POST["khaata_no"])) {
    $khaata_no = $_POST['khaata_no'];
    $run_query = fetch('khaata', array('khaata_no' => $khaata_no));
    if (mysqli_num_rows($run_query) > 0) {
        $row = mysqli_fetch_array($run_query);
    }
}
echo json_encode($row);
?>
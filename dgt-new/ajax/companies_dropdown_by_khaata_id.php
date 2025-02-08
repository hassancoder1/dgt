<?php include('../connection.php');

if (isset($_POST["khaata_id"])) {
    $khaata_id = $_POST['khaata_id'];
    $run_query = fetch('khaata_details', array('khaata_id' => $khaata_id, 'type' => 'company'));
    if (mysqli_num_rows($run_query) > 0) {
        echo '<option value="0" hidden>Select Company</option>';
        while ($row = mysqli_fetch_array($run_query)) {
            $row_data = json_decode($row['json_data']);
            echo '<option value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
        }
    } else {
        echo '<option value="0">No Company</option>';
    }
}

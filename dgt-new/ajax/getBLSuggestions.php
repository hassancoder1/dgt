<?php
require_once '../connection.php'; // Assuming this is where your DB connection is

$response = array('data' => array());

if (isset($_POST['bl_no'])) {
    $bl_no = mysqli_real_escape_string($connect, $_POST['bl_no']);
    $query = "SELECT sr_no, bl_no FROM general_loading WHERE bl_no LIKE '%$bl_no%' ORDER BY sr_no ASC";
    $result = $connect->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    }
}

$connect->close();
echo json_encode($response);

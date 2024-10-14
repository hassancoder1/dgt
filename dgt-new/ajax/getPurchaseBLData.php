<?php

require_once '../connection.php'; // Assuming this is where your DB connection is

$valid['success'] = array('success' => false, 'data' => array());

if (isset($_POST['sr_no']) && isset($_POST['bl_no'])) {
    $sr_no = mysqli_real_escape_string($connect, $_POST['sr_no']);
    $bl_no = mysqli_real_escape_string($connect, $_POST['bl_no']);

    // SQL query to fetch the data
    $sql = "SELECT * FROM general_loading WHERE sr_no = '$sr_no' AND bl_no = '$bl_no' ORDER BY id DESC";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $loading = json_decode($row['loading_details'], true);
        $receiving = json_decode($row['receiving_details'], true);
        $importer = json_decode($row['importer_details'], true);
        $notify = json_decode($row['notify_party_details'], true);
        $exporter = json_decode($row['exporter_details'], true);
        $goods = json_decode($row['goods_details'], true);
        $shipping = json_decode($row['shipping_details'], true);
        $data = [
            'sr_no' => $row['sr_no'],
            'p_id' => $row['p_id'],
            'loading' => $loading,
            'receiving' => $receiving,
            'bl_no' => $row['bl_no'],
            'report' => $row['report'],
            'importer' => $importer,
            'notify' => $notify,
            'exporter' => $exporter,
            'goods' => $goods,
            'shipping' => $shipping
        ];
        $valid['success'] = true;
        $valid['data'] = $data;
    } else {
        $valid['success'] = false;
        $valid['data'] = "No records found.";
    }

    $connect->close();
    echo json_encode($valid);
}

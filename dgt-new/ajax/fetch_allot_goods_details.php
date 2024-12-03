<?php
include('../connection.php');

if (isset($_POST["allot"])) {
    $allot = $_POST["allot"];
    $ItemsQ = mysqli_query($connect, "SELECT * FROM transaction_items WHERE allotment_name = '$allot' AND p_s='p'");
}

$allData = [];
$count = mysqli_num_rows($ItemsQ);

if ($count > 0) {
    while ($row = mysqli_fetch_assoc($ItemsQ)) {
        $row['good_name'] = goodsName($row['goods_id']);
        $row['date'] = my_date($row['created_at']);
        $allData[] = $row;
    }
    echo json_encode(['status' => 'success', 'msg' => "Records Found", 'data' => $allData]);
} else {
    echo json_encode(['status' => 'error', 'msg' => "No Records Found", 'data' => []]);
}

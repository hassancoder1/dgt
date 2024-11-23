<?php include('../connection.php');
global $connect;
$totalBardana = 0;
if (isset($_POST["buys_id"]) && isset($_POST["allot_name"])) {
    $allot_name = $_POST['allot_name'];
    $buys_id = $_POST['buys_id'];

    $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id' AND allot_name = '$allot_name'");
    if (mysqli_num_rows($detailsQ) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsQ);
        $totalBardana = $detailSums['bardana_qtySum'];
    }

    echo $totalBardana;
    /*$array['total_kgs'] = $total_kgs;
    echo json_encode($array);*/
} ?>
<?php include('../connection.php');
if (isset($_POST["allot"])) {
    $allot = $_POST["allot"];
    $ItemsQ = mysqli_query($connect, "SELECT DISTINCT goods_id FROM transaction_items WHERE allotment_name = '$allot' AND p_s='p'");
    }
    $count = mysqli_num_rows($ItemsQ);
    if ($count > 0) {
        echo '<option value="">'.(!empty($allot) ? 'Available' : 'ALL').' GOODS</option>';
        while ($row = mysqli_fetch_array($ItemsQ)) {
            echo '<option value="' . $row['goods_id'] . '">' . goodsName($row['goods_id']) . '</option>';
        }
    } else {
        echo '<option value="">No record</option>';
    }
?>
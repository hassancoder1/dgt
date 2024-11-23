<?php include('../connection.php');
if (isset($_POST["goods_id"])) {
    $good_id = $_POST["goods_id"];
    if(!isset($_POST['allot'])){
    //$goods = fetch('good_details', array('goods_id' => $goods_id));
    $ItemsQ = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = '$good_id'");
    }else{
        $allot = $_POST['allot'];
    $ItemsQ = mysqli_query($connect, "SELECT DISTINCT origin FROM transaction_items WHERE allotment_name = '$allot' AND goods_id='$good_id' AND p_s='p'");
    }
    $count = mysqli_num_rows($ItemsQ);
    if ($count > 0) {
        echo '<option value="">'.(!empty($allot) ? 'Available' : 'ALL').' ORIGINS</option>';
        while ($row = mysqli_fetch_array($ItemsQ)) {
            echo '<option value="' . $row['origin'] . '">' . $row['origin'] . '</option>';
        }
    } else {
        echo '<option value="">No record</option>';
    }
}

?>
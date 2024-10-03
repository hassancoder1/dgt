<?php include('../connection.php');
if (isset($_POST["goods_id"])) {
    $goods_id = $_POST['goods_id'];
    $col = $_POST['col'];
    //fetchAndDisplayOptionsByGoodsID($goods_id,'size');

    //$purchase_details = mysqli_query($connect, "SELECT DISTINCT size FROM `purchase_details` WHERE goods_id= '$goods_id'");
    $sizes = fetch('good_details',array('goods_id'=>$goods_id));
    if (mysqli_num_rows($sizes) > 0) {
        while ($pd = mysqli_fetch_assoc($sizes)) {
            echo '<option value="' . $pd[$col] . '">' . $pd[$col] . '</option>';
        }
    } else {
        echo '<option value=""></option>';
    }
} ?>
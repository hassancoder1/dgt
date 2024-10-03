<?php include('../connection.php');
if (isset($_POST["goods_id"])) {
    $goods_id = $_POST['goods_id'];
    fetchAndDisplayOptionsByGoodsID($goods_id,'size');

    /*$purchase_details = mysqli_query($connect, "SELECT DISTINCT size FROM `purchase_details` WHERE goods_id= '$goods_id'");
    if (mysqli_num_rows($purchase_details) > 0) {
        while ($pd = mysqli_fetch_assoc($purchase_details)) {
            echo '<option value="' . $pd['size'] . '">' . $pd['size'] . '</option>';
        }
    } else {
        echo '<option value=""></option>';
    }*/
} ?>
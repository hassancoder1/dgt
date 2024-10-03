<?php include('../connection.php');
if (isset($_POST["goods_id"])) {
    $goods_id = $_POST['goods_id'];
    echo fetchAndDisplayPurchaseOptionsByGoodsID($goods_id,'allot');
} ?>
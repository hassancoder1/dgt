<?php include('../connection.php');
if (isset($_POST["goods_id"])) {
    $goods_id = $_POST['goods_id'];
    $p_accs = fetchAndDisplayPurchaseOptionsByGoodsID($goods_id, 'p_khaata_no', true);
    echo json_encode($p_accs);
} ?>
<?php
require "../connection.php";
if (isset($_POST['warehouse']) && isset($_POST['allot'])) {
    $warehouse = $_POST['warehouse'];
    $allot = $_POST['allot'];
    $GloadingResult = $connect->query("SELECT id, t_id, t_sr, created_at FROM general_loading");
    $LloadingResult = $connect->query("SELECT id, t_id, t_sr, created_at FROM local_loading");
    $Gloading = mysqli_fetch_all($GloadingResult, MYSQLI_ASSOC);
    $Lloading = mysqli_fetch_all($LloadingResult, MYSQLI_ASSOC);
    $GloadingMap = [];
    foreach ($Gloading as $g) {
        $GloadingMap[$g['id']] = [$g['t_id'], $g['t_sr'], $g['created_at']];
    }
    $LloadingMap = [];
    foreach ($Lloading as $l) {
        $LloadingMap[$l['id']] = [$l['t_id'], $l['t_sr'], $l['created_at']];
    }
    $fetchSales = $connect->query("SELECT id, loading_id, good_code, ps_info, good_data FROM warehouses WHERE p_s='s' AND ps_info IS NOT NULL");
    $data = $connect->query("SELECT id, loading_id, good_code, type, ps_info, good_data FROM warehouses WHERE p_s='p' AND JSON_EXTRACT(good_data, '$.good.allotment_name') = '$allot'");
    $sortedEntries = [];
    $salesEntries = mysqli_fetch_all($fetchSales, MYSQLI_ASSOC);
    $salesByGoodCode = [];
    foreach ($salesEntries as $sale) {
        $salesByGoodCode[$sale['good_code']] = $sale;
    }
    if ($data->num_rows > 0) {
        $warehouseEntries = mysqli_fetch_all($data, MYSQLI_ASSOC);
        foreach ($warehouseEntries as $one) {
            if ($one['type'] === 'local') {
                $one['t_id']   = isset($LloadingMap[$one['loading_id']]) ? $LloadingMap[$one['loading_id']][0] : null;
                $one['t_sr']   = isset($LloadingMap[$one['loading_id']]) ? $LloadingMap[$one['loading_id']][1] : null;
                $one['date']   = isset($LloadingMap[$one['loading_id']]) ? $LloadingMap[$one['loading_id']][2] : null;
            } else {
                $one['t_id']   = isset($GloadingMap[$one['loading_id']]) ? $GloadingMap[$one['loading_id']][0] : null;
                $one['t_sr']   = isset($GloadingMap[$one['loading_id']]) ? $GloadingMap[$one['loading_id']][1] : null;
                $one['date']   = isset($GloadingMap[$one['loading_id']]) ? $GloadingMap[$one['loading_id']][2] : null;
            }
            $one['good_data'] = json_decode($one['good_data'], true);
            $one['good_data']['good']['goods_name'] = goodsName($one['good_data']['good']['goods_id']);
            $one['date'] = my_date($one['date']);
            $psInfo = json_decode($one['ps_info'], true);
            $saleGoods = isset($psInfo['sale_goods']) ? $psInfo['sale_goods'] : [];
            $purchaseQuantity = isset($one['good_data']['quantity_no']) ? $one['good_data']['quantity_no'] : 0;
            foreach ($saleGoods as $saleGoodCode) {
                if (isset($salesByGoodCode[$saleGoodCode])) {
                    $saleEntry = $salesByGoodCode[$saleGoodCode];
                    $saleGoodData = json_decode($saleEntry['good_data'], true);
                    $saleQuantity = isset($saleGoodData['quantity_no']) ? $saleGoodData['quantity_no'] : 0;
                    $purchaseQuantity = $purchaseQuantity - $saleQuantity;
                    if ($purchaseQuantity <= 0) {
                        $purchaseQuantity = 0;
                        break;
                    }
                }
            }
            $one['good_data']['quantity_no'] = $purchaseQuantity;
            if ($purchaseQuantity > 0) {
                $sortedEntries[] = $one;
            }
        }
    }
    echo json_encode($sortedEntries);
}

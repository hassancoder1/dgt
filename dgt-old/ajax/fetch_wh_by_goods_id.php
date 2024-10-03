<?php include('../connection.php');
if (isset($_POST["goods_id"]) && isset($_POST["wh_kd_id"])) {
    $array = array();
    $goods_id = $_POST['goods_id'];
    $size = $_POST['size'];
    $wh_kd_id = $_POST['wh_kd_id'];

    $data1 = fetch('purchase_details', array('goods_id' => $goods_id, 'size' => $size));
    $data = fetch('purchase_details', array('goods_id' => $goods_id, 'size' => $size));
    $warehouse_name = '';
    $wh_stock_qty_no = $wh_stock_total_kgs = $wh_sale_qty_no = $wh_sale_total_kgs = 0;
    $warehouse_khaata_ids = array();
    $khaataDetailsData = khaataDetailsData($wh_kd_id);
    if (!empty($khaataDetailsData)) {
        $dddd = khaataSingle($khaataDetailsData['khaata_id']);
        $warehouse_name = $dddd['khaata_no'] . ' ' . $khaataDetailsData['comp_name'];
    }
    $array['warehouse_name'] = $warehouse_name;
    if (mysqli_num_rows($data) > 0) {
        while ($datum1 = mysqli_fetch_assoc($data1)) {
            $parent_id = $datum1['parent_id'];
            $parent_data = fetch('purchases', array('id' => $parent_id));
            $p = mysqli_fetch_assoc($parent_data);
            $purchase_sale_type = $p['type'];
            $ware_json = json_decode($datum1['ware_json']);
            $aware_json = json_decode($datum1['aware_json']); //booking purchase
            $tware_json = json_decode($datum1['tware_json']); //local purchase
            if (!empty($ware_json)) {
                //$warehouse_khaata_ids[] = $ware_json->party_khaata_id;
                $warehouse_khaata_ids[] = $ware_json->party_kd_id;
            }
            if ($purchase_sale_type == 'booking') {
                if (!empty($aware_json)) {
                    $warehouse_khaata_ids[] = $aware_json->party_kd_id;
                }
            } else {
                if (!empty($tware_json)) {
                    $warehouse_khaata_ids[] = $tware_json->party_kd_id;
                }
            }
        }
        while ($datum = mysqli_fetch_assoc($data)) {
            if (in_array($wh_kd_id, $warehouse_khaata_ids)) {
                $wh_stock_qty_no += $datum['qty_no'];
                $wh_stock_total_kgs += $datum['total_kgs'];
            }
        }
        $array['wh_stock_qty_no'] = $wh_stock_qty_no;
        $array['wh_stock_total_kgs'] = $wh_stock_total_kgs;
    }
    $data_sales = fetch('sale_details', array('goods_id' => $goods_id, 'size' => $size));
    while ($sale = mysqli_fetch_assoc($data_sales)) {
        if ($wh_kd_id == $sale['wh_kd_id']) {
            $wh_sale_qty_no += $sale['qty_no'];
            $wh_sale_total_kgs += $sale['total_kgs'];
        }
    }
    $array['wh_sale_qty_no'] = $wh_sale_qty_no;
    $array['wh_sale_total_kgs'] = $wh_sale_total_kgs;
    $array['wh_bal_qty_no'] = $wh_stock_qty_no - $wh_sale_qty_no;
    $array['wh_bal_total_kgs'] = $wh_stock_total_kgs - $wh_sale_total_kgs;
    echo json_encode($array);
} ?>
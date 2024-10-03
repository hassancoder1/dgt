<?php include('../connection.php');
if (isset($_POST["goods_id"]) && isset($_POST["type"])) {
    $array = array();
    $type = $_POST['type'];
    $goods_id = $_POST['goods_id'];
    $size = $_POST['size'];
    //$warehouse_khaata_id = $_POST['warehouse_khaata_id'];
    if ($size != '') {
        $condition = array('goods_id' => $goods_id, 'size' => $size);
    } else {
        $condition = array('goods_id' => $goods_id);
    }
    if ($type == 'purchase') {
        $parent_table = 'purchases';
        $data = fetch('purchase_details', $condition);
    } else {
        $parent_table = 'sales';
        $data = fetch('sale_details', $condition);
    }
    $qty_no = $total_kgs = 0;
    /*$warehouse_khaata_ids = array();*/
    if (mysqli_num_rows($data) > 0) {
        /*while ($datum1 = mysqli_fetch_assoc($data1)) {
            $parent_id = $datum1['parent_id'];
            $parent_data = fetch($parent_table, array('id' => $parent_id));
            $p = mysqli_fetch_assoc($parent_data);
            $purchase_sale_type = $p['type'];
            $ware_json = json_decode($datum1['ware_json']);
            $aware_json = json_decode($datum1['aware_json']); //booking purchase
            $tware_json = json_decode($datum1['tware_json']); //local purchase
            if (!empty($ware_json)) {
                $warehouse_khaata_ids[] = $ware_json->party_khaata_id;
            }
            if ($purchase_sale_type == 'booking') {
                if (!empty($aware_json)) {
                    $warehouse_khaata_ids[] = $aware_json->party_khaata_id;
                }
            } else {
                if (!empty($tware_json)) {
                    $warehouse_khaata_ids[] = $tware_json->party_khaata_id;
                }
            }
        }*/
        while ($datum = mysqli_fetch_assoc($data)) {
            $qty_no += $datum['qty_no'];
            $total_kgs += $datum['total_kgs'];
            /*if ($warehouse_khaata_id > 0) {
                if (in_array($warehouse_khaata_id, $warehouse_khaata_ids)) {
                    $qty_no += $datum['qty_no'];
                    $total_kgs += $datum['total_kgs'];
                }
            }else{
                $qty_no += $datum['qty_no'];
                $total_kgs += $datum['total_kgs'];
            }*/
        }
        $array['qty_no'] = $qty_no;
        $array['total_kgs'] = $total_kgs;
    }
    echo json_encode($array);
} ?>
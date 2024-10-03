<?php $pageURL = 'afghan-invoices';
$page_title = 'AFGHAN INVOICES';
include("header.php");
global $branchId, $connect;
$view_url = $remove = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    $goods_name = isset($_GET['goods_name']) ? mysqli_real_escape_string($connect, $_GET['goods_name']) : '';
    $size = isset($_GET['size']) ? mysqli_real_escape_string($connect, $_GET['size']) : '';
    $brand = isset($_GET['brand']) ? mysqli_real_escape_string($connect, $_GET['brand']) : '';
    $origin = isset($_GET['origin']) ? mysqli_real_escape_string($connect, $_GET['origin']) : '';
    $start = isset($_GET['start']) ? mysqli_real_escape_string($connect, $_GET['start']) : '';
    $end = isset($_GET['end']) ? mysqli_real_escape_string($connect, $_GET['end']) : '';
    $is_transferred = isset($_GET['is_transferred']) ? mysqli_real_escape_string($connect, $_GET['is_transferred']) : '';
    $s_khaata_id = isset($_GET['s_khaata_id']) ? mysqli_real_escape_string($connect, $_GET['s_khaata_id']) : '';

    $params = [
        'goods_name' => $goods_name,
        'size' => $size,
        'brand' => $brand,
        'origin' => $origin,
        'start' => $start,
        'end' => $end,
        'is_transferred' => $is_transferred,
        's_khaata_id' => $s_khaata_id
    ];

    foreach ($params as $key => $value) {
        if ($value != '') {
            $pageURL .= (!str_contains($pageURL, '?') ? '?' : '&') . $key . '=' . urlencode($value);
        }
    }
    $view_url = '&view=1';
    /*if (isset($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
        $pageURL .= '?goods_name=' . $goods_name;
    }
    if (isset($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $pageURL .= '&size=' . $size;
    }
    if (isset($_GET['brand'])) {
        $brand = mysqli_real_escape_string($connect, $_GET['brand']);
        $pageURL .= '&brand=' . $brand;
    }
    if (isset($_GET['origin'])) {
        $origin = mysqli_real_escape_string($connect, $_GET['origin']);
        $pageURL .= '&origin=' . $origin;
    }
    if (isset($_GET['start'])) {
        $start_print = $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '&start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end_print = $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
    if (isset($_GET['is_transferred'])) {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $pageURL .= '&is_transferred=' . $is_transferred;
    }
    if (isset($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
        $pageURL .= '&s_khaata_id=' . $s_khaata_id;
    }*/
} else {
    $view_url = '?view=1';
}
$sql = "SELECT * FROM `invoices` WHERE is_active = 1 ORDER BY id asc ";
$purchases = mysqli_query($connect, $sql); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex table-form text-nowrap align-items-center justify-content-between">
            <?php echo addNew($pageURL . $view_url, 'NEW', 'btn-sm'); ?>
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
            <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
            <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                <button class="btn btn-sm btn-success">PRINT</button>
            </form>
            <form method="get" class="d-flex align-items-center ">
                <?php echo searchInput('', 'form-control-sm'); ?>
                <?php echo $remove; ?>
                <div class="input-group">
                    <input type="date" name="start" value="<?php echo $start; ?>" class="form-control">
                </div>
                <div class="input-group">
                    <input type="date" name="end" value="<?php echo $end; ?>" class="form-control">
                </div>
                <div class="input-group">
                    <select id="goods_name" name="goods_name" class="form-select">
                        <option value="">ALL GOODS</option>
                        <?php $goods = fetch('goods');
                        while ($good = mysqli_fetch_assoc($goods)) {
                            $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                            echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group">
                    <select name="s_khaata_id" class="form-select">
                        <option value="" hidden>Customer A/c</option>
                        <?php $accounts_query = fetch('khaata');
                        while ($aa = mysqli_fetch_assoc($accounts_query)) {
                            $sel = $s_khaata_id == $aa['khaata_no'] ? 'selected' : '';
                            echo '<option ' . $sel . ' value="' . $aa['khaata_no'] . '">' . $aa['khaata_no'] . '</option>';
                        } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 76dvh;">
                    <table class="table mb-0 table-bordered table-sm fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>DETAILS</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>VAT</th>
                            <th>REPORT</th>
                            <th>PURCHASER</th>
                            <th>TRANSFER</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($sale = mysqli_fetch_assoc($purchases)) {
                            $sale_id = $sale['id'];
                            $sale_type = $sale['type'];
                            $is_acc = $sale['is_acc'];
                            $rowColor = '';
                            $seller_json = json_decode($sale['seller_json']);
                            $khaata_tr1 = json_decode($sale['khaata_tr1']);
                            if (empty($khaata_tr1)) {
                                $rowColor = 'bg-danger bg-opacity-25';
                                //continue;
                            }
                            $cntrs = purchaseSpecificData($sale_id, 'purchase_rows');
                            $totals = purchaseSpecificData($sale_id, 'product_details');
                            $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                            $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                            $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . '<br>' : '';
                            $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';
                            if ($is_search) {
                                if ($start != '') {
                                    if ($sale['p_date'] < $start) continue;
                                }
                                if ($end != '') {
                                    if ($sale['p_date'] > $end) continue;
                                }
                                $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                                if ($goods_name != '') {
                                    if ($goods_name != $GoodsKaNaam) continue;
                                }
                                /*if ($is_transferred != '') {
                                    if ($is_transferred ==1){
                                        if (empty($khaata_tr1)) continue;
                                    }
                                    if ($is_transferred ==0){
                                        if (!empty($khaata_tr1)) continue;
                                    }
                                }*/
                                if ($s_khaata_id != '') {
                                    if ($s_khaata_id != $seller_json->khaata_no) continue;
                                }
                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;

                            $sd_data = fetch('purchase_details', array('parent_id' => $sale_id));
                            $vat_items = $vat_items_amount = 0;
                            while ($sd_datum = mysqli_fetch_assoc($sd_data)) {
                                if ($sd_datum['rate2'] > 0) {
                                    $vat_items_amount += $sd_datum['rate2'];
                                    $vat_items++;
                                }
                            }
                            //if ($vat_items_amount > 0) continue; ?>
                            <tr class="pointer clickable-row <?php echo $rowColor; ?>"
                                data-href="<?php echo $pageURL . $view_url . '&id=' . $sale_id; ?>">
                                <td class="text-nowrap font-size-11">
                                    <?php echo '<b>P#</b>' . $sale_id . purchaseSpecificData($sale_id, 'purchase_type');
                                    echo '<br><span class="font-size-11">';
                                    echo '<b>D.</b>' . date('y-m-d', strtotime($sale['p_date']));
                                    echo $sale['city'] != '' ? '<br><b>B.NAME</b>' . $sale['city'] : '';
                                    echo '</span>'; ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php echo '<b>B.</b> ' . branchName($sale['branch_id']) . '<br>'; ?>
                                    <?php echo '<b>OWNER</b> ' . $sale['p_khaata_no']; ?>
                                </td>
                                <td class="font-size-11 text-nowrap"><?php echo $Goods . $ITEMS . $Qty . $KGs; ?></td>
                                <td class="text-dark text-nowrap">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        //echo '<br><b>Transfer </b>' . $sale['t_date'];
                                    } ?>
                                </td>
                                <td><?php echo $vat_items_amount; ?></td>
                                <td class="font-size-10">
                                    <div style="width: 130px"><?php echo readMoreTooltip($sale['report'], 80) ?></div>
                                </td>
                                <td class="font-size-10 text--nowrap">
                                    <?php if (!empty($seller_json)) {
                                        echo '<b>A/C.</b>' . $seller_json->khaata_no . '<br>';
                                        if ($is_acc == 1) {
                                            $seller_khaata = khaataSingle($seller_json->khaata_id);
                                            echo '<b>COMP.</b>' . $seller_khaata['comp_name'];
                                        } else {
                                            echo '<b>PURCAHSE NAME</b>' . $seller_json->s_name . '<br>';
                                            echo '<b>COMP.</b>' . $seller_json->s_company . '<br>';
                                            echo '<b>VAT#</b>' . $seller_json->s_weight_no . '<br>';
                                        }
                                    } ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php if (!empty($khaata_tr1)) {
                                        echo '<b>Dr.A/C</b>' . $khaata_tr1->dr_khaata_no;
                                        echo isset($khaata_tr1->vat_khaata_no) ? '<b>&</b>' . $khaata_tr1->vat_khaata_no : '';
                                        echo ' <b>Cr.A/C</b>' . $khaata_tr1->cr_khaata_no;
                                        echo '<br><b>Transfer</b>' . date('y-m-d', strtotime($khaata_tr1->transfer_date));
                                        echo '<br><b>R. SR#</b> ';
                                        //echo getTransferredToRoznamchaSerial('Business', $sale_id, 'purchase_market');
                                    } ?>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                        <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                        <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                        <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
</script>
<?php if (isset($_POST['recordSubmit'])) {
    $type = 'danger';
    $msg = 'DB Error';
    $action = mysqli_real_escape_string($connect, $_POST['action']);
    $is_acc = mysqli_real_escape_string($connect, $_POST['is_acc']);
    $r_type = 'market';
    $fixed_khaata_no_market = 'M1';
    $fixed_khaata_id_market = '43';
    $data = array(
        'type' => $r_type,
        'is_acc' => $is_acc,
        'p_date' => mysqli_real_escape_string($connect, $_POST['p_date']),
        'p_khaata_no' => mysqli_real_escape_string($connect, $_POST['p_acc']),
        'receiver' => mysqli_real_escape_string($connect, $_POST['receiver']),//payemnt receive date
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id'])
    );
    if ($is_acc == 1) {
        $kk_iidd = mysqli_real_escape_string($connect, $_POST['khaata_id']);
        $pp_khaataa = khaataSingle($kk_iidd);
        $data['s_name'] = $pp_khaataa['khaata_name'];
        $seller_json = array(
            'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
            'khaata_id' => $kk_iidd
        );
    } else {
        $data['s_name'] = mysqli_real_escape_string($connect, $_POST['s_name']);
        $seller_json = array(
            'khaata_no' => $fixed_khaata_no_market,
            'khaata_id' => $fixed_khaata_id_market,
            's_name' => mysqli_real_escape_string($connect, $_POST['s_name']),
            's_company' => mysqli_real_escape_string($connect, $_POST['s_company']),
            's_weight_no' => mysqli_real_escape_string($connect, $_POST['s_weight_no']),
            's_phone' => mysqli_real_escape_string($connect, $_POST['s_phone']),
            's_email' => mysqli_real_escape_string($connect, $_POST['s_email']),
            's_address' => mysqli_real_escape_string($connect, $_POST['s_address']),
        );
    }
    $data['seller_json'] = json_encode($seller_json, JSON_UNESCAPED_UNICODE);
    $data2 = array(
        'goods_id' => mysqli_real_escape_string($connect, $_POST['goods_id']),
        'size' => mysqli_real_escape_string($connect, $_POST['size']),
        'brand' => mysqli_real_escape_string($connect, $_POST['brand']),
        'allot_name' => mysqli_real_escape_string($connect, $_POST['allot_name']),
        'wh_k_id' => mysqli_real_escape_string($connect, $_POST['wh_k_id']),
        'wh_kd_id' => mysqli_real_escape_string($connect, $_POST['wh_kd_id']),
        'qty_name' => mysqli_real_escape_string($connect, $_POST['qty_name']),
        'qty_no' => mysqli_real_escape_string($connect, $_POST['qty_no']),
        'qty_kgs' => mysqli_real_escape_string($connect, $_POST['qty_kgs']),
        'total_kgs' => mysqli_real_escape_string($connect, $_POST['total_kgs']),
        'empty_kgs' => mysqli_real_escape_string($connect, $_POST['empty_kgs']),
        'total_qty_kgs' => mysqli_real_escape_string($connect, $_POST['total_qty_kgs']),
        'net_kgs' => mysqli_real_escape_string($connect, $_POST['net_kgs']),
        'divide' => mysqli_real_escape_string($connect, $_POST['divide']),
        'weight' => mysqli_real_escape_string($connect, $_POST['weight']),
        'total' => mysqli_real_escape_string($connect, $_POST['total']),
        'price' => mysqli_real_escape_string($connect, $_POST['price']),
        'currency1' => mysqli_real_escape_string($connect, $_POST['currency1']),
        'rate1' => mysqli_real_escape_string($connect, $_POST['rate1']),
        'amount' => mysqli_real_escape_string($connect, $_POST['amount']),
        'currency2' => mysqli_real_escape_string($connect, $_POST['currency2']),
        'rate2' => mysqli_real_escape_string($connect, $_POST['rate2']),
        'opr' => '',
        'final_amount' => mysqli_real_escape_string($connect, $_POST['final_amount']),
    );
    if ($action == 'insert') {
        $data['created_by'] = $userId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $done = insert('invoices', $data);
        if ($done) {
            $pp_id = $connect->insert_id;
            $url = $pageURL . "?view=1&id=" . $pp_id;
            $type = 'success';
            $msg = ucfirst($r_type) . ' Purchase#' . $pp_id . ' saved.';
            $data2['parent_id'] = $pp_id;
            $pd_sr = getPurchaseDetailsSerial($pp_id);
            $data2['d_sr'] = $pd_sr;
            $details_added = insert('purchase_details', $data2);
            if ($details_added) {
                $ggd_id = $connect->insert_id;
                $url .= '&sd_id=' . $ggd_id . '&action=update_details';
            }
        }
    } else {
        $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('invoices', $data, array('id' => $s_id_hidden));
        if ($done) {
            $url = $pageURL . "?view=1&id=" . $s_id_hidden;
            $type = 'warning';
            $msg = ucfirst($r_type) . ' Purchase#' . $s_id_hidden . ' updated.';
            if ($action == 'add_details') {
                $data2['parent_id'] = $s_id_hidden;
                $pd_sr = getPurchaseDetailsSerial($s_id_hidden);
                $data2['d_sr'] = $pd_sr;
                $details_added = insert('purchase_details', $data2);
                if ($details_added) {
                    $ggd_id = $connect->insert_id;
                    $url .= '&sd_id=' . $ggd_id . '&action=update_details';
                    $msg .= ' and New Container saved.';
                }
            }
            if ($action == 'update_details') {
                $sd_id_hidden = mysqli_real_escape_string($connect, $_POST['sd_id_hidden']);
                $url .= '&sd_id=' . $sd_id_hidden . '&action=update_details';
                $details_added = update('purchase_details', $data2, array('id' => $sd_id_hidden));
                if ($details_added) {
                    $msg .= ' with Container details.';
                }
            }
        }
    }
    message($type, $url, $msg);
}
if (isset($_POST['deleteSDSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $sr_details_hidden = mysqli_real_escape_string($connect, $_POST['sr_details_hidden']);
    $s_id_delete = mysqli_real_escape_string($connect, $_POST['s_id_delete']);
    $sd_id_delete = mysqli_real_escape_string($connect, $_POST['sd_id_delete']);
    $done = mysqli_query($connect, "DELETE FROM `purchase_details` WHERE id='$sd_id_delete'");
    $url = $pageURL . "?view=1&id=" . $s_id_delete;
    if ($done) {
        $msg = "Container# " . $sr_details_hidden . " has been deleted from Purchase# " . $s_id_delete;
        $type = "success";
    }
    message($type, $url, $msg);
} ?>
<?php if (isset($_GET['view']) && $_GET['view'] == 1) {
    $sr_no = getAutoIncrement('invoices');
    $action_hidden = 'insert';
    $currency1 = 'AED';
    $p_date = date('Y-m-d');
    $is_acc_yes = 'checked';
    $s_company = $s_weight_no = $s_phone = $s_email = $s_address = '';
    $is_acc_no = $khaata_no = $p_acc = $s_name = $receiver = $size = $brand = $is_qty = $type = $city = $qty_name = $divide = $price = $currency2 = $report = $allot_name = '';
    $branch__id = $khaata_id = $sd_id = $sale_id = $goods_id = $wh_k_id = $wh_kd_id = $qty_no = $qty_kgs = $total_kgs = $empty_kgs = $total_qty_kgs = $net_kgs = $weight = $total = $rate1 = $amount = $rate2 = $final_amount = 0;
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $action_hidden = 'update';
        $sale_id = $sr_no = mysqli_real_escape_string($connect, $_GET['id']);
        $records = fetch('invoices', array('id' => $sale_id));
        $record = mysqli_fetch_assoc($records);
        $branch__id = $record['branch_id'];
        $type = $record['type'];
        $p_date = $record['p_date'];
        $is_acc = $record['is_acc'];
        $is_acc_yes = $record['is_acc'] == 1 ? 'checked' : '';
        $is_acc_no = $record['is_acc'] == 0 ? 'checked' : '';

        $s_name = $record['s_name'];
        $purchaser_json = json_decode($record['seller_json']);
        $khaata_no = $purchaser_json->khaata_no;
        $khaata_id = $purchaser_json->khaata_id;
        $s_company = $purchaser_json->s_company ?? '';
        $s_weight_no = $purchaser_json->s_weight_no ?? '';
        $s_phone = $purchaser_json->s_phone ?? '';
        $s_email = $purchaser_json->s_email ?? '';
        $s_address = $purchaser_json->s_address ?? '';
        $p_acc = $record['p_khaata_no'];
        $receiver = $record['receiver'];
        $city = $record['city'];
        $report = $record['report'];
        if (isset($_GET['action'])) {
            $action_hidden = $action = mysqli_real_escape_string($connect, $_GET['action']);
            $add_details = $action == 'add_details';
            if (isset($_GET['sd_id']) && $_GET['sd_id'] > 0) {
                $update_details = $action == 'update_details';
                $sd_id = mysqli_real_escape_string($connect, $_GET['sd_id']);
                $records2 = fetch('purchase_details', array('id' => $sd_id));
                $record2 = mysqli_fetch_assoc($records2);
                $goods_id = $record2['goods_id'];
                $size = $record2['size'];
                $brand = $record2['brand'];
                $allot_name = $record2['allot_name'];
                $wh_k_id = $record2['wh_k_id'];
                $wh_kd_id = $record2['wh_kd_id'];
                $qty_name = $record2['qty_name'];
                $divide = $record2['divide'];
                $price = $record2['price'];
                $currency1 = $record2['currency1'];
                $currency2 = $record2['currency2'];
                $qty_no = $record2['qty_no'];
                $qty_kgs = $record2['qty_kgs'];
                $total_kgs = $record2['total_kgs'];
                $empty_kgs = $record2['empty_kgs'];
                $total_qty_kgs = $record2['total_qty_kgs'];
                $net_kgs = $record2['net_kgs'];
                $weight = $record2['weight'];
                $total = $record2['total'];
                $rate1 = $record2['rate1'];
                $amount = $record2['amount'];
                $is_qty = $record2['is_qty'];
                $rate2 = $record2['rate2'];
                $opr = $record2['opr'];
                $final_amount = $record2['final_amount'];
                $is_qty = $record2['is_qty'] == 1 ? 'checked' : '';
            }
        }
    }
    $topArray = array(
        array('heading' => 'INVOICE # ', 'value' => $sr_no),
        array('heading' => 'D. ', 'value' => '<input type="date" name="_date" value="' . $p_date . '">'),
    );
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show'); $('#imp_khaata_no').focus();});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Afghan Invoice</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <form method="post" class="table-form">
                                <div class="d-flex justify-content-between flex-lg-nowrap gap-1 text-uppercase small">
                                    <div>
                                        <?php foreach ($topArray as $item) {
                                            echo '<b>' . $item['heading'] . '</b><span class="text-muted">' . $item['value'] . '</span><br>';
                                        } ?>
                                        <div class="d-flex align-items-center mt-2">
                                            <label for="branch_id" class="mb-0 bold">B. </label>
                                            <select class="bg-transparent border-1" id="branch_id" name="branch_id" style="min-width: 100px;">
                                                <?php $array_branch_condition = SuperAdmin() ? array() : array('id' => $branchId);
                                                $branches = fetch('branches', $array_branch_condition);
                                                while ($b = mysqli_fetch_assoc($branches)) {
                                                    $b_select = $b['id'] == $branch__id ? 'selected' : '';
                                                    echo '<option ' . $b_select . ' value="' . $b['id'] . '">' . $b['b_code'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php $array_account = array(
                                        array('label' => ' A/C#', 'id' => '_khaata_no'),
                                        array('label' => 'NAME', 'id' => '_khaata_name'),
                                        array('label' => 'B.', 'id' => '_b_name'),
                                        array('label' => 'CAT.', 'id' => '_c_name'),
                                        array('label' => 'BIZ', 'id' => '_business_name'),
                                        array('label' => 'COMP.', 'id' => '_comp_name')
                                    );
                                    $array_contacts = array('label' => '', 'id' => '_contacts'); ?>
                                    <div class="d-flex text-uppercase text-dark small">
                                        <?php echo '<div><b>IMPORTER</b>';
                                        foreach ($array_account as $item) {
                                            echo '<b>' . $item['label'] . '</b> <span class="text-muted" id="i' . $item['id'] . '"></span><br>';
                                        }
                                        echo '</div>';
                                        echo '<div>';//second div
                                        echo '<b>' . $array_contacts['label'] . '</b> <span class="text-muted" id="i' . $array_contacts['id'] . '"></span>';
                                        echo '</div>'; ?>
                                    </div>
                                    <div class="d-flex text-uppercase text-dark small px-1 border-start border-end border-dark">
                                        <?php echo '<div><b>EXPORTER</b>';
                                        foreach ($array_account as $item) {
                                            echo '<b>' . $item['label'] . '</b> <span class="text-muted" id="e' . $item['id'] . '"></span><br>';
                                        }
                                        echo '</div>';
                                        echo '<div>';//second div
                                        echo '<b>' . $array_contacts['label'] . '</b> <span class="text-muted" id="e' . $array_contacts['id'] . '"></span>';
                                        echo '</div>'; ?>
                                    </div>
                                    <div class="d-flex text-uppercase text-dark small">
                                        <?php echo '<div><b>NOTIFY PARTY</b>';
                                        foreach ($array_account as $item) {
                                            echo '<b>' . $item['label'] . '</b> <span class="text-muted" id="n' . $item['id'] . '"></span><br>';
                                        }
                                        echo '</div>';
                                        echo '<div>';//second div
                                        echo '<b>' . $array_contacts['label'] . '</b> <span class="text-muted" id="n' . $array_contacts['id'] . '"></span>';
                                        echo '</div>'; ?>
                                    </div>
                                </div>
                                <hr class="mt-0">
                                <div class="row gx-1 mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <div class="input-group position-relative">
                                            <label for="imp_khaata_no">Importer</label>
                                            <input autofocus type="text" id="imp_khaata_no" name="imp_khaata_no" class="form-control" value="<?php //echo $khaata_no; ?>">
                                            <small class="error-response top-0" id="imp_response"></small>
                                        </div>
                                        <input type="hidden" name="imp_khaata_id" id="imp_khaata_id">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group position-relative">
                                            <label for="exp_khaata_no">Exporter</label>
                                            <input type="text" id="exp_khaata_no" name="exp_khaata_no" class="form-control" value="<?php //echo $khaata_no; ?>">
                                            <small class="error-response top-0" id="exp_response"></small>
                                        </div>
                                        <input type="hidden" name="exp_khaata_id" id="exp_khaata_id">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group position-relative">
                                            <label for="noti_khaata_no">Notify Party</label>
                                            <input type="text" id="noti_khaata_no" name="noti_khaata_no" class="form-control" value="<?php //echo $khaata_no; ?>">
                                            <small class="error-response top-0" id="noti_response"></small>
                                        </div>
                                        <input type="hidden" name="noti_khaata_id" id="noti_khaata_id">
                                    </div>
                                </div>
                                <?php if ($action_hidden != 'update') { ?>
                                    <div class="row gx-1 gy-3">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="goods_id">GOODS</label>
                                                <select id="goods_id" name="goods_id" class="form-select" required>
                                                    <option hidden value="">Select</option>
                                                    <?php $goods = fetch('goods');
                                                    while ($good = mysqli_fetch_assoc($goods)) {
                                                        $g_selected = $good['id'] == $goods_id ? 'selected' : '';
                                                        echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="size">SIZE</label>
                                                <select class="form-select" name="size" id="size" required>
                                                    <option hidden value="">Select</option>
                                                    <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = '$goods_id'");
                                                    while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                        //$size_selected = $size_s['size'] == $size ? 'selected' : '';
                                                        echo '<option  value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="brand">Brand</label>
                                                <select class="form-select" name="brand" id="brand" required>
                                                    <option hidden value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="party_khaata_id">WH.A/C</label>
                                                <select class="form-select" name="wh_k_id" id="party_khaata_id"
                                                        required>
                                                    <option hidden value="">Select</option>
                                                    <?php $result = fetch('khaata_details', array('static_type' => 'Warehouse'));
                                                    while ($kh = mysqli_fetch_assoc($result)) {
                                                        if ($kh['comp_name'] != '') {
                                                            $wh_khaata = khaataSingle($kh['khaata_id']);
                                                            $wh_selected = $wh_khaata['id'] == $wh_k_id ? 'selected' : '';
                                                            echo '<option ' . $wh_selected . ' value="' . $wh_khaata['id'] . '">' . $wh_khaata['khaata_no'] . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="wh_kd_id">WH.</label>
                                                <?php //echo '$wh_k_id=' . $wh_k_id . '<br>' . '$wh_kd_id=' . $wh_kd_id;?>
                                                <select id="wh_kd_id" name="wh_kd_id" class="form-select">
                                                    <option hidden value="">Select</option>
                                                    <?php
                                                    //$result2 = fetch('khaata_details', array('static_type' => 'Warehouse', 'khaata_id' => $wh_k_id));
                                                    $result2 = mysqli_query($connect, "SELECT * FROM `khaata_details` WHERE static_type = 'Warehouse' AND khaata_id= 14;");
                                                    while ($kd = mysqli_fetch_assoc($result2)) {
                                                        if ($kd['comp_name'] != '') {
                                                            $wh_selected2 = $kd['id'] == $wh_kd_id ? 'selected' : '';
                                                            echo '<option ' . $wh_selected2 . ' value="' . $kd['id'] . '">' . $kd['khaata_no'] . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="allot_name">Allot Name</label>
                                                <input value="<?php echo $allot_name; ?>" id="allot_name"
                                                       name="allot_name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="qty_name">Qty Name</label>
                                                <input value="<?php echo $qty_name; ?>" id="qty_name" name="qty_name"
                                                       class="form-control" required>
                                                <label for="qty_no">Qty#</label>
                                                <input value="<?php echo $qty_no; ?>" id="qty_no" name="qty_no"
                                                       class="form-control currency" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="qty_kgs">Qty KGs</label>
                                                <input value="<?php echo $qty_kgs; ?>" id="qty_kgs" name="qty_kgs"
                                                       class="form-control currency" required>
                                                <label for="total_kgs">Total KGs</label>
                                                <input value="<?php echo $total_kgs; ?>" id="total_kgs" name="total_kgs"
                                                       class="form-control" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="empty_kgs">Empty KGs</label>
                                                <input value="<?php echo $empty_kgs; ?>" id="empty_kgs" name="empty_kgs"
                                                       class="form-control currency" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="total_qty_kgs">Total Qty KGs</label>
                                                <input value="<?php echo $total_qty_kgs; ?>" id="total_qty_kgs"
                                                       name="total_qty_kgs"
                                                       class="form-control" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="net_kgs">NET KGs</label>
                                                <input value="<?php echo $net_kgs; ?>" id="net_kgs" name="net_kgs"
                                                       class="form-control"
                                                       required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="divide">DIVIDE</label>
                                                <select id="divide" name="divide" class="form-select">
                                                    <?php $divides = array('TON' => 'TON', 'KGs' => 'KG', 'CARTON' => 'CARTON');
                                                    foreach ($divides as $item => $val) {
                                                        $d_sel = $divide == $val ? 'selected' : '';
                                                        echo '<option ' . $d_sel . ' value="' . $val . '">' . $item . '</option>';
                                                    } ?>
                                                </select>
                                                <label for="weight">WEIGHT</label>
                                                <input value="<?php echo $weight; ?>" id="weight" name="weight"
                                                       class="form-control currency" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="total">TOTAL</label>
                                                <input value="<?php echo $total; ?>" id="total" name="total"
                                                       class="form-control"
                                                       required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="price">PRICE</label>
                                                <select id="price" name="price" class="form-select">
                                                    <?php $prices = array('TON PRICE' => 'TON', 'KGs PRICE' => 'KG', 'CARTON PRICE' => 'CARTON');
                                                    foreach ($prices as $item => $val) {
                                                        $pr_sel = $price == $val ? 'selected' : '';
                                                        echo '<option ' . $pr_sel . ' value="' . $val . '">' . $item . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row gx-1 gy-1">
                                            <div class="col-9">
                                                <div class="row gx-1">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <label for="currency1">Currency</label>
                                                            <select id="currency1" name="currency1" class="form-select"
                                                                    required>
                                                                <option selected hidden disabled value="">Select
                                                                </option>
                                                                <?php $currencies = fetch('currencies');
                                                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                    $crr_sel = $crr['name'] == $currency1 ? 'selected' : '';
                                                                    echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <label for="rate1">RATE</label>
                                                            <input value="<?php echo $rate1; ?>" id="rate1" name="rate1"
                                                                   class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <label for="amount" class="text-danger">AMOUNT</label>
                                                            <input value="<?php echo round($amount, 2); ?>" id="amount"
                                                                   name="amount"
                                                                   class="form-control currency" required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row gx-0 gy-2">
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <label for="vat">VAT [%]</label>
                                                            <input value="<?php echo $currency2; ?>" id="vat"
                                                                   name="currency2" type="number" min="0" step="any"
                                                                   class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <label for="vat_amnt">VAT AMOUNT</label>
                                                            <input value="<?php echo $rate2; ?>" id="vat_amnt"
                                                                   name="rate2" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <label for="final_amount" class="text-danger">FINAL
                                                                AMOUNT</label>
                                                            <input value="<?php echo $final_amount; ?>"
                                                                   id="final_amount" name="final_amount"
                                                                   class="form-control" required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="mt-1 row gx-1">
                                    <div class="col-auto">
                                        <button name="recordSubmit" id="recordSubmit-" disabled type="submit" class="btn btn-primary btn-sm">Submit Disabled</button>
                                    </div>
                                </div>
                                <input type="hidden" name="action" value="<?php echo $action_hidden; ?>">
                                <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                                <input type="hidden" name="sd_id_hidden" value="<?php echo $sd_id; ?>">
                            </form>
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead>
                                            <tr class="text-nowrap">
                                                <th>#</th>
                                                <th>GOODS</th>
                                                <th>SIZE</th>
                                                <th>BRAND</th>
                                                <th>QTY</th>
                                                <th>KGs</th>
                                                <th>EMPTY</th>
                                                <th>NET KGs</th>
                                                <th>Wt.</th>
                                                <th>TOTAL</th>
                                                <th>PRICE</th>
                                                <th>AMOUNT</th>
                                                <th>VAT AMNT</th>
                                                <th class="text-end">FINAL</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $sr_details = 1;
                                            $count_rows = $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $vat = $final_amount_total = 0;
                                            $pur_d_q = fetch('purchase_details', array('parent_id' => $sale_id));
                                            while ($details = mysqli_fetch_assoc($pur_d_q)) {
                                                $details_id = $details['id'];
                                                echo '<tr>';
                                                echo '<td>' . $sr_details . '</td>';
                                                echo '<td><a href="' . $pageURL . '?view=1&id=' . $sale_id . '&sd_id=' . $details_id . '&action=update_details">' . goodsName($details['goods_id']) . '</a></td>';
                                                echo '<td>' . $details['size'] . '</td>';
                                                echo '<td>' . $details['brand'] . '</td>';
                                                echo '<td>' . $details['qty_no'] . $details['qty_name'] . '</td>';
                                                echo '<td>' . $details['total_kgs'] . '</td>';
                                                echo '<td>' . round($details['total_qty_kgs'], 2) . '</td>';
                                                echo '<td>' . $details['net_kgs'];
                                                echo '<sub>' . $details['divide'] . '</sub>';
                                                echo '</td>';
                                                echo '<td>' . $details['weight'] . '</td>';
                                                echo '<td>' . $details['total'] . '</td>';
                                                echo '<td>' . $details['price'] . '</td>';
                                                echo '<td>' . round($details['amount']);
                                                echo '<sub>' . $details['currency1'] . '</sub>';
                                                echo '</td>';
                                                echo '<td>' . round($details['rate2'], 2);
                                                echo $details['rate2'] > 0 ? '<sub>' . $details['currency2'] . '%</sub>' : '';
                                                echo '</td>';
                                                echo '<td class="text-end">' . round($details['final_amount'], 2);
                                                echo '<sub>' . $details['currency2'] . '</sub>';
                                                echo '</td>';
                                                echo '<td>';
                                                //if (empty($p_data['khaata_tr1'])) {
                                                $delete_msg = 'Are you sure to delete? \nContainer#' . $sr_details . '\nUnder Purchase#' . $sale_id;
                                                echo '<form method="post" onsubmit="return confirm(\'' . $delete_msg . '\')"><input value="' . $sale_id . '" name="s_id_delete" type="hidden"><input value="' . $details_id . '" name="sd_id_delete" type="hidden"><input value="' . $sr_details . '" name="sr_details_hidden" type="hidden">';
                                                echo '<button name="deleteSDSubmit" type="submit" class="btn btn-sm p-0 ms-1 text-danger">Delete</button>';
                                                echo '</form>';
                                                //}
                                                echo '</td>';
                                                echo '</tr>';
                                                $sr_details++;
                                                $qty_no += $details['qty_no'];
                                                $qty_kgs += $details['qty_kgs'];
                                                $total_kgs += $details['total_kgs'];
                                                $total_qty_kgs += $details['total_qty_kgs'];
                                                $net_kgs += $details['net_kgs'];
                                                $total += $details['total'];
                                                $amount += $details['amount'];
                                                $vat += $details['rate2'];
                                                $final_amount_total += $details['final_amount'];
                                                $count_rows++;
                                            }
                                            if ($qty_no > 0) {
                                                echo '<tr>';
                                                echo '<th colspan="4"></th>';
                                                echo '<th class="fw-bold">' . $qty_no . '</th>';
                                                echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                                                echo '<th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>';
                                                echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                                                echo '<th colspan="1"></th>';
                                                echo '<th class="fw-bold">' . round($total, 2) . '</th>';
                                                echo '<th colspan="1"></th>';
                                                echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                                                echo '<th class="fw-bold">' . round($vat, 2) . '</th>';
                                                echo '<th class="fw-bold text-end">' . round($final_amount_total, 2) . '</th>';
                                                echo '</tr>';
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 order-1 fixed-sidebar table-form">

                            <div class="bottom-buttons">
                                <div class="px-2">
                                    <?php echo $sale_id > 0 ? '<a class="btn btn-sm w-100 btn-dark mt-3" href="' . $pageURL . '?view=1&id=' . $sale_id . '&action=add_details">New Container</a>' : ''; ?>
                                    <a href="#.print/purchase-market-single?s_id=<?php echo $sale_id; ?>&action=booking"
                                       class="btn btn-success btn-sm w-100 mt-3">PRINT</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.location = $(this).data("href");
        });
    });
    VirtualSelect.init({
        ele: '.v-select-sm',
        placeholder: 'Choose',
        // showValueAsTags: true,
        optionHeight: '30px',
        showSelectedOptionsFirst: true,
        // allowNewOption: true,
        // hasOptionDescription: true,
        search: true
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        var goods_id = $('#goods_id').find(":selected").val();
        goodDetails(goods_id);
        $("#goods_id").change(function () {
            var goods_id = $(this).val();
            goodDetails(goods_id);
        });

    });


    function goodDetails(goods_id) {
        if (goods_id > 0) {
            $.ajax({
                type: 'POST',
                /*url: 'ajax/fetch_brands_by_goods_id.php',
                data: 'goods_id=' + goods_id,*/
                url: 'ajax/fetch_details_by_goods_id.php',
                data: 'goods_id=' + goods_id + '&col=brand',
                success: function (html) {
                    $('#brand').html(html);
                }
            });
            /*$.ajax({
                type: 'POST',
                url: 'ajax/fetch_wh_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    //console.log(html);
                    $('#wh_kd_id').html(html);
                }
            });*/
            /*$.ajax({
                type: 'POST',
                url: 'ajax/fetch_purchase_account_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    var ppp = JSON.parse(html);
                    $('#ppp').html(ppp[0]);
                    $('#purchase_account').val(ppp[0]);
                }
            });*/
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_details_by_goods_id.php',
                data: 'goods_id=' + goods_id + '&col=size',
                success: function (html) {
                    $('#size').html(html);
                    var size_value = getSelectedSize();
                    var wh_k_id = getSelectedWhKdID();
                    topTotals(goods_id, size_value, wh_k_id);
                }
            });
            /*$.ajax({
                type: 'POST',
                url: 'ajax/fetch_country_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#country').html(html);
                }
            });*/
            var whh = getSelectedWhKdID();
            warehouseDetails(whh);
        } else {
            $('#size').html('<option value="">Select</option>');
            $('#brand').html('<option value="">Select</option>');
        }
    }

    function getSelectedSize() {
        return $('#size :selected').val();
    }

    function getSelectedGoodsID() {
        return $('#goods_id :selected').val();
    }

    function getSelectedWhKdID() {
        return $('#wh_kd_id').find(":selected").val();
    }

    function getSelectedType() {
        return $('#type :selected').val();
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        toggleQtyAndRequired();
        $("#is_qty").change(toggleQtyAndRequired);
        $('#opr').on('change', function () {
            finalAmount();
        });
    });

    function toggleQtyAndRequired() {
        finalAmount();
        var $toggleQty = $(".toggleQty");
        var $is_qty2 = $("#is_qty");
        if ($is_qty2.is(":checked")) {
            $toggleQty.show();
            $("#currency2, #rate2, #opr").attr('required', true);
        } else {
            $toggleQty.hide();
            $("#currency2, #rate2, #opr").attr('required', false);
        }
    }

    function finalAmount() {
        var qty_no = parseFloat($("#qty_no").val()) || 0;
        var qty_kgs = parseFloat($("#qty_kgs").val()) || 0;

        var total_kgs = qty_no * qty_kgs;
        $("#total_kgs").val(total_kgs);

        var empty_kgs = parseFloat($("#empty_kgs").val()) || 0;
        var total_qty_kgs = qty_no * empty_kgs;
        $("#total_qty_kgs").val(total_qty_kgs);

        var net_kgs = total_kgs - total_qty_kgs;
        $("#net_kgs").val(net_kgs);

        var weight = parseFloat($("#weight").val()) || 0;
        var total = 0;

        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = Number(total).toFixed(3);
        }

        $("#total").val(isNaN(total) ? '' : total);

        var rate1 = parseFloat($("#rate1").val()) || 0;
        var final_amount = 0;
        var amount = 0;

        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
            amount = total * rate1;
            amount = Number(amount).toFixed(2);
        }

        $("#amount").val(isNaN(amount) ? '' : amount);

        var vat = parseFloat($("#vat").val()) || 0;
        var vat_amnt = percentage(vat, amount);
        vat_amnt = Number(vat_amnt).toFixed(2);
        $("#vat_amnt").val(isNaN(vat_amnt) ? '' : vat_amnt);
        if (!isNaN(vat) && vat !== 0 && !isNaN(amount)) {
            final_amount = Number(vat_amnt) + Number(amount);
            final_amount = Number(final_amount).toFixed(2);
        } else {
            final_amount = amount;
        }

        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }

    $(document).ready(function () {
        finalAmount();
        $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#vat').on('keyup', function () {
            finalAmount();
        });
    });

    function percentage(partialValue, totalValue) {
        return (partialValue / 100) * totalValue;
    }

    $(document).on('keyup', "#imp_khaata_no", function (e) {
        fetchKhaata("#imp_khaata_no", "#imp_khaata_id", "#imp_response", "#i", "#imp_khaata_image", "recordSubmit");
    });
    fetchKhaata("#imp_khaata_no", "#imp_khaata_id", "#imp_response", "#i", "#imp_khaata_image", "recordSubmit");

    $(document).on('keyup', "#exp_khaata_no", function (e) {
        fetchKhaata("#exp_khaata_no", "#exp_khaata_id", "#exp_response", "#e", "#exp_khaata_image", "recordSubmit");
    });
    fetchKhaata("#exp_khaata_no", "#exp_khaata_id", "#exp_response", "#e", "#exp_khaata_image", "recordSubmit");

    $(document).on('keyup', "#noti_khaata_no", function (e) {
        fetchKhaata("#noti_khaata_no", "#noti_khaata_id", "#noti_response", "#n", "#noti_khaata_image", "recordSubmit");
    });
    fetchKhaata("#noti_khaata_no", "#noti_khaata_id", "#noti_response", "#n", "#noti_khaata_image", "recordSubmit");




    function fetchKhaata(inputField, khaataId, responseId, prefix, khaataImageId, recordSubmitId) {
        let khaata_no = $(inputField).val();
        console.log(khaata_no);
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    $(khaataId).val(response.messages['khaata_id']);
                    $(prefix + '_khaata_no').text(khaata_no);
                    $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                    $(prefix + '_b_name').text(response.messages['b_code']);
                    $(prefix + '_c_name').text(response.messages['name']);
                    $(prefix + '_business_name').text(response.messages['business_name']);
                    $(prefix + '_address').text(response.messages['address']);
                    $(prefix + '_comp_name').text(response.messages['comp_name']);
                    var details = {
                        indexes: response.messages['indexes'],
                        vals: response.messages['vals']
                    };
                    $(prefix + '_contacts').html(displayKhaataDetails(details));
                    $(khaataImageId).attr("src", response.messages['image']);
                    $(recordSubmitId).prop('disabled', false);
                    $(responseId).html('<i class="fa fa-check-square text-success"></i>');
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    //$(responseId).text('INVALID');
                    $(responseId).html('<i class="fa fa-window-close text-danger"></i>');
                    $(prefix + '_khaata_no').text('---');
                    $(prefix + '_khaata_name').text('---');
                    $(prefix + '_c_name').text('---');
                    $(prefix + '_b_name').text('---');
                    $(prefix + '_comp_name').text('---');
                    $(prefix + '_business_name').text('---');
                    $(prefix + '_address').text('---');
                    $(prefix + '_contacts').text('');
                    $(khaataImageId).attr("src", 'assets/images/logo-placeholder.png');
                    $(khaataId).val(0);
                }
            }
        });
    }

    function displayKhaataDetails(details) {
        var html = ''; // Initialize an empty string to store HTML

        if (details.indexes && details.vals) {
            var indexes = JSON.parse(details.indexes);
            var vals = JSON.parse(details.vals);

            if (Array.isArray(indexes) && Array.isArray(vals)) {
                var count = Math.min(indexes.length, vals.length);

                for (var i = 0; i < count; i++) {
                    var key = indexes[i];
                    var value = vals[i];
                    // Construct the HTML string
                    html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                }
            }
        }

        return html; // Return the constructed HTML string
    }
</script>
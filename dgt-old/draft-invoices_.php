<?php $pageURL = 'draft-invoices';
$page_title = 'DRAFT INVOICES';
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
        array('heading' => 'No: ', 'value' => $sr_no),
    );
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show'); $('#imp_khaata_no').focus();});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><?php echo $page_title; ?></h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <form method="post" class="">
                                <div class="row small">
                                    <div class="col-5">
                                        <!--<label class="mb-0" for="from">From</label>-->
                                        <textarea class="form-control" name="from" rows="4"
                                                  placeholder="From"></textarea>
                                        <textarea class="form-control" name="from" rows="4"
                                                  placeholder="Third Party"></textarea>
                                        <textarea class="form-control" name="from" rows="4" placeholder="To"></textarea>
                                    </div>
                                    <div class="col table-form">
                                        <?php echo '<b>No: </b> ' . $sr_no; ?>
                                        <div class="input-group w-50">
                                            <label for="_date" class="input-group-text mb-0">Date: </label>
                                            <input type="date" class="form-control" name="_date" id="_date"
                                                   value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="my-2">
                                            <input type="text" class="form-control"
                                                   value="Afghan Transit Form Bill Of Loading">
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="input-group">
                                                <label for="no" class="input-group-text mb-0">No:</label>
                                                <input class="form-control" name="no" id="no">
                                            </div>
                                            <div class="input-group">
                                                <label for="_date2" class="input-group-text mb-0">Date: </label>
                                                <input type="date" class="form-control" name="_date2" id="_date2"
                                                       value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-2 text-center">
                                            <label for="terms" class=" mb-0">Terms of payments</label>
                                            <textarea class="form-control" name="terms" id="terms"
                                                      placeholder="Terms of payments"></textarea>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="input-group">
                                                <label for="letter" class="input-group-text mb-0">Letter Of Credit
                                                    No:</label>
                                                <input class="form-control" name="letter" id="letter">
                                            </div>
                                            <div class="input-group">
                                                <input class="form-control" name="collection"
                                                       value="Collection Basis Da Afghaistan Bank">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="">
                                                <label for="letter" class="mb-0">Through:</label>
                                                <textarea class="form-control" name="letter" id="letter"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table table-sm table-bordered table-form" id="invoiceTable">
                                            <thead>
                                            <tr>
                                                <th>Quantity</th>
                                                <th>Description of Goods</th>
                                                <th>Unit Price USD</th>
                                                <th>Total Price USD</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td style="width: 20%">
                                                    <div class="d-flex">
                                                        <input name="qty1" class="form-control">
                                                        <input name="qty2" class="form-control">
                                                        <input name="qty3" class="form-control">
                                                        <input name="qty4" class="form-control">
                                                    </div>
                                                </td>
                                                <td><input name="goods" class="form-control"></td>
                                                <td style="width: 10%"><input name="unit_price" class="form-control">
                                                </td>
                                                <td style="width: 10%"><input name="total_price" class="form-control">
                                                </td>
                                                <td style="width: 4%">
                                                    <input type="hidden" id="temp_id_hidden" name="temp_id_hidden" value="0">
                                                    <button type="button" id="saveItemBtn"
                                                            class="btn btn-sm btn-secondary">Save
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="table table-sm table-bordered table-hover table-form"
                                               id="invoiceTable2">
                                            <thead class="table-secondary">
                                            <tr>
                                                <th style="width: 2%">No</th>
                                                <th style="width: 20%">Quantity</th>
                                                <th>Description of Goods</th>
                                                <th style="width: 10%">Unit Price USD</th>
                                                <th style="width: 10%">Total Price USD</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $x = 0;
                                            $ddd = fetch('invoice_temp_data');
                                            while ($temp = mysqli_fetch_assoc($ddd)) {
                                                ++$x;
                                                $temp_json = json_decode($temp['json_data']);
                                                echo '<tr class="pointer" data-id="' . $temp['id'] . '">';
                                                echo '<td>' . $x . '</td>';
                                                echo '<td>';
                                                echo $temp_json->qty1;
                                                echo $temp_json->qty2;
                                                echo $temp_json->qty3;
                                                echo $temp_json->qty4;
                                                echo '</td>';
                                                echo '<td>' . $temp_json->goods . '</td>';
                                                echo '<td>' . $temp_json->unit_price . '</td>';
                                                echo '<td>' . $temp_json->total_price . '</td>';
                                                echo '</tr>';

                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="mt-1 row gx-1">
                                    <div class="col-auto">
                                        <button name="recordSubmit" id="recordSubmit-" disabled type="submit"
                                                class="btn btn-primary btn-sm">Submit Disabled
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="action" value="<?php echo $action_hidden; ?>">
                                <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                                <input type="hidden" name="sd_id_hidden" value="<?php echo $sd_id; ?>">
                            </form>
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
<script>
    $(document).ready(function () {
        let itemCount = 0;

        $('#saveItemBtn').on('click', function () {
            let qty1 = $('input[name="qty1"]').val();
            let qty2 = $('input[name="qty2"]').val();
            let qty3 = $('input[name="qty3"]').val();
            let qty4 = $('input[name="qty4"]').val();
            let quantity = [qty1, qty2, qty3, qty4].join(' ');
            let goods = $('input[name="goods"]').val();
            let unitPrice = $('input[name="unit_price"]').val();
            let totalPrice = $('input[name="total_price"]').val();
            let temp_id_hidden = $('input[name="temp_id_hidden"]').val();

            let data = {
                temp_id_hidden: temp_id_hidden,
                qty1: qty1,
                qty2: qty2,
                qty3: qty3,
                qty4: qty4,
                goods: goods,
                unit_price: unitPrice,
                total_price: totalPrice
            };

            $.ajax({
                type: 'POST',
                url: 'ajax/save_invoice_temp_data.php',
                data: data,
                success: function (response) {
                    let result = JSON.parse(response);
                    itemCount++;

                    let newRow = `
                    <tr data-id="${result.id}">
                        <td>${itemCount}</td>
                        <td>${quantity}</td>
                        <td>${goods}</td>
                        <td>${unitPrice}</td>
                        <td>${totalPrice}</td>
                    </tr>
                `;

                    let tempIdHiddenValue = parseInt($('#temp_id_hidden').val());

                    if (tempIdHiddenValue > 0) {
                        // Update existing row
                        let existingRow = $('#invoiceTable2 tbody').find(`tr[data-id="${tempIdHiddenValue}"]`);
                        if (existingRow.length > 0) {
                            existingRow.find('td:eq(1)').text(itemCount);
                            existingRow.find('td:eq(2)').text(quantity);
                            existingRow.find('td:eq(3)').text(goods);
                            existingRow.find('td:eq(4)').text(unitPrice);
                            existingRow.find('td:eq(5)').text(totalPrice);
                        }
                    } else {
                        // Append new row
                        $('#invoiceTable2 tbody').append(newRow);
                    }
                    // Clear the input fields after saving
                    $('input[name="qty1"]').val('');
                    $('input[name="qty2"]').val('');
                    $('input[name="qty3"]').val('');
                    $('input[name="qty4"]').val('');
                    $('input[name="goods"]').val('');
                    $('input[name="unit_price"]').val('');
                    $('input[name="total_price"]').val('');
                    $('input[name="temp_id_hidden"]').val(0);
                }
            });
        });

        // Add click event listener to invoiceTable2 rows
        $('#invoiceTable2').on('click', 'tr', function () {
            let id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: 'ajax/get_invoice_temp_data.php',
                data: {id: id},
                success: function (response) {
                    let data = JSON.parse(response).json_data;
                    let parsedData = JSON.parse(data);

                    $('input[name="qty1"]').val(parsedData.qty1);
                    $('input[name="qty2"]').val(parsedData.qty2);
                    $('input[name="qty3"]').val(parsedData.qty3);
                    $('input[name="qty4"]').val(parsedData.qty4);
                    $('input[name="goods"]').val(parsedData.goods);
                    $('input[name="unit_price"]').val(parsedData.unit_price);
                    $('input[name="total_price"]').val(parsedData.total_price);
                    $('input[name="temp_id_hidden"]').val(id);
                }
            });
        });
    });
</script>
<!--<script>
    $(document).ready(function() {
        let itemCount = 0;

        $('#saveItemBtn').on('click', function() {
            itemCount++;

            let qty1 = $('input[name="qty1"]').val();
            let qty2 = $('input[name="qty2"]').val();
            let qty3 = $('input[name="qty3"]').val();
            let qty4 = $('input[name="qty4"]').val();
            let quantity = [qty1, qty2, qty3, qty4].join(' ');
            let goods = $('input[name="goods"]').val();
            let unitPrice = $('input[name="unit_price"]').val();
            let totalPrice = $('input[name="total_price"]').val();

            let newRow = `
            <tr>
                <td>${itemCount}</td>
                <td>${quantity}</td>
                <td>${goods}</td>
                <td>${unitPrice}</td>
                <td>${totalPrice}</td>
            </tr>
        `;

            $('#invoiceTable2 tbody').append(newRow);

            // Clear the input fields after saving
            $('input[name="qty1"]').val('');
            $('input[name="qty2"]').val('');
            $('input[name="qty3"]').val('');
            $('input[name="qty4"]').val('');
            $('input[name="goods"]').val('');
            $('input[name="unit_price"]').val('');
            $('input[name="total_price"]').val('');
        });
        // Add click event listener to invoiceTable2 rows
        $('#invoiceTable2').on('click', 'tr', function() {
            let row = $(this).find('td');

            let quantities = row.eq(1).text().split(' ');
            $('input[name="qty1"]').val(quantities[0]);
            $('input[name="qty2"]').val(quantities[1]);
            $('input[name="qty3"]').val(quantities[2]);
            $('input[name="qty4"]').val(quantities[3]);
            $('input[name="goods"]').val(row.eq(2).text());
            $('input[name="unit_price"]').val(row.eq(3).text());
            $('input[name="total_price"]').val(row.eq(4).text());
        });
    });
</script>-->
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

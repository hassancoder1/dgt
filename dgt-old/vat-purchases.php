<?php $pageURL = 'vat-purchases';
$sql = "SELECT * FROM `purchases` WHERE type = 'market' ORDER BY khaata_tr1 ";
$page_title = 'VAT PURCHASES <sup>MARKET</sup>';
include("header.php");
global $branchId;
$remove = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['goods_name'])) {
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
    }
}
$sales = mysqli_query($connect, $sql); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex gap-0 table-form text-nowrap align-items-center justify-content-between">
                <div>
                    <div class="d-flex justify-content-between gap-md-3">
                        <div><b>Amnt</b><span id="amnt_total_span"></span></div>
                        <div><b>VAT</b><span id="vat_total_span"></span></div>
                        <div><b>FINAL</b><span id="final_total_span"></span></div>
                    </div>
                    <div class="d-flex justify-content-between gap-md-3">
                        <div><b>ROWS</b><span id="rows_count_span"></span></div>
                        <div><b>QTY</b><span id="p_qty_total_span"></span></div>
                        <div><b>KGs</b><span id="p_kgs_total_span"></span></div>
                    </div>
                </div>
                <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                    <input type="hidden" name="start" value="<?php echo $start; ?>">
                    <input type="hidden" name="end" value="<?php echo $end; ?>">
                    <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                    <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                    <button class="btn btn-sm btn-success"><i class="fa fa-print"></i></button>
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
                            <option value="" hidden>Seller A/c</option>
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
                                <th>SELLER</th>
                                <th>GOODS DETAILS</th>
                                <th>AMOUNT</th>
                                <th>VAT</th>
                                <th>DETAILS</th>
                                <th>REPORT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                            $amnt_total = $final_total = $vat_total = 0;
                            while ($sale = mysqli_fetch_assoc($sales)) {
                                if(!empty($sale['vat_json'])) continue;
                                $sale_id = $sale['id'];
                                $sale_type = $sale['type'];
                                $is_acc = $sale['is_acc'];
                                $rowColor = '';
                                $seller_json = json_decode($sale['seller_json']);
                                $khaata_tr1 = json_decode($sale['khaata_tr1']);
                                if (empty($khaata_tr1)) continue;
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
                                    if ($goods_name != '') {
                                        if ($goods_name != $totals['Goods'][0]) continue;
                                    }
                                    if ($s_khaata_id != '') {
                                        if (strtolower($s_khaata_id) != strtolower($seller_json->khaata_no)) continue;
                                    }
                                }

                                $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                                $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                                $amnt_total += !empty($totals['Amount']) ? $totals['Amount'] : 0;
                                $final_total += !empty($totals['Final']) ? $totals['Final'] : 0;
                                $sd_data = fetch('purchase_details', array('parent_id' => $sale_id));
                                $vat_items = $vat_items_amount = 0;
                                while ($sd_datum = mysqli_fetch_assoc($sd_data)) {
                                    if ($sd_datum['rate2'] > 0) {
                                        $vat_items_amount += $sd_datum['rate2'];
                                        $vat_items++;
                                        $vat_total += $vat_items_amount;
                                    }
                                }
                                if ($vat_items_amount <= 0) continue; ?>
                                <tr data-href="<?php echo $pageURL . '?view=1&id=' . $sale_id; ?>"
                                    class="font-size-11 text-nowrap pointer clickable-row <?php echo $rowColor; ?>">
                                    <td class="font-size-12">
                                        <?php echo '<b>P#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                        echo '<br><span class="font-size-11-">';
                                        echo '<b>D.</b>' . my_date($sale['p_date']);
                                        //echo $sale['city'] != '' ? '<br><b>BILL NAME</b>' . $sale['city'] : '';
                                        echo '</span>'; ?>
                                    </td>
                                    <td>
                                        <?php echo '<b>A/C.</b>' . $seller_json->khaata_no . '<br>';
                                        if ($is_acc == 1) {
                                            $seller_khaata = khaataSingle($seller_json->khaata_id);
                                            if (!empty($seller_khaata)) {
                                                echo '<b>NAME</b>' . $seller_khaata['khaata_name'];
                                                echo '<br><b>COMP.</b>' . $seller_khaata['comp_name'];
                                                $details_k = ['indexes' => $seller_khaata['indexes'], 'vals' => $seller_khaata['vals']];
                                                $reps = displayKhaataDetails($details_k, true);
                                                if (array_key_exists('VAT', $reps)) {
                                                    echo '<br><b>VAT# </b>' . $reps['VAT'];
                                                }
                                                if (array_key_exists('License', $reps)) {
                                                    echo '<br><b>License# </b>' . $reps['License'];
                                                }
                                            }
                                        } else {
                                            echo '<b>SALE NAME</b>' . $seller_json->s_name . '<br>';
                                            echo '<b>COMP.</b>' . $seller_json->s_company . '<br>';
                                            echo '<b>VAT#</b>' . $seller_json->s_weight_no . '<br>';
                                        } ?>
                                    </td>
                                    <td><?php echo $Goods . $ITEMS . $Qty . $KGs; ?></td>
                                    <td class="text-dark">
                                        <?php if ($cntrs > 0) {
                                            echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                            echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr1'] . '</sub>';
                                            //echo '<br><b>Transfer </b>' . $sale['t_date'];
                                        } ?>
                                    </td>
                                    <td><?php echo $vat_items_amount; ?></td>
                                    <td>
                                        <?php echo '<b>B.</b> ' . branchName($sale['branch_id']) . '<br>';
                                        echo '<b>OWNER</b> ' . strtoupper($sale['p_khaata_no']); ?>
                                    </td>
                                    <td class="font-size-10 text-wrap">
                                        <div style="width: 130px"><?php echo readMoreTooltip($sale['report'], 80) ?></div>
                                    </td>
                                </tr>
                                <?php $row_count++;
                            } ?>
                            </tbody>
                            <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                            <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                            <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                            <input type="hidden" id="amnt_total" value="<?php echo $amnt_total; ?>">
                            <input type="hidden" id="final_total" value="<?php echo $final_total; ?>">
                            <input type="hidden" id="vat_total" value="<?php echo $vat_total; ?>">
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
        $("#amnt_total_span").text($("#amnt_total").val());
        $("#final_total_span").text($("#final_total").val());
        $("#vat_total_span").text($("#vat_total").val());
    </script>
<?php if (isset($_POST['s_id_hidden_attach'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $ppp_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden_attach']);
    $url_ = $pageURL . "?p_id=" . $ppp_id . "&attach=1";
    //$dato = array('is_doc' => 1);
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($ppp_id, 'vat_purchase', basename($_FILES["attachments"]["name"][$key]));
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
            if ($moved) {
                $type = 'success';
                $msg = 'Attachment Saved ';
                $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            }
        }
    }
    message($type, $url_, $msg);
}
if (isset($_POST['recordSubmit'])) {
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
        $done = insert('purchases', $data);
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
        $done = update('purchases', $data, array('id' => $s_id_hidden));
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
}
if (isset($_POST['ttrFirstSubmit'])) {
    unset($_POST['ttrFirstSubmit']);
    $post_json = json_encode($_POST);

    /*final amount will be Cr. to Cr. (Purchaser) account;
    Dr. would be into two parts:
    1. $vat_khaata_no would be Dr. with VAT amount and
    2. FINAL AMOUNT - VAT AMOUNT Dr. with Dr. (sale) account */
    $vat_khaata_no = VAT_Account('khaata_no');
    $vat_khaata_id = VAT_Account('khaata_id');

    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    if ($bnaam_khaata_no == '') {
        echo '<script>alert("Cr. Account is missing :(");</script>';
        return;
    }
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);

    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']); //this is final amount
    $vat_amount = mysqli_real_escape_string($connect, $_POST['vat_amount']);
    $cr_amount = $amount - $vat_amount;
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $s_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $type_post = 'market';
    $url = $pageURL . '?view=1&id=' . $s_id;
    $type = ' SM';
    $transfered_from = 'purchase_market';
    $r_type = 'Business';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQ = fetch('purchases', array('id' => $s_id));
        $s_data = mysqli_fetch_assoc($pQ);
        $branch_serial = getBranchSerial($s_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $s_id,
            'branch_id' => $s_data['branch_id'],
            'user_id' => $userId,
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $s_id,
            'r_name' => $type,
            'r_no' => $s_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Purchase # " . $s_id;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) { // partial Cr. to Purcahse account, i.e. DP1
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr.A/c:' . $jmaa_khaata_no . ' & ' . $vat_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $cr_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                if ($i == 2) { // partial Cr. to VAT account, i.e. DU1987
                    $k_datum = khaataSingle($vat_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr.A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $vat_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $vat_khaata_no;
                    $dataArrayUpdate['amount'] = $vat_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $vat_khaata_no . "</span>";
                }
                if ($i == 3) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr.A/c:' . $bnaam_khaata_no . ' & ' . $vat_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 3; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $cr_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Dr.A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($vat_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $vat_khaata_id;
                    $dataArray['khaata_no'] = $vat_khaata_no;
                    $dataArray['amount'] = $vat_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Dr.A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $vat_khaata_no . "</span>";
                }
                if ($i == 3) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Cr.A/c:' . $bnaam_khaata_no . ' & ' . $vat_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $preData = array('khaata_tr1' => $post_json);
            $tlUpdated = update('purchases', $preData, array('id' => $s_id));
            $msg = 'Transferred to Business Roznamcha ' . $str;
            $msgType = 'success';
        } else {
            $msg = 'Transfer Error ';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    message($msgType, $url, $msg);
} ?>
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
    <script>
        $(document).ready(function () {
            toggleAccountDivs();
            $('input[name="is_acc"]').change(function () {
                toggleAccountDivs();
            });

            function toggleAccountDivs() {
                var isAccountSelected = $('input[name="is_acc"]:checked').val();

                if (isAccountSelected == 1) {
                    $('#yes_account').show();
                    $('#no_account').hide();
                    $("#khaata_no").attr('required', true);
                    $("#s_name").attr('required', false);
                } else {
                    $('#yes_account').hide();
                    $('#no_account').show();
                    //$('#khaata_no').val('');
                    fetchKhaata("#khaata_no", "#khaata_id", "#p_response", "#", "#p_khaata_image", "recordSubmit");
                    $("#khaata_no").attr('required', false);
                    $("#s_name").attr('required', true);
                }
            }
        });
    </script>
    <script type="text/javascript">
        var party_khaata_id = $('#party_khaata_id').find(":selected").val();
        warehouseKhaata(party_khaata_id);
        $("#party_khaata_id").change(function () {
            warehouseKhaata($(this).val());
        });

        $(document).ready(function () {
            var wh_kd_id = $('#wh_kd_id').find(":selected").val();
            warehouseDetails(wh_kd_id);

            $("#wh_kd_id").change(function () {
                var wh_kd_id = $(this).val();
                warehouseDetails(wh_kd_id);
            });


            var goods_id = $('#goods_id').find(":selected").val();
            goodDetails(goods_id);
            $("#goods_id").change(function () {
                var goods_id = $(this).val();
                goodDetails(goods_id);
            });

            $("#size").change(function () {
                var g = getSelectedGoodsID();
                var s = getSelectedSize();
                //var w = getSelectedWhKdID();
                topTotals(g, s);
            });
        });

        function warehouseKhaata(party_khaata_id) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetchKhaataDetailsDropdown.php',
                data: {khaata_id: party_khaata_id},
                success: function (html) {
                    console.log(party_khaata_id);
                    $('#wh_kd_id').html(html);
                    var ddd = getSelectedWhKdID();
                    warehouseDetails(ddd);
                }
            });
        }

        function warehouseDetails(wh_kd_id) {
            var gooods_id = getSelectedGoodsID();
            var siize = getSelectedSize();
            if (gooods_id) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetch_wh_by_goods_id.php',
                    //dataType:JSON,
                    data: 'goods_id=' + gooods_id + '&size=' + siize + '&wh_kd_id=' + wh_kd_id,
                    success: function (response) {
                        console.log(response)
                        if (response.trim() !== '' && response.trim() !== '[]') {
                            var responseData = JSON.parse(response);
                            $('#warehouse_name').text(responseData.warehouse_name);
                            $('#wh_stock_qty_no').text(responseData.wh_stock_qty_no);
                            $('#wh_stock_total_kgs').text(responseData.wh_stock_total_kgs);
                            $('#wh_sale_qty_no').text(responseData.wh_sale_qty_no);
                            $('#wh_sale_total_kgs').text(responseData.wh_sale_total_kgs);
                            $('#wh_bal_qty_no').text(responseData.wh_bal_qty_no);
                            $('#wh_bal_total_kgs').text(responseData.wh_bal_total_kgs);
                        } else {
                            $('#wh_stock_qty_no').text();
                            $('#wh_stock_total_kgs').text();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }

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

        function topTotals(goods_id, size) {
            //console.log('Goods Id=' + goods_id +'Size=' + size + 'WH Khaata Id=' + warehouse_khaata_id)
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_totals_sale_entry.php',
                //dataType:JSON,
                data: 'goods_id=' + goods_id + '&size=' + size + '&type=purchase',
                success: function (response) {
                    console.log(response)
                    if (response.trim() !== '' && response.trim() !== '[]') {
                        var responseData = JSON.parse(response);
                        var TOTAL_PURCHASE_QTY = responseData.qty_no;
                        var TOTAL_PURCHASE_KGS = responseData.total_kgs;
                        $('#qty_no_span').text(TOTAL_PURCHASE_QTY);
                        $('#total_kgs_span').text(TOTAL_PURCHASE_KGS);
                        $.ajax({
                            type: 'POST',
                            url: 'ajax/fetch_totals_sale_entry.php',
                            data: 'goods_id=' + goods_id + '&size=' + size + '&type=sale',
                            //data: 'goods_id=' + goods_id + '&size=' + size + '&warehouse_khaata_id=' + warehouse_khaata_id + '&type=sale',
                            success: function (response) {
                                if (response.trim() !== '' && response.trim() !== '[]') {
                                    var responseData = JSON.parse(response);
                                    var TOTAL_SALE_QTY = responseData.qty_no;
                                    var TOTAL_SALE_KGS = responseData.total_kgs;
                                    $("#qty_no_sale_span").text(TOTAL_SALE_QTY);
                                    $("#total_kgs_sale_span").text(TOTAL_SALE_KGS);

                                    /*BALANCE*/
                                    var qty_no_sale = $("#qty_no_sale").val();
                                    var total_kgs_sale = $("#total_kgs_sale").val();
                                    var qty_no_bal = TOTAL_PURCHASE_QTY - TOTAL_SALE_QTY;
                                    $("#qty_no_bal_span").text(qty_no_bal);
                                    var total_kgs_bal = TOTAL_PURCHASE_KGS - TOTAL_SALE_KGS;
                                    $("#total_kgs_bal_span").text(total_kgs_bal);

                                } else {
                                    $('#qty_no_span').text('');
                                    $('#total_kgs_span').text('');
                                }
                            }
                        });
                        var qty_no_sale = $("#qty_no_sale").val();
                        var qty_no_bal = responseData.qty_no - qty_no_sale;
                        $("#qty_no_bal_span").text(qty_no_bal);
                        var total_kgs_sale = $("#total_kgs_sale").val();
                        var total_kgs_bal = responseData.total_kgs - total_kgs_sale;
                        $("#total_kgs_bal_span").text(total_kgs_bal);

                    } else {
                        $('#qty_no_span').text('');
                        $('#total_kgs_span').text('');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
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

        $(document).on('keyup', "#khaata_no", function (e) {
            fetchKhaata("#khaata_no", "#khaata_id", "#p_response", "#", "#p_khaata_image", "recordSubmit");
        });
        fetchKhaata("#khaata_no", "#khaata_id", "#p_response", "#", "#p_khaata_image", "recordSubmit");

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
                        $(responseId).text('INVALID');
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
<?php if (isset($_GET['view']) && $_GET['view'] == 1 && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sr_no = getAutoIncrement('purchases');

    $sale_id = $sr_no = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('purchases', array('id' => $sale_id));
    $record = mysqli_fetch_assoc($records);
    $branch__id = $record['branch_id'];
    $type = $record['type'];
    $p_date = $record['p_date'];
    $s_name = $record['s_name'];

    $p_acc = $record['p_khaata_no'];
    $receiver = $record['receiver'];
    $city = $record['city'];
    $report = $record['report'];
    $topArray = array(
        array('heading' => 'PURCHASE DATE ', 'value' => my_date($p_date)),
        array('heading' => 'PURCHASE BILL# ', 'value' => $sr_no),
        array('heading' => 'BRANCH ', 'value' => branchName($branch__id)),
        array('heading' => 'BILL NAME ', 'value' => $record['city']),
        array('heading' => 'PAYMENT DATE ', 'value' => my_date($receiver)),
    );
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">VAT MARKET PURCHASE</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <div class="d-flex justify-content-between flex-wrap gap-1 text-uppercase small">
                                <div>
                                    <?php foreach ($topArray as $item) {
                                        echo '<b>' . $item['heading'] . '</b><span class="text-muted">' . $item['value'] . '</span><br>';
                                    } ?>
                                </div>
                                <div class="h6 font-size-12 text-uppercase">
                                    <?php $seller_json = json_decode($record['seller_json']);
                                    echo '<b>SELLER A/C.</b>' . $seller_json->khaata_no . '<br>';
                                    if ($is_acc == 1) {
                                        $seller_khaata = khaataSingle($seller_json->khaata_id);
                                        if (!empty($seller_khaata)) {
                                            echo '<b>A/C NAME</b> <span class="text-muted">' . $seller_khaata['khaata_name'] . '</span>';
                                            echo '<br><b>BRANCH </b><span class="text-muted">' . branchName($seller_khaata['branch_id']) . '</span>';
                                            echo '<br><b>CATEGORY </b><span class="text-muted">' . catName($seller_khaata['cat_id']) . '</span>';
                                            echo '<br><b>BUSINESS </b> <span class="text-muted">' . $seller_khaata['business_name'] . '</span>';
                                            echo '<br><b>COMPANY </b> <span class="text-muted">' . $seller_khaata['comp_name'] . '</span>';
                                            $details_k = ['indexes' => $seller_khaata['indexes'], 'vals' => $seller_khaata['vals']];
                                            $reps = displayKhaataDetails($details_k, true);
                                            if (array_key_exists('VAT', $reps)) {
                                                echo '<br><b>VAT# </b>' . $reps['VAT'];
                                            }
                                            if (array_key_exists('License', $reps)) {
                                                echo '<br><b>License# </b>' . $reps['License'];
                                            }
                                        }
                                    } else {
                                        echo '<b>SALE NAME</b>' . $seller_json->s_name . '<br>';
                                        echo '<b>COMP.</b>' . $seller_json->s_company . '<br>';
                                        echo '<b>VAT#</b>' . $seller_json->s_weight_no . '<br>';
                                        echo '<b>PH.</b>' . $seller_json->s_phone . '<br>';
                                        echo '<b>E.</b>' . $seller_json->s_email . '<br>';
                                        echo '<b>Addr.</b>' . $seller_json->s_address . '<br>';
                                    } ?>
                                </div>
                                <div class="h6 font-size-12 text-uppercase">
                                    <b>PURCHASER/OWNER AC. </b>
                                    <span class="text-muted" id="ppp__">DP1<?php //echo $p_acc; ?></span>
                                    <?php $purchaser_khaata = khaataSingle(4);
                                    if (!empty($purchaser_khaata)) {
                                        echo '<br><b>A/C NAME</b> <span class="text-muted">' . $purchaser_khaata['khaata_name'] . '</span>';
                                        echo '<br><b>BRANCH</b> <span class="text-muted">' . branchName($purchaser_khaata['branch_id']) . '</span>';
                                        echo '<br><b>CATEGORY</b> <span class="text-muted">' . catName($purchaser_khaata['cat_id']) . '</span>';
                                        echo '<br><b>BUSINESS</b> <span class="text-muted">' . $purchaser_khaata['business_name'] . '</span>';
                                        echo '<br><b>COMPANY</b> <span class="text-muted">' . $purchaser_khaata['comp_name'] . '</span>';
                                    } ?>
                                </div>
                            </div>
                            <div><b>REPORT</b> <?php echo $report; ?></div>
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead>
                                            <tr class="text-nowrap">
                                                <th>#</th>
                                                <th>GOODS</th>
                                                <th>QTY</th>
                                                <th>KGs</th>
                                                <th>EMPTY</th>
                                                <th>NET KGs</th>
                                                <th>Wt.</th>
                                                <th>TOTAL</th>
                                                <th>PRICE</th>
                                                <th>AMOUNT</th>
                                                <th>VAT AMNT</th>
                                                <!--<th class="text-end">FINAL</th>-->
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
                                                echo '<td>' . goodsName($details['goods_id']) . ' ' . $details['size'] . ' ' . $details['brand'] . '</td>';
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
                                                //echo '<td class="text-end">' . round($details['final_amount'], 2);
                                                //echo '<sub>' . $details['currency2'] . '</sub>';
                                                echo '<td>'; ?>
                                                <form id="purchaseAttachSubmit" method="post"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden" name="s_id_hidden_attach"
                                                           value="<?php echo $sale_id; ?>">
                                                    <input type="file" id="attachments" name="attachments[]"
                                                           class="d-none" multiple>
                                                    <input type="button"
                                                           class="form-control rounded-1 bg-dark p-0 text-white"
                                                           value="+ Documents"
                                                           onclick="document.getElementById('attachments').click();"/>
                                                </form>
                                                <script>
                                                    document.getElementById("attachments").onchange = function () {
                                                        document.getElementById("purchaseAttachSubmit").submit();
                                                    }
                                                </script>
                                                <?php $attachments = fetch('attachments', array('source_id' => $sale_id, 'source_name' => 'vat_purchase'));
                                                if (mysqli_num_rows($attachments) > 0) {
                                                    $no = 1;
                                                    while ($attachment = mysqli_fetch_assoc($attachments)) {
                                                        $link = 'attachments/' . $attachment['attachment'];
                                                        echo $no . '.<a class="text-decoration-underline" href="' . $link . '" target="_blank">' . readMore($attachment['attachment'], 27) . '</a><br>';
                                                        $no++;
                                                    }
                                                } ?>
                                                <?php echo '</td>';
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
                                                echo '<th colspan="2"></th>';
                                                echo '<th class="fw-bold">' . $qty_no . '</th>';
                                                echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                                                echo '<th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>';
                                                echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                                                echo '<th colspan="1"></th>';
                                                echo '<th class="fw-bold">' . round($total, 2) . '</th>';
                                                echo '<th colspan="1"></th>';
                                                echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                                                echo '<th class="fw-bold">' . round($vat, 2) . '</th>';
                                                //echo '<th class="fw-bold text-end">' . round($final_amount_total, 2) . '</th>';
                                                echo '</tr>';
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php if ($count_rows > 0) {
                                //$ddd = 'BILL NAME:' . $record['city'] . ' BILL#:' . $sale_id . ' ENTRY:' . $count_rows . ' T.Qty:' . $qty_no . ' T.KGs:' . $total . ' AMOUNT:' . $amount . ' VAT.AMOUNT:' . $vat . ' Payment date:' . $receiver;
                                $vat_json_e = array('vat_date' => date('Y-m-d'), 'vat_serial' => '', 'vat_details' => '', 'vat_tax' => '');
                                if (!empty($record['vat_json'])) {
                                    $vvv = json_decode($record['vat_json']);
                                    $vat_json_e = array(
                                        'vat_date' => $vvv->vat_date, 'vat_serial' => $vvv->vat_serial, 'vat_details' => $vvv->vat_details, 'vat_tax' => $vvv->vat_tax
                                    );
                                } ?>
                                <div class="card">
                                    <div class="card-body ">
                                        <form method="post">
                                            <div class="row gx-1 gy-3 table-form">
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <label for="vat_date">Date</label>
                                                        <input value="<?php echo $vat_json_e['vat_date']; ?>"
                                                               type="date"
                                                               id="vat_date" name="vat_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <label for="vat_serial">VAT Serial</label>
                                                        <input value="<?php echo $vat_json_e['vat_serial']; ?>" type="text"
                                                               id="vat_serial"
                                                               name="vat_serial" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <label for="vat_details">VAT Details</label>
                                                        <input value="<?php echo $vat_json_e['vat_details']; ?>"
                                                               type="text"
                                                               id="vat_details" name="vat_details" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <label for="vat_tax">VAT Tax.</label>
                                                        <input value="<?php echo $vat_json_e['vat_tax']; ?>" type="text"
                                                               id="vat_tax" name="vat_tax" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button name="vatJsonSubmit" type="submit"
                                                            class="btn btn-primary btn-sm">
                                                        <i class="fa fa-check-double"></i> Save
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="_id_hidden" value="<?php echo $sale_id; ?>">
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-2 order-1 fixed-sidebar table-form">
                            <div class="bottom-buttons">
                                <div class="px-2">
                                    <a href="print/purchase-market-single?s_id=<?php echo $sale_id; ?>&action=booking"
                                       target="_blank"
                                       class="btn btn-success btn-sm w-100 mt-3">PRINT</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
if (isset($_POST['vatJsonSubmit'])) {
    $_id_hidden = mysqli_real_escape_string($connect, $_POST['_id_hidden']);
    unset($_POST['vatJsonSubmit']);
    $json_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $data = array('vat_json' => $json_data);
    $url = $pageURL . '?view=1&id=' . $_id_hidden;
    $info = array('type' => 'danger', 'msg' => 'There is some System Error :(');
    $done = update('purchases', $data, array('id' => $_id_hidden));
    if ($done) {
        $info['type'] = 'success';
        $info['msg'] = 'VAT info saved..';
    }
    message($info['type'], $url, $info['msg']);
} ?>
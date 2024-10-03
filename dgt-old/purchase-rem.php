<?php $page_title = 'Remaining Form';
$pageURL = 'purchase-rem';
include("header.php");
global $connect;
$remove = $ps_type = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = $adv_full = '';
$is_search = false;
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer >=1  AND transfer2 =1 ";
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
    if (isset($_GET['ps_type'])) {
        $ps_type = mysqli_real_escape_string($connect, $_GET['ps_type']);
        $pageURL .= '&ps_type=' . $ps_type;
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
$sql .= " ORDER BY id"; ?>
<div class="row">
    <div class="col-lg-12">
        <form method="get" class="d-flex align-items-center table-form text-nowrap">
            <?php echo $remove; ?>
            <input type="date" name="start" value="<?php echo $start; ?>" class="form-control w-75">
            <input type="date" name="end" value="<?php echo $end; ?>" class="form-control w-75">
            <?php $type_array = array('booking', 'local'); ?>
            <select id="type" name="ps_type" class="form-select">
                <option value="">ALL</option>
                <?php foreach ($type_array as $item) {
                    $sel_ps_type = $ps_type == $item ? 'selected' : '';
                    echo '<option ' . $sel_ps_type . ' value="' . $item . '">' . ucfirst($item) . '</option>';
                } ?>
            </select>
            <div class="input-group">
                <select name="s_khaata_id" class="form-select">
                    <option value="" hidden>Seller A/c</option>
                    <?php $accounts_query = fetch('khaata');
                    while ($aa = mysqli_fetch_assoc($accounts_query)) {
                        $sel = $s_khaata_id == $aa['id'] ? 'selected' : '';
                        echo '<option ' . $sel . ' value="' . $aa['id'] . '">' . $aa['khaata_no'] . '</option>';
                    } ?>
                </select>
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
                <select class="form-select" name="is_transferred">
                    <option value="">All</option>
                    <?php $imp_exp_array = array(2 => 'transferred', 0 => 'not transferred');
                    foreach ($imp_exp_array as $item => $value) {
                        $sel_tran = $is_transferred == $item ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                    } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-search"></i></button>
        </form>
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
            <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
            <div class="d-flex text-nowrap">
                <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                    <input type="hidden" name="start" value="<?php echo $start; ?>">
                    <input type="hidden" name="end" value="<?php echo $end; ?>">
                    <input type="hidden" name="ps_type" value="<?php echo $ps_type; ?>">
                    <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                    <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                    <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                    <button class="btn btn-sm btn-success">PRINT</button>
                </form>
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 73dvh;">
                    <table class="table mb-0 table-bordered fix-head-table table-sm">
                        <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>BRANCH</th>
                            <th>SELLER</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>ADVANCE</th>
                            <th>REMAINING</th>
                            <th>TRANSFER</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $purchases = mysqli_query($connect, $sql);
                        $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($purchase = mysqli_fetch_assoc($purchases)) {
                            $purchase_id = $purchase['id'];
                            $purchase_type = $purchase['type'];
                            $transfer = $purchase['transfer'];
                            $p_khaata = khaataSingle($purchase['p_khaata_id']);
                            $s_khaata = khaataSingle($purchase['s_khaata_id']);

                            $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                            $totals = purchaseSpecificData($purchase_id, 'product_details');
                            $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                            $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                            $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . '<br>' : '';
                            $Origin = $cntrs > 0 ? '<b>ORIGIN. </b>' . $totals['Origin'][0] . '<br>' : '';
                            $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                            $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . ' ' : '';
                            $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] : '';
                            if ($is_search) {
                                if ($start != '') {
                                    if ($purchase['p_date'] < $start) continue;
                                }
                                if ($end != '') {
                                    if ($purchase['p_date'] > $end) continue;
                                }
                                $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                                if ($goods_name != '') {
                                    if ($goods_name != $GoodsKaNaam) continue;
                                }
                                if ($size != '') {
                                    if ($size != $totals['Size'][0]) continue;
                                }
                                if ($brand != '') {
                                    if ($brand != $totals['Brand'][0]) continue;
                                }
                                if ($origin != '') {
                                    if ($origin != $totals['Origin'][0]) continue;
                                }
                                if ($ps_type != '') {
                                    if ($purchase_type != $ps_type) continue;
                                }
                                if ($is_transferred != '') {
                                    if ($is_transferred == 2) {
                                        if ($transfer != 2) continue;
                                    } else {
                                        if ($transfer == 2) continue;
                                    }
                                }
                                if ($s_khaata_id != '') {
                                    if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                                }

                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            //$rowColor = empty($khaata_tr1) ? 'bg-danger bg-opacity-10' : '';
                            $totalPurchaseAmount = totalPurchaseAmount($purchase_id);
                            $adv_paid_total = purchaseSpecificData($purchase_id, 'adv_paid_total');
                            $rem_paid_total = purchaseSpecificData($purchase_id, 'rem_paid_total'); ?>
                            <tr class="pointer text-uppercase <?php //echo $rowColor; ?>"
                                onclick="viewPurchase(<?php echo $purchase_id; ?>)" data-bs-toggle="modal"
                                data-bs-target="#KhaataDetails">
                                <td>
                                    <?php echo '<b>P#</b>' . $purchase_id;
                                    echo purchaseSpecificData($purchase_id, 'purchase_type');
                                    echo '<br><span class="font-size-11"><b>P.D. </b>' . date('y-m-d', strtotime($purchase['p_date'])).'</span>';?>
                                </td>
                                <td class="font-size-11 text-nowrap text-uppercase">
                                    <?php echo '<b>P.A/c# </b>' . $purchase['p_khaata_no'] . '<br>'; ?>
                                    <?php echo '<b>B. </b>' . branchName($purchase['branch_id']); ?>
                                </td>
                                <td class="font-size-11 text-nowrap-">
                                    <?php echo '<b>A/c#</b>' . $purchase['s_khaata_no'] . '<br>';
                                    echo $s_khaata['khaata_name'] ?? '' . '<br>';
                                    echo $s_khaata['comp_name'] ?? ''; ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php echo $Goods . $ITEMS . $Qty . $KGs; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . round($totals['Amount'], 2) . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>', round($totalPurchaseAmount) . '<sub>' . $totals['curr2'] . '</sub>';
                                    } ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>' . round($purchase['pct_amt']) . '[' . round($purchase['pct'], 2) . '%]</b><br>';
                                    echo '<span class="text-success"><b>Paid </b>' . round($adv_paid_total) . '</span>'; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php $rem_amount = round($totalPurchaseAmount - $purchase['pct_amt']);
                                    $rem_pct = 100 - $purchase['pct'];
                                    echo '<b>' . round($rem_amount, 2) . '[' . round($rem_pct, 2) . '%]</b><br>';
                                    echo '<span class="text-success"><b>Paid </b>' . round($rem_paid_total) . '</span>'; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if ($transfer == 2) {
                                        echo '<i class="fa fa-check-double text-success"></i> Transferred';
                                        echo !empty($purchase['t_date2']) ? '<br><b>Transfer D.</b>' . date('y-m-d', strtotime($purchase['t_date2'])) : '';
                                    } ?>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                    <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    if (isset($_GET['purchase_pays_id']) && is_numeric($_GET['purchase_pays_id'])) {
        $purchase_pays_id = mysqli_real_escape_string($connect, $_GET['purchase_pays_id']);
    } else {
        $purchase_pays_id = 0;
    }
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id,$purchase_pays_id); });</script>";
} ?>
<?php if (isset($_POST['tRemSubmit'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $p_type = mysqli_real_escape_string($connect, $_POST['p_type_hidden']);
    $url = 'purchase-rem?p_id=' . $p_id;
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);
    $currency1 = mysqli_real_escape_string($connect, $_POST['currency1']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $currency2 = mysqli_real_escape_string($connect, $_POST['currency2']);
    $rate = mysqli_real_escape_string($connect, $_POST['rate']);
    $opr = mysqli_real_escape_string($connect, $_POST['opr']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $report = mysqli_real_escape_string($connect, $_POST['report']);
    $details = 'Amount: ' . $amount . $currency1 . ' Rate: ' . $rate . '/' . $currency2 . ' TransferDate' . $transfer_date . ' ' . $report;
    $data = array(
        'type' => 'p_rem',
        'purchase_id' => $p_id,
        'dr_khaata_no' => $jmaa_khaata_no,
        'dr_khaata_id' => $jmaa_khaata_id,
        'cr_khaata_no' => $bnaam_khaata_no,
        'cr_khaata_id' => $bnaam_khaata_id,
        'currency1' => $currency1,
        'amount' => $amount,
        'currency2' => $currency2,
        'rate' => $rate,
        'opr' => $opr,
        'final_amount' => $final_amount,
        'transfer_date' => $transfer_date,
        'report' => $details
    );
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $purchase_pays_id = mysqli_real_escape_string($connect, $_POST['purchase_pays_id_hidden']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $adv_payment_added = update('purchase_pays', $data, array('id' => $purchase_pays_id));
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $adv_payment_added = insert('purchase_pays', $data);
        $purchase_pays_id = $connect->insert_id;
    }
    $r_type = 'Business';
    $transfered_from = 'purchase_rem';
    $type = 'P.R';
    if ($adv_payment_added) {
        $msg = 'Payment saved in DB. ';
//$msg = 'Transferred to Business Roznamcha ' . $str . ' Also, transferred Loading form.';
        $msgType = 'success';
        $pdQ = fetch('purchases', array('id' => $p_id));
        $p_data = mysqli_fetch_assoc($pdQ);
        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $purchase_pays_id,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $p_data['created_by'],
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $purchase_pays_id,
            'r_name' => $type,
            'r_no' => $p_id,
            'details' => $details
        );
        $str = ucfirst($p_data['type']) . " Purchase#" . $p_id . " ";
        $transferred = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $dataArrayUpdate['dr_cr'] = 'dr';
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $dataArrayUpdate['dr_cr'] = 'cr';
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $transferred = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1;
                 $i <= 2;
                 $i++) {
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'dr';
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $transferred = insert('roznamchaas', $dataArray);
            }
        }
        if ($transferred) {
            $msg .= ' And transferred to Roznamcha successfully. ';
//$preData = array('khaata_adv' => $post_json);\
        } else {
            $msg .= ' Transfer Error :(';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    message($msgType, $url, $msg);
}
if (isset($_POST['transferRemToFull'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('transfer' => 2, 't_date2' => date('Y-m-d'));
    $locked = update('purchases', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Transferred to Full Payment Form. ';
    }
    message($type, $pageURL, $msg);
}
if (isset($_POST['deleteRemPaymentAndRozSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $url_ = "purchase-rem?view=1&p_id=" . $p_id_hidden;
    $p_type_hidden = mysqli_real_escape_string($connect, $_POST['p_type_hidden']);
    $purchase_pays_id = mysqli_real_escape_string($connect, $_POST['purchase_pays_id_hidden']);
    $r_id_hidden = json_decode($_POST['r_id_hidden'], true);
    $pays_del = mysqli_query($connect, "DELETE FROM `purchase_pays` WHERE id='$purchase_pays_id'");
    foreach ($r_id_hidden as $r_id) {
        $done = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id='$r_id'");
    }
    if ($pays_del) {
        $msg = " Payment Deleted for Purchase #" . $p_id_hidden;
        $type = "success";
    }
    message($type, $url_, $msg);
} ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
</script>
<script>
    function viewPurchase(id = null, purchase_pays_id = null) {
        if (id) {
            var pp_id = 0;
            if (purchase_pays_id) {
                pp_id = purchase_pays_id;
            }
            $.ajax({
                url: 'ajax/viewSinglePurchaseRem.php',
                type: 'post',
                data: {id: id, purchase_pays_id: pp_id},
                //data: {id: id, show_transfer: true},
                success: function (response) {
                    //console.log(response);
                    $('#viewDetails').html(response);
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="staticBackdropLabel">PURCHASE 80% PAYMENTS</h5>
                <a href="<?php echo $pageURL;?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>

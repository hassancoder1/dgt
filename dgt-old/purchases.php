<?php $page_title = 'Bill Transfer Form';
$pageURL = 'purchases';
include("header.php");
$remove = $ps_type = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = $adv_full = '';
$is_search = false;
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 ";
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
    if (isset($_GET['adv_full'])) {
        $adv_full = mysqli_real_escape_string($connect, $_GET['adv_full']);
        $pageURL .= '&adv_full=' . $adv_full;
    }
    if (isset($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
        $pageURL .= '&s_khaata_id=' . $s_khaata_id;
    }
}
$sql .= " ORDER BY khaata_tr1 "; ?>
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
                <div class="input-group d-none">
                    <select class="form-select" name="size" id="size">
                        <option value="">ALL SIZE</option>
                        <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size, goods_id FROM `good_details` ");
                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                            $G_NAME = goodsName($size_s['goods_id']);
                            if ($goods_name != '') {
                                if ($G_NAME != $goods_name) continue;
                            }
                            $size_selected = $size_s['size'] == $size ? 'selected' : '';
                            echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group d-none">
                    <select class="form-select" name="brand" id="brand">
                        <option value="">ALL BRAND</option>
                        <?php $goods_brands = mysqli_query($connect, "SELECT DISTINCT brand, goods_id FROM `good_details` ");
                        while ($g_brand = mysqli_fetch_assoc($goods_brands)) {
                            $G_NAME2 = goodsName($g_brand['goods_id']);
                            if ($goods_name != '') {
                                if ($G_NAME2 != $goods_name) continue;
                            }
                            $brand_selected = $g_brand['brand'] == $brand ? 'selected' : '';
                            echo '<option ' . $brand_selected . ' value="' . $g_brand['brand'] . '">' . $g_brand['brand'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group d-none">
                    <select class="form-select" name="origin" id="origin">
                        <option value="">ALL ORIGIN</option>
                        <?php $goods_origins = mysqli_query($connect, "SELECT DISTINCT origin, goods_id FROM good_details");
                        while ($g_origin = mysqli_fetch_assoc($goods_origins)) {
                            $G_NAME3 = goodsName($g_origin['goods_id']);
                            if ($goods_name != '') {
                                if ($G_NAME3 != $goods_name) continue;
                            }
                            $o_selected = $g_origin['origin'] == $origin ? 'selected' : '';
                            echo '<option ' . $o_selected . ' value="' . $g_origin['origin'] . '">' . $g_origin['origin'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group">
                    <select class="form-select" name="adv_full">
                        <option value="">All</option>
                        <?php $adv_full_array = array(1 => 'Advance', 2 => 'Full');
                        foreach ($adv_full_array as $item => $value) {
                            $sel_adv_full = $adv_full == $item ? 'selected' : '';
                            echo '<option ' . $sel_adv_full . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group">
                    <select class="form-select" name="is_transferred">
                        <option value="">All</option>
                        <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
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
                        <input type="hidden" name="adv_full" value="<?php echo $adv_full; ?>">
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
                            <tr class="text-nowrap">
                                <th>TYPE</th>
                                <th>BRANCH</th>
                                <th>SELLER</th>
                                <th>GOODS DETAILS</th>
                                <th>AMOUNT</th>
                                <th>PAYMENT</th>
                                <th>TRANSFER</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $purchases = mysqli_query($connect, $sql);
                            $row_count = $p_qty_total = $p_kgs_total = 0;
                            while ($purchase = mysqli_fetch_assoc($purchases)) {
                                $khaata_tr1 = json_decode($purchase['khaata_tr1']);
                                $purchase_id = $purchase['id'];
                                $purchase_type = $purchase['type'];
                                $p_khaata = khaataSingle($purchase['p_khaata_id']);
                                $s_khaata = khaataSingle($purchase['s_khaata_id']);
                                $transfer = $purchase['transfer'];

                                $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                                $totals = purchaseSpecificData($purchase_id, 'product_details');
                                $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                                $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                                $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . '<br>' : '';
                                $Origin = $cntrs > 0 ? '<b>ORIGIN. </b>' . $totals['Origin'][0] . '<br>' : '';
                                $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                                $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . ' ' : '';
                                $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';
                                if ($is_search) {
                                    if ($start != '') {
                                        if ($purchase['p_date'] < $start) continue;
                                    }
                                    if ($end != '') {
                                        if ($purchase['p_date'] > $end) continue;
                                    }
                                    if ($goods_name != '') {
                                        if ($goods_name != $totals['Goods'][0]) continue;
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
                                        if ($is_transferred == 1) {
                                            if ($transfer <= 0) continue;
                                        }
                                        if ($is_transferred == 0) {
                                            if ($transfer > 0) continue;
                                        }
                                    }
                                    if ($s_khaata_id != '') {
                                        if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                                    }
                                    if ($adv_full != '') {
                                        if ($adv_full != $transfer) continue;
                                    }

                                }
                                $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                                $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                                $rowColor = empty($khaata_tr1) ? 'bg-danger bg-opacity-10' : ''; ?>
                                <tr class="pointer text-uppercase <?php echo $rowColor; ?>"
                                    onclick="viewPurchase(<?php echo $purchase_id; ?>)" data-bs-toggle="modal"
                                    data-bs-target="#KhaataDetails">
                                    <td class="text-nowrap">
                                        <?php echo '<b>P#</b>' . $purchase_id;
                                        echo purchaseSpecificData($purchase_id, 'purchase_type');
                                        echo '<br><span class="font-size-11"><b>P.D. </b>' . date('y-m-d', strtotime($purchase['p_date'])) . '</span>'; ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap text-uppercase">
                                        <?php echo '<b>P.A/c#</b>' . $purchase['p_khaata_no'] . '<br>';
                                        echo '<b>B.</b> ' . branchName($purchase['branch_id']); ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap">
                                        <?php echo '<b>A/c#</b>' . $purchase['s_khaata_no'] . '<br>';
                                        echo $s_khaata['khaata_name'] ?? '' . '<br>';
                                        echo $s_khaata['comp_name'] ?? ''; ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap">
                                        <?php echo $Goods . $ITEMS . $Qty . $KGs; ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if ($cntrs > 0) {
                                            echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                            echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                            echo !empty($purchase['t_date']) ? '<br><b>Transfer D.</b>' . date('y-m-d', strtotime($purchase['t_date'])) : '';
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if ($transfer > 0) {
                                            if ($transfer == 1) {
                                                echo '<b>ADV [' . $purchase['pct'] . '%]</b><br>' . $purchase['pct_amt'];
                                            } elseif ($transfer == 2) {
                                                echo '<b>FULL</b>';
                                            }
                                        } ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap">
                                        <?php if (!empty($khaata_tr1)) {
                                            echo '<i class="fa fa-check-double text-success"></i> ';
                                            echo '<b>Dr.A/C</b>' . $khaata_tr1->dr_khaata_no;
                                            echo ' <b>Cr.A/C</b>' . $khaata_tr1->cr_khaata_no;
                                            echo '<br><b>Transfer</b> ' . date('y-m-d', strtotime($khaata_tr1->transfer_date));
                                            $rr = getTransferredToRoznamchaSerial('Business', $purchase_id, 'purchase_' . $purchase_type);
                                            echo $rr != '' ? '<br><b>R. SR#</b> ' . $rr : '';
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
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id); });</script>";
} ?>
<?php if (isset($_POST['transferAdvFullSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $transfer = mysqli_real_escape_string($connect, $_POST['transfer']);
    $pct = $pct_amt = '';
    if ($transfer == 1) {
        $str = 'Advance Payment ';
        $pct = mysqli_real_escape_string($connect, $_POST['pct']);
        $pct_amt = mysqli_real_escape_string($connect, $_POST['pct_amt']);
    } else {
        $str = 'Full Payment ';
    }
    $data = array('transfer' => $transfer, 'pct' => $pct, 'pct_amt' => $pct_amt,);
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $done = update('purchases', $data, array('id' => $p_id_hidden));
    $url = 'purchases?p_id=' . $p_id_hidden . '&view=1';
    if ($done) {
        $msg = $str . " has been saved for purchase#" . $p_id_hidden;
        $type = "success";
    }
    message($type, $url, $msg);
}
if (isset($_POST['ttrFirstSubmit'])) {
    unset($_POST['ttrFirstSubmit']);
    $post_json = json_encode($_POST);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);

    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $type_post = mysqli_real_escape_string($connect, $_POST['type']);
    $url = $pageURL . '?p_id=' . $p_id;
    $type = ' P.' . ucfirst(substr($type_post, 0, '1'));
    $transfered_from = 'purchase_' . $type_post;
    $r_type = 'Business';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQ = fetch('purchases', array('id' => $p_id));
        $p_data = mysqli_fetch_assoc($pQ);

        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $p_id,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $userId,
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $p_id,
            'r_name' => $type,
            'r_no' => $p_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Purchase # " . $p_id;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    /*$k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);*/
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Dr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Cr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $url .= '&view=1';
            $preData = array('khaata_tr1' => $post_json);
            $tlUpdated = update('purchases', $preData, array('id' => $p_id));
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
    <script>
        $("#rows_count_span").text($("#row_count").val());
        $("#p_qty_total_span").text($("#p_qty_total").val());
        $("#p_kgs_total_span").text($("#p_kgs_total").val());
    </script>
    <script>
        function viewPurchase(id = null) {
            if (id) {
                $.ajax({
                    url: 'ajax/viewSinglePurchase.php',
                    type: 'post',
                    data: {id: id, show_transfer: true},
                    //data: {id: id, p_khaata_no: k_no},
                    //dataType: 'json',
                    success: function (response) {
                        //console.log(response);
                        $('#viewDetails').html(response);
                        // $('#khaata_no').focus();
                        var temp_transfer_date = $("#temp_transfer_date").val();
                        console.log(temp_transfer_date);
                        if (typeof temp_transfer_date !== 'undefined') {
                            $("#transfer_date").val(temp_transfer_date);
                        }
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
                    <h5 class="modal-title text-danger" id="staticBackdropLabel">PURCHASE DETAILS</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </div>
        </div>
    </div>
<?php unset($_SESSION['response']); ?>
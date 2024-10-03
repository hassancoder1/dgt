<?php $pageURL = 'sales-booking';
//$sql = "SELECT * FROM `sales` WHERE type = 'booking' ORDER BY is_transfer ";
$page_title = 'Booking Sales ';
include("header.php");
$sql = "SELECT * FROM `sales` WHERE type = 'booking' ";
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
        <form method="get" class="d-flex align-items-center table-form text-nowrap">
            <?php echo $remove; ?>
            <input type="date" name="start" value="<?php echo $start; ?>" class="form-control w-75">
            <input type="date" name="end" value="<?php echo $end; ?>" class="form-control w-75">
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
                    <?php $imp_exp_array = array(1 => 'advance', 2 => 'full pay', 0 => 'not transferred');
                    foreach ($imp_exp_array as $item => $value) {
                        $sel_tran = $is_transferred == $item ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                    } ?>
                </select>
            </div>
            <div class="input-group">
                <select name="s_khaata_id" class="form-select">
                    <option value="" hidden>Customer A/c</option>
                    <?php $accounts_query = fetch('khaata');
                    while ($aa = mysqli_fetch_assoc($accounts_query)) {
                        $sel = $s_khaata_id == $aa['id'] ? 'selected' : '';
                        echo '<option ' . $sel . ' value="' . $aa['id'] . '">' . $aa['khaata_no'] . '</option>';
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
                <form action="print/sales-full" target="_blank" method="get">
                    <input type="hidden" name="table" value="sales">
                    <input type="hidden" name="url" value="sales-booking">
                    <input type="hidden" name="type" value="booking">
                    <input type="hidden" name="start" value="<?php echo $start; ?>">
                    <input type="hidden" name="end" value="<?php echo $end; ?>">
                    <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                    <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                    <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                    <button class="btn btn-sm btn-success">PRINT</button>
                </form>
                <?php echo addNew('sale-add', 'BOOKING SALE', 'btn-sm'); ?>
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 72dvh;">
                    <table class="table mb-0 table-bordered fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>DETAILS</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>REPORT</th>
                            <th>SOLD TO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($sale = mysqli_fetch_assoc($sales)) {
                            $sale_id = $sale['id'];
                            $sale_type = $sale['type'];
                            $transfer = $sale['transfer'];
                            $s_khaata_no = $sale['s_khaata_no'];

                            $cntrs = saleSpecificData($sale_id, 'sale_rows');
                            $totals = saleSpecificData($sale_id, 'product_details');
                            $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                            $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                            //$Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . '<br>' : '';
                            //$Origin = $cntrs > 0 ? '<b>ORIGIN. </b>' . $totals['Origin'][0] . '<br>' : '';
                            $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                            $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . '<br>' : '';
                            $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';

                            $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                            if ($is_search) {
                                if ($start != '') {
                                    if ($sale['s_date'] < $start) continue;
                                }
                                if ($end != '') {
                                    if ($sale['s_date'] > $end) continue;
                                }
                                if ($goods_name != '') {
                                    if ($goods_name != $GoodsKaNaam) continue;
                                }
                                if ($is_transferred != '') {
                                    if ($is_transferred == 0) {
                                        if ($transfer == 1 || $transfer == 2) continue;
                                    }
                                    if ($is_transferred == 1) {
                                        if ($transfer == 0 || $transfer == 2) continue;
                                    }
                                    if ($is_transferred == 2) {
                                        if ($transfer == 0 || $transfer == 1) continue;
                                    }
                                }
                                if ($s_khaata_id != '') {
                                    if ($s_khaata_id != $sale['s_khaata_id']) continue;
                                }
                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            $rowColor = $transfer == 0 ? 'bg-danger bg-opacity-10' : ''; ?>
                            <tr class="<?php echo $rowColor; ?>">
                                <td class="pointer text-nowrap" onclick="viewSale(<?php echo $sale_id; ?>)"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                    echo '<br>' . seaRoadBadge($sale['sea_road']);
                                    echo saleSpecificData($sale_id, 'transfer_type');
                                    echo '<br><span class="font-size-11"><b>D.</b>' . date('y-m-d', strtotime($sale['s_date'])) . '</span>';
                                    ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>B.</b>' . branchName($sale['branch_id']);
                                    echo ' <b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php echo $Goods . $ITEMS . $Qty . $KGs; ?>
                                </td>
                                <td class="text-dark text-nowrap">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                    } ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo readMoreTooltip($sale['report'], '50'); ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if ($sale['s_khaata_no'] == '') {
                                        echo '<div class="bg-danger">&nbsp;</div>';
                                    } else {
                                        echo '<b>A/c#</b>' . $sale['s_khaata_no'];
                                        $sold_to = khaataSingle($sale['s_khaata_no'], true);
                                        echo '<br>' . $sold_to['comp_name'];
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
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
</script>
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
    $data = array('transfer' => $transfer, 't_date' => date('Y-m-d'), 'pct' => $pct, 'pct_amt' => $pct_amt);
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $done = update('sales', $data, array('id' => $s_id_hidden));
    $url = $pageURL . '?s_id=' . $s_id_hidden . '&view=1';
    if ($done) {
        $msg = $str . " has been saved for sale#" . $s_id_hidden;
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
    $s_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $type_post = mysqli_real_escape_string($connect, $_POST['type']);
    $url = $pageURL . '?s_id=' . $s_id;
    $type = ' S.' . ucfirst(substr($type_post, 0, '1'));
    $transfered_from = 'sale_' . $type_post;
    $r_type = 'Business';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQ = fetch('sales', array('id' => $s_id));
        $p_data = mysqli_fetch_assoc($pQ);
        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $s_id,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $userId,
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $s_id,
            'r_name' => $type,
            'r_no' => $s_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Sale # " . $s_id;
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
            $tlUpdated = update('sales', $preData, array('id' => $s_id));
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
}
if (isset($_POST['s_id_hidden_attach'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $ppp_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden_attach']);
    $url_ = $pageURL . "?p_id=" . $ppp_id . "&attach=1";
    //$dato = array('is_doc' => 1);
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($ppp_id, 'sales', basename($_FILES["attachments"]["name"][$key]));
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
            //$dd = update('sales', $dato, array('id' => $ppp_id));
            //if ($moved && $dd) {
            if ($moved) {
                $type = 'success';
                $msg = 'Attachment Saved ';
                $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            }
        }
    }
    message($type, $url_, $msg);
} ?>
<?php if (isset($_GET['s_id']) && is_numeric($_GET['s_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $s_id = mysqli_real_escape_string($connect, $_GET['s_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewSale($s_id); });</script>";
} ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="staticBackdropLabel">SALE DETAILS</h5>
                <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewSale(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleSale.php',
                type: 'post',
                //data: {id: id},
                data: {id: id, show_transfer: true},
                //data: {id: id, p_khaata_no: k_no},
                //dataType: 'json',
                success: function (response) {
                    //console.log(response);
                    $('#viewDetails').html(response);
                    // $('#khaata_no').focus();
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>

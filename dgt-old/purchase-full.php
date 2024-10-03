<?php $page_title = 'Full Payment Form';
$pageURL = 'purchase-full';
include("header.php");
global $connect;
$remove = $ps_type = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = $adv_full = '';
$is_search = false;
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2 ";
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
} ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between gap-0">
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
                    <button class="btn btn-sm btn-success">PRINT</button>
                </form>
            </div>
            <form method="get" class="d-flex align-items-center table-form text-nowrap-">
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <?php echo $remove; ?>
                <input type="date" name="start" value="<?php echo $start; ?>" class="form-control">
                <input type="date" name="end" value="<?php echo $end; ?>" class="form-control">
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
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 76dvh;">
                    <table class="table mb-0 table-bordered fix-head-table table-sm">
                        <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>BRANCH</th>
                            <th>SELLER</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $purchases = mysqli_query($connect, $sql);
                        $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($purchase = mysqli_fetch_assoc($purchases)) {
                            $purchase_id = $purchase['id'];
                            $purchase_type = $purchase['type'];
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
                            $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';
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
                                if ($s_khaata_id != '') {
                                    if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                                }

                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            //$rowColor = empty($khaata_tr1) ? 'bg-danger bg-opacity-10' : ''; ?>
                            <tr class="pointer text-uppercase <?php //echo $rowColor; ?>"
                                onclick="viewPurchase(<?php echo $purchase_id; ?>)" data-bs-toggle="modal"
                                data-bs-target="#KhaataDetails">
                                <td>
                                    <?php echo '<b>P#</b>' . $purchase_id;
                                    echo purchaseSpecificData($purchase_id, 'purchase_type');
                                    echo '<br><span class="font-size-11"><b>P.D. </b>' . date('y-m-d', strtotime($purchase['p_date'])) . '</span>'; ?>
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
                                        echo '<br><b>Final </b>', round($totals['Final']) . '<sub>' . $totals['curr2'] . '</sub>';
                                        echo !empty($purchase['t2_date']) ? '<br><b>Transfer D.</b>' . date('y-m-d', strtotime($purchase['t2_date'])) : '';
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
    function viewPurchase(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSinglePurchaseFull.php',
                type: 'post',
                data: {id: id},
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
                <h5 class="modal-title text-danger" id="staticBackdropLabel">PURCHASE FULL PAYMENTS</h5>
                <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<?php if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id); });</script>";
} ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
</script>

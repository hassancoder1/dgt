<?php $page_title = 'Purchase Orders [LOCAL]';
$pageURL = 'purchase-local-orders';
include("header.php"); ?>
<?php $remove = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `purchases` WHERE type = 'local' AND is_locked =0 ";
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
} ?>
<style>
    label {
        margin-bottom: 0;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <form method="get" class="d-flex align-items-center table-form text-nowrap">
            <?php echo $remove; ?>
            <input type="date" name="start" value="<?php echo $start; ?>" class="form-control w-75">
            <input type="date" name="end" value="<?php echo $end; ?>" class="form-control w-75">
            <div class="input-group">
                <!--<label for="goods_name">GOODS</label>-->
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
                <!--<label for="size">SIZE</label>-->
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
            <div class="input-group">
                <!--<label for="brand">BRAND</label>-->
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
            <div class="input-group">
                <!--<label for="origin">ORIGIN</label>-->
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
                <select class="form-select" name="is_transferred">
                    <option value="">All</option>
                    <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                    foreach ($imp_exp_array as $item => $value) {
                        $sel_tran = $is_transferred == $item ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                    } ?>
                </select>
            </div>
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
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-search"></i></button>
        </form>
        <div class="d-flex align-items-center justify-content-between gap-3">
            <?php echo addNew('purchase-local-add', '', 'btn-sm'); ?>
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
            <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
            <div class="d-flex text-nowrap">
                <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                    <input type="hidden" name="start" value="<?php echo $start; ?>">
                    <input type="hidden" name="end" value="<?php echo $end; ?>">
                    <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                    <input type="hidden" name="size" value="<?php echo $size; ?>">
                    <input type="hidden" name="brand" value="<?php echo $brand; ?>">
                    <input type="hidden" name="origin" value="<?php echo $origin; ?>">
                    <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                    <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
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
                    <table class="table mb-0 table-bordered table-sm fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>BRANCH</th>
                            <th>SELLER</th>
                            <th>DETAILS</th>
                            <th colspan="2">GOODS DETAILS</th>
                            <th>AMOUNT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $purchases = mysqli_query($connect, $sql);
                        $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($purchase = mysqli_fetch_assoc($purchases)) {
                            $is_doc = $purchase['is_doc'];
                            $is_locked = $purchase['is_locked'];
                            $purchase_id = $purchase['id'];
                            $purchase_type = $purchase['type'];
                            $p_khaata = khaataSingle($purchase['p_khaata_id']);
                            $s_khaata = khaataSingle($purchase['s_khaata_id']);
                            $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                            $totals = purchaseSpecificData($purchase_id, 'product_details');
                            $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                            $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                            $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . ' ' : '';
                            $Origin = $cntrs > 0 ? '<b>ORIGIN. </b>' . $totals['Origin'][0] . ' ' : '';
                            $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . '<br>' : '';
                            $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . '<br>' : '';
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
                                if ($is_transferred != '') {
                                    if ($is_transferred == 1) {
                                        if ($is_locked == 0) continue;
                                    }
                                    if ($is_transferred == 0) {
                                        if ($is_locked == 1) continue;
                                    }
                                }
                                if ($s_khaata_id != '') {
                                    if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                                }
                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            $rowColor = '';
                            if ($is_locked == 0) {
                                if ($is_doc == 0) {
                                    $rowColor = 'bg-danger bg-opacity-10';
                                } else {
                                    $rowColor = 'bg-warning bg-opacity-10';
                                }
                            } ?>
                            <tr class="pointer <?php echo $rowColor; ?>"
                                onclick="viewPurchase(<?php echo $purchase_id; ?>)" data-bs-toggle="modal"
                                data-bs-target="#KhaataDetails">
                                <td class="text-nowrap font-size-11">
                                    <?php echo '<b>P#</b>' . $purchase_id;
                                    echo purchaseSpecificData($purchase_id, 'purchase_type');
                                    echo '<br><b>D.</b>' . date('y-m-d', strtotime($purchase['p_date']));
                                    if ($is_locked == 0) {
                                        echo $is_doc == 0 ? '<br><span class="text-danger">Contract Pending</span>' : '<br><i class="fa fa-check-double text-success"></i> Attachment';
                                    } else {
                                        echo '<br><i class="fa fa-lock text-success"></i> Transferred.';
                                    } ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php echo '<b>PURCHASE A/c#</b>' . $purchase['p_khaata_no'] . '<br>';
                                    echo '<b>B.</b> ' . branchName($purchase['branch_id']) . '<br>';
                                    echo '<b>D.</b> ' . date('y-m-d', strtotime($purchase['p_date'])); ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>A/c#</b>' . $purchase['s_khaata_no'] . '<br>';
                                    echo $s_khaata['khaata_name'] . '<br>';
                                    echo $s_khaata['comp_name']; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>COUNTRY</b>' . $purchase['country'] . '<br>';
                                    echo '<b>ALLOT</b>' . $purchase['allot'].'<br>';
                                    echo $Origin; ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php echo $Goods . $Size . $Brand; ?>
                                </td>
                                <td class="font-size-11 text-nowrap"><?php echo $ITEMS . $Qty . $KGs; ?></td>
                                <td class="text-dark">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                        echo !empty($purchase['t_date']) ? '<br><b>Transfer </b>' . $purchase['t_date'] : '';
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

<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">LOCAL PURCHASE ORDER DETAILS</h5>
                <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewPurchase(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSinglePurchase.php',
                type: 'post',
                data: {id: id},
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
<?php if (isset($_POST['deletePurchase'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $url_ = "purchases";
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $done = mysqli_query($connect, "DELETE FROM `purchase_details` WHERE parent_id='$p_id_hidden'");
    $done = mysqli_query($connect, "DELETE FROM `purchases` WHERE id='$p_id_hidden'");
    if ($done) {
        $msg = " Deleted Local Purchase #" . $p_id_hidden;
        $type = "success";
    }
    message($type, $url_, $msg);
}
if (isset($_POST['p_id_hidden_attach'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $ppp_id = mysqli_real_escape_string($connect, $_POST['p_id_hidden_attach']);
    $url_ = $pageURL . "?p_id=" . $ppp_id . "&attach=1";
    $dato = array('is_doc' => 1);
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($ppp_id, 'purchase_local_order', basename($_FILES["attachments"]["name"][$key]));
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
            $dd = update('purchases', $dato, array('id' => $ppp_id));
            if ($moved && $dd) {
                $type = 'success';
                $msg = 'Attachment Saved ';
                $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            }
        }
    }
    message($type, $url_, $msg);
}
if (isset($_POST['transferPurchase'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('is_locked' => 1, 't_date' => date('Y-m-d'));
    $locked = update('purchases', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Local Purchase transferred ';
    }
    message($type, $pageURL, $msg);
} ?>
<?php if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id); });</script>";
} ?>

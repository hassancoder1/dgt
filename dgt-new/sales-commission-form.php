<?php
$page_title = 'Commission';
$pageURL = 'sales-commission-form';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `transactions` WHERE p_s = 's' AND type='commission'";
$conditions = []; // Store all conditions here
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('sales-commission-form');
    $is_search = true;

    // Filter by goods_name
    if (isset($_GET['goods_name']) && !empty($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
        $print_filters[] = 'goods_name=' . $goods_name;
    }

    // Filter by start date
    if (isset($_GET['start']) && !empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        // $pageURL .= '&start=' . $start_print;
        $print_filters[] = 'start=' . $start_print;
        $conditions[] = "_date >= '$start_print'";
    }

    // Filter by end date
    if (isset($_GET['end']) && !empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        // $pageURL .= '&end=' . $end_print;
        $print_filters[] = 'end=' . $end_print;
        $conditions[] = "_date <= '$end_print'";
    }

    if (isset($_GET['is_transferred']) && $_GET['is_transferred'] !== '') {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $print_filters[] = "is_transferred=" . $is_transferred;
        if ($is_transferred === '1') {
            $conditions[] = "locked = '1'"; // Only inactive records
        } elseif ($is_transferred === '0') {
            $conditions[] = "locked = '0'"; // Only active records

        }
    }

    // Filter by s_khaata_id
    if (isset($_GET['s_khaata_id']) && !empty($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
        // $pageURL .= '&s_khaata_id=' . $s_khaata_id;
    }
}

// If there are any conditions, concatenate them with 'AND'
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
} else {
    $sql .= " AND locked IN ('1', '2')";
}

$sql .= " ORDER BY id DESC";
if (count($print_filters) > 0) {
    $pageURL .= "?";
    foreach ($print_filters as $filter) {
        $pageURL .= '&' . $filter;
    }
} else {
    // $pageURL .= '?is_tranferred=1';
    $is_transferred = '1';
}
$mypageURL = $pageURL;
?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="text-nowrap">
            <div class="lh-1">
                <b>Rows</b><span id="rows_count_span"></span>
                <b>Qty </b><span id="p_qty_total_span"></span>
                <br>
                <b>KGs</b><span id="p_kgs_total_span"></span>
            </div>
        </div>
        <form name="datesSubmit" method="get">
            <div class="input-group input-group-sm">
                <input type="date" name="start" value="<?php echo $start_print; ?>" class="form-control">
                <input type="date" name="end" value="<?php echo $end_print; ?>" class="form-control">
                <select id="goods_name" name="goods_name" class="form-select">
                    <option value="">ALL GOODS</option>
                    <?php $goods = fetch('goods');
                    while ($good = mysqli_fetch_assoc($goods)) {
                        $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                        echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                    } ?>
                </select>
                <!--<select class="form-select" name="size" id="size">
                            <option value="">ALL SIZE</option>
                            <?php /*$goods_sizes = mysqli_query($connect, "SELECT DISTINCT size, goods_id FROM `good_details` ");
                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                $G_NAME = goodsName($size_s['goods_id']);
                                if ($goods_name != '') {
                                    if ($G_NAME != $goods_name) continue;
                                }
                                $size_selected = $size_s['size'] == $size ? 'selected' : '';
                                echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                            } */ ?>
                        </select>
                        <select class="form-select" name="brand" id="brand">
                            <option value="">ALL BRAND</option>
                            <?php /*$goods_brands = mysqli_query($connect, "SELECT DISTINCT brand, goods_id FROM `good_details` ");
                            while ($g_brand = mysqli_fetch_assoc($goods_brands)) {
                                $G_NAME2 = goodsName($g_brand['goods_id']);
                                if ($goods_name != '') {
                                    if ($G_NAME2 != $goods_name) continue;
                                }
                                $brand_selected = $g_brand['brand'] == $brand ? 'selected' : '';
                                echo '<option ' . $brand_selected . ' value="' . $g_brand['brand'] . '">' . $g_brand['brand'] . '</option>';
                            } */ ?>
                        </select>-->
                <select class="form-select" name="is_transferred">
                    <option <?= isset($is_transferred) && $is_transferred == 1 ? 'selected' : 'selected'; ?> value="1">Transferred</option>
                    <option <?= isset($is_transferred) && $is_transferred == 0 ? 'selected' : ''; ?> value="0">Not Transferred</option>
                </select>




                <!-- <select class="form-select" name="is_transferred">
                    <option value="">All</option>
                    <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                    foreach ($imp_exp_array as $item => $value) {
                        $sel_tran = $is_transferred == $item ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                    } ?>
                </select> -->
                <!-- <select name="s_khaata_id" class="form-select">
                    <option value="">Seller A/c</option>
                    <?php $accounts_query = fetch('khaata');
                    while ($aa = mysqli_fetch_assoc($accounts_query)) {
                        $sel = $s_khaata_id == $aa['id'] ? 'selected' : '';
                        echo '<option ' . $sel . ' value="' . $aa['id'] . '">' . $aa['khaata_no'] . '</option>';
                    } ?>
                </select> -->

                <input type="text" class="form-control" name="s_khaata_id" placeholder="Account No." value="<?= $s_khaata_id ?>">
                <?php echo $remove; ?>
                <button type="submit" class="btn btn-success btn-sm">
                    Search
                </button>
            </div>
        </form>
        <div class="d-flex gap-1">
            <?php // echo searchInput('1', 'form-control form-control-sm '); 
            ?>
            <?php echo addNew('purchase-add', '', 'btn-sm'); ?>
            <form action="print/<?php echo $mypageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start_print; ?>">
                <input type="hidden" name="end" value="<?php echo $end_print; ?>">
                <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                <!-- <input type="hidden" name="size" value="<?php echo $size; ?>">
                <input type="hidden" name="brand" value="<?php echo $brand; ?>"> -->
                <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                <input type="hidden" name="secret" value="<?= base64_encode("powered-by-upsol"); ?>">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-print"></i>
                </button>
            </form>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 83vh">
                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th>Bill#</th>
                                <th>Type</th>
                                <th>BR.</th>
                                <th>Date</th>
                                <th>A/c</th>
                                <th>A/c Name</th>
                                <th>Goods Name</th>
                                <th>Qty</th>
                                <th>KGs</th>
                                <th>AMOUNT</th>
                                <th>PAYMENT TYPE</th>
                                <th>COUNTRY</th>
                                <th>ROAD</th>
                                <th>LOADING COUNTRY | DATE</th>
                                <th>RECEIVING COUNTRY | DATE</th>
                                <th>DOCS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $purchases = mysqli_query($connect, $sql);
                            $row_count = $p_qty_total = $p_kgs_total = 0;
                            $commission = [];
                            $commission_items_query = fetch('commission_items');
                            while ($commission_item = mysqli_fetch_assoc($commission_items_query)) {
                                $commission[] = $commission_item;
                            }
                            $commission_item_qty = $commission_item_amt = 0;
                            $sortedEnteries = $redEntries = $primaryEntries = $darkEntries = [];
                            while ($purchase = mysqli_fetch_assoc($purchases)) {
                                $_fields_single = transactionSingle($purchase['id']);
                                $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                                foreach ($commission as $com) {
                                    if ($com['sale_id'] == $purchase['id']) { // Check if the commission item matches the purchase ID
                                        $commission_item_qty += (float)$com['qty_no']; // Add the quantity to the total
                                        $commission_item_amt += (float)$com['final_amount']; // Add the quantity to the total
                                    }
                                }
                                $rowColor = '';
                                $rowColor = $Qty != $commission_item_qty ? 'text-danger ' : ' text-dark ';
                                $rowColor = $commission_item_qty === 0 ? 'text-danger' : ($Qty == $commission_item_qty ? 'text-dark' : 'text-primary');
                                $commission_item_qty = 0;
                                // Categorize entries based on color
                                if ($rowColor == 'text-danger') {
                                    $redEntries[] = $purchase;
                                } elseif ($rowColor == 'text-primary') {
                                    $primaryEntries[] = $purchase;
                                } elseif ($rowColor == 'text-dark') {
                                    $darkEntries[] = $purchase;
                                }
                            }
                            $sortedEnteries = array_merge($redEntries, $primaryEntries, $darkEntries);
                            foreach ($sortedEnteries as $purchase) {
                                foreach ($commission as $com) {
                                    if ($com['sale_id'] == $purchase['id']) { // Check if the commission item matches the purchase ID
                                        $commission_item_qty += (float)$com['qty_no']; // Add the quantity to the total
                                        $commission_item_amt += (float)$com['final_amount']; // Add the quantity to the total
                                    }
                                }
                                $id = $purchase['id'];
                                $p_sr = $purchase['sr'];
                                $is_doc = $purchase['is_doc'];
                                $locked = $purchase['locked'];
                                $_fields_single = transactionSingle($purchase['id']);
                                $cntrs = purchaseSpecificData($id, 'purchase_rows');
                                $totals = purchaseSpecificData($id, 'product_details');
                                $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
                                $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                                $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . ' ' : '';
                                $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                                $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];
                                $sea_road = '';
                                $sea_road_array = json_decode(getSeaRoadArray($id));
                                $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                                if (!empty($sea_road_array)) {
                                    $sea_road = $sea_road_array->sea_road ?? '';
                                    $_fields_sr = [];
                                    if ($sea_road === 'sea') {
                                        $_fields_sr = [
                                            'l_country' => $sea_road_array->l_country ?? '',
                                            'l_date'    => $sea_road_array->l_date ?? '',
                                            'r_country' => $sea_road_array->r_country ?? '',
                                            'r_date'    => $sea_road_array->r_date ?? '',
                                            'truck_no' => $sea_road_array->truck_no ?? '',
                                            'truck_name' => $sea_road_array->truck_name ?? '',
                                            'loading_company_name' => $sea_road_array->loading_company_name ?? '',
                                            'loading_date' => $sea_road_array->loading_date ?? '',
                                            'transfer_name' => $sea_road_array->transfer_name ?? ''
                                        ];
                                    } elseif ($sea_road === 'road') {
                                        $_fields_sr = [
                                            'l_country' => $sea_road_array->l_country_road ?? '',
                                            'l_date'    => $sea_road_array->l_date_road ?? '',
                                            'r_country' => $sea_road_array->r_country_road ?? '',
                                            'r_date'    => $sea_road_array->r_date_road ?? '',
                                            'old_company_name' => $sea_road_array->old_company_name ?? '',
                                            'transfer_company_name' => $sea_road_array->transfer_company_name ?? '',
                                            'warehouse_date' => $sea_road_array->warehouse_date ?? '',
                                        ];
                                    }
                                }

                                if ($is_search) {
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

                                    if ($is_transferred != '') {
                                        if ($is_transferred == '1') {
                                            if ($locked == 0) continue;
                                        }
                                        if ($is_transferred == '0') {
                                            if ($locked == 1) continue;
                                        }
                                    }
                                }
                                $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                                $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                                $rowColor = '';
                                $rowColor = $Qty != $commission_item_qty ? 'text-danger ' : ' text-dark ';
                                $rowColor = $commission_item_qty === 0 ? 'text-danger' : ($Qty == $commission_item_qty ? 'text-dark' : 'text-primary');
                                $commission_item_qty = 0;
                            ?>
                                <tr class="text-nowrap">
                                    <td class="pointer <?php echo $rowColor; ?>" onclick="window.location.href='?view=1&t_id=<?= $id; ?>'">
                                        <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $p_sr;
                                        echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : '';
                                        echo $locked == 2 ? '<i class="fa fa-lock text-success"></i><i class="fa fa-lock text-success" style="margin-left:-6px;"></i>' : ''; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); //badge(strtoupper($_fields_single['type']), $purchase['type'] == 'booking' ? 'dark' : 'danger'); 
                                                                            ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']);; ?></td>
                                    <td class="s_khaata_id_row <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['dr_acc']); ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['dr_acc_name']; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                                    <td class="<?php echo $rowColor; ?>">
                                        <?php if ($cntrs > 0) {
                                            echo $commission_item_amt . '<sub>' . $totals['curr2'] . '</sub>';
                                            $commission_item_amt = 0;
                                        } ?>
                                    </td>
                                    <td class="<?php echo $rowColor; ?> px-2"><?= isset($_fields_single['payment_details']->full_advance) ? ucwords($_fields_single['payment_details']->full_advance) : "No Payment Details Available"; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $purchase['country']; ?></td>
                                    <?php
                                    if ($sea_road == '') {
                                        echo '<td class="<?php echo $rowColor; ?>" colspan="3"></td>';
                                    } else {
                                        echo '<td class="' . $rowColor . '">' . $sea_road . '</td>';
                                        echo '<td class="' . $rowColor . '">' . $_fields_sr['l_country'] . ' ' . my_date($_fields_sr['l_date']) . '</td>';
                                        echo '<td class="' . $rowColor . '">' . $_fields_sr['r_country'] . ' ' . my_date($_fields_sr['r_date']) . '</td>';
                                    }
                                    ?>
                                    <td class="<?php echo $rowColor; ?>">
                                        <?php if ($is_doc == 1) {
                                            $atts = getAttachments($id, 'purchase_contract');
                                            foreach ($atts as $att) {
                                                echo '<a href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank"><i class="fa fa-download text-success"></i></a>';
                                            }
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
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewPurchase(id = null) {
        let editId = <?= $_GET['editId'] ?? 0; ?>;
        let viewId = <?= $_GET['viewId'] ?? 0; ?>;
        if (id) {
            $.ajax({
                url: 'ajax/editCommissionGoods.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "sales-commission-form",
                    type: 'sale',
                    editId: editId,
                    viewId: viewId
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<?php
if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $done = mysqli_query($connect, "DELETE FROM commission_items WHERE id='$deleteId'");
    messageNew("success", $pageURL, "Delete Successful!");
}
// if (isset($_GET['deleteLastEntry'])) {
//     mysqli_query($connect, "DELETE FROM commission_items WHERE id = (SELECT MAX(id) FROM commission_items)");
//     messageNew("success", $pageURL, "");
// }
if (isset($_POST['transferPurchase'])) {
    $sale = json_decode(mysqli_fetch_assoc(fetch('transactions', ['id' => $_POST['p_id_hidden']]))['payments'], true);
    $sale['p_total_amount'] = $_POSt['TotalFinalAmount'];
    if ($sale['full_advance'] === 'advance') {
        $sale['partial_amount1'] = $_POST['TotalFinalAmount'] * ((float)$sale['pct_value'] / 100);
        $sale['partial_amount2'] = $_POST['TotalFinalAmount'] - ($_POST['TotalFinalAmount'] * ((float)$sale['pct_value'] / 100));
    }
    $done = update('transactions', ['locked' => 2], ['id' => $_POST['p_id_hidden']]);
    messageNew("success", $pageURL, "Transferred To Bill!");
}
if (isset($_POST['recordSubmit'])) {
    $sale_id = mysqli_real_escape_string($connect, $_POST['sale_id']);
    $item_id = mysqli_real_escape_string($connect, $_POST['item_id']);
    if ($item_id > 0) {
        $data = array(
            'item_id' => mysqli_real_escape_string($connect, $_POST['item_id']),
            'description' => mysqli_real_escape_string($connect, $_POST['description']),
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
            'opr' => mysqli_real_escape_string($connect, $_POST['opr']),
            'tax_percent' => mysqli_real_escape_string($connect, $_POST['tax_percent']),
            'tax_amount' => mysqli_real_escape_string($connect, $_POST['tax_amount']),
            'total_with_tax' => mysqli_real_escape_string($connect, $_POST['total_with_tax']),
            'name' => mysqli_real_escape_string($connect, $_POST['name']),
            'details1' => mysqli_real_escape_string($connect, $_POST['details1']),
            'details2' => mysqli_real_escape_string($connect, $_POST['details2']),
            'commission_percent' => mysqli_real_escape_string($connect, $_POST['commission_percent']),
            'commission_amount' => mysqli_real_escape_string($connect, $_POST['commission_amount']),
            'additional_expense' => mysqli_real_escape_string($connect, $_POST['additional_expense']),
            'final_amount' => mysqli_real_escape_string($connect, (isset($_POST['total_with_tax']) && !empty($_POST['total_with_tax']) ? $_POST['total_with_tax'] : $_POST['final_amount']))
        );
        $data['sale_id'] = $sale_id;
        if (isset($_POST['updateId'])) {
            $done = update('commission_items', $data, ['id' => $_POST['updateId']]);
        } else {
            $done = insert('commission_items', $data);
        }
        if ($done) {
            $item_id_ = $connect->insert_id;
            $info['type'] = 'success';
            $info['msg'] = ' Success!. ';
        }
    }
    messageNew($info['type'], $pageURL, $info['msg']);
}
if (isset($_GET['t_id']) && is_numeric($_GET['t_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $t_id = mysqli_real_escape_string($connect, $_GET['t_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($t_id); });</script>";
}


if (isset($_POST['ttrFirstSubmit'])) {
    unset($_POST['ttrFirstSubmit']);
    $post_json = json_encode($_POST);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['transfer_amount']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    if (!empty($final_amount)) {
        $details = mysqli_real_escape_string($connect, $_POST['details']) . " | Amount: $amount " . $_POST['currency1'] . " " . $_POST['opr'] . " " . $_POST['rate'] . ' = ' . $final_amount . " " . $_POST['currency2'];
    } else {
        $details = mysqli_real_escape_string($connect, $_POST['details']) . " | Amount: $amount ";
    }
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $p_sr = mysqli_real_escape_string($connect, $_POST['p_sr']);
    $type_post = mysqli_real_escape_string($connect, $_POST['type']);
    $url = $pageURL . '?t_id=' . $p_id;
    $type = ' S.' . ucfirst(substr($type_post, 0, '1'));
    $transfered_from = 'sale_' . $type_post . '_' . $_POST['com_item_id'];
    $r_type = 'Business';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQuery = fetch('transactions', array('id' => $p_id));
        $p_data = mysqli_fetch_assoc($pQuery);
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
        $str = " Sale # " . $p_sr;
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
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = !empty($final_amount) ? $final_amount : $amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = !empty($final_amount) ? $final_amount : $amount;
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
                    $dataArray['amount'] = !empty($final_amount) ? $final_amount : $amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = !empty($final_amount) ? $final_amount : $amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $url .= '&view=1&viewId=' . $_POST['com_item_id'];
            $trans_level = isset($_POST['check_full_payment']) && $_POST['check_full_payment'] === 'true' ? 6 : 2;
            $preData = array('khaata_tr1' => $post_json, 'transfer_level' => 2, '`from`' => 'bill-transfer');
            $comItem = update('commission_items', ['transferred' => $post_json], ['id' => $_POST['com_item_id']]);
            $tlUpdated = update('transactions', $preData, array('id' => $p_id));
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
if (isset($_GET['DeleteOtherPaymentEntry'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id = $pays_id = $_GET['t_id'];
    $url_ = "sales-commission-form?view=1&t_id=" . $id;
    $r_ids = explode('~', mysqli_real_escape_string($connect, $_GET['rids']));
    $done = mysqli_query($connect, "DELETE FROM `purchase_pays` WHERE id='$pays_id'");
    foreach ($r_ids as $r_id) {
        $done = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id='$r_id'");
    }
    if ($done) {
        $msg = " Deleted! #" . $_GET['id'];
        $type = "success";
    }
    message($type, $url_, $msg);
}
if (isset($_POST['transferToFinal'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('transfer_level' => 6, '`from`' => 'sale-commission');
    // $data = array('transfer' => 2, 't_date2' => date('Y-m-d'));
    $locked = update('transactions', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Transferred to Full Payment Form. ';
    }
    message($type, $pageURL, $msg);
}

if (isset($_POST['PaymentSubmit'])) {
    unset($_POST['PaymentSubmit']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['p_acc_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['p_acc_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['s_acc_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['s_acc_id']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']) . " | Amount: $amount " . $_POST['currency1'] . " " . $_POST['opr'] . " " . $_POST['rate'] . ' = ' . $final_amount . " " . $_POST['currency2'];
    $bill_id = mysqli_real_escape_string($connect, $_POST['id']);
    // $type_post = "P/S Expenses";
    $url = $pageURL . '?view=1t_id' . $_POST['id'] . '&editId=' . $bill_id;
    $type = 'Other Amt';
    $transfered_from = 'sales-commission-form';
    $r_type = 'Other Amt';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $bill_id,
            'user_id' => $_SESSION['userId'],
            'username' => $_SESSION['username'],
            'r_date' => $transfer_date,
            'roznamcha_no' => $bill_id,
            'r_name' => $type,
            'r_no' => $bill_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Other Amt # " . $bill_id;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = 1 + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
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
    // message($msgType, $url, $msg);
}
?>
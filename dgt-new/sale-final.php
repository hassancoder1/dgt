<?php
$page_title = 'FBill Payment';
$pageURL = 'sale-final';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `transactions` WHERE p_s='s'";
$conditions = []; // Store all conditions here
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('sale-final');
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
}
$sql .= " AND locked IN ('1', '2')  AND transfer_level >= '6'";

$sql .= " ORDER BY id DESC";
if (count($print_filters) > 0) {
    $pageURL .= "?";
    foreach ($print_filters as $filter) {
        $pageURL .= '&' . $filter;
    }
} else {
    $pageURL .= '?is_tranferred=1';
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
            <?php echo addNew('sale-add', '', 'btn-sm'); ?>
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
                                <!-- <th>AMOUNT</th>
                                <th>Advance Payment</th> Updated Column
                                <th>Remaining Payment</th> Updated Column -->
                                <th class="text-success">Grand Total</th> <!-- Updated Column -->
                                <th class="text-danger">Balance</th>
                                <th>Transferred From</th>
                                <th>ROAD</th>
                                <th>Loading Date</th>
                                <th>Receiving Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $purchases = mysqli_query($connect, $sql);
                            $row_count = $p_qty_total = $p_kgs_total = 0;
                            $i = 1;

                            while ($purchase = mysqli_fetch_assoc($purchases)) {
                                $id = $purchase['id'];
                                $_fields_single = transactionSingle($id);
                                $is_doc = $purchase['is_doc'];
                                $locked = $purchase['locked'];

                                // Decode the payments JSON field
                                $payments = $purchase['payments'];
                                $payments = json_decode($payments, true);
                                // // Check if full_advance equals 'advance'
                                // if (isset($payments['full_advance']) && $payments['full_advance'] != 'advance') {
                                //     continue; // Skip this entry if not an advance payment
                                // }

                                $cntrs = purchaseSpecificData($id, 'purchase_rows');
                                $totals = purchaseSpecificData($id, 'product_details');
                                $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);

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

                                // $rowColor = $locked == 0 ? ($is_doc == 0 ? ' text-danger ' : ' text-warning ') : '';
                                $trans_from = ucwords(str_replace('sale-', '', $purchase['from']));
                                $type = '';
                                $my_amount = '';
                                if ($purchase['from'] === 'sale-advance') {
                                    $type = 'adv';
                                    $my_amount = $payments['partial_amount1'];
                                } elseif ($purchase['from'] === 'sale-remaining') {
                                    $type = 'rem';
                                    $my_amount = $payments['partial_amount2'];
                                } elseif ($purchase['from'] === 'sale-credit') {
                                    $type = 'crdt';
                                    $my_amount = $payments['p_total_amount'];
                                } elseif ($purchase['from'] === 'bill-transfer') {
                                    $type = '';
                                    $trans_from = "Bill Transfer";
                                }
                                if ($purchase['from'] !== 'bill-transfer' && $purchase['from'] !== 'sale-commission') {
                                    $paid_final = purchaseSpecificData($id, $type . '_paid_total', 'amount');
                                    $paid_final = (float)$paid_final;
                                    $bal = (float)$my_amount - $paid_final;
                                    $bal = $bal < 0.5 ? 0 : $bal;

                                    // Determine the row color based on the conditions
                                    if ($paid_final <= 0) {
                                        $rowColor = 'text-danger'; // Red color for zero or near zero advance paid
                                    } elseif ($bal == 0) {
                                        $rowColor = 'text-dark'; // No color if balance is zero
                                    } elseif ($paid_final > 0) {
                                        $rowColor = 'text-warning'; // Warning color if there's some total
                                    } else {
                                        $rowColor = ''; // Default case (no color)
                                    }
                                } else {
                                    $rowColor = '';
                                    $bal = (int)$payments['p_total_amount'] - (int)$payments['p_total_amount'];
                                    if ($purchase['from'] === 'sale-commission') {
                                        $com_items = fetch('commission_items', ['sale_id' => $id]);
                                        $totalPaid = $totalAmt = 0;
                                        while ($com_item = mysqli_fetch_assoc($com_items)) {
                                            $totalAmt += $com_item['final_amount'];
                                            $totalPaid += !empty($com_item['transferred']) ? json_decode($com_item['transferred'], true)['transfer_amount'] : 0;
                                        }
                                        $bal = $totalAmt - $totalPaid;
                                    }
                                }
                            ?>
                                <tr class="text-nowrap">
                                    <td class="pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $id; ?>)"
                                        data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                        <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $purchase['sr']; ?>
                                        <?php echo in_array($locked, [1, 2]) ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                                    </td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']); ?></td>
                                    <td class="s_khaata_id_row <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['dr_acc']); ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['dr_acc_name']; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                                    <!-- <td class="<?php echo $rowColor; ?>">
                                        <?php if ($cntrs > 0) {
                                            echo $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                        } ?>
                                    </td> -->

                                    <!-- New Advance Payment column displaying partial_amount1 -->
                                    <!-- <td class="<?php echo $rowColor; ?>">
                                        <?php echo isset($payments['partial_amount1']) ? $payments['partial_amount1'] : "No Advance Alloted"; ?>
                                    </td>
                                    <td class="<?php echo $rowColor; ?>">
                                        <?php echo isset($payments['partial_amount2']) ? $payments['partial_amount2'] : "No Advance Alloted"; ?>
                                    </td> -->
                                    <td class="text-success"><?= $purchase['from'] === 'sale-commission' ? $totalAmt : round((float)$payments['p_total_amount'], 2); ?></td>
                                    <td class="text-danger"><?= round($bal, 2); ?></td>
                                    <td class="<?php echo $rowColor; ?>"><?= $trans_from; ?></td> <!-- Transferred From -->

                                    <?php if ($sea_road == '') { ?>
                                        <td class="<?php echo $rowColor; ?>" colspan="3"></td>
                                    <?php } else { ?>
                                        <td class="<?php echo $rowColor; ?>"><?php echo $sea_road; ?></td>
                                        <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_sr['l_date']); ?></td>
                                        <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_sr['r_date']); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php
                                $row_count++;
                            }
                            ?>
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
                <h5 class="modal-title" id="staticBackdropLabel">SALE FINAL</h5>
                <a href="<?php echo $mypageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewPurchase(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSaleFinal.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "sale-remaining"
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

<script>
    $(document).ready(function() {
        // Function to get the query parameter value
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get the value of 's_khaata_id' parameter from the URL
        var s_khaata_id = getQueryParameter('s_khaata_id').toUpperCase();

        // Iterate over all the <td> elements with class 's_khaata_id_row'
        $('td.s_khaata_id_row').each(function() {
            // Get the text content of the current <td>
            var cellText = $(this).text().trim();
            // If the text doesn't match the 's_khaata_id' parameter, hide the parent <tr>
            if (cellText !== s_khaata_id && s_khaata_id !== '') {
                $(this).closest('tr').hide();
            }
        });
    });
</script>
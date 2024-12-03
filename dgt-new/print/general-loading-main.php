<?php
$page_title = 'G. Loading';
$pageURL = 'general-loading-main';
require("../connection.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $p_id = $sea_road = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `transactions` WHERE type='booking'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('general-loading-main');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_id;
        $conditions[] = "id = '$p_id'";
    }
    if (isset($_GET['start']) && !empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        $conditions[] = "_date >= '$start_print'";
    }
    if (isset($_GET['end']) && !empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        $conditions[] = "_date <= '$end_print'";
    }
    if (isset($_GET['is_transferred']) && $_GET['is_transferred'] !== '') {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $print_filters[] = "is_transferred=" . $is_transferred;
        if ($is_transferred === '1') {
            $conditions[] = "locked = '1'";
        } elseif ($is_transferred === '0') {
            $conditions[] = "locked = '0'";
        }
    }
    if (isset($_GET['acc_no']) && !empty($_GET['acc_no'])) {
        $acc_no = mysqli_real_escape_string($connect, $_GET['acc_no']);
        $print_filters[] = 'acc_no=' . $acc_no;
    }
    if (isset($_GET['acc_name']) && !empty($_GET['acc_name'])) {
        $acc_name = mysqli_real_escape_string($connect, $_GET['acc_name']);
        $print_filters[] = 'acc_name=' . $acc_name;
    }
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $page = mysqli_real_escape_string($connect, $_GET['page']);
        $print_filters[] = 'page=' . $page;
    }
    if (isset($_GET['sea_road']) && !empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(sea_road, '$.sea_road') = '$sea_road'";
    }
}
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
} else {
    $sql .= " AND locked = '1' AND transfer_level >= '2'";
}
$sql .= " ORDER BY id DESC LIMIT $start_from, $results_per_page";
$purchases = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print General Loading <?= $page >= 1 ? " - Page $page" : ''; ?></title>

    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    echo "</style>";
    ?>
    <style>
        @media print {
            .hide-on-print {
                display: none;
            }
        }
    </style>
</head>

<body class="mx-2">
    <div class="bg-white mt-3">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                General Loading
                <span class="text-muted" style="font-size: 12px; display: block;">
                    <?php
                    $applied_filters = [];
                    if ($p_id) $applied_filters[] = "P# $p_id";
                    if ($start_print || $end_print) $applied_filters[] = "From $start_print to $end_print";
                    if ($type) $applied_filters[] = "Purchase Type: $type";
                    if ($sea_road) $applied_filters[] = "Sea/Road: $sea_road";
                    if ($is_transferred) $applied_filters[] = "Transferred: " . ($is_transferred == '1' ? "YES" : "NO");
                    if ($acc_no) $applied_filters[] = "Acc No: $acc_no";
                    if (count($applied_filters) > 0) {
                        echo " | Applied Filters: " . implode(", ", $applied_filters);
                    }
                    ?>
                    <?php if (isset($page)) { ?>
                        | Page: <?php echo $page; ?>
                    <?php } ?>
                </span>
            </h1>
            <div class="d-flex gap-2">
                <div>
                    <button class="btn btn-sm btn-dark hide-on-print" onclick="window.location.href = '/'"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-success btn-sm hide-on-print" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-4" id="printSection">
            <table class="table table-bordered">
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
                        <th>SEA/ROAD</th>
                        <th>L. DATE</th>
                        <th>R. DATE</th>
                    </tr>
                </thead>
                <tbody id="RecordsTable">
                    <?php
                    $generalLoadingQuery = "SELECT * FROM general_loading";
                    $generalLoadingResult = mysqli_query($connect, $generalLoadingQuery);
                    $generalLoadingData = [];
                    while ($loading = mysqli_fetch_assoc($generalLoadingResult)) {
                        $p_id = $loading['p_id'];
                        if (!isset($generalLoadingData[$p_id])) {
                            $generalLoadingData[$p_id] = [];
                        }
                        $generalLoadingData[$p_id][] = $loading;
                    }

                    // Process each purchase record
                    $purchases = mysqli_query($connect, $sql);
                    $row_count = $p_qty_total = $p_kgs_total = 0;
                    $i = 1;

                    while ($purchase = mysqli_fetch_assoc($purchases)) {
                        $id = $purchase['id'];
                        $_fields_single = transactionSingle($id);
                        $is_doc = $purchase['is_doc'];
                        $locked = $purchase['locked'];
                        $payments = json_decode($purchase['payments'], true);
                        $cntrs = purchaseSpecificData($id, 'purchase_rows');
                        $totals = purchaseSpecificData($id, 'product_details');
                        $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
                        $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                        $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];

                        // Sea/Road information
                        $sea_road = '';
                        $sea_road_array = json_decode(getSeaRoadArray($id));
                        $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                        if (!empty($sea_road_array)) {
                            $sea_road = $sea_road_array->sea_road ?? '';
                            $_fields_sr = $sea_road == 'sea' ? [
                                'l_country' => $sea_road_array->l_country,
                                'l_date' => $sea_road_array->l_date,
                                'r_country' => $sea_road_array->r_country,
                                'r_date' => $sea_road_array->r_date
                            ] : [
                                'l_country' => $sea_road_array->l_country_road,
                                'l_date' => $sea_road_array->l_date_road,
                                'r_country' => $sea_road_array->r_country_road,
                                'r_date' => $sea_road_array->r_date_road
                            ];
                        }
                        $sr1_totals = $all_totals = ['quantity' => 0];
                        $matches_found = false;

                        if (isset($generalLoadingData[$id])) {
                            $matches_found = true;
                            foreach ($generalLoadingData[$id] as $loading) {
                                if ($loading['sr_no'] == 1) {
                                    $sr1_info = json_decode($loading['gloading_info'], true);
                                    $sr1_totals['quantity'] = $sr1_info['total_quanity_no'] ?? 0;
                                }
                                $goods_details = json_decode($loading['goods_details'], true);
                                $all_totals['quantity'] += $goods_details['quantity_no'] ?? 0;
                            }
                        }
                        if (!$matches_found) {
                            $rowColor = 'text-danger';
                        } elseif ($sr1_totals['quantity'] > $all_totals['quantity']) {
                            $rowColor = 'text-warning';
                        } elseif ($sr1_totals['quantity'] == $all_totals['quantity']) {
                            $rowColor = 'text-dark';
                        }

                    ?>
                        <tr class="text-nowrap">
                            <td class="pointer <?php echo $rowColor; ?>">
                                <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $id; ?>
                                <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                            </td>
                            <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']); ?></td>
                            <td class="acc_no <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['cr_acc']); ?></td>
                            <td class="acc_name <?php echo $rowColor; ?>"><?php echo $_fields_single['cr_acc_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                            <?php if ($sea_road == '') { ?>
                                <td class="<?php echo $rowColor; ?>" colspan="3"></td>
                            <?php } else { ?>
                                <td class="<?php echo $rowColor; ?>"><?php echo $sea_road; ?></td>
                            <?php
                                echo '<td class="' . $rowColor . '">' .  my_date($_fields_sr['l_date']) . '</td>';
                                echo '<td class="' . $rowColor . '">' . my_date($_fields_sr['r_date']) . '</td>';
                            } ?>
                        </tr>
                    <?php
                        $row_count++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
        $(document).ready(function() {
            var acc_no = getQueryParameter('acc_no') ? getQueryParameter('acc_no').toUpperCase() : '';
            var acc_name = getQueryParameter('acc_name') ? getQueryParameter('acc_name').toUpperCase() : '';
            $('tbody tr').each(function() {
                var rowAccNo = $(this).find('td.acc_no').text().trim().toUpperCase();
                var rowAccName = $(this).find('td.acc_name').text().trim().toUpperCase();
                if ((acc_no && rowAccNo !== acc_no) || (acc_name && !rowAccName.includes(acc_name))) {
                    $(this).hide();
                }
            });
        });
    </script>
</body>

</html>
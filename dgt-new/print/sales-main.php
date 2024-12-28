<?php
$page_title = 'Purchases';
$pageURL = 'purchases-main';
include("../connection.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $branch = $p_sr = $payment_type = $sea_road = $country = $country_type = $date_type = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `transactions` WHERE p_s='s'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('purchases-main');
    $is_search = true;

    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
        $conditions[] = "sr = '$p_sr'";
    }
    $date_type = isset($_GET['date_type']) ? $_GET['date_type'] : '';
    $print_filters[] = 'date_type=' . $date_type;
    if (!empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        if ($date_type == 'purchase') {
            $conditions[] = "_date >= '$start_print'";
        }
    }
    if (!empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        if ($date_type == 'purchase') {
            $conditions[] = "_date <= '$end_print'";
        }
    }
    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['type']);
        $print_filters[] = 'type=' . $type;
        $conditions[] = "type = '$type'";
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
    $country_type = isset($_GET['country_type']) ? $_GET['country_type'] : '';
    if (isset($_GET['country']) && !empty($_GET['country'])) {
        $country = mysqli_real_escape_string($connect, $_GET['country']);
        $print_filters[] = 'country=' . $country;
    }
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $page = mysqli_real_escape_string($connect, $_GET['page']);
        $print_filters[] = 'page=' . $page;
    }
    if (isset($_GET['branch']) && !empty($_GET['branch'])) {
        $branch = mysqli_real_escape_string($connect, $_GET['branch']);
        $print_filters[] = 'branch=' . $branch;
    }
    if (isset($_GET['sea_road']) && !empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(sea_road, '$.sea_road') = '$sea_road'";
    }
    if (isset($_GET['payment_type']) && !empty($_GET['payment_type'])) {
        $payment_type = mysqli_real_escape_string($connect, $_GET['payment_type']);
        $print_filters[] = 'payment_type=' . $payment_type;
        $conditions[] = "JSON_EXTRACT(payments, '$.full_advance') = '$payment_type'";
    }
}
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
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
    <title>Print Purchases <?= $page >= 1 ? " - Page $page" : ''; ?></title>

    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    include '../assets/fonts/lexend.css';
    echo "</style>";
    ?>
    <style>
        * {
            font-family: 'Lexend', serif;
        }

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
                Sales
                <span class="text-muted d-block">
                    <?php
                    $applied_filters = [];
                    if ($p_sr) $applied_filters[] = "P# $p_sr";
                    if ($date_type) $applied_filters[] = "Date Type: " . ucfirst($date_type);
                    if ($start_print && $end_print) $applied_filters[] = "From $start_print to $end_print";
                    if ($type) $applied_filters[] = "Purchase Type: $type";
                    if ($branch) $applied_filters[] = "Branch: $branch";
                    if ($payment_type) $applied_filters[] = "Payment Type: $payment_type";
                    if ($sea_road) $applied_filters[] = "Sea/Road: $sea_road";
                    if ($is_transferred !== '') $applied_filters[] = "Transfer Status: " . ($is_transferred ? 'Transferred' : 'Not Transferred');
                    if ($acc_no) $applied_filters[] = "Acc. No.: $acc_no";
                    if ($country_type) $applied_filters[] = "Country Type: $country_type";
                    if ($country) $applied_filters[] = "Country: $country";

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
                    <button class="btn btn-sm btn-dark hide-on-print" onclick="window.location.href = '/purchases'"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-success btn-sm hide-on-print" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <style>
            * {
                font-size: 10px;
            }
        </style>
        <div class="mt-4" id="RecordsTable">
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
                        <th>AMOUNT</th>
                        <th>PAYMENT TYPE</th>
                        <th>COUNTRY</th>
                        <th>Delivery Terms</th>
                        <th>ROAD</th>
                        <th>LOADING COUNTRY | DATE</th>
                        <th>RECEIVING COUNTRY | DATE</th>
                        <th>DOCS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $purchases = mysqli_query($connect, $sql);
                    $row_count = $p_qty_total = $p_kgs_total = 0;
                    while ($purchase = mysqli_fetch_assoc($purchases)) {
                        $id = $purchase['id'];
                        $_fields_single = transactionSingle($id);
                        $is_doc = $purchase['is_doc'];
                        $locked = $purchase['locked'];
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
                        $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                        $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                        $rowColor = '';
                        if ($locked == 0) {
                            $rowColor = $is_doc == 0 ? ' text-danger ' : ' text-warning ';
                        } ?>
                        <tr class="text-nowrap">
                            <td class="pointer <?php echo $rowColor; ?>">
                                <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $purchase['sr'];
                                echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                            <td class="<?php echo $rowColor; ?> branch"><?php echo branchName($_fields_single['branch_id']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']);; ?></td>
                            <td class="acc_no <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['cr_acc']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['cr_acc_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                            <td class="<?php echo $rowColor; ?>">
                                <?php if ($cntrs > 0) {
                                    echo $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                } ?>
                            </td>
                            <td class="<?php echo $rowColor; ?> px-2"><?= isset($_fields_single['payment_details']->full_advance) ? ucwords($_fields_single['payment_details']->full_advance) : "No Payment Details Available"; ?></td>
                            <td class="<?php echo $rowColor; ?>"><span class="purchase_country"><?php echo $purchase['country']; ?></span></td>
                            <td class="<?php echo $rowColor; ?>"><?php echo $purchase['delivery_terms']; ?></td>
                            <?php
                            if ($sea_road == '') {
                                echo '<td class="<?php echo $rowColor; ?>" colspan="3"></td>';
                            } else {
                                echo '<td class="' . $rowColor . '">' . $sea_road . '</td>';
                                echo '<td class="' . $rowColor . '"><span class="loading_country">' . $_fields_sr['l_country'] . '</span><span class="loading_date"> ' . $_fields_sr['l_date'] . '</span></td>';
                                echo '<td class="' . $rowColor . '"><span class="receiving_country">' . $_fields_sr['r_country'] . '</span><span class="receiving_date"> ' . $_fields_sr['r_date'] . '</span></td>';
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
        </div>
    </div>
    <script>
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
        $(document).ready(function() {
            var acc_no = getQueryParameter('acc_no') ? getQueryParameter('acc_no').toUpperCase() : '';
            var dateType = getQueryParameter('date_type') ? getQueryParameter('date_type') : '';
            var countryType = getQueryParameter('country_type') ? getQueryParameter('country_type') : '';
            var country = getQueryParameter('country') ? getQueryParameter('country') : '';
            var startDate = getQueryParameter('start') ? getQueryParameter('start') : '';
            var endDate = getQueryParameter('end') ? getQueryParameter('end') : '';
            var branch = getQueryParameter('branch') ? getQueryParameter('branch') : '';
            var start = startDate ? new Date(startDate) : null;
            var end = endDate ? new Date(endDate) : null;
            $('tbody tr').each(function() {
                var rowAccNo = $(this).find('td.acc_no').text().trim().toUpperCase();
                var rowBranch = $(this).find('td.branch').text().trim().toUpperCase();
                if (countryType !== '') {
                    var rowCountry = $(this).find('td span.' + countryType + '_country').text().trim();
                }
                var rowLoadingDateStr = $(this).find('td span.loading_date').text().trim();
                var rowReceivingDateStr = $(this).find('td span.receiving_date').text().trim();
                console.log(countryType, country, rowCountry);
                var hideRow = false;
                if (acc_no && rowAccNo !== acc_no) {
                    hideRow = true;
                }
                if (countryType && rowCountry !== country) {
                    hideRow = true;
                }
                if (dateType === 'loading' && rowLoadingDateStr) {
                    var rowLoadingDate = new Date(rowLoadingDateStr);
                    if (isNaN(rowLoadingDate) || (start && rowLoadingDate < start) || (end && rowLoadingDate > end)) {
                        hideRow = true;
                    }
                } else if (dateType === 'receiving' && rowReceivingDateStr) {
                    var rowReceivingDate = new Date(rowReceivingDateStr);
                    if (isNaN(rowReceivingDate) || (start && rowReceivingDate < start) || (end && rowReceivingDate > end)) {
                        hideRow = true;
                    }
                }
                if (branch !== '' && branch !== rowBranch) {
                    hideRow = true;
                }
                if (hideRow) {
                    $(this).hide();
                }
            });
        });

        function toggleDates() {
            const selectedValue = $('#date_type').val();
            if (selectedValue === "") {
                $('#startInput, #endInput').addClass('d-none');
            } else {
                $('#startInput, #endInput').removeClass('d-none');
            }
        }

        function toggleCountry() {
            const selectedValue = $('#country_type').val();
            if (selectedValue === "") {
                $('#countryInput').addClass('d-none');
            } else {
                $('#countryInput').removeClass('d-none');
            }
        }
    </script>
</body>

</html>
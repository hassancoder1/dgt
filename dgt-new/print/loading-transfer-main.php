<?php
$page_title = 'G. Loading';
$pageURL = 'loading-transfer-main';
require("../connection.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_id = $blSearch = $sea_road = $transferred = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading`";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('loading-transfer-main');
    $is_search = true;
    if (!empty($_GET['blSearch'])) {
        $blSearch = mysqli_real_escape_string($connect, $_GET['blSearch']);
        $print_filters[] = 'blSearch=' . $blSearch;
        $conditions[] = "bl_no='$blSearch'";
    }
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_id;
        $conditions[] = "p_id = '$p_id'";
    }
    $date_type = isset($_GET['date_type']) ? $_GET['date_type'] : '';
    $print_filters[] = 'date_type=' . $date_type;
    if (!empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        if ($date_type == 'loading') {
            $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_date') >= '$start_print'";
        } elseif ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_date') >= '$start_print'";
        }
    }
    if (!empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        if ($date_type == 'loading') {
            $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_date') <= '$end_print'";
        } elseif ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_date') <= '$end_print'";
        }
    }
    if (!empty($_GET['p_type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['p_type']);
        $print_filters[] = 'p_type=' . $type;
        $conditions[] = "p_type = '$type'";
    }
    if (isset($_GET['transferred']) && $_GET['transferred'] !== '') {
        $transferred = mysqli_real_escape_string($connect, $_GET['transferred']);
        $print_filters[] = 'transferred=' . $transferred;
        $conditions[] = $transferred === 'yes' ? "JSON_EXTRACT(agent_details, '$.ag_acc_no') IS NOT NULL" : "JSON_EXTRACT(agent_details, '$.ag_acc_no') IS NULL";
    }
    if (!empty($_GET['acc_no'])) {
        $acc_no = mysqli_real_escape_string($connect, $_GET['acc_no']);
        $print_filters[] = 'acc_no=' . $acc_no;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.ag_acc_no') = '$acc_no'";
    }
    if (!empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(shipping_details, '$.transfer_by') = '$sea_road'";
    }
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
$sql .= " ORDER BY id DESC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$count_sql = "SELECT COUNT(id) AS total FROM `general_loading`" . (count($conditions) > 0 ? " WHERE " . implode(' AND ', $conditions) : "");
$count_result = mysqli_query($connect, $count_sql);
$total_pages = ceil(mysqli_fetch_assoc($count_result)['total'] / $results_per_page);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Loading Transfer <?= $page >= 1 ? " - Page $page" : ''; ?></title>
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
</head>

<body class="mx-2">
    <div class="bg-white mt-3">
    <div class="d-flex justify-content-between align-items-center w-100">
            <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                Loading Transfer
                <span class="text-muted" style="font-size: 12px; display: block;">
                    <?php
                    $applied_filters = [];
                    if ($p_id) $applied_filters[] = "P# $p_id";
                    if ($date_type) $applied_filters[] = "Date Type: " . ucfirst($date_type);
                    if ($start_print && $end_print) $applied_filters[] = "From $start_print to $end_print";
                    if ($type) $applied_filters[] = "Purchase Type: $type";
                    if ($sea_road) $applied_filters[] = "Sea/Road: $sea_road";
                    if ($blSearch) $applied_filters[] = "B/L Search: $blSearch";
                    if ($transferred) $applied_filters[] = "Agent Transferred: $transferred";
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
                    <button class="btn btn-sm btn-dark" onclick="window.location.href = '/loading-transfer'"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-success btn-sm" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-nowrap">
                        <th>P#</th>
                        <th>WareHouse</th>
                        <th>AG ID</th>
                        <th>AG NAME</th>
                        <th>L_DATE</th>
                        <th>L_Port/Border</th>
                        <th>R_DATE</th>
                        <th>R_Port/Border</th>
                        <th>B/L No.</th>
                        <th>Containers</th>
                        <th>Total QTY</th>
                        <th>T.G.KGS</th>
                        <th>T.N.KGS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($connect, $sql);
                    $containerCounts = $Loadings = $netWeights = $grossWeights = $quantityNos = [];
                    while ($one = mysqli_fetch_assoc($result)) {
                        $gloadingInfo = json_decode($one['gloading_info'], true);
                        if (isset($gloadingInfo['child_ids']) && $gloadingInfo['child_ids'] !== null) {
                            $Loadings[] = $one;
                        }

                        $blNo = $one['bl_no'];
                        if (!isset($containerCounts[$blNo])) {
                            $containerCounts[$blNo] = 1;
                            $netWeights[$blNo] = 0;
                            $grossWeights[$blNo] = 0;
                            $quantityNos[$blNo] = 0;
                        } else {
                            $containerCounts[$blNo]++;
                        }
                        $goodsDetails = json_decode($one['goods_details'], true);
                        $netWeights[$blNo] += $goodsDetails['net_weight'];
                        $grossWeights[$blNo] += $goodsDetails['gross_weight'];
                        $quantityNos[$blNo] += $goodsDetails['quantity_no'];
                    }

                    $row_count = $p_qty_total = $p_kgs_total = 0;
                    $rowColor = '';
                    $locked = 0;
                    $pIdCounts = [];
                    foreach ($Loadings as $SingleLoading) {
                        $id = $SingleLoading['id'];
                        $pId = $SingleLoading['p_id'];

                        if (empty($SingleLoading['agent_details'])) {
                            $rowColor = 'text-danger';
                            $locked = 0;
                        } elseif (isset(json_decode($SingleLoading['agent_details'], true)['transferred'])) {
                            $transferred = json_decode($SingleLoading['agent_details'], true)['transferred'];
                            $rowColor = $transferred === true ? 'text-dark' : 'text-warning';
                            $locked = $transferred === true ? 1 : 0;
                        }

                        if (!isset($pIdCounts[$pId])) {
                            $pIdCounts[$pId] = 1;
                        } else {
                            $pIdCounts[$pId]++;
                        }
                        $pIdDisplayCount = $pIdCounts[$pId];
                    ?>

                        <tr class="text-nowrap">
                            <td class="pointer <?= $rowColor; ?>">
                                <?php echo '<b>P#', $pId . "</b> (" . $pIdDisplayCount . ")"; ?>
                                <?php echo $locked ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                            </td>
                            <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['cargo_transfer_warehouse']) ? json_decode($SingleLoading['agent_details'], true)['cargo_transfer_warehouse'] : ''; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_id'] : ''; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_name'] : ''; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= my_date(json_decode($SingleLoading['loading_details'], true)['loading_date']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_port_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= my_date(json_decode($SingleLoading['receiving_details'], true)['receiving_date']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_port_name']; ?></td>
                            <td class="<?php echo $rowColor; ?> fw-bold"><?= $SingleLoading['bl_no']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $containerCounts[$SingleLoading['bl_no']]; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $quantityNos[$SingleLoading['bl_no']]; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $grossWeights[$SingleLoading['bl_no']]; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $netWeights[$SingleLoading['bl_no']]; ?></td>
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
        function toggleDates() {
            const selectedValue = $('#date_type').val();
            if (selectedValue === "") {
                $('#startInput, #endInput').addClass('d-none');
            } else {
                $('#startInput, #endInput').removeClass('d-none');
            }
        };
    </script>
</body>

</html>
<?php
$page_title = 'Loading Transfer';
$pageURL = 'loading-transfer';
include("header.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_sr = $blSearch = $sea_road = $transferred = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 100;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading`";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('loading-transfer');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
        $conditions[] = "p_sr = '$p_sr'";
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
    if (!empty($_GET['blSearch'])) {
        $blSearch = mysqli_real_escape_string($connect, $_GET['blSearch']);
        $print_filters[] = 'blSearch=' . $blSearch;
        $conditions[] = "bl_no='$blSearch'";
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
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
            Loading Transfer
        </h1>
        <div class="d-flex gap-2">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center pagination-sm">
                    <?php
                    $current_url = $_SERVER['REQUEST_URI'];
                    $url_parts = parse_url($current_url);
                    parse_str($url_parts['query'] ?? '', $query_params);
                    unset($query_params['page']);
                    $base_url = $url_parts['path'] . '?' . http_build_query($query_params);

                    $count_sql = "SELECT COUNT(id) AS total FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.child_ids') IS NOT NULL";
                    if (count($conditions) > 0) {
                        $count_sql .= " AND " . implode(' AND ', $conditions);
                    }
                    $count_result = mysqli_query($connect, $count_sql);
                    $row = mysqli_fetch_assoc($count_result);
                    $total_pages = ceil($row['total'] / $results_per_page);

                    // Previous button
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Prev</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                    }

                    // Pagination logic with ellipsis
                    $max_displayed_pages = 5; // Maximum number of pages to display
                    if ($total_pages <= $max_displayed_pages) {
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active_class = ($i == $page) ? 'active' : '';
                            echo "<li class='page-item $active_class'><a class='page-link' href='" . $base_url . "&page=$i'>$i</a></li>";
                        }
                    } else {
                        // Always display the first page
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=1'>1</a></li>";
                        if ($page > 3) {
                            echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                        }

                        // Display pages around the current page
                        $start = max(2, $page - 1);
                        $end = min($total_pages - 1, $page + 1);
                        for ($i = $start; $i <= $end; $i++) {
                            $active_class = ($i == $page) ? 'active' : '';
                            echo "<li class='page-item $active_class'><a class='page-link' href='" . $base_url . "&page=$i'>$i</a></li>";
                        }

                        // Always display the last page
                        if ($page < $total_pages - 2) {
                            echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                        }
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=$total_pages'>$total_pages</a></li>";
                    }

                    // Next button
                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                    }
                    ?>
                </ul>
            </nav>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="<?= $print_url; ?>" target="_blank">
                            <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')">
                            <i class="fas text-secondary fa-print me-2"></i> Print
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                            <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                            <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                            <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                            <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form name="datesSubmit" class="mt-2" method="get">
        <div class="input-group input-group-sm">
            <div class="form-group">
                <label for="p_id" class="form-label">P/S#</label>
                <input type="number" name="p_id" value="<?php echo $p_sr; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
            </div>
            <div class="form-group">
                <label for="date_type" class="form-label">Date Type</label>
                <select class="form-select form-select-sm" name="date_type" style="max-width:130px;" id="date_type" onchange="toggleDates()">
                    <option value="" <?= !in_array($date_type, ['loading', 'receiving']) ? 'selected' : ''; ?>>All</option>
                    <option value="loading" <?= $date_type == 'loading' ? 'selected' : ''; ?>>Loading</option>
                    <option value="receiving" <?= $date_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                </select>
            </div>
            <div class="form-group <?= !in_array($date_type, ['loading', 'receiving']) ? 'd-none' : ''; ?>" id="startInput">
                <label for="start" class="form-label">Start Date</label>
                <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
            </div>
            <div class="form-group <?= !in_array($date_type, ['loading', 'receiving']) ? 'd-none' : ''; ?>" id="endInput">
                <label for="end" class="form-label">End Date</label>
                <input type="date" name="end" value="<?php echo $end_print; ?>" id="end" class="form-control form-control-sm mx-2" style="max-width:160px;">
            </div>
            <div class="form-group">
                <label for="sea_road" class="form-label">SEA/ROAD</label>
                <select class="form-select form-select-sm" name="sea_road" style="max-width:130px;" id="sea_road">
                    <option value="" <?= !in_array($sea_road, ['sea', 'road']) ? 'selected' : ''; ?>>All</option>
                    <option value="sea" <?= $sea_road == 'sea' ? 'selected' : ''; ?>>by Sea</option>
                    <option value="road" <?= $sea_road == 'road' ? 'selected' : ''; ?>>by Road</option>
                </select>
            </div>
            <div class="form-group mx-1">
                <label for="blSearch" class="form-label">B/L Search</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:130px;" name="blSearch" placeholder="B/L Search" value="<?php echo $blSearch; ?>" id="blSearch">
            </div>
            <div class="form-group mx-1">
                <label for="transferred" class="form-label">AG Tranferred</label>
                <select class="form-select form-select-sm" name="transferred" style="max-width:180px;" id="transferred">
                    <option value="" <?= !in_array($transferred, ['yes', 'no']) ? 'selected' : ''; ?>>All</option>
                    <option value="yes" <?= $transferred == 'yes' ? 'selected' : ''; ?>>YES</option>
                    <option value="no" <?= $transferred == 'no' ? 'selected' : ''; ?>>NO</option>
                </select>
            </div>
            <div class="form-group">
                <label for="acc_no" class="form-label">Acc No.</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:90px;" name="acc_no" placeholder="Acc No." value="<?php echo $acc_no; ?>" id="acc_no">
            </div>
            <div class="form-group mt-4 pt-1">
                <?= $remove ? '<a href="' . $pageURL . '" class="btn btn-sm btn-danger"><i class="fa fa-sync-alt"></i></a>' : ''; ?>
                <button type="submit" class="btn btn-sm btn-success">Search</button>
            </div>
        </div>
    </form>

    <div class="table-responsive mt-4" id="RecordsTable">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>P/S#</th>
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
                    $myAgent = json_decode($SingleLoading['agent_details'], true);
                    $id = $SingleLoading['id'];
                    $pId = $SingleLoading['p_id'];
                    $p_sr = $SingleLoading['p_sr'];
                    if (empty($SingleLoading['agent_details'])) {
                        $rowColor = 'text-danger';
                        $locked = 0;
                    } elseif (isset($myAgent['transferred'])) {
                        $transferred = $myAgent['transferred'];
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
                        <td class="pointer text-uppercase <?= $rowColor; ?>" onclick="window.location.href= '?view=1&id=<?= $SingleLoading['id']; ?>';">
                            <?php echo '<b>' . $SingleLoading['type'] . '#', $p_sr . "</b> (" . $pIdDisplayCount . ")"; ?>
                            <?php echo $locked ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                        </td>
                        <td class="<?php echo $rowColor; ?>"><?= $myAgent['cargo_transfer_warehouse'] ?? ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset($myAgent['ag_id']) ? (!empty($myAgent['ag_id']) ? $myAgent['ag_id'] : 'NOT EXIST') : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset($myAgent['ag_name']) ? (!empty($myAgent['ag_name']) ? $myAgent['ag_name'] : 'NOT EXIST') : ''; ?></td>
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
<?php include("footer.php"); ?>
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
        if (id) {
            $.ajax({
                url: 'ajax/viewLoadingTransfer.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "loading-transfer",
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your request. Please try again.');
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
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
<?php
if (isset($_POST['UpdatePermission'])) {
    $update_permission_ids = mysqli_real_escape_string($connect, $_POST['update_permission_ids']);
    $PermissionIDs = explode(',', $update_permission_ids);
    $idConditions = [];
    foreach ($PermissionIDs as $pair) {
        list($p_id, $sr_no, $t_type) = explode('-', trim($pair));
        $idConditions[] = "(p_id = '$p_id' AND sr_no = '$sr_no' AND type = '$t_type')";
    }
    $whereClause = implode(' OR ', $idConditions);
    $query = "SELECT id, agent_details FROM general_loading WHERE $whereClause";
    $result = mysqli_query($connect, $query);
    if ($result) {
        $updates = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $existingData = json_decode($row['agent_details'], true) ?? [];
            $existingData['permission_to_edit'] = $_POST['permission'];
            $updatedAgentDetails = mysqli_real_escape_string($connect, json_encode($existingData));
            $updates[] = "WHEN id = '$id' THEN '$updatedAgentDetails'";
        }
        if (!empty($updates)) {
            $updateQuery = "
                UPDATE general_loading
                SET agent_details = CASE " . implode(' ', $updates) . " ELSE agent_details END
                WHERE $whereClause
            ";
            $done = mysqli_query($connect, $updateQuery);
            if ($done) {
                $type = 'success';
                $msg = 'Agent Permission Updated!';
            } else {
                $type = 'danger';
                $msg = 'Update Failed!';
            }
            message($type, '', $msg);
        }
    } else {
        message('danger', '', 'No matching records found.');
    }
}
if (isset($_POST['TransferToAgent'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'loading-transfer?view=1&id=' . $_POST['openRecord'];
    $ag_transfer_ids = mysqli_real_escape_string($connect, $_POST['ag_transfer_ids']);
    $ag_id = mysqli_real_escape_string($connect, $_POST['ag_id']);
    $transferPairs = explode(',', $ag_transfer_ids);
    if (isset($_POST['agent_exists']) && $_POST['agent_exists'] === 'no') {
        $_POST['ag_acc_no'] = $_POST['ag_name'] = $_POST['ag_id'] = $_POST['ag_row_id'] = '';
    }
    $agentDetails = [
        'agent_exist' => $_POST['agent_exist'],
        'ag_acc_no' => $_POST['ag_acc_no'],
        'ag_name' => $_POST['ag_name'],
        'ag_id' => $_POST['ag_id'],
        'row_id' => $_POST['ag_row_id'],
        'cargo_transfer_warehouse' => $_POST['cargo_transfer'],
        'transferred' => true,
    ];
    $idConditions = [];
    foreach ($transferPairs as $index => $pair) {
        list($p_sr, $sr_no, $t_type) = explode('-', trim($pair));
        $idConditions[] = "(p_sr = '$p_sr' AND sr_no = '$sr_no' AND type = '$t_type')";

        // |||||||||||||||||||||||||||||\
        $Ttempdata = mysqli_fetch_assoc(fetch('transactions', ['sr' => $p_sr, 'p_s' => $t_type]));
        $Ptempdata = mysqli_fetch_assoc(fetch('general_loading', ['p_sr' => $p_sr, 'sr_no' => $sr_no, 'type' => $t_type]));
        $tdata = array_merge(
            transactionSingle($Ttempdata['id']),
            ['sea_road_array' => json_decode($Ttempdata['sea_road'], true)] ?? [],
            ['notify_party_details' => json_decode($Ttempdata['notify_party_details'], true)] ?? [],
            ['third_party_bank' => json_decode($Ttempdata['third_party_bank'], true)] ?? [],
            ['reports' => json_decode($Ttempdata['reports'], true)] ?? []
        );
        $transferData = array_merge(
            json_decode($Ptempdata['loading_details'], true),
            json_decode($Ptempdata['receiving_details'], true),
            json_decode($Ptempdata['shipping_details'], true),
            ['warehouse_transfer' => $_POST['cargo_transfer']],
            isset($_POST['warehouse_entry'.$index]) ? ['sold_from' => [$_POST['warehouse_entry'.$index]]] : []
        );
        $dummyTmp = $Ptempdata;
        unset(
            $Ptempdata['loading_details'],
            $Ptempdata['receiving_details'],
            $Ptempdata['shipping_details'],
            $Ptempdata['agent_details'],
            $Ptempdata['gloading_info'],
            $Ptempdata['importer_details'],
            $Ptempdata['exporter_details'],
            $Ptempdata['notify_party_details'],
            $Ptempdata['goods_details']
        );
        $tempG = json_decode($dummyTmp['goods_details'], true);
        $ldata = array_merge(
            $Ptempdata,
            [
                'transfer' => $transferData,
                'info' => json_decode($dummyTmp['gloading_info'], true),
                'importer' => json_decode($dummyTmp['importer_details'], true),
                'exporter' => json_decode($dummyTmp['exporter_details'], true),
                'notify' => json_decode($dummyTmp['notify_party_details'], true),
                'good' => array_merge(
                    $tempG,
                    [
                        'goods_json' => array_merge(
                            $tempG['goods_json'],
                            [
                                'qty_no' => (isset($_POST['warehouse_entry'.$index]) ? 0 : $tempG['quantity_no']),
                                // 'qty_no' => $tempG['quantity_no'],

                                'qty_name' => $tempG['quantity_name'],
                                'total_kgs' => (isset($_POST['warehouse_entry'.$index]) ? 0 : $tempG['gross_weight']),
                                'net_kgs' => (isset($_POST['warehouse_entry'.$index]) ? 0 : $tempG['net_weight']),
                                // 'total_kgs' => $tempG['gross_weight'],
                                // 'net_kgs' => $tempG['net_weight'],
                                'rate1' => $tempG['rate'],
                                'empty_kgs' => $tempG['empty_kgs'],
                                'size' => $tempG['size'],
                                'brand' => $tempG['brand'],
                                'origin' => $tempG['origin'],
                                'goods_id' => $tempG['goods_id'],
                            ]
                        )
                    ]
                ),
                'agent' => $agentDetails,
            ]
        );
        $my_unique_code = $_POST['unique_code'] . $Ptempdata['id'];
        $CCWTables = ['data_copies'];
        $CCWTables = array_merge($CCWTables, (isset($_POST['vat_general']) && $_POST['vat_general'] === 'yes' ? ['vat_copies'] : []));
        foreach ($CCWTables as $myTable) {
            $CCWdata = [
                'data_for' => $_POST['cargo_transfer'],
                'unique_code' => $my_unique_code,
                'tdata' => mysqli_real_escape_string($connect, json_encode($tdata)),
                'ldata' => $ldata,
            ];
            if (!recordExists($myTable, ['unique_code' => $CCWdata['unique_code']])) {
                $CCWdata['ldata'] = mysqli_real_escape_string($connect, json_encode($CCWdata['ldata']));
                insert($myTable, $CCWdata);
            } else {
                $fetchedLdata = json_decode(mysqli_fetch_assoc(fetch($myTable, ['unique_code' => $CCWdata['unique_code']]))['ldata'], true);
                if (isset($fetchedLdata['edited']) && $fetchedLdata['edited'] === true) {
                    continue;
                }
                $sold_to_from_key = isset($fetchedLdata['transfer']['sold_to']) ? 'sold_to' : (isset($fetchedLdata['transfer']['sold_from']) ? 'sold_from' : '');
                if ($fetchedLdata['ldata']['good']['quantity_no'] !== $fetchedLdata['ldata']['good']['goods_json']['qty_no']) {
                    $CCWdata['ldata']['good']['goods_json'] = $fetchedLdata['good']['goods_json'];
                }
                $CCWdata['ldata']['good']['goods_json'] = $fetchedLdata['good']['goods_json'];
                if (!empty($sold_to_from_key)) {
                    $CCWdata['ldata']['transfer'][$sold_to_from_key] = $fetchedLdata['transfer'][$sold_to_from_key];
                }
                $CCWdata['ldata'] = mysqli_real_escape_string($connect, json_encode($CCWdata['ldata']));
                update($myTable, $CCWdata, ['unique_code' => $CCWdata['unique_code']]);
            }
        }
        if (isset($_POST['warehouse_entry'.$index])) {
            $p_unique_code = explode('~', $_POST['warehouse_entry'.$index])[0];
            $existingLdata = json_decode(
                mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $p_unique_code]))['ldata'],
                true
            );
            $existingLdata['transfer']['sold_to'][] = $my_unique_code . '~' . $tempG['goods_id'] . '~' . goodsName($tempG['goods_id']) . '~' . $tempG['quantity_no'] . '~' . $tempG['quantity_name'] . '~' . $tempG['gross_weight'] . '~' . $tempG['net_weight'] . '~' . $transferData['warehouse_transfer'] . '~' . $sr_no;
            $tempQtyNo = $tempG['quantity_no'];
            $existingLdata['good']['goods_json']['qty_no'] -= $tempQtyNo;
            $updatedGood = calcNewValues($existingLdata['good']['goods_json']['qty_no'], $existingLdata['good'], 'rems');
            $existingLdata['good'] = $updatedGood;
            update('data_copies', ['ldata' => mysqli_real_escape_string($connect, json_encode($existingLdata))], ['unique_code' => $p_unique_code]);
        }
    }
    $whereClause = implode(' OR ', $idConditions);

    $parentCheckQuery = "
        SELECT id, gloading_info, JSON_EXTRACT(gloading_info, '$.child_ids') AS child_ids
        FROM general_loading
        WHERE $whereClause AND JSON_EXTRACT(gloading_info, '$.child_ids') IS NOT NULL";
    $parentCheckResult = mysqli_query($connect, $parentCheckQuery);
    $isParent = ($parentCheckResult && mysqli_num_rows($parentCheckResult) > 0);
    $parentId = null;
    $billNumber = 0;
    $existingGloadingData = [];
    if ($isParent) {
        $row = mysqli_fetch_assoc($parentCheckResult);
        $parentId = $row['id'];
        $existingGloadingData = json_decode($row['gloading_info'], true) ?? [];
        $existingBillQuery = "
            SELECT JSON_UNQUOTE(JSON_EXTRACT(gloading_info, '$.billNumber')) AS billNumber
            FROM general_loading
            WHERE JSON_EXTRACT(agent_details, '$.ag_id') = '$ag_id'
              AND JSON_EXTRACT(gloading_info, '$.child_ids') IS NOT NULL
              AND JSON_EXTRACT(gloading_info, '$.child_ids') != ''";
        $billNumbers = [];
        $existingBillResult = mysqli_query($connect, $existingBillQuery);
        while ($row = mysqli_fetch_assoc($existingBillResult)) {
            if (is_numeric($row['billNumber'])) {
                $billNumbers[] = (int)$row['billNumber'];
            }
        }
        $billNumber = !empty($billNumbers) ? max($billNumbers) + 1 : 1;
        $existingGloadingData['billNumber'] = $billNumber;
    }
    $gloadingInfo = json_encode($existingGloadingData);
    $agentDetails = mysqli_real_escape_string($connect, json_encode($agentDetails));
    if ($parentId) {
        $parentUpdateQuery = "
            UPDATE general_loading
            SET agent_details = '$agentDetails', gloading_info = '$gloadingInfo'
            WHERE id = '$parentId'";
        $doneParent = mysqli_query($connect, $parentUpdateQuery);
    }
    $childUpdateQuery = "
        UPDATE general_loading
        SET agent_details = '$agentDetails'
        WHERE $whereClause AND id != '$parentId'";
    $doneChildren = mysqli_query($connect, $childUpdateQuery);
    $permissionUpdate = update('user_permissions', ['permission' => json_encode(['agent-form'])], ['id' => $_POST['ag_row_id']]);
    if (($doneParent || $doneChildren) && $permissionUpdate) {
        $type = 'success';
        $msg = 'Agent Details Added!';
    }
    // message($type, $url, $msg);
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($id); });</script>";
}
?>
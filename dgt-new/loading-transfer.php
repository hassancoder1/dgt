<?php
$page_title = 'Loading Transfer';
$pageURL = 'loading-transfer';
include("header.php");
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
    $remove = removeFilter('loading-transfer');
    $is_search = true;
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
    if (!empty($_GET['blSearch'])) {
        $blSearch = mysqli_real_escape_string($connect, $_GET['blSearch']);
        $print_filters[] = 'blSearch=' . $blSearch;
        $conditions[] = "bl_no='$blSearch'";
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
                    $count_sql = "SELECT COUNT(id) AS total FROM `general_loading`";
                    if (count($conditions) > 0) {
                        $count_sql .= " WHERE " . implode(' AND ', $conditions);
                    } else {
                        $count_sql .= " WHERE 1";
                    }
                    $count_result = mysqli_query($connect, $count_sql);
                    if (!$count_result) {
                        echo "Error: " . mysqli_error($connect);
                        exit;
                    }
                    $row = mysqli_fetch_assoc($count_result);
                    $total_pages = ceil($row['total'] / $results_per_page);
                    echo '<ul class="pagination">';
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Previous</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = ($i == $page) ? 'active' : '';
                        echo "<li class='page-item $active_class'><a class='page-link' href='" . $base_url . "&page=$i'>$i</a></li>";
                    }
                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                    }
                    echo '</ul>';
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
                <label for="p_id" class="form-label">P#</label>
                <input type="number" name="p_id" value="<?php echo $p_id; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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
                <label for="p_type" class="form-label">Type</label>
                <select class="form-select form-select-sm" name="p_type" style="max-width:130px;" id="p_type">
                    <option value="" selected>All</option>
                    <?php
                    $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        $sel_tran = $type == $static_type['type_name'] ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $static_type['type_name'] . '">' . strtoupper(htmlspecialchars($static_type['details'])) . '</option>';
                    }
                    ?>
                </select>
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
    <style>
        #RecordsTable {
            height: 300px;
            overflow-y: scroll;
        }

        .fixed thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #fff;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="table-responsive mt-4" id="RecordsTable">
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
                        <td class="pointer <?= $rowColor; ?>" onclick="window.location.href= '?view=1&id=<?= $SingleLoading['id']; ?>';">
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
<?php include("footer.php"); ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="staticBackdropLabel">LOADING TRANSFER</h5>
                <div class="d-flex align-items-center">
                    <a href="print/purchase-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                        target="_blank" class="btn btn-dark btn-sm me-2">PRINT</a>
                    <form id="attachmentSubmit" method="post" enctype="multipart/form-data" class="d-flex align-items-center me-2">
                        <input type="hidden" name="t_id_hidden_attach" value="<?php echo $id; ?>">
                        <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                        <input type="button" class="form-control rounded-1 bg-dark text-white" value="+ Contract File"
                            onclick="document.getElementById('attachments').click();" />
                    </form>
                    <script>
                        document.getElementById("attachments").onchange = function() {
                            document.getElementById("attachmentSubmit").submit();
                        }
                    </script>
                    <div class="">
                        <?php
                        // $atts = getAttachments($id, 'purchase_contract');
                        // $no = 0;
                        // foreach ($atts as $att) {
                        //     echo ++$no . '.<a class="text-decoration-underline me-2" href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank">' . readMore($att['attachment'], 20) . '</a><br>';
                        // } 
                        ?>
                    </div>
                    <a href="<?php echo $pageURL; ?>" class="btn-close ms-3" aria-label="Close"></a>
                </div>
            </div>
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
        list($p_id, $sr_no) = explode('-', trim($pair));
        $idConditions[] = "(p_id = '$p_id' AND sr_no = '$sr_no')";
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

    $idConditions = [];
    foreach ($transferPairs as $pair) {
        list($p_id, $sr_no) = explode('-', trim($pair));
        $idConditions[] = "(p_id = '$p_id' AND sr_no = '$sr_no')";
    }
    $whereClause = implode(' OR ', $idConditions);

    $agentDetails = json_encode([
        'ag_acc_no' => mysqli_real_escape_string($connect, $_POST['ag_acc_no']),
        'ag_name' => mysqli_real_escape_string($connect, $_POST['ag_name']),
        'ag_id' => mysqli_real_escape_string($connect, $_POST['ag_id']),
        'row_id' => mysqli_real_escape_string($connect, $_POST['ag_row_id']),
        'cargo_transfer_warehouse' => $_POST['cargo_transfer'],
        'transferred' => true
    ]);
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
    message($type, $url, $msg);
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($id); });</script>";
}
?>
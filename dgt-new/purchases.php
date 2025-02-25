<?php
$page_title = 'Purchases';
$pageURL = 'purchases';
include("header.php");

$remove = $goods_name = $start_date = $end_date = $type = $acc_no = $branch = $p_sr = $payment_type = $sea_road = $country = $country_type = $search_type = $search = $is_transferred = '';
$is_search = false;
if (!empty($_GET['start_date']) || !empty($_GET['end_date'])) {
    $_GET['search'] = $_GET['start_date'] || $_GET['end_date'];
}
global $connect;
$results_per_page = 14;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

$sql = "SELECT *, 
        CASE 
            WHEN locked = 0 AND is_doc = 0 THEN 1 
            WHEN locked = 0 AND is_doc = 1 THEN 2 
            ELSE 3 
        END AS color_priority 
        FROM `transactions` 
        WHERE p_s='p'";
$conditions = [];
$print_filters = [];

if ($_GET) {
    $remove = removeFilter('purchases');
    $is_search = true;
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';
    // Filter by p_id if provided
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
        $conditions[] = "sr = '$p_sr'";
    }

    // Get the search type
    $search_type = isset($_GET['search_type']) ? $_GET['search_type'] : '';
    $print_filters[] = 'search_type=' . $search_type;

    // Process the search query if provided
    if (!empty($_GET['search'])) {
        $search = mysqli_real_escape_string($connect, $_GET['search']);
        $print_filters[] = 'search=' . $search;
        if ($search_type == 'number') {
            $conditions[] = "sr = '$search'";
        } elseif ($search_type == 'allot') {
            $conditions[] = "id IN (
                                SELECT parent_id 
                                FROM transaction_items 
                                WHERE allotment_name LIKE '%$search%'
                              )";
        } elseif ($search_type == 'good') {
            $good_query = "SELECT id FROM goods WHERE name LIKE '%$search%' LIMIT 1";
            $good_result = mysqli_query($connect, $good_query);
            if (mysqli_num_rows($good_result) > 0) {
                $good_row = mysqli_fetch_assoc($good_result);
                $good_id = $good_row['id'];
                $conditions[] = "id IN (
                                    SELECT parent_id 
                                    FROM transaction_items 
                                    WHERE goods_id = '$good_id'
                                  )";
            } else {
                $conditions[] = "0";
            }
        } elseif ($search_type == 'acc') {
            $conditions[] = "id IN (
                                SELECT trans_id 
                                FROM transaction_accounts 
                                WHERE dr_cr = 'cr' 
                                  AND ACC LIKE '%$search%'
                              )";
        } elseif ($search_type == 'date') {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $conditions[] = "_date >= '$start_date' AND _date <= '$end_date'";
        }
    }
    // Additional filters (type, branch, payment_type, etc.)
    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['type']);
        $print_filters[] = 'type=' . $type;
        $conditions[] = "type = '$type'";
    }
    if (isset($_GET['branch']) && !empty($_GET['branch'])) {
        $branch = mysqli_real_escape_string($connect, $_GET['branch']);
        $print_filters[] = 'branch=' . $branch;
        // Use a subquery to match branch_id in transactions with the id in branches where b_code matches.
        $conditions[] = "branch_id IN (
                            SELECT id
                            FROM branches
                            WHERE b_code LIKE '%$branch%'
                          )";
    }

    if (isset($_GET['payment_type']) && !empty($_GET['payment_type'])) {
        $payment_type = mysqli_real_escape_string($connect, $_GET['payment_type']);
        $print_filters[] = 'payment_type=' . $payment_type;
        $conditions[] = "JSON_EXTRACT(payments, '$.full_advance') = '$payment_type'";
    }
}

// Append any conditions to the main SQL query
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}

// Order and limit the results
$sql .= " ORDER BY color_priority, sr DESC LIMIT $start_from, $results_per_page";
// Execute the query
$purchases = mysqli_query($connect, $sql);

// Prepare the print URL
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
?>

<?php
// Calculate the total number of entries
$count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE p_s='p'";
if (count($conditions) > 0) {
    $count_sql .= ' AND ' . implode(' AND ', $conditions);
}
$count_result = mysqli_query($connect, $count_sql);
$row = mysqli_fetch_assoc($count_result);
$total_entries = $row['total'];
$results_per_page = 14; // Define the number of results per page
$total_pages = ceil($total_entries / $results_per_page);

// Determine the current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$page = max(1, min($page, $total_pages)); // Ensure the page is within range

// Calculate the start and end entry numbers
$start_entry = ($page - 1) * $results_per_page + 1;
$end_entry = min($page * $results_per_page, $total_entries);

// Generate the base URL for pagination
$current_url = $_SERVER['REQUEST_URI'];
$url_parts = parse_url($current_url);
parse_str($url_parts['query'] ?? '', $query_params);
unset($query_params['page']);
$base_url = $url_parts['path'] . '?' . http_build_query($query_params);
?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="d-flex justify-content-between align-items-center gap-2 p-2 text-white" style="margin-top:-25px;background:#364153;">
    <h1 class="fw-bold text-light fs-4">Purchases</h1>

    <form name="datesSubmit" class="d-flex align-items-end gap-2" method="get">
        <div class="input-group input-group-sm d-flex flex-wrap gap-2">
            <div class="form-group d-flex justify-content-center align-items-center">
                <select class="form-select form-select-sm" name="search_type" id="search_type" style="width:120px;">
                    <option value="" disabled <?= ($search_type == '') ? 'selected' : ''; ?>>Select</option>
                    <option value="number" <?= $search_type == 'number' ? 'selected' : ''; ?>>P#</option>
                    <option value="allot" <?= $search_type == 'allot' ? 'selected' : ''; ?>>Allot</option>
                    <option value="good" <?= $search_type == 'good' ? 'selected' : ''; ?>>Good</option>
                    <option value="acc" <?= $search_type == 'acc' ? 'selected' : ''; ?>>Acc</option>
                    <option value="date" <?= $search_type == 'date' ? 'selected' : ''; ?>>Date</option>
                </select>
                <input type="text" name="search" value="<?php echo $search; ?>" id="search" class="form-control form-control-sm" style="max-width:200px;" placeholder="Select Type and Search">
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" id="start_date" class="form-control form-control-sm" placeholder="Start Date" style="width:120px;">
                <input type="date" name="end_date" value="<?php echo $end_date; ?>" id="end_date" class="form-control form-control-sm" placeholder="End Date" style="width:120px;">
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchType = document.getElementById("search_type");
                        const textInput = document.getElementById("search");
                        const startDate = document.getElementById("start_date");
                        const endDate = document.getElementById("end_date");

                        function toggleInputs() {
                            if (searchType.value === "date") {
                                textInput.style.display = "none";
                                startDate.style.display = "inline-block";
                                endDate.style.display = "inline-block";
                            } else {
                                textInput.style.display = "inline-block";
                                startDate.style.display = "none";
                                endDate.style.display = "none";
                            }
                        }
                        searchType.addEventListener("change", toggleInputs);
                        toggleInputs();
                    });
                </script>
            </div>
            <div class="form-group">
                <!-- <label for="type" class="form-label">Purchase Type</label> -->
                <select class="form-select form-select-sm" name="type" id="type">
                    <option value="" selected>All (Purchase Type) </option>
                    <?php
                    $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        $sel_tran = $type == $static_type['type_name'] ? 'selected' : '';
                        echo '<option ' . $sel_tran . ' value="' . $static_type['type_name'] . '">' . strtoupper(htmlspecialchars($static_type['details'])) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <!-- <label for="branch" class="form-label">Branch</label> -->
                <select class="form-select form-select-sm" name="branch" id="branch">
                    <option value="" selected>All (Branch)</option>
                    <?php
                    $branches = fetch('branches');
                    while ($b = mysqli_fetch_assoc($branches)) {
                        $b_select = $b['b_code'] == $branch ? 'selected' : '';
                        echo '<option ' . $b_select . ' value="' . $b['b_code'] . '">' . $b['b_code'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <!-- <label for="payment_type" class="form-label"></label> -->
                <select class="form-select form-select-sm" name="payment_type" id="payment_type">
                    <option value="" selected>All (Payment Type)</option>
                    <option value="credit" <?= $payment_type === 'credit' ? 'selected' : ''; ?>>Credit</option>
                    <option value="full" <?= $payment_type === 'full' ? 'selected' : ''; ?>>Full</option>
                    <option value="advance" <?= $payment_type === 'advance' ? 'selected' : ''; ?>>Advance</option>
                </select>
            </div>

            <div class="form-group">
                <?= $remove ? '<a href="' . $pageURL . '" class="btn btn-sm btn-danger"><i class="fa fa-sync-alt"></i></a>' : ''; ?>
                <button type="submit" class="btn btn-sm btn-success">Search</button>
            </div>
        </div>
    </form>

    <div class="d-flex align-items-center gap-2">
        <ul class="pagination pagination-sm m-0">
            <?php
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Prev</a></li>";
            } else {
                echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
            }

            echo "<li class='page-item active'><span class='page-link'>$page</span></li>";

            if ($page + 1 <= $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page + 1) . "'>Next</a></li>";
            } else {
                echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
            }
            ?>
        </ul>

        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">New</button>
            <ul class="dropdown-menu">
                <?php
                $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                while ($static_type = mysqli_fetch_assoc($static_types)) {
                    echo '<li><a class="dropdown-item" href="purchase-add?type=' . urlencode($static_type['type_name']) . '">' . htmlspecialchars($static_type['details']) . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fa fa-print"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= $print_url; ?>" target="_blank"><i class="fas fa-eye me-2"></i> Print Preview</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')"><i class="fas fa-print me-2"></i> Print</a></li>
                <li><a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')"><i class="fas fa-file-pdf me-2"></i> Download PDF</a></li>
                <li><a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')"><i class="fas fa-file-word me-2"></i> Download Word</a></li>
                <li><a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')"><i class="fa fa-whatsapp me-2"></i> Send via WhatsApp</a></li>
                <li><a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')"><i class="fas fa-envelope me-2"></i> Send via Email</a></li>
            </ul>
        </div>
    </div>
</div>
<table class="table table-bordered px-2">
    <thead>
        <tr>
            <th class="bg-light">BR./BILL#</th>
            <th class="bg-light">Type/Date</th>
            <th class="bg-light">Allot/Good Name</th>
            <th class="bg-light">Seller Acc.</th>
            <th class="bg-light">Qty/KGs</th>
            <th class="bg-light">P.TYPE/COUNTRY</th>
            <th class="bg-light">D.Terms/Route</th>
            <th class="bg-light">LOADING/RECEIVING</th>
        </tr>
    </thead>
    <tbody class="mytable">
        <style>
            .mytable td {
                padding: 6px 1px 6px 6px;
            }
        </style>
        <?php
        $sortedEntries = [];
        while ($purchase = mysqli_fetch_assoc($purchases)) {
            $my_color = ''; // Default color
            if (empty($purchase['sea_road']) && empty($purchase['country']) && empty($purchase['delivery_terms']) && empty($purchase['payments'])) {
                $my_color = 'text-danger';
            } elseif (empty($purchase['sea_road']) || empty($purchase['country']) || empty($purchase['delivery_terms']) || empty($purchase['payments'])) {
                $my_color = 'text-warning';
            } elseif (!empty($purchase['sea_road']) && !empty($purchase['country']) && !empty($purchase['delivery_terms']) && !empty($purchase['payments']) && $purchase['locked'] == 1) {
                $my_color = 'text-dark';
            }
            $sortedEntries[] = array_merge($purchase, ['row_color' => $my_color]);
        }

        usort($sortedEntries, function ($a, $b) {
            $colorPriority = ['text-danger' => 1, 'text-warning' => 2, 'text-dark' => 3];
            return $colorPriority[$a['row_color']] <=> $colorPriority[$b['row_color']];
        });
        foreach ($sortedEntries as $purchase) {
            $id = $purchase['id'];
            $_fields_single = transactionSingle($id);
            $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
            $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
            $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];
            $_fields_sr = json_decode($purchase['sea_road'], true);
            $sea_road = ucwords($purchase['type'] === 'local' ? $_fields_single['lwl'] ?? '' : $_fields_single['sea_road'] ?? '');
            $rowColor = $purchase['row_color']; ?>
            <tr class="text-nowrap">
                <td class="pointer <?= $rowColor; ?>" onclick="viewPurchase(<?= $id; ?>)" data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                    <span><?= branchName($_fields_single['branch_id']); ?></span><br>
                    <?php
                    echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $purchase['sr'];
                    echo ($purchase['locked'] == 1) ? '<i class="fa fa-lock text-success"></i>' : '';
                    ?>
                </td>
                <td class="<?= $rowColor; ?>">
                    <span><?= strtoupper($_fields_single['type']); ?></span><br>
                    <?= my_date($_fields_single['_date']); ?>
                </td>
                <td class="<?= $rowColor; ?>">
                    <span><?= $_fields_single['items'][0]['allotment_name'] ?? ''; ?></span><br>
                    <?= $Goods; ?>
                </td>
                <td class="<?= $rowColor; ?>">
                    <span><?= strtoupper($_fields_single['cr_acc']); ?> </span><br><?= $_fields_single['cr_acc_name']; ?>
                </td>
                <td class="<?= $rowColor; ?>">
                    <span><?= number_format($Qty, 2); ?></span><br>
                    <?= number_format($KGs, 2); ?>
                </td>
                <td class="<?= $rowColor; ?> px-2">
                    <span>
                        <?= isset($_fields_single['payment_details']->full_advance)
                            ? ucwords($_fields_single['payment_details']->full_advance)
                            : "&times;"; ?>
                    </span><br>
                    <?= $purchase['country']; ?>
                </td>
                <td class="<?= $rowColor; ?> px-2">
                    <span><?= $purchase['delivery_terms']; ?></span><br>
                    <?= ($sea_road != '') ? $sea_road : "&times;"; ?>
                </td>
                <?php if ($sea_road != '') { ?>
                    <td class="<?= $rowColor; ?>">
                        L. <span class="loading_country"><?= $_fields_sr['l_country'] ?? '&times;'; ?></span>
                        <span class="loading_date"><?= $_fields_sr['l_date'] ?? '&times;'; ?></span><br> R. <span class="receiving_country"><?= $_fields_sr['r_country'] ?? '&times;'; ?></span>
                        <span class="receiving_date"><?= $_fields_sr['r_date'] ?? '&times;'; ?></span>
                    </td>
                <?php } else { ?>
                    <td class="<?= $rowColor; ?>" colspan="2"></td>
                <?php } ?>
            </tr>
        <?php
        } ?>
    </tbody>
</table>
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
        let printType = '<?= isset($_GET['print_type']) ? $_GET['print_type'] : 'contract'; ?>';
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleTransaction.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "purchases",
                    type: 'purchase',
                    print_type: printType,
                    timestamp: currentFormattedDateTime()

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

<?php if (isset($_POST['deleteTransaction'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $url_ = "purchases";
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $deleteAccounts = mysqli_query($connect, "DELETE FROM `transaction_accounts` WHERE trans_id='$p_id_hidden'");
    $deleteItems = mysqli_query($connect, "DELETE FROM `transaction_items` WHERE parent_id='$p_id_hidden'");
    $deleteTransaction = mysqli_query($connect, "DELETE FROM `transactions` WHERE id='$p_id_hidden'");
    if ($deleteAccounts && $deleteItems && $deleteTransaction) {
        $msg = "Deleted Booking Purchase #" . $p_id_hidden;
        $type = "success";
    }
    message($type, $url_, $msg);
}

if (isset($_POST['t_id_hidden_attach'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $ppp_id = mysqli_real_escape_string($connect, $_POST['t_id_hidden_attach']);
    $url_ = $pageURL . "?t_id=" . $ppp_id . "&attach=1";
    $dato = array('is_doc' => 1);
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($ppp_id, 'purchase_contract', basename($_FILES["attachments"]["name"][$key]));
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
            $dd = update('transactions', $dato, array('id' => $ppp_id));
            if ($moved && $dd) {
                $type = 'success';
                $msg = 'Attachment Saved ';
                $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            }
        }
    }
    messageNew($type, $url_, $msg);
}
if (isset($_POST['transferPurchase'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('locked' => 1, 'transfer_level' => 1, '`from`' => 'purchase-orders');
    $locked = update('transactions', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Purchase Successfully transferred.';
    } else {
        $type = 'failed';
        $msg = 'Purchase transfer Failed.';
    }
    messageNew($type, $pageURL, $msg);
}
if (isset($_GET['t_id']) && is_numeric($_GET['t_id'])) {/*&& isset($_GET['view']) && $_GET['view'] == 1*/
    $t_id = mysqli_real_escape_string($connect, $_GET['t_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($t_id); });</script>";
}

if (isset($_POST['purchaseReports'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $reportType = mysqli_real_escape_string($connect, $_POST['reportType']);
    $report = htmlspecialchars($_POST['reportBox']);
    $report = str_replace(array("\n", "\r", "\r\n"), ' ', $report);
    if (is_numeric($id) && recordExists('transactions', ['id' => $id])) {
        $records = fetch('transactions', ['id' => $id]);
        $record = mysqli_fetch_assoc($records);
        $reports = isset($record['reports']) && !empty($record['reports']) ? json_decode($record['reports'], true) : [];
        if (json_last_error() !== JSON_ERROR_NONE) {
            $msg = 'JSON Decode Error: ' . json_last_error_msg();
            messageNew('danger', $pageURL, $msg);
            return;
        }
        $reports[$reportType] = $report;
        $data = ['reports' => json_encode($reports)];
        error_log("Updating Reports Data: " . print_r($data, true));
        if (update('transactions', $data, ['id' => $id])) {
            $type = 'success';
            $msg = 'Report Successfully Updated.';
        } else {
            $msg = 'DB Update Failed';
        }
    }
    messageNew($type, $pageURL, $msg);
}
if (isset($_GET['deletePurchaseReport'])) {
    $id = isset($_GET['p_hidden_id']) ? $_GET['p_hidden_id'] : '';
    $type = $_GET['type'];
    $pageURL = $pageURL . '?t_id=' . $id;
    $deleteReport = isset($_GET['deletePurchaseReport']) ? $_GET['deletePurchaseReport'] : '';
    $records = fetch('transactions', ['id' => $id]);
    $record = mysqli_fetch_assoc($records);
    $reports = isset($record['reports']) ? json_decode($record['reports'], true) : [];
    if (isset($reports[$deleteReport])) {
        unset($reports[$deleteReport]);
        $data = ['reports' => json_encode($reports)];
        if (update('transactions', $data, ['id' => $id])) {
            messageNew('success', $pageURL, 'Report Deleted Successfully!');
        } else {
            messageNew('failed', $pageURL, 'Error in Deleting Report!');
        }
    } else {
        messageNew('failed', $pageURL, 'Report Type Not Found!');
    }
}
?>
<?php
$page_title = 'Sales';
$pageURL = 'sales';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $branch = $p_sr = $payment_type = $sea_road = $country = $country_type = $date_type = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT *, CASE WHEN locked = 0 AND is_doc = 0 THEN 1 WHEN locked = 0 AND is_doc = 1 THEN 2 ELSE 3 END as color_priority FROM `transactions` WHERE p_s='s'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('sales');
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
$sql .= " ORDER BY color_priority, sr DESC LIMIT $start_from, $results_per_page";
$purchases = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;

$textQ = mysqli_query($connect, "SELECT * FROM static_types WHERE type_for IN ('ps_types', 's_types')");
$static_types = [];
while ($test = mysqli_fetch_assoc($textQ)) {
    $static_types[] = $test;
}
?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3" style="margin-top:-30px;">
    <div class="d-flex justify-content-between align-items-center w-100">
        <form name="datesSubmit" class="mt-2" method="get">
            <div class="input-group input-group-sm">
                <div class="form-group">
                    <label for="p_id" class="form-label">P#</label>
                    <input type="number" name="p_id" value="<?php echo $p_sr; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
                </div>
                <div class="form-group">
                    <label for="date_type" class="form-label">Date Type</label>
                    <select class="form-select form-select-sm" name="date_type" style="max-width:130px;" id="date_type" onchange="toggleDates()">
                        <option value="" <?= !in_array($date_type, ['purchase', 'loading', 'receiving']) ? 'selected' : ''; ?>>All</option>
                        <option value="purchase" <?= $date_type == 'purchase' ? 'selected' : ''; ?>>Purchase</option>
                        <option value="loading" <?= $date_type == 'loading' ? 'selected' : ''; ?>>Loading</option>
                        <option value="receiving" <?= $date_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                    </select>
                </div>
                <div class="form-group <?= !in_array($date_type, ['purchase', 'loading', 'receiving']) ? 'd-none' : ''; ?>" id="startInput">
                    <label for="start" class="form-label">Start Date</label>
                    <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
                </div>
                <div class="form-group <?= !in_array($date_type, ['purchase', 'loading', 'receiving']) ? 'd-none' : ''; ?>" id="endInput">
                    <label for="end" class="form-label">End Date</label>
                    <input type="date" name="end" value="<?php echo $end_print; ?>" id="end" class="form-control form-control-sm mx-2" style="max-width:160px;">
                </div>
                <div class="form-group mx-1">
                    <label for="type" class="form-label">Purchase Type</label>
                    <select class="form-select form-select-sm" name="type" style="max-width:130px;" id="type">
                        <option value="" selected>All</option>
                        <?php
                        foreach ($static_types as $static_type) {
                            $sel_tran = $type == $static_type['type_name'] ? 'selected' : '';
                            echo '<option ' . $sel_tran . '  value="' . $static_type['type_name'] . '">' . strtoupper(htmlspecialchars($static_type['details'])) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mx-1">
                    <label for="branch" class="form-label">Branch</label>
                    <select class="form-select form-select-sm" name="branch" style="max-width:130px;" id="branch">
                        <option value="" selected>All</option>
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
                    <label for="payment_type" class="form-label">Payment Type</label>
                    <select class="form-select form-select-sm" name="payment_type" style="max-width:130px;" id="payment_type">
                        <option value="" selected>All</option>
                        <option value="credit" <?= $payment_type === 'credit' ? 'selected' : ''; ?>>Credit</option>
                        <option value="full" <?= $payment_type === 'full' ? 'selected' : ''; ?>>Full</option>
                        <option value="advance" <?= $payment_type === 'advance' ? 'selected' : ''; ?>>Advance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sea_road" class="form-label">SEA/ROAD</label>
                    <select class="form-select form-select-sm" name="sea_road" style="max-width:130px;" id="sea_road">
                        <option value="" selected>All</option>
                        <option value="sea" <?= $sea_road === 'sea' ? 'selected' : ''; ?>>by Sea</option>
                        <option value="road" <?= $sea_road === 'road' ? 'selected' : ''; ?>>by Road</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="is_transferred" class="form-label">Transfer Status</label>
                    <select class="form-select form-select-sm" name="is_transferred" style="max-width:180px;" id="is_transferred">
                        <option value="" selected>All</option>
                        <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                        foreach ($imp_exp_array as $item => $value) {
                            $sel_tran = $is_transferred == $item ? 'selected' : '';
                            echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="acc_no" class="form-label">Account No.</label>
                    <input type="text" class="form-control form-control-sm mx-1" style="max-width:90px;" name="acc_no" placeholder="Acc No." value="<?php echo $acc_no; ?>" id="acc_no">
                </div>
                <div class="form-group mx-1">
                    <label for="country_type" class="form-label">Country Type</label>
                    <select class="form-select form-select-sm" name="country_type" style="max-width:130px;" id="country_type" onchange="toggleCountry()">
                        <option value="" <?= !in_array($country_type, ['purchase', 'loading', 'receiving']) ? 'selected' : ''; ?>>All</option>
                        <option value="purchase" <?= $country_type == 'purchase' ? 'selected' : ''; ?>>Purchase</option>
                        <option value="loading" <?= $country_type == 'loading' ? 'selected' : ''; ?>>Loading</option>
                        <option value="receiving" <?= $country_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                    </select>
                </div>
                <div class="form-group mx-1 <?= !in_array($country_type, ['purchase', 'loading', 'receiving']) ? 'd-none' : ''; ?>" id="countryInput">
                    <label for="country" class="form-label">Country Name</label>
                    <input type="text" name="country" value="<?php echo $country; ?>" id="country" class="form-control form-control-sm mx-1" style="max-width:130px;">
                </div>
                <div class="form-group mt-4 pt-1">
                    <?= $remove ? '<a href="' . $pageURL . '" class="btn btn-sm btn-danger"><i class="fa fa-sync-alt"></i></a>' : ''; ?>
                    <button type="submit" class="btn btn-sm btn-success">Search</button>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <?php
                // Calculate the total number of entries
                $count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE p_s='s'";
                if (count($conditions) > 0) {
                    $count_sql .= ' AND ' . implode(' AND ', $conditions);
                }
                $count_result = mysqli_query($connect, $count_sql);
                $row = mysqli_fetch_assoc($count_result);
                $total_entries = $row['total'];
                $results_per_page = 25; // Define the number of results per page
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

                <!-- Showing X-Y entries of Z -->
                <div>
                    <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                        Sales
                    </h1>
                    <div class="text-center mb-2">
                        <p>Showing <?= $start_entry; ?>-<?= $end_entry; ?> entries of <?= $total_entries; ?></p>
                    </div>
                </div>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center pagination-sm">
                        <?php
                        // Previous button
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Prev</a></li>";
                        } else {
                            echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                        }

                        // Display current page
                        echo "<li class='page-item active'><span class='page-link'>$page</span></li>";

                        // Display the next page, if it exists
                        if ($page + 1 <= $total_pages) {
                            echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page + 1) . "'>" . ($page + 1) . "</a></li>";
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




                <div class="dropdown me-2">
                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                        New
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        if ($static_types) {
                            foreach ($static_types as $static_type) {
                                echo '<li><a class="dropdown-item" href="sale-add?type=' . urlencode($static_type['type_name']) . '">' . htmlspecialchars($static_type['details']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
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
    </div>

    <style>
        /* Modern Scrollbar Styling */
        #RecordsTable::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        #RecordsTable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #RecordsTable::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #RecordsTable::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Table Container */
        #RecordsTable {
            height: calc(100vh - 250px);
            overflow-y: auto;
            display: block;
            width: 100%;
            border: 2px solid #dee2e6;
            background: white;
        }

        /* Table Structure */
        #RecordsTable table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        /* Sticky Header */
        #RecordsTable thead {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #6c757d;
            color: white;
            border-bottom: 2px solid #455a64;
            /* Darker border for header */
        }

        /* Header Cells */
        #RecordsTable thead th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            border-right: 2px solid #ced4da;
        }

        /* Table Cells */
        #RecordsTable th,
        #RecordsTable td {
            padding: 10px;
            text-align: left;
            border: 1px solid #e9ecef;
            /* Solid border for cells */
        }

        /* Row Hover Effect */
        #RecordsTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #RecordsTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Sticky Columns */
        #RecordsTable .sticky-column {
            position: sticky;
            background: white;
            z-index: 1;
            border-right: 1px solid #ced4da;
            /* Border for sticky columns */
        }

        /* First Three Columns Sticky */
        #RecordsTable .sticky-column:first-child {
            left: 0;
        }

        #RecordsTable .sticky-column:nth-child(2) {
            left: 72px;
        }

        #RecordsTable .sticky-column:nth-child(3) {
            left: 149px;
        }

        /* Typography */
        body {
            font-family: 'Inter', sans-serif;
        }

        #RecordsTable th,
        #RecordsTable td {
            font-size: 14px;
        }

        /* Clickable Items */
        .pointer {
            cursor: pointer;
        }

        .pointer:hover {
            text-decoration: underline;
        }

        /* Icons */
        .fa-download {
            color: #28a745;
            transition: color 0.3s ease;
        }

        .fa-download:hover {
            color: #218838;
        }

        .fa-lock {
            color: #28a745;
        }

        /* Color Coding for Rows */
        .text-danger {
            color: #dc3545 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-dark {
            color: #343a40 !important;
        }
    </style>
    <div class="table-responsive mt-4" id="RecordsTable">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap sticky-row">
                    <th class="sticky-column">Bill#</th>
                    <th class="sticky-column">Type</th>
                    <th class="sticky-column">Allot</th>
                    <th>BR.</th>
                    <th>Date</th>
                    <th>Seller Acc.</th>
                    <th>Purchaser Acc.</th>
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
                    $rowColor = $purchase['color_priority'] == 1 ? ' text-danger ' : ($purchase['color_priority'] == 2 ? ' text-warning ' : ' text-dark '); ?>
                    <tr class="text-nowrap">
                        <td class="sticky-column pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $id; ?>)"
                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                            <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $purchase['sr'];
                            echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : '';
                            echo $locked == 2 ? '<i class="fa fa-lock text-success"></i><i class="fa fa-lock text-success" style="margin-left:-6px;"></i>' : ''; ?></td>
                        <td class="sticky-column <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                        <td class="sticky-column <?= $rowColor; ?>"><?= $_fields_single['items'][0]['allotment_name'] ?? ''; ?></td>
                        <td class="<?php echo $rowColor; ?> branch"><?php echo branchName($_fields_single['branch_id']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']);; ?></td>
                        <td class="acc_no <?php echo $rowColor; ?>"><?= strtoupper($_fields_single['cr_acc']) . ' ' . $_fields_single['cr_acc_name']; ?></td>
                        <td class="acc_no <?php echo $rowColor; ?>"><?= strtoupper($_fields_single['dr_acc']) . ' ' . $_fields_single['dr_acc_name']; ?></td>
                        <!-- <td class="acc_no <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['dr_acc']); ?></td> -->
                        <!-- <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['dr_acc_name']; ?></td> -->
                        <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo number_format($Qty, 2); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo number_format($KGs, 2); ?></td>
                        <td class="<?php echo $rowColor; ?>">
                            <?php if ($cntrs > 0) {
                                echo number_format($totals['Final'], 2) . '<sub>' . $totals['curr2'] . '</sub>';
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
                    page: "sales",
                    type: "sale",
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
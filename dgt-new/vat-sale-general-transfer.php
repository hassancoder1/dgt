<?php
$page_title = 'VAT SALES GENERAL TRANSFER';
$pageURL = 'vat-sale-general-transfer';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $branch = $p_sr = $payment_type = $sea_road = $country = $country_type = $date_type = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `transactions` WHERE p_s='s' AND type='local'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('vat-sale-general-transfer');
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
$sql .= " ORDER BY sr DESC LIMIT $start_from, $results_per_page";
$purchases = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
            SALE GENERAL TRANSFER
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
                    $count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE p_s='p'";
                    if (count($conditions) > 0) {
                        $count_sql .= ' AND ' . implode(' AND ', $conditions);
                    }
                    $count_result = mysqli_query($connect, $count_sql);
                    $row = mysqli_fetch_assoc($count_result);
                    $total_pages = ceil($row['total'] / $results_per_page);
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Prev</a></li>";
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
                    ?>
                </ul>
            </nav>
            <div class="dropdown me-2">
                <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    New
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                    $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        echo '<li><a class="dropdown-item" href="purchase-add?type=' . urlencode($static_type['type_name']) . '">' . htmlspecialchars($static_type['details']) . '</a></li>';
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
    <form name="datesSubmit" class="mt-2" method="get">
        <div class="input-group input-group-sm">
            <div class="form-group">
                <label for="p_id" class="form-label">P#</label>
                <input type="number" name="p_id" value="<?php echo $p_id; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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
                    <th><?= SuperAdmin() ? 'P#' : '#'; ?></th>
                    <th>P Date.</th>
                    <th>Acc Details</th>
                    <th>Allot</th>
                    <th>Goods Details</th>
                    <th>Quantity</th>
                    <th>Net Kgs</th>
                    <th>Gross Weight</th>
                    <th>U/P Name</th>
                    <th>Amount</th>
                    <th>Tax% amt</th>
                    <th>Total Amt</th>
                </tr>
            </thead>
            <tbody>
                <?php $purchases = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                while ($purchase = mysqli_fetch_assoc($purchases)) {
                    $id = $purchase['id'];
                    $p_sr = $purchase['sr'];
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
                    $net_KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_net_kgs'];
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
                    if ($locked == 0) {
                        $rowColor = $is_doc == 0 ? ' text-danger ' : ' text-warning ';
                    }
                    $totalAmount = $totalTax = $finalAmount = 0;
                    foreach ($_fields_single['items'] as $item) {
                        $totalAmount += (float)$item['amount'];
                        $totalTax += (float)$item['tax_amount'];
                        $finalAmount += (float)$item['final_amount'];
                    }
                ?>
                    <tr class="text-nowrap">
                        <td class="pointer <?= $rowColor; ?>" onclick="viewPurchase(<?= $id; ?>)"
                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                            <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $p_sr;
                            echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?php echo my_date($_fields_single['_date']);; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $_fields_single['cr_acc']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $_fields_single['items'][0]['allotment_name']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $Goods; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $Qty; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $net_KGs; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $KGs; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $_fields_single['items'][0]['rate1'] . ' ' . $_fields_single['items'][0]['currency1']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $totalAmount; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $totalTax; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $finalAmount; ?></td>
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
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleVATPurchase.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "vat-sale-general-transfer",
                    type: 'purchase'
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
<?php
if (isset($_POST['updateStatus'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('vat' => $_POST['vat_status']);
    $locked = update('transactions', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Sale Successfully transferred.';
    } else {
        $type = 'failed';
        $msg = 'Sale transfer Failed.';
    }
    messageNew($type, $pageURL, $msg);
}
if (isset($_GET['t_id']) && is_numeric($_GET['t_id'])) {/*&& isset($_GET['view']) && $_GET['view'] == 1*/
    $t_id = mysqli_real_escape_string($connect, $_GET['t_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($t_id); });</script>";
}
?>
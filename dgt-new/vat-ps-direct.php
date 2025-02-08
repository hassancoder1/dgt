<?php
$page_title = 'P/S General Transfer';
$pageURL = 'vat-ps-direct';
include("header.php");
$remove = $allotment_name = $goods_id = $size = $brand = $origin = $date_from = $date_to = '';
$is_search = false;
global $connect;
$conditions = [];
$print_filters = [];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sql = "SELECT * FROM `transactions` WHERE JSON_EXTRACT(show_in, '$.vat') = 'yes'";
if (!empty($_GET)) {
    $is_search = true;
    $remove = removeFilter('purchases');
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
}
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}
$sql .= " ORDER BY created_at DESC";
$transactions = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$sortedEntries = [];
$totalAmount = $totalTax = $totalWithTax = $soldAmount = $soldTax = $soldWithTax = $remainingAmount = $remainingTax = $remainingWithTax = 0;
while ($single = mysqli_fetch_assoc($transactions)) {
    $T = transactionSingle($single['id']);
    if (!empty($T['items'])) {
        $sortedEntries[] = [
            'id' => $single['id'],
            ...$T,
            'good' => [
                ...$T['items'][0]
            ],
            'totals' => [
                'quantity_no' => array_sum(array_column($T['items'], 'qty_no')),
                'net_kgs' => array_sum(array_column($T['items'], 'net_kgs')),
                'gross_kgs' => array_sum(array_column($T['items'], 'total_kgs')),
                'amount' => array_sum(array_column($T['items'], 'amount')),
                'tax_amount' => array_sum(array_column($T['items'], 'tax_amount')),
                'final_amount' => array_sum(array_column($T['items'], 'total_with_tax'))
            ],
            'row_color' => $T['p_s'] === 'p' ? 'text-success' : 'text-danger'
        ];
        if ($T['p_s'] === 'p') {
            $totalAmount += array_sum(array_column($T['items'], 'amount'));
            $totalAmount += array_sum(array_column($T['items'], 'tax_amount'));
            $totalWithTax += array_sum(array_column($T['items'], 'total_with_tax')) === 0 ? array_sum(array_column($T['items'], 'amount')) : array_sum(array_column($T['items'], 'total_with_tax'));
        } else {
            $soldAmount += array_sum(array_column($T['items'], 'amount'));
            $soldTax += array_sum(array_column($T['items'], 'tax_amount'));
            $soldWithTax += array_sum(array_column($T['items'], 'total_with_tax')) === 0 ? array_sum(array_column($T['items'], 'amount')) : array_sum(array_column($T['items'], 'total_with_tax'));
        }
    }
}
$remainingAmount = $totalAmount - $soldAmount;
$remainingTax = $totalTax - $soldTax;
$remainingWithTax = $totalWithTax - $soldWithTax;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>

<main class="mx-4" style="margin-top: -70px;">
    <section class="mb-4">
        <form method="get" class="row g-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary">P/S General Transfer</h5>
                    <div class="row">
                        <div class="col-md-8 row g-3 align-items-center">
                            <!-- Filters Row -->
                            <div class="col-md-3">
                                <label for="allot" class="form-label">Allotment Name</label>
                                <input type="text" name="allot" id="allot" class="form-control form-control-sm" placeholder="Allot Name" value="<?= htmlspecialchars($allot ?? ''); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="goods_id" class="form-label">Goods</label>
                                <select id="goods_id" name="goods_id" class="form-select form-select-sm">
                                    <option value="">All Goods</option>
                                    <?php $goods = fetch('goods');
                                    while ($good = mysqli_fetch_assoc($goods)) {
                                        $g_selected = $good['id'] == $goods_id ? 'selected' : '';
                                        echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . htmlspecialchars($good['name']) . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="size" class="form-label">Size</label>
                                <select class="form-select form-select-sm" name="size" id="size">
                                    <option value="">All Sizes</option>
                                    <?php
                                    $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM transaction_items");
                                    while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                        $size_selected = $size_s['size'] == $size ? 'selected' : '';
                                        echo '<option ' . $size_selected . ' value="' . htmlspecialchars($size_s['size']) . '">' . htmlspecialchars($size_s['size']) . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="brand" class="form-label">Brand</label>
                                <select class="form-select form-select-sm" name="brand" id="brand">
                                    <option value="">All Brands</option>
                                    <?php
                                    $goods_brands = mysqli_query($connect, "SELECT DISTINCT brand FROM transaction_items");
                                    while ($g_brand = mysqli_fetch_assoc($goods_brands)) {
                                        $brand_selected = $g_brand['brand'] == $brand ? 'selected' : '';
                                        echo '<option ' . $brand_selected . ' value="' . htmlspecialchars($g_brand['brand']) . '">' . htmlspecialchars($g_brand['brand']) . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="origin" class="form-label">Origin</label>
                                <select class="form-select form-select-sm" name="origin" id="origin">
                                    <option value="">All Origins</option>
                                    <?php
                                    $origins = mysqli_query($connect, "SELECT DISTINCT origin FROM transaction_items");
                                    while ($origin_s = mysqli_fetch_assoc($origins)) {
                                        $origin_selected = $origin_s['origin'] == $origin ? 'selected' : '';
                                        echo '<option ' . $origin_selected . ' value="' . htmlspecialchars($origin_s['origin']) . '">' . htmlspecialchars($origin_s['origin']) . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Date From</label>
                                <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="<?= htmlspecialchars($date_from ?? ''); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Date To</label>
                                <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="<?= htmlspecialchars($date_to ?? ''); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="actions" class="form-label">Actions</label>
                                <div class="d-flex gap-2 justify-content-start align-items-center">
                                    <?php if (count($_GET) > 1) { ?>
                                        <a href="<?= $pageURL; ?>" class="btn btn-sm btn-danger"><i class="fa fa-refresh"></i></a>
                                    <?php } ?>
                                    <button type="submit" class="btn btn-sm btn-success">Search</button>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        <ul class="dropdown-menu mt-2">
                                            <li><a class="dropdown-item" href="<?= $print_url; ?>" target="_blank"><i class="fas text-secondary fa-eye me-2"></i> Print Preview</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')"><i class="fas text-secondary fa-print me-2"></i> Print</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')"><i class="fas text-secondary fa-file-pdf me-2"></i> Download PDF</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')"><i class="fas text-secondary fa-file-word me-2"></i> Download Word File</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')"><i class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')"><i class="fas text-secondary fa-envelope me-2"></i> Send in Email</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Totals Table -->
                        <div class="col-md-4 mt-3">
                            <table class="table table-striped table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Tax</th>
                                        <th>Total with Tax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-success fw-bold">Purchases</td>
                                        <td class="text-success"><?= number_format($totalAmount ?? 0, 2); ?></td>
                                        <td class="text-success"><?= number_format($totalTax ?? 0, 2); ?></td>
                                        <td class="text-success"><?= number_format($totalWithTax ?? 0, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-danger fw-bold">Sales</td>
                                        <td class="text-danger">-<?= number_format($soldAmount ?? 0, 2); ?></td>
                                        <td class="text-danger">-<?= number_format($soldTax ?? 0, 2); ?></td>
                                        <td class="text-danger">-<?= number_format($soldWithTax ?? 0, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary fw-bold">Remainings</td>
                                        <td class="text-primary"><?= number_format($remainingAmount ?? 0, 2); ?></td>
                                        <td class="text-primary"><?= number_format($remainingTax ?? 0, 2); ?></td>
                                        <td class="text-primary"><?= number_format($remainingWithTax ?? 0, 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>


    <section>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <style>
                        table * {
                            font-size: 13px;
                        }
                    </style>

                    <table class="table table-bordered table-hover table-fixed">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Acc</th>
                                <th>Allot</th>
                                <th>Warehouse</th>
                                <th>Good Name</th>
                                <th>Size</th>
                                <th>Brand</th>
                                <th>Origin</th>
                                <th>Qty</th>
                                <th>N.KGS</th>
                                <th>G.KGS</th>
                                <th>U/Price</th>
                                <th>Amt</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $khaataQ = mysqli_fetch_all(mysqli_query($connect, "SELECT id, khaata_no FROM khaata"), MYSQLI_ASSOC);
                            $khaataDQ = mysqli_fetch_all(mysqli_query($connect, "SELECT khaata_id, json_data FROM khaata_details"), MYSQLI_ASSOC);
                            $khaataMap = [];
                            foreach ($khaataQ as $row) {
                                $khaataMap[$row['khaata_no']] = $row['id'];
                            }
                            $khaataDetailsMap = [];
                            foreach ($khaataDQ as $row) {
                                $khaataDetailsMap[$row['khaata_id']] = json_decode($row['json_data'], true);
                            }

                            foreach ($sortedEntries as $entry) {
                                $appendDash = $entry['p_s'] === 's' ? true : false;
                            ?>
                                <tr class="text-nowrap">
                                    <td class="<?= $entry['row_color']; ?> pointer" onclick="window.location.href='<?= $pageURL; ?>?view=1&id=<?= $entry['id']; ?>">
                                        <b><?= ucfirst($entry['p_s']); ?>#</b> <?= htmlspecialchars($entry['sr']); ?>
                                    </td>
                                    <td class="fw-bold"><?= ucfirst(htmlspecialchars($entry['type'] ?? 'N/A')); ?></td>
                                    <td class="fw-bold"><?= my_date(htmlspecialchars($entry['_date'] ?? 'N/A')); ?></td>
                                    <td class="fw-bold"><?= !empty($companyName) ? $companyName : 'N/A'; ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($entry['good']['allotment_name'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($entry['transfer']['warehouse_transfer'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= goodsName(htmlspecialchars($entry['good']['goods_id'])); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($entry['good']['size'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($entry['good']['brand'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($entry['good']['origin'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($entry['totals']['quantity_no']); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($entry['totals']['net_kgs']); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($entry['totals']['gross_kgs']); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($entry['good']['rate1'] ?? 'N/A') . ' ' . htmlspecialchars($entry['good']['price'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . number_format(htmlspecialchars(!empty($entry['totals']['amount']) ? $entry['totals']['amount'] : 0), 2); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . number_format(htmlspecialchars(!empty($entry['totals']['tax_amount']) ? $entry['totals']['tax_amount'] : 0), 2); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . number_format(htmlspecialchars(!empty($entry['totals']['final_amount']) ? $entry['totals']['final_amount'] : $entry['totals']['amount']), 2); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include("footer.php"); ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </form>
        </div>
    </div>
</div>

<script>
    function viewPurchase(unique_code, print_party_2, warehouse_type, print_type, sr_no) {
        if (unique_code) {
            $.ajax({
                url: 'ajax/editVATDirect.php',
                type: 'post',
                data: {
                    unique_code: unique_code,
                    print_party_1: unique_code,
                    print_party_2: print_party_2,
                    warehouse_type: 'General',
                    print_type: print_type,
                    sr_no: sr_no,
                    fetch_from: 'vat_copies'
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


if (isset($_GET['unique_code']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $unique_code = mysqli_real_escape_string($connect, $_GET['unique_code']);
    $sr_no = mysqli_real_escape_string($connect, $_GET['sr_no']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase('$unique_code','','','invoice','$sr_no'); });</script>";
}


if (isset($_POST['reSubmit'])) {
    unset($_POST['reSubmit']);
    echo "SDSDSDS";
    $tdata = json_decode($_POST['tdata'], true);
    function updateNestedJson(&$data, $key, $value)
    {
        foreach ($data as $k => &$v) {
            if ($k === $key) {
                $v = $value;
            } elseif (is_array($v)) {
                updateNestedJson($v, $key, $value);
            }
        }
    }
    foreach ($_POST as $key => $value) {
        if ($key !== 'tdata') {
            if (array_key_exists($key, $tdata)) {
                $tdata[$key] = $value;
            } else {
                updateNestedJson($tdata, $key, $value);
            }
        }
    }
    $tdata['edited'] = true;
    $data = [
        'unique_code' => $_POST['unique_code'],
        'tdata' => mysqli_real_escape_string($connect, json_encode($tdata)),
    ];
    if (!recordExists('vat_direct', ['unique_code' => $_POST['unique_code']])) {
        $done = insert('vat_direct', $data);
        if ($done) {
            $message = 'Record Added!';
            messageNew('success', 'vat-ps-direct', $message);
        }
    } else {
        $done = update('vat_direct', $data, ['unique_code' => $_POST['unique_code']]);
        if ($done) {
            $message = 'Record Updated!';
            messageNew('success', 'vat-ps-direct', $message);
        }
    }
}

if (isset($_GET['delete']) && $_GET['delete'] === base64_encode('DELETE ME!')) {
    $unique_code = $_GET['unique_code'];
    $done = mysqli_query($connect, "DELETE FROM vat_copies WHERE unique_code='$unique_code'");
    if ($done) {
        messageNew('success', $pageURL, 'Record Deleted!');
    }
}
?>
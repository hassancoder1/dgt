<?php
$page_title = 'P/S GENERAL TRANSFER';
include("header.php");
$pageURL = "vat-ps-direct";
$print_url = 'print/' . $pageURL;
$allot = $goods_id = $size = $brand = $origin = $date_from = $date_to = '';
$filters = [
    'size' => '',
    'brand' => '',
    'origin' => '',
    'goods_id' => '',
    'date_from' => '',
    'date_to' => '',
    'net_kgs' => '',
    'qty_no' => '',
    'allot' => ''
];

$is_search = false;
global $connect;
$conditions = [];
function escapeInput($input)
{
    global $connect;
    return mysqli_real_escape_string($connect, $input);
}

if ($_GET) {
    $allot = $_GET['allot'] ?? '';
    $goods_id = $_GET['goods_id'] ?? '';
    $size = $_GET['size'] ?? '';
    $brand = $_GET['brand'] ?? '';
    $origin = $_GET['origin'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';
    $resetFilters = removeFilter($pageURL);
    foreach ($filters as $key => &$value) {
        if (!empty($_GET[$key])) {
            $value = escapeInput($_GET[$key]);
            switch ($key) {
                case 'size':
                case 'brand':
                case 'origin':
                case 'goods_id':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.$key') = '$value'";
                    break;
                case 'date_from':
                    $conditions[] = "JSON_EXTRACT(tdata, '$._date') >= '$value'";
                    break;
                case 'date_to':
                    $conditions[] = "JSON_EXTRACT(tdata, '$._date') <= '$value'";
                    break;
                case 'net_kgs':
                case 'qty_no':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.goods_json.$key') = '$value'";
                    break;
                case 'allot':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.goods_json.allotment_name') = '$value'";
                    break;
            }
        }
    }
}
$where_clause = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
$sql = "SELECT * FROM transactions $where_clause";
$data = mysqli_query($connect, $sql);
$direct = mysqli_query($connect, "SELECT * FROM vat_direct");
$directEntries = [];
while ($direct_one = mysqli_fetch_assoc($direct)) {
    $directEntries[$direct_one['unique_code']] = $direct_one;
}
$entries = [];
while ($one = mysqli_fetch_assoc($data)) {
    $entry = array_merge(
        transactionSingle($one['id']),
        ['sea_road_array' => json_decode($one['sea_road'], true) ?? []],
        ['notify_party_details' => json_decode($one['notify_party_details'], true) ?? []],
        ['third_party_bank' => json_decode($one['third_party_bank'], true) ?? []],
        ['reports' => json_decode($one['reports'], true) ?? []],
        ["id" => $one['id'], "p_sr" => $one['sr']]
    );

    if (!isset($entries[$one['id']])) {
        $entries[$one['id']] = $entry;
        $entries[$one['id']]['items'] = [];
    }
    $entries[$one['id']]['items'] = array_merge($entries[$one['id']]['items'], $entry['items'] ?? []);
}

$totalAmount = $totalTax = $totalWithTax = 0;
$soldAmount = $soldTax = $soldWithTax = 0;
$sortedEntries = [];

foreach ($entries as $entry) {
    if (empty($entry['items'])) continue;
    foreach ($entry['items'] as $item) {
        $show_in = json_decode($item['show_in'], true);
        $show_in['vat'] = $show_in['vat'] ?? 'no';
        $unique_code = $entry['p_s'] . $entry['type'][0] . (isset($entry['sea_road_array']['route'])
            ? ($entry['sea_road_array']['route'] === 'local' ? 'ld' : 'wr')
            : ($entry['sea_road_array']['sea_road'] === 'sea' ? 'se' : 'rd')) . '_' . $entry['id'] . '_' . $item['id'];

        if ($show_in['vat'] === 'no') continue;

        $isPurchase = !isset($entry['p_s']) || $entry['p_s'] !== 's';
        $rowColor = $isPurchase ? 'text-success' : 'text-danger';

        $amount = floatval($item['amount'] ?? 0);
        $taxAmount = floatval($item['tax_amount'] ?? 0);
        $totalWithTaxAmount = floatval($item['total_with_tax'] ?? $amount);

        if ($isPurchase) {
            $totalAmount += $amount;
            $totalTax += $taxAmount;
            $totalWithTax += $totalWithTaxAmount;
        } else {
            $soldAmount += $amount;
            $soldTax += $taxAmount;
            $soldWithTax += $totalWithTaxAmount;
        }

        $sortedEntries[$entry['id']] = [
            'row_color' => $rowColor,
            'unique_code' => $unique_code,
            'tdata' => json_encode(array_merge($entry, ['good' => $item]))
        ];
    }
}
$remainingAmount = $totalAmount - $soldAmount;
$remainingTax = $totalTax - $soldTax;
$remainingWithTax = $totalWithTax - $soldWithTax;
$totals = [
    "total_amount" => $totalAmount,
    "total_tax" => $totalTax,
    "total_with_tax" => $totalWithTax,
    "sold_amount" => $soldAmount,
    "sold_tax" => $soldTax,
    "sold_with_tax" => $soldWithTax,
    "remaining_amount" => $totalAmount - $soldAmount,
    "remaining_tax" => $totalTax - $soldTax,
    "remaining_with_tax" => $totalWithTax - $soldWithTax
];
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>

<style>
    /* .col-md-2.main-page {
        width: 148px;
    } */

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
                                        <td class="text-success"><?= number_format($totalAmount, 2); ?></td>
                                        <td class="text-success"><?= number_format($totalTax, 2); ?></td>
                                        <td class="text-success"><?= number_format($totalWithTax, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-danger fw-bold">Sales</td>
                                        <td class="text-danger">-<?= number_format($soldAmount, 2); ?></td>
                                        <td class="text-danger">-<?= number_format($soldTax, 2); ?></td>
                                        <td class="text-danger">-<?= number_format($soldWithTax, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary fw-bold">Remainings</td>
                                        <td class="text-primary"><?= number_format($remainingAmount, 2); ?></td>
                                        <td class="text-primary"><?= number_format($remainingTax, 2); ?></td>
                                        <td class="text-primary"><?= number_format($remainingWithTax, 2); ?></td>
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
                                $tdata = json_decode($entry['tdata'], true);
                                $quantity = $tdata['good']['qty_no'] ?? 0;
                                $netWeight = $tdata['good']['net_kgs'] ?? 0;
                                $grossWeight = $tdata['good']['total_kgs'] ?? 0;
                                $amount = $tdata['good']['amount'] ?? 0;
                                $tax = $tdata['good']['tax_amount'] ?? 0;
                                $total = $tdata['good']['total_with_tax'] === 0 ? $tdata['good']['amount'] : $tdata['good']['total_with_tax'];
                                $appendDash = isset($tdata['p_s']) && $tdata['p_s'] === 's';
                                $CompanyWeightAcc = $tdata['p_s'] === 'p' ? $tdata['cr_acc'] : $tdata['dr_acc'];
                                $companyName = $weight = '';
                                if (isset($khaataMap[$CompanyWeightAcc])) {
                                    $khaataId = $khaataMap[$CompanyWeightAcc];
                                    if (isset($khaataDetailsMap[$khaataId])) {
                                        $details = $khaataDetailsMap[$khaataId];
                                        $companyName = $details['company_name'] ?? 'N/A';
                                        if (isset($details['indexes1']) && isset($details['vals1'])) {
                                            $combined = array_combine($details['indexes1'], $details['vals1']);
                                            $weight = $combined['WEIGHT'] ?? 'N/A';
                                        }
                                    }
                                }
                            ?>
                                <tr class="text-nowrap">
                                    <td class="<?= $entry['row_color']; ?> pointer" onclick="window.location.href='<?= $pageURL; ?>?view=1&unique_code=<?= $entry['unique_code']; ?>&print_type=contract&CCWpage=all&sr_no=<?= $tdata['good']['sr']; ?>'">
                                        <b><?= ucfirst($tdata['p_s']); ?>#</b> <?= htmlspecialchars($tdata['sr']); ?>
                                    </td>
                                    <td class="fw-bold"><?= ucfirst(htmlspecialchars($tdata['type'] ?? 'N/A')); ?></td>
                                    <td class="fw-bold"><?= my_date(htmlspecialchars($tdata['_date'] ?? 'N/A')); ?></td>
                                    <td class="fw-bold"><?= !empty($companyName) ? $companyName : 'N/A'; ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($tdata['good']['allotment_name'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($tdata['transfer']['warehouse_transfer'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= goodsName(htmlspecialchars($tdata['good']['goods_id'])); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($tdata['good']['size'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($tdata['good']['brand'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($tdata['good']['origin'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($quantity); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($netWeight); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($grossWeight); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($tdata['good']['goods_json']['rate1'] ?? 'N/A') . ' ' . htmlspecialchars($tdata['good']['goods_json']['price'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . number_format(htmlspecialchars(!empty($amount) ? $amount : 0), 2); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . number_format(htmlspecialchars(!empty($tax) ? $tax : 0), 2); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . number_format(htmlspecialchars(!empty($total) ? $total : 0), 2); ?></td>
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
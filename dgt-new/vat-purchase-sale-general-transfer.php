<?php
$page_title = 'P/S GENERAL TRANSFER';
include("header.php");
$pageURL = "vat-purchase-sale-general-transfer";
$print_url = 'print/' . $pageURL;
$_GET['CCWpage'] = 'all';
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

// Build conditions based on filters
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
$sql = "SELECT * FROM vat_copies $where_clause";
$data = mysqli_query($connect, $sql);
$entries = [];
while ($one = mysqli_fetch_assoc($data)) {
    if ($one['id'] !== null) {
        $entries[] = $one;
    }
}


$i = 1;
$redEntries = $yellowEntries = $darkEntries = [];
$totalAmount = $totalTax = $totalWithTax = 0; // Totals for purchase entries
$soldAmount = $soldTax = $soldWithTax = 0; // Totals for sold entries
$remainingAmount = $remainingTax = $remainingWithTax = 0; // Remaining totals
$sortedEntries = [];
foreach ($entries as $entry) {
    $ldata = json_decode($entry['ldata'], true);
    $tdata = json_decode($entry['tdata'], true);

    $TotalQty = $ldata['good']['quantity_no'] ?? 0; // Total quantity in the entry
    $RemQty = $ldata['good']['goods_json']['qty_no'] ?? 0; // Remaining quantity
    $SoldQty = $TotalQty - $RemQty; // Calculate sold quantity

    $appendDash = isset($tdata['p_s']) && $tdata['p_s'] === 's'; // 's' indicates a sale entry

    // // Categorize entries into red, yellow, or dark
    // if ($SoldQty === 0) {
    //     // No sales, entirely unsold
    //     $entry['row_color'] = 'text-danger'; // Red for unsold
    //     $redEntries[] = $entry;
    // } elseif ($RemQty > 0) {
    //     // Partially sold
    //     $entry['row_color'] = 'text-warning'; // Yellow for partially sold
    //     $yellowEntries[] = $entry;
    // } else {
    //     // Fully sold
    $entry['row_color'] = 'text-danger'; // Dark for fully sold
    //     $darkEntries[] = $entry;
    // }

    // Aggregate totals based on purchase or sale
    if (!$appendDash) {
        $entry['row_color'] = 'text-success'; // Dark for fully sold
        // Purchase entry
        $totalAmount += $ldata['good']['amount'];
        $totalTax += (float)$ldata['good']['tax_amount'];
        $totalWithTax += $ldata['good']['total_with_tax'] === 0 ? $ldata['good']['amount'] : $ldata['good']['total_with_tax'];
    } else {
        // Sale entry
        $soldAmount += $ldata['good']['amount'];
        $soldTax += (float)$ldata['good']['tax_amount'];
        $soldWithTax += $ldata['good']['total_with_tax'] === 0 ? $ldata['good']['amount'] : $ldata['good']['total_with_tax'];
    }
    $sortedEntries[] = $entry;
}

// Calculate remaining values
$remainingAmount = $totalAmount - $soldAmount;
$remainingTax = $totalTax - $soldTax;
$remainingWithTax = $totalWithTax - $soldWithTax;

// Merge categorized entries for further use or display
// $sortedEntries = array_merge($redEntries, $yellowEntries, $darkEntries);

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
                                $ldata = json_decode($entry['ldata'], true);
                                $tdata = json_decode($entry['tdata'], true);
                                $quantity = $ldata['good']['quantity_no'] ?? 0;
                                $netWeight = $ldata['good']['net_weight'] ?? 0;
                                $grossWeight = $ldata['good']['gross_weight'] ?? 0;
                                $amount = $ldata['good']['amount'] ?? 0;
                                $tax = $ldata['good']['tax_amount'] ?? 0;
                                $total = $ldata['good']['total_with_tax'] === 0 ? $ldata['good']['amount'] : $ldata['good']['total_with_tax'];
                                $appendDash = isset($tdata['p_s']) && $tdata['p_s'] === 's';
                                // Fetch company name and weight for $tdata['cr_acc']
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
                                    <td class="<?= $entry['row_color']; ?> pointer" onclick="window.location.href='<?= $pageURL; ?>?view=1&unique_code=<?= $entry['unique_code']; ?>&print_type=contract&CCWpage=all&sr_no=<?= $ldata['sr_no']; ?>'">
                                        <b><?= ucfirst($ldata['type']); ?>#</b> <?= htmlspecialchars($ldata['p_sr']); ?> (<?= htmlspecialchars($ldata['sr_no']); ?>)
                                    </td>
                                    <td class="fw-bold"><?= my_date(htmlspecialchars($tdata['_date'] ?? 'N/A')); ?></td>
                                    <td class="fw-bold"><?= !empty($companyName) ? $companyName : 'N/A'; ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['goods_json']['allotment_name'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['transfer']['warehouse_transfer'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= goodsName(htmlspecialchars($ldata['good']['goods_id'])); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['size'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['brand'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['origin'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($quantity); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($netWeight); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($grossWeight); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['goods_json']['rate1'] ?? 'N/A') . ' ' . htmlspecialchars($ldata['good']['goods_json']['price'] ?? 'N/A'); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($amount); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($tax); ?></td>
                                    <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? '-' : '+') . htmlspecialchars($total); ?></td>
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
                url: 'ajax/GetCustomEditEntry.php',
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

    // Decode tdata and ldata JSON strings into PHP arrays
    $tdata = json_decode($_POST['tdata'], true);
    $ldata = json_decode($_POST['ldata'], true);

    // Recursive function to update JSON structure
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

    // Iterate through POST keys to update tdata and ldata
    foreach ($_POST as $key => $value) {
        if ($key !== 'tdata' && $key !== 'ldata') { // Exclude JSON strings
            // Update tdata
            if (array_key_exists($key, $tdata)) {
                $tdata[$key] = $value;
            } else {
                updateNestedJson($tdata, $key, $value);
            }

            // Update ldata
            if (array_key_exists($key, $ldata)) {
                $ldata[$key] = $value;
            } else {
                updateNestedJson($ldata, $key, $value);
            }
        }
    }

    $ldata['good'] = calcNewValues([$_POST['quantity_no'], $_POST['qty_no']], $ldata['good'], 'both');
    $data = [
        'data_for' => mysqli_real_escape_string($connect, $_POST['warehouse_transfer']),
        'tdata' => mysqli_real_escape_string($connect, json_encode($tdata)),
        'ldata' => mysqli_real_escape_string($connect, json_encode($ldata))
    ];

    $done = update('vat_copies', $data, ['unique_code' => $_POST['unique_code']]);
    if ($done) {
        $message = 'Record Updated!';
        messageNew('success', 'vat-purchase-sale-general-transfer', $message);
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
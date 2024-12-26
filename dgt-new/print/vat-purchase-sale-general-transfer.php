<?php
$page_title = 'P/S GENERAL TRANSFER';
include("../connection.php");
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

$where_clause = !empty($conditions) ? ' AND ' . implode(' AND ', $conditions) : '';
$sql = "SELECT * FROM vat_copies WHERE unique_code LIKE '_l%' $where_clause";
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

foreach ($entries as $entry) {
    $ldata = json_decode($entry['ldata'], true);
    $tdata = json_decode($entry['tdata'], true);

    $TotalQty = $ldata['good']['quantity_no'] ?? 0; // Total quantity in the entry
    $RemQty = $ldata['good']['goods_json']['qty_no'] ?? 0; // Remaining quantity
    $SoldQty = $TotalQty - $RemQty; // Calculate sold quantity

    $appendDash = isset($tdata['p_s']) && $tdata['p_s'] === 's'; // 's' indicates a sale entry

    // Categorize entries into red, yellow, or dark
    if ($SoldQty === 0) {
        // No sales, entirely unsold
        $entry['row_color'] = 'text-danger'; // Red for unsold
        $redEntries[] = $entry;
    } elseif ($RemQty > 0) {
        // Partially sold
        $entry['row_color'] = 'text-warning'; // Yellow for partially sold
        $yellowEntries[] = $entry;
    } else {
        // Fully sold
        $entry['row_color'] = 'text-dark'; // Dark for fully sold
        $darkEntries[] = $entry;
    }

    // Aggregate totals based on purchase or sale
    if (!$appendDash) {
        // Purchase entry
        $totalAmount += $ldata['good']['amount'];
        $totalTax += (float)$ldata['good']['tax_amount'];
        $totalWithTax += $ldata['good']['total_with_tax'];
    } else {
        // Sale entry
        $soldAmount += $ldata['good']['amount'];
        $soldTax += (float)$ldata['good']['tax_amount'];
        $soldWithTax += $ldata['good']['total_with_tax'];
    }
}

// Calculate remaining values
$remainingAmount = $totalAmount - $soldAmount;
$remainingTax = $totalTax - $soldTax;
$remainingWithTax = $totalWithTax - $soldWithTax;

// Merge categorized entries for further use or display
$sortedEntries = array_merge($redEntries, $yellowEntries, $darkEntries);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> VAT P/S GENERAL TRANSFER LIST Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * {
            font-family: "Lexend", serif;
        }

        body {
            font-size: 12px;
            color: black;
            background-color: white;
            position: relative;
        }

        .page-break {
            margin-top: 10mm;
        }

        .container {
            width: 210mm;
        }

        @media print {
            .container {
                max-width: 100vw !important;
            }

            .hide-on-print {
                display: none;
            }

            .page-break {
                page-break-before: always;
                /* Forces a page break before this section */
            }
        }

        .signature-box {
            border-top: 1px solid black;
            margin-top: 20px;
            text-align: center;
            padding-top: 10px;
        }

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
</head>

<body>
    <main class="m-4">
        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-center m-2">
                <h5 class="card-title text-primary mb-3">VAT P/S GENERNAL TRANSFER LIST PRINT</h5>
                <button class="btn btn-warning btn-sm hide-one-print" onclick="window.print();">
                    <i class="fa fa-print"></i>
                </button>
            </div>
            <table class="table table-bordered table-hover table-fixed">
                <thead class="table-light">
                    <tr>
                        <th>(P/S)#</th>
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
                        $total = $ldata['good']['total_with_tax'] ?? 0;
                        $appendDash = isset($tdata['p_s']) && $tdata['p_s'] === 's';
                        // Fetch company name and weight for $tdata['cr_acc']
                        $crAcc = $tdata['cr_acc'] ?? '';
                        $companyName = $weight = '';
                        if (isset($khaataMap[$crAcc])) {
                            $khaataId = $khaataMap[$crAcc];
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
                                <b><?= ucfirst($ldata['type']); ?>#</b> <?= htmlspecialchars($ldata['p_id']); ?> (<?= htmlspecialchars($ldata['sr_no']); ?>)
                            </td>
                            <td class="fw-bold"><?= my_date(htmlspecialchars($tdata['_date'] ?? 'N/A')); ?></td>
                            <td class="fw-bold"><?= !empty($companyName) ? $companyName : 'N/A'; ?><?= !empty($weight) ? '<br>' . $weight : ''; ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['goods_json']['allotment_name'] ?? 'N/A'); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['transfer']['warehouse_transfer'] ?? 'N/A'); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= goodsName(htmlspecialchars($ldata['good']['goods_id'])); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['size'] ?? 'N/A'); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['brand'] ?? 'N/A'); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['origin'] ?? 'N/A'); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? ' -' : '') . htmlspecialchars($quantity); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? ' -' : '') . htmlspecialchars($netWeight); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? ' -' : '') . htmlspecialchars($grossWeight); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= htmlspecialchars($ldata['good']['goods_json']['rate1'] ?? 'N/A') . ' ' . htmlspecialchars($ldata['good']['goods_json']['price'] ?? 'N/A'); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? ' -' : '') . htmlspecialchars($amount); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? ' -' : '') . htmlspecialchars($tax); ?></td>
                            <td class="<?= $entry['row_color']; ?>"><?= ($appendDash ? ' -' : '') . htmlspecialchars($total); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
            <div class="row">
                <div class="col-4 mt-3 offset-8">
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
                                <td class="text-success fw-semibold"><?= number_format($totalAmount, 2); ?></td>
                                <td class="text-success fw-semibold"><?= number_format($totalTax, 2); ?></td>
                                <td class="text-success fw-semibold"><?= number_format($totalWithTax, 2); ?></td>
                            </tr>
                            <tr>
                                <td class="text-danger fw-bold">Sales</td>
                                <td class="text-danger fw-semibold">-<?= number_format($soldAmount, 2); ?></td>
                                <td class="text-danger fw-semibold">-<?= number_format($soldTax, 2); ?></td>
                                <td class="text-danger fw-semibold">-<?= number_format($soldWithTax, 2); ?></td>
                            </tr>
                            <tr>
                                <td class="text-primary fw-bold">Remainings</td>
                                <td class="text-primary fw-semibold"><?= number_format($remainingAmount, 2); ?></td>
                                <td class="text-primary fw-semibold"><?= number_format($remainingTax, 2); ?></td>
                                <td class="text-primary fw-semibold"><?= number_format($remainingWithTax, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </section>
    </main>
</body>

</html>
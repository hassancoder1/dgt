<?php
$page_title = 'P/S Expenses';
$pageURL = 'purchase-sale-expenses';
$print_url = '';
$editID = '';
include("../connection.php");
if (!isset($_GET['editID'])) {
    $query = "SELECT * FROM purchase_sale_expenses ORDER BY created_at DESC";
    $result = mysqli_query($connect, $query);
    $rows = [];
    while ($one = mysqli_fetch_assoc($result)) {
        $rows[] = $one;
    }
    $print_url = 'print/' . $pageURL;
}
if (isset($_GET['viewID']) || isset($_GET['editID'])) {
    $viewID = isset($_GET['viewID']) ? (int)$_GET['viewID'] : (int)$_GET['editID'];
    $print_url = 'print/' . $pageURL . (isset($_GET['viewID']) ? '?viewID=' . $_GET['viewID'] : '?') . (isset($_GET['print_type']) ? '&print_type=' . $_GET['print_type'] : '&print_type=full_print');
    $query = "SELECT * FROM purchase_sale_expenses WHERE id = $viewID LIMIT 1";
    $result = mysqli_query($connect, $query);
    $record = $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : ['id' => '', 't_id' => '', 'bill_no' => $UniqueBillNo, 'bill_date' => date('d-m-Y'), 'bill_name' => '', 'json_data' => '[]', 'bill_total_amount' => ''];
    $T = [];
    if ($result && mysqli_num_rows($result) > 0) {
        $T = getTransactionInfo($record['t_id']);
    }
}

function getTransactionInfo($id)
{
    global $connect;
    $Ttempdata = mysqli_fetch_assoc(fetch('transactions', ['id' => $id]));
    return array_merge(
        transactionSingle((int)$id),
        [
            'id' => $id,
            'sea_road_array'       => json_decode($Ttempdata['sea_road'] ?? '[]', true),
            'notify_party_details' => json_decode($Ttempdata['notify_party_details'] ?? '[]', true),
            'third_party_bank'     => json_decode($Ttempdata['third_party_bank'] ?? '[]', true),
            'reports'              => json_decode($Ttempdata['reports'] ?? '[]', true)
        ]
    );
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P/S Expenses</title>
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    include '../assets/fonts/lexend.css';
    echo "</style>";
    ?>
    <style>
        * {
            font-family: 'Lexend', serif;
        }

        @media print {
            .hide-on-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="m-3">
        <?php if (!isset($_GET['editID']) && !isset($_GET['viewID'])) { ?>
            <div>
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                        P/S EXPENSES
                    </h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-warning hide-on-print" onclick="window.print()"><i class="fa fa-print"></i></button>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4" id="RecordsTable">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>P/S#</th>
                            <th>Seller Acc.</th>
                            <th>Purchaser Acc.</th>
                            <th>Bill No</th>
                            <th>Bill Name</th>
                            <th>Bill Date</th>
                            <th>Bill Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowCount = 1;
                        foreach ($rows as $row):
                            $T = getTransactionInfo($row['t_id']);
                        ?>
                            <tr class="text-nowrap">
                                <td><?= htmlspecialchars($rowCount); ?></td>
                                <td><?= ucfirst($T['p_s']) . '# ' . $T['sr']; ?></td>
                                <td><?= $T['cr_acc'] . ' ' . $T['cr_acc_name']; ?></td>
                                <td><?= $T['dr_acc'] . ' ' . $T['dr_acc_name']; ?></td>
                                <td><?= htmlspecialchars($row['bill_no']); ?></td>
                                <td><?= strlen($row['bill_name']) > 50 ? htmlspecialchars(substr($row['bill_name'], 0, 50)) . '...' : htmlspecialchars($row['bill_name']); ?></td>
                                <td><?= my_date(htmlspecialchars($row['bill_date'])); ?></td>
                                <td><?= number_format($row['bill_total_amount'], 2); ?></td>
                            </tr>
                        <?php $rowCount++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
    </div>
<?php }
        if (isset($_GET['viewID'])): ?>
    <div class="row">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-2" id="formHeading" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                View Bill
            </h1>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-warning hide-on-print" onclick="window.print()"><i class="fa fa-print"></i></button>
            </div>
        </div>

        <div class="p-2">
            <strong>Bill Name:</strong>
            <span class="fw-semibold"><?= htmlspecialchars($record['bill_name']); ?></span>
        </div>
        <div class="my-2 d-flex gap-2">
            <strong><?= ucfirst($T['p_s']) . '# '; ?>:</strong>
            <span class="fw-semibold"><?= $T['sr']; ?></span><br>
            <strong>Seller:</strong><br>
            <span class="fw-semibold"><?= $T['cr_acc'] . ' ' . $T['cr_acc_name']; ?></span><br>
            <strong>Purchaser:</strong><br>
            <span class="fw-semibold"><?= $T['dr_acc'] . ' ' . $T['dr_acc_name']; ?></span><br>
            <strong>Bill#:</strong>
            <span class="fw-semibold"><?= htmlspecialchars($record['bill_no']); ?></span><br>
            <strong>Date:</strong>
            <span class="fw-semibold"><?= my_date(htmlspecialchars($record['bill_date'])); ?></span><br>
            <strong>Amt.:</strong>
            <span class="fw-semibold"><?= number_format($record['bill_total_amount']); ?></span>
        </div>
        <div>
            <div class="col-12 border-bottom px-2 pb-2 mb-2 row">
                <div class="col-12 mb-2 py-2 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <b><?php echo strtoupper($T['p_s_name']) . ' #'; ?> </b><?php echo $T['sr']; ?>
                    </div>
                    <div><b>User </b><?php echo $T['username']; ?></div>
                    <div><b>Date </b><?php echo my_date($T['_date']); ?></div>
                    <div><b>Type </b><?php echo badge(strtoupper($T['type']), 'dark'); ?></div>
                    <div><b>Country </b><?php echo $T['country']; ?></div>
                    <div><b>Branch </b><?php echo branchName($T['branch_id']); ?></div>
                    <div><b>Status </b>
                        <?php if ($T['locked'] == 0) {
                            echo $T['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                        } else {
                            echo '<i class="fa fa-lock text-success"></i> Transferred.';
                        } ?>
                    </div>
                </div>
                <?php if ($_GET['print_type'] === 'full_print') { ?>
                    <div class="col-3">
                        <div><b>Cr. A/c # </b><?php echo $T['dr_acc']; ?> <sup class="fw-bold text-danger"> (Purchase)</sup></div>
                        <div><b>Cr. A/c Name </b><?php echo $T['dr_acc_name']; ?></div>
                        <?php if (!empty($T['dr_acc_details'])) {
                            echo '<div><b>Company Details </b>' . nl2br($T['dr_acc_details']) . '</div>';
                        } ?>
                    </div>
                    <div class="col-3 border-end border-start">
                        <div><b>Dr. A/c # </b><?php echo $T['cr_acc']; ?> <sup class="fw-bold text-success"> (Sale)</sup></div>
                        <div><b>Dr. A/c Name </b><?php echo $T['cr_acc_name']; ?></div>
                        <?php if (!empty($T['cr_acc_details'])) {
                            echo '<div><b>Company Details </b>' . nl2br($T['cr_acc_details']) . '</div>';
                        } ?>
                    </div>
                <?php } ?>
                <?php if (!empty($T['sea_road_array'])) {
                    if ($T['type'] !== 'local'): ?>
                        <div class="col-<?= $_GET['print_type'] === 'full_print' ? '2' : '3'; ?> mb-3">
                            <h5 class="fw-bold text-primary">By <?= $T['sea_road']; ?></h5>
                            <h6 class="fw-bold">Loading Details</h6>
                            <ul class="list-unstyled">
                                <?php foreach ($T['sea_road_array'] as $key => $value):
                                    if (!empty($value)) {
                                        if (strpos($key, 'l_') === 0):
                                            $key = str_replace('_', ' ', $key); ?>
                                            <li>
                                                <strong><?= is_array($value) ? $value[0] : strtoupper($key); ?>:</strong>
                                                <?= is_array($value) ? $value[1] : $value; ?>
                                            </li>
                                    <?php endif;
                                    } ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-<?= $_GET['print_type'] === 'full_print' ? '2' : '3'; ?> mb-3">
                            <h6 class="fw-bold">Receiving Details</h6>
                            <ul class="list-unstyled">
                                <?php foreach ($T['sea_road_array'] as $key => $value):
                                    if (!empty($value)) {
                                        if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0):
                                            $key = str_replace('_', ' ', $key); ?>
                                            <li>
                                                <strong><?= $key === 'd_date_road' ? 'Arrival Date' : (is_array($value) ? $value[0] : strtoupper($key)); ?>:</strong>
                                                <?= is_array($value) ? $value[1] : $value; ?>
                                            </li>
                                    <?php endif;
                                    } ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                    <?php else: ?>
                        <div class="col-<?= $_GET['print_type'] === 'full_print' ? '4' : '6'; ?> mb-3">
                            <h6 class="fw-bold"><?= $T['sea_road'] == 'sea' ? 'Local' : 'Warehouse'; ?> Details</h6>
                            <ul class="list-unstyled">
                                <?php if ($T['sea_road'] == 'sea'): ?>
                                    <?php
                                    $fields = [
                                        'Truck No' => !empty($T['truck_no']) ? $T['truck_no'] : '',
                                        'Truck Name' => !empty($T['truck_name']) ? $T['truck_name'] : '',
                                        'Loading Company Name' => !empty($T['loading_company_name']) ? $T['loading_company_name'] : '',
                                        'Date' => !empty($T['loading_date']) ? $T['loading_date'] : '',
                                        'Transfer Name' => !empty($T['transfer_name']) ? $T['transfer_name'] : ''
                                    ];
                                    ?>
                                <?php else: ?>
                                    <?php
                                    $fields = [
                                        'Old Company Name' => !empty($T['old_company_name']) ? $T['old_company_name'] : '',
                                        'Transfer Company Name' => !empty($T['transfer_company_name']) ? $T['transfer_company_name'] : '',
                                        'Date' => !empty($T['warehouse_date']) ? $T['warehouse_date'] : ''
                                    ];
                                    ?>
                                <?php endif; ?>

                                <?php foreach ($fields as $label => $value): ?>
                                    <li><strong><?= $label; ?>:</strong> <?= $value; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;
                }
                $qty_no = 0;
                $gross_weight = 0;
                $net_weight = 0;
                $goods_name = '';

                $pur_d_q = fetch('transaction_items', array('parent_id' => $record['t_id']));
                if ($pur_d_q && mysqli_num_rows($pur_d_q) > 0) {
                    while ($details = mysqli_fetch_assoc($pur_d_q)) {
                        if (empty($goods_name)) {
                            $goods_name = goodsName($details['goods_id']);
                        }
                        $qty_no += $details['qty_no'];
                        $gross_weight += $details['total_kgs'];
                        $net_weight += $details['net_kgs'];
                    }
                }

                $table_prefix = ($T['type'] == 'local') ? 'local' : 'general';
                $loadings_data = fetch($table_prefix . '_loading', ['p_id' => $record['t_id']]);
                $uid = $blNos = $Containers = [];

                if ($loadings_data && mysqli_num_rows($loadings_data) > 0) {
                    while ($loading = mysqli_fetch_assoc($loadings_data)) {
                        if ($table_prefix == 'local') {
                            $uid[] = $loading['uid'];
                        } else {
                            $blNos[] = $loading['bl_no'];
                            $Containers[] = json_decode($loading['goods_details'], true)['container_no'];
                        }
                    }
                }

                $uid = array_unique($uid);
                $blNos = array_unique($blNos);
                $Containers = array_unique($Containers);

                if ($qty_no > 0) { ?>
                    <div class="col-<?= $_GET['print_type'] === 'full_print' ? '2' : '6'; ?>">
                        <strong>Good:</strong> <?= htmlspecialchars($goods_name); ?><br>
                        <strong>Quantity:</strong> <?= $qty_no; ?><br>
                        <strong>T.Gross.KGS:</strong> <?= round($gross_weight, 2); ?><br>
                        <strong>T.Net.KGS:</strong> <?= round($net_weight, 2); ?><br>
                        <?php if ($table_prefix == 'local') { ?>
                            <strong>UIDs:</strong> <?= htmlspecialchars(implode(', ', $uid)); ?><br>
                        <?php } else { ?>
                            <strong>Bl No:</strong> <?= htmlspecialchars(implode(', ', $blNos)); ?><br>
                            <strong>Containers:</strong> <?= htmlspecialchars(implode(', ', $Containers)); ?><br>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 3%;">Sr.</th>
                    <th style="width: 40%;">Details</th>
                    <th>Payment</th>
                    <th>Currency</th>
                    <th>Operator</th>
                    <th>Rate</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $sr_no = 1;
                if (isset($record['json_data'])):
                    $entries = json_decode($record['json_data'], true);
                    foreach ($entries as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($sr_no); ?></td>
                            <td><?= htmlspecialchars($entry['details']); ?></td>
                            <td><?= htmlspecialchars($entry['payment']); ?></td>
                            <td><?= htmlspecialchars($entry['currency']); ?></td>
                            <td><?= htmlspecialchars($entry['operator']); ?></td>
                            <td><?= htmlspecialchars($entry['rate']); ?></td>
                            <td><?= number_format(htmlspecialchars($entry['totalAmount'])); ?></td>
                        </tr>
                <?php $sr_no++;
                    endforeach;
                endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Total Display Section -->
    <div class="d-flex justify-content-end align-items-center">
        <div class="border p-4 rounded-3">
            <div><strong>Total Amount:</strong> <span><?= number_format($record['bill_total_amount'] ?? 0, 2); ?></span></div>
        </div>
    </div>

    <?php
            $roznamcha = json_decode($record['roznamcha_transfer'], true) ?? [];
            if (!empty($roznamcha)) {
                $p_acc_id = $roznamcha['p_acc_id'];
                $s_acc_id = $roznamcha['s_acc_id'];
                $khaatas = $connect->query("SELECT id, khaata_no, khaata_name, email, phone FROM khaata WHERE id IN ('$p_acc_id', '$s_acc_id')");
                $p_acc_details = $s_acc_details = [];
                while ($khaata = mysqli_fetch_assoc($khaatas)) {
                    if ($khaata['id'] == $p_acc_id) {
                        $p_acc_details = $khaata;
                    } elseif ($khaata['id'] == $s_acc_id) {
                        $s_acc_details = $khaata;
                    }
                }
            }
            $roznamcha = array_merge($roznamcha, ['amount' => $record['bill_total_amount'], 'transfer_date' => $roznamcha['transfer_date'] ?? date('Y-m-d'), 'final_amount' => '', 'rate' => '', 'details' => 'Final Amount: ' . number_format($record['bill_total_amount'])]);
            if (!empty($roznamcha) && $_GET['print_type'] === 'full_print') { ?>
        <div class="row my-2 px-4">
            <div class="col-6">
                <h6><strong>Purchaser: </strong></h6>
                <span><strong>Acc No: </strong><?= $p_acc_details['khaata_no']; ?></span><br>
                <span><strong>Acc Name: </strong><?= $p_acc_details['khaata_name']; ?></span><br>
                <span><strong>Email: </strong><?= $p_acc_details['email']; ?></span><br>
                <span><strong>Phone: </strong><?= $p_acc_details['phone']; ?></span><br>
            </div>
            <div class="col-6">
                <h6><strong>Seller: </strong></h6>
                <span><strong>Acc No: </strong><?= $s_acc_details['khaata_no']; ?></span><br>
                <span><strong>Acc Name: </strong><?= $s_acc_details['khaata_name']; ?></span><br>
                <span><strong>Email: </strong><?= $s_acc_details['email']; ?></span><br>
                <span><strong>Phone: </strong><?= $s_acc_details['phone']; ?></span><br>
            </div>
        </div>
    <?php } ?>
    <form method="post" class="m-4">
        <?php
            if (!empty($roznamcha)) {
                $rozQ = fetch('roznamchaas', array('r_type' => 'P/S Expenses Bill', 'transfered_from_id' => $record['id'], 'transfered_from' => 'purchase-sale-expenses'));
                if (mysqli_num_rows($rozQ) > 0) { ?>
                <table class="table table-sm table-bordered my-3">
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date</th>
                            <th>A/c#</th>
                            <th>Roz.#</th>
                            <th>Name</th>
                            <th>No</th>
                            <th>Details</th>
                            <th>Dr.</th>
                            <th>Cr.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                            $dr = $cr = 0; ?>
                            <input type="hidden" value="<?php echo $roz['r_date']; ?>"
                                id="temp_transfer_date">
                            <input type="hidden" value="<?php echo $roz['r_id']; ?>"
                                name="r_id[]">
                            <input type="hidden" value="<?php echo $roz['branch_serial']; ?>"
                                name="b_serial[]">
                            <tr>
                                <td>
                                    <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                </td>
                                <td><?php echo $roz['r_date']; ?></td>
                                <td>
                                    <a href="ledger?back-khaata-no=<?php echo $roz['khaata_no']; ?>"
                                        target="_blank"><?php echo $roz['khaata_no']; ?></a>
                                </td>
                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                <td class="small"><?php echo $roz['r_name']; ?></td>
                                <td><?php echo $roz['r_no']; ?></td>
                                <td class="small"><?php echo $roz['details']; ?></td>
                                <?php if ($roz['dr_cr'] == "dr") {
                                    $dr = $roz['amount'];
                                } else {
                                    $cr = $roz['amount'];
                                } ?>
                                <td class="text-success"><?php echo $dr; ?></td>
                                <td class="text-danger"><?php echo $cr; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
        <?php }
            } ?>
    </form>
    </div>
    </div>
<?php endif; ?>

<?php include("../footer.php"); ?>
<script>
    $(document).ready(function() {
        let srNo = 1;

        function calculateTotals() {
            let qty = parseFloat($('#payment').val()) || 0;
            let rate = parseFloat($('#rate').val()) || 0;
            let totalAmount;
            if ($('#operator').val() === '/') {
                totalAmount = qty / rate;
            } else {
                totalAmount = qty * rate;
            }
            $('#totalAmount').val(totalAmount)
        }

        function clearInputBoxes() {
            $('#details, #payment, #currency, #operator, #rate, #totalAmount').val('');
            $('#details').focus();
        }

        function addRowToTable() {
            let details = $('#details').val();
            let payment = $('#payment').val();
            let currency = $('#currency').val();
            let operator = $('#operator').val();
            let rate = $('#rate').val();
            let totalAmount = $('#totalAmount').val();
            if (details && payment && rate) {
                let newRow = `
            <tr>
                <td>${srNo}</td>
                <td>${details}</td>
                <td>${payment}</td>
                <td>${currency}</td>
                <td>${operator}</td>
                <td>${rate}</td>
                <td>${totalAmount}</td>
                <td>
                    <button class="btn btn-sm p-1 btn-warning editRow"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm p-1 btn-danger deleteRow"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
                $('#addedRowsTable tbody').append(newRow);
                srNo++;
                $('#srNo').val(srNo);
                addHiddenInputs();
                clearInputBoxes();
                updateTotals();
            } else {
                alert('Please fill all fields before adding the row.');
            }
        }

        function addHiddenInputs() {
            let rowCount = $('#addedRowsTable tbody tr').length;
            let hiddenInputs = '';
            $('#addedRowsTable tbody tr').each(function() {
                let details = $(this).find('td:eq(1)').text();
                let payment = $(this).find('td:eq(2)').text();
                let currency = $(this).find('td:eq(3)').text();
                let operator = $(this).find('td:eq(4)').text();
                let rate = $(this).find('td:eq(5)').text();
                let totalAmount = $(this).find('td:eq(6)').text();
                hiddenInputs += `<input type="hidden" name="details[]" value="${details}" />`;
                hiddenInputs += `<input type="hidden" name="payment[]" value="${payment}" />`;
                hiddenInputs += `<input type="hidden" name="currency[]" value="${currency}" />`;
                hiddenInputs += `<input type="hidden" name="operator[]" value="${operator}" />`;
                hiddenInputs += `<input type="hidden" name="rate[]" value="${rate}" />`;
                hiddenInputs += `<input type="hidden" name="totalAmount[]" value="${totalAmount}" />`;
            });
            $('#hiddenInputsContainer').html(hiddenInputs);
        }

        function updateTotals() {
            let totalAmount = 0;
            $('#addedRowsTable tbody tr').each(function() {
                totalAmount += parseFloat($(this).find('td:eq(6)').text()) || 0;
            });
            $('#parentBillAmount').val(totalAmount.toFixed(2));
        }
        $(document).on('input', '#payment, #operator, #rate', calculateTotals);
        $(document).on('keydown', 'input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let inputId = $(this).attr('id');
                if (inputId === 'details') {
                    $('#payment').focus();
                } else if (inputId === 'payment') {
                    $('#currency').focus();
                } else if (inputId === 'currency') {
                    $('#operator').focus();
                } else if (inputId === 'operator') {
                    $('#rate').focus();
                } else if (inputId === 'rate') {
                    calculateTotals();
                    addRowToTable();
                }
            }
        });
        let isEditing = false;
        $(document).on('click', '.editRow', function() {
            if (!isEditing) {
                isEditing = true;
                let row = $(this).closest('tr');
                $('#srNo').val(row.find('td:eq(0)').text());
                $('#details').val(row.find('td:eq(1)').text());
                $('#payment').val(row.find('td:eq(2)').text());
                $('#currency').val(row.find('td:eq(3)').text());
                $('#operator').val(row.find('td:eq(4)').text());
                $('#rate').val(row.find('td:eq(5)').text());
                $('#totalAmount').val(row.find('td:eq(6)').text());
                row.remove();
                updateTotals();
                addHiddenInputs();
            } else {
                alert('Please save the Bill first!');
                exit();
            }
        });

        // Delete row
        $(document).on('click', '.deleteRow', function() {
            $(this).closest('tr').remove();
            updateTotals();
            addHiddenInputs();
        });
    });

    function searchAcc(Acc) {
        var AccNo = $(Acc).val().toUpperCase();
        $.ajax({
            type: 'POST',
            url: 'ajax/fetchAgentDetails.php',
            data: 'acc_no=' + AccNo,
            success: function(html) {
                let data = JSON.parse(html).data;
                if (data.acc_no !== '') {
                    $(Acc).addClass('is-valid');
                    $(Acc).removeClass('is-invalid');
                    $(Acc + '_name').html('( ' + data.acc_name + ' )').removeClass('d-none');
                    $(Acc + '_id').val(data.row_id);
                } else {
                    $(Acc).removeClass('is-valid');
                    $(Acc).addClass('is-invalid');
                }
            },
            error: function(err) {

            }
        });
    }

    function searchTransaction(Transaction, Type, resultInput = '#parentTransactionId') {
        var TSr = $(Transaction).val();
        var Ttype = $(Type).val();
        $.ajax({
            type: 'POST',
            url: 'ajax/searchTransactionbySr.php',
            data: {
                t_sr: TSr,
                t_type: Ttype
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.id == 0) {
                    $(Transaction).addClass('is-invalid').removeClass('is-valid');
                    $(resultInput).val('');
                } else {
                    $(Transaction).addClass('is-valid').removeClass('is-invalid');
                    $(resultInput).val(response.id);
                }
            },
            error: function(err) {
                $(Transaction).addClass('is-invalid').removeClass('is-valid');
                $(resultInput).val('');
            }
        });
    }

    function lastAmount() {
        let amount = $("#amount").val();
        let rate = $("#rate").val();
        let opr = $('#opr').find(":selected").val();
        let final_amount;

        if (amount && rate) { // Ensure both amount and rate have values
            if (opr === "/") {
                final_amount = Number(amount) / Number(rate);
            } else {
                final_amount = Number(amount) * Number(rate);
            }
            final_amount = final_amount.toFixed(2);
            $("#final_amount").val(final_amount);

            let balance = $("#balance").val();
            balance = parseFloat(balance);
            if (balance >= 1) {} else {
                disableButton('recordSubmit');
            }
        }
    }
</script>
<?php
if (isset($_GET['deleteID'])) {
    $id = mysqli_real_escape_string($connect, $_GET['deleteID']);
    $done = mysqli_query($connect, "DELETE FROM purchase_sale_expenses WHERE id='$id'");
    if ($done) {
        messageNew('success', $pageURL, 'Record Deleted!');
    }
}
if (isset($_POST['SubmitBill'])) {
    $billNo = mysqli_real_escape_string($connect, $_POST['parentBillNo']);
    $billName = mysqli_real_escape_string($connect, $_POST['parentBillName']);
    $parentDate = mysqli_real_escape_string($connect, $_POST['parentDate']);
    $billtotalAmount = mysqli_real_escape_string($connect, $_POST['parentBillAmount']);
    $billTransactionId = mysqli_real_escape_string($connect, $_POST['parentTransactionId']);
    $editID = mysqli_real_escape_string($connect, $_POST['editID']);
    $entries = [];

    if (!empty($_POST['details']) && count($_POST['details']) > 0) {
        $details = $_POST['details'];
        $payments = $_POST['payment'];
        $currencies = $_POST['currency'];
        $operators = $_POST['operator'];
        $rates = $_POST['rate'];
        $totalAmounts = $_POST['totalAmount'];
        for ($i = 0; $i < count($details); $i++) {
            $entries[] = [
                'details' => mysqli_real_escape_string($connect, $details[$i]),
                'payment' => (float) $payments[$i],
                'currency' => $currencies[$i],
                'operator' => $operators[$i],
                'rate' => (float) $rates[$i],
                'totalAmount' => (float) $totalAmounts[$i],
            ];
        }
    }
    $entriesJSON = json_encode($entries);
    if ((float)$billtotalAmount !== (float)array_sum(array_column($entries, 'totalAmount'))) {
        $billtotalAmount = array_sum(array_column($entries, 'totalAmount'));
    }

    if (!empty($editID)) {
        $done = update('purchase_sale_expenses', [
            't_id' => $billTransactionId,
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'bill_date' => $parentDate,
            'json_data' => $entriesJSON,
            'bill_total_amount' => $billtotalAmount
        ], ['id' => $editID]);
    } else {
        $done = insert('purchase_sale_expenses', [
            't_id' => $billTransactionId,
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'bill_date' => $parentDate,
            'json_data' => $entriesJSON,
            'bill_total_amount' => $billtotalAmount
        ]);
    }

    if ($done) {
        messageNew('success', $pageURL, 'Success');
    }
}

if (isset($_POST['PaymentSubmit'])) {
    unset($_POST['PaymentSubmit']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['p_acc_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['p_acc_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['s_acc_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['s_acc_id']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']) . " | Amount: $amount " . $_POST['currency1'] . " " . $_POST['opr'] . " " . $_POST['rate'] . ' = ' . $final_amount . " " . $_POST['currency2'];
    $bill_id = mysqli_real_escape_string($connect, $_POST['id']);
    $type_post = "P/S Expenses";
    $url = $pageURL . '?viewID=' . $bill_id;
    $type = 'PS-Expenses';
    $transfered_from = 'purchase-sale-expenses';
    $r_type = 'P/S Expenses Bill';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $bill_id,
            'user_id' => $_SESSION['userId'],
            'username' => $_SESSION['username'],
            'r_date' => $transfer_date,
            'roznamcha_no' => $bill_id,
            'r_name' => $type,
            'r_no' => $bill_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " P/S Expenses # " . $bill_id;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = 1 + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            update('purchase_sale_expenses', ['roznamcha_transfer' => json_encode($_POST, true)], ['id' => $bill_id]);
            $msg = 'Transferred to Business Roznamcha ' . $str;
            $msgType = 'success';
        } else {
            $msg = 'Transfer Error ';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    message($msgType, $url, $msg);
}
?>
</div>
</body>

</html>
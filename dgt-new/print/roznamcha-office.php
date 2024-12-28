<?php
$page_title = 'Roznamcha Office';
$pageURL = 'roznamcha-office';
$print_url = '';
$editID = '';
include("../connection.php");
if (!isset($_GET['editID'])) {
    $query = "SELECT * FROM roznamcha_office ORDER BY created_at DESC";
    $result = mysqli_query($connect, $query);
    $rows = [];
    while ($one = mysqli_fetch_assoc($result)) {
        $rows[] = $one;
    }
    $print_url = 'print/' . $pageURL;
}
if (isset($_GET['viewID']) || isset($_GET['editID'])) {
    // Determine which ID to use (viewID or editID)
    $viewID = isset($_GET['viewID']) ? (int)$_GET['viewID'] : (int)$_GET['editID'];
    $print_url = 'print/' . $pageURL . (isset($_GET['viewID']) ? '?viewID=' . $_GET['viewID'] : '');
    $query = "SELECT * FROM roznamcha_office WHERE id = $viewID LIMIT 1";
    $result = mysqli_query($connect, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
    } else {
        $record = ['id' => '', 'bill_no' => '', 'bill_name' => '', 'json_data' => '[]', 'bill_amount' => '', 'total_vta_amount' => '', 'final_bill_amount' => '',];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Roznamcha</title>
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
                        OFFICE EXPENSES
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
                            <th>Bill No</th>
                            <th>Bill Name</th>
                            <th>Bill Date</th>
                            <th>Amount</th>
                            <th>VAT Total</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowCount = 1;
                        foreach ($rows as $row): ?>
                            <tr class="text-nowrap">
                                <td><?= htmlspecialchars($rowCount); ?></td>
                                <td><?= htmlspecialchars($row['bill_no']); ?></td>
                                <td><?= htmlspecialchars($row['bill_name']); ?></td>
                                <td><?= my_date(htmlspecialchars($row['bill_date'])); ?></td>
                                <td><?= number_format($row['total_amount'], 2); ?></td>
                                <td><?= number_format($row['tax_amount'], 2); ?></td>
                                <td><?= number_format($row['final_amount'], 2); ?></td>
                            </tr>
                        <?php $rowCount++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
    </div>
<?php }
        if (isset($_GET['viewID'])): ?>
    <div>
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-2" id="formHeading" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                Office Expenses (View Bill)
            </h1>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-warning hide-on-print" onclick="window.print()"><i class="fa fa-print"></i></button>
            </div>
        </div>
        <div class="mt-4">
            <!-- Bill Information Section -->
            <div class="row mb-4">
                <!-- Bill Number -->
                <div class="col-3">
                    <div class="border p-3 rounded-3">
                        <strong>Bill Number:</strong><br>
                        <span class="fw-semibold"><?= htmlspecialchars($record['bill_no']); ?></span>
                    </div>
                </div>

                <!-- Date -->
                <div class="col-3">
                    <div class="border p-3 rounded-3">
                        <strong>Date:</strong><br>
                        <span class="fw-semibold"><?= my_date(htmlspecialchars($record['bill_date'])); ?></span>
                    </div>
                </div>

                <!-- Bill Name -->
                <div class="col-6">
                    <div class="border p-3 rounded-3">
                        <strong>Bill Name:</strong><br>
                        <span class="fw-semibold"><?= htmlspecialchars($record['bill_name']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Table (view-only) -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 3%;">Sr.</th>
                            <th style="width: 40%;">Details</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>VTA %</th>
                            <th>VTA Total</th>
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
                                    <td><?= htmlspecialchars($entry['qty']); ?></td>
                                    <td><?= htmlspecialchars($entry['price']); ?></td>
                                    <td><?= htmlspecialchars($entry['amount']); ?></td>
                                    <td><?= htmlspecialchars($entry['vatPercent']); ?></td>
                                    <td><?= htmlspecialchars($entry['vatTotal']); ?></td>
                                    <td><?= htmlspecialchars($entry['totalAmount']); ?></td>
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
                    <div><strong>Total Amount:</strong> <span><?= $record['total_amount'] ?? 0; ?></span></div>
                    <div><strong>Total VAT Amount:</strong> <span><?= $record['tax_amount'] ?? 0; ?></span></div>
                    <div><strong>Grand Total:</strong> <span><?= $record['final_amount'] ?? 0; ?></span></div>
                </div>
            </div>

            <?php
            $roznamcha = json_decode($record['roznamcha_transfer'], true) ?? [];
            $roznamcha = array_merge($roznamcha, ['amount' => $record['final_amount'], 'transfer_date' => $roznamcha['transfer_date'] ?? date('Y-m-d'), 'final_amount' => '', 'rate' => '']);
            if (!empty($roznamcha)) {
                $rozQ = fetch('roznamchaas', array('r_type' => 'Office Bill', 'transfered_from_id' => $record['id'], 'transfered_from' => 'office-roznamcha'));
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
        </div>
    </div>
<?php endif; ?>

<?php include("../footer.php"); ?>
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
    $(document).ready(function() {
        // Function to calculate totals for a single row
        function calculateRow(row) {
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            let vat = parseFloat(row.find('.vat').val()) || 0;

            let amount = qty * price; // Calculate amount (Qty * Price)
            let vatTotal = (amount * vat) / 100; // Calculate VAT Total
            let totalAmount = amount + vatTotal; // Calculate Total Amount (Amount + VAT)

            // Update the row's fields with calculated values
            row.find('.amount').val(amount.toFixed(2));
            row.find('.vatTotal').val(vatTotal.toFixed(2));
            row.find('.totalAmount').val(totalAmount.toFixed(2));
        }

        // Function to calculate the grand totals
        function calculateGrandTotals() {
            let totalAmount = 0;
            let totalVTA = 0;
            let grandBillAmt = 0;

            // Iterate through each row and sum up totals
            $('#invoiceTable tr').each(function() {
                totalAmount += parseFloat($(this).find('.amount').val()) || 0;
                totalVTA += parseFloat($(this).find('.vatTotal').val()) || 0;
                grandBillAmt += parseFloat($(this).find('.totalAmount').val()) || 0;
            });

            // Update the total amounts in the display
            $('#totalAmount').text(totalAmount.toFixed(2));
            $('#totalVTA').text(totalVTA.toFixed(2));
            $('#grandBillAmt').text(grandBillAmt.toFixed(2));
        }

        // Add a new row when the "Add Row" button is clicked
        $('#addRow').click(function() {
            let newRow = `<tr>
                        <td><input type="text" class="form-control form-control-sm" name="billNo[]"></td>
                        <td><input type="text" class="form-control form-control-sm" name="details[]"></td>
                        <td><input type="number" class="form-control form-control-sm qty" name="qty[]" min="0" value="0"></td>
                        <td><input type="number" class="form-control form-control-sm price" name="price[]" min="0" value="0"></td>
                        <td><input type="number" class="form-control form-control-sm amount" name="amount[]" readonly></td>
                        <td><input type="number" class="form-control form-control-sm vat" name="vat[]" min="0" value="0"></td>
                        <td><input type="number" class="form-control form-control-sm vatTotal" name="vatTotal[]" readonly></td>
                        <td><input type="number" class="form-control form-control-sm totalAmount" name="totalAmount[]" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">&times;</button></td>
                    </tr>`;
            $('#invoiceTable').append(newRow); // Append the new row to the table
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove(); // Remove the closest row
            calculateGrandTotals(); // Recalculate the grand totals after removing a row
        });

        // Recalculate totals whenever qty, price, or VAT changes
        $(document).on('input', '.qty, .price, .vat', function() {
            let row = $(this).closest('tr'); // Get the current row
            calculateRow(row); // Recalculate the row totals
            calculateGrandTotals(); // Recalculate the grand totals
        });
    });
</script>
<?php
if (isset($_GET['deleteID'])) {
    $id = mysqli_real_escape_string($connect, $_GET['deleteID']);
    $done = mysqli_query($connect, "DELETE FROM roznamcha_office WHERE id='$id'");
    if ($done) {
        messageNew('success', $pageURL, 'Record Deleted!');
    }
}
// Handle Form Submission
if (isset($_POST['SubmitBill'])) {
    $billNo = mysqli_real_escape_string($connect, $_POST['parentBillNo']);
    $billName = mysqli_real_escape_string($connect, $_POST['parentBillName']);
    $editID = mysqli_real_escape_string($connect, $_POST['editID']);
    $entries = [];

    if (isset($_POST['billNo'])) {
        $billNos = $_POST['billNo'];
        $details = $_POST['details'];
        $qtys = $_POST['qty'];
        $prices = $_POST['price'];
        $amounts = $_POST['amount'];
        $vatPercents = $_POST['vat'];
        $vatTotals = $_POST['vatTotal'];
        $totalAmounts = $_POST['totalAmount'];

        for ($i = 0; $i < count($billNos); $i++) {
            $entries[] = [
                'billNo' => mysqli_real_escape_string($connect, $billNos[$i]),
                'details' => mysqli_real_escape_string($connect, $details[$i]),
                'qty' => (float) $qtys[$i],
                'price' => (float) $prices[$i],
                'amount' => (float) $amounts[$i],
                'vatPercent' => (float) $vatPercents[$i],
                'vatTotal' => (float) $vatTotals[$i],
                'totalAmount' => (float) $totalAmounts[$i],
            ];
        }
    }

    $entriesJSON = json_encode($entries);
    $totalAmount = array_sum(array_column($entries, 'amount'));
    $totalVTAAmount = array_sum(array_column($entries, 'vatTotal'));
    $finalBillAmount = array_sum(array_column($entries, 'totalAmount'));

    if (!empty($editID)) {
        $done = update('roznamcha_office', [
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'json_data' => $entriesJSON,
            'bill_amount' => $totalAmount,
            'total_vta_amount' => $totalVTAAmount,
            'final_bill_amount' => $finalBillAmount,
        ], ['id' => $editID]);
        if ($done) {
            messageNew('success', $pageURL . '?viewID=' . $editID, 'Bill Updated!');
        }
    } else {
        // Insert New Record
        $done = insert('roznamcha_office', [
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'json_data' => $entriesJSON,
            'bill_amount' => $totalAmount,
            'total_vta_amount' => $totalVTAAmount,
            'final_bill_amount' => $finalBillAmount,
        ]);

        if ($done) {
            messageNew('success', $pageURL, 'Bill Added!');
        }
    }
}
?>
</div>
</body>

</html>
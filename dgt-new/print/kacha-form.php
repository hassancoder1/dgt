<?php
$page_title = 'Roznamcha Office';
$pageURL = 'roznamcha-office';
$print_url = '';
$editID = '';
include("../connection.php");
if (!isset($_GET['editID'])) {
    $query = "SELECT * FROM kacha_form ORDER BY created_at DESC";
    $result = mysqli_query($connect, $query);
    $rows = [];
    while ($one = mysqli_fetch_assoc($result)) {
        $rows[] = $one;
    }
    $print_url = 'print/' . $pageURL;
}
if (isset($_GET['viewID']) || isset($_GET['editID'])) {
    $viewID = isset($_GET['viewID']) ? (int)$_GET['viewID'] : (int)$_GET['editID'];
    $print_url = 'print/' . $pageURL . (isset($_GET['viewID']) ? '?viewID=' . $_GET['viewID'] : '');
    $query = "SELECT * FROM kacha_form WHERE id = $viewID LIMIT 1";
    $result = mysqli_query($connect, $query);
    $record = $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : ['id' => '', 'acc_no' => '', 'acc_id' => '', 'bill_no' => $UniqueBillNo, 'bill_date' => date('d-m-Y'), 'bill_name' => '', 'json_data' => '[]', 'bill_total_amount' => ''];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kacha Form</title>
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
                        KACHA FORM
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
                            <th>Acc No</th>
                            <th>Bill Date</th>
                            <th>Bill Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowCount = 1;
                        foreach ($rows as $row): ?>
                            <tr class="text-nowrap">
                                <td><?= htmlspecialchars($rowCount); ?></td>
                                <td><?= htmlspecialchars($row['bill_no']); ?></td>
                                <td><?= strlen($row['bill_name']) > 50 ? htmlspecialchars(substr($row['bill_name'], 0, 50)) . '...' : htmlspecialchars($row['bill_name']); ?></td>
                                <td><?= htmlspecialchars($row['acc_no']); ?></td>
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
    <div>
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-2" id="formHeading" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                KACHA FORM (View Bill)
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
                        <strong>Bill Name: </strong>
                        <span class="fw-semibold"><?= htmlspecialchars($record['bill_name']); ?></span><br>
                        <strong>Acc: </strong>
                        <span class="fw-semibold"><?= htmlspecialchars($record['acc_no']) . ' ' . khaataSingle($record['acc_id'])['khaata_name']; ?></span><br>
                    </div>
                </div>
            </div>

            <!-- Dynamic Table (view-only) -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 3%;">Sr.</th>
                            <th>Date</th>
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
                                    <td><?= htmlspecialchars($entry['date']); ?></td>
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
                    <div><strong>Total Amount:</strong> <span><?= $record['bill_total_amount'] ?? 0; ?></span></div>
                </div>
            </div>
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
            $('#details, #date, #payment, #currency, #operator, #rate, #totalAmount').val('');
            $('#details').focus();
        }

        function addRowToTable() {
            let date = $('#date').val();
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
                <td>${date}</td>
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
                let date = $(this).find('td:eq(1)').text();
                let details = $(this).find('td:eq(2)').text();
                let payment = $(this).find('td:eq(3)').text();
                let currency = $(this).find('td:eq(4)').text();
                let operator = $(this).find('td:eq(5)').text();
                let rate = $(this).find('td:eq(6)').text();
                let totalAmount = $(this).find('td:eq(7)').text();
                hiddenInputs += `<input type="hidden" name="date[]" value="${date}" />`;
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
                totalAmount += parseFloat($(this).find('td:eq(7)').text()) || 0;
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
                $('#date').val(row.find('td:eq(1)').text());
                $('#details').val(row.find('td:eq(2)').text());
                $('#payment').val(row.find('td:eq(3)').text());
                $('#currency').val(row.find('td:eq(4)').text());
                $('#operator').val(row.find('td:eq(5)').text());
                $('#rate').val(row.find('td:eq(6)').text());
                $('#totalAmount').val(row.find('td:eq(7)').text());
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
</script>
<?php
if (isset($_GET['deleteID'])) {
    $id = mysqli_real_escape_string($connect, $_GET['deleteID']);
    $done = mysqli_query($connect, "DELETE FROM kacha_form WHERE id='$id'");
    if ($done) {
        messageNew('success', $pageURL, 'Record Deleted!');
    }
}
if (isset($_POST['SubmitBill'])) {
    $AccNo = mysqli_real_escape_string($connect, $_POST['parentAccNo']);
    $AccId = mysqli_real_escape_string($connect, $_POST['parentAccId']);
    $billNo = mysqli_real_escape_string($connect, $_POST['parentBillNo']);
    $billName = mysqli_real_escape_string($connect, $_POST['parentBillName']);
    $parentDate = mysqli_real_escape_string($connect, $_POST['parentDate']);
    $billtotalAmount = mysqli_real_escape_string($connect, $_POST['parentBillAmount']);
    $editID = mysqli_real_escape_string($connect, $_POST['editID']);
    $entries = [];

    if (!empty($_POST['details']) && count($_POST['details']) > 0) {
        $date = $_POST['date'];
        $details = $_POST['details'];
        $payments = $_POST['payment'];
        $currencies = $_POST['currency'];
        $operators = $_POST['operator'];
        $rates = $_POST['rate'];
        $totalAmounts = $_POST['totalAmount'];
        for ($i = 0; $i < count($details); $i++) {
            $entries[] = [
                'date' => mysqli_real_escape_string($connect, $date[$i]),
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
        $done = update('kacha_form', [
            'acc_no' => $AccNo,
            'acc_id' => $AccId,
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'bill_date' => $parentDate,
            'json_data' => $entriesJSON,
            'bill_total_amount' => $billtotalAmount
        ], ['id' => $editID]);
    } else {
        $done = insert('kacha_form', [
            'acc_no' => $AccNo,
            'acc_id' => $AccId,
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'bill_date' => $parentDate,
            'json_data' => $entriesJSON,
            'bill_total_amount' => $billtotalAmount
        ]);
    }

    if ($done) {
        messageNew('success', $pageURL, 'Bill Added!');
    }
}
?>
</div>
</body>

</html>
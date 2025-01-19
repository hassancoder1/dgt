<?php
$page_title = 'Kacha Office';
$pageURL = 'kacha-form';
$print_url = '';
$editID = '';
include("header.php");
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
    $billNumbers = [];
    $query = "SELECT bill_no FROM kacha_form";
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $billNumbers[] = $row['bill_no'];
    }
    $UniqueBillNo = generateUniqueBillNo($billNumbers);
    $query = "SELECT * FROM kacha_form WHERE id = $viewID LIMIT 1";
    $result = mysqli_query($connect, $query);
    $record = $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : ['id' => '', 'acc_no' => '', 'acc_id' => '', 'bill_no' => $UniqueBillNo, 'bill_date' => date('d-m-Y'), 'bill_name' => '', 'json_data' => '[]', 'bill_total_amount' => ''];
}
function generateUniqueBillNo($existingBillNumbers)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $billCodeLength = 8;
    do {
        $uniqueBillNo = '';
        for ($i = 0; $i < $billCodeLength; $i++) {
            $uniqueBillNo .= $characters[rand(0, strlen($characters) - 1)];
        }
    } while (in_array($uniqueBillNo, $existingBillNumbers));
    return $uniqueBillNo;
}
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="bg-white p-3 <?= isset($_GET['editID']) ? 'container mx-auto' : 'mx-5'; ?>" style="margin-top:-30px;">
    <?php if (!isset($_GET['editID']) && !isset($_GET['viewID'])) { ?>
        <div>
            <div class="d-flex justify-content-between align-items-center w-100">
                <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                    Kacha Form
                </h1>
                <div class="d-flex gap-2">
                    <div class="dropdown me-2">
                        <button class="btn btn-dark btn-sm" onclick="window.location.href = '?editID='">
                            <i class="fas fa-plus"></i> New
                        </button>

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
                            <th>Action</th>
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
                                <td>
                                    <a href="?viewID=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="?editID=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="?deleteID=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php $rowCount++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php }
    if (isset($_GET['editID']) && !isset($_GET['viewID'])): ?>
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-2" id="formHeading" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                    <?= isset($_GET['editID']) && empty($_GET['editID']) ? 'New' : 'Edit'; ?> Bill
                </h1>
                <div class="me-2">
                    <a href="<?= $pageURL; ?>" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="editID" value="<?= $record['id']; ?>">
                <div class="row mt-3">
                    <!-- Parent Fields -->
                    <small class="fw-bold text-dark d-none my-1" id="acc_name"></small>
                    <div class="col-md-2 mb-3 d-flex align-items-center justify-content-center">
                        <label for="acc" class="form-label me-2 text-nowrap">Acc#</label>
                        <input type="text" id="acc" value="<?= htmlspecialchars($record['acc_no']); ?>" name="parentAccNo" class="form-control form-control-sm" placeholder="Search Acc." onkeyup="searchAcc('#acc')">
                        <input type="hidden" name="parentAccId" id="acc_id" value="<?= $record['acc_id'] ?? ''; ?>">
                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-center justify-content-center">
                        <label for="parentBillNo" class="form-label me-2 text-nowrap">Bill#</label>
                        <input type="text" id="parentBillNo" value="<?= htmlspecialchars($record['bill_no']); ?>" name="parentBillNo" class="form-control form-control-sm" placeholder="Enter Bill Number" readonly>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-center justify-content-center">
                        <label for="parentDate" class="form-label me-2 text-nowrap">Date</label>
                        <input type="text" id="parentDate" value="<?= htmlspecialchars(date('d-m-Y')); ?>" name="parentDate" class="form-control form-control-sm" placeholder="Bill Date">
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-center justify-content-center">
                        <label for="parentBillAmount" class="form-label me-2 text-nowrap">Total Bill Amount</label>
                        <input type="text" id="parentBillAmount" value="<?= htmlspecialchars($record['bill_total_amount']); ?>" name="parentBillAmount" class="form-control form-control-sm" placeholder="Total Bill Amount">
                    </div>
                    <div class="col-md-12 mb-3 d-flex align-items-center justify-content-center">
                        <label for="parentBillName" class="form-label text-nowrap me-2">Bill Name</label>
                        <input type="text" id="parentBillName" value="<?= htmlspecialchars($record['bill_name']); ?>" name="parentBillName" class="form-control form-control-sm" placeholder="Enter Bill Name">
                    </div>

                </div>

                <!-- Input Row -->
                <div id="inputRow" class="row g-1 mb-3">
                    <div class="col-md-1">
                        <label>SR.No</label>
                        <input type="text" id="srNo" class="form-control form-control-sm" value="1" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Details</label>
                        <input type="text" id="details" class="form-control form-control-sm" placeholder="Enter Details">
                    </div>
                    <div class="col-md-2">
                        <label>Payment</label>
                        <input type="number" id="payment" class="form-control form-control-sm" min="0" placeholder="payment">
                    </div>
                    <div class="col-md-2">
                        <label>Currency</label>
                        <select id="currency" class="form-select form-select-sm">
                            <option value="" selected>Select</option>
                            <?php $currencies = fetch('currencies');
                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                echo '<option  value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Rate</label>
                        <input type="number" id="rate" class="form-control form-control-sm" min="0" placeholder="Rate">
                    </div>
                    <div class="col-md-2">
                        <label>Total Amount</label>
                        <input type="text" id="totalAmount" class="form-control form-control-sm" readonly>
                    </div>
                </div>

                <!-- Table -->
                <table class="table table-bordered" id="addedRowsTable">
                    <thead>
                        <tr>
                            <th>SR.No</th>
                            <th>Details</th>
                            <th>Payment</th>
                            <th>Currency</th>
                            <th>Rate</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sr_no = 1;
                        if (!empty($_GET['editID'])):
                            $entries = json_decode($record['json_data'], true);
                            foreach ($entries as $entry): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sr_no); ?></td>
                                    <td><?= htmlspecialchars($entry['details']); ?></td>
                                    <td><?= htmlspecialchars($entry['payment']); ?></td>
                                    <td><?= htmlspecialchars($entry['currency']); ?></td>
                                    <td><?= htmlspecialchars($entry['rate']); ?></td>
                                    <td><?= htmlspecialchars($entry['totalAmount']); ?></td>
                                    <td>
                                        <button class="btn btn-sm p-1 btn-warning editRow"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-sm p-1 btn-danger deleteRow"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                        <?php $sr_no++;
                            endforeach;
                        endif; ?>
                    </tbody>
                </table>

                <div id="hiddenInputsContainer">
                    <?php
                    if (!empty($_GET['editID'])) {
                        $entries = json_decode($record['json_data'], true);
                        foreach ($entries as $entry): ?>
                            <input type="hidden" name="details[]" value="<?= $entry['details']; ?>">
                            <input type="hidden" name="payment[]" value="<?= $entry['payment']; ?>">
                            <input type="hidden" name="currency[]" value="<?= $entry['currency']; ?>">
                            <input type="hidden" name="rate[]" value="<?= $entry['rate']; ?>">
                            <input type="hidden" name="totalAmount[]" value="<?= $entry['totalAmount']; ?>">
                    <?php endforeach;
                    } ?>
                </div>
                <!-- Submit Button -->
                <div class="d-flex justify-content-end align-items-center g-2">
                    <div>
                        <button type="submit" name="SubmitBill" class="btn btn-success mt-3">Submit Bill</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif;
    if (isset($_GET['viewID'])): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-2" id="formHeading" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                        View Bill
                    </h1>
                </div>

                <div class="p-2">
                    <strong>Bill Name:</strong>
                    <span class="fw-semibold"><?= htmlspecialchars($record['bill_name']); ?></span>
                </div>
            </div>
            <div class="col-md-10 border-end border-dark">
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 3%;">Sr.</th>
                                <th style="width: 40%;">Details</th>
                                <th>Payment</th>
                                <th>Currency</th>
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
                                        <td><?= htmlspecialchars($entry['rate']); ?></td>
                                        <td><?= number_format(htmlspecialchars($entry['totalAmount'])); ?></td>
                                    </tr>
                            <?php $sr_no++;
                                endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <div class="me-2">
                        <a href="?editID=<?php echo $record['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="?deleteID=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash-alt"></i>
                        </a>
                        <a href="<?= $pageURL; ?>" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
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
                <div class="mt-2">
                    <strong>Acc No:</strong>
                    <span class="fw-semibold"><?= htmlspecialchars($record['acc_no']); ?></span><br>
                    <strong>Name:</strong>
                    <span class="fw-semibold"><?= khaataSingle($record['acc_id'])['khaata_name']; ?></span><br>
                    <strong>Bill#:</strong>
                    <span class="fw-semibold"><?= htmlspecialchars($record['bill_no']); ?></span><br>
                    <strong>Date:</strong>
                    <span class="fw-semibold"><?= my_date(htmlspecialchars($record['bill_date'])); ?></span><br>
                    <strong>Amt.:</strong>
                    <span class="fw-semibold"><?= number_format($record['bill_total_amount']); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
<script>
    $(document).ready(function() {
        let srNo = 1;

        function calculateTotals() {
            let qty = parseFloat($('#payment').val()) || 0;
            let rate = parseFloat($('#rate').val()) || 0;
            let totalAmount = qty * rate;
            $('#totalAmount').val(totalAmount)
        }

        function clearInputBoxes() {
            $('#details, #payment, #currency, #rate, #totalAmount').val('');
            $('#details').focus();
        }

        function addRowToTable() {
            let details = $('#details').val();
            let payment = $('#payment').val();
            let currency = $('#currency').val();
            let rate = $('#rate').val();
            let totalAmount = $('#totalAmount').val();
            if (details && payment && rate) {
                let newRow = `
            <tr>
                <td>${srNo}</td>
                <td>${details}</td>
                <td>${payment}</td>
                <td>${currency}</td>
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
                let rate = $(this).find('td:eq(4)').text();
                let totalAmount = $(this).find('td:eq(5)').text();
                hiddenInputs += `<input type="hidden" name="details[]" value="${details}" />`;
                hiddenInputs += `<input type="hidden" name="payment[]" value="${payment}" />`;
                hiddenInputs += `<input type="hidden" name="currency[]" value="${currency}" />`;
                hiddenInputs += `<input type="hidden" name="rate[]" value="${rate}" />`;
                hiddenInputs += `<input type="hidden" name="totalAmount[]" value="${totalAmount}" />`;
            });
            $('#hiddenInputsContainer').html(hiddenInputs);
        }

        function updateTotals() {
            let totalAmount = 0;
            $('#addedRowsTable tbody tr').each(function() {
                totalAmount += parseFloat($(this).find('td:eq(5)').text()) || 0;
            });
            $('#parentBillAmount').val(totalAmount.toFixed(2));
        }
        $(document).on('input', '#payment, #rate', calculateTotals);
        $(document).on('keydown', 'input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let inputId = $(this).attr('id');
                if (inputId === 'details') {
                    $('#payment').focus();
                } else if (inputId === 'payment') {
                    $('#currency').focus();
                } else if (inputId === 'currency') {
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
                $('#rate').val(row.find('td:eq(4)').text());
                $('#totalAmount').val(row.find('td:eq(5)').text());
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
        $details = $_POST['details'];
        $payments = $_POST['payment'];
        $currencies = $_POST['currency'];
        $rates = $_POST['rate'];
        $totalAmounts = $_POST['totalAmount'];
        for ($i = 0; $i < count($details); $i++) {
            $entries[] = [
                'details' => mysqli_real_escape_string($connect, $details[$i]),
                'payment' => (float) $payments[$i],
                'currency' => $currencies[$i],
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
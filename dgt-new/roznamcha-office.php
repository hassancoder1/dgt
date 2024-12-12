<?php
$page_title = 'Roznamcha Office';
$pageURL = 'roznamcha-office';
$print_url = '';
$editID = '';
include("header.php");
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
    $viewID = isset($_GET['viewID']) ? (int)$_GET['viewID'] : (int)$_GET['editID'];
    $print_url = 'print/' . $pageURL . (isset($_GET['viewID']) ? '?viewID=' . $_GET['viewID'] : '');
    $billNumbers = [];
    $query = "SELECT bill_no FROM roznamcha_office";
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $billNumbers[] = $row['bill_no'];
    }
    $UniqueBillNo = generateUniqueBillNo($billNumbers);
    $query = "SELECT * FROM roznamcha_office WHERE id = $viewID LIMIT 1";
    $result = mysqli_query($connect, $query);
    $record = $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : ['id' => '', 'bill_no' => $UniqueBillNo, 'bill_date' => date('d-m-Y'), 'bill_name' => '', 'json_data' => '[]', 'total_amount' => '', 'tax_amount' => '', 'final_amount' => ''];
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
<div class="mx-5 bg-white p-3" style="margin-top:-30px;">
    <?php if (!isset($_GET['editID']) && !isset($_GET['viewID'])) { ?>
        <div>
            <div class="d-flex justify-content-between align-items-center w-100">
                <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                    OFFICE EXPENSES
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
                            <th>Bill Date</th>
                            <th>Amount</th>
                            <th>VAT Total</th>
                            <th>Total Amount</th>
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
                                <td><?= my_date(htmlspecialchars($row['bill_date'])); ?></td>
                                <td><?= number_format($row['total_amount'], 2); ?></td>
                                <td><?= number_format($row['tax_amount'], 2); ?></td>
                                <td><?= number_format($row['final_amount'], 2); ?></td>
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
                    <?= empty($editID) ? 'New' : 'Edit'; ?> Bill
                </h1>
                <div class="me-2">
                    <a href="<?= $pageURL; ?>" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="editID" value="<?= $record['id']; ?>">
                <div class="row">
                    <!-- Parent Fields -->
                    <div class="col-md-3 mb-3">
                        <label for="parentBillNo" class="form-label">Bill#</label>
                        <input type="text" id="parentBillNo" value="<?= htmlspecialchars($record['bill_no']); ?>" name="parentBillNo" class="form-control form-control-sm" placeholder="Enter Bill Number" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="parentDate" class="form-label">Date</label>
                        <input type="text" id="parentDate" value="<?= htmlspecialchars(date('d-m-Y')); ?>" name="parentDate" class="form-control form-control-sm" placeholder="Bill Date">
                    </div>
                    <div class="col-md-7 mb-3">
                        <label for="parentBillName" class="form-label">Bill Name</label>
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
                    <div class="col-md-1">
                        <label>Qty</label>
                        <input type="number" id="qty" class="form-control form-control-sm" min="0" placeholder="Qty">
                    </div>
                    <div class="col-md-1">
                        <label>Price</label>
                        <input type="number" id="price" class="form-control form-control-sm" min="0" placeholder="Price">
                    </div>
                    <div class="col-md-1">
                        <label>Total Amt</label>
                        <input type="text" id="totalWithoutTax" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-md-1">
                        <label>VAT (%)</label>
                        <input type="number" id="vat" class="form-control form-control-sm" min="0" placeholder="VAT">
                    </div>
                    <div class="col-md-1">
                        <label>Tax Amount</label>
                        <input type="text" id="taxAmount" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Final Amount</label>
                        <input type="text" id="totalAmount" class="form-control form-control-sm" readonly>
                    </div>
                </div>

                <!-- Table -->
                <table class="table table-bordered" id="addedRowsTable">
                    <thead>
                        <tr>
                            <th>SR.No</th>
                            <th>Details</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total Without Tax</th>
                            <th>VAT (%)</th>
                            <th>Tax Amount</th>
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
                                    <td><?= htmlspecialchars($entry['qty']); ?></td>
                                    <td><?= htmlspecialchars($entry['price']); ?></td>
                                    <td><?= htmlspecialchars($entry['amount']); ?></td>
                                    <td><?= htmlspecialchars($entry['vatPercent']); ?></td>
                                    <td><?= htmlspecialchars($entry['vatTotal']); ?></td>
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

                <!-- Totals Section -->
                <div class="d-flex justify-content-end align-items-center">
                    <div>
                        <strong>Total Amount:</strong> <span id="totalAmountDisplay"><?= $record['total_amount'] ?? 0; ?></span><br>
                        <strong>Total VAT Amount:</strong> <span id="totalVATDisplay"><?= $record['tax_amount'] ?? 0; ?></span><br>
                        <strong>Grand Total:</strong> <span id="grandTotalDisplay"><?= $record['final_amount'] ?? 0; ?></span>
                    </div>
                </div>
                <div id="hiddenInputsContainer">

                </div>
                <!-- Submit Button -->
                <button type="submit" name="SubmitBill" class="btn btn-success mt-3">Submit Bill</button>
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
                ?>
                <form method="post" class="m-4">
                    <div class="row gx-3 gy-4 align-items-center">
                        <!-- Purchaser Account -->
                        <div class="col-md-2">
                            <small class="fw-bold text-dark d-none my-1" id="p_acc_name"></small>
                            <label for="p_acc" class="form-label fw-bold text-danger">Purchaser Account</label>
                            <input type="text" value="<?= $roznamcha['p_acc_no'] ?? ''; ?>" id="p_acc" name="p_acc_no"
                                onkeyup="searchAcc('#p_acc')" tabindex="-1" class="form-control form-control-sm" required
                                placeholder="Enter Purchaser Acc">

                            <input type="hidden" name="p_acc_id" id="p_acc_id" value="<?= $roznamcha['p_acc_id'] ?? ''; ?>">
                        </div>

                        <!-- Seller Account -->
                        <div class="col-md-2">
                            <small class="fw-bold text-dark d-none my-1" id="s_acc_name"></small>
                            <label for="s_acc" class="form-label fw-bold text-success">Seller Account</label>
                            <input type="text" value="<?= $roznamcha['s_acc_no'] ?? ''; ?>" id="s_acc" name="s_acc_no"
                                onkeyup="searchAcc('#s_acc')" tabindex="-1" class="form-control form-control-sm" required
                                placeholder="Enter Seller Acc">
                            <input type="hidden" name="s_acc_id" id="s_acc_id" value="<?= $roznamcha['s_acc_id'] ?? ''; ?>">
                        </div>

                        <!-- Currency -->
                        <div class="col-md-2">
                            <label for="currency1" class="form-label fw-bold">Primary Currency</label>
                            <select id="currency1" name="currency1" class="form-select form-select-sm" required>
                                <option value="" hidden>Select</option>
                                <?php $currencies = fetch('currencies');
                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                    $sel_curr = $roznamcha['currency1'] == $crr['name'] ? 'selected' : '';
                                    echo '<option ' . $sel_curr . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                } ?>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-2">
                            <label for="amount" class="form-label fw-bold">Amount</label>
                            <input type="text" id="amount" name="amount" class="form-control form-control-sm" onkeyup="lastAmount()"
                                readonly required value="<?= $record['final_amount'] ?? 0; ?>" placeholder="0.00">
                        </div>

                        <!-- Secondary Currency and Rate -->
                        <div class="col-md-4">
                            <div class="row gx-2">
                                <div class="col-7">
                                    <label for="currency2" class="form-label fw-bold">Secondary Currency</label>
                                    <select id="currency2" name="currency2" class="form-select form-select-sm" required>
                                        <option value="" hidden>Select</option>
                                        <?php $currencies = fetch('currencies');
                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                            $sel_curr2 = $roznamcha['currency2'] == $crr['name'] ? 'selected' : '';
                                            echo '<option ' . $sel_curr2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label for="rate" class="form-label fw-bold">Rate</label>
                                    <input type="text" name="rate" id="rate" class="form-control form-control-sm" required
                                        onkeyup="lastAmount()" value="<?= $roznamcha['rate'] ?? 0; ?>" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Operation -->
                        <div class="col-md-2">
                            <label for="opr" class="form-label fw-bold">Operation</label>
                            <select name="opr" class="form-select form-select-sm" id="opr" required onchange="lastAmount()">
                                <?php $ops = array('[*]' => '*', '[/]' => '/');
                                foreach ($ops as $opName => $op) {
                                    $sel_op = $roznamcha['opr'] == $op ? 'selected' : '';
                                    echo '<option ' . $sel_op . ' value="' . $op . '">' . $opName . '</option>';
                                } ?>
                            </select>
                        </div>

                        <!-- Final Amount and Date -->
                        <div class="col-md-4">
                            <div class="row gx-2">
                                <div class="col-6">
                                    <label for="final_amount" class="form-label fw-bold">Final Amount</label>
                                    <input type="text" name="final_amount" id="final_amount" class="form-control form-control-sm" required
                                        readonly tabindex="-1" value="<?= $roznamcha['final_amount'] ?? 0; ?>" placeholder="0.00">
                                </div>
                                <div class="col-6">
                                    <label for="transfer_date" class="form-label fw-bold">Transfer Date</label>
                                    <input type="date" class="form-control form-control-sm" id="transfer_date" name="transfer_date"
                                        required value="<?= $roznamcha['transfer_date']; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-md-6">
                            <label for="details" class="form-label fw-bold">Details</label>
                            <input type="text" name="details" id="details" class="form-control form-control-sm" placeholder="Enter transaction details" value="<?= $roznamcha['details'] ?? ''; ?>">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-2">
                            <button name="PaymentSubmit" type="submit" id="SubmitForm"
                                class="btn btn-primary btn-sm "><i class="fa-solid fa-money-bill-transfer"></i> Transfer</button>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="total_amount" value="<?= $record['total_amount'] ?? 0; ?>">
                        <input type="hidden" name="total_vta_amount" value="<?= $record['tax_amount'] ?? 0; ?>">
                        <input type="hidden" name="total_bill_amount" value="<?= $record['final_amount'] ?? 0; ?>">
                        <input type="hidden" name="id" value="<?= $record['id']; ?>">
                    </div>


                    <?php
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
                </form>
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
                    <strong>Bill#:</strong>
                    <span class="fw-semibold"><?= htmlspecialchars($record['bill_no']); ?></span><br>
                    <strong>Date:</strong>
                    <span class="fw-semibold"><?= my_date(htmlspecialchars($record['bill_date'])); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
<script>
    $(document).ready(function() {
        let srNo = 1;

        // Calculate totals and tax amount
        function calculateTotals() {
            let qty = parseFloat($('#qty').val()) || 0;
            let price = parseFloat($('#price').val()) || 0;
            let vat = parseFloat($('#vat').val()) || 0;

            let totalWithoutTax = qty * price;
            let taxAmount = (totalWithoutTax * vat) / 100;
            let totalAmount = totalWithoutTax + taxAmount;

            $('#totalWithoutTax').val(totalWithoutTax.toFixed(2));
            $('#taxAmount').val(taxAmount.toFixed(2));
            $('#totalAmount').val(totalAmount.toFixed(2));
        }

        // Clear input fields
        function clearInputBoxes() {
            $('#details, #qty, #price, #vat, #totalWithoutTax, #taxAmount, #totalAmount').val('');
            $('#details').focus();
        }

        // Add row to table
        function addRowToTable() {
            let details = $('#details').val();
            let qty = $('#qty').val();
            let price = $('#price').val();
            let totalWithoutTax = $('#totalWithoutTax').val();
            let vat = $('#vat').val();
            let taxAmount = $('#taxAmount').val();
            let totalAmount = $('#totalAmount').val();

            if (details && qty && price) {
                let newRow = `
            <tr>
                <td>${srNo}</td>
                <td>${details}</td>
                <td>${qty}</td>
                <td>${price}</td>
                <td>${totalWithoutTax}</td>
                <td>${vat}</td>
                <td>${taxAmount}</td>
                <td>${totalAmount}</td>
                <td>
                    <button class="btn btn-sm p-1 btn-warning editRow"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm p-1 btn-danger deleteRow"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
                $('#addedRowsTable tbody').append(newRow);
                srNo++;
                $('#srNo').val(srNo);

                // Add hidden input fields for row data
                addHiddenInputs();

                clearInputBoxes();
                updateTotals();
            } else {
                alert('Please fill all fields before adding the row.');
            }
        }

        // Add hidden inputs with row values
        function addHiddenInputs() {
            let rowCount = $('#addedRowsTable tbody tr').length;
            let hiddenInputs = '';

            $('#addedRowsTable tbody tr').each(function() {
                let details = $(this).find('td:eq(1)').text();
                let qty = $(this).find('td:eq(2)').text();
                let price = $(this).find('td:eq(3)').text();
                let totalWithoutTax = $(this).find('td:eq(4)').text();
                let vat = $(this).find('td:eq(5)').text();
                let taxAmount = $(this).find('td:eq(6)').text();
                let totalAmount = $(this).find('td:eq(7)').text();

                hiddenInputs += `<input type="hidden" name="details[]" value="${details}" />`;
                hiddenInputs += `<input type="hidden" name="qty[]" value="${qty}" />`;
                hiddenInputs += `<input type="hidden" name="price[]" value="${price}" />`;
                hiddenInputs += `<input type="hidden" name="totalWithoutTax[]" value="${totalWithoutTax}" />`;
                hiddenInputs += `<input type="hidden" name="vat[]" value="${vat}" />`;
                hiddenInputs += `<input type="hidden" name="taxAmount[]" value="${taxAmount}" />`;
                hiddenInputs += `<input type="hidden" name="totalAmount[]" value="${totalAmount}" />`;
            });

            $('#hiddenInputsContainer').html(hiddenInputs);
        }

        // Update total amounts
        function updateTotals() {
            let totalAmount = 0;
            let totalVATAmount = 0;
            $('#addedRowsTable tbody tr').each(function() {
                totalAmount += parseFloat($(this).find('td:eq(4)').text()) || 0;
                totalVATAmount += parseFloat($(this).find('td:eq(6)').text()) || 0;
            });

            $('#totalAmountDisplay').text(totalAmount.toFixed(2));
            $('#totalVATDisplay').text(totalVATAmount.toFixed(2));
            $('#grandTotalDisplay').text((totalAmount + totalVATAmount).toFixed(2));
        }

        // Event Handlers
        $(document).on('input', '#qty, #price, #vat', calculateTotals);
        $(document).on('keydown', 'input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let inputId = $(this).attr('id');
                if (inputId === 'details') {
                    $('#qty').focus();
                } else if (inputId === 'qty') {
                    $('#price').focus();
                } else if (inputId === 'price') {
                    $('#vat').focus();
                } else if (inputId === 'vat') {
                    calculateTotals();
                    addRowToTable();
                }
            }
        });

        // Edit row
        let isEditing = false;
        $(document).on('click', '.editRow', function() {
            if (!isEditing) {
                isEditing = true;
                let row = $(this).closest('tr');
                $('#srNo').val(row.find('td:eq(0)').text());
                $('#details').val(row.find('td:eq(1)').text());
                $('#qty').val(row.find('td:eq(2)').text());
                $('#price').val(row.find('td:eq(3)').text());
                $('#totalWithoutTax').val(row.find('td:eq(4)').text());
                $('#vat').val(row.find('td:eq(5)').text());
                $('#taxAmount').val(row.find('td:eq(6)').text());
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

    function lastAmount() {
        let amount = $("#amount").val();
        let rate = $("#rate").val();
        let operator = $('#opr').find(":selected").val();
        let final_amount;

        if (amount && rate) { // Ensure both amount and rate have values
            if (operator === "/") {
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
    $done = mysqli_query($connect, "DELETE FROM roznamcha_office WHERE id='$id'");
    if ($done) {
        messageNew('success', $pageURL, 'Record Deleted!');
    }
}
if (isset($_POST['SubmitBill'])) {
    $billNo = mysqli_real_escape_string($connect, $_POST['parentBillNo']);
    $billName = mysqli_real_escape_string($connect, $_POST['parentBillName']);
    $parentDate = mysqli_real_escape_string($connect, $_POST['parentDate']);
    $editID = mysqli_real_escape_string($connect, $_POST['editID']);
    $entries = [];

    if (!empty($_POST['details']) && count($_POST['details']) > 0) {
        $details = $_POST['details'];
        $qtys = $_POST['qty'];
        $prices = $_POST['price'];
        $amounts = $_POST['totalWithoutTax'];
        $vatPercents = $_POST['vat'];
        $vatTotals = $_POST['taxAmount'];
        $totalAmounts = $_POST['totalAmount'];

        for ($i = 0; $i < count($details); $i++) {
            $entries[] = [
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
            'bill_date' => $parentDate,
            'json_data' => $entriesJSON,
            'total_amount' => $totalAmount,
            'tax_amount' => $totalVTAAmount,
            'final_amount' => $finalBillAmount
        ], ['id' => $editID]);
    } else {
        $done = insert('roznamcha_office', [
            'bill_no' => $billNo,
            'bill_name' => $billName,
            'bill_date' => $parentDate,
            'json_data' => $entriesJSON,
            'total_amount' => $totalAmount,
            'tax_amount' => $totalVTAAmount,
            'final_amount' => $finalBillAmount
        ]);
    }

    if ($done) {
        messageNew('success', $pageURL, 'Bill Added!');
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
    $type_post = "Office Bill";
    $url = $pageURL . '?viewID=' . $bill_id;
    $type = 'Office-Bill';
    $transfered_from = 'office-roznamcha';
    $r_type = 'Office Bill';
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
        $str = " Office Bill # " . $bill_id;
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
            update('roznamcha_office', ['roznamcha_transfer' => json_encode($_POST, true)], ['id' => $bill_id]);
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
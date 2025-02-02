<?php
require_once '../connection.php';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$sql = "SELECT * FROM general_loading WHERE id='$id'";
$parentRow = mysqli_query($connect, $sql);
$parentRow = mysqli_fetch_assoc($parentRow);
$parentId = $parentRow['id'];
$_fields = transactionSingle($parentRow['p_id']);
$parentAgent = json_decode($parentRow['agent_details'], true);
$parentGLoadingInfo = json_decode($parentRow['gloading_info'], true);

$result = fetch('agent_payments', array('bl_no' => $parentRow['bl_no']));
$editRow = $editId = null;
if (isset($_POST['editId'])) {
    $editId = $_POST['editId'];
}
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    if ($row['id'] == $editId) {
        $editRow = $row;
    }
}
$rowCount = count($rows);
$firstRow = $rowCount > 0 ? $rows[0] : null;
?>
<div class="modal-header d-flex justify-content-between bg-white align-items-center">
    <h5 class="modal-title" id="staticBackdropLabel">CARRY BILL</h5>
    <div class="d-flex align-items-center gap-2">
        <a href="print/index?secret=<?= base64_encode('agent-bill-print'); ?>&id=<?= $parentId; ?>&carry-print=true" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2">PRINT</a>
        <a href="carry-bill" class="btn-close ms-3" aria-label="Close"></a>
    </div>
</div>
<div class="row">
    <div class="col-md-10 order-0 content-column">
        <div class="card my-2">
            <div class="card-body">
                <!-- <div class="row">
                    <div class="col-md-12 pb-2 mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div><b><?= ucfirst($parentRow['type']) . "#" . $parentRow['p_sr']; ?></b></div>
                            <div><b>Purchase Date </b><?php echo my_date($parentRow['p_date']); ?></div>
                            <div><b>Type </b><?php echo badge(strtoupper($parentRow['p_type']), 'dark'); ?></div>
                            <div><b>Branch </b><?php echo $parentRow['p_branch']; ?></div>
                        </div>
                    </div>
                </div> -->

                <div class="row gy-1 border-bottom py-1">
                    <div class="col-md-12 row my-3">
                        <div class="col-md-3">
                            <b>Bill#:</b> <?= $firstRow['bill_no'] ?><br>
                            <b>Date:</b> <?= $firstRow['date'] ?><br>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-6 fw-bold">Loading Details</div>
                        <div>
                            <?php
                            $l_date = json_decode($parentRow['loading_details'], true)['loading_date'];
                            echo '<b>Loading Date: ' . $l_date . '</b><br>';
                            $bl_no = $parentRow['bl_no'];
                            $query = "SELECT bl_no, COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(goods_details, '$.container_no'))) AS unique_container_count FROM general_loading WHERE bl_no = '$bl_no' GROUP BY bl_no";
                            $result = $connect->query($query);
                            if ($result) {
                                $row = $result->fetch_assoc();
                                echo '<b>B/L No: ' . ($row['bl_no'] ?? $bl_no) . '</b><br>' .
                                    '<b>Containers: </b>' . ($row['unique_container_count'] ?? 0);
                            } else {
                                echo '<b>B/L No: ' . $bl_no . '</b> - No containers found';
                            }
                            ?>
                            <span class="fw-bold d-block">By <?= ucwords(json_decode($parentRow['shipping_details'], true)['transfer_by']); ?></span>
                        </div>
                    </div>

                    <?php if (!empty($parentAgent)): ?>
                        <div class="col-md-3">
                            <div class="fs-6 fw-bold">Agent Details</div>
                            <div>
                                <?php
                                foreach ($parentAgent as $key => $value) {
                                    if ($key === 'ag_acc_no' || $key === 'ag_name' || $key === 'ag_id') {
                                        echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                    };
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($parentAgent)): ?>
                        <div class="col-md-3">
                            <div>
                                <?php
                                // Define the keys you want to display
                                $allowedKeys = [
                                    'received_date',
                                    'clearing_date',
                                    'loading_truck_number',
                                    'truck_returning_date',
                                    'boe_date',
                                    'pick_up_date',
                                    'waiting_if_any',
                                    'days_waiting',
                                    'return_date',
                                    'truck_number',
                                    'driver_details',
                                    'transporter_name'
                                ];

                                // Iterate over $parentAgent or $Agent and display only the allowed keys
                                foreach ($parentAgent as $key => $value) {
                                    if (in_array($key, $allowedKeys)) {
                                        // Format and output key and value
                                        echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . htmlspecialchars($value) . "<br>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 mx-3">
                <b>Bill Details</b> <?= $firstRow['bill_details'] ?>
            </div>
            <?php
            $TtoAccounts = !$firstRow || ($firstRow && json_decode($firstRow['transfer_details'], true)['transferred_to_accounts'] === false);

            if (!$editRow) {
                $editRow = ['id' => '', 'loading_id' => $parentRow['id'], 'bill_no' => $parentGLoadingInfo['billNumber'], 'date' => date('M-y-d'), 'bill_details' => '', 'sr_no' => $rowCount + 1, 'details' => '', 'quantity' => '', 'rate' => '', 'total' => '', 'tax_amount' => '', 'tax_percentage' => '', 'grand_total' => ''];
            }
            ?>
            <div class="table-responsive px-3">
                <table class="table mt-2 px-2 table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="bg-dark text-white">Sr#</th>
                            <th class="bg-dark text-white" colspan="3">Details</th>
                            <th class="bg-dark text-white">Quantity</th>
                            <th class="bg-dark text-white">Rate</th>
                            <th class="bg-dark text-white">Total</th>
                            <th class="bg-dark text-white">Tax %</th>
                            <th class="bg-dark text-white">Tax Amount</th>
                            <th class="bg-dark text-white">Grand Total</th>
                            <th class="bg-dark text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_amount = $total_bill_amount = $total_tax = 0;
                        if ($rowCount > 0) {
                            $firstRowId = $rows[0]['id'] ?? null;
                            $child_ids = implode(',', array_filter(array_column($rows, 'id'), fn($editId) => $editId !== $firstRowId));
                            foreach ($rows as $row) {
                        ?>
                                <tr>
                                    <td class="border border-dark"><?= htmlspecialchars($row['sr_no'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark" colspan="3"><?= htmlspecialchars($row['details'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark"><?= htmlspecialchars($row['quantity'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark"><?= htmlspecialchars($row['rate'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark"><?= htmlspecialchars($row['total'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark"><?= htmlspecialchars($row['tax_percentage'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark"><?= htmlspecialchars($row['tax_amount'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark"><?= htmlspecialchars($row['grand_total'], ENT_QUOTES); ?></td>
                                    <td class="border border-dark text-center" style="position: relative;">
                                        <?php if (SuperAdmin() || $TtoAdmin) { ?>
                                            <?php if ($row['id'] !== $firstRowId): ?>
                                                <a href="agent-payments-form?deleteAgPaymentEntry=true&billEntryId=<?= $row['id']; ?>&loading_id=<?= $parentId; ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <?php
                                                $attachments = json_decode($row['agent_file'], true) ?? [];
                                                if ($attachments !== []) {
                                                    echo '<a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                                                <i class="fa fa-paperclip text-success me-2"></i>
                                            </a>
                                            <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -140%; display: none; z-index: 1000; width: 200px;">';
                                                    foreach ($attachments as $item) {
                                                        $fileName = htmlspecialchars($item, ENT_QUOTES);
                                                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                        $trimmedName = (strlen($fileName) > 15) ? substr($fileName, 0, 15) . '...' . $fileExtension : $fileName;
                                                        echo '<a href="attachments/' . $fileName . '" download="' . $fileName . '" class="d-block mb-2">' . $trimmedName . '</a>';
                                                    }
                                                    echo '</div>';
                                                } else {
                                                    echo '<i class="fw-bold fa fa-times text-danger"></i>';
                                                }
                                                ?>
                                            <?php endif; ?>
                                            <a href="agent-payments-form?view=1&editId=<?= $row['id']; ?>&id=<?= $parentId; ?>" class="text-primary me-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php
                                        } else {
                                            echo "Transferred";
                                        } ?>

                                    </td>
                                </tr>
                        <?php
                                $total_amount += $row['total'];
                                $total_tax += $row['tax_amount'];
                                $total_bill_amount += $row['grand_total'];
                            }
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
        <?php
        $agKhaataNo = $parentAgent['ag_acc_no'];
        $AgKhaata = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id FROM khaata WHERE khaata_no='$agKhaataNo'"));
        $transferDetails = json_decode($firstRow['transfer_details'], true);
        $rozCondition = isset($transferDetails['transfer_info']) && $transferDetails['transfer_info'] != '';
        ?>
        <div class="card">
            <div class="card-body p-2">
                <form method="post">
                    <div class="row gx-1 gy-3 table-form mb-3 <?= $rozCondition ? 'd-none transfer-form' : '' ?>">
                        <div class="col-2">
                            <small class="fw-bold text-danger" id="p_response"></small>
                            <div class="input-group position-relative">
                                <label for="khaata_no1" class="text-success">Ag. A/c</label>
                                <input name="dr_khaata_no" id="khaata_no1" required class="form-control bg-transparent"
                                    value="<?= $parentAgent['ag_acc_no']; ?>">
                            </div>
                            <input type="hidden" name="dr_khaata_id" id="p_khaata_id" value="<?= $AgKhaata['id']; ?>">
                        </div>

                        <div class="col-2">
                            <small class="fw-bold text-danger" id="p_response"></small>
                            <div class="input-group position-relative">
                                <label for="khaata_no2" class="text-danger">Cr. A/c</label>
                                <input name="cr_khaata_no" id="khaata_no2" required class="form-control bg-transparent"
                                    value="<?php echo $_fields['dr_acc']; ?>">
                            </div>
                            <input type="hidden" name="cr_khaata_id" id="s_khaata_id" value="<?php echo $_fields['dr_acc_id'] ?>">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="currency1">Currency</label>
                                <select id="currency1" name="currency1" class="form-select bg-transparent" required>
                                    <option value="" hidden="">Select</option>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $sel_curr = $adv_arr['currency1'] == $crr['name'] ? 'selected' : '';
                                        echo '<option ' . $sel_curr . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="amount">Amount</label>
                                <input type="text" id="amount" name="amount" class="form-control currency"
                                    onkeyup="lastAmount()" required value="<?= $total_bill_amount; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="currency2">Currency</label>
                                <select id="currency2" name="currency2" class="form-select bg-transparent" required>
                                    <option value="" hidden="">Select</option>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $sel_curr2 = $adv_arr['currency2'] == $crr['name'] ? 'selected' : '';
                                        echo '<option ' . $sel_curr2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                                <label for="rate">Rate</label>
                                <input type="text" name="rate" class="form-control currency" id="rate" required
                                    onkeyup="lastAmount()" value="<?= isset($adv_arr['rate']) ? $adv_arr['rate'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="input-group">
                                <label for="opr">Op.</label>
                                <select name="opr" class="form-select" id="opr" required onchange="lastAmount()">
                                    <?php $ops = array('[*]' => '*', '[/]' => '/');
                                    foreach ($ops as $opName => $op) {
                                        $sel_op = $adv_arr['opr'] == $op ? 'selected' : '';
                                        echo '<option ' . $sel_op . ' value="' . $op . '">' . $opName . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="input-group">
                                <label for="final_amount">F.Amt.</label>
                                <input type="text" name="final_amount" class="form-control" id="final_amount" required
                                    readonly tabindex="-1" value="<?= isset($adv_arr['final_amount']) ? $adv_arr['final_amount'] : ''; ?>">

                                <label for="transfer_date">Date</label>
                                <input type="date" class="form-control" id="transfer_date" name="transfer_date" required
                                    value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="col-7">
                            <div class="input-group">
                                <label for="details">Details</label>
                                <input type="text" name="details" id="details" class="form-control"
                                    value="">
                            </div>
                        </div>

                        <input type="hidden" name="total_amount" value="<?= $total_amount; ?>">
                        <input type="hidden" name="loading_id" value="<?= $parentId; ?>">
                        <input type="hidden" name="bl_no" value="<?= $bl_no; ?>">
                        <input type="hidden" name="p_id" value="<?= $id; ?>">
                        <input type="hidden" name="billNumber" value="<?= $parentGLoadingInfo['billNumber']; ?>">
                        <input type="hidden" name="total_tax_amount" value="<?= $total_tax; ?>">
                        <input type="hidden" name="total_bill_amount" value="<?= $total_bill_amount; ?>">
                        <input type="hidden" name="parent_payment_id" value="<?= $firstRow['id']; ?>">
                        <input type="hidden" name="child_ids" value="<?= $child_ids; ?>">
                        <input type="hidden" name="existing_data" value='<?= $firstRow['transfer_details']; ?>'>
                        <input type="hidden" name="type" value='<?= $parentRow['type']; ?>'>
                        <div class="col-2">
                            <button name="agPaymentSubmit" type="submit" id="SubmitForm"
                                class="btn btn-primary w-100 btn-sm"><i class="fa fa-upload"></i>Transfer
                            </button>
                        </div>
                    </div>
                    <?php
                    if ($rozCondition) {
                        $rozQ = fetch('roznamchaas', array('r_type' => 'Agent Bill', 'transfered_from_id' => $firstRow['id'], 'transfered_from' => 'agent_payments'));
                        if (mysqli_num_rows($rozQ) > 0) { ?>
                            <table class="table table-sm table-bordered mb-0">
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
    </div>
    <div class="col-2 order-1 table-form bg-white">
        <div class="align-items-center justify-content-between flex-wrap pt-2">
            <div>
                <strong><?php echo strtoupper($_fields['p_s_name']) . ' #'; ?></strong>
                <?php echo $_fields['sr']; ?>
            </div>
            <div>
                <strong>User:</strong> <?php echo $_fields['username']; ?>
            </div>
            <div>
                <strong>Date:</strong> <?php echo my_date($_fields['_date']); ?>
            </div>
            <div>
                <strong>Type:</strong> <?php echo badge(strtoupper($_fields['type']), 'dark'); ?>
            </div>
            <div>
                <strong>Country:</strong> <?php echo $_fields['country']; ?>
            </div>
            <div>
                <strong>Branch:</strong> <?php echo branchName($_fields['branch_id']); ?>
            </div>
        </div>
        <div class="my-3">
            <!-- Total Details  -->
            <b>T. AMOUNT: </b><span id="show_total_amount"><?= isset($total_amount) ? $total_amount : ''; ?></span><br>
            <b>T. TAX: </b><span id="show_total_amount"><?= isset($total_tax) ? $total_tax : ''; ?></span><br>
            <b>T.B. AMOUNT: </b><span id="show_total_amount"><?= isset($total_bill_amount) ? $total_bill_amount : ''; ?></span>
        </div>
        <?php if (!empty($firstRow) && json_decode($firstRow['transfer_details'], true)['transferred_to_accounts'] === true) {
            echo '<b class="text-success mt-3"><i class="fa fa-check"></i> Transferred</b>';
        } ?></button>
        <button class="btn btn-warning d-block btn-sm" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function agentDetails() {
            var agentAccNo = $('#ag_acc_no').val().toUpperCase();
            $.ajax({
                type: 'POST',
                url: 'ajax/fetchAgentDetails.php',
                data: 'agent_acc_no=' + agentAccNo,
                success: function(html) {
                    let data = JSON.parse(html).data;
                    if (data.ag_acc_no !== '') {
                        $('#ag_acc_no').addClass('is-valid');
                        $('#ag_acc_no').removeClass('is-invalid');
                        $('#ag_name').val(data.ag_name);
                        $('#ag_id').val(data.ag_id);
                    } else {
                        $('#ag_acc_no').removeClass('is-valid');
                        $('#ag_acc_no').addClass('is-invalid');
                        $('#ag_name').val('');
                        $('#ag_id').val('');
                    }
                },
                error: function(err) {

                }
            });
        }

        function toggleDownloadMenu(event, iconElement) {
            event.preventDefault();
            document.querySelectorAll('.attachment-menu').forEach(menu => {
                menu.style.display = 'none';
            });
            const currentMenu = iconElement.nextElementSibling;
            if (currentMenu.style.display === 'none' || currentMenu.style.display === '') {
                currentMenu.style.display = 'block';
            } else {
                currentMenu.style.display = 'none';
            }
        }

        function calcGrand(value1, value2, Operator, totalInput) {
            let grand_Total = 0;
            grand_Total = Operator === 'multi' ? parseFloat($(value1).val()) * parseFloat($(value2).val()) : parseFloat($(value1).val()) + parseFloat($(value2).val());
            $(totalInput).val(isNaN(grand_Total) ? '' : grand_Total);
        }

        function calcTax(taxPercentageSelector, totalSelector, taxSelector) {
            var taxPercentage = parseFloat($(taxPercentageSelector).val().replace('%', '')) || 0;
            $(taxSelector).val(((taxPercentage / 100) * (parseFloat($(totalSelector).val()) || 0)).toFixed(2));
            calcGrand('#total', '#tax', 'plus', '#grand_total');
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
                // if (balance !== 0) {
                //     if (final_amount > balance) {
                //         disableButton('agPaymentSubmit');
                //     } else {
                //         enableButton('agPaymentSubmit');
                //     }
                // } else {
                //     disableButton('agPaymentSubmit');
                // }
                if (balance >= 1) {
                    if (final_amount <= balance + 0.5) {
                        enableButton('agPaymentSubmit');
                    } else {
                        disableButton('agPaymentSubmit');
                    }
                } else {
                    disableButton('agPaymentSubmit');
                }
            }
        }

        function fetchKhaata(inputField, khaataId, responseId, prefix, khaataImageId, recordSubmitId) {
            let khaata_no = $(inputField).val();
            $.ajax({
                url: 'ajax/fetchSingleKhaata.php',
                type: 'post',
                data: {
                    khaata_no: khaata_no
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {
                        enableButton(recordSubmitId);
                        $(khaataId).val(response.messages['khaata_id']);
                        $(prefix + '_khaata_no').text(khaata_no);
                        $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                        $(prefix + '_b_name').text(response.messages['b_name']);
                        $(prefix + '_c_name').text(response.messages['name']);
                        $(prefix + '_business_name').text(response.messages['business_name']);
                        $(prefix + '_address').text(response.messages['address']);
                        $(prefix + '_comp_name').text(response.messages['comp_name']);
                        var details = {
                            indexes: response.messages['indexes'],
                            vals: response.messages['vals']
                        };
                        $(prefix + '_contacts').html(displayKhaataDetails(details));
                        $(khaataImageId).attr("src", response.messages['image']);
                        $(recordSubmitId).prop('disabled', false);
                        lastAmount();
                        $(responseId).text('');
                    }
                    if (response.success === false) {
                        disableButton(recordSubmitId);
                        $(responseId).text('INVALID');
                        $(prefix + '_khaata_no').text('---');
                        $(prefix + '_khaata_name').text('---');
                        $(prefix + '_c_name').text('---');
                        $(prefix + '_b_name').text('---');
                        $(prefix + '_comp_name').text('---');
                        $(prefix + '_business_name').text('---');
                        $(prefix + '_address').text('---');
                        $(prefix + '_contacts').text('');
                        $(khaataImageId).attr("src", 'assets/images/logo-placeholder.png');
                        $(khaataId).val(0);
                    }
                }
            });
        }

        function displayKhaataDetails(details) {
            var html = ''; // Initialize an empty string to store HTML

            if (details.indexes && details.vals) {
                var indexes = JSON.parse(details.indexes);
                var vals = JSON.parse(details.vals);

                if (Array.isArray(indexes) && Array.isArray(vals)) {
                    var count = Math.min(indexes.length, vals.length);

                    for (var i = 0; i < count; i++) {
                        var key = indexes[i];
                        var value = vals[i];
                        // Construct the HTML string
                        html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                    }
                }
            }

            return html; // Return the constructed HTML string
        }

        $(document).on('keyup', "#khaata_no1", function(e) {
            fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
        });
        fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
        $(document).on('keyup', "#khaata_no2", function(e) {
            fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");
        });
        fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");
    </script>
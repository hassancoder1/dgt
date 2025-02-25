<?php require_once '../connection.php';
$bl_id = $_POST['id'];
$BL = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
$BL['loading_info'] = json_decode($BL['loading_info'] ?? '[]', true);
$BL['goods_info'] = json_decode($BL['goods_info'] ?? '[]', true);
$BL['agent_info'] = json_decode($BL['agent_info'] ?? '[]', true);
$BL['warehouse_info'] = json_decode($BL['warehouse_info'] ?? '[]', true);
$BL['t_info'] = transactionSingle($BL['t_id']);
?>
<div class="modal-header d-flex justify-content-between bg-white align-items-center">
    <h5 class="modal-title" id="staticBackdropLabel">AGENT BILL</h5>
    <div class="d-flex align-items-center gap-2">
        <a href="print/index?secret=<?= base64_encode('agent-bill-print'); ?>&id=<?= $BL['id']; ?>" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2">PRINT</a>
        <a href="carry-bill" class="btn-close ms-3" aria-label="Close"></a>
    </div>
</div>
<div class="row">
    <div class="col-md-10 order-0 content-column">
        <div class="card my-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 pb-2 mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div><b><?= ucfirst($BL['t_info']['p_s']) . "#" . $BL['t_sr']; ?></b></div>
                            <div><b>Purchase Date </b><?= my_date($BL['t_info']['_date']); ?></div>
                            <div><b>Type </b><?= badge(strtoupper($BL['t_info']['type']), 'dark'); ?></div>
                            <div><b>Branch </b><?= branchName($BL['t_info']['branch_id']); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row gy-1 border-bottom py-1">
                    <div class="col-md-12">
                        <span class="fs-6 fw-bold">By <?= ucwords($BL['loading_info']['shipping']['transfer_by']); ?></span>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-6 fw-bold">Loading Details</div>
                        <div>
                            <?php $loadingDetails = $BL['loading_info']['loading']; ?>
                            <b>Loading Date: </b><?= $loadingDetails['loading_date'] ?? ''; ?><br>
                            <b>Bl/No: </b><?= $BL['bl_no'] ?? ''; ?><br>
                            <b>Containers: </b> <?= count($BL['loading_info']['transferred_to_payments']) ?? 'No Containers Transferred';
                                                ?>
                        </div>
                    </div>

                    <?php
                    if (!empty(reset($BL['agent_info']))): ?>
                        <div class="col-md-3">
                            <div class="fs-6 fw-bold">Agent Details</div>
                            <div>
                                <?php
                                foreach (reset($BL['agent_info']) as $key => $value) {
                                    if ($key === 'ag_acc_no' || $key === 'ag_name' || $key === 'ag_id') {
                                        echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                    };
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty(reset($BL['agent_info']))): ?>
                        <div class="col-md-3">
                            <div>
                                <?php
                                // Define the keys you want to display
                                $allowedKeys = [
                                    'doe_date',
                                    'pick_up_date',
                                    'waiting_days',
                                    'truck_returning_date',
                                    'return_date',
                                    'transporter_name',
                                    'truck_number',
                                    'details',
                                    'driver_name',
                                    'driver_number',
                                    'transporter_name'
                                ];

                                foreach (reset($BL['agent_info']) as $key => $value) {
                                    if (in_array($key, $allowedKeys)) {
                                        echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . htmlspecialchars($value) . "<br>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                $Bill = mysqli_fetch_assoc(fetch('agent_payments', ['bl_id' => $BL['id']]));
                if ($Bill) {
                    $items = json_decode($Bill['bill_entries'], true);
                    $total_amount = number_format(array_sum(array_column($items, 'amount')), 2);
                    $total_tax_amount = number_format(array_sum(array_column($items, 'tax_amount')), 2);
                    $total_final_amount = number_format(array_sum(array_column($items, 'final_amount')), 2);
                } else {
                    $items = [];
                    $total_amount = $total_tax_amount = $total_final_amount = 0;
                }
                $currentSr = 0;
                foreach ($items as $item) {
                    $currentSr = $item['sr'] > $currentSr ? $item['sr'] : '';
                    if (!empty($_POST['edit']) && $_POST['edit'] === $BL['bl_no'] . '~' . $item['sr']) {
                        $fillData = $item;
                    } else {
                    }
                }
                $newSr = !empty($_POST['edit']) ? $_POST['edit'] : $currentSr += 1;
                if (empty($_POST['edit'])) {
                    $fillData = [
                        'sr' => $newSr,
                        'details' => '',
                        'quantity' => '',
                        'rate' => '',
                        'amount' => '',
                        'tax_percent' => '',
                        'tax_amount' => '',
                        'final_amount' => ''
                    ];
                }
                $Bill['transfer_info'] = json_decode($Bill['transfer_info'], true);
                $TtoAccounts = isset($Bill['transfer_info']['transferred_to_accounts']) ? $Bill['transfer_info']['transferred_to_accounts']  : false;
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
                            if (count($items) > 0) {
                                foreach ($items as $key => $row) {
                            ?>
                                    <tr>
                                        <td class="border border-dark"><?= htmlspecialchars($row['sr'], ENT_QUOTES); ?></td>
                                        <td class="border border-dark" colspan="3"><?= htmlspecialchars($row['details'], ENT_QUOTES); ?></td>
                                        <td class="border border-dark"><?= htmlspecialchars($row['quantity'], ENT_QUOTES); ?></td>
                                        <td class="border border-dark"><?= htmlspecialchars($row['rate'], ENT_QUOTES); ?></td>
                                        <td class="border border-dark"><?= number_format($row['amount'], 2); ?></td>
                                        <td class="border border-dark"><?= htmlspecialchars($row['tax_percent'], ENT_QUOTES); ?>%</td>
                                        <td class="border border-dark"><?= number_format($row['tax_amount'], 2); ?></td>
                                        <td class="border border-dark"><?= number_format($row['final_amount'], 2); ?></td>
                                        <td class="border border-dark text-center" style="position: relative;">
                                            <?php if (SuperAdmin() || $TtoAccounts) { ?>
                                                <a href="agent-payments-form?view=1&bl_id=<?= $BL['id']; ?>&delete=<?= $key; ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php
                                                if (reset($items)['sr'] === $row['sr']) {
                                                    $attachments = json_decode($Bill['attachments'] ?? '[]', true);
                                                    if (!empty($attachments)) {
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
                                                }
                                                ?>
                                                <a href="agent-payments-form?view=1&bl_id=<?= $BL['id']; ?>&edit=<?= $key; ?>" class="text-primary me-2">
                                                    <i class="fa fa-edit"></i>
                                                </a><?php
                                                } else {
                                                    echo "Transferred";
                                                } ?>

                                        </td>
                                    </tr>
                            <?php  }
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
                <?php
                $Agent = reset($BL['agent_info']);
                $agKhaataNo = $Agent['ag_acc_no'];
                $AgKhaata = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id FROM khaata WHERE khaata_no='$agKhaataNo'"));
                $rozCondition = isset($Bill['transfer_info']['transferred_info']) && !empty($Bill['transfer_info']['transferred_info']);
                $roznamcha = !empty($Bill['transfer_info']['transferred_info']) ? $Bill['transfer_info']['transferred_info'] : ['dr_acc_no' => $agKhaataNo, 'dr_acc_id' => $AgKhaata['id'], 'cr_acc_no' => $BL['t_info']['dr_acc'], 'cr_acc_id' => $BL['t_info']['dr_acc_id']];
                $BillAmount = array_sum(array_column($items, 'final_amount'));
                ?>

                <form method="post" class="m-4">
                    <div class="row gx-1 gy-3 table-form mb-3 <?= $rozCondition ? 'd-none transfer-form' : '' ?>">
                        <div class="row gx-3 gy-4 align-items-center">
                            <div class="col-md-2">
                                <small class="fw-bold text-dark d-none my-1" id="p_acc_name"></small>
                                <label for="p_acc" class="form-label fw-bold text-danger">Cr. Account</label>
                                <input type="text" value="<?= $roznamcha['cr_acc_no'] ?? ''; ?>" id="p_acc" name="dr_acc_no"
                                    onkeyup="searchAcc('#p_acc')" tabindex="-1" class="form-control form-control-sm" required
                                    placeholder="Enter Purchaser Acc">
                                <input type="hidden" name="dr_acc_id" id="p_acc_id" value="<?= $roznamcha['cr_acc_id'] ?? ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <small class="fw-bold text-dark d-none my-1" id="s_acc_name"></small>
                                <label for="s_acc" class="form-label fw-bold text-success">Agent Account</label>
                                <input type="text" value="<?= $roznamcha['dr_acc_no'] ?? ''; ?>" id="s_acc" name="cr_acc_no"
                                    onkeyup="searchAcc('#s_acc')" tabindex="-1" class="form-control form-control-sm" required
                                    placeholder="Enter Seller Acc">
                                <input type="hidden" name="cr_acc_id" id="cr_acc_id" value="<?= $roznamcha['dr_acc_id'] ?? ''; ?>">
                            </div>
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
                            <div class="col-md-2">
                                <label for="amount" class="form-label fw-bold">Amount</label>
                                <input type="text" id="amount" name="amount" class="form-control form-control-sm" onkeyup="lastAmount()"
                                    readonly required value="<?= $BillAmount ?? 0; ?>" placeholder="0.00">
                            </div>
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
                                            required value="<?= $roznamcha['transfer_date'] ?? ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="details" class="form-label fw-bold">Details</label>
                                <input type="text" name="details" id="details" class="form-control form-control-sm" placeholder="Enter transaction details" value="<?= $roznamcha['details'] ?? ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <button name="PaymentSubmit" type="submit" id="SubmitForm"
                                    class="btn btn-primary btn-sm "><i class="fa-solid fa-money-bill-transfer"></i> Transfer</button>
                            </div>
                            <input type="hidden" name="bill_id" value="<?= $Bill['id'] ?>">
                            <input type="hidden" name="bl_id" value="<?= $BL['id'] ?>">
                            <input type="hidden" name="p_s" value="<?= $BL['p_s']; ?>">
                            <input type="hidden" name="branch_id" value="<?= $BL['t_info']['branch_id']; ?>">
                        </div>
                    </div>
                    <?php
                    if (!empty($roznamcha)) {
                        $rozQ = fetch('roznamchaas', array('r_type' => 'Agent Bill', 'transfered_from_id' => $Bill['id'], 'transfered_from' => 'agent_payments'));
                        if (mysqli_num_rows($rozQ) > 0) { ?>
                            <table class="table table-sm table-bordered m-1">
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
        <div class="my-3">
            <b>T. AMOUNT: </b><span><?= $total_amount; ?></span><br>
            <b>T. TAX: </b><span><?= $total_tax_amount; ?></span><br>
            <b>T.B. AMOUNT: </b><span><?= $total_final_amount; ?></span>
        </div>
        <div class="d-flex" style="align-items: center;">
            <?php
            if ($rozCondition) {
            ?>
                <button class="btn btn-warning btn-sm" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
            <?php
            } ?>
        </div>
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
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
        <a href="agent-payments-form" class="btn-close ms-3" aria-label="Close"></a>
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
            $TtoAdmin = isset($Bill['transfer_info']['transferred_to_admin']) ? $Bill['transfer_info']['transferred_to_admin']  : false;
            ?>
            <form method="post" class="table-form p-3" enctype="multipart/form-data">
                <h5 class="text-primary">Agent Payments Form</h5>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="bill_no" class="form-label">Bill No#</label>
                        <input type="text" name="bill_no" id="bill_no" required readonly value="<?= $Bill['bill_no'] ?? reset($BL['agent_info'])['bill_number']; ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label for="bill_date" class="form-label">Date</label>
                        <input type="text" name="bill_date" readonly id="bill_date" required value="<?= $Bill['bill_date'] ?? date('m-d-Y'); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-7">
                        <label for="bill_details" class="form-label">Bill Details</label>
                        <input type="text" name="bill_details" id="bill_details" required value="<?= $Bill['bill_details'] ?? ''; ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 mt-4">
                        <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                        <span class="btn cursor btn-sm btn-success mt-3" onclick="document.getElementById('attachments').click();"><i class="fa fa-paperclip"></i> File</span>
                    </div>
                    <div class="col-md-1">
                        <label for="sr_no" class="form-label">Sr#</label>
                        <input type="text" name="sr" id="sr_no" required value="<?= $fillData['sr']; ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <!-- text Area  -->
                        <label for="details" class="form-label">Details</label>
                        <input type="text" name="details" id="details" required class="form-control form-control-sm" value="<?= $fillData['details']; ?>">
                    </div>

                    <div class="col-md-1">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="text" name="quantity" id="quantity" required class="form-control form-control-sm" value="<?= $fillData['quantity']; ?>" onkeyup="calcGrand('#quantity','#rate', 'multi', '#total')">
                    </div>

                    <div class="col-md-1">
                        <label for="rate" class="form-label">Rate</label>
                        <input type="text" name="rate" id="rate" required class="form-control form-control-sm" value="<?= $fillData['rate']; ?>" onkeyup="calcGrand('#quantity','#rate', 'multi', '#total')">
                    </div>

                    <div class="col-md-1">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" name="amount" id="total" required class="form-control form-control-sm" value="<?= $fillData['amount']; ?>" onkeyup="calcGrand('#total', '#tax', 'plus', '#grand_total')">
                    </div>

                    <div class="col-md-1">
                        <label for="tax-percentage" class="form-label">Tax %</label>
                        <input type="text" name="tax_percent" id="tax-percentage" required class="form-control form-control-sm" onkeyup="calcTax('#tax-percentage', '#total', '#tax')" value="<?= $fillData['tax_percent']; ?>">
                    </div>

                    <div class="col-md-1">
                        <label for="tax" class="form-label">Tax</label>
                        <input type="text" name="tax_amount" id="tax" required class="form-control form-control-sm" value="<?= $fillData['tax_amount']; ?>" onkeyup="calcGrand('#total', '#tax', 'plus', '#grand_total')">
                    </div>

                    <div class="col-md-1">
                        <label for="grand_total" class="form-label">G.Total</label>
                        <input type="text" name="final_amount" id="grand_total" required class="form-control form-control-sm" value="<?= $fillData['final_amount']; ?>">
                    </div>

                    <div class="col-md-1">
                        <input type="hidden" name="edit" value="<?= $_POST['edit']; ?>">
                        <input type="hidden" name="bl_id" value="<?= $BL['id']; ?>">
                        <button name="SubmitBill" id="AgentFormSubmit" type="submit"
                            class="btn btn-primary btn-sm rounded-0 mt-4">
                            Save
                        </button>
                    </div>
            </form>
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
                                        <?php if (SuperAdmin() || $TtoAdmin) { ?>
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
        <form method="post">
            <?php
            if (empty($Bill['tranfer_info'])) {
                if (!$TtoAdmin):
            ?>
                    <input type="hidden" name="total_amount" value="<?= $total_amount; ?>">
                    <input type="hidden" name="total_tax_amount" value="<?= $total_tax_amount; ?>">
                    <input type="hidden" name="total_final_amount" value="<?= $total_final_amount; ?>">
                    <input type="hidden" name="bl_id" value="<?= $BL['id']; ?>">
                    <input type="hidden" name="bl_no" value="<?= $BL['bl_no']; ?>">
                    <button type="submit" name="TransferBillToAdmin" class="btn btn-dark btn-sm">Transfer</button>
                <?php else: ?>
                    <b class="text-success"><i class="fa fa-check"></i> Transferred to Admin</b>
            <?php endif;
            } ?>
        </form>
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

    function calcGrand(value1, value2, Operator, totalInput) {
        let grand_Total = 0;
        grand_Total = Operator === 'multi' ? parseFloat($(value1).val()) * parseFloat($(value2).val()) : parseFloat($(value1).val()) + parseFloat($(value2).val());
        $(totalInput).val(isNaN(grand_Total) ? '' : grand_Total);
        calcTax('#tax-percentage', '#total', '#tax');
    }

    function calcTax(taxPercentageSelector, totalSelector, taxSelector) {
        var taxPercentage = parseFloat($(taxPercentageSelector).val().replace('%', '')) || 0;
        $(taxSelector).val(((taxPercentage / 100) * (parseFloat($(totalSelector).val()) || 0)).toFixed(2));
        calcGrand('#total', '#tax', 'plus', '#grand_total');
    }
</script>
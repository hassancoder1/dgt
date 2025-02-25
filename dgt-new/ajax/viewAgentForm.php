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
    <h5 class="modal-title" id="staticBackdropLabel">CUSTOM CLEARING AGENT FORM</h5>
    <div class="d-flex align-items-center gap-2">
        <a onclick="openAndPrint('print/bl-no-print.php?loading=general&bl_no=<?= $BL['bl_no']; ?>&agent-print=true')" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2">PRINT</a>
        <a href="agent-form" class="btn-close ms-3" aria-label="Close"></a>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <div class="card my-2">
            <div class="card-body">
                <div class="row border-bottom pb-2">
                    <div class="col-md-4">
                        <div><b>Importer. A/c # </b><?= $BL['loading_info']['importer']['im_acc_no']; ?></div>
                        <div><b>Importer. A/c Name </b><?= $BL['loading_info']['importer']['im_acc_name']; ?></div>
                        <?php if (!empty($BL['loading_info']['importer']['im_acc_details'])) {
                            echo '<div><b>Company Details </b>' . nl2br($BL['loading_info']['importer']['im_acc_details']) . '</div>';
                        } ?>
                    </div>

                    <div class="col-md-4 border-end border-start">
                        <div><b>Exporter. A/c # </b><?= $BL['loading_info']['exporter']['xp_acc_no']; ?></div>
                        <div><b>Exporter. A/c Name </b><?= $BL['loading_info']['exporter']['xp_acc_name']; ?></div>
                        <?php if (!empty($BL['loading_info']['exporter']['xp_acc_details'])) {
                            echo '<div><b>Company Details </b>' . nl2br($BL['loading_info']['exporter']['xp_acc_details']) . '</div>';
                        } ?>
                    </div>

                    <div class="col-md-4">
                        <?php if ($BL['loading_info']['notify']) { ?>
                            <div><b>Notify Party Acc No. </b><?= $BL['loading_info']['notify']['np_acc_no']; ?></div>
                            <div><b>Acc Name </b><?= $BL['loading_info']['notify']['np_acc_name']; ?></div>
                        <?php
                            if (!empty($BL['loading_info']['notify']['np_acc_details'])) {
                                $details = $BL['loading_info']['notify']['np_acc_details'];
                                $countryPos = strpos($details, 'Country');
                                if ($countryPos !== false) {
                                    $companyName = substr($details, 0, $countryPos);
                                    echo '<div><b>Company Name: </b>' . trim($companyName) . '</div>';
                                }
                            }
                        } else {
                            echo "Notify Party Details Not Added!";
                        }
                        ?>
                    </div>
                </div>

                <?php if (!empty($BL['loading_info']['loading'])): ?>
                    <div class="row gy-1 border-bottom py-1">
                        <div class="col-md-12">
                            <span class="fs-6 fw-bold">By <?= ucwords($BL['loading_info']['shipping']['transfer_by']); ?></span>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 fw-bold">Loading Details</div>
                            <div>
                                <?php
                                foreach ($BL['loading_info']['loading'] as $key => $value) {
                                    // Check if the key contains 'port' and update accordingly
                                    if (strpos($key, 'port') !== false) {
                                        $displayKey = $BL['loading_info']['shipping']['transfer_by'] === 'sea' ? 'Port' : 'Border';
                                        $key = str_replace('port', $displayKey, $key);
                                    }
                                    echo '<b>' . ucwords(str_replace('_', ' ', $key)) . ': </b>' . $value . "<br>";
                                }
                                echo '<b>B/L No: </b>' . $BL['bl_no'] . "<br>";
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($BL['loading_info']['receiving'])): ?>
                        <div class="col-md-3">
                            <div class="fs-6 fw-bold">Receiving Details</div>
                            <div>
                                <?php
                                foreach ($BL['loading_info']['receiving'] as $key => $value) {
                                    if (strpos($key, 'port') !== false) {
                                        $displayKey = $BL['loading_info']['shipping']['transfer_by'] === 'sea' ? 'Port' : 'Border';
                                        $key = str_replace('port', $displayKey, $key);
                                    }
                                    echo '<b>' . ucwords(str_replace('_', ' ', $key)) . ': </b>' . $value . "<br>";
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($BL['loading_info']['shipping'])): ?>
                        <div class="col-md-6">
                            <div class="fs-6 fw-bold">Shipping Details</div>
                            <div>
                                <?php
                                foreach ($BL['loading_info']['shipping'] as $key => $value) {
                                    echo '<b>' . ucwords(str_replace('_', ' ', $key)) . ': </b>' . $value . "<br>";
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($BL['agent_info'])): ?>
                        <div class="col-md-3">
                            <div class="fs-6 fw-bold">Agent Details</div>
                            <div>
                                <?php
                                foreach (reset($BL['agent_info']) as $key => $value) {
                                    if (in_array($key, ['ag_acc_no', 'ag_name', 'ag_id'])) {
                                        echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($BL['loading_info']['report'])): ?>
                        <div class="col-md-12">
                            <div class="fs-6 fw-bold">Report</div>
                            <?php
                            echo $BL['loading_info']['report'];
                            ?>
                        <?php endif; ?>
                        </div>
                    </div>
            </div>
            <div class="table-responsive" style="overflow: visible;width:100%;">
                <table class="table mt-2 table-hover table-sm" style="white-space: nowrap;">
                    <thead>
                        <style>
                            .text-white,
                            .border-dark {
                                font-size: 12px;
                            }
                        </style>
                        <tr>
                            <th class="bg-dark text-white text-center"><i class="fa fa-check-square-o"></i></th>
                            <th class="bg-dark text-white">Sr#</th>
                            <th class="bg-dark text-white">Container No</th>
                            <th class="bg-dark text-white">Goods Name</th>
                            <th class="bg-dark text-white">Quantity</th>
                            <th class="bg-dark text-white">BOE No</th>
                            <th class="bg-dark text-white">PickUp.D</th>
                            <th class="bg-dark text-white">Waiting Days</th>
                            <th class="bg-dark text-white">Return.D</th>
                            <th class="bg-dark text-white">Transporter</th>
                            <th class="bg-dark text-white">Truck No.</th>
                            <th class="bg-dark text-white">Details</th>
                            <th class="bg-dark text-white">Driver Name</th>
                            <th class="bg-dark text-white">Driver No</th>
                            <th class="bg-dark text-white">Ag.FILE(S)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ifAllTransferred = false;
                        if (!empty($BL['goods_info'])) {
                            foreach ($BL['goods_info'] as $key => $good) {
                                $Agent = isset($BL['agent_info'][$key]) ? $BL['agent_info'][$key] : [];
                                $Warehouse = isset($BL['warehouse_info'][$key]) ? $BL['warehouse_info'][$key] : [];
                                $ifAllTransferred = (isset($Agent['ag_acc_no']) && !empty($Agent['ag_acc_no'])) ? true : false;
                        ?>
                                <tr>
                                    <td class="border border-dark text-center">
                                        <input type="checkbox" class="row-checkbox" value="<?= $BL['bl_no'] . '~' . $good['sr']; ?>">
                                    </td>
                                    <td class="border border-dark"><a href="agent-form?view=1&bl_id=<?= $BL['id']; ?>&edit=<?= $key; ?>"><b>#<?= $good['sr']; ?></b></a>
                                        <?php if (isset($_POST['edit']) && $_POST['edit'] == $good['sr']) {
                                            echo '<i class="fa fa-eye m-1" style="font-size: 12px;"></i>';
                                        } ?>
                                    </td>
                                    <td class="border border-dark"><?= $good['container_no']; ?></td>
                                    <td class="border border-dark"><?= goodsName($good['good']['goods_id']); ?></td>
                                    <td class="border border-dark"><?= $good['quantity_no']; ?> <sub class="fw-bold"><?= $good['quantity_name']; ?></sub></td>
                                    <?php
                                    if (isset($Agent['edit_permission'])): ?>
                                        <td class="border border-dark agent_details"><?= $Agent['boe_no'] ?? ''; ?></td>
                                        <td class="border border-dark"><?= $Agent['pick_up_date'] ?? ''; ?></td>
                                        <td class="border border-dark"><?= $Agent['waiting_days'] ?? ''; ?></td>
                                        <td class="border border-dark"><?= $Agent['return_date'] ?? ''; ?></td>
                                        <td class="border border-dark"><?= $Agent['transporter_name'] ?? ''; ?></td>
                                        <td class="border border-dark"><?= $Agent['truck_number'] ?? ''; ?></td>
                                        <td class="border border-dark" title="<?= htmlspecialchars($Agent['details'] ?? ''); ?>">
                                            <?= limitWords($Agent['details'] ?? '', 3); ?>
                                        </td>
                                        <td class="border border-dark"><?= $Agent['driver_name'] ?? ''; ?></td>
                                        <td class="border border-dark"><?= $Agent['driver_number'] ?? ''; ?></td>
                                        <td class="border border-dark text-success" style="position: relative;">
                                            <?php
                                            if (!empty($Agent['attachments'])) {
                                                echo '<a href="javascript:void(0);" class="attachment-toggle" style="text-decoration: none; color: inherit;">
                <i class="fa fa-paperclip"></i>
              </a>
              <div class="bg-light border border-dark p-2 attachment-menu" 
                   style="position: absolute; top: 100%; left: 0; display: none; z-index: 1000; width: 200px;">';

                                                foreach ($Agent['attachments'] as $fileName2) {
                                                    $fileName2 = htmlspecialchars($fileName2, ENT_QUOTES);
                                                    $fileExtension2 = pathinfo($fileName2, PATHINFO_EXTENSION);
                                                    $baseName = pathinfo($fileName2, PATHINFO_FILENAME);
                                                    $trimmedName2 = (strlen($baseName) > 15) ? substr($baseName, 0, 15) . '...' : $baseName;
                                                    $displayName = $trimmedName2 . '.' . $fileExtension2;
                                                    echo '<a href="attachments/' . $fileName2 . '" download="' . $fileName2 . '" class="d-block mb-2">' . $displayName . '</a>';
                                                }
                                                echo '</div>';
                                            } else {
                                                echo '<i class="fw-bold fa fa-times text-danger"></i>';
                                            }
                                            ?>
                                        </td>

                                    <?php endif; ?>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
            <span class="fw-bold text-danger tex-sm my-2 d-none" id="transfer-alert"></span>
            <?php
            if (!empty($_POST['edit'])) {
                $found = isset($BL['agent_info'][$_POST['edit']]['edit_permission']) && $BL['agent_info'][$_POST['edit']]['edit_permission'] === true;
                if (SuperAdmin() || !isset($BL['agent_info'][$_POST['edit']]['edit_permission']) || $found) {
                    $myAgent = $BL['agent_info'][$_POST['edit']];
            ?>
                    <form method="post" class="table-form p-3" enctype="multipart/form-data">
                        <h5 class="text-primary">Agent Form</h5>
                        <div class="row g-3">
                            <!-- BOE DATE -->
                            <div class="col-md-2">
                                <label for="boe_date" class="form-label">BOE Date</label>
                                <input type="date" name="boe_date" id="boe_date" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['boe_date'] ?? ''; ?>">
                            </div>

                            <!-- BOE DATE -->
                            <div class="col-md-2">
                                <label for="boe_no" class="form-label">BOE No</label>
                                <input type="text" name="boe_no" id="boe_no" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['boe_no'] ?? ''; ?>">
                            </div>

                            <!-- PICK UP DATE -->
                            <div class="col-md-2">
                                <label for="pick_up_date" class="form-label">Pick Up Date</label>
                                <input type="date" name="pick_up_date" id="pick_up_date" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['pick_up_date'] ?? ''; ?>">
                            </div>

                            <!-- WAITING IF ANY -->
                            <div class="col-md-2">
                                <label for="waiting_days" class="form-label">Waiting (days)</label>
                                <input type="text" name="waiting_days" id="waiting_days"
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['waiting_days'] ?? ''; ?>">
                            </div>

                            <!-- RETURN DATE -->
                            <div class="col-md-2">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" name="return_date" id="return_date" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['return_date'] ?? ''; ?>">
                            </div>


                            <!-- TRANSPORTER NAME -->
                            <div class="col-md-3">
                                <label for="transporter_name" class="form-label">Transporter Name</label>
                                <input type="text" name="transporter_name" id="transporter_name" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['transporter_name'] ?? ''; ?>">
                            </div>
                            <!-- TRUCK NUMBER -->
                            <div class="col-md-2">
                                <label for="truck_number" class="form-label">Truck Number</label>
                                <input type="text" name="truck_number" id="truck_number" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['truck_number'] ?? ''; ?>">
                            </div>
                            <!-- Details -->
                            <div class="col-md-4">
                                <label for="details" class="form-label">Details</label>
                                <input type="text" name="details" id="details" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['details'] ?? ''; ?>">
                            </div>

                            <!-- DRIVER NAME -->
                            <div class="col-md-3">
                                <label for="driver_name" class="form-label">Driver Name</label>
                                <input type="text" name="driver_name" id="driver_name" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['driver_name'] ?? ''; ?>">
                            </div>

                            <!-- DRIVER NUMBER -->
                            <div class="col-md-3">
                                <label for="driver_number" class="form-label">Driver Number</label>
                                <input type="number" name="driver_number" id="driver_number" required
                                    class="form-control form-control-sm"
                                    value="<?= $myAgent['driver_number'] ?? ''; ?>">
                            </div>
                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <input type="file" id="agent_file" name="agent_file[]" class="d-none" multiple>
                                    <span class="btn cursor btn-sm btn-success" onclick="document.getElementById('agent_file').click();">
                                        <i class="fa fa-paperclip"></i> Add File(s)
                                    </span>
                                    <input type="hidden" name="bl_id" value="<?= $BL['id']; ?>">
                                    <input type="hidden" name="item" value="<?= $_POST['edit']; ?>">
                                    <button name="AgentFormSubmit" id="AgentFormSubmit" type="submit"
                                        class="btn btn-primary btn-sm rounded-0">
                                        Save
                                    </button>
                                </div>
                            </div>

                    </form>
        </div>
<?php }
                if (isset($BL['agent_info'][$_POST['edit']]['edit_permission']) && $BL['agent_info'][$_POST['edit']]['edit_permission'] === false) {
                    echo "<b class='text-danger'>Edit Permission not Allowed For #" . $_POST['edit'] . "</b>";
                }
            } ?>
    </div>
</div>
<div class="col-md-2 card">
    <div class="border-bottom px-2 pb-2 my-2">
        <div><b><?= ucfirst($BL['t_type']) . '#' . $BL['t_sr']; ?></b></div>
        <div><b>Date </b><?php echo my_date($BL['t_info']['_date']); ?></div>
        <div><b>Type </b><?php echo badge(strtoupper($BL['t_type']), 'dark'); ?></div>
        <div><b>Branch </b><?= branchName($BL['t_info']['branch_id']); ?></div>
    </div>
    <form action="" method="post">
        <input type="hidden" name="payment_transfer_ids" class="form-control form-control-sm" id="payment_transfer_ids" value="<?= implode('~', $BL['loading_info']['transferred_to_payments'] ?? []); ?>">
        <input type="hidden" name="bl_id" value="<?= $BL['id']; ?>">
        <input type="hidden" name="TransferToPayments" value="true">
        <button type="submit" class="btn btn-warning btn-sm mt-2">Transfer</button>
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

    $(document).ready(function() {
        $(document).on('click', '.attachment-toggle', function(event) {
            event.preventDefault();
            $('.attachment-menu').hide();
            $(this).next('.attachment-menu').toggle();
        });
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.attachment-toggle, .attachment-menu').length) {
                $('.attachment-menu').hide();
            }
        });
        $('.row-checkbox').change(function() {
            let currentValues = $('#payment_transfer_ids').val().split('~').filter(v => v); // Get current values
            let selectedValue = $(this).val();
            if ($(this).is(':checked')) {
                if (!currentValues.includes(selectedValue)) {
                    currentValues.push(selectedValue);
                }
            } else {
                currentValues = currentValues.filter(v => v !== selectedValue);
            }
            $('#payment_transfer_ids').val(currentValues.join('~'));
        });
    });

    function openAndPrint(url) {
        const newWindow = window.open(
            url,
            '_blank',
            'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + screen.width + ',height=' + screen.height
        );
    }
</script>
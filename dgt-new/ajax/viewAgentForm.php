<?php require_once '../connection.php';
$id = $_POST['id'];
$level = $_POST['level'];
if ($id > 0) {
    $records = fetch('general_loading', array('id' => $id));
    $parent = $record = mysqli_fetch_assoc($records);
    $Importer = isset($record['importer_details']) ? json_decode($record['importer_details'], true) : [];
    $Notify = isset($record['notify_party_details']) ? json_decode($record['notify_party_details'], true) : [];
    $Exporter = isset($record['exporter_details']) ? json_decode($record['exporter_details'], true) : [];
    $Goods = isset($record['goods_details']) ? json_decode($record['goods_details'], true) : [];
    $Shipping = isset($record['shipping_details']) ? json_decode($record['shipping_details'], true) : [];
    $Loading = isset($record['loading_details']) ? json_decode($record['loading_details'], true) : [];
    $Receiving = isset($record['receiving_details']) ? json_decode($record['receiving_details'], true) : [];
    $Agent = isset($record['agent_details']) ? json_decode($record['agent_details'], true) : [];
    if (!empty($record)) {
        $data = json_decode($parent['gloading_info'], true);
        $ptransfrd = isset($data['payments_trans_ids']) ? $data['payments_trans_ids'] : [];
        if (isset($data['child_ids'])) {
            $childIdsString = $data['child_ids'];
            $childIdsArray = explode(', ', $childIdsString);
            $childIdsArray = array_map('trim', $childIdsArray);
            $childIdsArray = array_map('mysqli_real_escape_string', array_fill(0, count($childIdsArray), $connect), $childIdsArray);
            $childIdsList = implode("', '", $childIdsArray);
            $childRecordQuery = "SELECT * FROM general_loading WHERE id IN ('$childIdsList')";
            $childRecordResult = mysqli_query($connect, $childRecordQuery);
            $childRecords = [];
            if ($childRecordResult) {
                $childRecords[] = $parent;
                while ($childRecord = mysqli_fetch_assoc($childRecordResult)) {
                    $childRecords[] = $childRecord;
                }
            }
?>
            <div class="modal-header d-flex justify-content-between bg-white align-items-center">
                <h5 class="modal-title" id="staticBackdropLabel">CUSTOM CLEARING AGENT FORM</h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="print/index?secret=<?= base64_encode('bl-no-print'); ?>&blSearch=<?= $parent['bl_no']; ?>&agent-print=true" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2">PRINT</a>
                    <a href="agent-form" class="btn-close ms-3" aria-label="Close"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="row border-bottom pb-2">
                                <div class="col-md-4">
                                    <div><b>Importer. A/c # </b><?php echo $Importer['im_acc_no']; ?></div>
                                    <div><b>Importer. A/c Name </b><?php echo $Importer['im_acc_name']; ?></div>
                                    <?php if (!empty($Importer['im_acc_details'])) {
                                        echo '<div><b>Company Details </b>' . nl2br($Importer['im_acc_details']) . '</div>';
                                    } ?>
                                </div>

                                <div class="col-md-4 border-end border-start">
                                    <div><b>Exporter. A/c # </b><?php echo $Exporter['xp_acc_no']; ?></div>
                                    <div><b>Exporter. A/c Name </b><?php echo $Exporter['xp_acc_name']; ?></div>
                                    <?php if (!empty($Exporter['xp_acc_details'])) {
                                        echo '<div><b>Company Details </b>' . nl2br($Exporter['xp_acc_details']) . '</div>';
                                    } ?>
                                </div>

                                <div class="col-md-4">
                                    <?php if ($Notify) { ?>
                                        <div><b>Notify Party Acc No. </b><?= $Notify['np_acc_no']; ?></div>
                                        <div><b>Acc Name </b><?php echo $Notify['np_acc_name']; ?></div>
                                    <?php
                                        if (!empty($Notify['np_acc_details'])) {
                                            $details = $Notify['np_acc_details'];
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
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-12">
                                    <span class="fs-6 fw-bold">By <?= ucwords($Shipping['transfer_by']); ?></span>
                                </div>
                                <?php if (!empty($Loading)): ?>
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Loading Details</div>
                                        <div>
                                            <?php
                                            foreach ($Loading as $key => $value) {
                                                // Check if the key contains 'port' and update accordingly
                                                if (strpos($key, 'port') !== false) {
                                                    $displayKey = $Shipping['transfer_by'] === 'sea' ? 'Port' : 'Border';
                                                    $key = str_replace('port', $displayKey, $key);
                                                }
                                                echo '<b>' . ucwords(str_replace('_', ' ', $key)) . ': </b>' . $value . "<br>";
                                            }
                                            echo '<b>B/L No: </b>' . $record['bl_no'] . "<br>";

                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <?php if (!empty($Receiving)): ?>
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Receiving Details</div>
                                        <div>
                                            <?php
                                            foreach ($Receiving as $key => $value) {
                                                if (strpos($key, 'port') !== false) {
                                                    $displayKey = $Shipping['transfer_by'] === 'sea' ? 'Port' : 'Border';
                                                    $key = str_replace('port', $displayKey, $key);
                                                }
                                                echo '<b>' . ucwords(str_replace('_', ' ', $key)) . ': </b>' . $value . "<br>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <?php if (!empty($Shipping)): ?>
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Shipping Details</div>
                                        <div>
                                            <?php
                                            foreach ($Shipping as $key => $value) {
                                                if ($key == 'transfer_by') {
                                                    continue;
                                                }
                                                echo '<b>' . ucwords(str_replace('shipping_', ' ', $key)) . ': </b>' . $value . "<br>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($Agent)): ?>
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Agent Details</div>
                                        <div>
                                            <?php
                                            foreach ($Agent as $key => $value) {
                                                if (in_array($key, ['ag_acc_no', 'ag_name', 'ag_id'])) {
                                                    echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                                } else {
                                                    if ($key === 'cargo_transfer_warehouse') {
                                                        echo '<b> Cargo WareHouse: </b>' . $value . "<br>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($record['report'])): ?>
                                    <div class="col-md-12">
                                        <div class="fs-6 fw-bold">Report</div>
                                        <?php
                                        echo $record['report'];
                                        ?>
                                    <?php endif; ?>
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
                                            <?php // if (isset($Agent['permission_to_edit'])): 
                                            ?>
                                            <th class="bg-dark text-white">BOE.D</th>
                                            <th class="bg-dark text-white">PickUp.D</th>
                                            <th class="bg-dark text-white">Waiting(YES/NO)</th>
                                            <th class="bg-dark text-white">No.of.Days.W</th>
                                            <th class="bg-dark text-white">Return.D</th>
                                            <th class="bg-dark text-white">Truck No.</th>
                                            <th class="bg-dark text-white">Driver</th>
                                            <th class="bg-dark text-white">Transporter</th>
                                            <th class="bg-dark text-white">Ag.FILE(S)</th>
                                            <?php // endif; 
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($childRecords)) {
                                            foreach ($childRecords as $record) {
                                                $Agent = isset($record['agent_details']) ? json_decode($record['agent_details'], true) : [];
                                                if (!isset($Agent['transferred'])) {
                                                    continue;
                                                }
                                                if (isset($Agent['ag_id'])) {
                                                    if ($Agent['ag_id'] !== json_decode($parent['agent_details'], true)['ag_id']) {
                                                        continue;
                                                    }
                                                }
                                                if (in_array(($record['p_id'] . '-' . $record['sr_no']), $ptransfrd)) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                        ?>
                                                <tr>
                                                    <td class="border border-dark text-center">
                                                        <input type="checkbox" class="row-checkbox" <?= $checked; ?> value="<?= $record['p_id'] . '-' . $record['sr_no']; ?>">
                                                    </td>
                                                    <td class="border border-dark"><a href="agent-form?view=1&lp_id=<?= $parent['id']; ?>&action=update&editId=<?= $record['id']; ?>"><b>#<?= $record['p_id'] . '-' . $record['sr_no']; ?></b></a>
                                                        <?php if (isset($_POST['editId']) && $_POST['editId'] == $record['id']) {
                                                            echo '<i class="fa fa-eye m-1" style="font-size: 12px;"></i>';
                                                        } ?>
                                                    </td>
                                                    <td class="border border-dark"><?= json_decode($record['goods_details'], true)['container_no']; ?></td>
                                                    <td class="border border-dark"><?= goodsName(json_decode($record['goods_details'], true)['goods_id']); ?></td>
                                                    <td class="border border-dark"><?= json_decode($record['goods_details'], true)['quantity_no']; ?> <sub class="fw-bold"><?= json_decode($record['goods_details'], true)['quantity_name']; ?></sub></td>
                                                    <?php if (isset($Agent['permission_to_edit'])): ?>
                                                        <td class="border border-dark"><?= $Agent['boe_date'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['pick_up_date'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['waiting_if_any'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['days_waiting'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['return_date'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['truck_number'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['driver_details'] ?? ''; ?></td>
                                                        <td class="border border-dark"><?= $Agent['transporter_name'] ?? ''; ?></td>
                                                        <td class="border border-dark text-success" style="position: relative;">
                                                            <?php
                                                            $attachments2 = $Agent['attachments'] ?? [];
                                                            if (!empty($attachments2)) {
                                                                echo '<a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                <i class="fa fa-paperclip"></i>
              </a>
              <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -500%; display: none; z-index: 1000; width: 200px;">';

                                                                foreach ($attachments2 as $fileName2) {
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
                            $myrecords = fetch('general_loading', array('id' => $_POST['editId']));
                            $myrecord = mysqli_fetch_assoc($myrecords);
                            $myAgent = isset($myrecord['agent_details']) ? json_decode($myrecord['agent_details'], true) : [];
                            if (SuperAdmin() || (!isset($myAgent['permission_to_edit']) || $myAgent['permission_to_edit'] === 'Yes')): ?>
                                <div class="card mt-3 <?= $_POST['action'] === 'update' ? '' : 'd-none'; ?>">
                                    <div class="card-body">
                                        <!-- onsubmit="if (document.querySelector('#bill_of_entry_no').value !== '<?= $parent['bl_no']; ?>') { alert('B/L No does not match'); return false; } return true;" -->
                                        <form method="post" class="table-form" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= $myrecord['id']; ?>">
                                            <input type="hidden" name="parent_id" value="<?= $parent['id']; ?>">
                                            <input type="hidden" name="existing_data" value='<?= $myrecord['agent_details']; ?>'>
                                            <?php if (isset($myAgent['received_date'])) {
                                                echo '<input type="hidden" name="case" value="update">';
                                            } else {
                                                echo '<input type="hidden" name="case" value="new">';
                                            } ?>
                                            <h5 class="text-primary">Agent Form</h5>
                                            <div class="row g-3">
                                                <!-- BOE DATE -->
                                                <div class="col-md-3">
                                                    <label for="boe_date" class="form-label">BOE Date</label>
                                                    <input type="date" name="boe_date" id="boe_date" required
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['boe_date']) ? $myAgent['boe_date'] : ''; ?>">
                                                </div>

                                                <!-- PICK UP DATE -->
                                                <div class="col-md-3">
                                                    <label for="pick_up_date" class="form-label">Pick Up Date</label>
                                                    <input type="date" name="pick_up_date" id="pick_up_date" required
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['pick_up_date']) ? $myAgent['pick_up_date'] : ''; ?>">
                                                </div>

                                                <!-- WAITING IF ANY -->
                                                <div class="col-md-3">
                                                    <label for="waiting_if_any" class="form-label">Waiting (If Any)</label>
                                                    <input type="text" name="waiting_if_any" id="waiting_if_any"
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['waiting_if_any']) ? $myAgent['waiting_if_any'] : ''; ?>">
                                                </div>

                                                <!-- NO. OF DAYS WAITING -->
                                                <div class="col-md-3">
                                                    <label for="days_waiting" class="form-label">No. of Days Waiting</label>
                                                    <input type="number" name="days_waiting" id="days_waiting"
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['days_waiting']) ? $myAgent['days_waiting'] : ''; ?>">
                                                </div>

                                                <!-- RETURN DATE -->
                                                <div class="col-md-3">
                                                    <label for="return_date" class="form-label">Return Date</label>
                                                    <input type="date" name="return_date" id="return_date" required
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['return_date']) ? $myAgent['return_date'] : ''; ?>">
                                                </div>

                                                <!-- TRUCK NUMBER -->
                                                <div class="col-md-3">
                                                    <label for="truck_number" class="form-label">Truck Number</label>
                                                    <input type="text" name="truck_number" id="truck_number" required
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['truck_number']) ? $myAgent['truck_number'] : ''; ?>">
                                                </div>

                                                <!-- DRIVER NAME/DRIVER NUMBER -->
                                                <div class="col-md-3">
                                                    <label for="driver_details" class="form-label">Driver Name/Number</label>
                                                    <input type="text" name="driver_details" id="driver_details" required
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['driver_details']) ? $myAgent['driver_details'] : ''; ?>">
                                                </div>

                                                <!-- TRANSPORTER NAME -->
                                                <div class="col-md-3">
                                                    <label for="transporter_name" class="form-label">Transporter Name</label>
                                                    <input type="text" name="transporter_name" id="transporter_name" required
                                                        class="form-control form-control-sm"
                                                        value="<?= isset($myAgent['transporter_name']) ? $myAgent['transporter_name'] : ''; ?>">
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="row mt-4">
                                                    <div class="col-md-12 text-end">
                                                        <input type="file" id="agent_file" name="agent_file[]" class="d-none" multiple>
                                                        <span class="btn cursor btn-sm btn-success" onclick="document.getElementById('agent_file').click();">
                                                            <i class="fa fa-paperclip"></i> Add File(s)
                                                        </span>
                                                        <button name="AgentFormSubmit" id="AgentFormSubmit" type="submit"
                                                            class="btn btn-primary btn-sm rounded-0">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            <?php
                            endif;
                            if (!SuperAdmin() && isset($myAgent['permission_to_edit']) && $myAgent['permission_to_edit'] === 'No') {
                                echo "<b class='text-danger'>Edit Permission not Allowed For #" . $myrecord['p_id'] . '-' . $myrecord['sr_no'] . "</b>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 card">
                    <div class="border-bottom px-2 pb-2 my-2">
                        <div><b><?= "Sr#" . $record['sr_no']; ?></b></div>
                        <div><b>Date </b><?php echo my_date($record['created_at']); ?></div>
                        <div><b>Type </b><?php echo badge(strtoupper($record['p_type']), 'dark'); ?></div>
                        <div><b>Branch </b><?php echo $record['p_branch']; ?></div>
                    </div>
                    <?php // if ($data['transferred_to_payments'] === false): 
                    ?>
                    <form action="" method="post">
                        <input type="text" name="payment_transfer_ids" class="form-control form-control-sm" id="payment_transfer_ids" value="<?= implode(',', $ptransfrd); ?>">
                        <input type="hidden" name="parent_id" value="<?= $parent['id']; ?>">
                        <input type="hidden" name="TransferToPayments" value="true">
                        <button type="submit" class="btn btn-warning btn-sm mt-2">Transfer</button>
                    </form>
                    <?php // else: 
                    ?>
                    <!-- <b class="text-success">Transferrred To Agent Payments</b> -->
                    <?php // endif; 
                    ?>
                </div>
            </div>
<?php
        }
    }
}
?>
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

    $(document).ready(function() {
        $('.row-checkbox').change(function() {
            let selectedValues = [];
            $('.row-checkbox:checked').each(function() {
                let checkbox = $(this);
                let agAccNoElem = checkbox.closest('tr').find('.ag_clearing_date');
                if (agAccNoElem.length === 0) {
                    $('#transfer-alert').text('Please Select #' + checkbox.val() + ' and Add Data to Transfer it!').removeClass('d-none');
                } else {
                    selectedValues.push(checkbox.val());
                }
            });
            $('#payment_transfer_ids').val(selectedValues.join(','));
        });
    });
</script>
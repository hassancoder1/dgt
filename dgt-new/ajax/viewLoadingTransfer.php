<?php require_once '../connection.php';
$id = $_POST['id'];
$level = $_POST['level'];
if ($id > 0) {
    $records = fetch('general_loading', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $Importer = isset($record['importer_details']) ? json_decode($record['importer_details'], true) : [];
    $Notify = isset($record['notify_party_details']) ? json_decode($record['notify_party_details'], true) : [];
    $Exporter = isset($record['exporter_details']) ? json_decode($record['exporter_details'], true) : [];
    $Goods = isset($record['goods_details']) ? json_decode($record['goods_details'], true) : [];
    $Shipping = isset($record['shipping_details']) ? json_decode($record['shipping_details'], true) : [];
    $Loading = isset($record['loading_details']) ? json_decode($record['loading_details'], true) : [];
    $Receiving = isset($record['receiving_details']) ? json_decode($record['receiving_details'], true) : [];
    if (!empty($record)) { ?>
        <div class="row">
            <div class="col-md-10">
                <div class="card my-2">
                    <div class="card-body">
                        <div class="row border-bottom pb-2">
                            <div class="col-md-12 border-bottom px-2 pb-2 mb-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div><b><?= "Sr#" . $record['sr_no']; ?></b></div>
                                    <div><b>Purchase Date </b><?php echo my_date($record['p_date']); ?></div>
                                    <div><b>Type </b><?php echo badge(strtoupper($record['p_type']), 'dark'); ?></div>
                                    <div><b>Branch </b><?php echo $record['p_branch']; ?></div>
                                </div>
                            </div>

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

                        <?php if (!empty($Loading)): ?>
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-12">
                                    <span class="fs-6 fw-bold">By <?= ucwords($Shipping['transfer_by']); ?></span>
                                </div>
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
                                <div class="col-md-6">
                                    <div class="fs-6 fw-bold">Shipping Details</div>
                                    <div>
                                        <?php
                                        foreach ($Shipping as $key => $value) {
                                            echo '<b>' . ucwords(str_replace('_', ' ', $key)) . ': </b>' . $value . "<br>";
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
                                <div class="table-responsive">
                                    <table class="table mt-2 table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="bg-dark text-white">Sr#</th>

                                                <th class="bg-dark text-white">Container No</th>
                                                <th class="bg-dark text-white">B/L.No</th>

                                                <th class="bg-dark text-white">G.Ne</th>

                                                <th class="bg-dark text-white">Qty Ne</th>
                                                <th class="bg-dark text-white">Qty No</th>
                                                <th class="bg-dark text-white">G.W.KGS</th>
                                                <th class="bg-dark text-white">N.W.KGS</th>
                                                <th class="bg-dark text-white">FILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border border-dark"><a href="general-loading?p_id=<?= $id; ?>&view=1&lp_id=<?= $record['id']; ?>&action=update"><?= $record['sr_no']; ?></a></td>
                                                <td class="border border-dark"><?= json_decode($record['goods_details'], true)['container_no']; ?></td>
                                                <td class="border border-dark"><?= $record['bl_no']; ?></td>
                                                <td class="border border-dark"><?= goodsName(json_decode($record['goods_details'], true)['goods_id']); ?></td>

                                                <td class="border border-dark"><?= json_decode($record['goods_details'], true)['quantity_name']; ?></td>
                                                <td class="border border-dark"><?= json_decode($record['goods_details'], true)['quantity_no']; ?></td>
                                                <td class="border border-dark"><?= json_decode($record['goods_details'], true)['gross_weight']; ?></td>
                                                <td class="border border-dark"><?= json_decode($record['goods_details'], true)['net_weight']; ?></td>
                                                <td class="border border-dark text-success"><a href="#"></a><i class="fa fa-download"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="card mt-3 transfer-form d-none">
                                        <div class="card-body">
                                            <form method="post" class="table-form">
                                                <input type="hidden" name="id" value="<?= $record['id']; ?>">
                                                <input type="hidden" name="row_id" id="row_id" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['row_id'] : ''; ?>">
                                                <h5 class="text-primary">Agent Details</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label for="ag_acc_no" class="form-label">Agent Acc No</label>
                                                        <input type="text" name="ag_acc_no" id="ag_acc_no" required value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['ag_acc_no'] : ''; ?>" class="form-control form-control-sm" onkeyup="agentDetails()">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="ag_name" class="form-label">AGENT NAME</label>
                                                        <input type="text" name="ag_name" id="ag_name" required class="form-control form-control-sm" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['ag_name'] : ''; ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="ag_id" class="form-label">AGENT ID</label>
                                                        <input type="text" name="ag_id" id="ag_id" required class="form-control form-control-sm" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['ag_id'] : ''; ?>">
                                                    </div>
                                                    <?php
                                                    $warehouse = !empty(json_decode($record['agent_details'], true)['cargo_transfer_warehouse']) ? json_decode($record['agent_details'], true)['cargo_transfer_warehouse'] : '';
                                                    $warehouseOptions = ['Free Zone', 'OFF Site', 'Transit'];
                                                    ?>

                                                    <!-- Cargo Transfer Dropdown -->
                                                    <div class="col-md-3">
                                                        <label for="cargo_transfer" class="form-label">Cargo Transfer</label>
                                                        <select id="cargo_transfer" name="cargo_transfer" class="form-select form-control-sm" required>
                                                            <option disabled <?= in_array($warehouse, $warehouseOptions) ? '' : 'selected' ?>>Select One</option>
                                                            <option value="Free Zone" <?= $warehouse === 'freezone' ? 'selected' : '' ?>>Freezone Warehouse</option>
                                                            <option value="OFF Site" <?= $warehouse === 'offsite' ? 'selected' : '' ?>>Offsite Warehouse</option>
                                                            <option value="Transit" <?= $warehouse === 'transit' ? 'selected' : '' ?>>Transit Warehouse</option>
                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-12 text-end">
                                                        <?php if (isset($record['agent_details']) && !empty($record['agent_details'])) {
                                                            $ag = json_decode($record['agent_details'], true);
                                                            if (isset($ag['transferred']) && $ag['transferred'] === true) {
                                                        ?>
                                                                <b class="text-success"><i class="fa fa-check"> </i> Transferred to Agent</b><br>
                                                                <!-- <b>LoadingID: #<?= $record['id'] ?></b><br> -->
                                                                <?php
                                                                if (SuperAdmin() && isset($ag['permission_to_edit'])) {
                                                                ?>
                                                                    <label for="change_permission" class="form-label mt-3">Permission to Edit: </label>
                                                                    <input type="hidden" name="existing_agent_data" value='<?= $record['agent_details']; ?>'>
                                                                    <input type="checkbox" name="change_permission" <?= $ag['permission_to_edit'] === 'no' ? '' : 'checked';  ?> id="change_permission">
                                                                    <button name="UpdatePermission" id="UpdatePermission" type="submit"
                                                                        class="btn btn-primary btn-sm rounded-0">
                                                                        Update
                                                                    </button>
                                                                <?php
                                                                }
                                                            } else { ?>
                                                                <button name="TransferToAgent" id="TransferToAgent" type="submit"
                                                                    class="btn btn-primary btn-sm rounded-0">
                                                                    Transfer To Agent
                                                                </button>
                                                            <?php
                                                            }
                                                        } else { ?>
                                                            <button name="LoadingTransfer" id="GLoadingSubmit" type="submit"
                                                                class="btn btn-primary btn-sm rounded-0">
                                                                Save
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 card">
                <button class="btn btn-warning btn-sm mt-2" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Transfer to Agent</button>
            </div>
        </div>
<?php }
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
                    $('#row_id').val(data.row_id);
                } else {
                    $('#ag_acc_no').removeClass('is-valid');
                    $('#ag_acc_no').addClass('is-invalid');
                    $('#ag_name').val('');
                    $('#ag_id').val('');
                    $('#row_id').val('');
                }
            },
            error: function(err) {

            }
        });
    }
</script>
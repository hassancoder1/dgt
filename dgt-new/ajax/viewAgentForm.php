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
    $Agent = isset($record['agent_details']) ? json_decode($record['agent_details'], true) : [];
    if (!empty($record)) { ?>
        <div class="row">
            <div class="col-md-9">
                <div class="card my-2">
                    <div class="card-body">
                        <div class="row border-bottom pb-2">

                        </div>

                        <div class="row gy-1 border-bottom py-1">
                            <div class="col-md-12">
                                <span class="fs-6 fw-bold">By <?= ucwords($Shipping['transfer_by']); ?></span>
                            </div>
                            <?php if (!empty($Loading)): ?>
                                <div class="col-md-2">
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
                                <div class="col-md-2">
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
                                <div class="col-md-4">
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

                            <?php if (!empty($Agent)): ?>
                                <div class="col-md-3">
                                    <div class="fs-6 fw-bold">Agent Details</div>
                                    <div>
                                        <?php
                                        foreach ($Agent as $key => $value) {
                                            if (in_array($key, ['ag_acc_no', 'ag_name', 'ag_id', 'cargo_transfer_warehouse'])) {
                                                echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                            };
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
                                        <th class="bg-dark text-white">L.FILE(S)</th>
                                        <?php if (isset($Agent['permission_to_edit'])): ?>
                                            <th class="bg-dark text-white">R Date</th>
                                            <th class="bg-dark text-white">Clear Date</th>
                                            <th class="bg-dark text-white">Bill Of Entry No</th>
                                            <th class="bg-dark text-white">L.Truck No</th>
                                            <th class="bg-dark text-white">Truck Receiving Date</th>
                                            <th class="bg-dark text-white">Ag.FILE(S)</th>
                                        <?php endif; ?>
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
                                        <td class="border border-dark text-success" style="position: relative;">
                                            <a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                                                <i class="fa fa-paperclip"></i>
                                            </a>
                                            <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -190%; display: none; z-index: 1000; width: 200px;">
                                                <?php
                                                $attachments = json_decode($record['attachments'], true) ?? [];
                                                foreach ($attachments as $item) {
                                                    $fileName = htmlspecialchars($item[1], ENT_QUOTES);
                                                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                    $trimmedName = (strlen($fileName) > 15) ? substr($fileName, 0, 15) . '...' . $fileExtension : $fileName;
                                                    echo '<a href="attachments/' . $fileName . '" download="' . $fileName . '" class="d-block mb-2">' . $trimmedName . '</a>';
                                                }
                                                if (empty($attachments)) echo '<p>No attachments available</p>';
                                                ?>
                                            </div>
                                        </td>
                                        <?php if (isset($Agent['permission_to_edit'])): ?>
                                            <td class="border border-dark"><?= json_decode($record['agent_details'], true)['received_date']; ?></td>
                                            <td class="border border-dark"><?= json_decode($record['agent_details'], true)['clearing_date']; ?></td>
                                            <td class="border border-dark"><?= json_decode($record['agent_details'], true)['bill_of_entry_no']; ?></td>
                                            <td class="border border-dark"><?= json_decode($record['agent_details'], true)['loading_truck_number']; ?></td>
                                            <td class="border border-dark"><?= json_decode($record['agent_details'], true)['truck_returning_date']; ?></td>
                                            <td class="border border-dark text-success" style="position: relative;">
                                                <a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                                                    <i class="fa fa-paperclip"></i>
                                                </a>
                                                <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -200%; display: none; z-index: 1000; width: 200px;">
                                                    <?php
                                                    $attachments = $Agent['attachments'] ?? [];
                                                    if (!empty($attachments)) {
                                                        foreach ($attachments as $key => $fileName) {
                                                            $fileName = htmlspecialchars($fileName, ENT_QUOTES);
                                                            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                            $trimmedName = (strlen($fileName) > 15) ? substr($fileName, 0, 15) . '...' . $fileExtension : $fileName;
                                                            echo '<a href="attachments/' . $fileName . '" download="' . $fileName . '" class="d-block mb-2">' . $trimmedName . '</a>';
                                                        }
                                                    } else {
                                                        echo '<p>No attachments available</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>

                                        <?php endif; ?>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if (($Agent['transferred']  === true && (!isset($Agent['permission_to_edit'])) || $Agent['permission_to_edit'] === 'yes')): ?>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <form method="post" class="table-form" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $record['id']; ?>">
                                        <input type="hidden" name="existing_data" value='<?= $record['agent_details']; ?>'>
                                        <h5 class="text-primary">Agent Form</h5>
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label for="received_date" class="form-label">Received Date</label>
                                                <input type="date" name="received_date" id="received_date" required value="<?= isset($Agent['received_date']) ? $Agent['received_date'] : ''; ?>" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="clearing_date" class="form-label">Clearing Date</label>
                                                <input type="date" name="clearing_date" id="clearing_date" required class="form-control form-control-sm" value="<?= isset($Agent['clearing_date']) ? $Agent['clearing_date'] : ''; ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="bill_of_entry_no" class="form-label">Bill Of Entry No</label>
                                                <input type="text" name="bill_of_entry_no" id="bill_of_entry_no" required class="form-control form-control-sm" value="<?= isset($Agent['bill_of_entry_no']) ? $Agent['bill_of_entry_no'] : ''; ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="loading_truck_number" class="form-label">Loading Truck Number</label>
                                                <input type="text" name="loading_truck_number" id="loading_truck_number" required class="form-control form-control-sm" value="<?= isset($Agent['loading_truck_number']) ? $Agent['loading_truck_number'] : ''; ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="truck_returning_date" class="form-label">Truck Retruning Date</label>
                                                <input type="date" name="truck_returning_date" id="truck_returning_date" required class="form-control form-control-sm" value="<?= isset($Agent['truck_returning_date']) ? $Agent['truck_returning_date'] : ''; ?>">
                                            </div>

                                            <div class="col-md-5">
                                                <label for="report" class="form-label">Report</label>
                                                <input type="text" name="report" id="report" required class="form-control form-control-sm" value="<?= isset($Agent['report']) ? $Agent['report'] : ''; ?>">
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12 text-end">
                                                    <input type="file" id="agent_file" name="agent_file[]" class="d-none" multiple>
                                                    <span class="btn cursor btn-sm btn-success" onclick="document.getElementById('agent_file').click();"><i class="fa fa-paperclip"></i> Add File(s)</span>
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 card mt-2">

                <div class="border-bottom my-2">
                    <!-- <div class="d-flex align-items-center justify-content-between"> -->
                    <div><b><?= "Sr#" . $record['sr_no']; ?></b></div>
                    <div><b>Purchase Date </b><?php echo my_date($record['p_date']); ?></div>
                    <div><b>Type </b><?php echo badge(strtoupper($record['p_type']), 'dark'); ?></div>
                    <div><b>Branch </b><?php echo $record['p_branch']; ?></div>
                    <!-- </div> -->
                </div>

                <div class="px-1">
                    <small class="fw-bold">Importer</small>
                    <!-- <div><b>Importer. A/c # </b><?php echo $Importer['im_acc_no']; ?></div>
                        <div><b>Importer. A/c Name </b><?php echo $Importer['im_acc_name']; ?></div> -->
                    <?php if (!empty($Importer['im_acc_details'])) {
                        echo '<div><b>Company Details </b>' . $Importer['im_acc_details'] . '</div>';
                    } ?>
                </div>

                <div class="px-1">
                <small class="fw-bold">Exporter</small>
                    <!-- <div><b>Exporter. A/c # </b><?php echo $Exporter['xp_acc_no']; ?></div>
                        <div><b>Exporter. A/c Name </b><?php echo $Exporter['xp_acc_name']; ?></div> -->
                    <?php if (!empty($Exporter['xp_acc_details'])) {
                        echo '<div><b>Company Details </b>' . $Exporter['xp_acc_details'] . '</div>';
                    } ?>
                </div>

                <div class="px-1">
                <small class="fw-bold">Notify</small>
                    <?php if ($Notify) { ?>
                        <!-- <div><b>Notify Party Acc No. </b><?= $Notify['np_acc_no']; ?></div>
                            <div><b>Acc Name </b><?php echo $Notify['np_acc_name']; ?></div> -->
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
        </div>
<?php
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
</script>
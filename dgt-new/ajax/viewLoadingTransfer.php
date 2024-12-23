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

    if (!empty($record)) {
        $data = json_decode($parent['gloading_info'], true);
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
                <h5 class="modal-title" id="staticBackdropLabel">LOADING TRANSFER</h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="print/index?secret=<?= base64_encode('loading-transfer-print'); ?>&blSearch=<?= $parent['bl_no']; ?>" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2">PRINT</a>
                    <a href="loading-transfer" class="btn-close ms-3" aria-label="Close"></a>
                </div>
            </div>
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
                                                    <th class="bg-dark text-white text-center"><i class="fa fa-check-square-o"></i></th>
                                                    <th class="bg-dark text-white">#</th>
                                                    <th class="bg-dark text-white">Container No</th>
                                                    <th class="bg-dark text-white">G.Ne</th>
                                                    <th class="bg-dark text-white">Qty Ne</th>
                                                    <th class="bg-dark text-white">Qty No</th>
                                                    <th class="bg-dark text-white">G.W.KGS</th>
                                                    <th class="bg-dark text-white">N.W.KGS</th>
                                                    <th class="bg-dark text-white">AG Acc No</th>
                                                    <th class="bg-dark text-white">AG Name</th>
                                                    <th class="bg-dark text-white">Edit</th>
                                                    <th class="bg-dark text-white">FILE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ifAllTransferred = false;
                                                if (!empty($childRecords)) {
                                                    foreach ($childRecords as $record) {
                                                        $Agent = isset($record['agent_details']) ? json_decode($record['agent_details'], true) : [];
                                                        $ifAllTransferred = (isset($Agent['ag_acc_no']) && !empty($Agent['ag_acc_no'])) ? true : false;
                                                ?>
                                                        <tr data-loading="<?= $record['id']; ?>">
                                                            <td class="border border-dark text-center">
                                                                <input type="checkbox" class="row-checkbox" value="<?= $record['p_id'] . '-' . $record['sr_no']; ?>">
                                                            </td>
                                                            <td class="border border-dark"><a href="general-loading?p_id=<?= $id; ?>&view=1&lp_id=<?= $record['id']; ?>&action=update"><?= $record['p_id'] . '-' . $record['sr_no']; ?></a></td>
                                                            <td class="border border-dark"><?= json_decode($record['goods_details'], true)['container_no']; ?></td>
                                                            <td class="border border-dark"><?= goodsName(json_decode($record['goods_details'], true)['goods_id']); ?></td>

                                                            <td class="border border-dark"><?= json_decode($record['goods_details'], true)['quantity_name']; ?></td>
                                                            <td class="border border-dark"><?= json_decode($record['goods_details'], true)['quantity_no']; ?></td>
                                                            <td class="border border-dark"><?= json_decode($record['goods_details'], true)['gross_weight']; ?></td>
                                                            <td class="border border-dark"><?= json_decode($record['goods_details'], true)['net_weight']; ?></td>
                                                            <td class="border border-dark ag_acc_no"><?= isset($Agent['ag_acc_no']) ? $Agent['ag_acc_no'] : '<i class="text-danger fa fa-times"></i>'; ?></td>
                                                            <td class="border border-dark"><?= isset($Agent['ag_name']) ? $Agent['ag_name'] : '<i class="text-danger fa fa-times"></i>'; ?></td>
                                                            <td class="border border-dark fw-bold"><?= isset($Agent['permission_to_edit']) ? ($Agent['permission_to_edit'] === 'No' ? '<span class="text-danger">No</span>' : '<span class="text-success">Yes</span>') : '<span class="text-danger">No</span>'; ?></td>
                                                            <td class="border border-dark text-success" style="position: relative;">
                                                                <?php
                                                                $attachments = json_decode($record['attachments'], true) ?? [];
                                                                if ($attachments !== []) {
                                                                    echo '<a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                                                <i class="fa fa-paperclip"></i>
                                            </a>
                                            <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -500%; display: none; z-index: 1000; width: 200px;">';
                                                                    foreach ($attachments as $item) {
                                                                        $fileName = htmlspecialchars($item[1], ENT_QUOTES);
                                                                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                                        $trimmedName = (strlen($fileName) > 15) ? substr($fileName, 0, 15) . '...' . $fileExtension : $fileName;
                                                                        echo '<a href="attachments/' . $fileName . '" download="' . $fileName . '" class="d-block mb-2">' . $trimmedName . '</a>';
                                                                    }
                                                                    echo '</div>';
                                                                } else {
                                                                    echo '<i class="fw-bold fa fa-times text-danger"></i>';
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <strong class="text-success <?= !$ifAllTransferred ? 'd-none' : '' ?>" id="transferredText">All enteries are Transferred!</strong>
                                    <div class="card mt-3 <?= $ifAllTransferred ? 'd-none transfer-form' : '' ?>">
                                        <div class="card-body">
                                            <span class="fw-bold text-danger tex-sm my-2" id="transfer-alert"></span>
                                            <form method="post" class="table-form">
                                                <input type="hidden" name="unique_code" value="<?= $parent['type'] . $parent['p_type'][0] . ($Shipping['transfer_by'] === 'sea' ? 'se' : 'rd')
                                                                                                    . '_' . $parent['p_id'] . '_'; ?>">
                                                <input type="hidden" name="currentLoading" id="currentLoading">
                                                <input type="hidden" name="openRecord" value="<?= $parent['id']; ?>">
                                                <input type="hidden" name="ag_row_id" id="row_id" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['row_id'] : ''; ?>">
                                                <!--  -->
                                                <h5 class="text-primary">Agent Details</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-1">
                                                        <label for="ag_acc_no" class="form-label">Acc No</label>
                                                        <input type="text" name="ag_acc_no" id="ag_acc_no" required class="form-control form-control-sm" onkeyup="agentDetails()" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['ag_acc_no'] : ''; ?>">
                                                        <!--  -->
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="ag_name" class="form-label">AGENT NAME</label>
                                                        <input type="text" name="ag_name" id="ag_name" required class="form-control form-control-sm" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['ag_name'] : ''; ?>">
                                                        <!--  -->
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="ag_id" class="form-label">AGENT ID</label>
                                                        <input type="text" name="ag_id" id="ag_id" required class="form-control form-control-sm" value="<?= isset($record['agent_details']) && !empty($record['agent_details']) ? json_decode($record['agent_details'], true)['ag_id'] : ''; ?>">
                                                        <!--  -->
                                                    </div>
                                                    <?php
                                                    $warehouse = !empty(json_decode($record['agent_details'], true)['cargo_transfer_warehouse']) ? json_decode($record['agent_details'], true)['cargo_transfer_warehouse'] : '';
                                                    $warehouseOptions = ['Local Import', 'Free Zone Import', 'Import Re-Export', 'Transit', 'Local Export', 'Local Market'];
                                                    $saleCheck = '';
                                                    if ($parent['type'] === 's') {
                                                        $saleCheck = 'onchange="currentStock(this)"';
                                                    }
                                                    ?>

                                                    <!-- Cargo Transfer Dropdown -->
                                                    <div class="col-md-3">
                                                        <label for="cargo_transfer" class="form-label">Cargo Transfer</label>
                                                        <select id="cargo_transfer" name="cargo_transfer" class="form-select form-control-sm" <?= $saleCheck; ?> required>
                                                            <option disabled <?= !in_array($warehouse, $warehouseOptions) ? 'selected' : ''; ?>>Select One</option>
                                                            <option value="Local Import" <?= $warehouse === 'Local Import' ? 'selected' : ''; ?>>Local Import</option>
                                                            <option value="Free Zone Import" <?= $warehouse === 'Free Zone Import' ? 'selected' : ''; ?>>Free Zone Import</option>
                                                            <option value="Import Re-Export" <?= $warehouse === 'Import Re-Export' ? 'selected' : ''; ?>>Import Re-Export</option>
                                                            <option value="Transit" <?= $warehouse === 'Transit' ? 'selected' : ''; ?>>Transit</option>
                                                            <option value="Local Export" <?= $warehouse === 'Local Export' ? 'selected' : ''; ?>>Local Export</option>
                                                            <option value="Local Market" <?= $warehouse === 'Local Market' ? 'selected' : ''; ?>>Local Market</option>
                                                        </select>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="ag_transfer_ids" class="form-label">Transfer IDs <small> ( Ex:66-4,57-1,66-5 )</small></label>
                                                        <input type="text" name="ag_transfer_ids" id="ag_transfer_ids" required class="form-control form-control-sm">
                                                    </div>

                                                    <!-- Modal Popup for Data -->
                                                    <div class="modal fade" id="warehouseModal" tabindex="-1" aria-labelledby="warehouseModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content border border-primary rounded-2">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="warehouseModalLabel">Select Warehouse Entry</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div id="loadingSpinner" class="text-center my-4">
                                                                        <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden">Loading...</span>
                                                                        </div>
                                                                    </div>
                                                                    <div id="warehouseEntries" style="display: none;">
                                                                        <!-- Table for Entries -->
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><i class="far fa-circle"></i></th>
                                                                                    <th>P#(SR#)</th>
                                                                                    <th>Allot</th>
                                                                                    <th>Goods Name</th>
                                                                                    <th>Size</th>
                                                                                    <th>Brand</th>
                                                                                    <th>Origin</th>
                                                                                    <th>Quantity</th>
                                                                                    <th>Gross Weight</th>
                                                                                    <th>Net Weight</th>
                                                                                    <th>Container No</th>
                                                                                    <th>Container Name</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="entriesTableBody">
                                                                                <!-- Data rows will be inserted here dynamically -->
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" id="connectButton" class="btn btn-primary" disabled>Connect</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($parent['type'] === 's') { ?>
                                                        <div class="col-md-5">
                                                            <label for="warehouse_entry" class="form-label">Current Entries In Warehouse</label>
                                                            <select id="warehouse_entry" name="warehouse_entry" class="form-select form-select-sm">
                                                            </select>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-12 text-end">
                                                        <button name="TransferToAgent" id="TransferToAgent" type="submit"
                                                            class="btn btn-primary btn-sm rounded-0">
                                                            Transfer To Agent
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 card p-4 text-center mt-2">
                    <div class="d-flex justify-content-center align-items-center vh-100">

                        <div>
                            <form action="" method="POST" class="mt-4">
                                <label for="update_permission_ids">Update Edit Permission</label>
                                <input type="text" name="update_permission_ids" class="form-control form-control-sm" id="update_permission_ids" />
                                <select name="permission" id="permission" class="form-select form-select-sm mt-2">
                                    <option value="No" selected>Not Allowed</option>
                                    <option value="Yes">Allowed</option>
                                </select>
                                <input type="submit" name="UpdatePermission" value="Update Permission" class="btn btn-sm btn-primary mt-2">
                            </form>
                            <button class="btn btn-warning btn-sm mt-2 <?= !$ifAllTransferred ? 'd-none' : '' ?>" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">
                                Toggle Transfer Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>
<?php }
    }
}
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let selectedEntry = null;
    let saleQtyValue = null;

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

    $(document).ready(function() {
        $('.row-checkbox').change(function() {
            let selectedValues = [];
            let selectedForPermission = [];
            $('.row-checkbox:checked').each(function() {
                let checkbox = $(this);
                saleQtyValue = parseFloat(checkbox.closest('tr').find('td:nth-child(6)').text().trim());
                selectedValues.push(checkbox.val());
                $('#currentLoading').val(checkbox.closest('tr').data('loading'));
                let agAccNoElem = checkbox.closest('tr').find('.ag_acc_no');
                if (agAccNoElem.children('i.fa-times').length > 0) {
                    $('#transfer-alert').text('This Row #' + checkbox.val() + ' is not transferred to any Agent Yet!');
                    // document.querySelector('.transfer-form').classList.remove('d-none');
                } else {
                    selectedForPermission.push(checkbox.val());
                }
            });
            $('#ag_transfer_ids').val(selectedValues.join(','));
            $('#update_permission_ids').val(selectedForPermission.join(','));
        });
    });




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

    function currentStock(event) {
        if (saleQtyValue !== null) {
            const selectedWarehouse = $('#cargo_transfer').val() ?? '';
            $('#warehouse_entry').html('');
            $('#entriesTableBody').html('');
            $('#warehouseModal').modal('show');
            $('#loadingSpinner').show();
            $('#connectButton').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: 'ajax/purchase_enteries_in_warehouse.php',
                data: {
                    warehouse: selectedWarehouse
                },
                success: function(res) {
                    try {
                        const data = JSON.parse(res);

                        if (data && Object.keys(data).length > 0) {
                            let entriesHtml = '';

                            // Loop through each warehouse and its entries
                            Object.entries(data).forEach(([warehouse, entries]) => {
                                entriesHtml += `
                                <tr>
                                    <td colspan="12" class="bg-primary text-white text-center">
                                        Warehouse: ${warehouse}
                                    </td>
                                </tr>`;

                                entries.forEach(entry => {
                                    entriesHtml += `
                                    <tr>
                                        <td>
                                            <input type="radio" name="warehouseEntry" 
                                                value="${entry.unique_code}~${entry.goods_id}~${entry.goods_name}~${entry.quantity_no}~${entry.quantity_name}~${entry.gross_weight}~${entry.net_weight}" />
                                        </td>
                                        <td class="d-none">P#${entry.p_id} (${entry.sr_no}) => ${entry.goods_name} (${entry.quantity_no}) ${entry.quantity_name}</td>
                                        <td>P#${entry.p_id} (${entry.sr_no})</td>
                                        <td>${entry.allot}</td>
                                        <td>${entry.goods_name}</td>
                                        <td>${entry.size}</td>
                                        <td>${entry.brand}</td>
                                        <td>${entry.origin}</td>
                                        <td>
                                            <span class="ajax-qty">${entry.quantity_no}</span>
                                            <sub>${entry.quantity_name}</sub>
                                        </td>
                                        <td>${entry.gross_weight}</td>
                                        <td>${entry.net_weight}</td>
                                        <td>${entry.container_no}</td>
                                        <td>${entry.container_name}</td>
                                    </tr>`;
                                });
                            });

                            $('#entriesTableBody').html(entriesHtml);
                            $('#warehouseEntries').show();
                        } else {
                            $('#entriesTableBody').html('<tr><td colspan="12" class="text-center">No entries found.</td></tr>');
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);
                    } finally {
                        $('#loadingSpinner').hide();
                    }
                },
                error: function() {
                    $('#entriesTableBody').html('<tr><td colspan="12" class="text-danger text-center">Error fetching data.</td></tr>');
                    $('#loadingSpinner').hide();
                }
            });
        } else {
            alert("Please Select an entry first!");
        }
    }


    // Handle entry selection
    $(document).on('change', '#warehouseEntries input[type="radio"]', function() {
        selectedEntry = $(this).val();
        $('#connectButton').prop('disabled', false);
    });

    // Handle connect button click
    $('#connectButton').click(function() {
        if (selectedEntry) {
            const selectedRadio = $('#warehouseEntries input[type="radio"]:checked');
            const selectedLabel = selectedRadio.closest('tr').find('td').eq(1).text().trim();
            let purchasedQtyValue = parseFloat(selectedRadio.closest('tr').find('td').eq(8).find('.ajax-qty').text().trim());
            if (saleQtyValue <= purchasedQtyValue) {
                if (selectedLabel) {
                    const optionHtml = `<option value="${selectedEntry}" selected>${selectedLabel}</option>`;
                    $('#warehouse_entry').html(optionHtml);
                    $('#warehouseModal').modal('hide');
                } else {
                    alert('Failed to retrieve entry details. Please try again.');
                }
            } else {
                alert('Sale Quantity is greater then purchased');
            }
        }
    });
</script>
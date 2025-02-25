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

                    <?php if (!empty($BL['loading_info']['report'])): ?>
                        <div class="col-md-12">
                            <div class="fs-6 fw-bold">Report</div>
                            <?php
                            echo $BL['loading_info']['report'];
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
                                    $currentGoodsStats = [];
                                    if (!empty($BL['goods_info'])) {
                                        foreach ($BL['goods_info'] as $key => $good) {
                                            $currentGoodsStats[$key] = $good;
                                            $Agent = isset($BL['agent_info'][$key]) ? ($BL['agent_info'][$key]['agent_exist'] ? $BL['agent_info'][$key] : []) : [];
                                            $Warehouse = isset($BL['warehouse_info'][$key]) ? $BL['warehouse_info'][$key] : [];
                                            $ifAllTransferred = (isset($Agent['ag_acc_no']) && !empty($Agent['ag_acc_no'])) ? true : false;
                                    ?>
                                            <tr class="<?= !empty($Agent) ? 'my-bg-secondary' : ''; ?>">
                                                <td class="border border-dark text-center">
                                                    <?php if (empty($Agent)) { ?>
                                                        <input type="checkbox" class="row-checkbox" value="<?= $BL['bl_no'] . '~' . $good['sr']; ?>"> <?php } else { ?>
                                                        <input type="checkbox" class="row-checkbox d-none" value="<?= $BL['bl_no'] . '~' . $good['sr']; ?>" id="<?= $BL['bl_no'] . '~' . $good['sr']; ?>">
                                                        <label for="<?= $BL['bl_no'] . '~' . $good['sr']; ?>" class="text-primary text-decoration-underline pointer">Edit</label>
                                                        <style>
                                                            .my-bg-secondary>* {
                                                                background-color: #f7f7f7 !important;
                                                            }
                                                        </style>
                                                    <?php } ?>
                                                </td>
                                                <td class="border border-dark"><a href="general-loading?t_id=<?= $BL['t_id']; ?>&view=1&edit=<?= $key; ?>">#<?= $good['sr']; ?></a></td>
                                                <td class="border border-dark"><?= $good['container_no']; ?></td>
                                                <td class="border border-dark"><?= goodsName($good['good']['goods_id']); ?></td>

                                                <td class="border border-dark"><?= $good['quantity_name']; ?></td>
                                                <td class="border border-dark"><?= $good['quantity_no']; ?></td>
                                                <td class="border border-dark"><?= $good['gross_weight']; ?></td>
                                                <td class="border border-dark"><?= $good['net_weight']; ?></td>
                                                <td class="border border-dark ag_acc_no"><?= isset($Agent['ag_acc_no']) ? (!empty($Agent['ag_acc_no']) ? $Agent['ag_acc_no'] : 'NOT EXIST') : '<i class="text-danger fa fa-times"></i>'; ?></td>
                                                <td class="border border-dark"><?= isset($Agent['ag_name']) ? (!empty($Agent['ag_name']) ? $Agent['ag_name'] : 'NOT EXIST') : '<i class="text-danger fa fa-times"></i>'; ?></td>
                                                <td class="border border-dark fw-bold"><?= $Agent['edit_permission'] ?? false ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'; ?></td>
                                                <td class="border border-dark text-success" style="position: relative;">
                                                    <?php
                                                    $attachments = json_decode($BL['attachments'], true) ?? [];
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
                        <div class="d-none transfer-form">
                            <form method="POST">
                                <h5 class="text-primary">Agent Details</h5>
                                <div class="row g-3">
                                    <div class="col-md-1">
                                        <label for="agent_exist" class="form-label">Exist ??</label>
                                        <select name="agent_exist" id="agent_exist" class="form-select form-select-sm">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2" id="AccNoInputDiv">
                                        <label for="ag_acc_no" class="form-label">Acc No</label>
                                        <input type="text" name="ag_acc_no" id="ag_acc_no" class="form-control form-control-sm" onkeyup="agentDetails()">
                                        <input type="hidden" name="row_id" id="row_id">
                                    </div>
                                    <div class="col-md-3" id="AGNameInputDiv">
                                        <label for="ag_name" class="form-label">AGENT NAME</label>
                                        <input type="text" name="ag_name" id="ag_name" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2" id="AGIDInputDiv">
                                        <label for="ag_id" class="form-label">AGENT ID</label>
                                        <input type="text" name="ag_id" id="ag_id" class="form-control form-control-sm">
                                    </div>
                                    <?php $saleCheck = $BL['p_s'] === 's' ? 'onchange="linkPurchase(this)"' : '';  ?>
                                    <div class="col-md-3">
                                        <label for="warehouse" class="form-label">Warehouse</label>
                                        <select id="warehouse" name="warehouse" class="form-select form-select-sm" <?= $saleCheck; ?> required>
                                            <option disabled selected>Select One</option>
                                            <?php
                                            $warehouse_types = mysqli_fetch_all(fetch('static_types', ['type_for' => 'warehouse']), MYSQLI_ASSOC);
                                            foreach ($warehouse_types as $warehouse_type) { ?>
                                                <option
                                                    value="<?= $warehouse_type['details']; ?>">
                                                    <?= $warehouse_type['type_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Modal Popup for Warehouse Entries -->
                                    <div class="modal fade" id="warehouseModal" tabindex="-1" aria-labelledby="warehouseModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content border border-primary rounded-2">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="warehouseModalLabel">Select Warehouse Entry</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Loading Spinner -->
                                                    <div id="loadingSpinner" class="text-center my-4">
                                                        <div class="spinner-border" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>

                                                    <!-- Table to Display Warehouse Entries -->
                                                    <div id="warehouseEntries" style="display: none;">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th><i class="far fa-circle"></i></th>
                                                                    <th>P#(SR#)</th>
                                                                    <th>Date</th>
                                                                    <th>Allotment</th>
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

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <input type="hidden" name="bl_id" value="<?= $BL['id']; ?>">
                                        <input type="hidden" name="p_s" value="<?= $BL['p_s'] ?>">
                                        <input type="hidden" name="goods_info" id="goods_info" value='<?= json_encode($BL['goods_info'] ?? []); ?>'>
                                        <input type="hidden" name="agent_info" id="agent_info" value='<?= json_encode($BL['agent_info'] ?? []); ?>'>
                                        <input type="hidden" name="warehouse_info" id="warehouse_info" value='<?= json_encode($BL['warehouse_info'] ?? []); ?>'>
                                        <input type="hidden" name="purchase_selected_ids" id="purchase_selected_ids">
                                        <input type="hidden" name="keys" id="keys">
                                        <button name="TransferToAgentAndWarehouse" id="TransferToAgentAndWarehouse" type="submit"
                                            class="btn btn-primary btn-sm rounded-0">
                                            Transfer To Agent
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3">
                        <?php /*
                        $text = $BL['p_s'] === 's' ? 'Purchases: ' : 'Sales: ';
                        $ps = $BL['p_s'] === 's' ? 'S# ' . $BL['t_sr'] : 'P# ' . $BL['t_sr'];
                        $psInvert = $BL['p_s'] === 'p' ? 'S# ' : 'P# ';
                        $warehouses = mysqli_fetch_all(fetch('warehouses'), MYSQLI_ASSOC);
                        $thatenteries = []; 
                        foreach($warehouses as $one){
                            $thatenteries[$one['good_code']] = $one['ps_info'];
                        }
                        $Gloadings = mysqli_fetch_all(fetch('general_loading'), MYSQLI_ASSOC);
                        if (!empty($BL['warehouse_info'])) { ?>
                            <div>
                                <strong><?= $text; ?></strong><br>
                                <?php
                                foreach ($currentGoodsStats as $key => $good) { ?>
                                    <span><?= $ps; ?>(<?= $good['sr']; ?>) => (<?= ucwords(str_replace('-', ' ', $BL['warehouse_info'][$key]['warehouse'])); ?> -> (<?= $good['quantity_no']; ?>) -> Warehouse) <?= $psInvert; ?> 1(1)</span><br>
                                <?php } ?>
                            </div>
                        <?php
                        } */ ?>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 card p-4 text-center mt-2">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div>
                <form method="POST" class="mt-4">
                    <input type="hidden" name="permissionkeys" id="permissionkeys">
                    <input type="hidden" name="bl_id" value="<?= $BL['id']; ?>">
                    <label for="permission">Update Edit Permission</label>
                    <select name="permission" id="permission" class="form-select form-select-sm mt-2">
                        <option value="no" selected>Not Allowed</option>
                        <option value="yes">Allowed</option>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        var goodsInfo = JSON.parse($('#goods_info').val() || '{}');
        var agentInfo = JSON.parse($('#agent_info').val() || '{}');
        var warehouseInfo = JSON.parse($('#warehouse_info').val() || '{}');
        if ($('#agent_exist').val() === 'no') {
            $('#AccNoInputDiv, #AGNameInputDiv, #AGIDInputDiv').hide();
        } else {
            $('#AccNoInputDiv, #AGNameInputDiv, #AGIDInputDiv').show();
        }
        $("#agent_exist").on("change", function() {
            if ($(this).val() === 'no') {
                $("#AccNoInputDiv, #AGNameInputDiv, #AGIDInputDiv").hide();
            } else {
                $("#AccNoInputDiv, #AGNameInputDiv, #AGIDInputDiv").show();
            }
        });
        var selectedKeys = [];

        function updateSelectedKeysInput() {
            var keysString = selectedKeys.join('~');
            $('#keys').val(keysString);
            $('#permissionkeys').val(keysString);
        }

        function fillFieldsIfSingle() {
            if (selectedKeys.length === 1) {
                var key = selectedKeys[0];
                if (agentInfo.hasOwnProperty(key)) {
                    $('#agent_exist').val(agentInfo[key].agent_exist === true ? 'yes' : 'no');
                    if (!agentInfo[key].agent_exist) {
                        $('#ag_acc_no, #ag_name, #ag_id, #warehouse, #row_id').val('');
                        $("#AccNoInputDiv, #AGNameInputDiv, #AGIDInputDiv").hide();
                    } else {
                        $('#ag_acc_no').val(agentInfo[key].ag_acc_no);
                        $('#ag_name').val(agentInfo[key].ag_name);
                        $('#ag_id').val(agentInfo[key].ag_id);
                        $('#row_id').val(warehouseInfo[key].row_id);
                    }
                    $('#warehouse').val(warehouseInfo[key].warehouse);
                }
            } else {
                $('#ag_acc_no, #ag_name, #ag_id, #warehouse').val('');
            }
        }
        $('.row-checkbox').on('change', function() {
            $('.transfer-form').removeClass('d-none');
            var key = $(this).val();
            if ($(this).is(':checked')) {
                if (selectedKeys.indexOf(key) === -1) {
                    selectedKeys.push(key);
                }
            } else {
                var index = selectedKeys.indexOf(key);
                if (index !== -1) {
                    selectedKeys.splice(index, 1);
                }
            }
            updateSelectedKeysInput();
            fillFieldsIfSingle();
        });
    });


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

    function linkPurchase(selectInput) {
        let selectedWarehouse = $(selectInput).val();
        const allotmentName = Object.values(JSON.parse($('#goods_info').val()))[0]?.good?.allotment_name;

        $.ajax({
            url: 'ajax/fetch_warehouse_purchases.php',
            type: 'POST',
            dataType: 'json',
            data: {
                warehouse: selectedWarehouse,
                allot: allotmentName
            },
            beforeSend: function() {
                $('#loadingSpinner').show();
                $('#entriesTableBody').empty();
                $('#warehouseEntries').hide();
            },
            success: function(response) {
                let data = Array.isArray(response) ? response : JSON.parse(response); // ✅ Ensure it's an array

                try {
                    if (data && data.length > 0) {
                        let entriesHtml = '';

                        data.forEach(entry => {
                            entriesHtml += `
                    <tr>
                        <td>
                            <input type="checkbox" class="entry-checkbox" value="${entry.good_code}">
                        </td>
                        <td>P#${entry.t_sr} (${entry.id})</td>
                        <td>${entry.date}</td>
                        <td>${entry.good_data.good.allotment_name}</td>
                        <td>${entry.good_data.good.goods_name}</td>
                        <td>${entry.good_data.good.size}</td>
                        <td>${entry.good_data.good.brand}</td>
                        <td>${entry.good_data.good.origin}</td>
                        <td>${entry.good_data.quantity_no} <sub>${entry.good_data.quantity_name}</sub></td>
                        <td>${entry.good_data.gross_weight}</td>
                        <td>${entry.good_data.net_weight}</td>
                        <td>${entry.good_data.container_no}</td>
                        <td>${entry.good_data.container_name}</td>
                    </tr>`;
                        });

                        $('#entriesTableBody').html(entriesHtml);
                        $('#warehouseEntries').show();

                        $('#warehouseModal').modal('show');

                    } else {
                        $('#entriesTableBody').html('<tr><td colspan="12" class="text-center">No entries found.</td></tr>');
                    }
                } catch (error) {
                    console.error('Error parsing data:', error);
                } finally {
                    $('#loadingSpinner').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                $('#loadingSpinner').hide();
            }
        });
    }
    $(document).ready(function() {
        // Function to handle checkbox selection
        function updateConnectButtonState() {
            let checkedBoxes = $('.entry-checkbox:checked');
            $('#connectButton').prop('disabled', checkedBoxes.length === 0);
        }

        // Listen for checkbox changes
        $(document).on('change', '.entry-checkbox', function() {
            updateConnectButtonState();
        });

        // Handle connect button click
        $('#connectButton').click(function() {
            let selectedValues = [];

            $('.entry-checkbox:checked').each(function() {
                selectedValues.push($(this).val());
            });

            // Join values with '@' and store in hidden input
            $('#purchase_selected_ids').val(selectedValues.join('@'));

            // Close the modal
            $('#warehouseModal').modal('hide');
        });

        // Ensure button is disabled when modal opens
        $('#warehouseModal').on('show.bs.modal', function() {
            $('#connectButton').prop('disabled', true);
        });
    });
</script>
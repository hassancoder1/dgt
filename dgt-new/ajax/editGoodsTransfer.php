<?php
require_once '../connection.php';
// Existing variables and code...
$page_title = 'EDIT INFORMATION';
$back_page_url = $data_for = $pageURL = $_POST['page'];
$unique_code = $_POST['unique_code'];

[$Ttype, $Tcat, $Troute, $TID, $BLUID] = decode_unique_code($unique_code, 'all');

$LoadingsTable = ($Tcat === 'l' ? 'local' : 'general') . '_loading';
$searchColumn = ($Tcat === 'l') ? 'uid' : 'bl_no';
$recordExists = recordExists('data_copies', ['unique_code' => $unique_code]);
$LoadingsData = [];
if ($recordExists) {
    $dataType = "Copied";
    $data = mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $unique_code]));
    $Tdata = json_decode($data['tdata'], true);
    $LoadingsData = json_decode($data['ldata'], true);
} else {
    $dataType = "Original";
    $LoadingsQuery = mysqli_query($connect, "SELECT * FROM $LoadingsTable WHERE $searchColumn = '$BLUID'");
    while ($SL = mysqli_fetch_assoc($LoadingsQuery)) {
        $LoadingsData[] = $SL;
    }
    function normalizeEntry($id, $key, $value, &$normalizedEntry)
    {
        $decoded = is_string($value) ? json_decode($value, true) : null;
        if (is_array($decoded)) {
            foreach ($decoded as $nestedKey => $nestedValue) {
                normalizeEntry($id, $nestedKey, $nestedValue, $normalizedEntry);
            }
        } else {
            $normalizedEntry["l_{$id}_{$key}"] = $value;
        }
    }
    $flattenedData = [];
    foreach ($LoadingsData as $loading) {
        $id = $loading['id'];
        foreach ($loading as $key => $value) {
            normalizeEntry($id, $key, $value, $flattenedData);
        }
    }
    $LoadingsData = $flattenedData;
    $Ttempdata = mysqli_fetch_assoc(fetch('transactions', ['id' => $TID]));
    $Tdata = array_merge(transactionSingle($TID), json_decode($Ttempdata['sea_road'], true), json_decode($Ttempdata['notify_party_details'], true) ?? []);
}

$groupedData = [];
foreach ($LoadingsData as $key => $value) {
    preg_match('/l_(\d+)_/', $key, $matches);
    $l_ID = $matches[1] ?? null;
    if ($l_ID !== null) {
        if (!isset($groupedData[$l_ID])) {
            $groupedData[$l_ID] = [];
        }
        $groupedData[$l_ID][$key] = $value;
    }
}

if (isset($Ldata['rate'], $Ldata['empty_kgs'])) {
    echo "<script>";
    echo "let emptyKgs = " . $Ldata['empty_kgs'] . ";";
    echo "let Rate = " . $Ldata['rate'] . ";";
    echo "</script>";
}
?>

<div class="modal-header bg-white mb-2">
    <h5 class="modal-title" id="staticBackdropLabel">EDIT INFORMATION</h5>
    <a href="<?= $data_for; ?>" class="btn-close"></a>
</div>
<div class="row">
    <div class="col-md-10">
        <form method="POST">
            <?= $recordExists ? '<input type="hidden" name="updateTrue" value="true">' : ''; ?>
            <input type="hidden" name="tdata" value='<?= json_encode($Tdata); ?>'>
            <input type="hidden" name="ldata" value='<?= json_encode($LoadingsData); ?>'>
            <input type="hidden" name="recordEdited" id="recordEdited">
            <?php
            if ($Tcat === 'l') { ?>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row g-3 mt-1">
                            <div class="col-md-<?= $Tcat === 'l' ? '6' : '4'; ?>">
                                <label for="dr_acc_no" class="form-label fw-bold">Purchaser Details</label>
                                <div class="input-group">
                                    <input type="hidden" name="t_dr_acc_id" value="<?= isset($Tdata['dr_acc_id']) ? $Tdata['dr_acc_id'] : ''; ?>" id="dr_acc_id">
                                    <input type="text" id="dr_acc_no" name="t_dr_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                        placeholder="ACC No" value="<?= isset($Tdata['dr_acc']) ? $Tdata['dr_acc'] : ''; ?>">
                                    <input type="text" id="dr_acc_name" name="t_dr_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                        placeholder="Importer Name" value="<?= isset($Tdata['dr_acc_name']) ? $Tdata['dr_acc_name'] : ''; ?>">
                                </div>
                                <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="t_dr_acc_kd_id" id="dr_acc_kd_id">
                                    <option hidden value="">Select Company</option>
                                    <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Tdata['dr_acc_id']) ? $Tdata['dr_acc_id'] : '', 'type' => 'company'));
                                    while ($row = mysqli_fetch_array($run_query)) {
                                        $row_data = json_decode($row['json_data']);
                                        $sel_kd2 = $row['id'] == $Tdata['dr_acc_kd_id'] ? 'selected' : '';
                                        echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                    }  ?>
                                </select>
                                <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="t_dr_acc_details" id="dr_acc_details" rows="5"
                                    placeholder="Company Details"><?= isset($Tdata['dr_acc_details']) ? $Tdata['dr_acc_details'] : ''; ?></textarea>
                            </div>

                            <div class="col-md-<?= $Tcat === 'l' ? '6' : '4'; ?>">
                                <label for="cr_acc_no" class="form-label fw-bold">Seller Details</label>
                                <div class="input-group">
                                    <input type="hidden" name="t_cr_acc_id" value="<?= isset($Tdata['cr_acc_id']) ? $Tdata['cr_acc_id'] : ''; ?>" id="cr_acc_id">
                                    <input type="text" id="cr_acc_no" name="t_cr_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                        placeholder="ACC No" value="<?= isset($Tdata['cr_acc']) ? $Tdata['cr_acc'] : ''; ?>">
                                    <input type="text" id="cr_acc_name" name="t_cr_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                        placeholder="Notify Party Name" value="<?= isset($Tdata['cr_acc_name']) ? $Tdata['cr_acc_name'] : ''; ?>">
                                </div>
                                <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="t_cr_acc_kd_id" id="cr_acc_kd_id">
                                    <option hidden value="">Select Company</option>
                                    <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Tdata['cr_acc_id']) ? $Tdata['cr_acc_id'] : '', 'type' => 'company'));
                                    while ($row = mysqli_fetch_array($run_query)) {
                                        $row_data = json_decode($row['json_data']);
                                        $sel_kd2 = $row['id'] == $Tdata['cr_acc_kd_id'] ? 'selected' : '';
                                        echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                    }  ?>
                                </select>
                                <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="t_cr_acc_details" id="cr_acc_details" rows="5"
                                    placeholder="Company Details"><?= isset($Tdata['cr_acc_details']) ? $Tdata['cr_acc_details'] : ''; ?></textarea>
                            </div>
                            <?php /* if ($Tcat !== 'l') { ?>
                                    <div class="col-md-4">
                                        <label for="np_acc" class="form-label fw-bold">Transaction Notify Party Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="t_np_acc_id" value="<?= isset($Tdata['np_acc_id']) ? $Tdata['np_acc_id'] : ''; ?>" id="np_acc_id">
                                            <input type="text" id="np_acc" name="t_np_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Tdata['np_acc']) ? $Tdata['np_acc'] : ''; ?>">
                                            <input type="text" id="np_acc_name" name="t_np_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                                placeholder="Notify Party Name" value="<?= isset($Tdata['np_acc_name']) ? $Tdata['np_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="t_np_acc_kd_id" id="np_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Tdata['np_acc_id']) ? $Tdata['np_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Tdata['np_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            }  ?>
                                        </select>
                                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="t_np_acc_details" id="np_acc_details" rows="5"
                                            placeholder="Company Details"><?= isset($Tdata['np_acc_details']) ? $Tdata['np_acc_details'] : ''; ?></textarea>
                                    </div>
                                <?php } */ ?>
                        </div>
                    </div>
                </div>
            <?php }
            $processedFirst = false;
            foreach ($groupedData as $l_ID => $largeEnteries):
                if ($processedFirst) {
                    break;
                }
                $Ldata = [];
                foreach ($largeEnteries as $key => $value) {
                    $Ldata[str_replace("l_{$l_ID}_", "", $key)] = $value;
                }
                $processedFirst = true;
            ?>
                <div>
                    <?php
                    if ($Tcat !== 'l') { ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="im_acc_no" class="form-label fw-bold">Importer Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="l_<?= $l_ID; ?>_im_acc_id" value="<?= isset($Ldata['im_acc_id']) ? $Ldata['im_acc_id'] : ''; ?>" id="im_acc_id">
                                            <input type="text" id="im_acc_no" name="l_<?= $l_ID; ?>_im_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Ldata['im_acc_no']) ? $Ldata['im_acc_no'] : ''; ?>">
                                            <input type="text" id="im_acc_name" name="l_<?= $l_ID; ?>_im_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                                placeholder="Importer Name" value="<?= isset($Ldata['im_acc_name']) ? $Ldata['im_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="l_<?= $l_ID; ?>_im_acc_kd_id" id="im_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['im_acc_id']) ? $Ldata['im_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Ldata['im_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            }  ?>
                                        </select>
                                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="l_<?= $l_ID; ?>_im_acc_details" id="im_acc_details" rows="5"
                                            placeholder="Company Details"><?= isset($Ldata['im_acc_details']) ? $Ldata['im_acc_details'] : ''; ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="xp_acc_no" class="form-label fw-bold">Exporter Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="l_<?= $l_ID; ?>_xp_acc_id" value="<?= isset($Ldata['xp_acc_id']) ? $Ldata['xp_acc_id'] : ''; ?>" id="xp_acc_id">
                                            <input type="text" id="xp_acc_no" name="l_<?= $l_ID; ?>_xp_acc_no" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Ldata['xp_acc_no']) ? $Ldata['xp_acc_no'] : ''; ?>">
                                            <input type="text" id="xp_acc_name" name="l_<?= $l_ID; ?>_xp_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                                placeholder="Importer Name" value="<?= isset($Ldata['xp_acc_name']) ? $Ldata['xp_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="l_<?= $l_ID; ?>_xp_acc_kd_id" id="xp_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['xp_acc_id']) ? $Ldata['xp_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Ldata['xp_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            }  ?>
                                        </select>
                                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="l_<?= $l_ID; ?>_xp_acc_details" id="xp_acc_details" rows="5"
                                            placeholder="Company Details"><?= isset($Ldata['xp_acc_details']) ? $Ldata['xp_acc_details'] : ''; ?></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="np_acc_no" class="form-label fw-bold">Notify Party Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="l_<?= $l_ID; ?>_np_acc_id" value="<?= isset($Ldata['np_acc_id']) ? $Ldata['np_acc_id'] : ''; ?>" id="np_acc_id">
                                            <input type="text" id="np_acc_no" name="l_<?= $l_ID; ?>_np_acc_no" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Ldata['np_acc_no']) ? $Ldata['np_acc_no'] : ''; ?>">
                                            <input type="text" id="np_acc_name" name="l_<?= $l_ID; ?>_np_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                                placeholder="Notify Party Name" value="<?= isset($Ldata['np_acc_name']) ? $Ldata['np_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="l_<?= $l_ID; ?>_np_acc_kd_id" id="np_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['np_acc_id']) ? $Ldata['np_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Ldata['np_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            }  ?>
                                        </select>
                                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="l_<?= $l_ID; ?>_np_acc_details" id="np_acc_details" rows="5"
                                            placeholder="Company Details"><?= isset($Ldata['np_acc_details']) ? $Ldata['np_acc_details'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row gy-3">
                                <?php
                                if ($Tcat === 'l') {
                                    if ($Ldata['route'] === 'local') { ?>
                                        <div class="col-md-2">
                                            <label for="truck_no" class="form-label">Truck Number</label>
                                            <input id="truck_no" name="l_<?= $l_ID; ?>_truck_no"
                                                value="<?php echo $Ldata['truck_no']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="truck_name" class="form-label">Truck Name</label>
                                            <input id="truck_name" name="l_<?= $l_ID; ?>_truck_name"
                                                value="<?php echo $Ldata['truck_name']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="loading_warehouse" class="form-label">Loading Warehouse</label>
                                            <input id="loading_warehouse" name="l_<?= $l_ID; ?>_loading_warehouse"
                                                value="<?php echo $Ldata['loading_warehouse']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="receiving_warehouse" class="form-label">Receiving Warehouse</label>
                                            <input id="receiving_warehouse" name="l_<?= $l_ID; ?>_receiving_warehouse"
                                                value="<?php echo $Ldata['receiving_warehouse']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <label for="loading_company_name" class="form-label">Loading Company</label>
                                        <input id="loading_company_name" name="l_<?= $l_ID; ?>_loading_company_name"
                                            value="<?php echo $Ldata['loading_company_name']; ?>" type="text"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="receiving_company_name" class="form-label">Receiving Company Name</label>
                                        <input id="receiving_company_name" name="l_<?= $l_ID; ?>_receiving_company_name"
                                            value="<?php echo $Ldata['receiving_company_name']; ?>" type="text"
                                            class="form-control form-control-sm">
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-2">
                                        <label for="loading_country" class="form-label">Loading Country</label>
                                        <input type="text" name="l_<?= $l_ID; ?>_loading_country" id="loading_country" value="<?= $Ldata['loading_country']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="loading_port_name" class="form-label">L <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                        <input type="text" name="l_<?= $l_ID; ?>_loading_port_name" id="loading_port_name" value="<?= $Ldata['loading_port_name']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="receiving_country" class="form-label">Receiving Country</label>
                                        <input type="text" name="l_<?= $l_ID; ?>_receiving_country" id="receiving_country" value="<?= $Ldata['receiving_country']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="receiving_port_name" class="form-label">R <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                        <input type="text" name="l_<?= $l_ID; ?>_receiving_port_name" id="receiving_port_name" value="<?= $Ldata['receiving_port_name']; ?>" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Name -->
                                    <div class="col-md-3">
                                        <label for="shipping_name" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name</label>
                                        <input type="text" name="l_<?= $l_ID; ?>_shipping_name" value="<?= $Ldata['shipping_name']; ?>" id="shipping_name" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Address -->
                                    <div class="col-md-4">
                                        <label for="shipping_address" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address</label>
                                        <input type="text" name="l_<?= $l_ID; ?>_shipping_address" value="<?= $Ldata['shipping_address']; ?>" id="shipping_address" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Phone -->
                                    <div class="col-md-2">
                                        <label for="shipping_phone" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone</label>
                                        <input type="tel" name="l_<?= $l_ID; ?>_shipping_phone" value="<?= $Ldata['shipping_phone']; ?>" id="shipping_phone" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping WhatsApp -->
                                    <div class="col-md-2">
                                        <label for="shipping_whatsapp" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp</label>
                                        <input type="tel" name="l_<?= $l_ID; ?>_shipping_whatsapp" value="<?= $Ldata['shipping_whatsapp']; ?>" id="shipping_whatsapp" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Email -->
                                    <div class="col-md-3">
                                        <label for="shipping_email" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email</label>
                                        <input type="email" name="l_<?= $l_ID; ?>_shipping_email" value="<?= $Ldata['shipping_email']; ?>" id="shipping_email" required class="form-control form-control-sm">
                                    </div>
                                <?php } ?>
                                <div class="col-md-2">
                                    <label for="loading_date" class="form-label">Loading Date</label>
                                    <input type="date" class="form-control form-control-sm" id="loading_date" name="l_<?= $l_ID; ?>_loading_date"
                                        value="<?php echo $Ldata['loading_date']; ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="receiving_date" class="form-label">Receiving Date</label>
                                    <input type="date" class="form-control form-control-sm" id="receiving_date" name="l_<?= $l_ID; ?>_receiving_date"
                                        value="<?php echo $Ldata['receiving_date']; ?>">
                                </div>

                                <?php $warehouse = isset($Ldata['warehouse_transfer']) ? ($Ldata['warehouse_transfer'] ?? '') : ($Ldata['cargo_transfer_warehouse'] ?? '');
                                $saleCheck = $Ttype === 's' ? 'onchange="currentStock(this,\'' . $l_ID . '\',\'' . $Ldata['goods_id'] . '\',\'' . $Ldata['size'] . '\',\'' . $Ldata['brand'] . '\',\'' . $Ldata['origin'] . '\',\'' . $Ldata['quantity_name'] . '\')"' : ''; ?>
                                <!-- Warehouse Transfer Field -->
                                <div class="col-md-3">
                                    <label for="l_<?= $l_ID; ?>_warehouse_transfer" class="form-label">Cargo Transfer</label>
                                    <select id="l_<?= $l_ID; ?>_warehouse_transfer" name="l_<?= $l_ID; ?>_warehouse_transfer" class="form-select form-select-sm" <?= $saleCheck; ?> required>
                                        <option disabled <?= empty($warehouse) ? 'selected' : '' ?>>Select One</option>
                                        <option value="Local Import" <?= isset($warehouse) && $warehouse === 'Local Import' ? 'selected' : '' ?>>Local Import</option>
                                        <option value="Free Zone Import" <?= isset($warehouse) && $warehouse === 'Free Zone Import' ? 'selected' : '' ?>>Free Zone Import</option>
                                        <option value="Import Re-Export" <?= isset($warehouse) && $warehouse === 'Import Re-Export' ? 'selected' : '' ?>>Import Re-Export</option>
                                        <option value="Transit" <?= isset($warehouse) && $warehouse === 'Transit' ? 'selected' : '' ?>>Transit</option>
                                        <option value="Local Export" <?= isset($warehouse) && $warehouse === 'Local Export' ? 'selected' : '' ?>>Local Export</option>
                                        <option value="Local Market" <?= isset($warehouse) && $warehouse === 'Local Market' ? 'selected' : '' ?>>Local Market</option>
                                    </select>
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

                                <?php if ($Ttype === 's') { ?>
                                    <div class="col-md-5">
                                        <label for="l_<?= $l_ID; ?>_warehouse_entry" class="form-label">Current Enteries In Selected WareHouse</label>
                                        <select
                                            id="l_<?= $l_ID; ?>_warehouse_entry"
                                            name="l_<?= $l_ID; ?>_warehouse_entry"
                                            class="form-select form-select-sm"
                                            <?= $saleCheck; ?>
                                            required>
                                            <?php if (!empty($Ldata['warehouse_entry'])) {
                                                $againstEntry = explode('~', $Ldata['warehouse_entry']); ?>
                                                <option value="<?= $Ldata['warehouse_entry']; ?>">
                                                    <?= ucfirst(decode_unique_code($againstEntry['0'], 'Ttype')) . '#' . decode_unique_code($againstEntry['0'], 'TID') . ' => ' . $againstEntry[3]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } else {
                                    if (!empty($Ldata['sold_to'])) {
                                        $soldToEntry = explode('~', $Ldata['sold_to']);
                                        echo '<span class="fw-bold text-danger"> SOLD IN ' . ucfirst(decode_unique_code($soldToEntry['0'], 'Ttype')) . '#' . decode_unique_code($soldToEntry['0'], 'TID') . ' => ' . $soldToEntry[2], '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach; ?>
            <div>
                <table class="table mt-2 table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="bg-dark text-white">Sr#</th>
                            <th class="bg-dark text-white">Container No</th>
                            <th class="bg-dark text-white">B/L | UID</th>
                            <th class="bg-dark text-white">Goods Name</th>
                            <th class="bg-dark text-white">Quantity</th>
                            <th class="bg-dark text-white">G.W.KGS</th>
                            <th class="bg-dark text-white">N.W.KGS</th>
                            <th class="bg-dark text-white">L.DATE</th>
                            <th class="bg-dark text-white">L.<?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?></th>
                            <th class="bg-dark text-white">R.DATE</th>
                            <th class="bg-dark text-white">R.<?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?></th>
                            <th class="bg-dark text-white">Edit</th>
                        </tr>
                    </thead>
                    <tbody class="loadingsTable">
                        <?php
                        $quantity_no = $gross_weight = $net_weight = 0;
                        foreach ($groupedData as $l_ID => $row) {
                        ?>
                            <tr>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_sr_no"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_container_no"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_bl_no"] ?? $row["l_{$l_ID}_uid"]; ?></td>
                                <td class="border border-dark"><?= goodsName($row["l_{$l_ID}_goods_id"]); ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_quantity_no"] ?? 'N/A'; ?> <sub><?= $row["l_{$l_ID}_quantity_name"] ?? 'N/A'; ?></sub></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_gross_weight"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_net_weight"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_loading_date"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_loading_port_name"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_receiving_date"] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= $row["l_{$l_ID}_receiving_port_name"] ?? 'N/A'; ?></td>
                                <td>
                                    <i class="fa fa-pencil fs-5 text-primary pointer toggle-icon"
                                        data-id="<?= $l_ID; ?>"></i>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>
            <?php
            foreach ($groupedData as $l_ID => $largeEnteries):
                $Ldata = [];
                foreach ($largeEnteries as $key => $value) {
                    $Ldata[str_replace("l_{$l_ID}_", "", $key)] = $value;
                }
            ?>
                <div class="d-none entryform<?= $l_ID; ?>">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-md-3">
                                    <div class="input-group my-2">
                                        <label for="allotment_name" class="col-form-label text-nowrap">Allotment Name</label>
                                        <input value="<?= isset($Ldata['allotment_name']) ? $Ldata['allotment_name'] : ''; ?>" id="allotment_name"
                                            name="allotment_name" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="input-group my-2">
                                        <label for="goods_id">GOODS</label>
                                        <select id="<?= $l_ID; ?>goods_id" name="goods_id" class="form-select form-select-sm" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            $goods = fetch('goods');
                                            while ($good = mysqli_fetch_assoc($goods)) {
                                                $g_selected = $good['id'] == $Ldata['goods_id'] ? 'selected' : '';
                                                echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="input-group my-2">
                                        <label for="size">SIZE</label>
                                        <select class="form-select form-select-sm" name="size" id="<?= $l_ID; ?>size" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $Ldata['goods_id']);
                                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                $size_selected = $size_s['size'] == $Ldata['size'] ? 'selected' : '';
                                                echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="input-group my-2">
                                        <label for="origin">ORIGIN</label>
                                        <select class="form-select form-select-sm" name="origin" id="<?= $l_ID; ?>origin" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $Ldata['goods_id']);
                                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                $size_selected = $size_s['origin'] == $Ldata['origin'] ? 'selected' : '';
                                                echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="input-group my-2">
                                        <label for="brand">BRAND</label>
                                        <select class="form-select form-select-sm" name="brand" id="<?= $l_ID; ?>brand" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $Ldata['goods_id']);
                                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                $size_selected = $size_s['brand'] == $Ldata['brand'] ? 'selected' : '';
                                                echo '<option ' . $size_selected . ' value="' . $size_s['brand'] . '">' . $size_s['brand'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7 border-end">
                                    <div class="row gx-1 gy-3">
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label for="qty_name" class="col-sm-4 col-form-label text-nowrap">Qty
                                                    Name</label>
                                                <div class="col-sm">
                                                    <input value="<?php echo $Ldata['qty_name']; ?>" id="<?= $l_ID; ?>qty_name"
                                                        name="qty_name" class="form-control form-control-sm" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="qty_no">Qty#</label>
                                                <div class="col-sm">
                                                    <input value="<?php echo $Ldata['qty_no']; ?>" id="<?= $l_ID; ?>qty_no"
                                                        name="qty_no"
                                                        class="form-control form-control-sm currency" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap" for="qty_kgs">Qty
                                                    KGs</label>
                                                <div class="col-sm">
                                                    <input value="<?php echo $Ldata['qty_kgs']; ?>" id="<?= $l_ID; ?>qty_kgs"
                                                        name="qty_kgs"
                                                        class="form-control form-control-sm currency" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap" for="empty_kgs">Empty
                                                    KGs</label>
                                                <div class="col-sm">
                                                    <input value="<?php echo $Ldata['empty_kgs']; ?>"
                                                        id="<?= $l_ID; ?>empty_kgs"
                                                        name="empty_kgs" class="form-control form-control-sm currency" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="divide">DIVIDE</label>
                                                <div class="col-sm">
                                                    <select id="<?= $l_ID; ?>divide" name="divide" class="form-select">
                                                        <?php $divides = array('TON' => 'TON', 'KGs' => 'KG', 'CARTON' => 'CARTON');
                                                        foreach ($divides as $item => $val) {
                                                            $d_sel = $Ldata['divide'] == $val ? 'selected' : '';
                                                            echo '<option ' . $d_sel . ' value="' . $val . '">' . $item . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="weight">WEIGHT</label>
                                                <div class="col-sm">
                                                    <input value="<?php echo $Ldata['weight']; ?>" id="<?= $l_ID; ?>weight"
                                                        name="weight"
                                                        class="form-control form-control-sm currency" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="price">PRICE</label>
                                                <div class="col-sm">
                                                    <select id="<?= $l_ID; ?>price" name="price" class="form-select form-select-sm">
                                                        <?php $prices = array('TON PRICE' => 'TON', 'KGs PRICE' => 'KG', 'CARTON PRICE' => 'CARTON');
                                                        foreach ($prices as $item => $val) {
                                                            $pr_sel = $Ldata['price'] == $val ? 'selected' : '';
                                                            echo '<option ' . $pr_sel . ' value="' . $val . '">' . $item . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="currency1">Currency</label>
                                                <div class="col-sm">
                                                    <select id="<?= $l_ID; ?>currency1" name="currency1" class="form-select form-select-sm"
                                                        required>
                                                        <option selected hidden disabled value="">Select</option>
                                                        <?php $currencies = fetch('currencies');
                                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                                            $crr_sel = $crr['name'] == $Ldata['currency1'] ? 'selected' : '';
                                                            echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="rate1">RATE</label>
                                                <div class="col-sm">
                                                    <input value="<?php echo $Ldata['rate1']; ?>" id="<?= $l_ID; ?>rate1"
                                                        name="rate1"
                                                        class="form-control form-control-sm currency" required>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (decode_unique_code($unique_code, 'Tcat') !== 'l'): ?>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="currency2">Currency</label>
                                                    <div class="col-sm">
                                                        <select id="<?= $l_ID; ?>currency2" name="currency2" class="form-select form-select-sm"
                                                            required>
                                                            <option selected hidden disabled value="">Select</option>
                                                            <?php $currencies = fetch('currencies');
                                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                $crr_sel2 = $crr['name'] == $Ldata['currency2'] ? 'selected' : '';
                                                                echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="rate2">Rate</label>
                                                    <div class="col-sm">
                                                        <input value="<?php echo $Ldata['rate2']; ?>" id="<?= $l_ID; ?>rate2"
                                                            name="rate2"
                                                            class="form-control form-control-sm currency" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                                    <div class="col-sm">
                                                        <select id="<?= $l_ID; ?>opr" name="opr" class="form-select form-select-sm" required>
                                                            <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                            foreach ($ops as $opName => $op) {
                                                                $op_sel = $Ldata['opr'] == $op ? 'selected' : '';
                                                                echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="tax_percent">Tax %</label>
                                                    <div class="col-sm">
                                                        <input type="text" value="<?php echo $Ldata['tax_percent']; ?>" id="<?= $l_ID; ?>tax_percent"
                                                            name="tax_percent"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="tax_amount">Tax.Amt</label>
                                                    <div class="col-sm">
                                                        <input type="text" value="<?php echo $Ldata['tax_amount']; ?>" id="<?= $l_ID; ?>tax_amount"
                                                            name="tax_amount"
                                                            class="form-control form-control-sm" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <!-- <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="total_with_tax">Amt+Tax</label> -->
                                                    <div class="col-sm">
                                                        <input type="hidden" value="<?php echo $Ldata['total_with_tax']; ?>" id="<?= $l_ID; ?>total_with_tax"
                                                            name="total_with_tax">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <table class="table table-sm">
                                        <tbody class="text-nowrap">
                                            <?php
                                            echo '<tr><th class="fw-normal">TOTAL KGs </th><th><span id="<?= $l_ID; ?>total_kgs_span"></span></th></tr>';
                                            echo '<tr><th class="fw-normal">TOTAL QTY KGs </th><th><span id="<?= $l_ID; ?>total_qty_kgs_span"></span></th></tr>';
                                            echo '<tr><th class="fw-normal">NET KGs </th><th><span id="<?= $l_ID; ?>net_kgs_span"></span></th></tr>';
                                            echo '<tr><th class="fw-normal">TOTAL </th><th><span id="<?= $l_ID; ?>total_span"></span></th></tr>';
                                            echo '<tr><th class="fw-normal">AMOUNT  </th><th><span id="<?= $l_ID; ?>amount_span"></span></th></tr>';
                                            if (decode_unique_code($unique_code, 'Tcat') !== 'l') {
                                                echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span id="<?= $l_ID; ?>final_amount_span"></span></th></tr>';
                                            } else {
                                                echo '<tr><th class="fw-normal text-danger">Amt+Tax  </th><th><span id="<?= $l_ID; ?>total_with_tax_span">0</span></th></tr>';
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                    <input value="<?php echo $Ldata['total_kgs']; ?>" id="<?= $l_ID; ?>total_kgs"
                                        name="total_kgs" type="hidden">
                                    <input value="<?php echo $Ldata['total_qty_kgs']; ?>" id="<?= $l_ID; ?>total_qty_kgs"
                                        name="total_qty_kgs"
                                        type="hidden">
                                    <input value="<?php echo $Ldata['net_kgs']; ?>" id="<?= $l_ID; ?>net_kgs" name="net_kgs"
                                        type="hidden">
                                    <input value="<?php echo $Ldata['total']; ?>" id="<?= $l_ID; ?>total" name="total"
                                        type="hidden">
                                    <input value="<?php echo $Ldata['amount']; ?>" id="<?= $l_ID; ?>amount" name="amount"
                                        type="hidden">
                                    <input value="<?php echo $Ldata['final_amount']; ?>" id="<?= $l_ID; ?>final_amount"
                                        name="final_amount" type="hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        finalAmount<?= $l_ID; ?>();
                        $('#<?= $l_ID ?>qty_no,#<?= $l_ID ?>qty_kgs,#<?= $l_ID ?>empty_kgs,#<?= $l_ID ?>weight,#<?= $l_ID ?>rate1,#<?= $l_ID ?>rate2,#<?= $l_ID ?>opr').on('keyup', function() {
                            finalAmount<?= $l_ID; ?>();
                        });
                        $("#<?= $l_ID ?>goods_id").change(function() {
                            var <?= $l_ID; ?>goods_id = $(this).val();
                            goodDetails<?= $l_ID; ?>(<?= $l_ID; ?>goods_id);
                        });
                        $("#<?= $l_ID ?>type").change(function() {
                            $('#<?= $l_ID ?>bookingForm').toggleClass('d-none row');
                            $('#<?= $l_ID ?>localForm').toggleClass('d-none row');
                        });
                    });

                    function goodDetails<?= $l_ID; ?>(goods_id) {
                        if (goods_id > 0) {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/fetch_good_sizes.php',
                                data: 'goods_id=' + goods_id,
                                success: function(html) {
                                    $('#<?= $l_ID ?>size').html(html);
                                }
                            });
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/fetch_good_brands.php',
                                data: 'goods_id=' + goods_id,
                                success: function(html) {
                                    $('#<?= $l_ID ?>brand').html(html);
                                }
                            });
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/fetch_good_origins.php',
                                data: 'goods_id=' + goods_id,
                                success: function(html) {
                                    $('#<?= $l_ID ?>origin').html(html);
                                }
                            });
                        } else {
                            $('#<?= $l_ID ?>size').html('<option value="">Select</option>');
                            $('#<?= $l_ID ?>brand').html('<option value="">Select</option>');
                            $('#<?= $l_ID ?>origin').html('<option value="">Select</option>');
                        }
                    }

                    function finalAmount<?= $l_ID; ?>() {
                        var <?= $l_ID; ?>qty_no = parseFloat($("#<?= $l_ID ?>qty_no").val()) || 0;
                        var <?= $l_ID; ?>qty_kgs = parseFloat($("#<?= $l_ID ?>qty_kgs").val()) || 0;

                        var <?= $l_ID; ?>total_kgs = qty_no * qty_kgs;
                        $("#<?= $l_ID ?>total_kgs").val(total_kgs);
                        $("#<?= $l_ID ?>total_kgs_span").text(total_kgs);

                        var <?= $l_ID; ?>empty_kgs = parseFloat($("#<?= $l_ID ?>empty_kgs").val()) || 0;
                        var <?= $l_ID; ?>total_qty_kgs = qty_no * empty_kgs;
                        $("#<?= $l_ID ?>total_qty_kgs").val(total_qty_kgs);
                        $("#<?= $l_ID ?>total_qty_kgs_span").text(total_qty_kgs);

                        var <?= $l_ID; ?>net_kgs = total_kgs - total_qty_kgs;
                        $("#<?= $l_ID ?>net_kgs").val(net_kgs);
                        $("#<?= $l_ID ?>net_kgs_span").text(net_kgs);

                        var <?= $l_ID; ?>weight = parseFloat($("#<?= $l_ID ?>weight").val()) || 0;
                        var <?= $l_ID; ?>total = 0;

                        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
                            total = net_kgs / weight;
                            total = total.toFixed(3);
                        }

                        $("#<?= $l_ID ?>total").val(isNaN(total) ? '' : total);
                        $("#<?= $l_ID ?>total_span").text(isNaN(total) ? '' : total);

                        var <?= $l_ID; ?>rate1 = parseFloat($("#<?= $l_ID ?>rate1").val()) || 0;
                        var <?= $l_ID; ?>final_amount = 0;
                        var <?= $l_ID; ?>amount = 0;

                        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
                            amount = total * rate1;
                            amount = amount.toFixed(3);
                            final_amount = amount;
                        }

                        $("#<?= $l_ID ?>amount").val(isNaN(amount) ? '' : amount);
                        $("#<?= $l_ID ?>amount_span").text(isNaN(amount) ? '' : amount);
                        updateTaxAndTotal<?= $l_ID; ?>();
                        //if ($("#<?= $l_ID ?>is_qty").prop('checked') == true) {
                        var <?= $l_ID; ?>rate2 = parseFloat($("#<?= $l_ID ?>rate2").val()) || 0;
                        let <?= $l_ID; ?>operator = $('#<?= $l_ID ?>opr').find(":selected").val();

                        if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
                            final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
                            final_amount = final_amount.toFixed(3);
                        }
                        //}

                        $("#<?= $l_ID ?>final_amount").val(isFinite(final_amount) ? final_amount : '');
                        $("#<?= $l_ID ?>final_amount_span").text(isFinite(final_amount) ? final_amount : '');

                        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
                            disableButton('recordSubmit');
                        } else {
                            enableButton('recordSubmit');
                        }
                    }

                    function updateTaxAndTotal<?= $l_ID; ?>() {
                        let <?= $l_ID; ?>amount = parseFloat($('#<?= $l_ID ?>amount_span').text()) || 0;
                        let <?= $l_ID; ?>taxPercent = parseFloat($('#<?= $l_ID ?>tax_percent').val()) || 0;
                        let <?= $l_ID; ?>taxAmount = (amount * (taxPercent / 100)).toFixed(2);
                        let <?= $l_ID; ?>totalWithTax = (amount + parseFloat(taxAmount)).toFixed(2);
                        $('#<?= $l_ID ?>tax_amount').val(taxAmount != 0 ? taxAmount : '');
                        $('#<?= $l_ID ?>total_with_tax').val(totalWithTax);
                        $('#<?= $l_ID ?>total_with_tax_span').text(totalWithTax);
                    }
                </script>
            <?php endforeach; ?>

            <div class="row bg-white p-3 mt-4">
                <div class="col-md-12 text-end">
                    <input type="hidden" name="unique_code" value="<?= $unique_code; ?>">
                    <input type="hidden" name="data_for" value="<?= $data_for; ?>">
                    <button name="reSubmit" id="reSubmit" type="submit"
                        class="btn btn-warning btn-sm rounded-0">
                        <i class="fa fa-paper-plane"></i> Update </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-2">
        <div class="card p-3 h-100 position-relative">
            <div>
                <b><?php echo strtoupper($Tdata['p_s_name']) . ' #'; ?> </b><?php echo $Tdata['sr_no']; ?>
            </div>
            <div><b>User </b><?php echo $Tdata['username']; ?></div>
            <div><b>Date </b><?php echo my_date($Tdata['_date']); ?></div>
            <div><b>Type </b><?php echo badge(strtoupper($Tdata['type']), 'dark'); ?></div>
            <div><b>Country </b><?php echo $Tdata['country']; ?></div>
            <div><b>Branch </b><?php echo branchName($Tdata['branch_id']); ?></div>
            <div><b>Status </b>
                <?php if ($Tdata['locked'] == 0) {
                    echo $Tdata['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                } else {
                    echo '<i class="fa fa-lock text-success"></i> Transferred.';
                } ?>
            </div>
            <button class="btn btn-warning btn-sm mt-2" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
            <?= $dataType === 'Copied' ? '<span class="fw-bold text-success my-1">Transferred</span>' : '<span class="fw-bold text-danger my-1">Not Transferred</span>'; ?>
            <div class="info-text position-absolute bottom-0 start-50 translate-middle-x">
                <small style="font-size: 9px;font-weight:500;"><?= $dataType; ?> Info</small>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('keyup', "#dr_acc_no", function(e) {
        fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "reSubmit");
    });

    $(document).on('keyup', "#cr_acc_no", function(e) {
        fetchKhaata("#cr_acc", "#cr_acc_id", "#cr_acc_kd_id", "reSubmit");
    });
    $(document).on('keyup', "#im_acc_no", function(e) {
        fetchKhaata("#im_acc", "#im_acc_id", "#im_acc_kd_id", "reSubmit");
    });

    $(document).on('keyup', "#np_acc_no", function(e) {
        fetchKhaata("#np_acc", "#np_acc_id", "#np_acc_kd_id", "reSubmit");
    });

    $(document).on('keyup', "#xp_acc_no", function(e) {
        fetchKhaata("#xp_acc", "#xp_acc_id", "#xp_acc_kd_id", "reSubmit");
    });

    function updateContants(e, type) {
        if (type === 'rate') {
            Rate = $(e).val();
        } else if (type === 'empty_kgs') {
            emptyKgs = $(e).val();
        }
        autoCalc('#quantity_no', '#gross_weight', '#net_weight', Rate, emptyKgs)
    }

    function khaataCompanies(khaata_id, dropdown_id, callback) {
        if (khaata_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/companies_dropdown_by_khaata_id.php',
                data: {
                    khaata_id: khaata_id
                },
                success: function(html) {
                    // Set the default "Choose" option as selected and hidden
                    $('#' + dropdown_id).html('<option value="" selected hidden>Choose</option>' + html);
                    if (typeof callback === 'function') {
                        callback(); // Trigger the callback function if provided
                    }
                },
                error: function(xhr, status, error) {
                    $('#' + dropdown_id).html('<option value="0">FAILED</option>');
                }
            });
        } else {
            $('#' + dropdown_id).html('<option value="0">FAILED</option>');
        }
    }


    // Update fetchKhaata function
    function fetchKhaata(inputField, khaataId, kd_dropdown, recordSubmitId) {
        let khaata_no = $(inputField + '_no').val();
        let khaata_id_this = 0;

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
                    khaata_id_this = response.messages['khaata_id'];
                    $(khaataId).val(khaata_id_this);
                    $(recordSubmitId).prop('disabled', false);
                    $(inputField + '_no').addClass('is-valid');
                    $(inputField + '_no').removeClass('is-invalid');
                    $(inputField + '_name').val(response.messages['khaata_name']);

                    if (inputField === '#dr_acc') {
                        khaataCompanies(khaata_id_this, 'dr_acc_kd_id', function() {
                            $('#dr_acc_kd_id').val(response.messages['dr_acc_kd_id']);
                            khaataDetailsSingle($('#dr_acc_kd_id').val(), 'dr_acc_details');
                        });
                    }
                    if (inputField === '#cr_acc') {
                        khaataCompanies(khaata_id_this, 'cr_acc_kd_id', function() {
                            $('#cr_acc_kd_id').val(response.messages['cr_acc_kd_id']);
                            khaataDetailsSingle($('#cr_acc_kd_id').val(), 'cr_acc_details');
                        });
                    }
                    if (inputField === '#im_acc') {
                        khaataCompanies(khaata_id_this, 'im_acc_kd_id', function() {
                            $('#im_acc_kd_id').val(response.messages['im_acc_kd_id']);
                            khaataDetailsSingle($('#im_acc_kd_id').val(), 'im_acc_details');
                        });
                    }
                    if (inputField === '#np_acc') {
                        khaataCompanies(khaata_id_this, 'np_acc_kd_id', function() {
                            $('#np_acc_kd_id').val(response.messages['np_acc_kd_id']);
                            khaataDetailsSingle($('#np_acc_kd_id').val(), 'np_acc_details');
                        });
                    }
                    if (inputField === '#xp_acc') {
                        khaataCompanies(khaata_id_this, 'xp_acc_kd_id', function() {
                            $('#xp_acc_kd_id').val(response.messages['xp_acc_kd_id']);
                            khaataDetailsSingle($('#xp_acc_kd_id').val(), 'xp_acc_details');
                        });
                    }
                } else {
                    disableButton(recordSubmitId);
                    $(inputField).addClass('is-invalid');
                    $(inputField).removeClass('is-valid');
                    $(khaataId).val(0);
                    $(kd_dropdown).html('<option value="0">Invalid A/c.</option>');
                }
            },
            error: function(e) {
                $(kd_dropdown).html('<option value="0">Invalid A/c.</option>');
            }
        });
    }



    function khaataDetailsSingle(khaata_details_id, dropdown_id) {
        if (khaata_details_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/khaata_details_by_id.php',
                data: {
                    khaata_details_id: khaata_details_id
                },
                success: function(response) {
                    var data = JSON.parse(response)
                    var data_comp = JSON.parse(data[3])
                    var datu = data_comp['company_name'] + ' Country: ' + data_comp['country'] + ' City: ' + data_comp['city'] + ' State: ' + data_comp['state'] + ' Address: ' + data_comp['address'];
                    var indexVals = '';
                    if (data_comp['indexes1'] && data_comp['vals1']) {
                        for (var i = 0; i < data_comp['indexes1'].length; i++) {
                            indexVals += '\n' + data_comp['indexes1'][i] + ': ' + data_comp['vals1'][i];
                        }
                    }
                    $('#' + dropdown_id).val(datu + indexVals);
                },
                error: function(xhr, status, error) {}
            });
        } else {
            $('#' + dropdown_id).val('');
        }
    }
    $(document).ready(function() {
        $('#dr_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'dr_acc_details');
        });
        $('#cr_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'cr_acc_details');
        });
        $('#im_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'im_acc_details');
        });
        $('#np_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'np_acc_details');
        });
        $('#xp_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'xp_acc_details');
        });
        $('#goods_id, #size, #brand, #origin').on('change', function() {
            populateFields();
        });
    });

    function autoCalc(quantityNo, grossWeight, netWeight, Rate, emptyKgs) {
        let qty = parseFloat($(quantityNo).val()) || 0;
        let myGrossweight = qty * parseFloat(Rate);
        $(grossWeight).val(myGrossweight.toFixed(2));
        let myNetWeight = myGrossweight - (parseFloat(emptyKgs) * qty);
        $(netWeight).val(myNetWeight.toFixed(2));
        $('#rate').val(Rate);
        $('#empty_kgs').val(emptyKgs);
    }

    function populateFields() {
        const goodsId = $('#goods_id').val();
        const size = $('#size').val();
        const brand = $('#brand').val();
        const origin = $('#origin').val();
        $('#myquantity_name').val('');
        $('#quantity_no').val('');
        $('#gross_weight').val('');
        $('#net_weight').val('');
        $('#goodsTable .goodRow').each(function() {
            const row = $(this);
            const TgoodsId = row.find('.TgoodsId').text();
            const rowSize = row.find('.size').text();
            const rowBrand = row.find('.brand').text();
            const rowOrigin = row.find('.origin').text();
            console.log(TgoodsId, rowSize, rowBrand, rowOrigin);
            console.log(goodsId, size, brand, origin);
            if (goodsId === TgoodsId && size === rowSize && brand === rowBrand && origin === rowOrigin) {
                $('#myquantity_name').val(row.data('quantity-name'));
                $('#quantity_no').val(row.data('quantity')).trigger('keyup');
                $('#gross_weight').val(row.data('gross-kgs'));
                $('#net_weight').val(row.data('net-kgs'));
                emptyKgs = row.data('empty-kgs');
                Rate = row.data('rate');
                $('#rate').val(Rate);
                $('#empty_kgs').val(emptyKgs);
                return false;
            }
        });
    }
</script>
<!-- JavaScript -->
<script>
    let selectedEntry = null;
    let SLID = null;

    function currentStock(event, LID, goodsID, size, brand, origin, qtyName) {
        SLID = LID;
        const targetPrefix = '#l_' + LID + '_';
        const selectedWarehouse = $(targetPrefix + 'warehouse_transfer').val() ?? '';
        $(targetPrefix + 'warehouse_entry').html('');

        // Show the modal and display the loading spinner
        $('#warehouseModal').modal('show');
        $('#loadingSpinner').show();
        // $('#warehouseEntries').hide().html('');
        $('#connectButton').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: 'ajax/purchase_enteries_in_warehouse.php',
            data: {
                l_id: LID,
                goods_id: goodsID,
                size: size,
                brand: brand,
                origin: origin,
                quantity_name: qtyName,
                warehouse: selectedWarehouse
            },
            success: function(res) {
                const data = JSON.parse(res);

                // Check if data is not empty and contains entries
                if (data && Object.keys(data).length > 0) {
                    let entriesHtml = '';

                    // Iterate over the unique codes and their corresponding entries
                    Object.keys(data).forEach((uniqueCode) => {
                        const entries = data[uniqueCode];
                        entries.forEach((entry, index) => {
                            entriesHtml += `
                        <tr>
                            <td><input class="form-check-input" type="radio" name="warehouseEntry" id="entry_${uniqueCode}_${index}" value="${uniqueCode}~${entry.loadingID}~${entry.goods_id}~${entry.goods_name}~${entry.size}~${entry.brand}~${entry.origin}~${entry.quantity_no}~${entry.quantity_name}~${entry.gross_weight}~${entry.net_weight}~${entry.container_no}~${entry.container_name}"></td>
                            <td>${entry.goods_name}</td>
                            <td>${entry.size}</td>
                            <td>${entry.brand}</td>
                            <td>${entry.origin}</td>
                            <td>${entry.quantity_no} <sub>${entry.quantity_name}</sub></td>
                            <td>${entry.gross_weight}</td>
                            <td>${entry.net_weight}</td>
                            <td>${entry.container_no}</td>
                            <td>${entry.container_name}</td>
                        </tr>`;
                        });
                    });

                    // Populate the modal with entries
                    $('#entriesTableBody').html(entriesHtml);
                    $('#warehouseEntries').show();
                } else {
                    $('#warehouseEntries').html('<p class="text-center text-muted">No entries found.</p>').show();
                }

                // Hide the spinner
                $('#loadingSpinner').hide();

                // Enable the connect button if there's at least one radio input
                $('#connectButton').prop('disabled', !$('#warehouseEntries input[type="radio"]').length);
            },
            error: function(error) {
                console.error(error);
                $('#loadingSpinner').hide();
                $('#warehouseEntries').html('<p class="text-danger text-center">Failed to fetch data.</p>').show();
            }
        });
    }

    // Handle entry selection
    $(document).on('change', '#warehouseEntries input[type="radio"]', function() {
        selectedEntry = $(this).val();
        $('#connectButton').prop('disabled', false);
    });

    // Handle connect button click
    $('#connectButton').click(function() {
        const targetField = '#l_' + SLID + '_warehouse_entry';
        if (selectedEntry) {
            const selectedRadio = $('#warehouseEntries input[type="radio"]:checked');
            const selectedLabel = selectedRadio.closest('tr').find('td').eq(1).text().trim(); // Goods Name column

            if (selectedLabel) {
                const optionHtml = `<option value="${selectedEntry}" selected>${selectedLabel}</option>`;
                $(targetField).html(optionHtml);
                $('#warehouseModal').modal('hide');
            } else {
                alert('Failed to retrieve entry details. Please try again.');
            }
        }
    });


    $(document).ready(function() {
        let activeId = null;
        $('.toggle-icon').on('click', function() {
            const id = $(this).data('id');
            $('#recordEdited').val(id);
            const entryForm = $(`.entryform${id}`);
            const isActive = id === activeId;
            if (activeId) {
                $(`.entryform${activeId}`).addClass('d-none');
                $(`[data-id="${activeId}"]`).removeClass('text-danger').addClass('text-primary');
            }
            if (!isActive) {
                entryForm.removeClass('d-none');
                $(this).removeClass('text-primary').addClass('text-danger');
                activeId = id;
            } else {
                activeId = null;
            }
        });
    });
</script>
<?php
if ($Tcat === 'l') { ?>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row g-3 mt-1">
                <div class="col-md-<?= $Tcat === 'l' ? '6' : '4'; ?>">
                    <label for="dr_acc_no" class="form-label fw-bold">Purchaser Details</label>
                    <div class="input-group">
                        <input type="hidden" name="dr_acc_id" value="<?= isset($Tdata['dr_acc_id']) ? $Tdata['dr_acc_id'] : ''; ?>" id="dr_acc_id">
                        <input type="text" id="dr_acc_no" name="dr_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                            placeholder="ACC No" value="<?= isset($Tdata['dr_acc']) ? $Tdata['dr_acc'] : ''; ?>">
                        <input type="text" id="dr_acc_name" name="dr_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                            placeholder="Importer Name" value="<?= isset($Tdata['dr_acc_name']) ? $Tdata['dr_acc_name'] : ''; ?>">
                    </div>
                    <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="dr_acc_kd_id" id="dr_acc_kd_id">
                        <option hidden value="">Select Company</option>
                        <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Tdata['dr_acc_id']) ? $Tdata['dr_acc_id'] : '', 'type' => 'company'));
                        while ($row = mysqli_fetch_array($run_query)) {
                            $row_data = json_decode($row['json_data']);
                            $sel_kd2 = $row['id'] == $Tdata['dr_acc_kd_id'] ? 'selected' : '';
                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                        }  ?>
                    </select>
                    <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="dr_acc_details" id="dr_acc_details" rows="5"
                        placeholder="Company Details"><?= isset($Tdata['dr_acc_details']) ? $Tdata['dr_acc_details'] : ''; ?></textarea>
                </div>

                <div class="col-md-<?= $Tcat === 'l' ? '6' : '4'; ?>">
                    <label for="cr_acc_no" class="form-label fw-bold">Seller Details</label>
                    <div class="input-group">
                        <input type="hidden" name="cr_acc_id" value="<?= isset($Tdata['cr_acc_id']) ? $Tdata['cr_acc_id'] : ''; ?>" id="cr_acc_id">
                        <input type="text" id="cr_acc_no" name="cr_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                            placeholder="ACC No" value="<?= isset($Tdata['cr_acc']) ? $Tdata['cr_acc'] : ''; ?>">
                        <input type="text" id="cr_acc_name" name="cr_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                            placeholder="Notify Party Name" value="<?= isset($Tdata['cr_acc_name']) ? $Tdata['cr_acc_name'] : ''; ?>">
                    </div>
                    <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="cr_acc_kd_id" id="cr_acc_kd_id">
                        <option hidden value="">Select Company</option>
                        <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Tdata['cr_acc_id']) ? $Tdata['cr_acc_id'] : '', 'type' => 'company'));
                        while ($row = mysqli_fetch_array($run_query)) {
                            $row_data = json_decode($row['json_data']);
                            $sel_kd2 = $row['id'] == $Tdata['cr_acc_kd_id'] ? 'selected' : '';
                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                        }  ?>
                    </select>
                    <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="cr_acc_details" id="cr_acc_details" rows="5"
                        placeholder="Company Details"><?= isset($Tdata['cr_acc_details']) ? $Tdata['cr_acc_details'] : ''; ?></textarea>
                </div>
                <?php /* if ($Tcat !== 'l') { ?>
                                    <div class="col-md-4">
                                        <label for="np_acc" class="form-label fw-bold">Transaction Notify Party Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="np_acc_id" value="<?= isset($Tdata['np_acc_id']) ? $Tdata['np_acc_id'] : ''; ?>" id="np_acc_id">
                                            <input type="text" id="np_acc" name="np_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Tdata['np_acc']) ? $Tdata['np_acc'] : ''; ?>">
                                            <input type="text" id="np_acc_name" name="np_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                                placeholder="Notify Party Name" value="<?= isset($Tdata['np_acc_name']) ? $Tdata['np_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="np_acc_kd_id" id="np_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Tdata['np_acc_id']) ? $Tdata['np_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Tdata['np_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            }  ?>
                                        </select>
                                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="np_acc_details" id="np_acc_details" rows="5"
                                            placeholder="Company Details"><?= isset($Tdata['np_acc_details']) ? $Tdata['np_acc_details'] : ''; ?></textarea>
                                    </div>
                                <?php } */ ?>
            </div>
        </div>
    </div>
<?php } ?>
<div>
    <?php
    if ($Tcat !== 'l') { ?>
        <div class="card mb-2">
            <div class="card-body">
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label for="im_acc_no" class="form-label fw-bold">Importer Details</label>
                        <div class="input-group">
                            <input type="hidden" name="im_acc_id" value="<?= isset($Ldata['importer']['im_acc_id']) ? $Ldata['importer']['im_acc_id'] : ''; ?>" id="im_acc_id">
                            <input type="text" id="im_acc_no" name="im_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                placeholder="ACC No" value="<?= isset($Ldata['importer']['im_acc_no']) ? $Ldata['importer']['im_acc_no'] : ''; ?>">
                            <input type="text" id="im_acc_name" name="im_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                placeholder="Importer Name" value="<?= isset($Ldata['importer']['im_acc_name']) ? $Ldata['importer']['im_acc_name'] : ''; ?>">
                        </div>
                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="im_acc_kd_id" id="im_acc_kd_id">
                            <option hidden value="">Select Company</option>
                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['importer']['im_acc_id']) ? $Ldata['importer']['im_acc_id'] : '', 'type' => 'company'));
                            while ($row = mysqli_fetch_array($run_query)) {
                                $row_data = json_decode($row['json_data']);
                                $sel_kd2 = $row['id'] == $Ldata['importer']['im_acc_kd_id'] ? 'selected' : '';
                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                            }  ?>
                        </select>
                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="im_acc_details" id="im_acc_details" rows="5"
                            placeholder="Company Details"><?= isset($Ldata['importer']['im_acc_details']) ? $Ldata['importer']['im_acc_details'] : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="xp_acc_no" class="form-label fw-bold">Exporter Details</label>
                        <div class="input-group">
                            <input type="hidden" name="xp_acc_id" value="<?= isset($Ldata['exporter']['xp_acc_id']) ? $Ldata['exporter']['xp_acc_id'] : ''; ?>" id="xp_acc_id">
                            <input type="text" id="xp_acc_no" name="xp_acc_no" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                placeholder="ACC No" value="<?= isset($Ldata['exporter']['xp_acc_no']) ? $Ldata['exporter']['xp_acc_no'] : ''; ?>">
                            <input type="text" id="xp_acc_name" name="xp_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                placeholder="Importer Name" value="<?= isset($Ldata['exporter']['xp_acc_name']) ? $Ldata['exporter']['xp_acc_name'] : ''; ?>">
                        </div>
                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="xp_acc_kd_id" id="xp_acc_kd_id">
                            <option hidden value="">Select Company</option>
                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['exporter']['xp_acc_id']) ? $Ldata['exporter']['xp_acc_id'] : '', 'type' => 'company'));
                            while ($row = mysqli_fetch_array($run_query)) {
                                $row_data = json_decode($row['json_data']);
                                $sel_kd2 = $row['id'] == $Ldata['exporter']['xp_acc_kd_id'] ? 'selected' : '';
                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                            }  ?>
                        </select>
                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="xp_acc_details" id="xp_acc_details" rows="5"
                            placeholder="Company Details"><?= isset($Ldata['exporter']['xp_acc_details']) ? $Ldata['exporter']['xp_acc_details'] : ''; ?></textarea>
                    </div>

                    <div class="col-md-4">
                        <label for="np_acc_no" class="form-label fw-bold">Notify Party Details</label>
                        <div class="input-group">
                            <input type="hidden" name="np_acc_id" value="<?= isset($Ldata['notify']['np_acc_id']) ? $Ldata['notify']['np_acc_id'] : ''; ?>" id="np_acc_id">
                            <input type="text" id="np_acc_no" name="np_acc_no" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                placeholder="ACC No" value="<?= isset($Ldata['notify']['np_acc_no']) ? $Ldata['notify']['np_acc_no'] : ''; ?>">
                            <input type="text" id="np_acc_name" name="np_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                placeholder="Notify Party Name" value="<?= isset($Ldata['notify']['np_acc_name']) ? $Ldata['notify']['np_acc_name'] : ''; ?>">
                        </div>
                        <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="np_acc_kd_id" id="np_acc_kd_id">
                            <option hidden value="">Select Company</option>
                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['notify']['np_acc_id']) ? $Ldata['notify']['np_acc_id'] : '', 'type' => 'company'));
                            while ($row = mysqli_fetch_array($run_query)) {
                                $row_data = json_decode($row['json_data']);
                                $sel_kd2 = $row['id'] == $Ldata['notify']['np_acc_kd_id'] ? 'selected' : '';
                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                            }  ?>
                        </select>
                        <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="np_acc_details" id="np_acc_details" rows="5"
                            placeholder="Company Details"><?= isset($Ldata['notify']['np_acc_details']) ? $Ldata['notify']['np_acc_details'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- Summary Section (Initially Visible) -->
    <?php
    $warehouse = $Ldata['transfer']['warehouse_transfer'] ?? '';
    ?>
    <div id="transferSec">
        <div class="summarySection">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row gy-3">
                        <?php if ($Tcat === 'l') {
                            if ($Ldata['transfer']['route'] === 'local') { ?>
                                <div class="col-md-2">
                                    <span>
                                        <b>Truck Number</b><br>
                                        <?php echo $Ldata['transfer']['truck_no']; ?>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span>
                                        <b>Truck Name</b><br>
                                        <?php echo $Ldata['transfer']['truck_name']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span>
                                        <b>Loading Warehouse</b><br>
                                        <?php echo $Ldata['transfer']['loading_warehouse']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span>
                                        <b>Receiving Warehouse</b><br>
                                        <?php echo $Ldata['transfer']['receiving_warehouse']; ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <div class="col-md-3">
                                <span>
                                    <b>Loading Company</b><br>
                                    <?php echo $Ldata['transfer']['loading_company_name']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span>
                                    <b>Receiving Company Name</b><br>
                                    <?php echo $Ldata['transfer']['receiving_company_name']; ?>
                                </span>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-2">
                                <span>
                                    <b>Loading Country</b><br>
                                    <?php echo $Ldata['transfer']['loading_country']; ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span>
                                    <b>L <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</b><br>
                                    <?php echo $Ldata['transfer']['loading_port_name']; ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span>
                                    <b>Receiving Country</b><br>
                                    <?php echo $Ldata['transfer']['receiving_country']; ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span>
                                    <b>R <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</b><br>
                                    <?php echo $Ldata['transfer']['receiving_port_name']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span>
                                    <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name</b><br>
                                    <?php echo $Ldata['transfer']['shipping_name']; ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <span>
                                    <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address</b><br>
                                    <?php echo $Ldata['transfer']['shipping_address']; ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span>
                                    <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone</b><br>
                                    <?php echo $Ldata['transfer']['shipping_phone']; ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span>
                                    <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp</b><br>
                                    <?php echo $Ldata['transfer']['shipping_whatsapp']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span>
                                    <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email</b><br>
                                    <?php echo $Ldata['transfer']['shipping_email']; ?>
                                </span>
                            </div>
                        <?php } ?>
                        <div class="col-md-2">
                            <span>
                                <b>Loading Date</b><br>
                                <?php echo $Ldata['transfer']['loading_date']; ?>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <span>
                                <b>Receiving Date</b><br>
                                <?php echo $Ldata['transfer']['receiving_date']; ?>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <span>
                                <b>Cargo Transfer</b><br>
                                <?php echo $warehouse; ?>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="editButton btn btn-sm btn-primary" style="position: absolute; top: 5px; right: 5px;">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Editable Form Section (Initially Hidden) -->
        <div class="formSection" style="display: none;">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row gy-3">
                        <!-- Form Fields (Same as in the Summary Section) -->
                        <?php if ($Tcat === 'l') {
                            if ($Ldata['transfer']['route'] === 'local') { ?>
                                <div class="col-md-2">
                                    <label for="truck_no" class="form-label">Truck Number</label>
                                    <input id="truck_no" name="truck_no" value="<?php echo $Ldata['transfer']['truck_no']; ?>" type="text" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="truck_name" class="form-label">Truck Name</label>
                                    <input id="truck_name" name="truck_name" value="<?php echo $Ldata['transfer']['truck_name']; ?>" type="text" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label for="loading_warehouse" class="form-label">Loading Warehouse</label>
                                    <input id="loading_warehouse" name="loading_warehouse" value="<?php echo $Ldata['transfer']['loading_warehouse']; ?>" type="text" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label for="receiving_warehouse" class="form-label">Receiving Warehouse</label>
                                    <input id="receiving_warehouse" name="receiving_warehouse" value="<?php echo $Ldata['transfer']['receiving_warehouse']; ?>" type="text" class="form-control form-control-sm">
                                </div>
                            <?php } ?>
                            <div class="col-md-3">
                                <label for="loading_company_name" class="form-label">Loading Company</label>
                                <input id="loading_company_name" name="loading_company_name" value="<?php echo $Ldata['transfer']['loading_company_name']; ?>" type="text" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label for="receiving_company_name" class="form-label">Receiving Company Name</label>
                                <input id="receiving_company_name" name="receiving_company_name" value="<?php echo $Ldata['transfer']['receiving_company_name']; ?>" type="text" class="form-control form-control-sm">
                            </div>
                        <?php } else { ?>
                            <!-- Form Fields for Sea/Road -->
                            <div class="col-md-2">
                                <label for="loading_country" class="form-label">Loading Country</label>
                                <input type="text" name="loading_country" id="loading_country" value="<?= $Ldata['transfer']['loading_country']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="loading_port_name" class="form-label">L <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                <input type="text" name="loading_port_name" id="loading_port_name" value="<?= $Ldata['transfer']['loading_port_name']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="receiving_country" class="form-label">Receiving Country</label>
                                <input type="text" name="receiving_country" id="receiving_country" value="<?= $Ldata['transfer']['receiving_country']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="receiving_port_name" class="form-label">R <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                <input type="text" name="receiving_port_name" id="receiving_port_name" value="<?= $Ldata['transfer']['receiving_port_name']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label for="shipping_name" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name</label>
                                <input type="text" name="shipping_name" id="shipping_name" value="<?= $Ldata['transfer']['shipping_name']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label for="shipping_address" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address</label>
                                <input type="text" name="shipping_address" id="shipping_address" value="<?= $Ldata['transfer']['shipping_address']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="shipping_phone" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone</label>
                                <input type="text" name="shipping_phone" id="shipping_phone" value="<?= $Ldata['transfer']['shipping_phone']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="shipping_whatsapp" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp</label>
                                <input type="text" name="shipping_whatsapp" id="shipping_whatsapp" value="<?= $Ldata['transfer']['shipping_whatsapp']; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label for="shipping_email" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email</label>
                                <input type="text" name="shipping_email" id="shipping_email" value="<?= $Ldata['transfer']['shipping_email']; ?>" class="form-control form-control-sm">
                            </div>
                        <?php } ?>
                        <div class="col-md-2">
                            <label for="loading_date" class="form-label">Loading Date</label>
                            <input type="text" name="loading_date" id="loading_date" value="<?= $Ldata['transfer']['loading_date']; ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="receiving_date" class="form-label">Receiving Date</label>
                            <input type="text" name="receiving_date" id="receiving_date" value="<?= $Ldata['transfer']['receiving_date']; ?>" class="form-control form-control-sm">
                        </div>
                        <!-- Edit Section (when in edit mode) -->
                        <div class="col-md-3">
                            <label for="warehouse_transfer" class="form-label">Cargo Transfer</label>
                            <select id="warehouse_transfer" name="warehouse_transfer" class="form-select form-select-sm">
                                <option disabled <?= empty($warehouse) ? 'selected' : '' ?>>Select One</option>
                                <option value="Local Import" <?= $warehouse === 'Local Import' ? 'selected' : '' ?>>Local Import</option>
                                <option value="Free Zone Import" <?= $warehouse === 'Free Zone Import' ? 'selected' : '' ?>>Free Zone Import</option>
                                <option value="Import Re-Export" <?= $warehouse === 'Import Re-Export' ? 'selected' : '' ?>>Import Re-Export</option>
                                <option value="Transit" <?= $warehouse === 'Transit' ? 'selected' : '' ?>>Transit</option>
                                <option value="Local Export" <?= $warehouse === 'Local Export' ? 'selected' : '' ?>>Local Export</option>
                                <option value="Local Market" <?= $warehouse === 'Local Market' ? 'selected' : '' ?>>Local Market</option>
                            </select>
                        </div>

                        <input type="hidden" name="transfer_to_warehouse_ids" id="transfer_to_warehouse_ids" value="<?= $LID; ?>">

                        <!-- Cross Button -->
                        <div class="col-md-3">
                            <button type="button" class=" btn btn-sm btn-danger" style="position: absolute; top: 5px; right: 5px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="goodsSec">
    <div class="summarySection">
        <div class="card mb-2">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3">
                        <span>
                            <b>Allot:</b>
                            <?php echo isset($Good['goods_json']['allotment_name']) ? $Good['goods_json']['allotment_name'] : ''; ?>
                        </span><br>
                        <span>
                            <b>GOODS:</b>
                            <?php
                            $goods = fetch('goods');
                            while ($good = mysqli_fetch_assoc($goods)) {
                                if ($good['id'] == $Good['goods_json']['goods_id']) {
                                    echo $good['name'];
                                }
                            }
                            ?>
                        </span><br>
                        <span>
                            <b>SIZE:</b>
                            <?php echo isset($Good['goods_json']['size']) ? $Good['goods_json']['size'] : ''; ?>
                        </span><br>
                        <span>
                            <b>ORIGIN:</b>
                            <?php echo isset($Good['goods_json']['origin']) ? $Good['goods_json']['origin'] : ''; ?>
                        </span><br>
                        <span>
                            <b>BRAND:</b>
                            <?php echo isset($Good['goods_json']['brand']) ? $Good['goods_json']['brand'] : ''; ?>
                        </span><br>
                        <span>
                            <b>Qty Name:</b>
                            <?php echo isset($Good['goods_json']['qty_name']) ? $Good['goods_json']['qty_name'] : ''; ?>
                        </span><br>


                    </div>
                    <div class="col-md-3">
                        <span>
                            <b>Qty#:</b>
                            <?php echo $Good['goods_json']['qty_no']; ?>
                        </span><br>
                        <span>
                            <b>Qty KGs:</b>
                            <?php echo isset($Good['goods_json']['qty_kgs']) ? $Good['goods_json']['qty_kgs'] : ''; ?>
                        </span><br>
                        <span>
                            <b>Empty KGs:</b>
                            <?php echo isset($Good['goods_json']['empty_kgs']) ? $Good['goods_json']['empty_kgs'] : ''; ?>
                        </span><br>
                        <span>
                            <b>DIVIDE:</b>
                            <?php echo isset($Good['goods_json']['divide']) ? $Good['goods_json']['divide'] : ''; ?>
                        </span><br>
                        <span>
                            <b>WEIGHT:</b>
                            <?php echo isset($Good['goods_json']['weight']) ? $Good['goods_json']['weight'] : ''; ?>
                        </span><br>
                        <span>
                            <b>PRICE:</b>
                            <?php echo isset($Good['goods_json']['price']) ? $Good['goods_json']['price'] : ''; ?>
                        </span><br>
                        <span>
                            <b>CURRENCY:</b>
                            <?php echo isset($Good['goods_json']['currency1']) ? $Good['goods_json']['currency1'] : ''; ?>
                        </span><br>
                    </div>

                    <div class="col-md-3">
                        <span>
                            <b>RATE:</b>
                            <?php echo isset($Good['goods_json']['rate1']) ? $Good['goods_json']['rate1'] : ''; ?>
                        </span><br>

                        <?php if (decode_unique_code($unique_code, 'Tcat') !== 'l'): ?>
                            <span>
                                <b>CURRENCY 2:</b>
                                <?php echo isset($Good['goods_json']['currency2']) ? $Good['goods_json']['currency2'] : ''; ?>
                            </span><br>
                            <span>
                                <b>RATE 2:</b>
                                <?php echo isset($Good['goods_json']['rate2']) ? $Good['goods_json']['rate2'] : ''; ?>
                            </span><br>
                        <?php endif; ?>
                        <span>
                            <b>TAX %:</b>
                            <?php echo isset($Good['goods_json']['tax_percent']) ? $Good['goods_json']['tax_percent'] : ''; ?>
                        </span><br>
                        <span>
                            <b>TAX AMT:</b>
                            <?php echo isset($Good['goods_json']['tax_amount']) ? $Good['goods_json']['tax_amount'] : ''; ?>
                        </span><br>
                    </div>

                    <div class="col-md-2">
                        <table class="table table-sm mt-3">
                            <tbody class="text-nowrap">
                                <tr>
                                    <th class="fw-normal">TOTAL KGs</th>
                                    <th><span class="total_kgs_span"><?php echo isset($Good['goods_json']['total_kgs']) ? $Good['goods_json']['total_kgs'] : ''; ?></span></th>
                                </tr>
                                <tr>
                                    <th class="fw-normal">TOTAL QTY KGs</th>
                                    <th><span class="total_qty_kgs_span"><?php echo isset($Good['goods_json']['total_qty_kgs']) ? $Good['goods_json']['total_qty_kgs'] : ''; ?></span></th>
                                </tr>
                                <tr>
                                    <th class="fw-normal">NET KGs</th>
                                    <th><span class="net_kgs_span"><?php echo isset($Good['goods_json']['net_kgs']) ? $Good['goods_json']['net_kgs'] : ''; ?></span></th>
                                </tr>
                                <tr>
                                    <th class="fw-normal">TOTAL</th>
                                    <th><span class="total_span"><?php echo isset($Good['goods_json']['total']) ? $Good['goods_json']['total'] : ''; ?></span></th>
                                </tr>
                                <tr>
                                    <th class="fw-normal">AMOUNT</th>
                                    <th><span class="amount_span"><?php echo isset($Good['goods_json']['amount']) ? $Good['goods_json']['amount'] : ''; ?></span></th>
                                </tr>
                                <?php if (decode_unique_code($unique_code, 'Tcat') !== 'l'): ?>
                                    <tr>
                                        <th class="fw-normal text-danger">FINAL</th>
                                        <th><span class="final_amount_span"><?php echo isset($Good['goods_json']['final_amount']) ? $Good['goods_json']['final_amount'] : ''; ?></span></th>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <th class="fw-normal text-danger">Amt+Tax</th>
                                        <th><span class="total_with_tax_span"><?php echo isset($Good['goods_json']['total_with_tax']) ? $Good['goods_json']['total_with_tax'] : '0'; ?></span></th>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h5 class="text-primary mt-3">Agent Form ( <small style="font-size: 14px;"><b>Agent: </b> <?= $Agent['ag_name'] ?? 'Not Set'; ?> | <b>Acc: </b> <?= $Agent['ag_acc_no'] ?? 'Not Set'; ?> </small> )</h5>
                <div class="row g-3">
                    <div class="col-md-2">
                        <span>
                            <b>BOE Date:</b><br>
                            <?php echo isset($Agent['boe_date']) ? $Agent['boe_date'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <span>
                            <b>Pick Up Date:</b><br>
                            <?php echo isset($Agent['pick_up_date']) ? $Agent['pick_up_date'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <span>
                            <b>Waiting (days):</b><br>
                            <?php echo isset($Agent['waiting_days']) ? $Agent['waiting_days'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <span>
                            <b>Return Date:</b><br>
                            <?php echo isset($Agent['return_date']) ? $Agent['return_date'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <span>
                            <b>Transporter Name:</b><br>
                            <?php echo isset($Agent['transporter_name']) ? $Agent['transporter_name'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <span>
                            <b>Truck Number:</b><br>
                            <?php echo isset($Agent['truck_number']) ? $Agent['truck_number'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-4">
                        <span>
                            <b>Details:</b><br>
                            <?php echo isset($Agent['details']) ? $Agent['details'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-3">
                        <span>
                            <b>Driver Name:</b><br>
                            <?php echo isset($Agent['driver_name']) ? $Agent['driver_name'] : ''; ?>
                        </span>
                    </div>

                    <div class="col-md-3">
                        <span>
                            <b>Driver Number:</b><br>
                            <?php echo isset($Agent['driver_number']) ? $Agent['driver_number'] : ''; ?>
                        </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="editButton btn btn-sm btn-primary" style="position: absolute; top: 5px; right: 5px;">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="formSection" style="display: none;">
        <div class="card mb-2">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3">
                        <div class="input-group my-2">
                            <label class="col-form-label text-nowrap">Allotment Name</label>
                            <input value="<?= $Good['goods_json']['allotment_name'] ?? ''; ?>" name="<?= $LID ?>_allotment_name" class="allotment_name form-control form-control-sm">
                        </div>

                        <div class="input-group my-2">
                            <label>GOODS</label>
                            <select name="<?= $LID ?>_goods_id" class="goods_id form-select form-select-sm">
                                <option hidden value="">Select</option>
                                <?php
                                $goods = fetch('goods');
                                while ($good = mysqli_fetch_assoc($goods)) {
                                    $g_selected = $good['id'] == $Good['goods_json']['goods_id'] ? 'selected' : '';
                                    echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input-group my-2">
                            <label for="size">SIZE</label>
                            <select class="size form-select form-select-sm" name="<?= $LID ?>_size">
                                <option hidden value="">Select</option>
                                <?php
                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $Good['goods_json']['goods_id']);
                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                    $size_selected = $size_s['size'] == $Good['goods_json']['size'] ? 'selected' : '';
                                    echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input-group my-2">
                            <label for="origin">ORIGIN</label>
                            <select class="form-select form-select-sm origin" name="<?= $LID ?>_origin">
                                <option hidden value="">Select</option>
                                <?php
                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $Good['goods_json']['goods_id']);
                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                    $size_selected = $size_s['origin'] == $Good['goods_json']['origin'] ? 'selected' : '';
                                    echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input-group my-2">
                            <label for="brand">BRAND</label>
                            <select class="form-select form-select-sm brand" name="<?= $LID ?>_brand">
                                <option hidden value="">Select</option>
                                <?php
                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $Good['goods_json']['goods_id']);
                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                    $size_selected = $size_s['brand'] == $Good['goods_json']['brand'] ? 'selected' : '';
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
                                        <input value="<?php echo $Good['goods_json']['qty_no']; ?>"
                                            name="<?= $LID ?>_qty_name" class="qty_name form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-0">
                                    <label class="col-sm-4 col-form-label text-nowrap"
                                        for="qty_no">Qty#</label>
                                    <div class="col-sm">
                                        <input value="<?= $Good['goods_json']['qty_no']; ?>"
                                            name="<?= $LID ?>_qty_no"
                                            class="form-control qty_no form-control-sm currency">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-0">
                                    <label class="col-sm-4 col-form-label text-nowrap" for="qty_kgs">Qty
                                        KGs</label>
                                    <div class="col-sm">
                                        <input value="<?php echo $Good['goods_json']['qty_kgs']; ?>"
                                            name="<?= $LID ?>_qty_kgs"
                                            class="form-control qty_kgs form-control-sm currency">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-0">
                                    <label class="col-sm-4 col-form-label text-nowrap" for="empty_kgs">Empty
                                        KGs</label>
                                    <div class="col-sm">
                                        <input value="<?php echo $Good['goods_json']['empty_kgs']; ?>"
                                            name="<?= $LID ?>_empty_kgs" class="empty_kgs form-control form-control-sm currency">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-0">
                                    <label class="col-sm-4 col-form-label text-nowrap"
                                        for="divide">DIVIDE</label>
                                    <div class="col-sm">
                                        <select name="<?= $LID ?>_divide" class="divide form-select">
                                            <?php $divides = array('TON' => 'TON', 'KGs' => 'KG', 'CARTON' => 'CARTON');
                                            foreach ($divides as $item => $val) {
                                                $d_sel = ($Good['goods_json']['divide'] ?? '') == $val ? 'selected' : '';
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
                                        <input value="<?php echo $Good['goods_json']['weight']; ?>"
                                            name="<?= $LID ?>_weight"
                                            class="form-control weight form-control-sm currency">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-0">
                                    <label class="col-sm-4 col-form-label text-nowrap"
                                        for="price">PRICE</label>
                                    <div class="col-sm">
                                        <select name="<?= $LID ?>_price" class="price form-select form-select-sm">
                                            <?php $prices = array('TON PRICE' => 'TON', 'KGs PRICE' => 'KG', 'CARTON PRICE' => 'CARTON');
                                            foreach ($prices as $item => $val) {
                                                $pr_sel = ($Good['goods_json']['price'] ?? '') == $val ? 'selected' : '';
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
                                        <select name="<?= $LID ?>_currency1" class="currency1 form-select form-select-sm">
                                            <option selected hidden disabled value="">Select</option>
                                            <?php $currencies = fetch('currencies');
                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                $crr_sel = $crr['name'] == ($Good['goods_json']['currency1'] ?? '') ? 'selected' : '';
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
                                        <input value="<?php echo $Good['goods_json']['rate1']; ?>"
                                            name="<?= $LID ?>_rate1"
                                            class="rate1 form-control form-control-sm currency">
                                    </div>
                                </div>
                            </div>
                            <?php if (decode_unique_code($unique_code, 'Tcat') !== 'l'): ?>
                                <div class="col-md-4">
                                    <div class="row g-0">
                                        <label class="col-sm-4 col-form-label text-nowrap"
                                            for="currency2">Currency</label>
                                        <div class="col-sm">
                                            <select name="<?= $LID ?>_currency2" class="form-select form-select-sm currency2">
                                                <option selected hidden disabled value="">Select</option>
                                                <?php $currencies = fetch('currencies');
                                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                                    $crr_sel2 = $crr['name'] == ($Good['goods_json']['currency2'] ?? '') ? 'selected' : '';
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
                                            <input value="<?php echo $Good['goods_json']['rate2']; ?>"
                                                name="<?= $LID ?>_rate2"
                                                class="form-control rate2 form-control-sm currency">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row g-0">
                                        <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                        <div class="col-sm">
                                            <select name="<?= $LID ?>_opr" class="opr form-select form-select-sm">
                                                <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                foreach ($ops as $opName => $op) {
                                                    $op_sel = ($Good['goods_json']['opr'] ?? '') == $op ? 'selected' : '';
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
                                            <input type="text" value="<?php echo $Good['goods_json']['tax_percent']; ?>"
                                                name="<?= $LID ?>_tax_percent"
                                                class="form-control tax_percent form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row g-0">
                                        <label class="col-sm-4 col-form-label text-nowrap"
                                            for="tax_amount">Tax.Amt</label>
                                        <div class="col-sm">
                                            <input type="text" value="<?php echo $Good['goods_json']['tax_amount']; ?>"
                                                name="<?= $LID ?>_tax_amount"
                                                class="form-control tax_amount form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row g-0">
                                        <!-- <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="total_with_tax">Amt+Tax</label> -->
                                        <div class="col-sm">
                                            <input type="hidden" value="<?php echo $Good['goods_json']['total_with_tax']; ?>"
                                                name="<?= $LID ?>_total_with_tax" class="total_with_tax">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-2 mt-5">
                        <table class="table table-sm">
                            <tbody class="text-nowrap">
                                <?php
                                echo '<tr><th class="fw-normal">TOTAL KGs </th><th><span class="total_kgs_span">' . $Good['goods_json']['total_kgs'] . '</span></th></tr>';
                                echo '<tr><th class="fw-normal">TOTAL QTY KGs </th><th><span class="total_qty_kgs_span"></span></th></tr>';
                                echo '<tr><th class="fw-normal">NET KGs </th><th><span class="net_kgs_span">' . $Good['goods_json']['net_kgs'] . '</span></th></tr>';
                                echo '<tr><th class="fw-normal">TOTAL </th><th><span class="total_span"></span></th></tr>';
                                echo '<tr><th class="fw-normal">AMOUNT  </th><th><span class="amount_span"></span></th></tr>';
                                if (decode_unique_code($unique_code, 'Tcat') !== 'l') {
                                    echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span class="final_amount_span"></span></th></tr>';
                                } else {
                                    echo '<tr><th class="fw-normal text-danger">Amt+Tax  </th><th><span class="total_with_tax_span">0</span></th></tr>';
                                };
                                ?>
                            </tbody>
                        </table>
                        <input value="<?= $Good['goods_json']['total_kgs'] ?>" class="total_kgs"
                            name="<?= $LID ?>_total_kgs" type="hidden">
                        <input value="<?php echo $Good['goods_json']['total_qty_kgs']; ?>"
                            name="<?= $LID ?>_total_qty_kgs" class="total_qty_kgs"
                            type="hidden">
                        <input value="<?= $Good['goods_json']['net_kgs'] ?>" name="<?= $LID ?>_net_kgs"
                            type="hidden" class="net_kgs">
                        <input value="<?php echo $Good['goods_json']['total']; ?>" name="<?= $LID ?>_total"
                            type="hidden" class="total">
                        <input value="<?php echo $Good['goods_json']['amount']; ?>" name="<?= $LID ?>_amount"
                            type="hidden" class="amount">
                        <input value="<?php echo $Good['goods_json']['final_amount']; ?>"
                            name="<?= $LID ?>_final_amount" type="hidden" class="final_amount">
                    </div>
                </div>
                <h5 class="text-primary mt-3">Agent Form ( <small style="font-size: 14px;"><b>Agent: </b> <?= $Agent['ag_name'] ?? 'Not Set'; ?> | <b>Acc: </b> <?= $Agent['ag_acc_no'] ?? 'Not Set'; ?> </small> )</h5>
                <div class="row g-3">
                    <!-- BOE DATE -->
                    <div class="col-md-2">
                        <label for="boe_date" class="form-label">BOE Date</label>
                        <input type="date" name="<?= $LID ?>_boe_date" id="boe_date"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['boe_date']) ? $Agent['boe_date'] : ''; ?>">
                    </div>

                    <!-- PICK UP DATE -->
                    <div class="col-md-2">
                        <label for="pick_up_date" class="form-label">Pick Up Date</label>
                        <input type="date" name="<?= $LID ?>_pick_up_date" id="pick_up_date"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['pick_up_date']) ? $Agent['pick_up_date'] : ''; ?>">
                    </div>

                    <!-- WAITING IF ANY -->
                    <div class="col-md-2">
                        <label for="waiting_days" class="form-label">Waiting (days)</label>
                        <input type="text" name="<?= $LID ?>_waiting_days" id="waiting_days"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['waiting_days']) ? $Agent['waiting_days'] : ''; ?>">
                    </div>

                    <!-- RETURN DATE -->
                    <div class="col-md-2">
                        <label for="return_date" class="form-label">Return Date</label>
                        <input type="date" name="<?= $LID ?>_return_date" id="return_date"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['return_date']) ? $Agent['return_date'] : ''; ?>">
                    </div>

                    <!-- TRANSPORTER NAME -->
                    <div class="col-md-3">
                        <label for="transporter_name" class="form-label">Transporter Name</label>
                        <input type="text" name="<?= $LID ?>_transporter_name" id="transporter_name"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['transporter_name']) ? $Agent['transporter_name'] : ''; ?>">
                    </div>

                    <!-- TRUCK NUMBER -->
                    <div class="col-md-2">
                        <label for="truck_number" class="form-label">Truck Number</label>
                        <input type="text" name="<?= $LID ?>_truck_number" id="truck_number"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['truck_number']) ? $Agent['truck_number'] : ''; ?>">
                    </div>

                    <!-- DETAILS -->
                    <div class="col-md-4">
                        <label for="details" class="form-label">Details</label>
                        <input type="text" name="<?= $LID ?>_details" id="details"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['details']) ? $Agent['details'] : ''; ?>">
                    </div>

                    <!-- DRIVER NAME -->
                    <div class="col-md-3">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" name="<?= $LID ?>_driver_name" id="driver_name"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['driver_name']) ? $Agent['driver_name'] : ''; ?>">
                    </div>

                    <!-- DRIVER NUMBER -->
                    <div class="col-md-3">
                        <label for="driver_number" class="form-label">Driver Number</label>
                        <input type="number" name="<?= $LID ?>_driver_number" id="driver_number"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['driver_number']) ? $Agent['driver_number'] : ''; ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="closeButton btn btn-sm btn-danger" style="position: absolute; top: 5px; right: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
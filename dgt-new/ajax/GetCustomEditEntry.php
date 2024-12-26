<?php
require_once '../connection.php';
$unique_code = $_POST['unique_code'];
[$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');
$data = mysqli_fetch_assoc(fetch($_POST['fetch_from'], ['unique_code' => $unique_code]));
$Tdata = json_decode($data['tdata'], true);
$Ldata = json_decode($data['ldata'], true);
$Good = $Ldata['good'];
$Agent = $Ldata['agent'] ?? [];
$print_url = 'print/print-custom-warehouse.php?warehouse_type=' . $_POST['warehouse_type'] . '&p1=' . $_POST['print_party_1'] . '&p2=' . $_POST['print_party_2'];
echo '<script>let printUrl = "' . $print_url . '";</script>';
?>
<div class="modal-header d-flex justify-content-between bg-white align-items-center mb-2">
    <h5 class="modal-title fw-bold" id="staticBackdropLabel">EDIT => <?= ucfirst($Ttype) . '# ' . $TID . ' (' . $_POST['sr_no'] . ') (' . ucwords(str_replace('-', ' ', $_POST['warehouse_type'])) . ')'; ?></h5>
    <div class="d-flex align-items-center gap-2">
        <?php if ($_POST['fetch_from'] !== 'vat_copies') { ?>
            <select name="print_type" id="print_type" class="form-select form-select-sm">
                <option value="invoice" <?= $_POST['print_type'] === 'invoice' ? 'selected' : ''; ?>>Invoice Print</option>
                <option value="packing-list" <?= $_POST['print_type'] === 'packing-list' ? 'selected' : ''; ?>>Packing List Print</option>
            </select>
        <?php } ?>
        <script>
            let fullPrintURL = printUrl + '&print_type=<?= $_POST['print_type'] ?>';
            // document.querySelector('#printPreviewBtn').setAttribute('href', fullPrintURL);
            document.querySelector('#print_type').addEventListener('change', function() {
                fullPrintURL = printUrl + '&print_type=' + this.value;
                // document.querySelector('#printPreviewBtn').setAttribute('href', fullPrintURL);
            });
        </script>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-print"></i>
            </button>
            <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                <!-- <li>
                    <a class="dropdown-item" id="printPreviewBtn" target="_blank">
                        <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                    </a>
                </li> -->

                <li>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint(fullPrintURL)">
                        <i class="fas text-secondary fa-print me-2"></i> Print
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', fullPrintURL)">
                        <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="#" onclick="getFileThrough('word', fullPrintURL)">
                        <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', fullPrintURL)">
                        <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="#" onclick="getFileThrough('email', fullPrintURL)">
                        <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                    </a>
                </li>
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>
<input type="hidden" name="tdata" id="tdata" value='<?= $data['tdata']; ?>'>
<input type="hidden" name="ldata" id="tdata" value='<?= $data['ldata']; ?>'>
<input type="hidden" name="unique_code" value="<?= $unique_code; ?>">
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
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row g-3">
                <!-- Quantity Name -->
                <div class="col-md-3">
                    <label for="quantity_name" class="form-label">Qty Name</label>
                    <input type="text" name="quantity_name" value="<?= $Good['quantity_name']; ?>" id="myquantity_name" required class="form-control form-control-sm">
                </div>

                <!-- Quantity No -->
                <div class="col-md-3">
                    <label for="quantity_no" class="form-label">Qty No</label>
                    <input type="number" name="quantity_no" value="<?= $Good['quantity_no']; ?>" id="quantity_no" required class="form-control form-control-sm" step="0.01" onkeyup="autoCalc('#quantity_no', '#gross_weight', '#net_weight', Rate, emptyKgs)">
                    <input type="hidden" name="rate" id="rate" value="">
                    <input type="hidden" name="empty_kgs" id="empty_kgs" value="">
                </div>

                <!-- Gross Weight -->
                <div class="col-md-3">
                    <label for="gross_weight" class="form-label">G.Weight</label>
                    <input type="number" name="gross_weight" value="<?= $Good['gross_weight']; ?>" id="gross_weight" required class="form-control form-control-sm" step="0.01">
                </div>
                <!-- Net Weight -->
                <div class="col-md-3">
                    <label for="net_weight" class="form-label">N.Weight</label>
                    <input type="number" name="net_weight" value="<?= $Good['net_weight']; ?>" id="net_weight" required class="form-control form-control-sm" step="0.01">
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-2" id="goodsDiv">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-3">
                    <div class="input-group my-2">
                        <label class="col-form-label text-nowrap">Allotment Name</label>
                        <input value="<?= $Good['goods_json']['allotment_name'] ?? ''; ?>" name="allotment_name" class="allotment_name form-control form-control-sm">
                    </div>

                    <div class="input-group my-2">
                        <label>GOODS</label>
                        <select name="goods_id" class="goods_id form-select form-select-sm">
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
                        <select class="size form-select form-select-sm" name="size">
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
                        <select class="form-select form-select-sm origin" name="origin">
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
                        <select class="form-select form-select-sm brand" name="brand">
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
                                    <input value="<?php echo $Good['goods_json']['qty_name']; ?>"
                                        name="qty_name" class="qty_name form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row g-0">
                                <label class="col-sm-4 col-form-label text-nowrap"
                                    for="qty_no">Qty#</label>
                                <div class="col-sm">
                                    <input value="<?= $Good['goods_json']['qty_no']; ?>"
                                        name="qty_no"
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
                                        name="qty_kgs"
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
                                        name="empty_kgs" class="empty_kgs form-control form-control-sm currency">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row g-0">
                                <label class="col-sm-4 col-form-label text-nowrap"
                                    for="divide">DIVIDE</label>
                                <div class="col-sm">
                                    <select name="divide" class="divide form-select">
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
                                        name="weight"
                                        class="form-control weight form-control-sm currency">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row g-0">
                                <label class="col-sm-4 col-form-label text-nowrap"
                                    for="price">PRICE</label>
                                <div class="col-sm">
                                    <select name="price" class="price form-select form-select-sm">
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
                                    <select name="currency1" class="currency1 form-select form-select-sm">
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
                                        name="rate1"
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
                                        <select name="currency2" class="form-select form-select-sm currency2">
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
                                            name="rate2"
                                            class="form-control rate2 form-control-sm currency">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-0">
                                    <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                    <div class="col-sm">
                                        <select name="opr" class="opr form-select form-select-sm">
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
                                            name="tax_percent"
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
                                            name="tax_amount"
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
                                            name="total_with_tax" class="total_with_tax">
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
                        name="total_kgs" type="hidden">
                    <input value="<?php echo $Good['goods_json']['total_qty_kgs']; ?>"
                        name="total_qty_kgs" class="total_qty_kgs"
                        type="hidden">
                    <input value="<?= $Good['goods_json']['net_kgs'] ?>" name="net_kgs"
                        type="hidden" class="net_kgs">
                    <input value="<?php echo $Good['goods_json']['total']; ?>" name="total"
                        type="hidden" class="total">
                    <input value="<?php echo $Good['goods_json']['amount']; ?>" name="amount"
                        type="hidden" class="amount">
                    <input value="<?php echo $Good['goods_json']['final_amount']; ?>"
                        name="final_amount" type="hidden" class="final_amount">
                </div>
            </div>
            <?php if ($Tcat !== 'l') { ?>
                <h5 class="text-primary mt-3">Agent Form ( <small style="font-size: 14px;"><b>Agent: </b> <?= $Agent['ag_name'] ?? 'Not Set'; ?> | <b>Acc: </b> <?= $Agent['ag_acc_no'] ?? 'Not Set'; ?> </small> )</h5>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="boe_no" class="form-label"> BOE No.</label>
                        <input type="text" name="boe_no" id="boe_no"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['boe_no']) ? $Agent['boe_no'] : ''; ?>">
                    </div>
                    <!-- BOE DATE -->
                    <div class="col-md-2">
                        <label for="boe_date" class="form-label">BOE Date</label>
                        <input type="date" name="boe_date" id="boe_date"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['boe_date']) ? $Agent['boe_date'] : ''; ?>">
                    </div>

                    <!-- PICK UP DATE -->
                    <div class="col-md-2">
                        <label for="pick_up_date" class="form-label">Pick Up Date</label>
                        <input type="date" name="pick_up_date" id="pick_up_date"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['pick_up_date']) ? $Agent['pick_up_date'] : ''; ?>">
                    </div>

                    <!-- WAITING IF ANY -->
                    <div class="col-md-2">
                        <label for="waiting_days" class="form-label">Waiting (days)</label>
                        <input type="text" name="waiting_days" id="waiting_days"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['waiting_days']) ? $Agent['waiting_days'] : ''; ?>">
                    </div>

                    <!-- RETURN DATE -->
                    <div class="col-md-2">
                        <label for="return_date" class="form-label">Return Date</label>
                        <input type="date" name="return_date" id="return_date"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['return_date']) ? $Agent['return_date'] : ''; ?>">
                    </div>

                    <!-- TRANSPORTER NAME -->
                    <div class="col-md-2">
                        <label for="transporter_name" class="form-label">Transporter Name</label>
                        <input type="text" name="transporter_name" id="transporter_name"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['transporter_name']) ? $Agent['transporter_name'] : ''; ?>">
                    </div>

                    <!-- TRUCK NUMBER -->
                    <div class="col-md-2">
                        <label for="truck_number" class="form-label">Truck Number</label>
                        <input type="text" name="truck_number" id="truck_number"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['truck_number']) ? $Agent['truck_number'] : ''; ?>">
                    </div>

                    <!-- DETAILS -->
                    <div class="col-md-4">
                        <label for="details" class="form-label">Details</label>
                        <input type="text" name="details" id="details"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['details']) ? $Agent['details'] : ''; ?>">
                    </div>

                    <!-- DRIVER NAME -->
                    <div class="col-md-3">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" name="driver_name" id="driver_name"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['driver_name']) ? $Agent['driver_name'] : ''; ?>">
                    </div>

                    <!-- DRIVER NUMBER -->
                    <div class="col-md-3">
                        <label for="driver_number" class="form-label">Driver Number</label>
                        <input type="number" name="driver_number" id="driver_number"
                            class="form-control form-control-sm"
                            value="<?= isset($Agent['driver_number']) ? $Agent['driver_number'] : ''; ?>">
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>
<div class="row bg-white p-3 mt-4">
    <div class="col-md-12 text-end">
        <button name="reSubmit" id="reSubmit" type="submit"
            class="btn btn-warning btn-sm rounded-0">
            <i class="fa fa-paper-plane"></i> Update </button>
    </div>
</div>
<script>
    let Rate;
    let emptyKgs;
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
    $(document).ready(function() {
        finalAmount();
        $(document).on('keyup', '#goodsDiv input', function() {
            finalAmount();
        });

        $(document).on('change', '#goodsDiv select', function() {
            finalAmount();
        });
    });

    function finalAmount() {
        var qty_no = parseFloat($(".qty_no").val()) || 0;
        var qty_kgs = parseFloat($(".qty_kgs").val()) || 0;

        var total_kgs = qty_no * qty_kgs;
        $(".total_kgs").val(total_kgs.toFixed(2));
        $(".total_kgs_span").text(total_kgs.toFixed(2));

        var empty_kgs = parseFloat($(".empty_kgs").val()) || 0;
        var total_qty_kgs = qty_no * empty_kgs;
        $(".total_qty_kgs").val(total_qty_kgs.toFixed(2));
        $(".total_qty_kgs_span").text(total_qty_kgs.toFixed(2));

        var net_kgs = total_kgs - total_qty_kgs;
        $(".net_kgs").val(net_kgs.toFixed(2));
        $(".net_kgs_span").text(net_kgs.toFixed(2));

        var weight = parseFloat($(".weight").val()) || 0;
        var total = 0;

        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = total.toFixed(3);
        }

        $(".total").val(isNaN(total) ? '' : total);
        $(".total_span").text(isNaN(total) ? '' : total);

        var rate1 = parseFloat($(".rate1").val()) || 0;
        // var amount = (isNaN(total) || total === 0) ? 0 : (total * rate1).toFixed(3);
        var final_amount = 0;
        var amount = 0;
        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
            amount = total * rate1;
            amount = amount.toFixed(3);
            final_amount = amount;
        }

        $(".amount").val(isNaN(amount) ? '' : amount);
        $(".amount_span").text(isNaN(amount) ? '' : amount);

        updateTaxAndTotal();

        var rate2 = parseFloat($(".rate2").val()) || 0;
        let operator = $('.opr').find(":selected").val();
        if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
            final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
            final_amount = parseFloat(final_amount.toFixed(3));
        }

        $(".final_amount").val(isFinite(final_amount) ? final_amount : '');
        $(".final_amount_span").text(isFinite(final_amount) ? final_amount : '');

        if (isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('reSubmit');
        } else {
            enableButton('reSubmit');
        }
        Rate = rate1;
        emptyKgs = empty_kgs;
        autoCalc('#quantity_no', '#gross_weight', '#net_weight', Rate, emptyKgs);

    }


    function updateTaxAndTotal() {
        let amount = parseFloat($('.amount_span').text()) || 0;
        let taxPercent = parseFloat($('.tax_percent').val()) || 0;
        let taxAmount = (amount * (taxPercent / 100)).toFixed(2);
        let totalWithTax = (amount + parseFloat(taxAmount)).toFixed(2);
        $('.tax_amount').val(taxAmount != 0 ? taxAmount : '');
        $('.total_with_tax').val(totalWithTax);
        $('.total_with_tax_span').text(totalWithTax);
    }
</script>
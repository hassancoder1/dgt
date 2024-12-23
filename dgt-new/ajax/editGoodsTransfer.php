<?php
require_once '../connection.php';

$page_title = 'EDIT INFORMATION';
$back_page_url = $data_for = $pageURL = $_POST['page'];
$print_url = 'print/' . $data_for;
$unique_code = $_POST['unique_code'];

// Decode unique code
[$Ttype, $Tcat, $Troute, $TID, $BLUID] = decode_unique_code($unique_code, 'all');

$LoadingsTable = ($Tcat === 'l' ? 'local' : 'general') . '_loading';
$searchColumn = ($Tcat === 'l') ? 'uid' : 'bl_no';

$recordExists = recordExists('data_copies', ['unique_code' => $unique_code]);
$Ldata = [];

if ($recordExists) {
    $dataType = "Copied";
    $data = mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $unique_code]));
    $Tdata = json_decode($data['tdata'], true);
    $Ldata = json_decode($data['ldata'], true);
} else {
    $dataType = "Original";
    $LoadingsQuery = mysqli_query($connect, "SELECT * FROM $LoadingsTable WHERE $searchColumn = '$BLUID'");
    while ($SL = mysqli_fetch_assoc($LoadingsQuery)) {
        $Ldata[] = $SL;
    }

    $firstEntry = $Ldata[0] ?? [];
    $Ttempdata = mysqli_fetch_assoc(fetch('transactions', ['id' => $TID]));
    $Tdata = array_merge(
        transactionSingle($TID),
        json_decode($Ttempdata['sea_road'], true),
        json_decode($Ttempdata['notify_party_details'], true) ?? [],
        ['third_party_bank' => json_decode($Ttempdata['third_party_bank'], true)] ?? [],
        ['reports' => json_decode($Ttempdata['reports'], true)] ?? []
    );

    $goodsData = [];
    foreach ($Ldata as $loading) {
        $goodsID = $loading['id'];
        $goods_data = json_decode($loading['goods_details'], true);
        $goodsData[$goodsID] = [
            "sr_no" => $loading['sr_no'] ?? '',
            "goods_id" => $goods_data['goods_id'] ?? '',
            "quantity_no" => $goods_data['quantity_no'] ?? '',
            "rate" => $goods_data['rate'] ?? '',
            "empty_kgs" => $goods_data['empty_kgs'] ?? '',
            "quantity_name" => $goods_data['quantity_name'] ?? '',
            "size" => $goods_data['size'] ?? '',
            "brand" => $goods_data['brand'] ?? '',
            "origin" => $goods_data['origin'] ?? '',
            "net_weight" => $goods_data['net_weight'] ?? '',
            "gross_weight" => $goods_data['gross_weight'] ?? '',
            "container_no" => $goods_data['container_no'] ?? '',
            "container_name" => $goods_data['container_name'] ?? '',
            "goods_json" => json_decode($goods_data['goods_json'] ?? '{}', true),
            "agent" => json_decode($loading['agent_details'] ?? $loading['transfer_details'], true),
        ];
    }
    $finalData = [
        "id" => $firstEntry['id'],
        $searchColumn => $firstEntry[$searchColumn],
        "goods" => $goodsData,
        "report" => $firstEntry['report'] ?? '',
        "attachments" => json_decode($firstEntry['attachments'] ?? '[]', true),
        "created_at" => $firstEntry['created_at'] ?? '',
    ];
    if ($Tcat === 'l') {
        $Ldata = array_merge($finalData, [
            "lloading_info" => json_decode($firstEntry['lloading_info'] ?? '[]', true),
            "transfer_details" => json_decode($firstEntry['transfer_details'] ?? '[]', true),
        ]);
    } else {
        $Ldata = array_merge($finalData, [
            "transfer_details" => array_merge(json_decode($firstEntry['loading_details'] ?? '[]', true), json_decode($firstEntry['receiving_details'] ?? '[]', true), json_decode($firstEntry['shipping_details'] ?? '[]', true)),
            "gloading_info" => json_decode($firstEntry['gloading_info'] ?? '[]', true),
            "importer_details" => json_decode($firstEntry['importer_details'] ?? '[]', true),
            "notify_party_details" => json_decode($firstEntry['notify_party_details'] ?? '[]', true),
            "exporter_details" => json_decode($firstEntry['exporter_details'] ?? '[]', true)
        ]);
    }
}

$_POST['print_type'] = $_POST['print_type'] ?? '';
$print_url .= '?unique_code=' . $unique_code . '&print_type=' . $_POST['print_type'] . "&timestamp=" . ($_POST['timestamp'] ?? '');
?>


<div class="modal-header d-flex justify-content-between bg-white align-items-center mb-2">
    <h5 class="modal-title" id="staticBackdropLabel">EDIT INFORMATION</h5>
    <div class="d-flex align-items-center gap-2">
        <?php if ($dataType !== 'Original') { ?>
            <select name="print_type" id="print_type" class="form-select form-select-sm">
                <option value="contract" <?= $_POST['print_type'] === 'contract' ? 'selected' : ''; ?>>Contract Print</option>
                <option value="invoice" <?= $_POST['print_type'] === 'invoice' ? 'selected' : ''; ?>>Invoice Print</option>
            </select>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="<?= $print_url; ?>" target="_blank">
                            <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')">
                            <i class="fas text-secondary fa-print me-2"></i> Print
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                            <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                            <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                            <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                            <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                        </a>
                    </li>
                </ul>
            </div>
        <?php } ?>
        <a href="<?= $data_for; ?>" class="btn-close"></a>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <form method="POST">
            <?= $recordExists ? '<input type="hidden" name="updateTrue" value="true">' : ''; ?>
            <input type="hidden" name="tdata" value='<?= json_encode($Tdata); ?>'>
            <input type="hidden" name="ldata" value='<?= json_encode($Ldata); ?>'>
            <input type="hidden" name="recordEdited" id="recordEdited">
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
                                        <input type="hidden" name="im_acc_id" value="<?= isset($Ldata['importer_details']['im_acc_id']) ? $Ldata['importer_details']['im_acc_id'] : ''; ?>" id="im_acc_id">
                                        <input type="text" id="im_acc_no" name="im_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                            placeholder="ACC No" value="<?= isset($Ldata['importer_details']['im_acc_no']) ? $Ldata['importer_details']['im_acc_no'] : ''; ?>">
                                        <input type="text" id="im_acc_name" name="im_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                            placeholder="Importer Name" value="<?= isset($Ldata['importer_details']['im_acc_name']) ? $Ldata['importer_details']['im_acc_name'] : ''; ?>">
                                    </div>
                                    <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="im_acc_kd_id" id="im_acc_kd_id">
                                        <option hidden value="">Select Company</option>
                                        <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['importer_details']['im_acc_id']) ? $Ldata['importer_details']['im_acc_id'] : '', 'type' => 'company'));
                                        while ($row = mysqli_fetch_array($run_query)) {
                                            $row_data = json_decode($row['json_data']);
                                            $sel_kd2 = $row['id'] == $Ldata['importer_details']['im_acc_kd_id'] ? 'selected' : '';
                                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                        }  ?>
                                    </select>
                                    <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="im_acc_details" id="im_acc_details" rows="5"
                                        placeholder="Company Details"><?= isset($Ldata['importer_details']['im_acc_details']) ? $Ldata['importer_details']['im_acc_details'] : ''; ?></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="xp_acc_no" class="form-label fw-bold">Exporter Details</label>
                                    <div class="input-group">
                                        <input type="hidden" name="xp_acc_id" value="<?= isset($Ldata['exporter_details']['xp_acc_id']) ? $Ldata['exporter_details']['xp_acc_id'] : ''; ?>" id="xp_acc_id">
                                        <input type="text" id="xp_acc_no" name="xp_acc_no" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                            placeholder="ACC No" value="<?= isset($Ldata['exporter_details']['xp_acc_no']) ? $Ldata['exporter_details']['xp_acc_no'] : ''; ?>">
                                        <input type="text" id="xp_acc_name" name="xp_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                            placeholder="Importer Name" value="<?= isset($Ldata['exporter_details']['xp_acc_name']) ? $Ldata['exporter_details']['xp_acc_name'] : ''; ?>">
                                    </div>
                                    <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="xp_acc_kd_id" id="xp_acc_kd_id">
                                        <option hidden value="">Select Company</option>
                                        <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['exporter_details']['xp_acc_id']) ? $Ldata['exporter_details']['xp_acc_id'] : '', 'type' => 'company'));
                                        while ($row = mysqli_fetch_array($run_query)) {
                                            $row_data = json_decode($row['json_data']);
                                            $sel_kd2 = $row['id'] == $Ldata['exporter_details']['xp_acc_kd_id'] ? 'selected' : '';
                                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                        }  ?>
                                    </select>
                                    <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="xp_acc_details" id="xp_acc_details" rows="5"
                                        placeholder="Company Details"><?= isset($Ldata['exporter_details']['xp_acc_details']) ? $Ldata['exporter_details']['xp_acc_details'] : ''; ?></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label for="np_acc_no" class="form-label fw-bold">Notify Party Details</label>
                                    <div class="input-group">
                                        <input type="hidden" name="np_acc_id" value="<?= isset($Ldata['notify_party_details']['np_acc_id']) ? $Ldata['notify_party_details']['np_acc_id'] : ''; ?>" id="np_acc_id">
                                        <input type="text" id="np_acc_no" name="np_acc_no" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                            placeholder="ACC No" value="<?= isset($Ldata['notify_party_details']['np_acc_no']) ? $Ldata['notify_party_details']['np_acc_no'] : ''; ?>">
                                        <input type="text" id="np_acc_name" name="np_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                            placeholder="Notify Party Name" value="<?= isset($Ldata['notify_party_details']['np_acc_name']) ? $Ldata['notify_party_details']['np_acc_name'] : ''; ?>">
                                    </div>
                                    <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="np_acc_kd_id" id="np_acc_kd_id">
                                        <option hidden value="">Select Company</option>
                                        <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Ldata['notify_party_details']['np_acc_id']) ? $Ldata['notify_party_details']['np_acc_id'] : '', 'type' => 'company'));
                                        while ($row = mysqli_fetch_array($run_query)) {
                                            $row_data = json_decode($row['json_data']);
                                            $sel_kd2 = $row['id'] == $Ldata['notify_party_details']['np_acc_kd_id'] ? 'selected' : '';
                                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                        }  ?>
                                    </select>
                                    <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="np_acc_details" id="np_acc_details" rows="5"
                                        placeholder="Company Details"><?= isset($Ldata['notify_party_details']['np_acc_details']) ? $Ldata['notify_party_details']['np_acc_details'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- Summary Section (Initially Visible) -->
                <?php
                $warehouse = '';
                if (!empty($Ldata['goods'])) {
                    $firstGoods = reset($Ldata['goods']);
                    $warehouse = $firstGoods['agent']['cargo_transfer_warehouse'] ?? $firstGoods['agent']['warehouse_transfer'] ?? '';
                }
                $saleCheck = $Ttype === 's' ? 'onchange="currentStock(this)"' : '';
                ?>
                <div id="summarySection">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row gy-3">
                                <?php if ($Tcat === 'l') {
                                    if ($Ldata['transfer_details']['route'] === 'local') { ?>
                                        <div class="col-md-2">
                                            <span>
                                                <b>Truck Number</b><br>
                                                <?php echo $Ldata['transfer_details']['truck_no']; ?>
                                            </span>
                                        </div>
                                        <div class="col-md-2">
                                            <span>
                                                <b>Truck Name</b><br>
                                                <?php echo $Ldata['transfer_details']['truck_name']; ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>
                                                <b>Loading Warehouse</b><br>
                                                <?php echo $Ldata['transfer_details']['loading_warehouse']; ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>
                                                <b>Receiving Warehouse</b><br>
                                                <?php echo $Ldata['transfer_details']['receiving_warehouse']; ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <span>
                                            <b>Loading Company</b><br>
                                            <?php echo $Ldata['transfer_details']['loading_company_name']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>
                                            <b>Receiving Company Name</b><br>
                                            <?php echo $Ldata['transfer_details']['receiving_company_name']; ?>
                                        </span>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-2">
                                        <span>
                                            <b>Loading Country</b><br>
                                            <?php echo $Ldata['transfer_details']['loading_country']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>
                                            <b>L <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</b><br>
                                            <?php echo $Ldata['transfer_details']['loading_port_name']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>
                                            <b>Receiving Country</b><br>
                                            <?php echo $Ldata['transfer_details']['receiving_country']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>
                                            <b>R <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</b><br>
                                            <?php echo $Ldata['transfer_details']['receiving_port_name']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>
                                            <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name</b><br>
                                            <?php echo $Ldata['transfer_details']['shipping_name']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>
                                            <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address</b><br>
                                            <?php echo $Ldata['transfer_details']['shipping_address']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>
                                            <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone</b><br>
                                            <?php echo $Ldata['transfer_details']['shipping_phone']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>
                                            <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp</b><br>
                                            <?php echo $Ldata['transfer_details']['shipping_whatsapp']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>
                                            <b><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email</b><br>
                                            <?php echo $Ldata['transfer_details']['shipping_email']; ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <div class="col-md-2">
                                    <span>
                                        <b>Loading Date</b><br>
                                        <?php echo $Ldata['transfer_details']['loading_date']; ?>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span>
                                        <b>Receiving Date</b><br>
                                        <?php echo $Ldata['transfer_details']['receiving_date']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span>
                                        <b>Cargo Transfer</b><br>
                                        <?php echo $warehouse; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="editButton" class="btn btn-sm btn-primary" style="position: absolute; top: 5px; right: 5px;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Editable Form Section (Initially Hidden) -->
                <div id="formSection" style="display: none;">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row gy-3">
                                <!-- Form Fields (Same as in the Summary Section) -->
                                <?php if ($Tcat === 'l') {
                                    if ($Ldata['transfer_details']['route'] === 'local') { ?>
                                        <div class="col-md-2">
                                            <label for="truck_no" class="form-label">Truck Number</label>
                                            <input id="truck_no" name="truck_no" value="<?php echo $Ldata['transfer_details']['truck_no']; ?>" type="text" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="truck_name" class="form-label">Truck Name</label>
                                            <input id="truck_name" name="truck_name" value="<?php echo $Ldata['transfer_details']['truck_name']; ?>" type="text" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="loading_warehouse" class="form-label">Loading Warehouse</label>
                                            <input id="loading_warehouse" name="loading_warehouse" value="<?php echo $Ldata['transfer_details']['loading_warehouse']; ?>" type="text" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="receiving_warehouse" class="form-label">Receiving Warehouse</label>
                                            <input id="receiving_warehouse" name="receiving_warehouse" value="<?php echo $Ldata['transfer_details']['receiving_warehouse']; ?>" type="text" class="form-control form-control-sm">
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <label for="loading_company_name" class="form-label">Loading Company</label>
                                        <input id="loading_company_name" name="loading_company_name" value="<?php echo $Ldata['transfer_details']['loading_company_name']; ?>" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="receiving_company_name" class="form-label">Receiving Company Name</label>
                                        <input id="receiving_company_name" name="receiving_company_name" value="<?php echo $Ldata['transfer_details']['receiving_company_name']; ?>" type="text" class="form-control form-control-sm">
                                    </div>
                                <?php } else { ?>
                                    <!-- Form Fields for Sea/Road -->
                                    <div class="col-md-2">
                                        <label for="loading_country" class="form-label">Loading Country</label>
                                        <input type="text" name="loading_country" id="loading_country" value="<?= $Ldata['transfer_details']['loading_country']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="loading_port_name" class="form-label">L <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                        <input type="text" name="loading_port_name" id="loading_port_name" value="<?= $Ldata['transfer_details']['loading_port_name']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="receiving_country" class="form-label">Receiving Country</label>
                                        <input type="text" name="receiving_country" id="receiving_country" value="<?= $Ldata['transfer_details']['receiving_country']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="receiving_port_name" class="form-label">R <?= $Tdata['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                        <input type="text" name="receiving_port_name" id="receiving_port_name" value="<?= $Ldata['transfer_details']['receiving_port_name']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="shipping_name" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name</label>
                                        <input type="text" name="shipping_name" id="shipping_name" value="<?= $Ldata['transfer_details']['shipping_name']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="shipping_address" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address</label>
                                        <input type="text" name="shipping_address" id="shipping_address" value="<?= $Ldata['transfer_details']['shipping_address']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="shipping_phone" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone</label>
                                        <input type="text" name="shipping_phone" id="shipping_phone" value="<?= $Ldata['transfer_details']['shipping_phone']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="shipping_whatsapp" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp</label>
                                        <input type="text" name="shipping_whatsapp" id="shipping_whatsapp" value="<?= $Ldata['transfer_details']['shipping_whatsapp']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="shipping_email" class="form-label"><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email</label>
                                        <input type="text" name="shipping_email" id="shipping_email" value="<?= $Ldata['transfer_details']['shipping_email']; ?>" class="form-control form-control-sm">
                                    </div>
                                <?php } ?>
                                <div class="col-md-2">
                                    <label for="loading_date" class="form-label">Loading Date</label>
                                    <input type="text" name="loading_date" id="loading_date" value="<?= $Ldata['transfer_details']['loading_date']; ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="receiving_date" class="form-label">Receiving Date</label>
                                    <input type="text" name="receiving_date" id="receiving_date" value="<?= $Ldata['transfer_details']['receiving_date']; ?>" class="form-control form-control-sm">
                                </div>
                                <!-- Edit Section (when in edit mode) -->
                                <div class="col-md-3">
                                    <label for="warehouse_transfer" class="form-label">Cargo Transfer</label>
                                    <select id="warehouse_transfer" name="warehouse_transfer" class="form-select form-select-sm" <?= $saleCheck; ?>>
                                        <option disabled <?= empty($warehouse) ? 'selected' : '' ?>>Select One</option>
                                        <option value="Local Import" <?= $warehouse === 'Local Import' ? 'selected' : '' ?>>Local Import</option>
                                        <option value="Free Zone Import" <?= $warehouse === 'Free Zone Import' ? 'selected' : '' ?>>Free Zone Import</option>
                                        <option value="Import Re-Export" <?= $warehouse === 'Import Re-Export' ? 'selected' : '' ?>>Import Re-Export</option>
                                        <option value="Transit" <?= $warehouse === 'Transit' ? 'selected' : '' ?>>Transit</option>
                                        <option value="Local Export" <?= $warehouse === 'Local Export' ? 'selected' : '' ?>>Local Export</option>
                                        <option value="Local Market" <?= $warehouse === 'Local Market' ? 'selected' : '' ?>>Local Market</option>
                                    </select>
                                </div>

                                <input type="hidden" name="transfer_to_warehouse_ids" id="transfer_to_warehouse_ids">

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

                                <?php if ($Ttype === 's') { ?>
                                    <div class="col-md-5">
                                        <label for="warehouse_entry" class="form-label">Current Entries In Selected Warehouse</label>
                                        <select id="warehouse_entry" name="warehouse_entry" class="form-select form-select-sm" <?= $saleCheck; ?>>
                                            <?php if (!empty($Ldata['goods'][0]['agent']['sold_to'])) {
                                                // $soldToEntry = explode('~', $Ldata['sold_to'][0]); 
                                            ?>
                                                <!-- <option value="<?= $Ldata['sold_to'][0]; ?>"> -->
                                                <!--     <?= 'P#' . decode_unique_code($againstEntry[0], 'TID') . ' => ' . $againstEntry[3]; ?> -->
                                                <!-- </option> -->
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } else {
                                    if (!empty($Ldata['sold_to'])) {
                                        $soldToEntry = explode('~', $Ldata['sold_to'][0]);
                                        echo '<span class="fw-bold text-danger"> SOLD IN P#' . decode_unique_code($soldToEntry[0], 'TID') . ' => ' . $soldToEntry[2], '</span>';
                                    }
                                }
                                ?>

                                <!-- Cross Button -->
                                <div class="col-md-3">
                                    <button type="button" id="closeButton" class="btn btn-sm btn-danger" style="position: absolute; top: 5px; right: 5px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <table class="table mt-2 table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="bg-dark text-white"><i class="fa fa-check-square"></i></th>
                            <th class="bg-dark text-white">Sr#</th>
                            <?php if ($Tcat !== 'l') { ?>
                                <th class="bg-dark text-white">Container No</th>
                                <th class="bg-dark text-white">Container Name</th>
                            <?php } ?>
                            <th class="bg-dark text-white">Warehouse</th>
                            <th class="bg-dark text-white">Goods Name</th>
                            <th class="bg-dark text-white">Size</th>
                            <th class="bg-dark text-white">Brand</th>
                            <th class="bg-dark text-white">Origin</th>
                            <th class="bg-dark text-white">Quantity</th>
                            <th class="bg-dark text-white">G.W.KGS</th>
                            <th class="bg-dark text-white">N.W.KGS</th>
                            <th class="bg-dark text-white">Edit</th>
                        </tr>
                    </thead>
                    <tbody class="loadingsTable">
                        <?php
                        $quantity_no = $gross_weight = $net_weight = 0;
                        foreach ($Ldata['goods'] as $l_ID => $row) {
                        ?>
                            <tr>
                                <td class="border border-dark text-center">
                                    <input type="checkbox" class="row-checkbox" value="<?= $l_ID; ?>">
                                </td>
                                <td class="border border-dark"><?= $row["sr_no"] ?? 'N/A'; ?></td>
                                <?php if ($Tcat !== 'l') { ?>
                                    <td class="border border-dark"><?= $row["container_no"] ?? 'N/A'; ?></td>
                                    <td class="border border-dark"><?= $row["container_name"] ?? 'N/A'; ?></td>
                                <?php } ?>
                                <td class="border border-dark"><?= $row["agent"]['cargo_transfer_warehouse'] ?? $row['agent']['warehouse_transfer'] ?? ''; ?></td>
                                <td class="border border-dark"><?= goodsName($row["goods_id"]); ?></td>
                                <td class="border border-dark"><?= $row["size"]; ?></td>
                                <td class="border border-dark"><?= $row["brand"]; ?></td>
                                <td class="border border-dark"><?= $row["origin"]; ?></td>
                                <td class="border border-dark"><?= !isset($row['edited']) ? $row["quantity_no"] : $row['goods_json']['qty_no'] ?? 'N/A'; ?> <sub><?= $row["quantity_name"] ?? 'N/A'; ?></sub></td>
                                <td class="border border-dark"><?= !isset($row['edited']) ? $row["gross_weight"] : $row['goods_json']['total_kgs'] ?? 'N/A'; ?></td>
                                <td class="border border-dark"><?= !isset($row['edited']) ? $row["net_weight"] : $row['goods_json']['net_kgs'] ?? 'N/A'; ?></td>
                                <td>
                                    <i class="fa fa-pencil fs-5 text-primary pointer toggle-icon" data-id="<?= $l_ID; ?>"></i>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>
            <div id="goodsDiv">
                <?php
                foreach ($Ldata['goods'] as $l_ID => $row):
                ?>
                    <div class="d-none entryform entryform<?= $l_ID; ?>">
                        <div class=" card mb-2">
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-md-3">
                                        <div class="input-group my-2">
                                            <label class="col-form-label text-nowrap">Allotment Name</label>
                                            <input value="<?= isset($row['goods_json']['allotment_name']) ? $row['goods_json']['allotment_name'] : ''; ?>" name="<?= $l_ID ?>_allotment_name" class="allotment_name form-control form-control-sm">
                                        </div>

                                        <div class="input-group my-2">
                                            <label>GOODS</label>
                                            <select name="<?= $l_ID ?>_goods_id" class="goods_id form-select form-select-sm">
                                                <option hidden value="">Select</option>
                                                <?php
                                                $goods = fetch('goods');
                                                while ($good = mysqli_fetch_assoc($goods)) {
                                                    $g_selected = $good['id'] == $row['goods_id'] ? 'selected' : '';
                                                    echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="input-group my-2">
                                            <label for="size">SIZE</label>
                                            <select class="size form-select form-select-sm" name="<?= $l_ID ?>_size">
                                                <option hidden value="">Select</option>
                                                <?php
                                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $row['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['size'] == $row['size'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="input-group my-2">
                                            <label for="origin">ORIGIN</label>
                                            <select class="form-select form-select-sm origin" name="<?= $l_ID ?>_origin">
                                                <option hidden value="">Select</option>
                                                <?php
                                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $row['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['origin'] == $row['origin'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="input-group my-2">
                                            <label for="brand">BRAND</label>
                                            <select class="form-select form-select-sm brand" name="<?= $l_ID ?>_brand">
                                                <option hidden value="">Select</option>
                                                <?php
                                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $row['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['brand'] == $row['brand'] ? 'selected' : '';
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
                                                        <input value="<?php echo $row['quantity_name']; ?>"
                                                            name="<?= $l_ID ?>_qty_name" class="qty_name form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="qty_no">Qty#</label>
                                                    <div class="col-sm">
                                                        <input value="<?= !isset($row['edited']) ? $row["quantity_no"] : $row['goods_json']['qty_no']; ?>"
                                                            name="<?= $l_ID ?>_qty_no"
                                                            class="form-control qty_no form-control-sm currency">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap" for="qty_kgs">Qty
                                                        KGs</label>
                                                    <div class="col-sm">
                                                        <input value="<?php echo $row['goods_json']['qty_kgs']; ?>"
                                                            name="<?= $l_ID ?>_qty_kgs"
                                                            class="form-control qty_kgs form-control-sm currency">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap" for="empty_kgs">Empty
                                                        KGs</label>
                                                    <div class="col-sm">
                                                        <input value="<?php echo $row['goods_json']['empty_kgs']; ?>"
                                                            name="<?= $l_ID ?>_empty_kgs" class="empty_kgs form-control form-control-sm currency">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="divide">DIVIDE</label>
                                                    <div class="col-sm">
                                                        <select name="<?= $l_ID ?>_divide" class="divide form-select">
                                                            <?php $divides = array('TON' => 'TON', 'KGs' => 'KG', 'CARTON' => 'CARTON');
                                                            foreach ($divides as $item => $val) {
                                                                $d_sel = ($row['goods_json']['divide'] ?? '') == $val ? 'selected' : '';
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
                                                        <input value="<?php echo $row['goods_json']['weight']; ?>"
                                                            name="<?= $l_ID ?>_weight"
                                                            class="form-control weight form-control-sm currency">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="price">PRICE</label>
                                                    <div class="col-sm">
                                                        <select name="<?= $l_ID ?>_price" class="price form-select form-select-sm">
                                                            <?php $prices = array('TON PRICE' => 'TON', 'KGs PRICE' => 'KG', 'CARTON PRICE' => 'CARTON');
                                                            foreach ($prices as $item => $val) {
                                                                $pr_sel = ($row['goods_json']['price'] ?? '') == $val ? 'selected' : '';
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
                                                        <select name="<?= $l_ID ?>_currency1" class="currency1 form-select form-select-sm">
                                                            <option selected hidden disabled value="">Select</option>
                                                            <?php $currencies = fetch('currencies');
                                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                $crr_sel = $crr['name'] == ($row['goods_json']['currency1'] ?? '') ? 'selected' : '';
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
                                                        <input value="<?php echo $row['goods_json']['rate1']; ?>"
                                                            name="<?= $l_ID ?>_rate1"
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
                                                            <select name="<?= $l_ID ?>_currency2" class="form-select form-select-sm currency2">
                                                                <option selected hidden disabled value="">Select</option>
                                                                <?php $currencies = fetch('currencies');
                                                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                    $crr_sel2 = $crr['name'] == ($row['goods_json']['currency2'] ?? '') ? 'selected' : '';
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
                                                            <input value="<?php echo $row['goods_json']['rate2']; ?>"
                                                                name="<?= $l_ID ?>_rate2"
                                                                class="form-control rate2 form-control-sm currency">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                                        <div class="col-sm">
                                                            <select name="<?= $l_ID ?>_opr" class="opr form-select form-select-sm">
                                                                <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                                foreach ($ops as $opName => $op) {
                                                                    $op_sel = ($row['goods_json']['opr'] ?? '') == $op ? 'selected' : '';
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
                                                            <input type="text" value="<?php echo $row['goods_json']['tax_percent']; ?>"
                                                                name="<?= $l_ID ?>_tax_percent"
                                                                class="form-control tax_percent form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="tax_amount">Tax.Amt</label>
                                                        <div class="col-sm">
                                                            <input type="text" value="<?php echo $row['goods_json']['tax_amount']; ?>"
                                                                name="<?= $l_ID ?>_tax_amount"
                                                                class="form-control tax_amount form-control-sm" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <!-- <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="total_with_tax">Amt+Tax</label> -->
                                                        <div class="col-sm">
                                                            <input type="hidden" value="<?php echo $row['goods_json']['total_with_tax']; ?>"
                                                                name="<?= $l_ID ?>_total_with_tax" class="total_with_tax">
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
                                                echo '<tr><th class="fw-normal">TOTAL KGs </th><th><span class="total_kgs_span">' . ($dataType === 'Original' ? $row["quantity_no"] : $row['goods_json']['total_kgs']) . '</span></th></tr>';
                                                echo '<tr><th class="fw-normal">TOTAL QTY KGs </th><th><span class="total_qty_kgs_span"></span></th></tr>';
                                                echo '<tr><th class="fw-normal">NET KGs </th><th><span class="net_kgs_span">' . ($dataType === 'Original' ? $row["quantity_no"] : $row['goods_json']['net_kgs']) . '</span></th></tr>';
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
                                        <input value="<?= !isset($row['edited']) ? $row["quantity_no"] : $row['goods_json']['total_kgs'] ?>" class="total_kgs"
                                            name="<?= $l_ID ?>_total_kgs" type="hidden">
                                        <input value="<?php echo $row['goods_json']['total_qty_kgs']; ?>"
                                            name="<?= $l_ID ?>_total_qty_kgs" class="total_qty_kgs"
                                            type="hidden">
                                        <input value="<?= !isset($row['edited']) ? $row["quantity_no"] : $row['goods_json']['net_kgs'] ?>" name="<?= $l_ID ?>_net_kgs"
                                            type="hidden" class="net_kgs">
                                        <input value="<?php echo $row['goods_json']['total']; ?>" name="<?= $l_ID ?>_total"
                                            type="hidden" class="total">
                                        <input value="<?php echo $row['goods_json']['amount']; ?>" name="<?= $l_ID ?>_amount"
                                            type="hidden" class="amount">
                                        <input value="<?php echo $row['goods_json']['final_amount']; ?>"
                                            name="<?= $l_ID ?>_final_amount" type="hidden" class="final_amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-3">
                            <strong>
                                <?php
                                $needle = '';
                                if (isset($row['agent']['purchased_in'])) {
                                    $needle = 'purchased_in';
                                } elseif (isset($row['agent']['sold_to'])) {
                                    $needle = 'sold_to';
                                }
                                if (!empty($needle)) {
                                    foreach ($row['agent'][$needle] as $p) {
                                        $data = explode('~', $p);
                                        echo 'P#' . decode_unique_code($data[0], 'TID') . ' => ' . $data[3] . '(' . $data[4] . ') ' . $data[5] . ' ' . $data[6] . ' ' . $data[7] . "<br>";
                                    }
                                }
                                ?>
                            </strong>
                        </div>
                        <?php $myAgent = $row['agent']; ?>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-primary">Agent Form ( <small style="font-size: 14px;"><b>Agent: </b> <?= $myAgent['ag_name'] ?? 'Not Set'; ?> | <b>Acc: </b> <?= $myAgent['ag_acc_no'] ?? 'Not Set'; ?> </small> )</h5>
                                <div class="row g-3">
                                    <!-- BOE DATE -->
                                    <div class="col-md-2">
                                        <label for="boe_date" class="form-label">BOE Date</label>
                                        <input type="date" name="<?= $l_ID ?>_boe_date" id="boe_date"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['boe_date']) ? $myAgent['boe_date'] : ''; ?>">
                                    </div>

                                    <!-- PICK UP DATE -->
                                    <div class="col-md-2">
                                        <label for="pick_up_date" class="form-label">Pick Up Date</label>
                                        <input type="date" name="<?= $l_ID ?>_pick_up_date" id="pick_up_date"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['pick_up_date']) ? $myAgent['pick_up_date'] : ''; ?>">
                                    </div>

                                    <!-- WAITING IF ANY -->
                                    <div class="col-md-2">
                                        <label for="waiting_days" class="form-label">Waiting (days)</label>
                                        <input type="text" name="<?= $l_ID ?>_waiting_days" id="waiting_days"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['waiting_days']) ? $myAgent['waiting_days'] : ''; ?>">
                                    </div>

                                    <!-- RETURN DATE -->
                                    <div class="col-md-2">
                                        <label for="return_date" class="form-label">Return Date</label>
                                        <input type="date" name="<?= $l_ID ?>_return_date" id="return_date"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['return_date']) ? $myAgent['return_date'] : ''; ?>">
                                    </div>

                                    <!-- TRANSPORTER NAME -->
                                    <div class="col-md-3">
                                        <label for="transporter_name" class="form-label">Transporter Name</label>
                                        <input type="text" name="<?= $l_ID ?>_transporter_name" id="transporter_name"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['transporter_name']) ? $myAgent['transporter_name'] : ''; ?>">
                                    </div>

                                    <!-- TRUCK NUMBER -->
                                    <div class="col-md-2">
                                        <label for="truck_number" class="form-label">Truck Number</label>
                                        <input type="text" name="<?= $l_ID ?>_truck_number" id="truck_number"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['truck_number']) ? $myAgent['truck_number'] : ''; ?>">
                                    </div>

                                    <!-- DETAILS -->
                                    <div class="col-md-4">
                                        <label for="details" class="form-label">Details</label>
                                        <input type="text" name="<?= $l_ID ?>_details" id="details"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['details']) ? $myAgent['details'] : ''; ?>">
                                    </div>

                                    <!-- DRIVER NAME -->
                                    <div class="col-md-3">
                                        <label for="driver_name" class="form-label">Driver Name</label>
                                        <input type="text" name="<?= $l_ID ?>_driver_name" id="driver_name"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['driver_name']) ? $myAgent['driver_name'] : ''; ?>">
                                    </div>

                                    <!-- DRIVER NUMBER -->
                                    <div class="col-md-3">
                                        <label for="driver_number" class="form-label">Driver Number</label>
                                        <input type="number" name="<?= $l_ID ?>_driver_number" id="driver_number"
                                            class="form-control form-control-sm"
                                            value="<?= isset($myAgent['driver_number']) ? $myAgent['driver_number'] : ''; ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

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
                <small><a href="?delete=<?= base64_encode('DELETE ME!'); ?>&unique_code=<?= $unique_code; ?>" class="fw-bold text-danger"><i class="fa fa-trash-alt"></i> Delete</a></small><br>
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
    $('#print_type').change(function() {
        window.location.href = '?view=1&unique_code=<?= $unique_code; ?>&print_type=' + $(this).val();
    });
</script>
<!-- JavaScript -->
<script>
    let selectedEntry = null;
    let saleQtyValue = null;

    function currentStock(event) {
        if (saleQtyValue !== null) {
            const selectedWarehouse = $('#warehouse_transfer').val() ?? '';
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
                            Object.entries(data).forEach(([uniqueCode, entries]) => {
                                Object.entries(entries).forEach(([randomId, entry]) => {
                                    entriesHtml += `
                            <tr>
                                <td>
                                    <input type="radio" name="warehouseEntry" value="${uniqueCode}~${randomId}~${entry.goods_id}~${entry.goods_name}~${entry.quantity_no}~${entry.quantity_name}~${entry.gross_weight}~${entry.net_weight}" />
                                </td>
                                <td class="d-none">P#${entry.p_id} (${entry.sr_no}) => ${entry.goods_name} (${entry.quantity_no}) ${entry.quantity_name}</sub></td>
                                <td>P#${entry.p_id} (${entry.sr_no})</td>
                                <td>${entry.allot}</td>
                                <td>${entry.goods_name}</td>
                                <td>${entry.size}</td>
                                <td>${entry.brand}</td>
                                <td>${entry.origin}</td>
                                <td><span class="ajax-qty">${entry.quantity_no}</span> <sub>${entry.quantity_name}</sub></td>
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
            alert("Please Select a entry first!");
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
                finalAmount(entryForm);
            } else {
                activeId = null;
            }
        });
    });

    $(document).ready(function() {
        $(document).on('keyup', '#goodsDiv input', function() {
            finalAmount($(this).closest('.entryform'));
        });

        $(document).on('change', '#goodsDiv select', function() {
            finalAmount($(this).closest('.entryform'));
        });
        $('.row-checkbox').change(function() {
            let selectedValues = [];
            $('.row-checkbox:checked').each(function() {
                let checkbox = $(this);
                selectedValues.push(checkbox.val());
                saleQtyValue = parseFloat($('.entryform' + checkbox.val() + ' .qty_no').val());
            });
            $('#transfer_to_warehouse_ids').val(selectedValues.join(','));
        });

    });

    function finalAmount(entryForm) {
        var qty_no = parseFloat(entryForm.find(".qty_no").val()) || 0;
        var qty_kgs = parseFloat(entryForm.find(".qty_kgs").val()) || 0;

        var total_kgs = qty_no * qty_kgs;
        entryForm.find(".total_kgs").val(total_kgs.toFixed(2));
        entryForm.find(".total_kgs_span").text(total_kgs.toFixed(2));

        var empty_kgs = parseFloat(entryForm.find(".empty_kgs").val()) || 0;
        var total_qty_kgs = qty_no * empty_kgs;
        entryForm.find(".total_qty_kgs").val(total_qty_kgs.toFixed(2));
        entryForm.find(".total_qty_kgs_span").text(total_qty_kgs.toFixed(2));

        var net_kgs = total_kgs - total_qty_kgs;
        entryForm.find(".net_kgs").val(net_kgs.toFixed(2));
        entryForm.find(".net_kgs_span").text(net_kgs.toFixed(2));

        var weight = parseFloat(entryForm.find(".weight").val()) || 0;
        var total = 0;

        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = total.toFixed(3);
        }

        entryForm.find(".total").val(isNaN(total) ? '' : total);
        entryForm.find(".total_span").text(isNaN(total) ? '' : total);

        var rate1 = parseFloat(entryForm.find(".rate1").val()) || 0;
        // var amount = (isNaN(total) || total === 0) ? 0 : (total * rate1).toFixed(3);
        var final_amount = 0;
        var amount = 0;
        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
            amount = total * rate1;
            amount = amount.toFixed(3);
            final_amount = amount;
        }

        entryForm.find(".amount").val(isNaN(amount) ? '' : amount);
        entryForm.find(".amount_span").text(isNaN(amount) ? '' : amount);

        updateTaxAndTotal(entryForm);

        var rate2 = parseFloat(entryForm.find(".rate2").val()) || 0;
        let operator = entryForm.find('.opr').find(":selected").val();
        if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
            final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
            final_amount = parseFloat(final_amount.toFixed(3));
        }

        entryForm.find(".final_amount").val(isFinite(final_amount) ? final_amount : '');
        entryForm.find(".final_amount_span").text(isFinite(final_amount) ? final_amount : '');

        if (isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('reSubmit');
        } else {
            enableButton('reSubmit');
        }
    }


    function updateTaxAndTotal(entryForm) {
        let amount = parseFloat(entryForm.find('.amount_span').text()) || 0;
        let taxPercent = parseFloat(entryForm.find('.tax_percent').val()) || 0;
        let taxAmount = (amount * (taxPercent / 100)).toFixed(2);
        let totalWithTax = (amount + parseFloat(taxAmount)).toFixed(2);
        entryForm.find('.tax_amount').val(taxAmount != 0 ? taxAmount : '');
        entryForm.find('.total_with_tax').val(totalWithTax);
        entryForm.find('.total_with_tax_span').text(totalWithTax);
    }
    $(document).ready(function() {
        // Show form section on pencil icon click
        $('#editButton').click(function() {
            $('#summarySection').hide(); // Hide summary
            $('#formSection').show(); // Show form section
        });

        // Show summary section on cross icon click
        $('#closeButton').click(function() {
            $('#formSection').hide(); // Hide form section
            $('#summarySection').show(); // Show summary
        });
    });
</script>
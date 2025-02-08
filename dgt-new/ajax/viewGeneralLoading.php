<?php require_once '../connection.php';
$id = $_POST['id'];

if ($id > 0) {
    $update = isset($_POST['edit']) && !empty($_POST['edit']) ? true : false;
    $record = mysqli_fetch_assoc(fetch('transactions', array('id' => $id)));
    $T = array_merge(
        transactionSingle($id),
        [
            'sea_road_array' => json_decode($record['sea_road'] ?? '[]', true),
            'notify_party' => json_decode($record['notify_party_details'] ?? '[]', true)
        ],
    );
    $stmt = $connect->prepare("SELECT * FROM general_loading WHERE t_id = ? ORDER BY created_at");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $allBlRows = $result->fetch_all(MYSQLI_ASSOC);
    $loadedTotals = [
        'quantity_no' => 0,
        'gross_weight' => 0,
        'net_weight' => 0,
    ];
    $TransactionTotals = [
        'quantity_no' => 0,
        'gross_weight' => 0,
        'net_weight' => 0,
    ];
    $rowsExist = false;
    $loadedGoodsQuantities = $loadedGoods = $quantityPerGood = $uniqueBlNumbers = [];
    $lastEntrySr = $activeBl = null;
    if (!empty($allBlRows)) {
        $rowsExist = true;
        $uniqueBlNumbers = array_unique(array_column($allBlRows, 'bl_no'));
        foreach ($allBlRows as $oneBl) {
            $Goods = json_decode($oneBl['goods_info'], true);
            if (json_decode($oneBl['loading_info'], true)['active'] === true) {
                $activeBl = $oneBl;
                $activeBl['loading_info'] = json_decode($oneBl['loading_info'], true);
                $activeBl['goods_info'] = json_decode($oneBl['goods_info'], true);
            }
            foreach ($Goods as $GKey => $oneGood) {
                if ($_POST['edit'] === $GKey) {
                    $updateRow = $oneBl;
                    $updateRow['loading_info'] = json_decode($oneBl['loading_info'], true);
                    $updateRow['good_info'] = $oneGood;
                    $updateRow['sr'] = $oneGood['sr'];
                    $activeBl = $updateRow;
                }
                $loadedGoods[$oneBl['bl_no'] . '-' . $oneGood['sr']] = $oneGood;
                $loadedTotals['quantity_no'] += (float)$oneGood['quantity_no'];
                $loadedTotals['gross_weight'] += (float)$oneGood['gross_weight'] ?? 0;
                $loadedTotals['net_weight'] += (float)$oneGood['net_weight'] ?? 0;
                if (!isset($quantityPerGood[$oneGood['good']['goods_id'] . '~' . $oneGood['good']['size'] . '~' . $oneGood['good']['brand'] . '~' . $oneGood['good']['origin'] . '~' . $oneGood['good']['qty_name']])) {
                    $quantityPerGood[$oneGood['good']['goods_id'] . '~' . $oneGood['good']['size'] . '~' . $oneGood['good']['brand'] . '~' . $oneGood['good']['origin'] . '~' . $oneGood['good']['qty_name']] = 0;
                }
                $quantityPerGood[$oneGood['good']['goods_id'] . '~' . $oneGood['good']['size'] . '~' . $oneGood['good']['brand'] . '~' . $oneGood['good']['origin'] . '~' . $oneGood['good']['qty_name']] += (float)$oneGood['quantity_no'];
                $lastEntrySr = $oneGood['sr'];
            }
        }
    }
    if ($lastEntrySr !== null) {
        $CurrentSr = $update ? $updateRow['sr'] : $lastEntrySr + 1;
    } else {
        $CurrentSr = 1;
    }
    foreach ($T['items'] as $item) {
        $TransactionTotals['quantity_no'] += $item['qty_no'];
        $TransactionTotals['gross_weight'] += $item['total_kgs'];
        $TransactionTotals['net_weight'] += $item['net_kgs'];
    }
    $remainingTotals = [
        'quantity_no' => $TransactionTotals['quantity_no'] - $loadedTotals['quantity_no'],
        'gross_weight' => $TransactionTotals['gross_weight'] - $loadedTotals['gross_weight'],
        'net_weight' => $TransactionTotals['net_weight'] - $loadedTotals['net_weight'],
    ];
    $ShippingText = ($T['sea_road'] === 'sea') ? 'Shipping' : 'Transporter';
?>
    <div class="modal-header d-flex justify-content-between bg-white align-items-center">
        <h5 class="modal-title" id="staticBackdropLabel">GENERAL LOADING</h5>
        <div class="d-flex align-items-center gap-2">
            <?php if ($rowsExist): ?>
                <div class="d-flex gap-2 align-items-center">
                    <label for="blSearch" style="text-wrap:nowrap;">B/L No Print </label>
                    <select name="blSearch" id="blSearch" class="form-select form-select-sm">
                        <option value="">Select B/L No</option>
                        <?php foreach ($uniqueBlNumbers as $onebl): ?>
                            <option value="<?= $onebl; ?>"><?= $onebl; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a href="#" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2 disabled">PRINT</a>
                </div>
            <?php endif; ?>

            <button class="btn btn-warning btn-sm" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
            <!-- Close Button -->
            <a href="general-loading" class="btn-close ms-3" aria-label="Close"></a>
        </div>
    </div>
    <style>
        #bl_suggestions {
            z-index: 1000;
            background-color: white;
            border: 1px solid #ddd;
        }

        #bl_suggestions .list-group-item {
            cursor: pointer;
        }

        #bl_suggestions .list-group-item:hover {
            background-color: #f0f0f0;
        }
    </style>
    <div class="row pt-2">
        <div class="col-md-10 bg-white rounded border">
            <?php if (!empty($T['sea_road_array'])) { ?>
                <div class="row gy-1 border-bottom py-1">
                    <div class="col-md-12">
                        <span class="fw-bold">By </span>
                        <?= $T['sea_road']; ?>
                    </div>
                    <div class="col-md-3">
                        <div class="fw-bold">Loading Details</div>
                        <div>
                            <?php
                            foreach ($T['sea_road_array'] as $key => $value) {
                                if (strpos($key, 'l_') === 0) {
                                    if (is_array($value)) {
                                        echo '<b style="font-size:13px;">' . str_replace(['l_', '_road', '_'], ['', '', ' '], $value[0]) . ':</b> ' . $value[1] . '<br>';
                                    } else {
                                        echo '<b style="font-size:13px;">' . strtoupper(str_replace(['l_', '_road', '_'], ['', '', ' '], $key)) . ':</b> ' . $value . '<br>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="fw-bold">Receiving Details</div>
                        <?php foreach ($T['sea_road_array'] as $key => $value): ?>
                            <?php
                            if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0):
                                if (is_array($value)) {
                                    echo '<b style="font-size:13px;">' . str_replace(['d_', '_road', 'r_', '_'], ['', '', '', ' '], $value[0]) . ':</b> ' . $value[1] . '<br>';
                                } else {
                                    echo '<b style="font-size:13px;">' . strtoupper(str_replace(['d_', '_road', 'r_', '_'], ['', '', '', ' '], $key)) . ':</b> ' . $value . '<br>';
                                }
                            endif;
                            ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="fw-bold">Goods Calculations</div>
                        <div class="d-flex gap-2">
                            <div>
                                <b style="font-size:13px;">Goods Qty: <span class="text-success"><?= $TransactionTotals['quantity_no']; ?></span></b><br>
                                <b style="font-size:13px;">Loaded Qty: <span class="text-danger"><?= $loadedTotals['quantity_no']; ?></span></b><br>
                                <hr class="my-1" style="width:130px;">
                                <b style="font-size:13px;">Remaining: <span><?= $remainingTotals['quantity_no']; ?></span></b>
                            </div>
                            <div>
                                <b style="font-size:13px;">G.Weight: <span class="text-success"><?= $TransactionTotals['gross_weight']; ?></span></b><br>
                                <b style="font-size:13px;">Loaded G.W: <span class="text-danger"><?= $loadedTotals['gross_weight']; ?></span></b><br>
                                <hr class="my-1" style="width:130px;">
                                <b style="font-size:13px;">Remaining: <span><?= $remainingTotals['gross_weight']; ?></span></b>
                            </div>
                            <div>
                                <b style="font-size:13px;">N.Weight: <span class="text-success"><?= $TransactionTotals['net_weight']; ?></span></b><br>
                                <b style="font-size:13px;">Loaded N.W: <span class="text-danger"><?= $loadedTotals['net_weight']; ?></span></b><br>
                                <hr class="my-1" style="width:130px;">
                                <b style="font-size:13px;">Remaining: <span><?= $remainingTotals['net_weight']; ?></span></b>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($T['items'])) { ?>
                <table class="table mt-2 table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Goods</th>
                            <th>SIZE</th>
                            <th>BRAND</th>
                            <th>ORIGIN</th>
                            <th>Qty</th>
                            <th>Total KGs</th>
                            <th>Net KGs</th>
                        </tr>
                    </thead>
                    <tbody id="goodsTable">
                        <?php $items = $T['items'];
                        $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                        $i = 0;
                        $rate = 0;
                        foreach ($items as $details) {
                            echo '<tr class="goodRow" data-goodsjson=\'' . json_encode($details) . '\'>';
                            echo '<td>' . $details['sr'] . '</td>';
                            echo '<td>' . goodsName($details['goods_id']) . '</td>';
                            echo '<td>' . $details['size'] . '</td>';
                            echo '<td>' . $details['brand'] . '</td>';
                            echo '<td>' . $details['origin'] . '</td>';
                            echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                            echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                            echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                            echo '</tr>';
                            $qty_no += $details['qty_no'];
                            $total_kgs += $details['total_kgs'];
                            $net_kgs += $details['net_kgs'];
                        }
                        if ($qty_no > 0) {
                            echo '<tr>
                                            <th colspan="5"></th>
                                            <th class="fw-bold text-success">' . $qty_no . '</th>
                                            <th class="fw-bold text-success">' . round($total_kgs, 2) . '</th>
                                            <th class="fw-bold text-success">' . round($net_kgs, 2) . '</th>
                                        </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            <?php }
            if (!empty($loadedGoods)) {
            ?>
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
                            <th class="bg-dark text-white">L.DATE</th>
                            <th class="bg-dark text-white">L.<?= $T['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?></th>
                            <th class="bg-dark text-white">R.DATE</th>
                            <th class="bg-dark text-white">R.<?= $T['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?></th>
                            <th class="bg-dark text-white">FILE</th>
                        </tr>
                    </thead>
                    <tbody class="loadingsTable">
                        <?php
                        foreach ($allBlRows as $oneBl) {
                            $G = json_decode($oneBl['goods_info'], true);
                            $L = json_decode($oneBl['loading_info'], true);
                            foreach ($G as $GSr => $oneG) { ?>
                                <tr class="LoadingRow">
                                    <td class="border sr_no border-dark"><a href="general-loading?t_id=<?= $id; ?>&view=1&edit=<?= $oneBl['bl_no'] . '-' . $oneG['sr']; ?>">#<?= $oneG['sr']; ?></a></td>
                                    <td class="border border-dark"><?= $oneG['container_no'] ?? ''; ?></td>
                                    <td class="border border-dark"><?= $oneBl['bl_no']; ?></td>
                                    <td class="border border-dark"><?= goodsName($oneG['good']['goods_id']); ?></td>
                                    <td class="border border-dark"><?= $oneG['quantity_name']; ?></td>
                                    <td class="border quantity_no border-dark"><?= $oneG['quantity_no']; ?></td>
                                    <td class="border border-dark"><?= $oneG['gross_weight']; ?></td>
                                    <td class="border border-dark"><?= $oneG['net_weight']; ?></td>
                                    <td class="border border-dark"><?= $L['loading']['loading_date']; ?></td>
                                    <td class="border border-dark"><?= $L['loading']['loading_port_name']; ?></td>
                                    <td class="border border-dark"><?= $L['receiving']['receiving_date']; ?></td>
                                    <td class="border border-dark"><?= $L['receiving']['receiving_port_name']; ?></td>
                                    <td class="border border-dark text-success" style="position: relative;">
                                        <?php
                                        $attachments = $L['attachments'] ?? [];
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
                        }; ?>
                        <tr>
                            <th colspan="5"></th>
                            <th class="fw-bold"><?= $loadedTotals['quantity_no']; ?></th>
                            <th class="fw-bold"><?= $loadedTotals['gross_weight']; ?></th>
                            <th class="fw-bold"><?= $loadedTotals['net_weight']; ?></th>
                            <th colspan="6"></th>
                        </tr>
                    </tbody>
                </table>
            <?php
            }
            ?>
            <div class="transfer-form d-none">
                <form method="post" class="table-form <?= $update ? 'border border-danger p-2' : ''; ?>" onsubmit="return compareValues()" enctype="multipart/form-data">
                    <div style="width:100%; display: flex; justify-content: space-between; margin-bottom: 2px;">
                        <h5 class="text-primary">General Information</h5>
                    </div>
                    <span><b>Date Today: </b><?= my_date(date('Y-m-d')); ?></span>
                    <div class="row g-3 mt-2">
                        <div class="col-md-1">
                            <label for="sr" class="form-label">Sr#</label>
                            <input type="number" name="sr" id="sr" required readonly class="form-control form-control-sm" value="<?= $CurrentSr; ?>">
                        </div>
                        <div class="col-md-2 position-relative">
                            <label for="bl_no" class="form-label">B/L No</label>
                            <input type="text" name="bl_no" id="bl_no" onkeyup="GetBLSuggestions()" required value="<?= $activeBl['bl_no'] ?? ''; ?>" class="form-control form-control-sm">
                            <ul id="bl_suggestions" class="list-group position-absolute w-100" style="display:none; max-height: 200px; overflow-y: auto;"></ul>
                        </div>
                        <div class="col-md-2">
                            <label for="loading_date" class="form-label">Loading Date</label>
                            <input type="date" name="loading_date" id="loading_date" value="<?= $activeBl['loading_info']['loading']['loading_date'] ?? ''; ?>" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="receiving_date" class="form-label">Receiving Date</label>
                            <input type="date" name="receiving_date" id="receiving_date" value="<?= $activeBl['loading_info']['receiving']['receiving_date'] ?? ''; ?>" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="loading_country" class="form-label">Loading Country</label>
                            <input type="text" name="loading_country" id="loading_country" value="<?= $activeBl['loading_info']['loading']['loading_country'] ?? ''; ?>" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="loading_port_name" class="form-label">L <?= $T['sea_road'] === 'sea' ? 'PORT' : 'BORDER' ?? ''; ?> Name</label>
                            <input type="text" name="loading_port_name" id="loading_port_name" value="<?= $activeBl['loading_info']['loading']['loading_port_name'] ?? ''; ?>" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="receiving_country" class="form-label">Receiving Country</label>
                            <input type="text" name="receiving_country" id="receiving_country" value="<?= $activeBl['loading_info']['receiving']['receiving_country'] ?? ''; ?>" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="receiving_port_name" class="form-label">R <?= $T['sea_road'] === 'sea' ? 'PORT' : 'BORDER' ?? ''; ?> Name</label>
                            <input type="text" name="receiving_port_name" id="receiving_port_name" value="<?= $activeBl['loading_info']['receiving']['receiving_port_name'] ?? ''; ?>" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-5">
                            <label for="report" class="form-label">Report</label>
                            <input type="text" name="report" id="report" required value="<?= $activeBl['loading_info']['report'] ?? ''; ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="shipping_name" class="form-label"><?= $ShippingText ?? ''; ?> Name</label>
                            <input type="text" name="shipping_name" value="<?= $activeBl['loading_info']['shipping']['shipping_name'] ?? ''; ?>" id="shipping_name" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label for="shipping_address" class="form-label"><?= $ShippingText ?? ''; ?> Address</label>
                            <input type="text" name="shipping_address" value="<?= $activeBl['loading_info']['shipping']['shipping_address'] ?? ''; ?>" id="shipping_address" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="shipping_phone" class="form-label"><?= $ShippingText ?? ''; ?> Phone</label>
                            <input type="tel" name="shipping_phone" value="<?= $activeBl['loading_info']['shipping']['shipping_phone'] ?? ''; ?>" id="shipping_phone" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="shipping_whatsapp" class="form-label"><?= $ShippingText ?? ''; ?> WhatsApp</label>
                            <input type="tel" name="shipping_whatsapp" value="<?= $activeBl['loading_info']['shipping']['shipping_whatsapp'] ?? ''; ?>" id="shipping_whatsapp" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="shipping_email" class="form-label"><?= $ShippingText ?? ''; ?> Email</label>
                            <input type="email" name="shipping_email" value="<?= $activeBl['loading_info']['shipping']['shipping_email'] ?? ''; ?>" id="shipping_email" required class="form-control form-control-sm">
                        </div>
                        <input type="hidden" name="transfer_by" value="<?= $T['sea_road']; ?>">
                    </div>
                    <h5 class="text-primary mt-4">Importer, Notify Party, and Exporter Details</h5>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="im_acc_no" class="form-label fw-bold">Importer Details</label>
                            <div class="input-group">
                                <input type="hidden" name="im_acc_id" value="<?= $activeBl['loading_info']['importer']['im_acc_id'] ?? ''; ?>" id="im_acc_id">
                                <input type="text" id="im_acc_no" name="im_acc_no" class="form-control form-control-sm w-25"
                                    placeholder="ACC No" value="<?= $activeBl['loading_info']['importer']['im_acc_no'] ?? ''; ?>">
                                <input type="text" id="im_acc_name" name="im_acc_name" class="form-control form-control-sm w-75"
                                    placeholder="Importer Name" value="<?= $activeBl['loading_info']['importer']['im_acc_name'] ?? ''; ?>">
                            </div>
                            <select class="form-select form-select-sm mt-2" name="im_acc_kd_id" id="im_acc_kd_id">
                                <option hidden value="">Select Company</option>
                                <?php $run_query = fetch('khaata_details', array('khaata_id' => $activeBl['loading_info']['importer']['im_acc_id'] ?? '', 'type' => 'company'));
                                while ($row = mysqli_fetch_array($run_query)) {
                                    $row_data = json_decode($row['json_data']);
                                    $sel_kd2 = $row['id'] == $activeBl['loading_info']['importer']['im_acc_kd_id'] ? 'selected' : '';
                                    echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                } ?>
                            </select>
                            <textarea class="form-control form-control-sm mt-2" name="im_acc_details" id="im_acc_details" rows="2"
                                placeholder="Company Details"><?= $activeBl['loading_info']['importer']['im_acc_details'] ?? ''; ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="np_acc_no" class="form-label fw-bold">Notify Party Details</label>
                            <div class="input-group">
                                <input type="hidden" name="np_acc_id" value="<?= $activeBl['loading_info']['notify']['np_acc_id'] ?? ''; ?>" id="np_acc_id">
                                <input type="text" id="np_acc_no" name="np_acc_no" class="form-control form-control-sm w-25"
                                    placeholder="ACC No" value="<?= $activeBl['loading_info']['notify']['np_acc_no'] ?? ''; ?>">
                                <input type="text" id="np_acc_name" name="np_acc_name" class="form-control form-control-sm w-75"
                                    placeholder="Notify Party Name" value="<?= $activeBl['loading_info']['notify']['np_acc_name'] ?? ''; ?>">
                            </div>
                            <select class="form-select form-select-sm mt-2" name="np_acc_kd_id" id="np_acc_kd_id">
                                <option hidden value="">Select Company</option>
                                <?php $run_query = fetch('khaata_details', array('khaata_id' => $activeBl['loading_info']['notify']['np_acc_id'] ?? '', 'type' => 'company'));
                                while ($row = mysqli_fetch_array($run_query)) {
                                    $row_data = json_decode($row['json_data']);
                                    $sel_kd2 = $row['id'] == $activeBl['loading_info']['notify']['np_acc_kd_id'] ? 'selected' : '';
                                    echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                } ?>
                            </select>
                            <textarea class="form-control form-control-sm mt-2" name="np_acc_details" id="np_acc_details" rows="2"
                                placeholder="Company Details"><?= $activeBl['loading_info']['notify']['np_acc_details'] ?? ''; ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="xp_acc_no" class="form-label fw-bold">Exporter Details</label>
                            <div class="input-group">
                                <input type="hidden" name="xp_acc_id" value="<?= $activeBl['loading_info']['exporter']['xp_acc_id'] ?? ''; ?>" id="xp_acc_id">
                                <input type="text" id="xp_acc_no" name="xp_acc_no" class="form-control form-control-sm w-25"
                                    placeholder="ACC No" value="<?= $activeBl['loading_info']['exporter']['xp_acc_no'] ?? ''; ?>">
                                <input type="text" id="xp_acc_name" name="xp_acc_name" class="form-control form-control-sm w-75"
                                    placeholder="Exporter Name" value="<?= $activeBl['loading_info']['exporter']['xp_acc_name'] ?? ''; ?>">
                            </div>
                            <select class="form-select form-select-sm mt-2" name="xp_acc_kd_id" id="xp_acc_kd_id">
                                <option hidden value="">Select Company</option>
                                <?php $run_query = fetch('khaata_details', array('khaata_id' => $activeBl['loading_info']['exporter']['xp_acc_id'] ?? '', 'type' => 'company'));
                                while ($row = mysqli_fetch_array($run_query)) {
                                    $row_data = json_decode($row['json_data']);
                                    $sel_kd2 = $row['id'] == $activeBl['loading_info']['exporter']['xp_acc_kd_id'] ? 'selected' : '';
                                    echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                } ?>
                            </select>
                            <textarea class="form-control form-control-sm mt-2" name="xp_acc_details" id="xp_acc_details" rows="2"
                                placeholder="Company Details"><?= $activeBl['loading_info']['exporter']['xp_acc_details'] ?? ''; ?></textarea>
                        </div>
                    </div>
                    <h5 class="text-primary mt-4">Goods Details</h5>
                    <hr>
                    <div class="row g-3">
                        <input type="hidden" name="loaded_quantity" id="loaded_quantity" value='<?= json_encode($quantityPerGood ?? []); ?>'>
                        <input type="hidden" name="existing_goods" id="existing_goods" value='<?= json_encode($activeBl ? $activeBl['goods_info'] : []); ?>'>
                        <?php if ($update) {
                            echo '<input name="updateGood" type="hidden" value=\'' . json_encode($updateRow['good_info']) . '\'/>';
                        } ?>
                        <div class="col-md-4">
                            <label for="select_good" class="form-label">Select Good</label>
                            <select id="select_good" name="select_good" class="form-select" required>
                                <option value="">Select</option>
                                <?php
                                foreach ($T['items'] as $item) {
                                    $G = $updateRow['good_info']['good']['id'] ?? '';
                                    $selected = $item['id'] == $G ? 'selected' : '';
                                    echo '<option ' . $selected . ' value=\'' . json_encode($item) . '\'>' . $item['sr'] . '. ' . goodsName($item['goods_id']) . ' / ' . $item['size'] . ' / ' . $item['brand'] . ' / ' . $item['origin'] . ' </option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="quantity_name" class="form-label">Qty Name</label>
                            <input type="text" name="quantity_name" value="<?= $updateRow['good_info']['quantity_name'] ?? ''; ?>" id="quantity_name" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="quantity_no" class="form-label">Qty No</label>
                            <input type="text" name="quantity_no" value="<?= $updateRow['good_info']['quantity_no'] ?? ''; ?>" id="quantity_no" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="gross_weight" class="form-label">G.Weight</label>
                            <input type="text" name="gross_weight" value="<?= $updateRow['good_info']['gross_weight'] ?? ''; ?>" id="gross_weight" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="net_weight" class="form-label">N.Weight</label>
                            <input type="text" name="net_weight" value="<?= $updateRow['good_info']['net_weight'] ?? ''; ?>" id="net_weight" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="container_no" class="form-label">Container No</label>
                            <input type="text" name="container_no" value="<?= $updateRow['good_info']['container_no'] ?? ''; ?>" id="container_no" required class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label for="container_name" class="form-label">Container Name</label>
                            <input type="text" name="container_name" value="<?= $updateRow['good_info']['container_name'] ?? ''; ?>" id="container_name" required class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-md-12 text-end">
                            <input type="hidden" name="t_id" value="<?= $id; ?>">
                            <input type="hidden" name="t_sr" value="<?= $T['sr']; ?>">
                            <input type="hidden" name="p_s" value="<?= $T['p_s']; ?>">
                            <input type="hidden" name="t_type" value="<?= $T['type']; ?>">
                            <input type="hidden" name="update" value="<?= $update; ?>">
                            <?php if ($activeBl) {
                                echo '<input name="activeBl" type="hidden" value="' . $activeBl['bl_no'] . '"/>';
                            } ?>
                            <input type="reset"
                                class="btn btn-warning btn-sm rounded-0" value="Clear Form">
                            <button name="<?= $update ? 'entryUpdate' : 'entryInsert'; ?>" id="entrySubmit" type="submit"
                                class="btn btn-<?= $update ? 'warning' : 'primary'; ?> btn-sm rounded-0">
                                <i class="fa fa-paper-plane"></i> <?= $update ? 'Update' : 'Submit'; ?>
                            </button>
                            <div class="text-danger fw-bold mt-1 d-none show_complete_msg">Remaining Quantity is Now Zero (<?= $remainingTotals['quantity_no']; ?>)</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2 bg-white rounded border">
            <div class="align-items-center justify-content-between flex-wrap pt-2">
                <div>
                    <strong><?php echo strtoupper($T['p_s_name']) . ' #'; ?></strong>
                    <?php echo $T['sr']; ?>
                </div>
                <div>
                    <strong>User:</strong> <?php echo $T['username']; ?>
                </div>
                <div>
                    <strong>Date:</strong> <?php echo my_date($T['_date']); ?>
                </div>
                <div>
                    <strong>Type:</strong> <?php echo badge(strtoupper($T['type']), 'dark'); ?>
                </div>
                <div>
                    <strong>Country:</strong> <?php echo $T['country']; ?>
                </div>
                <div>
                    <strong>Branch:</strong> <?php echo branchName($T['branch_id']); ?>
                </div>
                <div>
                    <strong>Status:</strong>
                    <?php
                    if ($T['locked'] == 0) {
                        echo $T['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                    } else {
                        echo '<i class="fa fa-lock text-success"></i> Transferred.';
                    }
                    ?>
                </div>
            </div>

            <div class="mt-3">
                <div>
                    <strong>Cr. A/c #:</strong> <?php echo $T['dr_acc']; ?><br>
                    <strong>Cr. A/c Name:</strong> <?php echo $T['dr_acc_name']; ?>
                </div>
            </div>

            <div class="mt-2">
                <div>
                    <strong>Dr. A/c #:</strong> <?php echo $T['cr_acc']; ?><br>
                    <strong>Dr. A/c Name:</strong> <?php echo $T['cr_acc_name']; ?>
                </div>
            </div>

            <div class="mt-2">
                <?php if ($T['notify_party']): ?>
                    <div>
                        <strong>NP Acc No.:</strong> <?= $T['notify_party']['np_acc']; ?><br>
                        <strong>Acc Name:</strong> <?php echo $T['notify_party']['np_acc_name']; ?>
                    </div>
                <?php else: ?>
                    <div class="text-danger">Notify Party Details Not Added!</div>
                <?php endif; ?>
            </div>
            <?php if ($activeBl) {
                echo '<a href="?updateActiveBlStatus=close&bl_no=' . $activeBl['bl_no'] . '&t_id=' . $activeBl['t_id'] . '" class="btn btn-success btn-sm mt-2">Transfer BL</a>';
            } ?>
        </div>
    </div>
    <script>
        let qty_kgs = 0,
            empty_kgs = 0;
        <?php if ($update): ?>
            const goodData = <?= json_encode($updateRow['good_info']['good']) ?>;
            qty_kgs = parseFloat(goodData.qty_kgs) || 0;
            empty_kgs = parseFloat(goodData.empty_kgs) || 0;
        <?php endif; ?>
        $(document).ready(function() {
            $('#quantity_no, #good_select').on('input change', function() {
                calculateWeights();
            });
        });

        function calculateWeights() {
            let qty_no = parseFloat($('#quantity_no').val()) || 0;
            let total_kgs = parseFloat(qty_no * qty_kgs).toFixed(2);
            let total_qty_kgs = parseFloat(qty_no * empty_kgs).toFixed(2);
            let net_kgs = parseFloat(total_kgs - total_qty_kgs).toFixed(2);
            $('#gross_weight').val(total_kgs);
            $('#net_weight').val(net_kgs);
        }

        function populateFields(goodSelect) {
            try {
                const loadedQuantity = JSON.parse($('#loaded_quantity').val()) || {};
                const Good = JSON.parse($(goodSelect).val());
                qty_kgs = parseFloat(Good.qty_kgs) || 0;
                empty_kgs = parseFloat(Good.empty_kgs) || 0;
                $('#quantity_name').val(Good.qty_name);
                const loadedQtyKey = `${Good.goods_id}~${Good.size}~${Good.brand}~${Good.origin}~${Good.qty_name}`;
                let remainingQty = parseFloat(Good.qty_no) || 0;
                if (loadedQuantity[loadedQtyKey]) {
                    remainingQty -= parseFloat(loadedQuantity[loadedQtyKey]) || 0;
                }
                $('#quantity_no').val(Math.max(remainingQty, 0));
                calculateWeights();
            } catch (error) {
                console.error("Error processing data:", error);
            }
        }
        $(document).ready(function() {
            $('#im_acc_kd_id').on('change', function() {
                khaataDetailsSingle($(this).val(), 'im_acc_details');
            });
            $('#np_acc_kd_id').on('change', function() {
                khaataDetailsSingle($(this).val(), 'np_acc_details');
            });
            $('#xp_acc_kd_id').on('change', function() {
                khaataDetailsSingle($(this).val(), 'xp_acc_details');
            });

            $('#select_good').on('change', function() {
                populateFields(this);
            });
            if (parseInt('<?= $remainingTotals['quantity_no'] + ($updateRow['good_info']['quantity_no'] ?? 0); ?>') === 0) {
                disableButton('entrySubmit');
                $('.show_complete_msg').removeClass('d-none');
            }

            $('#blSearch').on('change', function() {
                const selectedValue = $(this).val();
                const printButton = $('#printButton');
                if (selectedValue) {
                    printButton
                        .removeClass('disabled')
                        .attr('href', `print/index?secret=<?= base64_encode("bl-no-print"); ?>&blSearch=${selectedValue}`);
                } else {
                    printButton
                        .addClass('disabled')
                        .attr('href', '#')
                        .attr('onclick', 'return false;');
                }
            });
        });

        $(document).on('keyup', "#im_acc_no", function(e) {
            fetchKhaata("#im_acc", "#im_acc_id", "#im_acc_kd_id", "NoDisableBtnRequired");
        });

        $(document).on('keyup', "#np_acc_no", function(e) {
            fetchKhaata("#np_acc", "#np_acc_id", "#np_acc_kd_id", "NoDisableBtnRequired");
        });

        $(document).on('keyup', "#xp_acc_no", function(e) {
            fetchKhaata("#xp_acc", "#xp_acc_id", "#xp_acc_kd_id", "NoDisableBtnRequired");
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
                        $('#' + dropdown_id).html('<option value="" selected hidden>Choose</option>' + html);
                        if (typeof callback === 'function') {
                            callback();
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
<?php
}
?>
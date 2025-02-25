<?php require_once '../connection.php';
$id = $_POST['id'];

if ($id > 0) {
    $update = isset($_POST['edit']) && !empty($_POST['edit']) ? true : false;
    $record = mysqli_fetch_assoc(fetch('transactions', array('id' => $id)));
    $T = array_merge(
        transactionSingle($id),
        [
            'route_info' => json_decode($record['sea_road'] ?? '[]', true),
            'notify_party' => json_decode($record['notify_party_details'] ?? '[]', true)
        ],
    );
    $stmt = $connect->prepare("SELECT * FROM local_loading WHERE t_id = ? ORDER BY created_at");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $allUIDRows = $result->fetch_all(MYSQLI_ASSOC);
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
    $loadedGoodsQuantities = $loadedGoods = $quantityPerGood = $uniqueUIDs = [];
    $lastEntrySr = $activeUID = null;
    if (!empty($allUIDRows)) {
        $rowsExist = true;
        foreach ($allUIDRows as $oneUID) {
            $Goods = json_decode($oneUID['goods_info'], true);
            if (json_decode($oneUID['loading_info'], true)['transferred'] === false) {
                $activeUID = $oneUID;
                $activeUID['loading_info'] = json_decode($oneUID['loading_info'], true);
                $activeUID['goods_info'] = json_decode($oneUID['goods_info'], true);
            }
            if (!empty($Goods)) {
                $uniqueUIDs[] = $oneUID['uid'];
            }
            foreach ($Goods as $GKey => $oneGood) {
                if ($update && $_POST['edit'] === $GKey) {
                    $updateRow = $oneUID;
                    $updateRow['loading_info'] = json_decode($oneUID['loading_info'], true);
                    $updateRow['good_info'] = $oneGood;
                    $updateRow['sr'] = $oneGood['sr'];
                    $activeUID = $updateRow;
                }
                if (!empty($_POST['wTransId'])) {
                    $transId = explode('~', $_POST['wTransId'])[0];
                    if ($oneUID['uid'] !== $transId) {
                        continue;
                    }
                }
                $loadedGoods[$oneUID['uid'] . '~' . $oneGood['sr']] = $oneGood;
                $loadedTotals['quantity_no'] += (float)$oneGood['quantity_no'];
                $loadedTotals['gross_weight'] += (float)$oneGood['gross_weight'] ?? 0;
                $loadedTotals['net_weight'] += (float)$oneGood['net_weight'] ?? 0;

                // Build unique key for quantity per good
                $goodKey = $oneGood['good']['goods_id'] . '~' .
                    $oneGood['good']['size'] . '~' .
                    $oneGood['good']['brand'] . '~' .
                    $oneGood['good']['origin'] . '~' .
                    $oneGood['good']['qty_name'];
                if (!isset($quantityPerGood[$goodKey])) {
                    $quantityPerGood[$goodKey] = 0;
                }
                $quantityPerGood[$goodKey] += (float)$oneGood['quantity_no'];

                // Update the maximum sr if needed
                if ($lastEntrySr === null || $oneGood['sr'] > $lastEntrySr) {
                    $lastEntrySr = $oneGood['sr'];
                }
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
?>
    <div class="modal-header d-flex justify-content-between bg-white align-items-center">
        <h5 class="modal-title" id="staticBackdropLabel">LOCAL LOADING</h5>
        <div class="d-flex align-items-center gap-1">
            <?php if ($rowsExist): ?>
                <form method="GET" class="d-flex align-items-center gap-1">
                    <input type="hidden" name="t_id" value="<?= $id; ?>">
                    <label for="uid_print" class="mb-0">UID</label>
                    <select name="uid" id="uid_print" class="form-select form-select-sm">
                        <option value="">Select UID No</option>
                        <?php foreach ($uniqueUIDs as $oneUID): ?>
                            <option value="<?= $oneUID; ?>"><?= $oneUID; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="transferUID" class="btn btn-success btn-sm text-nowrap">Close UID</button>
                    <button type="submit" name="print" class="btn btn-dark btn-sm">Print</button>
                </form>
            <?php endif; ?>
            <a href="local-loading" class="btn-close ms-3" aria-label="Close"></a>
        </div>
    </div>

    <div class="row pt-2">
        <!-- Main Content: 10 columns -->
        <div class="col-md-10 bg-white rounded border">
            <?php if (!empty($T['route_info'])) { ?>
                <div class="row gy-1 border-bottom my-3">
                    <div class="col-md-4">
                        <span class="fw-bold">By </span>
                        <?= ucfirst($T['route_info']['lwl'] === 'local' ? 'loading' : $T['route_info']['lwl']) ?? ''; ?>
                        <div>
                            <?php
                            foreach ($T['route_info'] as $key => $value) {
                                if (!in_array($key, ['lwl', 'hidden_id', 'seaRoadDetailsSubmit', 'purchaseLocal', 'route'])) {
                                    echo '<b style="font-size:13px;">' . strtoupper(str_replace('_', ' ', $key)) . ':</b> ' . $value . '<br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-5">
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
                        <?php
                        $items = $T['items'];
                        $qty_no = $total_kgs = $net_kgs = 0;
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
            <?php } ?>

            <?php if (!empty($loadedGoods)) { ?>
                <table class="table mt-2 table-hover table-sm">
                    <thead>
                        <tr class="text-nowrap">
                            <?php if (!empty($_POST['wTransId'])) { ?>
                                <th class="bg-dark text-white"><i class="fa fa-circle-o"></i></th>
                            <?php } ?>
                            <th class="bg-dark text-white">Sr#</th>
                            <th class="bg-dark text-white">Warehouse</th>
                            <th class="bg-dark text-white">UID</th>
                            <th class="bg-dark text-white">G.Name</th>
                            <th class="bg-dark text-white">Qty Ne</th>
                            <th class="bg-dark text-white">Qty No</th>
                            <th class="bg-dark text-white">G.W.KGS</th>
                            <th class="bg-dark text-white">N.W.KGS</th>
                            <th class="bg-dark text-white">L.DATE</th>
                            <th class="bg-dark text-white">L Company</th>
                            <th class="bg-dark text-white">R.DATE</th>
                            <th class="bg-dark text-white">R Company</th>
                            <th class="bg-dark text-white">Truck No</th>
                            <th class="bg-dark text-white">Driver Name</th>
                            <th class="bg-dark text-white">FILE</th>
                        </tr>
                    </thead>
                    <tbody class="loadingsTable">
                        <?php
                        foreach ($allUIDRows as $oneUID) {
                            if (!empty($_POST['wTransId'])) {
                                $transId = explode('~', $_POST['wTransId'])[0];
                                if ($oneUID['uid'] !== $transId) {
                                    continue;
                                }
                            }
                            $G = json_decode($oneUID['goods_info'], true);
                            $W = json_decode($oneUID['warehouse_info'], true);
                            $L = json_decode($oneUID['loading_info'], true);
                            foreach ($G as $GSr => $oneG) { ?>
                                <tr class="LoadingRow text-nowrap">
                                    <?php if (!empty($_POST['wTransId'])) { ?>
                                        <td class="border border-dark text-center">
                                            <input type="checkbox" class="row-checkbox" value="<?= $oneUID['uid'] . '~' . $oneG['sr']; ?>">
                                        </td>
                                    <?php } ?>
                                    <td class="border sr_no border-dark">
                                        <a href="local-loading?t_id=<?= $id; ?>&view=1&edit=<?= $oneUID['uid'] . '~' . $oneG['sr']; ?>">
                                            #<?= $oneG['sr']; ?>
                                        </a>
                                    </td>
                                    <td class="border border-dark">
                                        <a href="local-loading?t_id=<?= $id; ?>&view=1&warehouse_trans_id=<?= $oneUID['uid'] . '~' . $oneG['sr']; ?>">
                                            <?= !empty($W[$GSr]['warehouse'])  ? ucwords(str_replace('-', ' ', $W[$GSr]['warehouse'])) : 'Choose'; ?>
                                        </a>
                                    </td>
                                    <td class="border border-dark"><?= $oneUID['uid']; ?></td>
                                    <td class="border border-dark"><?= goodsName($oneG['good']['goods_id']); ?></td>
                                    <td class="border border-dark"><?= $oneG['quantity_name']; ?></td>
                                    <td class="border quantity_no border-dark"><?= $oneG['quantity_no']; ?></td>
                                    <td class="border border-dark"><?= $oneG['gross_weight']; ?></td>
                                    <td class="border border-dark"><?= $oneG['net_weight']; ?></td>
                                    <td class="border border-dark"><?= $L['loading_date']; ?></td>
                                    <td class="border border-dark"><?= $L['loading_company_name']; ?></td>
                                    <td class="border border-dark"><?= $L['receiving_date']; ?></td>
                                    <td class="border border-dark"><?= $L['receiving_company_name']; ?></td>
                                    <td class="border border-dark"><?= $oneG['truck_number'] ?? ''; ?></td>
                                    <td class="border border-dark"><?= $oneG['driver_name'] ?? ''; ?></td>
                                    <td class="border border-dark text-success" style="position: relative;">
                                        <?php
                                        $attachments = json_decode($oneUID['attachments'] ?? '[]', true) ?? [];
                                        if (!empty($attachments)) {
                                            echo '<a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                                                <i class="fa fa-paperclip"></i>
                                              </a>
                                              <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -500%; display: none; z-index: 1000; width: 200px;">';
                                            foreach ($attachments as $item) {
                                                $fileName = htmlspecialchars($item, ENT_QUOTES);
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
                        <tr>
                            <th colspan="5"></th>
                            <?php if (!empty($_POST['wTransId'])) {
                                echo '<th></th>';
                            } ?>
                            <th class="fw-bold"><?= $loadedTotals['quantity_no']; ?></th>
                            <th class="fw-bold"><?= $loadedTotals['gross_weight']; ?></th>
                            <th class="fw-bold"><?= $loadedTotals['net_weight']; ?></th>
                            <th colspan="6"></th>
                        </tr>
                    </tbody>
                </table>
            <?php } ?>
            <div class="transfer-form d-none">
                <?php if (empty($_POST['wTransId'])) { ?>
                    <form method="post" class="table-form <?= $update ? 'border border-danger p-2' : ''; ?>" onsubmit="return compareValues()" enctype="multipart/form-data">
                        <div style="width:100%; display: flex; justify-content: space-between; align-items:center; margin-bottom: 2px;">
                            <h5 class="text-primary"><strong><?= ucfirst($T['route_info']['lwl'] === 'local' ? 'loading' : $T['route_info']['lwl']); ?></strong> Information</h5>
                            <?php if ($update) { ?>
                                <div> <a class="btn btn-sm btn-danger" href="local-loading?t_id=<?= $id; ?>&delete=<?= $_POST['edit']; ?>">Delete</a>
                                    <input type="file" id="entry_file" name="entry_file[]" class="d-none" multiple>
                                    <span class="btn cursor btn-sm btn-success" onclick="document.getElementById('entry_file').click();"><i class="fa fa-paperclip"></i> File</span>
                                </div>
                            <?php } ?>
                        </div>
                        <span><b>Date Today: </b><?= my_date(date('Y-m-d')); ?></span>
                        <div class="row g-3 mt-2">
                            <div class="col-md-1">
                                <label for="sr" class="form-label">Sr#</label>
                                <input type="number" name="sr" id="sr" required readonly class="form-control form-control-sm" value="<?= $CurrentSr; ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="uid" class="form-label">UID</label>
                                <input type="text" name="uid" id="uid" required value="<?= $activeUID['uid'] ?? ''; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="loading_date" class="form-label">Loading Date</label>
                                <input type="date" class="form-control form-control-sm" id="loading_date" name="loading_date"
                                    value="<?= $activeUID['loading_info']['loading_date'] ?? $T['route_info']['loading_date'] ?? ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="receiving_date" class="form-label">Receiving Date</label>
                                <input type="date" class="form-control form-control-sm" id="receiving_date" name="receiving_date"
                                    value="<?= $activeUID['loading_info']['receiving_date'] ?? $T['route_info']['receiving_date'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="loading_company_name" class="form-label">Loading Company</label>
                                <input id="loading_company_name" name="loading_company_name"
                                    value="<?= $activeUID['loading_info']['loading_company_name'] ?? $T['route_info']['loading_company_name'] ?? ''; ?>" type="text"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="receiving_company_name" class="form-label">Receiving Company</label>
                                <input id="receiving_company_name" name="receiving_company_name"
                                    value="<?= $activeUID['loading_info']['receiving_company_name'] ?? $T['route_info']['receiving_company_name'] ?? ''; ?>" type="text"
                                    class="form-control form-control-sm">
                            </div>
                            <?php
                            if ($T['route_info']['lwl'] === 'local') { ?>
                                <div class="col-md-2">
                                    <label for="truck_number" class="form-label">Truck Number</label>
                                    <input id="truck_number" name="truck_number"
                                        value="<?= $activeUID['loading_info']['truck_number'] ?? $T['route_info']['truck_number'] ?? ''; ?>" type="text"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-2">
                                    <label for="truck_name" class="form-label">Truck Name</label>
                                    <input id="truck_name" name="truck_name"
                                        value="<?= $activeUID['loading_info']['truck_name'] ?? $T['route_info']['truck_name'] ?? ''; ?>" type="text"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label for="loading_warehouse" class="form-label">Loading Warehouse</label>
                                    <input id="loading_warehouse" name="loading_warehouse"
                                        value="<?= $activeUID['loading_info']['loading_warehouse'] ?? $T['route_info']['loading_warehouse'] ?? ''; ?>" type="text"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label for="receiving_warehouse" class="form-label">Receiving Warehouse</label>
                                    <input id="receiving_warehouse" name="receiving_warehouse"
                                        value="<?= $activeUID['loading_info']['receiving_warehouse'] ?? $T['route_info']['receiving_warehouse'] ?? ''; ?>" type="text"
                                        class="form-control form-control-sm">
                                </div>
                            <?php } elseif ($T['route_info']['lwl'] === 'warehouse') { ?>
                                <div class="col-md-2">
                                    <label for="transfer_date" class="form-label">Transfer Date</label>
                                    <input type="date" class="form-control form-control-sm" id="transfer_date" name="transfer_date"
                                        value="<?= $activeUID['loading_info']['transfer_date'] ?? $T['route_info']['transfer_date'] ?? ''; ?>">
                                </div>
                            <?php } elseif ($T['route_info']['lwl'] === 'launch') { ?>
                                <div class="col-md-2">
                                    <label for="port_name" class="form-label">PORT NAME</label>
                                    <input type="text" class="form-control form-control-sm" id="port_name" name="port_name"
                                        value="<?= $activeUID['loading_info']['port_name'] ?? $T['route_info']['port_name'] ?? ''; ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="launch_number" class="form-label">Launch Number</label>
                                    <input type="date" class="form-control form-control-sm" id="launch_number" name="launch_number"
                                        value="<?= $activeUID['loading_info']['launch_number'] ?? $T['route_info']['launch_number'] ?? ''; ?>">
                                </div>
                            <?php } ?>
                            <div class="col-md-5">
                                <label for="report" class="form-label">Report</label>
                                <input type="text" name="report" id="report" required value="<?= $activeUID['loading_info']['report'] ?? ''; ?>" class="form-control form-control-sm">
                            </div>
                        </div>
                        <hr>
                        <h5 class="text-primary mt-4">Goods Details</h5>
                        <div class="row g-3">
                            <input type="hidden" name="loaded_quantity" id="loaded_quantity" value='<?= json_encode($quantityPerGood ?? []); ?>'>
                            <input type="hidden" name="existing_goods" id="existing_goods" value='<?= json_encode($activeUID ? $activeUID['goods_info'] : []); ?>'>
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
                        </div>
                        <div class="toggle-transporter d-none">
                            <h5 class="text-primary mt-4">Transporter Details</h5>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="truck_number" class="form-label">Truck Number</label>
                                    <input type="text" name="truck_number" value="<?= $updateRow['good_info']['truck_number'] ?? ''; ?>" id="truck_number" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="driver_name" class="form-label">Driver Name</label>
                                    <input type="text" name="driver_name" value="<?= $updateRow['good_info']['driver_name'] ?? ''; ?>" id="driver_name" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="driver_id" class="form-label">Driver ID</label>
                                    <input type="email" name="driver_id" value="<?= $updateRow['good_info']['driver_id'] ?? ''; ?>" id="driver_id" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="driver_cell" class="form-label">Driver Cell</label>
                                    <input type="tel" name="driver_cell" value="<?= $updateRow['good_info']['driver_cell'] ?? ''; ?>" id="driver_cell" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="transport_name" class="form-label">Transport Name</label>
                                    <input type="tel" name="transport_name" value="<?= $updateRow['good_info']['transport_name'] ?? ''; ?>" id="transport_name" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-4">
                            <div class="col-md-12 text-end">
                                <input type="hidden" name="t_id" value="<?= $id; ?>">
                                <input type="hidden" name="t_sr" value="<?= $T['sr']; ?>">
                                <input type="hidden" name="p_s" value="<?= $T['p_s']; ?>">
                                <input type="hidden" name="t_type" value="<?= $T['type']; ?>">
                                <input type="hidden" name="update" value="<?= $update; ?>">
                                <input name="activeUID" type="hidden" value="<?= $activeUID['uid'] ?? ''; ?>" />
                                <input type="reset"
                                    class="btn btn-warning btn-sm rounded-0" value="Clear Form">
                                <button name="entrySubmit" id="entrySubmit" type="submit"
                                    class="btn btn-<?= $update ? 'warning' : 'primary'; ?> btn-sm rounded-0">
                                    <i class="fa fa-paper-plane"></i> <?= $update ? 'Update' : 'Submit'; ?>
                                </button>
                                <div class="text-danger fw-bold mt-1 d-none show_complete_msg">Remaining Quantity is Now Zero (<?= $remainingTotals['quantity_no']; ?>)</div>
                            </div>
                        </div>
                    </form>
                <?php } else {
                    $UID = mysqli_fetch_assoc(fetch('local_loading', ['uid' => explode('~', $_POST['wTransId'])[0]]));
                    $UID['goods_info'] = json_decode($UID['goods_info'] ?? '[]', true);
                    $UID['warehouse_info'] = json_decode($UID['warehouse_info'] ?? '[]', true);
                ?>
                    <form method="POST">
                        <?php $saleCheck = $UID['p_s'] === 's' ? 'onchange="linkPurchase(this)"' : '';  ?>
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
                        <div class="row mt-4">
                            <div class="col-md-12 text-end">
                                <input type="hidden" name="uid_id" value="<?= $UID['id']; ?>">
                                <input type="hidden" name="p_s" value="<?= $UID['p_s'] ?>">
                                <input type="hidden" name="goods_info" id="goods_info" value='<?= json_encode($UID['goods_info']); ?>'>
                                <input type="hidden" name="warehouse_info" id="warehouse_info" value='<?= json_encode($UID['warehouse_info']); ?>'>
                                <input type="hidden" name="purchase_selected_ids" id="purchase_selected_ids">
                                <input type="hidden" name="keys" id="keys">
                                <button name="TransferWarehouse" id="TransferWarehouse" type="submit"
                                    class="btn btn-primary btn-sm rounded-0">
                                    Transfer
                                </button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>

        <!-- Sidebar: 2 columns -->
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
                    <?php if ($T['locked'] == 0): ?>
                        <?php echo $T['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment'; ?>
                    <?php else: ?>
                        <i class="fa fa-lock text-success"></i> Transferred.
                    <?php endif; ?>
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
            <button class="btn btn-warning btn-sm my-1" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">
                Toggle Form
            </button>
            <button class="btn btn-outline-success btn-sm my-1" onclick="document.querySelector('.toggle-transporter').classList.toggle('d-none');" title="Toggle Transporter">
                <i class="fa fa-plus"></i> Transporter
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
            $('#uid_print').on('change', function() {
                window.open('print/uid-print.php?uid=' + $(this).val(), '_blank',
                    'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + screen.width + ',height=' + screen.height);

            });
            $('#select_good').on('change', function() {
                populateFields(this);
            });
            if (parseInt('<?= $remainingTotals['quantity_no'] + ($updateRow['good_info']['quantity_no'] ?? 0); ?>') === 0) {
                disableButton('entrySubmit');
                $('.show_complete_msg').removeClass('d-none');
            }
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
    </script>
    <script>
        $(document).ready(function() {
            var warehouseInfo = JSON.parse($('#warehouse_info').val() || '{}');
            var selectedKeys = [];

            function updateSelectedKeysInput() {
                var keysString = selectedKeys.join('~');
                $('#keys').val(keysString);
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
            function updateConnectButtonState() {
                let checkedBoxes = $('.entry-checkbox:checked');
                $('#connectButton').prop('disabled', checkedBoxes.length === 0);
            }
            $(document).on('change', '.entry-checkbox', function() {
                updateConnectButtonState();
            });
            $('#connectButton').click(function() {
                let selectedValues = [];
                $('.entry-checkbox:checked').each(function() {
                    selectedValues.push($(this).val());
                });
                $('#purchase_selected_ids').val(selectedValues.join('@'));
                $('#warehouseModal').modal('hide');
            });
            $('#warehouseModal').on('show.bs.modal', function() {
                $('#connectButton').prop('disabled', true);
            });
        });
    </script>
<?php
}
?>
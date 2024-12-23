<?php require_once '../connection.php';
$id = $_POST['id'];
if ($id > 0) {
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $sea_road = json_decode($record['sea_road'], true);
    $_fields = transactionSingle($id);
    $route = $_fields['sea_road_array'] = array_merge($_fields['sea_road_array'], !empty($sea_road) ? $sea_road : []);
    if (!empty($_fields)) {
        $result = mysqli_query($connect, "SELECT * FROM local_loading where p_id=$id");
        $firstRow = null;
        $uniqueUIDNOs = [];
        $rows = [];
        $max_sr_no = $total_loaded_quantity_no = $total_loaded_gross_weight = $total_loaded_net_weight = $next_uid_entry_no = 0;
        $child_ids = "";
        $ActiveUIDRow = null;
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!in_array($row['uid'], $uniqueUIDNOs)) {
                    $uniqueUIDNOs[] = $row['uid'];
                }
                $rows[] = $row;
                if ($firstRow === null) {
                    $firstRow = $row;
                    $current_uid = $firstRow['uid'];
                    $uid_no_query = mysqli_query($connect, "
                        SELECT COUNT(*) AS uid_count, GROUP_CONCAT(id) AS child_ids 
                        FROM local_loading 
                        WHERE uid = '$current_uid'
                    ");
                    $uid_no_result = mysqli_fetch_assoc($uid_no_query);

                    $next_uid_entry_no = $uid_no_result['uid_count'] + 1;
                    $child_ids = implode(',', array_filter(explode(',', $uid_no_result['child_ids']), fn($id) => $id != $firstRow['id']));
                }
                $ActiveUIDNo = isset($firstRow) ? json_decode($firstRow['lloading_info'], true)['active_uid'] : '';
                if (isset($ActiveUIDNo) && $row['uid'] === $ActiveUIDNo) {
                    if ($ActiveUIDRow === null || $row['created_at'] < $ActiveUIDRow['created_at']) {
                        $ActiveUIDRow = $row;
                    }
                }
                if ($row['sr_no'] > $max_sr_no) {
                    $max_sr_no = $row['sr_no'];
                }
                $goods_details = json_decode($row['goods_details'], true);
                if (!(isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['lp_id']) && $row['id'] == $_POST['lp_id'])) {
                    $total_loaded_quantity_no += $goods_details['quantity_no'];
                    $total_loaded_gross_weight += $goods_details['gross_weight'];
                    $total_loaded_net_weight += $goods_details['net_weight'];
                }
            }
        }
        $next_sr_no = $max_sr_no + 1;
        $total_quantity_no = $total_gross_weight = $total_net_weight = 0;
        foreach ($_fields['items'] as $calcItem) {
            $total_quantity_no += $calcItem['qty_no'];
            $total_gross_weight += $calcItem['total_kgs'];
            $total_net_weight += $calcItem['net_kgs'];
        }
        $remaining_quantity_no = $total_quantity_no - $total_loaded_quantity_no;
        $remaining_gross_weight = $total_gross_weight - $total_loaded_gross_weight;
        $remaining_net_weight = $total_net_weight - $total_loaded_net_weight;
?>
        <div class="modal-header d-flex justify-content-between bg-white align-items-center">
            <h5 class="modal-title" id="staticBackdropLabel">LOCAL LOADING</h5>
            <div class="d-flex align-items-center gap-2">
                <?php if ($firstRow): ?>
                    <div class="d-flex gap-2 align-items-center">
                        <label for="UIDSearch" style="text-wrap:nowrap;">UID Print </label>
                        <select name="UIDSearch" id="UIDSearch" class="form-select form-select-sm">
                            <option value="">Select UID</option>
                            <?php foreach ($uniqueUIDNOs as $oneUID): ?>
                                <option value="<?= $oneUID; ?>"><?= $oneUID; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <a href="#" target="_blank" id="printButton" class="btn btn-dark btn-sm me-2 disabled">PRINT</a>
                    </div>
                <?php endif; ?>

                <button class="btn btn-warning btn-sm" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
                <!-- Close Button -->
                <a href="local-loading" class="btn-close ms-3" aria-label="Close"></a>
            </div>
        </div>
        <script>
            let emptyKgs = 0;
            let Rate = 0;
        </script>
        <div class="row">
            <div class="col-md-10">
                <div class="card my-2">
                    <div class="card-body">
                        <?php if (!empty($route)): ?>
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-4">
                                    <span class="fs-6 fw-bold">
                                        Loading <span class="text-success">"<?= ucwords($route['route']); ?></span>" Transfer </span>

                                </div>
                                <div class="col-md-8">
                                    <span class="fs-6 fw-bold">Goods Calculations</span>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if ($route['route'] === 'local'): ?>
                                        <b>Truck No:</b> <?= $route['truck_no'] ?? 'N/A'; ?><br>
                                        <b>Truck Name:</b> <?= $route['truck_name'] ?? 'N/A'; ?><br>
                                        <b>Loading Warehouse:</b> <?= $route['loading_warehouse'] ?? 'N/A'; ?><br>
                                        <b>Receiving Warehouse:</b> <?= $route['loading_warehouse'] ?? 'N/A'; ?><br>
                                    <?php endif; ?>
                                    <b>WareHouse:</b> <?= $route['warehouse_transfer'] ?? 'N/A'; ?><br>
                                    <b>Loading Company:</b> <?= $route['loading_company_name'] ?? 'N/A'; ?><br>
                                    <b>Receiving Company:</b> <?= $route['loading_company_name'] ?? 'N/A'; ?><br>
                                    <b>Loading Date:</b> <?= $route['loading_date'] ?? 'N/A'; ?><br>
                                    <b>Receiving Date:</b> <?= $route['receiving_date'] ?? 'N/A'; ?><br>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex gap-4">
                                        <div>
                                            <b>Goods Qty: <span class="text-success"><?= $total_quantity_no; ?></span></b><br>
                                            <b>Loaded Qty: <span class="text-danger"><?= $total_loaded_quantity_no; ?></span></b><br>
                                            <hr class="my-1">
                                            <b>Remaining: <span><?= $remaining_quantity_no; ?></span></b>
                                        </div>

                                        <div>
                                            <b>G.Weight: <span class="text-success"><?= $total_gross_weight; ?></span></b><br>
                                            <b>Loaded G.W: <span class="text-danger"><?= $total_loaded_gross_weight; ?></span></b><br>
                                            <hr class="my-1">
                                            <b>Remaining: <span><?= $remaining_gross_weight; ?></span></b>
                                        </div>

                                        <div>
                                            <b>N.Weight: <span class="text-success"><?= $total_net_weight; ?></span></b><br>
                                            <b>Loaded N.W: <span class="text-danger"><?= $total_loaded_net_weight; ?></span></b><br>
                                            <hr class="my-1">
                                            <b>Remaining: <span><?= $remaining_net_weight; ?></span></b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($_fields['items'])) { ?>
                            <table class="table mt-2 table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Goods</th>
                                        <th>Size</th>
                                        <th>Brand</th>
                                        <th>Origin</th>
                                        <th>Qty</th>
                                        <th>Total KGs</th>
                                        <th>Net KGs</th>
                                        <th>Tax%</th>
                                        <th>Tax.Amt</th>
                                        <th>Amt+Tax</th>
                                    </tr>
                                </thead>
                                <tbody id="goodsTable">
                                    <?php
                                    $items = $_fields['items'];
                                    $qty_no = $total_kgs = $net_kgs = $final_amount = $total_tax_amount = $total_with_tax = 0;
                                    foreach ($items as $details) {
                                        echo '<tr class="goodRow" data-empty-kgs="' . $details['empty_kgs'] . '" data-rate="' . $details['qty_kgs'] . '" data-quantity="' . $details['qty_no'] . '" data-quantity-name="' . $details['qty_name'] . '" data-net-kgs="' . round($details['net_kgs'], 2) . '" data-gross-kgs="' . round($details['total_kgs'], 2) . '" data-goodsjson=\'' . json_encode($details) . '\'>';
                                        echo '<td>' . $details['sr'] . '</td>';
                                        echo '<td class="TgoodsId d-none">' . $details['goods_id'] . '</td>';
                                        echo '<td>' . goodsName($details['goods_id']) . '</td>';
                                        echo '<td class="size">' . $details['size'] . '</td>';
                                        echo '<td class="brand">' . $details['brand'] . '</td>';
                                        echo '<td class="origin">' . $details['origin'] . '</td>';
                                        echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                        echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                                        echo '<td>' . str_replace('%', '', $details['tax_percent']) . '%</td>';
                                        echo '<td>' . round((float)$details['tax_amount'], 2) . '</td>';
                                        echo '<td>' . round($details['total_with_tax'], 2) . '</td>';
                                        echo '</tr>';
                                        $qty_no += $details['qty_no'];
                                        $total_kgs += $details['total_kgs'];
                                        $net_kgs += $details['net_kgs'];
                                        $final_amount += $details['final_amount'];
                                        $total_tax_amount += (float)$details['tax_amount'];
                                        $total_with_tax += (float)$details['total_with_tax'];
                                    }

                                    if ($qty_no > 0) {
                                        echo '<tr>';
                                        echo '<th colspan="5" class="text-end"></th>';
                                        echo '<th class="fw-bold text-success">' . $qty_no . '</th>';
                                        echo '<th class="fw-bold text-success">' . round($total_kgs, 2) . '</th>';
                                        echo '<th class="fw-bold text-success">' . round($net_kgs, 2) . '</th>';
                                        echo '<th></th>';
                                        echo '<th class="fw-bold text-success">' . round($total_tax_amount, 2) . '</th>';
                                        echo '<th class="fw-bold text-success">' . round($total_with_tax, 2) . '</th>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <div>
                        <style>
                            th,
                            td {
                                text-wrap: nowrap;
                            }
                        </style>
                        <table class="table mt-2 table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="bg-dark text-white">Sr#</th>
                                    <th class="bg-dark text-white">UID</th>
                                    <th class="bg-dark text-white">Goods Name</th>
                                    <th class="bg-dark text-white">Qty Name</th>
                                    <th class="bg-dark text-white">Qty No</th>
                                    <th class="bg-dark text-white">G.W.KGS</th>
                                    <th class="bg-dark text-white">N.W.KGS</th>
                                    <?php if ($route['sea_road'] === 'sea'): ?>
                                        <th class="bg-dark text-white">Truck No.</th>
                                        <th class="bg-dark text-white">Truck Name</th>
                                        <th class="bg-dark text-white">L WareHouse</th>
                                        <th class="bg-dark text-white">R WareHouse</th>
                                    <?php endif; ?>
                                    <th class="bg-dark text-white">WareHouse</th>
                                    <th class="bg-dark text-white">L Company</th>
                                    <th class="bg-dark text-white">L Date</th>
                                    <th class="bg-dark text-white">R Company</th>
                                    <th class="bg-dark text-white">R Date</th>
                                    <th class="bg-dark text-white">FILE</th>
                                </tr>
                            </thead>
                            <tbody class="loadingsTable">
                                <?php
                                $quantity_no = $gross_weight = $net_weight = 0;
                                foreach ($rows as $row): ?>
                                    <tr class="LoadingRow">
                                        <td class="border sr_no border-dark"><a href="local-loading?p_id=<?= $id; ?>&view=1&lp_id=<?= $row['id']; ?>&action=update&sr_no=<?= $row['sr_no']; ?>">#<?= $row['sr_no']; ?></a></td>
                                        <td class="border border-dark"><?= $row['uid']; ?></td>
                                        <td class="border border-dark"><?= goodsName(json_decode($row['goods_details'], true)['goods_id']); ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['quantity_name']; ?></td>
                                        <td class="border quantity_no border-dark"><?= json_decode($row['goods_details'], true)['quantity_no']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['gross_weight']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['net_weight']; ?></td>
                                        <?php if ($route['sea_road'] === 'sea'): ?>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['truck_no']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['truck_name']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['loading_warehouse']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['receiving_warehouse']; ?></td>
                                        <?php endif; ?>
                                        <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['warehouse_transfer']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['loading_company_name']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['receiving_company_name']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['loading_date']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['receiving_date']; ?></td>
                                        <td class="border border-dark text-success" style="position: relative;">
                                            <?php
                                            $attachments = json_decode($row['attachments'], true) ?? [];
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
                                    <?php
                                    $quantity_no += (int)json_decode($row['goods_details'], true)['quantity_no'];
                                    $gross_weight += (float)json_decode($row['goods_details'], true)['gross_weight'];
                                    $net_weight += (float)json_decode($row['goods_details'], true)['net_weight'];
                                    ?>
                                <?php endforeach; ?>
                                <tr>
                                    <th colspan="4"></th>
                                    <th class="fw-bold" id="total_loaded_quantity_no"><?= $quantity_no; ?></th>
                                    <th class="fw-bold" id="total_loaded_gross_weight"><?= $gross_weight; ?></th>
                                    <th class="fw-bold" id="total_loaded_net_weight"><?= $net_weight; ?></th>
                                    <th colspan="12"></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card mt-3 transfer-form d-none">
                        <div class="card-body p-3">
                            <form method="post" class="table-form <?= (isset($_POST['action']) ? $_POST['action'] : '') === 'update' ? 'border border-danger p-2' : ''; ?>" onsubmit="return compareValues()" enctype="multipart/form-data">
                                <input type="hidden" name="unique_code" value="<?= $record['p_s'] . $record['type'][0] . ($sea_road['route'] === 'local' ? 'ld' : 'wr') . '_' . $record['id'] . '_'; ?>">
                                <?php
                                $routeDetails = [
                                    'uid' => $sea_road['uid'] ?? '',
                                    'truck_no' => $sea_road['truck_no'] ?? '',
                                    'truck_name' => $sea_road['truck_name'] ?? '',
                                    'loading_company_name' => $sea_road['loading_company_name'] ?? '',
                                    'receiving_company_name' => $sea_road['receiving_company_name'] ?? '',
                                    'loading_date' => $sea_road['loading_date'] ?? '',
                                    'receiving_date' => $sea_road['receiving_date'] ?? '',
                                    'loading_warehouse' => $sea_road['loading_warehouse'] ?? '',
                                    'receiving_warehouse' => $sea_road['receiving_warehouse'] ?? '',
                                    'warehouse_transfer' => $sea_road['warehouse_transfer'] ?? '',
                                    'route' => $sea_road['route'] ?? '',
                                ];
                                $routeDetails = array_merge($sea_road, $routeDetails);

                                if (isset($_POST['lp_id']) && isset($_POST['action']) && $_POST['action'] === 'update') {
                                    $rowId = $_POST['lp_id'];
                                    $updateQ = mysqli_query($connect, "SELECT * FROM local_loading WHERE id = '$rowId'");
                                    $updateRow = mysqli_fetch_assoc($updateQ);
                                    $next_sr_no = $updateRow['sr_no'];
                                    $last_record['uid'] = $updateRow['uid'];
                                    $last_record['report'] = $updateRow['report'];
                                    $Goods = isset($updateRow['goods_details']) ? json_decode($updateRow['goods_details'], true) : [];
                                    $Transfer = isset($updateRow['transfer_details']) ? json_decode($updateRow['transfer_details'], true) : [];
                                    echo '<input type="hidden" name="action" value="update">';
                                    echo '<input type="hidden" name="id" value="' . $updateRow['id'] . '">';
                                    $action = isset($_POST['action']) ? $_POST['action'] : '';
                                } elseif ($ActiveUIDRow) {
                                    $action = 'new';
                                    $last_record['uid'] = $ActiveUIDRow['uid'];
                                    $last_record['report'] = $ActiveUIDRow['report'];
                                    $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => ''];
                                    $Transfer = isset($ActiveUIDRow['transfer_details']) ? json_decode($ActiveUIDRow['transfer_details'], true) : [];
                                    echo '<input type="hidden" name="active_uid" value="' . $ActiveUIDRow['uid'] . '">';
                                } else {
                                    $action = 'new';
                                    $last_record = [];
                                    $last_record['uid'] = '';
                                    $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => ''];
                                    $Transfer = [
                                        'uid' => '',
                                        'truck_no' => '',
                                        'truck_name' => '',
                                        'loading_company_name' => '',
                                        'receiving_company_name' => '',
                                        'loading_date' => '',
                                        'receiving_date' => '',
                                        'loading_warehouse' => '',
                                        'receiving_warehouse' => '',
                                        'warehouse_transfer' => '',
                                        'route' => $routeDetails['route']
                                    ];
                                    $last_record['report'] = '';
                                }
                                ?>
                                <div style="width:100%; display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <?php if ($action === 'update') { ?>
                                        <h5 class="fw-bold">Update Loading <span class="text-success">"<?= ucwords($sea_road['route']); ?>"</span> Transfer</h5>
                                        <?php if (!isset(json_decode($updateRow['lloading_info'], true)['child_ids'])) { ?>
                                            <a href="local-loading?deleteLoadingEntry=true&lp_id=<?= $updateRow['id']; ?>&p_id=<?= $id ?>" class="btn btn-danger btn-sm">Delete This Entry</a>
                                        <?php }
                                    } else { ?>
                                        <h5 class="fw-bold">Loading <span class="text-success">"<?= ucwords($sea_road['route']); ?>"</span> Transfer</h5>
                                        <?php };
                                    if (!$ActiveUIDRow): if ($firstRow): ?>
                                            <span class="btn btn-warning btn-sm mr-2 pointer"
                                                onclick="populateInputs()">
                                                Get Purchase Truck Details
                                            </span>
                                            <script>
                                                function populateInputs() {
                                                    let values = JSON.parse('<?= json_encode($routeDetails); ?>');
                                                    let inputIDs = {
                                                        uid: '#uid',
                                                        truck_no: '#truck_no',
                                                        truck_name: '#truck_name',
                                                        loading_company_name: '#loading_company_name',
                                                        receiving_company_name: '#receiving_company_name',
                                                        loading_date: '#loading_date',
                                                        receiving_date: '#receiving_date',
                                                        loading_warehouse: '#loading_warehouse',
                                                        receiving_warehouse: '#receiving_warehouse',
                                                        warehouse_transfer: '#warehouse_transfer',
                                                        route: '#route'
                                                    };
                                                    Object.keys(inputIDs).forEach(key => {
                                                        if (values[key] !== undefined) {
                                                            let inputElement = document.querySelector(inputIDs[key]);
                                                            if (inputElement) {
                                                                inputElement.value = values[key];
                                                            }
                                                        }
                                                    });
                                                }
                                            </script>
                                        <?php endif; ?>
                                        <input type="file" id="entry_file" name="entry_file[]" class="d-none" multiple>
                                        <span class="btn cursor btn-success" onclick="document.getElementById('entry_file').click();"><i class="fa fa-paperclip"></i> Add File(s)</span>
                                    <?php endif; ?>
                                </div>
                                <!-- General Information -->
                                <hr>
                                <?php if ($firstRow) { ?>
                                    <input type="hidden" name="uid_entry_no" value="<?= $next_uid_entry_no; ?>">
                                    <input type="hidden" name="child_ids" value="<?= $child_ids; ?>">
                                <?php } ?>
                                <input type="hidden" name="total_quantity_no" value="<?= $total_quantity_no; ?>">
                                <input type="hidden" name="total_gross_weight" value="<?= $total_gross_weight; ?>">
                                <input type="hidden" name="total_net_weight" value="<?= $total_net_weight; ?>">
                                <input type="hidden" name="existingRouteData" value='<?= json_encode($routeDetails); ?>'>
                                <input type="hidden" name="route" value='<?= $Transfer['route']; ?>'>
                                <input type="hidden" name="type" value="<?= $record['p_s']; ?>">

                                <input type="hidden" name="p_id" id="p_id" value="<?= $id; ?>">
                                <input type="hidden" name="p_branch" id="p_branch" value="<?= branchName($_fields['branch_id']); ?>">
                                <input type="hidden" name="p_date" id="p_date" value="<?= $_fields['_date']; ?>">
                                <input type="hidden" name="p_cr_acc" id="p_cr_acc" value="<?= $_fields['cr_acc']; ?>">
                                <input type="hidden" name="p_cr_acc_name" id="p_cr_acc_name" value="<?= $_fields['cr_acc_name']; ?>">
                                <!-- Sr# (small field) -->
                                <div class="d-flex justify-content-between">
                                    <?php
                                    if ($action === 'update') {
                                    ?>
                                        <span><b>Entry Date: </b><?= my_date($updateRow['created_at']); ?></span>
                                    <?php
                                    } else { ?>
                                        <span><b>Date Today: </b><?= my_date(date('Y-m-d')); ?></span>
                                    <?php }
                                    if ($firstRow) {
                                    ?>
                                        <span>
                                            <Label for="updateRoutes" class="pointer">Update Routes: </Label> <input type="checkbox" name="updateRoutes" id="updateRoutes" class="pointer">
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="row g-3 mt-2">
                                    <!-- Sr# (small field) -->
                                    <div class="col-md-1">
                                        <label for="sr_no" class="form-label" class="form-label">Sr#</label>
                                        <input type="number" name="sr_no" id="sr_no" required readonly class="form-control form-control-sm" value="<?php echo $next_sr_no; ?>">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="uid" class="form-label" class="form-label">UID</label>
                                        <input type="text" name="uid" id="uid" required class="form-control form-control-sm" value="<?php echo $Transfer['uid']; ?>">
                                    </div>
                                    <?php if ($Transfer['route'] === 'local') { ?>
                                        <div class="col-md-2">
                                            <label for="truck_no" class="form-label">Truck Number</label>
                                            <input id="truck_no" name="truck_no"
                                                value="<?php echo $Transfer['truck_no']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="truck_name" class="form-label">Truck Name</label>
                                            <input id="truck_name" name="truck_name"
                                                value="<?php echo $Transfer['truck_name']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="loading_warehouse" class="form-label">Loading Warehouse</label>
                                            <input id="loading_warehouse" name="loading_warehouse"
                                                value="<?php echo $Transfer['loading_warehouse']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="receiving_warehouse" class="form-label">Receiving Warehouse</label>
                                            <input id="receiving_warehouse" name="receiving_warehouse"
                                                value="<?php echo $Transfer['receiving_warehouse']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <label for="loading_company_name" class="form-label">Loading Company</label>
                                        <input id="loading_company_name" name="loading_company_name"
                                            value="<?php echo $Transfer['loading_company_name']; ?>" type="text"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="loading_date" class="form-label">Loading Date</label>
                                        <input type="date" class="form-control form-control-sm" id="loading_date" name="loading_date"
                                            value="<?php echo $Transfer['loading_date']; ?>">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="receiving_company_name" class="form-label">Receiving Company Name</label>
                                        <input id="receiving_company_name" name="receiving_company_name"
                                            value="<?php echo $Transfer['receiving_company_name']; ?>" type="text"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="receiving_date" class="form-label">Receiving Date</label>
                                        <input type="date" class="form-control form-control-sm" id="receiving_date" name="receiving_date"
                                            value="<?php echo $Transfer['receiving_date']; ?>">
                                    </div>

                                    <?php
                                    $warehouse = isset($Transfer['warehouse_transfer']) && !empty($Transfer['warehouse_transfer']) ? $Transfer['warehouse_transfer'] : '';
                                    $warehouseOptions = ['Local Import', 'Free Zone Import', 'Import Re-Export', 'Transit', 'Local Export', 'Local Market'];
                                    $saleCheck = '';
                                    if ($record['p_s'] === 's') {
                                        $saleCheck = 'onchange="currentStock(this)"';
                                    }
                                    ?>

                                    <!-- Cargo Transfer Dropdown -->
                                    <div class="col-md-3">
                                        <label for="warehouse_transfer" class="form-label">Cargo Transfer</label>
                                        <select id="warehouse_transfer" name="warehouse_transfer" class="form-select form-control-sm" <?= $saleCheck; ?> required>
                                            <option disabled <?= !in_array($warehouse, $warehouseOptions) ? 'selected' : ''; ?>>Select One</option>
                                            <option value="Local Import" <?= $warehouse === 'Local Import' ? 'selected' : ''; ?>>Local Import</option>
                                            <option value="Free Zone Import" <?= $warehouse === 'Free Zone Import' ? 'selected' : ''; ?>>Free Zone Import</option>
                                            <option value="Import Re-Export" <?= $warehouse === 'Import Re-Export' ? 'selected' : ''; ?>>Import Re-Export</option>
                                            <option value="Transit" <?= $warehouse === 'Transit' ? 'selected' : ''; ?>>Transit</option>
                                            <option value="Local Export" <?= $warehouse === 'Local Export' ? 'selected' : ''; ?>>Local Export</option>
                                            <option value="Local Market" <?= $warehouse === 'Local Market' ? 'selected' : ''; ?>>Local Market</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="report" class="form-label">Report</label>
                                        <input type="text" name="report" id="report" required value="<?= $last_record['report']; ?>" class="form-control form-control-sm">
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
                                    <?php if ($record['p_s'] === 's') { ?>
                                        <div class="col-md-5">
                                            <label for="warehouse_entry" class="form-label">Current Entries In Warehouse</label>
                                            <select id="warehouse_entry" name="warehouse_entry" class="form-select form-select-sm">
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>

                                <!-- Goods Details -->
                                <h5 class="text-primary mt-4">Goods Details</h5>
                                <hr>
                                <?php
                                // Step 1: Initialize unique options for goods, sizes, brands, and origins
                                $goods_options = [];
                                $sizes = [];
                                $brands = [];
                                $origins = [];

                                foreach ($_fields['items'] as $details) {
                                    $goods_id = $details['goods_id'];
                                    $goods_name = goodsName($goods_id);

                                    // Add goods names if not already added
                                    if (!isset($goods_options[$goods_id])) {
                                        $goods_options[$goods_id] = $goods_name;
                                    }

                                    // Add unique sizes, brands, and origins
                                    $sizes[] = $details['size'];
                                    $brands[] = $details['brand'];
                                    $origins[] = $details['origin'];
                                }

                                // Remove duplicates
                                $sizes = array_unique($sizes);
                                $brands = array_unique($brands);
                                $origins = array_unique($origins);
                                ?>

                                <div class="row g-3">
                                    <input type="hidden" name="goods_json" id="goods_json">
                                    <!-- Goods Name -->
                                    <div class="col-md-2">
                                        <label for="goods_id" class="form-label">Goods Name</label>
                                        <select id="goods_id" name="goods_id" class="form-select" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            foreach ($goods_options as $id => $name) {
                                                $selected = ($id == $Goods['goods_id']) ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . $id . '">' . htmlspecialchars($name) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- SIZE -->
                                    <div class="col-md-2">
                                        <label for="size" class="form-label">SIZE</label>
                                        <select class="form-select" name="size" id="size" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            foreach ($sizes as $option) {
                                                $selected = ($option == $Goods['size']) ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- BRAND -->
                                    <div class="col-md-2">
                                        <label for="brand" class="form-label">BRAND</label>
                                        <select class="form-select" name="brand" id="brand" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            foreach ($brands as $option) {
                                                $selected = ($option == $Goods['brand']) ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- ORIGIN -->
                                    <div class="col-md-2">
                                        <label for="origin" class="form-label">ORIGIN</label>
                                        <select class="form-select" name="origin" id="origin" required>
                                            <option hidden value="">Select</option>
                                            <?php
                                            foreach ($origins as $option) {
                                                $selected = ($option == $Goods['origin']) ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <!-- Quantity Name -->
                                    <div class="col-md-2">
                                        <label for="quantity_name" class="form-label">Qty Name</label>
                                        <input type="text" name="quantity_name" value="<?= $Goods['quantity_name']; ?>" id="myquantity_name" required class="form-control form-control-sm">
                                    </div>

                                    <!-- Quantity No -->
                                    <div class="col-md-1">
                                        <label for="quantity_no" class="form-label">Qty No</label>
                                        <input type="number" name="quantity_no" value="<?= $Goods['quantity_no']; ?>" id="quantity_no" required class="form-control form-control-sm" step="0.01" onkeyup="autoCalc('#quantity_no', '#gross_weight', '#net_weight', Rate, emptyKgs)">
                                        <input type="hidden" name="rate" id="rate" value="">
                                        <input type="hidden" name="empty_kgs" id="empty_kgs" value="">
                                    </div>

                                    <!-- Gross Weight -->
                                    <div class="col-md-1">
                                        <label for="gross_weight" class="form-label">G.Weight</label>
                                        <input type="number" name="gross_weight" value="<?= $Goods['gross_weight']; ?>" id="gross_weight" required class="form-control form-control-sm" step="0.01">
                                    </div>
                                    <!-- Net Weight -->
                                    <div class="col-md-1">
                                        <label for="net_weight" class="form-label">N.Weight</label>
                                        <input type="number" name="net_weight" value="<?= $Goods['net_weight']; ?>" id="net_weight" required class="form-control form-control-sm" step="0.01">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <input type="reset"
                                            class="btn btn-warning btn-sm rounded-0" value="Clear Form">
                                        <button name="LLoadingSubmit" id="LLoadingSubmit" type="submit"
                                            class="btn btn-<?= $action === 'update' ? 'warning' : 'primary'; ?> btn-sm rounded-0">
                                            <i class="fa fa-paper-plane"></i> <?= $action === 'update' ? 'Update' : 'Submit'; ?>
                                        </button>
                                        <div class="text-danger fw-bold mt-1 d-none show_complete_msg">Remaining Quantity is Now Zero (<?= $remaining_quantity_no; ?>)</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 card mt-2">
                <div class="align-items-center justify-content-between flex-wrap pt-2">
                    <div>
                        <strong><?php echo strtoupper($_fields['p_s_name']) . ' #'; ?></strong>
                        <?php echo $_fields['sr_no']; ?>
                    </div>
                    <div>
                        <strong>User:</strong> <?php echo $_fields['username']; ?>
                    </div>
                    <div>
                        <strong>Date:</strong> <?php echo my_date($_fields['_date']); ?>
                    </div>
                    <div>
                        <strong>Type:</strong> <?php echo badge(strtoupper($_fields['type']), 'dark'); ?>
                    </div>
                    <div>
                        <strong>Country:</strong> <?php echo $_fields['country']; ?>
                    </div>
                    <div>
                        <strong>Branch:</strong> <?php echo branchName($_fields['branch_id']); ?>
                    </div>
                    <div>
                        <strong>Status:</strong>
                        <?php
                        if ($_fields['locked'] == 0) {
                            echo $_fields['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                        } else {
                            echo '<i class="fa fa-lock text-success"></i> Transferred.';
                        }
                        ?>
                    </div>
                </div>

                <div class="mt-3">
                    <div>
                        <strong>Cr. A/c #:</strong> <?php echo $_fields['dr_acc']; ?><br>
                        <strong>Cr. A/c Name:</strong> <?php echo $_fields['dr_acc_name']; ?>
                    </div>
                </div>

                <div class="mt-2">
                    <div>
                        <strong>Dr. A/c #:</strong> <?php echo $_fields['cr_acc']; ?><br>
                        <strong>Dr. A/c Name:</strong> <?php echo $_fields['cr_acc_name']; ?>
                    </div>
                </div>
                <?php if ($firstRow) {
                    echo '<a href="?updateActiveUIDNo=&parent_id=' . $firstRow['id'] . '&p_id=' . $firstRow['p_id'] . '" class="btn btn-success btn-sm mt-2">Close UID</a>';
                } ?>
            </div>
        </div>
<?php }
} ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let selectedEntry = null;
    let saleQtyValue = null;

    function compareValues() {
        let remainingQty = parseInt('<?= $remaining_quantity_no + (isset($updateRow) ? $Goods['quantity_no'] : 0); ?>');
        let remainingGross = parseInt('<?= $remaining_gross_weight + (isset($updateRow) ? $Goods['gross_weight'] : 0); ?>');
        let remainingNet = parseInt('<?= $remaining_net_weight + (isset($updateRow) ? $Goods['net_weight'] : 0); ?>');
        let msg = "";
        if (parseInt($('#quantity_no').val()) > remainingQty) {
            msg = ("You can only add " + remainingQty + " Quantity For This Good");
        } else if (parseInt($('#gross_weight').val()) > remainingGross) {
            msg = ("You can only add " + remainingGross + " Gross Weight For This Good");
        } else if (parseInt($('#net_weight').val()) > remainingNet) {
            msg = ("You can only add " + remainingNet + " Net Weight For This Good");
        }
        if (msg === '') {
            return true
        } else {
            alert(msg);
            return false
        };
    }
    $(document).ready(function() {
        $('#goods_id, #size, #brand, #origin').on('change', function() {
            populateFields();
        });
        if (parseInt('<?= $remaining_quantity_no; ?>') === 0) {
            disableButton('LLoadingSubmit');
            $('.show_complete_msg').removeClass('d-none');
        }
        $('#UIDSearch').on('change', function() {
            const selectedValue = $(this).val();
            const printButton = $('#printButton');
            if (selectedValue) {
                printButton
                    .removeClass('disabled')
                    .attr('href', `print/index?secret=<?= base64_encode("uid-print"); ?>&UIDSearch=${selectedValue}`);
            } else {
                printButton
                    .addClass('disabled')
                    .attr('href', '#')
                    .attr('onclick', 'return false;');
            }
        });
    });

    function lastAmount() {
        let amount = $("#amount").val();
        let rate = $("#rate").val();
        let operator = $('#opr').find(":selected").val();
        let final_amount;
        if (operator === "/") {
            final_amount = Number(amount) / Number(rate);
        } else {
            final_amount = Number(amount) * Number(rate);
        }
        final_amount = final_amount.toFixed(3);
        $("#final_amount").val(final_amount);
        var balance = $("#balance").val();
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

    function autoCalc(quantityNo, grossWeight, netWeight, Rate, emptyKgs) {
        let qty = parseFloat($(quantityNo).val()) || 0;
        let myGrossweight = qty * parseFloat(Rate);
        $(grossWeight).val(myGrossweight.toFixed(2));
        let myNetWeight = myGrossweight - (parseFloat(emptyKgs) * qty);
        $(netWeight).val(myNetWeight.toFixed(2));
        $('#rate').val(Rate);
        $('#empty_kgs').val(emptyKgs);
        saleQtyValue = qty;
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
                $('#goods_json').val(JSON.stringify(row.data('goodsjson')));
                emptyKgs = row.data('empty-kgs');
                Rate = row.data('rate');
                $('#rate').val(Rate);
                $('#empty_kgs').val(emptyKgs);
                return false;
            }
        });
    }

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
            alert("Please Fill Quantity Box First!");
        }
    }
    $(document).on('change', '#warehouseEntries input[type="radio"]', function() {
        selectedEntry = $(this).val();
        $('#connectButton').prop('disabled', false);
    });
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
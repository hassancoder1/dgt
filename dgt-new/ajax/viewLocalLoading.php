<?php require_once '../connection.php';
$id = $_POST['id'];
if ($id > 0) {
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $sea_road = json_decode($record['sea_road'], true);
    $_fields = transactionSingle($id);
    $_fields['sea_road_array'] = array_merge($_fields['sea_road_array'], !empty($sea_road) ? $sea_road : []);
    if (!empty($_fields)) {
        $result = mysqli_query($connect, "SELECT * FROM local_loading where p_id=$id");
        $firstRow = null;
        $uniqueTruckNOs = [];
        $rows = [];
        $max_sr_no = $total_loaded_quantity_no = $total_loaded_gross_weight = $total_loaded_net_weight = $next_truck_entry_no = 0;
        $child_ids = "";
        $ActiveTruckRow = null;
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!in_array($row['truck_no'], $uniqueTruckNOs)) {
                    $uniqueTruckNOs[] = $row['truck_no'];
                }
                $rows[] = $row;
                if ($firstRow === null) {
                    $firstRow = $row;
                    $current_truck_no = $firstRow['truck_no'];
                    $truck_no_query = mysqli_query($connect, "
                        SELECT COUNT(*) AS truck_count, GROUP_CONCAT(id) AS child_ids 
                        FROM general_loading 
                        WHERE bl_no = '$current_truck_no'
                    ");
                    $truck_no_result = mysqli_fetch_assoc($truck_no_query);

                    $next_truck_entry_no = $truck_no_result['truck_count'] + 1;
                    $child_ids = implode(',', array_filter(explode(',', $truck_no_result['child_ids']), fn($id) => $id != $firstRow['id']));
                }
                $ActiveTruckNo = isset($firstRow) ? json_decode($firstRow['lloading_info'], true)['active_truck_no'] : '';
                // Check if this row's bl_no matches ActiveBlNo and is the oldest entry
                if (isset($ActiveTruckNo) && $row['truck_no'] === $ActiveTruckNo) {
                    if ($ActiveTruckRow === null || $row['created_at'] < $ActiveTruckRow['created_at']) {
                        $ActiveTruckRow = $row;
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
                        <label for="truckNoSearch" style="text-wrap:nowrap;">Truck No Print </label>
                        <select name="truckNoSearch" id="truckNoSearch" class="form-select form-select-sm">
                            <option value="">Select Truck No</option>
                            <?php foreach ($uniqueTruckNOs as $oneTruckNo): ?>
                                <option value="<?= $oneTruckNo; ?>"><?= $oneTruckNo; ?></option>
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
                        <?php if (!empty($_fields['sea_road_array'])): ?>
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-4">
                                    <span class="fs-6 fw-bold">
                                        <?= isset($_fields['sea_road_array']['truck_no']) ? 'Loading Local Transfer' : 'WareHouse Transfer'; ?> </span>

                                </div>
                                <div class="col-md-8">
                                    <span class="fs-6 fw-bold">Goods Calculations</span>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if ($_fields['sea_road_array']['sea_road'] === 'sea'): ?>
                                        <b>Truck No:</b> <?= $_fields['sea_road_array']['truck_no'] ?? 'N/A'; ?><br>
                                        <b>Truck Name:</b> <?= $_fields['sea_road_array']['truck_name'] ?? 'N/A'; ?><br>
                                        <b>Loading Company:</b> <?= $_fields['sea_road_array']['loading_company_name'] ?? 'N/A'; ?><br>
                                        <b>Loading Date:</b> <?= $_fields['sea_road_array']['loading_date'] ?? 'N/A'; ?><br>
                                        <b>Transfer Name:</b> <?= $_fields['sea_road_array']['transfer_name'] ?? 'N/A'; ?><br>
                                    <?php endif; ?>
                                    <?php if ($_fields['sea_road_array']['sea_road'] === 'road'): ?>
                                        <b>Old Company Name:</b> <?= $_fields['sea_road_array']['old_company_name'] ?? 'N/A'; ?><br>
                                        <b>Transfer Company Name:</b> <?= $_fields['sea_road_array']['transfer_company_name'] ?? 'N/A'; ?><br>
                                        <b>Warehouse Date:</b> <?= $_fields['sea_road_array']['warehouse_date'] ?? 'N/A'; ?><br>
                                    <?php endif; ?>
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
                                        echo '<tr class="goodRow" data-empty-kgs="' . $details['empty_kgs'] . '" data-rate="' . $details['qty_kgs'] . '" data-quantity="' . $details['qty_no'] . '" data-quantity-name="' . $details['qty_name'] . '" data-net-kgs="' . round($details['net_kgs'], 2) . '" data-gross-kgs="' . round($details['total_kgs'], 2) . '">';
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
                                        echo '<td>' . round($details['tax_amount'], 2) . '</td>';
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
                                    <th class="bg-dark text-white">Truck No.</th>
                                    <th class="bg-dark text-white">Truck Name</th>
                                    <th class="bg-dark text-white">Goods Name</th>
                                    <th class="bg-dark text-white">Qty Name</th>
                                    <th class="bg-dark text-white">Qty No</th>
                                    <th class="bg-dark text-white">G.W.KGS</th>
                                    <th class="bg-dark text-white">N.W.KGS</th>
                                    <?php if ($_fields['sea_road_array']['sea_road'] === 'sea'): ?>
                                        <th class="bg-dark text-white">L Company</th>
                                        <th class="bg-dark text-white">Date</th>
                                        <th class="bg-dark text-white">Trans Nme</th>
                                    <?php elseif ($_fields['sea_road_array']['sea_road'] === 'road'): ?>
                                        <th class="bg-dark text-white">Old Company Name</th>
                                        <th class="bg-dark text-white">Trans Company</th>
                                        <th class="bg-dark text-white">Date</th>
                                    <?php endif; ?>
                                    <th class="bg-dark text-white">FILE</th>
                                </tr>
                            </thead>
                            <tbody class="loadingsTable">
                                <?php
                                $quantity_no = $gross_weight = $net_weight = 0;
                                foreach ($rows as $row): ?>
                                    <tr class="LoadingRow">
                                        <td class="border sr_no border-dark"><a href="local-loading?p_id=<?= $id; ?>&view=1&lp_id=<?= $row['id']; ?>&action=update&sr_no=<?= $row['sr_no']; ?>">#<?= $row['sr_no']; ?></a></td>
                                        <td class="border border-dark"><?= $row['truck_no']; ?></td>
                                        <td class="border border-dark"><?= $row['truck_name']; ?></td>
                                        <td class="border border-dark"><?= goodsName(json_decode($row['goods_details'], true)['goods_id']); ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['quantity_name']; ?></td>
                                        <td class="border quantity_no border-dark"><?= json_decode($row['goods_details'], true)['quantity_no']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['gross_weight']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['net_weight']; ?></td>
                                        <?php if ($_fields['sea_road_array']['sea_road'] === 'sea'): ?>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['loading_company_name']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['loading_date']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['transfer_name']; ?></td>
                                        <?php elseif ($_fields['sea_road_array']['sea_road'] === 'road'): ?>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['old_company_name']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['transfer_company_name']; ?></td>
                                            <td class="border border-dark"><?= json_decode($row['transfer_details'], true)['warehouse_date']; ?></td>
                                        <?php endif; ?>
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
                                    <th colspan="5"></th>
                                    <th class="fw-bold" id="total_loaded_quantity_no"><?= $quantity_no; ?></th>
                                    <th class="fw-bold" id="total_loaded_gross_weight"><?= $gross_weight; ?></th>
                                    <th class="fw-bold" id="total_loaded_net_weight"><?= $net_weight; ?></th>
                                    <!-- <th colspan="7"></th> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card mt-3 transfer-form d-none">
                        <div class="card-body p-3">
                            <form method="post" class="table-form <?= (isset($_POST['action']) ? $_POST['action'] : '') === 'update' ? 'border border-danger p-2' : ''; ?>" onsubmit="return compareValues()" enctype="multipart/form-data">
                                <?php
                                $routeDetails = [
                                    'truck_no' => $sea_road['truck_no'] ?? '',
                                    'truck_name' => $sea_road['truck_name'] ?? '',
                                    'loading_company_name' => $sea_road['loading_company_name'] ?? '',
                                    'loading_date' => $sea_road['loading_date'] ?? '',
                                    'transfer_name' => $sea_road['transfer_name'] ?? '',
                                    'old_company_name' => $sea_road['old_company_name'] ?? '',
                                    'transfer_company_name' => $sea_road['transfer_company_name'] ?? '',
                                    'warehouse_date' => $sea_road['warehouse_date'] ?? '',
                                    'route' => $sea_road['route'] ?? '',
                                ];
                                $routeDetails = array_merge($sea_road, $routeDetails);

                                if (isset($_POST['lp_id']) && isset($_POST['action']) && $_POST['action'] === 'update') {
                                    $rowId = $_POST['lp_id'];
                                    $updateQ = mysqli_query($connect, "SELECT * FROM local_loading WHERE id = '$rowId'");
                                    $updateRow = mysqli_fetch_assoc($updateQ);
                                    $next_sr_no = $updateRow['sr_no'];
                                    $last_record['truck_no'] = $updateRow['truck_no'];
                                    $last_record['report'] = $updateRow['report'];
                                    $Goods = isset($updateRow['goods_details']) ? json_decode($updateRow['goods_details'], true) : [];
                                    $Transfer = isset($updateRow['transfer_details']) ? json_decode($updateRow['transfer_details'], true) : [];
                                    echo '<input type="hidden" name="action" value="update">';
                                    echo '<input type="hidden" name="id" value="' . $updateRow['id'] . '">';
                                    $action = isset($_POST['action']) ? $_POST['action'] : '';
                                } elseif ($ActiveTruckRow) {
                                    $action = 'new';
                                    $last_record['truck_no'] = $ActiveTruckRow['truck_no'];
                                    $last_record['report'] = $ActiveTruckRow['report'];
                                    $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => ''];
                                    $Transfer = isset($ActiveTruckRow['transfer_details']) ? json_decode($ActiveTruckRow['transfer_details'], true) : [];
                                    echo '<input type="hidden" name="active_truck_no" value="' . $ActiveTruckRow['truck_no'] . '">';
                                } else {
                                    $action = 'new';
                                    $last_record = [];
                                    $last_record['truck_no'] = '';
                                    $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => '', 'container_no' => '', 'container_name' => ''];
                                    $Transfer = [
                                        'truck_no' => '',
                                        'truck_name' => '',
                                        'loading_company_name' => '',
                                        'loading_date' => '',
                                        'transfer_name' => '',
                                        'old_company_name' => '',
                                        'transfer_company_name' => '',
                                        'warehouse_date' => '',
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
                                        <h5 class="fw-bold">General Loading <span class="text-success">"<?= ucwords($sea_road['route']); ?>"</span> Transfer</h5>
                                        <?php };
                                    if (!$ActiveTruckRow): if ($firstRow): ?>
                                            <span class="btn btn-warning btn-sm mr-2 pointer"
                                                onclick="populateInputs()">
                                                Get Purchase Truck Details
                                            </span>
                                            <script>
                                                function populateInputs() {
                                                    let values = JSON.parse('<?= json_encode($routeDetails); ?>');
                                                    let inputIDs = {
                                                        truck_no: '#truck_no',
                                                        truck_name: '#truck_name',
                                                        loading_company_name: '#loading_company_name',
                                                        loading_date: '#loading_date',
                                                        transfer_name: '#transfer_name',
                                                        old_company_name: '#old_company_name',
                                                        transfer_company_name: '#transfer_company_name',
                                                        warehouse_date: '#warehouse_date',
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
                                    <input type="hidden" name="truck_entry_no" value="<?= $next_truck_entry_no; ?>">
                                    <input type="hidden" name="child_ids" value="<?= $child_ids; ?>">
                                <?php } ?>
                                <input type="hidden" name="total_quantity_no" value="<?= $total_quantity_no; ?>">
                                <input type="hidden" name="total_gross_weight" value="<?= $total_gross_weight; ?>">
                                <input type="hidden" name="total_net_weight" value="<?= $total_net_weight; ?>">
                                <input type="hidden" name="existingRouteData" value='<?= json_encode($routeDetails); ?>'>
                                <input type="hidden" name="route" value='<?= $Transfer['route']; ?>'>

                                <input type="hidden" name="p_id" id="p_id" value="<?= $id; ?>">
                                <input type="hidden" name="transfer_by" id="transfer_by" value="<?= isset($_fields['sea_road_array']['truck_no']) ? 'Loading Local Transfer' : 'WareHouse Transfer';; ?>">
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
                                    <div class="col-md-3">
                                        <label for="truck_no" class="form-label">Truck Number</label>
                                        <input id="truck_no" name="truck_no"
                                            value="<?php echo $Transfer['truck_no']; ?>" type="text"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="truck_name" class="form-label">Truck Name</label>
                                        <input id="truck_name" name="truck_name"
                                            value="<?php echo $Transfer['truck_name']; ?>" type="text"
                                            class="form-control form-control-sm">
                                    </div>
                                    <?php if ($Transfer['route'] === 'local') { ?>
                                        <div class="col-md-4">
                                            <label for="loading_company_name" class="form-label">Loading Company</label>
                                            <input id="loading_company_name" name="loading_company_name"
                                                value="<?php echo $Transfer['loading_company_name']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="loading_date" class="form-label">Date</label>
                                            <input id="loading_date" name="loading_date"
                                                value="<?php echo $Transfer['loading_date']; ?>" type="date"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="transfer_name" class="form-label">Transfer Name</label>
                                            <input id="transfer_name" name="transfer_name"
                                                value="<?php echo $Transfer['transfer_name']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                    <?php } elseif ($Transfer['route'] === 'warehouse') { ?>
                                        <div class="col-md-3">
                                            <label for="old_company_name" class="form-label">Old Company Name</label>
                                            <input id="old_company_name" name="old_company_name"
                                                value="<?php echo $Transfer['old_company_name']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="transfer_company_name" class="form-label">Transfer Company Name</label>
                                            <input id="transfer_company_name" name="transfer_company_name"
                                                value="<?php echo $Transfer['transfer_company_name']; ?>" type="text"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="warehouse_date" class="form-label">Date</label>
                                            <input type="date" class="form-control form-control-sm" id="warehouse_date" name="warehouse_date"
                                                value="<?php echo $Transfer['warehouse_date']; ?>">
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-5">
                                        <label for="report" class="form-label">Report</label>
                                        <input type="text" name="report" id="report" required value="<?= $last_record['report']; ?>" class="form-control form-control-sm">
                                    </div>
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
                    echo '<a href="?updateActiveTruckNo=&parent_id=' . $firstRow['id'] . '&p_id=' . $firstRow['p_id'] . '" class="btn btn-success btn-sm mt-2">Close Truck No</a>';
                } ?>
            </div>
        </div>
<?php }
} ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
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
        $('#truckNoSearch').on('change', function() {
            const selectedValue = $(this).val();
            const printButton = $('#printButton');
            if (selectedValue) {
                printButton
                    .removeClass('disabled')
                    .attr('href', `print/index?secret=<?= base64_encode("truck-no-print"); ?>&truckNoSearch=${selectedValue}`);
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
                return false;
            }
        });
    }
</script>
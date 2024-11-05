<script>
    let emptyKgs = 0;
    let Rate = 0;
</script>
<?php require_once '../connection.php';
$id = $_POST['id'];
$level = $_POST['level'];

if ($id > 0) {
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $_fields = transactionSingle($id);
    $notify_party = isset($record['notify_party_details']) ? json_decode($record['notify_party_details'], true) : false;
    if (!empty($_fields)) { ?>
        <?php
        $result = mysqli_query($connect, "SELECT * FROM general_loading where p_id=$id");
        $all_records = [];
        $firstRow = null;
        $rows = [];
        $max_sr_no = $total_loaded_quantity_no = $total_loaded_gross_weight = $total_loaded_net_weight = $next_bl_entry = 0;
        $child_ids = "";
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
                if ($firstRow === null) {
                    $firstRow = $row;
                    $current_bl_no = $firstRow['bl_no'];
                    $bl_no_query = mysqli_query($connect, "
                SELECT COUNT(*) AS bl_count, GROUP_CONCAT(id) AS child_ids 
                FROM general_loading 
                WHERE bl_no = '$current_bl_no'
            ");
                    $bl_no_result = mysqli_fetch_assoc($bl_no_query);

                    $next_bl_entry = $bl_no_result['bl_count'] + 1;
                    $child_ids = implode(',', array_filter(explode(',', $bl_no_result['child_ids']), fn($id) => $id != $firstRow['id']));
                }
                if ($row['sr_no'] > $max_sr_no) {
                    $max_sr_no = $row['sr_no'];
                }
                $goods_details = json_decode($row['goods_details'], true);
                $total_loaded_quantity_no += $goods_details['quantity_no'];
                $total_loaded_gross_weight += $goods_details['gross_weight'];
                $total_loaded_net_weight += $goods_details['net_weight'];
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
        <div class="row">
            <div class="col-md-10">
                <div class="card my-2">
                    <div class="card-body">
                        <?php if (!empty($_fields['sea_road_array'])): ?>
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-12">
                                    <span class="fw-bold">By </span>
                                    <?php
                                    echo $_fields['sea_road'];                              ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="fw-bold">Loading Details</div>
                                    <div>
                                        <?php
                                        $loadingDetails = [];
                                        foreach ($_fields['sea_road_array'] as $key => $value) {
                                            if (strpos($key, 'l_') === 0) {
                                                if (is_array($value)) {
                                                    echo '<b style="font-size:13px;">' . $value[0] . ':</b> ' . $value[1] . '<br>';
                                                    $loadingDetails[$value[0]] = $value[1];
                                                } else {
                                                    echo '<b style="font-size:13px;">' . strtoupper($key) . ':</b> ' . $value . '<br>';
                                                    $loadingDetails[strtoupper($key)] = $value;
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" id="loadingDetailsJSON" value='<?= json_encode($loadingDetails); ?>'>
                                </div>


                                <!-- Receiving Country Column -->
                                <div class="col-md-3">
                                    <div class="fw-bold">Receiving Details</div>
                                    <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                        <?php
                                        // Check if the key starts with 'r_' (receiving-related) or 'd_' (delivery-related)
                                        if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0):
                                            if (is_array($value)) {
                                                // For delivery dates, show the label as "Arrival Date" instead of just the key
                                                $label =  $value[0];
                                                echo '<b style="font-size:13px;">' . $label . ':</b> ' . $value[1] . '<br>';
                                            } else {
                                                echo '<b style="font-size:13px;">' . strtoupper($key) . ':</b> ' . $value . '<br>';
                                            }
                                        endif;
                                        ?>
                                    <?php endforeach; ?>
                                </div>

                                <div class="col-md-3">
                                    <div class="fw-bold">Goods Calculations</div>
                                    <div class="d-flex">
                                        <div>
                                            <b style="font-size:13px;">Goods Qty: <span class="text-success"><?= $total_quantity_no; ?></span></b><br>
                                            <b style="font-size:13px;">Loaded Qty: <span class="text-danger"><?= $total_loaded_quantity_no; ?></span></b><br>
                                            <hr class="my-1" style="width:130px;">
                                            <b style="font-size:13px;">Remaining: <span><?= $remaining_quantity_no; ?></span></b>
                                        </div>

                                        <div>
                                            <b style="font-size:13px;">G.Weight: <span class="text-success"><?= $total_gross_weight; ?></span></b><br>
                                            <b style="font-size:13px;">Loaded G.W: <span class="text-danger"><?= $total_loaded_gross_weight; ?></span></b><br>
                                            <hr class="my-1" style="width:130px;">
                                            <b style="font-size:13px;">Remaining: <span><?= $remaining_gross_weight; ?></span></b>
                                        </div>

                                        <div>
                                            <b style="font-size:13px;">N.Weight: <span class="text-success"><?= $total_net_weight; ?></span></b><br>
                                            <b style="font-size:13px;">Loaded N.W: <span class="text-danger"><?= $total_loaded_net_weight; ?></span></b><br>
                                            <hr class="my-1" style="width:130px;">
                                            <b style="font-size:13px;">Remaining: <span><?= $remaining_net_weight; ?></span></b>
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
                                        <th>SIZE</th>
                                        <th>BRAND</th>
                                        <th>ORIGIN</th>
                                        <th>Qty</th>
                                        <th>Total KGs</th>
                                        <th>Net KGs</th>
                                    </tr>
                                </thead>
                                <tbody id="goodsTable">
                                    <?php $items = $_fields['items'];
                                    $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                                    $i = 0;
                                    $rate = 0;
                                    foreach ($items as $details) {
                                        echo '<tr class="goodRow" data-empty-kgs="' . $details['empty_kgs'] . '" data-rate="' . $details['rate1'] . '" data-quantity="' . $details['qty_no'] . '" data-quantity-name="' . $details['qty_name'] . '" data-net-kgs="' . round($details['net_kgs'], 2) . '" data-gross-kgs="' . round($details['total_kgs'], 2) . '">';
                                        echo '<td>' . $details['sr'] . '</td>';
                                        echo '<td class="TgoodsId d-none">' . $details['goods_id'] . '</td>';
                                        echo '<td>' . goodsName($details['goods_id']) . '</td>';
                                        echo '<td class="size">' . $details['size'] . '</td>';
                                        echo '<td class="brand">' . $details['brand'] . '</td>';
                                        echo '<td class="origin">' . $details['origin'] . '</td>';
                                        echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                        echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                                        echo '</tr>';

                                        // Perform calculations
                                        $rate += $details['rate1'];
                                        $qty_no += $details['qty_no'];
                                        $qty_kgs += $details['qty_kgs'];
                                        $total_kgs += $details['total_kgs'];
                                        $net_kgs += $details['net_kgs'];
                                        $total += $details['total'];
                                        $amount += $details['amount'];
                                        $final_amount += $details['final_amount'];
                                    }
                                    if ($qty_no > 0) {
                                        $goodsTotals = [
                                            'Quantity' => $qty_no,
                                            'Total KGs' => round($total_kgs, 2),
                                            'Total Quantity KGs' => round($total_qty_kgs, 2),
                                            'Net KGs' => round($net_kgs, 2),
                                            'Total' => round($total, 2),
                                            'Final' => round($final_amount, 2)
                                        ];
                                        echo '<input type="hidden" id="goodsTotalsJSON" value=\'' . json_encode($goodsTotals) . '\'>';
                                        echo '<tr>
                                                <th colspan="5"></th>
                                                <th class="fw-bold text-success" id="total_quantity_no">' . $qty_no . '</th>
                                                <th class="fw-bold text-success" id="total_gross_weight">' . round($total_kgs, 2) . '</th>
                                                <th class="fw-bold text-success" id="total_net_weight">' . round($net_kgs, 2) . '</th>
                                                <th colspan="1"></th>
                                                <th></th>
                                              </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <!-- HTML to display records in the table -->
                    <div class="table-responsive">
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
                                    <th class="bg-dark text-white">L.<?= $_fields['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?></th>
                                    <th class="bg-dark text-white">R.DATE</th>
                                    <th class="bg-dark text-white">R.<?= $_fields['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?></th>
                                    <th class="bg-dark text-white">FILE</th>
                                </tr>
                            </thead>
                            <tbody class="loadingsTable">
                                <?php
                                $quantity_no = $gross_weight = $net_weight = 0;
                                foreach ($rows as $row): ?>
                                    <tr class="LoadingRow">
                                        <td class="border sr_no border-dark"><a href="general-loading?p_id=<?= $id; ?>&view=1&lp_id=<?= $row['id']; ?>&action=update&sr_no=<?= $row['sr_no']; ?>"><?= $row['sr_no']; ?></a></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['container_no']; ?></td>
                                        <td class="border border-dark"><?= $row['bl_no']; ?></td>
                                        <!-- <td class="border border-dark"><?= json_decode($row['importer_details'], true)['im_acc_no']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['exporter_details'], true)['xp_acc_no']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['notify_party_details'], true)['np_acc_no']; ?></td> -->
                                        <td class="border border-dark"><?= goodsName(json_decode($row['goods_details'], true)['goods_id']); ?></td>
                                        <!-- <td class="border border-dark"><?= json_decode($row['goods_details'], true)['size']; ?></td>
                                <td class="border border-dark"><?= json_decode($row['goods_details'], true)['brand']; ?></td>
                                <td class="border border-dark"><?= json_decode($row['goods_details'], true)['origin']; ?></td> -->
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['quantity_name']; ?></td>
                                        <td class="border quantity_no border-dark"><?= json_decode($row['goods_details'], true)['quantity_no']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['gross_weight']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['goods_details'], true)['net_weight']; ?></td>
                                        <!-- <td class="border border-dark"><?= $row['report']; ?></td> -->
                                        <!-- <td class="border border-dark"><?= json_decode($row['shipping_details'], true)['shipping_name']; ?></td> -->
                                        <td class="border border-dark"><?= json_decode($row['loading_details'], true)['loading_date']; ?></td>
                                        <!-- <td class="border border-dark"><?= json_decode($row['loading_details'], true)['loading_country']; ?></td> -->
                                        <td class="border border-dark"><?= json_decode($row['loading_details'], true)['loading_port_name']; ?></td>
                                        <td class="border border-dark"><?= json_decode($row['receiving_details'], true)['receiving_date']; ?></td>
                                        <!-- <td class="border border-dark"><?= json_decode($row['receiving_details'], true)['receiving_country']; ?></td> -->
                                        <td class="border border-dark"><?= json_decode($row['receiving_details'], true)['receiving_port_name']; ?></td>
                                        <td class="border border-dark text-success" style="position: relative;">
                                            <a href="javascript:void(0);" onclick="toggleDownloadMenu(event, this)" style="text-decoration: none; color: inherit;">
                                                <i class="fa fa-paperclip"></i>
                                            </a>
                                            <div class="bg-light border border-dark p-2 attachment-menu" style="position: absolute; top: -100%; left: -500%; display: none; z-index: 1000; width: 200px;">
                                                <?php
                                                $attachments = json_decode($row['attachments'], true) ?? [];
                                                foreach ($attachments as $item) {
                                                    $fileName = htmlspecialchars($item[1], ENT_QUOTES);
                                                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                    $trimmedName = (strlen($fileName) > 15) ? substr($fileName, 0, 15) . '...' . $fileExtension : $fileName;
                                                    echo '<a href="attachments/' . $fileName . '" download="' . $fileName . '" class="d-block mb-2">' . $trimmedName . '</a>';
                                                }
                                                if (empty($attachments)) echo '<p>No attachments available</p>';
                                                ?>
                                            </div>
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
                                    <th colspan="7"></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card mt-3 transfer-form d-none">
                        <div class="card-body p-3">
                            <form method="post" class="table-form <?= (isset($_POST['action']) ? $_POST['action'] : '') === 'update' ? 'border border-danger p-2' : ''; ?>" onsubmit="return compareValues()" enctype="multipart/form-data">
                                <?php
                                if (isset($_POST['lp_id']) && isset($_POST['action']) && $_POST['action'] === 'update') {
                                    $rowId = $_POST['lp_id'];
                                    $updateQ = mysqli_query($connect, "SELECT * FROM general_loading WHERE id = '$rowId'");
                                    $updateRow = mysqli_fetch_assoc($updateQ);
                                    $next_sr_no = $updateRow['sr_no'];
                                    $last_record['bl_no'] = $updateRow['bl_no'];
                                    $last_record['report'] = $updateRow['report'];
                                    $Importer = isset($updateRow['importer_details']) ? json_decode($updateRow['importer_details'], true) : [];
                                    $Notify = isset($updateRow['notify_party_details']) ? json_decode($updateRow['notify_party_details'], true) : [];
                                    $Exporter = isset($updateRow['exporter_details']) ? json_decode($updateRow['exporter_details'], true) : [];
                                    $Goods = isset($updateRow['goods_details']) ? json_decode($updateRow['goods_details'], true) : [];
                                    $Shipping = isset($updateRow['shipping_details']) ? json_decode($updateRow['shipping_details'], true) : [];
                                    $Loading = isset($updateRow['loading_details']) ? json_decode($updateRow['loading_details'], true) : [];
                                    $Receiving = isset($updateRow['receiving_details']) ? json_decode($updateRow['receiving_details'], true) : [];
                                    echo '<input type="hidden" name="action" value="update">';
                                    echo '<input type="hidden" name="id" value="' . $updateRow['id'] . '">';
                                    $action = isset($_POST['action']) ? $_POST['action'] : '';
                                } elseif ($firstRow) {
                                    $action = 'new';
                                    $last_record['bl_no'] = $firstRow['bl_no'];
                                    $last_record['report'] = $firstRow['report'];
                                    $Importer = isset($firstRow['importer_details']) ? json_decode($firstRow['importer_details'], true) : [];
                                    $Notify = isset($firstRow['notify_party_details']) ? json_decode($firstRow['notify_party_details'], true) : [];
                                    $Exporter = isset($firstRow['exporter_details']) ? json_decode($firstRow['exporter_details'], true) : [];
                                    // $Goods = isset($firstRow['goods_details']) ? json_decode($firstRow['goods_details'], true) : [];
                                    $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => '', 'container_no' => '', 'container_name' => ''];
                                    $Shipping = isset($firstRow['shipping_details']) ? json_decode($firstRow['shipping_details'], true) : [];
                                    $Loading = isset($firstRow['loading_details']) ? json_decode($firstRow['loading_details'], true) : [];
                                    $Receiving = isset($firstRow['receiving_details']) ? json_decode($firstRow['receiving_details'], true) : [];
                                } else {
                                    $action = 'new';
                                    $last_record = [];
                                    $last_record['bl_no'] = '';
                                    $Importer = ['im_acc_id' => '', 'im_acc_no' => '', 'im_acc_name' => '', 'im_acc_kd_id' => '', 'im_acc_details' => ''];
                                    $Notify = ['np_acc_id' => '', 'np_acc_no' => '', 'np_acc_name' => '', 'np_acc_kd_id' => '', 'np_acc_details' => ''];
                                    $Exporter = ['xp_acc_id' => '', 'xp_acc_no' => '', 'xp_acc_name' => '', 'xp_acc_kd_id' => '', 'xp_acc_details' => ''];
                                    $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => '', 'container_no' => '', 'container_name' => ''];
                                    $Shipping = ['shipping_name' => '', 'shipping_phone' => '', 'shipping_whatsapp' => '', 'shipping_email' => '', 'shipping_address' => ''];
                                    $Loading = ['loading_date' => '', 'loading_country' => '', 'loading_port_name' => ''];
                                    $Receiving = ['receiving_date' => '', 'receiving_country' => '', 'receiving_port_name' => ''];
                                    $last_record['report'] = '';
                                }
                                ?>
                                <div style="width:100%; display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <?php if ($action === 'update') { ?>
                                        <h5 class="text-primary">Update Information</h5>
                                        <?php if (!isset(json_decode($updateRow['gloading_info'], true)['child_ids'])) { ?>
                                            <a href="general-loading?deleteLoadingEntry=true&lp_id=<?= $updateRow['id']; ?>&p_id=<?= $id ?>" class="btn btn-danger btn-sm">Delete This Entry</a>
                                        <?php }
                                    } else { ?>
                                        <h5 class="text-primary">General Information</h5>
                                    <?php }; ?>
                                    <input type="file" id="entry_file" name="entry_file[]" class="d-none" multiple>
                                    <span class="btn cursor btn-success" onclick="document.getElementById('entry_file').click();"><i class="fa fa-paperclip"></i> Add File(s)</span>
                                </div>
                                <!-- General Information -->
                                <hr>
                                <?php if ($firstRow) { ?>
                                    <input type="hidden" name="bl_entry_no" value="<?= $next_bl_entry; ?>">
                                    <input type="hidden" name="child_ids" value="<?= $child_ids; ?>">
                                <?php } ?>
                                <input type="hidden" name="total_quantity_no" value="<?= $total_quantity_no; ?>">
                                <input type="hidden" name="total_gross_weight" value="<?= $total_gross_weight; ?>">
                                <input type="hidden" name="total_net_weight" value="<?= $total_net_weight; ?>">

                                <input type="hidden" name="p_id" id="p_id" value="<?= $id; ?>">
                                <input type="hidden" name="transfer_by" id="transfer_by" value="<?= $_fields['sea_road']; ?>">
                                <input type="hidden" name="p_type" id="p_type" value="<?= $_fields['type']; ?>">
                                <input type="hidden" name="p_branch" id="p_branch" value="<?= branchName($_fields['branch_id']); ?>">
                                <input type="hidden" name="p_date" id="p_date" value="<?= $_fields['_date']; ?>">
                                <input type="hidden" name="p_cr_acc" id="p_cr_acc" value="<?= $_fields['cr_acc']; ?>">
                                <input type="hidden" name="p_cr_acc_name" id="p_cr_acc_name" value="<?= $_fields['cr_acc_name']; ?>">
                                <!-- Sr# (small field) -->
                                <?php
                                if ($action === 'update') {
                                ?>
                                    <span><b>Entry Date: </b><?= my_date($updateRow['created_at']); ?></span>
                                <?php
                                } else { ?>
                                    <span><b>Date Today: </b><?= my_date(date('Y-m-d')); ?></span>
                                <?php } ?>
                                <div class="row g-3 mt-2">
                                    <!-- Sr# (small field) -->
                                    <div class="col-md-1">
                                        <label for="sr_no" class="form-label">Sr#</label>
                                        <input type="number" name="sr_no" id="sr_no" required readonly class="form-control form-control-sm" value="<?php echo $next_sr_no; ?>">
                                    </div>

                                    <!-- (B/L) Bill of Lading Number  -->
                                    <!-- B/L No (small field) -->
                                    <div class="col-md-2 position-relative">
                                        <label for="bl_no" class="form-label">B/L No</label>
                                        <input type="text" name="bl_no" id="bl_no" onkeyup="GetBLSuggestions()" required value="<?= $last_record['bl_no']; ?>" class="form-control form-control-sm">
                                        <ul id="bl_suggestions" class="list-group position-absolute w-100" style="display:none; max-height: 200px; overflow-y: auto;"></ul>
                                    </div>

                                    <!-- Loading Date (small field) -->
                                    <div class="col-md-2">
                                        <label for="loading_date" class="form-label">Loading Date</label>
                                        <input type="date" name="loading_date" id="loading_date" value="<?= $Loading['loading_date']; ?>" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Receiving Date (small field) -->
                                    <div class="col-md-2">
                                        <label for="receiving_date" class="form-label">Receiving Date</label>
                                        <input type="date" name="receiving_date" id="receiving_date" value="<?= $Receiving['receiving_date']; ?>" required class="form-control form-control-sm">
                                    </div>


                                    <div class="col-md-2">
                                        <label for="loading_country" class="form-label">Loading Country</label>
                                        <input type="text" name="loading_country" id="loading_country" value="<?= $Loading['loading_country']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="loading_port_name" class="form-label">L <?= $_fields['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                        <input type="text" name="loading_port_name" id="loading_port_name" value="<?= $Loading['loading_port_name']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="receiving_country" class="form-label">Receiving Country</label>
                                        <input type="text" name="receiving_country" id="receiving_country" value="<?= $Receiving['receiving_country']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="receiving_port_name" class="form-label">R <?= $_fields['sea_road'] === 'sea' ? 'PORT' : 'BORDER'; ?> Name</label>
                                        <input type="text" name="receiving_port_name" id="receiving_port_name" value="<?= $Receiving['receiving_port_name']; ?>" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-5">
                                        <label for="report" class="form-label">Report</label>
                                        <input type="text" name="report" id="report" required value="<?= $last_record['report']; ?>" class="form-control form-control-sm">
                                    </div>

                                    <!-- Shipping Name -->
                                    <div class="col-md-3">
                                        <label for="shipping_name" class="form-label"><?= $_fields['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name</label>
                                        <input type="text" name="shipping_name" value="<?= $Shipping['shipping_name']; ?>" id="shipping_name" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Address -->
                                    <div class="col-md-4">
                                        <label for="shipping_address" class="form-label"><?= $_fields['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address</label>
                                        <input type="text" name="shipping_address" value="<?= $Shipping['shipping_address']; ?>" id="shipping_address" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Phone -->
                                    <div class="col-md-2">
                                        <label for="shipping_phone" class="form-label"><?= $_fields['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone</label>
                                        <input type="tel" name="shipping_phone" value="<?= $Shipping['shipping_phone']; ?>" id="shipping_phone" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping WhatsApp -->
                                    <div class="col-md-2">
                                        <label for="shipping_whatsapp" class="form-label"><?= $_fields['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp</label>
                                        <input type="tel" name="shipping_whatsapp" value="<?= $Shipping['shipping_whatsapp']; ?>" id="shipping_whatsapp" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Email -->
                                    <div class="col-md-3">
                                        <label for="shipping_email" class="form-label"><?= $_fields['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email</label>
                                        <input type="email" name="shipping_email" value="<?= $Shipping['shipping_email']; ?>" id="shipping_email" required class="form-control form-control-sm">
                                    </div>
                                </div>

                                <!-- Importer, Notify Party, Exporter in One Row -->
                                <h5 class="text-primary mt-4">Importer, Notify Party, and Exporter Details</h5>
                                <hr>
                                <div class="row g-3">
                                    <!-- Importer Details -->
                                    <div class="col-md-4">
                                        <label for="im_acc_no" class="form-label fw-bold">Importer Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="im_acc_id" value="<?= isset($Importer['im_acc_id']) ? $Importer['im_acc_id'] : ''; ?>" id="im_acc_id">
                                            <input type="text" id="im_acc_no" name="im_acc_no" class="form-control form-control-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Importer['im_acc_no']) ? $Importer['im_acc_no'] : ''; ?>">
                                            <input type="text" id="im_acc_name" name="im_acc_name" class="form-control form-control-sm w-75"
                                                placeholder="Importer Name" value="<?= isset($Importer['im_acc_name']) ? $Importer['im_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm mt-2" name="im_acc_kd_id" id="im_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Importer['im_acc_id']) ? $Importer['im_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Importer['im_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            } ?>
                                        </select>
                                        <textarea class="form-control form-control-sm mt-2" name="im_acc_details" id="im_acc_details" rows="2"
                                            placeholder="Company Details"><?= isset($Importer['im_acc_details']) ? $Importer['im_acc_details'] : ''; ?></textarea>
                                    </div>

                                    <!-- Notify Party Details -->
                                    <div class="col-md-4">
                                        <label for="np_acc_no" class="form-label fw-bold">Notify Party Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="np_acc_id" value="<?= isset($Notify['np_acc_id']) ? $Notify['np_acc_id'] : ''; ?>" id="np_acc_id">
                                            <input type="text" id="np_acc_no" name="np_acc_no" class="form-control form-control-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Notify['np_acc_no']) ? $Notify['np_acc_no'] : ''; ?>">
                                            <input type="text" id="np_acc_name" name="np_acc_name" class="form-control form-control-sm w-75"
                                                placeholder="Notify Party Name" value="<?= isset($Notify['np_acc_name']) ? $Notify['np_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm mt-2" name="np_acc_kd_id" id="np_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Notify['np_acc_id']) ? $Notify['np_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Notify['np_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            } ?>
                                        </select>
                                        <textarea class="form-control form-control-sm mt-2" name="np_acc_details" id="np_acc_details" rows="2"
                                            placeholder="Company Details"><?= isset($Notify['np_acc_details']) ? $Notify['np_acc_details'] : ''; ?></textarea>
                                    </div>

                                    <!-- Exporter Details -->
                                    <div class="col-md-4">
                                        <label for="xp_acc_no" class="form-label fw-bold">Exporter Details</label>
                                        <div class="input-group">
                                            <input type="hidden" name="xp_acc_id" value="<?= isset($Exporter['xp_acc_id']) ? $Exporter['xp_acc_id'] : ''; ?>" id="xp_acc_id">
                                            <input type="text" id="xp_acc_no" name="xp_acc_no" class="form-control form-control-sm w-25"
                                                placeholder="ACC No" value="<?= isset($Exporter['xp_acc_no']) ? $Exporter['xp_acc_no'] : ''; ?>">
                                            <input type="text" id="xp_acc_name" name="xp_acc_name" class="form-control form-control-sm w-75"
                                                placeholder="Exporter Name" value="<?= isset($Exporter['xp_acc_name']) ? $Exporter['xp_acc_name'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm mt-2" name="xp_acc_kd_id" id="xp_acc_kd_id">
                                            <option hidden value="">Select Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($Exporter['xp_acc_id']) ? $Exporter['xp_acc_id'] : '', 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $Exporter['xp_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            } ?>
                                        </select>
                                        <textarea class="form-control form-control-sm mt-2" name="xp_acc_details" id="xp_acc_details" rows="2"
                                            placeholder="Company Details"><?= isset($Exporter['xp_acc_details']) ? $Exporter['xp_acc_details'] : ''; ?></textarea>
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
                                        <input type="number" name="quantity_no" value="<?= $Goods['quantity_no']; ?>" id="quantity_no" required class="form-control form-control-sm" step="0.00001" onkeyup="autoCalc('#quantity_no', '#gross_weight', '#net_weight', Rate, emptyKgs)">
                                    </div>

                                    <!-- Gross Weight -->
                                    <div class="col-md-1">
                                        <label for="gross_weight" class="form-label">G.Weight</label>
                                        <input type="number" name="gross_weight" value="<?= $Goods['gross_weight']; ?>" id="gross_weight" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Net Weight -->
                                    <div class="col-md-1">
                                        <label for="net_weight" class="form-label">N.Weight</label>
                                        <input type="number" name="net_weight" value="<?= $Goods['net_weight']; ?>" id="net_weight" required class="form-control form-control-sm">
                                    </div>

                                    <!-- Container No -->
                                    <div class="col-md-2">
                                        <label for="container_no" class="form-label">Container No</label>
                                        <input type="text" name="container_no" value="<?= $Goods['container_no']; ?>" id="container_no" required class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="container_name" class="form-label">Container Name</label>
                                        <input type="text" name="container_name" value="<?= $Goods['container_name']; ?>" id="container_name" required class="form-control form-control-sm">
                                    </div>
                                </div>

                                <!-- Shipping Details
                                <h5 class="text-primary mt-4"><?= $_fields['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Details</h5>
                                <hr>
                                <div class="row g-3">
                                    

                                </div> -->
                                <!-- Submit Button -->
                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <input type="reset"
                                            class="btn btn-warning btn-sm rounded-0" value="Clear Form">

                                        <button name="GLoadingSubmit" id="GLoadingSubmit" type="submit"
                                            class="btn btn-<?= $action === 'update' ? 'warning' : 'primary'; ?> btn-sm rounded-0">
                                            <i class="fa fa-paper-plane"></i> <?= $action === 'update' ? 'Update' : 'Submit'; ?>
                                        </button>
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

                <div class="mt-2">
                    <?php if ($notify_party): ?>
                        <div>
                            <strong>NP Acc No.:</strong> <?= $notify_party['np_acc']; ?><br>
                            <strong>Acc Name:</strong> <?php echo $notify_party['np_acc_name']; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-danger">Notify Party Details Not Added!</div>
                    <?php endif; ?>
                </div>

                <button class="btn btn-warning btn-sm mt-2" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
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
        function hidePctInputs() {
            $("#pct, #pct_amt").hide();
        }

        function showPctInputs() {
            $("#pct, #pct_amt").show();
        }

        function updatePctAmt() {
            var pctValue = parseFloat($("#pct").val()) || 0;
            var finalAmt = parseFloat($("#final_amt_hidden").val()) || 0;
            var pctAmt = (pctValue / 100) * finalAmt;

            $("#pct_amt").val(pctAmt.toFixed(2));
        }

        // Initialize: Hide pct inputs
        hidePctInputs();

        // Show/hide pct inputs based on the selected option
        $("#transfer").change(function() {
            showHidePctInputs($(this).val());
        });

        function showHidePctInputs(transfer = null) {
            if (transfer == "1") {
                showPctInputs();
            } else {
                hidePctInputs();
            }
        }

        let transfer = $('#transfer').find(":selected").val();
        showHidePctInputs(transfer);

        // Update pct_amt when user inputs a number in pct
        $("#pct").on("input", updatePctAmt);
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
        /*if (Number(balance) >= 1) {
            if (Number(balance) <= Number(final_amount)) {
                disableButton('recordSubmit');
            } else {
                enableButton('recordSubmit');
            }
        } else {
            disableButton('recordSubmit');
        }*/
    }

    $(document).on('keyup', "#im_acc_no", function(e) {
        fetchKhaata("#im_acc", "#im_acc_id", "#im_acc_kd_id", "NoDisableBtnRequired");
    });

    $(document).on('keyup', "#np_acc_no", function(e) {
        fetchKhaata("#np_acc", "#np_acc_id", "#np_acc_kd_id", "NoDisableBtnRequired");
    });

    $(document).on('keyup', "#xp_acc_no", function(e) {
        fetchKhaata("#xp_acc", "#xp_acc_id", "#xp_acc_kd_id", "NoDisableBtnRequired");
    });

    function GetBLData(inputField, SrNo, recordSubmitId) {
        let BLNo = $(inputField).val();
        $.ajax({
            url: 'ajax/getPurchaseBLData.php',
            type: 'post',
            data: {
                bl_no: BLNo,
                sr_no: SrNo
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    $(inputField).addClass('is-valid');
                    $(inputField).removeClass('is-invalid');
                    let data = response.data;
                    let loading = data.loading;
                    let receiving = data.receiving;
                    let importer = data.importer;
                    let notify = data.notify;
                    let exporter = data.exporter;
                    let goods = data.goods;
                    let shipping = data.shipping;

                    $('#loading_date').val(loading.loading_date);
                    $('#loading_country').val(loading.loading_country);
                    $('#loading_port_name').val(loading.loading_port_name);

                    $('#receiving_date').val(receiving.receiving_date);
                    $('#receiving_country').val(receiving.receiving_country);
                    $('#receiving_port_name').val(receiving.receiving_port_name);

                    $('#report').val(data.report);

                    $('#im_acc_id').val(importer.im_acc_id);
                    $('#im_acc_no').val(importer.im_acc_no);
                    $('#im_acc_name').val(importer.im_acc_name);
                    khaataCompanies(importer.im_acc_id, 'im_acc_kd_id', function() {
                        $('#im_acc_kd_id').val(importer.im_acc_kd_id);
                    });
                    $('#im_acc_details').val(importer.im_acc_details);

                    $('#np_acc_id').val(notify.np_acc_id);
                    $('#np_acc_no').val(notify.np_acc_no);
                    $('#np_acc_name').val(notify.np_acc_name);
                    khaataCompanies(notify.np_acc_id, 'np_acc_kd_id', function() {
                        $('#np_acc_kd_id').val(notify.np_acc_kd_id);
                    });
                    $('#np_acc_details').val(importer.im_acc_details);

                    $('#xp_acc_id').val(exporter.xp_acc_id);
                    $('#xp_acc_no').val(exporter.xp_acc_no);
                    $('#xp_acc_name').val(exporter.xp_acc_name);
                    khaataCompanies(exporter.xp_acc_id, 'xp_acc_kd_id', function() {
                        $('#xp_acc_kd_id').val(exporter.xp_acc_kd_id);
                    });
                    $('#xp_acc_details').val(exporter.xp_acc_details);

                    // $('#goods_id').val(goods.goods_id);
                    // $('#myquantity_name').val(goods.quantity_name);
                    // $('#quantity_no').val(goods.quantity_no);
                    // $('#size').val(goods.size);
                    // $('#brand').val(goods.brand);
                    // $('#origin').val(goods.origin);
                    // $('#net_weight').val(goods.net_weight);
                    // $('#gross_weight').val(goods.gross_weight);
                    // $('#container_no').val(goods.container_no);

                    $('#shipping_name').val(shipping.shipping_name);
                    $('#shipping_phone').val(shipping.shipping_phone);
                    $('#shipping_whatsapp').val(shipping.shipping_whatsapp);
                    $('#shipping_email').val(shipping.shipping_email);
                    $('#shipping_address').val(shipping.shipping_address);
                }

                if (response.success === false) {
                    disableButton(recordSubmitId);
                    $(inputField).addClass('is-invalid');
                    $(inputField).removeClass('is-valid');
                }
            }
        });
    }

    function GetBLSuggestions() {
        let BLNo = $('#bl_no').val();
        if (BLNo.length < 2) {
            $('#bl_suggestions').hide();
            return;
        }

        $.ajax({
            url: 'ajax/getBLSuggestions.php',
            type: 'post',
            data: {
                bl_no: BLNo
            },
            dataType: 'json',
            success: function(response) {
                let suggestions = response.data;
                if (suggestions.length > 0) {
                    let suggestionList = '';
                    suggestions.forEach(function(item) {
                        suggestionList += `<li class="list-group-item" onclick="selectBLSuggestion(${item.sr_no}, '${item.bl_no}')">#${item.sr_no}. ${item.bl_no}</li>`;
                    });
                    $('#bl_suggestions').html(suggestionList).show();
                } else {
                    $('#bl_suggestions').hide();
                }
            }
        });
    }

    function selectBLSuggestion(sr_no, bl_no) {
        $('#bl_no').val(bl_no);
        $('#bl_suggestions').hide();
        GetBLData('#bl_no', sr_no, 'GLoadingSubmit');
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
                    var datu = data_comp['company_name'] + '\n' +
                        'Country: ' + data_comp['country'] + '\n' +
                        'City: ' + data_comp['city'] + '\n' +
                        'State: ' + data_comp['state'] + '\n' +
                        'Address: ' + data_comp['address'];
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
        $('#im_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'im_acc_details');
            //var kd_id = $(this).val();
        });
        $('#np_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'np_acc_details');
            //var kd_id = $(this).val();
        });
        $('#xp_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'xp_acc_details');
            //var kd_id = $(this).val();
        });
        // updateQuantities();

        // $("#goods_id").change(function() {
        //     var goods_id = $(this).val();
        //     goodDetails(goods_id);
        // });
        $('#goods_id, #size, #brand, #origin').on('change', function() {
            populateFields();
        });
        // $("#quantity_no").change(autoCalc('#quantity_no', '#gross_weight', '#net_weight'));

    });
    // function goodDetails(goods_id) {
    //     if (goods_id > 0) {
    //         // Get existing sizes, brands, and origins from the table
    //         var existingSizes = getTableData('size');
    //         var existingBrands = getTableData('brand');
    //         var existingOrigins = getTableData('origin');

    //         $.ajax({
    //             type: 'POST',
    //             url: 'ajax/fetch_good_details.php',
    //             data: {
    //                 goods_id: goods_id
    //             },
    //             dataType: 'json',
    //             success: function(response) {
    //                 // Ensure the response contains arrays for sizes, brands, and origins
    //                 var allSizes = Array.isArray(response.sizes) ? response.sizes : [];
    //                 var allBrands = Array.isArray(response.brands) ? response.brands : [];
    //                 var allOrigins = Array.isArray(response.origins) ? response.origins : [];

    //                 // Filter sizes to only show those available in the table
    //                 var filteredSizes = allSizes.filter(function(size) {
    //                     return existingSizes.includes(size);
    //                 });

    //                 // Filter brands to only show those available in the table
    //                 var filteredBrands = allBrands.filter(function(brand) {
    //                     return existingBrands.includes(brand);
    //                 });

    //                 // Filter origins to only show those available in the table
    //                 var filteredOrigins = allOrigins.filter(function(origin) {
    //                     return existingOrigins.includes(origin);
    //                 });

    //                 // Populate the Size dropdown
    //                 $('#size').html('<option hidden value="">Select</option>');
    //                 if (filteredSizes.length > 0) {
    //                     $.each(filteredSizes, function(index, size) {
    //                         $('#size').append('<option value="' + size + '">' + size + '</option>');
    //                     });
    //                 } else {
    //                     $('#size').append('<option value="">No available sizes</option>');
    //                 }

    //                 // Populate the Brand dropdown
    //                 $('#brand').html('<option hidden value="">Select</option>');
    //                 if (filteredBrands.length > 0) {
    //                     $.each(filteredBrands, function(index, brand) {
    //                         $('#brand').append('<option value="' + brand + '">' + brand + '</option>');
    //                     });
    //                 } else {
    //                     $('#brand').append('<option value="">No available brands</option>');
    //                 }

    //                 // Populate the Origin dropdown
    //                 $('#origin').html('<option hidden value="">Select</option>');
    //                 if (filteredOrigins.length > 0) {
    //                     $.each(filteredOrigins, function(index, origin) {
    //                         $('#origin').append('<option value="' + origin + '">' + origin + '</option>');
    //                     });
    //                 } else {
    //                     $('#origin').append('<option value="">No available origins</option>');
    //                 }
    //             }
    //         });
    //     } else {
    //         $('#size').html('<option hidden value="">Select</option>');
    //         $('#brand').html('<option hidden value="">Select</option>');
    //         $('#origin').html('<option hidden value="">Select</option>');
    //     }
    // }

    // // Function to get data from the table based on column class name
    // function getTableData(columnClass) {
    //     var values = [];
    //     $('#goodsTable tr').each(function() {
    //         var value = $(this).find('td.' + columnClass).text().trim();
    //         if (value && !values.includes(value)) {
    //             values.push(value);
    //         }
    //     });
    //     return values;
    // }

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
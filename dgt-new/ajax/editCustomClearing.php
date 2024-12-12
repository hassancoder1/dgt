<?php
require_once '../connection.php';
// Existing variables and code...
$page_title = 'EDIT INFORMATION';
$back_page_url = $data_for = $pageURL = $_POST['page'];
$unique_code = $_POST['unique_code'];

[$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');

$LoadingsTable = ($Tcat === 'l' ? 'local' : 'general') . '_loading';
$recordExists = recordExists('data_copies', ['unique_code' => $unique_code]);
$LoadingsData = [];
if ($recordExists) {
    $dataType = "Copied";
    $data = mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $unique_code]));
    $Tdata = json_decode($data['tdata'], true);
    $LoadingsData = json_decode($data['ldata'], true);
} else {
    $dataType = "Original";
    $LoadingsQuery = mysqli_query($connect, "SELECT * FROM $LoadingsTable WHERE p_id='$TID'");
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
function fetchCompanyName($kd_id)
{
    $run_query = fetch('khaata_details', array('id' => $kd_id, 'type' => 'company'));
    if ($row = mysqli_fetch_array($run_query)) {
        $row_data = json_decode($row['json_data']);
        return htmlspecialchars($row_data->company_name ?? 'N/A');
    }
    return 'N/A';
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
            <?php if ($Tcat === 'l') { ?>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row g-3 mt-1">
                            <!-- Purchaser Details -->
                            <div class="col-md-<?= $Tcat === 'l' ? '6' : '4'; ?>">
                                <label class="form-label fw-bold">Purchaser Details</label>
                                <div>
                                    <span><strong>ACC No:</strong> <?= isset($Tdata['dr_acc']) ? htmlspecialchars($Tdata['dr_acc']) : 'N/A'; ?></span><br>
                                    <span><strong>Importer Name:</strong> <?= isset($Tdata['dr_acc_name']) ? htmlspecialchars($Tdata['dr_acc_name']) : 'N/A'; ?></span><br>
                                    <span><strong>Company:</strong>
                                        <?= isset($Tdata['dr_acc_kd_id']) ? fetchCompanyName($Tdata['dr_acc_kd_id']) : 'N/A'; ?>
                                    </span><br>
                                    <span><strong>Company Details:</strong> <?= isset($Tdata['dr_acc_details']) ? nl2br(htmlspecialchars($Tdata['dr_acc_details'])) : 'N/A'; ?></span><br>
                                </div>
                            </div>

                            <!-- Seller Details -->
                            <div class="col-md-<?= $Tcat === 'l' ? '6' : '4'; ?>">
                                <label class="form-label fw-bold">Seller Details</label>
                                <div>
                                    <span><strong>ACC No:</strong> <?= isset($Tdata['cr_acc']) ? htmlspecialchars($Tdata['cr_acc']) : 'N/A'; ?></span><br>
                                    <span><strong>Notify Party Name:</strong> <?= isset($Tdata['cr_acc_name']) ? htmlspecialchars($Tdata['cr_acc_name']) : 'N/A'; ?></span><br>
                                    <span><strong>Company:</strong>
                                        <?= isset($Tdata['cr_acc_kd_id']) ? fetchCompanyName($Tdata['cr_acc_kd_id']) : 'N/A'; ?>
                                    </span><br>
                                    <span><strong>Company Details:</strong> <?= isset($Tdata['cr_acc_details']) ? nl2br(htmlspecialchars($Tdata['cr_acc_details'])) : 'N/A'; ?></span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
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
                    <?php
                    if ($Tcat !== 'l') { ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row g-3 mt-1">
                                    <!-- Importer Details -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Importer Details</label>
                                        <div>
                                            <span><strong>ACC No:</strong> <?= isset($Ldata['im_acc_no']) ? htmlspecialchars($Ldata['im_acc_no']) : 'N/A'; ?></span><br>
                                            <span><strong>Importer Name:</strong> <?= isset($Ldata['im_acc_name']) ? htmlspecialchars($Ldata['im_acc_name']) : 'N/A'; ?></span><br>
                                            <span><strong>Company Details:</strong> <?= isset($Ldata['im_acc_details']) ? nl2br(htmlspecialchars($Ldata['im_acc_details'])) : 'N/A'; ?></span><br>
                                        </div>
                                    </div>

                                    <!-- Exporter Details -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Exporter Details</label>
                                        <div>
                                            <span><strong>ACC No:</strong> <?= isset($Ldata['xp_acc_no']) ? htmlspecialchars($Ldata['xp_acc_no']) : 'N/A'; ?></span><br>
                                            <span><strong>Exporter Name:</strong> <?= isset($Ldata['xp_acc_name']) ? htmlspecialchars($Ldata['xp_acc_name']) : 'N/A'; ?></span><br>
                                            <span><strong>Company Details:</strong> <?= isset($Ldata['xp_acc_details']) ? nl2br(htmlspecialchars($Ldata['xp_acc_details'])) : 'N/A'; ?></span><br>
                                        </div>
                                    </div>

                                    <!-- Notify Party Details -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Notify Party Details</label>
                                        <div>
                                            <span><strong>ACC No:</strong> <?= isset($Ldata['np_acc_no']) ? htmlspecialchars($Ldata['np_acc_no']) : 'N/A'; ?></span><br>
                                            <span><strong>Notify Party Name:</strong> <?= isset($Ldata['np_acc_name']) ? htmlspecialchars($Ldata['np_acc_name']) : 'N/A'; ?></span><br>
                                            <span><strong>Company Details:</strong> <?= isset($Ldata['np_acc_details']) ? nl2br(htmlspecialchars($Ldata['np_acc_details'])) : 'N/A'; ?></span><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row gy-3">
                                <?php if ($Tcat === 'l') {
                                    if ($Ldata['route'] === 'local') { ?>
                                        <div class="col-md-4">
                                            <span><strong>Truck Number:</strong> <?= $Ldata['truck_no'] ?? 'N/A'; ?></span><br>
                                            <span><strong>Truck Name:</strong> <?= $Ldata['truck_name'] ?? 'N/A'; ?></span><br>
                                            <span><strong>Loading Warehouse:</strong> <?= $Ldata['loading_warehouse'] ?? 'N/A'; ?></span><br>
                                            <span><strong>Receiving Warehouse:</strong> <?= $Ldata['receiving_warehouse'] ?? 'N/A'; ?></span><br>
                                            <span><strong>Loading Company:</strong> <?= $Ldata['loading_company_name'] ?? 'N/A'; ?></span><br>
                                            <span><strong>Receiving Company Name:</strong> <?= $Ldata['receiving_company_name'] ?? 'N/A'; ?></span><br>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-4">
                                        <span><strong>Loading Country:</strong> <?= $Ldata['loading_country'] ?? 'N/A'; ?></span><br>
                                        <span><strong>L <?= $Tdata['sea_road'] === 'sea' ? 'Port' : 'Border'; ?> Name:</strong> <?= $Ldata['loading_port_name'] ?? 'N/A'; ?></span><br>
                                        <span><strong>Receiving Country:</strong> <?= $Ldata['receiving_country'] ?? 'N/A'; ?></span><br>
                                        <span><strong>R <?= $Tdata['sea_road'] === 'sea' ? 'Port' : 'Border'; ?> Name:</strong> <?= $Ldata['receiving_port_name'] ?? 'N/A'; ?></span><br>
                                        <span><strong><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name:</strong> <?= $Ldata['shipping_name'] ?? 'N/A'; ?></span><br>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-4">
                                        <span><strong>Loading Country:</strong> <?= $Ldata['loading_country'] ?? 'N/A'; ?></span><br>
                                        <span><strong>L <?= $Tdata['sea_road'] === 'sea' ? 'Port' : 'Border'; ?> Name:</strong> <?= $Ldata['loading_port_name'] ?? 'N/A'; ?></span><br>
                                        <span><strong>Receiving Country:</strong> <?= $Ldata['receiving_country'] ?? 'N/A'; ?></span><br>
                                        <span><strong>R <?= $Tdata['sea_road'] === 'sea' ? 'Port' : 'Border'; ?> Name:</strong> <?= $Ldata['receiving_port_name'] ?? 'N/A'; ?></span><br>
                                    </div>

                                    <div class="col-md-4">
                                        <span><strong><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Name:</strong> <?= $Ldata['shipping_name'] ?? 'N/A'; ?></span><br>
                                        <span><strong><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Address:</strong> <?= $Ldata['shipping_address'] ?? 'N/A'; ?></span><br>
                                        <span><strong><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Phone:</strong> <?= $Ldata['shipping_phone'] ?? 'N/A'; ?></span><br>
                                        <span><strong><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> WhatsApp:</strong> <?= $Ldata['shipping_whatsapp'] ?? 'N/A'; ?></span><br>
                                        <span><strong><?= $Tdata['sea_road'] === 'sea' ? 'Shipping' : 'Transporter'; ?> Email:</strong> <?= $Ldata['shipping_email'] ?? 'N/A'; ?></span><br>
                                    </div>
                                <?php } ?>

                                <div class="col-md-4">
                                    <span><strong>Loading Date:</strong> <?= $Ldata['loading_date'] ?? 'N/A'; ?></span><br>
                                    <span><strong>Receiving Date:</strong> <?= $Ldata['receiving_date'] ?? 'N/A'; ?></span><br>
                                    <span><strong>Cargo Transfer:</strong> <?= isset($Ldata['warehouse_transfer']) && !empty($Ldata['warehouse_transfer']) ? $Ldata['warehouse_transfer'] : 'N/A'; ?></span><br>
                                </div>

                                <?php if ($Ttype === 's') { ?>
                                    <div class="col-md-4">
                                        <span><strong>Current Entries In Selected Warehouse:</strong> <?= isset($Ldata['warehouse_enteries']) && !empty($Ldata['warehouse_enteries']) ? $Ldata['warehouse_enteries'] : 'N/A'; ?></span><br>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <form method="post" class="table-form collapse show" id="collapseTow">
                                <div class="row gy-3">
                                    <div class="col-md-4">
                                        <div class="row gx-1 gy-3">
                                            <div class="col-md-7">
                                                <div><b>Sr# </b> <?= $Ldata['sr_no']; ?></div>
                                                <div class="row g-0">
                                                    <label for="allotment_name" class="col-md-6 col-form-label text-nowrap">Allotment Name</label>
                                                    <div class="col-md-6">
                                                        <input value="<?= isset($Ldata['allotment_name']) ? $Ldata['allotment_name'] : ''; ?>" id="allotment_name"
                                                            name="allotment_name" class="form-control form-control-sm" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <label for="goods_id">GOODS</label>
                                                    <select id="goods_id" name="goods_id" class="form-select form-select-sm" required>
                                                        <option hidden value="">Select</option>
                                                        <?php $goods = fetch('goods');
                                                        while ($good = mysqli_fetch_assoc($goods)) {
                                                            $g_selected = $good['id'] == $Ldata['goods_id'] ? 'selected' : '';
                                                            echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <label for="size">SIZE</label>
                                                    <select class="form-select form-select-sm" name="size" id="size" required>
                                                        <option hidden value="">Select</option>
                                                        <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $Ldata['goods_id']);
                                                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                            $size_selected = $size_s['size'] == $Ldata['size'] ? 'selected' : '';
                                                            echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <label for="origin">ORIGIN</label>
                                                    <select class="form-select form-select-sm" name="origin" id="origin" required>
                                                        <option hidden value="">Select</option>
                                                        <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $Ldata['goods_id']);
                                                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                            $size_selected = $size_s['origin'] == $Ldata['origin'] ? 'selected' : '';
                                                            echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <label for="brand">BRAND</label>
                                                    <!-- <input type="text" name="brand" id="brand" value="<?= $Ldata['brand']; ?>" class="form-control" required> -->
                                                    <select class="form-select form-select-sm" name="brand" id="brand" required>
                                                        <option hidden value="">Select</option>
                                                        <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $Ldata['goods_id']);
                                                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                            $size_selected = $size_s['brand'] == $Ldata['brand'] ? 'selected' : '';
                                                            echo '<option ' . $size_selected . ' value="' . $size_s['brand'] . '">' . $size_s['brand'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 border-end">
                                        <div class="row gx-1 gy-3">
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label for="qty_name" class="col-sm-4 col-form-label text-nowrap">Qty
                                                        Name</label>
                                                    <div class="col-sm">
                                                        <input value="<?php echo $Ldata['qty_name']; ?>" id="qty_name"
                                                            name="qty_name" class="form-control form-control-sm" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="qty_no">Qty#</label>
                                                    <div class="col-sm">
                                                        <input value="<?php echo $Ldata['qty_no']; ?>" id="qty_no"
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
                                                        <input value="<?php echo $Ldata['qty_kgs']; ?>" id="qty_kgs"
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
                                                            id="empty_kgs"
                                                            name="empty_kgs" class="form-control form-control-sm currency" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row g-0">
                                                    <label class="col-sm-4 col-form-label text-nowrap"
                                                        for="divide">DIVIDE</label>
                                                    <div class="col-sm">
                                                        <select id="divide" name="divide" class="form-select">
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
                                                        <input value="<?php echo $Ldata['weight']; ?>" id="weight"
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
                                                        <select id="price" name="price" class="form-select form-select-sm">
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
                                                        <select id="currency1" name="currency1" class="form-select form-select-sm"
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
                                                        <input value="<?php echo $Ldata['rate1']; ?>" id="rate1"
                                                            name="rate1"
                                                            class="form-control form-control-sm currency" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (decode_unique_code($data['unique_code'], 'Tcat') !== 'l'): ?>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="currency2">Currency</label>
                                                        <div class="col-sm">
                                                            <select id="currency2" name="currency2" class="form-select form-select-sm"
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
                                                            <input value="<?php echo $Ldata['rate2']; ?>" id="rate2"
                                                                name="rate2"
                                                                class="form-control form-control-sm currency" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                                        <div class="col-sm">
                                                            <select id="opr" name="opr" class="form-select form-select-sm" required>
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
                                                            <input type="text" value="<?php echo $Ldata['tax_percent']; ?>" id="tax_percent"
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
                                                            <input type="text" value="<?php echo $Ldata['tax_amount']; ?>" id="tax_amount"
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
                                                            <input type="hidden" value="<?php echo $Ldata['total_with_tax']; ?>" id="total_with_tax"
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
                                                echo '<tr><th class="fw-normal">TOTAL KGs </th><th><span id="total_kgs_span"></span></th></tr>';
                                                echo '<tr><th class="fw-normal">TOTAL QTY KGs </th><th><span id="total_qty_kgs_span"></span></th></tr>';
                                                echo '<tr><th class="fw-normal">NET KGs </th><th><span id="net_kgs_span"></span></th></tr>';
                                                echo '<tr><th class="fw-normal">TOTAL </th><th><span id="total_span"></span></th></tr>';
                                                echo '<tr><th class="fw-normal">AMOUNT  </th><th><span id="amount_span"></span></th></tr>';
                                                if (decode_unique_code($data['unique_code'], 'Tcat') !== 'l') {
                                                    echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span id="final_amount_span"></span></th></tr>';
                                                } else {
                                                    echo '<tr><th class="fw-normal text-danger">Amt+Tax  </th><th><span id="total_with_tax_span">0</span></th></tr>';
                                                };
                                                ?>
                                            </tbody>
                                        </table>
                                        <input value="<?php echo $Ldata['total_kgs']; ?>" id="total_kgs"
                                            name="total_kgs" type="hidden">
                                        <input value="<?php echo $Ldata['total_qty_kgs']; ?>" id="total_qty_kgs"
                                            name="total_qty_kgs"
                                            type="hidden">
                                        <input value="<?php echo $Ldata['net_kgs']; ?>" id="net_kgs" name="net_kgs"
                                            type="hidden">
                                        <input value="<?php echo $Ldata['total']; ?>" id="total" name="total"
                                            type="hidden">
                                        <input value="<?php echo $Ldata['amount']; ?>" id="amount" name="amount"
                                            type="hidden">
                                        <input value="<?php echo $Ldata['final_amount']; ?>" id="final_amount"
                                            name="final_amount" type="hidden">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-md-1">
                                    <label for="ag_acc_no" class="form-label">Acc No</label>
                                    <input type="text" name="ag_acc_no" id="ag_acc_no" required class="form-control form-control-sm" value="<?= $Ldata['ag_acc_no'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="ag_name" class="form-label">AGENT NAME</label>
                                    <input type="text" name="ag_name" id="ag_name" required class="form-control form-control-sm" value="<?= $Ldata['ag_name'] ?? ''; ?>">
                                </div>

                                <div class="col-md-2">
                                    <label for="ag_id" class="form-label">AGENT ID</label>
                                    <input type="text" name="ag_id" id="ag_id" value="<?= $Ldata['ag_id'] ?? ''; ?>" required class="form-control form-control-sm">
                                </div>
                                <?php $warehouse = $Ldata['cargo_transfer_warehouse'] ?? ''; ?>
                                <div class="col-md-3">
                                    <label for="l_<?= $l_ID; ?>_cargo_transfer" class="form-label">Cargo Transfer</label>
                                    <select id="l_<?= $l_ID; ?>_cargo_transfer" name="l_<?= $l_ID; ?>_cargo_transfer" class="form-select form-select-sm" required>
                                        <option disabled <?= empty($warehouse) ? 'selected' : '' ?>>Select One</option>
                                        <option value="Local Import" <?= isset($warehouse) && $warehouse === 'Local Import' ? 'selected' : '' ?>>Local Import</option>
                                        <option value="Free Zone Import" <?= isset($warehouse) && $warehouse === 'Free Zone Import' ? 'selected' : '' ?>>Free Zone Import</option>
                                        <option value="Import Re-Export" <?= isset($warehouse) && $warehouse === 'Import Re-Export' ? 'selected' : '' ?>>Import Re-Export</option>
                                        <option value="Transit" <?= isset($warehouse) && $warehouse === 'Transit' ? 'selected' : '' ?>>Transit</option>
                                        <option value="Local Export" <?= isset($warehouse) && $warehouse === 'Local Export' ? 'selected' : '' ?>>Local Export</option>
                                        <option value="Local Market" <?= isset($warehouse) && $warehouse === 'Local Market' ? 'selected' : '' ?>>Local Market</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="received_date" class="form-label">Received Date</label>
                                    <input type="date" name="received_date" id="received_date" required value="<?= $Ldata['received_date'] ?? ''; ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label for="clearing_date" class="form-label">Clearing Date</label>
                                    <input type="date" name="clearing_date" id="clearing_date" required class="form-control form-control-sm" value="<?= $Ldata['clearing_date'] ?? ''; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="bill_of_entry_no" class="form-label">Bill Of Entry No</label>
                                    <input type="text" name="bill_of_entry_no" id="bill_of_entry_no" required class="form-control form-control-sm" value="<?= $Ldata['bill_of_entry_no'] ?? ''; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="loading_truck_number" class="form-label">Loading Truck Number</label>
                                    <input type="text" name="loading_truck_number" id="loading_truck_number" required class="form-control form-control-sm" value="<?= $Ldata['loading_truck_number'] ?? ''; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="truck_returning_date" class="form-label">Truck Retruning Date</label>
                                    <input type="date" name="truck_returning_date" id="truck_returning_date" required class="form-control form-control-sm" value="<?= $Ldata['truck_returning_date'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            endforeach; ?>
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
                <b><?= strtoupper($Tdata['p_s_name']) . ' #'; ?> </b><?= $Tdata['sr_no']; ?>
            </div>
            <div><b>User </b><?= $Tdata['username']; ?></div>
            <div><b>Date </b><?= my_date($Tdata['_date']); ?></div>
            <div><b>Type </b><?= badge(strtoupper($Tdata['type']), 'dark'); ?></div>
            <div><b>Country </b><?= $Tdata['country']; ?></div>
            <div><b>Branch </b><?= branchName($Tdata['branch_id']); ?></div>
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
<script>
    function currentStock(event, LID, goodsID, size, brand, origin, qtyName) {
        // console.log(goodsID, size, brand, origin, qtyName);
        let targetPrefix = '#l_' + LID + '_';
        let selectedWarehouse = $(targetPrefix + 'warehouse_transfer').val() ?? '';
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
                var data = JSON.parse(res);
                $(targetPrefix + 'warehouse_enteries').html(data.html.trim());
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
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
<script type="text/javascript">
    $(document).on('keyup', "#dr_acc", function(e) {
        fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "purchaseSubmit");
    });
    //fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "purchaseSubmit");

    $(document).on('keyup', "#cr_acc", function(e) {
        fetchKhaata("#cr_acc", "#cr_acc_id", "#cr_acc_kd_id", "purchaseSubmit");
    });

    $(document).on('keyup', "#np_acc", function(e) {
        fetchKhaata("#np_acc", "#np_acc_id", "#np_acc_kd_id", "notifyPartyDetailsSubmit");
    });

    $(document).on('keyup', "#search_acc_no", function(e) {
        fillTPBankDetails("#search_acc_no", "#search_acc_id", "thirdPartyBankSubmit");
    });

    function decodeSpecialCharacters(string) {
        const replacements = {
            'u0027': "'",
            'u0022': '"',
            'u0026': '&',
            'u003C': '<',
            'u003E': '>',
            'u0021': '!',
            'u002C': ',',
            'u002E': '.',
            'u003B': ';',
            'u003A': ':',
            'u003F': '?',
            'u0040': '@',
            'u002B': '+',
            'u002D': '-',
            'u002F': '/',
            'u005C': '\\',
            'u0028': '(',
            'u0029': ')',
            'u007B': '{',
            'u007D': '}',
            'u005B': '[',
            'u005D': ']',
            'u00A0': ' '
        };

        // Loop through replacements and replace each key in the string
        for (const key in replacements) {
            string = string.split(key).join(replacements[key]);
        }

        return string;
    }


    function fillTPBankDetails(inputFieldID, UniqueIDInput, SubmitButtonId) {
        let khaata_no = $(inputFieldID).val();
        disableButton(SubmitButtonId);
        $.ajax({
            type: 'POST',
            url: 'ajax/khaata_by_id.php',
            data: {
                khaata_no: khaata_no
            },
            success: function(response) {
                enableButton(SubmitButtonId);
                $(SubmitButtonId).prop('disabled', false);

                let data = JSON.parse(response) ? JSON.parse(response) : '';
                $(UniqueIDInput).val(data.id);

                if (data.bank_details) {
                    $('#responseText').html("Data Retrieved From (A/C No. " + khaata_no + ")");
                    $('#responseText').addClass('text-success bg-success');
                    $('#responseText').removeClass('text-danger bg-danger');
                    $(inputFieldID).addClass('is-valid');
                    $(inputFieldID).removeClass('is-invalid');

                    // Decode special characters in bank_details
                    let bank_details = JSON.parse(decodeSpecialCharacters(data.bank_details));

                    // Clear the existing values
                    $('#acc_no').val(bank_details.acc_no);
                    $('#acc_name').val(bank_details.acc_name);
                    $('#b_company').val(bank_details.company);
                    $('#iban').val(bank_details.iban);
                    $('#branch_code').val(bank_details.branch_code);
                    $('#currency').val(bank_details.currency);
                    $('#bank_country').val(bank_details.country);
                    $('#bank_state').val(bank_details.state);
                    $('#bank_city').val(bank_details.city);
                    $('#bank_address').val(bank_details.address);

                    // Remove previous contact rows
                    $(".contact_row").remove();

                    // Loop through indexes4 and vals4 arrays
                    if (bank_details.indexes4 && bank_details.vals4) {
                        let arrayNumber = 0;

                        bank_details.indexes4.forEach(function(indexValue, index) {
                            // Create dynamic row with dropdowns and inputs
                            let rowHtml = `
                        <tr class="col-md-6 contact_row contact_row_${arrayNumber}">
                            <td onclick="removeContactRow(this)">
                                <i class="fa fa-close fa-2xl btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                            </td>
                            <td class="w-50">
                                <select name="indexes4[]" class="form-select contact_indexes">
                                    <?php
                                    $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                        echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="w-50">
                                <input name="vals4[]" required placeholder="Value ${index + 1}" class="form-control contact_vals" value="${bank_details.vals4[index]}">
                            </td>
                        </tr>
                        `;

                            // Append the new row to the table
                            $("#contact_table_body").append(rowHtml);

                            // Preselect the correct option for the index
                            $(`.contact_row_${arrayNumber} .contact_indexes option[value='${indexValue}']`).prop('selected', true);

                            arrayNumber++;
                        });
                    }
                } else {
                    $('#responseText').html("Data Not Found For (A/C No. " + khaata_no + ")");
                    $('#responseText').removeClass('text-success bg-success');
                    $('#responseText').addClass('text-danger bg-danger');
                    $(inputFieldID).removeClass('is-valid');
                    $(inputFieldID).addClass('is-invalid');
                    $('#acc_no').val('');
                    $('#acc_name').val('');
                    $('#b_company').val('');
                    $('#iban').val('');
                    $('#branch_code').val('');
                    $('#currency').val('');
                    $('#bank_country').val('');
                    $('#bank_state').val('');
                    $('#bank_city').val('');
                    $('#bank_address').val('');
                    $(".contact_row").remove();
                }
            },
            error: function(xhr, status, error) {
                // Handle error if needed
            }
        });
    }



    function fetchKhaata(inputField, khaataId, kd_dropdown, recordSubmitId) {

        let khaata_no = $(inputField).val();
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
                    $(inputField).addClass('is-valid');
                    $(inputField).removeClass('is-invalid');
                    $(inputField + '_name').val(response.messages['khaata_name']);
                    if (inputField == '#dr_acc') {
                        khaataCompanies(khaata_id_this, 'dr_acc_kd_id');
                        var dr_acc_kd_id = $('#dr_acc_kd_id').find(":selected").val();
                        khaataDetailsSingle(dr_acc_kd_id, 'dr_acc_details');
                    }
                    if (inputField == '#cr_acc') {
                        khaataCompanies(khaata_id_this, 'cr_acc_kd_id');
                        var cr_acc_kd_id = $('#cr_acc_kd_id').find(":selected").val();
                        khaataDetailsSingle(cr_acc_kd_id, 'cr_acc_details');
                    }

                    if (inputField == '#np_acc') {
                        khaataCompanies(khaata_id_this, 'np_acc_kd_id');
                        var np_acc_kd_id = $('#np_acc_kd_id').find(":selected").val();
                        khaataDetailsSingle(np_acc_kd_id, 'np_acc_details');
                    }
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    $(inputField).addClass('is-invalid');
                    $(inputField).removeClass('is-valid');
                    $(khaataId).val(0);
                    $(kd_dropdown).html('<option value="0">Invalid A/c.</option>');
                }
            },
            error: function(e) {
                $(inputField).html('<option value="0">Invalid A/c.</option>');
            }
        });
    }

    function khaataCompanies(khaata_id, dropdown_id) {
        if (khaata_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/companies_dropdown_by_khaata_id.php',
                data: {
                    khaata_id: khaata_id
                },
                success: function(html) {
                    $('#' + dropdown_id).html(html);
                },
                error: function(xhr, status, error) {
                    //console.error("AJAX call failed:", status, error); // Debugging line
                }
            });
        } else {
            $('#' + dropdown_id).html('<option value="0">FAILED</option>');
        }
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
                    console.log(data_comp);
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
        $('#dr_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'dr_acc_details');
            //var kd_id = $(this).val();
        });
        $('#cr_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'cr_acc_details');
            //var kd_id = $(this).val();
        });
        $('#np_acc_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), 'np_acc_details');
            //var kd_id = $(this).val();
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        finalAmount();
        $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#rate2,#opr').on('keyup', function() {
            finalAmount();
        });
        $("#goods_id").change(function() {
            var goods_id = $(this).val();
            goodDetails(goods_id);
        });
        $("#type").change(function() {
            $('#bookingForm').toggleClass('d-none row');
            $('#localForm').toggleClass('d-none row');
        });
    });

    function goodDetails(goods_id) {
        if (goods_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_sizes.php',
                data: 'goods_id=' + goods_id,
                success: function(html) {
                    $('#size').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_brands.php',
                data: 'goods_id=' + goods_id,
                success: function(html) {
                    $('#brand').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_origins.php',
                data: 'goods_id=' + goods_id,
                success: function(html) {
                    $('#origin').html(html);
                }
            });
        } else {
            $('#size').html('<option value="">Select</option>');
            $('#brand').html('<option value="">Select</option>');
            $('#origin').html('<option value="">Select</option>');
        }
    }

    function finalAmount() {
        var qty_no = parseFloat($("#qty_no").val()) || 0;
        var qty_kgs = parseFloat($("#qty_kgs").val()) || 0;

        var total_kgs = qty_no * qty_kgs;
        $("#total_kgs").val(total_kgs);
        $("#total_kgs_span").text(total_kgs);

        var empty_kgs = parseFloat($("#empty_kgs").val()) || 0;
        var total_qty_kgs = qty_no * empty_kgs;
        $("#total_qty_kgs").val(total_qty_kgs);
        $("#total_qty_kgs_span").text(total_qty_kgs);

        var net_kgs = total_kgs - total_qty_kgs;
        $("#net_kgs").val(net_kgs);
        $("#net_kgs_span").text(net_kgs);

        var weight = parseFloat($("#weight").val()) || 0;
        var total = 0;

        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = total.toFixed(3);
        }

        $("#total").val(isNaN(total) ? '' : total);
        $("#total_span").text(isNaN(total) ? '' : total);

        var rate1 = parseFloat($("#rate1").val()) || 0;
        var final_amount = 0;
        var amount = 0;

        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
            amount = total * rate1;
            amount = amount.toFixed(3);
            final_amount = amount;
        }

        $("#amount").val(isNaN(amount) ? '' : amount);
        $("#amount_span").text(isNaN(amount) ? '' : amount);
        updateTaxAndTotal();
        //if ($("#is_qty").prop('checked') == true) {
        var rate2 = parseFloat($("#rate2").val()) || 0;
        let operator = $('#opr').find(":selected").val();

        if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
            final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
            final_amount = final_amount.toFixed(3);
        }
        //}

        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');
        $("#final_amount_span").text(isFinite(final_amount) ? final_amount : '');

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }

    function updateTaxAndTotal() {
        let amount = parseFloat($('#amount_span').text()) || 0;
        let taxPercent = parseFloat($('#tax_percent').val()) || 0;
        let taxAmount = (amount * (taxPercent / 100)).toFixed(2);
        let totalWithTax = (amount + parseFloat(taxAmount)).toFixed(2);
        $('#tax_amount').val(taxAmount != 0 ? taxAmount : '');
        $('#total_with_tax').val(totalWithTax);
        $('#total_with_tax_span').text(totalWithTax);
    }
</script>
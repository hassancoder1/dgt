<?php
require_once '../connection.php';
$page_title = 'EDIT INFORMATION';
$back_page_url = 'local-warehouse-transfer-goods';
$pageURL = "local-warehouse-transfer-goods";
$id = $_POST['id'];
global $userId, $userName, $branchId;
$_fields = [
    'username' => $userName,
    'branch_id' => $branchId,
    'p_s' => '',
    'type' => '',
    'active' => 1,
    'locked' => 0,
    '_date' => date('Y-m-d'),
    'country' => '',
    'delivery_terms' => '',
    'dr_acc' => '',
    'dr_acc_name' => '',
    'dr_acc_details' => '',
    'dr_acc_id' => 0,
    'dr_acc_kd_id' => 0,
    'cr_acc' => '',
    'cr_acc_name' => '',
    'cr_acc_details' => '',
    'cr_acc_id' => 0,
    'cr_acc_kd_id' => 0,
    'transaction_accounts_dr_id' => 0,
    'transaction_accounts_cr_id' => 0
];
$_fields = transactionSingle($id);
$Routes = json_decode(mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM transactions WHERE id='$id'"))['sea_road'], true);
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
    'route' => $Routes['route']
];
$last_record = ['report' => ''];

$Routes = [
    'uid' => $LLoadingRoute['uid'] ?? '',
    'truck_no' => $LLoadingRoute['truck_no'] ?? '',
    'truck_name' => $LLoadingRoute['truck_name'] ?? '',
    'loading_company_name' => $LLoadingRoute['loading_company_name'] ?? '',
    'receiving_company_name' => $LLoadingRoute['receiving_company_name'] ?? '',
    'loading_date' => $LLoadingRoute['loading_date'] ?? '',
    'receiving_date' => $LLoadingRoute['receiving_date'] ?? '',
    'loading_warehouse' => $LLoadingRoute['loading_warehouse'] ?? '',
    'receiving_warehouse' => $LLoadingRoute['receiving_warehouse'] ?? '',
    'warehouse_transfer' => $LLoadingRoute['warehouse_transfer'] ?? '',
    'route' => $LLoadingRoute['route'] ?? '',
];

?>
<div class="modal-header bg-white mb-2">
    <h5 class="modal-title" id="staticBackdropLabel">EDIT INFORMATION</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="row">
    <div class="col-md-9">
        <form action="POST">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="dr_acc" class="form-label fw-bold">Purchaser Details</label>
                            <div class="input-group">
                                <input type="hidden" name="dr_acc_id" value="<?= isset($_fields['dr_acc_id']) ? $_fields['dr_acc_id'] : ''; ?>" id="dr_acc_id">
                                <input type="text" id="dr_acc" name="dr_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                    placeholder="ACC No" value="<?= isset($_fields['dr_acc']) ? $_fields['dr_acc'] : ''; ?>">
                                <input type="text" id="dr_acc_name" name="dr_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                    placeholder="Importer Name" value="<?= isset($_fields['dr_acc_name']) ? $_fields['dr_acc_name'] : ''; ?>">
                            </div>
                            <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="dr_acc_kd_id" id="dr_acc_kd_id">
                                <option hidden value="">Select Company</option>
                                <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($_fields['dr_acc_id']) ? $_fields['dr_acc_id'] : '', 'type' => 'company'));
                                while ($row = mysqli_fetch_array($run_query)) {
                                    $row_data = json_decode($row['json_data']);
                                    $sel_kd2 = $row['id'] == $_fields['dr_acc_kd_id'] ? 'selected' : '';
                                    echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                }  ?>
                            </select>
                            <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="dr_acc_details" id="dr_acc_details" rows="5"
                                placeholder="Company Details"><?= isset($_fields['dr_acc_details']) ? $_fields['dr_acc_details'] : ''; ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="cr_acc" class="form-label fw-bold">Seller Details</label>
                            <div class="input-group">
                                <input type="hidden" name="cr_acc_id" value="<?= isset($_fields['cr_acc_id']) ? $_fields['cr_acc_id'] : ''; ?>" id="cr_acc_id">
                                <input type="text" id="cr_acc" name="cr_acc" class="form-control form-control-sm form-control form-control-sm-sm w-25"
                                    placeholder="ACC No" value="<?= isset($_fields['cr_acc']) ? $_fields['cr_acc'] : ''; ?>">
                                <input type="text" id="cr_acc_name" name="cr_acc_name" class="form-control form-control-sm form-control form-control-sm-sm w-75"
                                    placeholder="Notify Party Name" value="<?= isset($_fields['cr_acc_name']) ? $_fields['cr_acc_name'] : ''; ?>">
                            </div>
                            <select class="form-select form-select-sm form-select form-select-sm-sm mt-2" name="cr_acc_kd_id" id="cr_acc_kd_id">
                                <option hidden value="">Select Company</option>
                                <?php $run_query = fetch('khaata_details', array('khaata_id' => isset($_fields['cr_acc_id']) ? $_fields['cr_acc_id'] : '', 'type' => 'company'));
                                while ($row = mysqli_fetch_array($run_query)) {
                                    $row_data = json_decode($row['json_data']);
                                    $sel_kd2 = $row['id'] == $_fields['cr_acc_kd_id'] ? 'selected' : '';
                                    echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                }  ?>
                            </select>
                            <textarea class="form-control form-control-sm form-control form-control-sm-sm mt-2" name="cr_acc_details" id="cr_acc_details" rows="5"
                                placeholder="Company Details"><?= isset($_fields['cr_acc_details']) ? $_fields['cr_acc_details'] : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row gy-3">
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

                        <div class="col-md-3">
                            <label for="warehouse_transfer" class="form-label">Cargo Transfer</label>
                            <select id="warehouse_transfer" name="warehouse_transfer" class="form-select form-select-sm" required>
                                <option disabled <?= empty($Transfer['warehouse_transfer']) ? 'selected' : '' ?>>Select One</option>
                                <option value="Free Zone" <?= isset($Transfer['warehouse_transfer']) && $Transfer['warehouse_transfer'] === 'Free Zone' ? 'selected' : '' ?>>Freezone Warehouse</option>
                                <option value="OFF Site" <?= isset($Transfer['warehouse_transfer']) && $Transfer['warehouse_transfer'] === 'OFF Site' ? 'selected' : '' ?>>Offsite Warehouse</option>
                                <option value="Transit" <?= isset($Transfer['warehouse_transfer']) && $Transfer['warehouse_transfer'] === 'Transit' ? 'selected' : '' ?>>Transit Warehouse</option>
                            </select>
                        </div>
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
                                class="btn btn-warning btn-sm rounded-0">
                                <i class="fa fa-paper-plane"></i> Update </button>
                            <div class="text-danger fw-bold mt-1 d-none show_complete_msg">Remaining Quantity is Now Zero (<?= $remaining_quantity_no; ?>)</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-3">
        <div class="card p-3" style="height:80vh;">
            <div>
                <b><?php echo strtoupper($_fields['p_s_name']) . ' #'; ?> </b><?php echo $_fields['sr_no']; ?>
            </div>
            <div><b>User </b><?php echo $_fields['username']; ?></div>
            <!-- </div>
                                <div class="d-flex align-items-center justify-content-between"> -->
            <div><b>Date </b><?php echo my_date($_fields['_date']); ?></div>
            <div><b>Type </b><?php echo badge(strtoupper($_fields['type']), 'dark'); ?></div>
            <!-- </div>
                                <div class="d-flex align-items-center justify-content-between"> -->
            <div><b>Country </b><?php echo $_fields['country']; ?></div>
            <div><b>Branch </b><?php echo branchName($_fields['branch_id']); ?></div>
            <!-- </div>
                                <div class="d-flex align-items-center justify-content-between"> -->
            <div><b>Status </b>
                <?php if ($_fields['locked'] == 0) {
                    echo $_fields['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                } else {
                    echo '<i class="fa fa-lock text-success"></i> Transferred.';
                } ?>
            </div>
        </div>
    </div>
</div>
<?php require_once '../connection.php'; ?>
<div class="card mt-3">
    <div class="card-body p-3">
        <form method="post" class="table-form" enctype="multipart/form-data">
            <?php
            $action = 'new';
            $last_record = [];
            $last_record['bl_no'] = '';
            $Importer = ['im_acc_id' => '', 'im_acc_no' => '', 'im_acc_name' => '', 'im_acc_kd_id' => '', 'im_acc_details' => ''];
            $Notify = ['np_acc_id' => '', 'np_acc_no' => '', 'np_acc_name' => '', 'np_acc_kd_id' => '', 'np_acc_details' => ''];
            $Exporter = ['xp_acc_id' => '', 'xp_acc_no' => '', 'xp_acc_name' => '', 'xp_acc_kd_id' => '', 'xp_acc_details' => ''];
            $Goods = ['goods_id' => '', 'quantity_no' => '', 'quantity_name' => '', 'size' => '', 'brand' => '', 'origin' => '', 'net_weight' => '', 'gross_weight' => '', 'container_no' => ''];
            $Shipping = ['shipping_name' => '', 'shipping_phone' => '', 'shipping_whatsapp' => '', 'shipping_email' => '', 'shipping_address' => ''];
            $Loading = ['loading_date' => '', 'loading_country' => '', 'loading_port_name' => ''];
            $Receiving = ['receiving_date' => '', 'receiving_country' => '', 'receiving_port_name' => ''];
            $last_record['report'] = '';
            ?>
            <div style="width:100%; display: flex; justify-content: space-between; margin-bottom: 2px;">
                <h5 class="text-primary">General Information</h5>
            </div>
            <hr>
            <span><b>Date Today: </b><?= my_date(date('Y-m-d')); ?></span>
            <div class="row g-3 mt-2">
                <div class="col-md-1">
                    <label for="sr_no" class="form-label">Sr#</label>
                    <input type="number" name="sr_no" id="sr_no" required readonly class="form-control form-control-sm" value="<?php echo $next_sr_no; ?>">
                </div>

                <!-- B/L No (small field) -->
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
                    <label for="loading_port_name" class="form-label">L PORT Name</label>
                    <input type="text" name="loading_port_name" id="loading_port_name" value="<?= $Loading['loading_port_name']; ?>" required class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="receiving_country" class="form-label">Receiving Country</label>
                    <input type="text" name="receiving_country" id="receiving_country" value="<?= $Receiving['receiving_country']; ?>" required class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="receiving_port_name" class="form-label">R PORT Name</label>
                    <input type="text" name="receiving_port_name" id="receiving_port_name" value="<?= $Receiving['receiving_port_name']; ?>" required class="form-control form-control-sm">
                </div>

                <div class="col-md-5">
                    <label for="report" class="form-label">Report</label>
                    <input type="text" name="report" id="report" required value="<?= $last_record['report']; ?>" class="form-control form-control-sm">
                </div>
            </div>
        </form>
    </div>
</div>
<div>
<div class="mt-1">
                                <label for="show_in_vat" class="form-label">
                                    <i class="fas fa-exchange-alt me-1"></i>
                                    VAT Transfer
                                </label>
                                <select id="show_in_vat" class="form-select form-select-sm" name="show_in_vat" required>
                                    <option value="no" <?= $item_fields['show_in_vat'] === 'no' ? 'selected' : ''; ?>>No</option>
                                    <option value="yes" <?= $item_fields['show_in_vat'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>
                            <div class="mt-1">
                                <label class="form-label">
                                    <i class="fas fa-truck-loading me-1"></i>
                                    Loading Transfer
                                </label>
                                <select class="form-select form-select-sm" name="show_in_loading" required>
                                    <option value="no" <?= $item_fields['show_in_loading'] === 'no' ? 'selected' : ''; ?>>No</option>
                                    <option value="yes" <?= $item_fields['show_in_loading'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>
                            <div class="mt-1">
                                <label class="form-label">
                                    <i class="fas fa-warehouse me-1"></i>
                                    Warehouse Transfer
                                </label>
                                <select class="form-select form-select-sm" name="show_in_warehouse" required>
                                    <option value="no" <?= $item_fields['show_in_warehouse'] === 'no' ? 'selected' : ''; ?>>No</option>
                                    <option value="yes" <?= $item_fields['show_in_warehouse'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>

</div>
<?php if ($id > 0) { ?>
            <div class="card mb-2">
                <div class="position-absolute end-0 top-0">
                    <a class="btn btn-link text-dark" data-bs-toggle="collapse" href="#collapseTow" role="button"
                        aria-expanded="false" aria-controls="collapseTow">
                        <i class="fa fa-angle-down"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form method="post" class="table-form collapse show" id="collapseTow">
                        <input type="hidden" name="sr" value="<?= $item_fields['sr']; ?>">
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <div class="row gx-1 gy-3">
                                    <div><b>Sr# </b> <?php echo $item_fields['sr']; ?></div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="allotment_name" class="col-md-6 col-form-label text-nowrap">Allotment Name</label>
                                            <div class="col-md-6">
                                                <input value="<?= isset($item_fields['allotment_name']) ? $item_fields['allotment_name'] : ''; ?>" id="allotment_name"
                                                    name="allotment_name" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="show_in_vat">VAT Transfer: </label>
                                            <select class="form-select" name="show_in_vat" id="show_in_vat" required>
                                                <option value="no" <?= $item_fields['show_in_vat'] === 'no' ? 'selected' : ''; ?>>No</option>
                                                <option value="yes" <?= $item_fields['show_in_vat'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="goods_id">GOODS</label>
                                            <select id="goods_id" name="goods_id" class="form-select" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods = fetch('goods');
                                                while ($good = mysqli_fetch_assoc($goods)) {
                                                    $g_selected = $good['id'] == $item_fields['goods_id'] ? 'selected' : '';
                                                    echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="show_in_loading">Loading Trans</label>
                                            <select class="form-select" name="show_in_loading" id="show_in_loading" required>
                                                <option value="no" <?= $item_fields['show_in_loading'] === 'no' ? 'selected' : ''; ?>>No</option>
                                                <option value="yes" <?= $item_fields['show_in_loading'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="size">SIZE</label>
                                            <select class="form-select" name="size" id="size" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['size'] == $item_fields['size'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="show_in_warehouse">Warehouse</label>
                                            <select class="form-select" name="show_in_warehouse" id="show_in_warehouse" required>
                                                <option value="no" <?= $item_fields['show_in_warehouse'] === 'no' ? 'selected' : ''; ?>>No</option>
                                                <option value="yes" <?= $item_fields['show_in_warehouse'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="origin">ORIGIN</label>
                                            <select class="form-select" name="origin" id="origin" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['origin'] == $item_fields['origin'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="brand">BRAND</label>
                                            <input type="text" name="brand" id="brand" value="<?= $item_fields['brand']; ?>" class="form-control" required>
                                            <!-- <select class="form-select" name="brand" id="brand" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['brand'] == $item_fields['brand'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['brand'] . '">' . $size_s['brand'] . '</option>';
                                                } ?>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label for="quality_report">Report</label>
                                            <textarea type="text" name="quality_report" id="quality_report" class="form-control" rows="2"><?= $item_fields['quality_report']; ?></textarea>
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
                                                <input value="<?php echo $item_fields['qty_name']; ?>" id="qty_name"
                                                    name="qty_name" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="qty_no">Qty#</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['qty_no']; ?>" id="qty_no"
                                                    name="qty_no"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap" for="qty_kgs">Qty
                                                KGs</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['qty_kgs']; ?>" id="qty_kgs"
                                                    name="qty_kgs"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap" for="empty_kgs">Empty
                                                KGs</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['empty_kgs']; ?>"
                                                    id="empty_kgs"
                                                    name="empty_kgs" class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="divide">DIVIDE</label>
                                            <div class="col-sm">
                                                <select id="divide" name="divide" class="form-select">
                                                    <?php $divides = array('D/TON' => 'D/TON', 'D/KGs' => 'D/KG', 'D/CARTON' => 'D/CARTON', 'D/PP BAGS' => 'D/PP BAGS');
                                                    foreach ($divides as $item => $val) {
                                                        $d_sel = $item_fields['divide'] == $val ? 'selected' : '';
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
                                                <input value="<?php echo $item_fields['weight']; ?>" id="weight"
                                                    name="weight"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="price">PRICE</label>
                                            <div class="col-sm">
                                                <select id="price" name="price" class="form-select">
                                                    <?php $prices = array('P/TON' => 'P/TON', 'P/KGs' => 'P/KG', 'P/CARTON' => 'P/CARTON', 'P/PP BAGS' => 'P/PP BAGS');
                                                    foreach ($prices as $item => $val) {
                                                        $pr_sel = $item_fields['price'] == $val ? 'selected' : '';
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
                                                <select id="currency1" name="currency1" class="form-select"
                                                    required>
                                                    <option selected hidden disabled value="">Select</option>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $crr_sel = $crr['name'] == $item_fields['currency1'] ? 'selected' : '';
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
                                                <input value="<?php echo $item_fields['rate1']; ?>" id="rate1"
                                                    name="rate1"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($_GET['type'] !== 'local'): ?>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="currency2">Currency</label>
                                                <div class="col-sm">
                                                    <select id="currency2" name="currency2" class="form-select"
                                                        required>
                                                        <option selected hidden disabled value="">Select</option>
                                                        <?php $currencies = fetch('currencies');
                                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                                            $crr_sel2 = $crr['name'] == $item_fields['currency2'] ? 'selected' : '';
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
                                                    <input value="<?php echo $item_fields['rate2']; ?>" id="rate2"
                                                        name="rate2"
                                                        class="form-control currency" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                                <div class="col-sm">
                                                    <select id="opr" name="opr" class="form-select" required>
                                                        <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                        foreach ($ops as $opName => $op) {
                                                            $op_sel = $item_fields['opr'] == $op ? 'selected' : '';
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
                                                    <input type="text" value="<?php echo $item_fields['tax_percent']; ?>" id="tax_percent"
                                                        name="tax_percent"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="tax_amount">Tax.Amt</label>
                                                <div class="col-sm">
                                                    <input type="text" value="<?php echo $item_fields['tax_amount']; ?>" id="tax_amount"
                                                        name="tax_amount"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <!-- <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="total_with_tax">Amt+Tax</label> -->
                                                <div class="col-sm">
                                                    <input type="hidden" value="<?php echo $item_fields['total_with_tax']; ?>" id="total_with_tax"
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
                                        if ($_GET['type'] !== 'local') {
                                            echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span id="final_amount_span"></span></th></tr>';
                                        } else {
                                            echo '<tr><th class="fw-normal text-danger">Amt+Tax  </th><th><span id="total_with_tax_span">0</span></th></tr>';
                                        };
                                        ?>
                                    </tbody>
                                </table>
                                <input value="<?php echo $item_fields['total_kgs']; ?>" id="total_kgs"
                                    name="total_kgs" type="hidden">
                                <input value="<?php echo $item_fields['total_qty_kgs']; ?>" id="total_qty_kgs"
                                    name="total_qty_kgs"
                                    type="hidden">
                                <input value="<?php echo $item_fields['net_kgs']; ?>" id="net_kgs" name="net_kgs"
                                    type="hidden">
                                <input value="<?php echo $item_fields['total']; ?>" id="total" name="total"
                                    type="hidden">
                                <input value="<?php echo $item_fields['amount']; ?>" id="amount" name="amount"
                                    type="hidden">
                                <input value="<?php echo $item_fields['final_amount']; ?>" id="final_amount"
                                    name="final_amount" type="hidden">
                                <div class="d-flex align-items-center justify-content-between">
                                    <button name="recordSubmit" id="recordSubmit" type="submit"
                                        class="btn btn-dark">Submit
                                    </button>
                                    <?php //echo $id > 0 ? addNew($pageURL . '?id=' . $id . '&action=add_details') : '';
                                    echo $item_id > 0 ? backUrl('purchase-add?id=' . $id . '&type=' . $_GET['type']) : '';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="hidden_item_id" value="<?php echo $item_id; ?>">
                    </form>
                </div>
            </div>
        <?php } ?>
<?php require_once '../connection.php';
$purchase_id = $_POST['id'];
//$p_khaata_no = $_POST['p_khaata_no'];
if ($purchase_id > 0) {
    $p_data = fetch('purchases', array('id' => $purchase_id));
    $record = mysqli_fetch_assoc($p_data);
    $purchase_id = $record['id'];
    $purchase_type = $record['type'];
    $p_khaata = khaataSingle($record['p_khaata_id']);
    $s_khaata = khaataSingle($record['s_khaata_id']);
    $topArray = array(
        array('heading' => 'Sr#', 'value' => $purchase_id, 'id' => ''),
        array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $record['created_by'], 'username'), 'id' => ''),
        array('heading' => 'DATE', 'value' => $record['p_date'], 'id' => ''),
        array('heading' => 'BRANCH', 'value' => branchName($record['branch_id']), 'id' => ''),
        array('heading' => 'COUNTRY', 'value' => $record['country'], 'id' => ''),
        array('heading' => 'TYPE', 'value' => $record['type'], 'id' => ''),
        array('heading' => 'ALLOT NAME', 'value' => $record['allot'], 'id' => ''),
    );
    //var_dump($record); ?>
    <div class="row">
        <div class="col-10 order-0 content-column">
            <div class="row pt-3 gx-2 text-uppercase small">
                <div class="col-auto">
                    <?php foreach ($topArray as $item) {
                        echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                    } ?>
                </div>
                <div class="col ">
                    <div class="card mb-1 position-relative">
                        <div class="info-div">Purchaser</div>
                        <div class="d-flex p-1">
                            <div>
                                <?php $array_acc1 = array(
                                    array('label' => 'A/C#', 'id' => 'p_khaata_no', 'val' => $record['p_khaata_no']),
                                    array('label' => 'A/C NAME', 'id' => 'p_khaata_name', 'val' => $p_khaata['khaata_name']),
                                    array('label' => 'BRANCH', 'id' => 'p_b_name', 'val' => branchName($p_khaata['branch_id'])),
                                    array('label' => 'CATEGORY', 'id' => 'p_c_name', 'val' => catName($p_khaata['cat_id'])),
                                    array('label' => 'BUSINESS', 'id' => 'p_business_name', 'val' => $p_khaata['business_name']),
                                    array('label' => 'COMPANY', 'id' => 'p_comp_name', 'val' => $p_khaata['comp_name'])
                                ); ?>
                                <?php foreach ($array_acc1 as $item) {
                                    echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '">' . $item['val'] . '</span><br>';
                                } ?>
                            </div>
                            <div>
                                <?php echo '<span class="text-muted" id="p_contacts">';
                                $details1 = ['indexes' => $p_khaata['indexes'], 'vals' => $p_khaata['vals']];
                                echo displayKhaataDetails($details1);
                                echo '</span>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col ">
                    <div class="card mb-1 position-relative">
                        <div class="info-div">SELLER</div>
                        <div class="d-flex p-1">
                            <div>
                                <?php $array_acc2 = array(
                                    array('label' => 'A/C#', 'id' => 's_khaata_no', 'val' => $record['s_khaata_no']),
                                    array('label' => 'A/C NAME', 'id' => 's_khaata_name', 'val' => $s_khaata['khaata_name']),
                                    array('label' => 'BRANCH', 'id' => 's_b_name', 'val' => branchName($s_khaata['branch_id'])),
                                    array('label' => 'CATEGORY', 'id' => 's_c_name', 'val' => catName($s_khaata['cat_id'])),
                                    array('label' => 'BUSINESS', 'id' => 's_business_name', 'val' => $s_khaata['business_name']),
                                    array('label' => 'COMPANY', 'id' => 's_comp_name', 'val' => $s_khaata['comp_name'])
                                ); ?>
                                <?php foreach ($array_acc2 as $item) {
                                    echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '">' . $item['val'] . '</span><br>';
                                } ?>
                            </div>
                            <div>
                                <?php echo '<span class="text-muted" id="s_contacts">';
                                $details2 = ['indexes' => $s_khaata['indexes'], 'vals' => $s_khaata['vals']];
                                echo displayKhaataDetails($details2);
                                /*$contacts = displayKhaataDetails($details2, true);
                                echo array_key_exists('Phone', $contacts) ? '<b>P.</b> ' . $contacts['Phone'] . '<br>' : '';
                                echo array_key_exists('WhatsApp', $contacts) ? '<b>WA.</b> ' . $contacts['WhatsApp'] . '<br>' : '';
                                echo array_key_exists('Email', $contacts) ? '<b>E.</b> ' . $contacts['Email'] : '';*/
                                echo '</span>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $records2q = fetch('purchase_details', array('parent_id' => $purchase_id));
            $final_amounts = $amounts = $qtys = $totals = 0;
            $country = $allot = $goods = $curr = $rate = $curr2 = $rate2 = '';
            $rows = mysqli_num_rows($records2q);
            while ($record2 = mysqli_fetch_assoc($records2q)) {
                $arr1 = array(
                    array('GOODS', goodsName($record2['goods_id'])),
                    array('Size', $record2['size']),
                    array('Brand', $record2['brand']),
                    array('Origin', $record2['origin']),
                );
                $arr2 = array(
                    array('Qty Name', $record2['qty_name']),
                    array('Qty#', $record2['qty_no']),
                    array('Qty KGs', $record2['qty_kgs']),
                    array('Total KGs', $record2['total_kgs']),
                );
                $arr3 = array(
                    array('Empty KGs', $record2['empty_kgs']),
                    array('Total Qty KGs', $record2['total_qty_kgs']),
                    array('Net KGs', $record2['net_kgs']),
                    array('Divide', $record2['divide']),
                );
                $arr4 = array(
                    array('Weight', $record2['weight']),
                    array('Total', $record2['total']),
                    array('Price', $record2['price'] . ' PRICE'),
                    //array('Currency', $record2['currency1']),
                    array('Rate', $record2['rate1']),
                );
                $arr5 = array(
                    array('Amount ', round($record2['amount'], 2) . '<sub>' . $record2['currency1'] . '</sub>'),
                    array('Qty?', $record2['is_qty'] == 1 ? 'YES' : 'NO'),
                    array('Rate', $record2['rate2']),
                    array('Operator', $record2['opr']),
                );
                $arr6 = array(
                    array('Final Amount ', $record2['final_amount'] . '<sub>' . $record2['currency2'] . '</sub>'),
                );
                $amounts += $record2['amount'];
                $final_amounts += $record2['final_amount'];
                $curr = $record2['currency1'];
                $rate = $record2['rate1'];
                $curr2 = $record2['currency2'];
                $rate2 = $record2['rate2'];
                $goods = goodsName($record2['goods_id']);
                $qtys += $record2['qty_no'];
                $totals += $record2['total']; ?>
                <div class="card text-uppercase small mb-0">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col">
                                <?php foreach ($arr1 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php
                                foreach ($arr2 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr3 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr4 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr5 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                    if ($item[1] == 'NO') continue;
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr6 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                    if ($item[1] == 'NO') continue;
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-12 mt-2">
                    <?php $details2 = ['indexes' => $record['rep_indexes'], 'vals' => $record['rep_vals']];
                    $reps = displayKhaataDetails($details2, true);
                    foreach ($reps as $key => $val) {
                        echo '<span class=""><b>' . $key . ' Report: </b>' . $val . '</span><br>';
                    } ?>
                </div>
            </div>
            <?php if (isset($_POST['show_transfer'])) {
                if ($record['is_locked'] == 1) {
                    $ddd = 'ENTRY:' . $rows . ' GOODS:' . $goods . ' COUNTRY:' . $record['country'] . ' ALLOT:' . $record['allot'] . ' T.Qty:' . $qtys . ' T.KGs:' . $totals . ' RATE:' . $rate . ' T.AMNT:' . $amounts . $curr . ' EXCH.:' . $curr2; ?>
                    <div class="card">
                        <div class="card-body p-2">
                            <form method="post">
                                <div class="row gx-1 gy-3 table-form mb-3">
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="s_khaata_no" class="text-success">Dr. (Sale)</label>
                                            <input value="<?php echo $record['s_khaata_no']; ?>" id="s_khaata_no"
                                                   name="dr_khaata_no" readonly tabindex="-1" class="form-control"
                                                   required>
                                        </div>
                                        <input type="hidden" name="dr_khaata_id"
                                               value="<?php echo $record['s_khaata_id']; ?>">
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="p_khaata_no" class="text-danger">Cr. (Purchaser)</label>
                                            <input type="text" id="p_khaata_no" name="cr_khaata_no" class="form-control"
                                                   readonly tabindex="-1" value="<?php echo $record['p_khaata_no']; ?>">
                                        </div>
                                        <input type="hidden" name="cr_khaata_id"
                                               value="<?php echo $record['p_khaata_id'] ?>">
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="transfer_date">Date</label>
                                            <input type="date" class="form-control" id="transfer_date"
                                                   name="transfer_date" required value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="amount" class="mb-0">Amount</label>
                                            <input value="<?php echo round($final_amounts, 2); ?>" id="amount" readonly
                                                   name="amount" class="form-control" required tabindex="-1">
                                        </div>
                                    </div>
                                    <div class="col-11">
                                        <div class="input-group">
                                            <label for="details">Details</label>
                                            <input type="text" name="details" id="details" class="form-control"
                                                   value="<?php echo $ddd; ?>">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button name="ttrFirstSubmit" type="submit"
                                                class="btn btn-primary w-100 btn-sm"><i class="fa fa-upload"></i>Transfer
                                        </button>
                                    </div>
                                    <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                                    <input type="hidden" name="type" value="<?php echo $record['type']; ?>">
                                </div>
                                <?php if ($record['khaata_tr1'] != '') {
                                    $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $purchase_id, 'transfered_from' => 'purchase_' . $record['type']));
                                    if (mysqli_num_rows($rozQ) > 0) { ?>
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead>
                                            <tr>
                                                <th>Sr#</th>
                                                <th>Date</th>
                                                <th>A/c#</th>
                                                <th>Roz.#</th>
                                                <th>Name</th>
                                                <th>No</th>
                                                <th>Details</th>
                                                <th>Dr.</th>
                                                <th>Cr.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                                                $dr = $cr = 0; ?>
                                                <input type="hidden" value="<?php echo $roz['r_date']; ?>"
                                                       id="temp_transfer_date">
                                                <input type="hidden" value="<?php echo $roz['r_id']; ?>" name="r_id[]">
                                                <tr>
                                                    <td>
                                                        <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                                    </td>
                                                    <td><?php echo $roz['r_date']; ?></td>
                                                    <td>
                                                        <a href="ledger?back-khaata-no=<?php echo $roz['khaata_no']; ?>"
                                                           target="_blank"><?php echo $roz['khaata_no']; ?></a></td>
                                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                                    <td class="small"><?php echo $roz['r_name']; ?></td>
                                                    <td><?php echo $roz['r_no']; ?></td>
                                                    <td class="small"><?php echo $roz['details']; ?></td>
                                                    <?php if ($roz['dr_cr'] == "dr") {
                                                        $dr = $roz['amount'];
                                                    } else {
                                                        $cr = $roz['amount'];
                                                    } ?>
                                                    <td class="text-success"><?php echo $dr; ?></td>
                                                    <td class="text-danger"><?php echo $cr; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="col-2 order-1 fixed-sidebar table-form">
            <div>
                <div>
                    <?php if ($record['is_locked'] == 1) { ?>
                        <p class="bold mb-0"><?php echo 'Amount: ' . round($final_amounts, 2); ?></p>
                        <form method="post">
                            <select class="form-select" id="transfer" name="transfer" required>
                                <option value="" hidden="">Select</option>
                                <?php $transfer_array = array('Advance' => '1', 'Full Payment' => '2');
                                foreach ($transfer_array as $item => $value) {
                                    $t_sel = $value == $record['transfer'] ? 'selected' : '';
                                    echo '<option ' . $t_sel . ' value="' . $value . '">' . $item . '</option>';
                                } ?>
                            </select>
                            <input id="final_amt_hidden" type="hidden" value="<?php echo $final_amounts; ?>">
                            <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                            <div class="row g-0">
                                <div class="col-3">
                                    <input value="<?php echo $record['pct'] ?>" id="pct" name="pct" type="number"
                                           min="1"
                                           max="100" step="any" class="form-control" placeholder="%">
                                    <!--pattern="^(\d{1,2}|100)$"-->
                                </div>
                                <div class="col">
                                    <input value="<?php echo $record['pct_amt'] ?>" readonly id="pct_amt" name="pct_amt"
                                           class="form-control"
                                           placeholder="Adv. Amount">
                                </div>
                            </div>
                            <button name="transferAdvFullSubmit" type="submit" class="btn btn-sm btn-secondary">Submit
                                Now
                            </button>
                        </form>
                    <?php } ?>
                    <form id="purchaseAttachSubmit" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="p_id_hidden_attach" value="<?php echo $purchase_id; ?>">
                        <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                        <input type="button" class="form-control rounded-1 bg-dark text-white mt-5"
                               value="+ Contract File"
                               onclick="document.getElementById('attachments').click();"/>
                    </form>
                    <script>
                        document.getElementById("attachments").onchange = function () {
                            document.getElementById("purchaseAttachSubmit").submit();
                        }
                    </script>
                    <?php $attachments = fetch('attachments', array('source_id' => $purchase_id, 'source_name' => 'purchase_market_order'));
                    if (mysqli_num_rows($attachments) > 0) {
                        //echo '<h6 class=" mb-0 d-inline fw-bold">Documents </h6>';
                        $no = 1;
                        while ($attachment = mysqli_fetch_assoc($attachments)) {
                            $link = 'attachments/' . $attachment['attachment'];
                            echo $no . '.<a class="text-decoration-underline" href="' . $link . '" target="_blank">' . readMore($attachment['attachment'], 27) . '</a><br>';
                            $no++;
                        }
                    } ?>
                    <?php if ($record['is_locked'] == 0) {
                        if ($record['is_doc'] > 0) { ?>
                            <form method="post"
                                  onsubmit="return confirm('Lock this purchase.\n You cannot delete the purchase later.\n Press OK to transfer')">
                                <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                                <button name="transferPurchase" type="submit" class="btn btn-dark btn-sm w-100 mt-3">
                                    TRANSFER
                                </button>
                            </form>
                        <?php }
                    } ?>
                </div>
                <div class="bottom-buttons">
                    <div class="px-2">
                        <?php //$update_url = $purchase_type == 'booking' ? 'purchase-add' : 'purchase-local-add'; ?>
                        <?php $update_url = $purchase_type == 'booking' ? 'purchase-add' : ($purchase_type == 'market' ? 'purchase-market-add' : 'purchase-local-add'); ?>
                        <a href="<?php echo $update_url . '?id=' . $purchase_id; ?>"
                           class="btn btn-dark btn-sm w-100 mt-2">UPDATE</a>
                        <?php if ($record['is_locked'] == 0) { ?>
                            <form method="post" onsubmit="return confirm('Are you sure to delete?');">
                                <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                                <button name="deletePurchase" type="submit" class="btn btn-danger btn-sm w-100 mt-2">
                                    DELETE
                                </button>
                            </form>
                        <?php } ?>
                        <a href="print/purchase-booking?p_id=<?php echo $purchase_id; ?>&action=booking" target="_blank"
                           class="btn btn-success btn-sm w-100 mt-3">PRINT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
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
        $("#transfer").change(function () {
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
</script>
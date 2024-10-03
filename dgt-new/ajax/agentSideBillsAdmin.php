<?php require_once '../connection.php';
$pur_sale = $_POST['source'];
$parent_id = $_POST['id'];
$purchase_agents_id = $_POST['purchase_agents_id'];
$d_id = $_POST['d_id'];
$khaataId = $_POST['khaataId'];
if ($d_id > 0) {
    $parent_query = fetch($pur_sale . 's', array('id' => $parent_id));
    $parent_data = mysqli_fetch_assoc($parent_query);
    $parent_id = $parent_data['id'];
    $purchase_type = $parent_data['type'];

    $details_query = fetch($pur_sale . '_details', array('id' => $d_id));
    $details = mysqli_fetch_assoc($details_query);
    $d_sr = $details['d_sr'];
    $imp_json = json_decode($details['imp_json']);
    $exp_json = json_decode($details['exp_json']);
    $notify_json = json_decode($details['notify_json']);
    $ware_json = json_decode($details['tware_json']);
    $bail_json = json_decode($details['bail_json']);
    $agentKhaata = khaataSingle($khaataId);
    $check_agent_query = fetch('purchase_agents', array('id' => $purchase_agents_id));
    $agent_data = mysqli_fetch_assoc($check_agent_query);
    $pa_sr = $agent_data['a_sr'];
    $type = $agent_data['type'];


    $bnaam_khaata_no = $parent_data['p_khaata_no'];
    if ($pur_sale == 'purchase') {
        $ps_date = $parent_data['p_date'];
        $ps_type = purchaseSpecificData($parent_id, 'purchase_type');
        $bnaam_khaata_id = $parent_data['p_khaata_id'];
    } else {
        $ps_date = $parent_data['s_date'];
        $ps_type = saleSpecificData($parent_id, 'sale_type');
        $ddummy = khaataSingle($parent_data['p_khaata_no'], true);
        $bnaam_khaata_id = $ddummy['id'];
    }

    $topArray = array(
        array('heading' => 'Sr#', 'value' => '[' . $pa_sr . '] ' . $parent_id . '-' . $d_sr),
        array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $parent_data['created_by'], 'username')),
        array('heading' => 'DATE', 'value' => $ps_date),
        array('heading' => 'BRANCH', 'value' => branchName($parent_data['branch_id'])),
        array('heading' => 'TYPE', 'value' => $parent_data['type']),
    );

    if ($pur_sale == 'purchase') {
        $topArray[] = array('heading' => 'COUNTRY', 'value' => $parent_data['country']);
        $topArray[] = array('heading' => 'ALLOT NAME', 'value' => $parent_data['allot']);
    } else {
        $topArray[] = array('heading' => 'CITY', 'value' => $parent_data['city']);
    }
    //var_dump($record); ?>
    <div class="row">
        <div class="col-md-10 order-1 content-column table-form">
            <div class="card mb-1">
                <div class="card-body p-2 text-uppercase small">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <div class="row">
                        <div class="col">
                            <?php foreach ($topArray as $item) {
                                echo '<b>' . $item['heading'] . '</b><span>' . $item['value'] . '</span><br>';
                            } ?>
                        </div>
                        <?php $arr1 = array(
                            array('GOODS', goodsName($details['goods_id'])),
                            array('Qty Name', $details['qty_name']),
                            array('Qty#', $details['qty_no']),
                            array('Total KGs', $details['total_kgs']),
                            array('Net KGs', round($details['net_kgs'], 2)),
                        ); ?>
                        <div class="col">
                            <?php foreach ($arr1 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col">
                            <div class="bg-light">BAIL DESC</div>
                            <?php if (!empty($bail_json)) {
                                $arr_bail1 = array(
                                    'Container#' => $bail_json->container_no,
                                    'Bail#' => $bail_json->bail_no,
                                    'Container Name' => $bail_json->container_name,
                                    'Container Size' => $bail_json->container_size,
                                    'Bail Report' => $bail_json->bail_report
                                ); ?>
                                <?php foreach ($arr_bail1 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <div class="bg-light">CUSTOM ENTRY DESC</div>
                            <?php $agent_details = json_decode($agent_data['details']);
                            if (!empty($agent_details)) {
                                echo '<b>Entry Bill# </b>' . $agent_details->bill_no;
                                echo '<br><b>Entry Bill Date</b>' . $agent_details->bill_date;
                                /*echo '<br><b>Loading Truck#</b>' . $agent_details->truck_no;
                                echo '<br><b>Container Name</b>' . $agent_details->ctr_name;
                                echo '<br><b>Container#</b>' . $agent_details->ctr_no;*/
                            } ?>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="row mb-1 gx-1 align-items-center">
                        <?php echo '<div class="col-auto">';
                        $sea_road = $parent_data['sea_road'];
                        echo '<h5 class="text-uppercase mb-0">by ' . $sea_road . '</h5>';
                        echo '</div>';
                        if ($parent_data['sea_road'] == 'road') {
                            if (!empty($parent_data['road_json'])) {
                                $road_json = json_decode($parent_data['road_json']);
                                echo '<div class="col">';
                                echo '<b>Loading Country</b>' . $road_json->l_country_road;
                                echo '<br><b>Receiving Country</b>' . $road_json->r_country_road;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Loading Date</b>' . $road_json->l_date_road;
                                echo '<br><b>Receiving Date</b>' . $road_json->r_date_road;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Loading Border</b>' . $road_json->l_border_road;
                                echo '<br><b>Receiving Border</b>' . $road_json->r_border_road;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Status</b>' . $road_json->truck_container;
                                echo '<br><b>Delivery Date</b>' . $road_json->d_date_road;
                                echo '</div>';
                            }
                        }
                        if ($parent_data['sea_road'] == 'sea') {
                            if ($parent_data['is_loading'] == 1) {
                                $loading_json = json_decode($parent_data['loading_json']);
                                echo '<div class="col">';
                                echo '<b>Loading Country</b>' . $loading_json->l_country;
                                echo '<br><b>Loading Port</b>' . $loading_json->l_port;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Loading Date</b>' . $loading_json->l_date;
                                echo '<br><b>Container Name</b>' . $loading_json->ctr_name;
                                echo '</div>';
                            }
                            if ($parent_data['is_receiving'] == 1) {
                                $receiving_json = json_decode($parent_data['receiving_json']);
                                echo '<div class="col">';
                                echo '<b>Receiving Country</b>' . $receiving_json->r_country;
                                echo '<br><b>Receiving Port</b>' . $receiving_json->r_port;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Receiving Date</b>' . $receiving_json->r_date;
                                echo '<br><b>Arrival Date</b>' . $receiving_json->arrival_date;
                                echo '</div>';
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <?php $bill = json_decode($agent_data['bill']); ?>
            <?php if (!empty($bill)) { ?>
                <div class=" collapse show" id="collapseDetails">
                    <form method="post" class="">
                        <div class="row gx-1">
                            <div class="col-8">
                                <div class="card p-2 rounded-0 mb-1">
                                    <input type="hidden" name="details" value="<?php echo $bill->details; ?>"
                                           class="form-control">
                                    <table class="table table-borderless mb-0" id="productTable">
                                        <thead class="table-secondary">
                                        <tr class="text-center">
                                            <td style="width: 4%;">Sr#</td>
                                            <td style="width: 8%">QTY</td>
                                            <td>DESCRIPTION</td>
                                            <td style="width: 15%">AMOUNT</td>
                                            <td style="width: 5%"></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $arrayNumber = 0;
                                        $x = 1;
                                        $exp_qtys = $bill->exp_qtys;
                                        $exp_details = $bill->exp_details;
                                        $exp_values = $bill->exp_values;
                                        //for ($x = 1; $x < 3; $x++) {
                                        foreach ($exp_qtys as $key => $val) { ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td>
                                                    <input class="text-center form-control bg-white"
                                                           value="<?php echo $x; ?>" disabled>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="exp_qtys[]" required
                                                           class="form-control" id="exp_qtys<?php echo $x; ?>"
                                                           placeholder="QTY <?php echo $x; ?>"
                                                           value="<?php echo $exp_qtys[$key]; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_details[]" required
                                                           class="form-control"
                                                           id="exp_name<?php echo $x; ?>"
                                                           placeholder="DESCRIPTION <?php echo $x; ?>"
                                                           value="<?php echo $exp_details[$key]; ?>">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="exp_values[]" required
                                                           onkeyup="getTotal(<?php echo $x ?>)" class="form-control"
                                                           id="exp_value<?php echo $x; ?>"
                                                           placeholder="Amt. <?php echo $x; ?>"
                                                           value="<?php echo $exp_values[$key]; ?>">
                                                </td>
                                                <td>
                                        <span id="removeProductRowBtn" class="btn btn-link text-danger p-1"
                                              onclick="removeProductRow(<?php echo $x; ?>)">Remove</span>
                                                </td>
                                            </tr>
                                            <?php
                                            $arrayNumber++;
                                            $x++;
                                        } ?>
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                    <span class="btn btn-secondary btn-sm" onclick="addRow()" id="addRowBtn"
                                          data-loading-text="Loading...">+ Add line</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card p-2 rounded-0 mb-3">
                                    <div class="row gx-0">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <label for="total" class="bold">T.</label>
                                                <input id="total" readonly name="amount" class="form-control bold"
                                                       required
                                                       value="<?php //echo $bill->total; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="tax_rate">TAX</label>
                                                <input id="tax_rate" name="tax_rate" class="form-control" required
                                                       type="number" min="0" step="any"
                                                       value="<?php echo $bill->tax_rate; ?>">
                                                <span class="input-group-text" id="tax_rate"
                                                      style="padding: 2.84px;">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="bill_date">D.</label>
                                                <input name="bill_date" type="date" id="bill_date" class="form-control"
                                                       value="<?php echo $bill->bill_date; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label for="first_amount" class="bold">FINAL AMOUNT</label>
                                                <input id="first_amount" name="first_amount" class="form-control"
                                                       required readonly tabindex="-1"
                                                       value="<?php echo $bill->first_amount; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check mt-md-1">
                                                <?php $checked = isset($bill->is_qty) && $bill->is_qty == 1 ? 'checked' : ''; ?>
                                                <input type="checkbox" class="form-check-input" id="is_qty"
                                                       name="is_qty"
                                                       value="1" <?php echo $checked; ?>>
                                                <label class="form-check-label" for="is_qty">Convert amount?</label>
                                            </div>
                                        </div>

                                        <div class="col-md-5 toggleQty">
                                            <div class="input-group">
                                                <label for="currency">Currency</label>
                                                <select id="currency" name="currency" class="form-select"
                                                        required>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $crr_sel2 = $crr['name'] == $bill->currency ? 'selected' : '';
                                                        echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 toggleQty">
                                            <div class="input-group">
                                                <label for="rate">Rate</label>
                                                <input id="rate" name="rate" class="form-control" type="number"
                                                       step="any" min="0" value="<?php echo $bill->rate; ?>"
                                                       autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3 toggleQty">
                                            <div class="input-group">
                                                <label for="opr">Opr</label>
                                                <select id="opr" name="opr" class="form-select">
                                                    <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                    foreach ($ops as $opName => $op) {
                                                        $op_sel = $bill->opr == $op ? 'selected' : '';
                                                        echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="final_amount" class="bold text-danger">FINAL</label>
                                                <input id="final_amount" name="final_amount" class="form-control"
                                                       required readonly>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label for="report">REPORT</label>
                                                <textarea rows="8" id="report" name="report" class="form-control"
                                                          required><?php echo $bill->report; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <button name="agentBillSubmit" id="recordSubmit" type="submit"
                                                    class="btn btn-dark btn-sm w-100">UPDATE BILL
                                            </button>
                                            <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $rozQ = mysqli_query($connect, "SELECT * FROM `roznamchaas` WHERE r_type='Business' AND transfered_from_id = '$purchase_agents_id' AND (transfered_from = 'purchase_agentsimport' || transfered_from = 'purchase_agentsexport')");
                        //$rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $purchase_id, 'transfered_from' => 'purchase_' . $record['type']));
                        if (mysqli_num_rows($rozQ) > 0) { ?>
                            <div class="card rounded-0 p-0">
                                <table class="table mb-0 table--bordered">
                                    <thead>
                                    <tr class="table-secondary">
                                        <td>Sr#</td>
                                        <td>Date</td>
                                        <td>A/c#</td>
                                        <td>Roz.#</td>
                                        <td>Name</td>
                                        <td>No</td>
                                        <td style="width: 45%">Details</td>
                                        <td>Dr.</td>
                                        <td>Cr.</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                                        $dr = $cr = 0; ?>
                                        <input type="hidden" value="<?php echo $roz['r_date']; ?>"
                                               id="temp_transfer_date">
                                        <input type="hidden" value="<?php echo $roz['r_id']; ?>" name="r_id[]">
                                        <tr>
                                            <td class="text-nowrap">
                                                <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                            </td>
                                            <td class="text-nowrap"><?php echo $roz['r_date']; ?></td>
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
                                            <td class="text-success"><?php echo round($dr); ?></td>
                                            <td class="text-danger"><?php echo round($cr); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <input type="hidden" name="source" value="<?php echo $pur_sale; ?>">
                        <input type="hidden" name="dr_khaata_id" value="<?php echo $khaataId; ?>">
                        <input type="hidden" name="dr_khaata_no" value="<?php echo $agentKhaata['khaata_no']; ?>">
                        <input type="hidden" name="cr_khaata_id" value="<?php echo $bnaam_khaata_id; ?>">
                        <input type="hidden" name="cr_khaata_no" value="<?php echo $bnaam_khaata_no; ?>">
                        <input type="hidden" name="type" value="<?php echo $agent_data['type']; ?>">
                        <input type="hidden" name="pd_id_hidden" value="<?php echo $d_id; ?>">
                        <input type="hidden" name="p_id_hidden" value="<?php echo $parent_id; ?>">
                        <input type="hidden" name="pa_sr_hidden" value="<?php echo $pa_sr; ?>">
                        <input type="hidden" name="d_sr_hidden" value="<?php echo $d_sr; ?>">

                        <input type="hidden" name="branch_id" value="<?php echo $agentKhaata['branch_id']; ?>">
                        <input type="hidden" name="purchase_agents_id" value="<?php echo $purchase_agents_id; ?>">
                        <input type="hidden" name="created_at" value="<?php echo date('Y-m-d'); ?>">
                    </form>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-2 order-0 fixed-sidebar table-form shadow">
            <h5 class="mb-0 text-success">Dr. A/c.</h5>
            <?php echo '<b>A/c.#</b>' . $agentKhaata['khaata_no'];
            echo '<br><b>Name</b>' . $agentKhaata['khaata_name']; ?>
            <h5 class="mt-3 mb-0 text-danger">Cr. A/c.</h5>
            <?php echo '<b>A/c.#</b>' . $bnaam_khaata_no;
            $bnaamKhaata = khaataSingle($bnaam_khaata_id);
            echo '<br><b>Name</b>' . $bnaamKhaata['khaata_name']; ?>

            <div class="bottom-buttons px-2">
                <?php if (!empty($bill)) { ?>
                    <a class="btn btn-dark btn-sm w-100 mb-3" data-bs-toggle="collapse" href="#collapseDetails"
                       role="button"
                       aria-expanded="false" aria-controls="collapseExample">UPDATE BILL</a>
                <?php } ?>
                <?php echo '<a href="print/purchase-agent-bill?p_id=' . $parent_id . '&pd_id=' . $d_id .
                    '&purchase_agents_id=' . $purchase_agents_id . '&action=admin" class="btn btn-success btn-sm w-100" target="_blank">PRINT</a>'; ?>
            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    function addRow() {
        $("#addRowBtn").button("loading");
        var tableLength = $("#productTable tbody tr").length;
        var tableRow;
        var arrayNumber;
        var count;

        if (tableLength > 0) {
            tableRow = $("#productTable tbody tr:last").attr('id');
            arrayNumber = $("#productTable tbody tr:last").attr('class');
            count = tableRow.substring(3);
            count = Number(count) + 1;
            arrayNumber = Number(arrayNumber) + 1;
        } else {
            // no table row
            count = 1;
            arrayNumber = 0;
        }

        $("#addRowBtn").button("reset");

        var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">' +
            '<td>' +
            '<input class="text-center form-control bg-white" value="' + count + '" disabled>' +
            '</td>' +
            '<td>' +
            '<input type="number" min="0" name="exp_qtys[]" required class="form-control" id="exp_name' + count + '" placeholder="QTY ' + count + '">' +
            '</td>' +
            '<td>' +
            '<input type="text" name="exp_details[]" required class="form-control" id="exp_name' + count + '" placeholder="DESCRIPTION ' + count + '">' +
            '</td>' +
            '<td>' +
            '<input type="number" min="0" step="any" name="exp_values[]" autocomplete="off" required onkeyup="getTotal(' + count + ')" class="form-control" id="exp_value' + count + '" placeholder="Amt. ' + count + '">' +
            '</td>' +
            '<td>' +
            '<span id="removeProductRowBtn" class="btn btn-link text-danger p-1" onclick="removeProductRow(' + count + ')">Remove</span>' +
            '</td>' +
            '</tr>';
        if (tableLength > 0) {
            $("#productTable tbody tr:last").after(tr);
        } else {
            $("#productTable tbody").append(tr);
        }
    }

    function removeProductRow(row = null) {
        if (row) {
            var tableLength = $("#productTable tbody tr").length;
            if (tableLength > 1) {
                $("#row" + row).remove();
            }
            subAmount();
        } else {
            alert('error! Refresh the page again');
        }
    }

    function getTotal(row = null) {
        if (row) {
            var total = Number($("#exp_value" + row).val());
            //total = total.toFixed(2);
            //$("#exp_value" + row).val(total);
            subAmount();
        } else {
            alert('no row !! please refresh the page');
        }
    }

    function subAmount() {
        var tableProductLength = $("#productTable tbody tr").length;
        var totalSubAmount = 0;
        var grandTotal = 0;
        for (x = 0; x < tableProductLength; x++) {
            var tr = $("#productTable tbody tr")[x];
            var count = $(tr).attr('id');
            count = count.substring(3);

            totalSubAmount = Number(totalSubAmount) + Number($("#exp_value" + count).val());
        }
        //totalSubAmount = totalSubAmount.toFixed(2);
        $("#total").val(totalSubAmount);
        var first_amount = 0;
        var tax_rate = parseFloat($("#tax_rate").val()) || 0;
        var tax = percentage(tax_rate, totalSubAmount);
        first_amount = Number(totalSubAmount) - Number(tax);
        $("#first_amount").val(isFinite(first_amount) ? first_amount : '');

        var final_amount = 0;
        if ($('#is_qty').is(":checked")) {
            var rate = parseFloat($("#rate").val()) || 0;
            let operator = $('#opr').find(":selected").val();
            if (!isNaN(rate) && rate !== 0 && !isNaN(first_amount)) {
                final_amount = (operator === '/') ? first_amount / rate : first_amount * rate;
                final_amount = final_amount.toFixed(3);
            }
        } else {
            final_amount = first_amount;
        }
        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');

        //var tax_rate = parseFloat($("#tax_rate").val()) || 0;
        /*var tax = percentage(tax_rate, first_amount);
        final_amount = Number(first_amount) - Number(tax);
        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');*/

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }

    function toggleQtyAndRequired() {
        var $toggleQty = $(".toggleQty");
        var $is_qty2 = $("#is_qty");
        if ($is_qty2.is(":checked")) {
            subAmount();
            $toggleQty.show();
            $("#currency, #rate, #opr").attr('required', true);
        } else {
            subAmount();
            $toggleQty.hide();
            $("#currency, #rate, #opr").attr('required', false);
        }
    }

    function percentage(partialValue, totalValue) {
        return (partialValue / 100) * totalValue;
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#rate,#tax_rate').on('keyup', function () {
            subAmount();
        });
    });
    $(document).ready(function () {
        toggleQtyAndRequired();
        $("#is_qty").change(toggleQtyAndRequired);
        $('#opr').on('change', function () {
            subAmount();
        });
    });
</script>
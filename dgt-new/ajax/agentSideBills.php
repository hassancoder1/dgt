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

    if ($pur_sale == 'purchase') {
        $ps_date = $parent_data['p_date'];
        $ps_type = purchaseSpecificData($parent_id, 'purchase_type');
    } else {
        $ps_date = $parent_data['s_date'];
        $ps_type = saleSpecificData($parent_id, 'sale_type');
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
    } ?>
    <div class="row">
        <div class="col-md-10 order-1 content-column table-form">
            <div class="card">
                <div class="card-body p-2 text-uppercase- small">
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
                            <?php $agent_details = json_decode($agent_data['details']);
                            if (!empty($agent_details)) {
                                echo '<b>Entry Bill# </b>' . $agent_details->bill_no;
                                echo '<br><b>Entry Bill Date</b>' . $agent_details->bill_date;
                                echo '<br><b>Loading Truck#</b>' . $agent_details->truck_no;
                                echo '<br><b>Container Name</b>' . $agent_details->ctr_name;
                                echo '<br><b>Container#</b>' . $agent_details->ctr_no;
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
                    <hr class="my-0">
                    <div class="row gx-1 align-items-center">
                        <div class="col-auto">
                            <h5 class="text-uppercase mb-0">BILL DETAILS</h5>
                        </div>
                        <?php $bill = json_decode($agent_data['bill']);
                        if (!empty($bill)) {
                            echo '<div class="col">';
                            echo '<b>BILL DATE# </b>' . $bill->bill_date;
                            echo '<br><b>FIRST AMOUNT </b>' . $bill->amount;
                            echo '</div>';
                            echo '<div class="col">';
                            echo '<b>TAX </b>' . $bill->tax_rate . '%';
                            echo '<br><b>FINAL </b>' . $bill->first_amount;
                            echo '</div>';
                            echo '<div class="col-6">';
                            echo '<b>REPORT </b>' . $bill->report;
                            echo '<table class="table text-center d-none">';
                            echo '<tr><th style="width: 4%;">Sr#</th><th style="width: 8%">QTY</th><th>DESCRIPTION</th><th style="width: 15%">AMOUNT</th></tr>';
                            $exp_qtys = $bill->exp_qtys;
                            $exp_details = $bill->exp_details;
                            $exp_values = $bill->exp_values;
                            $srr = 1;
                            foreach ($exp_qtys as $key => $val) {
                                echo '<tr>';
                                echo '<td>' . $srr . '</td>';
                                echo '<td>' . $exp_qtys[$key] . '</td>';
                                echo '<td>' . $exp_details[$key] . '</td>';
                                echo '<td>' . $exp_values[$key] . '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                            echo '</div>';
                        } ?>
                    </div>
                </div>
            </div>
            <form method="post" class="collapse -show" id="collapseDetails">
                <div class="row gx-1">
                    <div class="col-8">
                        <div class="card p-2 rounded-0">
                            <?php $container_no = isset($bail_json->container_no) ? ' CONTAINER#' . $bail_json->container_no : '';
                            $bail_no = isset($bail_json->bail_no) ? ' BAIL#' . $bail_json->bail_no : '';
                            $dett = 'GOODS:' . goodsName($details['goods_id']) . $container_no . $bail_no; ?>
                            <input type="hidden" name="details" value="<?php echo $dett; ?>">
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
                                for ($x = 1; $x < 3; $x++) { ?>
                                    <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                        <td>
                                            <input class="text-center form-control bg-white"
                                                   value="<?php echo $x; ?>" disabled>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="exp_qtys[]" required
                                                   class="form-control"
                                                   id="exp_qtys<?php echo $x; ?>"
                                                   placeholder="QTY <?php echo $x; ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="exp_details[]" required class="form-control"
                                                   id="exp_name<?php echo $x; ?>"
                                                   placeholder="DESCRIPTION <?php echo $x; ?>">
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="exp_values[]" required
                                                   onkeyup="getTotal(<?php echo $x ?>)" class="form-control"
                                                   id="exp_value<?php echo $x; ?>"
                                                   placeholder="Amt. <?php echo $x; ?>">
                                        </td>
                                        <td>
                                        <span id="removeProductRowBtn" class="btn btn-link text-danger p-1"
                                              onclick="removeProductRow(<?php echo $x; ?>)">Remove</span>
                                        </td>
                                    </tr>
                                    <?php
                                    $arrayNumber++;
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
                        <div class="card p-2 rounded-0">
                            <div class="row gx-0 gy-3">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <label for="total" class="bold">T.</label>
                                        <input id="total" readonly name="amount" class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="tax_rate">TAX</label>
                                        <input id="tax_rate" name="tax_rate" class="form-control" required
                                               type="number" min="0" step="any">
                                        <span class="input-group-text" id="tax_rate"
                                              style="padding: 2.84px;">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="bill_date">D.</label>
                                        <input name="bill_date" type="date" id="bill_date" class="form-control"
                                               value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="first_amount" class="bold">FINAL AMOUNT</label>
                                        <input id="first_amount" name="first_amount" class="form-control" required
                                               readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <label for="report">REPORT</label>
                                        <textarea id="report" name="report" class="form-control" required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <button name="agentBillSubmit" id="recordSubmit" type="submit"
                                            class="btn btn-primary btn-sm w-100">Save Bill
                                    </button>
                                    <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="dr_khaata_id" value="<?php echo $khaataId; ?>">
                <input type="hidden" name="dr_khaata_no" value="<?php echo $agentKhaata['khaata_no']; ?>">
                <input type="hidden" name="type" value="<?php echo $agent_data['type']; ?>">
                <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                <input type="hidden" name="id_hidden" value="<?php echo $parent_id; ?>">
                <input type="hidden" name="branch_id" value="<?php echo $agentKhaata['branch_id']; ?>">
                <input type="hidden" name="purchase_agents_id" value="<?php echo $purchase_agents_id; ?>">
                <input type="hidden" name="pur_sale_hidden" value="<?php echo $pur_sale; ?>">
                <input type="hidden" name="created_at" value="<?php echo date('Y-m-d'); ?>">
            </form>
        </div>
        <div class="col-md-2 order-0 fixed-sidebar table-form shadow">
            <?php if (empty($bill)) { ?>
            <a class="btn btn-dark btn-sm w-100 mt-1" data-bs-toggle="collapse" href="#collapseDetails"
               role="button" aria-expanded="false" aria-controls="collapseExample">
                Add Bill
            </a>
            <?php } ?>
            <div class="bottom-buttons px-2">
                <?php echo '<a href="../print/purchase-agent-bill?id=' . $parent_id . '&d_id=' . $d_id .
                    '&purchase_agents_id=' . $purchase_agents_id . '&action=agent" class="btn btn-success btn-sm w-100" target="_blank">PRINT</a>'; ?>
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

        if (first_amount <= 0 || isNaN(first_amount) || !isFinite(first_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }

    function percentage(partialValue, totalValue) {
        return (partialValue / 100) * totalValue;
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tax_rate').on('keyup', function () {
            subAmount();
        });
    });
</script>
<?php $page_title = 'Exchange Entry';
$back_page_url = 'exchanges';
include("header.php");
global $connect, $branchId;
$url = "exchange-add";
$sr_no = getAutoIncrement('exchanges');
$action_hidden = 'insert';
$id_hidden = 0;
$p_s = 'p';
$created_at = $transfer_date = date('Y-m-d');
$curr1 = $qty = $per_price = $opr = $curr2 = $amount = $first_amount = $details = '';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action_hidden = 'update';
    $id_hidden = $sr_no = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('exchanges', array('id' => $id_hidden));
    $record = mysqli_fetch_assoc($records);
    $p_s = $record['p_s'];
    $curr1 = $record['curr1'];
    $qty = $record['qty'];
    $per_price = $record['per_price'];
    $opr = $record['opr'];
    $curr2 = $record['curr2'];
    $first_amount = $amount = $record['amount'];
    $details = $record['details'];
    $created_at = $record['created_at'];
    $r_details = $record['curr1'] . ' ' . $record['qty'] . ' ' . $record['per_price'] . ' ' . $record['curr2'] . ' ' . $record['amount'];

    $dr_khaata_no = $cr_khaata_no = $aed_rate = $opr2 = $final_amount = '';
    if (!empty($record['khaata_exchange'])) {
        $khaata_exchange = json_decode($record['khaata_exchange']);
        $dr_khaata_no = $khaata_exchange->dr_khaata_no;
        $cr_khaata_no = $khaata_exchange->cr_khaata_no;
        $transfer_date = $khaata_exchange->transfer_date;
        $first_amount = $khaata_exchange->first_amount;
        $aed_rate = $khaata_exchange->aed_rate;
        $opr2 = $khaata_exchange->opr;
        $final_amount = $khaata_exchange->final_amount;
        $r_details = $khaata_exchange->details;
    }
}
$topArray = array(
    array('heading' => 'Sr. ', 'value' => $sr_no, 'id' => ''),
    array('heading' => 'DATE ', 'value' => my_date($created_at), 'id' => '')
); ?>
<div class="row">
    <div class="col-md-12">
        <form method="post" enctype="multipart/form-data" class=" table-form">
            <div class="d-flex justify-content-between flex-wrap gap-1 text-uppercase small">
                <div>
                    <?php foreach ($topArray as $item) {
                        echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '" class="text-muted me-5">' . $item['value'] . '</span>';
                    } ?>
                </div>
            </div>
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3 gx-0">
                        <div class="col-md-3">
                            <div class="input-group ">
                                <label for="client" class="mb-0">Type</label>
                                <div class="form-control d-flex bg-light">
                                    <?php $p_s_array = array(array('p', 'Purchase', 'text-success'), array('s', 'Sale', 'text-danger'));
                                    foreach ($p_s_array as $item) {
                                        $checked_ps = $item[0] == $p_s ? 'checked' : '';
                                        echo '<div class="form-check form-check-inline">';
                                        echo '<input class="form-check-input" type="radio" name="p_s" id="' . $item[0] . '" value="' . $item[0] . '" ' . $checked_ps . '>';
                                        echo '<label class="form-check-label ' . $item[2] . '" for="' . $item[0] . '">' . $item[1] . '</label>';
                                        echo '</div>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="curr1"><span id="label1"></span> CURRENCY </label>
                                <select id="curr1" name="curr1" class="form-select" required>
                                    <option selected hidden disabled value="">Select</option>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $p_crr_sel = $crr['name'] == $curr1 ? 'selected' : '';
                                        echo '<option ' . $p_crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="qty">Qty</label>
                                <input value="<?php echo $qty; ?>" type="text" id="qty" name="qty"
                                       class="form-control currency" autocomplete="off"
                                       onkeyup="calculateAmount(this)">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="per_price">Per Price</label>
                                <input value="<?php echo $per_price; ?>" type="text" id="per_price" name="per_price"
                                       class="form-control currency" autocomplete="off"
                                       onkeyup="calculateAmount(this)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="operator">Operator</label>
                                <select name="opr" class="form-select" id="operator" onchange="calculateAmount()">
                                    <?php $ops = array('Multiply (*)' => '*', 'Divide (/)' => '/');
                                    foreach ($ops as $opName => $op) {
                                        $op_sel = $opr == $op ? 'selected' : '';
                                        echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="curr2"><span id="label2"></span> CURRENCY </label>
                                <select id="curr2" name="curr2" class="form-select" REQUIRED>
                                    <option selected hidden disabled value="">Select</option>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $crr_sel = $crr['name'] == $curr2 ? 'selected' : '';
                                        echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="amount" class="bold">Amount</label>
                                <input value="<?php echo $amount; ?>" type="text" id="amount" name="amount"
                                       class="form-control currency" required readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="details">Details</label>
                                <input value="<?php echo $details; ?>" type="text" id="details" name="details"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button name="exchangeSubmit" id="exchangeSubmit" type="submit" class="btn btn-primary btn-sm">
                            Submit
                        </button>
                        <?php if ($action_hidden == "update") {
                            echo '<a href="' . $url . '" class="btn btn-dark btn-sm">Add New</a>';
                        } ?>
                    </div>
                    <input type="hidden" name="action" value="<?php echo base64_encode($action_hidden); ?>">
                    <input type="hidden" name="hidden_id" value="<?php echo $id_hidden; ?>">
                </div>
            </div>
        </form>
        <?php if ($id_hidden > 0) { ?>
            <div class="card table-form">
                <div class="card-body">
                    <form method="post">
                        <div class="row gx-1 gy-3 table-form mb-3">
                            <div class="col-md-3">
                                <div class="input-group position-relative">
                                    <label for="khaata_no1" class="text-success">Dr. A/c</label>
                                    <input type="text" id="khaata_no1" name="dr_khaata_no"
                                           class="form-control bg-transparent" required
                                           value="<?php echo $dr_khaata_no; ?>">
                                    <small class="error-response top-0" id="p_response"></small>
                                </div>
                                <input type="hidden" name="dr_khaata_id" id="p_khaata_id">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group position-relative">
                                    <label for="khaata_no2" class="text-danger">Cr. A/c</label>
                                    <input type="text" id="khaata_no2" name="cr_khaata_no"
                                           class="form-control bg-transparent" required
                                           value="<?php echo $cr_khaata_no; ?>">
                                    <small class="error-response top-0" id="s_response"></small>
                                </div>
                                <input type="hidden" name="cr_khaata_id" id="s_khaata_id">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="transfer_date">Date</label>
                                    <input type="date" class="form-control" id="transfer_date"
                                           name="transfer_date" required value="<?php echo $transfer_date; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="first_amount">Amount</label>
                                    <input value="<?php echo $first_amount; ?>" id="first_amount" readonly
                                           name="first_amount" class="form-control" required tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="aed_rate">AED Rate</label>
                                    <input value="<?php echo $aed_rate; ?>" type="text" id="aed_rate" name="aed_rate"
                                           required class="form-control currency"
                                           onkeyup="calculateAEDFinalAmount(this)">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="operator2">Operator</label>
                                    <select name="opr" class="form-select" id="operator2"
                                            onchange="calculateAEDFinalAmount()">
                                        <?php $ops = array('Multiply (*)' => '*', 'Divide (/)' => '/');
                                        foreach ($ops as $opName => $op) {
                                            $op_sel = $opr2 == $op ? 'selected' : '';
                                            echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="final_amount" class="bold">Final Amount</label>
                                    <input value="<?php echo $final_amount; ?>" type="text" id="final_amount"
                                           name="final_amount" class="form-control currency" required readonly>
                                </div>
                            </div>
                            <div class="col-11">
                                <div class="input-group">
                                    <label for="details">Details</label>
                                    <input type="text" name="details" id="details" class="form-control"
                                           value="<?php echo $r_details; ?>">
                                </div>
                            </div>
                            <div class="col-1">
                                <button name="ttrExchangeSubmit" type="submit" id="recordSubmit"
                                        class="btn btn-primary w-100 btn-sm"><i class="fa fa-upload"></i>Transfer
                                </button>
                            </div>
                            <input type="hidden" name="exch_id_hidden" value="<?php echo $id_hidden; ?>">
                            <input type="hidden" name="type" value="<?php //echo $record['type']; ?>">
                        </div>
                        <?php if ($record['khaata_exchange'] != '') {
                            $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $id_hidden, 'transfered_from' => 'exchange'));
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
        <?php } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['exchangeSubmit'])) {
    $type = 'danger';
    $msg = 'DB Error';
    $action = mysqli_real_escape_string($connect, $_POST['action']);
    $post_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $data = array(
        'p_s' => mysqli_real_escape_string($connect, $_POST['p_s']),
        'curr1' => mysqli_real_escape_string($connect, $_POST['curr1']),
        'qty' => mysqli_real_escape_string($connect, $_POST['qty']),
        'per_price' => mysqli_real_escape_string($connect, $_POST['per_price']),
        'opr' => mysqli_real_escape_string($connect, $_POST['opr']),
        'curr2' => mysqli_real_escape_string($connect, $_POST['curr2']),
        'amount' => $amount,
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
    );
    if (base64_decode($action) == 'insert') {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['branch_id'] = $branchId;
        $done = insert('exchanges', $data);
        if ($done) {
            $url .= "?id=" . $connect->insert_id;
            $type = 'success';
            $msg = ' Exchange Saved.';
        }
    } else {
        //$data['updated_at'] = date('Y-m-d H:i:s');
        $url .= "?id=" . $post_id;
        $done = update('exchanges', $data, array('id' => $post_id));
        if ($done) {
            $type = 'success';
            $msg = ' Exchange updated.';
        }
    }
    message($type, $url, $msg);
}
if (isset($_POST['ttrExchangeSubmit'])) {
    unset($_POST['ttrExchangeSubmit']);
    $post_json = json_encode($_POST);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);

    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $exch_id_hidden = mysqli_real_escape_string($connect, $_POST['exch_id_hidden']);
    //$type_post = mysqli_real_escape_string($connect, $_POST['type']);
    $url = $url . '?id=' . $exch_id_hidden;
    $type = ' Exchange';
    $transfered_from = 'exchange';
    $r_type = 'Business';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQ = fetch('exchanges', array('id' => $exch_id_hidden));
        $p_data = mysqli_fetch_assoc($pQ);
        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $exch_id_hidden,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $userId,
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $exch_id_hidden,
            'r_name' => $type,
            'r_no' => $exch_id_hidden,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Exchange # " . $exch_id_hidden;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $str .= "<span class='badge bg-dark mx-2'>Dr. " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Cr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Dr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $url .= '&view=1';
            $preData = array('khaata_exchange' => $post_json);
            $tlUpdated = update('exchanges', $preData, array('id' => $exch_id_hidden));
            $msg = 'Transferred to Business Roznamcha ' . $str;
            $msgType = 'success';
        } else {
            $msg = 'Transfer Error ';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    messageNew($msgType, $url, $msg);
} ?>
<script>
    $(document).ready(function () {
        $('input[name="p_s"]').change(function () {
            if ($('#p').is(':checked')) {
                $('#label1').text('PURCHASE');
                $('#label2').text('SALE');
            } else if ($('#s').is(':checked')) {
                $('#label1').text('SALE');
                $('#label2').text('PURCHASE');
            }
        });

        // Trigger change event on page load to set initial state
        $('input[name="p_s"]:checked').trigger('change');
    });
</script>
<script>
    function calculateAmount(e) {
        //var value = $(e).val();
        var qty = $("#qty").val();
        var per_price = $("#per_price").val();
        var amount = 0;
        let operator = $('#operator').find(":selected").val();
        if (operator === "/") {
            amount = Number(qty) / Number(per_price);
        } else {
            amount = Number(qty) * Number(per_price);
        }
        $("#amount").val(amount.toFixed(2));
    }

    function calculateAEDFinalAmount(e) {
        //var value = $(e).val();
        var first_amount = $("#first_amount").val();
        var aed_rate = $("#aed_rate").val();
        var amount = 0;
        let operator = $('#operator2').find(":selected").val();
        if (operator === "/") {
            amount = Number(first_amount) / Number(aed_rate);
        } else {
            amount = Number(first_amount) * Number(aed_rate);
        }
        $("#final_amount").val(isFinite(amount) ? amount : '');
    }
</script>
<script>
    function toggleQtyAndRequired() {
        var $toggleQty = $(".toggleQty");
        var $is_qty2 = $("#is_qty");
        if ($is_qty2.is(":checked")) {
            $toggleQty.show();
            $("#qty, #per_price, #operator").attr('required', true);
            /*if (getRoznamchaType() === 'Bill' || getRoznamchaType() === 'Cash') {

            }*/
            disableAmount(true);
            //$("#amount").prop('readonly', true);
        } else {
            $toggleQty.hide();
            $("#qty, #per_price, #operator").attr('required', false);
            disableAmount(false);
            //$("#amount").prop('readonly', false);
        }
    }

    function toggleContainers() {
        var r_type = $("#r_type").val();
        toggleQtyAndRequired();
        if (r_type !== '') {
            if (r_type === 'Business') {
                $(".bank-inputs,.bill-inputs,.cash-inputs").hide();
            }
            if (r_type === 'Bank') {
                $(".bill-inputs,.cash-inputs").hide();
                $(".bank-inputs").show();
            }
            if (r_type === 'Bill') {
                $(".bank-inputs,.cash-inputs").hide();
                $(".bill-inputs").show();
            }
            if (r_type === 'Cash') {
                $(".bank-inputs,.bill-inputs").hide();
                $(".cash-inputs").show();
            }
        }
    }

    function disableAmount(yes = true) {
        if (yes) {
            $("#amount").prop('readonly', true);
        } else {
            $("#amount").prop('readonly', false);
        }
    }

    function getRoznamchaType() {
        return $('#r_type').find(":selected").val();
    }
</script>
<script>
    function fetchKhaata(inputField, khaataId, responseId, prefix, khaataImageId, recordSubmitId) {
        let khaata_no = $(inputField).val();
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    $(khaataId).val(response.messages['khaata_id']);

                    // $(recordSubmitId).prop('disabled', false);
                    $(inputField).addClass('is-valid');
                    $(inputField).removeClass('is-invalid');
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    //$(responseId).text('INVALID');
                    $(inputField).removeClass('is-valid');
                    $(inputField).addClass('is-invalid');
                    $(khaataId).val(0);
                }
            }
        });
    }

    disableButton('recordSubmit');
    $(document).on('keyup', "#khaata_no1", function (e) {
        fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
    });
    fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");

    $(document).on('keyup', "#khaata_no2", function (e) {
        fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");
    });
    fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");

</script>

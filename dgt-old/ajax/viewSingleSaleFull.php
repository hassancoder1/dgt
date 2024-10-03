<?php require_once '../connection.php';
$sale_id = $_POST['id'];
$sale_pays_id = $_POST['sale_pays_id'];
if ($sale_id > 0) {
    $s_data = fetch('sales', array('id' => $sale_id));
    $record = mysqli_fetch_assoc($s_data);
    $transfer = $record['transfer'];
    $sale_id = $record['id'];
    $sale_type = $record['type'];
    $topArray = array(
        array('heading' => 'SALE D.', 'value' => $record['s_date'], 'id' => ''),
        array('heading' => 'SALE BILL#', 'value' => $sale_id, 'id' => ''),
        array('heading' => 'B.', 'value' => branchName($record['branch_id']), 'id' => ''),
        array('heading' => 'CITY', 'value' => $record['city'], 'id' => ''),
        array('heading' => 'TYPE', 'value' => $sale_type, 'id' => ''),
        array('heading' => 'SALE NAME', 'value' => $record['s_name'], 'id' => ''),
        array('heading' => 'RECEIVER NAME', 'value' => $record['receiver'], 'id' => ''),
        //array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $record['created_by'], 'username'), 'id' => ''),
    );
    $details_k = array();
    $is_loading = $is_receiving = $khaata_no = $khaata_id = $l_country = $l_port = $ctr_name = $r_country = $r_port = '';
    $l_date = $r_date = $arrival_date = date('Y-m-d');
    if (!empty($record['seller_json'])) {
        $collaps_show = '';
        $seller_json = json_decode($record['seller_json']);
        //var_dump($seller_json);
        $khaata_no = $seller_json->khaata_no;
        $khaata_id = $seller_json->khaata_id;
        if (isset($seller_json->is_loading)) {
            if ($seller_json->is_loading == 1) {
                $is_loading = 'checked';
                $l_country = $seller_json->l_country;
                $l_port = $seller_json->l_port;
                $l_date = $seller_json->l_date;
                $ctr_name = $seller_json->ctr_name;
            }
        }
        if (isset($seller_json->is_receiving)) {
            if ($seller_json->is_receiving == 1) {
                $is_receiving = 'checked';
                $r_country = $seller_json->r_country;
                $r_port = $seller_json->r_port;
                $r_date = $seller_json->r_date;
                $arrival_date = $seller_json->arrival_date;
            }
        }
        $details_k = ['indexes' => json_encode($seller_json->rep_indexes), 'vals' => json_encode($seller_json->rep_vals)];
    }
    $totalSaleAmount = totalSaleAmount($sale_id);
    $adv_paid_total = saleSpecificData($sale_id, 'adv_paid_total');
    $rem_paid_total = saleSpecificData($sale_id, 'rem_paid_total');
    $rem_amount = $totalSaleAmount - $record['pct_amt'];
    $rem_pct = 100 - $record['pct'];
    $bal = $totalSaleAmount - $rem_paid_total - $adv_paid_total;
    //var_dump($record); ?>
    <div class="row">
        <div class="col-10 order-1 content-column">
            <?php echo $_SESSION['response'] ?? ''; ?>
            <div class="row pt-3 gx-2 text-uppercase small">
                <div class="col-auto">
                    <?php foreach ($topArray as $item) {
                        echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                    } ?>
                </div>
                <div class="col-6">
                    <?php if ($khaata_id > 0) { ?>
                        <div class="position-relative ">
                            <!--<div class="info-div">Seller</div>-->
                            <div class=" d-flex p-1">
                                <div>
                                    <?php $seller = khaataSingle($khaata_id);
                                    $array_acc1 = array(array('label' => 'Seller A/C#', 'id' => $khaata_no), array('label' => 'A/C NAME', 'id' => $seller['khaata_name']), array('label' => 'B.', 'id' => branchName($seller['branch_id'])), array('label' => 'CAT.', 'id' => catName($seller['cat_id'])), array('label' => 'BUSINESS', 'id' => $seller['business_name']), array('label' => 'COMPANY', 'id' => $seller['comp_name']));
                                    foreach ($array_acc1 as $item) {
                                        echo '<b>' . $item['label'] . ' </b><span class="text-muted">' . $item['id'] . '</span><br>';
                                    } ?>
                                </div>
                                <div>
                                    <?php $details2 = ['indexes' => $seller['indexes'], 'vals' => $seller['vals']];
                                    echo displayKhaataDetails($details2); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col">
                    <?php echo $record['report'] != '' ? '<b>REPORT: </b>' . $record['report'] : ''; ?>
                </div>
            </div>
            <?php $records2q = fetch('sale_details', array('parent_id' => $sale_id));
            $final_amounts = $amounts = $qtys = $totals = 0;
            $country = $allot = $goods = $curr = $rate = $curr2 = $rate2 = '';
            $rows = mysqli_num_rows($records2q);
            while ($record2 = mysqli_fetch_assoc($records2q)) {
                $arr1 = array(array('GOODS', goodsName($record2['goods_id'])), array('Size', $record2['size']), array('Brand', $record2['brand']));
                $arr2 = array(array('Qty Name', $record2['qty_name']), array('Qty#', $record2['qty_no']), array('Qty KGs', $record2['qty_kgs']), array('Total KGs', $record2['total_kgs']));
                $arr3 = array(array('Empty KGs', $record2['empty_kgs']), array('Total Qty KGs', $record2['total_qty_kgs']), array('Net KGs', $record2['net_kgs']), array('Divide', $record2['divide']));
                $arr4 = array(array('Weight', $record2['weight']), array('Total', $record2['total']), array('Price', $record2['price'] . ' PRICE'), array('Rate', $record2['rate1']));
                $arr5 = array(array('Amount ', $record2['amount'] . '<sub>' . $record2['currency1'] . '</sub>'), array('Qty?', $record2['is_qty'] == 1 ? 'YES' : 'NO'), array('Rate', $record2['rate2'] . ' [' . $record2['opr'] . ']'), array('Final Amount ', $record2['final_amount'] . '<sub>' . $record2['currency2'] . '</sub>'));
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
                                <?php
                                foreach ($arr3 as $item) {
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
                        </div>
                    </div>
                </div>
            <?php }
            if (!empty($record['seller_json'])) { ?>
                <div class="card mb-1 text-uppercase small mt-1">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-6">
                                <table class="table mb-2 table-sm">
                                    <tbody>
                                    <?php echo '<tr><td class="fw-bold text-uppercase">Loading Country</td><td>' . $seller_json->l_country . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Loading Port</td><td>' . $seller_json->l_port . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Loading Date</td><td>' . $seller_json->l_date . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Container Name</td><td>' . $seller_json->ctr_name . '</td></tr>'; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table mb-2 table-sm">
                                    <tbody>
                                    <?php echo '<tr><td class="fw-bold text-uppercase">Receiving Country</td><td>' . $seller_json->r_country . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Receiving Port</td><td>' . $seller_json->r_port . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Receiving Date</td><td>' . $seller_json->r_date . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Arrival Data</td><td>' . $seller_json->arrival_date . '</td></tr>'; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <?php $details_k = ['indexes' => json_encode($seller_json->rep_indexes), 'vals' => json_encode($seller_json->rep_vals)];
                                $reps = displayKhaataDetails($details_k, true);
                                if (array_key_exists('Condition', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Goods Condition Report <span class="fw-normal text-decoration-underline">' . $reps['Condition'] . '</span></div>';
                                }
                                if (array_key_exists('Loading', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Loading Report <span class="fw-normal text-decoration-underline">' . $reps['Loading'] . '</span></div>';
                                }
                                if (array_key_exists('Booking', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Booking Report <span class="fw-normal text-decoration-underline">' . $reps['Booking'] . '</span></div>';
                                }
                                if (array_key_exists('Final', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Final Report <span class="fw-normal text-decoration-underline">' . $reps['Final'] . '</span></div>';
                                } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 d-none">
                                <?php echo '<div><b>Loading Country</b> ' . $l_country . '</div>'; ?>
                                <?php echo '<div><b>Loading Port</b> ' . $l_port . '</div>'; ?>
                                <?php echo '<div><b>Loading Date</b> ' . $l_date . '</div>'; ?>
                                <?php echo '<div><b>Container Name</b> ' . $ctr_name . '</div>'; ?>
                            </div>
                            <div class="col-3 d-none">
                                <?php echo '<div><b>Receiving Country</b> ' . $r_country . '</div>'; ?>
                                <?php echo '<div><b>Receiving Port</b> ' . $r_port . '</div>'; ?>
                                <?php echo '<div><b>Receiving Date</b> ' . $r_date . '</div>'; ?>
                                <?php echo '<div><b>Arrival Date</b> ' . $arrival_date . '</div>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <?php if ($record['khaata_tr1'] != '') {
                    $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $sale_id, 'transfered_from' => 'sale_' . $record['type']));
                    $roz = mysqli_fetch_assoc($rozQ);
                    $roz_arr1 = array(
                        array('Sr#', SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']),
                        array('Date', $roz['r_date']),
                        array('ID', $roz['username']),
                        array('Branch', branchName($roz['branch_id'])),
                    );
                    $roz_arr2 = array(
                        array('Roz.#', $roz['roznamcha_no']),
                        array('Name', $roz['r_name']),
                        array('No', $roz['r_no']),
                    );
                    $roz_arr3 = array(
                        array('Dr.', $roz['amount']),
                        //array('Cr.', 0),
                    );
                    $roz_arr4 = array(
                        array('Total Amount', round($final_amounts, 2)),
                        array('Percent', $record['pct'] . '%'),
                        array('Advance', round($record['pct_amt'], 2)),
                    ); ?>
                    <div class="col-2">
                        <?php foreach ($roz_arr1 as $item) {
                            echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                        } ?>
                    </div>
                    <div class="col-2">
                        <?php foreach ($roz_arr2 as $item) {
                            echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                        } ?>
                    </div>
                    <div class="col-2">
                        <?php foreach ($roz_arr3 as $item) {
                            echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                        } ?>
                    </div>
                    <div class="col-2">
                        <?php foreach ($roz_arr4 as $item) {
                            echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                        } ?>
                    </div>
                <?php } ?>
                <div class="col">
                    <?php echo '<b>Remaining </b>' . round($rem_amount) . '<sub>' . $rem_pct . '%</sub>'; ?>
                </div>
            </div>
        </div>
        <div class="col-2 order-0 fixed-sidebar table-form">
            <div>
                <div class="bottom-buttons">
                    <div class="px-2">
                        <a href="print/sale-invoice?s_id=<?php echo $sale_id; ?>&action=rem" target="_blank"
                           class="btn btn-success btn-sm w-100">PRINT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    function fetchKhaata(inputField, khaataId, responseId, prefix, khaataImageId, recordSubmitId) {
        let khaata_no = $(inputField).val();
        console.log(khaata_no);
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    $(khaataId).val(response.messages['khaata_id']);
                    $(prefix + '_khaata_no').text(khaata_no);
                    $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                    $(prefix + '_b_name').text(response.messages['b_name']);
                    $(prefix + '_c_name').text(response.messages['name']);
                    $(prefix + '_business_name').text(response.messages['business_name']);
                    $(prefix + '_address').text(response.messages['address']);
                    $(prefix + '_comp_name').text(response.messages['comp_name']);
                    var details = {
                        indexes: response.messages['indexes'],
                        vals: response.messages['vals']
                    };
                    $(prefix + '_contacts').html(displayKhaataDetails(details));
                    $(khaataImageId).attr("src", response.messages['image']);
                    $(recordSubmitId).prop('disabled', false);
                    $(responseId).text('');
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    $(responseId).text('INVALID');
                    $(prefix + '_khaata_no').text('---');
                    $(prefix + '_khaata_name').text('---');
                    $(prefix + '_c_name').text('---');
                    $(prefix + '_b_name').text('---');
                    $(prefix + '_comp_name').text('---');
                    $(prefix + '_business_name').text('---');
                    $(prefix + '_address').text('---');
                    $(prefix + '_contacts').text('');
                    $(khaataImageId).attr("src", 'assets/images/logo-placeholder.png');
                    $(khaataId).val(0);
                }
            }
        });
    }

    function displayKhaataDetails(details) {
        var html = ''; // Initialize an empty string to store HTML

        if (details.indexes && details.vals) {
            var indexes = JSON.parse(details.indexes);
            var vals = JSON.parse(details.vals);

            if (Array.isArray(indexes) && Array.isArray(vals)) {
                var count = Math.min(indexes.length, vals.length);

                for (var i = 0; i < count; i++) {
                    var key = indexes[i];
                    var value = vals[i];
                    // Construct the HTML string
                    html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                }
            }
        }

        return html; // Return the constructed HTML string
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
        if (Number(balance) >= 1) {
            if (Number(balance) <= Number(final_amount)) {
                disableButton('recordSubmit');
            } else {
                enableButton('recordSubmit');
            }
        } else {
            disableButton('recordSubmit');
        }
    }
</script>


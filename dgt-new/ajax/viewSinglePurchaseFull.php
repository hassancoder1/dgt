<?php require_once '../connection.php';
$purchase_id = $_POST['id'];
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
        <div class="col-10 order-1 content-column">
            <div class="card">
                <div class="card-body p-2 text-uppercase small">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <div class="row gx-2">
                        <div class="col-auto">
                            <?php foreach ($topArray as $item) {
                                echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                            } ?>
                        </div>
                        <div class="col">
                            <div class="card- mb-1 position-relative">
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
                                            echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                                        } ?>
                                    </div>
                                    <div>
                                        <?php //echo '<span class="text-muted" id="p_contacts">';
                                        $details1 = ['indexes' => $p_khaata['indexes'], 'vals' => $p_khaata['vals']];
                                        echo displayKhaataDetails($details1);
                                        //echo '</span>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-1 position-relative">
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
                                            echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                                        } ?>
                                    </div>
                                    <div>
                                        <?php //echo '<span class="text-muted" id="s_contacts">';
                                        $details2 = ['indexes' => $s_khaata['indexes'], 'vals' => $s_khaata['vals']];
                                        echo displayKhaataDetails($details2);
                                        ///echo '</span>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
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
                            array('Total Qty KGs', round($record2['total_qty_kgs'],2)),
                            array('Net KGs', round($record2['net_kgs'],2)),
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
                            array('Amount ', round($record2['amount']) . '<sub>' . $record2['currency1'] . '</sub>'),
                            array('Qty?', $record2['is_qty'] == 1 ? 'YES' : 'NO'),
                            array('Rate', $record2['rate2'] . ' [' . $record2['opr'] . ']')
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
                        <div class="row mb-1">
                            <div class="col">
                                <?php foreach ($arr1 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr2 as $item) {
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
                                    if ($item[1] == 'NO') exit();
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr6 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                    if ($item[1] == 'NO') exit();
                                } ?>
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
                    <?php if ($record['khaata_tr1'] != '') {
                        $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $purchase_id, 'transfered_from' => 'purchase_' . $record['type']));
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
                            //array('Percent', $record['pct'] . '%'),
                            //array('Advance', round($record['pct_amt'], 2)),
                        );

                    } ?>
                    <hr class="my-0">
                    <div class="row">
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
                        <div class="col">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-2 order-0 fixed-sidebar table-form">
            <div>
                <div></div>
                <div class="bottom-buttons">
                    <div class="px-2">
                        <a href="print/purchase-booking?p_id=<?php echo $purchase_id; ?>&action=booking" target="_blank"
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
        if (Number(balance) <= Number(final_amount)) {

            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }
</script>


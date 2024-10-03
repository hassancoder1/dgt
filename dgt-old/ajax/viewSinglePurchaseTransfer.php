<?php require_once '../connection.php';
$purchase_id = $_POST['id'];
$pd_id = $_POST['pd_id'];
if ($purchase_id > 0) {
    $p_data = fetch('purchases', array('id' => $purchase_id));
    $record = mysqli_fetch_assoc($p_data);
    $purchase_id = $record['id'];
    $purchase_type = $record['type'];
    $p_khaata = khaataSingle($record['p_khaata_id']);
    $s_khaata = khaataSingle($record['s_khaata_id']);
    $records2q = fetch('purchase_details', array('id' => $pd_id));
    $rows = mysqli_num_rows($records2q);
    $record2 = mysqli_fetch_assoc($records2q);
    $imp_json = json_decode($record2['imp_json']);
    $exp_json = json_decode($record2['exp_json']);
    $notify_json = json_decode($record2['notify_json']);
    $ware_json = json_decode($record2['ware_json']);
    $bail_json = json_decode($record2['bail_json']);
    $pd_sr = $record2['d_sr'];
    $topArray = array(
        array('heading' => 'Sr#', 'value' => $purchase_id . '-' . $pd_sr, 'id' => ''),
        array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $record['created_by'], 'username'), 'id' => ''),
        array('heading' => 'DATE', 'value' => $record['p_date'], 'id' => ''),
        array('heading' => 'BRANCH', 'value' => branchName($record['branch_id']), 'id' => ''),
        array('heading' => 'COUNTRY', 'value' => $record['country'], 'id' => ''),
        array('heading' => 'TYPE', 'value' => $record['type'], 'id' => ''),
        array('heading' => 'ALLOT NAME', 'value' => $record['allot'], 'id' => ''),
    );
    //var_dump($record); ?>
    <div class="row">
        <div class="col-md-10 order-1 content-column table-form">
            <div class="card">
                <div class="card-body p-2 text-uppercase small">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <div class="row">
                        <div class="col-4">
                            <?php foreach ($topArray as $item) {
                                echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                            } ?>
                        </div>
                        <div class="col-4 p-1">
                            <div class="info-div- bg-light">PURCHASER</div>
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
                        <div class="col-4 p-1">
                            <div class="info-div- bg-light">SELLER</div>
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
                        <div class="col p-1">
                            <div class="bg-light">IMPORTER</div>
                            <?php if (!empty($imp_json)) {
                                echo '<b>NAME</b>' . $imp_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                echo '<br><b>CITY</b>' . $imp_json->city;
                                echo '<br><b>ADDRESS</b>' . $imp_json->address;
                                //echo '<br><b>REPORT</b>' . $imp_json->report;
                            } ?>
                        </div>
                        <div class="col p-1">
                            <div class="info-div- bg-light">EXPORTER</div>
                            <?php if (!empty($exp_json)) {
                                echo '<b>NAME</b>' . $exp_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                echo '<br><b>CITY</b>' . $exp_json->city;
                                echo '<br><b>ADDRESS</b>' . $exp_json->address;
                                //echo '<br><b>REPORT</b>' . $exp_json->report;
                            } ?>
                        </div>
                        <div class="col p-1">
                            <div class="info-div- bg-light">NOTIFY PARTY</div>
                            <?php if (!empty($notify_json)) {
                                echo '<b>NAME</b>' . $notify_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                echo '<br><b>CITY</b>' . $notify_json->city;
                                echo '<br><b>ADDRESS</b>' . $notify_json->address;
                                //echo '<br><b>REPORT</b>' . $notify_json->report;
                            } ?>
                        </div>
                        <div class="col p-1">
                            <div class="bg-light">WAREHOUSE</div>
                            <?php if (!empty($ware_json)) {
                                echo '<b>NAME</b>' . $ware_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $ware_json->country;
                                echo '<br><b>CITY</b>' . $ware_json->city;
                                echo '<br><b>ADDRESS</b>' . $ware_json->address;
                                //echo '<br><b>REPORT</b>' . $notify_json->report;
                            } ?>
                        </div>
                    </div>
                    <hr class="my-0">
                    <?php $arr1 = array(
                        array('GOODS', goodsName($record2['goods_id'])),
                        array('Size', $record2['size']),
                        array('Brand', $record2['brand']),
                        array('Origin', $record2['origin']),
                    );
                    $arr2 = array(
                        array('Qty Name', $record2['qty_name']),
                        array('Qty#', $record2['qty_no']),
                        //array('Qty KGs', $record2['qty_kgs']),
                        array('Total KGs', $record2['total_kgs']),
                        // array('Empty KGs', $record2['empty_kgs']),
                        array('Total Qty KGs', round($record2['total_qty_kgs'], 2)),
                        array('Net KGs', round($record2['net_kgs'], 2)),
                    );
                    $l_country = $l_port = $l_date = $ctr_name = $r_country = $r_port = $r_date = $arrival_date = '';
                    if ($record['is_loading'] == 1) {
                        $loading_json = json_decode($record['loading_json']);
                        $l_country = $loading_json->l_country;
                        $l_port = $loading_json->l_port;
                        $l_date = $loading_json->l_date;
                        $ctr_name = $loading_json->ctr_name;
                    }
                    if ($record['is_receiving'] == 1) {
                        $receiving_json = json_decode($record['receiving_json']);
                        $r_country = $receiving_json->r_country;
                        $r_port = $receiving_json->r_port;
                        $r_date = $receiving_json->r_date;
                        $arrival_date = $receiving_json->arrival_date;
                    }
                    $arr3 = array(
                        array('Loading Country', $l_country),
                        array('Loading Port', $l_port),
                        array('Loading Date', $l_date),
                        array('Container Name', $ctr_name),
                    );
                    $arr4 = array(
                        array('Receiving Country', $r_country),
                        array('Receiving Port', $r_port),
                        array('Receiving Date', $r_date),
                        array('Arrival Date', $arrival_date),
                    );
                    $arr5 = array(
                        //array('Divide', $record2['divide']),
                        array('Weight', $record2['weight']),
                        array('Total', $record2['total']),
                        //array('Price', $record2['price'] . ' PRICE'),
                        //array('Currency', $record2['currency1']),
                        //array('Rate', $record2['rate1']),
                        array('Amount ', round($record2['amount']) . '<sub>' . $record2['currency1'] . '</sub>'),
                        array('Qty?', $record2['is_qty'] == 1 ? 'YES' : 'NO'),
                        array('Rate', $record2['rate2'] . ' [' . $record2['opr'] . ']'),
                        array('Final Amount ', $record2['final_amount'] . '<sub>' . $record2['currency2'] . '</sub>'),
                    ); ?>
                    <div class="row mb-1">
                        <div class="col-2">
                            <?php foreach ($arr1 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php foreach ($arr2 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php foreach ($arr3 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($arr4 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                    </div>
                    <?php //var_dump($bail_json);
                    if (!empty($bail_json)) {
                        $arr_bail1 = array(
                            'Bail#' => $bail_json->bail_no,
                            'Container Name' => $bail_json->container_name,
                            'Container#' => $bail_json->container_no,
                            'Container Size' => $bail_json->container_size,
                            'Bail Report' => $bail_json->bail_report
                        );
                        $arr_bail2 = array(
                            'Loading Country' => $bail_json->loading_country,
                            'Loading Port' => $bail_json->loading_port,
                            'Loading Date' => $bail_json->loading_date,
                            'Receiving Country' => $bail_json->receiving_country,
                            'Receiving Port' => $bail_json->receiving_port,
                            'Receiving Date' => $bail_json->receiving_date,
                            'Freight Period' => $bail_json->freight,
                        );
                        $arr_bail_shipp = array(
                            'Shipping Lane Name' => $bail_json->loading_shipper_address,
                            'Address' => $bail_json->receiving_shipper_address,
                            'Company' => $bail_json->ship_comp,
                            'Phone' => $bail_json->ship_phone,
                            'Email' => $bail_json->ship_email,
                            'WhatsApp' => $bail_json->ship_wa,
                        ); ?>
                        <hr class="my-0">
                        <div class="row gx-1">
                            <div class="col-4">
                                <?php foreach ($arr_bail1 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                            <div class="col-4">
                                <?php foreach ($arr_bail2 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                            <div class="col-4">
                                <?php foreach ($arr_bail_shipp as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 order-0 fixed-sidebar table-form shadow">
            <!--<button data-title="Warehouse" data-type="Warehouse" data-pd-id="<?php /*echo $pd_id; */ ?>" class="my-1 openSecondModal btn btn-dark btn-sm w-100">Warehouse</button>-->
            <div class="mt-1">
                <input type="file" id="attachments" placeholder="attachments" name="attachments[]"
                       class="d-none" multiple>
                <input type="button" class="form-control rounded-1 bg-dark text-white" value="+ Add Documents"
                       onclick="document.getElementById('attachments').click();"/>
                <?php $attachments = fetch('attachments', array('source_id' => $pd_id, 'source_name' => 'purchase_loading'));
                if (mysqli_num_rows($attachments) > 0) {
                    echo '<h6 class=" mb-0 d-inline fw-bold">Bail Documents </h6>';
                    $no = 1;
                    while ($attachment = mysqli_fetch_assoc($attachments)) {
                        $link = 'attachments/' . $attachment['attachment'];
                        echo $no . '.<a class="text-decoration-underline" href="' . $link . '" target="_blank">' . readMore($attachment['attachment'], 20) . '</a> ';
                        $no++;
                    }
                } ?>
            </div>
            <div class="mt-4">
                <form method="post" class="d-flex-">
                    <select class="form-select" id="transfer_as" name="transfer_as" required>
                        <option value="" hidden="">Select</option>
                        <?php $transfer_array = array('Agent Form' => '1', 'Stock Form' => '2');
                        foreach ($transfer_array as $item => $value) {
                            $t_sel = $value == $record2['transfer_as'] ? 'selected' : '';
                            echo '<option ' . $t_sel . ' value="' . $value . '">' . $item . '</option>';
                        } ?>
                    </select>
                    <input value="<?php echo $purchase_id; ?>" type="hidden" name="p_id_hidden">
                    <input value="<?php echo $pd_id; ?>" type="hidden" name="pd_id_hidden">
                    <button name="transferASFormSubmit" type="submit" class="btn btn-sm btn-dark w-100">
                        Transfer Now
                    </button>
                </form>
                <?php if ($record2['transfer_as'] > 0) {
                    echo '<i class="fa fa-check-double text-success"></i> Transferred';
                } ?>
            </div>
            <div class="bottom-buttons">
                <div class="px-2">
                    <a href="print/purchase-booking?p_id=<?php echo $purchase_id; ?>&action=booking" target="_blank"
                       class="btn btn-success btn-sm w-100 mt-3">PRINT</a>
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

</script>
<script>
    VirtualSelect.init({
        ele: '.v-select-sm',
        placeholder: 'Choose',
        // showValueAsTags: true,
        optionHeight: '30px',
        showSelectedOptionsFirst: true,
        // allowNewOption: true,
        // hasOptionDescription: true,
        search: true
    });

    var openSecondModals = document.getElementsByClassName('openSecondModal');
    Array.from(openSecondModals).forEach(function (element) {
        element.addEventListener('click', function () {
            var secondModal = new bootstrap.Modal(document.getElementById('secondModal'));
            secondModal.show();
            var pd_id = $(this).data('pd-id');
            var title = $(this).data('title');
            var type = $(this).data('type');
            showSecondModal(pd_id, title, type);
        });
    });

    function showSecondModal(pd_id = null, title = null, type = null) {
        if (pd_id) {
            $.ajax({
                url: 'ajax/viewSinglePurchaseLoadingSecondModal.php',
                type: 'post',
                data: {pd_id: pd_id, title: title, type: type},
                success: function (response) {
                    $('#addImpExpNotify').html(response);
                    $('#secondModalLabel').html(title);
                    $('#party-type').val(type);
                }
            });
        }
    }
</script>
<script>
    document.getElementById('openBailModal').addEventListener('click', function () {
        // Show the second modal without hiding the first one
        var bailModal = new bootstrap.Modal(document.getElementById('bailModal'));
        bailModal.show();
        var pd_id = $(this).data('pd-id');
        if (pd_id) {
            $.ajax({
                url: 'ajax/viewSinglePurchaseLoadingBailModal.php',
                type: 'post',
                data: {pd_id: pd_id},
                success: function (response) {
                    $('#bailModalForm').html(response);
                }
            });
        }
    });
</script>
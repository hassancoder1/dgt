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
    $tware_json = json_decode($record2['tware_json']);
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
            <div class="card px-1 mb-1 rounded-0 text-uppercase small">
                <div class="row gx-1">
                    <div class="col-2">
                        <?php foreach ($topArray as $item) {
                            echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                        } ?>
                    </div>
                    <div class="col">
                        <span class="bg-secondary text-white px-2">PURCHASER</span>
                        <?php $array_acc1 = array(
                            array('label' => 'A/C#', 'id' => 'p_khaata_no', 'val' => $record['p_khaata_no']),
                            array('label' => 'A/C NAME', 'id' => 'p_khaata_name', 'val' => $p_khaata['khaata_name']),
                            /*array('label' => 'BRANCH', 'id' => 'p_b_name', 'val' => branchName($p_khaata['branch_id'])),
                            array('label' => 'CATEGORY', 'id' => 'p_c_name', 'val' => catName($p_khaata['cat_id'])),
                            array('label' => 'BUSINESS', 'id' => 'p_business_name', 'val' => $p_khaata['business_name']),*/
                            array('label' => 'COMPANY', 'id' => 'p_comp_name', 'val' => $p_khaata['comp_name'])
                        ); ?>
                        <?php foreach ($array_acc1 as $item) {
                            echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                        } ?>
                    </div>
                    <div class="col">
                        <span class="bg-secondary text-white px-2">SELLER</span>
                        <?php $array_acc2 = array(
                            array('label' => 'A/C#', 'id' => 's_khaata_no', 'val' => $record['s_khaata_no']),
                            array('label' => 'A/C NAME', 'id' => 's_khaata_name', 'val' => $s_khaata['khaata_name']),
                            /*array('label' => 'BRANCH', 'id' => 's_b_name', 'val' => branchName($s_khaata['branch_id'])),
                            array('label' => 'CATEGORY', 'id' => 's_c_name', 'val' => catName($s_khaata['cat_id'])),
                            array('label' => 'BUSINESS', 'id' => 's_business_name', 'val' => $s_khaata['business_name']),*/
                            array('label' => 'COMPANY', 'id' => 's_comp_name', 'val' => $s_khaata['comp_name'])
                        ); ?>
                        <?php foreach ($array_acc2 as $item) {
                            echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                        } ?>
                    </div>
                    <?php if ($record['type'] == 'booking') { ?>
                        <div class="col">
                            <span class="bg-secondary text-white px-2">IMPORTER</span>
                            <?php if (!empty($imp_json)) {
                                echo '<b>NAME</b>' . $imp_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                echo '<br><b>CITY</b>' . $imp_json->city;
                                echo '<br><b>ADDRESS</b>' . $imp_json->address;
                                //echo '<br><b>REPORT</b>' . $imp_json->report;
                            } ?>
                        </div>
                        <div class="col">
                            <span class="bg-secondary text-white px-2">EXPORTER</span>
                            <?php if (!empty($exp_json)) {
                                echo '<b>NAME</b>' . $exp_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                echo '<br><b>CITY</b>' . $exp_json->city;
                                echo '<br><b>ADDRESS</b>' . $exp_json->address;
                                //echo '<br><b>REPORT</b>' . $exp_json->report;
                            } ?>
                        </div>
                        <div class="col">
                            <span class="bg-secondary text-white px-2">NOTIFY PARTY</span>
                            <?php if (!empty($notify_json)) {
                                echo '<b>NAME</b>' . $notify_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                echo '<br><b>CITY</b>' . $notify_json->city;
                                echo '<br><b>ADDRESS</b>' . $notify_json->address;
                                //echo '<br><b>REPORT</b>' . $notify_json->report;
                            } ?>
                        </div>
                    <?php } else { ?>
                        <div class="col">
                            <span class="bg-secondary text-white px-2">LOADING WAREHOUSE</span>
                            <?php if (!empty($ware_json)) {
                                echo '<b>NAME</b>' . $ware_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $ware_json->country;
                                echo '<br><b>CITY</b>' . $ware_json->city;
                                echo '<br><b>ADDRESS</b>' . $ware_json->address;
                            } ?>
                        </div>
                        <div class="col">
                            <span class="bg-secondary text-white px-2">TRANSFER WAREHOUSE</span>
                            <?php if (!empty($tware_json)) {
                                echo '<b>NAME</b>' . $tware_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $tware_json->country;
                                echo '<br><b>CITY</b>' . $tware_json->city;
                                echo '<br><b>ADDRESS</b>' . $tware_json->address;
                                //echo '<br><b>REPORT</b>' . $notify_json->report;
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($record['type'] == 'booking') { ?>
                <div class="card px-1 mb-1 rounded-0 text-uppercase small">
                    <div class="row gx-1">
                        <div class="col-6">
                            <span class="bg-secondary text-white px-2">IMPORT AGENT & FILLED DETAILS</span>
                            <?php if ($record2['is_imp_agent'] == 1) {
                                $iAQ = fetch('purchase_agents', array('type' => 'import', 'd_id' => $pd_id));
                                if (mysqli_num_rows($iAQ) > 0) {
                                    $ii = mysqli_fetch_assoc($iAQ);
                                    $impAgentKhaata = khaataSingle($ii['khaata_id']);
                                    $array_agent1 = array(
                                        array('label' => 'A/C#', 'val' => $impAgentKhaata['khaata_no']),
                                        array('label' => 'A/C NAME', 'val' => $impAgentKhaata['khaata_name']),
                                        array('label' => 'BRANCH', 'val' => branchName($impAgentKhaata['branch_id'])),
                                        array('label' => 'CATEGORY', 'val' => catName($impAgentKhaata['cat_id'])),
                                        array('label' => 'BUSINESS', 'val' => $impAgentKhaata['business_name']),
                                        array('label' => 'COMPANY', 'val' => $impAgentKhaata['comp_name'])
                                    ); ?>
                                    <div class="row gx-1">
                                        <div class="col-5">
                                            <?php foreach ($array_agent1 as $item) {
                                                echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                                            } ?>
                                        </div>
                                        <div class="col-7">
                                            <?php $check_agent_query_imp = fetch('purchase_agents', array('d_id' => $pd_id, 'khaata_id' => $ii['khaata_id'], 'type' => 'import'));
                                            if (mysqli_num_rows($check_agent_query_imp) > 0) {
                                                $check_agent_data_imp = mysqli_fetch_assoc($check_agent_query_imp);
                                                $details_imp = json_decode($check_agent_data_imp['details']);
                                                if (!empty($details_imp)) {
                                                    echo '<div class="col">';
                                                    echo '<b>Documents Receiving Date</b>' . $details_imp->doc_rec_date;
                                                    echo '<br><b>Container Receiving Date</b>' . $details_imp->ctr_rec_date;
                                                    echo '<br><b>Entry Bill# </b>' . $details_imp->bill_no;
                                                    echo '<br><b>Entry Bill Date</b>' . $details_imp->bill_date;
                                                    echo '</div>';
                                                    echo '<div class="col">';
                                                    echo '<b>Container Return</b>' . $details_imp->ctr_return_date;
                                                    echo '<br><b>Loading Truck#</b>' . $details_imp->truck_no;
                                                    echo '<br><b>Container Name</b>' . $details_imp->ctr_name;
                                                    echo '<br><b>Container#</b>' . $details_imp->ctr_no;
                                                    echo '</div>';
                                                    echo '<div class="col">';
                                                    echo '<b>Driver Name</b>' . $details_imp->driver_name;
                                                    echo '<br><b>Phone</b>' . $details_imp->driver_phone;
                                                    echo '<br><b>Report</b>' . $details_imp->report;
                                                    echo '</div>';
                                                } else {
                                                    echo '<br><span class="text-warning bold">PENDING...</span>';
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                <?php }
                            } else {
                                echo '<h5 class="text-danger">NO IMPORT AGENT</h5>';
                                echo 'Contact Admin for more details';
                            } ?>
                        </div>
                        <div class="col-6">
                            <span class="bg-secondary text-white px-2">EXPORT AGENT & FILLED DETAILS</span>
                            <?php if ($record2['is_exp_agent'] == 1) {
                                $eAQ = fetch('purchase_agents', array('type' => 'export', 'd_id' => $pd_id));
                                if (mysqli_num_rows($eAQ) > 0) {
                                    $ee = mysqli_fetch_assoc($eAQ);
                                    $expAgentKhaata = khaataSingle($ee['khaata_id']);
                                    $array_agent2 = array(
                                        array('label' => 'A/C#', 'val' => $expAgentKhaata['khaata_no']),
                                        array('label' => 'A/C NAME', 'val' => $expAgentKhaata['khaata_name']),
                                        array('label' => 'BRANCH', 'val' => branchName($expAgentKhaata['branch_id'])),
                                        array('label' => 'CATEGORY', 'val' => catName($expAgentKhaata['cat_id'])),
                                        array('label' => 'BUSINESS', 'val' => $expAgentKhaata['business_name']),
                                        array('label' => 'COMPANY', 'val' => $expAgentKhaata['comp_name'])
                                    ); ?>
                                    <div class="row gx-1">
                                        <div class="col-5">
                                            <?php foreach ($array_agent2 as $item) {
                                                echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                                            } ?>
                                        </div>
                                        <div class="col-7">
                                            <?php $check_agent_query_imp = fetch('purchase_agents', array('d_id' => $pd_id, 'khaata_id' => $ii['khaata_id'], 'type' => 'import'));
                                            if (mysqli_num_rows($check_agent_query_imp) > 0) {
                                                $check_agent_data_imp = mysqli_fetch_assoc($check_agent_query_imp);
                                                $details_imp = json_decode($check_agent_data_imp['details']);
                                                if (!empty($details_imp)) {
                                                    echo '<div class="col">';
                                                    echo '<b>Documents Receiving Date</b>' . $details_imp->doc_rec_date;
                                                    echo '<br><b>Container Receiving Date</b>' . $details_imp->ctr_rec_date;
                                                    echo '<br><b>Entry Bill# </b>' . $details_imp->bill_no;
                                                    echo '<br><b>Entry Bill Date</b>' . $details_imp->bill_date;
                                                    echo '</div>';
                                                    echo '<div class="col">';
                                                    echo '<b>Container Return</b>' . $details_imp->ctr_return_date;
                                                    echo '<br><b>Loading Truck#</b>' . $details_imp->truck_no;
                                                    echo '<br><b>Container Name</b>' . $details_imp->ctr_name;
                                                    echo '<br><b>Container#</b>' . $details_imp->ctr_no;
                                                    echo '</div>';
                                                    echo '<div class="col">';
                                                    echo '<b>Driver Name</b>' . $details_imp->driver_name;
                                                    echo '<br><b>Phone</b>' . $details_imp->driver_phone;
                                                    echo '<br><b>Report</b>' . $details_imp->report;
                                                    echo '</div>';
                                                } else {
                                                    echo '<br><span class="text-warning bold">PENDING...</span>';
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<h5 class="text-danger">NO EXPORT AGENT</h5>';
                                echo 'Contact Admin for more details';
                            } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="card px-1 mb-1 rounded-0 text-uppercase small">
                <span class="bg-secondary text-white px-2" style="width: fit-content">GOODS DETAILS</span>
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
                <div class="row ">
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
            </div>
            <div class="card px-1 mb-1 rounded-0 text-uppercase small">
                <span class="bg-secondary text-white px-2" style="width: fit-content">BAIL DETAILS</span>
                <?php //var_dump($bail_json);
                if (!empty($bail_json)) {
                    if ($record['type'] == 'booking') {
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
                    <?php } else {
                        $arr_bail1 = array(
                            'Report' => $bail_json->bail_report,
                        );
                        $arr_bail2 = array(
                            'Loading Date' => $bail_json->loading_date,
                            'Receiving Date' => $bail_json->receiving_date,
                        );
                        $arr_bail_shipp = array(
                            'Truck No.' => $bail_json->truck_no,
                            'Driver Name' => $bail_json->driver_name,
                            'Phone' => $bail_json->driver_phone
                        );
                    } ?>
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
        <div class="col-md-2 order-0 fixed-sidebar table-form shadow">
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
                url: 'ajax/viewSinglePurchaseAgentSecondModal.php',
                type: 'post',
                data: {pd_id: pd_id, title: title, type: type},
                success: function (response) {
                    $('#addImpExpAgent').html(response);
                    $('#secondModalLabel').html(title);
                    $('#party-type').val(type);
                }
            });
        }
    }
</script>
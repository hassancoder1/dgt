<?php require_once '../connection.php';
$pur_sale = $_POST['source'];
$parent_id = $_POST['id'];
$purchase_agents_id = $_POST['purchase_agents_id'];
$d_id = $_POST['d_id'];
if ($d_id > 0) {
    $parent_query = fetch($pur_sale . 's', array('id' => $parent_id));
    $parent_data = mysqli_fetch_assoc($parent_query);

    $details_query = fetch($pur_sale . '_details', array('id' => $d_id));
    $details = mysqli_fetch_assoc($details_query);
    $d_sr = $details['d_sr'];

    $imp_json = json_decode($details['imp_json']);
    $exp_json = json_decode($details['exp_json']);
    $notify_json = json_decode($details['notify_json']);
    $ware_json = json_decode($details['tware_json']);
    $bail_json = json_decode($details['bail_json']);

    $check_agent_query = fetch('purchase_agents', array('id' => $purchase_agents_id));
    $agent_data = mysqli_fetch_assoc($check_agent_query);
    $khaataId = $agent_data['khaata_id'];
    $agentKhaata = khaataSingle($agent_data['khaata_id']);
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
    }
    $goods_array1 = array(
        array('GOODS', goodsName($details['goods_id'])),
        array('Size', $details['size']),
        array('Brand', $details['brand']),
        array('Origin', $details['origin']),
    );
    $goods_array2 = array(
        array('Qty Name', $details['qty_name']),
        array('Qty#', $details['qty_no']),
        array('Total KGs', $details['total_kgs']),
        array('Total Qty KGs', round($details['total_qty_kgs'], 2)),
        array('Net KGs', round($details['net_kgs'], 2)),
    ); ?>
    <div class="row">
        <div class="col-md-10 order-1 content-column table-form">
            <div class="card">
                <div class="card-body p-2 text-uppercase- small">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <div class="row gx-1">
                        <div class="col-auto">
                            <?php foreach ($topArray as $item) {
                                echo '<b>' . $item['heading'] . '</b><span>' . $item['value'] . '</span><br>';
                            } ?>
                        </div>
                        <div class="col">
                            <div class="bg-light">IMPORTER</div>
                            <?php if (!empty($imp_json)) {
                                echo '<b>NAME</b>' . $imp_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                echo '<br><b>CITY</b>' . $imp_json->city;
                                echo '<br><b>ADDRESS</b>' . $imp_json->address;
                            } ?>
                        </div>
                        <div class="col">
                            <div class="info-div- bg-light">EXPORTER</div>
                            <?php if (!empty($exp_json)) {
                                echo '<b>NAME</b>' . $exp_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                echo '<br><b>CITY</b>' . $exp_json->city;
                                echo '<br><b>ADDRESS</b>' . $exp_json->address;
                                //echo '<br><b>REPORT</b>' . $exp_json->report;
                            } ?>
                        </div>
                        <div class="col">
                            <div class="info-div- bg-light">NOTIFY PARTY</div>
                            <?php if (!empty($notify_json)) {
                                echo '<b>NAME</b>' . $notify_json->comp_name;
                                echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                echo '<br><b>CITY</b>' . $notify_json->city;
                                echo '<br><b>ADDRESS</b>' . $notify_json->address;
                            } ?>
                        </div>
                        <div class="col">
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
                    <div class="row mb-1">
                        <div class="col-2">
                            <?php foreach ($goods_array1 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php foreach ($goods_array2 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <?php if (!empty($bail_json)) {
                            $bail_array1 = array(
                                'Bail#' => $bail_json->bail_no,
                                'Container Name' => $bail_json->container_name,
                                'Container#' => $bail_json->container_no,
                                'Container Size' => $bail_json->container_size,
                                'Bail Report' => $bail_json->bail_report
                            );
                            $bail_array2 = array(
                                'Loading Country' => $bail_json->loading_country,
                                'Loading Port' => $bail_json->loading_port,
                                'Loading Date' => $bail_json->loading_date,
                                'Receiving Country' => $bail_json->receiving_country,
                                'Receiving Port' => $bail_json->receiving_port,
                                'Receiving Date' => $bail_json->receiving_date,
                                'Freight Period' => $bail_json->freight,
                            );
                            $bail_array3 = array(
                                'Shipping Lane Name' => $bail_json->loading_shipper_address,
                                'Address' => $bail_json->receiving_shipper_address,
                                'Company' => $bail_json->ship_comp,
                                'Phone' => $bail_json->ship_phone,
                                'Email' => $bail_json->ship_email,
                                'WhatsApp' => $bail_json->ship_wa,
                            ); ?>
                            <div class="col">
                                <?php foreach ($bail_array1 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($bail_array2 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($bail_array3 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <hr class="my-0">
                    <div class="row mb-1">
                        <?php $bail_array = array(
                            'doc_rec_date' => 'Y-m-d',
                            'ctr_rec_date' => 'Y-m-d',
                            'bill_no' => '',
                            'bill_date' => 'Y-m-d',
                            'report' => '',
                            /*'ctr_return_date' => 'Y-m-d',
                            'truck_no' => '',
                            'ctr_name' => '',
                            'ctr_no' => '',
                            'driver_name' => '',
                            'driver_phone' => '',*/
                        );
                        $check_agent_query = fetch('purchase_agents', array('id' => $purchase_agents_id));
                        if (mysqli_num_rows($check_agent_query) > 0) {
                            $check_agent_data = mysqli_fetch_assoc($check_agent_query);
                            $details = json_decode($check_agent_data['details']);
                            if (empty($details)) {
                                echo '<div class="col">';
                                echo '<h5 class="text-danger">Pending details from Agent '.$agentKhaata['khaata_no'].'</h5>';
                                echo '</div>';
                            } else {
                                echo '<div class="col">';
                                echo '<b>Documents Receiving Date</b>' . $details->doc_rec_date;
                                echo '<br><b>Container Receiving Date</b>' . $details->ctr_rec_date;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Entry Bill# </b>' . $details->bill_no;
                                echo '<br><b>Entry Bill Date</b>' . $details->bill_date;
                                echo '</div>';
                                echo '<div class="col-6">';
                                echo '<b>Report</b>' . $details->report;
                                echo '</div>';
                                $bail_array = array(
                                    'doc_rec_date' => $details->doc_rec_date,
                                    'ctr_rec_date' => $details->ctr_rec_date,
                                    'bill_no' => $details->bill_no,
                                    'bill_date' => $details->bill_date,
                                    'report' => $details->report
                                );
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <form method="post" class="collapse show" id="collapseDetails">
                <div class="row table-form gx-1 gy-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="doc_rec_date">Documents Receiving</label>
                            <input value="<?php echo $bail_array['doc_rec_date']; ?>" type="date" id="doc_rec_date"
                                   name="doc_rec_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ctr_rec_date">Container Receiving</label>
                            <input value="<?php echo $bail_array['ctr_rec_date']; ?>" type="date" id="ctr_rec_date"
                                   name="ctr_rec_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="bill_no">Entry Bill#</label>
                            <input value="<?php echo $bail_array['bill_no']; ?>" type="text" id="bill_no"
                                   name="bill_no" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="bill_date">Entry Bill</label>
                            <input value="<?php echo $bail_array['bill_date']; ?>" type="date" id="bill_date"
                                   name="bill_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label for="report">Report</label>
                            <textarea id="report" name="report" class="form-control" rows="5"
                                      required><?php echo $bail_array['report']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-sm btn-dark" id="saveDetailsSubmit" name="saveDetailsSubmit">
                        Save Details
                    </button>
                </div>
                <input type="hidden" name="khaata_id" value="<?php echo $khaataId; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <input type="hidden" name="pd_id_hidden" value="<?php echo $d_id; ?>">
                <input type="hidden" name="p_id_hidden" value="<?php echo $parent_id; ?>">
                <input type="hidden" name="purchase_agents_id" value="<?php echo $purchase_agents_id; ?>">
                <input type="hidden" name="created_at" value="<?php echo date('Y-m-d'); ?>">
            </form>
        </div>
        <div class="col-md-2 order-0 fixed-sidebar table-form shadow">
            <div class="bottom-buttons px-2">
                <a class="btn btn-dark btn-sm w-100" data-bs-toggle="collapse" href="#collapseDetails" role="button"
                   aria-expanded="false" aria-controls="collapseExample">
                    Add Details
                </a>
            </div>
        </div>
    </div>
<?php } ?>
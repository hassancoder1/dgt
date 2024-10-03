<?php require_once '../connection.php';
$id = $_POST['id'];
$d_id = $_POST['d_id'];
$source = $_POST['source'];
if ($id > 0) {
    $details_k = array();
    $khaata_no = $khaata_id = $l_country = $l_port = $ctr_name = $r_country = $r_port = $l_date = $r_date = $arrival_date = '';
    if ($source == 'purchase') {
        $is_purchase = true;
        $is_sale = false;
        $p_data = fetch('purchases', array('id' => $id));
        $record = mysqli_fetch_assoc($p_data);
        $p_khaata = khaataSingle($record['p_khaata_id']);
        $s_khaata = khaataSingle($record['s_khaata_id']);
        $records2q = fetch('purchase_details', array('id' => $d_id));
        $record2 = mysqli_fetch_assoc($records2q);
        $d_sr = $record2['d_sr'];
        $topArray = array(array('heading' => 'PURCHASE#', 'value' => $id . '-' . $d_sr), array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $record['created_by'], 'username')), array('heading' => 'DATE', 'value' => $record['p_date']), array('heading' => 'BRANCH', 'value' => branchName($record['branch_id'])), array('heading' => 'COUNTRY', 'value' => $record['country']), array('heading' => 'TYPE', 'value' => $record['type']), array('heading' => 'ALLOT NAME', 'value' => $record['allot']));
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

    } else {
        $is_purchase = false;
        $is_sale = true;
        $p_data = fetch('sales', array('id' => $id));
        $record = mysqli_fetch_assoc($p_data);
        $records2q = fetch('sale_details', array('id' => $d_id));
        $record2 = mysqli_fetch_assoc($records2q);
        $d_sr = $record2['d_sr'];
        $topArray = array(array('heading' => 'SALE D.', 'value' => $record['s_date']), array('heading' => 'SALE#', 'value' => $id . '-' . $d_sr), array('heading' => 'B.', 'value' => branchName($record['branch_id'])), array('heading' => 'CITY', 'value' => $record['city']), array('heading' => 'TYPE', 'value' => $record['type']), array('heading' => 'SALE NAME', 'value' => $record['s_name']), array('heading' => 'RECEIVER NAME', 'value' => $record['receiver']),);
        if (!empty($record['seller_json'])) {
            $seller_json = json_decode($record['seller_json']);
            $khaata_no = $seller_json->khaata_no;
            $khaata_id = $seller_json->khaata_id;
            if (isset($seller_json->is_loading)) {
                if ($seller_json->is_loading == 1) {
                    $l_country = $seller_json->l_country;
                    $l_port = $seller_json->l_port;
                    $l_date = $seller_json->l_date;
                    $ctr_name = $seller_json->ctr_name;
                }
            }
            if (isset($seller_json->is_receiving)) {
                if ($seller_json->is_receiving == 1) {
                    $r_country = $seller_json->r_country;
                    $r_port = $seller_json->r_port;
                    $r_date = $seller_json->r_date;
                    $arrival_date = $seller_json->arrival_date;
                }
            }
            if (isset($seller_json->rep_indexes)) {
                $details_k = ['indexes' => json_encode($seller_json->rep_indexes), 'vals' => json_encode($seller_json->rep_vals)];
            }
        }
    }
    $purchase_type = $record['type'];
    $imp_json = json_decode($record2['imp_json']);
    $exp_json = json_decode($record2['exp_json']);
    $notify_json = json_decode($record2['notify_json']);
    $ware_json = json_decode($record2['ware_json']);
    $tware_json = json_decode($record2['tware_json']);
    $bail_json = json_decode($record2['bail_json']);
    $goods_array1 = array(array('GOODS', goodsName($record2['goods_id'])), array('Size', $record2['size']), array('Brand', $record2['brand']), array('Origin', $record2['origin']));
    $goods_array2 = array(array('Qty Name', $record2['qty_name']), array('Qty#', $record2['qty_no']), array('Total KGs', $record2['total_kgs']), array('Total Qty KGs', round($record2['total_qty_kgs'], 2)), array('Net KGs', round($record2['net_kgs'], 2)));
    $goods_array3 = array(array('Loading Country', $l_country), array('Loading Port', $l_port), array('Loading Date', $l_date), array('Container Name', $ctr_name));
    $goods_array4 = array(array('Receiving Country', $r_country), array('Receiving Port', $r_port), array('Receiving Date', $r_date), array('Arrival Date', $arrival_date));
    if ($purchase_type == 'booking') {
        //$data = purchaseBailDetails($d_id);
        if (!empty($bail_json)) {
            $bail_array1 = array('Bail#' => $bail_json->bail_no, 'Container Name' => $bail_json->container_name, 'Container#' => $bail_json->container_no, 'Container Size' => $bail_json->container_size, 'Bail Report' => $bail_json->bail_report);
            $bail_array2 = array('Loading Country' => $bail_json->loading_country, 'Loading Port' => $bail_json->loading_port, 'Loading Date' => $bail_json->loading_date, 'Receiving Country' => $bail_json->receiving_country, 'Receiving Port' => $bail_json->receiving_port, 'Receiving Date' => $bail_json->receiving_date, 'Freight Period' => $bail_json->freight);
            $bail_array3 = array('Shipping Lane Name' => $bail_json->loading_shipper_address, 'Address' => $bail_json->receiving_shipper_address, 'Company' => $bail_json->ship_comp, 'Phone' => $bail_json->ship_phone, 'Email' => $bail_json->ship_email, 'WhatsApp' => $bail_json->ship_wa);
        }
    } else {
        //$data = purchaseBailDetails($d_id, 'local');
        if (!empty($bail_json)) {
            $bail_array1 = array('Report' => $bail_json->bail_report);
            $bail_array2 = array('Loading Date' => $bail_json->loading_date, 'Receiving Date' => $bail_json->receiving_date);
            $bail_array3 = array('Truck No.' => $bail_json->truck_no, 'Driver Name' => $bail_json->driver_name, 'Phone' => $bail_json->driver_phone);
        }
    }
    //var_dump($record); ?>
    <div class="row">
        <div class="col-md-10 order-1 content-column table-form">
            <div class="card">
                <div class="card-body p-2 text-uppercase small">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <div class="row">
                        <div class="col-2">
                            <?php foreach ($topArray as $item) {
                                echo '<b>' . $item['heading'] . '</b> ' . $item['value'] . '<br>';
                            } ?>
                        </div>
                        <?php if ($is_purchase) {
                            echo '<div class="col p-1">';
                            echo '<div class="info-div- bg-light">PURCHASER</div>';
                            $array_acc1 = array(array('label' => 'A/C#', 'id' => 'p_khaata_no', 'val' => $record['p_khaata_no']), array('label' => 'A/C NAME', 'id' => 'p_khaata_name', 'val' => $p_khaata['khaata_name']), array('label' => 'BRANCH', 'id' => 'p_b_name', 'val' => branchName($p_khaata['branch_id'])), array('label' => 'CATEGORY', 'id' => 'p_c_name', 'val' => catName($p_khaata['cat_id'])), array('label' => 'BUSINESS', 'id' => 'p_business_name', 'val' => $p_khaata['business_name']), array('label' => 'COMPANY', 'id' => 'p_comp_name', 'val' => $p_khaata['comp_name']));
                            foreach ($array_acc1 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                            }
                            echo '</div>';
                        } else {
                            if ($khaata_id > 0) {
                                echo '<div class="col-3 p-1">';
                                echo '<div class="info-div- bg-light">SELLER</div>';
                                echo '<div class=" d-flex p-1"><div>';
                                $seller = khaataSingle($khaata_id);
                                $array_acc1 = array(array('label' => 'Seller A/C#', 'id' => $khaata_no), array('label' => 'A/C NAME', 'id' => $seller['khaata_name']), array('label' => 'B.', 'id' => branchName($seller['branch_id'])), array('label' => 'CAT.', 'id' => catName($seller['cat_id'])), array('label' => 'BUSINESS', 'id' => $seller['business_name']), array('label' => 'COMPANY', 'id' => $seller['comp_name']));
                                foreach ($array_acc1 as $item) {
                                    echo '<b>' . $item['label'] . ' </b><span class="text-muted">' . $item['id'] . '</span><br>';
                                }
                                echo '</div>';
                                echo '<div>';
                                $details2 = ['indexes' => $seller['indexes'], 'vals' => $seller['vals']];
                                echo displayKhaataDetails($details2);
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } ?>
                        <?php if ($is_purchase) {
                            echo '<div class="col p-1">';
                            echo '<div class="info-div- bg-light">SELLER</div>';
                            $array_acc2 = array(array('label' => 'A/C#', 'id' => 's_khaata_no', 'val' => $record['s_khaata_no']), array('label' => 'A/C NAME', 'id' => 's_khaata_name', 'val' => $s_khaata['khaata_name']), array('label' => 'BRANCH', 'id' => 's_b_name', 'val' => branchName($s_khaata['branch_id'])), array('label' => 'CATEGORY', 'id' => 's_c_name', 'val' => catName($s_khaata['cat_id'])), array('label' => 'BUSINESS', 'id' => 's_business_name', 'val' => $s_khaata['business_name']), array('label' => 'COMPANY', 'id' => 's_comp_name', 'val' => $s_khaata['comp_name'])); ?>
                            <?php foreach ($array_acc2 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                            }
                            echo '</div>';
                        } ?>
                        <?php if ($purchase_type == 'booking') { ?>
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
                        <?php } else { ?>
                            <div class="col p-1">
                                <div class="bg-light">LOADING WAREHOUSE</div>
                                <?php if (!empty($ware_json)) {
                                    echo '<b>NAME</b>' . $ware_json->comp_name;
                                    echo '<br><b>COUNTRY</b>' . $ware_json->country;
                                    echo '<br><b>CITY</b>' . $ware_json->city;
                                    echo '<br><b>ADDRESS</b>' . $ware_json->address;
                                    //echo '<br><b>REPORT</b>' . $notify_json->report;
                                } ?>
                            </div>
                        <?php } ?>
                        <div class="col p-1">
                            <div class="bg-light">TRANSFER WAREHOUSE</div>
                            <?php if ($source == 'purchase') {
                                if (!empty($tware_json)) {
                                    echo '<b>NAME</b>' . $tware_json->comp_name;
                                    echo '<br><b>COUNTRY</b>' . $tware_json->country;
                                    echo '<br><b>CITY</b>' . $tware_json->city;
                                    echo '<br><b>ADDRESS</b>' . $tware_json->address;
                                }
                            } else {
                                if ($record2['wh_kd_id'] > 0) {
                                    $wh_data = khaataDetailsData($record2['wh_kd_id']);
                                    echo '<b>NAME</b>' . $wh_data['comp_name'];
                                    echo '<br><b>COUNTRY</b>' . countryName($wh_data['country_id']);
                                    echo '<br><b>CITY</b>' . $wh_data['city'];
                                    echo '<br><b>ADDRESS</b>' . $wh_data['address'];
                                }
                            } ?>
                        </div>
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
                        <div class="col-2">
                            <?php foreach ($goods_array3 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($goods_array4 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                    </div>
                    <?php if (!empty($bail_json)) {
                        echo '<hr class="my-0">'; ?>
                        <div class="row gx-1">
                            <div class="col-4">
                                <?php foreach ($bail_array1 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                            <div class="col-4">
                                <?php foreach ($bail_array2 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                            <div class="col-4">
                                <?php foreach ($bail_array3 as $name => $value) {
                                    echo '<b>' . $name . '</b> ' . $value . '<br>';
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row gx-1">
                        <div class="col-6">
                            <div class="info-div- bg-light">IMPORT AGENT</div>
                            <?php if ($record2['is_imp_agent'] == 1) {
                                $iAQ = fetch('purchase_agents', array('pur_sale' => $source, 'type' => 'import', 'd_id' => $d_id));
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
                                            <?php $check_agent_query_imp = fetch('purchase_agents', array('pur_sale' => $source, 'd_id' => $d_id, 'khaata_id' => $ii['khaata_id'], 'type' => 'import'));
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
                                echo $record2['no_imp_agent_msg'];
                                //echo 'Contact Admin for more details';
                            } ?>
                        </div>
                        <div class="col-6">
                            <div class="info-div- bg-light">EXPORT AGENT</div>
                            <?php if ($record2['is_exp_agent'] == 1) {
                                $eAQ = fetch('purchase_agents', array('pur_sale' => $source, 'type' => 'export', 'd_id' => $d_id));
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
                                            <?php $check_agent_query_imp = fetch('purchase_agents', array('pur_sale' => $source, 'd_id' => $d_id, 'khaata_id' => $ee['khaata_id'], 'type' => 'import'));
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
                                echo '<h5 class="text-danger">NO EXPORT AGENT</h5>';
                                echo $record2['no_exp_agent_msg'];
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 order-0 fixed-sidebar table-form shadow">
            <div class="btn-group w-100">
                <?php if ($record2['is_imp_agent'] == 1) {
                    $noAgentBtnTxt = 'Set as NO AGENT'; ?>
                    <button data-title="Import Agent" data-type="import" data-pd-id="<?php echo $d_id; ?>"
                            data-source="<?php echo $source; ?>"
                            class="openSecondModal btn btn-dark btn-sm">Import Agent
                    </button>
                <?php } else {
                    $noAgentBtnTxt = 'Allow AGENT Again';
                } ?>
                <?php if ($record2['is_imp_agent'] == 1 || SuperAdmin()) { ?>
                    <a class="btn btn-danger btn-sm" data-bs-toggle="collapse" href="#collapseImpAgent" role="button"
                       aria-expanded="false" aria-controls="collapseImpAgent">
                        <?php echo $noAgentBtnTxt; ?>
                    </a>
                <?php } ?>
            </div>
            <div class="collapse mb-5" id="collapseImpAgent">
                <form method="post"
                      onsubmit="return confirm('You are setting that there is No Import Agent for this container\nPress OK to confirm');">
                    <label for="msg" class="mb-0">Note</label>
                    <textarea class="form-control" name="msg" required
                              placeholder="Note about agent"><?php echo $record2['no_imp_agent_msg']; ?></textarea>
                    <input type="hidden" name="id_hidden" value="<?php echo $id; ?>">
                    <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                    <input type="hidden" name="source_hidden" value="<?php echo $source; ?>">
                    <input type="hidden" name="agent_type" value="imp">
                    <input type="hidden" name="col_value" value="<?php echo $record2['is_imp_agent']; ?>">
                    <button class="btn btn-dark btn-sm" type="submit" name="noAgentSubmit">Submit</button>
                </form>
            </div>
            <div class="btn-group mt-2 w-100">
                <?php if ($record2['is_exp_agent'] == 1) {
                    $noAgentBtnTxt2 = 'Set as NO AGENT'; ?>
                    <button data-title="Export Agent" data-type="export" data-pd-id="<?php echo $d_id; ?>"
                            data-source="<?php echo $source; ?>"
                            class="openSecondModal btn btn-dark btn-sm">Export Agent
                    </button>
                <?php } else {
                    $noAgentBtnTxt2 = 'Allow AGENT Again';
                } ?>
                <?php if ($record2['is_exp_agent'] == 1 || SuperAdmin()) { ?>
                    <a class="btn btn-danger btn-sm" data-bs-toggle="collapse" href="#collapseExpAgent" role="button"
                       aria-expanded="false" aria-controls="collapseExpAgent">
                        <?php echo $noAgentBtnTxt2; ?>
                    </a>
                <?php } ?>
            </div>
            <div class="collapse mb-5" id="collapseExpAgent">
                <form method="post"
                      onsubmit="return confirm('You are setting that there is No Export Agent for this container\nPress OK to confirm');">
                    <label for="msg" class="mb-0">Note</label>
                    <textarea class="form-control" name="msg" required
                              placeholder="Note about agent"><?php echo $record2['no_exp_agent_msg']; ?></textarea>
                    <input type="hidden" name="id_hidden" value="<?php echo $id; ?>">
                    <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                    <input type="hidden" name="source_hidden" value="<?php echo $source; ?>">
                    <input type="hidden" name="agent_type" value="exp">
                    <input type="hidden" name="col_value" value="<?php echo $record2['is_exp_agent']; ?>">
                    <button class="btn btn-dark btn-sm" type="submit" name="noAgentSubmit">Submit</button>
                </form>
            </div>
            <!--<button data-title="Agent Warehouse" data-type="AWarehouse" data-pd-id="<?php /*echo $d_id; */ ?>" class="my-2 openThirdModal btn btn-dark btn-sm w-100">STOCK WAREHOUSE</button>-->
            <div class="bottom-buttons">
                <div class="px-2">
                    <a href="print/purchase-booking?p_id=<?php echo $id; ?>&action=booking" target="_blank"
                       class="btn btn-success btn-sm w-100">PRINT</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
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
            var source = $(this).data('source');
            showSecondModal(source, pd_id, title, type);
        });
    });

    function showSecondModal(source = null, pd_id = null, title = null, type = null) {
        if (pd_id) {
            $.ajax({
                url: 'ajax/viewSinglePurchaseAgentSecondModal.php',
                type: 'post',
                data: {source: source, pd_id: pd_id, title: title, type: type},
                success: function (response) {
                    console.log(response)
                    $('#addImpExpAgent').html(response);
                    $('#secondModalLabel').html(title);
                    $('#party-type').val(type);
                }
            });
        }
    }
</script>
<script>
    var openThirdModals = document.getElementsByClassName('openThirdModal');
    Array.from(openThirdModals).forEach(function (element) {
        element.addEventListener('click', function () {
            var thirdModal = new bootstrap.Modal(document.getElementById('thirdModal'));
            thirdModal.show();
            var pd_id = $(this).data('pd-id');
            var title = $(this).data('title');
            var type = $(this).data('type');
            openThirdModal(pd_id, title, type);
        });
    });

    function openThirdModal(pd_id = null, title = null, type = null) {
        if (pd_id) {
            $.ajax({
                url: 'ajax/viewSinglePurchaseLoadingSecondModal.php',
                type: 'post',
                data: {pd_id: pd_id, title: title, type: type},
                success: function (response) {
                    $('#thirdModalDetails').html(response);
                    $('#thirdModalLabel').html(title);
                    $('#party-type').val(type);
                }
            });
        }
    }
</script>
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

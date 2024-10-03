<?php $page_title = 'General Agent';
$pageURL = 'purchase-agent';
include("header.php"); ?>
<?php $removeFilter = $type_msg = $cat_msg = $type_msg = "";
$cat_ids = array();
$goods_id = 0;
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2  ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['type']);
        $sql .= " AND type = " . "'$type'" . " ";
        $type_msg = $type;
    }
} ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between table-form mb-1">
                <form method="get" class="d-flex w-75">
                    <?php $type_array = array('booking', 'local'); ?>
                    <select id="type" name="type" class="form-select">
                        <?php foreach ($type_array as $item) {
                            //$type_selected = $type == $item ? 'selected' : '';
                            echo '<option  value="' . $item . '">' . ucfirst($item) . '</option>';
                        } ?>
                    </select>
                    <button type="submit" class="btn btn-white btn-sm"><i class="fa fa-search"></i></button>
                </form>
                <div>
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <?php unset($_SESSION['response']); ?>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th>TYPE</th>
                                <th>PURCHASER & SELLER</th>
                                <th>GOODS</th>
                                <th>BAIL</th>
                                <th class="text-nowrap">IMP AGENT</th>
                                <th class="text-nowrap">EXP AGENT</th>
                                <!--<th>IMPORTER</th>
                                <th>EXPORTER</th>
                                <th>NOTIFY PARTY</th>
                                <th>WAREHOUSE</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $purchases = mysqli_query($connect, $sql);
                            while ($purchase = mysqli_fetch_assoc($purchases)) {
                                $pd_count = 0;
                                $purchase_id = $purchase['id'];
                                $purchase_type = $purchase['type'];
                                $p_khaata = khaataSingle($purchase['p_khaata_id']);
                                $s_khaata = khaataSingle($purchase['s_khaata_id']);
                                $totals = purchaseSpecificData($purchase_id, 'product_details');
                                $pd_query = fetch('purchase_details', array('parent_id' => $purchase_id));
                                $count_details = mysqli_num_rows($pd_query);
                                while ($details = mysqli_fetch_assoc($pd_query)) {
                                    if ($details['is_transfer'] == 0) continue;
                                    if ($details['transfer_as'] == 0 || $details['transfer_as'] > 1) continue; //this is important, transfer is must form transfer form.
                                    //agent =1 , stock =2;
                                    $imp_json = json_decode($details['imp_json']);
                                    $exp_json = json_decode($details['exp_json']);
                                    $notify_json = json_decode($details['notify_json']);
                                    $ware_json = json_decode($details['ware_json']);
                                    $bail_json = json_decode($details['bail_json']);
                                    $pd_id = $details['id'];
                                    $pd_sr = $details['d_sr'];
                                    $pd_count++; ?>
                                    <tr class="pointer text-uppercase <?php //echo $rowColor; ?>"
                                        onclick="viewPurchase(<?php echo $purchase_id; ?>,<?php echo $pd_id; ?>)"
                                        data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                        <td class="text-nowrap">
                                            <?php echo '<b>P#</b>' . $purchase_id . '-' . $pd_sr . '<br>';
                                            echo purchaseSpecificData($purchase_id, 'purchase_type');
                                            echo '<br><span class="font-size-11"><b>D.</b>' . $purchase['p_date'] . '</span>';
                                            /*if ($details['transfer_as'] > 0) {
                                                echo '<br><span class="font-size-11"><i class="fa fa-check-double text-success"></i> Transferred.</span>';
                                            }*/ ?>
                                        </td>
                                        <td class="small text-nowrap">
                                            <?php echo '<b>BRANCH</b>' . branchName($purchase['branch_id']) . '<br>';
                                            echo '<b>PURCHASE A/c#</b>' . $purchase['p_khaata_no'] . '<br>';
                                            echo '<b>SELLER A/c#</b>' . $purchase['s_khaata_no'];
                                            echo '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name'];
                                            //echo $s_khaata['comp_name']; ?>
                                        </td>
                                        <td class="small text-nowrap">
                                            <?php $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                                            if ($cntrs > 0) {
                                                echo '<b>ITEMS. </b>', $cntrs;
                                                echo '<br><b>Qty </b>', $details['qty_no'];
                                                echo '<br><b>KGs </b>', $details['total_kgs'];
                                                echo '<br><b>Goods </b>', $totals['Goods'][0];
                                            } ?>
                                        </td>
                                        <td class="small">
                                            <?php if (!empty($bail_json)) {
                                                echo '<b>CONTAINER#</b>' . $bail_json->container_no;
                                                echo '<br><b>BAIL#</b>' . $bail_json->bail_no;
                                                echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                                echo '<br><b>RECEIVE D.</b>' . $bail_json->receiving_date;
                                            } ?>
                                        </td>
                                        <td class="small text-nowrap">
                                            <?php if ($details['is_imp_agent'] == 1) {
                                                $purchase_agent_data = purchase_agent_data('purchase',$pd_id, 'import');
                                                if (!empty($purchase_agent_data)) {
                                                    $imp_agent_khaata = khaataSingle($purchase_agent_data['khaata_id']);
                                                    echo '<b>IMP&nbsp;A/C.</b>' . $imp_agent_khaata['khaata_no'];
                                                    echo '<b>D.</b>' . date('y-m-d', strtotime($purchase_agent_data['created_at']));
                                                    echo '<br><b>A/C&nbsp;N</b>' . $imp_agent_khaata['khaata_name'];
                                                    $imp_details = json_decode($purchase_agent_data['details']);
                                                    if (!empty($imp_details)) {
                                                        echo '<br><b>Entry Bill# </b>' . $imp_details->bill_no;
                                                        echo '<br><b>Container#</b>' . $imp_details->ctr_no;
                                                        //echo '<br><b>Loading Truck#</b>' . $imp_details->truck_no;
                                                        /*echo '<br><b>Documents Receiving Date</b>' . $imp_details->doc_rec_date;
                                                        echo '<br><b>Container Receiving Date</b>' . $imp_details->ctr_rec_date;
                                                        echo '<br><b>Entry Bill Date</b>' . $imp_details->bill_date;
                                                        echo '<br><b>Container Return</b>' . $imp_details->ctr_return_date;
                                                        echo '<br><b>Container Name</b>' . $imp_details->ctr_name;
                                                        echo '<br><b>Driver Name</b>' . $imp_details->driver_name;
                                                        echo '<br><b>Phone</b>' . $imp_details->driver_phone;
                                                        echo '<br><b>Report</b>' . $imp_details->report;*/
                                                    } else {
                                                        echo '<br><span class="text-warning bold">PENDING...</span>';
                                                    }
                                                }
                                            } else {
                                                echo '<span class="text-danger bold">NO IMPORT AGENT.</span>';
                                            } ?>
                                        </td>
                                        <td class="small text-nowrap">
                                            <?php if ($details['is_exp_agent'] == 1) {
                                                $purchase_agent_data_exp = purchase_agent_data('purchase',$pd_id, 'export');
                                                if (!empty($purchase_agent_data_exp)) {
                                                    $exp_agent_khaata = khaataSingle($purchase_agent_data_exp['khaata_id']);
                                                    echo '<b>EXP&nbsp;A/C.</b>' . $exp_agent_khaata['khaata_no'];
                                                    echo '<b>D.</b>' . date('y-m-d', strtotime($exp_agent_khaata['created_at']));
                                                    echo '<br><b>A/C&nbsp;N</b>' . $exp_agent_khaata['khaata_name'];

                                                    $exp_details = json_decode($purchase_agent_data_exp['details']);
                                                    if (!empty($exp_details)) {
                                                        echo '<br><b>Entry Bill# </b>' . $exp_details->bill_no;
                                                        echo '<br><b>Container#</b>' . $exp_details->ctr_no;
                                                        //echo '<br><b>Loading Truck#</b>' . $exp_details->truck_no;
                                                        /*echo '<br><b>Documents Receiving Date</b>' . $exp_details->doc_rec_date;
                                                        echo '<br><b>Container Receiving Date</b>' . $exp_details->ctr_rec_date;
                                                        echo '<br><b>Entry Bill Date</b>' . $exp_details->bill_date;
                                                        echo '<br><b>Container Return</b>' . $exp_details->ctr_return_date;
                                                        echo '<br><b>Container Name</b>' . $exp_details->ctr_name;
                                                        echo '<br><b>Driver Name</b>' . $exp_details->driver_name;
                                                        echo '<br><b>Phone</b>' . $exp_details->driver_phone;
                                                        echo '<br><b>Report</b>' . $exp_details->report;*/
                                                    } else {
                                                        echo '<br><span class="text-warning bold">PENDING...</span>';
                                                    }
                                                }
                                            } else {
                                                echo '<span class="text-danger bold">NO EXPORT AGENT.</span>';
                                            } ?>
                                        </td>
                                        <!--<td class="font-size-10">
                                            <?php /*if (!empty($imp_json)) {
                                                echo '<b>COMPANY</b>' . $imp_json->comp_name;
                                                echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                            } */ ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php /*if (!empty($exp_json)) {
                                                echo '<b>COMPANY</b>' . $exp_json->comp_name;
                                                echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                            } */ ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php /*if (!empty($notify_json)) {
                                                echo '<b>COMPANY</b>' . $notify_json->comp_name;
                                                echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                            } */ ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php /*if (!empty($ware_json)) {
                                                echo '<b>NAME</b>' . $ware_json->comp_name;
                                                echo '<br><b>COUNTRY</b>' . $ware_json->country;
                                            } */ ?>
                                        </td>-->
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>
    <script>
        function viewPurchase(id = null, pd_id = null) {
            if (id) {
                $.ajax({
                    url: 'ajax/viewSinglePurchaseAgent.php',
                    type: 'post',
                    data: {id: id, pd_id: pd_id},
                    success: function (response) {
                        $('#viewDetails').html(response);
                    }
                });
            } else {
                alert('error!! Refresh the page again');
            }
        }
    </script>
<?php if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['pd_id']) && is_numeric($_GET['pd_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    $pd_id = mysqli_real_escape_string($connect, $_GET['pd_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id,$pd_id); });</script>";
    if (isset($_GET['secondModal']) && $_GET['secondModal'] == 1) {
        echo "<script>jQuery(document).ready(function ($) {  $('#secondModal').modal('show');});</script>";
    }
} ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">GENERAL AGENT</h5>
                    <a href="purchase-agent" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="secondModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="secondModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg" style="min-height: 300px;">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="secondModalLabel">Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light " id="addImpExpAgent">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="thirdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="thirdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg" style="min-height: 300px;">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="thirdModalLabel">AGENT WAREHOUSE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light " id="thirdModalDetails"></div>
            </div>
        </div>
    </div>
<?php if (isset($_POST['saveAgentPLoading'])) {
    $msg = 'DB Errro :(';
    $msg_type = 'danger';
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
    $urll = 'purchase-agent';
    $data = array('type' => $type, 'pd_id' => $pd_id, 'khaata_id' => $khaata_id,);
    $dd = fetch('purchase_agents', array('type' => $type, 'd_id' => $pd_id, 'khaata_id' => $khaata_id));/*maybe comment , 'khaata_id' => $khaata_id*/
    if (mysqli_num_rows($dd) > 0) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $_SESSION['userId'];
        $done = update('purchase_agents', $data, array('type' => $type, 'd_id' => $pd_id));
    } else {
        $pa_sr = getPurchaseAgentSerial($khaata_id);
        $data['a_sr'] = $pa_sr;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $_SESSION['userId'];
        $done = insert('purchase_agents', $data);
    }
    if ($done) {
        $urll .= '?p_id=' . $p_id . '&pd_id=' . $pd_id . '&view=1';
        $msg = "Agent saved successfully.";
        $msg_type = "success";
    }
    message($msg_type, $urll, $msg);
}
if (isset($_POST['savePartyPLoading'])) {
    $msg = 'DB Errro :(';
    $msg_type = 'danger';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $urll = 'purchase-agent';
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $validatedData = array_map('htmlspecialchars', $_POST);
    $post = json_encode($validatedData);
    if (is_numeric($pd_id_hidden) && $pd_id_hidden > 0 && in_array($type, ['AWarehouse'])) {
        $urll .= '?p_id=' . $p_id_hidden . '&pd_id=' . $pd_id_hidden . '&view=1';
        $data['aware_json'] = $post;
        $done = update('purchase_details', $data, array('id' => $pd_id_hidden));
        if ($done) {
            $msg = 'Party saved.';
            $msg_type = 'success';
        }
    }
    message($msg_type, $urll, $msg);
}
if (isset($_POST['noAgentSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $agent_msg = mysqli_real_escape_string($connect, $_POST['msg']);
    $agent_type = mysqli_real_escape_string($connect, $_POST['agent_type']);
    $col_value = mysqli_real_escape_string($connect, $_POST['col_value']);
    $col_value_new = $col_value == 0 ? 1 : 0;
    if ($agent_type == 'imp') {
        $data = array('is_imp_agent' => $col_value_new, 'no_imp_agent_msg' => $agent_msg);
    } else {
        $data = array('is_exp_agent' => $col_value_new, 'no_exp_agent_msg' => $agent_msg);
    }
    $locked = update('purchase_details', $data, array('id' => $pd_id_hidden));
    $URL = $pageURL . '?p_id=' . $p_id_hidden . '&pd_id=' . $pd_id_hidden . '&view=1';
    if ($locked) {
        $type = 'success';
        $msg = 'Agent attributes saved. ';
    }
    message($type, $URL, $msg);
}
?>
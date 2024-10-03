<?php $page_title = 'Agent Forms';
$pageURL = 'my-forms';
include("header.php"); ?>
<?php global $khaataId; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="table-form mb-1 d-flex justify-content-between">
            <div>
                <b>ROWS </b><span id="numRows_span"></span>
            </div>
            <div>
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? ''; ?>
                <?php unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 75dvh;">
                    <table class="table mb-0 fix-head-table">
                        <thead>
                        <tr>
                            <th>TYPE</th>
                            <!--<th>PURCHASER & SELLER</th>-->
                            <th>GOODS</th>
                            <th>BAIL</th>
                            <!--<th class="text-nowrap">IMP/EXP AGENT</th>-->
                            <th>IMPORTER</th>
                            <th>EXPORTER</th>
                            <th>NOTIFY&nbsp;PARTY</th>
                            <th>WAREHOUSE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $numRows = 0;
                        $check_agent_query = mysqli_query($connect, "SELECT * FROM `purchase_agents` WHERE khaata_id = '$khaataId' ORDER BY a_sr DESC ");
                        //$check_agent_query = fetch('purchase_agents', array('khaata_id' => $khaataId));
                        //var_dump($check_agent_query);
                        if (mysqli_num_rows($check_agent_query) > 0) {
                            while ($agent_data = mysqli_fetch_assoc($check_agent_query)) {
                                $pur_sale = $agent_data['pur_sale'];
                                $d_id = $agent_data['d_id'];
                                $purchase_agents_type = $agent_data['type'];
                                $purchase_agents_id = $agent_data['id'];
                                $pa_sr = $agent_data['a_sr'];
                                $rowColor = $agent_data['details'] == '' ? 'bg-danger bg-opacity-10' : '';

                                $pd_query = fetch($pur_sale . '_details', array('id' => $d_id));
                                $details = mysqli_fetch_assoc($pd_query);
                                $d_sr = $details['d_sr'];
                                $parent_id = $details['parent_id'];
                                if ($details['is_transfer'] == 0) continue;
                                if ($details['transfer_as'] == 0 || $details['transfer_as'] > 1) continue; //this is important, transfer is must form transfer form.
                                //agent =1 , stock =2;
                                $parent_query = fetch($pur_sale . 's', array('id' => $parent_id));
                                $parent_data = mysqli_fetch_assoc($parent_query);
                                if ($pur_sale == 'purchase') {
                                    if ($parent_data['is_locked'] != 1) continue;
                                    $ps_date = $parent_data['p_date'];
                                    $ps_type = purchaseSpecificData($parent_id, 'purchase_type');
                                    $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                                } else {
                                    $ps_date = $parent_data['s_date'];
                                    $ps_type = saleSpecificData($parent_id, 'sale_type');
                                    $cntrs = saleSpecificData($parent_id, 'sale_rows');
                                }
                                $pur_sale == 'purchase' ?: saleSpecificData($parent_id, $pur_sale . '_type');

                                if ($parent_data['type'] == 'booking' && $parent_data['transfer'] == 2) {
                                    //$sql = "SELECT * FROM `purchases` WHERE type = 'booking' AND is_locked = 1 AND transfer = 2  ";
                                } else {
                                    continue;
                                }
                                $imp_json = json_decode($details['imp_json']);
                                $exp_json = json_decode($details['exp_json']);
                                $notify_json = json_decode($details['notify_json']);
                                $ware_json = json_decode($details['tware_json']);
                                $bail_json = json_decode($details['bail_json']); ?>
                                <tr class="text-uppercase <?php echo $rowColor; ?>">
                                    <td class="pointer text-nowrap"
                                        onclick="viewPurchase(<?php echo $parent_id; ?>,<?php echo $d_id; ?>,<?php echo $purchase_agents_id; ?>,<?php echo $khaataId; ?>,'<?php echo $pur_sale; ?>')"
                                        data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                        <?php echo $pur_sale == 'purchase' ? '<b>P#</b>' : '<b>S#</b> ';
                                        echo '[' . $pa_sr . '] ' . $parent_id . '-' . $d_sr . '<br>';
                                        //echo $pur_sale == 'purchase' ? purchaseSpecificData($parent_id, $pur_sale . '_type') : saleSpecificData($parent_id, $pur_sale . '_type');
                                        echo '<span class="badge bg-danger">' . $purchase_agents_type . '</span>';
                                        echo $ps_type;
                                        echo '<br><span class="badge bg-secondary"> By ' . $parent_data['sea_road'] . '</span>';
                                        echo '<br><span class="font-size-11"><b>D.</b>' . $ps_date . '</span>';
                                        ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap">
                                        <?php echo '<b>ITEMS. </b>' . $cntrs;
                                        echo '<br><b>Qty </b>' . $details['qty_no'];
                                        echo '<br><b>KGs </b>' . $details['total_kgs'];
                                        echo '<br><b>Goods </b>' . goodsName($details['goods_id']);
                                        ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($bail_json)) {
                                            echo '<b>CONTAINER#</b>' . $bail_json->container_no;
                                            echo '<br><b>BAIL#</b>' . $bail_json->bail_no;
                                            echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                            echo '<br><b>RECEIVE D.</b>' . $bail_json->receiving_date;
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($imp_json)) {
                                            echo '<b>COMPANY</b>' . $imp_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                            //echo '<br><b>CITY</b>' . $imp_json->city;
                                            //echo '<br><b>ADDRESS</b>' . $imp_json->address;
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($exp_json)) {
                                            echo '<b>COMPANY</b>' . $exp_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($notify_json)) {
                                            echo '<b>COMPANY</b>' . $notify_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($ware_json)) {
                                            echo '<b>NAME</b>' . $ware_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $ware_json->country;
                                        } ?>
                                    </td>
                                </tr>
                                <?php $numRows++;
                            }
                        } ?>
                        </tbody>
                        <input type="hidden" value="<?php echo $numRows; ?>" id="numRows">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['saveDetailsSubmit'])) {
    unset($_POST['saveDetailsSubmit']);
    $msg = 'DB Errro :(';
    $msg_type = 'danger';
    $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $d_id = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $purchase_agents_id = mysqli_real_escape_string($connect, $_POST['purchase_agents_id']);

    $validatedData = array_map('htmlspecialchars', $_POST);
    $post = json_encode($validatedData);

    $data = array('details' => json_encode($_POST));
    $done = update('purchase_agents', $data, array('id' => $purchase_agents_id));
    if ($done) {
        $msg = "Details saved successfully.";
        $msg_type = "success";
    }
    message($msg_type, $pageURL, $msg);
} ?>
<script>
    function viewPurchase(id = null, pd_id = null, purchase_agents_id = null, khaataId = null, source = null) {
        /*function viewPurchase(id = null, pd_id = null, type = null, khaataId = null) {*/
        if (id) {
            $.ajax({
                url: '../ajax/agentSideDetails.php',
                type: 'post',
                data: {id: id, d_id: pd_id, purchase_agents_id: purchase_agents_id, khaataId: khaataId, source: source},
                success: function (response) {
                    $('#viewDetails').html(response);
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<script type="text/javascript">
    $("#numRows_span").text($("#numRows").val());
</script>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">AGENT DETAILS FORM</h5>
                <a href="my-forms" class="btn-close" aria-label="Close"></a>
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
                <h5 class="modal-title text-uppercase" id="secondModalLabel">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light " id="detailsImpExpAgent">
            </div>
        </div>
    </div>
</div>
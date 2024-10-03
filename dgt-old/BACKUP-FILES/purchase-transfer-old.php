<?php $page_title = 'Transfer Form';
$pageURL = 'purchase-transfer';
include("header.php"); ?>
<?php $removeFilter = $type_msg = $cat_msg = $type_msg = "";
$cat_ids = array();
$goods_id = 0;
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2 ";
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
                                <th>IMPORTER</th>
                                <th>EXPORTER</th>
                                <th>NOTIFY PARTY</th>
                                <th>WAREHOUSE</th>
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
                                    if ($details['is_transfer'] == 0) continue; //this is important, transfer is must form purchase loading page.
                                    $imp_json = json_decode($details['imp_json']);
                                    $exp_json = json_decode($details['exp_json']);
                                    $notify_json = json_decode($details['notify_json']);
                                    $ware_json = json_decode($details['ware_json']);
                                    $bail_json = json_decode($details['bail_json']);
                                    $pd_id = $details['id'];
                                    $pd_sr = $details['d_sr'];
                                    $pd_count++;
                                    $transfer_as = $details['transfer_as'];
                                    $transfer_as_msg = '';
                                    $rowColor = 'bg-danger bg-opacity-25';
                                    if ($transfer_as > 0) {
                                        $rowColor = '';
                                        $transfer_as_msg = '<br><span class="font-size-11"><i class="fa fa-check-double text-success"></i> ';
                                        if ($transfer_as == 1) {
                                            $transfer_as_msg .= 'Agent';
                                        } else {
                                            $transfer_as_msg .= 'Stock';
                                        }
                                        $transfer_as_msg .= '</span>';
                                        $transfer_as_msg .= '<span class="font-size-11"><b>D.</b>' . date('y-m-d', strtotime($details['as_date'])) . '</span>';
                                    }
                                    ?>
                                    <tr class="pointer text-uppercase <?php echo $rowColor; ?>"
                                        onclick="viewPurchase(<?php echo $purchase_id; ?>,<?php echo $pd_id; ?>)"
                                        data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                        <td class="text-nowrap">
                                            <?php $ctr_no = $count_details > 1 ? '-' . $pd_count : '';
                                            echo '<b>P#</b>' . $purchase_id . '-' . $pd_sr . '<br>';
                                            echo purchaseSpecificData($purchase_id, 'purchase_type');
                                            echo '<br><span class="font-size-11"><b>D.</b>' . $purchase['p_date'] . '</span>';
                                            echo $transfer_as_msg; ?>
                                        </td>
                                        <td class="small text-nowrap">
                                            <?php
                                            echo '<b>BRANCH</b>' . branchName($purchase['branch_id']) . '<br>';
                                            echo '<b>PURCHASE A/c#</b>' . $purchase['p_khaata_no'] . '<br>';
                                            echo '<b>SELLER A/c#</b>' . $purchase['s_khaata_no'];
                                            echo '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name'];
                                            //echo $s_khaata['comp_name']; ?>
                                        </td>

                                        <td class="font-size-11 text-nowrap">
                                            <?php $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                                            if ($cntrs > 0) {
                                                echo '<b>ITEMS. </b>', $cntrs;
                                                echo '<br><b>Qty </b>', $details['qty_no'];
                                                echo '<br><b>KGs </b>', $details['total_kgs'];
                                                echo '<br><b>Goods </b>', $totals['Goods'][0];
                                            } ?>
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
                    url: 'ajax/viewSinglePurchaseTransfer.php',
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
                    <h5 class="modal-title text-danger" id="staticBackdropLabel">PURCHASE LOADING</h5>
                    <a href="purchase-transfer" class="btn-close" aria-label="Close"></a>
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
                    <h5 class="modal-title" id="secondModalLabel">Importer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light " id="addImpExpNotify">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="bailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg" style="min-height: 300px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="bailModalLabel">Bail Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light-" id="bailModalForm"></div>
            </div>
        </div>
    </div>
<?php if (isset($_POST['savePartyPLoading'])) {
    $msg = 'DB Errro :(';
    $msg_type = 'danger';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $urll = 'purchase-loading';
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $validatedData = array_map('htmlspecialchars', $_POST);
    $post = json_encode($validatedData);
    if (is_numeric($pd_id_hidden) && $pd_id_hidden > 0 && in_array($type, ['Importer', 'Exporter', 'Notify', 'Warehouse'])) {
        $urll .= '?p_id=' . $p_id_hidden . '&pd_id=' . $pd_id_hidden . '&view=1';
        switch ($type) {
            case 'Importer':
                $data = ['imp_json' => $post];
                break;
            case 'Exporter':
                $data = ['exp_json' => $post];
                break;
            case 'Notify':
                $data = ['notify_json' => $post];
                break;
            case 'Warehouse':
                $data = ['ware_json' => $post];
                break;
        }
        $done = update('purchase_details', $data, array('id' => $pd_id_hidden));
        if ($done) {
            $msg = 'Party saved.';
            $msg_type = 'success';
        }
    }
    message($msg_type, $urll, $msg);
}
if (isset($_POST['bailDetailsSubmit'])) {
    $msg = 'DB Errro :(';
    $msg_type = 'danger';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $url2 = 'purchase-loading';
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $validatedData = array_map('htmlspecialchars', $_POST);
    $post = json_encode($validatedData);
    if (is_numeric($pd_id_hidden) && $pd_id_hidden > 0) {
        $url2 .= '?p_id=' . $p_id_hidden . '&pd_id=' . $pd_id_hidden . '&view=1';
        $data = array('bail_json' => $post);
        $done = update('purchase_details', $data, array('id' => $pd_id_hidden));
        if ($done) {
            $msg = 'Bail details saved.';
            $msg_type = 'success';
        }
    }
    message($msg_type, $url2, $msg);
}
if (isset($_POST['transferASFormSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $data = array('transfer_as' => $_POST['transfer_as'], 'as_date' => date('Y-m-d'));
    $locked = update('purchase_details', $data, array('id' => $pd_id_hidden));
    $URL = $pageURL . '?p_id=' . $p_id_hidden . '&pd_id=' . $pd_id_hidden . '&view=1';
    if ($locked) {
        $type = 'success';
        $msg = 'Purchase transferred to Transfer Form ';
    }
    message($type, $URL, $msg);
} ?>
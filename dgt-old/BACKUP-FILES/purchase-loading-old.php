<?php $pageURL = 'purchase-loading';
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2 ";
$types_array = array('booking' => '', 'local' => '');
$page_title = 'Loading Form ';
$table_head = array('TYPE', 'PURCHASER & SELLER', 'GOODS', 'BAIL', 'IMPORTER', 'EXPORTER', 'NOTIFY PARTY');
if (isset($_GET['type']) && array_key_exists($_GET['type'], $types_array)) {
    $pp_type = $_GET['type'];
    $sql .= " AND type = '$pp_type'";
    $types_array[$pp_type] = 'active';
    $page_title .= ucfirst($pp_type);
    if ($pp_type == 'local') {
        $table_head = array('TYPE', 'PURCHASER & SELLER', 'GOODS', 'BAIL', 'LOADING WAREHOUSE', 'TRANSFER WAREHOUSE');
    }
} else {
    $sql .= " AND type = 'booking'";
    $types_array['booking'] = 'active';
    $page_title .= ' Booking ';
}
$page_title .= ' Purchase';
include("header.php"); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between table-form">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $types_array['booking']; ?>" href="?type=booking">
                            <span class="d-block d-sm-none"><i class="fas fa-book"></i> Booking</span>
                            <span class="d-none d-sm-block">Booking Purchase</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $types_array['local']; ?>" href="?type=local">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i> Local</span>
                            <span class="d-none d-sm-block">Local Purchase</span>
                        </a>
                    </li>
                </ul>
                <div>
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
            </div>
            <div class="card rounded-0">
                <div class="card-body p-0">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <?php unset($_SESSION['response']);
                    //echo $sql; ?>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr class="text-nowrap">
                                <?php foreach ($table_head as $item) {
                                    echo '<th>' . $item . '</th>';
                                } ?>
                                <!--<th>TYPE</th>
                                <th>PURCHASER & SELLER</th>
                                <th>GOODS</th>
                                <th>BAIL</th>
                                <th>IMPORTER</th>
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
                                    $imp_json = json_decode($details['imp_json']);
                                    $exp_json = json_decode($details['exp_json']);
                                    $notify_json = json_decode($details['notify_json']);
                                    $ware_json = json_decode($details['ware_json']);
                                    $tware_json = json_decode($details['tware_json']);
                                    $bail_json = json_decode($details['bail_json']);
                                    $pd_id = $details['id'];
                                    $pd_sr = $details['d_sr'];
                                    $pd_count++; ?>
                                    <tr class="pointer text-uppercase <?php //echo $rowColor; ?>"
                                        onclick="viewPurchase(<?php echo $purchase_id; ?>,<?php echo $pd_id; ?>)"
                                        data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                        <td class="text-nowrap">
                                            <?php echo '<b>P#</b>' . $purchase_id . '-' . $pd_sr;
                                            if ($details['is_transfer'] > 0) {
                                                echo ' <span class="font-size-10"><i class="fa fa-check-double text-success"></i>TRANSF</span>';
                                            }
                                            echo '<br>' . purchaseSpecificData($purchase_id, 'purchase_type');
                                            echo '<br><span class="font-size-11"><b>D.</b>' . $purchase['p_date'] . '</span>'; ?>
                                        </td>
                                        <td class="font-size-10 text-nowrap">
                                            <?php echo '<b>BRANCH</b>' . branchName($purchase['branch_id']) . '<br>';
                                            echo '<b>PURCHASE A/c#</b>' . $purchase['p_khaata_no'] . '<br>';
                                            echo '<b>SELLER A/c#</b>' . $purchase['s_khaata_no'];
                                            echo '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name']; ?>
                                        </td>
                                        <td class="font-size-10 text-nowrap">
                                            <?php $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                                            if ($cntrs > 0) {
                                                echo '<b>ITEMS. </b>', $cntrs;
                                                echo '<br><b>Qty </b>', $details['qty_no'];
                                                echo '<br><b>KGs </b>', $details['total_kgs'];
                                                echo '<br><b>Goods </b>', $totals['Goods'][0];
                                            } ?>
                                        </td>
                                        <td class="font-size-10 text-nowrap">
                                            <?php if (!empty($bail_json)) {
                                                if ($purchase['type'] == 'booking') {
                                                    echo '<b>CONTAINER#</b>' . $bail_json->container_no;
                                                    echo '<br><b>BAIL#</b>' . $bail_json->bail_no;
                                                    echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                                    echo '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                                } else {
                                                    echo '<b>D.Name </b>' . $bail_json->driver_name;
                                                    echo '<br><b>D.Phone </b>' . $bail_json->driver_phone;
                                                    echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                                    echo '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                                }
                                            } ?>
                                        </td>
                                        <?php if ($purchase['type'] == 'booking') { ?>
                                            <td class="font-size-10">
                                                <?php if (!empty($imp_json)) {
                                                    echo '<b>COMPANY</b>' . $imp_json->comp_name;
                                                    echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                                } ?>
                                            </td>
                                            <td class="font-size-10">
                                                <?php if (!empty($exp_json)) {
                                                    echo '<b>COMPANY</b>' . $exp_json->comp_name;
                                                    echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                                } ?>
                                            </td>
                                            <td class="font-size-10">
                                                <?php if (!empty($notify_json)) {
                                                    echo '<b>COMPANY</b>' . $notify_json->comp_name;
                                                    echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                                } ?>
                                            </td>
                                        <?php } else { ?>
                                            <td class="font-size-10">
                                                <?php if (!empty($ware_json)) {
                                                    echo '<b>NAME</b>' . $ware_json->comp_name;
                                                    echo '<br><b>COUNTRY</b>' . $ware_json->country;
                                                    echo '<br><b>CITY</b>' . $ware_json->city;
                                                } ?>
                                            </td>
                                            <td class="font-size-10">
                                                <?php if (!empty($tware_json)) {
                                                    echo '<b>NAME</b>' . $tware_json->comp_name;
                                                    echo '<br><b>COUNTRY</b>' . $tware_json->country;
                                                    echo '<br><b>CITY</b>' . $tware_json->city;
                                                } ?>
                                            </td>
                                        <?php } ?>
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
                    url: 'ajax/viewSinglePurchaseLoading.php',
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
    <!--<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="staticBackdropLabel">PURCHASE LOADING</h5>
                    <a href="purchase-loading" class="btn-close" aria-label="Close" style="margin-right: 220px;"></a>
                </div>
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </div>
        </div>
    </div>-->
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
                    <a href="purchase-loading" class="btn-close" aria-label="Close"></a>
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
    if (is_numeric($pd_id_hidden) && $pd_id_hidden > 0 && in_array($type, ['Importer', 'Exporter', 'Notify', 'Warehouse', 'TWarehouse'])) {
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
            case 'TWarehouse':
                $data = ['tware_json' => $post];
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
if (isset($_POST['transferFormSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $data = array('is_transfer' => 1, 't_date' => date('Y-m-d'));
    $locked = update('purchase_details', $data, array('id' => $pd_id_hidden));
    $URL = $pageURL . '?p_id=' . $p_id_hidden . '&pd_id=' . $pd_id_hidden . '&view=1';
    if ($locked) {
        $type = 'success';
        $msg = 'Purchase transferred to Transfer Form ';
    }
    message($type, $URL, $msg);
} ?>
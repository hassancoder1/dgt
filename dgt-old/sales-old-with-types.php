<?php
$pageURL = 'sales';
$sql = "SELECT * FROM `sales` WHERE id > 0 ";
$types_array = array('booking' => '', 'local' => '', 'market' => '');
$table_head = array('TYPE', 'PURCHASER & SELLER', 'GOODS', 'BAIL', 'IMPORTER', 'EXPORTER', 'NOTIFY PARTY');
if (isset($_GET['type']) && array_key_exists($_GET['type'], $types_array)) {
    $pp_type = $_GET['type'];
    $sql .= " AND type = '$pp_type'";
    $types_array[$pp_type] = 'active';
    $page_title = ucfirst($pp_type);
    $type_msg = $pp_type;
} else {
    $sql .= " AND type = 'booking'";
    $types_array['booking'] = 'active';
    $page_title = ' Booking ';
}
$page_title .= ' Sales ';
$sql .= " ORDER BY is_transfer ";
include("header.php"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between table-form gap-md-5">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php echo $types_array['booking']; ?>" href="?type=booking">
                        <span class="d-block d-sm-none"><i class="fas fa-book"></i> Booking</span>
                        <span class="d-none d-sm-block">Booking Sale</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $types_array['local']; ?>" href="?type=local">
                        <span class="d-block d-sm-none"><i class="fas fa-home"></i> Local</span>
                        <span class="d-none d-sm-block">Local Sale</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $types_array['market']; ?>" href="?type=market">
                        <span class="d-block d-sm-none"><i class="fas fa-building"></i> Market</span>
                        <span class="d-none d-sm-block">Market Sale</span>
                    </a>
                </li>
            </ul>
            <div class="flex-fill">
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
            <div>
                <?php echo addNew('sale-add', '', 'btn-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="text-center">
                <?php //echo $sql; ?>
            </div>
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>DETAILS</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>SELLER</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sales = mysqli_query($connect, $sql);
                        while ($sale = mysqli_fetch_assoc($sales)) {
                            //$is_doc = $sale['is_doc'];
                            $sale_id = $sale['id'];
                            $sale_type = $sale['type'];
                            $is_transfer = $sale['is_transfer'];
                            $rowColor = '';
                            if ($is_transfer == 0) {
                                $rowColor = 'bg-warning bg-opacity-10';
                            } else {
                                $rowColor = 'bg-warning-bg-opacity-10';
                            } ?>
                            <tr class="pointer <?php echo $rowColor; ?>" onclick="viewSale(<?php echo $sale_id; ?>)"
                                data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                <td>
                                    <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                    echo '<br><b>D.</b>' . date('y-m-d', strtotime($sale['s_date']));
                                    echo $is_transfer != 0 ? '<br><span class="small"><i class="fa fa-check-double text-success"></i> Transferred.</span>' : ''; ?>
                                </td>
                                <td class="small">
                                    <?php echo '<b>BRANCH</b>' . branchName($sale['branch_id']) . '<br>'; ?>
                                    <?php echo '<b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                </td>
                                <td class="small">
                                    <?php $cntrs = saleSpecificData($sale_id, 'sale_rows');
                                    $totals = saleSpecificData($sale_id, 'product_details');
                                    if ($cntrs > 0) {
                                        echo '<b>GOODS. </b>', $totals['Goods'][0];
                                        echo '<br><b>ITEMS. </b>', $cntrs;
                                        echo '<br><b>Qty </b>', $totals['Qty'];
                                        echo '<br><b>KGs </b>', $totals['KGs'];
                                    } ?>
                                </td>
                                <td class="text-dark">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                        //echo '<br><b>Transfer </b>' . $sale['t_date'];
                                    } ?>
                                </td>
                                <td class="small">
                                    <?php if (!empty($sale['seller_json'])) {
                                        $seller_json = json_decode($sale['seller_json']);
                                        echo '<b>A/C.</b>' . $seller_json->khaata_no;
                                        echo '<br><b>Loading D.</b>' .$seller_json->l_date;
                                        echo '<br><b>Container</b>' . $seller_json->ctr_name;
                                    } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['deleteSaleSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $done = mysqli_query($connect, "DELETE FROM `sale_details` WHERE parent_id='$s_id_hidden'");
    $done = mysqli_query($connect, "DELETE FROM `sales` WHERE id='$s_id_hidden'");
    if ($done) {
        $msg = " Deleted Sale #" . $s_id_hidden;
        $type = "success";
    }
    message($type, $pageURL, $msg);
}
if (isset($_POST['s_id_hidden_attach'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $ppp_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden_attach']);
    $url_ = $pageURL . "?p_id=" . $ppp_id . "&attach=1";
    //$dato = array('is_doc' => 1);
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($ppp_id, 'sales', basename($_FILES["attachments"]["name"][$key]));
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
            //$dd = update('sales', $dato, array('id' => $ppp_id));
            //if ($moved && $dd) {
            if ($moved) {
                $type = 'success';
                $msg = 'Attachment Saved ';
                $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            }
        }
    }
    message($type, $url_, $msg);
}
if (isset($_POST['transferSaleSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $data = array('is_transfer' => 1, 'tr_date' => date('Y-m-d'));
    $locked = update('sales', $data, array('id' => $s_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Sale transferred ';
    }
    message($type, $pageURL, $msg);
}
if (isset($_POST['sellerAccountSubmit'])) {
    unset($_POST['sellerAccountSubmit']);
    $post = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $data = array('seller_json' => $post);
    $locked = update('sales', $data, array('id' => $s_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Seller account added ';
        $pageURL .= '?s_id=' . $s_id_hidden . '&view=1';
    }
    message($type, $pageURL, $msg);
} ?>
<?php if (isset($_GET['s_id']) && is_numeric($_GET['s_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $s_id = mysqli_real_escape_string($connect, $_GET['s_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewSale($s_id); });</script>";
} ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="staticBackdropLabel">SALE DETAILS</h5>
                <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewSale(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleSale.php',
                type: 'post',
                data: {id: id},
                //data: {id: id, p_khaata_no: k_no},
                //dataType: 'json',
                success: function (response) {
                    //console.log(response);
                    $('#viewDetails').html(response);
                    // $('#khaata_no').focus();
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>

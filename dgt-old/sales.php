<?php $pageURL = 'sales';
$sql = "SELECT * FROM `sales` WHERE id > 0 ";
$types_array = array('all' => '', 'booking' => '', 'local' => '', 'market' => '');
$table_head = array('TYPE', 'PURCHASER & SELLER', 'GOODS', 'BAIL', 'IMPORTER', 'EXPORTER', 'NOTIFY PARTY');
if (isset($_GET['type']) && array_key_exists($_GET['type'], $types_array)) {
    $pp_type = $_GET['type'];
    $sql .= " AND type = '$pp_type'";
    $pageURL .= '?type=' . $pp_type;
    $types_array[$pp_type] = 'active';
    $page_title = ucfirst($pp_type) . ' Sales';
    $type_msg = $pp_type;
} else {
    $sql .= " AND type != 'market'";
    $types_array['all'] = 'active';
    $page_title = ' All Sales ';
}
$page_title .= ' [ADMIN]';
include("header.php"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between table-form gap-md-5">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php echo $types_array['all']; ?>" href="sales">
                        <span class="d-block d-sm-none"><i class="fas fa-dashcube"></i> All</span>
                        <span class="d-none d-sm-block">All Sales</span>
                    </a>
                </li>
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
                <!--<li class="nav-item">
                    <a class="nav-link <?php /*echo $types_array['market']; */ ?>" href="?type=market">
                        <span class="d-block d-sm-none"><i class="fas fa-building"></i> Market</span>
                        <span class="d-none d-sm-block">Market Sale</span>
                    </a>
                </li>-->
            </ul>
            <div>
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
            <?php //echo addNew('sale-add', '', 'btn-sm'); ?>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 75dvh;">
                    <table class="table mb-0 fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>DETAILS</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>REPORT</th>
                            <th>SOLD TO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sales = mysqli_query($connect, $sql);
                        while ($sale = mysqli_fetch_assoc($sales)) {
                            //$is_doc = $sale['is_doc'];
                            $sale_id = $sale['id'];
                            $sale_type = $sale['type'];
                            $rowColor = ''; ?>
                            <tr class="pointer <?php echo $rowColor; ?>" onclick="viewSale(<?php echo $sale_id; ?>)"
                                data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                <td class="text-nowrap text-uppercase">
                                    <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                    echo '<br>' . seaRoadBadge($sale['sea_road']);
                                    echo '<br><span class="font-size-11"><b>D.</b>' . date('y-m-d', strtotime($sale['s_date'])) . '</span>';
                                    ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>B.</b>' . branchName($sale['branch_id']);
                                    echo ' <b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php $cntrs = saleSpecificData($sale_id, 'sale_rows');
                                    $totals = saleSpecificData($sale_id, 'product_details');
                                    if ($cntrs > 0) {
                                        echo '<b>GOODS. </b>' . $totals['Goods'][0];
                                        echo '<br><b>ITEMS. </b>' . $cntrs;
                                        echo ' <b>Qty </b>' . $totals['Qty'];
                                        echo '<br><b>KGs </b>' . $totals['KGs'];
                                    } ?>
                                </td>
                                <td class="text-dark text-nowrap">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . round($totals['Amount']) . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . round($totals['Final']) . '<sub>' . $totals['curr2'] . '</sub>';
                                    } ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo readMoreTooltip($sale['report'], '50'); ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if ($sale['s_khaata_no'] == '') {
                                        echo '<div class="bg-danger">&nbsp;</div>';
                                    } else {
                                        echo '<b>A/c#</b>' . $sale['s_khaata_no'];
                                        $sold_to = khaataSingle($sale['s_khaata_no'], true);
                                        echo '<br>' . $sold_to['comp_name'];
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

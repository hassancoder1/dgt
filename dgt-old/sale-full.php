<?php $page_title = 'Full Payment Form';
$pageURL = 'sale-full';
include("header.php");
$sql = "SELECT * FROM `sales` WHERE transfer =2 ORDER BY transfer ";
$sales = mysqli_query($connect, $sql);
$remove = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
        $pageURL .= '?goods_name=' . $goods_name;
    }
    if (isset($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $pageURL .= '&size=' . $size;
    }
    if (isset($_GET['brand'])) {
        $brand = mysqli_real_escape_string($connect, $_GET['brand']);
        $pageURL .= '&brand=' . $brand;
    }
    if (isset($_GET['origin'])) {
        $origin = mysqli_real_escape_string($connect, $_GET['origin']);
        $pageURL .= '&origin=' . $origin;
    }
    if (isset($_GET['start'])) {
        $start_print = $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '&start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end_print = $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
    if (isset($_GET['is_transferred'])) {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $pageURL .= '&is_transferred=' . $is_transferred;
    }
    if (isset($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
        $pageURL .= '&s_khaata_id=' . $s_khaata_id;
    }
}
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex table-form text-nowrap align-items-center justify-content-between">
                <?php echo addNew($pageURL . '?view=1', 'NEW', 'btn-sm'); ?>
                <div><b>ROWS: </b><span id="rows_count_span"></span></div>
                <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
                <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
                <form action="print/sales-full" target="_blank" method="get" class="d-none">
                    <input type="hidden" name="table" value="sales">
                    <input type="hidden" name="url" value="sales-booking">
                    <input type="hidden" name="type" value="booking">
                    <input type="hidden" name="start" value="<?php echo $start; ?>">
                    <input type="hidden" name="end" value="<?php echo $end; ?>">
                    <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                    <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                    <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                    <button class="btn btn-sm btn-success">PRINT</button>
                </form>
                <form method="get" class="d-flex align-items-center ">
                    <?php echo searchInput('', 'form-control-sm'); ?>
                    <?php echo $remove; ?>
                    <div class="input-group">
                        <input type="date" name="start" value="<?php echo $start; ?>" class="form-control">
                    </div>
                    <div class="input-group">
                        <input type="date" name="end" value="<?php echo $end; ?>" class="form-control">
                    </div>
                    <div class="input-group">
                        <select id="goods_name" name="goods_name" class="form-select">
                            <option value="">ALL GOODS</option>
                            <?php $goods = fetch('goods');
                            while ($good = mysqli_fetch_assoc($goods)) {
                                $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                                echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="s_khaata_id" class="form-select">
                            <option value="" hidden>Customer A/c</option>
                            <?php $accounts_query = fetch('khaata');
                            while ($aa = mysqli_fetch_assoc($accounts_query)) {
                                $sel = $s_khaata_id == $aa['khaata_no'] ? 'selected' : '';
                                echo '<option ' . $sel . ' value="' . $aa['khaata_no'] . '">' . $aa['khaata_no'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-search"></i></button>
                </form>
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
                                <th>BRANCH</th>
                                <th>GOODS DETAILS</th>
                                <th>SELLER</th>
                                <th>AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                            $sales = mysqli_query($connect, $sql);
                            while ($sale = mysqli_fetch_assoc($sales)) {
                                $sale_id = $sale['id'];
                                $sale_type = $sale['type'];
                                $totals = saleSpecificData($sale_id, 'product_details');
                                $seller_json = json_decode($sale['seller_json']);
                                //$rowColor = empty($khaata_tr1) ? 'bg-danger bg-opacity-10' : ''; ?>
                                <tr class="pointer text-uppercase <?php //echo $rowColor; ?>"
                                    onclick="viewSale(<?php echo $sale_id; ?>)" data-bs-toggle="modal"
                                    data-bs-target="#KhaataDetails">
                                    <td>
                                        <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                        echo '<br><b>D.</b>' . date('y-m-d', strtotime($sale['s_date'])); ?>
                                    </td>
                                    <td class="small">
                                        <?php echo '<b>BRANCH</b>' . branchName($sale['branch_id']) . '<br>'; ?>
                                        <?php echo '<b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                    </td>
                                    <td class="small text-nowrap">
                                        <?php $cntrs = saleSpecificData($sale_id, 'sale_rows');
                                        if ($cntrs > 0) {
                                            echo '<b>GOODS. </b>' . $totals['Goods'][0];
                                            echo '<br><b>ITEMS. </b>' . $cntrs;
                                            echo '<br><b>Qty </b>' . $totals['Qty'];
                                            echo '<br><b>KGs </b>' . $totals['KGs'];
                                        } ?>
                                    </td>
                                    <td class="small text-nowrap">
                                        <?php if (!empty($seller_json)) {
                                            echo '<b>A/C.</b>' . $seller_json->khaata_no;
                                            echo '<br><b>Loading D.</b>' . date('y-m-d', strtotime($seller_json->l_date));
                                            echo '<br><b>Container</b>' . $seller_json->ctr_name;
                                        } ?>
                                    </td>
                                    <td class="text-dark-">
                                        <?php if ($cntrs > 0) {
                                            echo '<b>Amt. </b>' . $totals['Amount'];
                                            echo '<br><b>Final </b>' . $totals['Final'];
                                            echo '<br><b>TRANSFER D.</b>' . date('y-m-d', strtotime($sale['t_date']));
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
    <script>
        function viewSale(id = null, sale_pays_id = null) {
            if (id) {
                var sp_id = 0;
                if (sale_pays_id) {
                    sp_id = sale_pays_id;
                }
                $.ajax({
                    url: 'ajax/viewSingleSaleFull.php',
                    type: 'post',
                    data: {id: id, sale_pays_id: sp_id},
                    //data: {id: id, show_transfer: true},
                    success: function (response) {
                        //console.log(response);
                        $('#viewDetails').html(response);
                    }
                });
            } else {
                alert('error!! Refresh the page again');
            }
        }
    </script>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="staticBackdropLabel">SALE FULL PAYMENTS</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </div>
        </div>
    </div>
<?php if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id); });</script>";
} ?>
<?php $page_title = 'Qty Stock';
$pageURL = 'stock-qty';
include("header.php");
$goods_id_search = 0;
$remove = $size = $start_print = $end_print = $start = $end = $wh_khaata = $pur_sale = '';
$is_search = false;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['goods_id'])) {
        $goods_id_search = mysqli_real_escape_string($connect, $_GET['goods_id']);
        $pageURL .= '?goods_id=' . $goods_id_search;
    }
    if (isset($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $pageURL .= '&size=' . $size;
    }
    if (isset($_GET['wh_khaata'])) {
        $wh_khaata = mysqli_real_escape_string($connect, $_GET['wh_khaata']);
        $pageURL .= '&wh_khaata=' . $wh_khaata;
    }
    if (isset($_GET['pur_sale'])) {
        $pur_sale = mysqli_real_escape_string($connect, $_GET['pur_sale']);
        $pageURL .= '&pur_sale=' . $pur_sale;
    }
    if (isset($_GET['start'])) {
        $start_print = $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '&start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end_print = $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
} ?>
<style>
    label {
        margin-bottom: 0;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between gap-1">
            <form method="get" class="table-form d-flex align-items-center">
                <?php echo $remove; ?>
                <div class="input-group" style="width: 250px">
                    <label for="goods_id">GOODS</label>
                    <select id="goods_id" name="goods_id" class="form-select">
                        <option value="">ALL GOODS</option>
                        <?php $goods = fetch('goods');
                        while ($good = mysqli_fetch_assoc($goods)) {
                            $g_selected = $good['id'] == $goods_id_search ? 'selected' : '';
                            echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                        } ?>
                    </select>
                </div>
                <button class="btn btn-sm btn-secondary"><i class="fa fa-search"></i></button>
            </form>
            <!--<div><b>ROWS: </b><span id="rows_count_span"></span></div>-->
            <div><b>PURCHASE QTY: </b><span id="p_qty_total_span"></span></div>
            <div><b>PURCHASE KGs: </b><span id="p_kgs_total_span"></span></div>
            <div><b>SALE QTY: </b><span id="s_qty_total_span"></span></div>
            <div><b>SALE KGs: </b><span id="s_kgs_total_span"></span></div>
            <form action="print/stock" target="_blank" method="get">
                <input type="hidden" name="wh_khaata" value="<?php echo $wh_khaata; ?>">
                <input type="hidden" name="goods_id" value="<?php echo $goods_id_search; ?>">
                <input type="hidden" name="size" value="<?php echo $size; ?>">
                <input type="hidden" name="pur_sale" value="<?php echo $pur_sale; ?>">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                <input type="hidden" name="start" value="<?php echo $start_print; ?>">
                <input type="hidden" name="end" value="<?php echo $end_print; ?>">
                <button class="btn btn-sm btn-success">PRINT</button>
            </form>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? ''; ?>
                <?php unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 72dvh;">
                    <table class="table table-bordered mb-0 fix-head-table">
                        <thead>
                        <tr class="text-nowrap table-light- font-size-12">
                            <th>#</th>
                            <th>GOODS</th>
                            <th>P.QTY</th>
                            <th>S.QTY</th>
                            <th>QTY BAL.</th>
                            <th>P.KGs</th>
                            <th>S.KGs</th>
                            <th>KGs BAL.</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                        $p_qty_total_all = $s_qty_total_all = $p_kgs_total_all = $s_kgs_total_all = 0;
                        $goods_query = fetch('goods');
                        while ($goods_table_data = mysqli_fetch_assoc($goods_query)) {
                            $goods_id = $goods_table_data['id'];
                            $goods_name = $goods_table_data['name'];
                            $sql_union = "SELECT 'purchase' AS source_table,id,parent_id,goods_id,is_transfer,transfer_as,qty_no,total_kgs FROM purchase_details WHERE goods_id = '$goods_id'
                            UNION 
                            SELECT 'sale' AS source_table,id,parent_id,goods_id,is_transfer,transfer_as,qty_no,total_kgs FROM sale_details WHERE goods_id = '$goods_id'
                            ORDER BY goods_id";
                            $query_union = mysqli_query($connect, $sql_union);
                            $p_qty_total = $s_qty_total = $p_kgs_total = $s_kgs_total = 0;

                            $p_goods_ids = $s_goods_ids = array();
                            while ($details = mysqli_fetch_assoc($query_union)) {
                                $source_table = $details['source_table'];
                                $d_id = $details['id'];
                                $parent_id = $details['parent_id'];
                                $is_transfer = $details['is_transfer'];
                                $transfer_as = $details['transfer_as'];
                                if ($source_table == 'purchase') {
                                    if ($is_transfer == 0) continue; // transferred from loading form to Transfer Form
                                    /* purchase-transfer => Stock=2 | Agent=1
                                     * Show if $details['transfer_as'] =2
                                     * OR  if $details['transfer_as'] = 1 then check purchase_agents table
                                     * Show if details colum in not NULL in purchase_agents table */
                                    if ($transfer_as == 0) {
                                        continue;
                                    } elseif ($transfer_as == 1) {
                                        $condo = fetch('purchase_agents', array('d_id' => $d_id));
                                        $count_purchase_agents_empty_details = 0;
                                        while ($zoz = mysqli_fetch_assoc($condo)) {
                                            if (empty($zoz['details'])) {
                                                ++$count_purchase_agents_empty_details;
                                            }
                                        }
                                        if ($count_purchase_agents_empty_details > 0) continue;
                                    } else {
                                        // do nothing, coz 2 is for Stock
                                    }
                                }
                                $parent_query = fetch($source_table . 's', array('id' => $parent_id));
                                $parent_data = mysqli_fetch_assoc($parent_query);
                                if ($source_table == 'purchase') {
                                    if ($parent_data['transfer'] < 2) continue; /*transfer = 2 mean payment is complete. Either transferred to Full or transferred from Advance to full*/
                                    $rowColor = 'text-danger';
                                    if ($parent_data['is_locked'] != 1) continue;
                                    $p_qty_total += $details['qty_no'];
                                    $p_kgs_total += $details['total_kgs'];
                                } else {
                                    $rowColor = '';
                                    $khaata_tr1 = json_decode($parent_data['khaata_tr1']);
                                    if (empty($khaata_tr1)) continue;
                                    //$s_goods_ids[] = $details['goods_id'];
                                    $s_qty_total += $details['qty_no'];
                                    $s_kgs_total += $details['total_kgs'];
                                }
                            }
                            if ($is_search) {
                                if ($goods_id_search > 0) {
                                    if ($goods_id_search != $goods_id) continue;
                                }
                                if ($size != '') {
                                    if ($size != $details['size']) continue;
                                }
                                if ($start != '') {
                                    if ($source_table == 'purchase') {
                                        if ($parent_data['p_date'] < $start) continue;
                                    } else {
                                        if ($parent_data['s_date'] < $start) continue;
                                    }
                                }
                                if ($end != '') {
                                    if ($source_table == 'purchase') {
                                        if ($parent_data['p_date'] > $end) continue;
                                    } else {
                                        if ($parent_data['s_date'] > $end) continue;
                                    }
                                }
                                if ($pur_sale != '') {
                                    if ($pur_sale != $source_table) continue;
                                }
                            }
                            if ($p_qty_total == 0 && $s_qty_total == 0 && $p_kgs_total == 0 && $s_kgs_total == 0) continue; ?>
                            <tr class="text-uppercase text-nowrap">
                                <?php
                                echo '<td>' . $no . '</td>';
                                echo '<td>' . $goods_name . '</td>';

                                echo '<td class="text-danger">' . $p_qty_total . '</td>';
                                echo '<td class="text-success">' . $s_qty_total . '</td>';
                                $qty_balance = $p_qty_total - $s_qty_total;
                                echo '<td class="bold">' . $qty_balance . '</td>';

                                echo '<td class="text-danger">' . $p_kgs_total . '</td>';
                                echo '<td class="text-success">' . $s_kgs_total . '</td>';
                                $kgs_balance = $p_kgs_total - $s_kgs_total;
                                echo '<td class="bold">' . $kgs_balance . '</td>'; ?>

                            </tr>
                            <?php $no++;
                            $p_qty_total_all += $p_qty_total;
                            $s_qty_total_all += $s_qty_total;
                            $p_kgs_total_all += $p_kgs_total;
                            $s_kgs_total_all += $s_kgs_total;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total_all; ?>">
                    <input type="hidden" id="s_qty_total" value="<?php echo $s_qty_total_all; ?>">
                    <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total_all; ?>">
                    <input type="hidden" id="s_kgs_total" value="<?php echo $s_kgs_total_all; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    //$("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#s_qty_total_span").text($("#s_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
    $("#s_kgs_total_span").text($("#s_kgs_total").val());
</script>
<script>
    function viewPurchase(id = null, pd_id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSinglePurchaseStock.php',
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
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">PURCHASE STOCK</h5>
                <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>

<?php $pageURL = 'vat-general';
$page_title = 'VAT GENERAL';
include("header.php");
global $branchId;
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
} ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex table-form text-nowrap align-items-center justify-content-between">
            <div>
                <div class="d-flex justify-content-between gap-md-3">
                    <div><b>Amnt</b><span id="amnt_total_span"></span></div>
                    <div><b>VAT</b><span id="vat_total_span"></span></div>
                    <div><b>FINAL</b><span id="final_total_span"></span></div>
                </div>
                <div class="d-flex justify-content-between gap-md-3">
                    <div><b>ROWS</b><span id="rows_count_span"></span></div>
                    <div><b>QTY</b><span id="p_qty_total_span"></span></div>
                    <div><b>KGs</b><span id="p_kgs_total_span"></span></div>
                </div>
            </div>
            <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                <button class="btn btn-sm btn-success"><i class="fa fa-print"></i></button>
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
                        <option value="" hidden>Party A/c</option>
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
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 76dvh;">
                    <table class="table mb-0 table-bordered table-sm fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>PARTY A/C.</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>VAT</th>
                            <th>Bal.</th>
                            <th>DETAILS</th>
                            <th>REPORT</th>
                            <th>VAT Form</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                        $amnt_total = $final_total = $vat_total = 0;
                        $sql_union = "SELECT 'purchase' AS source_table,id,type,is_acc,seller_json,khaata_tr1,p_date,city,branch_id,p_khaata_no,report,vat_json 
                            FROM purchases WHERE type = 'market'
                            UNION 
                            SELECT 'sale' AS source_table,id,type,is_acc,seller_json,khaata_tr1,s_date,city,branch_id,p_khaata_no,report,vat_json 
                            FROM sales WHERE type = 'market'
                            ORDER BY khaata_tr1";
                        $records = mysqli_query($connect, $sql_union);
                        while ($record = mysqli_fetch_assoc($records)) {
                            $parent_id = $record['id'];
                            $type = $record['type'];
                            $is_acc = $record['is_acc'];
                            $seller_json = json_decode($record['seller_json']);
                            $khaata_tr1 = json_decode($record['khaata_tr1']);
                            if (empty($khaata_tr1)) continue;
                            $source_table = $record['source_table'];
                            if ($source_table == 'purchase') {
                                $ps_type = purchaseSpecificData($parent_id, $source_table . '_type');
                                $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                                $totals = purchaseSpecificData($parent_id, 'product_details');
                                $rowColor = '';
                            } else {
                                $ps_type = saleSpecificData($parent_id, $source_table . '_type');
                                $cntrs = saleSpecificData($parent_id, 'sale_rows');
                                $totals = saleSpecificData($parent_id, 'product_details');
                                $rowColor = 'bg-success bg-opacity-10 border border-success';
                            }
                            $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                            $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                            $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . '<br>' : '';
                            $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';
                            if ($is_search) {
                                if ($start != '') {
                                    if ($record['p_date'] < $start) continue;
                                }
                                if ($end != '') {
                                    if ($record['p_date'] > $end) continue;
                                }
                                $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                                if ($goods_name != '') {
                                    if ($goods_name != $GoodsKaNaam) continue;
                                }
                                if ($s_khaata_id != '') {
                                    if ($s_khaata_id != $seller_json->khaata_no) continue;
                                }
                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            $amnt_total += !empty($totals['Amount']) ? $totals['Amount'] : 0;
                            $final_total += !empty($totals['Final']) ? $totals['Final'] : 0;

                            $sd_data = fetch($source_table . '_details', array('parent_id' => $parent_id));
                            $vat_items = $vat_items_amount = 0;
                            while ($sd_datum = mysqli_fetch_assoc($sd_data)) {
                                if ($sd_datum['rate2'] > 0) {
                                    $vat_items_amount += $sd_datum['rate2'];
                                    $vat_items++;
                                    $vat_total += $vat_items_amount;
                                }
                            }
                            if ($vat_items_amount <= 0) continue; ?>
                            <tr class="text-nowrap <?php echo $rowColor; ?>">
                                <td class="font-size-11">
                                    <?php echo '<b>' . substr(strtoupper($source_table), 0, 1) . '#</b>' . $parent_id . $ps_type;
                                    echo '<br><span class="font-size-11"><b>D.</b>' . date('y-m-d', strtotime($record['p_date']));
                                    echo $record['city'] != '' ? '<br><b>BILL NAME</b>' . $record['city'] : '';
                                    echo '</span>'; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>A/C.</b>' . $seller_json->khaata_no . '<br>';
                                    if ($is_acc == 1) {
                                        $seller_khaata = khaataSingle($seller_json->khaata_id);
                                        if (!empty($seller_khaata)) {
                                            echo '<b>NAME</b>' . $seller_khaata['khaata_name'];
                                            echo '<br><b>COMP.</b>' . $seller_khaata['comp_name'];
                                            $details_k = ['indexes' => $seller_khaata['indexes'], 'vals' => $seller_khaata['vals']];
                                            $reps = displayKhaataDetails($details_k, true);
                                            if (array_key_exists('VAT', $reps)) {
                                                echo '<br><b>VAT# </b>' . $reps['VAT'];
                                            }
                                            if (array_key_exists('License', $reps)) {
                                                echo '<br><b>License# </b>' . $reps['License'];
                                            }
                                        }
                                    } else {
                                        echo '<b>SALE NAME</b>' . $seller_json->s_name . '<br>';
                                        echo '<b>COMP.</b>' . $seller_json->s_company . '<br>';
                                        echo '<b>VAT#</b>' . $seller_json->s_weight_no . '<br>';
                                    } ?>
                                </td>

                                <td class="font-size-11"><?php echo $Goods . $ITEMS . $Qty . $KGs; ?></td>
                                <td class="text-dark">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        //echo '<br><b>Transfer </b>' . $record['t_date'];
                                    } ?>
                                </td>
                                <td><?php echo $vat_items_amount; ?></td>
                                <td>---</td>
                                <td class="font-size-11 ">
                                    <?php echo '<b>B.</b> ' . branchName($record['branch_id']) . '<br>';
                                    echo '<b>OWNER</b> ' . strtoupper($record['p_khaata_no']); ?>
                                </td>
                                <td class="font-size-10 text-wrap">
                                    <div style="width: 130px"><?php echo readMoreTooltip($record['report'], 80) ?></div>
                                </td>
                                <td class="font-size-11">
                                    <?php $vat_json_e = array('vat_date' => '', 'vat_serial' => '', 'vat_details' => '', 'vat_tax' => '');
                                    if (!empty($record['vat_json'])) {
                                        $vvv = json_decode($record['vat_json']);
                                        $vat_json_e = array(
                                            'vat_date' => $vvv->vat_date, 'vat_serial' => $vvv->vat_serial,
                                            'vat_details' => $vvv->vat_details, 'vat_tax' => $vvv->vat_tax
                                        );
                                        echo '<b>D.</b>' . my_date($vat_json_e['vat_date']) . '<br>';
                                        echo '<b>VAT Sr.</b>' . $vat_json_e['vat_serial'] . '<br>';
                                        echo '<b>VAT Tax</b>' . $vat_json_e['vat_tax'] . '<br>';
                                        echo '<b>VAT Details</b>' . $vat_json_e['vat_details'];
                                    } ?>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                        <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                        <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                        <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                        <input type="hidden" id="amnt_total" value="<?php echo $amnt_total; ?>">
                        <input type="hidden" id="final_total" value="<?php echo $final_total; ?>">
                        <input type="hidden" id="vat_total" value="<?php echo $vat_total; ?>">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
    $("#amnt_total_span").text($("#amnt_total").val());
    $("#final_total_span").text($("#final_total").val());
    $("#vat_total_span").text($("#vat_total").val());
</script>
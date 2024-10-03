<?php $page_title = 'Warehouse Details';
$pageURL = 'warehouse-details';
include("header.php");
$goods_id = 0;
$remove = $size = $start_print = $end_print = $start = $end = $wh_khaata = $pur_sale = '';
$is_search = false;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['goods_id'])) {
        $goods_id = mysqli_real_escape_string($connect, $_GET['goods_id']);
        $pageURL .= '?goods_id=' . $goods_id;
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
        <form method="get" class="table-form d-flex align-items-center text-nowrap flex-fill">
            <?php echo $remove; ?>
            <div class="input-group w-25">
                <label for="wh_khaata" class="d-none">A/C</label>
                <select class="form-select" name="wh_khaata" id="wh_khaata" required>
                    <option value="" hidden>SELECT A/C</option>
                    <?php $result = fetch('khaata_details', array('static_type' => 'Warehouse'));
                    while ($kh = mysqli_fetch_assoc($result)) {
                        if ($kh['comp_name'] != '') {
                            $wh_khaataaa = khaataSingle($kh['khaata_id']);
                            $wh_selected = $wh_khaataaa['khaata_no'] == $wh_khaata ? 'selected' : '';
                            echo '<option ' . $wh_selected . ' value="' . $wh_khaataaa['khaata_no'] . '">' . $wh_khaataaa['khaata_no'] . '</option>';
                        }
                    } ?>
                </select>
            </div>
            <?php if ($is_search && $wh_khaata != '') { ?>
                <div class="input-group">
                    <label for="goods_id">GOODS</label>
                    <select id="goods_id" name="goods_id" class="form-select">
                        <option value="">ALL GOODS</option>
                        <?php $goods = fetch('goods');
                        while ($good = mysqli_fetch_assoc($goods)) {
                            $g_selected = $good['id'] == $goods_id ? 'selected' : '';
                            echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="size">SIZE</label>
                    <select class="form-select" name="size" id="size">
                        <option value="">ALL SIZE</option>
                        <?php //$goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = '$goods_id'");
                        $sql_size = "SELECT DISTINCT size FROM good_details ";
                        $sql_size .= $goods_id > 0 ? " WHERE goods_id = '$goods_id' " : "";
                        $goods_sizes = mysqli_query($connect, $sql_size);
                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                            $size_selected = $size_s['size'] == $size ? 'selected' : '';
                            echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group w-75">
                    <label for="pur_sale">P.S.</label>
                    <select class="form-select" name="pur_sale" id="pur_sale">
                        <option value="">ALL</option>
                        <?php $pur_sale_array = array('purchase', 'sale');
                        foreach ($pur_sale_array as $str) {
                            $ps_selected = $str == $pur_sale ? 'selected' : '';
                            echo '<option ' . $ps_selected . ' value="' . $str . '">' . strtoupper($str) . '</option>';
                        } ?>
                    </select>
                </div>
                <input type="date" name="start" value="<?php echo $start; ?>" class="form-control w-75">
                <input type="date" name="end" value="<?php echo $end; ?>" class="form-control w-75">
            <?php } ?>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-search"></i></button>
        </form>
        <?php if ($is_search && $wh_khaata != '') {
            $dummy_wh_khaata = khaataSingle($wh_khaata, true); ?>
            <div class="d-flex align-items-center justify-content-between gap-1">
                <div class="card rounded-0 position-relative small mb-1">
                    <div class="info-div">Account</div>
                    <div class="d-flex justify-content-between p-1 gap-1">
                        <div class="text-nowrap">
                            <b>A/C#</b> <?php echo $dummy_wh_khaata['khaata_no'] . '<br>'; ?>
                            <b>A/C NAME</b><?php echo $dummy_wh_khaata['khaata_name'] . '<br>'; ?>
                            <b>B.</b> <?php echo branchName($dummy_wh_khaata['branch_id']); ?>
                            <b>CAT.</b> <?php echo catName($dummy_wh_khaata['cat_id']); ?>
                        </div>
                        <div>
                            <b>B. NAME</b> <?php echo $dummy_wh_khaata['business_name'] . '<br>'; ?>
                            <b>ADD.</b> <?php echo $dummy_wh_khaata['address'] . '<br>'; ?>
                            <b>COMP.</b> <?php echo $dummy_wh_khaata['comp_name']; ?>
                        </div>
                        <div class="text-nowrap">
                            <?php $selected_khaata_details = ['indexes' => $dummy_wh_khaata['indexes'], 'vals' => $dummy_wh_khaata['vals']];
                            echo displayKhaataDetails($selected_khaata_details);
                            $contacts = displayKhaataDetails($selected_khaata_details, true);
                            /*echo array_key_exists('Phone', $contacts) ? '<b>P.</b> ' . $contacts['Phone'] . '<br>' : '';
                            echo array_key_exists('WhatsApp', $contacts) ? '<b>WA.</b> ' . $contacts['WhatsApp'] . '<br>' : '';
                            echo array_key_exists('Email', $contacts) ? '<b>E.</b> ' . $contacts['Email'] : '';*/ ?>
                        </div>
                        <div>
                            <img id="khaata_image" src="<?php echo $dummy_wh_khaata['image']; ?>"
                                 class="avatar-lg rounded shadow" alt="Image">
                        </div>
                    </div>
                </div>
                <div>
                    <b>P. QTY: </b><span id="p_qty_total_span"></span><br>
                    <b>P. KGs: </b><span id="p_kgs_total_span"></span></div>
                <div>
                    <b>S. QTY: </b><span id="s_qty_total_span"></span><br>
                    <b>S. KGs: </b><span id="s_kgs_total_span"></span>
                </div>
                <div>
                    <b>ROWS: </b><span id="rows_count_span"></span>
                    <form action="print/stock" target="_blank" method="get">
                        <input type="hidden" name="wh_khaata" value="<?php echo $wh_khaata; ?>">
                        <input type="hidden" name="goods_id" value="<?php echo $goods_id; ?>">
                        <input type="hidden" name="size" value="<?php echo $size; ?>">
                        <input type="hidden" name="pur_sale" value="<?php echo $pur_sale; ?>">
                        <input type="hidden" name="start" value="<?php echo $start; ?>">
                        <input type="hidden" name="end" value="<?php echo $end; ?>">
                        <input type="hidden" name="start" value="<?php echo $start_print; ?>">
                        <input type="hidden" name="end" value="<?php echo $end_print; ?>">
                        <button class="btn btn-sm btn-success w-100">PRINT</button>
                    </form>
                </div>
            </div>
        <?php } ?>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? ''; ?>
                <?php unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 66dvh;">
                    <table class="table mb-0 table-bordered fix-head-table">
                        <thead>
                        <tr class="text-nowrap table-light font-size-12">
                            <th>#</th>
                            <th>SR#</th>
                            <th>P.DATE</th>
                            <!--<th>P.A/C</th>
                            <th>S.A/C</th>
                            <th>B.</th>-->
                            <th>CONTAINER#</th>
                            <th>BAIL#</th>
                            <th>CTR REC.D.</th>
                            <th>ALLOT</th>
                            <th>GOODS</th>
                            <th>SIZE</th>
                            <th>P.QTY</th>
                            <th>S.QTY</th>
                            <th>P.KGs</th>
                            <th>S.KGs</th>
                            <!--<th>WAREHOUSE</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = $row_count = $p_qty_total = $s_qty_total = $p_kgs_total = $s_kgs_total = 0;
                        if ($is_search && $wh_khaata != '') {
                            $sql_union = "SELECT 'purchase' AS source_table,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg,wh_k_id,wh_kd_id
                            FROM purchase_details
                            UNION 
                            SELECT 'sale' AS source_table    ,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg,wh_k_id,wh_kd_id
                            FROM sale_details 
                            ORDER BY transfer_as";
                            $query_union = mysqli_query($connect, $sql_union);
                            while ($details = mysqli_fetch_assoc($query_union)) {
                                $source_table = $details['source_table'];
                                $d_id = $details['id'];
                                $d_sr = $details['d_sr'];
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
                                $imp_json = json_decode($details['imp_json']);
                                $exp_json = json_decode($details['exp_json']);
                                $notify_json = json_decode($details['notify_json']);
                                $ware_json = json_decode($details['ware_json']); // loading warehouse
                                $tware_json = json_decode($details['tware_json']); //transfer warehouse
                                $bail_json = json_decode($details['bail_json']);
                                //purchases / sales parent tables data
                                $parent_query = fetch($source_table . 's', array('id' => $parent_id));
                                $parent_data = mysqli_fetch_assoc($parent_query);
                                if ($source_table == 'purchase') {
                                    if ($parent_data['transfer'] < 2) continue; /*transfer = 2 mean payment is complete. Either transferred to Full or transferred from Advance to full*/
                                }
                                $purchase_sale_type = $parent_data['type'];
                                $container_no = $bail_no = $ctr_rec_date = $warehouse = '';
                                if ($source_table == 'purchase') {
                                    $rowColor = 'text-danger';
                                    if ($parent_data['is_locked'] != 1) continue;
                                    $label = '<b>P#</b>';
                                    $type_badge = purchaseSpecificData($parent_id, 'purchase_type');
                                    $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                                    $purchase_sale_date = date('y-m-d', strtotime($parent_data['p_date']));
                                    $s_khaata_no = $parent_data['s_khaata_no'];
                                    $s_khaata = khaataSingle($parent_data['s_khaata_id']);

                                    $p_khaata_no = $parent_data['p_khaata_no'];
                                    $p_khaata = khaataSingle($parent_data['p_khaata_id']);
                                    $allot = $parent_data['allot'];
                                    $totals = purchaseSpecificData($parent_id, 'product_details');
                                    if (!empty($tware_json)) {
                                        $transfer_wh_khaata = khaataSingle($tware_json->party_khaata_id);
                                        $warehouse = $transfer_wh_khaata['khaata_no'] . ' ' . $transfer_wh_khaata['khaata_name'];
                                        if ($is_search && $wh_khaata != '') {
                                            if ($wh_khaata != $transfer_wh_khaata['khaata_no']) continue;
                                        }
                                    }
                                } else {
                                    $rowColor = '';
                                    $khaata_tr1 = json_decode($parent_data['khaata_tr1']);
                                    if (empty($khaata_tr1)) continue;
                                    $label = '<b>S#</b>';
                                    $type_badge = saleSpecificData($parent_id, 'sale_type');
                                    $cntrs = saleSpecificData($parent_id, 'sale_rows');
                                    $purchase_sale_date = date('y-m-d', strtotime($parent_data['s_date']));
                                    $seller_json = json_decode($parent_data['seller_json']);
                                    $p_khaata_no = !empty($seller_json) ? $seller_json->khaata_no : '';
                                    $p_khaata = !empty($seller_json) ? khaataSingle($seller_json->khaata_id) : array();
                                    $s_khaata_no = $parent_data['p_khaata_no'];
                                    $s_khaata = khaataSingle($parent_data['p_khaata_no'], true);
                                    $allot = '';
                                    $totals = saleSpecificData($parent_id, 'product_details');
                                    $wh_khaataa = khaataSingle($details['wh_k_id']);
                                    $wh_khaata_details = khaataDetailsData($details['wh_kd_id']);
                                    $warehouse = $wh_khaataa['khaata_no'] . ' ';
                                    $warehouse .= isset($wh_khaata_details['comp_name']) ? $wh_khaata_details['comp_name'] : '';
                                    if ($is_search && $wh_khaata != '') {
                                        if ($wh_khaata != $wh_khaataa['khaata_no']) continue;
                                    }
                                }
                                if (!empty($bail_json)) {
                                    if ($purchase_sale_type == 'booking') {
                                        $container_no = isset($bail_json->container_no) ? $bail_json->container_no : '';
                                    } else {
                                        $container_no = isset($bail_json->truck_no) ? $bail_json->truck_no : '';
                                    }
                                }
                                if ($purchase_sale_type == 'booking') {
                                    if (!empty($bail_json)) {
                                        $bail_no = isset($bail_json->bail_no) ? $bail_json->bail_no : '';
                                    }
                                } else {
                                    if (!empty($ware_json)) {
                                        $loading_wh_khaata = khaataSingle($ware_json->party_khaata_id);
                                        $bail_no = $loading_wh_khaata['khaata_no'] . '<sub>LDG WH.A/C.</sub>';
                                    }
                                }
                                if ($purchase_sale_type == 'booking') {
                                    //get ctr_rec_date from purchase_agents =>import
                                    $purchase_agents_dataa = fetch('purchase_agents', array('d_id' => $d_id, 'type' => 'import'));
                                    if (mysqli_num_rows($purchase_agents_dataa) > 0) {
                                        $purchase_agents_datum = mysqli_fetch_assoc($purchase_agents_dataa);
                                        if (!empty($purchase_agents_datum['details'])) {
                                            $purchase_agents_details = json_decode($purchase_agents_datum['details']);
                                            $ctr_rec_date = $purchase_agents_details->ctr_rec_date;
                                        }
                                    }
                                } else {
                                    if (!empty($bail_json)) {
                                        $ctr_rec_date = $bail_json->receiving_date;
                                    }
                                }
                                if ($is_search) {
                                    if ($goods_id > 0) {
                                        if ($goods_id != $details['goods_id']) continue;
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
                                ++$no; ?>
                                <tr class="text-uppercase text-nowrap font-size-11 <?php echo $rowColor; ?>">
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $label . $parent_id . '-' . $d_sr; ?></td>
                                    <td><?php echo $purchase_sale_date; ?></td>
                                    <!--<td><?php /*echo $p_khaata_no; */?></td>
                                    <td><?php /*echo $s_khaata_no; */?></td>
                                    <td><?php /*echo branchName($parent_data['branch_id']); */?></td>-->
                                    <td><?php echo $container_no; ?></td>
                                    <td><?php echo $bail_no; ?></td>
                                    <td><?php echo $ctr_rec_date; ?></td>
                                    <td><?php echo $allot; ?></td>
                                    <td><?php echo goodsName($details['goods_id']); ?></td>
                                    <td><?php echo $details['size']; ?></td>
                                    <?php if ($source_table == 'purchase') {
                                        echo '<td>' . $details['qty_no'] . '</td>';
                                        echo '<td>0</td>';
                                        $p_qty_total += $details['qty_no'];
                                    } else {
                                        echo '<td>0</td>';
                                        echo '<td>' . $details['qty_no'] . '</td>';
                                        $s_qty_total += $details['qty_no'];
                                    }
                                    if ($source_table == 'purchase') {
                                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                        echo '<td>0</td>';
                                        $p_kgs_total += $details['total_kgs'];
                                    } else {
                                        echo '<td>0</td>';
                                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                        $s_kgs_total += $details['total_kgs'];
                                    } ?>
                                    <!--<td><?php /*echo $warehouse;  */?></td>-->
                                </tr>
                                <?php $row_count++;
                            }
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                    <input type="hidden" id="s_qty_total" value="<?php echo $s_qty_total; ?>">
                    <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                    <input type="hidden" id="s_kgs_total" value="<?php echo $s_kgs_total; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
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

<?php $pageURL = 'agents';
$page_title = 'General Agents Form [Purchase]';
$table_head = array('TYPE', 'T. WAREHOUSE', 'PURCHASER & SELLER', 'GOODS', 'REPORT', 'BAIL', 'IMP AGENT', 'EXP AGENT');
include("header.php");
$remove = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $search_acc = $sale_pur = '';
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
        $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '&start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
    if (isset($_GET['is_transferred'])) {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $pageURL .= '&is_transferred=' . $is_transferred;
    }
    if (isset($_GET['search_acc'])) {
        $search_acc = mysqli_real_escape_string($connect, $_GET['search_acc']);
        $pageURL .= '&search_acc=' . $search_acc;
    }
    if (isset($_GET['sale_pur'])) {
        $sale_pur = mysqli_real_escape_string($connect, $_GET['sale_pur']);
        $pageURL .= '&sale_pur=' . $sale_pur;
    }
}
$sql2 = "SELECT 'purchase' AS source_table,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg
    FROM purchase_details
    UNION 
    SELECT 'sale' AS source_table,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg
    FROM sale_details 
    ORDER BY is_imp_agent";
$query2 = mysqli_query($connect, $sql2); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex table-form text-nowrap align-items-center justify-content-between gap-0">
                <div><b>ROWS:</b><span id="rows_count_span"></span></div>
                <div><b>QTY:</b><span id="p_qty_total_span"></span></div>
                <div><b>KGs:</b><span id="p_kgs_total_span"></span></div>
                <form method="get" class="d-flex align-items-center ">
                    <?php echo searchInput('', 'form-control-sm');
                    echo $remove; ?>
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
                        <select name="search_acc" class="form-select">
                            <option value="" hidden>A/c</option>
                            <?php $accounts_query = fetch('khaata');
                            while ($aa = mysqli_fetch_assoc($accounts_query)) {
                                $sel = $search_acc == $aa['khaata_no'] ? 'selected' : '';
                                echo '<option ' . $sel . ' value="' . $aa['khaata_no'] . '">' . $aa['khaata_no'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="sale_pur" class="form-select">
                            <?php $ss_pp_array = array('All' => '', 'Sale' => 'sale', 'Purchase' => 'purchase');
                            foreach ($ss_pp_array as $item => $value) {
                                $ss_pp_sel = $sale_pur == $value ? 'selected' : '';
                                echo '<option ' . $ss_pp_sel . ' value="' . $value . '">' . $item . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="input-group d-none">
                        <select name="is_transferred" class="form-select">
                            <option value="" hidden>Transfer</option>
                            <?php $tt_array = array('All' => '', 'Transferred' => '1', 'Not Transferred' => '0');
                            foreach ($tt_array as $item => $value) {
                                $tt_sel = $is_transferred == $value ? 'selected' : '';
                                echo '<option ' . $tt_sel . ' value="' . $value . '">' . $item . '</option>';
                            } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="card rounded-0">
                <div class="card-body p-0">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <?php unset($_SESSION['response']); ?>
                    <div class="table-responsive" style="height: 78dvh;">
                        <table class="table mb-0 table-bordered table-sm fix-head-table">
                            <thead>
                            <tr class="text-nowrap">
                                <?php foreach ($table_head as $item) {
                                    echo '<th>' . $item . '</th>';
                                } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                            while ($details = mysqli_fetch_assoc($query2)) {
                                $source_table = $details['source_table'];
                                $d_id = $details['id'];
                                $parent_id = $details['parent_id'];
                                $imp_json = json_decode($details['imp_json']);
                                $exp_json = json_decode($details['exp_json']);
                                $notify_json = json_decode($details['notify_json']);
                                $ware_json = json_decode($details['ware_json']);
                                $tware_json = json_decode($details['tware_json']);
                                $bail_json = json_decode($details['bail_json']);
                                $d_sr = $details['d_sr'];
                                //purchases / sales parent tables data
                                $parent_query = fetch($source_table . 's', array('id' => $parent_id));
                                $parent_data = mysqli_fetch_assoc($parent_query);
                                $purchase_sale_type = $parent_data['type'];
                                if ($source_table == 'purchase') {
                                    if ($parent_data['is_locked'] != 1) continue;
                                    $type_badge = purchaseSpecificData($parent_id, 'purchase_type');
                                    $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                                    $purchase_sale_date = date('y-m-d', strtotime($parent_data['p_date']));
                                    $s_khaata_no = $parent_data['s_khaata_no'];
                                    $s_khaata = khaataSingle($parent_data['s_khaata_id']);
                                    $totals = purchaseSpecificData($parent_id, 'product_details');
                                } else {
                                    $type_badge = saleSpecificData($parent_id, 'sale_type');
                                    $cntrs = saleSpecificData($parent_id, 'sale_rows');
                                    $purchase_sale_date = date('y-m-d', strtotime($parent_data['s_date']));
                                    $seller_json = json_decode($parent_data['seller_json']);
                                    $s_khaata_no = !empty($seller_json) ? $seller_json->khaata_no : '';
                                    $totals = saleSpecificData($parent_id, 'product_details');
                                    $s_khaata = !empty($seller_json) ? khaataSingle($seller_json->khaata_id) : array();
                                }
                                $is_transfer = $details['is_transfer'];
                                $transfer_as = $details['transfer_as'];
                                if ($is_transfer == 0) continue;
                                if ($transfer_as == 0 || $transfer_as > 1) continue; //agent =1 , stock =2;
                                if ($purchase_sale_type == 'market') continue;
                                //if ($parent_data['transfer'] < 2) continue;
                                $is_imp_agent = $details['is_imp_agent'];
                                $is_exp_agent = $details['is_exp_agent'];
                                $imp_agent_message = $exp_agent_message = '';
                                $dummy_var = 0;
                                if ($is_imp_agent == 1) {
                                    $purchase_agent_data = purchase_agent_data($source_table, $d_id, 'import');
                                    if (!empty($purchase_agent_data)) {
                                        ++$dummy_var;
                                        $imp_agent_khaata = khaataSingle($purchase_agent_data['khaata_id']);
                                        $imp_agent_message .= '<b>IMP&nbsp;A/C.</b>' . $imp_agent_khaata['khaata_no'];
                                        $imp_agent_message .= '<b>D.</b>' . date('y-m-d', strtotime($purchase_agent_data['created_at']));
                                        $imp_agent_message .= '<br><b>A/C&nbsp;N</b>' . $imp_agent_khaata['khaata_name'];
                                        $imp_details = json_decode($purchase_agent_data['details']);
                                        if (!empty($imp_details)) {
                                            ++$dummy_var;
                                            $imp_agent_message .= '<br><b>Entry Bill# </b>' . $imp_details->bill_no;
                                            $imp_agent_message .= '<br><b>Container#</b>' . $imp_details->ctr_no;
                                        } else {
                                            $imp_agent_message .= '<br><span class="text-warning bold">PENDING...</span>';
                                        }
                                    }
                                } else {
                                    ++$dummy_var;
                                    ++$dummy_var;
                                    $imp_agent_message .= '<span class="text-danger bold">' . $details['no_imp_agent_msg'] . '</span>';
                                }
                                if ($is_exp_agent == 1) {
                                    $purchase_agent_data_exp = purchase_agent_data($source_table, $d_id, 'export');
                                    if (!empty($purchase_agent_data_exp)) {
                                        ++$dummy_var;
                                        $exp_agent_khaata = khaataSingle($purchase_agent_data_exp['khaata_id']);
                                        $exp_agent_message .= '<b>EXP&nbsp;A/C.</b>' . $exp_agent_khaata['khaata_no'];
                                        $exp_agent_message .= '<b>D.</b>' . date('y-m-d', strtotime($exp_agent_khaata['created_at']));
                                        $exp_agent_message .= '<br><b>A/C&nbsp;N</b>' . $exp_agent_khaata['khaata_name'];
                                        $exp_details = json_decode($purchase_agent_data_exp['details']);
                                        if (!empty($exp_details)) {
                                            ++$dummy_var;
                                            $exp_agent_message .= '<br><b>Entry Bill# </b>' . $exp_details->bill_no;
                                            $exp_agent_message .= isset($exp_details->ctr_no)? '<br><b>Container#</b>' . $exp_details->ctr_no : '';
                                        } else {
                                            $exp_agent_message .= '<br><span class="text-warning bold">PENDING...</span>';
                                        }
                                    }
                                } else {
                                    ++$dummy_var;
                                    ++$dummy_var;
                                    $exp_agent_message .= '<span class="text-danger bold">' . $details['no_exp_agent_msg'] . '</span>';
                                }

                                if ($dummy_var <= 1) {
                                    $rowColor = 'bg-danger bg-opacity-10 border border-secondary';
                                } else {
                                    if ($dummy_var == 2 || $dummy_var == 3) {
                                        $rowColor = 'bg-warning bg-opacity-10 border border-secondary';
                                    } else {
                                        $rowColor = ' ';
                                    }
                                }
                                if ($is_search) {
                                    if ($start != '') {
                                        if ($purchase_sale_date < $start) continue;
                                    }
                                    if ($end != '') {
                                        if ($purchase_sale_date > $end) continue;
                                    }
                                    if ($goods_name != '') {
                                        if ($goods_name != $totals['Goods'][0]) continue;
                                    }
                                    if ($is_transferred != '') {
                                        if ($is_transferred == 1) {
                                            if ($transfer_as <= 0) continue;
                                        }
                                        if ($is_transferred == 0) {
                                            if ($transfer_as > 0) continue;
                                        }
                                    }
                                    if ($search_acc != '') {
                                        if ($search_acc != $s_khaata_no && $search_acc != $parent_data['p_khaata_no']) continue;
                                    }
                                    if ($sale_pur != '') {
                                        if ($sale_pur != $source_table) continue;
                                    }
                                }
                                $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                                $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0; ?>
                                <tr class="text-uppercase <?php echo $rowColor; ?>">
                                    <td class="pointer text-nowrap"
                                        onclick="viewDetails(<?php echo $parent_id; ?>,<?php echo $d_id; ?>,'<?php echo $source_table; ?>')"
                                        data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                        <?php echo $source_table == 'purchase' ? '<b>P#</b>' : '<b>S#</b>';
                                        echo $parent_id . '-' . $d_sr . $type_badge . '<br><span class="font-size-11"><b>D.</b>' . $purchase_sale_date . '</span>';
                                        //echo '$dummy_var= ' . $dummy_var; ?>
                                    </td>
                                    <td class="font-size-10">
                                        <?php if ($source_table == 'purchase') {
                                            if (!empty($tware_json)) {
                                                echo '<span class="text-nowrap"><b>NAME</b>' . $tware_json->comp_name . '</span><br>';
                                                echo '<b>COUNTRY</b>' . $tware_json->country . '<br>';
                                                echo '<b>CITY</b>' . $tware_json->city . '<br>';
                                                echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $tware_json->address . '"><b>ADDR.</b>' . readMore($tware_json->address, '30') . '</span>';
                                            }
                                        } else {
                                            if ($details['wh_kd_id'] > 0) {
                                                $wh_data = khaataDetailsData($details['wh_kd_id']);
                                                echo '<span class="text-nowrap"><b>NAME</b>' . $wh_data['comp_name'] . '</span>';
                                                echo '<br><b>COUNTRY</b>' . countryName($wh_data['country_id']);
                                                echo '<br><b>CITY</b>' . $wh_data['city'];
                                                echo '<br><span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $wh_data['address'] . '"><b>ADDR.</b>' . readMore($wh_data['address'], '30') . '</span>';
                                            }
                                        } ?>
                                    </td>
                                    <td class="font-size-10 text-nowrap">
                                        <?php echo '<b>BRANCH</b>' . branchName($parent_data['branch_id']) . '<br>';
                                        echo '<b>PURCHASE A/c#</b>' . $parent_data['p_khaata_no'] . '<br>';
                                        echo '<b>SELLER A/c#</b>' . $s_khaata_no;
                                        echo isset($s_khaata['khaata_name']) ? '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name'] : ''; ?>
                                    </td>
                                    <td class="font-size-10 text-nowrap">
                                        <?php echo $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . '<br><b>Qty </b>' . $details['qty_no'] . '<br><b>KGs </b>' . $details['total_kgs'] . '<br><b>Goods </b>' . $totals['Goods'][0] : ''; ?>
                                    </td>
                                    <td class="font-size-10">
                                        <?php if ($source_table == 'purchase') {
                                            $details_k = ['indexes' => $parent_data['rep_indexes'], 'vals' => $parent_data['rep_vals']];
                                            $reps = displayKhaataDetails($details_k, true);
                                            if (array_key_exists('Final', $reps)) {
                                                echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $reps['Final'] . '">' . readMore($reps['Final'], '40') . '</span>';
                                            }
                                        } else {
                                            echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $parent_data['report'] . '">' . readMore($parent_data['report'], '40') . '</span>';
                                        } ?>
                                    </td>
                                    <td class="font-size-10 text-nowrap">
                                        <?php if (!empty($bail_json)) {
                                            if ($purchase_sale_type == 'booking') {
                                                echo '<b>CONTAINER#</b>' . $bail_json->container_no . '<br><b>BAIL#</b>' . $bail_json->bail_no . '<br><b>LOADING D.</b>' . $bail_json->loading_date . '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                            } else {
                                                echo isset($bail_json->driver_name) ? '<b>D.Name </b>' . $bail_json->driver_name : '';
                                                echo isset($bail_json->driver_phone) ? '<br><b>D.Phone </b>' . $bail_json->driver_phone : '';
                                                echo '<br><b>LOADING D.</b>' . $bail_json->loading_date . '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                            }
                                        } ?>
                                    </td>
                                    <td class="font-size-10 text-nowrap"><?php echo $imp_agent_message; ?></td>
                                    <td class="font-size-10 text-nowrap"><?php echo $exp_agent_message; ?></td>
                                </tr>
                                <?php ++$row_count;
                            } ?>
                            </tbody>
                        </table>
                        <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                        <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                        <input type="hidden" id="p_kgs_total" value="<?php echo round($p_kgs_total); ?>">
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
    </script>
    <script>
        function viewDetails(id = null, d_id = null, source = null) {
            if (id) {
                $.ajax({
                    url: 'ajax/viewSingleAgent.php',
                    type: 'post',
                    data: {id: id, d_id: d_id, source: source},
                    success: function (response) {
                        $('#viewDetails').html(response);
                        $('#staticBackdropLabel').prepend(source.toUpperCase());
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                        alert('An error occurred. Please try again.');
                    }
                });
            } else {
                console.error('Invalid arguments for viewDetails function.');
                alert('An error occurred. Please try again.');
            }
        }
    </script>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> GENERAL AGENT</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
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
                    <h5 class="modal-title" id="secondModalLabel">Agent</h5>
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
<?php if (isset($_GET['source']) && isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['d_id']) && is_numeric($_GET['d_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $source = mysqli_real_escape_string($connect, $_GET['source']);
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $d_id = mysqli_real_escape_string($connect, $_GET['d_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewDetails($id,$d_id,'$source'); });</script>";
    if (isset($_GET['secondModal']) && $_GET['secondModal'] == 1) {
        echo "<script>jQuery(document).ready(function ($) {  $('#secondModal').modal('show');});</script>";
    }
}
if (isset($_POST['saveAgentPLoading'])) {
    $msg = 'DB Errro :(';
    $msg_type = 'danger';
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $id_hidden = mysqli_real_escape_string($connect, $_POST['id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    $source = mysqli_real_escape_string($connect, $_POST['source_hidden']);
    $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
    $urll = $pageURL;
    $data = array('type' => $type, 'd_id' => $d_id_hidden, 'khaata_id' => $khaata_id);
    $dd = fetch('purchase_agents', array('pur_sale' => $source, 'type' => $type, 'd_id' => $d_id_hidden, 'khaata_id' => $khaata_id));/*maybe comment , 'khaata_id' => $khaata_id*/
    if (mysqli_num_rows($dd) > 0) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $_SESSION['userId'];
        $done = update('purchase_agents', $data, array('pur_sale' => $source, 'type' => $type, 'd_id' => $d_id_hidden));
    } else {
        $pa_sr = getPurchaseAgentSerial($khaata_id);
        $data['a_sr'] = $pa_sr;
        $data['pur_sale'] = $source;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $_SESSION['userId'];
        $done = insert('purchase_agents', $data);
    }
    if ($done) {
        $urll .= '?source=' . $source . '&id=' . $id_hidden . '&d_id=' . $d_id_hidden . '&view=1';
        $msg = ucfirst($type) . " Agent saved successfully.";
        $msg_type = "success";
    }
    message($msg_type, $urll, $msg);
}
if (isset($_POST['noAgentSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id_hidden = mysqli_real_escape_string($connect, $_POST['id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    $source = mysqli_real_escape_string($connect, $_POST['source_hidden']);
    $agent_msg = mysqli_real_escape_string($connect, $_POST['msg']);
    $agent_type = mysqli_real_escape_string($connect, $_POST['agent_type']);
    $col_value = mysqli_real_escape_string($connect, $_POST['col_value']);
    $col_value_new = $col_value == 0 ? 1 : 0;
    if ($agent_type == 'imp') {
        $data = array('is_imp_agent' => $col_value_new, 'no_imp_agent_msg' => $agent_msg);
    } else {
        $data = array('is_exp_agent' => $col_value_new, 'no_exp_agent_msg' => $agent_msg);
    }
    if ($source == 'purchase') {
        $locked = update('purchase_details', $data, array('id' => $d_id_hidden));
    } else {
        $locked = update('sale_details', $data, array('id' => $d_id_hidden));
    }
    $URL = $pageURL . '?source=' . $source . '&id=' . $id_hidden . '&d_id=' . $d_id_hidden . '&view=1';
    if ($locked) {
        $type = 'success';
        $msg = strtoupper($agent_type) . ' Agent attributes saved. ';
    }
    message($type, $URL, $msg);
} ?>
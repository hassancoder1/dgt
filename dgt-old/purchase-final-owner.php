<?php $page_title = 'Purchase Owner Form';
$pageURL = 'purchase-final-owner';
include("header.php");
global $connect;
$remove = $ps_type = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $s_khaata_id = $adv_full = '';
$is_search = false;
$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2  "; ?>
<!--transfer = 2 mean payment is complete. Either transferred to Full or transferred from Advance to full-->
<?php if ($_GET) {
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
    if (isset($_GET['ps_type'])) {
        $ps_type = mysqli_real_escape_string($connect, $_GET['ps_type']);
        $pageURL .= '&ps_type=' . $ps_type;
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
        <div class="d-flex align-items-center justify-content-between gap-0">
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
            <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
            <form method="get" class="d-flex align-items-center table-form text-nowrap-">
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <?php echo $remove; ?>
                <input type="date" name="start" value="<?php echo $start; ?>" class="form-control">
                <input type="date" name="end" value="<?php echo $end; ?>" class="form-control">
                <?php $type_array = array('booking', 'local'); ?>
                <select id="type" name="ps_type" class="form-select">
                    <option value="">ALL</option>
                    <?php foreach ($type_array as $item) {
                        $sel_ps_type = $ps_type == $item ? 'selected' : '';
                        echo '<option ' . $sel_ps_type . ' value="' . $item . '">' . ucfirst($item) . '</option>';
                    } ?>
                </select>
                <div class="input-group">
                    <select name="s_khaata_id" class="form-select">
                        <option value="" hidden>Seller A/c</option>
                        <?php $accounts_query = fetch('khaata');
                        while ($aa = mysqli_fetch_assoc($accounts_query)) {
                            $sel = $s_khaata_id == $aa['id'] ? 'selected' : '';
                            echo '<option ' . $sel . ' value="' . $aa['id'] . '">' . $aa['khaata_no'] . '</option>';
                        } ?>
                    </select>
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

                <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? ''; ?>
                <?php unset($_SESSION['response']); ?>
                <div class="table-responsive">
                    <table class="table mb-0 table-bordered -border-dark">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>PURCHASER & SELLER</th>
                            <th>GOODS</th>
                            <th>BAIL</th>
                            <th>IMPORTER</th>
                            <th>EXPORTER</th>
                            <th>NOTIFY PARTY</th>
                            <th>WAREHOUSE</th>
                            <th>P. AMT</th>
                            <th>IMPORT BILL</th>
                            <th>EXPORT BILL</th>
                            <th>T. BILL</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $final_bill  = $p_qty_total = $p_kgs_total = 0;
                        $purchase_details_query = fetch('purchase_details');
                        //echo 'PD Rows: ' . mysqli_num_rows($purchase_details_query);
                        while ($details = mysqli_fetch_assoc($purchase_details_query)) {
                            $pd_id = $details['id'];
                            $pd_sr = $details['d_sr'];
                            $purchase_id = $details['parent_id'];
                            if ($details['is_transfer'] == 0) continue; // transferred from loading form to Transfer Form
                            /* purchase-transfer => Stock=2 | Agent=1
                             * Show if $details['transfer_as'] =2
                             * OR  if $details['transfer_as'] = 1 then check purchase_agents table
                             * Show if details colum in not NULL in purchase_agents table */
                            if ($details['transfer_as'] == 0) {
                                continue;
                            } elseif ($details['transfer_as'] == 1) {
                                $condo = fetch('purchase_agents', array('d_id' => $pd_id));
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
                            $imp_json = json_decode($details['imp_json']);
                            $exp_json = json_decode($details['exp_json']);
                            $notify_json = json_decode($details['notify_json']);
                            $ware_json = json_decode($details['ware_json']);
                            $tware_json = json_decode($details['tware_json']); //local purchase
                            $aware_json = json_decode($details['aware_json']); //booking purchase
                            $bail_json = json_decode($details['bail_json']);
                            $totals = purchaseSpecificData($purchase_id, 'product_details');
                            $purchases_query = fetch('purchases', array('id' => $purchase_id, 'is_locked' => 1, 'transfer' => 2));
                            //$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2  ";
                            if (mysqli_num_rows($purchases_query) > 0) {
                                $purchase = mysqli_fetch_assoc($purchases_query);
                                $purchase_type = $purchase['type'];
                                $p_khaata = khaataSingle($purchase['p_khaata_id']);
                                $s_khaata = khaataSingle($purchase['s_khaata_id']);
                                if ($is_search) {
                                    if ($start != '') {
                                        if ($purchase['p_date'] < $start) continue;
                                    }
                                    if ($end != '') {
                                        if ($purchase['p_date'] > $end) continue;
                                    }
                                    if ($goods_name != '') {
                                        if ($goods_name != $totals['Goods'][0]) continue;
                                    }
                                    if ($ps_type != '') {
                                        if ($purchase_type != $ps_type) continue;
                                    }
                                    if ($s_khaata_id != '') {
                                        if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                                    }

                                }
                                $p_qty_total += $totals['Qty'];
                                $p_kgs_total += $totals['KGs'];
                                ?>
                                <tr class="pointer text-uppercase text-nowrap"
                                    onclick="viewPurchase(<?php echo $purchase_id; ?>,<?php echo $pd_id; ?>)"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <td class="text-nowrap">
                                        <?php //$ctr_no = $count_details > 1 ? '-' . $pd_count : '';
                                        echo '<b>P#</b>' . $purchase_id . '-' . $pd_sr . '<br>';
                                        echo purchaseSpecificData($purchase_id, 'purchase_type');
                                        echo '<br><span class="font-size-11"><b>D.</b>' . $purchase['p_date'] . '</span>';
                                        //echo $transfer_as_msg;  ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap">
                                        <?php echo '<b>BRANCH</b>' . branchName($purchase['branch_id']) . '<br>';
                                        echo '<b>PURCHASE A/c#</b>' . $purchase['p_khaata_no'] . '<br>';
                                        echo '<b>SELLER A/c#</b>' . $purchase['s_khaata_no'];
                                        echo '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name'];
                                        //echo $s_khaata['comp_name']; ?>
                                    </td>
                                    <td class="font-size-11 text-nowrap">
                                        <?php $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                                        if ($cntrs > 0) {
                                            echo '<b>ITEMS. </b>' . $cntrs . ' <b>Qty</b>' . $details['qty_no'];
                                            echo '<br><b>KGs</b>' . $details['total_kgs'];
                                            echo '<br><b>Size </b>' . $totals['Size'][0];
                                            echo '<br><b>Goods </b>' . $totals['Goods'][0];
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($bail_json)) {
                                            if ($purchase_type == 'booking') {
                                                echo isset($bail_json->container_no) ? '<b>CONTAINER#</b>' . $bail_json->container_no : '';
                                            } else {
                                                echo isset($bail_json->truck_no) ? '<b>TRUCK#</b>' . $bail_json->truck_no : '';
                                            }
                                            if ($purchase_type == 'booking') {
                                                if (!empty($bail_json)) {
                                                    echo isset($bail_json->bail_no) ? '<br><b>BAIL#</b>' . $bail_json->bail_no : '';
                                                }
                                            } else {
                                                if (!empty($ware_json)) {
                                                    $loading_wh_khaata = khaataSingle($ware_json->party_khaata_id);
                                                    echo '<b>LDG WH.A/C#</b>' . $loading_wh_khaata['khaata_no'];
                                                }
                                            }
                                            echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                            if ($purchase_type == 'booking') {
                                                //get ctr_rec_date from purchase_agents =>import
                                                $purchase_agents_dataa = fetch('purchase_agents', array('d_id' => $pd_id, 'type' => 'import'));
                                                if (mysqli_num_rows($purchase_agents_dataa) > 0) {
                                                    $purchase_agents_datum = mysqli_fetch_assoc($purchase_agents_dataa);
                                                    if (!empty($purchase_agents_datum['details'])) {
                                                        $purchase_agents_details = json_decode($purchase_agents_datum['details']);
                                                        echo '<br><b>CTR RECEIVE D.</b>' . $purchase_agents_details->ctr_rec_date;
                                                    }
                                                }
                                            } else {
                                                if (!empty($bail_json)) {
                                                    echo '<br><b>RECEIVE D.</b>' . $bail_json->receiving_date;
                                                }
                                            }
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($imp_json)) {
                                            echo '<b>COMPANY</b>' . $imp_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $imp_json->country;
                                            echo '<br><b>CITY</b>' . $imp_json->city;
                                            echo '<br><b>ADDRESS</b>' . readMore($imp_json->address, '25');
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($exp_json)) {
                                            echo '<b>COMPANY</b>' . $exp_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $exp_json->country;
                                            echo '<br><b>CITY</b>' . $exp_json->city;
                                            echo '<br><b>ADDRESS</b>' . readMore($exp_json->address, '25');
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if (!empty($notify_json)) {
                                            echo '<b>COMPANY</b>' . $notify_json->comp_name;
                                            echo '<br><b>COUNTRY</b>' . $notify_json->country;
                                            echo '<br><b>CITY</b>' . $notify_json->city;
                                            echo '<br><b>ADDRESS</b>' . readMore($notify_json->address, '25');
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if ($purchase_type == 'booking') {
                                            if (!empty($aware_json)) {
                                                $agent_wh_khaata = khaataSingle($aware_json->party_khaata_id);
                                                echo $agent_wh_khaata['khaata_no'] . '<br>' . $agent_wh_khaata['khaata_name'];
                                            }
                                        } else {
                                            if (!empty($tware_json)) {
                                                $transfer_wh_khaata = khaataSingle($tware_json->party_khaata_id);
                                                echo $transfer_wh_khaata['khaata_no'] . '<br>' . $transfer_wh_khaata['khaata_name'];
                                            }
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php if ($cntrs > 0) {
                                            echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                            echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                            $final_bill += $totals['Final'];
                                            echo !empty($purchase['t_date']) ? '<br><b>Transfer D.</b>' . $purchase['t_date'] : '';
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php $p_agent_imp = purchase_agent_data('purchase',$pd_id, 'import');
                                        if (!empty($p_agent_imp)) {
                                            $import_agent_ka_bill = json_decode($p_agent_imp['bill']);
                                            if (!empty($import_agent_ka_bill)) {
                                                echo '<b>ITEMS </b>' . count($import_agent_ka_bill->exp_qtys);
                                                echo '<br><b>VALUE </b>' . round($import_agent_ka_bill->amount);
                                                if (isset($import_agent_ka_bill->final_amount)){
                                                    echo '<br><b>FINAL </b>' . round($import_agent_ka_bill->final_amount);
                                                    $final_bill += $import_agent_ka_bill->final_amount;
                                                }
                                            }
                                        } ?>
                                    </td>
                                    <td class="font-size-11">
                                        <?php $p_agent_exp = purchase_agent_data('purchase',$pd_id, 'export');
                                        if (!empty($p_agent_exp)) {
                                            $export_agent_ka_bill = json_decode($p_agent_exp['bill']);
                                            if (!empty($export_agent_ka_bill)) {
                                                echo '<b>ITEMS </b>' . count($export_agent_ka_bill->exp_qtys);
                                                echo '<br><b>VALUE </b>' . round($export_agent_ka_bill->amount);
                                                if (isset($export_agent_ka_bill->final_amount)){
                                                    echo '<br><b>FINAL </b>' . round($export_agent_ka_bill->final_amount);
                                                    $final_bill += $export_agent_ka_bill->final_amount;
                                                }
                                            }
                                        } ?>
                                    </td>
                                    <td><?php echo $final_bill; ?></td>
                                </tr>
                                <?php $row_count++;
                            }
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                    <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
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
                <a href="purchase-final-owner" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
</script>

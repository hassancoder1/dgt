<?php $page_title = 'Warehouse Details';
$pageURL = 'warehouse-details';
include("header.php");
$k_id = 0;
if (isset($_GET['k_id']) && is_numeric($_GET['k_id'])) {
    $k_id = mysqli_real_escape_string($connect, $_GET['k_id']);
} ?>

<div class="row table-form">
    <div class="col-md-3">
        <form method="get" class="d-flex ">
            <select id="k_id" name="k_id" class="v-select-sm" required>
                <?php echo $k_id > 0 ? '' : '<option value="" disabled selected></option>';
                $kdss = mysqli_query($connect, "SELECT DISTINCT (khaata_id) FROM `khaata_details`");
                //$kdss = fetch('khaata_details', array('static_type' => 'Warehouse'));
                while ($kh = mysqli_fetch_assoc($kdss)) {
                    $k_idd = $kh['khaata_id'];
                    $selected_khaata = khaataSingle($k_idd);
                    $sel = $k_id == $k_idd ? 'selected' : '';
                    echo '<option ' . $sel . ' value="' . $k_idd . '">' . $selected_khaata['khaata_no'] . '</option>';
                } ?>
            </select>
            <!--<select id="party_kd_id" name="kd_id" class="form-select"><option hidden value="">Select</option></select>-->
            <button class="btn btn-secondary py-0 rounded-0"><i class="fa fa-search"></i></button>
        </form>
        <div class="d-flex align-items-center justify-content-between">
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <div><b>QTY: </b><span id="total_qty_span"></span></div>
            <div><b>KGs: </b><span id="total_kgs_span"></span></div>
        </div>
        <?php echo searchInput('a', 'form-control-sm'); ?>
    </div>
    <div class="col-md-9">
        <div class="card rounded-0 position-relative small mb-1">
            <div class="info-div">Account</div>
            <?php if ($k_id > 0) {
                $selected_khaata = khaataSingle($k_id); ?>
                <div class="d-flex justify-content-between p-1 gap-1">
                    <div class="text-nowrap">
                        <b>A/C#</b> <?php echo $selected_khaata['khaata_no'] . '<br>'; ?>
                        <b>A/C NAME</b><?php echo $selected_khaata['khaata_name'] . '<br>'; ?>
                        <b>B.</b> <?php echo branchName($selected_khaata['branch_id']); ?>
                        <b>CAT.</b> <?php echo catName($selected_khaata['cat_id']); ?>
                    </div>
                    <div>
                        <b>B. NAME</b> <?php echo $selected_khaata['business_name'] . '<br>'; ?>
                        <b>ADD.</b> <?php echo $selected_khaata['address'] . '<br>'; ?>
                        <b>COMP.</b> <?php echo $selected_khaata['comp_name']; ?>
                    </div>
                    <div class="text-nowrap">
                        <?php $selected_khaata_details = ['indexes' => $selected_khaata['indexes'], 'vals' => $selected_khaata['vals']];
                        echo displayKhaataDetails($selected_khaata_details);
                        $contacts = displayKhaataDetails($selected_khaata_details, true);
                        /*echo array_key_exists('Phone', $contacts) ? '<b>P.</b> ' . $contacts['Phone'] . '<br>' : '';
                        echo array_key_exists('WhatsApp', $contacts) ? '<b>WA.</b> ' . $contacts['WhatsApp'] . '<br>' : '';
                        echo array_key_exists('Email', $contacts) ? '<b>E.</b> ' . $contacts['Email'] : '';*/ ?>
                    </div>
                    <div>
                        <img id="khaata_image" src="<?php echo $selected_khaata['image']; ?>"
                             class="avatar-lg rounded shadow" alt="Image">
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? ''; ?>
                <?php unset($_SESSION['response']); ?>
                <div class="table-responsive">
                    <table class="table mb-0 table-bordered -border-dark">
                        <thead>
                        <tr class="text-nowrap">
                            <th>SR#</th>
                            <th>P.DATE</th>
                            <th>P.A/C</th>
                            <th>S.A/C</th>
                            <th>Br.</th>
                            <th>CONTAINER#</th>
                            <th>BAIL#</th>
                            <th>CTR RECV.</th>
                            <th>ALLOT</th>
                            <th>GOODS</th>
                            <th>SIZE</th>
                            <th>QTY</th>
                            <th>T.KGs</th>
                            <!--<th>WAREHOUSE</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $total_qty = $total_kgs = 0;
                        $purchase_details_query = fetch('purchase_details');
                        //echo 'PD Rows: ' . mysqli_num_rows($purchase_details_query);
                        $warehouse_td = '';
                        while ($details = mysqli_fetch_assoc($purchase_details_query)) {
                            $warehouse_khaata_ids = array();
                            $pd_id = $details['id'];
                            $pd_sr = $details['d_sr'];
                            $purchase_id = $details['parent_id'];
                            //if ($details['is_transfer'] == 0) continue; // transferred from loading form to Transfer Form
                            /* purchase-transfer => Stock=2 | Agent=1
                             * Show if $details['transfer_as'] =2
                             * OR  if $details['transfer_as'] = 1 then check purchase_agents table
                             * Show if details colum in not NULL in purchase_agents table */
                            /*if ($details['transfer_as'] == 0) {
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
                            }*/

                            $ware_json = json_decode($details['ware_json']);
                            $tware_json = json_decode($details['tware_json']); //local purchase
                            $aware_json = json_decode($details['aware_json']); //booking purchase


                            $bail_json = json_decode($details['bail_json']);
                            $totals = purchaseSpecificData($purchase_id, 'product_details');
                            $purchases_query = fetch('purchases', array('id' => $purchase_id, 'is_locked' => 1));
                            //$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2  ";
                            if (mysqli_num_rows($purchases_query) > 0) {
                                $purchase = mysqli_fetch_assoc($purchases_query);
                                $purchase_type = $purchase['type'];

                                if (!empty($ware_json)) {
                                    $warehouse_khaata_ids[] = $ware_json->party_khaata_id;
                                    //$wh_khaata = khaataSingle($ware_json->party_khaata_id);
                                }
                                if ($purchase_type == 'booking') {
                                    if (!empty($aware_json)) {
                                        $warehouse_khaata_ids[] = $aware_json->party_khaata_id;
                                        $agent_wh_khaata = khaataSingle($aware_json->party_khaata_id);
                                        $warehouse_td = $agent_wh_khaata['khaata_no'] . ' ' . $agent_wh_khaata['khaata_name'];
                                    }
                                } else {
                                }
                                if (!empty($tware_json)) {
                                    $warehouse_khaata_ids[] = $tware_json->party_khaata_id;
                                    $transfer_wh_khaata = khaataSingle($tware_json->party_khaata_id);
                                    $warehouse_td = '<b>A/C#</b>' . $transfer_wh_khaata['khaata_no'] . ' <b>A/C Name</b>' . $transfer_wh_khaata['khaata_name'];
                                }

                                if (in_array($k_id, $warehouse_khaata_ids)) {
                                    //echo '<tr><td colspan="5">' . $k_id . ' found here.</td></tr>';
                                } else {
                                    continue;
                                }
                                //echo '<pre>';print_r($warehouse_khaata_ids);
                                $p_khaata = khaataSingle($purchase['p_khaata_id']);
                                $s_khaata = khaataSingle($purchase['s_khaata_id']); ?>
                                <tr class="pointer text-uppercase text-nowrap font-size-11 <?php //echo $rowColor; ?>"
                                    onclick="viewPurchase(<?php echo $purchase_id; ?>,<?php echo $pd_id; ?>)"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <td class="text-nowrap">
                                        <?php //$ctr_no = $count_details > 1 ? '-' . $pd_count : '';
                                        echo '<b>P#</b>' . $purchase_id . '-' . $pd_sr . '<br>'; ?>
                                    </td>
                                    <td class="text-nowrap"><?php echo date('y-m-d', strtotime($purchase['p_date'])); ?></td>
                                    <td><?php echo $purchase['p_khaata_no']; ?></td>
                                    <td><?php echo $purchase['s_khaata_no']; ?></td>
                                    <td class=""><?php echo branchName($purchase['branch_id']); ?></td>
                                    <td>
                                        <?php if (!empty($bail_json)) {
                                            if ($purchase_type == 'booking') {
                                                echo isset($bail_json->container_no) ? $bail_json->container_no : '';
                                            } else {
                                                echo isset($bail_json->truck_no) ? $bail_json->truck_no : '';
                                            }
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if ($purchase_type == 'booking') {
                                            if (!empty($bail_json)) {
                                                echo isset($bail_json->bail_no) ? $bail_json->bail_no : '';
                                            }
                                        } else {
                                            if (!empty($ware_json)) {
                                                $loading_wh_khaata = khaataSingle($ware_json->party_khaata_id);
                                                echo '<sub>LDG WH.A/C.</sub>' . $loading_wh_khaata['khaata_no'];
                                            }
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if ($purchase_type == 'booking') {
                                            //get ctr_rec_date from purchase_agents =>import
                                            $purchase_agents_dataa = fetch('purchase_agents', array('d_id' => $pd_id, 'type' => 'import'));
                                            if (mysqli_num_rows($purchase_agents_dataa) > 0) {
                                                $purchase_agents_datum = mysqli_fetch_assoc($purchase_agents_dataa);
                                                if (!empty($purchase_agents_datum['details'])) {
                                                    $purchase_agents_details = json_decode($purchase_agents_datum['details']);
                                                    echo $purchase_agents_details->ctr_rec_date;
                                                }
                                            }
                                        } else {
                                            if (!empty($bail_json)) {
                                                echo $bail_json->receiving_date;
                                            }
                                        } ?>
                                    </td>
                                    <td><?php echo $purchase['allot']; ?></td>
                                    <td class="small text-nowrap">
                                        <?php $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                                        echo $cntrs > 0 ? $totals['Goods'][0] : ''; ?>
                                    </td>
                                    <td class="small"><?php echo $cntrs > 0 ? $totals['Size'][0] : ''; ?></td>
                                    <td>
                                        <?php if ($cntrs > 0) {
                                            echo $details['qty_no'];
                                            $total_qty += $details['qty_no'];
                                        } ?>
                                    </td>
                                    <td><?php if ($cntrs > 0) {
                                            echo $details['total_kgs'];
                                            $total_kgs += $details['total_kgs'];
                                        } ?></td>
                                    <td class="font-size-11"><?php echo $warehouse_td; ?></td>
                                </tr>
                                <?php $row_count++;
                            }
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="total_qty" value="<?php echo $total_qty; ?>">
                    <input type="hidden" id="total_kgs" value="<?php echo $total_kgs; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#total_qty_span").text($("#total_qty").val());
    $("#total_kgs_span").text($("#total_kgs").val());
    var party_khaata_id_selected = $('#party_khaata_id').find(":selected").val();
    impKhaata(party_khaata_id_selected);
    console.log('party_khaata_id_selected=' + party_khaata_id_selected);
    $("#party_khaata_id").change(function () {
        impKhaata($(this).val());
    });

    //$('#khaata_details_form').hide();

    function impKhaata(party_khaata_id) {
        //$('#party_details').html('');
        //$('#khaata_id_hidden').val(party_khaata_id);
        $.ajax({
            type: 'POST',
            url: 'ajax/fetchKhaataDetailsDropdown.php',
            data: {khaata_id: party_khaata_id},
            success: function (html) {
                //console.log(party_khaata_id);
                /*if (Number(party_khaata_id) > 0) {
                    $('#khaata_details_form').show();
                } else {
                    $('#khaata_details_form').hide();
                }*/

                $('#party_kd_id').html(html);
                /*var party_kd_id2 = $('#party_kd_id').find(":selected").val();
                if (party_kd_id2 > 0) {
                    impKhaataDetails(party_kd_id2);
                }*/
            }
        });
    }
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

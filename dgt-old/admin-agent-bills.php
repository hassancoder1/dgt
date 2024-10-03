<?php $page_title = 'Admin Agent Bills ';
$pageURL = 'admin-agent-bills';
include("header.php");
//$sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2  ";
$remove = $agent_khaata_id = $start = $end = $imp_exp = $is_complete = $is_transferred = '';
$is_search = false;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['agent_khaata_id'])) {
        $agent_khaata_id = mysqli_real_escape_string($connect, $_GET['agent_khaata_id']);
        $pageURL .= '?agent_khaata_id=' . $agent_khaata_id;
    }
    if (isset($_GET['start'])) {
        $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '&start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
    if (isset($_GET['imp_exp'])) {
        $imp_exp = mysqli_real_escape_string($connect, $_GET['imp_exp']);
        $pageURL .= '&imp_exp=' . $imp_exp;
    }
    if (isset($_GET['is_complete'])) {
        $is_complete = mysqli_real_escape_string($connect, $_GET['is_complete']);
        $pageURL .= '&is_complete=' . $is_complete;
    }
    if (isset($_GET['is_transferred'])) {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $pageURL .= '&is_transferred=' . $is_transferred;
    }
} ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between">
            <form method="get" class="d-flex align-items-center table-form flex-fill text-nowrap">
                <div>
                    <b>ROWS </b><span id="numRows_span"></span>
                </div>
                <?php echo $remove; ?>
                <div>
                    <input class="form-control" type="date" name="start" value="<?php echo $start; ?>">
                </div>
                <div>
                    <input class="form-control" type="date" name="end" value="<?php echo $end; ?>">
                </div>
                <div class="input-group">
                    <select class="form-select" name="imp_exp">
                        <option value="">All</option>
                        <?php $imp_exp_array = array('import', 'export');
                        foreach ($imp_exp_array as $item) {
                            $sel_imp = $imp_exp == $item ? 'selected' : '';
                            echo '<option ' . $sel_imp . ' value="' . $item . '">' . strtoupper($item) . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="input-group">
                    <select class="form-select" name="is_complete">
                        <option value="">All</option>
                        <?php $imp_exp_array = array(1 => 'complete', 0 => 'incomplete');
                        foreach ($imp_exp_array as $item => $value) {
                            $sel_comp = $is_complete == $item ? 'selected' : '';
                            echo '<option ' . $sel_comp . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                        } ?>
                    </select>
                </div>
                <?php if ($is_complete == 1) { ?>
                    <div class="input-group">
                        <select class="form-select" name="is_transferred">
                            <option value="">All</option>
                            <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                            foreach ($imp_exp_array as $item => $value) {
                                $sel_tran = $is_transferred == $item ? 'selected' : '';
                                echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                            } ?>
                        </select>
                    </div>
                <?php } ?>
                <div class="input-group">
                    <select name="agent_khaata_id" class="form-select">
                        <option value="" hidden>Agent A/c</option>
                        <?php $select_purchase_agents = mysqli_query($connect, "SELECT DISTINCT khaata_id FROM `purchase_agents`");
                        while ($aa = mysqli_fetch_assoc($select_purchase_agents)) {
                            $select_agent_ac = khaataSingle($aa['khaata_id']);
                            $sel = $agent_khaata_id == $aa['khaata_id'] ? 'selected' : '';
                            echo '<option ' . $sel . ' value="' . $aa['khaata_id'] . '">' . $select_agent_ac['khaata_no'] . '</option>';
                        } ?>
                    </select>
                </div>
                <button class="btn btn-sm btn-secondary">search</button>
                <div class="input-group">
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
            </form>
            <form action="print/admin-agent-bills" target="_blank" method="get">
                <input type="hidden" name="agent_khaata_id" value="<?php echo $agent_khaata_id; ?>">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                <input type="hidden" name="imp_exp" value="<?php echo $imp_exp; ?>">
                <input type="hidden" name="is_complete" value="<?php echo $is_complete; ?>">
                <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                <button class="btn btn-sm btn-success">PRINT</button>
            </form>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? ''; ?>
                <?php unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 75dvh;">
                    <table class="table table-bordered mb-0 fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>TYPE</th>
                            <th>AGENT DESC.</th>
                            <th>GOODS</th>
                            <th>PURCHASE / SALE</th>
                            <th>BAIL DESC.</th>
                            <th>CUSTOM ENTRY DESC.</th>
                            <th>BILL DESC.</th>
                            <th>TRANSFER</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $pas = mysqli_query($connect, "SELECT * FROM `purchase_agents`  ORDER BY bill");
                        $numRows = 0;
                        while ($pa = mysqli_fetch_assoc($pas)) {
                            //if (empty($pa['bill'])) continue;
                            $purchase_agents_type = $pa['type'];
                            $pur_sale = $pa['pur_sale'];
                            $purchase_agents_id = $pa['id'];
                            $pa_sr = $pa['a_sr'];
                            $khaata_id = $pa['khaata_id'];

                            $agent_khaata = khaataSingle($khaata_id);
                            $d_id = $pa['d_id'];
                            $bill = json_decode($pa['bill']);

                            $pd_query = fetch($pur_sale . '_details', array('id' => $d_id));
                            $details = mysqli_fetch_assoc($pd_query);
                            $d_sr = $details['d_sr'];
                            $parent_id = $details['parent_id'];
                            $bail_json = json_decode($details['bail_json']);
                            $parent_query = fetch($pur_sale . 's', array('id' => $parent_id));
                            $parent_data = mysqli_fetch_assoc($parent_query);
                            if ($pur_sale == 'purchase') {
                                if ($parent_data['is_locked'] != 1) continue;
                                $ps_date = $parent_data['p_date'];
                                $ps_type = purchaseSpecificData($parent_id, 'purchase_type');
                                $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                                $p_khaata = khaataSingle($parent_data['p_khaata_id']);
                                $p_khaata_no = $parent_data['p_khaata_no'];
                            } else {
                                $ps_date = $parent_data['s_date'];
                                $ps_type = saleSpecificData($parent_id, 'sale_type');
                                $cntrs = saleSpecificData($parent_id, 'sale_rows');
                                $p_khaata = khaataSingle($parent_data['s_khaata_no'], true);
                                $p_khaata_no = $parent_data['s_khaata_no'];
                            }
                            if ($parent_data['type'] == 'booking' && $parent_data['transfer'] == 2) {
                            } else {
                                continue;
                            }
                            if ($is_search) {
                                if ($agent_khaata_id != '') {
                                    if ($agent_khaata_id != $khaata_id) continue;
                                }
                                if ($imp_exp != '') {
                                    if ($imp_exp != $purchase_agents_type) continue;
                                }
                                if ($is_complete != '') {
                                    if ($is_complete == 1) {
                                        if (empty($bill)) continue;
                                        if ($is_transferred != '') {
                                            if ($is_transferred == 1) {
                                                if (!isset($bill->p_id_hidden)) continue;
                                            }
                                            if ($is_transferred == 0) {
                                                if (isset($bill->p_id_hidden)) continue;
                                            }
                                        }
                                    }
                                    if ($is_complete == 0) {
                                        if (!empty($bill)) continue;
                                    }
                                }
                                if ($start != '') {
                                    if ($ps_date < $start) continue;
                                }
                                if ($end != '') {
                                    if ($ps_date > $end) continue;
                                }
                            }

                            $rowColor = '';
                            if (empty($bill)) {
                                $rowColor = 'bg-danger bg-opacity-25';
                            } else {
                                if (isset($bill->p_id_hidden)) {
                                } else {
                                    $rowColor = 'bg-warning bg-opacity-25';
                                }
                            } ?>
                            <tr class="text-uppercase <?php echo $rowColor; ?>">
                                <td class="pointer text-nowrap" data-bs-toggle="modal" data-bs-target="#KhaataDetails"
                                    onclick="viewPurchase(<?php echo $parent_id; ?>,<?php echo $d_id; ?>,<?php echo $purchase_agents_id; ?>,<?php echo $khaata_id; ?>,'<?php echo $pur_sale; ?>')">
                                    <?php echo $pur_sale == 'purchase' ? '<b>P#</b>' : '<b>S#</b> ';
                                    echo '[' . $pa_sr . '] ' . $parent_id . '-' . $d_sr . '<br>';
                                    echo '<span class="badge bg-danger">' . $purchase_agents_type . '</span>';
                                    echo $ps_type;
                                    echo '<br><span class="badge bg-secondary"> By ' . $parent_data['sea_road'] . '</span>';
                                    echo '<br><span class="font-size-11"><b>D.</b>' . $ps_date . '</span>'; ?>
                                </td>
                                <td class="font-size-11 text-nowrap-">
                                    <?php echo '<b>A/C#</b>' . $agent_khaata['khaata_no'] . '<br>' . '<b>NAME</b>' . $agent_khaata['khaata_name'];
                                    //. '<br>' . '<b>COMP.</b>' . $agent_khaata['comp_name']?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php echo '<b>ITEMS. </b>' . $cntrs;
                                    echo '<br><b>Qty </b>' . $details['qty_no'];
                                    echo '<br><b>KGs </b>' . $details['total_kgs'];
                                    echo '<br><b>Goods </b>' . goodsName($details['goods_id']); ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo $pur_sale == 'purchase' ? '<b>COUNTRY</b>' . $parent_data['country'] : '<b>CITY</b>' . $parent_data['city'];
                                    echo $pur_sale == 'purchase' ? '<br><b>ALLOT</b>' . $parent_data['allot'] : '<br><b>S.NAME</b>' . $parent_data['s_name'];
                                    echo $pur_sale == 'purchase' ? '<br><b>P.A/C#</b>' : '<br><b>S.A/C#</b>';
                                    echo $p_khaata_no; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if (!empty($bail_json)) {
                                        echo '<b>CONTAINER#</b>' . $bail_json->container_no;
                                        echo '<br><b>BAIL#</b>' . $bail_json->bail_no;
                                        echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                        echo '<br><b>RECEIVE D.</b>' . $bail_json->receiving_date;
                                    } else {
                                        echo '<div class="bg-danger">&nbsp;</div>';
                                    } ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if (!empty($pa['details'])) {
                                        $details = json_decode($pa['details']);
                                        echo '<b>Entry Bill#</b>' . $details->bill_no;
                                        echo '<br><b>Entry Bill Date</b>' . $details->bill_date;
                                        echo '<br><b>Report</b>' . $details->report;
                                    } else {
                                        echo '<div class="bg-danger">&nbsp;</div>';
                                    } ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php if (!empty($bill)) {
                                        echo '<b>ITEMS </b>' . count($bill->exp_qtys);
                                        echo '<br><b>VALUE </b>' . round($bill->amount);
                                        echo isset($bill->final_amount) ? '<br><b>FINAL </b>' . round($bill->final_amount) : '';
                                    } ?>
                                </td>
                                <td class="font-size-11 text-nowrap">
                                    <?php if (!empty($bill)) {
                                        if (isset($bill->p_id_hidden)) {
                                            echo '<b>Dr.A/C</b>' . $bill->dr_khaata_no;
                                            /*Find Cr. A/c. (p_khaata_id) from purchases table*/
                                            $purch_temp = fetchTableAssoc($bill->p_id_hidden, $pur_sale . 's');
                                            echo '<br><b>Cr.A/C</b>' . $purch_temp['p_khaata_no'];
                                            echo '<br><b>Transfer</b> ' . date('y-m-d', strtotime($bill->created_at));
                                            echo '<br><b>R. SR#</b> ';
                                            echo getTransferredToRoznamchaSerial('Business', $purchase_agents_id, 'purchase_agentsimport');
                                            echo getTransferredToRoznamchaSerial('Business', $purchase_agents_id, 'purchase_agentsexport');
                                        }
                                    } ?>
                                </td>
                            </tr>
                            <?php $numRows++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" value="<?php echo $numRows; ?>" id="numRows">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['agentBillSubmit'])) {
    unset($_POST['agentBillSubmit']);
    $post = json_encode($_POST);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);

    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $purchase_type = mysqli_real_escape_string($connect, $_POST['type']);
    $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $source = mysqli_real_escape_string($connect, $_POST['source']);
    $bill_date = mysqli_real_escape_string($connect, $_POST['bill_date']);
    $bill_giver = mysqli_real_escape_string($connect, $_POST['bill_giver']);
    $amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
    $purchase_agents_id = mysqli_real_escape_string($connect, $_POST['purchase_agents_id']);
    $pa_sr_hidden = mysqli_real_escape_string($connect, $_POST['pa_sr_hidden']);
    $d_sr_hidden = mysqli_real_escape_string($connect, $_POST['d_sr_hidden']);


    $type = ucfirst(substr($source, 0, 1)) . '.' . $purchase_type;
    $transfered_from = 'purchase_agents' . $purchase_type;
    $report = mysqli_real_escape_string($connect, $_POST['report']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $tax_rate = mysqli_real_escape_string($connect, $_POST['tax_rate']);
    if (isset($_POST['is_qty'])) {
        $details .= ' CURRENCY:' . $_POST['currency'];
        $details .= ' RATE:' . mysqli_real_escape_string($connect, $_POST['rate']);
    }
    if ($tax_rate > 0) {
        $details .= ' TAX:' . $tax_rate . '%';
    }
    $details .= ' ' . $report;
    $r_type = 'Business';
    $roznamcha_no = '[' . $pa_sr_hidden . '] ' . $p_id_hidden . '-' . $d_sr_hidden;
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $branch_serial = getBranchSerial($branch_id, $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $purchase_agents_id,
            'branch_id' => $branch_id,
            'user_id' => $userId,
            'username' => $userName,
            'r_date' => $bill_date,
            'roznamcha_no' => $roznamcha_no,
            'r_name' => $type,
            'r_no' => $pa_sr_hidden,
            'created_at' => date('Y-m-d H:i:s')
        );
        //$str = " Purchase Agents # " . $purchase_agents_id;
        $str = strtoupper($source) . '# ' . $roznamcha_no;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    /*$k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);*/
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['roznamcha_no'] = $roznamcha_no;
                    $dataArrayUpdate['r_no'] = $pa_sr_hidden;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['roznamcha_no'] = $roznamcha_no;
                    $dataArrayUpdate['r_no'] = $pa_sr_hidden;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Cr. A/c:' . $bnaam_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Dr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            //$url .= '&view=1';
            $preData = array('bill' => $post);
            $tlUpdated = update('purchase_agents', $preData, array('id' => $purchase_agents_id));
            $msg = $str . ' Agent Bill has beed update with Business Roznamcha values. ';
            $msgType = 'success';
        } else {
            $msg = 'Transfer Error ';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    message($msgType, $pageURL, $msg);
} ?>
<script>
    function viewPurchase(id = null, pd_id = null, purchase_agents_id = null, khaataId = null, source = null) {
        if (id) {
            $.ajax({
                url: 'ajax/agentSideBillsAdmin.php',
                type: 'post',
                data: {id: id, d_id: pd_id, purchase_agents_id: purchase_agents_id, khaataId: khaataId, source: source},
                success: function (response) {
                    $('#viewDetails').html(response);
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<script type="text/javascript">
    $("#numRows_span").text($("#numRows").val());
</script>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">AGENT BILL</h5>
                <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>

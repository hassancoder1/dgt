<?php $page_title = 'Sales Advance Form';
$pageURL = 'sale-advance';
include("header.php"); ?>
<?php $sql = "SELECT * FROM `sales` WHERE transfer =1 ORDER BY transfer ";
if ($_GET) {
    $removeFilter = removeFilter('sales');
    if (isset($_GET['type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['type']);
        $sql .= " AND type = " . "'$type'" . " ";
        $type_msg = $type;
    }
}
$sales = mysqli_query($connect, $sql); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between table-form gap-md-5">
                <div>
                    <b>ROWS: </b><span id=""><?php echo mysqli_num_rows($sales); ?></span>
                </div>
                <div class="">
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
                <div>
                    <?php echo addNew('sale-add', '', 'btn-sm'); ?>
                </div>
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
                                <th>REPORT</th>
                                <th>SELLER</th>
                                <th>AMOUNT</th>
                                <th>ADVANCE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($sale = mysqli_fetch_assoc($sales)) {
                                $sale_id = $sale['id'];
                                $sale_type = $sale['type'];
                                $transfer2 = $sale['transfer2'];
                                $totals = saleSpecificData($sale_id, 'product_details');
                                $rowColor = '';
                                $seller_json = json_decode($sale['seller_json']);
                                if ($transfer2 == 0) {
                                    $rowColor = 'bg-danger bg-opacity-10';
                                } ?>
                                <tr class="pointer text-uppercase <?php echo $rowColor; ?>"
                                    onclick="viewSale(<?php echo $sale_id; ?>)" data-bs-toggle="modal"
                                    data-bs-target="#KhaataDetails">
                                    <td>
                                        <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                        echo '<br><b>D.</b>' . date('y-m-d', strtotime($sale['s_date']));
                                        echo '<span class="small">';
                                        if ($transfer2 == 1) {
                                            echo '<br><i class="fa fa-check-double text-success"></i> Transferred';
                                        }
                                        echo '</span>'; ?>
                                    </td>
                                    <td class="small">
                                        <?php echo '<b>BRANCH</b>' . branchName($sale['branch_id']) . '<br>'; ?>
                                        <?php echo '<b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                    </td>
                                    <td class="small text-nowrap">
                                        <?php $cntrs = saleSpecificData($sale_id, 'sale_rows');
                                        if ($cntrs > 0) {
                                            echo '<b>GOODS. </b>'. $totals['Goods'][0];
                                            echo '<br><b>ITEMS. </b>'. $cntrs;
                                            echo '<br><b>Qty </b>'. $totals['Qty'];
                                            echo '<br><b>KGs </b>'. $totals['KGs'];
                                        } ?>
                                    </td>
                                    <td class="small">
                                        <?php echo $sale['report']; ?>
                                    </td>
                                    <td class="small text-nowrap">
                                        <?php if (!empty($seller_json)) {
                                            echo '<b>A/C.</b>' . $seller_json->khaata_no;
                                            echo '<br><b>Loading D.</b>' . date('y-m-d', strtotime($seller_json->l_date));
                                            echo '<br><b>Container</b>' . $seller_json->ctr_name;
                                        } ?>
                                    </td>
                                    <td class="small">
                                        <?php if ($cntrs > 0) {
                                            echo '<b>Amt. </b>' . $totals['Amount'];
                                            echo '<br><b>Final </b>' . $totals['Final'];
                                            echo '<br><b>Transfer </b>' . $sale['t2_date'];
                                        } ?>
                                    </td>
                                    <td class="small">
                                        <?php echo '<b>' . $sale['pct_amt'] . '[' . $sale['pct'] . '%]</b><br>';
                                        $adv_paid = saleSpecificData($sale_id, 'adv_paid_total');
                                        echo '<span class="text-success"><b>Paid </b>' . round($adv_paid) . '</span><br>';
                                        echo '<span class="text-danger"><b>Bal. </b>' . round($sale['pct_amt'] - $adv_paid) . '</span>'; ?>
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
<?php if (isset($_GET['s_id']) && is_numeric($_GET['s_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $s_id = mysqli_real_escape_string($connect, $_GET['s_id']);
    if (isset($_GET['sale_pays_id']) && is_numeric($_GET['sale_pays_id'])) {
        $sale_pays_id = mysqli_real_escape_string($connect, $_GET['sale_pays_id']);
    } else {
        $sale_pays_id = 0;
    }
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewSale($s_id,$sale_pays_id); });</script>";
} ?>
    <script>
        function viewSale(id = null, sale_pays_id = null) {
            if (id) {
                var sp_id = 0;
                if (sale_pays_id) {
                    sp_id = sale_pays_id;
                }
                $.ajax({
                    url: 'ajax/viewSingleSaleAdvance.php',
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
                    <h5 class="modal-title text-danger" id="staticBackdropLabel">PURCHASE ADVANCE PAYMENTS</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </div>
        </div>
    </div>
<?php if (isset($_POST['tAdvSubmit'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $s_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $s_type = mysqli_real_escape_string($connect, $_POST['s_type_hidden']);
    $url = $pageURL.'?s_id=' . $s_id . '&view=1';
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);
    $currency1 = mysqli_real_escape_string($connect, $_POST['currency1']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $currency2 = mysqli_real_escape_string($connect, $_POST['currency2']);
    $rate = mysqli_real_escape_string($connect, $_POST['rate']);
    $opr = mysqli_real_escape_string($connect, $_POST['opr']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $report = mysqli_real_escape_string($connect, $_POST['report']);
    $details = 'Amount: ' . $amount . $currency1 . ' Rate: ' . $rate . '/' . $currency2 . ' TransferDate' . $transfer_date . ' ' . $report;
    $data = array(
        'type' => 's_adv',
        'sale_id' => $s_id,
        'dr_khaata_no' => $jmaa_khaata_no,
        'dr_khaata_id' => $jmaa_khaata_id,
        'cr_khaata_no' => $bnaam_khaata_no,
        'cr_khaata_id' => $bnaam_khaata_id,
        'currency1' => $currency1,
        'amount' => $amount,
        'currency2' => $currency2,
        'rate' => $rate,
        'opr' => $opr,
        'final_amount' => $final_amount,
        'transfer_date' => $transfer_date,
        'report' => $details
    );
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $sale_pays_id = mysqli_real_escape_string($connect, $_POST['sale_pays_id_hidden']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $adv_payment_added = update('sale_pays', $data, array('id' => $sale_pays_id));
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $adv_payment_added = insert('sale_pays', $data);
        $sale_pays_id = $connect->insert_id;
    }
    $r_type = 'Business';
    $transfered_from = 'sale_advance';
    $type = 'S.A';
    if ($adv_payment_added) {
        $msg = 'Advance payment saved in DB. ';
        //$msg = 'Transferred to Business Roznamcha ' . $str . ' Also, transferred Loading form.';
        $msgType = 'success';
        $pdQ = fetch('sales', array('id' => $s_id));
        $s_data = mysqli_fetch_assoc($pdQ);
        $branch_serial = getBranchSerial($s_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $sale_pays_id,
            'branch_id' => $s_data['branch_id'],
            'user_id' => $s_data['created_by'],
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $sale_pays_id,
            'r_name' => $type,
            'r_no' => $s_id,
            'details' => $details
        );
        $str = ucfirst($s_data['type']) . " Sale#" . $s_id . " ";
        $transferred = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $dataArrayUpdate['dr_cr'] = 'dr';
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $dataArrayUpdate['dr_cr'] = 'cr';
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $transferred = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'dr';
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $transferred = insert('roznamchaas', $dataArray);
            }
        }
        if ($transferred) {
            $msg .= ' And transferred to Roznamcha successfully. ';
            //$preData = array('khaata_adv' => $post_json);\
        } else {
            $msg .= ' Transfer Error :(';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    message($msgType, $url, $msg);
}
if (isset($_POST['transferAdvanceToRem'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $data = array('transfer2' => 1, 't2_date' => date('Y-m-d'));
    $locked = update('sales', $data, array('id' => $s_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Sale Advance transferred ';
    }
    message($type, $pageURL.'?s_id='.$s_id_hidden.'&view=1', $msg);
}
if (isset($_POST['deletePaymentAndRozSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $url_ = $pageURL."?view=1&s_id=" . $s_id_hidden;
    $p_type_hidden = mysqli_real_escape_string($connect, $_POST['s_type_hidden']);
    $sale_pays_id = mysqli_real_escape_string($connect, $_POST['sale_pays_id_hidden']);
    $r_id_hidden = json_decode($_POST['r_id_hidden'], true);
    $pays_del = mysqli_query($connect, "DELETE FROM `sale_pays` WHERE id='$sale_pays_id'");
    foreach ($r_id_hidden as $r_id) {
        $done = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id='$r_id'");
    }
    if ($pays_del) {
        $msg = " Payment Deleted for Sale #" . $s_id_hidden;
        $type = "success";
    }
    message($type, $url_, $msg);
} ?>
<?php $pageURL = 'sale-advance';
//$sql = "SELECT * FROM `sales` WHERE type = 'booking' AND is_transfer=1 ORDER BY is_transfer ";
$sql = "SELECT * FROM `sales` WHERE  is_transfer=1 AND transfer =1 ORDER BY transfer ";
$page_title = 'Sales Advance Form';
include("header.php");
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
            <div class="text-center">
                <?php //echo $sql; ?>
            </div>
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>DETAILS</th>
                            <th>GOODS DETAILS</th>
                            <th>AMOUNT</th>
                            <th>REPORT</th>
                            <th>SELLER</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($sale = mysqli_fetch_assoc($sales)) {
                            //$is_doc = $sale['is_doc'];
                            $sale_id = $sale['id'];
                            $sale_type = $sale['type'];
                            $rowColor = '';
                            $seller_json = json_decode($sale['seller_json']);
                            if (empty($seller_json)) {
                                $rowColor = 'bg-danger bg-opacity-10';
                            } ?>
                            <tr class="pointer <?php echo $rowColor; ?>" onclick="viewSale(<?php echo $sale_id; ?>)"
                                data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                <td>
                                    <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                    echo '<br><b>D.</b>' . date('y-m-d', strtotime($sale['s_date'])); ?>
                                </td>
                                <td class="small">
                                    <?php echo '<b>BRANCH</b>' . branchName($sale['branch_id']) . '<br>'; ?>
                                    <?php echo '<b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                </td>
                                <td class="small">
                                    <?php $cntrs = saleSpecificData($sale_id, 'sale_rows');
                                    $totals = saleSpecificData($sale_id, 'product_details');
                                    if ($cntrs > 0) {
                                        echo '<b>GOODS. </b>', $totals['Goods'][0];
                                        echo '<br><b>ITEMS. </b>', $cntrs;
                                        echo '<br><b>Qty </b>', $totals['Qty'];
                                        echo '<br><b>KGs </b>', $totals['KGs'];
                                    } ?>
                                </td>
                                <td class="text-dark">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                        //echo '<br><b>Transfer </b>' . $sale['t_date'];
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
<?php if (isset($_POST['transferAdvFullSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $transfer = mysqli_real_escape_string($connect, $_POST['transfer']);
    $pct = $pct_amt = '';
    if ($transfer == 1) {
        $str = 'Advance Payment ';
        $pct = mysqli_real_escape_string($connect, $_POST['pct']);
        $pct_amt = mysqli_real_escape_string($connect, $_POST['pct_amt']);
    } else {
        $str = 'Full Payment ';
    }
    $data = array('transfer' => $transfer, 't_date' => date('Y-m-d'), 'pct' => $pct, 'pct_amt' => $pct_amt);
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $done = update('sales', $data, array('id' => $s_id_hidden));
    $url = $pageURL . '?s_id=' . $s_id_hidden . '&view=1';
    if ($done) {
        $msg = $str . " has been saved for sale#" . $s_id_hidden;
        $type = "success";
    }
    message($type, $url, $msg);
}
if (isset($_POST['ttrFirstSubmit'])) {
    unset($_POST['ttrFirstSubmit']);
    $post_json = json_encode($_POST);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);

    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $s_id = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $type_post = mysqli_real_escape_string($connect, $_POST['type']);
    $url = $pageURL.'?s_id=' . $s_id;
    $type = ' S.' . ucfirst(substr($type_post, 0, '1'));
    $transfered_from = 'sale_' . $type_post;
    $r_type = 'Business';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQ = fetch('sales', array('id' => $s_id));
        $p_data = mysqli_fetch_assoc($pQ);
        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $s_id,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $userId,
            'username' => $userName,
            'r_date' => $transfer_date,
            'roznamcha_no' => $s_id,
            'r_name' => $type,
            'r_no' => $s_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Sale # " . $s_id;
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
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $amount;
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
                    $dataArray['details'] = 'Dr. A/c:' . $bnaam_khaata_no . ' ' . $details;
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
                    $dataArray['details'] = 'Cr. A/c:' . $jmaa_khaata_no . ' ' . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $url .= '&view=1';
            $preData = array('khaata_tr1' => $post_json);
            $tlUpdated = update('sales', $preData, array('id' => $s_id));
            $msg = 'Transferred to Business Roznamcha ' . $str;
            $msgType = 'success';
        } else {
            $msg = 'Transfer Error ';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    message($msgType, $url, $msg);
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
}
if (isset($_POST['sellerAccountSubmit'])) {
    unset($_POST['sellerAccountSubmit']);
    $post = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
    $data = array('seller_json' => $post);
    $locked = update('sales', $data, array('id' => $s_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Seller account added ';
        $pageURL .= '?s_id=' . $s_id_hidden . '&view=1';
    }
    message($type, $pageURL, $msg);
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
                <h5 class="modal-title text-danger" id="staticBackdropLabel">SALE ADVANCE PAYMENTS</h5>
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
                url: 'ajax/viewSingleSaleAdvance.php',
                type: 'post',
                //data: {id: id},
                data: {id: id, show_transfer: true},
                //data: {id: id, p_khaata_no: k_no},
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

<?php
$page_title = 'Carry Bill';
$pageURL = 'carry-bill';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$user = $_SESSION['username'];
$sql = "SELECT * FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL";
$sql .= " AND JSON_EXTRACT(agent_details, '$.ag_id') IS NOT NULL";
$mypageURL = $pageURL;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <h1 class="mb-2">Agent Payments</h1>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <?php if (SuperAdmin()): ?>
                        <th>P#</th>
                        <th>AGENT ACC</th>
                        <th>AGENT ID</th>
                        <th>AGENT NAME</th>
                    <?php else: ?>
                        <th>#</th>
                    <?php endif; ?>
                    <th>Bill Of Entry</th>
                    <?php if (!SuperAdmin()): ?>
                        <th>AGENT ACC</th>
                        <th>AGENT ID</th>
                        <th>AGENT NAME</th>
                    <?php endif; ?>
                    <th>Received Date</th>
                    <th>Clearing Date</th>
                    <th>L Truck No</th>
                    <th>Truck.R.Date</th>
                    <th>Total Bill Amount</th>
                    <th>Transfer Date</th>
                    <th>Roz#</th>
                </tr>
            </thead>
            <tbody>
            <?php
$Loadings = mysqli_query($connect, $sql);
$row_count = $p_kgs_total = $p_qty_total = 0;
$grandTotal = $rowColor = '';

// Step 1: Prefetch all agent payments data into an array
$paymentTotals = [];
$paymentsData = [];
$payment_totalQ = mysqli_query($connect, "SELECT * FROM agent_payments");
while ($payment_total = mysqli_fetch_assoc($payment_totalQ)) {
    $transferDetails = json_decode($payment_total['transfer_details'], true);
    
    // Skip payment if transferred_to_admin is false or not set
    if (empty($transferDetails['transferred_to_admin']) || !$transferDetails['transferred_to_admin']) {
        continue;
    }

    $loadingId = $payment_total['loading_id'];
    $paymentTotals[$loadingId] = $transferDetails['total_bill_amount'] ?? 0;
    $paymentsData[$loadingId] = $payment_total['id']; // Store primary ID for each loading_id
}

// Step 2: Prefetch all relevant roznamchaas entries into an array
$roznamchaasData = [];
$roznamchaasQ = mysqli_query($connect, "SELECT r_id, branch_serial, created_at, transfered_from_id FROM roznamchaas WHERE r_type = 'Agent Bill'");
while ($roznamchaas = mysqli_fetch_assoc($roznamchaasQ)) {
    $transferredFromId = $roznamchaas['transfered_from_id'];
    $roznamchaasData[$transferredFromId][] = [
        'r_id' => $roznamchaas['r_id'],
        'branch_serial' => $roznamchaas['branch_serial'],
        'created_at' => $roznamchaas['created_at']
    ];
}

$row_count = 0;
foreach ($Loadings as $SingleLoading) {
    $agentDetails = json_decode($SingleLoading['agent_details'], true);
    $loadingId = $SingleLoading['id'];

    // Check if grandTotal exists for this loading ID, else skip
    if (!isset($paymentTotals[$loadingId])) {
        continue;
    }

    $currentBillNumber = json_decode($SingleLoading['gloading_info'], true)['billNumber'] ?? '';
    $grandTotal = $paymentTotals[$loadingId];
    $SuperCode = $rowColor . ' pointer" onclick="window.location.href = \'carry-bill?view=1&id=' . $loadingId . '\';"';
    if (SuperAdmin()) {
        $SuperCode .= ' data-bs-toggle="modal" data-bs-target="#KhaataDetails"';
    }

    // Retrieve the primary ID for this loading from the preloaded agent payments data
    $primaryId = $paymentsData[$loadingId] ?? null;

    // Retrieve and format roznamchaas data
    $roznamchaasDisplay = $createdAt = '';
    if ($primaryId && isset($roznamchaasData[$primaryId])) {
        $roznamchaasEntries = $roznamchaasData[$primaryId];
        $roznamchaasDisplay = implode('<br>', array_map(function($entry) {
            return "<small>{$entry['r_id']}-{$entry['branch_serial']}</small>";
        }, $roznamchaasEntries));
        $createdAt = my_date(end($roznamchaasEntries)['created_at']);
    }
?>

<tr class="text-nowrap">
    <?php if (!SuperAdmin()): ?>
        <td class="<?= $rowColor; ?>"><?= $row_count + 1; ?></td>
    <?php endif; ?>
    <td class="<?= $SuperCode; ?>"><b><?= SuperAdmin() ? "P#" . $SingleLoading['p_id'] . " ($currentBillNumber)" : $SingleLoading['bl_no']; ?></b></td>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_acc_no']; ?></td>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
    <?php if (SuperAdmin()): ?>
        <td class="<?= $SuperCode; ?>"><b><?= $SingleLoading['bl_no']; ?></b></td>
    <?php endif; ?>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['received_date']; ?></td>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['clearing_date']; ?></td>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['loading_truck_number']; ?></td>
    <td class="<?= $rowColor; ?>"><?= $agentDetails['truck_returning_date']; ?></td>
    <td class="<?= $rowColor; ?>"><?= $grandTotal; ?></td>
    <td class="<?= $rowColor; ?>"><?= !empty($createdAt) ? $createdAt : '<i class="fa fa-times text-danger"></i>'; ?></td>
    <td class="<?= $rowColor; ?>"><?= !empty($roznamchaasDisplay) ? $roznamchaasDisplay : '<i class="fa fa-times text-danger"></i>'; ?></td>
</tr>

<?php
    $row_count++;
}
?>

            </tbody>
        </table>
    </div>
</div>
<?php include("footer.php"); ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="staticBackdropLabel">AGENT PAYMENTS</h5>
                <div class="d-flex align-items-center">


                    <!-- Close Button -->
                    <a href="<?php echo $mypageURL; ?>" class="btn-close ms-3" aria-label="Close"></a>
                </div>
            </div>


            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewPurchase(id = null) {
        let editId = '<?= isset($_GET['editId']) ? $_GET['editId'] : ''; ?>';
        if (id) {
            $.ajax({
                url: 'ajax/viewDailyAgentPaymentsCarry.php',
                type: 'post',
                data: {
                    id: id,
                    editId: editId,
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your request. Please try again.');
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<script>
    $(document).ready(function() {
        // Function to get the query parameter value
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get the value of 's_khaata_id' parameter from the URL
        var s_khaata_id = getQueryParameter('s_khaata_id').toUpperCase();

        // Iterate over all the <td class="<?php echo $rowColor; ?>"> elements with class 's_khaata_id_row'
        $('td.s_khaata_id_row').each(function() {
            // Get the text content of the current <td class="<?php echo $rowColor; ?>">
            var cellText = $(this).text().trim();
            // If the text doesn't match the 's_khaata_id' parameter, hide the parent <tr>
            if (cellText !== s_khaata_id && s_khaata_id !== '') {
                $(this).closest('tr').hide();
            }
        });
    });
</script>

<?php
if (isset($_POST['agPaymentSubmit'])) {
    unset($_POST['agPaymentSubmit']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);

    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $p_id = mysqli_real_escape_string($connect, $_POST['parent_payment_id']);
    $type_post = "Agent Bill";
    $url = $pageURL . '?view=1&id=' . $_POST['loading_id'];
    $type = ' P.' . ucfirst(substr($type_post, 0, '1'));
    $transfered_from = 'agent_payments';
    $r_type = 'Agent Bill';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQuery = fetch('transactions', array('id' => $_POST['p_id']));
        $p_data = mysqli_fetch_assoc($pQuery);
        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $p_id,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $_SESSION['userId'],
            'username' => $user,
            'r_date' => $transfer_date,
            'roznamcha_no' => $p_id,
            'r_name' => $type,
            'r_no' => $p_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Agent Bill # " . $p_id;
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
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " $amount " . $details;
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
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " $amount " . $details;
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
            $existingData = json_decode($_POST['existing_data'], true);
            $existingData['transferred_to_accounts'] = true;
            unset($_POST['existing_data']);
            $post_json = [
                'transfer_info' => $_POST
            ];
            $final = json_encode(array_merge($existingData, $post_json));
            $preData = array('transfer_details' => $final);
            $tlUpdated = update('agent_payments', $preData, array('id' => $p_id));
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
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $loadingID = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>
    jQuery(document).ready(function($) {
        $('#KhaataDetails').modal('show');
    });
</script>";
    echo "<script>
    jQuery(document).ready(function($) {
        viewPurchase($loadingID);
    });
</script>";
}
?>
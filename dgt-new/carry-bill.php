<?php
$page_title = 'Agent Payments Form';
$pageURL = 'carry-bill';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$user = $_SESSION['username'];
$mypageURL = $pageURL;
?>



<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="text-nowrap">
            <div class="lh-1">
                <b>Rows</b><span id="rows_count_span"></span>
                <b>Qty </b><span id="p_qty_total_span"></span>
                <br>
                <b>KGs</b><span id="p_kgs_total_span"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 83vh">
                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
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
                                <th>T.R Date</th>
                                <th>T.Bill.Amt</th>
                                <th>Transfer Date</th>
                                <th colspan="2">#Sr.No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row_count = $p_qty_total = $p_kgs_total = 0;

                            // SQL query to fetch all records, sorted by bill_of_entry_no and created_at
                            $sql = "
            SELECT * 
            FROM general_loading 
            " . ($user !== 'admin' ? "WHERE JSON_EXTRACT(agent_details, '$.ag_id') = '$user'" : "") . "
            ORDER BY JSON_EXTRACT(agent_details, '$.bill_of_entry_no'), created_at DESC
        ";

                            $Loadings = mysqli_query($connect, $sql);
                            $groupedData = [];

                            // Group records by bill_of_entry_no
                            while ($row = mysqli_fetch_assoc($Loadings)) {
                                $billNo = json_decode($row['agent_details'], true)['bill_of_entry_no'];
                                if (!isset($groupedData[$billNo])) {
                                    $groupedData[$billNo] = [];
                                }
                                $groupedData[$billNo][] = $row;
                            }

                            // Fetch agent payments data and organize it by loading_id
                            $agentPaymentsData = mysqli_query($connect, "
            SELECT id, loading_id, grand_total, transfer_details,
                   JSON_UNQUOTE(JSON_EXTRACT(transfer_details, '$.parent_id')) AS parent_id,
                   JSON_UNQUOTE(JSON_EXTRACT(transfer_details, '$.transferred_to_admin')) AS transferred_to_admin
            FROM agent_payments
        ");

                            $loadingStatus = [];
                            while ($payment = mysqli_fetch_assoc($agentPaymentsData)) {
                                $loadingId = $payment['loading_id'];
                                $parentId = $payment['parent_id'] ?? null;

                                if (!$parentId) {
                                    $loadingStatus[$loadingId]['parent'] = $payment;
                                } else {
                                    $loadingStatus[$loadingId]['children'][] = $payment;
                                }
                            }

                            // Display the grouped data with calculated grand total and Roznamcha details
                            foreach ($groupedData as $billNo => $entries) {
                                $entry = $entries[0]; // Select only the first entry for each bill_of_entry_no

                                $agentDetails = json_decode($entry['agent_details'], true);
                                $loadingId = $entry['id'];
                                $rowColor = isset($loadingStatus[$loadingId]['parent']) ?
                                    ($loadingStatus[$loadingId]['parent']['transferred_to_admin'] === 'true' ? 'text-dark' : 'text-warning')
                                    : 'text-danger';

                                // Calculate grand total (including parent and children)
                                $grandTotal = 0;
                                $Rsr_no = [];
                                $Rtrans_date = '';
                                if (isset($loadingStatus[$loadingId])) {
                                    $parent = $loadingStatus[$loadingId]['parent'];
                                    $children = $loadingStatus[$loadingId]['children'] ?? [];

                                    $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $parent['id'], 'transfered_from' => 'agent_payments'));
                                    if (mysqli_num_rows($rozQ) > 0) {
                                        while ($roz = mysqli_fetch_assoc($rozQ)) {
                                            $Rsr_no[] = $roz['r_id'] . '-' . $roz['branch_serial'];
                                            $Rtrans_date = $roz['r_date'];
                                        }
                                    }

                                    $grandTotal += $parent['grand_total'];
                                    foreach ($children as $child) {
                                        $grandTotal += $child['grand_total'];
                                    }
                                }

                                $billNumber = ++$row_count;

                            ?>
                                <tr class="text-nowrap <?= $rowColor; ?>">
                                    <?php if (SuperAdmin()): ?>
                                        <td class=" pointer" onclick="window.location.href = '<?= 'carry-bill?loadingID=' . $loadingId . '&bill_of_entry_no=' . $agentDetails['bill_of_entry_no'] . '&billNumber=' . $billNumber; ?>';"><?= "P#" . $entry['p_id'] . " ($billNumber)"; ?></td>
                                        <td><?= $agentDetails['ag_acc_no']; ?></td>
                                        <td><?= $agentDetails['ag_id']; ?></td>
                                        <td><?= $agentDetails['ag_name']; ?></td>
                                        <td><?= $agentDetails['bill_of_entry_no']; ?></td>
                                        <td><?= $agentDetails['received_date']; ?></td>
                                        <td><?= $agentDetails['clearing_date']; ?></td>
                                        <td><?= $agentDetails['loading_truck_number']; ?></td>
                                        <td><?= $agentDetails['truck_returning_date']; ?></td>
                                        <td><?= $grandTotal; ?></td>
                                        <td><?= $Rtrans_date; ?></td>
                                        <td><?= !empty($Rsr_no) ? implode("<br>", $Rsr_no) : ''; ?></td>
                                    <?php else: ?>
                                        <td><?= $row_count; ?></td>
                                        <td><?= $agentDetails['bill_of_entry_no']; ?></td>
                                        <td><?= $agentDetails['ag_acc_no']; ?></td>
                                        <td><?= $agentDetails['ag_id']; ?></td>
                                        <td><?= $agentDetails['ag_name']; ?></td>
                                        <td><?= $agentDetails['received_date']; ?></td>
                                        <td><?= $agentDetails['clearing_date']; ?></td>
                                        <td><?= $agentDetails['loading_truck_number']; ?></td>
                                        <td><?= $agentDetails['truck_returning_date']; ?></td>
                                        <td><?= $grandTotal; ?></td>
                                        <td><?= $Rtrans_date; ?></td>
                                        <td><?= !empty($Rsr_no) ? implode("<br>", $Rsr_no) : ''; ?></td>
                                    <?php endif; ?>
                                </tr>

                            <?php
                            }
                            ?>
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
    $("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());
</script>
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
    function viewPurchase(loading_id = null, Bill_of_entry_no = null, id = null, edit = null, billNumber = null) {
        if (loading_id) {
            $.ajax({
                url: 'ajax/viewDailyAgentPaymentsCarry.php',
                type: 'post',
                data: {
                    loading_id: loading_id,
                    bill_of_entry_no: Bill_of_entry_no,
                    level: 1,
                    page: "carry-bill",
                    id: id,
                    edit: edit,
                    billNumber: billNumber
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
if (isset($_POST['AgentPaymentsFormSubmit']) || isset($_POST['UpdateAgPaymentEntry'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'carry-bill';
    $loading_id = mysqli_real_escape_string($connect, $_POST['loading_id']);
    $bill_of_entry_no = mysqli_real_escape_string($connect, $_POST['bill_of_entry_no']);
    $loading_bl_no = mysqli_real_escape_string($connect, $_POST['loading_bl_no']);
    $bill_no = mysqli_real_escape_string($connect, $_POST['bill_no']);
    $date = mysqli_real_escape_string($connect, $_POST['date']);
    $bill_details = mysqli_real_escape_string($connect, $_POST['bill_details']);
    $sr_no = mysqli_real_escape_string($connect, $_POST['sr_no']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $quantity = mysqli_real_escape_string($connect, $_POST['quantity']);
    $rate = mysqli_real_escape_string($connect, $_POST['rate']);
    $total = mysqli_real_escape_string($connect, $_POST['total']);
    $tax_percentage = mysqli_real_escape_string($connect, $_POST['tax-percentage']);
    $tax_amount = mysqli_real_escape_string($connect, $_POST['tax']);
    $grand_total = mysqli_real_escape_string($connect, $_POST['grand_total']);

    $uploadDir = 'attachments/';
    $uploadedFiles = [];

    // Handle file uploads
    if (!empty($_FILES['agent_file']['name'][0])) {
        foreach ($_FILES['agent_file']['name'] as $key => $filename) {
            $tmpName = $_FILES['agent_file']['tmp_name'][$key];
            $newFilename = time() . '_' . basename($filename);

            if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                $uploadedFiles[$key] = $newFilename;
            }
        }
    }
    $combineData = [
        'bill_no' => $bill_no,
        'date' => $date,
        'bill_details' => $bill_details,
        'transfer_details' => json_encode([
            'bill_of_entry_no' => $bill_of_entry_no,
            'transferred_to_admin' => false,
            'transferred_to_accounts' => false,
        ]),
        'agent_file' => json_encode($uploadedFiles),
    ];
    $data = [
        'bill_no' => $bill_no,
        'loading_id' => $loading_id,
        'loading_bl_no' => $loading_bl_no,
        'sr_no' => $sr_no,
        'details' => $details,
        'quantity' => $quantity,
        'rate' => $rate,
        'total' => $total,
        'tax_percentage' => $tax_percentage,
        'tax_amount' => $tax_amount,
        'grand_total' => $grand_total,
    ];
    if (isset($_POST['firstRowID'])) {
        $data['transfer_details'] = json_encode(['parent_id' => $_POST['firstRowID']]);
        $done = update('agent_payments', $combineData, ['id' => $_POST['firstRowID']]);
    } else {
        $data += $combineData;
    }
    $done = isset($_POST['UpdateAgPaymentEntry']) ? update('agent_payments', $data, array('id' => $_POST['id'])) : insert('agent_payments', $data);
    if ($done) {
        $type = 'success';
        $url .= "?loadingID=$loading_id&bill_of_entry_no=$bill_of_entry_no&billNumber=$bill_no";
        $msg = 'Agent Payment Added!';
    }
    message($type, $url, $msg);
}
if (isset($_GET['deleteAgPaymentEntry'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'carry-bill';
    $id = mysqli_real_escape_string($connect, $_GET['id']);

    $done = mysqli_query($connect, "DELETE FROM agent_payments WHERE id='$id'");
    if ($done) {
        $type = 'success';
        $msg = 'Agent Payment Added!';
    }
    message($type, $url, $msg);
}
if (isset($_POST['TransferBillToAdmin'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'carry-bill';
    $parent_id = mysqli_real_escape_string($connect, $_POST['parent_payment_id']);
    $existingData = json_decode($_POST['existing_data'], true); // Convert to associative array
    $child_ids = mysqli_real_escape_string($connect, $_POST['child_ids']);
    $child_ids_array = explode(',', $child_ids);

    $combineData = [
        'transferred_to_admin' => true,
        'child_ids' => $child_ids,
        'total_amount' => mysqli_real_escape_string($connect, $_POST['total_amount']),
        'total_bill_amount' => mysqli_real_escape_string($connect, $_POST['total_bill_amount']),
        'total_tax_amount' => mysqli_real_escape_string($connect, $_POST['total_tax_amount']),
    ];

    // Merge existing data with new data
    $existingData = array_merge($existingData, $combineData);
    $data = [
        'transfer_details' => json_encode($existingData)
    ];
    $done = update('agent_payments', $data, ['id' => $parent_id]);

    // Update child rows if the parent update was successful
    if ($done && !empty($child_ids_array)) {
        foreach ($child_ids_array as $child_id) {
            $child_id = mysqli_real_escape_string($connect, $child_id);
            $updateData = [
                'transfer_details' => json_encode([
                    'parent_id' => $parent_id
                ])
            ];
            $done = update('agent_payments', $updateData, ['id' => $child_id]);
        }
    }

    // Feedback message
    if ($done) {
        $type = 'success';
        $msg = 'Transferred to Admin';
    }
    message($type, $url, $msg);
}



if (isset($_GET['loadingID']) && is_numeric($_GET['loadingID'])) {/*&& isset($_GET['view']) && $_GET['view'] == 1*/
    $loadingID = mysqli_real_escape_string($connect, $_GET['loadingID']);
    $bill_of_entry_no = $_GET['bill_of_entry_no'];
    $id = mysqli_real_escape_string($connect, isset($_GET['id']) ? $_GET['id'] : '');
    $edit = mysqli_real_escape_string($connect, isset($_GET['edit']) ? $_GET['edit'] : '');
    $billNumber = mysqli_real_escape_string($connect, $_GET['billNumber']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    if (!empty($id) && $edit) {
        echo "<script>jQuery(document).ready(function ($) {  viewPurchase($loadingID, '$bill_of_entry_no', '$id', '$edit'); });</script>";
    } else {
        echo "<script>jQuery(document).ready(function ($) {  viewPurchase($loadingID, '$bill_of_entry_no', null, null, $billNumber); });</script>";
    }
}

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
    $url = $pageURL . '?loadingID=' . $_POST['loading_id'] . '&bill_of_entry_no=' . $_POST['bill_of_entry_no'] . '&billNumber=' . $_POST['billNumber'];
    $type = ' P.' . ucfirst(substr($type_post, 0, '1'));
    $transfered_from = 'agent_payments';
    $r_type = 'Business';
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
?>
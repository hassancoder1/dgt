<?php
$page_title = 'Agent Payments Form';
$pageURL = 'agent-payments-form';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$user = $_SESSION['username'];
$sql = "SELECT * FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL";
if ($user !== 'admin') {
    $sql .= " AND JSON_EXTRACT(agent_details, '$.ag_id') = '$user'";
}
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
                    <th>Truck Returning Date</th>
                    <th>Total Bill Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_kgs_total = $p_qty_total = 0;
                $grandTotal = $rowColor = '';
                $paymentTotals = [];
                $payment_totalQ = mysqli_query($connect, "SELECT * FROM agent_payments WHERE JSON_EXTRACT(transfer_details, '$.transferred_to_admin') IS NOT NULL");
                while ($payment_total = mysqli_fetch_assoc($payment_totalQ)) {
                    $transferDetails = json_decode($payment_total['transfer_details'], true);
                    $loadingId = $payment_total['loading_id'];
                    $paymentTotals[$loadingId] = isset($transferDetails['total_bill_amount']) ? $transferDetails['total_bill_amount'] : 0;
                }
                foreach ($Loadings as $SingleLoading) {
                    $agentDetails = json_decode($SingleLoading['agent_details'], true);
                    $loadingId = $SingleLoading['id'];
                    $currentBillNumber = json_decode($SingleLoading['gloading_info'], true)['billNumber'] ?? '';
                    $grandTotal = isset($paymentTotals[$loadingId]) ? $paymentTotals[$loadingId] : 0;
                    $SuperCode = $rowColor . ' pointer" onclick="window.location.href = \'agent-payments-form?view=1&id=' . $loadingId . '\';"';
                    if (SuperAdmin()) {
                        $SuperCode .= ' data-bs-toggle="modal" data-bs-target="#KhaataDetails"';
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
                url: 'ajax/viewAgentPaymentsForm.php',
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
if (isset($_POST['AgentPaymentsFormSubmit']) || isset($_POST['UpdateAgPaymentEntry'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'agent-payments-form';
    $loading_id = mysqli_real_escape_string($connect, $_POST['loading_id']);
    $bl_no = mysqli_real_escape_string($connect, $_POST['bl_no']);
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
    $editId = mysqli_real_escape_string($connect, isset($_POST['editId']) ? $_POST['editId'] : '');
    $uploadDir = 'attachments/';
    $uploadedFiles = [];
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
            'bl_no' => $bl_no,
            'transferred_to_admin' => false,
            'transferred_to_accounts' => false,
        ]),
        'agent_file' => json_encode($uploadedFiles),
    ];
    $data = [
        'bill_no' => $bill_no,
        'loading_id' => $loading_id,
        'bl_no' => $bl_no,
        'sr_no' => $sr_no,
        'details' => $details,
        'quantity' => $quantity,
        'rate' => $rate,
        'total' => $total,
        'tax_percentage' => $tax_percentage,
        'tax_amount' => $tax_amount,
        'grand_total' => $grand_total,
    ];
    if (isset($_POST['firstRowID']) && $editId !== $_POST['firstRowID']) {
        $data['transfer_details'] = json_encode(['parent_id' => $_POST['firstRowID']]);
        $done = update('agent_payments', $combineData, ['id' => $_POST['firstRowID']]);
    } else {
        $data = array_merge($data, $combineData);
    }
    $done = isset($_POST['UpdateAgPaymentEntry']) ? update('agent_payments', $data, array('id' => $editId)) : insert('agent_payments', $data);
    if ($done) {
        $type = 'success';
        $url .= "?view=1&id=$loading_id";
        $msg = 'Agent Payment Added!';
    }
    // message($type, $url, $msg);
}
if (isset($_GET['deleteAgPaymentEntry'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'agent-payments-form?view=1&id=' . mysqli_real_escape_string($connect, $_GET['loading_id']);
    $id = mysqli_real_escape_string($connect, $_GET['billEntryId']);
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
    $url = 'agent-payments-form?view=1&id=' . mysqli_real_escape_string($connect, $_POST['loading_id']);
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



if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $loadingID = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($loadingID); });</script>";
}

?>
<?php
$page_title = 'Agent Payments Form';
$pageURL = 'agent-payments-form';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$user = $_SESSION['username'];

// Base SQL query to fetch the latest record for each bill_of_entry_no
$sql = "
    SELECT * 
    FROM (
        SELECT *, MAX(created_at) AS latest_date 
        FROM general_loading 
        " . ($user !== 'admin' ? "WHERE JSON_EXTRACT(agent_details, '$.ag_id') = '$user'" : "") . "
        GROUP BY JSON_EXTRACT(agent_details, '$.bill_of_entry_no')
    ) AS latest_loadings
";

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
        <form name="datesSubmit" method="get">
            <div class="input-group input-group-sm">
                <!-- <input type="date" name="start" value="<?php echo $start_print; ?>" class="form-control">
                <input type="date" name="end" value="<?php echo $end_print; ?>" class="form-control">
                <select id="goods_name" name="goods_name" class="form-select">
                    <option value="">ALL GOODS</option>
                    <?php
                    // $goods = fetch('goods');
                    // while ($good = mysqli_fetch_assoc($goods)) {
                    //     $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                    //     echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                    // } 
                    ?>
                </select> -->
                <!--<select class="form-select" name="size" id="size">
                            <option value="">ALL SIZE</option>
                            <?php /*$goods_sizes = mysqli_query($connect, "SELECT DISTINCT size, goods_id FROM `good_details` ");
                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                $G_NAME = goodsName($size_s['goods_id']);
                                if ($goods_name != '') {
                                    if ($G_NAME != $goods_name) continue;
                                }
                                $size_selected = $size_s['size'] == $size ? 'selected' : '';
                                echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                            } */ ?>
                        </select>
                        <select class="form-select" name="brand" id="brand">
                            <option value="">ALL BRAND</option>
                            <?php /*$goods_brands = mysqli_query($connect, "SELECT DISTINCT brand, goods_id FROM `good_details` ");
                            while ($g_brand = mysqli_fetch_assoc($goods_brands)) {
                                $G_NAME2 = goodsName($g_brand['goods_id']);
                                if ($goods_name != '') {
                                    if ($G_NAME2 != $goods_name) continue;
                                }
                                $brand_selected = $g_brand['brand'] == $brand ? 'selected' : '';
                                echo '<option ' . $brand_selected . ' value="' . $g_brand['brand'] . '">' . $g_brand['brand'] . '</option>';
                            } */ ?>
                        </select>-->
                <!-- <select class="form-select" name="is_transferred">
                    <option <?= isset($is_transferred) && $is_transferred == 1 ? 'selected' : 'selected'; ?> value="1">Transferred</option>
                    <option <?= isset($is_transferred) && $is_transferred == 0 ? 'selected' : ''; ?> value="0">Not Transferred</option>
                </select> -->




                <!-- <select class="form-select" name="is_transferred">
                    <option value="">All</option>
                    <?php
                    //  $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                    // foreach ($imp_exp_array as $item => $value) {
                    //     $sel_tran = $is_transferred == $item ? 'selected' : '';
                    //     echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                    // } 
                    ?>
                </select> -->
                <!-- <select name="s_khaata_id" class="form-select">
                    <option value="">Seller A/c</option>
                    <?php /* $accounts_query = fetch('khaata');
                    while ($aa = mysqli_fetch_assoc($accounts_query)) {
                        $sel = $s_khaata_id == $aa['id'] ? 'selected' : '';
                        echo '<option ' . $sel . ' value="' . $aa['id'] . '">' . $aa['khaata_no'] . '</option>';
                    } */ ?>
                </select> -->

                <!-- <input type="text" class="form-control" name="s_khaata_id" placeholder="Account No." value="<?= $s_khaata_id ?>">
                <?php echo $remove; ?>
                <button type="submit" class="btn btn-success btn-sm">
                    Search
                </button> -->
            </div>
        </form>
        <!-- <div class="d-flex gap-1">
            <?php // echo addNew('purchase-add', '', 'btn-sm'); 
            ?>
            <form action="print/<?php echo $mypageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start_print; ?>">
                <input type="hidden" name="end" value="<?php echo $end_print; ?>">
                <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                 <input type="hidden" name="size" value="<?php echo $size; ?>">
                <input type="hidden" name="brand" value="<?php echo $brand; ?>"> -->
        <!-- <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                <input type="hidden" name="secret" value="<?= base64_encode("powered-by-upsol"); ?>">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-print"></i>
                </button>
            </form> -->

        <!-- </div> -->
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
                                <th>Truck Returning Date</th>
                                <th>Total Bill Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Loadings = mysqli_query($connect, $sql); // Load initial rows
                            $row_count = $p_kgs_total = $p_qty_total = 0;

                            // Fetch all relevant rows from agent_payments table
                            $agentPaymentsData = mysqli_query($connect, "
    SELECT id, loading_id, grand_total, JSON_UNQUOTE(JSON_EXTRACT(transfer_details, '$.parent_id')) AS parent_id, 
           JSON_UNQUOTE(JSON_EXTRACT(transfer_details, '$.transferred_to_admin')) AS transferred_to_admin 
    FROM agent_payments
");

                            // Group agent_payments by loading_id
                            $loadingStatus = [];
                            while ($payment = mysqli_fetch_assoc($agentPaymentsData)) {
                                $loadingId = $payment['loading_id'];
                                $parentId = $payment['parent_id'] ?? null;

                                // If this is the parent row (no parent_id), store it under the parent
                                if (!$parentId) {
                                    $loadingStatus[$loadingId]['parent'] = $payment;
                                } else {
                                    $loadingStatus[$loadingId]['children'][] = $payment;
                                }
                            }

                            $rows = [];
                            $agentEntryCount = [];
                            $billCounters = [];

                            // Step 2: Fetch and process each SingleLoading row
                            while ($SingleLoadings = mysqli_fetch_assoc($Loadings)) {
                                $rows[] = $SingleLoadings;
                                $agentDetails = json_decode($SingleLoadings['agent_details'], true);
                                $agentId = $agentDetails['ag_id'] ?? null;
                                if ($agentId) {
                                    $agentEntryCount[$agentId] = ($agentEntryCount[$agentId] ?? 0) + 1;
                                    $billCounters[$agentId] = $billCounters[$agentId] ?? 0;
                                }
                            }

                            // Step 3: Display the rows and calculate grand total
                            foreach ($rows as $SingleLoading) {
                                $agentDetails = json_decode($SingleLoading['agent_details'], true);
                                $agentId = $agentDetails['ag_id'] ?? null;
                                $loadingId = $SingleLoading['id'];

                                // Determine row color
                                $rowColor = !isset($loadingStatus[$loadingId]['parent'])
                                    ? 'text-danger'
                                    : ($loadingStatus[$loadingId]['parent']['transferred_to_admin'] === 'true' ? 'text-dark' : 'text-warning');

                                // Calculate grand total (including parent and children)
                                $grandTotal = 0;
                                if (isset($loadingStatus[$loadingId])) {
                                    $parent = $loadingStatus[$loadingId]['parent'];
                                    $children = $loadingStatus[$loadingId]['children'] ?? [];

                                    // Sum grand total for parent and child rows
                                    $grandTotal += $parent['grand_total'];
                                    foreach ($children as $child) {
                                        $grandTotal += $child['grand_total'];
                                    }
                                }

                                if ($agentId && isset($agentDetails['bill_of_entry_no'])) {
                                    $currentBillNumber = ++$billCounters[$agentId];
                                    $SuperCode = $rowColor . ' pointer" onclick="window.location.href = \'agent-payments-form?loadingID=' . $loadingId . '&bill_of_entry_no=' . $agentDetails['bill_of_entry_no'] . '&billNumber=' . $currentBillNumber . '\';"';
                                    if (SuperAdmin()) {
                                        $SuperCode .= ' data-bs-toggle="modal" data-bs-target="#KhaataDetails"';
                                    }
                            ?>
                                    <tr class="text-nowrap">
                                        <?php if (!SuperAdmin()): ?>
                                            <td class="<?= $rowColor; ?>"><?= $row_count + 1; ?></td>
                                        <?php endif; ?>
                                        <td class="<?= $SuperCode; ?>"><b><?= SuperAdmin() ? "P#" . $SingleLoading['p_id'] . " ($currentBillNumber)" : $agentDetails['bill_of_entry_no']; ?></b></td>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_acc_no']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
                                        <?php if (SuperAdmin()): ?>
                                            <td class="<?= $SuperCode; ?>"><b><?= $agentDetails['bill_of_entry_no']; ?></b></td>
                                        <?php endif; ?>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['received_date']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['clearing_date']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['loading_truck_number']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $agentDetails['truck_returning_date']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $grandTotal; ?></td>
                                    </tr>
                            <?php
                                }
                                $row_count++;
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
                url: 'ajax/viewAgentPaymentsForm.php',
                type: 'post',
                data: {
                    loading_id: loading_id,
                    bill_of_entry_no: Bill_of_entry_no,
                    level: 1,
                    page: "agent-payments-form",
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
    $url = 'agent-payments-form';
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
    $url = 'agent-payments-form';
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
    $url = 'agent-payments-form';
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
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $edit = mysqli_real_escape_string($connect, $_GET['edit']);
    $billNumber = mysqli_real_escape_string($connect, $_GET['billNumber']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    if (!empty($id) && $edit) {
        echo "<script>jQuery(document).ready(function ($) {  viewPurchase($loadingID, '$bill_of_entry_no', $id, $edit); });</script>";
    } else {
        echo "<script>jQuery(document).ready(function ($) {  viewPurchase($loadingID, '$bill_of_entry_no', null, null, $billNumber); });</script>";
    }
}

?>
<!-- When we will press transfer button from bill transfer form the enteries along with accoutn transfer will alse be shown in Ship -> General Loading -->
<?php
$page_title = 'Agent Form';
$pageURL = 'agent-form';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$user = $_SESSION['username'];
$sql = "SELECT * FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL";
if ($user !== 'admin') {
    $sql .= " AND JSON_EXTRACT(agent_details, '$.ag_id') = '$user'";
}
$mypageURL = $pageURL;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <h4 class="mb-2">Custom Clearing Agent Form</h4>
    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th><?= SuperAdmin() ? 'P' : ''; ?>#</th>
                    <th>B/L No.</th>
                    <th>AG ID</th>
                    <th>AG NAME</th>
                    <th>L_DATE</th>
                    <th>L_PORT</th>
                    <th>R_DATE</th>
                    <th>R_PORT</th>
                    <!-- <th>Container No</th> -->
                    <th>Goods Name</th>
                    <th>ORIGIN</th>
                    <th>QTY.Ne</th>
                    <th>QTY.No</th>
                    <th>G.W.KGS</th>
                    <th>N.W.KGS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $rowColor = '';
                $locked = 0;
                while ($SingleLoading = mysqli_fetch_assoc($Loadings)) {
                    $id = $SingleLoading['id'];
                    $billNumber = json_decode($SingleLoading['gloading_info'], true)['billNumber'];
                    $agentDetails = json_decode($SingleLoading['agent_details'], true);
                    if (!empty($agentDetails) && isset($agentDetails['transferred']) && $agentDetails['transferred'] === true) {
                        if (isset($agentDetails['bill_of_entry_no'])) {
                            $rowColor = 'text-dark';
                            $locked = 1;
                        } else {
                            $rowColor = 'text-danger';
                        }
                ?>

                        <tr class="text-nowrap">
                            <?php if (SuperAdmin()) { ?>
                                <td class="pointer <?php echo $rowColor; ?>" onclick="window.location.href= '?lp_id=<?= $SingleLoading['id']; ?>&view=1';"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <?= '<b>P#' . $SingleLoading['p_id'] . "($billNumber)"; ?>
                                    <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                                </td>
                            <?php } else { ?>
                                <td class="pointer <?php echo $rowColor; ?>" onclick="window.location.href= '?lp_id=<?= $SingleLoading['id']; ?>&view=1';"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <b>#<?= $billNumber; ?></b>
                                    <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                                </td>
                            <?php } ?>
                            <td class="<?php echo $rowColor; ?>"><?= $SingleLoading['bl_no']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_date']; ?></td>
                            <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_country']; ?></td> -->
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_port_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_date']; ?></td>
                            <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_country']; ?></td> -->
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_port_name']; ?></td>
                            <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['container_no']; ?></td> -->
                            <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['importer_details'], true)['im_acc_no']; ?></td>
                                         
                                <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['exporter_details'], true)['xp_acc_no']; ?></td>
                                <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['notify_party_details'], true)['np_acc_no']; ?></td> -->
                            <td class="<?php echo $rowColor; ?>"><?= goodsName(json_decode($SingleLoading['goods_details'], true)['goods_id']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['origin']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['quantity_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['quantity_no']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= round(json_decode($SingleLoading['goods_details'], true)['gross_weight']); ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= round(json_decode($SingleLoading['goods_details'], true)['net_weight']); ?></td>
                        </tr>
                <?php
                        $row_count++;
                    }
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
                <b class="" id="staticBackdropLabel"></b>
                <h4 class="text-left">AGENT CUSTOM CLEARNING FORM</h4>
                <div class="d-flex align-items-center">
                    <!-- Print Button -->
                    <a href="print/purchase-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                        target="_blank" class="btn btn-dark btn-sm me-2">PRINT</a>

                    <!-- Contract File Upload -->
                    <form id="attachmentSubmit" method="post" enctype="multipart/form-data" class="d-flex align-items-center me-2">
                        <input type="hidden" name="t_id_hidden_attach" value="<?php echo $id; ?>">
                        <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                        <input type="button" class="form-control rounded-1 bg-dark text-white" value="+ Contract File"
                            onclick="document.getElementById('attachments').click();" />
                    </form>

                    <script>
                        document.getElementById("attachments").onchange = function() {
                            document.getElementById("attachmentSubmit").submit();
                        }
                    </script>

                    <!-- Attachments List -->
                    <div class="">
                        <?php
                        // $atts = getAttachments($id, 'purchase_contract');
                        // $no = 0;
                        // foreach ($atts as $att) {
                        //     echo ++$no . '.<a class="text-decoration-underline me-2" href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank">' . readMore($att['attachment'], 20) . '</a><br>';
                        // } 
                        ?>
                    </div>

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
        if (id) {
            let action = '<?= isset($_GET['action']) ? $_GET['action'] : '' ?>';
            let editId = '<?= isset($_GET['editId']) ? $_GET['editId'] : '' ?>';
            $.ajax({
                url: 'ajax/viewAgentForm.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "agent-form",
                    action: action,
                    editId: editId
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

        // Iterate over all the <td> elements with class 's_khaata_id_row'
        $('td.s_khaata_id_row').each(function() {
            // Get the text content of the current <td>
            var cellText = $(this).text().trim();
            // If the text doesn't match the 's_khaata_id' parameter, hide the parent <tr>
            if (cellText !== s_khaata_id && s_khaata_id !== '') {
                $(this).closest('tr').hide();
            }
        });
    });
</script>

<?php
if (isset($_POST['AgentFormSubmit'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'agent-form';
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $parent_id = mysqli_real_escape_string($connect, $_POST['parent_id']);
    $agent = json_decode($_POST['existing_data'], true);
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
    $ag_id = $agent['ag_id'];
    $billNQ = mysqli_query($connect, "SELECT COUNT(*) as billCount FROM general_loading WHERE JSON_EXTRACT(agent_details, '$.ag_id') = '$ag_id' AND JSON_EXTRACT(agent_details, '$.ag_billNumber')");
    $billNumber = 0;
    if ($billNQ && $result = mysqli_fetch_assoc($billNQ)) {
        $billNumber = $result['billCount'] + 1;
    }
    $data = [
        "agent_details" => json_encode([
            'ag_acc_no' => mysqli_real_escape_string($connect, $agent['ag_acc_no']),
            'ag_name' => mysqli_real_escape_string($connect, $agent['ag_name']),
            'ag_id' => mysqli_real_escape_string($connect, $agent['ag_id']),
            'cargo_transfer_warehouse' => mysqli_real_escape_string($connect, $agent['cargo_transfer_warehouse']),
            'row_id' => mysqli_real_escape_string($connect, $agent['row_id']),
            'transferred' => true,
            'permission_to_edit' => 'No',
            'ag_billNumber' => $billNumber,
            'received_date' => mysqli_real_escape_string($connect, $_POST['received_date']),
            'clearing_date' => mysqli_real_escape_string($connect, $_POST['clearing_date']),
            'bill_of_entry_no' => (string)mysqli_real_escape_string($connect, $_POST['bill_of_entry_no']),
            'loading_truck_number' => mysqli_real_escape_string($connect, $_POST['loading_truck_number']),
            'truck_returning_date' => mysqli_real_escape_string($connect, $_POST['truck_returning_date']),
            'report' => mysqli_real_escape_string($connect, $_POST['report']),
            'attachments' => (object)$uploadedFiles
        ], JSON_UNESCAPED_UNICODE)
    ];

    $done = update('general_loading', $data, ['id' => $id]);
    $done = update('user_permissions', array('permission' => json_encode(['agent-form', 'agent-payments-form'])), array('id' => $agent['row_id']));
    if ($done) {
        $type = 'success';
        $msg = 'Agent Form Updated!';
    }
    message($type, $url . "?lp_id=" . $parent_id . '&view=1&action=update&editId=' . $id, $msg);
}
if (isset($_GET['lp_id']) && is_numeric($_GET['lp_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $lp_id = mysqli_real_escape_string($connect, $_GET['lp_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($lp_id); });</script>";
}
?>
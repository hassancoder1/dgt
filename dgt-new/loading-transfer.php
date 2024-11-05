<!-- When we will press transfer button from bill transfer form the enteries along with accoutn transfer will alse be shown in Ship -> General Loading -->
<?php
$page_title = 'Loading Transfer';
$pageURL = 'loading-transfer';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `general_loading`";
$mypageURL = $pageURL;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <h1 class="mb-2">Loading Transfer</h1>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>P#</th>
                    <th>WareHouse</th>
                    <th>AG ID</th>
                    <th>AG NAME</th>
                    <th>L_DATE</th>
                    <th>L_PORT/BORDER</th>
                    <th>R_DATE</th>
                    <th>R_PORT/BORDER</th>
                    <th>B/L No.</th>
                    <th>Containers</th>
                    <th>Goods Name</th>
                    <th>SIZE</th>
                    <th>BRAND</th>
                    <th>ORIGIN</th>
                    <th>QTY.Ne</th>
                    <th>QTY.No</th>
                    <th>G.W.KGS</th>
                    <th>N.W.KGS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($connect, $sql);
                $Loadings = [];
                $containerCounts = [];
                while ($one = mysqli_fetch_assoc($result)) {
                    $gloadingInfo = json_decode($one['gloading_info'], true);
                    if (isset($gloadingInfo['child_ids']) && $gloadingInfo['child_ids'] !== null) {
                        $Loadings[] = $one;
                    }
                    $blNo = $one['bl_no'];
                    if (!isset($containerCounts[$blNo])) {
                        $containerCounts[$blNo] = 1;
                    } else {
                        $containerCounts[$blNo]++;
                    }
                }
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $rowColor = '';
                $locked = 0;
                $pIdCounts = [];
                foreach ($Loadings as $SingleLoading) {
                    $id = $SingleLoading['id'];
                    $pId = $SingleLoading['p_id'];
                    $blNo = $SingleLoading['bl_no'];
                    if (empty($SingleLoading['agent_details'])) {
                        $rowColor = 'text-danger';
                        $locked = 0;
                    } elseif (isset(json_decode($SingleLoading['agent_details'], true)['transferred'])) {
                        $transferred = json_decode($SingleLoading['agent_details'], true)['transferred'];
                        if ($transferred === true) {
                            $rowColor = 'text-dark';
                            $locked = 1;
                        } else {
                            $rowColor = 'text-warning';
                            $locked = 0;
                        }
                    }
                    if (!isset($pIdCounts[$pId])) {
                        $pIdCounts[$pId] = 1;
                    } else {
                        $pIdCounts[$pId]++;
                    }
                    $pIdDisplayCount = $pIdCounts[$pId];
                ?>

                    <tr class="text-nowrap">
                        <td class="pointer <?= $rowColor; ?>" onclick="window.location.href= '?view=1&id=<?= $SingleLoading['id']; ?>';">
                            <?php echo '<b>P#', $pId . "</b> (" . $pIdDisplayCount . ")"; ?>
                            <?php echo $locked ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                        </td>
                        <!-- <td class="<?php echo $rowColor; ?>"><?php echo $SingleLoading['sr_no']; ?></td> -->
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['cargo_transfer_warehouse']) ? json_decode($SingleLoading['agent_details'], true)['cargo_transfer_warehouse'] : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_id'] : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_name'] : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_date']; ?></td>
                        <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_country']; ?></td> -->
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_port_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_date']; ?></td>
                        <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_country']; ?></td> -->
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_port_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= $SingleLoading['bl_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= $containerCounts[$SingleLoading['bl_no']]; ?></td>
                        <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['importer_details'], true)['im_acc_no']; ?></td> -->
                        <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['exporter_details'], true)['xp_acc_no']; ?></td> -->
                        <!-- <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['notify_party_details'], true)['np_acc_no']; ?></td> -->
                        <td class="<?php echo $rowColor; ?>"><?= goodsName(json_decode($SingleLoading['goods_details'], true)['goods_id']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['size']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['brand']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['origin']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['quantity_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['quantity_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['gross_weight']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['net_weight']; ?></td>
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
                <h5 class="modal-title" id="staticBackdropLabel">LOADING TRANSFER</h5>
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
            $.ajax({
                url: 'ajax/viewLoadingTransfer.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "loading-transfer",
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
if (isset($_POST['UpdatePermission'])) {
    $update_permission_ids = mysqli_real_escape_string($connect, $_POST['update_permission_ids']);
    $PermissionIDs = explode(',', $update_permission_ids);
    $idConditions = [];
    foreach ($PermissionIDs as $pair) {
        list($p_id, $sr_no) = explode('-', trim($pair));
        $idConditions[] = "(p_id = '$p_id' AND sr_no = '$sr_no')";
    }
    $whereClause = implode(' OR ', $idConditions);
    $query = "SELECT id, agent_details FROM general_loading WHERE $whereClause";
    $result = mysqli_query($connect, $query);
    if ($result) {
        $updates = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $existingData = json_decode($row['agent_details'], true) ?? [];
            $existingData['permission_to_edit'] = $_POST['permission'];
            $updatedAgentDetails = mysqli_real_escape_string($connect, json_encode($existingData));
            $updates[] = "WHEN id = '$id' THEN '$updatedAgentDetails'";
        }
        if (!empty($updates)) {
            $updateQuery = "
                UPDATE general_loading
                SET agent_details = CASE " . implode(' ', $updates) . " ELSE agent_details END
                WHERE $whereClause
            ";
            $done = mysqli_query($connect, $updateQuery);
            if ($done) {
                $type = 'success';
                $msg = 'Agent Permission Updated!';
            } else {
                $type = 'danger';
                $msg = 'Update Failed!';
            }
            message($type, '', $msg);
        }
    } else {
        message('danger', '', 'No matching records found.');
    }
}

if (isset($_POST['TransferToAgent'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'loading-transfer?view=1&id=' . $_POST['openRecord'];
    $ag_transfer_ids = mysqli_real_escape_string($connect, $_POST['ag_transfer_ids']);
    $ag_id = mysqli_real_escape_string($connect, $_POST['ag_id']);
    $transferPairs = explode(',', $ag_transfer_ids);

    $idConditions = [];
    foreach ($transferPairs as $pair) {
        list($p_id, $sr_no) = explode('-', trim($pair));
        $idConditions[] = "(p_id = '$p_id' AND sr_no = '$sr_no')";
    }
    $whereClause = implode(' OR ', $idConditions);

    $agentDetails = json_encode([
        'ag_acc_no' => mysqli_real_escape_string($connect, $_POST['ag_acc_no']),
        'ag_name' => mysqli_real_escape_string($connect, $_POST['ag_name']),
        'ag_id' => mysqli_real_escape_string($connect, $_POST['ag_id']),
        'row_id' => mysqli_real_escape_string($connect, $_POST['ag_row_id']),
        'cargo_transfer_warehouse' => $_POST['cargo_transfer'],
        'transferred' => true
    ]);

    // Check if there is a parent record
    $parentCheckQuery = "
        SELECT id, gloading_info, JSON_EXTRACT(gloading_info, '$.child_ids') AS child_ids
        FROM general_loading
        WHERE $whereClause AND JSON_EXTRACT(gloading_info, '$.child_ids') IS NOT NULL";
    $parentCheckResult = mysqli_query($connect, $parentCheckQuery);
    $isParent = ($parentCheckResult && mysqli_num_rows($parentCheckResult) > 0);
    $parentId = null;
    $billNumber = 0;
    $existingGloadingData = [];

    if ($isParent) {
        $row = mysqli_fetch_assoc($parentCheckResult);
        $parentId = $row['id'];
        $existingGloadingData = json_decode($row['gloading_info'], true) ?? [];

        // Retrieve and increment billNumber
        $existingBillQuery = "
            SELECT JSON_UNQUOTE(JSON_EXTRACT(gloading_info, '$.billNumber')) AS billNumber
            FROM general_loading
            WHERE JSON_EXTRACT(agent_details, '$.ag_id') = '$ag_id'
              AND JSON_EXTRACT(gloading_info, '$.child_ids') IS NOT NULL
              AND JSON_EXTRACT(gloading_info, '$.child_ids') != ''";

        $billNumbers = [];
        $existingBillResult = mysqli_query($connect, $existingBillQuery);
        while ($row = mysqli_fetch_assoc($existingBillResult)) {
            if (is_numeric($row['billNumber'])) {
                $billNumbers[] = (int)$row['billNumber'];
            }
        }
        $billNumber = !empty($billNumbers) ? max($billNumbers) + 1 : 1;
        $existingGloadingData['billNumber'] = $billNumber;
    }

    $gloadingInfo = json_encode($existingGloadingData);

    // Update only the parent with gloading_info
    if ($parentId) {
        $parentUpdateQuery = "
            UPDATE general_loading
            SET agent_details = '$agentDetails', gloading_info = '$gloadingInfo'
            WHERE id = '$parentId'";
        $doneParent = mysqli_query($connect, $parentUpdateQuery);
    }

    // Update only child records without gloading_info
    $childUpdateQuery = "
        UPDATE general_loading
        SET agent_details = '$agentDetails'
        WHERE $whereClause AND id != '$parentId'";
    $doneChildren = mysqli_query($connect, $childUpdateQuery);

    // Update user permissions if required
    $permissionUpdate = update('user_permissions', ['permission' => json_encode(['agent-form'])], ['id' => $_POST['ag_row_id']]);

    if (($doneParent || $doneChildren) && $permissionUpdate) {
        $type = 'success';
        $msg = 'Agent Details Added!';
    }
    message($type, $url, $msg);
}



if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($id); });</script>";
}
?>
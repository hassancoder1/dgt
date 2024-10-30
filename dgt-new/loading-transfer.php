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
                    <th>Sr#</th>
                    <th>WareHouse</th>
                    <th>L_DATE</th>
                    <th>L_COUNTRY</th>
                    <th>L_PORT/BORDER</th>
                    <th>R_DATE</th>
                    <th>R_COUNTRY</th>
                    <th>R_PORT/BORDER</th>
                    <th>B/L No.</th>
                    <th>Container No</th>
                    <th>Im.N</th>
                    <th>Ex.N</th>
                    <th>N.P.N</th>
                    <th>Goods Name</th>
                    <th>SIZE</th>
                    <th>BRAND</th>
                    <th>ORIGIN</th>
                    <th>QTY.Ne</th>
                    <th>QTY.No</th>
                    <th>G.W.KGS</th>
                    <th>N.W.KGS</th>
                    <th>AG ID</th>
                    <th>AG NAME</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $i = 1;
                $rowColor = '';
                $locked = 0;
                while ($SingleLoading = mysqli_fetch_assoc($Loadings)) {
                    $id = $SingleLoading['id'];
                    if (!($SingleLoading['agent_details'])) {
                        $rowColor = 'text-danger';
                    } elseif (isset(json_decode($SingleLoading['agent_details'], true)['transferred'])) {
                        if (json_decode($SingleLoading['agent_details'], true)['transferred'] === true) {
                            $rowColor = 'text-dark';
                        } else {
                            $rowColor = 'text-warning';
                        }
                    }
                ?>
                    <tr class="text-nowrap">
                        <td class="pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $SingleLoading['id']; ?>)"
                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                            <?php echo '<b>P#', $SingleLoading['p_id']; ?>
                            <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                        </td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $SingleLoading['sr_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['cargo_transfer_warehouse']) ? json_decode($SingleLoading['agent_details'], true)['cargo_transfer_warehouse'] : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_date']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_country']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_port_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_date']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_country']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_port_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= $SingleLoading['bl_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['container_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['importer_details'], true)['im_acc_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['exporter_details'], true)['xp_acc_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['notify_party_details'], true)['np_acc_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= goodsName(json_decode($SingleLoading['goods_details'], true)['goods_id']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['size']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['brand']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['origin']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['quantity_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['quantity_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['gross_weight']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['net_weight']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_id'] : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_name'] : ''; ?></td>
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
    function viewPurchase(id = null, loading_id = null) {
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
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $existingData = json_decode($_POST['existing_agent_data'], true);
    $existingData = array_merge($existingData, [
        'ag_acc_no' => mysqli_real_escape_string($connect, $_POST['ag_acc_no']),
        'ag_name' => mysqli_real_escape_string($connect, $_POST['ag_name']),
        'ag_id' => mysqli_real_escape_string($connect, $_POST['ag_id']),
        'row_id' => mysqli_real_escape_string($connect, $_POST['row_id']),
        'cargo_transfer_warehouse' => $_POST['cargo_transfer'],
        'transferred' => isset($_POST['TransferToAgent']) ? true : false
    ]);
    $existingData['permission_to_edit'] = isset($_POST['change_permission']) && $_POST['change_permission'] === 'on' ? 'yes' : 'no';
    $done = update('general_loading', array('agent_details' => json_encode($existingData)), array('id' => $id));
    if ($done) {
        $type = 'success';
        $msg = 'Agent Permission Updated!';
    }
    message($type, $url, $msg);
}
if (isset($_POST['LoadingTransfer']) || isset($_POST['TransferToAgent'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'loading-transfer';
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $data = [
        "agent_details" => json_encode([
            'ag_acc_no' => mysqli_real_escape_string($connect, $_POST['ag_acc_no']),
            'ag_name' => mysqli_real_escape_string($connect, $_POST['ag_name']),
            'ag_id' => mysqli_real_escape_string($connect, $_POST['ag_id']),
            'row_id' => mysqli_real_escape_string($connect, $_POST['row_id']),
            'cargo_transfer_warehouse' => $_POST['cargo_transfer'],
            'transferred' => isset($_POST['TransferToAgent']) ? true : false
        ])
    ];
    $done = update('general_loading', $data, array('id' => $id));
    if (isset($_POST['TransferToAgent'])) {
        $done = update('user_permissions', array('permission' => json_encode(['agent-form'])), array('id' => $_POST['row_id']));
    }
    if ($done) {
        $type = 'success';
        $msg = 'Agent Details Added!';
    }
    message($type, $url, $msg);
}
?>
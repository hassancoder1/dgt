<!-- When we will press transfer button from bill transfer form the enteries along with accoutn transfer will alse be shown in Ship -> General Loading -->
<?php
$page_title = 'Loading Transfer';
$pageURL = 'loading-transfer';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `general_loading`";
$conditions = []; // Store all conditions here
$print_filters = [];
// if ($_GET) {
//     $remove = removeFilter('general-loading');
//     $is_search = true;

//     // Filter by goods_name
//     if (isset($_GET['goods_name']) && !empty($_GET['goods_name'])) {
//         $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
//         $print_filters[] = 'goods_name=' . $goods_name;
//     }

//     // Filter by start date
//     if (isset($_GET['start']) && !empty($_GET['start'])) {
//         $start_print = mysqli_real_escape_string($connect, $_GET['start']);
//         // $pageURL .= '&start=' . $start_print;
//         $print_filters[] = 'start=' . $start_print;
//         $conditions[] = "_date >= '$start_print'";
//     }

//     // Filter by end date
//     if (isset($_GET['end']) && !empty($_GET['end'])) {
//         $end_print = mysqli_real_escape_string($connect, $_GET['end']);
//         // $pageURL .= '&end=' . $end_print;
//         $print_filters[] = 'end=' . $end_print;
//         $conditions[] = "_date <= '$end_print'";
//     }

//     if (isset($_GET['is_transferred']) && $_GET['is_transferred'] !== '') {
//         $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
//         $print_filters[] = "is_transferred=" . $is_transferred;
//         if ($is_transferred === '1') {
//             $conditions[] = "locked = '1'"; // Only inactive records
//         } elseif ($is_transferred === '0') {
//             $conditions[] = "locked = '0'"; // Only active records

//         }
//     }

//     // Filter by s_khaata_id
//     if (isset($_GET['s_khaata_id']) && !empty($_GET['s_khaata_id'])) {
//         $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
//         // $pageURL .= '&s_khaata_id=' . $s_khaata_id;
//     }
// }

// // If there are any conditions, concatenate them with 'AND'
// if (count($conditions) > 0) {
//     $sql .= ' AND ' . implode(' AND ', $conditions);
// } else {
//     $sql .= " AND locked = '1' AND transfer_level >= '2'";
// }

// $sql .= " ORDER BY id DESC";
// if (count($print_filters) > 0) {
//     $pageURL .= "?";
//     foreach ($print_filters as $filter) {
//         $pageURL .= '&' . $filter;
//     }
// } else {
//     $pageURL .= '?is_tranferred=1';
//     $is_transferred = '1';
// }
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
                                <th>P#</th>
                                <th>Sr#</th>
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
                <h5 class="modal-title" id="staticBackdropLabel">GENERAL LOADING</h5>
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
            'cargo_transfer_warehouse' => mysqli_real_escape_string($connect, $_POST['cargo_transfer']),
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
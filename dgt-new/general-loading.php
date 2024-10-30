<!-- When we will press transfer button from bill transfer form the enteries along with accoutn transfer will alse be shown in Ship -> General Loading -->
<?php
$page_title = 'G. Loading';
$pageURL = 'general-loading';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `transactions` WHERE p_s='p'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('general-loading');
    $is_search = true;

    if (isset($_GET['goods_name']) && !empty($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
        $print_filters[] = 'goods_name=' . $goods_name;
    }

    if (isset($_GET['start']) && !empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        $conditions[] = "_date >= '$start_print'";
    }

    if (isset($_GET['end']) && !empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        $conditions[] = "_date <= '$end_print'";
    }

    if (isset($_GET['s_khaata_id']) && !empty($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
    }
}

if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
} else {
    $sql .= " AND locked = '1' AND transfer_level >= '2'";
}

$sql .= " ORDER BY id DESC";
if (count($print_filters) > 0) {
    $pageURL .= "?";
    foreach ($print_filters as $filter) {
        $pageURL .= '&' . $filter;
    }
} else {
    $pageURL .= '?is_tranferred=1';
    $is_transferred = '1';
}
$mypageURL = $pageURL;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <h1 class="mb-2">General Loading</h1>
    <form name="datesSubmit" method="get">
        <div class="input-group input-group-sm">
            <input type="date" name="start" value="<?php echo $start_print; ?>" class="form-control" style="max-width:130px;">
            <input type="date" name="end" value="<?php echo $end_print; ?>" class="form-control" style="max-width:130px;">
            <input type="text" class="form-control" style="max-width:130px;" name="s_khaata_id" placeholder="Account No." value="<?= $s_khaata_id ?>">
            <?php echo $remove; ?>
            <button type="submit" class="btn btn-success btn-sm">
                Search
            </button>
        </div>
    </form>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>Bill#</th>
                    <th>Type</th>
                    <th>BR.</th>
                    <th>Date</th>
                    <th>A/c</th>
                    <th>A/c Name</th>
                    <th>Goods Name</th>
                    <th>Qty</th>
                    <th>KGs</th>
                    <th>ROAD</th>
                    <th>LOADING COUNTRY | DATE</th>
                    <th>RECEIVING COUNTRY | DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $purchases = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $i = 1;
                while ($purchase = mysqli_fetch_assoc($purchases)) {
                    $id = $purchase['id'];
                    $_fields_single = transactionSingle($id);
                    $is_doc = $purchase['is_doc'];
                    $locked = $purchase['locked'];
                    $payments = $purchase['payments'];
                    $payments = json_decode($payments, true);
                    $cntrs = purchaseSpecificData($id, 'purchase_rows');
                    $totals = purchaseSpecificData($id, 'product_details');
                    $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
                    $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                    $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];
                    $sea_road = '';
                    $sea_road_array = json_decode(getSeaRoadArray($id));
                    $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                    if (!empty($sea_road_array)) {
                        $sea_road = $sea_road_array->sea_road ?? '';
                        if ($sea_road == 'sea') {
                            $_fields_sr = [
                                'l_country' => $sea_road_array->l_country,
                                'l_date' => $sea_road_array->l_date,
                                'r_country' => $sea_road_array->r_country,
                                'r_date' => $sea_road_array->r_date
                            ];
                        }
                        if ($sea_road == 'road') {
                            $_fields_sr = [
                                'l_country' => $sea_road_array->l_country_road,
                                'l_date' => $sea_road_array->l_date_road,
                                'r_country' => $sea_road_array->r_country_road,
                                'r_date' => $sea_road_array->r_date_road
                            ];
                        }
                    }
                    $adv_paid_final = purchaseSpecificData($id, 'adv_paid_total');
                    $bal = 100 - $adv_paid_final;
                    $bal = $bal < 0.5 ? 0 : $bal;
                    if ($adv_paid_final <= 0) {
                        $rowColor = 'text-danger';
                    } elseif ($bal == 0) {
                        $rowColor = 'text-dark';
                    } elseif ($adv_paid_final > 0) {
                        $rowColor = 'text-warning';
                    } else {
                        $rowColor = '';
                    }
                ?>
                    <tr class="text-nowrap">
                        <td class="pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $id; ?>)"
                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                            <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $id; ?>
                            <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                        </td>
                        <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']); ?></td>
                        <td class="s_khaata_id_row <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['cr_acc']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['cr_acc_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                        <?php if ($sea_road == '') { ?>
                            <td class="<?php echo $rowColor; ?>" colspan="3"></td>
                        <?php } else { ?>
                            <td class="<?php echo $rowColor; ?>"><?php echo $sea_road; ?></td>
                        <?php
                            echo '<td class="' . $rowColor . '">' . $_fields_sr['l_country'] . ' ' . my_date($_fields_sr['l_date']) . '</td>';
                            echo '<td class="' . $rowColor . '">' . $_fields_sr['r_country'] . ' ' . my_date($_fields_sr['r_date']) . '</td>';
                        } ?>
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
                <h5 class="modal-title" id="staticBackdropLabel">GENERAL LOADING</h5>
                <div class="d-flex align-items-center">
                    <!-- Print Button -->
                    <a href="print/purchase-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                        target="_blank" class="btn btn-dark btn-sm me-2">PRINT</a>

                    <!-- Contract File Upload -->
                    <form id="attachmentSubmit" method="post" enctype="multipart/form-data" class="d-flex align-items-center me-2">
                        <input type="hidden" name="t_id_hidden_attach" value="<?php echo $id; ?>">
                        <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                        <input type="button" class="form-control rounded-1 bg-dark btn btn-sm text-white" value="+ Contract File"
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
                        $atts = getAttachments($id, 'purchase_contract');
                        $no = 0;
                        foreach ($atts as $att) {
                            echo ++$no . '.<a class="text-decoration-underline me-2" href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank">' . readMore($att['attachment'], 20) . '</a><br>';
                        } ?>
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
    function viewPurchase(id = null, purchase_pays_id = null) {
        if (id) {
            var pp_id = purchase_pays_id || 0; // Default to 0 if purchase_pays_id is null
            let action = '<?= isset($_GET['action']) ? $_GET['action'] : '' ?>'; // Check if action exists
            let lp_id = '<?= isset($_GET['lp_id']) ? $_GET['lp_id'] : '' ?>'; // Check if lp_id exists
            let sr_no = '<?= isset($_GET['sr_no']) ? $_GET['sr_no'] : '' ?>'; // Check if lp_id exists

            $.ajax({
                url: 'ajax/viewGeneralLoading.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "purchase-general-loading",
                    purchase_pays_id: pp_id,
                    lp_id: lp_id,
                    action: action,
                    sr_no: sr_no
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
<?php
if (isset($_POST['GLoadingSubmit'])) {
    $msg = 'DB Error';
    $msgType = 'danger';

    // General Details
    $sr_no = mysqli_real_escape_string($connect, $_POST['sr_no']);
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id']);
    $p_type = mysqli_real_escape_string($connect, $_POST['p_type']);
    $p_branch = mysqli_real_escape_string($connect, $_POST['p_branch']);
    $p_date = mysqli_real_escape_string($connect, $_POST['p_date']);
    $p_cr_acc = mysqli_real_escape_string($connect, $_POST['p_cr_acc']);
    $p_cr_acc_name = mysqli_real_escape_string($connect, $_POST['p_cr_acc_name']);
    $bl_no = mysqli_real_escape_string($connect, $_POST['bl_no']);
    $report = mysqli_real_escape_string($connect, $_POST['report']);


    // Process uploaded files
    $uploadedFiles = [];
    $uploadDir = 'attachments/'; // Directory to store uploaded files

    if (!empty($_FILES['entry_file']['name'][0])) {
        foreach ($_FILES['entry_file']['name'] as $key => $filename) {
            $tmpName = $_FILES['entry_file']['tmp_name'][$key];
            $newFilename = time() . '_' . basename($filename); // Unique file name

            // Move the file to the desired directory
            if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                $uploadedFiles[] = [$key, $newFilename];
            }
        }
    } else {
        $uploadedFiles = [];
    }

    // Loading Details
    $loading_details = [
        'loading_date' => mysqli_real_escape_string($connect, $_POST['loading_date']),
        'loading_country' => mysqli_real_escape_string($connect, $_POST['loading_country']),
        'loading_port_name' => mysqli_real_escape_string($connect, $_POST['loading_port_name']),
    ];

    // Receiving Details
    $receiving_details = [
        'receiving_date' => mysqli_real_escape_string($connect, $_POST['receiving_date']),
        'receiving_country' => mysqli_real_escape_string($connect, $_POST['receiving_country']),
        'receiving_port_name' => mysqli_real_escape_string($connect, $_POST['receiving_port_name']),
    ];

    // Importer Details
    $importer_details = [
        'im_acc_id' => mysqli_real_escape_string($connect, $_POST['im_acc_id']),
        'im_acc_no' => mysqli_real_escape_string($connect, $_POST['im_acc_no']),
        'im_acc_name' => mysqli_real_escape_string($connect, $_POST['im_acc_name']),
        'im_acc_kd_id' => mysqli_real_escape_string($connect, $_POST['im_acc_kd_id']),
        'im_acc_details' => mysqli_real_escape_string($connect, $_POST['im_acc_details'])
    ];

    // Notify Party Details
    $notify_party_details = [
        'np_acc_id' => mysqli_real_escape_string($connect, $_POST['np_acc_id']),
        'np_acc_no' => mysqli_real_escape_string($connect, $_POST['np_acc_no']),
        'np_acc_name' => mysqli_real_escape_string($connect, $_POST['np_acc_name']),
        'np_acc_kd_id' => mysqli_real_escape_string($connect, $_POST['np_acc_kd_id']),
        'np_acc_details' => mysqli_real_escape_string($connect, $_POST['np_acc_details'])
    ];

    // Exporter Details
    $exporter_details = [
        'xp_acc_id' => mysqli_real_escape_string($connect, $_POST['xp_acc_id']),
        'xp_acc_no' => mysqli_real_escape_string($connect, $_POST['xp_acc_no']),
        'xp_acc_name' => mysqli_real_escape_string($connect, $_POST['xp_acc_name']),
        'xp_acc_kd_id' => mysqli_real_escape_string($connect, $_POST['xp_acc_kd_id']),
        'xp_acc_details' => mysqli_real_escape_string($connect, $_POST['xp_acc_details'])
    ];

    // Goods Details
    $goods_details = [
        'goods_id' => mysqli_real_escape_string($connect, $_POST['goods_id']),
        'quantity_no' => mysqli_real_escape_string($connect, $_POST['quantity_no']),
        'quantity_name' => mysqli_real_escape_string($connect, $_POST['quantity_name']),
        'size' => mysqli_real_escape_string($connect, $_POST['size']),
        'brand' => mysqli_real_escape_string($connect, $_POST['brand']),
        'origin' => mysqli_real_escape_string($connect, $_POST['origin']),
        'net_weight' => mysqli_real_escape_string($connect, $_POST['net_weight']),
        'gross_weight' => mysqli_real_escape_string($connect, $_POST['gross_weight']),
        'container_no' => mysqli_real_escape_string($connect, $_POST['container_no']),
        'container_name' => mysqli_real_escape_string($connect, $_POST['container_name'])
    ];

    // Shipping Details
    $shipping_details = [
        'shipping_name' => mysqli_real_escape_string($connect, $_POST['shipping_name']),
        'shipping_phone' => mysqli_real_escape_string($connect, $_POST['shipping_phone']),
        'shipping_whatsapp' => mysqli_real_escape_string($connect, $_POST['shipping_whatsapp']),
        'shipping_email' => mysqli_real_escape_string($connect, $_POST['shipping_email']),
        'shipping_address' => mysqli_real_escape_string($connect, $_POST['shipping_address']),
        'transfer_by' => mysqli_real_escape_string($connect, $_POST['transfer_by'])
    ];

    $data = [
        'sr_no' => $sr_no,
        'p_id' => $p_id,
        'p_type' => $p_type,
        'p_branch' => $p_branch,
        'p_date' => $p_date,
        'p_cr_acc' => $p_cr_acc,
        'p_cr_acc_name' => $p_cr_acc_name,
        'loading_details' => json_encode($loading_details),
        'receiving_details' => json_encode($receiving_details),
        'bl_no' => $bl_no,
        'report' => $report,
        'importer_details' => json_encode($importer_details),
        'notify_party_details' => json_encode($notify_party_details),
        'exporter_details' => json_encode($exporter_details),
        'goods_details' => json_encode($goods_details),
        'shipping_details' => json_encode($shipping_details),
        'attachments' => json_encode($uploadedFiles)
    ];
    if (isset($_POST['action']) && isset($_POST['id'])) {
        $url_ = "general-loading?p_id=" . $p_id . "&view=1";
        $done = update('general_loading', $data, array('id' => $_POST['id']));
        if ($done) {
            $type = 'success';
            $msg = 'Entry Updated!';
        }
    } else {
        $url_ = "general-loading?p_id=" . $p_id . "&view=1";
        $done = insert('general_loading', $data);
        if ($done) {
            $type = 'success';
            $msg = 'New Goods Loading Added!';
        }
    }
    messageNew($type, $url_, $msg);
    // Debugging (optional)
}

if (isset($_GET['deleteLoadingEntry']) && isset($_GET['lp_id']) && !empty($_GET['lp_id'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id = mysqli_real_escape_string($connect, $_GET['lp_id']);
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    $url_ = "general-loading?view=1&p_id=" . $p_id;
    $done = mysqli_query($connect, "DELETE FROM `general_loading` WHERE id='$id'");
    if ($done) {
        $msg = " Loading Entry Deleted for Purchase #" . $p_id;
        $type = "success";
    }
    message($type, $url_, $msg);
}
if (isset($_POST['t_id_hidden_attach'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $ppp_id = mysqli_real_escape_string($connect, $_POST['t_id_hidden_attach']);
    $url_ = $pageURL . "?t_id=" . $ppp_id . "&attach=1";
    $dato = array('is_doc' => 1);
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($ppp_id, 'purchase_contract', basename($_FILES["attachments"]["name"][$key]));
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
            $dd = update('transactions', $dato, array('id' => $ppp_id));
            if ($moved && $dd) {
                $type = 'success';
                $msg = 'Attachment Saved ';
                $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            }
        }
    }
    messageNew($type, $url_, $msg);
}
if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    if (isset($_GET['purchase_pays_id']) && is_numeric($_GET['purchase_pays_id'])) {
        $purchase_pays_id = mysqli_real_escape_string($connect, $_GET['purchase_pays_id']);
    } else {
        $purchase_pays_id = 0;
    }
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id,$purchase_pays_id); });</script>";
}

if (isset($_POST['transferAdvanceToRem'])) {
?>
<?php
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('transfer_level' => 4);
    $locked = update('transactions', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Purchase Advance transferred ';
    }
    message($type, $pageURL, $msg);
}
?>
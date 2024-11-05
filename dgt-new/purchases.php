<?php
$page_title = 'Purchases';
$pageURL = 'purchases';
include("header.php");
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `transactions` WHERE p_s='p'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter($pageURL);
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
    if (isset($_GET['is_transferred']) && $_GET['is_transferred'] !== '') {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $print_filters[] = "is_transferred=" . $is_transferred;
        if ($is_transferred === '1') {
            $conditions[] = "locked = '1'";
        } elseif ($is_transferred === '0') {
            $conditions[] = "locked = '0'";
        }
    }
    if (isset($_GET['s_khaata_id']) && !empty($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
        $print_filters[] = 's_khaata_id=' . $s_khaata_id;
    }
}
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}
$sql .= " ORDER BY id DESC";
if (count($print_filters) > 0) {
    $pageURL .= "?";
    $pageURL .= implode('&', $print_filters);
}
$mypageURL = $pageURL;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1 class="mb-2">PURCHASES</h1>

        <div class="d-flex gap-1">
            <div class="dropdown me-2">
                <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    New
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                    $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        echo '<li><a class="dropdown-item" href="purchase-add?type=' . urlencode($static_type['type_name']) . '">' . htmlspecialchars($static_type['details']) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <form action="print/<?php echo $mypageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start_print; ?>">
                <input type="hidden" name="end" value="<?php echo $end_print; ?>">
                <input type="hidden" name="goods_name" value="<?php echo $goods_name; ?>">
                <input type="hidden" name="is_transferred" value="<?php echo $is_transferred; ?>">
                <input type="hidden" name="s_khaata_id" value="<?php echo $s_khaata_id; ?>">
                <input type="hidden" name="secret" value="<?= base64_encode("powered-by-upsol"); ?>">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-print"></i>
                </button>
            </form>
        </div>
    </div>


    <form name="datesSubmit" method="get">
        <div class="input-group input-group-sm">
            <input type="date" name="start" value="<?php echo $start_print; ?>" class="form-control">
            <input type="date" name="end" value="<?php echo $end_print; ?>" class="form-control">
            <select id="goods_name" name="goods_name" class="form-select">
                <option value="">ALL GOODS</option>
                <?php $goods = fetch('goods');
                while ($good = mysqli_fetch_assoc($goods)) {
                    $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                    echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                } ?>
            </select>
            <select class="form-select" name="is_transferred">
                <option value="">All</option>
                <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                foreach ($imp_exp_array as $item => $value) {
                    $sel_tran = $is_transferred == $item ? 'selected' : '';
                    echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                } ?>
            </select>

            <input type="text" class="form-control" name="s_khaata_id" placeholder="Account No." value="<?= $s_khaata_id ?>">
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
                    <th>AMOUNT</th>
                    <th>PAYMENT TYPE</th>
                    <th>COUNTRY</th>
                    <th>ROAD</th>
                    <th>LOADING COUNTRY | DATE</th>
                    <th>RECEIVING COUNTRY | DATE</th>
                    <th>DOCS</th>
                </tr>
            </thead>
            <tbody>
                <?php $purchases = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                while ($purchase = mysqli_fetch_assoc($purchases)) {
                    $id = $purchase['id'];
                    $_fields_single = transactionSingle($id);
                    $is_doc = $purchase['is_doc'];
                    $locked = $purchase['locked'];
                    $cntrs = purchaseSpecificData($id, 'purchase_rows');
                    $totals = purchaseSpecificData($id, 'product_details');
                    $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
                    $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                    $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . ' ' : '';
                    $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                    $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];
                    $sea_road = '';
                    $sea_road_array = json_decode(getSeaRoadArray($id));
                    $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                    if (!empty($sea_road_array)) {
                        $sea_road = $sea_road_array->sea_road ?? '';
                        if ($sea_road == 'sea') {
                            $_fields_sr = ['l_country' => $sea_road_array->l_country, 'l_date' => $sea_road_array->l_date, 'r_country' => $sea_road_array->r_country, 'r_date' => $sea_road_array->r_date];
                        }
                        if ($sea_road == 'road') {
                            $_fields_sr = ['l_country' => $sea_road_array->l_country_road, 'l_date' => $sea_road_array->l_date_road, 'r_country' => $sea_road_array->r_country_road, 'r_date' => $sea_road_array->r_date_road];
                        }
                    }
                    if ($is_search) {
                        $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                        if ($goods_name != '') {
                            if ($goods_name != $GoodsKaNaam) continue;
                        }
                        if ($size != '') {
                            if ($size != $totals['Size'][0]) continue;
                        }
                        if ($brand != '') {
                            if ($brand != $totals['Brand'][0]) continue;
                        }

                        if ($is_transferred != '') {
                            if ($is_transferred == '1') {
                                if ($locked == 0) continue;
                            }
                            if ($is_transferred == '0') {
                                if ($locked == 1) continue;
                            }
                        }
                    }
                    $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                    $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                    $rowColor = '';
                    if ($locked == 0) {
                        $rowColor = $is_doc == 0 ? ' text-danger ' : ' text-warning ';
                    } ?>
                    <tr class="text-nowrap">
                        <td class="pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $id; ?>)"
                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                            <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $id;
                            echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']);; ?></td>
                        <td class="s_khaata_id_row <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['cr_acc']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['cr_acc_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                        <td class="<?php echo $rowColor; ?>">
                            <?php if ($cntrs > 0) {
                                echo $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                            } ?>
                        </td>
                        <td class="<?php echo $rowColor; ?> px-2"><?= isset($_fields_single['payment_details']->full_advance) ? ucwords($_fields_single['payment_details']->full_advance) : "No Payment Details Available"; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $purchase['country']; ?></td>
                        <?php
                        if ($sea_road == '') {
                            echo '<td class="<?php echo $rowColor; ?>" colspan="3"></td>';
                        } else {
                            echo '<td class="' . $rowColor . '">' . $sea_road . '</td>';
                            echo '<td class="' . $rowColor . '">' . $_fields_sr['l_country'] . ' ' . my_date($_fields_sr['l_date']) . '</td>';
                            echo '<td class="' . $rowColor . '">' . $_fields_sr['r_country'] . ' ' . my_date($_fields_sr['r_date']) . '</td>';
                        }
                        ?>
                        <td class="<?php echo $rowColor; ?>">
                            <?php if ($is_doc == 1) {
                                $atts = getAttachments($id, 'purchase_contract');
                                foreach ($atts as $att) {
                                    echo '<a href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank"><i class="fa fa-download text-success"></i></a>';
                                }
                            } ?>
                        </td>
                    </tr>
                <?php $row_count++;
                } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("footer.php"); ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">PURCHASE DETAILS</h5>
                <a href="<?php echo $mypageURL; ?>" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewPurchase(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleTransaction.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "purchases"
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<?php if (isset($_POST['deleteTransaction'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $url_ = "purchases";
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $deleteAccounts = mysqli_query($connect, "DELETE FROM `transaction_accounts` WHERE trans_id='$p_id_hidden'");
    $deleteItems = mysqli_query($connect, "DELETE FROM `transaction_items` WHERE parent_id='$p_id_hidden'");
    $deleteTransaction = mysqli_query($connect, "DELETE FROM `transactions` WHERE id='$p_id_hidden'");
    if ($deleteAccounts && $deleteItems && $deleteTransaction) {
        $msg = "Deleted Booking Purchase #" . $p_id_hidden;
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
if (isset($_POST['transferPurchase'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $data = array('locked' => 1, 'transfer_level' => 1, '`from`' => 'purchase-orders');
    $locked = update('transactions', $data, array('id' => $p_id_hidden));
    if ($locked) {
        $type = 'success';
        $msg = 'Purchase Successfully transferred.';
    } else {
        $type = 'failed';
        $msg = 'Purchase transfer Failed.';
    }
    messageNew($type, $pageURL, $msg);
}
if (isset($_GET['t_id']) && is_numeric($_GET['t_id'])) {/*&& isset($_GET['view']) && $_GET['view'] == 1*/
    $t_id = mysqli_real_escape_string($connect, $_GET['t_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($t_id); });</script>";
}

if (isset($_POST['purchaseReports'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $reportType = mysqli_real_escape_string($connect, $_POST['reportType']);
    $report = htmlspecialchars($_POST['reportBox']);
    $report = str_replace(array("\n", "\r", "\r\n"), ' ', $report);
    if (is_numeric($id) && recordExists('transactions', ['id' => $id])) {
        $records = fetch('transactions', ['id' => $id]);
        $record = mysqli_fetch_assoc($records);
        $reports = isset($record['reports']) && !empty($record['reports']) ? json_decode($record['reports'], true) : [];
        if (json_last_error() !== JSON_ERROR_NONE) {
            $msg = 'JSON Decode Error: ' . json_last_error_msg();
            messageNew('danger', $pageURL, $msg);
            return;
        }
        $reports[$reportType] = $report;
        $data = ['reports' => json_encode($reports)];
        error_log("Updating Reports Data: " . print_r($data, true));
        if (update('transactions', $data, ['id' => $id])) {
            $type = 'success';
            $msg = 'Report Successfully Updated.';
        } else {
            $msg = 'DB Update Failed';
        }
    }
    messageNew($type, $pageURL, $msg);
}
if (isset($_GET['deletePurchaseReport'])) {
    $id = isset($_GET['p_hidden_id']) ? $_GET['p_hidden_id'] : '';
    $type = $_GET['type'];
    $pageURL = $pageURL . '?t_id='.$id;
    $deleteReport = isset($_GET['deletePurchaseReport']) ? $_GET['deletePurchaseReport'] : '';
    $records = fetch('transactions', ['id' => $id]);
    $record = mysqli_fetch_assoc($records);
    $reports = isset($record['reports']) ? json_decode($record['reports'], true) : [];
    if (isset($reports[$deleteReport])) {
        unset($reports[$deleteReport]);
        $data = ['reports' => json_encode($reports)];
        if (update('transactions', $data, ['id' => $id])) {
            messageNew('success', $pageURL, 'Report Deleted Successfully!');
        } else {
            messageNew('failed', $pageURL, 'Error in Deleting Report!');
        }
    } else {
        messageNew('failed', $pageURL, 'Report Type Not Found!');
    }
}
?>
<script>
    $(document).ready(function() {
        // Function to get the query parameter value
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        if (getQueryParameter('s_khaata_id')) {
            var s_khaata_id = getQueryParameter('s_khaata_id').toUpperCase();
            $('td.s_khaata_id_row').each(function() {
                var cellText = $(this).text().trim();
                if (cellText !== s_khaata_id && s_khaata_id !== '') {
                    $(this).closest('tr').hide();
                }
            });
        }
    });
</script>
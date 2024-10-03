<?php $pageURL = 'loading-purchase-booking';
$page_title = 'Purchases Booking Loading ';
include("header.php");
$remove = $size = $brand = $origin = $goods_name = $start = $end = $is_transferred = $search_acc = $sale_pur = '';
$is_search = false;
global $connect;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
        $pageURL .= '?goods_name=' . $goods_name;
    }
    if (isset($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $pageURL .= '&size=' . $size;
    }
    if (isset($_GET['brand'])) {
        $brand = mysqli_real_escape_string($connect, $_GET['brand']);
        $pageURL .= '&brand=' . $brand;
    }
    if (isset($_GET['origin'])) {
        $origin = mysqli_real_escape_string($connect, $_GET['origin']);
        $pageURL .= '&origin=' . $origin;
    }
    if (isset($_GET['start'])) {
        $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '&start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
    if (isset($_GET['is_transferred'])) {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $pageURL .= '&is_transferred=' . $is_transferred;
    }
    if (isset($_GET['search_acc'])) {
        $search_acc = mysqli_real_escape_string($connect, $_GET['search_acc']);
        $pageURL .= '&search_acc=' . $search_acc;
    }
}
$sql2 = "SELECT * FROM purchase_details ORDER BY is_transfer";
$query2 = mysqli_query($connect, $sql2); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex table-form text-nowrap align-items-center justify-content-between gap-0">
                <div><b>ROWS:</b><span id="rows_count_span"></span></div>
                <div><b>QTY:</b><span id="p_qty_total_span"></span></div>
                <div><b>KGs:</b><span id="p_kgs_total_span"></span></div>
                <form method="get" class="d-flex align-items-center ">
                    <?php echo searchInput('', 'form-control-sm');
                    echo $remove; ?>
                    <div class="input-group">
                        <input type="date" name="start" value="<?php echo $start; ?>" class="form-control">
                    </div>
                    <div class="input-group">
                        <input type="date" name="end" value="<?php echo $end; ?>" class="form-control">
                    </div>
                    <div class="input-group">
                        <select id="goods_name" name="goods_name" class="form-select">
                            <option value="">ALL GOODS</option>
                            <?php $goods = fetch('goods');
                            while ($good = mysqli_fetch_assoc($goods)) {
                                $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                                echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="search_acc" class="form-select">
                            <option value="" hidden>A/c</option>
                            <?php $accounts_query = fetch('khaata');
                            while ($aa = mysqli_fetch_assoc($accounts_query)) {
                                $sel = $search_acc == $aa['khaata_no'] ? 'selected' : '';
                                echo '<option ' . $sel . ' value="' . $aa['khaata_no'] . '">' . $aa['khaata_no'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="is_transferred" class="form-select">
                            <option value="" hidden>Transfer</option>
                            <?php $tt_array = array('All' => '', 'Transferred' => '1', 'Not Transferred' => '0');
                            foreach ($tt_array as $item => $value) {
                                $tt_sel = $is_transferred == $value ? 'selected' : '';
                                echo '<option ' . $tt_sel . ' value="' . $value . '">' . $item . '</option>';
                            } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="card rounded-0">
                <div class="card-body p-0">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <?php unset($_SESSION['response']); ?>
                    <div class="table-responsive" style="height: 78dvh;">
                        <table class="table mb-0 table-bordered table-sm fix-head-table">
                            <thead>
                            <tr class="text-nowrap">
                                <th>TYPE</th>
                                <th>PURCHASER &amp; SELLER</th>
                                <th>GOODS</th>
                                <th style="width: 40% !important;">REPORT</th>
                                <th>BAIL</th>
                                <th>IMPORTER</th>
                                <th>EXPORTER</th>
                                <th>NOTIFY PARTY</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                            $query2 = mysqli_query($connect, $sql2);
                            while ($details = mysqli_fetch_assoc($query2)) {
                                $source_table = 'purchase';
                                $d_id = $details['id'];
                                $parent_id = $details['parent_id'];
                                $imp_json = json_decode($details['imp_json']);
                                $exp_json = json_decode($details['exp_json']);
                                $notify_json = json_decode($details['notify_json']);
                                $ware_json = json_decode($details['ware_json']);
                                $tware_json = json_decode($details['tware_json']);
                                $bail_json = json_decode($details['bail_json']);
                                $d_sr = $details['d_sr'];
                                //purchases / sales parent tables data
                                $parent_query = fetch('purchases', array('id' => $parent_id));
                                $parent_data = mysqli_fetch_assoc($parent_query);
                                $purchase_sale_type = $parent_data['type'];
                                $s_khaata_no = '';

                                if ($parent_data['is_locked'] != 1) continue;
                                $type_badge = '<br>' . purchaseSpecificData($parent_id, 'purchase_type');
                                $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                                $purchase_sale_date = date('y-m-d', strtotime($parent_data['p_date']));
                                $s_khaata_no = $parent_data['s_khaata_no'];
                                $s_khaata = khaataSingle($parent_data['s_khaata_id']);
                                $totals = purchaseSpecificData($parent_id, 'product_details');

                                $is_transfer = $details['is_transfer'];
                                $rowColor = $is_transfer <= 0 ? 'bg-danger bg-opacity-10' : '';
                                if ($purchase_sale_type == 'market') continue;
                                if ($parent_data['transfer'] < 2) continue;
                                if ($purchase_sale_type == 'booking') {
                                    //if ($search == $s_khaata_no || strtoupper($search) == $s_khaata_no || strtolower($search) == $s_khaata_no) {}
                                    if ($is_search) {
                                        if ($start != '') {
                                            if ($purchase_sale_date < $start) continue;
                                        }
                                        if ($end != '') {
                                            if ($purchase_sale_date > $end) continue;
                                        }
                                        if ($goods_name != '') {
                                            if ($goods_name != $totals['Goods'][0]) continue;
                                        }
                                        if ($is_transferred != '') {
                                            if ($is_transferred == 1) {
                                                if ($is_transfer <= 0) continue;
                                            }
                                            if ($is_transferred == 0) {
                                                if ($is_transfer > 0) continue;
                                            }
                                        }
                                        if ($search_acc != '') {
                                            if ($search_acc != $s_khaata_no) continue;
                                        }
                                    }
                                    $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                                    $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                                    ?>
                                    <tr class="text-uppercase <?php echo $rowColor; ?>">
                                        <td class="pointer text-nowrap"
                                            onclick="viewDetails(<?php echo $parent_id; ?>,<?php echo $d_id; ?>,'<?php echo $source_table; ?>')"
                                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                            <?php echo '<b>P#</b>' . $parent_id . '-' . $d_sr;
                                            echo $is_transfer > 0 ? '<span class="font-size-10"><i class="fa fa-check-double text-success"></i>TRANSF</span>' : '';
                                            echo $type_badge . '<br><span class="font-size-11"><b>D.</b>' . $purchase_sale_date . '</span>'; ?>
                                        </td>
                                        <td class="font-size-10 text-nowrap">
                                            <?php echo '<b>BRANCH</b>' . branchName($parent_data['branch_id']) . '<br>';
                                            echo '<b>PURCHASER A/c#</b>' . $parent_data['p_khaata_no'] . '<br>';
                                            echo '<b>SELLER A/c#</b>' . $s_khaata_no;
                                            echo isset($s_khaata['khaata_name']) ? '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name'] : ''; ?>
                                        </td>
                                        <td class="font-size-10 text-nowrap">
                                            <?php echo $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . '<br><b>Qty </b>' . $details['qty_no'] . '<br><b>KGs </b>' . $details['total_kgs'] . '<br><b>Goods </b>' . $totals['Goods'][0] : ''; ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php $details_k = ['indexes' => $parent_data['rep_indexes'], 'vals' => $parent_data['rep_vals']];
                                            $reps = displayKhaataDetails($details_k, true);
                                            if (array_key_exists('Final', $reps)) {
                                                echo '<div style="width: 150px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $reps['Final'] . '">' . readMore($reps['Final'], '80') . '</div>';
                                            } ?>
                                        </td>
                                        <td class="font-size-10 text-nowrap">
                                            <?php if (!empty($bail_json)) {
                                                echo '<b>CONTAINER#</b>' . $bail_json->container_no . '<br><b>BAIL#</b>' . $bail_json->bail_no . '<br><b>LOADING D.</b>' . $bail_json->loading_date . '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                            } ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php if (!empty($imp_json)) {
                                                echo '<b>COMPANY</b>' . $imp_json->comp_name . '<br><b>COUNTRY</b>' . $imp_json->country;
                                            } ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php if (!empty($exp_json)) {
                                                echo '<b>COMPANY</b>' . $exp_json->comp_name . '<br><b>COUNTRY</b>' . $exp_json->country;
                                            } ?>
                                        </td>
                                        <td class="font-size-10">
                                            <?php if (!empty($notify_json)) {
                                                echo '<b>COMPANY</b>' . $notify_json->comp_name . '<br><b>COUNTRY</b>' . $notify_json->country;
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php ++$row_count;
                                }
                            } ?>
                            </tbody>
                        </table>
                        <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                        <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                        <input type="hidden" id="p_kgs_total" value="<?php echo round($p_kgs_total); ?>">
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
    <script>
        function viewDetails(id = null, d_id = null, source = null) {
            if (id) {
                $.ajax({
                    url: 'ajax/viewSingleLoading.php',
                    type: 'post',
                    data: {id: id, d_id: d_id, source: source},
                    success: function (response) {
                        $('#viewDetails').html(response);
                        $('#staticBackdropLabel').prepend(source.toUpperCase());
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                        alert('An error occurred. Please try again.');
                    }
                });
            } else {
                console.error('Invalid arguments for viewDetails function.');
                alert('An error occurred. Please try again.');
            }
        }

        /*function viewDetails(id = null, d_id = null, source = null) {
            if (id) {
                $.ajax({
                    url: 'ajax/viewSingleLoading.php',
                    type: 'post',
                    data: {id: id, d_id: d_id, source: source},
                    success: function (response) {
                        $('#viewDetails').html(response);
                        $('#staticBackdropLabel').prepend(source.toUpperCase());
                    }
                });
            } else {
                alert('error!! Refresh the page again');
            }
        }*/
    </script>
<?php if (isset($_GET['source']) && isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['d_id']) && is_numeric($_GET['d_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $source = mysqli_real_escape_string($connect, $_GET['source']);
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $d_id = mysqli_real_escape_string($connect, $_GET['d_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewDetails($id,$d_id,'$source'); });</script>";
    if (isset($_GET['secondModal']) && $_GET['secondModal'] == 1) {
        echo "<script>jQuery(document).ready(function ($) {  $('#secondModal').modal('show');});</script>";
    }
} ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bold" id="staticBackdropLabel"> LOADING Details</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0" id="viewDetails"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="secondModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="secondModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg" style="min-height: 300px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="secondModalLabel">Importer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light " id="addImpExpNotify">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="bailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg" style="min-height: 300px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="bailModalLabel">Bail Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light-" id="bailModalForm"></div>
            </div>
        </div>
    </div>
<?php if (isset($_POST['savePartyPLoading'])) {
    $msg = 'DB Error :(';
    $msg_type = 'danger';
    $id_hidden = mysqli_real_escape_string($connect, $_POST['id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    $source = mysqli_real_escape_string($connect, $_POST['source_hidden']);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $validatedData = array_map('htmlspecialchars', $_POST);
    $post = json_encode($validatedData);
    $urll = $pageURL;
    if (is_numeric($d_id_hidden) && $d_id_hidden > 0 && in_array($type, ['Importer', 'Exporter', 'Notify', 'Warehouse', 'TWarehouse'])) {
        $urll .= '?source=' . $source . '&id=' . $id_hidden . '&d_id=' . $d_id_hidden . '&view=1';
        switch ($type) {
            case 'Importer':
                $data = ['imp_json' => $post];
                break;
            case 'Exporter':
                $data = ['exp_json' => $post];
                break;
            case 'Notify':
                $data = ['notify_json' => $post];
                break;
            case 'Warehouse':
                $data = ['ware_json' => $post];
                break;
            case 'TWarehouse':
                $data = ['tware_json' => $post];
                break;
        }
        $done = update('purchase_details', $data, array('id' => $d_id_hidden));
        if ($done) {
            $msg = 'Party saved.';
            $msg_type = 'success';
        }
    }
    message($msg_type, $urll, $msg);
}
if (isset($_POST['bailDetailsSubmit'])) {
    $msg = 'DB Error :(';
    $msg_type = 'danger';
    $id_hidden = mysqli_real_escape_string($connect, $_POST['id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    $source = mysqli_real_escape_string($connect, $_POST['source_hidden']);
    $url2 = $pageURL;
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $validatedData = array_map('htmlspecialchars', $_POST);
    $post = json_encode($validatedData);
    if (is_numeric($d_id_hidden) && $d_id_hidden > 0) {
        $url2 .= '?source=' . $source . '&id=' . $id_hidden . '&d_id=' . $d_id_hidden . '&view=1';
        $data = array('bail_json' => $post);
        $done = update('purchase_details', $data, array('id' => $d_id_hidden));
        if ($done) {
            $msg = 'Bail details saved.';
            $msg_type = 'success';
        }
    }
    message($msg_type, $url2, $msg);
}
if (isset($_POST['transferFormSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id_hidden = mysqli_real_escape_string($connect, $_POST['id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    $source = mysqli_real_escape_string($connect, $_POST['source_hidden']);
    $data = array('is_transfer' => 1, 't_date' => date('Y-m-d'));

    $done = update('purchase_details', $data, array('id' => $d_id_hidden));
    //if ($source == 'purchase') {} else {$done = update('sale_details', $data, array('id' => $d_id_hidden));}
    $URL = $pageURL . '?source=' . $source . '&id=' . $id_hidden . '&d_id=' . $d_id_hidden . '&view=1';
    if ($done) {
        $type = 'success';
        $msg = 'Purchase transferred to Transfer Form ';
    }
    message($type, $URL, $msg);
} ?>
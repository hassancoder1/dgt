<?php $pageURL = 'invoices';
$page_title = 'INVOICES';
include("header.php");
global $branchId, $userId, $connect;
$view_url = $remove = $start = $end = '';
$is_search = false;
global $connect;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    $start = isset($_GET['start']) ? mysqli_real_escape_string($connect, $_GET['start']) : '';
    $end = isset($_GET['end']) ? mysqli_real_escape_string($connect, $_GET['end']) : '';
    $params = ['start' => $start, 'end' => $end];
    foreach ($params as $key => $value) {
        if ($value != '') {
            $pageURL .= (!str_contains($pageURL, '?') ? '?' : '&') . $key . '=' . urlencode($value);
        }
    }
    $view_url = '&view=1';
} else {
    $view_url = '?view=1';
}
$sql = "SELECT * FROM `invoices` WHERE is_active = 1 ORDER BY id asc ";
$invoices = mysqli_query($connect, $sql); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex table-form text-nowrap align-items-center justify-content-between">
            <?php echo addNew($pageURL . $view_url, 'NEW', 'btn-sm'); ?>
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <form method="get" class="d-flex align-items-center ">
                <?php echo $remove;
                echo searchInput('', 'form-control-sm'); ?>
                <div class="input-group">
                    <input type="date" name="start" value="<?php echo $start; ?>" class="form-control">
                </div>
                <div class="input-group">
                    <input type="date" name="end" value="<?php echo $end; ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <?php echo $_SESSION['response'] ?? '';
                unset($_SESSION['response']); ?>
                <div class="table-responsive" style="height: 76dvh;">
                    <table class="table mb-0 table-bordered table-sm fix-head-table">
                        <thead>
                        <tr>
                            <th>Inv #</th>
                            <th>Date</th>
                            <th>Importer</th>
                            <th>Exporter</th>
                            <th>Notify Party</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = 0;
                        $invoices = fetch('invoices', array('is_active' => 1));
                        while ($inv = mysqli_fetch_assoc($invoices)) {
                            $json = json_decode($inv['json_data']);
                            $q_imp = fetch('imps_exps', array('id' => $json->importer_id));
                            $importer = mysqli_fetch_assoc($q_imp);
                            $q_exp = fetch('imps_exps', array('id' => $json->exporter_id));
                            $exporter = mysqli_fetch_assoc($q_exp);
                            $q_party = fetch('parties', array('id' => $json->party_id));
                            $party = mysqli_fetch_assoc($q_party); ?>
                            <tr class="">
                                <td><span class="badge bg-secondary"><?php echo $inv['inv_no']; ?></span></td>
                                <td class="">
                                    <a href="invoice-add?id=<?php echo $inv['id']; ?>"><?php echo $inv['inv_date']; ?></a>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13"><?php echo $importer['comp_name']; ?></span>
                                    <br><span
                                            class="badge badge-pill badge-soft-danger font-size-12"><?php echo $importer['city']; ?></span>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13 "><?php echo $exporter['comp_name']; ?></span>
                                    <br><span
                                            class="badge badge-pill badge-soft-danger font-size-12"><?php echo $exporter['city']; ?></span>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13"><?php echo $party['comp_name']; ?></span>
                                    <br><span
                                            class="badge badge-pill badge-soft-danger font-size-12"><?php echo $party['city']; ?></span>
                                </td>
                            </tr>
                            <tr class=" border-bottom border-primary">
                                <td class="small" colspan="5">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div>
                                            <a href="print/invoice?inv_id=<?php echo $inv['id']; ?>"
                                               class="btn btn-primary btn-sm" target="_blank">Print</a>
                                        </div>
                                        <div class="">
                                            <span class="text-muted">Goods Name</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->goods_name; ?></span>
                                            <span class="text-muted">Origin</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->origin; ?></span>
                                            <span class="text-muted">Terms</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->terms; ?></span>
                                            <span class="text-muted">Shipping Method</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->shipping_method; ?></span>
                                            <span class="text-muted">Port of loading</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->loading_port; ?></span>
                                            <span class="text-muted">Shipping Terms</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->shipping_terms; ?></span>
                                            <span class="text-muted">Delivery Date</span>
                                            <span class="text-dark font-size-13 bold"><?php echo date('d M Y', strtotime($json->delivery_date)); ?></span>
                                            <span class="text-muted">Payment Terms</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->payment_terms; ?></span>
                                            <span class="text-muted">Due Date</span>
                                            <span class="text-dark font-size-13 bold"><?php echo date('d M Y', strtotime($json->due_date)); ?></span>
                                            <span class="text-muted">Qty Name</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->qty_name; ?></span>
                                            <span class="text-muted">Qty No</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->qty_no; ?></span>
                                            <span class="text-muted">KGs</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->kgs; ?></span>
                                            <span class="text-muted">Total KGs</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->total_kgs; ?></span>
                                            <span class="text-muted">Price</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->unit_price . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Total amount</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->amount . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Freight</span>
                                            <span class="text-dark font-size-13 bold"><?php echo empty($json->freight) ? '0' : $json->freight . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Net Total</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->total_amount; ?></span>
                                        </div>
                                        <div>
                                            <?php $delMsg = 'کیا آپ ختم / ڈیلیٹ کرنا چاہتے ہیں؟' . '\n' . 'انوائس نمبر: ' . $inv['id']; ?>
                                            <form method="post" onsubmit="return confirm('<?php echo $delMsg; ?>')">
                                                <input type="hidden" name="inv_id" value="<?php echo $inv['id']; ?>">
                                                <button type="submit" name="deleteInv" class="btn btn-danger btn-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                        <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    /*$("#p_qty_total_span").text($("#p_qty_total").val());
    $("#p_kgs_total_span").text($("#p_kgs_total").val());*/
</script>
<?php if (isset($_POST['deleteInv'])) {
    $URL_DEL = 'invoices';
    $inv_id = mysqli_real_escape_string($connect, $_POST['inv_id']);
    $done = mysqli_query($connect, "UPDATE `invoices` SET is_active = 0 WHERE id = '$inv_id'");
    $msgg = 'Invoice # ';
    $msgg .= ' <span class="badge badge-pill badge-soft-danger font-size-12">' . $inv_id . '</span> ';

    if ($done) {
        $msgg .= 'Deleted';
        message('success', $URL_DEL, $msgg);
    } else {
        $msgg .= 'DB error';
        message('danger', $URL_DEL, $msgg);
    }
} ?>
<?php if (isset($_POST['recordSubmit'])) {
    $_from = $_POST['_from'];
    $third_party = $_POST['third_party'];
    $_to = $_POST['_to'];
    unset($_POST['_from']);
    unset($_POST['third_party']);
    unset($_POST['_to']);
    $terms = $_POST['terms'];
    unset($_POST['terms']);
    $through = $_POST['through'];
    unset($_POST['through']);

    $json_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $data = array(
        '_from' => $_from,
        'third_party' => $third_party,
        '_to' => $_to,
        'type' => 'afg',
        '_date' => $_POST['_date1'],
        'json_data' => $json_data,
        'terms' => $terms,
        'through' => $through
    );
    $url = 'afghan-invoices?view=1';
    $info = array('type' => 'danger', 'msg' => 'There is some System Error :(');
    $inv_id_hidden = mysqli_real_escape_string($connect, $_POST['inv_id_hidden']);
    if ($inv_id_hidden > 0) {
        $done = update('afg_invs', $data, array('id' => $inv_id_hidden));
        $url .= '&id=' . $inv_id_hidden;
    } else {
        $done = insert('afg_invs', $data);
        $url .= '&id=' . $connect->insert_id;
    }
    if ($done) {
        $info['type'] = 'success';
        $info['msg'] = 'Draft invoice saved.';
    }
    message($info['type'], $url, $info['msg']);
} ?>
<?php if (isset($_POST['deleteSDSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $sr_details_hidden = mysqli_real_escape_string($connect, $_POST['sr_details_hidden']);
    $inv_id_hidden = mysqli_real_escape_string($connect, $_POST['inv_id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    $done = mysqli_query($connect, "DELETE FROM `afg_inv_details` WHERE id='$d_id_hidden'");
    $url = $pageURL . "?view=1&id=" . $inv_id_hidden;
    if ($done) {
        $msg = "Container has been deleted ";
        $type = "success";
    }
    message($type, $url, $msg);
} ?>
<?php if (isset($_GET['view']) && $_GET['view'] == 1) {
    $inv_id = 0;
    $sr_no = getAutoIncrement('invoices');
    $_fields = array('inv_no' => $sr_no, 'inv_date' => date('Y-m-d'),
        'a' => '',
    );
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $inv_id = mysqli_real_escape_string($connect, $_GET['id']);
        $records = fetch('invoices', array('id' => $inv_id));
        $record = mysqli_fetch_assoc($records);
        $json_data = json_decode($record['json_data']);
        $_fields = array(
            'inv_no' => $record['inv_no'],
            'inv_date' => $record['inv_date'],
            'a' => $json_data->a,
        );
    }
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show'); $('#imp_khaata_no').focus();});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="staticBackdropLabel"><?php echo $page_title . ' #' . $_fields['inv_no']; ?></h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <form method="post">
                                <div class="row table-form mt-1">
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <label for="inv_no" class="form-label">Invoice #</label>
                                            <input type="text" class="form-control currency" id="inv_no" required autofocus
                                                   value="<?php echo $_fields['inv_no']; ?>" name="inv_no">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <label class="form-label" for="inv_date">Invoice Date</label>
                                            <input type="date" class="form-control" id="inv_date"
                                                   name="inv_date" required value="<?php echo $_fields['inv_date']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row table-form mt-1">
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group- d-flex align-items-center">
                                                    <label for="importer_id" class="mb-0">Importer</label>
                                                    <select id="importer_id" name="importer_id" required
                                                            class="virtual-select">
                                                        <?php $importers = fetch('khaata');
                                                        while ($importer = mysqli_fetch_assoc($importers)) {
                                                            $imp_sel = $importer['id'] == $json->importer_id ? 'selected' : '';
                                                            echo '<option ' . $imp_sel . ' value="' . $importer['id'] . '">' . $importer['name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                    <!--<small id="imp_response" class="text-danger position-absolute top-0 right-0" style="z-index: 9"></small>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border p-1 bg-white">
                                            <table class="table-sm table mb-0">
                                                <tr>
                                                    <td colspan="3" class="">
                                                        <span class="text-muted">Company</span>
                                                        <span class="text-dark font-size-13 bold" id="imp_comp_name"></span>
                                                    </td>
                                                    <td class="">
                                                        <span class="text-muted">City</span>
                                                        <span class="text-dark font-size-13 bold" id="imp_city"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class=" ">
                                                        <span class="text-muted">Address</span>
                                                        <span class="text-dark font-size-13 bold"
                                                              id="imp_comp_address"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Mobile</span>
                                                        <span class="text-dark font-size-13 bold" id="imp_mobile"></span>
                                                    </td>
                                                    <td colspan="2">
                                                        <span class="text-muted">Email</span>
                                                        <span class="text-dark font-size-13 bold" id="imp_email"></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex align-items-center">
                                                    <label for="exporter_id" class="mb-0">Exporter</label>
                                                    <select id="exporter_id" name="exporter_id" required
                                                            class=" virtual-select">
                                                        <?php $exporters = fetch('imps_exps');
                                                        while ($exporter = mysqli_fetch_assoc($exporters)) {
                                                            $exp_sel = $exporter['id'] == $json->exporter_id ? 'selected' : '';
                                                            echo '<option ' . $exp_sel . ' value="' . $exporter['id'] . '">' . $exporter['name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border p-1 bg-white">
                                            <table class="table-sm table mb-0">
                                                <tr>
                                                    <td colspan="3"><span class="text-muted">Company</span>
                                                        <span class="text-dark font-size-13 bold" id="exp_comp_name"></span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">City</span>
                                                        <span class="text-dark font-size-13 bold" id="exp_city"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <span class="text-muted">Address</span>
                                                        <span class="text-dark font-size-13 bold"
                                                              id="exp_comp_address"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Mobile</span>
                                                        <span class="text-dark font-size-13 bold" id="exp_mobile"></span>
                                                    </td>
                                                    <td colspan="2" class="">
                                                        <span class="text-muted">Email</span>
                                                        <span class="text-dark font-size-13 bold" id="exp_email"></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex align-items-center">
                                                    <label for="party_id" class="mb-0">Notify&nbsp;Party</label>
                                                    <select id="party_id" name="party_id" required
                                                            class=" virtual-select">
                                                        <?php $parties = fetch('parties');
                                                        while ($party = mysqli_fetch_assoc($parties)) {
                                                            $party_sel = $party['id'] == $json->party_id ? 'selected' : '';
                                                            echo '<option ' . $party_sel . ' value="' . $party['id'] . '">' . $party['name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border p-1 bg-white">
                                            <table class="table-sm table mb-0">
                                                <tr>
                                                    <td colspan="3"><span class="text-muted">Company</span>
                                                        <span class="text-dark font-size-13 bold"
                                                              id="party_comp_name"></span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">City</span>
                                                        <span class="text-dark font-size-13 bold" id="party_city"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <span class="text-muted">Address</span>
                                                        <span class="text-dark font-size-13 bold"
                                                              id="party_comp_address"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Mobile</span>
                                                        <span class="text-dark font-size-13 bold" id="party_mobile"></span>
                                                    </td>
                                                    <td colspan="2" class="">
                                                        <span class="text-muted">Email</span>
                                                        <span class="text-dark font-size-13 bold" id="party_email"></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group- d-flex align-items-center">
                                                    <label for="bank_khaata_id" class="mb-0">Bank&nbsp;Name</label>
                                                    <select id="bank_khaata_id" name="bank_khaata_id" required
                                                            class="virtual-select">
                                                        <?php $banks = fetch('khaata', array('acc_for' => 'bank'));
                                                        while ($bank = mysqli_fetch_assoc($banks)) {
                                                            $bank_sel = $bank['id'] == $json->bank_khaata_id ? 'selected' : '';
                                                            echo '<option ' . $bank_sel . ' value="' . $bank['id'] . '">' . $bank['business_name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border p-1 bg-white">
                                            <table class="table-sm table mb-0">
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Bank A/c No.</span>
                                                        <span class="text-dark font-size-13 bold" id="b_khaata_name"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Company Name</span>
                                                        <span class="text-dark font-size-13 bold" id="b_comp_name"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Address</span>
                                                        <span class="text-dark font-size-13 bold" id="b_address"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-muted">Branch Name</span>
                                                        <span class="text-dark font-size-13 bold" id="b_cnic_name"></span>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td colspan="2">
                                                        <span class="text-muted">Currency</span>
                                                        <span class="text-dark font-size-13 bold" id="b_cnic"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <span class="text-muted">More Details</span>
                                                        <span class="text-dark font-size-13 bold" id="b_details"></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row table-form gx-1 gy-3 mt-1">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-dark">
                                        <tr>
                                            <th><label for="goods_name">Goods Name</label></th>
                                            <th><label for="origin">Origin</label></th>
                                            <th><label for="terms">Terms</label></th>
                                            <th><label for="shipping_method">Shipping Method</label></th>
                                            <th><label for="loading_port">Port of loading</label></th>
                                            <th><label for="shipping_terms">Shipping Terms</label></th>
                                            <th><label for="delivery_date">Delivery Date</label></th>
                                            <th><label for="payment_terms">Payment Terms</label></th>
                                            <th><label for="due_date">Due Date</label></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="virtual-select" id="goods_name" name="goods_name" required>
                                                    <?php $goods = fetch('good_names', array('type' => 'name'));
                                                    while ($good = mysqli_fetch_assoc($goods)) {
                                                        $gn_sel = $good['name'] == $json->goods_name ? 'selected' : '';
                                                        echo '<option ' . $gn_sel . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="origin" name="origin"
                                                       placeholder="Origin" required value="<?php echo $json->origin; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="terms" name="terms" placeholder="Terms"
                                                       required value="<?php echo $json->terms; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="shipping_method"
                                                       name="shipping_method" placeholder="Method" required
                                                       value="<?php echo $json->shipping_method; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="loading_port"
                                                       name="loading_port" placeholder="Port of loading" required
                                                       value="<?php echo $json->loading_port; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="shipping_terms"
                                                       name="shipping_terms" placeholder="Shipping Terms" required
                                                       value="<?php echo $json->shipping_terms; ?>">
                                            </td>
                                            <td><input type="date" class="form-control" id="delivery_date"
                                                       name="delivery_date" required value="<?php echo $json->delivery_date; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="payment_terms"
                                                       name="payment_terms" required placeholder="Payment Terms"
                                                       value="<?php echo $json->payment_terms; ?>">
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" id="due_date" name="due_date"
                                                       required value="<?php echo $json->due_date; ?>"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm ">
                                        <thead class="table-dark">
                                        <tr>
                                            <th><label for="qty_kgs">Qty Name</label></th>
                                            <th><label for="qty_kgs">Qty No</label></th>
                                            <th><label for="qty_kgs">KGs</label></th>
                                            <th><label for="qty_kgs">Total KGs</label></th>
                                            <th><label for="unit_price">Unit Price/KG</label></th>
                                            <th><label for="amount">Total Amount</label></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" id="qty_name" name="qty_name"
                                                       placeholder="Name" required value="<?php echo $json->qty_name; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control currency" id="qty_no" name="qty_no"
                                                       placeholder="Number" required onkeyup="totalKGs();"
                                                       value="<?php echo $json->qty_no; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control currency" id="kgs" name="kgs"
                                                       placeholder="KGs" required onkeyup="totalKGs();"
                                                       value="<?php echo $json->kgs; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="total_kgs" readonly
                                                       tabindex="-1" name="total_kgs" placeholder="Total KGs" required
                                                       value="<?php echo $json->total_kgs; ?>">
                                            </td>
                                            <td class="d-flex">
                                                <input type="text" class="form-control currency flex-grow-1" id="price"
                                                       name="unit_price" placeholder="Unit Price" required
                                                       onkeyup="firstAmount();" value="<?php echo $json->unit_price; ?>">
                                                <select id="currency" name="currency" class="form-select" required>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $crr_sel = $crr['name'] == $json->currency ? 'selected' : '';
                                                        echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control bg-white" id="amount" name="amount" readonly
                                                       value="<?php echo $json->amount; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-end"><label for="freight" class="mt-1">Freight</label></td>
                                            <td>
                                                <input type="text" class="form-control currency" id="freight" name="freight"
                                                       placeholder="Freight" onkeyup="totalAmount();"
                                                       value="<?php echo $json->freight; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button name="recordSubmit" id="recordSubmit" type="submit"
                                                        class="btn btn-success btn-sm">Update Invoice
                                                </button>

                                            </td>
                                            <td colspan="3"></td>
                                            <td class="text-end">
                                                <label for="total_amount" class="col-form-label-lg fw-bold">Net Total </label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-lg bg-white fw-bold"
                                                       id="total_amount"
                                                       name="total_amount" readonly value="<?php echo $json->total_amount; ?>">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                                <input type="hidden" value="update" name="action">
                            </form>
                        </div>
                        <div class="col-2 order-1 fixed-sidebar table-form">
                            <div class="bottom-buttons">
                                <div class="px-2">
                                    <?php echo $inv_id > 0 ? '<a class="btn btn-sm w-100 btn-dark mt-3" href="' . $pageURL . '?view=1&id=' . $inv_id . '"><i class="fa fa-long-arrow-alt-left"></i> Back</a>' : '';
                                    echo $inv_id > 0 ? '<a href="print/invoice?id=' . base64_encode($inv_id) . '&secret=' . base64_encode('powered-by-upsol') . '&type=' . base64_encode('inv') . '" class="btn btn-success btn-sm w-100 mt-3" target="_blank">PRINT</a>' : ''; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (isset($_POST['saveInvoiceItemSubmit'])) {
    $inv_id_hidden = mysqli_real_escape_string($connect, $_POST['inv_id_hidden']);
    $d_id_hidden = mysqli_real_escape_string($connect, $_POST['d_id_hidden']);
    unset($_POST['_to']);
    $json_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $data = array(
        'parent_id' => $inv_id_hidden,
        'json_data' => $json_data
    );
    $url = $pageURL . '?view=1&id=' . $inv_id_hidden;
    $info = array('type' => 'danger', 'msg' => 'There is some System Error :(');

    if ($d_id_hidden > 0) {
        $done = update('afg_inv_details', $data, array('id' => $d_id_hidden));
    } else {
        $done = insert('afg_inv_details', $data);
    }
    if ($done) {
        $info['type'] = 'success';
        $info['msg'] = 'Details saved in AFG invoice.';
    }
    message($info['type'], $url, $info['msg']);
} ?>
<script type="text/javascript">
    $(document).ready(function () {
        totalPrice();
        $('#kgs,#unit_price').on('keyup', function () {
            totalPrice();
        });
    });

    function totalPrice() {
        var kgs = parseFloat($("#kgs").val()) || 0;
        var unit_price = parseFloat($("#unit_price").val()) || 0;

        var total_price = kgs * unit_price;
        total_price = total_price.toFixed(2);
        $("#total_price").val(total_price);

        /*if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = Number(total).toFixed(3);
        }*/

        if (total_price <= 0 || isNaN(total_price) || !isFinite(total_price)) {
            disableButton('saveInvoiceItemSubmit');
        } else {
            enableButton('saveInvoiceItemSubmit');
        }
    }
</script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.location = $(this).data("href");
        });
    });
    VirtualSelect.init({
        ele: '.v-select-sm',
        placeholder: 'Choose',
        // showValueAsTags: true,
        optionHeight: '30px',
        showSelectedOptionsFirst: true,
        // allowNewOption: true,
        // hasOptionDescription: true,
        search: true
    });
</script>
<script>
    $(document).ready(function () {
        let itemCount = 0;

        $('#saveItemBtn').on('click', function () {
            let qty1 = $('input[name="qty1"]').val();
            let qty2 = $('input[name="qty2"]').val();
            let qty3 = $('input[name="qty3"]').val();
            let kgs = $('input[name="kgs"]').val();
            let quantity = [qty1, qty2, qty3, kgs].join(' ');
            let goods = $('input[name="goods"]').val();
            let unitPrice = $('input[name="unit_price"]').val();
            let totalPrice = $('input[name="total_price"]').val();
            let temp_id_hidden = $('input[name="temp_id_hidden"]').val();

            let data = {
                temp_id_hidden: temp_id_hidden,
                qty1: qty1,
                qty2: qty2,
                qty3: qty3,
                kgs: kgs,
                goods: goods,
                unit_price: unitPrice,
                total_price: totalPrice
            };

            $.ajax({
                type: 'POST',
                url: 'ajax/save_invoice_temp_data.php',
                data: data,
                success: function (response) {
                    let result = JSON.parse(response);
                    itemCount++;

                    let newRow = `
                    <tr data-id="${result.id}">
                        <td>${itemCount}</td>
                        <td>${quantity}</td>
                        <td>${goods}</td>
                        <td>${unitPrice}</td>
                        <td>${totalPrice}</td>
                    </tr>
                `;

                    let tempIdHiddenValue = parseInt($('#temp_id_hidden').val());

                    if (tempIdHiddenValue > 0) {
                        // Update existing row
                        let existingRow = $('#invoiceTable2 tbody').find(`tr[data-id="${tempIdHiddenValue}"]`);
                        if (existingRow.length > 0) {
                            existingRow.find('td:eq(1)').text(itemCount);
                            existingRow.find('td:eq(2)').text(quantity);
                            existingRow.find('td:eq(3)').text(goods);
                            existingRow.find('td:eq(4)').text(unitPrice);
                            existingRow.find('td:eq(5)').text(totalPrice);
                        }
                    } else {
                        // Append new row
                        $('#invoiceTable2 tbody').append(newRow);
                    }
                    // Clear the input fields after saving
                    $('input[name="qty1"]').val('');
                    $('input[name="qty2"]').val('');
                    $('input[name="qty3"]').val('');
                    $('input[name="kgs"]').val('');
                    $('input[name="goods"]').val('');
                    $('input[name="unit_price"]').val('');
                    $('input[name="total_price"]').val('');
                    $('input[name="temp_id_hidden"]').val(0);
                }
            });
        });

        // Add click event listener to invoiceTable2 rows
        $('#invoiceTable2').on('click', 'tr', function () {
            let id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: 'ajax/get_invoice_temp_data.php',
                data: {id: id},
                success: function (response) {
                    let data = JSON.parse(response).json_data;
                    let parsedData = JSON.parse(data);

                    $('input[name="qty1"]').val(parsedData.qty1);
                    $('input[name="qty2"]').val(parsedData.qty2);
                    $('input[name="qty3"]').val(parsedData.qty3);
                    $('input[name="kgs"]').val(parsedData.kgs);
                    $('input[name="goods"]').val(parsedData.goods);
                    $('input[name="unit_price"]').val(parsedData.unit_price);
                    $('input[name="total_price"]').val(parsedData.total_price);
                    $('input[name="temp_id_hidden"]').val(id);
                }
            });
        });
    });
</script>
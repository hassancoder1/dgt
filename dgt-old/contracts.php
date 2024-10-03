<?php $pageURL = 'contracts';
$page_title = 'CONTRACTS';
include("header.php");
global $connect;
$sql = "SELECT * FROM `contracts` WHERE is_active = 1 ORDER BY id ";
$remove = $start = $end = '';
$is_search = false;
global $connect;
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['start'])) {
        $start_print = $start = mysqli_real_escape_string($connect, $_GET['start']);
        $pageURL .= '?start=' . $start;
    }
    if (isset($_GET['end'])) {
        $end_print = $end = mysqli_real_escape_string($connect, $_GET['end']);
        $pageURL .= '&end=' . $end;
    }
}
$contracts = mysqli_query($connect, $sql);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex table-form text-nowrap align-items-center justify-content-between">
            <?php echo addNew($pageURL . '?view=1', 'NEW', 'btn-sm'); ?>
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <form method="get" class="d-flex align-items-center ">
                <?php echo searchInput('', 'form-control-sm'); ?>
                <?php echo $remove; ?>
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
                        <tr class="text-nowrap">
                            <th>Contract #</th>
                            <th>Date</th>
                            <th>Importer</th>
                            <th>Exporter</th>
                            <th>Notify Party</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = 0;
                        while ($inv = mysqli_fetch_assoc($contracts)) {
                            $contract_id = $inv['id'];
                            $rowColor = '';
                            $json = json_decode($inv['json_data']); ?>
                            <tr class="pointer clickable-row <?php echo $rowColor; ?>"
                                data-href="<?php echo $pageURL . '?view=1&id=' . $contract_id; ?>">
                                <td>
                                    <span class="badge bg-secondary"><?php echo $inv['contract_no']; ?></span>
                                    <a href="print/contract?contract_id=<?php echo $inv['id']; ?>"
                                       class="btn btn-primary btn-sm py-0" target="_blank"><i class="fa fa-print"></i>
                                        Print</a>
                                </td>
                                <td class="">
                                    <a href="contract-add?id=<?php echo $inv['id']; ?>"><?php echo $inv['contract_date']; ?></a>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13">IMP</span>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13 ">EXP</span>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13">EXP</span>
                                </td>
                            </tr>
                            <tr class=" border-bottom border-primary">
                                <td class="small" colspan="5">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div>
                                            <span class="text-muted">Goods Name</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->goods_name; ?></span>
                                            <span class="text-muted">Origin</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->origin; ?></span>
                                            <span class="text-muted">Terms</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->terms; ?></span>
                                            <span class="text-muted">Shipping Method</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->shipping_method; ?></span>
                                            <span class="text-muted">Loading Country</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->loading_country; ?></span>
                                            <span class="text-muted">Receiving Country</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->receiving_country; ?></span>
                                            <span class="text-muted">Loading Date</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo date('d M Y', strtotime($json->loading_date)); ?></span>
                                            <span class="text-muted">Receiving Date</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo date('d M Y', strtotime($json->receiving_date)); ?></span>
                                            <span class="text-muted">Payment Terms</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->payment_terms; ?></span>
                                            <span class="text-muted">Qty Name</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->qty_name; ?></span>
                                            <span class="text-muted">Qty No</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->qty_no; ?></span>
                                            <span class="text-muted">KGs</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->kgs; ?></span>
                                            <span class="text-muted">Total KGs</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->total_kgs; ?></span>
                                            <span class="text-muted">Price</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->unit_price . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Total amount</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->amount . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Freight</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo empty($json->freight) ? '0' : $json->freight . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Net Total</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->total_amount; ?></span>
                                        </div>
                                        <div class="text-nowrap">
                                            <?php $delMsg = 'Are you sure to Delete' . '\n' . 'Contract #: ' . $inv['id']; ?>
                                            <form method="post" onsubmit="return confirm('<?php echo $delMsg; ?>')">
                                                <input type="hidden" name="contract_id"
                                                       value="<?php echo $inv['id']; ?>">
                                                <button type="submit" name="deleteInv"
                                                        class="btn btn-outline-danger btn-sm py-0">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
</script>
<?php if (isset($_POST['recordSubmit'])) {
    unset($_POST['recordSubmit']);
    $url = "contract-add";
    $type = 'danger';
    $msg = 'DB Failed';
    $data = array(
        'contract_no' => mysqli_real_escape_string($connect, $_POST['contract_no']),
        'contract_date' => mysqli_real_escape_string($connect, $_POST['contract_date']),
        'json_data' => json_encode($_POST)
    );
    if (isset($_POST['action']) && $_POST['action'] == "update") {
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('contracts', $data, array('id' => $hidden_id));
        $msg = "Updated Contract # " . $_POST['contract_no'];
        $type = "info";
        $url .= "?id=" . $hidden_id;
    } else {
        $data['json_packing'] = json_encode($_POST);
        $data['json_proforma'] = json_encode($_POST);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $data['branch_id'] = $branchId;
        $done = insert('contracts', $data);
        if ($done) {
            $msg = "Saved Contract # " . $_POST['contract_no'];
            $type = "success";
            $url .= "?id=" . $connect->insert_id;
        }
    }
    message($type, $url, $msg);
} ?>
<?php if (isset($_GET['view']) && $_GET['view'] == 1) {
    $contract_no = getAutoIncrement('contracts');
    $action_hidden = 'insert';
    $contract_date = date('Y-m-d');
    $json_data = array();
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $action_hidden = 'update';
        $contract_no = mysqli_real_escape_string($connect, $_GET['id']);
        $records = fetch('contracts', array('id' => $contract_no));
        $record = mysqli_fetch_assoc($records);

        $contract_date = $record['contract_date'];
        $branch_id = $record['branch_id'];
        $json_data = json_decode($record['json_data']);
    }
    $topArray = array(
        array('heading' => 'CONTRACT# ', 'value' => $contract_no),
        array('heading' => 'DATE ', 'value' => '<input type="date" name="s_date" value="' . $contract_date . '">')
    );
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">CONTRACT ENTRY</h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <form method="post" class="table-form">
                                <div class="d-flex justify-content-between flex-wrap gap-1 text-uppercase small">
                                    <div>
                                        <?php foreach ($topArray as $item) {
                                            echo '<b>' . $item['heading'] . '</b><span class="text-muted">' . $item['value'] . '</span><br>';
                                        } ?>
                                        <div class="d-flex align-items-center">
                                            <label for="branch_id" class="mb-0 bold">Branch</label>
                                            <select id="branch_id" name="branch_id"
                                                    class="form-select bg-transparent border-0"
                                                    style="min-width: 130px;">
                                                <?php //$branch_sql = "SELECT * FROM `branches` ";if (!SuperAdmin()) {$branch_sql .= " WHERE id= '$branchId' ";}$branches = mysqli_query($connect, $branch_sql);
                                                $array_branch_condition = SuperAdmin() ? array() : array('id' => $branchId);
                                                $branches = fetch('branches', $array_branch_condition);
                                                while ($b = mysqli_fetch_assoc($branches)) {
                                                    $b_select = $b['id'] == $branch_id ? 'selected' : '';
                                                    echo '<option ' . $b_select . ' value="' . $b['id'] . '">' . $b['b_code'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="h6 font-size-12 text-uppercase">
                                        <b>NOTIFY PARTY </b><span class="text-muted"></span>
                                    </div>
                                </div>
                                <div class="card rounded-0">
                                    <div class="card-body p-0">
                                        <div class="row table-form mt-1">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <label for="contract_no" class="form-label">Contract #</label>
                                                    <input type="text" class="form-control currency" id="contract_no"
                                                           required autofocus
                                                           value="<?php echo $contract_no; ?>" name="contract_no">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <label class="form-label" for="contract_date">Contract Date</label>
                                                    <input type="date" class="form-control" id="contract_date"
                                                           name="contract_date" required
                                                           value="<?php echo $inv['contract_date']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row table-form mt-1 gx-0">
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="input-group- d-flex align-items-center">
                                                    <label for="party_khaata_id" class="mb-0">Buyer</label>
                                                    <select id="party_khaata_id" name="party_khaata_id"
                                                            class="v-select-sm">
                                                        <?php $kdss = fetch('khaata');
                                                        while ($kh = mysqli_fetch_assoc($kdss)) {
                                                            //$sel = $party_khaata_id == $kh['id'] ? 'selected' : '';
                                                            echo '<option value="' . $kh['id'] . '">' . $kh['khaata_no'] . '</option>';
                                                        } ?>
                                                    </select>
                                                    <select id="party_kd_id" name="party_kd_id" class="form-select">
                                                        <option hidden value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="border p-1 bg-white">
                                                    <table class="table-sm table mb-0">
                                                        <tr>
                                                            <td colspan="3" class="">
                                                                <span class="text-muted">Company</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="imp_comp_name"></span>
                                                            </td>
                                                            <td class="">
                                                                <span class="text-muted">City</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="imp_city"></span>
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
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="imp_mobile"></span>
                                                            </td>
                                                            <td colspan="2">
                                                                <span class="text-muted">Email</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="imp_email"></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="d-flex align-items-center">
                                                            <label for="exporter_id" class="mb-0">Seller</label>
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
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="exp_comp_name"></span>
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">City</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="exp_city"></span>
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
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="exp_mobile"></span>
                                                            </td>
                                                            <td colspan="2" class="">
                                                                <span class="text-muted">Email</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="exp_email"></span>
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
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="party_city"></span>
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
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="party_mobile"></span>
                                                            </td>
                                                            <td colspan="2" class="">
                                                                <span class="text-muted">Email</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="party_email"></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="input-group- d-flex align-items-center">
                                                            <label for="bank_khaata_id"
                                                                   class="mb-0">Bank&nbsp;Name</label>
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
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="b_khaata_name"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <span class="text-muted">Company Name</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="b_comp_name"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <span class="text-muted">Address</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="b_address"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <span class="text-muted">Branch Name</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="b_cnic_name"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="2">
                                                                <span class="text-muted">Currency</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="b_cnic"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">
                                                                <span class="text-muted">More Details</span>
                                                                <span class="text-dark font-size-13 bold"
                                                                      id="b_details"></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row table-form gx-1 gy-3 mt-1">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-secondary">
                                                <tr>
                                                    <th><label for="goods_name" class="mb-0">Goods Name</label></th>
                                                    <th><label for="origin">Origin</label></th>
                                                    <th><label for="terms">Terms</label></th>
                                                    <th><label for="shipping_method">Shipping Method</label></th>
                                                    <th><label for="loading_country">Loading Country</label></th>
                                                    <th><label for="receiving_country">Receiving Country</label></th>
                                                    <!--<th><label for="shipping_terms">Shipping Terms</label></th>-->
                                                    <th><label for="loading_date">Loading Date</label></th>
                                                    <th><label for="receiving_date">Receiving Date</label></th>
                                                    <th><label for="payment_terms">Payment Terms</label></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="virtual-select" id="goods_name" name="goods_name"
                                                                required>
                                                            <?php $goods = fetch('good_names', array('type' => 'name'));
                                                            while ($good = mysqli_fetch_assoc($goods)) {
                                                                $gn_sel = $good['name'] == $json->goods_name ? 'selected' : '';
                                                                echo '<option ' . $gn_sel . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="origin"
                                                               name="origin"
                                                               placeholder="Origin" required
                                                               value="<?php echo $json->origin; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="terms" name="terms"
                                                               placeholder="Terms"
                                                               required value="<?php echo $json->terms; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="shipping_method"
                                                               name="shipping_method" placeholder="Method" required
                                                               value="<?php echo $json->shipping_method; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="loading_country"
                                                               name="loading_country" placeholder="Loading Country"
                                                               required
                                                               value="<?php echo $json->loading_country; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="receiving_country"
                                                               name="receiving_country" placeholder="Receiving Country"
                                                               required
                                                               value="<?php echo $json->receiving_country; ?>">
                                                    </td>
                                                    <!--<td>
                                    <input type="text" class="form-control" id="shipping_terms"
                                           name="shipping_terms" placeholder="Shipping Terms" required
                                           value="<?php /*echo $json->shipping_terms; */ ?>">
                                </td>-->
                                                    <td><input type="date" class="form-control" id="loading_date"
                                                               name="loading_date" required
                                                               value="<?php echo $json->loading_date; ?>">
                                                    </td>
                                                    <td><input type="date" class="form-control" id="receiving_date"
                                                               name="receiving_date"
                                                               required value="<?php echo $json->receiving_date; ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control" id="payment_terms"
                                                               name="payment_terms"
                                                               required placeholder="Payment Terms"
                                                               value="<?php echo $json->payment_terms; ?>"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-sm ">
                                                <thead class="table-secondary">
                                                <tr>
                                                    <th><label for="qty_name">Qty Name</label></th>
                                                    <th><label for="qty_no">Qty No</label></th>
                                                    <th><label for="kgs">KGs</label></th>
                                                    <th><label for="total_kgs">Total KGs</label></th>
                                                    <th><label for="price">Unit Price/KG</label></th>
                                                    <th><label for="amount">Total Amount</label></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" id="qty_name"
                                                               name="qty_name"
                                                               placeholder="Name" required
                                                               value="<?php echo $json->qty_name; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control currency" id="qty_no"
                                                               name="qty_no"
                                                               placeholder="Number" required onkeyup="totalKGs();"
                                                               value="<?php echo $json->qty_no; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control currency" id="kgs"
                                                               name="kgs"
                                                               placeholder="KGs" required onkeyup="totalKGs();"
                                                               value="<?php echo $json->kgs; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="total_kgs"
                                                               name="total_kgs" placeholder="Total KGs" required
                                                               value="<?php echo $json->total_kgs; ?>">
                                                    </td>
                                                    <td class="d-flex">
                                                        <input type="text" class="form-control currency flex-grow-1"
                                                               id="price"
                                                               name="unit_price" placeholder="Unit Price" required
                                                               onkeyup="firstAmount();"
                                                               value="<?php echo $json->unit_price; ?>">
                                                        <select id="currency" name="currency" class="form-select"
                                                                required>
                                                            <?php $currencies = fetch('currencies');
                                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                $crr_sel = $crr['name'] == $json->currency ? 'selected' : '';
                                                                echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control bg-white" id="amount"
                                                               name="amount" readonly
                                                               value="<?php echo $json->amount; ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="d-flex">
                                                            <label for="contract_details" class="mt-1">Details</label>
                                                            <textarea placeholder="Contract Details" type="text"
                                                                      class="form-control"
                                                                      name="contract_details"
                                                                      id="contract_details"><?php echo $json->contract_details; ?></textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <label for="advance_per"
                                                                   class="mt-1">Advance&nbsp;(%)</label>
                                                            <input type="text" class="form-control currency"
                                                                   id="advance_per"
                                                                   name="advance_per" placeholder="Advance"
                                                                   onkeyup="advanceAmount();"
                                                                   value="<?php echo $json->advance_per; ?>">
                                                            <input value="<?php echo $json->advance; ?>" type="text"
                                                                   class="form-control" id="advance" name="advance"
                                                                   readonly tabindex="-1">
                                                        </div>
                                                        <div class="d-flex">
                                                            <label for="total_amount" class="mt-1 fw-bold">Net&nbsp;Total </label>
                                                            <input type="text" class="form-control bg-white fw-bold"
                                                                   id="total_amount" name="total_amount" readonly
                                                                   value="<?php echo $json->total_amount; ?>">
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        <button name="recordSubmit" id="recordSubmit" type="submit"
                                                                class="btn btn-success btn-sm">Update Contract
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" value="<?php echo $contract_no; ?>" name="hidden_id">
                                        <input type="hidden" value="update" name="action">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-2 order-1 fixed-sidebar table-form">
                            <div class="h6">
                                <b>T. TTTT </b><span class="text-muted" id="qty_no_span"></span><br>
                            </div>
                            <hr>
                            <div class="bottom-buttons">
                                <div class="px-2">
                                    <?php if (isset($_GET['id'])) {
                                        echo '<a href="print/contract?contract_id=' . $_GET['id'] . '" class="btn btn-success w-100 btn-sm" target="_blank"><i class="fa fa-print"></i> Print</a>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
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

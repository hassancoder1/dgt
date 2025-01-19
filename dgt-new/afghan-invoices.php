<?php $pageURL = 'afghan-invoices';
$page_title = 'AFGHAN INV.';
include("header.php");
global $branchId, $userId, $connect;
$get_khaata_id = 0;
$view_url = $removeFilter = $goods_name = $is_cleared = '';
$start = $end = date('Y-m-d');
$is_search = false;
global $connect;
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    $is_search = true;
    $start = isset($_GET['start']) ? mysqli_real_escape_string($connect, $_GET['start']) : '';
    $end = isset($_GET['end']) ? mysqli_real_escape_string($connect, $_GET['end']) : '';
    $get_khaata_id = isset($_GET['get_khaata_id']) ? mysqli_real_escape_string($connect, $_GET['get_khaata_id']) : '';
    $goods_name = isset($_GET['goods_name']) ? mysqli_real_escape_string($connect, $_GET['goods_name']) : '';
    $is_cleared = isset($_GET['is_cleared']) ? mysqli_real_escape_string($connect, $_GET['is_cleared']) : '';
    $params = ['start' => $start, 'end' => $end, 'goods_name' => $goods_name, 'get_khaata_id' => $get_khaata_id];
    foreach ($params as $key => $value) {
        if ($value != '') {
            $pageURL .= (!str_contains($pageURL, '?') ? '?' : '&') . $key . '=' . urlencode($value);
        }
    }
    $view_url = '?&view=1';
} else {
    $view_url = '?view=1';
}
$sql = "SELECT * FROM `afg_invs` WHERE is_active = 1 AND type = 'afg' ORDER BY json_final asc ";
$invoices = mysqli_query($connect, $sql); ?>
<style>
    .vscomp-toggle-button {
        border-radius: 0;
        padding: 4px 8px;
    }
</style>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-md-2">
            <div class="lh-1">
                <b>ROWS: </b><span id="rows_count_span"></span><br>
                <b>AMOUNT: </b><span id="amount_total_span"></span>
            </div>
            <div class="lh-1">
                <b>TT: </b><span id="tt_amount_total_span"></span>
                <br><b>Balance: </b><span id="bal_span"></span>
            </div>
        </div>
        <form name="datesSubmit" method="get">
            <div class="input-group input-group-sm">
                <select name="get_khaata_id" id="get_khaata_id"
                    class="v-select p-0 form-control form-control-sm">
                    <option value="" hidden disabled selected>Importer A/c</option>
                    <?php $accounts_query = fetch('khaata');
                    while ($aa = mysqli_fetch_assoc($accounts_query)) {
                        $sel = $get_khaata_id == $aa['id'] ? 'selected' : '';
                        echo '<option ' . $sel . ' value="' . $aa['id'] . '">' . $aa['khaata_no'] . '</option>';
                    } ?>
                </select>
                <select class="form-select" name="is_cleared" id="is_cleared">
                    <option value="">All</option>
                    <?php $imp_exp_array = array(1 => 'TT Cleared', 0 => 'None Cleared');
                    foreach ($imp_exp_array as $item => $value) {
                        $sel_tran = $is_cleared == $item ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                    } ?>
                </select>
                <input type="date" name="start" id="start" value="<?php echo $start; ?>" class="form-control">
                <input type="date" name="end" id="end" value="<?php echo $end; ?>" class="form-control">
                <?php echo $removeFilter; ?>
            </div>
        </form>
        <div class="d-flex gap-1">
            <?php echo searchInput('a', 'form-control-sm '); ?>
            <?php echo addNew($pageURL . $view_url, '', 'btn-sm'); ?>
            <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                <input type="hidden" name="get_khaata_id" value="<?php echo $get_khaata_id; ?>">
                <input type="hidden" name="is_cleared" value="<?php echo $is_cleared; ?>">
                <button class="btn btn-sm btn-success"><i class="fa fa-print"></i></button>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap text-uppercase">
                                <th>INV#</th>
                                <th>Date</th>
                                <th>FROM</th>
                                <th>TO</th>
                                <th>Qty</th>
                                <th>KGs</th>
                                <th>Goods</th>
                                <th>AMOUNT</th>
                                <th>TT</th>
                                <th>Final Amount</th>
                                <th>Bank Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $row_count = $amount_total = $tt_amount_total = $from_khaata_id = $imp_khaata_id = 0;
                            $from_khaata_no = $imp_khaata_no = '';
                            while ($inv = mysqli_fetch_assoc($invoices)) {
                                $inv_goods_name = '';
                                $qty1 = $kgs = $total_price = 0;
                                $inv_id = $inv['id'];
                                $inv_type = $inv['type'];
                                $inv_json = json_decode($inv['json_data']);
                                $imp_khaata_id = $inv_json->imp_khaata_id;
                                $imp_khaata_no = $inv_json->imp_khaata_no;

                                $from_khaata_id = $inv_json->from_khaata_id ?? 0;
                                $from_khaata_no = $inv_json->from_khaata_no ?? 0;

                                $details_query = fetch('afg_inv_details', array('parent_id' => $inv_id));
                                if (mysqli_num_rows($details_query) > 0) {
                                    while ($details = mysqli_fetch_assoc($details_query)) {
                                        $json_data2 = json_decode($details['json_data']);
                                        $total_price += (int)$json_data2->total_price;
                                        $qty1 += $json_data2->qty1;
                                        $kgs += $json_data2->kgs;
                                        $inv_goods_name = $json_data2->goods;
                                    }
                                    $amount_total += $total_price;
                                }
                                $inv_tt_amount = $inv_final_amount = $inv_curr = $inv_bank_details = '';
                                if (!empty($inv['json_final'])) {
                                    $kkk = json_decode($inv['json_final']);
                                    $inv_tt_amount = $kkk->tt_amount;
                                    $inv_final_amount = $kkk->final_amount;
                                    $inv_curr = $kkk->curr;
                                    $inv_bank_details = $kkk->bank_details;
                                    $tt_amount_total += $inv_tt_amount;
                                }
                                $rowColor = ($inv_tt_amount > 0) ? '' : 'bg-danger ';
                                if ($is_search) {
                                    if ($start != '') {
                                        if ($inv['_date'] < $start) continue;
                                    }
                                    if ($end != '') {
                                        if ($inv['_date'] > $end) continue;
                                    }
                                    if ($get_khaata_id != '') {
                                        if ($get_khaata_id != $imp_khaata_id) continue;
                                    }
                                    if ($is_cleared != '') {
                                        if ($is_cleared == 1) {
                                            if ($inv_tt_amount <= 0) continue;
                                        }
                                        if ($is_cleared == 0) {
                                            if ($inv_tt_amount > 0) continue;
                                        }
                                    }
                                }
                                ++$row_count; ?>
                                <tr class="pointer clickable-row bold <?php echo $rowColor; ?>"
                                    data-href="<?php echo $pageURL . $view_url . '&id=' . $inv_id; ?>">
                                    <td><?php echo $inv_json->no1; ?></td>
                                    <td class="text-nowrap"><?php echo date('y-m-d', strtotime($inv['_date'])); ?></td>
                                    <td><?php echo badge(strtoupper($from_khaata_no), 'dark'); ?>
                                        <?php echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $inv['_from'] . '">' . firstLine($inv['_from']) . '</span>'; ?></td>
                                    <td>
                                        <?php echo badge(strtoupper($imp_khaata_no), 'dark'); ?>
                                        <?php echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $inv['_to'] . '">' . firstLine($inv['_to']) . '</span>'; ?>
                                    </td>
                                    <td><?php echo $qty1 ?></td>
                                    <td><?php echo $kgs; ?></td>
                                    <td class="text-nowrap"><?php echo $inv_goods_name; ?></td>
                                    <td class="fw-bold"><?php echo $total_price > 0 ? $total_price . '<sub>USD</sub>' : ''; ?></td>
                                    <td><?php echo $inv_tt_amount; ?></td>
                                    <td class="fw-bold"><?php echo $inv_final_amount . '<sub>' . $inv_curr . '</sub>'; ?></td>
                                    <td class="fw-normal"><?php echo $inv_bank_details; ?></td>
                                </tr>
                            <?php }
                            $bal = $amount_total - $tt_amount_total; ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="tt_total_price" value="<?php echo $amount_total; ?>">
                    <input type="hidden" id="tt_amount_total" value="<?php echo $tt_amount_total; ?>">
                    <input type="hidden" id="bal" value="<?php echo $bal; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").text($("#row_count").val());
    $("#amount_total_span").text($("#tt_total_price").val());
    $("#tt_amount_total_span").text($("#tt_amount_total").val());
    $("#bal_span").text($("#bal").val());
</script>
<script type="text/javascript">
    $(function() {
        $('#get_khaata_id,#is_cleared, #start,#end').change(function() {
            document.datesSubmit.submit();
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        totalPrice();
        $('#kgs,#unit_price').on('keyup', function() {
            totalPrice();
        });
    });

    function totalPrice() {
        var kgs = parseFloat($("#kgs").val()) || 0;
        var unit_price = parseFloat($("#unit_price").val()) || 0;
        var total_price = kgs * unit_price;
        total_price = total_price.toFixed(2);
        //console.log(total_price);
        $("#total_price").val(total_price);
        if (total_price <= 0 || isNaN(total_price) || !isFinite(total_price)) {
            disableButton('saveInvoiceItemSubmit');
        } else {
            enableButton('saveInvoiceItemSubmit');
        }
    }
</script>
<script type="text/javascript">
    /*disableButton('recordSubmit');*/
    $(document).on('keyup', "#from_khaata_no", function(e) {
        fetchKhaata("#from_khaata_no", "#from_khaata_id", "#from_kd_id", "#from", "#from_khaata_image", "recordSubmit");
    });
    fetchKhaata("#from_khaata_no", "#from_khaata_id", "#from_kd_id", "#from", "#from_khaata_image", "recordSubmit");

    $(document).on('keyup', "#imp_khaata_no", function(e) {
        fetchKhaata("#imp_khaata_no", "#imp_khaata_id", "#imp_kd_id", "#imp", "#s_khaata_image", "recordSubmit");
    });
    fetchKhaata("#imp_khaata_no", "#imp_khaata_id", "#imp_kd_id", "#imp", "#s_khaata_image", "recordSubmit");

    function fetchKhaata(inputField, khaataId, kd_dropdown, prefix, khaataImageId, recordSubmitId) {

        let khaata_no = $(inputField).val();
        let khaata_id_this = 0;
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {
                khaata_no: khaata_no
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    khaata_id_this = response.messages['khaata_id'];
                    $(khaataId).val(khaata_id_this);
                    $(recordSubmitId).prop('disabled', false);
                    $(inputField).addClass('is-valid');
                    $(inputField).removeClass('is-invalid');
                    if (inputField == '#from_khaata_no') {
                        khaataCompanies(khaata_id_this, 'from_kd_id');
                        var kd_id_from = $('#from_kd_id').find(":selected").val();
                        khaataDetailsSingle(kd_id_from, '_from');

                        /*var _from = response.messages['khaata_name'] + '\n' + response.messages['email'] + '\n' + response.messages['phone'] + '\n';
                        $("#_from").val(_from);*/
                    }
                    if (inputField == '#imp_khaata_no') {
                        khaataCompanies(khaata_id_this, 'imp_kd_id');
                        var kd_id1 = $('#imp_kd_id').find(":selected").val();
                        khaataDetailsSingle(kd_id1, '_to');
                    }
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    $(inputField).addClass('is-invalid');
                    $(inputField).removeClass('is-valid');
                    $(khaataId).val(0);
                    $(kd_dropdown).html('<option value="">Invalid A/c.</option>');
                }
            },
            error: function(e) {
                $(inputField).html('<option value="">Invalid A/c.</option>');
            }
        });
    }

    function khaataCompanies(khaata_id, dropdown_id) {
        if (khaata_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/companies_dropdown_by_khaata_id.php',
                data: {
                    khaata_id: khaata_id
                },
                success: function(html) {
                    $('#' + dropdown_id).html(html);
                },
                error: function(xhr, status, error) {
                    //console.error("AJAX call failed:", status, error); // Debugging line
                }
            });
        } else {
            $('#' + dropdown_id).html('<option value="">Invalid A/c.</option>');
        }
    }

    function khaataDetailsSingle(khaata_details_id, dropdown_id) {
        if (khaata_details_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/khaata_details_by_id.php',
                data: {
                    khaata_details_id: khaata_details_id
                },
                success: function(response) {
                    var data = JSON.parse(response)
                    var data_comp = JSON.parse(data[3])
                    console.log(data_comp);
                    var datu = data_comp['company_name'] + '\n' +
                        'Country: ' + data_comp['country'] + '\n' +
                        'City: ' + data_comp['city'] + '\n' +
                        'State: ' + data_comp['state'] + '\n' +
                        'Address: ' + data_comp['address'] + '\n';
                    var indexVals = '';
                    if (data_comp['indexes1'] && data_comp['vals1']) {
                        for (var i = 0; i < data_comp['indexes1'].length; i++) {
                            indexVals += data_comp['indexes1'][i] + ': ' + data_comp['vals1'][i] + '\n';
                        }
                    }
                    $('#' + dropdown_id).val(datu + indexVals);
                },
                error: function(xhr, status, error) {}
            });
        } else {
            $('#' + dropdown_id).val('');
        }
    }

    $(document).ready(function() {
        $('#imp_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), '_to');
            //var kd_id = $(this).val();
        });
        $('#from_kd_id').on('change', function() {
            khaataDetailsSingle($(this).val(), '_from');
            //var kd_id = $(this).val();
        });

    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
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
<?php
if (isset($_GET['view']) && $_GET['view'] == 1) {
    $inv_id = $d_id = 0;
    $sr_no = getAutoIncrement('afg_invs');
    $_fields = array(
        '_date1' => date('Y-m-d'),
        '_date2' => '',
        'from_khaata_no' => '',
        'from_khaata_id' => 0,
        'from_kd_id' => 0,
        '_from' => '',
        'third_party' => 'DAMAAN GENERAL TRADING L.L.C
ADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS
AREA,DEIRA,DUBAI BARI
EMAIL:damaan.dubai@gmail.com
MOBILE NO+971507164963',
        '_to' => '',
        'imp_khaata_no' => '',
        'imp_khaata_id' => 0,
        'imp_kd_id' => 0,
        'no1' => $sr_no,
        'no2' => '',
        'afg' => 'Afghan Transit Form Bill Of Loading',
        'terms' => '',
        'letter' => '',
        'collection' => 'Collection Basis Da Afghaistan Bank',
        'through' => ''
    );
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $inv_id = mysqli_real_escape_string($connect, $_GET['id']);
        $records = fetch('afg_invs', array('id' => $inv_id));
        $record = mysqli_fetch_assoc($records);
        $branch__id = $record['branch_id'];
        $type = $record['type'];
        $json_data = json_decode($record['json_data']);
        $_fields = array(
            '_date1' => $record['_date'],
            '_date2' => $json_data->_date2,
            '_from' => $record['_from'],
            'third_party' => $record['third_party'],
            '_to' => $record['_to'],
            'from_khaata_no' => $json_data->from_khaata_no,
            'from_khaata_id' => $json_data->from_khaata_id,
            'from_kd_id' => $json_data->from_kd_id,
            'imp_khaata_no' => $json_data->imp_khaata_no,
            'imp_khaata_id' => $json_data->imp_khaata_id,
            'imp_kd_id' => $json_data->imp_kd_id,
            'no1' => $json_data->no1,
            'no2' => $json_data->no2,
            'afg' => $json_data->afg,
            'terms' => $record['terms'],
            'letter' => $json_data->letter,
            'collection' => $json_data->collection,
            'through' => $record['through']
        );
    }
    //$('#imp_khaata_no').focus();
    //echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show'); $('#KhaataDetails').animate({ scrollTop: $('#KhaataDetails .modal-dialog').height() }, 500); });</script>";
    echo "<script>document.addEventListener('DOMContentLoaded', function() {
    var khaataDetailsModal = document.getElementById('KhaataDetails');
    var modalInstance = new bootstrap.Modal(khaataDetailsModal);
    modalInstance.show();});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="staticBackdropLabel"><?php echo $page_title . ' #' . $_fields['no1']; ?></h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <form method="post">
                                <div class="row table-form">
                                    <div class="col-md-5">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="input-group ">
                                                <label for="from_khaata_no" class="mb-0">From A/c.</label>
                                                <input type="text" id="from_khaata_no" name="from_khaata_no"
                                                    class="form-control -is-invalid" required
                                                    value="<?php echo $_fields['from_khaata_no']; ?>">
                                                <select class="form-select w-50" name="from_kd_id" id="from_kd_id"
                                                    required>
                                                    <option hidden value="">Company</option>
                                                    <?php $run_query = fetch('khaata_details', array('khaata_id' => $_fields['from_khaata_id'], 'type' => 'company'));
                                                    while ($row = mysqli_fetch_array($run_query)) {
                                                        $row_data = json_decode($row['json_data']);
                                                        $sel_kd1 = $row['id'] == $_fields['from_kd_id'] ? 'selected' : '';
                                                        echo '<option ' . $sel_kd1 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                            <input value="<?php echo $_fields['from_khaata_id']; ?>" type="hidden"
                                                name="from_khaata_id" id="from_khaata_id">
                                        </div>
                                        <div class="mb-2">
                                            <!--<label class="mb-0" for="_from">FROM</label>-->
                                            <textarea class="form-control form-control-sm" name="_from" id="_from"
                                                rows="8" placeholder="From"
                                                required><?php echo $_fields['_from']; ?></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="input-group">
                                                <label for="imp_khaata_no" class="mb-0">Importer A/c.</label>
                                                <input type="text" id="imp_khaata_no" name="imp_khaata_no"
                                                    class="form-control -is-invalid" required
                                                    value="<?php echo $_fields['imp_khaata_no']; ?>">
                                                <select class="form-select w-50" name="imp_kd_id" id="imp_kd_id"
                                                    required>
                                                    <option hidden value="">Company</option>
                                                    <?php $run_query = fetch('khaata_details', array('khaata_id' => $_fields['imp_khaata_id'], 'type' => 'company'));
                                                    while ($row = mysqli_fetch_array($run_query)) {
                                                        $row_data = json_decode($row['json_data']);
                                                        $sel_kd = $row['id'] == $_fields['imp_kd_id'] ? 'selected' : '';
                                                        echo '<option ' . $sel_kd . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                            <input value="<?php echo $_fields['imp_khaata_id']; ?>" type="hidden"
                                                name="imp_khaata_id" id="imp_khaata_id">
                                        </div>
                                        <div class="mb-1">
                                            <!--<label class="mb-0" for="_to">TO</label>-->
                                            <textarea class="form-control form-control-sm" name="_to" id="_to" rows="8"
                                                placeholder="To"
                                                required><?php echo $_fields['_to']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="input-group mb-2">
                                                    <label for="no1">No: </label>
                                                    <input class="form-control" name="no1" id="no1"
                                                        value="<?php echo $_fields['no1']; ?>" required>
                                                </div>
                                                <div class="input-group">
                                                    <label for="_date1">Date: </label>
                                                    <input type="date" class="form-control" name="_date1" id="_date1"
                                                        value="<?php echo $_fields['_date1'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <button name="recordSubmit" id="recordSubmit" type="submit"
                                                    class="btn btn-dark">Save
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <input name="afg" type="text" class="form-control"
                                                value="<?php echo $_fields['afg']; ?>" required>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="input-group">
                                                <label for="no2">No:</label>
                                                <input class="form-control" name="no2" id="no2" required
                                                    value="<?php echo $_fields['no2']; ?>">
                                            </div>
                                            <div class="input-group">
                                                <label for="_date2">Date: </label>
                                                <input type="date" class="form-control" name="_date2" id="_date2"
                                                    value="<?php echo $_fields['_date2']; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label for="terms" class=" mb-0">Terms of payments</label>
                                            <textarea rows="1" class="form-control form-control-sm" name="terms"
                                                id="terms" placeholder="Terms of payments"
                                                required><?php echo $_fields['terms']; ?></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="through" class="mb-0">Through:</label>
                                            <textarea rows="1" class="form-control form-control-sm" name="through"
                                                id="through"
                                                required><?php echo $_fields['through']; ?></textarea>
                                        </div>

                                        <div class="d-flex mb-2">
                                            <div class="input-group">
                                                <label for="letter">Letter Of Credit No:</label>
                                                <input class="form-control" name="letter" id="letter" required
                                                    value="<?php echo $_fields['letter']; ?>">
                                            </div>
                                            <div class="input-group">
                                                <input class="form-control" name="collection" required
                                                    value="<?php echo $_fields['collection']; ?>">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="mb-0" for="third_party">THIRD PARTY</label>
                                            <textarea class="form-control form-control-sm" name="third_party"
                                                id="third_party" rows="5"
                                                placeholder="Third Party"
                                                required><?php echo $_fields['third_party']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="inv_id_hidden" value="<?php echo $inv_id; ?>">
                            </form>
                            <?php $_fields2 = array('qty1' => '', 'qty2' => '', 'qty3' => '', 'kgs' => '', 'goods' => '', 'unit_price' => '', 'total_price' => '');
                            if ($inv_id > 0) {
                                if (isset($_GET['d_id']) && $_GET['d_id'] > 0) {
                                    $d_id = mysqli_real_escape_string($connect, $_GET['d_id']);
                                    $records2 = fetch('afg_inv_details', array('id' => $d_id));
                                    $record2 = mysqli_fetch_assoc($records2);
                                    $json_data2 = json_decode($record2['json_data']);
                                    $_fields2 = array(
                                        'parent_id' => $record2['parent_id'],
                                        'qty1' => $json_data2->qty1,
                                        'qty2' => $json_data2->qty2,
                                        'qty3' => $json_data2->qty3,
                                        'kgs' => $json_data2->kgs,
                                        'goods' => $json_data2->goods,
                                        'unit_price' => $json_data2->unit_price,
                                        'total_price' => $json_data2->total_price,
                                    );
                                    echo "<script>document.addEventListener('DOMContentLoaded', function() {
                                        var khaataDetailsModal = document.getElementById('KhaataDetails');
                                        var qty1 = document.getElementById('qty1');
                                        khaataDetailsModal.addEventListener('shown.bs.modal', function() {
                                            qty1.focus();
                                            var modalBody = khaataDetailsModal.querySelector('.modal-body');
                                            modalBody.scrollTo({top: modalBody.scrollHeight,behavior: 'smooth'});
                                        });
                                    });</script>";
                                } ?>
                                <form method="post">
                                    <div class="row mt-3">
                                        <div class="col">
                                            <style>
                                                .table-form td {
                                                    padding: 0;
                                                }
                                            </style>
                                            <table class="table table-sm table-bordered table-form">
                                                <thead class="table-dark">
                                                    <tr class="text-nowrap">
                                                        <th>Quantity</th>
                                                        <th>KGs</th>
                                                        <th>Description of Goods</th>
                                                        <th>Unit Price USD</th>
                                                        <th>Total Price USD</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 20%">
                                                            <div class="d-flex">
                                                                <input value="<?php echo $_fields2['qty1']; ?>" required
                                                                    placeholder="XXX" name="qty1" id="qty1"
                                                                    class="form-control" <?php echo $d_id > 0 ? 'autofocus' : ''; ?>>
                                                                <input value="<?php echo $_fields2['qty2']; ?>"
                                                                    placeholder="PP BAGS" name="qty2"
                                                                    class="form-control" required>
                                                                <input value="<?php echo $_fields2['qty3']; ?>"
                                                                    placeholder="KGS" name="qty3" class="form-control"
                                                                    required>
                                                            </div>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <input value="<?php echo $_fields2['kgs']; ?>"
                                                                placeholder="TOTAL KGS" name="kgs" id="kgs"
                                                                class="form-control" type="number" step="any" required>
                                                        </td>
                                                        <td><input value="<?php echo $_fields2['goods']; ?>" name="goods"
                                                                class="form-control" required></td>
                                                        <td style="width: 10%">
                                                            <input value="<?php echo $_fields2['unit_price']; ?>"
                                                                placeholder="UNIT PRICE"
                                                                name="unit_price" id="unit_price" class="form-control"
                                                                type="number" step="any" required>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <input readonly value="<?php echo $_fields2['total_price']; ?>"
                                                                name="total_price" id="total_price" class="form-control"
                                                                required>
                                                        </td>
                                                        <td style="width: 4%">
                                                            <input type="hidden" name="d_id_hidden"
                                                                value="<?php echo $d_id; ?>">
                                                            <input type="hidden" name="inv_id_hidden"
                                                                value="<?php echo $inv_id; ?>">
                                                            <button type="submit" id="saveInvoiceItemSubmit"
                                                                name="saveInvoiceItemSubmit"
                                                                class="btn py-0 btn-dark align-self-baseline">Save
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                                </form>
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr class="text-nowrap">
                                            <th style="width: 3%">#</th>
                                            <th style="width: 17%">Quantity</th>
                                            <th style="width: 10%">KGs</th>
                                            <th>Description of Goods</th>
                                            <th style="width: 10%">Unit Price USD</th>
                                            <th style="width: 10%">Total Price USD</th>
                                            <th style="width: 3%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $x = $grand = 0;
                                        $ddd = fetch('afg_inv_details', array('parent_id' => $inv_id));
                                        while ($temp = mysqli_fetch_assoc($ddd)) {
                                            ++$x;
                                            $temp_json = json_decode($temp['json_data']);
                                            echo '<tr class="pointer-clickable-row">';
                                            echo '<td>' . $x . '</td>';
                                            echo '<td><a class="text-dark" href="' . $pageURL . '?view=1&id=' . $inv_id . '&d_id=' . $temp['id'] . '">';
                                            echo $temp_json->qty1 . ' ' . $temp_json->qty2 . ' ' . $temp_json->qty3;
                                            echo '</a></td>';
                                            echo '<td>' . $temp_json->kgs . '</td>';
                                            echo '<td>' . $temp_json->goods . '</td>';
                                            echo '<td>' . $temp_json->unit_price . '</td>';
                                            echo '<td>' . $temp_json->total_price . '</td>';
                                            echo '<td class="text-center">'; ?>
                                            <form method="post" onsubmit="return confirm('Are you sure to delete?')">
                                                <input type="hidden" name="inv_id_hidden" value="<?php echo $inv_id; ?>">
                                                <input type="hidden" name="d_id_hidden" value="<?php echo $temp['id']; ?>">
                                                <button class="btn btn-sm btn-outline-danger py-0 px-1" type="submit"
                                                    data-bs-toggle="tooltip" data-bs-title="Delete record."
                                                    name="deleteSDSubmit">
                                                    <i class="fa fa-trash-alt"></i></button>
                                            </form>
                                        <?php echo '</td>';
                                            echo '</tr>';
                                            $grand += (int)$temp_json->total_price;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="4"></td>
                                            <td>Total</td>
                                            <td><?php echo $grand; ?></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="card bg-light">
                                    <div class="card-body ">
                                        <?php $json_final = array(
                                            't_date' => date('Y-m-d'),
                                            'tt_amount' => $grand,
                                            'final_amount' => '',
                                            'curr' => '',
                                            'bank_details' => ''
                                        );
                                        if (!empty($record['json_final'])) {
                                            $jjj = json_decode($record['json_final']);
                                            $json_final = array(
                                                't_date' => $jjj->t_date,
                                                'tt_amount' => $jjj->tt_amount,
                                                'final_amount' => $jjj->final_amount,
                                                'curr' => $jjj->curr,
                                                'bank_details' => $jjj->bank_details
                                            );
                                        } ?>
                                        <form method="post">
                                            <div class="row gx-1 gy-3 table-form">
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <label for="t_date">Date</label>
                                                        <input value="<?php echo $json_final['t_date']; ?>" type="date"
                                                            id="t_date" name="t_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group">
                                                        <label for="tt_amount">TT. Amount</label>
                                                        <input type="text" name="tt_amount" id="tt_amount"
                                                            class="form-control"
                                                            value="<?php echo $json_final['tt_amount']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="input-group">
                                                        <label for="final_amount">Final Amount</label>
                                                        <input type="text" name="final_amount" id="final_amount"
                                                            class="form-control"
                                                            value="<?php echo $json_final['final_amount']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <label for="curr">Currency</label>
                                                        <select id="curr" name="curr" class="form-select" required>
                                                            <option selected hidden disabled value="">Select</option>
                                                            <?php $currencies = fetch('currencies');
                                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                $crr_sel = $crr['name'] == $json_final['curr'] ? 'selected' : '';
                                                                echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="input-group">
                                                        <label for="bank_details">Bank Details</label>
                                                        <input type="text" name="bank_details" id="bank_details"
                                                            class="form-control"
                                                            value="<?php echo $json_final['bank_details']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <button name="invFinalSubmit" type="submit"
                                                        class="btn btn-dark btn-sm w-100">Save
                                                    </button>
                                                </div>
                                                <input type="hidden" name="inv_id_hidden" value="<?php echo $inv_id ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-2 order-1 fixed-sidebar table-form">
                            <div class="bottom-buttons">
                                <div class="px-2">
                                    <?php echo $inv_id > 0 && $d_id > 0 ? '<a class="btn btn-sm w-100 btn-dark mt-3" href="' . $pageURL . '?view=1&id=' . $inv_id . '"><i class="fa fa-long-arrow-alt-left"></i> Back</a>' : '';
                                    echo $inv_id > 0 ? '<a href="print/afg-invoice?id=' . base64_encode($inv_id) . '&secret=' . base64_encode('powered-by-upsol') . '&type=' . base64_encode('afg') . '" class="btn btn-success btn-sm w-100 mt-3">PRINT</a>' : ''; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
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
        $info['msg'] = 'Afghan invoice saved.';
    }
    messageNew($info['type'], $url, $info['msg']);
}
if (isset($_POST['saveInvoiceItemSubmit'])) {
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
    messageNew($info['type'], $url, $info['msg']);
}
if (isset($_POST['invFinalSubmit'])) {
    $inv_id_hidden = mysqli_real_escape_string($connect, $_POST['inv_id_hidden']);
    unset($_POST['invFinalSubmit']);
    $json_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $data = array('json_final' => $json_data);
    $url = $pageURL . '?view=1&id=' . $inv_id_hidden;
    $info = array('type' => 'danger', 'msg' => 'There is some System Error :(');
    $done = update('afg_invs', $data, array('id' => $inv_id_hidden));
    if ($done) {
        $info['type'] = 'success';
        $info['msg'] = 'AFG invoice transferred.';
    }
    messageNew($info['type'], $url, $info['msg']);
}
if (isset($_POST['deleteSDSubmit'])) {
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
    messageNew($type, $url, $msg);
} ?>
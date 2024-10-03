<?php $pageURL = 'draft-invoices';
$page_title = 'DRAFT INVOICES';
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
$sql = "SELECT * FROM `afg_invs` WHERE is_active = 1 AND type = 'draft' ORDER BY id asc ";
$invoices = mysqli_query($connect, $sql); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex table-form text-nowrap align-items-center justify-content-between">
            <?php echo addNew($pageURL . $view_url, 'NEW', 'btn-sm'); ?>
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <!--<div><b>QTY: </b><span id="p_qty_total_span"></span></div>
            <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>-->
            <form action="print/<?php echo $pageURL; ?>" target="_blank" method="get">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                <!--<button class="btn btn-sm btn-success">PRINT</button>-->
            </form>
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
                        <tr class="text-nowrap">
                            <th style="width: 6%">#</th>
                            <th>FROM</th>
                            <th>THIRD PARTY</th>
                            <th>TO</th>
                            <th style="width: 10%">AMOUNT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $total_price = 0;
                        while ($inv = mysqli_fetch_assoc($invoices)) {
                            $inv_id = $inv['id'];
                            $inv_type = $inv['type'];
                            $inv_json = json_decode($inv['json_data']);
                            $details_query = fetch('afg_inv_details', array('id' => $inv_id));
                            if (mysqli_num_rows($details_query) > 0) {
                                $details = mysqli_fetch_assoc($details_query);
                                $json_data2 = json_decode($details['json_data']);
                                $total_price += $json_data2->total_price;
                            }
                            $rowColor = '';
                            if ($is_search) {
                                if ($start != '') {
                                    if ($inv['_date'] < $start) continue;
                                }
                                if ($end != '') {
                                    if ($inv['_date'] > $end) continue;
                                }
                            } ?>
                            <tr class="pointer clickable-row <?php echo $rowColor; ?>"
                                data-href="<?php echo $pageURL . $view_url . '&id=' . $inv_id; ?>">
                                <td class="text-nowrap font-size-11">
                                    <?php echo '<b>INV#</b>' . $inv_json->no1;;
                                    echo '<br><b>D.</b>' . date('y-m-d', strtotime($inv['_date'])); ?>
                                </td>
                                <td class="font-size-11"><?php echo nl2br($inv['_from']); ?></td>
                                <td class="font-size-11"><?php echo nl2br($inv['third_party']); ?></td>
                                <td class="font-size-11"><?php echo nl2br($inv['_to']); ?></td>
                                <td class="bold"><?php echo $total_price>0 ? $total_price . ' USD' : ''; ?></td>
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
        'type' => 'draft',
        '_date' => $_POST['_date1'],
        'json_data' => $json_data,
        'terms' => $terms,
        'through' => $through
    );
    $url = 'draft-invoices?view=1';
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
    $inv_id = $d_id = 0;
    $sr_no = getAutoIncrement('afg_invs');
    $_fields = array('_date1' => date('Y-m-d'), '_date2' => date('Y-m-d'),
        '_from' => '', 'third_party' => '', '_to' => '', 'no1' => $sr_no, 'no2' => '',
        'afg' => 'Afghan Transit Form Bill Of Loading', 'terms' => '', 'letter' => '',
        'collection' => 'Collection Basis Da Afghaistan Bank', 'through' => ''
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
            'no1' => $json_data->no1,
            'no2' => $json_data->no2,
            'afg' => $json_data->afg,
            'terms' => $record['terms'],
            'letter' => $json_data->letter,
            'collection' => $json_data->collection,
            'through' => $record['through']
        );
    }
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show'); $('#imp_khaata_no').focus();});</script>"; ?>
    <div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><?php echo $page_title. ' #'.$_fields['no1']; ?></h5>
                    <a href="<?php echo $pageURL; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body bg-light pt-0">
                    <div class="row">
                        <div class="col-10 order-0 content-column">
                            <form method="post">
                                <div class="row small">
                                    <div class="col-5">
                                        <label class="mb-0" for="_from">FROM</label>
                                        <textarea class="form-control" name="_from" id="_from" rows="4"
                                                  placeholder="From"
                                                  required><?php echo $_fields['_from']; ?></textarea>
                                        <label class="mb-0" for="third_party">THIRD PARTY</label>
                                        <textarea class="form-control" name="third_party" id="third_party" rows="4"
                                                  placeholder="Third Party"
                                                  required><?php echo $_fields['third_party']; ?></textarea>
                                        <label class="mb-0" for="_to">TO</label>
                                        <textarea class="form-control" name="_to" id="_to" rows="4" placeholder="To"
                                                  required><?php echo $_fields['_to']; ?></textarea>
                                    </div>
                                    <div class="col table-form">
                                        <?php //echo '<b>No: </b> ' . $sr_no; ?>
                                        <div class="input-group w-25 mb-1">
                                            <label for="no1" class="input-group-text mb-0">No: </label>
                                            <input class="form-control" name="no1" id="no1"
                                                   value="<?php echo $_fields['no1']; ?>" required>
                                        </div>
                                        <div class="input-group w-50">
                                            <label for="_date1" class="input-group-text mb-0">Date: </label>
                                            <input type="date" class="form-control" name="_date1" id="_date1"
                                                   value="<?php echo $_fields['_date1'] ?>" required>
                                        </div>
                                        <div class="my-2">
                                            <input name="afg" type="text" class="form-control"
                                                   value="<?php echo $_fields['afg']; ?>" required>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="input-group">
                                                <label for="no2" class="input-group-text mb-0">No:</label>
                                                <input class="form-control" name="no2" id="no2" required
                                                       value="<?php echo $_fields['no2']; ?>">
                                            </div>
                                            <div class="input-group">
                                                <label for="_date2" class="input-group-text mb-0">Date: </label>
                                                <input type="date" class="form-control" name="_date2" id="_date2"
                                                       value="<?php echo $_fields['_date2']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="mb-2 text-center">
                                            <label for="terms" class=" mb-0">Terms of payments</label>
                                            <textarea style="height: 50px" class="form-control" name="terms" id="terms"
                                                      required
                                                      placeholder="Terms of payments"><?php echo $_fields['terms']; ?></textarea>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="input-group">
                                                <label for="letter" class="input-group-text mb-0">Letter Of Credit
                                                    No:</label>
                                                <input class="form-control" name="letter" id="letter" required
                                                       value="<?php echo $_fields['letter']; ?>">
                                            </div>
                                            <div class="input-group">
                                                <input class="form-control" name="collection" required
                                                       value="<?php echo $_fields['collection']; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label for="through" class="mb-0">Through:</label>
                                            <textarea style="height: 50px" class="form-control" name="through"
                                                      id="through"
                                                      required><?php echo $_fields['through']; ?></textarea>
                                        </div>
                                        <button name="recordSubmit" id="recordSubmit" type="submit"
                                                class="btn btn-primary btn-sm">Save & Go Next <i
                                                    class="fa fa-long-arrow-alt-right"></i></button>
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
                                } ?>
                                <form method="post">
                                    <div class="row mt-3">
                                        <div class="col">
                                            <table class="table table-sm table-bordered table-form">
                                                <thead>
                                                <tr>
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
                                                                   placeholder="XXX" name="qty1" class="form-control">
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
                                                                class="btn btn-sm btn-secondary">Save
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                                </form>
                                <table class="table table-sm table-bordered table-hover table-form">
                                    <thead class="table-secondary">
                                    <tr>
                                        <th style="width: 3%">#</th>
                                        <th style="width: 17%">Quantity</th>
                                        <th style="width: 10%">KGs</th>
                                        <th>Description of Goods</th>
                                        <th style="width: 10%">Unit Price USD</th>
                                        <th style="width: 10%">Total Price USD</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x = 0;
                                    $ddd = fetch('afg_inv_details', array('parent_id' => $inv_id));
                                    while ($temp = mysqli_fetch_assoc($ddd)) {
                                        ++$x;
                                        $temp_json = json_decode($temp['json_data']);
                                        echo '<tr class="pointer-clickable-row">';
                                        echo '<td>' . $x . '</td>';
                                        echo '<td><a href="' . $pageURL . '?view=1&id=' . $inv_id . '&d_id=' . $temp['id'] . '">';
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
                                            <button class="p-0 btn" type="submit" name="deleteSDSubmit"><i
                                                        class="fa fa-trash"></i></button>
                                        </form>
                                        <?php echo '</td>';
                                        echo '</tr>';
                                    } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                        <div class="col-2 order-1 fixed-sidebar table-form">
                            <div class="bottom-buttons">
                                <div class="px-2">
                                    <?php echo $inv_id > 0 && $d_id > 0 ? '<a class="btn btn-sm w-100 btn-dark mt-3" href="' . $pageURL . '?view=1&id=' . $inv_id . '"><i class="fa fa-long-arrow-alt-left"></i> Back</a>' : '';
                                    echo $inv_id > 0 ? '<a href="print/afg-invoice?id=' . base64_encode($inv_id) . '&secret=' . base64_encode('powered-by-upsol') . '&type=' . base64_encode('draft') . '" class="btn btn-success btn-sm w-100 mt-3" target="_blank">PRINT</a>' : ''; ?>
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
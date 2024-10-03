<?php require_once '../connection.php';
$sr_no = getAutoIncrement('sales');
$action_hidden = 'insert';
$currency1 = 'AED';
$s_date = date('Y-m-d');
$p_acc = $s_name = $receiver = $size = $brand = $is_qty = $type = $city = $qty_name = $divide = $price = $currency1 = $currency2 = $report = '';
$branch__id = $sd_id = $sale_id = $goods_id = $wh_k_id = $wh_kd_id = $qty_no = $qty_kgs = $total_kgs = $empty_kgs = $total_qty_kgs = $net_kgs = $weight = $total = $rate1 = $amount = $rate2 = $opr = $final_amount = 0;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action_hidden = 'update';
    $sale_id = $sr_no = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('sales', array('id' => $sale_id));
    $record = mysqli_fetch_assoc($records);
    $branch__id = $record['branch_id'];
    $type = $record['type'];
    $s_date = $record['s_date'];
    $city = $record['city'];
    $s_name = $record['s_name'];
    $p_acc = $record['p_khaata_no'];
    $receiver = $record['receiver'];
    $report = $record['report'];
    if (isset($_GET['action'])) {
        $action_hidden = $action = mysqli_real_escape_string($connect, $_GET['action']);
        $add_details = $action == 'add_details';
        if (isset($_GET['sd_id']) && $_GET['sd_id'] > 0) {
            $update_details = $action == 'update_details';
            $sd_id = mysqli_real_escape_string($connect, $_GET['sd_id']);
            $records2 = fetch('sale_details', array('id' => $sd_id));
            $record2 = mysqli_fetch_assoc($records2);
            $goods_id = $record2['goods_id'];
            $size = $record2['size'];
            $brand = $record2['brand'];
            $wh_k_id = $record2['wh_k_id'];
            $wh_kd_id = $record2['wh_kd_id'];
            $qty_name = $record2['qty_name'];
            $divide = $record2['divide'];
            $price = $record2['price'];
            $currency1 = $record2['currency1'];
            $currency2 = $record2['currency2'];
            $qty_no = $record2['qty_no'];
            $qty_kgs = $record2['qty_kgs'];
            $total_kgs = $record2['total_kgs'];
            $empty_kgs = $record2['empty_kgs'];
            $total_qty_kgs = $record2['total_qty_kgs'];
            $net_kgs = $record2['net_kgs'];
            $weight = $record2['weight'];
            $total = $record2['total'];
            $rate1 = $record2['rate1'];
            $amount = $record2['amount'];
            $is_qty = $record2['is_qty'];
            $rate2 = $record2['rate2'];
            $opr = $record2['opr'];
            $final_amount = $record2['final_amount'];
            $is_qty = $record2['is_qty'] == 1 ? 'checked' : '';
        }
    }
}
$topArray = array(array('heading' => 'SALE DATE ', 'value' => date('d-M-Y'), 'id' => ''), array('heading' => 'SALE BILL# ', 'value' => $sr_no, 'id' => ''));
 ?>
<div class="row">
    <div class="col-10 order-0 content-column">
        <?php echo $_SESSION['response'] ?? ''; ?>
        <div class="row pt-3 gx-2 text-uppercase small">
            <div class="col-3">
                <?php foreach ($topArray as $item) {
                    echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                } ?>
            </div>

        </div>
    </div>
    <div class="col-2 order-1 fixed-sidebar table-form">
        <div>
            <form id="purchaseAttachSubmit" method="post" enctype="multipart/form-data">
                <input type="hidden" name="s_id_hidden_attach" value="<?php echo $sale_id; ?>">
                <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                <input type="button" class="form-control rounded-1 bg-dark mt-3 text-white"
                       value="+ Documents" onclick="document.getElementById('attachments').click();"/>
            </form>
            <script>
                document.getElementById("attachments").onchange = function () {
                    document.getElementById("purchaseAttachSubmit").submit();
                }
            </script>
            <?php $attachments = fetch('attachments', array('source_id' => $sale_id, 'source_name' => 'sales'));
            if (mysqli_num_rows($attachments) > 0) {
                //echo '<h6 class=" mb-0 d-inline fw-bold">Documents </h6>';
                $no = 1;
                while ($attachment = mysqli_fetch_assoc($attachments)) {
                    $link = 'attachments/' . $attachment['attachment'];
                    echo $no . '.<a class="text-decoration-underline" href="' . $link . '" target="_blank">' . readMore($attachment['attachment'], 27) . '</a><br>';
                    $no++;
                }
            } ?>
        </div>
        <div class="bottom-buttons">
            <div class="px-2">
                <a href="print/sales-invoice?s_id=<?php echo $sale_id; ?>&action=booking" target="_blank"
                   class="btn btn-success btn-sm w-100 mt-3">PRINT</a>
            </div>
        </div>
    </div>
</div>

<script>
    disableButton('recordSubmitSeller');
    $(document).on('keyup', "#khaata_no", function (e) {
        fetchKhaata();
    });
    fetchKhaata();

    function fetchKhaata() {
        let khaata_no = $("#khaata_no").val();
        let khaata_id = $("#khaata_id");
        var prefix = '#s';
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton('recordSubmitSeller');
                    khaata_id.val(response.messages['khaata_id']);
                    $(prefix + '_khaata_no').text(khaata_no);
                    $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                    $(prefix + '_b_name').text(response.messages['b_code']);
                    $(prefix + '_c_name').text(response.messages['name']);
                    $(prefix + '_business_name').text(response.messages['business_name']);
                    $(prefix + '_address').text(response.messages['address']);
                    $(prefix + '_comp_name').text(response.messages['comp_name']);
                    var details = {indexes: response.messages['indexes'], vals: response.messages['vals']};
                    $(prefix + '_contacts').html(displayKhaataDetails(details));
                    //$(khaataImageId).attr("src", response.messages['image']);
                    /*var seller_details = '<b>A/C Name</b>' + response.messages['khaata_name'] + response.messages['comp_name'];
                    $("#seller-details").html(seller_details);*/
                    $("#recordSubmitSeller").prop('disabled', false);
                    $("#response").text('');
                }
                if (response.success === false) {
                    $(prefix + '_khaata_no').text('');
                    $(prefix + '_khaata_name').text('');
                    $(prefix + '_b_name').text('');
                    $(prefix + '_c_name').text('');
                    $(prefix + '_business_name').text('');
                    $(prefix + '_address').text('');
                    $(prefix + '_comp_name').text('');
                    $(prefix + '_contacts').html('');
                    disableButton('recordSubmitSeller');
                    $("#response").text('INVALID');
                    khaata_id.val(0);
                }
            }
        });
    }

    function displayKhaataDetails(details) {
        var html = '';
        if (details.indexes && details.vals) {
            var indexes = JSON.parse(details.indexes);
            var vals = JSON.parse(details.vals);

            if (Array.isArray(indexes) && Array.isArray(vals)) {
                var count = Math.min(indexes.length, vals.length);

                for (var i = 0; i < count; i++) {
                    var key = indexes[i];
                    var value = vals[i];
                    // Construct the HTML string
                    html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                }
            }
        }

        return html; // Return the constructed HTML string
    }
</script>
<script>
    toggleLoadingAndRequired();
    $("#is_loading").change(toggleLoadingAndRequired);

    function toggleLoadingAndRequired() {
        var $toggleLoading = $(".toggleLoading");
        var $is_qty2 = $("#is_loading");
        if ($is_qty2.is(":checked")) {
            $toggleLoading.show();
            $("#l_country, #l_port, #l_date, #ctr_name").attr('required', true);
        } else {
            $toggleLoading.hide();
            $("#l_country, #l_port, #l_date, #ctr_name").attr('required', false);
        }
    }

    toggleReceivingAndRequired();
    $("#is_receiving").change(toggleReceivingAndRequired);

    function toggleReceivingAndRequired() {
        var $toggleReceiving = $(".toggleReceiving");
        var $is_receiving = $("#is_receiving");
        if ($is_receiving.is(":checked")) {
            $toggleReceiving.show();
            $("#r_country, #r_port, #r_date, #arrival_date").attr('required', true);
        } else {
            $toggleReceiving.hide();
            $("#r_country, #r_port, #r_date, #arrival_date").attr('required', false);
        }
    }
</script>
<script type="text/javascript">
    function addReportRow() {
        $("#addReportRowBtn").button("loading");
        var tableLength = $("#reportsTable tbody tr").length;
        var tableRow;
        var arrayNumber;
        var count;
        if (tableLength > 0) {
            tableRow = $("#reportsTable tbody tr:last").attr('id');
            arrayNumber = $("#reportsTable tbody tr:last").attr('class');
            count = parseInt(tableRow.match(/\d+/)[0], 10) + 1;
            arrayNumber = Number(arrayNumber) + 1;
        } else {
            count = 1;
            arrayNumber = 0;
        }
        console.log(count);
        $("#addReportRowBtn").button("reset");
        $.ajax({
            type: 'GET',
            url: 'ajax/fetchStaticTypesForPurchaseAddReports.php',
            success: function (html) {
                $('#rep_indexes' + count).html(html);
            }
        });
        var tr = '<tr id="rep_row' + count + '" class="' + arrayNumber + '">' +
            '<td style="width: 20%">' +
            '<select id="rep_indexes' + count + '" name="rep_indexes[]" class="form-select">' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="rep_vals[]" required class="form-control" id="rep_vals' + count + '">' +
            '</td>' +
            '<td style="width: 5%">' +
            '<span id="removeReportRowBtn' + count + '" class="btn btn-link text-danger p-1" onclick="removeReportRow(' + count + ')">DELETE</span>' +
            '</td>' +
            '</tr>';
        if (tableLength > 0) {
            $("#reportsTable tbody tr:last").after(tr);
        } else {
            $("#reportsTable tbody").append(tr);
        }
    }

    function removeReportRow(row = null) {
        if (row) {
            var tableLength = $("#reportsTable tbody tr").length;
            if (tableLength > 1) {
                $("#rep_row" + row).remove();
            }
            //subAmount();
        } else {
            alert('error! Refresh the page again');
        }
    }
</script>
<script>
    $(document).ready(function () {
        function hidePctInputs() {
            $("#pct,#pct_label, #pct_amt").hide();
        }

        function showPctInputs() {
            $("#pct,#pct_label, #pct_amt").show();
        }

        function updatePctAmt() {
            var pctValue = parseFloat($("#pct").val()) || 0;
            var finalAmt = parseFloat($("#final_amt_hidden").val()) || 0;
            var pctAmt = (pctValue / 100) * finalAmt;

            $("#pct_amt").val(pctAmt.toFixed(2));
        }

        // Initialize: Hide pct inputs
        hidePctInputs();

        // Show/hide pct inputs based on the selected option
        $("#transfer").change(function () {
            showHidePctInputs($(this).val());
        });

        function showHidePctInputs(transfer = null) {
            if (transfer == "1") {
                showPctInputs();
            } else {
                hidePctInputs();
            }
        }

        let transfer = $('#transfer').find(":selected").val();
        showHidePctInputs(transfer);

        // Update pct_amt when user inputs a number in pct
        $("#pct").on("input", updatePctAmt);

    });
</script>
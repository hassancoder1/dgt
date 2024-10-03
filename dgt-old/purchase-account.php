<?php $page_title = 'Purchase Account';
include("header.php"); ?>
<div class="row">
    <div class="col-lg-12 ">
        <div class="d-flex justify-content-between gap-md-4">
            <?php $bkn = isset($_GET['back-khaata-no']) ? mysqli_real_escape_string($connect, $_GET['back-khaata-no']) : ''; ?>
            <div class="table-form">
                <div class="input-group position-relative ">
                    <label for="khaata_no">A/C</label>
                    <input type="text" id="khaata_no" class="form-control form-control-sm inputFilter" autofocus
                           placeholder="A/c (F2)" value="<?php echo $bkn; ?>" autocomplete="off">
                    <small id="response" class="error-response"></small>
                </div>
            </div>
            <form id="datesBranchForm" class="table-form flex-fill">
                <div class="input-group">
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="<?php //echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                    <!--<label for="start_date"> TO </label>-->
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="<?php //echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                    <select id="goods_id" name="goods_id" class="form-select" onchange="dataBranchDates();">
                        <option value="0">ALL GOODS</option>
                        <?php $goods = fetch('goods');
                        while ($good = mysqli_fetch_assoc($goods)) {
                            //$g_selected = $good['name'] == $goods_name ? 'selected' : '';
                            echo '<option  value="' . $good['id'] . '">' . $good['name'] . '</option>';
                        } ?>
                    </select>
                    <select class="form-select" name="size" id="size" onchange="dataBranchDates();">
                        <option value="">ALL SIZE</option>
                        <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM `good_details` ");
                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                            echo '<option value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                        } ?>
                    </select>
                    <select class="form-select" name="allot" id="allot" onchange="dataBranchDates();">
                        <option value="">ALL ALLOT NAME</option>
                        <?php $allotNames = getAllotNames();
                        foreach ($allotNames as $allotName) {
                            echo '<option value="' . $allotName . '">' . $allotName . '</option>';
                        } ?>
                    </select>
                </div>
                <input type="hidden" id="khaat_id_bottom">
            </form>
            <form action="print/purchase-account" method="post" id="printLedgerForm" target="_blank" class="">
                <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                <input type="hidden" name="khaata_id" id="khaata_id_print" value="0">
                <input type="hidden" name="goods_id" id="goods_id_print" value="0">
                <input type="hidden" name="size" id="size_print" value="">
                <input type="hidden" name="allot" id="allot_print" value="">
                <input type="hidden" name="action" id="action_print" value="false">
                <input type="hidden" name="start_date" id="start_date_print" value="">
                <input type="hidden" name="end_date" id="end_date_print" value="">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="printSizeRadio" id="lg" value="lg">
                    <label data-bs-toggle="tooltip" data-bs-original-title="Landscape (Large)" data-bs-placement="left"
                           class="btn btn-success btn-sm mb-0" for="lg"><i class="fa fa-print"></i></label>
                    <input type="radio" class="btn-check" name="printSizeRadio" id="lg" value="lg">
                    <label data-bs-toggle="tooltip" data-bs-original-title="Portrait (Small)" data-bs-placement="left"
                           class="btn btn-primary btn-sm mb-0" for="sm"><i class="fa fa-print"></i></label>
                    <input type="radio" class="btn-check" name="printSizeRadio" id="sm" value="sm">
                </div>
            </form>
        </div>
        <div class="d-flex justify-content-between align-items-start gap-md-3 text-uppercase small">
            <div class="flex-fill card p-1 mb-1">
                <table class="table table-borderless table-sm mb-0">
                    <thead>
                    <tr class="text-nowrap">
                        <td class="pb-0 fw-bold">Rows</td>
                        <td class="pb-0" id="rows_span"></td>
                        <td class="pb-0 fw-bold">Qty</td>
                        <td class="pb-0" id="total_qty_span"></td>
                    </tr>
                    <tr class="text-nowrap">
                        <td class="pb-0 fw-bold">KGs</td>
                        <td class="pb-0" id="total_kgs_span"></td>
                        <td class="pb-0 fw-bold">Amount</td>
                        <td class="pb-0" id="total_amount_span"></td>
                    </tr>
                    <tr class="text-nowrap">
                        <td class="pb-0 fw-bold">Net KGs</td>
                        <td class="pb-0" id="net_kgs_span"></td>
                        <td class="pb-0 fw-bold">F.Amount</td>
                        <td class="pb-0" id="total_final_amount_span"></td>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="w-75" style="">
                <div class="card mb-1 position-relative">
                    <!--<div class="info-div">Account</div>-->
                    <div class="d-flex justify-content-between gap-2 p-1">
                        <div>
                            <?php $array_acc1 = array(array('label' => 'A/C#', 'id' => 'khaata_no1'), array('label' => 'A/C NAME', 'id' => 'khaata_name'),
                                /*array('label' => 'BRANCH', 'id' => 'b_name'), array('label' => 'CATEGORY', 'id' => 'c_name')*/
                            );
                            $array_acc2 = array(array('label' => 'BUSINESS NAME', 'id' => 'business_name'), array('label' => 'ADDRESS', 'id' => 'address'), array('label' => 'COMPANY', 'id' => 'comp_name'));
                            $array_acc3 = array(array('label' => '', 'id' => 'contacts'));
                            foreach ($array_acc1 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span><br>';
                            } ?>
                            <b>B.</b><span class="text-muted" id="b_name"></span>
                            <b>CAT.</b><span class="text-muted" id="c_name"></span>
                        </div>
                        <div>
                            <?php foreach ($array_acc2 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span><br>';
                            } ?>
                        </div>
                        <div>
                            <?php foreach ($array_acc3 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span>';
                            } ?>
                        </div>
                        <div>
                            <img id="khaata_image" src="assets/images/logo-placeholder.png"
                                 class=" rounded shadow" alt="Image" style="height: 3.4rem; width: 3.4rem">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 64dvh;">
                    <table class="table mb-0 table-bordered fix-head-table table-sm">
                        <thead>
                        <tr class="text-nowrap text-uppercase">
                            <th>P#</th>
                            <th>Allot</th>
                            <th>Bill#</th>
                            <th>Date</th>
                            <th>Importer Name</th>
                            <th>Type</th>
                            <th>Bail#</th>
                            <th>Container#</th>
                            <th>Goods Name</th>
                            <th>Size</th>
                            <th>Qty Name</th>
                            <th>Qty#</th>
                            <th>Gross KG</th>
                            <th>Net KG</th>
                            <th>Per Price</th>
                            <th>Amount</th>
                            <th>Exch. Name</th>
                            <th>Exch. Rate</th>
                            <th>F. Amount</th>
                        </tr>
                        </thead>
                        <tbody id="ledger-table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $(document).on('keyup', "#khaata_no", function (e) {
        fetchKhaata();
    });
    fetchKhaata();
    $("#khaata_print_btn").hide();
    $("#start_date").attr('disabled', 'disabled');
    $("#end_date").attr('disabled', 'disabled');
    $("#goods_id").attr('disabled', 'disabled');
    $("#size").attr('disabled', 'disabled');
    $("#allot").attr('disabled', 'disabled');

    function fetchKhaata() {
        let khaata_no = $("#khaata_no").val();
        $("#datesBranchForm")[0].reset();
        $("#printLedgerForm")[0].reset();
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#khaata_id").val(response.messages['khaata_id']);
                    $("#khaata_print_btn").show();
                    $("#khaata_id_print").val(response.messages['khaata_id']);
                    $("#khaata_no1").text(khaata_no);
                    $("#c_name").text(response.messages['name']);
                    $("#b_name").text(response.messages['b_name']);
                    $("#khaata_name").text(response.messages['khaata_name']);
                    $("#comp_name").text(response.messages['comp_name']);
                    $("#business_name").text(response.messages['business_name']);
                    $("#address").text(response.messages['address']);
                    var details = {
                        indexes: response.messages['indexes'],
                        vals: response.messages['vals']
                    };
                    $("#contacts").html(displayKhaataDetails(details));
                    $("#khaata_image").attr("src", response.messages['image']);
                    $("#start_date").removeAttr('disabled');
                    $("#end_date").removeAttr('disabled');
                    $("#goods_id").removeAttr('disabled');
                    $("#size").removeAttr('disabled');
                    $("#allot").removeAttr('disabled');
                    //$(':input[type="submit"]').prop('disabled', false);
                    $("#response").text('');
                    var khaata_id = response.messages['khaata_id'];
                    $("#khaat_id_bottom").val(khaata_id);
                    //alert(khaata_id);
                    $.ajax({
                        url: 'ajax/fetchPurchasesAccountWise.php',
                        type: 'post',
                        data: {khaata_id: khaata_id},
                        dataType: 'json',
                        success: function (data) {
                            //$("#khaata_no").blur();
                            //$("#start_date").focus();
                            //console.log(data['bottomData']);
                            $("#ledger-table").html(data['tableData']);
                            $("#rows_span").text(data['bottomData'][0]);

                            $("#total_qty_span").text(data['bottomData'][1].toFixed(2));
                            $("#total_kgs_span").text(data['bottomData'][2].toFixed(2));
                            $("#net_kgs_span").text(data['bottomData'][3]);
                            $("#total_amount_span").text(data['bottomData'][4]);
                            $("#total_final_amount_span").text(data['bottomData'][5]);
                        }
                    });
                }
                if (response.success === false) {
                    $("#khaata_print_btn").hide();
                    $("#start_date").attr('disabled', 'disabled');
                    $("#end_date").attr('disabled', 'disabled');
                    $("#goods_id").attr('disabled', 'disabled');
                    $("#size").attr('disabled', 'disabled');
                    $("#allot").attr('disabled', 'disabled');
                    $("#response").text('INVALID');
                    $("#khaata_id").val(0);

                    $("#khaata_no1").text('---');
                    $("#c_name").text('---');
                    $("#b_name").text('---');
                    $("#khaata_name").text('---');
                    $("#comp_name").text('---');
                    $("#business_name").text('---');
                    $("#address").text('---');
                    $("#contacts").text('');
                    $("#khaata_image").attr("src", 'assets/images/logo-placeholder.png');
                    $("#ledger-table").html('');
                }
            }
        });
    }

    function displayKhaataDetails(details) {
        var html = ''; // Initialize an empty string to store HTML

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
    function dataBranchDates() {
        var khaat_id_bottom = $("#khaat_id_bottom").val();
        var goods_id = $("#goods_id").val();
        $("#goods_id_print").val(goods_id);
        var start_date = $("#start_date").val();
        $("#start_date_print").val(start_date);
        var end_date = $("#end_date").val();
        $("#end_date_print").val(end_date);
        var size = $("#size").val();
        $("#size_print").val(size);
        $("#action_print").val(true);

        var allot = $("#allot").val();
        $("#allot_print").val(allot);
        //console.log(allot);

        $.ajax({
            url: 'ajax/fetchPurchasesAccountWise.php',
            type: 'post',
            data: {
                khaata_id: khaat_id_bottom,
                goods_id: goods_id,
                size: size,
                allot: allot,
                action: true,
                start_date: start_date,
                end_date: end_date
            },
            dataType: 'json',
            success: function (data) {
                //console.log(data['bottomData']);
                $("#ledger-table").html(data['tableData']);
                $("#rows_span").text(data['bottomData'][0]);
                $("#total_qty_span").text(data['bottomData'][1].toFixed(2));
                $("#total_kgs_span").text(data['bottomData'][2].toFixed(2));
                $("#net_kgs_span").text(data['bottomData'][3]);
                $("#total_amount_span").text(data['bottomData'][4]);
                $("#total_final_amount_span").text(data['bottomData'][5]);
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#goods_id").change(function () {
            var goods_id = $(this).val();
            goodDetails(goods_id);
        });
    });

    function goodDetails(goods_id) {
        if (goods_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_sizes.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#size').html(html);
                }
            });
        } else {
            $('#size').html('<option value="">ALL SIZE</option>');
        }
    }
</script>
<script>
    $('input[name=printSizeRadio]').change(function () {
        //document.printLedgerAllCategoriesForm.submit();
        $('#printLedgerForm').submit();
    });
</script>

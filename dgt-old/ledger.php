<?php $page_title = 'Ledger';
include("header.php"); ?>
<div class="row">
    <div class="col-lg-12 ">
        <div class="d-flex justify-content-between table-form">
            <?php $bkn = isset($_GET['back-khaata-no']) ? mysqli_real_escape_string($connect, $_GET['back-khaata-no']) : ''; ?>
            <div>
                <div class="input-group position-relative">
                    <label for="khaata_no">A/C</label>
                    <input type="text" id="khaata_no" class="form-control form-control-sm inputFilter" autofocus
                           placeholder="A/c (F2)" value="<?php echo $bkn; ?>">
                    <small id="response" class="error-response"></small>
                </div>
            </div>
            <form id="datesBranchForm">
                <div class="input-group">
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                    <label for="start_date"> TO </label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                    <select name="branch_id" id="branch_id" class=" form-select" onchange="dataBranchDates();">
                        <option value="0">All Branch</option>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) {
                            echo '<option value="' . $branch["id"] . '">' . $branch["b_name"] . '</option>';
                        } ?>
                    </select>
                </div>
                <input type="hidden" id="khaat_id_bottom">
            </form>
            <form action="print/ledger" method="post" id="printLedgerForm" target="_blank">
                <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                <input type="hidden" name="khaata_id" id="khaata_id_print" value="0">
                <input type="hidden" name="branch_id" id="branch_id_print" value="0">
                <input type="hidden" name="start_date" id="start_date_print" value="">
                <input type="hidden" name="end_date" id="end_date_print" value="">
                <button id="khaata_print_btn" name="printLedgerSubmit" type="submit"
                        class="btn btn-primary btn-sm">
                    <i class="fa fa-print"></i> Print
                </button>
            </form>
        </div>
        <div class="d-flex justify-content-between align-items-start gap-1 text-uppercase small">
            <div>
                <div class="text-capitalize-">Rows<span id="rows_span" class="fw-bold"></span></div>
                <div>Dr.<span id="dr_total_span" class="fw-bold"></span></div>
                <div>Cr.<span id="cr_total_span" class="fw-bold"></span></div>
                <div>Balance<span id="cr_balance_span" class="fw-bold"></span></div>
            </div>
            <div style="width: 80%">
                <div class="card mb-1 position-relative">
                    <div class="info-div">Account</div>
                    <div class="d-flex p-1">
                        <div>
                            <?php $array_acc1 = array(array('label' => 'A/C#', 'id' => 'khaata_no1'), array('label' => 'A/C NAME', 'id' => 'khaata_name'), array('label' => 'BRANCH', 'id' => 'b_name'), array('label' => 'CATEGORY', 'id' => 'c_name'));
                            $array_acc2 = array(
                                array('label' => 'BUSINESS NAME', 'id' => 'business_name'), array('label' => 'ADDRESS', 'id' => 'address'),
                                array('label' => 'COMPANY', 'id' => 'comp_name')
                            );
                            $array_acc3 = array(
                                array('label' => '', 'id' => 'contacts'),
                            );
                            ?>
                            <?php foreach ($array_acc1 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span><br>';
                            } ?>
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
                                 class="avatar-lg rounded shadow"
                                 alt="Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 62dvh;">
                    <table class="table mb-0 table-bordered fix-head-table table-sm">
                        <thead>
                        <tr class="text-nowrap text-uppercase">
                            <th>B.</th>
                            <th>Date</th>
                            <th>Serial</th>
                            <th>User</th>
                            <th>Roz#</th>
                            <th>Name</th>
                            <th>No.</th>
                            <th>Details</th>
                            <th>Dr.</th>
                            <th>Cr.</th>
                            <!--<th>Dr./Cr.</th>-->
                            <th>Total</th>
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
    $("#branch_id").attr('disabled', 'disabled');

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
                    $("#branch_id").removeAttr('disabled');
                    //$(':input[type="submit"]').prop('disabled', false);
                    $("#response").text('');
                    var khaata_id = response.messages['khaata_id'];
                    $("#khaat_id_bottom").val(khaata_id);
                    //alert(khaata_id);
                    $.ajax({
                        url: 'ajax/fetchLedgerForm.php',
                        type: 'post',
                        data: {khaata_id: khaata_id},
                        dataType: 'json',
                        success: function (data) {
                            //$("#khaata_no").blur();
                            //$("#start_date").focus();
                            //console.log(data['bottomData']);
                            $("#ledger-table").html(data['tableData']);
                            $("#rows_span").text(data['bottomData'][0]);
                            $("#dr_total_span").text(data['bottomData'][1].toFixed(2));
                            $("#cr_total_span").text(data['bottomData'][2].toFixed(2));
                            $("#cr_balance_span").text(data['bottomData'][3].toFixed(2));
                        }
                    });
                }
                if (response.success === false) {
                    $("#khaata_print_btn").hide();
                    $("#start_date").attr('disabled', 'disabled');
                    $("#end_date").attr('disabled', 'disabled');
                    $("#branch_id").attr('disabled', 'disabled');
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
        //alert('func called');
        var khaat_id_bottom = $("#khaat_id_bottom").val();
        var branch_id = $("#branch_id").val();

        $("#branch_id_print").val(branch_id);
        var start_date = $("#start_date").val();
        $("#start_date_print").val(start_date);
        var end_date = $("#end_date").val();
        $("#end_date_print").val(end_date);
        $.ajax({
            url: 'ajax/fetchLedgerForm.php',
            type: 'post',
            data: {
                khaata_id: khaat_id_bottom,
                branch_id: branch_id,
                action: true,
                start_date: start_date,
                end_date: end_date
            },
            dataType: 'json',
            success: function (data) {
                //console.log(data);
                console.log(data['bottomData']);
                $("#ledger-table").html(data['tableData']);
                $("#rows_span").text(data['bottomData'][0]);
                $("#dr_total_span").text(data['bottomData'][1].toFixed(2));
                $("#cr_total_span").text(data['bottomData'][2].toFixed(2));
                $("#cr_balance_span").text(data['bottomData'][3]);
            }
        });
    }
</script>

<?php $page_title = 'Ledger';
$url = "ledger";
include("header.php");
$bkn = isset($_GET['back-khaata-no']) ? mysqli_real_escape_string($connect, $_GET['back-khaata-no']) : ''; ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-1">
            <form action="print/ledger" method="get" id="printLedgerForm"><!--target="_blank"-->
                <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                <input type="hidden" name="khaata_id" id="khaata_id_print" value="0">
                <input type="hidden" name="branch_id" id="branch_id_print" value="0">
                <input type="hidden" name="start_date" id="start_date_print" value="">
                <input type="hidden" name="end_date" id="end_date_print" value="">
                <button id="khaata_print_btn" type="submit" class="btn btn-dark btn-sm">
                    <i class="fa fa-print"></i> Print
                </button>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 ">
        <div class="card mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-2 table-form">
                    <div>
                        <div class="input-group">
                            <label for="khaata_no" class="fw-bold">A/C</label>
                            <input type="text" id="khaata_no" class="form-control inputFilter" autofocus
                                placeholder="A/c (F2)" value="<?php echo $bkn; ?>">
                        </div>
                        <form id="datesBranchForm">
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                            </div>
                            <div>
                                <select name="branch_id" id="branch_id" class=" form-select"
                                    onchange="dataBranchDates();">
                                    <option value="0">All Branch</option>
                                    <?php $branches = fetch('branches');
                                    while ($branch = mysqli_fetch_assoc($branches)) {
                                        echo '<option value="' . $branch["id"] . '">' . $branch["b_code"] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <input type="hidden" id="khaat_id_bottom">
                        </form>
                    </div>
                    <div>
                        <b>Entries</b> <span id="rows_span" class="text-muted"></span><br>
                        <b>Dr.</b> <span id="dr_total_span" class="text-muted"></span><br>
                        <b>Cr.</b> <span id="cr_total_span" class="text-muted"></span><br>
                        <b>Balance</b> <span id="cr_balance_span" class="text-muted"></span>
                    </div>
                    <div>
                        <div><b>A/C#</b> <span class="text-muted" id="khaata_no1">---</span></div>
                        <div><b>A/C NAME</b> <span class="text-muted" id="khaata_name"></span></div>
                        <div><b>BR.</b> <span class="text-muted" id="b_name"></span>
                            <b>CAT.</b> <span class="text-muted" id="c_name"></span>
                        </div>
                        <div><b>PHONE</b> <span class="text-muted" id="k_phone"></span></div>
                        <!--<div><b>EMAIL</b> <span class="text-muted" id="k_email"></span></div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
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
    $(document).on('keyup', "#khaata_no", function(e) {
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
            data: {
                khaata_no: khaata_no
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    $("#khaata_id").val(response.messages['khaata_id']);
                    $("#khaata_print_btn").show();
                    $("#khaata_id_print").val(response.messages['khaata_id']);
                    $("#khaata_no1").text(khaata_no);
                    $("#c_name").text(response.messages['name']);
                    $("#b_name").text(response.messages['b_code']);
                    $("#khaata_name").text(response.messages['khaata_name']);
                    $("#k_email").text(response.messages['email']);
                    $("#k_phone").text(response.messages['phone']);

                    $("#start_date").removeAttr('disabled');
                    $("#end_date").removeAttr('disabled');
                    $("#branch_id").removeAttr('disabled');

                    $("#khaata_no").addClass('is-valid');
                    $("#khaata_no").removeClass('is-invalid');

                    var khaata_id = response.messages['khaata_id'];
                    $("#khaat_id_bottom").val(khaata_id);
                    $.ajax({
                        url: 'ajax/fetchLedgerForm.php',
                        type: 'post',
                        data: {
                            khaata_id: khaata_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            $("#ledger-table").html(data['tableData']);
                            $("#rows_span").text(data['bottomData'][0]);
                            $("#dr_total_span").text(data['bottomData'][1].toFixed(2));
                            $("#cr_total_span").text(data['bottomData'][2].toFixed(2));
                            var baal = data['bottomData'][3].toFixed(2);
                            var cclas = baal > 0 ? 'text-success' : 'text-danger';
                            $("#cr_balance_span").html('<span id="bb_all" class="fw-bold">' + baal + '</span>');
                            $("#bb_all").addClass(cclas);
                        }
                    });
                }
                if (response.success === false) {
                    $("#khaata_print_btn").hide();
                    $("#start_date").attr('disabled', 'disabled');
                    $("#end_date").attr('disabled', 'disabled');
                    $("#branch_id").attr('disabled', 'disabled');
                    $("#khaata_no").addClass('is-invalid');
                    $("#khaata_no").removeClass('is-valid');
                    $("#khaata_id").val(0);
                    $("#khaata_no1").text('');
                    $("#c_name").text('');
                    $("#b_name").text('');
                    $("#k_email").text('');
                    $("#k_phone").text('');
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
            success: function(data) {
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
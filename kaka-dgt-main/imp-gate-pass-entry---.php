<?php include("header.php"); ?>
<style>
    #table-header-fixed {
        top: 130px !important;
    }
</style>
<div class="heading-div ">
    <div class="card-bod px-1 border-bottom">
        <div class="row">
            <div class="col-md-auto">
                <h4 class="mt-4 ms-2">گیٹ پاس چیکنگ</h4>
            </div>
            <div class="col">
                <div class="row gx-0 ">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-group">
                                <label for="khaata_no" class="input-group-text">اکاونٹ نمبر</label>
                                <input type="text" id="khaata_no" class="form-control form-control-sm ltr inputFilter"
                                       autofocus placeholder="F2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="khaata_name" class="input-group-text">کھاتہ نام</label>
                            <input type="text" id="khaata_name" class="form-control form-control-sm input-urdu">
                            <label for="comp_name" class="input-group-text">کمپنی نام</label>
                            <input type="text" id="comp_name" class="form-control form-control-sm input-urdu" disabled>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="input-group">
                            <label for="c_name" class="input-group-text">کیٹیگری</label>
                            <input type="text" id="c_name" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="input-group">
                            <label for="b_name" class="input-group-text">برانچ</label>
                            <input type="text" id="b_name" class="form-control form-control-sm input-urdu" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="business_name" class="input-group-text">کاروبار نام</label>
                            <input type="text" id="business_name" class="form-control form-control-sm input-urdu"
                                   disabled>
                        </div>
                    </div>
                </div>
                <div class="row gx-0 mt-2">
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="address" class="input-group-text">کاروبار پتہ</label>
                            <input type="text" id="address" class="form-control form-control-sm input-urdu" disabled>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <label for="mobile" class="input-group-text">موبائل نمبر</label>
                            <input type="text" id="mobile" class="form-control form-control-sm ltr" disabled>
                            <label for="whatsapp" class="input-group-text">واٹس ایپ</label>
                            <input type="text" id="whatsapp" class="form-control form-control-sm ltr" disabled>
                            <label for="phone" class="input-group-text">فون نمبر</label>
                            <input type="text" id="phone" class="form-control form-control-sm ltr" disabled>
                            <label for="email" class="input-group-text">ای میل</label>
                            <input type="email" id="email" class="form-control form-control-sm ltr" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <img id="khaata_image" src="assets/images/others/logo-placeholder.png" alt="image"
                     class="img-fluid p-1" style="height: 82px;">
            </div>
        </div>
    </div>
</div>
<div class="row mt-4 pt-5">
    <div class="col-md-12">
        <div class="card border-bottom-0 border-top-0 mt-2">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="table-responsive scroll screen-ht-71">
                <table class="table table-bordered " id="fix-head-table">
                    <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>لوڈنگ سیریل</th>
                        <th>سمری سیریل</th>
                        <th>ٹرک نمبر</th>
                        <th>لوڈنگ گودام</th>
                        <th>خالی کرنے گودام</th>
                        <th>بھیجنے والا</th>
                        <th>جنس نام</th>
                        <th>باردانہ نام</th>
                        <th>باردانہ تعداد</th>
                        <th>ٹوٹل وزن</th>
                        <th>گیٹ پاس دینے باردانہ</th>
                        <th>بیلنس بنام</th>
                        <!--<th width="7%">برانچ</th>
                        <th width="7%">تاریخ</th>
                        <th width="7%">سیریل</th>
                        <th width="5%">یوزر</th>
                        <th width="7%">روزنامچہ نمبر</th>
                        <th width="8%">نام</th>
                        <th width="7%">نمبر</th>
                        <th width="26%">تفصیل</th>
                        <th width="8%">جمع</th>
                        <th width="8%">بنام</th>
                        <th width="2%" style="font-size: 12px">جمع بنام</th>
                        <th width="8%">ٹوٹل</th>-->
                    </tr>
                    </thead>
                    <tbody id="gatpass-table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-lg" style="position: fixed; bottom: 0; width: 100%; left: 0; right: 0;">
    <div class="card-body p-1">
        <div class="row ">
            <div class="col-md-2">
                <div class="input-group" id="">
                    <input type="date" name="start_date" id="start_date" class="form-control bg-transparent ltr"
                           value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                    <label for="start_date" class="input-group-text urdu">سے</label>
                    <input type="date" name="end_date" id="end_date" class="form-control bg-transparent ltr"
                           value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                </div>
                <input type="hidden" id="khaat_id_bottom">
            </div>
            <div class="col-md-1 border-start border-end">
                <button type="button" class="btn btn-primary btn-icon-text w-100 pt-1 pb-2"
                        onclick="window.print();">
                    <i class="btn-icon-prepend" data-feather="printer"></i>
                    پرنٹ
                </button>
            </div>
            <div class="col-md-9 border-right">
                <div class="row gx-0">
                    <div class="col-4">
                        <div class="input-group">
                            <label for="total_indraaj" class="input-group-text urdu">ٹوٹل سیریل</label>
                            <input id="total_indraaj" disabled type="text" value="0"
                                   class="form-control bg-transparent">
                            <label for="totalSummaryQty" class="input-group-text urdu">ٹوٹل سمری تعداد</label>
                            <input disabled type="text" id="totalSummaryQty" class="form-control bg-transparent">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <label for="total_b" class="input-group-text urdu">ٹوٹل باردانہ</label>
                            <input disabled type="text" id="total_bnaam" class="form-control bg-transparent">
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <label for="balance_bnaam" class="input-group-text urdu">ٹوٹل گیٹ پاس شدہ باردانہ</label>
                            <input disabled type="text" id="balance_bnaam" value="-192999292"
                                   class="form-control bg-transparent ltr">
                            <label for="balance_bnaam" class="input-group-text urdu">بقایا باردانہ</label>
                            <input disabled type="text" id="balance_bnaam" value="-192999292"
                                   class="form-control bg-transparent ltr">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .bottom-button {
        display: none;
    }
</style>
<?php include("footer.php"); ?>
<script>
    $("#start_date").attr('disabled', 'disabled');
    $("#end_date").attr('disabled', 'disabled');
    $("#branch_id").attr('disabled', 'disabled');
    var khaata_id = $("#khaata_id").val();
    var khaata_no1 = $("#khaata_no1").val();
    var c_name = $("#c_name").val();
    var b_name = $("#b_name").val();
    var khaata_name = $("#khaata_name").val();
    var comp_name = $("#comp_name").val();
    var business_name = $("#business_name").val();
    var address = $("#address").val();
    var mobile = $("#mobile").val();
    var whatsapp = $("#whatsapp").val();
    var phone = $("#phone").val();
    var email = $("#email").val();
    var khaata_image = $("#khaata_image").attr('src');
    /*get khaata values at first time*/

    var typingTimer;
    var doneTypingInterval = 1000;  //time in ms, 5 seconds for example
    var $input = $('#khaata_no');
    var khaata_no = '';
    $input.on('keyup', function (e) {
        clearTimeout(typingTimer);
        khaata_no = $('#khaata_no').val();
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });
    function doneTyping() {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#khaata_id").val(response.messages['khaata_id']);
                    $("#khaata_no").val(khaata_no);
                    $("#c_name").val(response.messages['c_name']);
                    $("#b_name").val(response.messages['b_name']);
                    $("#khaata_name").val(response.messages['khaata_name']);
                    $("#comp_name").val(response.messages['comp_name']);
                    $("#business_name").val(response.messages['business_name']);
                    $("#address").val(response.messages['address']);
                    $("#mobile").val(response.messages['mobile']);
                    $("#whatsapp").val(response.messages['whatsapp']);
                    $("#phone").val(response.messages['phone']);
                    $("#email").val(response.messages['email']);
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
                        url: 'ajax/fetchImpGatePassEntry.php',
                        type: 'post',
                        data: {khaata_id: khaata_id},
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            $("#gatpass-table").html(data['tableData']);
                            $("#total_indraaj").val(data['bottomData'][0]);
                            $("#totalSummaryQty").val(data['bottomData'][1]);
                            $("#total_bnaam").val(data['bottomData'][2]);
                            $("#balance_bnaam").val(data['bottomData'][3]);
                        }
                    });
                }
                if (response.success === false) {
                    $("#start_date").attr('disabled', 'disabled');
                    $("#end_date").attr('disabled', 'disabled');
                    $("#branch_id").attr('disabled', 'disabled');
                    $("#response").text('کھاتہ نمبر درست نہیں ہے');
                    $("#khaata_id").val(khaata_id);
                    $("#khaata_no1").val(khaata_no1);
                    $("#c_name").val(c_name);
                    $("#b_name").val(b_name);
                    $("#khaata_name").val(khaata_name);
                    $("#comp_name").val(comp_name);
                    $("#business_name").val(business_name);
                    $("#address").val(address);
                    $("#mobile").val(mobile);
                    $("#whatsapp").val(whatsapp);
                    $("#phone").val(phone);
                    $("#email").val(email);
                    $("#khaata_image").attr("src", khaata_image);
                    $("#gatpass-table").html('');
                }
            }
        });
    }
</script>
<script>
    function dataBranchDates() {
        var khaat_id_bottom = $("#khaat_id_bottom").val();
        var branch_id = $("#branch_id").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
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
                //console.log(data['bottomData']);
                $("#gatpass-table").html(data['tableData']);
                $("#total_indraaj").val(data['bottomData'][0]);
                $("#total_jmaa").val(data['bottomData'][1]);
                $("#total_bnaam").val(data['bottomData'][2]);
                $("#balance_bnaam").val(data['bottomData'][3]);
            }
        });
    }
</script>
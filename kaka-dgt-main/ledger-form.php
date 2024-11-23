<?php include("header.php"); ?>
<style>
    /*#table-header-fixed {
        top: 130px !important;
    }*/
</style>
<div class="heading-div ">
    <div class="card-bod px-2 border-bottom">
        <div class="row gx-0 align-items-center">
            <!--<div class="col-md-auto">
                <h4 class="mt-2 ms-2">کھاتہ</h4>
            </div>-->
            <div class="col-sm">
                <div class="row gx-0 gy-0">
                    <div class="col-md-1 position-relative">
                        <div class="input-group">
                            <?php $bkn = isset($_GET['back-khaata-no']) ? mysqli_real_escape_string($connect, $_GET['back-khaata-no']) : ''; ?>
                            <label for="khaata_no" class="input-group-text">اکاونٹ</label>
                            <input type="text" id="khaata_no"
                                   class="form-control form-control-sm bg-transparent inputFilter"
                                   autofocus placeholder="اکاونٹ نمبر (F2)" value="<?php echo $bkn; ?>">
                            <span id="response" class="position-absolute urdu small-2 text-danger left-0"
                                  style="top: 10px;"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="khaata_id_search" class="input-group-text">کھاتہ نام</label>
                            <input type="hidden" id="khaata_name" class="form-control">
                            <select id="khaata_id_search" name="khaata_id_search"
                                    class="form-control border-bottom-0 khaata-select mt-n1 p-0 bg-transparent">
                                <option selected disabled>انتخاب کریں</option>
                                <?php $khaatas = fetch('khaata');
                                while ($khaata = mysqli_fetch_assoc($khaatas)) {
                                    echo '<option value="' . $khaata['id'] . '">' . $khaata['khaata_name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="comp_name" class="input-group-text">کمپنی نام</label>
                            <input type="text" id="comp_name" class="form-control form-control-sm urdu-2 bold-" disabled>
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
                            <input type="text" id="b_name" class="form-control form-control-sm urdu-2 " disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="business_name" class="input-group-text">کاروبار نام</label>
                            <input type="text" id="business_name" class="form-control form-control-sm urdu-2"
                                   disabled>
                        </div>
                    </div>
                </div>
                <div class="row gx-0 gy-0">
                    <div class="col-md-4">
                        <div class="input-group">
                            <label for="address" class="input-group-text">کاروبار پتہ</label>
                            <input type="text" id="address" class="form-control form-control-sm urdu-2" disabled>
                        </div>
                    </div>
                    <div class="col-md-8">
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
            <div class="col-sm-auto d--flex align-items-center">
                <img id="khaata_image" src="assets/images/others/logo-placeholder.png" alt="image"
                     class="img-fluid py-1 " style="height: 85px;">
            </div>
        </div>
    </div>
</div>
<div class="row mt-3 pt-5">
    <div class="col-md-12">
        <div class="card shadow-lg">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="table-responsive scroll screen-ht-71--" style="height: 73vh;">
                <table class="table table-bordered " id="fix-head-table">
                    <thead>
                    <tr>
                        <th class="small">برانچ</th>
                        <th class="small">تاریخ</th>
                        <th class="small">سیریل</th>
                        <th class="small">یوزر</th>
                        <th class="small">روزنامچہ نمبر</th>
                        <th class="small">نام</th>
                        <th class="small">نمبر</th>
                        <th class="small" style="width: 30%;">تفصیل</th>
                        <th class="small">جمع</th>
                        <th class="small">بنام</th>
                        <th class="small-2">رقم</th>
                        <th class="small">ٹوٹل</th>
                    </tr>
                    <!--<tr>
                        <th class="small" width="7%">برانچ</th>
                        <th class="small" width="7%">تاریخ</th>
                        <th class="small" width="7%">سیریل</th>
                        <th class="small" width="5%">یوزر</th>
                        <th class="small" width="7%">روزنامچہ نمبر</th>
                        <th class="small" width="8%">نام</th>
                        <th class="small" width="7%">نمبر</th>
                        <th class="small" width="26%">تفصیل</th>
                        <th class="small" width="7%">جمع</th>
                        <th class="small" width="7%">بنام</th>
                        <th width="4%" style="font-size: 12px">جمع بنام</th>
                        <th class="small" width="8%">ٹوٹل</th>
                    </tr>-->
                    </thead>
                    <tbody id="ledger-table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-lg" style="position: fixed; bottom: 0; width: 100%; left: 0; right: 0;">
    <div class="card-body pt-0 p-1 container">
        <div class="row ">
            <div class="col-md-4">
                <div class="row gx-0">
                    <div class="col-md-12">
                        <form id="datesBranchForm">
                            <div class="input-group" id="">
                                <input type="date" name="start_date" id="start_date" class="form-control bg-transparent"
                                       value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                                <label for="start_date" class="input-group-text urdu">سے</label>
                                <input type="date" name="end_date" id="end_date" class="form-control bg-transparent"
                                       value="<?php echo date('Y-m-d'); ?>" onchange="dataBranchDates();">
                                <label for="branch_id" class="input-group-text urdu">برانچ</label>
                                <select name="branch_id" id="branch_id" class=" form-control bg-transparent input-urdu"
                                        onchange="dataBranchDates();">
                                    <option value="0" hidden> برانچ ؟</option>
                                    <option value="0">آل برانچ</option>
                                    <?php $branches = fetch('branches');
                                    while ($branch = mysqli_fetch_assoc($branches)) {
                                        echo '<option value="' . $branch["id"] . '">' . $branch["b_name"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" id="khaat_id_bottom">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-1 border-start border-end">
                <form action="print/ledger-form" method="post" id="printLedgerForm" target="_blank">
                    <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                    <input type="hidden" name="khaata_id" id="khaata_id_print" value="0">
                    <input type="hidden" name="branch_id" id="branch_id_print" value="0">
                    <input type="hidden" name="start_date" id="start_date_print" value="">
                    <input type="hidden" name="end_date" id="end_date_print" value="">
                    <button id="khaata_print_btn" name="printLedgerSubmit" type="submit"
                            class="btn btn-primary btn-icon-text w-100 btn-sm pt-0 mt-md-1">
                        <i class="btn-icon-prepend" data-feather="printer"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-7 border-right">
                <div class="row gx-0 row-cols-2">
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="total_indraaj" class="input-group-text urdu">ٹوٹل اندراج</label>
                            <input id="total_indraaj" disabled type="text" value="0"
                                   class="form-control bg-transparent">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="total_jmaa" class="input-group-text urdu">ٹوٹل جمع</label>
                            <input disabled type="text" id="total_jmaa" value=""
                                   class="form-control bg-transparent">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="total_bnaam" class="input-group-text urdu">ٹوٹل بنام</label>
                            <input disabled type="text" id="total_bnaam" value=""
                                   class="form-control bg-transparent">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="balance_bnaam" class="input-group-text urdu">بیلنس<!-- بنام--></label>
                            <input disabled type="text" id="balance_bnaam" value=""
                                   class="form-control bg-transparent ltr">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript">
    VirtualSelect.init({
        ele: '.khaata-select',
        placeholder: '',
        searchPlaceholderText: 'تلاش کریں',
        search: true,
        autoSelectFirstOption: false
        //required: false,
    });

</script>
<script>
    $(function () {
        searchKhaata($('#khaata_id_search').val());
    });
    $('#khaata_id_search').change(function () {
        searchKhaata($(this).val());
    });

    function searchKhaata(khaata_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_id: khaata_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    console.log(response);
                    /*print button*/
                    $("#khaata_print_btn").show();
                    $("#khaata_id_print").val(response.messages['khaata_id']);
                    $("#khaata_id").val(response.messages['khaata_id']);
                    $("#khaata_no").val(response.messages['khaata_no']);
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
                        url: 'ajax/fetchLedgerForm.php',
                        type: 'post',
                        data: {khaata_id: khaata_id},
                        dataType: 'json',
                        success: function (data) {
                            $("#khaata_no").blur();
                            $("#start_date").focus();
                            //console.log(data['bottomData']);
                            $("#ledger-table").html(data['tableData']);
                            $("#total_indraaj").val(data['bottomData'][0]);
                            $("#total_jmaa").val(data['bottomData'][1]);
                            $("#total_bnaam").val(data['bottomData'][2]);
                            if (data['bottomData'][3] < 0) {
                                $("#balance_bnaam").addClass('text-danger');
                            } else if (data['bottomData'][3] > 0) {
                                $("#balance_bnaam").addClass('text-success');
                            }else{
                                $("#balance_bnaam").addClass('text-dark');
                            }
                            $("#balance_bnaam").val(data['bottomData'][3]);
                        }
                    });
                }
                if (response.success === false) {
                    $("#khaata_print_btn").hide();
                    $("#start_date").attr('disabled', 'disabled');
                    $("#end_date").attr('disabled', 'disabled');
                    $("#branch_id").attr('disabled', 'disabled');
                    $("#response").text('کھاتہ');
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
                    $("#ledger-table").html('');
                }
            }
        });
    }
</script>
<script>
    $("#khaata_print_btn").hide();
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
        $("#datesBranchForm")[0].reset();
        $("#printLedgerForm")[0].reset();
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    /*print button*/
                    document.querySelector('#khaata_id_search').setValue(response.messages['khaata_id']);
                    $("#khaata_print_btn").show();
                    $("#khaata_id_print").val(response.messages['khaata_id']);
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
                        url: 'ajax/fetchLedgerForm.php',
                        type: 'post',
                        data: {khaata_id: khaata_id},
                        dataType: 'json',
                        success: function (data) {
                            $("#khaata_no").blur();
                            $("#start_date").focus();
                            //console.log(data['bottomData']);
                            $("#ledger-table").html(data['tableData']);
                            $("#total_indraaj").val(data['bottomData'][0]);
                            $("#total_jmaa").val(data['bottomData'][1]);
                            $("#total_bnaam").val(data['bottomData'][2]);
                            $("#balance_bnaam").val(data['bottomData'][3]);
                        }
                    });
                }
                if (response.success === false) {
                    document.querySelector('#khaata_id_search').reset();
                    $("#khaata_print_btn").hide();
                    $("#start_date").attr('disabled', 'disabled');
                    $("#end_date").attr('disabled', 'disabled');
                    $("#branch_id").attr('disabled', 'disabled');
                    $("#response").text('کھاتہ');
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
                    $("#ledger-table").html('');
                }
            }
        });
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
                //console.log(data['bottomData']);
                $("#ledger-table").html(data['tableData']);
                $("#total_indraaj").val(data['bottomData'][0]);
                $("#total_jmaa").val(data['bottomData'][1]);
                $("#total_bnaam").val(data['bottomData'][2]);
                $("#balance_bnaam").val(data['bottomData'][3]);
            }
        });
    }
</script>
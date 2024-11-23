<?php include("header.php"); ?>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['type'])
    && $_GET['type'] == 'godam-received'
) {
    $urlArray = array('godam-received' => array('path' => 'imp-kiraya-godam-received', 'title' => ' گودام پہنچ انٹری ', 'type' => 'کرایہ سمری', 'transfered_from' => 'godam_received'));
    $page = $urlArray[$_GET["type"]];
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('imp_truck_loadings', array('id' => $id));
    $record = mysqli_fetch_assoc($records); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n3">
        <div>
            <h4 class="mb-3 mb-md-0"><?php echo $page['title']; ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="<?php echo $page['path']; ?>"
               class="btn btn-dark btn-icon-text mb-2 mb-md-0 pt-0 pb-1 mt-1">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="p-2">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="row gx-0 gy-2">
                        <div class="col-3">
                            <div class="input-group">
                                <label for="ser" class="input-group-text urdu">لوڈنگ سیریل</label>
                                <input type="text" id="ser" class="form-control" disabled
                                       value="<?php echo $id; ?>">
                                <label for="today"
                                       class="input-group-text input-group-addon bg-transparent urdu">لوڈنگ
                                    تاریخ</label>
                                <input type="text" id="today" class="form-control bg-transparent border-primary"
                                       placeholder="تاریخ" value="<?php echo $record['loading_date']; ?>"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="owner_name" class="input-group-text urdu">مالک نام</label>
                                <input type="text" id="owner_name" name="owner_name"
                                       class="form-control input-urdu" disabled
                                       value="<?php echo $record['owner_name']; ?>">
                                <label for="jins" class="input-group-text urdu">جنس</label>
                                <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                       disabled value="<?php echo $record['jins']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                <input type="text" id="truck_no" name="truck_no" class="form-control" required
                                       value="<?php echo $record['truck_no']; ?>" disabled>
                                <label for="truck_name" class="input-group-text urdu">ٹرک نام</label>
                                <input type="text" id="truck_name" name="truck_name" tabindex="-1"
                                       class="form-control urdu-2 bold" disabled
                                       value="<?php echo $record['truck_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                <input type="text" id="driver_name" name="" disabled
                                       class="form-control urdu-2 bold" required readonly
                                       value="<?php echo $record['driver_name']; ?>">
                                <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                                <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                                       class="form-control ltr small-2" disabled
                                       value="<?php echo $record['driver_mobile']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label class="input-group-text urdu">لوڈنگ کرنے گودام</label>
                                <?php $loadings = fetch('godam_loading_forms', array('id' => $record['godam_loading_id']));
                                $loading = mysqli_fetch_assoc($loadings); ?>
                                <input type="text" value="<?php echo $loading['name'] ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $loading['mobile1'] ?>">
                                <label class="input-group-text urdu">منشی کانام</label>
                                <input type="text" class="form-control urdu-2 bold" disabled
                                       value="<?php echo $loading['munshi'] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label class="input-group-text urdu">پتہ</label>
                                <input type="text" disabled value="<?php echo $loading['address'] ?>"
                                       class="form-control urdu-2 bold">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label class="input-group-text urdu">خالی کرنے گودام</label>
                                <?php $empties = fetch('godam_empty_forms', array('id' => $record['godam_empty_id']));
                                $empty = mysqli_fetch_assoc($empties); ?>
                                <input type="text" value="<?php echo $empty['name'] ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $empty['mobile1'] ?>">
                                <label class="input-group-text urdu">منشی کانام</label>
                                <input type="text" class="form-control urdu-2 bold" disabled
                                       value="<?php echo $empty['munshi'] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label class="input-group-text urdu">پتہ</label>
                                <input type="text" disabled value="<?php echo $empty['address'] ?>"
                                       class="form-control urdu-2 bold">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">کنسائینی نام</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['consignee_name']; ?>">
                                <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['sender_city']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <label class="input-group-text urdu">رپورٹ</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['report']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-2 pb-3">
                <form id="insert_form">
                    <div class="row gx-0 row-cols me-2">
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="sr_no">سیریل نمبر</label>
                                <input type="text" id="sr_no" name="sr_no" class="form-control" disabled
                                       value="<?php //echo getMaalSerial($id); ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="jins_name">جنس نام</label>
                                <input type="text" id="jins_name" name="jins_name"
                                       class="form-control input-urdu" required readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="bardana_name">باردانہ نام</label>
                                <input type="text" id="bardana_name" name="bardana_name"
                                       class="input-urdu form-control" required readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="bardana_qty">باردانہ تعداد</label>
                                <input type="text" id="bardana_qty" name="bardana_qty" onkeyup="totalWt()"
                                       class="form-control currency" required readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="per_wt">فی وزن</label>
                                <input type="text" id="per_wt" name="per_wt" class="form-control currency" required
                                       onkeyup="totalWt()" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="total_wt">ٹوٹل وزن</label>
                                <input type="text" id="total_wt" name="total_wt" class="form-control currency"
                                       readonly tabindex="-1">
                            </div>
                        </div>
                        <script>
                            function totalWt(e) {
                                var value = $(e).val();
                                var id = $(e).attr('id');
                                var bardana_qty = $("#bardana_qty").val();
                                var per_wt = $("#per_wt").val();
                                var total_wt = Number(bardana_qty) * Number(per_wt);
                                $("#total_wt").val(total_wt);
                            }
                        </script>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="empty_wt">خالی وزن</label>
                                <input type="text" id="empty_wt" name="empty_wt" class="form-control currency"
                                       required onkeyup="totalKWt()" readonly>
                            </div>
                        </div>
                        <script>
                            function totalKWt(e) {
                                var empty_wt = $("#empty_wt").val();
                                var bardana_qty = $("#bardana_qty").val();
                                var total_empty_wt = Number(bardana_qty) * Number(empty_wt);
                                $("#total_empty_wt").val(total_empty_wt);
                                var ww = $("#total_wt").val();
                                //alert("Total wt=" + ww + " Total empty wt=" + total_empty_wt);
                                var saaf_wt = Number(ww) - Number(total_empty_wt);
                                $("#saaf_wt").val(saaf_wt);
                            }
                        </script>
                    </div>
                    <div class="row gx-0 row-cols mt-2 me-2">
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="total_empty_wt">ٹوٹل خالی وزن </label>
                                <input type="text" id="total_empty_wt" name="total_empty_wt" readonly tabindex="-1"
                                       class="form-control currency">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="saaf_wt">صاف وزن</label>
                                <input type="text" id="saaf_wt" name="saaf_wt" readonly tabindex="-1"
                                       class="form-control currency">
                            </div>
                        </div>

                        <div class="col">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <label class="input-group-text urdu" for="godam_receive_date">گودام پہنچ تاریخ</label>
                                <input type="text" id="godam_receive_date" name="godam_receive_date"
                                       class="form-control " required data-input>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="godam_receive_no">گودام پہنچ نمبر</label>
                                <input type="text" id="godam_receive_no" name="godam_receive_no" required
                                       class="form-control numberOnly">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="receive_bardana_qty">پہنچ باردانہ
                                    تعداد</label>
                                <input type="text" id="receive_bardana_qty" name="receive_bardana_qty"
                                       class="form-control numberOnly">
                                <?php //if ($record['r_transfered'] == 0) { ?>
                                    <button type="submit" name="recordSubmit" id="recordSubmit"
                                            class="btn btn-outline-danger pt-0 mt-1 d-none">تبدیل
                                    </button>
                                <?php //} ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="imp_truck_maals_id" id="imp_truck_maals_id" value="0">
                    <input type="hidden" name="form_name" value="<?php echo $page['transfered_from']; ?>">
                    <input type="hidden" name="imp_tl_id" value="<?php echo $id; ?>">
                </form>
            </div>
            <div class="row gx-2">
                <div class="col">
                    <div class="card mt-2 border-top-0">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th> سیریل نمبر</th>
                                    <th>جنس نام</th>
                                    <th>باردن نام</th>
                                    <th>باردن تعداد</th>
                                    <th>فی وزن</th>
                                    <th>ٹوٹل وزن</th>
                                    <th>خالی باردن وزن</th>
                                    <th>ٹوٹل خالی وزن</th>
                                    <th>صاف وزن</th>
                                    <th>گودام پہنچ تاریخ</th>
                                    <th>گودام پہنچ نمبر</th>
                                    <th>پہنچ باردانہ تعداد</th>
                                    <th>باردانہ بیلنس</th>
                                </tr>
                                </thead>
                                <tbody id="records_table">
                                <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $id));
                                $x = 1;
                                $remainingRows = $godam_receive_no = $receive_bardana_qty = $bardana_balance = 0;
                                $godam_receive_date = null;
                                $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = $total_expFinal = 0;
                                while ($maal = mysqli_fetch_assoc($maals)) {
                                    $maal2 = isKirayaAdded($maal['id'], $page['transfered_from']);
                                    if ($maal2['success']) {
                                        $maal2Id = $maal2['output']['id'];
                                        $json2 = json_decode($maal2['output']['json_data']);
                                        $godam_receive_date = $json2->godam_receive_date;
                                        $godam_receive_no = $json2->godam_receive_no;
                                        $receive_bardana_qty = $json2->receive_bardana_qty;
                                        $total_expFinal += $json2->receive_bardana_qty;
                                    } else {
                                        $maal2Id = $taqseem_qty = $per_mazdoori = $total_exp = $godam_receive_no = $receive_bardana_qty = 0;
                                    }
                                    $json = json_decode($maal['json_data']); ?>
                                    <tr class="row-py-0 cursor-pointer" id="<?php echo $maal['id']; ?>"
                                        data-maal2-id="<?php echo $maal2Id; ?>"
                                        data-form-name="<?php echo $page['transfered_from']; ?>"
                                        onclick="maalEntryRowEdit(this)">
                                        <td><?php echo $x; ?></td>
                                        <td><?php echo $json->jins_name; ?></td>
                                        <td><?php echo $json->bardana_name; ?></td>
                                        <td><?php echo $json->bardana_qty; ?></td>
                                        <td><?php echo $json->per_wt; ?></td>
                                        <td><?php echo $json->total_wt; ?></td>
                                        <td class="ltr"><?php echo $json->empty_wt; ?></td>
                                        <td><?php echo $json->total_empty_wt; ?></td>
                                        <td class="ltr"><?php echo $json->saaf_wt; ?></td>
                                        <?php if ($maal2Id > 0) { ?>
                                            <td><?php echo $godam_receive_date; ?></td>
                                            <td><?php echo $godam_receive_no; ?></td>
                                            <td><?php echo $receive_bardana_qty; ?></td>
                                            <td>
                                                <?php $barSubBal = $json->bardana_qty - $receive_bardana_qty;
                                                echo $barSubBal;
                                                $bardana_balance += $barSubBal; ?>
                                            </td>
                                        <?php } else {
                                            echo '<td colspan="4"></td>';
                                        } ?>
                                    </tr>
                                    <?php $x++;
                                    $remainingRows++;
                                    $bardana_qty += $json->bardana_qty;
                                    $per_wt += $json->per_wt;
                                    $total_wt += $json->total_wt;
                                    $empty_wt += $json->empty_wt;
                                    $total_empty_wt += $json->total_empty_wt;
                                    $saaf_wt += $json->saaf_wt;
                                    if ($maal2Id > 0) {
                                        $remainingRows--;
                                    }
                                } ?>
                                <tr class="row-py-0 bg-info bg-opacity-25 bold">
                                    <td><?php echo $x - 1; ?></td>
                                    <td colspan="2"></td>
                                    <td><?php echo $bardana_qty; ?></td>
                                    <td><?php echo $per_wt; ?></td>
                                    <td><?php echo $total_wt; ?></td>
                                    <td><?php echo $empty_wt; ?></td>
                                    <td><?php echo $total_empty_wt; ?></td>
                                    <td class="ltr"><?php echo $saaf_wt; ?></td>
                                    <td colspan="2"></td>
                                    <td><span id="total_exp_final"><?php echo $total_expFinal; ?></span>
                                    <input type="hidden" value="<?php echo $remainingRows; ?>" id="remainingRows">
                                    </td>
                                    <td class="ltr"><?php echo $bardana_balance; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo '<script>window.location.href="imp-kiraya-summary";</script>';
} ?>
<?php include("footer.php"); ?>
<script>
    function maalEntryRowEdit(e) {
        var id = $(e).attr('id');
        var maal2id = $(e).attr('data-maal2-id');
        var form_name = $(e).attr('data-form-name');
        //alert(form_name);
        $.ajax({
            url: "ajax/fetchSingleImpTruckMaalEntry.php",
            method: "POST",
            data: {id: id, maal2id: maal2id, form_name: form_name},
            success: function (data) {
                totalBill();
                $(e).addClass('bg-warning');
                $(e).siblings().removeClass("bg-warning");
                var dd = $.parseJSON(data);
                console.log(dd.json2);
                //$('#recordSubmit').text("تبدیل");
                $('#recordSubmit').addClass("d-block");
                $('#recordSubmit').removeClass("d-none");
                $('#sr_no').val(id);
                $('#imp_truck_maals_id').val(id);
                $('#imp_truck_maals_action').val("update");
                $('#jins_name').val(dd.json1.jins_name);
                $('#bardana_name').val(dd.json1.bardana_name);
                $('#bardana_qty').val(dd.json1.bardana_qty);
                $('#per_wt').val(dd.json1.per_wt);
                $('#total_wt').val(dd.json1.total_wt);
                $('#empty_wt').val(dd.json1.empty_wt);
                $('#total_empty_wt').val(dd.json1.total_empty_wt);
                $('#saaf_wt').val(dd.json1.saaf_wt);
                if (dd.json2 === undefined) {
                    $('#godam_receive_date').val("");
                    $('#godam_receive_no').val("");
                    $('#receive_bardana_qty').val("");
                } else {
                    $('#godam_receive_date').val(dd.json2.godam_receive_date);
                    $('#godam_receive_no').val(dd.json2.godam_receive_no);
                    $('#receive_bardana_qty').val(dd.json2.receive_bardana_qty);
                }
                $('#godam_receive_date').focus();
            },
            error: function () {

            }
        });
    }
</script>
<script>
    totalBill();
    function totalBill() {
        var total_exp_final = $('#total_exp_final').text();
        //alert(total_exp_final);
        $('#total').val(total_exp_final);
    }
    $('#insert_form').on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "ajax/impTruckMaalsKirayaEntry.php",
            method: "POST",
            data: $('#insert_form').serialize(),
            beforeSend: function () {
                $('#recordSubmit').val("ڈیٹ محفوظ ہورہاہے");
            },
            success: function (data) {
                console.log(data);
                $('#records_table').html(data);
                $('#insert_form')[0].reset();
                $('#recordSubmit').addClass("d-none");
                $('#recordSubmit').removeClass("d-block");
                $(".alert-dismissible").fadeTo(3000, 1000).slideUp(1000, function () {
                    $(".alert-dismissible").slideUp(1000);
                    $(".alert-section").addClass('d-none');
                });
                var sr_no = $('#sr_no').val();
                sr_no = Number(sr_no) + 1;
                $('#sr_no').val(sr_no);
                totalBill();
                remainingRows();
                $('#afg_jmaa_khaata_no').focus();
            }
        });
    });
</script>
<script>
    $("#recordSubmitFinal").prop('disabled', true);
    var khaata_id = $("#khaata_id1").val();
    var typingTimer;
    var doneTypingInterval = 1000;
    var $input = $('#afg_jmaa_khaata_no');
    var khaata_no = '';
    $input.on('keyup', function (e) {
        clearTimeout(typingTimer);
        khaata_no = $('#afg_jmaa_khaata_no').val();
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
                    /*$("#recordSubmit").prop('disabled', false);
                     $("#recordUpdate").prop('disabled', false);*/
                    $("#khaata_id1").val(response.messages['khaata_id']);
                    $("#response1").text('');
                    var res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                    $("#jm_kh_tafseel").html(res);
                }
                if (response.success === false) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    $("#recordUpdate").prop('disabled', true);
                    $("#response1").text('جمع کھاتہ نمبر درست نہیں ہے');
                    $("#jm_kh_tafseel").text('');
                    $("#khaata_id1").val(khaata_id);
                }
            }
        });
    }
</script>
<script>
    var khaata_id2 = $("#khaata_id2").val();
    var typingTimer2;
    var doneTypingInterval2 = 1000;
    var $input2 = $('#afg_bnaam_khaata_no');
    var khaata_no2 = '';
    $input2.on('keyup', function (e) {
        clearTimeout(typingTimer2);
        khaata_no2 = $('#afg_bnaam_khaata_no').val();
        typingTimer2 = setTimeout(doneTyping2, doneTypingInterval2);
    });
    function doneTyping2() {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no2},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    remainingRows();
                    $("#recordSubmitFinal").prop('disabled', false);
                    $("#recordUpdate").prop('disabled', false);
                    $("#khaata_id2").val(response.messages['khaata_id']);
                    $("#response2").text('');
                    //$("#bm_kh_tafseel").text(response.messages['khaata_name']);
                    var res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                    $("#bm_kh_tafseel").html(res);
                }
                if (response.success === false) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    $("#recordUpdate").prop('disabled', true);
                    $("#response2").text('بنام کھاتہ نمبر درست نہیں ہے');
                    $("#bm_kh_tafseel").text('');
                    $("#khaata_id2").val(khaata_id2);
                }
            }
        });
    }
    function remainingRows() {
        var remainingRows = $("#remainingRows").val();
        if (remainingRows > 0) {
            var msg = remainingRows + ' لائن باقی';
            $("#remainingRowsMsg").text(msg);
            //alert('لائینوں میں اندراج ابھی باقی ہے۔\n' + 'باقی لائینیں: ' + remainingRows);
            $("#recordSubmitFinal").hide();
        } else {
            //alert('سارے ریکارڈ اندراج ہو چکے ہیں۔ اب آپ ٹرانسفر کر سکتے ہیں۔ ');
            $("#recordSubmitFinal").show();
            $("#remainingRowsMsg").hide();
        }
    }
</script>
<!--<script>
    function saveImpMaalEntry(e) {
        var id = $(e).attr('id');
        var url = $(e).attr('data-url');
        var str = "کیا آپ محفوظ کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id)) {
                window.location.href = 'ajax/saveImpMaalEntry.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>-->
<?php include("header.php");
$pageURL = 'import-maal-entry-add';
$return_url = 'import-maal-entry';
$array_return_urls = array('import-maal-entry', 'import-maal-entry-final');
if (isset($_GET['return_url']) && in_array($_GET['return_url'], $array_return_urls)) {
    $return_url = mysqli_real_escape_string($connect, $_GET['return_url']);
}
?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n2">
    <div>
        <h4 class="mb-3 mb-md-0">امپورٹ مال انٹری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl($return_url); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('imp_truck_loadings', array('id' => $id));
            $record = mysqli_fetch_assoc($records);
            if (!empty($record['sender_receiver'])) {
                $sender_receiver = json_decode($record['sender_receiver']);
                $names = array(
                    'sender_id' => $sender_receiver->sender_id,
                    'sender_name' => $sender_receiver->sender_name,
                    'sender_address' => $sender_receiver->sender_address,
                    'sender_mobile' => $sender_receiver->sender_mobile,
                    'sender_wa' => $sender_receiver->sender_wa,
                    'receiver_id' => $sender_receiver->receiver_id,
                    'receiver_name' => $sender_receiver->receiver_name,
                    'receiver_address' => $sender_receiver->receiver_address,
                    'receiver_mobile' => $sender_receiver->receiver_mobile,
                    'receiver_wa' => $sender_receiver->receiver_wa
                );
            } else {
                $names = array(
                    'sender_id' => 0,
                    'sender_name' => '',
                    'sender_address' => '',
                    'sender_mobile' => '',
                    'sender_wa' => '',
                    'receiver_id' => 0,
                    'receiver_name' => '',
                    'receiver_address' => '',
                    'receiver_mobile' => '',
                    'receiver_wa' => ''
                );
            } ?>
            <div class="card">
                <div class="p-2">
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
            <div class="card mt-2 p-2">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <form id="sender_receiver_form_" method="post">
                    <div class="row ">
                        <div class="col-11">
                            <div class="row gx-0 gy-2">
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="sender_id" class="input-group-text urdu">مال بھیجنےوالا</label>
                                        <select id="sender_id" name="sender_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $senders = fetch('senders');
                                            while ($sender = mysqli_fetch_assoc($senders)) {
                                                $s_selected = $sender['id'] == $names['sender_id'] ? 'selected' : '';
                                                echo '<option ' . $s_selected . ' value="' . $sender['id'] . '">' . $sender['comp_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseDTSender" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 10px;"></small>
                                    <input type="hidden" name="sender_name" id="sender_name"
                                           value="<?php echo $names['sender_name'] ?>">
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="sender_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="sender_address" name="sender_address"
                                               class="form-control" required readonly tabindex="-1"
                                               value="<?php echo $names['sender_address']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="sender_mobile" class="input-group-text urdu">موبائل</label>
                                        <input type="text" id="sender_mobile" name="sender_mobile"
                                               class="form-control ltr" required readonly tabindex="-1"
                                               value="<?php echo $names['sender_mobile']; ?>">
                                        <label for="sender_wa" class="input-group-text urdu">واٹس ایپ</label>
                                        <input type="text" id="sender_wa" name="sender_wa"
                                               class="form-control ltr" readonly tabindex="-1"
                                               value="<?php echo $names['sender_wa']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="receiver_id" class="input-group-text urdu">مال وصول کرنے
                                            والا</label>
                                        <select id="receiver_id" name="receiver_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $receivers = fetch('receivers');
                                            while ($receiver = mysqli_fetch_assoc($receivers)) {
                                                $r_selected = $receiver['id'] == $names['receiver_id'] ? 'selected' : '';
                                                echo '<option ' . $r_selected . ' value="' . $receiver['id'] . '">' . $receiver['comp_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseDTReceiver" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 10px;"></small>
                                    <input type="hidden" name="receiver_name" id="receiver_name">
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="receiver_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="receiver_address" name="receiver_address"
                                               class="form-control" required readonly tabindex="-1"
                                               value="<?php echo $names['receiver_address']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="receiver_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input type="text" id="receiver_mobile" name="receiver_mobile"
                                               class="form-control ltr" required readonly tabindex="-1"
                                               value="<?php echo $names['receiver_mobile']; ?>">
                                        <label for="receiver_wa" class="input-group-text urdu">واٹس ایپ نمبر</label>
                                        <input type="text" id="receiver_wa" name="receiver_wa"
                                               class="form-control ltr" required readonly tabindex="-1"
                                               value="<?php echo $names['receiver_wa']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1">
                            <input type="hidden" name="tl_id_hidden" value="<?php echo $id; ?>">
                            <button type="submit" name="senderReceiverSubmit" class="mt-4 btn btn-dark w-100">محفوظ
                            </button>
                        </div>
                    </div>
                </form>
                <?php if (isset($_POST['senderReceiverSubmit'])) {
                    $tl_id_hidden = $_POST['tl_id_hidden'];
                    $url = $pageURL . '?id=' . $tl_id_hidden;
                    $data = array(
                        'sender_receiver' => json_encode($_POST, JSON_UNESCAPED_UNICODE)
                    );
                    $done = update('imp_truck_loadings', $data, array('id' => $_POST['tl_id_hidden']));
                    if ($done) {
                        message('success', $url, 'مال بھیجنے والا / وصول کرنے والا محفوظ ہو گیا ہے۔');
                    } else {
                        message('danger', $url, 'ڈیٹا بیس پرابلم');
                    }
                } ?>
            </div>
            <div class="card mt-2">
                <div class="table-responsive">
                    <form id="insert_form">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>سیریل نمبر</th>
                                <th>جنس نام</th>
                                <th>باردانہ نام</th>
                                <th>باردانہ تعداد</th>
                                <th>فی وزن</th>
                                <th>ٹوٹل وزن</th>
                                <th>خالی وزن</th>
                                <th>ٹوٹل خالی وزن</th>
                                <th>صاف وزن</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" id="sr_no" name="sr_no" class="form-control" disabled
                                           value="<?php echo getAutoIncrement('imp_truck_maals'); ?>">
                                </td>
                                <td>
                                    <input type="text" id="jins_name" name="jins_name" placeholder="جنس نام"
                                           autofocus class="form-control input-urdu" required>
                                </td>
                                <td>
                                    <input type="text" id="bardana_name" name="bardana_name"
                                           placeholder="باردانہ نام" class="input-urdu form-control" required>
                                </td>
                                <td>
                                    <input type="text" id="bardana_qty" name="bardana_qty"
                                           placeholder="باردانہ تعداد" onkeyup="totalWt()"
                                           class="form-control currency" required>
                                </td>
                                <td>
                                    <input type="text" id="per_wt" name="per_wt" placeholder="فی وزن"
                                           class="form-control currency" required onkeyup="totalWt()">
                                </td>
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
                                <td>
                                    <input type="text" id="total_wt" name="total_wt" placeholder="ٹوٹل وزن"
                                           class="form-control currency" readonly tabindex="-1">
                                </td>
                                <td>
                                    <input type="text" id="empty_wt" name="empty_wt" placeholder="خالی وزن"
                                           class="form-control currency" required onkeyup="totalKWt()">
                                </td>
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
                                <td>
                                    <input type="text" id="total_empty_wt" name="total_empty_wt" readonly
                                           tabindex="-1" placeholder="ٹوٹل خالی وزن" class="form-control currency">
                                </td>
                                <td>
                                    <input type="text" id="saaf_wt" name="saaf_wt" readonly tabindex="-1"
                                           placeholder="صاف وزن" class="form-control currency">
                                </td>
                                <td>
                                    <button type="submit" name="recordSubmit" id="recordSubmit"
                                            class="btn btn-outline-primary pt-0">محفوظ
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="imp_truck_maals_id" id="imp_truck_maals_id" value="0">
                        <input type="hidden" name="imp_truck_maals_action" id="imp_truck_maals_action" value="insert">
                        <input type="hidden" name="imp_tl_id" value="<?php echo $id; ?>">
                    </form>
                </div>
            </div>
            <div class="card mt-2">
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
                        </tr>
                        </thead>
                        <tbody id="records_table">
                        <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $id));
                        $x = 0;
                        $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
                        while ($maal = mysqli_fetch_assoc($maals)) {
                            $json = json_decode($maal['json_data']); ?>
                            <tr class="row-py-0 cursor-pointer" id="<?php echo $maal['id']; ?>"
                                onclick="maalEntryRowEdit(this)">
                                <td><?php echo $maal['id']; ?></td>
                                <td><?php echo $json->jins_name; ?></td>
                                <td><?php echo $json->bardana_name; ?></td>
                                <td><?php echo $json->bardana_qty; ?></td>
                                <td><?php echo $json->per_wt; ?></td>
                                <td><?php echo $json->total_wt; ?></td>
                                <td><?php echo $json->empty_wt; ?></td>
                                <td><?php echo $json->total_empty_wt; ?></td>
                                <td><?php echo $json->saaf_wt; ?></td>
                                <!--<td><input type="button" id="update_row" name="<?php /*echo $maal['id'];*/ ?>" value="update" onclick="updateFunction(event);"></td>-->
                            </tr>
                            <?php $x++;
                            $bardana_qty += $json->bardana_qty;
                            $per_wt += $json->per_wt;
                            $total_wt += $json->total_wt;
                            $empty_wt += $json->empty_wt;
                            $total_empty_wt += $json->total_empty_wt;
                            $saaf_wt += $json->saaf_wt;
                        } ?>
                        <tr class="row-py-0 bg-info bg-opacity-25 bold">
                            <td><?php echo $x; ?></td>
                            <td colspan="2"></td>
                            <td><?php echo $bardana_qty; ?></td>
                            <td><?php echo $per_wt; ?></td>
                            <td><?php echo $total_wt; ?></td>
                            <td><?php echo $empty_wt; ?></td>
                            <td><?php echo $total_empty_wt; ?></td>
                            <td><?php echo $saaf_wt; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php if ($x > 0 && !empty($record['sender_receiver']) && $record['is_saved'] == 0) { ?>
                    <a class="btn btn-primary btn-icon-text m-4 w-50 text-center" onclick="saveImpMaalEntry(this)"
                       id="<?php echo $id; ?>" data-url="<?php echo $return_url; ?>">
                        <i class="btn-icon-prepend" data-feather="check-square"></i>محفوظ کریں</a>
                <?php } ?>
            </div>
        <?php } else {
            echo '<script>window.location.href="' . $return_url . '";</script>';
        } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    function maalEntryRowEdit(e) {
        let id = $(e).attr('id');
        $.ajax({
            url: "ajax/fetchSingleImpTruckMaalEntryAdd.php",
            method: "POST",
            data: {id: id},
            success: function (data) {
                $(e).addClass('bg-light');
                $(e).siblings().removeClass("bg-light");
                let dd = $.parseJSON(data);
                $('#recordSubmit').text("تبدیل");
                $('#recordSubmit').addClass("btn-outline-danger");
                $('#recordSubmit').removeClass("btn-outline-primary");
                $('#sr_no').val(id);
                $('#imp_truck_maals_id').val(id);
                $('#imp_truck_maals_action').val("update");
                $('#jins_name').val(dd.messages.jins_name);
                $('#bardana_name').val(dd.messages.bardana_name);
                $('#bardana_qty').val(dd.messages.bardana_qty);
                $('#per_wt').val(dd.messages.per_wt);
                $('#total_wt').val(dd.messages.total_wt);
                $('#empty_wt').val(dd.messages.empty_wt);
                $('#total_empty_wt').val(dd.messages.total_empty_wt);
                $('#saaf_wt').val(dd.messages.saaf_wt);
            },
            error: function () {

            }
        });
    }
</script>
<script>
    $('#insert_form').on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "ajax/impTruckMaalsEntry.php",
            method: "POST",
            data: $('#insert_form').serialize(),
            beforeSend: function () {
                $('#recordSubmit').val("ڈیٹ محفوظ ہورہاہے");
            },
            success: function (data) {
                $('#records_table').html(data);
                $('#insert_form')[0].reset();
                $('#recordSubmit').text("محفوظ");
                $('#recordSubmit').addClass("btn-outline-primary");
                $('#recordSubmit').removeClass("btn-outline-danger");
                var sr_no = $('#sr_no').val();
                sr_no = Number(sr_no) + 1;
                $('#sr_no').val(sr_no);
            }
        });
    });
</script>
<script>
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
</script>


<script>
    $(function () {
        var sel_godam_loading_id = $('#godam_loading_id').val();
        loadingAjax(sel_godam_loading_id);
    });
    $('#godam_loading_id').change(function () {
        var godam_loading_id = $(this).val();
        loadingAjax(godam_loading_id);
    });

    function loadingAjax(godam_loading_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleGodamLoading.php',
            type: 'post',
            data: {godam_loading_id: godam_loading_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#godam_loading_mobile").val(response.messages['mobile1']);
                    $("#godam_loading_munshi").val(response.messages['munshi']);
                    $("#godam_loading_address").val(response.messages['address']);
                    $("#responseGodamLoading").text('');
                }
                if (response.success === false) {
                    $("#responseGodamLoading").text('لوڈنگ کرنے گودام درست نہیں ہے');
                }
            }
        });
    }

    $(function () {
        var sel_godam_empty_id = $('#godam_empty_id').val();
        emptyAjax(sel_godam_empty_id);
    });
    $('#godam_empty_id').change(function () {
        var godam_empty_id = $(this).val();
        emptyAjax(godam_empty_id);
    });

    function emptyAjax(godam_empty_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleGodamEmpty.php',
            type: 'post',
            data: {godam_empty_id: godam_empty_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#godam_empty_mobile").val(response.messages['mobile1']);
                    $("#godam_empty_munshi").val(response.messages['munshi']);
                    $("#godam_empty_address").val(response.messages['address']);
                    $("#responseGodamEmpty").text('');
                }
                if (response.success === false) {
                    $("#responseGodamEmpty").text('خالی کرنے گودام درست نہیں ہے');
                }
            }
        });
    }

</script>
<script>
    /*sender*/
    $(function () {
        senderAjax($('#sender_id').val());
    });
    $('#sender_id').change(function () {
        senderAjax($(this).val());
    });

    function senderAjax(dt_sender_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleDTSender.php',
            type: 'post',
            data: {dt_sender_id: dt_sender_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#sender_name").val(response.messages['comp_name']);
                    $("#sender_address").val(response.messages['address']);
                    $("#sender_mobile").val(response.messages['mobile']);
                    $("#sender_wa").val(response.messages['whatsapp']);
                    $("#responseDTSender").text('');
                }
                if (response.success === false) {
                    $("#responseDTSender").text('مال بھیجنےوالا');
                }
            }
        });
    }

    /*receiver*/
    $(function () {
        receiverAjax($('#receiver_id').val());
    });
    $('#receiver_id').change(function () {
        receiverAjax($(this).val());
    });

    function receiverAjax(dt_receiver_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleDTReceiver.php',
            type: 'post',
            data: {dt_receiver_id: dt_receiver_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#receiver_name").val(response.messages['comp_name']);
                    $("#receiver_address").val(response.messages['address']);
                    $("#receiver_mobile").val(response.messages['mobile']);
                    $("#receiver_wa").val(response.messages['whatsapp']);
                    $("#responseDTReceiver").text('');
                }
                if (response.success === false) {
                    $("#responseDTReceiver").text('مال وصول کرنے والا');
                }
            }
        });
    }
</script>
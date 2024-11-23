<?php include("header.php");
$pageURL = 'imp-maal-transfer-form';
$searchUserName = $username_msg = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');

$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=1 AND is_saved=1 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $searchUserName = $_GET['username'];
        $sql .= " AND truck_no = " . "'$searchUserName'" . " ";
        $username_msg = '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>';
    }
    echo '<div class="filter-div">' . $date_msg . $username_msg . $removeFilter . '</div>';
}
$sql .= " ORDER BY id DESC ";
$records = mysqli_query($connect, $sql);
$noOfRows = mysqli_num_rows($records); ?>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n1">امپورٹ مال انٹری کو ٹرانسفر کریں</h4>
    </div>
    <div class="urdu">تعداد انٹری <span id="count_rows_span" class="bold underline"></span></div>
    <form name="datesSubmit" method="get" class="d-flex">
        <div class="urdu d-flex align-items-center wd-md-120 me-2">
            <?php echo searchInput('a'); ?>
        </div>
        <div class="input-group flatpickr wd-130" id="flatpickr-date">
            <input id="r_date_start" name="r_date_start" value="<?php echo $start_date; ?>" type="text"
                   class="form-control " placeholder="تاریخ ابتداء" data-input>
            <label for="r_date_end" class="input-group-text urdu">سے</label>
        </div>
        <div class="flatpickr wd-80" id="flatpickr-date">
            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="text"
                   class="form-control" placeholder="تاریخ انتہاء" data-input>
        </div>
        <div class="input-group wd-200">
            <label for="username" class="input-group-text urdu">ٹرک
                نمبر</label>
            <input type="text" id="username" name="username" class="form-control urdu"
                   placeholder="ٹرک نمبر" value="<?php echo $searchUserName; ?>" required>
        </div>
    </form>
</div>
<div class="row mt-3 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-sm" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <th class="small">لوڈنگ#</th>
                        <th class="small">لوڈنگ تاریخ</th>
                        <th>ٹرک نمبر</th>
                        <th>ڈرائیورنام</th>
                        <th class="small-3">سیریل<br> تعداد</th>
                        <th>بھیجنےوالا</th>
                        <th class="small-2">وصول کرنےوالا</th>
                        <th> شہر</th>
                        <th class="small-2">لوڈکرانےگودام</th>
                        <th class="small-2">خالی کرانےگودام</th>
                        <th class="small-2">ٹوٹل باردنہ</th>
                        <th class="small-2">ٹوٹل وزن</th>
                        <th class="small" width="20%">ٹرانسفر</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 0;
                    while ($loading = mysqli_fetch_assoc($records)) {
                        $transfer_to_forms_json = array();
                        if (empty($loading['transfer_to_forms'])) {
                            //in-complete form, show here
                        } else {
                            continue;
                            $transfer_to_forms_json = json_decode($loading['transfer_to_forms']);
                            $transfer_to_forms_json = implode(',', $transfer_to_forms_json);
                            $transfer_to_forms_json = explode(',', $transfer_to_forms_json);
                        }
                        if (!empty($loading['sender_receiver'])) {
                            $sender_receiver = json_decode($loading['sender_receiver']);
                            $names = array(
                                'sender_name' => $sender_receiver->sender_name,
                                'sender_address' => $sender_receiver->sender_address,
                                'sender_mobile' => $sender_receiver->sender_mobile,
                                'sender_wa' => $sender_receiver->sender_wa,
                                'receiver_name' => $sender_receiver->receiver_name,
                                'receiver_address' => $sender_receiver->receiver_address,
                                'receiver_mobile' => $sender_receiver->receiver_mobile,
                                'receiver_wa' => $sender_receiver->receiver_wa
                            );
                        } else {
                            $names = array(
                                'sender_name' => '',
                                'sender_address' => '',
                                'sender_mobile' => '',
                                'sender_wa' => '',
                                'receiver_name' => '',
                                'receiver_address' => '',
                                'receiver_mobile' => '',
                                'receiver_wa' => ''
                            );
                        } ?>
                        <tr class="text-nowrap">
                            <td><?php echo $loading["id"]; ?></td>
                            <td class="small-2"><?php echo $loading['loading_date']; ?></td>
                            <td class="small"><?php echo $loading['truck_no']; ?></td>
                            <td class="small-2"><?php echo $loading['driver_name']; ?><br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                            <td>
                                <?php $tadadQuery = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                                echo mysqli_num_rows($tadadQuery); ?>
                            </td>
                            <td class="small-3"><?php echo $names['sender_name']; ?>
                                <br><span dir="ltr"><?php echo $names['sender_mobile']; ?></span>
                            </td>
                            <td class="small-3"><?php echo $names['receiver_name']; ?>
                                <br><span dir="ltr"><?php echo $names['receiver_mobile']; ?></span>
                            </td>
                            <td class="small-2"><?php echo $loading['sender_city']; ?></td>
                            <td class="small-3">
                                <?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                <br><span dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-3">
                                <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                <br><span
                                    dir="ltr"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                            </td>
                            <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $loading['id']));
                            $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
                            while ($maal = mysqli_fetch_assoc($maals)) {
                                $json = json_decode($maal['json_data']);
                                $bardana_qty += $json->bardana_qty;
                                $per_wt += $json->per_wt;
                                $total_wt += $json->total_wt;
                                $empty_wt += $json->empty_wt;
                                $total_empty_wt += $json->total_empty_wt;
                                $saaf_wt += $json->saaf_wt;
                            } ?>
                            <td><?php echo $bardana_qty; ?></td>
                            <td><?php echo $total_wt; ?></td>
                            <td>
                                <form method="post">
                                    <div class="input-group d-flex">
                                        <select multiple name="transfer_to_forms[]" id="t_ids<?php echo $no; ?>"
                                                onchange="transfVals(this)" required
                                                class="virtual-select saif bg-transparent w-75">
                                            <?php $tt = fetch('imp_transfer_to_names');
                                            while ($t = mysqli_fetch_assoc($tt)) {
                                                $form_selected = in_array($t['t_value'], $transfer_to_forms_json) ? 'selected' : '';
                                                echo '<option ' . $form_selected . ' value="' . $t['t_value'] . '">' . $t['t_name'] . '</option>';
                                            } ?>
                                        </select>
                                        <input type="hidden" value="<?php echo $loading["id"]; ?>" name="tl_id_hidden">
                                        <button type="submit" name="transferToFormsSubmit" class="btn btn-outline-primary btn-sm py-0">محفوظ</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php $no++;
                    } ?>
                    </tbody>
                    <input type="hidden" id="count_rows" value="<?php echo $no; ?>">
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['transferToFormsSubmit'])) {
    $url = 'imp-maal-transfer-form';
    $tl_id_hidden = $_POST['tl_id_hidden'];
    $transfer_to_forms = json_encode($_POST['transfer_to_forms']);
    $dataTransfer = array(
        'transfer_to_forms' => $transfer_to_forms,
        'transfer_to_forms_date' => date('Y-m-d')
    );
    $upp = update('imp_truck_loadings', $dataTransfer, array('id' => $tl_id_hidden));
    if ($upp) {
        message('success', $url, 'ٹرانسفر ہو گیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>
<script>
    /*$("select option").each(function () {
     var $thisOption = $(this);
     var valueToCompare = "kiraya";

     if ($thisOption.val() == valueToCompare) {
     $thisOption.attr("disabled", "disabled");
     }
     });*/
    /*document.querySelector('#t_ids').addEventListener('afterOpen', function () {
     alert(this.value);
     });*/
    document.querySelector('.saif').addEventListener('change', function (e) {
        var values = this.value;
        if (values.includes('kilo')) {
            $(this).parent().parent().find("div[data-value='commission'").removeClass('selected');
            $(this).parent().parent().find("div[data-value='kilo'").addClass('selected');
        }
        if (values.includes('commission')) {
            $(this).parent().parent().find("div[data-value='kilo'").removeClass('selected');
            $(this).parent().parent().find("div[data-value='commission'").addClass('selected');
        }
    });

    function transfVals(e) {
        var id = $(e).attr('id');
        // alert(id);
        //$('#dropDownId :selected').text();
        document.querySelector(id).addEventListener('change', function () {
            //alert(this.value);
        });
    }

    /*$('#t_ids').on('change', function () {
     alert(this.value);
     });*/
</script>
<script>
    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            var username = $("#username").val();
            if (username == '' || username.length < 3) {
                evt.preventDefault();
                return false;
            }
            document.datesSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
    $("#count_rows_span").text($("#count_rows").val());
</script>
<script>
    function transferImpTruckLoading(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferImpTruckLoading.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>

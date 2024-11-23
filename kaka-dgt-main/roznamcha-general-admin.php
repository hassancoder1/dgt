<?php include("header.php"); ?>
<?php include("only-admin.php"); ?>
<?php $searchUserName = $date_append = $username_append = $branch_msg = $selectedBranch = $date_msg = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_id > 0 ";
if ($_POST) {
    $removeFilter = removeFilter('roznamcha-general-admin');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date' " . " ";
        $date_msg = '<span class="badge bg-secondary "><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
    }
    if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
        $postBranchId = $_POST['branch_id'];
        if ($postBranchId > 0) {
            $sql .= " AND branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
            $branch_msg = '<span class="badge bg-dark urdu ms-1">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
        } else {
            $branch_msg = '<span class="badge bg-dark urdu ms-1">آل برانچ</span>';
        }
    }
} else {
    $sql .= " AND r_date = '$start_date'" . " ";
}
//echo $sql;
//$sqlStats = "SELECT * FROM roznamchaas WHERE r_id > 0 {$date_append} {$username_append} {$branch_append}";
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$bnaamTotal = $jmaaTotal = $mezan = $totalRows = 0;
$totalRows = mysqli_num_rows($recordStats);
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<div class="filter-div">
    <?php echo $date_msg . $branch_msg . '<span class="badge bg-primary ms-1">' . $searchUserName . '</span>' . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n3">ایڈمن جنرل روزنامچہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-110 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <div class="input-group wd-130 mb-2 mb-md-0">
                <label for="branch_id" class="input-group-text input-group-addon bg-transparent urdu ps-0">برانچ</label>
                <select id="branch_id" name="branch_id" class="form-select bg-transparent border-primary">
                    <option hidden value="">برانچ انتخاب</option>
                    <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>>آل برانچ</option>
                    <?php $branches = fetch('branches');
                    while ($branch = mysqli_fetch_assoc($branches)) {
                        $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                        echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="input-group wd-120 mb-2 mb-md-0">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu ps-0">یوزر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="یوزر" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-120 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
        </div>
        <div class="input-group wd-100 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">اندراج</span>
            <input type="text" autofocus class="form-control bg-transparent border-primary"
                  disabled value="<?php echo $totalRows; ?>">
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل جمع"
                   value="<?php echo $jmaaTotal; ?>" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <div class="input-group wd-140 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
        <form action="print/roznamcha-full" method="post" target="_blank">
            <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
            <input name="r_type" value="<?php echo GENERAL; ?>" type="hidden">
            <input name="r_date_start" value="<?php echo $start_date; ?>" type="hidden">
            <input name="r_date_end" value="<?php echo $end_date; ?>" type="hidden">
            <input name="branch_id" value="<?php echo $selectedBranchId; ?>" type="hidden">
            <input name="username" value="<?php echo $searchUserName; ?>" type="hidden">
            <input name="url" value="roznamcha-general-admin" type="hidden">
            <button type="submit" class="btn btn-primary btn-icon-text pt-0 me-1">
                <i class="btn-icon-prepend me-0" data-feather="printer"></i>
            </button>
        </form>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-sm" id="fix-head-table">
                    <thead>
                    <tr>
                        <th width="7%">روزنامچہ نام</th>
                        <th width="10%"><span>برانچ #</span> <span>مین #</span></th>
                        <th width="6%">برانچ</th>
                        <th width="6%">یوزر</th>
                        <th width="7%">کھاتہ نمبر</th>
                        <th width="7%">روزنامچہ نمبر</th>
                        <th width="10%">نام</th>
                        <th width="7%">نمبر</th>
                        <th width="26%">تفصیل</th>
                        <th width="7%">جمع</th>
                        <th width="7%">بنام</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($records) > 0) {
                        while ($roz = mysqli_fetch_assoc($records)) { ?>
                            <tr >
                                <td class="text-nowrap small-3">
                                    <?php echo roznamchaName($roz['r_type']); ?>
                                    <?php echo roznamchaName($roz['transfered_from']); ?>
                                </td>
                                <td class="d-flex justify-content-between align-items-center">
                                    <?php $editUrl = "";
                                    if ($roz['r_type'] == "karobar") {
                                        $editUrl = "roznamcha-karobar-add?id=" . $roz["r_id"];
                                    }
                                    if ($roz['r_type'] == "bank") {
                                        $editUrl = "roznamcha-bank-add?id=" . $roz["r_id"];
                                    }
                                    if ($roz['r_type'] == "bill") {
                                        $editUrl = "roznamcha-bill-add?id=" . $roz["r_id"];
                                    } ?>
                                    <a href="<?php echo $editUrl; ?>"><?php echo $roz['branch_serial'] . ' - ' . $roz['r_id']; ?></a>
                                    <a onclick="deleteRoznamcha(this)"
                                       id="<?php echo $roz['r_id']; ?>"
                                       data-url="roznamcha-general-admin"
                                       data-r-type="<?php echo $roz['r_type']; ?>"
                                       data-jmaa="<?php echo $roz['jmaa_amount']; ?>"
                                       data-bnaam="<?php echo $roz['bnaam_amount']; ?>"
                                       class="btn btn-outline-danger py-0 px-1 urdu-2 small-3 mt-2">ختم
                                    </a>
                                    <script>
                                        function roznamchaName(type=null) {
                                            var typeee = "";
                                            switch (type) {
                                                case "karobar":
                                                    typeee = 'کاروبار روزنامچہ';
                                                    break;
                                                case "bank":
                                                    typeee = 'بینک روزنامچہ';
                                                    break;
                                                case "bill":
                                                    typeee = 'بل روزنامچہ';
                                                    break;
                                                case "AFG":
                                                    typeee = 'افغانی ٹرک کرایہ';
                                                    break;
                                                case "import_exp":
                                                    typeee = 'امپورٹ کسٹم خرچہ';
                                                    break;
                                                case "dt_custom_exp":
                                                    typeee = 'ڈاون ٹرانزٹ کسٹم خرچہ';
                                                    break;
                                                case "r_office_exp":
                                                    typeee = 'آفس خرچہ';
                                                    break;
                                                case "r_home_exp":
                                                    typeee = 'گھر خرچہ';
                                                    break;
                                                case "kiraya_summary":
                                                    typeee = 'امپورٹ کرایہ سمری';
                                                    break;
                                                case "ut_karachi":
                                                    typeee = 'کراچی امپورٹ';
                                                    break;
                                                case "ut_chaman":
                                                    typeee = 'چمن ایکسپورٹ';
                                                    break;
                                                case "ut_border":
                                                    typeee = 'افغان بارڈر';
                                                    break;
                                                case "ut_qandhar":
                                                    typeee = 'قندھار کسٹم';
                                                    break;
                                                case "ut_border_bill":
                                                    typeee = 'بارڈر بل';
                                                    break;
                                                default:
                                                    typeee = 'نامعلوم';
                                                    break;
                                            }
                                            return typeee;
                                        }
                                        function deleteRoznamcha(e) {
                                            var id = $(e).attr('id');
                                            var url = $(e).attr('data-url');
                                            var r_type = $(e).attr('data-r-type');
                                            var r_type_u = roznamchaName(r_type);
                                            var jmaa = $(e).attr('data-jmaa');
                                            var bnaam = $(e).attr('data-bnaam');
                                            var msgg = 'کیا آپ واقعی ختم کرنا چاہتے ہیں؟';
                                            msgg += '\n';
                                            msgg += 'روزنامچہ نام: ' + r_type_u;
                                            msgg += '\n';
                                            msgg += 'جمع: ' + jmaa;
                                            msgg += '\n';
                                            msgg += 'بنام: ' + bnaam;
                                            if (id && url && r_type) {
                                                if (confirm(msgg)) {
                                                    window.location.href = 'ajax/deleteRoznamcha.php?id=' + id +
                                                        '&url=' + url + '&r_type=' + r_type;
                                                }
                                            } else {
                                                alert("پیج کو ریفریش کرکے دوبارہ کوشش کریں۔");
                                            }
                                        }
                                    </script>
                                </td>
                                <?php if (Administrator()) { ?>
                                    <td class="small-3"><?php echo branchName($roz['branch_id']); ?></td>
                                <?php } ?>
                                <td><?php echo getTableDataByIdAndColName('users', $roz['user_id'], 'username'); ?></td>
                                <td><?php echo $roz['khaata_no']; ?></td>
                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                <td class="urdu-td small-2"><?php echo $roz['r_name']; ?></td>
                                <td><?php echo $roz['r_no']; ?></td>
                                <?php $str = "";
                                if ($roz['jmaa_amount'] == 0) {
                                    $str = "بنام:- ";
                                }
                                if ($roz['bnaam_amount'] == 0) {
                                    $str = "جمع:- ";
                                } ?>
                                <td class="small-3"><?php echo $str . $roz['details']; ?></td>
                                <td><?php echo $roz['jmaa_amount']; ?></td>
                                <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr class="text-center"><th colspan="10"> کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
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
    $(function () {
        $('#r_date_start, #r_date_end, #branch_id').change(function () {
            document.datesSubmit.submit();
        });
        /*$('#branch_id').change(function () {
         document.userNameSubmit.submit();
         });*/
    });
</script>
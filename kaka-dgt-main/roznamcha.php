<?php $page_title = 'Roznamcha';
include("header.php");
$pageURL = 'roznamcha';
global $branchId;
$searchUserName = $selectedBranch = $date_msg = $branch_msg = $username_msg = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_id>0 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary urdu"><span class="me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $searchUserName = $_GET['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        //$username_msg = '<span class="badge bg-primary ms-1">' . $searchUserName . '</span>';
        $username_msg = '<span class="badge bg-primary ms-1 pt-2">' . $searchUserName . '</span>';
    }
    if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
        $postBranchId = $_GET['branch_id'];
        if ($postBranchId > 0) {
            $sql .= "AND branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
            $branch_msg = '<span class="badge bg-dark ms-1 pt-1 urdu">' . branchName($selectedBranchId) . '</span>';
        }
    }
} else {
    $sql .= " AND r_date = '$start_date'";
}
$sql .= Administrator() ? " " : " AND branch_id = '$branchId' ";
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$bnaamTotal = $jmaaTotal = $mezan = $totalRows = 0;
$totalRows = mysqli_num_rows($recordStats);
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal;
//echo $sql;?>
<?php echo '<div class="filter-div">' . $date_msg . $branch_msg . $username_msg . $removeFilter . '</div>'; ?>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mt-n2 mb-3 mb-md-0">روزنامچہ
            <sub> <?php echo $branch_msg; ?> </sub>
        </h4>
    </div>
    <div class="d-flex gap-md-2 urdu">
        <div>تعداد انٹری <span id="rows_span" class="bold underline"></span></div>
        <div class="mx-3">جمع <span id="dr_total_span" class="bold underline"></span></div>
        <div>بنام <span id="cr_total_span" class="bold underline"></span></div>
    </div>
    <form name="datesSubmit" method="get">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group flatpickr wd-110" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>" type="text"
                       class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-80 me-2" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <?php if (Administrator()) { ?>
                <div class="input-group wd-150 me-2">
                    <select id="branch_id" name="branch_id" class="form-select">
                        <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>> آل برانچ</option>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) {
                            $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                            echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                        } ?>
                    </select>
                </div>
            <?php } ?>
            <div class="input-group wd-100 me-2">
                <input type="text" id="username" name="username" class="form-control urdu"
                       placeholder="یوزر آئی ڈی" value="<?php echo $searchUserName; ?>">
            </div>
            <div class="wd-100">
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
        </div>
    </form>
    <div class="d-flex ac_row table-form">
        <?php if (Administrator()) { ?>
            <form action="print/roznamcha-full" method="post" target="_blank">
                <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
                <input name="r_type" value="<?php echo GENERAL; ?>" type="hidden">
                <input name="r_date_start" value="<?php echo $start_date; ?>" type="hidden">
                <input name="r_date_end" value="<?php echo $end_date; ?>" type="hidden">
                <input name="branch_id" value="<?php echo $selectedBranchId; ?>" type="hidden">
                <input name="username" value="<?php echo $searchUserName; ?>" type="hidden">
                <input name="url" value="roznamcha" type="hidden">
                <button type="submit" class="btn btn-primary btn-icon-text pt-0 me-1">
                    <i class="btn-icon-prepend me-0" data-feather="printer"></i>
                </button>
            </form>
        <?php } ?>
        <?php echo addNew('roznamcha-add', '', 'ms-2 '); ?>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            }
            //echo $sql; ?>
            <div class="table-responsive scroll screen-ht-">
                <table class="table table-bordered table-sm" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <th>روزنامچہ نام</th>
                        <th>سیریل</th>
                        <?php echo Administrator() ? '<th>برانچ</th>' : ''; ?>
                        <th>یوزر</th>
                        <th>کھاتہ نمبر</th>
                        <th>روزنامچہ نمبر</th>
                        <th>نام</th>
                        <th>نمبر</th>
                        <th width="">تفصیل</th>
                        <th>جمع</th>
                        <th>بنام</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $bnaamTotal = $jmaaTotal = $mezan = $totalRows = 0;
                    if (mysqli_num_rows($records) > 0) {
                        while ($roz = mysqli_fetch_assoc($records)) {
                            $r_id = $roz['r_id']; ?>
                            <tr>
                                <td class="text-nowrap small-3">
                                    <?php echo roznamchaName($roz['r_type']);
                                    echo roznamchaName($roz['transfered_from']); ?>
                                </td>
                                <td class="d-flex justify-content-between align-items-center text-nowrap">
                                    <?php $sr = Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial'];
                                    echo '<a href="roznamcha-add?id=' . $r_id . '">' . $sr . '</a>'; ?>
                                </td>
                                <?php echo Administrator() ? '<td class="small-3 text-nowrap">' . branchName($roz['branch_id']) . '</td>' : ''; ?>
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
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo 'print/roznamcha-single?r_id=' . base64_encode($r_id) . '&secret=' . base64_encode('powered-by-upsol'); ?>"
                                           class="btn btn-primary px-1 py-0 btn-sm" data-bs-toggle="tooltip"
                                           data-bs-placement="top" title="Print">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        <?php if (Administrator()) { ?>
                                            <a onclick="deleteRoznamcha(this)" id="<?php echo $roz['r_id']; ?>"
                                               data-url="roznamcha"
                                               data-r-type="<?php echo $roz['r_type']; ?>"
                                               data-jmaa="<?php echo $roz['jmaa_amount']; ?>"
                                               data-bnaam="<?php echo $roz['bnaam_amount']; ?>"
                                               class="btn btn-danger px-1 py-0 btn-sm " data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fa fa-trash"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php $totalRows++;
                            $jmaaTotal += $roz['jmaa_amount'];
                            $bnaamTotal += $roz['bnaam_amount'];
                        }
                    } else {
                        echo '<tr class="text-center"><th colspan="12"> کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="rows" value="<?php echo $totalRows; ?>">
                <input type="hidden" id="dr_total" value="<?php echo $jmaaTotal; ?>">
                <input type="hidden" id="cr_total" value="<?php echo $bnaamTotal; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_span").text($("#rows").val());
    $("#dr_total_span").text($("#dr_total").val());
    $("#cr_total_span").text($("#cr_total").val());
</script>
<script>
    function roznamchaName(type = null) {
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
    });

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

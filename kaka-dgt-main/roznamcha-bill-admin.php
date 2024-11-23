<?php include("header.php"); ?>
<?php include("only-admin.php"); ?>
<?php $searchUserName = $selectedBranch = $date_msg = $branch_msg = $username_msg = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_type= 'bill' ";
if ($_POST) {
    $removeFilter = removeFilter('roznamcha-bill-admin');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $username_msg = '<span class="badge bg-primary pt-2 ms-1">' . $searchUserName . '</span>';
    }
    if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
        $postBranchId = $_POST['branch_id'];
        if ($postBranchId > 0) {
            $sql .= "AND branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
            $branch_msg = '<span class="badge bg-dark urdu ms-1">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
        }
    }
} else {
    $sql .= " AND r_date = '$start_date'";
}
//echo $sql;
//$sqlStats = "SELECT * FROM roznamchaas WHERE r_type= 'bill' {$date_append} {$username_append} {$branch_append}";
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$totalRows = mysqli_num_rows($recordStats);
$bnaamTotal = $jmaaTotal = $mezan = $bnaam_qtyTotal = $jmaa_qtyTotal = $mezanQty = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
    $bnaam_qtyTotal += $stat['bnaam_qty'];
    $jmaa_qtyTotal += $stat['jmaa_qty'];
}
$mezan = $jmaaTotal - $bnaamTotal;
$mezanQty = $jmaa_qtyTotal - $bnaam_qtyTotal; ?>
<div class="filter-div">
    <?php echo $date_msg . $username_msg . $branch_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-2 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">ایڈمن بل روزنامچہ</h4>
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
            <div class="input-group wd-120 mb-2 mb-md-0">
                <label for="branch_id" class="input-group-text input-group-addon bg-transparent urdu">برانچ</label>
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
            <div class="input-group wd-100 mb-2 mb-md-0">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">یوزر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="یوزر" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-100 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
        </div>
        <div class="input-group wd-90 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">اندراج</span>
            <input type="text" autofocus class="form-control bg-transparent border-primary"
                   disabled value="<?php echo $totalRows; ?>">
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل جمع"
                   value="<?php echo $jmaaTotal; ?>" readonly>
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
        <a href="roznamcha-bill-currency-admin"
           class="btn btn-outline-primary btn-icon-text py-1 me-1">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>بل کرنسی</a>
        <form action="print/roznamcha-full" method="post" target="_blank">
            <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
            <input name="r_type" value="<?php echo BILL; ?>" type="hidden">
            <input name="r_date_start" value="<?php echo $start_date; ?>" type="hidden">
            <input name="r_date_end" value="<?php echo $end_date; ?>" type="hidden">
            <input name="branch_id" value="<?php echo $selectedBranchId; ?>" type="hidden">
            <input name="username" value="<?php echo $searchUserName; ?>" type="hidden">
            <input name="url" value="roznamcha-bill-admin" type="hidden">
            <button type="submit" class="btn btn-primary btn-icon-text pt-0 me-1">
                <i class="btn-icon-prepend me-0" data-feather="printer"></i>
            </button>
        </form>
    </div>
    <!--<div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <div class="row gy-0">
                <div class="col">
                    <div class="input-group mb-2 mb-md-0">
                        <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع تعداد</span>
                        <input type="text" class="form-control bg-transparent border-primary"
                               value="<?php /*echo $jmaa_qtyTotal; */ ?>" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-2 mb-md-0">
                        <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام تعداد</span>
                        <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                               value="<?php /*echo $bnaam_qtyTotal; */ ?>" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-2 mb-md-0">
                        <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
                        <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="میزان"
                               value="<?php /*echo $mezanQty; */ ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>-->
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="table-responsive scroll screen-ht-71" style="height: 78vh;">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th width="7%"><span>برانچ #</span> <span>مین #</span></th>
                            <th width="6%">برانچ</th>
                            <th width="5%">یوزر</th>
                            <th width="6%">کھاتہ نمبر</th>
                            <th width=7%">روزنامچہ نمبر</th>
                            <th width="6%">نام</th>
                            <th width="6%">نمبر</th>
                            <th width="5%">جنس</th>
                            <th width="25%">تفصیل</th>
                            <th width="5%">فی قیمت</th>
                            <th width="5%">جمع تعداد</th>
                            <th width="5%">بنام تعداد</th>
                            <th width="6%">جمع</th>
                            <th width="6%">بنام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($records) > 0) {
                            while ($roz = mysqli_fetch_assoc($records)) { ?>
                                <tr>
                                    <td>
                                        <a href="roznamcha-bill-add?id=<?php echo $roz["r_id"]; ?>"><?php echo $roz['branch_serial'] . ' - ' . $roz['r_id']; ?></a>
                                    </td>
                                    <td class="small">
                                        <?php echo getTableDataByIdAndColName('branches', $roz['branch_id'], 'b_name'); ?></td>
                                    <td><?php echo getTableDataByIdAndColName('users', $roz['user_id'], 'username'); ?></td>
                                    <td><?php echo $roz['khaata_no']; ?></td>
                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                    <td class="small"><?php echo $roz['r_name']; ?></td>
                                    <td><?php echo $roz['r_no']; ?></td>
                                    <td><?php echo $roz['r_jins']; ?></td>
                                    <?php $str = "";
                                    if ($roz['jmaa_amount'] == 0) {
                                        $str = "بنام:- ";
                                    }
                                    if ($roz['bnaam_amount'] == 0) {
                                        $str = "جمع:- ";
                                    } ?>
                                    <td class="small"><?php echo $str;
                                        echo $roz['details']; ?></td>
                                    <td><?php echo $roz['per_price']; ?></td>
                                    <td><?php echo $roz['jmaa_qty']; ?></td>
                                    <td><?php echo $roz['bnaam_qty']; ?></td>
                                    <td><?php echo $roz['jmaa_amount']; ?></td>
                                    <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                </tr>
                            <?php }
                        } else {
                            echo '<tr class="text-center"><th colspan="14"> کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-lg" style="position: fixed; bottom: 0; width: 100%; left: 0; right: 0;">
    <div class="card-body pt-0 pb-1">
        <div class="d-flex float-end">
            <div class="w-200">
                <p class="d-flex">
                    <span class="urdu me-2">کل جمع تعداد</span>
                    <span class="mt-1 border-bottom border-secondary px-3"><?php echo $jmaa_qtyTotal; ?></span>
                </p>
            </div>
            <div class="w-200 ms-4">
                <p class="d-flex">
                    <span class="urdu me-2">کل بنام تعداد</span>
                    <span class="mt-1 text-danger border-bottom border-secondary px-3"><?php echo $bnaam_qtyTotal; ?></span>
                </p>
            </div>
            <div class="w-200 ms-4">
                <p class="d-flex">
                    <span class="urdu me-2">میزان</span>
                    <span class="mt-1 border-bottom border-secondary px-3"><?php echo $mezanQty; ?></span>
                </p>
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
    });
</script>

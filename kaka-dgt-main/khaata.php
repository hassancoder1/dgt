<?php include("header.php"); ?>
<?php $cat_append = $branch_append = $searchKhaataName = $khaata_append = $removeFilter = $cat_msg = "";
$cat_ids = array();
$selectedBranchId = 0;
$sql = "SELECT * FROM `khaata` WHERE id > 0 ";
if ($_GET) {
    $removeFilter = removeFilter('khaata');
    if (isset($_GET['cat_ids']) && !empty($_GET['cat_ids'][0])) {
        $branch_append = $khaata_append = "";
        $cat_ids = $_GET['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $cat_append = " AND cat_id IN " . $in;
        $sql .= $cat_append;
        if (!empty($_GET['branch_id'])) {
            $postBranchId = $_GET['branch_id'];
            $selectedBranchId = $postBranchId;
            if ($postBranchId > 0) {
                $branch_append = " AND branch_id = " . "'$postBranchId'" . " ";
                $sql .= $branch_append;
            }
        }
        $cat_msg = 'کیٹگری: ';
        foreach ($cat_ids as $cc) {
            $cat_msg .= getTableDataByIdAndColName('cats', $cc, 'c_name') . ' | ';
        }
    } elseif (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
        $cat_append = $khaata_append = "";
        $postBranchId = $_GET['branch_id'];
        $selectedBranchId = $postBranchId;
        if ($postBranchId > 0) {
            $branch_append = " AND branch_id = " . "'$postBranchId'" . " ";
        }
        $sql .= $branch_append;
        if (isset($_GET['cat_ids']) && !empty($_GET['cat_ids'][0])) {
            $cat_ids = $_GET['cat_ids'];
            $cat_ids = explode(',', $cat_ids[0]);
            $in = "(" . implode(',', $cat_ids) . ")";
            $cat_append = " AND cat_id IN " . $in;
            $cat_msg = 'کیٹگری: ';
            foreach ($cat_ids as $cc) {
                $cat_msg .= ' ' . getTableDataByIdAndColName('cats', $cc, 'c_name') . ' | ';
            }
            $sql .= $cat_append;
        }
    } elseif (isset($_GET['khaata_no_s']) && !empty($_GET['khaata_no_s'])) {
        $cat_append = $branch_append = "";
        $khaata_no_s = $_GET['khaata_no_s'];
        $khaata_append = " AND khaata_no = " . "'$khaata_no_s'" . " ";
        $searchKhaataName = $khaata_no_s;
        $sql .= $khaata_append;
    }
} else {
    $removeFilter = $cat_append = $branch_append = $khaata_append = "";
} ?>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div class="d-flex align-items-center">
        <h4 class="">کھاتہ</h4>
        <div class="filter-div-">
            <?php echo '<span class="badge bg-primary">' . $cat_msg . '</span>' . '<span class="badge bg-light text-dark border border-success urdu">' . $searchKhaataName . '</span>';
            if ($selectedBranchId > 0) {
                echo '<span class="badge bg-dark urdu">' . branchName($selectedBranchId) . '</span>';
            }
            echo $removeFilter; ?>
        </div>
    </div>
    <form name="userNameSubmit" method="get">
        <div class="d-flex align-items-center flex-wrap text-nowrap d-print-none">
            <div class="input-group wd-150 mb-2 mb-md-0 ms-2">
                <select multiple name="cat_ids[]" id="cat_ids" placeholder="کیٹیگری" class="virtual-select bg-transparent">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        if (in_array($cat['id'], $cat_ids)) {
                            $c_selected = 'selected';
                        } else {
                            $c_selected = '';
                        }
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <?php if (Administrator()) { ?>
                <div class="input-group wd-180 mb-2 mb-md-0">
                    <label for="branch_id" class="input-group-text input-group-addon bg-transparent urdu">برانچ</label>
                    <select id="branch_id" name="branch_id" class="form-select bg-transparent border-primary">
                        <option hidden value="">برانچ</option>
                        <option value="0">آل برانچ</option>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) {
                            $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                            echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                        } ?>
                    </select>
                </div>
            <?php } ?>
            <div class="input-group wd-150 mb-2 mb-md-0">
                <label for="khaata_no_s" class="input-group-text input-group-addon bg-transparent urdu">کھاتہ
                    نام</label>
                <input type="text" id="khaata_no_s" name="khaata_no_s"
                       class="form-control bg-transparent border-primary"
                       placeholder="کھاتہ نام" value="<?php echo $searchKhaataName; ?>" required>
            </div>
            <div class="input-group wd-200 me-2 mb-2 mb-md-0">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle="">
                    <i class="btn-icon-prepend text-primary" data-feather="search"></i></span>
                <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                       placeholder="ٹیبل میں تلاش کریں (F2)">
            </div>
            <a href="khaata-add"
               class="btn btn-outline-primary btn-icon-text py-1">
                <i class="btn-icon-prepend" data-feather="file-plus"></i>اندراج</a>
        </div>
    </form>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                }
                //echo $sql; ?>
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th>کھاتہ#</th>
                            <th class="small">کیٹیگری</th>
                            <th class="">برانچ</th>
                            <th>کھاتہ نام</th>
                            <th class="small">کمپنی نام</th>
                            <th>کاروبار نام</th>
                            <th>شہر</th>
                            <th>کاروبار پتہ</th>
                            <th>موبائل</th>
                            <th>واٹس ایپ</th>
                            <th>فون</th>
                            <th>ای میل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $khaatas = mysqli_query($connect, $sql);
                        while ($khaata = mysqli_fetch_assoc($khaatas)) { ?>
                            <tr class="">
                                <td><a href="khaata-add?id=<?php echo $khaata['id']; ?>">
                                        <?php echo $khaata['khaata_no']; ?></a></td>
                                <td><?php echo getTableDataByIdAndColName('cats', $khaata['cat_id'], 'c_name'); ?></td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('branches', $khaata['branch_id'], 'b_name'); ?></td>
                                <td class="small-2"><?php echo $khaata['khaata_name']; ?></td>
                                <td class="small-2"><?php echo $khaata['comp_name']; ?></td>
                                <td class="small-2"><?php echo $khaata['business_name']; ?></td>
                                <td class="small-2"><?php echo $khaata['city']; ?></td>
                                <td class="small-2"><?php echo $khaata['address']; ?></td>
                                <td class="ltr small-2 text-nowrap"><?php echo $khaata['mobile']; ?></td>
                                <td class="ltr small-2 text-nowrap"><?php echo $khaata['whatsapp']; ?></td>
                                <td class="ltr small-2 text-nowrap"><?php echo $khaata['phone']; ?></td>
                                <td class="ltr small-2"><?php echo $khaata['email']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <!--<table class="table table-bordered overflow-hidden" id="table-header-fixed"></table>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript">
    $(function () {
        $('#branch_id, #cat_ids').change(function () {
            document.userNameSubmit.submit();
        });
    });
</script>
<script>
    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            var username = $("#khaata_no_s").val();
            if (username == '' || username.length < 3) {
                evt.preventDefault();
                return false;
            }
            document.userNameSubmit.submit();
        }
    }
</script>


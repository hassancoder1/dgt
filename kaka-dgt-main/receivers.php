<?php include("header.php"); ?>
    <div
        class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">مال وصول کرنے والا تفصیل</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap d-print-none">
            <div class="input-group wd-400 me-2 mb-2 mb-md-0">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle="">
                    <i class="btn-icon-prepend text-primary" data-feather="search"></i></span>
                <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                       placeholder="ٹیبل میں تلاش کریں (F2)">
            </div>
            <?php echo addNew('receiver-add'); ?>
        </div>
    </div>
    <div class="row mt-4 pt-2">
        <div class="col-md-12">
            <div class="card">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered table-sm" id="fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>کمپنی مالک کا نام</th>
                            <th>موبائل نمبر</th>
                            <th>کانسانے نام</th>
                            <th>شہر نام</th>
                            <th>کمپنی نام</th>
                            <th>لائسینس نمبر</th>
                            <th>پتہ</th>
                            <th>مزید رپورٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $records = fetch('receivers');
                        while ($record = mysqli_fetch_assoc($records)) { ?>
                            <tr>
                                <td class="small urdu-td">
                                    <a href="receiver-add?id=<?php echo $record['id']; ?>">
                                        <?php echo $record['comp_owner_name']; ?></a>
                                </td>
                                <td class="small ltr"><?php echo $record['mobile']; ?></td>
                                <td class="small urdu-td"><?php echo $record['kansani_name']; ?></td>
                                <td class="small urdu-td"><?php echo $record['city']; ?></td>
                                <td class="small urdu-td"><?php echo $record['comp_name']; ?></td>
                                <td class="ltr"><?php echo $record['license_no']; ?></td>
                                <td class="small urdu-td"><?php echo $record['address']; ?></td>
                                <td class="small urdu-td"><?php echo $record['details']; ?></td>
                                <!--<td>
                                        <a onclick="deleteRecord(this)" data-url="dt-receivers"
                                           data-tbl="dt_receivers" id="<?php /*echo $record['id']; */ ?>">
                                            <i class="btn p-0 text-danger" data-feather="trash"></i>
                                        </a>
                                    </td>-->
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>
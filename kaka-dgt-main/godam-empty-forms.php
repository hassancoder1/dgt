<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">گودام خالی کرنے کے فارم</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap d-print-none">
            <div class="input-group wd-400 me-2 mb-2 mb-md-0">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle="">
                    <i class="btn-icon-prepend text-primary" data-feather="search"></i></span>
                <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                       placeholder="ٹیبل میں تلاش کریں (F2)">
            </div>
            <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                    onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
            </button>
            <a href="godam-empty-form-add"
               class="btn btn-outline-primary btn-icon-text py-1">
                <i class="btn-icon-prepend" data-feather="file-plus"></i>اندراج</a>
        </div>
    </div>
    <div class="row mt-4 pt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="table-responsive scroll screen-ht">
                        <table class="table table-bordered " id="fix-head-table">
                            <thead>
                            <tr class="text-nowrap">
                                <th>گودام کا نام</th>
                                <th>پتہ</th>
                                <th>شہر نام</th>
                                <th>گودام کا منشی کا نام</th>
                                <th>موبائل نمبر 1</th>
                                <th>موبائل نمبر 2</th>
                                <th>فون نمبر</th>
                                <th>مزید رپورٹ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $records = fetch('godam_empty_forms');
                            while ($record = mysqli_fetch_assoc($records)) { ?>
                                <tr class="text-nowrap">
                                    <td class="small urdu-td">
                                        <a href="godam-empty-form-add?id=<?php echo $record['id']; ?>">
                                            <?php echo $record['name']; ?></a>
                                    </td>
                                    <td class="small urdu-td"><?php echo $record['address']; ?></td>
                                    <td class="small urdu-td"><?php echo $record['city']; ?></td>
                                    <td class="small urdu-td"><?php echo $record['munshi']; ?></td>
                                    <td class="ltr"><?php echo $record['mobile1']; ?></td>
                                    <td class="ltr"><?php echo $record['mobile2']; ?></td>
                                    <td class="ltr"><?php echo $record['phone']; ?></td>
                                    <td class="small urdu-td"><?php echo $record['details']; ?></td>
                                    <!--<td>
                                        <a onclick="deleteRecord(this)" data-url="godam-empty-forms"
                                           data-tbl="godam_empty_forms" id="<?php /*echo $record['id']; */?>">
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
    </div>
<?php include("footer.php"); ?>
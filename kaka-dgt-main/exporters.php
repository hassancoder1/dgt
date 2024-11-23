<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">آل ایکسپورٹر تفصیل</h4>
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
            <a href="exporter-add"
               class="btn btn-outline-primary btn-icon-text py-1">
                <i class="btn-icon-prepend" data-feather="file-plus"></i>اندراج</a>
        </div>
    </div>
    <div class="row mt-4 pt-2">
        <div class="col-md-12">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card">
                <div class="">
                    <div class="table-responsive scroll screen-ht">
                        <table class="table table-bordered " id="fix-head-table">
                            <thead>
                            <tr class="text-nowrap">
                                <th>نام</th>
                                <th>موبائل نمبر</th>
                                <th>ای میل</th>
                                <th>شہر نام</th>
                                <th>کمپنی نام</th>
                                <th>کمپنی موبائل</th>
                                <th>کمپنی ای میل</th>
                                <th>کمپنی شہر کا نام</th>
                                <th>کمپنی پتہ</th>
                                <th>کمپنی این ٹی این</th>
                                <th>کمپنی سیل ٹیکسٹ نمبر</th>
                                <th>کانسانے نام</th>
                                <th>ریبوک آئی ڈی</th>
                                <th>پاسپورٹ</th>
                                <th>تاریخ</th>
                                <th>مزید رپورٹ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $records = fetch('exporters');
                            while ($record = mysqli_fetch_assoc($records)) { ?>
                                <tr class="text-nowrap">
                                    <td class="small urdu-td">
                                        <a href="exporter-add?id=<?php echo $record['id']; ?>">
                                            <?php echo $record['name']; ?></a>
                                    </td>
                                    <td class="ltr"><?php echo $record['mobile']; ?></td>
                                    <td><?php echo $record['email']; ?></td>
                                    <td class="small "><?php echo $record['city']; ?></td>
                                    <td class="small "><?php echo $record['comp_name']; ?></td>
                                    <td class="ltr"><?php echo $record['comp_mobile']; ?></td>
                                    <td><?php echo $record['comp_email']; ?></td>
                                    <td class="small "><?php echo $record['comp_city']; ?></td>
                                    <td class="small"><?php echo $record['comp_address']; ?></td>
                                    <td><?php echo $record['comp_ntn']; ?></td>
                                    <td><?php echo $record['comp_tax_no']; ?></td>
                                    <td class="small "><?php echo $record['kansani_name']; ?></td>
                                    <td class="small"><?php echo $record['rebock_id']; ?></td>
                                    <td class=""><?php echo $record['passport']; ?></td>
                                    <td class=""><?php echo $record['rec_date']; ?></td>
                                    <td class="small "><?php echo $record['details']; ?></td>
                                    <!--<td>
                                        <a onclick="deleteRecord(this)" data-url="importers"
                                           data-tbl="importers" id="<?php /*echo $record['id']; */?>">
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
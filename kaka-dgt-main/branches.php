<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">آل برانچ تفصیل</h4>
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
            <a href="branch-add"
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
                            <tr>
                                <th>برانچ</th>
                                <th>پتہ</th>
                                <th>انچارج</th>
                                <th>موبائل</th>
                                <th>فون نمبر</th>
                                <th>ای میل</th>
                                <th>شہر</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $branches = fetch('branches');
                            while ($branch = mysqli_fetch_assoc($branches)) { ?>
                                <tr>
                                    <td class="small urdu-td">
                                        <a href="branch-add?id=<?php echo $branch['id']; ?>">
                                            <?php echo $branch['b_name']; ?></a>
                                    </td>
                                    <td class="small urdu-td"><?php echo $branch['b_address']; ?></td>
                                    <td class="small urdu-td"><?php echo $branch['b_incharge']; ?></td>
                                    <td class="ltr"><?php echo $branch['b_mobile']; ?></td>
                                    <td class="ltr"><?php echo $branch['b_phone']; ?></td>
                                    <td><?php echo $branch['b_email']; ?></td>
                                    <td class="small urdu-td"><?php echo $branch['b_city']; ?></td>
                                    <!--<td>
                                        <a class="" onclick="deleteRecord(this)" data-url="branch"
                                           data-tbl="branch" id="<?php /*echo $branch['id']; */?>">
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
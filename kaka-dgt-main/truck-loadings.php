<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">ٹرک لوڈنگز</h4>
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
            <a href="truck-loading-add"
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
                                <th>ٹرک نمبر</th>
                                <th>چیسز نمبر</th>
                                <th>ٹرک نام</th>
                                <th>ماڈل</th>
                                <th>ڈرائیور نام</th>
                                <th>والد کا نام</th>
                                <th>شناختی کارڈ نمبر</th>
                                <th>قوم</th>
                                <th>موبائل نمبر 1</th>
                                <th>موبائل نمبر 2</th>
                                <th>گھر کا پتہ</th>
                                <th>شہر کا نام</th>
                                <th>مزید رپورٹ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $loadings = fetch('truck_loadings');
                            while ($loading = mysqli_fetch_assoc($loadings)) { ?>
                                <tr class="text-nowrap">
                                    <td class="small urdu-td">
                                        <a href="truck-loading-add?id=<?php echo $loading['id']; ?>">
                                            <?php echo $loading['truck_no']; ?></a>
                                    </td>
                                    <td><?php echo $loading['chassis_no']; ?></td>
                                    <td><?php echo $loading['truck_name']; ?></td>
                                    <td><?php echo $loading['truck_modal']; ?></td>
                                    <td class="small urdu-td"><?php echo $loading['driver_name']; ?></td>
                                    <td class="small urdu-td"><?php echo $loading['d_father']; ?></td>
                                    <td><?php echo $loading['d_cnic']; ?></td>
                                    <td class="small urdu-td"><?php echo $loading['d_caste']; ?></td>
                                    <td><?php echo $loading['d_mobile1']; ?></td>
                                    <td><?php echo $loading['d_mobile2']; ?></td>
                                    <td class="small urdu-td"><?php echo $loading['d_address']; ?></td>
                                    <td class="small urdu-td"><?php echo $loading['d_city']; ?></td>
                                    <td class="small urdu-td"><?php echo $loading['d_details']; ?></td>
                                    <!--<td>
                                        <a class="" onclick="deleteRecord(this)" data-url="truck-loadings"
                                           data-tbl="truck_loadings" id="<?php /*echo $loading['id']; */?>">
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
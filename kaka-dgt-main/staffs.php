<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">ملازم انٹری فارم</h4>
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
            <a href="staff-add"
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
                            <tr>
                                <th>تاریخ</th>
                                <th>برانچ</th>
                                <th>پوسٹ</th>
                                <th>شہرکانام</th>
                                <th>نام</th>
                                <th>لائسینس نام</th>
                                <th>والد نام</th>
                                <th>شناختی کارڈ نمبر</th>
                                <th>قوم</th>
                                <th>گھر کا پتہ</th>
                                <th>موبائل نمبر</th>
                                <th>ای میل</th>
                                <th>رقم</th>
                                <th>مزید رپورٹ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $staffs = fetch('staffs');
                            while ($staff = mysqli_fetch_assoc($staffs)) { ?>
                                <tr class="text-nowrap">
                                    <td class="">
                                        <a href="staff-add?id=<?php echo $staff['id']; ?>">
                                            <?php echo $staff['staff_date']; ?></a>
                                    </td>
                                    <td>
                                        <?php echo getTableDataByIdAndColName('branches', $staff['branch_id'], 'b_name'); ?>
                                    </td>
                                    <td class="urdu-td small"><?php echo userRole($staff['role']); ?></td>
                                    <td class="urdu-td small"><?php echo $staff['city']; ?></td>
                                    <td class="urdu-td small"><?php echo $staff['staff_name']; ?></td>
                                    <td><?php echo $staff['license_name']; ?></td>
                                    <td class="urdu-td small"><?php echo $staff['father_name']; ?></td>
                                    <td><?php echo $staff['cnic']; ?></td>
                                    <td class="small urdu-td"><?php echo $staff['caste']; ?></td>
                                    <td class="small urdu-td"><?php echo $staff['address']; ?></td>
                                    <td class="ltr"><?php echo $staff['mobile']; ?></td>
                                    <td class="ltr"><?php echo $staff['email']; ?></td>
                                    <td class="ltr"><?php echo $staff['salary']; ?></td>
                                    <td class="small urdu-td"><?php echo $staff['details']; ?></td>
                                    <!--<td>
                                        <a class="" onclick="deleteRecord(this)" data-url="staffs"
                                           data-tbl="staffs" id="<?php /*echo $staff['id']; */?>">
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
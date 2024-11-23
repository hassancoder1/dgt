<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mt-n1">بروکرز</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap d-print-none">
            <div class="input-group wd-110 me-lg-2 me-1 wd-lg-200-f">
                <input id="tableFilter" type="text" autofocus class="form-control border-primary"
                       placeholder="تلاش کریں (F2)">
            </div>
            <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                    onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
            </button>
            <a href="broker-add"
               class="btn btn-outline-primary  pt-1 pb-2">اندراج</a>
        </div>
    </div>
    <div class="row mt-4 pt-2">
        <div class="col-md-12">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card">
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th>نام</th>
                            <th>کھاتہ</th>
                            <th>موبائل</th>
                            <th>ای میل</th>
                            <th>شہر</th>
                            <th>پتہ</th>
                            <th>مزید رپورٹ</th>
                            <!--<th>ایکشن</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php $cas = fetch('brokers');
                        while ($ca = mysqli_fetch_assoc($cas)) { ?>
                            <tr class="text-nowrap">
                                <td class="small">
                                    <a href="broker-add?id=<?php echo $ca['id']; ?>">
                                        <?php echo $ca['name']; ?></a>
                                </td>
                                <td><?php echo $ca['khaata_no']; ?></td>
                                <td class="ltr"><?php echo $ca['mobile']; ?></td>
                                <td><?php echo $ca['email']; ?></td>
                                <td class="small"><?php echo $ca['city']; ?></td>
                                <td class="small"><?php echo $ca['address']; ?></td>
                                <td class="small"><?php echo $ca['more_details']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>
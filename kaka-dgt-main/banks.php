<?php include("header.php"); ?>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div>
            <h4 class="mb-3 mb-md-0">آل بینک تفصیل</h4>
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
            <a href="bank-add"
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
                                <th>بینک نام</th>
                                <th>برانچ</th>
                                <th>اکاونٹ نام</th>
                                <th>اکاونٹ نمبر</th>
                                <th>برانچ کوڈ</th>
                                <th>پتہ</th>
                                <th>موبائل نمبر</th>
                                <th>فون نمبر</th>
                                <th>ای میل</th>
                                <th>مزید رپورٹ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $banks = fetch('banks');
                            while ($bank = mysqli_fetch_assoc($banks)) { ?>
                                <tr class="text-nowrap">
                                    <td class="small">
                                        <a href="bank-add?id=<?php echo $bank['id']; ?>">
                                            <?php echo $bank['bank_name']; ?></a>
                                    </td>
                                    <td class="small-2"><?php echo $bank['bank_address']; ?></td>
                                    <td class="small"><?php echo $bank['acc_name']; ?></td>
                                    <td class="small-2"><?php echo $bank['acc_no']; ?></td>
                                    <td class="small"><?php echo $bank['branch_code']; ?></td>
                                    <td class="small-2"><?php echo $bank['bank_address']; ?></td>
                                    <td class="small"><?php echo $bank['bank_mobile']; ?></td>
                                    <td class="small"><?php echo $bank['bank_phone']; ?></td>
                                    <td class="small"><?php echo $bank['bank_email']; ?></td>
                                    <td class="small-2"><?php echo $bank['bank_details']; ?></td>
                                    <!--<td>
                                        <a class="" onclick="deleteRecord(this)" data-url="banks"
                                           data-tbl="banks" id="<?php /*echo $bank['id']; */ ?>">
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
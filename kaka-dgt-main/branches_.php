<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">برانچ تفصیل</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group wd-400 me-2 mb-2 mb-md-0">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle="">
                    <i class="btn-icon-prepend text-primary" data-feather="search"></i></span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="ٹیبل میں تلاش کریں (F2)">
        </div>
        <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0"
                onclick="window.print();">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-printer btn-icon-prepend">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            پرنٹ لیں
        </button>
        <button id="addRecord" data-bs-target="#branchModal" type="button" data-bs-toggle="modal"
                class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>
            برانچ اندراج
        </button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="records_table">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>برانچ</th>
                            <th>پتہ</th>
                            <th>انچارج</th>
                            <th>موبائل</th>
                            <th>فون نمبر</th>
                            <th>ای میل</th>
                            <th>شہر</th>
                            <th>ایکشن</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) { ?>
                            <tr>
                                <td class="small urdu-td">
                                    <a id="<?php echo $branch['id']; ?>" data-bs-target="#branchModal" type="button"
                                       data-bs-toggle="modal"
                                       class="btn-link pointer edit_data"> <?php echo $branch['b_name']; ?></a>
                                </td>
                                <td class="small urdu-td"><?php echo $branch['b_address']; ?></td>
                                <td class="small urdu-td"><?php echo $branch['b_incharge']; ?></td>
                                <td class="ltr text-start"><?php echo $branch['b_mobile']; ?></td>
                                <td class="ltr text-start"><?php echo $branch['b_phone']; ?></td>
                                <td><?php echo $branch['b_email']; ?></td>
                                <td class="small urdu-td"><?php echo $branch['b_city']; ?></td>
                                <td class="">
                                    <a class="" onclick="deleteRecord(this)" data-url="branches" data-tbl="branches"
                                       id="<?php echo $branch['id']; ?>"><i class="text-danger btn-icon-prepend"
                                                                            data-feather="trash"></i></span></a>
                                </td>
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
<div class="modal fade" id="branchModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">برانچ اندراج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form method="post" id="insert_form">
                <div class="modal-body">
                    <div class="row gx-0 gy-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">برانچ نام</span>
                                    <input type="text" id="b_name" name="b_name" class="form-control input-urdu"
                                           required
                                           autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">برانچ پتہ</span>
                                    <input type="text" id="b_address" name="b_address" class="form-control input-urdu"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">برانچ انچارج نام</span>
                                    <input type="text" id="b_incharge" name="b_incharge" class="form-control input-urdu"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">موبائل نمبر</span>
                                    <input type="text" id="b_mobile" name="b_mobile" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">فون نمبر</span>
                                    <input id="b_phone" name="b_phone" required class="form-control"
                                           data-inputmask-alias="(+99) 9999-9999" inputmode="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">ای میل</span>
                                    <input type="email" id="b_email" name="b_email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">شہر کا نام</span>
                                    <input type="text" id="b_city" name="b_city" class="form-control input-urdu"
                                           required
                                           autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="branch_id" id="branch_id">
                <div class="modal-footer d-block">
                    <div class="row">
                        <div class="col-lg-3">
                            <button name="insert" id="insert" type="submit" class="btn btn-success w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="check-square"></i>
                                محفوظ کریں
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-primary w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="printer"></i>
                                پرنٹ کریں
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-dark w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                درستگی
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-danger w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="trash-2"></i>
                                ختم کریں
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<!--b_name	b_address	b_incharge	b_mobile	b_phone	b_email	b_city-->
<script>
    $(document).ready(function () {
        $('#addRecord').click(function () {
            //$('#insert').html("محفوظ کریں");
            //$('#insert').val("Insert");
            $('#insert_form')[0].reset();
        });
        $(document).on('click', '.edit_data', function () {
            var id = $(this).attr("id");
            $.ajax({
                url: "ajax/branch/fetch.php",
                method: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (data) {
                    $('#b_name').val(data.b_name);
                    $('#b_address').val(data.b_address);
                    $('#b_incharge').val(data.b_incharge);
                    $('#b_mobile').val(data.b_mobile);
                    $('#b_phone').val(data.b_phone);
                    $('#b_email').val(data.b_email);
                    $('#b_city').val(data.b_city);
                    $('#branch_id').val(data.id);
                    $('#insert').val("Update");
                    $('#add_data_Modal').modal('show');
                }
            });
        });
        $('#insert_form').on("submit", function (event) {
            event.preventDefault();
            if ($('#b_name').val() == "") {
                alert("Name is required");
            } else if ($('#b_address').val() == '') {
                alert("Address is required");
            } else {
                $.ajax({
                    url: "ajax/branch/insert.php",
                    method: "POST",
                    data: $('#insert_form').serialize(),
                    beforeSend: function () {
                        $('#insert').val("ڈیٹ محفوظ ہورہاہے");
                    },
                    success: function (data) {
                        $('#insert_form')[0].reset();
                        $('#branchModal').modal('hide');
                        $('#records_table').html(data);
                    }
                });
            }
        });
        $(document).on('click', '.view_data', function () {
            var employee_id = $(this).attr("id");
            if (employee_id != '') {
                $.ajax({
                    url: "select.php",
                    method: "POST",
                    data: {
                        employee_id: employee_id
                    },
                    success: function (data) {
                        $('#employee_detail').html(data);
                        $('#dataModal').modal('show');
                    }
                });
            }
        });
    });

</script>
<?php include("header.php"); ?>
<div
        class="heading-div d-flex justify-content-between align-items-center gap-2 px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3- text-nowrap mb-md-0">یوزرز تفصیل</h4>
    </div>
    <div>
        <input id="tableFilter" type="text" autofocus class="form-control "
               placeholder="ٹیبل میں تلاش کریں (F2)">
    </div>
    <div>
        <a href="user-add" class="btn btn-outline-primary btn-icon-text pt-0 pb-2">
            اندراج
        </a>
    </div>
</div>
<div class="row mt-3 pt-3">
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
                            <th>والدکانام</th>
                            <th>تاریخ</th>
                            <th>برانچ</th>
                            <th>پوسٹ</th>
                            <th>ای میل</th>
                            <th>موبائل نمبر</th>
                            <th>فون نمبر</th>
                            <th>آئ ڈی</th>
                            <th>پاسورڈ</th>
                            <th>تفصیل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $users = fetch('users');
                        while ($user = mysqli_fetch_assoc($users)) { ?>
                            <tr class="text-nowrap">
                                <td>
                                    <?php $u_perms_q = fetch('user_permissions', array('user_id' => $user['id']));
                                    $u_perms = mysqli_fetch_assoc($u_perms_q);
                                    $noOfPerms = "";
                                    $permSpanClass = 'float-end badge bg-primary p-1 ';
                                    $permTooltipText = $user['full_name'] . ' ';
                                    if (!empty($u_perms['permission'])) {
                                        $perms = json_decode($u_perms['permission']);
                                        if (getNumRows('forms') == count($perms)) {
                                            $noOfPerms = "سب فارم";
                                            $permTooltipText .= ' سارے فارم دیکھ سکتا ہے۔';
                                        } else {
                                            $noOfPerms = count($perms);
                                            $permTooltipText .= ' ' . $noOfPerms . ' ';
                                            $permTooltipText .= ' فارم دیکھ سکتا ہے۔ ';
                                        }
                                    }
                                    if (Administrator()) {
                                        echo '<span class="' . $permSpanClass . '" data-tooltip="' . $permTooltipText . '" data-tooltip-position="bottom right">' . $noOfPerms . '</span>';
                                    } ?>
                                    <a class="small"
                                       href="user-add?id=<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?></a>
                                </td>
                                <td class="small-2"><?php echo $user['father_name']; ?></td>
                                <td class="text-nowrap"><?php echo $user['user_date']; ?></td>
                                <td class="small-2">
                                    <?php echo branchName($user['branch_id']);
                                    //echo $user['father_name']; ?>
                                </td>
                                <td class="small-2"><?php echo userRole($user['role']); ?></td>
                                <td class="small"><?php echo $user['email']; ?></td>
                                <td class="text-nowrap ltr small-2"><?php echo $user['mobile']; ?></td>
                                <td class="ltr small-2 text-nowrap"><?php echo $user['phone']; ?></td>
                                <td class=""><?php echo $user['username']; ?></td>
                                <td class=""><?php echo $user['pass']; ?></td>
                                <td class="small-2"><?php echo $user['user_details']; ?></td>
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
<script>
    function deleteUser(e) {
        var id = $(e).attr('id');
        var url = $(e).attr('data-url');
        var tbl = $(e).attr('data-tbl');
        if (id) {
            if (confirm('Are you sure to delete?')) {
                window.location.href = 'ajax/deleteUser.php?id=' + id + '&tbl=' + tbl + '&url=' + url;
            } else {
                //alert('Action aborted.\nPicture not deleted.');
            }
        }
    }
</script>
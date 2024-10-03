<?php $page_title = 'Users List';
$pageURL = 'users';
include("header.php");
if (!SuperAdmin()) {
    messageNew('danger', './', 'ACCESS DENIED.');
}
$sql = "SELECT * FROM `users` WHERE id>0 ";
if (isset($_GET['role'])) {
    $type = mysqli_real_escape_string($connect, $_GET['role']);
    $sql .= " AND role = " . "'$type'" . " ";
} ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex align-items-center gap-3 d-none">
            <a href="<?php echo $pageURL; ?>" class="text-dark"><b>ALL USERS</b>
                (<?php echo getNumRows('users'); ?>
                )</a>
            <?php $roles = fetch('roles');
            while ($role = mysqli_fetch_assoc($roles)) {
                echo '<a href="' . $pageURL . '?role=' . $role['role_name'] . '" class="text-dark"><b>' . strtoupper($role['role_name']) . '</b>';
                echo ' (' . getNumRows('users', 'role', $role['role_name']) . ')';
                echo '</a>';
            } ?>
        </div>
        <form method="get">
            <select class="form-select form-select-sm" name="role" id="role" required>
                <option value="0">All Posts</option>
                <?php $roles = fetch('roles');
                while ($role = mysqli_fetch_assoc($roles)) {
                    $b_sel = $type == $role['role_name'] ? 'selected' : '';
                    echo '<option ' . $b_sel . ' value="' . $role['role_name'] . '">' . $role['role_name'] . '</option>';
                } ?>
            </select>
        </form>
        <div class="d-flex gap-2">
            <?php echo searchInput('a', 'form-control-sm '); ?>
            <?php echo addNew('user-add', '', 'btn-sm'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>Date</th>
                        <th>ID TYPE</th>
                        <th>NAME</th>
                        <th>POST</th>
                        <th>BRANCH</th>
                        <th>ID</th>
                        <th>PASSWORD</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $users = mysqli_query($connect, $sql);
                    $no = 0;
                    while ($user = mysqli_fetch_assoc($users)) {
                        $is_active = $user['is_active'];
                        $active_msg = $is_active == 1 ? '<i class="fa fa-check-double"></i>' : '<i class="fa fa-warning text-danger" data-bs-toggle="tooltip" data-bs-title="This user is blocked"></i>';
                        if (!empty($user['image']) && file_exists($user['image'])) {
                            $img_path = $user['image'];
                        }else{
                            $img_path = 'assets/images/avatar.jpg';
                        }
                        ++$no; ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><img src="<?php echo $img_path; ?>" class="img-fluid" width="20" height="20" alt="profile"></td>
                            <td><?php echo my_date($user['created_at']); ?></td>
                            <td><?php echo $user['type']; ?></td>
                            <td>
                                <a class="text-dark" href="user-add?id=<?php echo $user['id']; ?>">
                                    <?php echo $user['full_name']; ?>
                                </a>
                            </td>
                            <td><?php echo badge(strtoupper($user['role']), 'dark'); ?></td>
                            <td><?php echo branchName($user['branch_id']); ?></td>
                            <td><?php echo $active_msg . $user['username']; ?></td>
                            <td><?php echo $user['pass']; ?></td>
                            <td class="d-flex gap-2">
                                <?php if (SuperAdmin()) { ?>
                                    <form method="post" onsubmit="return confirm('Are you sure to delete?')">
                                        <input type="hidden" name="hidden_id" value="<?php echo $user['id']; ?>">
                                        <button name="deleteSubmit" type="submit" data-bs-toggle="tooltip"
                                                data-bs-title="Delete User <?php echo $user['full_name']; ?>"
                                                class="btn btn-sm btn-outline-danger py-0 px-1">
                                            <i class="fa fa-trash-alt"></i></button>
                                    </form>
                                    <form method="post" onsubmit="return confirm('Are you sure?')">
                                        <input type="hidden" name="hidden_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="hidden_status" value="<?php echo $is_active; ?>">
                                        <button name="blockSubmit" type="submit" data-bs-toggle="tooltip"
                                                data-bs-title="Block / Unblock User <?php echo $user['full_name']; ?>"
                                                class="btn btn-sm btn-outline-dark py-0 px-1">
                                            <i class="fa fa-lock"></i></button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>$("#entries").addClass('active');</script>
<script>$("#users").addClass('active');</script>
<script type="text/javascript">
    $(function () {
        $('#role').on('change', function () {
            var role = $(this).val();
            var url = 'users';
            if (role != '') {
                url += '?role=' + role;
            }
            window.location = url;
            return false;
        });
    });
</script>
<?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['deleteSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    mysqli_begin_transaction($connect);
    try {
        $dependentTables = [
            'khaata' => 'created_by',
            'branches' => 'created_by',
        ];
        foreach ($dependentTables as $table => $foreignKeyColumn) {
            $checkDependents = mysqli_query($connect, "SELECT COUNT(*) AS count FROM `$table` WHERE `$foreignKeyColumn` = '$hidden_id'");
            $dependentCount = mysqli_fetch_assoc($checkDependents)['count'];
            if ($dependentCount > 0) {
                throw new Exception("Unable to delete the record due to dependencies.");
            }
        }
        $del = mysqli_query($connect, "DELETE FROM `users` WHERE id = '$hidden_id'");
        if (!$del) {
            throw new Exception("Failed to delete user.");
        }
        mysqli_commit($connect);
        $msg_array['msg'] = 'Record Successfully Deleted.';
        $msg_array['type'] = 'success';
    } catch (Exception $e) {
        mysqli_rollback($connect);
        $msg_array['msg'] = $e->getMessage();
        $msg_array['type'] = 'danger';
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
}
if (isset($_POST['blockSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $hidden_status = mysqli_real_escape_string($connect, $_POST['hidden_status']);
    $x = $hidden_status == 1 ? 0 : 1;
    $done = update('users', ['is_active' => $x], ['id' => $hidden_id]);
    if ($done) {
        $msg_array['msg'] = 'User Status Successfully Changed.';
        $msg_array['type'] = 'success';
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
}
?>

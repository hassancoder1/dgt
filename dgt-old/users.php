<?php $page_title = 'Users List';
$pageURL = 'users';
include("header.php");
if (!SuperAdmin()) {
    message('danger', './', 'ACCESS DENIED.');
}
$sql = "SELECT * FROM `users` WHERE id > 0 ";
if (isset($_GET['role'])) {
    $type = mysqli_real_escape_string($connect, $_GET['role']);
    $sql .= " AND role = " . "'$type'" . " ";
} ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?php echo $pageURL; ?>" class="text-dark"><b>ALL USERS</b>
                        (<?php echo getNumRows('users'); ?>)</a>
                    <?php $roles = fetch('roles');
                    while ($role = mysqli_fetch_assoc($roles)) {
                        echo '<a href="' . $pageURL . '?role=' . $role['role_name'] . '" class="text-dark"><b>' . strtoupper($role['role_name']) . '</b>';
                        echo ' (' . getNumRows('users', 'role', $role['role_name']) . ')';
                        echo '</a>';
                    } ?>
                </div>
                <?php if (isset($_SESSION['response'])) {
                    echo '<div class="flex-fill">' . $_SESSION['response'] . '</div>';
                    unset($_SESSION['response']);
                } ?>
                <div class="d-flex">
                    <?php echo addNew('user-add', '', 'btn-sm'); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive" style="height: 73dvh;">
                        <table class="table mb-0 table-bordered fix-head-table table-sm">
                            <thead>
                            <tr class="text-nowrap">
                                <th></th>
                                <th>TYPE</th>
                                <th>NAME</th>
                                <th>ACCOUNT</th>
                                <th>BRANCH</th>
                                <th>CONTACTS</th>
                                <th>ID-PASSWORD</th>
                                <th>DETAILS</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $users = mysqli_query($connect, $sql);
                            while ($user = mysqli_fetch_assoc($users)) {
                                $khaata_id = $user['khaata_id'];
                                $userKhaata = khaataSingle($khaata_id); ?>
                                <tr class="font-size-12">
                                    <td>
                                        <img src="<?php echo $user['image']; ?>" class="avatar-md rounded" alt="img">
                                    </td>
                                    <td>
                                        <?php echo '<span class="badge badge-soft-success">' . $user['role'] . '</span>';
                                        echo '<br><span class="font-size-11"><b>D.</b>' . date('y-m-d', strtotime($user['user_date'])) . '</span>'; ?>
                                    </td>
                                    <td class="">
                                        <a class="text-dark" href="user-add?id=<?php echo $user['id']; ?>">
                                            <?php echo '<b>A/c#</b>' . $user['full_name'] . '<br>';
                                            echo $user['father_name'] != "" ? '<b>Father</b>' . $user['father_name'] : '';
                                            //echo '<br><b>Company Name</b>' . $khaata['comp_name'];
                                            ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($khaata_id > 0) {
                                            echo '<b>A/c#</b>' . $userKhaata['khaata_no'] . '<br>';
                                            echo '<b>A/c Name</b>' . $userKhaata['khaata_name'];
                                            //echo '<br><b>Company Name</b>' . $userKhaata['comp_name'];
                                        } ?>
                                    </td>
                                    <td class="small text-nowrap">
                                        <?php echo branchName($user['branch_id']) . '<br>';
                                        echo catName($user['cat_id'], 'C.'); ?>
                                    </td>

                                    <td class="text-nowrap text-dark">
                                        <?php echo href_link('tel://' . $user['mobile'], $user['mobile'], '', 'text-dark', '', 'M.', 'Call on Mobile Number');
                                        echo '<br>' . href_link('tel://' . $user['phone'], $user['phone'], '', 'text-dark', '', 'P.', 'Call on Phone Number');
                                        echo '<br>' . href_link('mailto://' . $user['email'], $user['email'], '', 'text-dark', '', 'E.', 'Send Email'); ?>
                                    </td>
                                    <td class="small">
                                        <?php echo $user['username'] . '<br>' . $user['pass']; ?>
                                    </td>
                                    <td class="small"><?php echo $user['user_details']; ?></td>
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
<?php $page_title = 'Branches';
$pageURL = 'branches';
include("header.php"); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-md-2">
                <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
                <div class="d-flex gap-2">
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                    <?php echo addNew('branch-add', '', 'btn-sm'); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>BRANCH NAME</th>
                            <th>BRANCH CODE</th>
                            <th>OWNER</th>
                            <th>COUNTRY</th>
                            <th>CITY</th>
                            <th>CONTACT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $branches = fetch('branches');
                        $x = 1;
                        while ($branch = mysqli_fetch_assoc($branches)) { ?>
                            <tr class="font-size-12">
                                <td><?php echo $x; ?></td>
                                <td><?php echo '<a href="branch-add?id=' . $branch['id'] . '">' . $branch['b_name'] . '</a>'; ?></td>
                                <td><?php echo $branch['b_code']; ?></td>
                                <td><?php echo $branch['name']; ?></td>
                                <td><?php echo countryName($branch['country_id']); ?></td>
                                <td><?php echo $branch['city']; ?></td>
                                <td>
                                    <ul class="list-unstyled- socials">
                                        <li><a href="tel://<?php echo $branch['mobile']; ?>"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="Phone <?php echo $branch['mobile']; ?>"><i
                                                        class="fa fa-phone"></i></a></li>
                                        <li><a href="tel://<?php echo $branch['phone']; ?>"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="Mobile <?php echo $branch['phone']; ?>"><i
                                                        class="fa fa-mobile"></i></a></li>
                                        <li><a href="mailto://<?php echo $branch['email']; ?>"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="Email <?php echo $branch['email']; ?>"><i
                                                        class="fa fa-envelope"></i></a></li>
                                        <li><a href="https://wa.me/<?php echo $branch['whatsapp']; ?>"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="Whatsapp <?php echo $branch['whatsapp']; ?>"><i
                                                        class="fab fa-whatsapp"></i></a></li>
                                    </ul>
                                </td>
                                <td>
                                    <?php if (SuperAdmin()) { ?>
                                        <form method="post" onsubmit="return confirm('Are you sure to delete?')">
                                            <input type="hidden" name="hidden_id" value="<?php echo $branch['id']; ?>">
                                            <button name="deleteSubmit" type="submit" data-bs-toggle="tooltip"
                                                    data-bs-title="Delete Branch <?php echo $branch['b_code']; ?>"
                                                    class="btn btn-sm btn-outline-danger py-0 px-1">
                                                <i class="fa fa-trash-alt"></i></button>
                                        </form>
                                    <?php } ?>
                                </td>
                                <?php $branch_user_id = $branch['user_id'];
                                $users = fetch('users', array('id' => $branch_user_id));
                                $record_user = mysqli_fetch_assoc($users); ?>
                                <!--<td><?php /*echo $record_user['username'].'<br>' . $record_user['pass']; */ ?></td>-->
                            </tr>
                            <?php $x++;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>
    <script>$("#entries").addClass('active');</script>
    <script>$("#branches").addClass('active');</script><?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['deleteSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    mysqli_begin_transaction($connect);
    try {
        $dependentTables = [
            'cats' => 'branch_id',
            'khaata' => 'branch_id',
            'users' => 'branch_id'
        ];
        foreach ($dependentTables as $table => $foreignKeyColumn) {
            $checkDependents = mysqli_query($connect, "SELECT COUNT(*) AS count FROM `$table` WHERE `$foreignKeyColumn` = '$hidden_id'");
            $dependentCount = mysqli_fetch_assoc($checkDependents)['count'];
            if ($dependentCount > 0) {
                throw new Exception("Unable to delete the record due to dependencies.");
            }
        }
        $del = mysqli_query($connect, "DELETE FROM `branches` WHERE id = '$hidden_id'");
        if (!$del) {
            throw new Exception("Failed to delete.");
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
} ?>
<?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['deleteSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    mysqli_begin_transaction($connect);
    try {
        $dependentTables = [
            'khaata' => 'cat_id'
        ];
        foreach ($dependentTables as $table => $foreignKeyColumn) {
            $checkDependents = mysqli_query($connect, "SELECT COUNT(*) AS count FROM `$table` WHERE `$foreignKeyColumn` = '$hidden_id'");
            $dependentCount = mysqli_fetch_assoc($checkDependents)['count'];
            if ($dependentCount > 0) {
                throw new Exception("Unable to delete the record due to dependencies.");
            }
        }
        $del = mysqli_query($connect, "DELETE FROM `cats` WHERE id = '$hidden_id'");
        if (!$del) {
            throw new Exception("Failed to delete.");
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
} ?>
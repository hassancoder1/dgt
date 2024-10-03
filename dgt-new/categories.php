<?php $page_title = 'Categories';
$pageURL = 'categories';
include("header.php");
$sql = "SELECT * FROM `cats` WHERE id>0 ";
$branch_id = 0;
if (isset($_GET['branch_id']) && is_numeric($_GET['branch_id'])) {
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $sql .= " AND branch_id = '$branch_id'";
}
$sql .= " ORDER BY branch_id DESC "; ?>
    <div class="fixed-top">
        <?php require_once('nav-links.php'); ?>
        <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
            <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
            <form method="get">
                <select class="form-select form-select-sm" name="branch_id" id="branch_id" required>
                    <option value="0">All Branch</option>
                    <?php $branches = fetch('branches');
                    while ($b = mysqli_fetch_assoc($branches)) {
                        $b_sel = $branch_id == $b['id'] ? 'selected' : '';
                        echo '<option ' . $b_sel . ' value="' . $b['id'] . '">' . $b['b_name'] . '</option>';
                    } ?>
                </select>
            </form>
            <div class="d-flex gap-2">
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <?php echo addNew('category-add', '', 'btn-sm'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>NAME</th>
                            <th>BRANCH</th>
                            <th>DETAILS</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $cats = mysqli_query($connect, $sql);
                        $x = 1;
                        while ($cat = mysqli_fetch_assoc($cats)) { ?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><a class="text-dark"
                                       href="<?php echo 'category-add?id=' . $cat['id']; ?>"><?php echo $cat['name']; ?></a>
                                </td>
                                <td><?php echo branchName($cat['branch_id']); ?></td>
                                <td><?php echo $cat['details']; ?></td>
                                <td>
                                    <?php if (SuperAdmin()) { ?>
                                        <form method="post" onsubmit="return confirm('Are you sure to delete?')">
                                            <input type="hidden" name="hidden_id" value="<?php echo $cat['id']; ?>">
                                            <button name="deleteSubmit" type="submit" data-bs-toggle="tooltip"
                                                    data-bs-title="Delete Category <?php echo $cat['name']; ?>"
                                                    class="btn btn-sm btn-outline-danger py-0 px-1">
                                                <i class="fa fa-trash-alt"></i></button>
                                        </form>
                                    <?php } ?>
                                </td>
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
    <script>$("#categories").addClass('active');</script>
    <script type="text/javascript">
        $(function () {
            $('#branch_id').on('change', function () {
                var branch_id = $(this).val();
                var url = 'categories';
                if (branch_id > 0) {
                    url += '?branch_id=' + branch_id;
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
<?php
$page_title = 'Categories';
$pageURL = 'categories';
include("header.php");

// Build SQL query for categories
$sql = "SELECT * FROM `cats` WHERE id > 0 ";
$branch_id = 0;
if (isset($_GET['branch_id']) && is_numeric($_GET['branch_id'])) {
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $sql .= " AND branch_id = '$branch_id'";
}
$sql .= " ORDER BY branch_id DESC ";

?>

<div class="container-fluid">
    <div class="fixed-top bg-light shadow-sm border-bottom">
        <?php require_once('nav-links.php'); ?>

        <div class="d-flex align-items-center justify-content-between p-3">
            <h5 class="text-uppercase mb-0">Categories</h5>

            <div class="d-flex align-items-center gap-3">
                <!-- Branch Filter -->
                <form method="get" class="d-flex align-items-center">
                    <select class="form-select form-select-sm" name="branch_id" id="branch_id" required>
                        <option value="0">All Branches</option>
                        <?php $branches = fetch('branches');
                        while ($b = mysqli_fetch_assoc($branches)) {
                            $b_sel = $branch_id == $b['id'] ? 'selected' : '';
                            echo '<option ' . $b_sel . ' value="' . $b['id'] . '">' . $b['b_name'] . '</option>';
                        } ?>
                    </select>
                </form>

                <!-- Search and Add New Buttons -->
                <div class="d-flex gap-2">
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                    <?php echo addNew('category-add', '', 'btn-sm btn-success'); ?>
                </div>

                <!-- Print Options -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-print"></i>
                    </button>
                    <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="<?= $print_url; ?>" target="_blank"><i class="fas text-secondary fa-eye me-2"></i> Print Preview</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')"><i class="fas text-secondary fa-print me-2"></i> Print</a></li>
                        <li><a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')"><i class="fas text-secondary fa-file-pdf me-2"></i> Download PDF</a></li>
                        <li><a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')"><i class="fas text-secondary fa-file-word me-2"></i> Download Word File</a></li>
                        <li><a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')"><i class="fa text-secondary fa-whatsapp me-2"></i> Send on WhatsApp</a></li>
                        <li><a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')"><i class="fas text-secondary fa-envelope me-2"></i> Send via Email</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 pt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>NAME</th>
                                <th>BRANCH</th>
                                <th>DETAILS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cats = mysqli_query($connect, $sql);
                            $x = 1;
                            while ($cat = mysqli_fetch_assoc($cats)) { ?>
                                <tr>
                                    <td><?php echo $x; ?></td>
                                    <td><a class="text-dark" href="<?php echo 'category-add?id=' . $cat['id']; ?>"><?php echo $cat['name']; ?></a></td>
                                    <td><?php echo branchName($cat['branch_id']); ?></td>
                                    <td><?php echo $cat['details']; ?></td>
                                    <td>
                                        <?php if (SuperAdmin()) { ?>
                                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                <input type="hidden" name="hidden_id" value="<?php echo $cat['id']; ?>">
                                                <button type="submit" name="deleteSubmit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php $x++; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
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

<?php
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
        messageNew('success', $pageURL, 'Record Successfully Deleted.');
    } catch (Exception $e) {
        mysqli_rollback($connect);
        messageNew('danger', $pageURL, $e->getMessage());
    }
}
?>

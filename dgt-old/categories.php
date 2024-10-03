<?php $page_title = 'Categories';
include("header.php");
$sql = "SELECT * FROM `cats` WHERE id>0 ";
$branch_id = 0;
if (isset($_GET['branch_id']) && is_numeric($_GET['branch_id'])) {
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $sql .= " AND branch_id = '$branch_id'";
}
$sql .= " ORDER BY branch_id DESC ";
?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <?php echo searchInput('a', 'form-control-sm'); ?>
            </div>
            <form name="filterForm" method="get" style="min-width: 250px;">
                <select class="form-control" data-trigger name="branch_id" id="branch_id" required>
                    <option value="0">All Branch</option>
                    <?php $branches = fetch('branches');
                    while ($b = mysqli_fetch_assoc($branches)) {
                        $b_sel = $branch_id == $b['id'] ? 'selected' : '';
                        echo '<option ' . $b_sel . ' value="' . $b['id'] . '">' . $b['b_name'] . '</option>';
                    } ?>
                </select>
            </form>
            <div class="d-flex">
                <?php echo addNew('category-add', '', 'btn-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>CATEGORY NAME</th>
                            <th>BRANCH</th>
                            <th>DETAILS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $cats = mysqli_query($connect, $sql);
                        $x = 1;
                        while ($cat = mysqli_fetch_assoc($cats)) { ?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><a href="<?php echo 'category-add?id=' . $cat['id']; ?>"><?php echo $cat['name']; ?></a></td>
                                <td><?php echo branchName($cat['branch_id']); ?></td>
                                <td><?php echo $cat['details']; ?></td>
                            </tr>
                            <?php $x++;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
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
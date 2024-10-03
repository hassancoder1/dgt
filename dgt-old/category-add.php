<?php $page_title = 'Category Entry';
$back_page_url = 'categories';
include("header.php");
$newRecord = true;
$updateRecord = false;
$id = 0;
$record = array();
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('cats', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $newRecord = false;
    $updateRecord = true;
} ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card-body">
                <div class="mt-4 mt-xl-0">
                    <?php if ($updateRecord) { ?>
                        <form method="post" onsubmit="return confirm('Are you sure to update data?');">
                            <div class="row gy-4">
                                <div class="col-md-2">
                                    <label for="name" class="form-label">CATEGORY NAME</label>
                                    <input value="<?php echo $record['name']; ?>" id="name" name="name"
                                           class="form-control" required autofocus>
                                </div>
                                <div class="col-md-3">
                                    <label for="choices-single-default" class="form-label">BRANCH</label>
                                    <select class="form-control" data-trigger name="branch_id"
                                            id="choices-single-default" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php $branches = fetch('branches');
                                        while ($b = mysqli_fetch_assoc($branches)) {
                                            $c_sel = $record['branch_id'] == $b['id'] ? 'selected' : '';
                                            echo '<option ' . $c_sel . ' value="' . $b['id'] . '">' . $b['b_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <label for="details" class="form-label">DETAILS</label>
                                    <input value="<?php echo $record['details']; ?>" id="details" name="details"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button id="recordSubmit" name="recordSubmit" type="submit" class="btn btn-dark w-md">
                                    Update Category
                                </button>
                            </div>
                            <input type="hidden" value="update" name="action">
                            <input type="hidden" value="<?php echo $id; ?>" name="cat_id_hidden">
                        </form>
                    <?php }
                    if ($newRecord) { ?>
                        <form method="post" onsubmit="return confirm('Are you sure to save data?');">
                            <div class="row gy-4">
                                <div class="col-md-2">
                                    <label for="name" class="form-label">CATEGORY NAME</label>
                                    <input type="text" id="name" name="name" class="form-control" required autofocus>
                                </div>
                                <div class="col-md-3">
                                    <label for="choices-single-default" class="form-label">BRANCH</label>
                                    <select class="form-control" data-trigger name="branch_id"
                                            id="choices-single-default" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php $countries = fetch('branches');
                                        while ($country = mysqli_fetch_assoc($countries)) {
                                            echo '<option value="' . $country['id'] . '">' . $country['b_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <label for="details" class="form-label">DETAILS</label>
                                    <input type="text" id="details" name="details" class="form-control">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button id="recordSubmit" name="recordSubmit" type="submit" class="btn btn-dark w-md">
                                    Add New Category
                                </button>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php $url = "category-add";
if (isset($_POST['recordSubmit'])) {
    $type = 'danger';
    $msg = 'System Error!';
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $record_added = false;
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $cat_id = mysqli_real_escape_string($connect, $_POST['cat_id_hidden']);
        $record_added = update('cats', $data, array('id' => $cat_id));
        if ($record_added) {
            $type = 'warning';
            $msg = 'Category has been updated under branch ' . branchName($_POST['branch_id']);
        }
        $url .= '?id=' . $cat_id;
    } else {
        $record_added = insert('cats', $data);
        if ($record_added) {
            $url .= '?id=' . $connect->insert_id;
            $type = 'success';
            $msg = 'New Category has been created under branch ' . branchName($_POST['branch_id']);
        }
    }
    message($type, $url, $msg);
} ?>



<?php $page_title = 'Category Entry';
$pageURL = 'category-add';
$back_page_url = 'categories';
include("header.php");
$id = $branch_id = 0;
$name = $details = '';
$record = array();
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('cats', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $name = $record['name'];
    $branch_id = $record['branch_id'];
    $details = $record['details'];
} ?>
<div class="row">
    <div class="col-xl-12">
        <div class="d-flex align-items-center justify-content-between mb-md-2">
            <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
            <div>
                <?php echo backUrl($back_page_url); ?>
                <?php echo addNew('category-add', '', 'btn-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post" onsubmit="return confirm('Are you sure to save data?');">
                    <div class="row gy-4 gx-1 table-form">
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="name" class="form-label">CATEGORY NAME</label>
                                <input value="<?php echo $name; ?>" id="name" name="name" class="form-control" required
                                       autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="branch_id">BRANCH</label>
                                <select class="form-select" data-trigger name="branch_id" id="branch_id" required>
                                    <option value="" hidden="" disabled selected>Select</option>
                                    <?php $branches = fetch('branches');
                                    while ($b = mysqli_fetch_assoc($branches)) {
                                        $c_sel = $branch_id == $b['id'] ? 'selected' : '';
                                        echo '<option ' . $c_sel . ' value="' . $b['id'] . '">' . $b['b_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="details" class="form-label">DETAILS</label>
                                <input value="<?php echo $details; ?>" id="details" name="details"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button id="recordSubmit" name="recordSubmit" type="submit" class="btn btn-dark">
                            Submit
                        </button>
                    </div>
                    <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>$("#entries").addClass('active');</script>
<script>$("#categories").addClass('active');</script>
<?php if (isset($_POST['recordSubmit'])) {
    $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id']),
        'details' => mysqli_real_escape_string($connect, $_POST['details'])
    );
    if ($hidden_id > 0) {
        $done = update('cats', $data, array('id' => $hidden_id));
        $pageURL .= "?id=" . $hidden_id;
        if ($done) {
            $msg_array['msg'] = 'Category Successfully Updated.';
            $msg_array['type'] = 'success';
        }
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $done = insert('users', $data);
        if ($done) {
            $insert_id = $connect->insert_id;
            $pageURL .= "?id=" . $insert_id;
            $msg_array['msg'] = 'New Category Successfully added.';
            $msg_array['type'] = 'success';
        }
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);




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
    messageNew($type, $url, $msg);
} ?>



<?php $page_title = 'Navbar';
$pageURL = 'navbar';
include("header.php");
$navId = $parent_id = $is_view = 0;
$label = $url = $position = '';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $navId = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('navbar', array('id' => $navId));
    $record = mysqli_fetch_assoc($records);
    $parent_id = $record['parent_id'];
    $is_view = $record['is_view'];
    $label = $record['label'];
    $url = $record['url'];
    $position = $record['position'];
} ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-2">
            <?php //echo searchInput('a', 'form-control-sm '); ?>
            <?php echo addNew('navbar', '', 'btn-sm'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body position-relative">
                        <?php if ($navId > 0) { ?>
                            <form method="post" class="position-absolute" style="right: 10px; top: 10px; z-index: 9"
                                  onsubmit="return confirm('Are you sure to delete?')">
                                <input type="hidden" name="hidden_id" value="<?php echo $navId; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-sm btn-danger"
                                        name="deleteNavSubmit">Delete
                                </button>
                            </form>
                        <?php } ?>
                        <form method="post" class="table-form pt-3">
                            <div class="row gy-4 gx-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="label">Label</label>
                                        <input value="<?php echo $label; ?>" id="label" name="label"
                                               class="form-control" autofocus required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="url">URL </label>
                                        <input value="<?php echo $url; ?>" name="url" id="url" class="form-control"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="position">Position</label>
                                        <input value="<?php echo $position; ?>" type="number"
                                               id="position" placeholder="Number" class="form-control" required
                                               name="position">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label for="parent_id">Parent</label>
                                        <select name="parent_id" id="parent_id" class="form-select">
                                            <option value="">No Parent</option>
                                            <?php
                                            $parents = mysqli_query($connect, "SELECT * FROM `navbar`  ORDER BY `position` ASC");
                                            //$parents = mysqli_query($connect, "SELECT * FROM `navbar` WHERE parent_id < 1 ORDER BY `position` ASC");
                                            //$parents = fetch('navbar',array('parent_id' => $parent_id));
                                            while ($parent = mysqli_fetch_assoc($parents)) {
                                                $parent_select = $parent_id == $parent['id'] ? 'selected' : '';
                                                echo '<option ' . $parent_select . ' value="' . $parent['id'] . '">' . $parent['label'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!--<label for="url" class="form-label">&nbsp;</label>-->
                                    <div class="form-check my-1 form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_view"
                                               name="is_view"
                                               value="1" <?php echo $is_view == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_view">
                                            Show in forms?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" name="createNavSubmit" class="btn btn-dark">
                                    Submit
                                </button>
                                <?php echo $navId > 0 ? backUrl('navbar') : ''; ?>
                            </div>
                            <input type="hidden" name="hidden_id" value="<?php echo $navId; ?>">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php $navs = mysqli_query($connect, "SELECT * FROM `navbar` WHERE parent_id =0 ORDER BY position");
                if (mysqli_num_rows($navs) > 0) {
                    echo '<ul class="list-group">';
                    while ($row = mysqli_fetch_assoc($navs)) {
                        echo '<li class="list-group-item">';
                        echo '<a class="fw-bold text-dark" href="navbar?id=' . $row['id'] . '">';
                        echo $row['position'] . '. ' . $row['label'];
                        echo '</a>';
                        generateSubMenuAdmin($row['id'], $connect);
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo 'No navigation links found.';
                } ?>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['createNavSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $data = array(
        'label' => mysqli_real_escape_string($connect, $_POST['label']),
        'url' => mysqli_real_escape_string($connect, $_POST['url']),
        'position' => mysqli_real_escape_string($connect, $_POST['position'])
    );
    $data['is_view'] = isset($_POST['is_view']) ? 1 : 0;
    if (isset($_POST['parent_id'])) {
        $data['parent_id'] = mysqli_real_escape_string($connect, $_POST['parent_id']);
    }


    if ($hidden_id > 0) {
        $done = update('navbar', $data, array('id' => $hidden_id));
        $pageURL .= "?id=" . $hidden_id;
        if ($done) {
            $msg_array['msg'] = 'Navbar Successfully Updated.';
            $msg_array['type'] = 'success';
        }
    } else {
        $done = insert('navbar', $data);
        if ($done) {
            $insert_id = $connect->insert_id;
            $pageURL .= "?id=" . $insert_id;

            $msg_array['msg'] = 'Navbar Successfully added.';
            $msg_array['type'] = 'success';
        }
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
} ?>
<?php if (isset($_POST[''])) {
    $msg = 'DB Error :(';
    $msgType = 'danger';
    $data = array(
        'label' => mysqli_real_escape_string($connect, $_POST['label']),
        'url' => mysqli_real_escape_string($connect, $_POST['url']),
        'position' => mysqli_real_escape_string($connect, $_POST['position'])
    );
    $data['is_view'] = isset($_POST['is_view']) ? 1 : 0;
    if (isset($_POST['parent_id'])) {
        $data['parent_id'] = mysqli_real_escape_string($connect, $_POST['parent_id']);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $done = update('navbar', $data, array('id' => $hidden_id));
        if ($done) {
            $msg = 'Navbar link hase been updated.';
            $msgType = 'success';
        }
    } else {
        $done = insert('navbar', $data);
        if ($done) {
            $msg = 'New Navbar link has been saved.';
            $msgType = 'success';
        }
    }
    message($msgType, $pageURL, $msg);

} ?>
<?php if (isset($_POST['deleteNavSubmit'])) {
    $msg = 'DB Error :(';
    $msgType = 'danger';
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['hidden_id']) && is_numeric($_POST['hidden_id'])) {
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $done = mysqli_query($connect, "DELETE FROM `navbar` WHERE id = '$hidden_id'");
        if ($done) {
            $msg = 'Navbar link hase been Deleted.';
            $msgType = 'warning';
        }
    }
    messageNew($msgType, $pageURL, $msg);

} ?>


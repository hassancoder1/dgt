<?php $page_title = 'Navbar';
include("header.php"); ?>
<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body position-relative">
                <?php if (isset($_SESSION['response'])) {
                    echo '<div class="flex-fill">' . $_SESSION['response'] . '</div>';
                    unset($_SESSION['response']);
                } ?>
                <?php if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $navId = mysqli_real_escape_string($connect, $_GET['id']);
                    $records = fetch('navbar', array('id' => $navId));
                    $record = mysqli_fetch_assoc($records); ?>
                    <form method="post" class="position-absolute" style="right: 10px; top: 10px"
                          onsubmit="return confirm('Are you sure to delete?')">
                        <input type="hidden" name="hidden_id" value="<?php echo $navId; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                name="deleteNavSubmit">Delete
                        </button>
                    </form>
                    <form method="post">
                        <div class="row mb-3">
                            <div class="col-8">
                                <label for="label" class="form-label">Label </label>
                                <input value="<?php echo $record['label']; ?>" type="text" id="label"
                                       placeholder="Label"
                                       class="form-control" autofocus required name="label">
                            </div>
                            <div class="col-4">
                                <label for="url" class="form-label">Is View? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_view" name="is_view"
                                           value="1" <?php echo $record['is_view'] == 1 ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_view">
                                        Yes, Sure
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL </label>
                            <input value="<?php echo $record['url']; ?>" type="text" id="url"
                                   placeholder="URL"
                                   class="form-control"
                                   required name="url">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="position" class="form-label">Position</label>
                                <input value="<?php echo $record['position']; ?>" type="number" id="position"
                                       placeholder="Number" class="form-control" required name="position">
                            </div>
                            <div class="col">
                                <label for="icon_class" class="form-label">Icon class</label>
                                <input type="text" id="icon_class" placeholder="e.g. grid-outline"
                                       class="form-control" name="icon_class">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">No Parent</option>
                                <?php $parents = fetch('navbar');
                                while ($parent = mysqli_fetch_assoc($parents)) {
                                    $parent_select = $record['parent_id'] == $parent['id'] ? 'selected' : '';
                                    echo '<option ' . $parent_select . ' value="' . $parent['id'] . '">' . $parent['label'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="createNavSubmit" class="btn btn-success btn-sm">
                                Submit
                            </button>
                            <?php echo backUrl('navbar'); ?>
                        </div>
                        <input type="hidden" name="hidden_id" value="<?php echo $navId; ?>">
                        <input type="hidden" name="action" value="update">
                    </form>
                <?php } else { ?>
                    <form method="post">
                        <div class="row mb-3">
                            <div class="col-8">
                                <label for="label" class="form-label">Label</label>
                                <input type="text" id="label" placeholder="Label"
                                       class="form-control" autofocus required name="label">
                            </div>
                            <div class="col-4">
                                <label for="url" class="form-label">Is View? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_view" name="is_view"
                                           value="1" checked>
                                    <label class="form-check-label" for="is_view">
                                        Yes, Sure
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL </label>
                            <input type="text" id="url" placeholder="URL" class="form-control" required name="url">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="position" class="form-label">Position</label>
                                <input type="number" id="position" placeholder="Number" class="form-control"
                                       required
                                       name="position">
                            </div>
                            <div class="col">
                                <label for="icon_class" class="form-label">Icon class</label>
                                <input type="text" id="icon_class" placeholder="e.g. grid-outline"
                                       class="form-control" name="icon_class">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent (Optional)</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">No Parent</option>
                                <?php $parents = fetch('navbar');
                                while ($parent = mysqli_fetch_assoc($parents)) {
                                    echo '<option value="' . $parent['id'] . '">' . $parent['label'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <button type="submit" name="createNavSubmit" class="btn btn-dark">
                            Submit
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-7 grid-margin stretch-card">
        <table class="table table-sm">
            <tbody>
            <?php
            //$navs = mysqli_query($connect,"SELECT * FROM navbar ORDER BY position");
            $navs = mysqli_query($connect, "SELECT * FROM `navbar` WHERE parent_id =0 ORDER BY position");
            if (mysqli_num_rows($navs) > 0) {
                echo '<ul class="list-group">';
                while ($row = mysqli_fetch_assoc($navs)) {
                    echo '<li class="list-group-item">';
                    echo '<a class="" href="navbar?id=' . $row['id'] . '">';
                    $eva = $row['icon_class'] == '' ? 'calendar-outline' : $row['icon_class'];
                    echo '<i class="icon nav-icon" style="width:15px; height:15px; fill:#b4b2b2" data-eva="' . $eva . '"></i> ';
                    echo '<span class="">' . $row['position'] . '</span>. '. $row['label'];
                    echo '</a>';
                    //echo '<span class="badge bg-primary p-1 ms-2">URL: ' . $row['url'] . '</span>';
                    generateSubMenuAdmin($row['id'], $connect);
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo 'No navigation links found.';
            } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['createNavSubmit'])) {
    $msg = 'DB Error :(';
    $msgType = 'danger';
    $data = array(
        'label' => mysqli_real_escape_string($connect, $_POST['label']),
        'url' => mysqli_real_escape_string($connect, $_POST['url']),
        'icon_class' => mysqli_real_escape_string($connect, $_POST['icon_class']),
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
    message($msgType, $url, $msg);

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
    message($msgType, $url, $msg);

} ?>


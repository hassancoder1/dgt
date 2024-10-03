<?php $page_title = 'Goods';
include("header.php");
$sql = "SELECT * FROM `goods` WHERE id>0 ";
$goods_id = $gd_id = 0;
$name = $size = $brand = $origin = '';
$action = 'insert';
if (isset($_GET['goods_id']) && is_numeric($_GET['goods_id'])) {
    $goods_id = mysqli_real_escape_string($connect, $_GET['goods_id']);
    $sql .= " AND id = '$goods_id'";
    $action = 'update';
    $goods_query = fetch('goods', array('id' => $goods_id));
    $good_single = mysqli_fetch_assoc($goods_query);
    $name = $good_single['name'];
    if (isset($_GET['gd_id']) && is_numeric($_GET['gd_id'])) {
        $gd_id = mysqli_real_escape_string($connect, $_GET['gd_id']);
        $gd_query = fetch('good_details', array('id' => $gd_id));
        $gd_single = mysqli_fetch_assoc($gd_query);
        $size = $gd_single['size'];
        $brand = $gd_single['brand'];
        $origin = $gd_single['origin'];
    }
} ?>

    <div class="fixed-top">
        <?php require_once('nav-links.php'); ?>
        <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
            <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
            <form name="filterForm" method="get">
                <select class="form-select form-select-sm" name="goods_id" id="goods_id" required>
                    <option value="0">All Goods</option>
                    <?php $branches = fetch('goods');
                    while ($b = mysqli_fetch_assoc($branches)) {
                        $b_sel = $goods_id == $b['id'] ? 'selected' : '';
                        echo '<option ' . $b_sel . ' value="' . $b['id'] . '">' . $b['name'] . '</option>';
                    } ?>
                </select>
            </form>
            <div class="d-flex gap-2 text-nowrap">
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                    + New
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $goods = mysqli_query($connect, $sql);
            $x = 1;
            while ($good = mysqli_fetch_assoc($goods)) { ?>
                <div class="card- mb-1">
                    <div class="card-body p-2">
                        <a href="<?php echo 'goods?goods_id=' . $good['id']; ?>"
                           class="text-muted fw-bold"><?php echo $x . '-' . $good['name']; ?></a>
                        <div class="row row-cols gx-1">
                            <?php $gds = fetch('good_details', array('goods_id' => $good['id']));
                            while ($gd = mysqli_fetch_assoc($gds)) { ?>
                                <div class="col">
                                    <div class="border card mb-1 p-1 small">
                                        <a href="<?php echo 'goods?goods_id=' . $good['id'] . '&gd_id=' . $gd['id']; ?>"
                                           class="text-dark">
                                            <?php echo '<div><span class="text-muted">S.</span> ' . $gd['size'] . '</div>';
                                            echo '<div><span class="text-muted">B.</span> ' . $gd['brand'] . '</div>';
                                            echo '<div><span class="text-muted">O.</span> ' . $gd['origin'] . '</div>'; ?>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $x++;
            } ?>

        </div>
    </div>
<?php include("footer.php"); ?>
    <script>$("#entries").addClass('active');</script>
    <script>$("#goods").addClass('active');</script>
<?php $url = "goods";
if (isset($_POST['recordSubmit'])) {
    $type = 'danger';
    $msg = 'System Error!';
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name'])
    );
    $data2 = array(
        'size' => mysqli_real_escape_string($connect, $_POST['size']),
        'brand' => mysqli_real_escape_string($connect, $_POST['brand']),
        'origin' => mysqli_real_escape_string($connect, $_POST['origin'])
    );

    $record_added = false;
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $record_added = update('goods', $data, array('id' => $hidden_id));
        if ($record_added) {
            $type = 'success';
            $msg = 'Record updated.';
        }
        $url .= '?goods_id=' . $hidden_id;
        if (isset($_POST['gd_hidden_id'])) {
            $gd_hidden_id = mysqli_real_escape_string($connect, $_POST['gd_hidden_id']);
            if ($gd_hidden_id > 0) {
                $record_added = update('good_details', $data2, array('id' => $gd_hidden_id));
            } else {
                $data2['goods_id'] = $hidden_id;
                $record_added = insert('good_details', $data2);
            }
        }
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $record_added = insert('goods', $data);
        if ($record_added) {
            $g_id = $connect->insert_id;
            $url .= '?goods_id=' . $g_id;
            $data2['goods_id'] = $g_id;
            $record_added = insert('good_details', $data2);
            $type = 'success';
            $msg = 'New record saved.';
        }
    }
    messageNew($type, $url, $msg);
} ?>
    <script type="text/javascript">
        $(function () {
            $('#goods_id').on('change', function () {
                var goods_id = $(this).val();
                var url = 'goods';
                if (goods_id > 0) {
                    url += '?goods_id=' + goods_id;
                }
                window.location = url;
                return false;
            });
        });
    </script>
<?php if (isset($_GET['goods_id']) && is_numeric($_GET['goods_id'])) {
    echo "<script>jQuery(document).ready(function ($) {  $('#staticBackdrop').modal('show');});</script>";
} ?>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Goods</h1>
                    <a href="goods" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <form method="post" class="table-form">
                        <div class="row gy-3 gx-1">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label for="name">GOODS</label>
                                    <input value="<?php echo $name ?>" id="name" name="name"
                                           class="form-control"
                                           required>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="input-group">
                                    <label for="size">SIZE</label>
                                    <input value="<?php echo $size ?>" id="size" name="size"
                                           class="form-control"
                                           required>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="input-group">
                                    <label for="brand">BRAND</label>
                                    <input value="<?php echo $brand ?>" id="brand" name="brand"
                                           class="form-control"
                                           required>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="input-group">
                                    <label for="origin">ORIGIN</label>
                                    <input value="<?php echo $origin; ?>" id="origin" name="origin"
                                           class="form-control"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" name="recordSubmit" class="btn btn-primary btn-sm">Submit
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="hidden_id" value="<?php echo $goods_id; ?>">
                        <input type="hidden" name="gd_hidden_id" value="<?php echo $gd_id; ?>">
                        <input type="hidden" name="action" value="<?php echo $action; ?>">
                    </form>
                    <?php if ($goods_id > 0) { ?>
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>SIZE</th>
                                <th>BRAND</th>
                                <th>ORIGIN</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $gds = fetch('good_details', array('goods_id' => $goods_id));
                            $y = 1;
                            while ($gd = mysqli_fetch_assoc($gds)) { ?>
                                <tr>
                                    <td><?php echo $y; ?></td>
                                    <td>
                                        <a href="<?php echo 'goods?goods_id=' . $goods_id . '&gd_id=' . $gd['id']; ?>"><?php echo $gd['size']; ?></a>
                                    </td>
                                    <td><?php echo $gd['brand']; ?></td>
                                    <td><?php echo $gd['origin']; ?></td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('Are you sure to delete?');">
                                            <input type="hidden" name="goods_id" value="<?php echo $goods_id; ?>">
                                            <input type="hidden" name="gd_id" value="<?php echo $gd['id']; ?>">
                                            <button type="submit" name="deleteGdSubmit"
                                                    class="btn btn-link text-danger py-0">Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $y++;
                            } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php if (isset($_POST['deleteGdSubmit'])) {
    $goods_id = mysqli_real_escape_string($connect, $_POST['goods_id']);
    $gd_id = mysqli_real_escape_string($connect, $_POST['gd_id']);
    $type = 'danger';
    $msg = 'System Error!';
    if ($gd_id > 0) {
        $d = mysqli_query($connect, "DELETE FROM `good_details` WHERE id = '$gd_id'");
        if ($d) {
            $type = 'success';
            $msg = 'Record deleted successfully.';
        }
    }
    messageNew($type, $url . '?goods_id=' . $goods_id, $msg);
} ?>
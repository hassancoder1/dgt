<?php $page_title = 'Accounts';
$pageURL = 'khaata';
include("header.php"); ?>
<?php $acc_for = $khaata_no = $khaata_no_msg = $removeFilter = $branch_msg = $cat_msg = $acc_for_msg = "";
$cat_ids = array();
$selectedBranchId = 0;
$sql = "SELECT * FROM `khaata` WHERE id > 0 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (!empty($_GET['acc_for'])) {
        $acc_for = mysqli_real_escape_string($connect, $_GET['acc_for']);
        $sql .= " AND acc_for = " . "'$acc_for'" . " ";
        $acc_for_msg = '<b>Type</b>' . strtoupper($acc_for);
    }
    if (isset($_GET['cat_ids']) && !empty($_GET['cat_ids'][0])) {
        $cat_ids = $_GET['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $sql .= " AND cat_id IN " . $in;
        $cat_msg = '<b>C.</b>';
        foreach ($cat_ids as $cc) {
            $cat_msg .= catName($cc) . '|';
        }
    }
    if (!empty($_GET['branch_id'])) {
        $selectedBranchId = $_GET['branch_id'];
        if ($selectedBranchId > 0) {
            $sql .= " AND branch_id = " . "'$selectedBranchId'" . " ";
            $branch_msg = branchName($selectedBranchId, 'B.');
        }
    }
    if (!empty($_GET['khaata_no'])) {
        $khaata_no = $_GET['khaata_no'];
        $sql .= " AND khaata_no = " . "'$khaata_no'" . " ";
        $khaata_no_msg = '<b>A/c No.</b>' . $khaata_no;
    }
} ?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div><b>ROWS: </b><span id="rows_count_span"></span></div>
        <form method="get" class="d-flex align-items-center -table-form">
            <select id="branch_id" name="branch_id" class="form-select form-select-sm">
                <option value="0">All Branches</option>
                <?php $branches = fetch('branches');
                while ($branch = mysqli_fetch_assoc($branches)) {
                    $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                    echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_code'] . '</option>';
                } ?>
            </select>
            <select id="acc_for" name="acc_for" class="form-select form-select-sm">
                <option value="">All Types</option>
                <?php $types = array('client', 'agent', 'bank');
                foreach ($types as $type) {
                    $selectedType = $type == $acc_for ? "selected" : "";
                    echo '<option ' . $selectedType . ' value="' . $type . '">' . strtoupper($type) . '</option>';
                } ?>
            </select>
            <input type="text" id="khaata_no" name="khaata_no" class="form-control form-control-sm"
                placeholder="Account No."
                value="<?php echo $khaata_no; ?>">
            <select multiple name="cat_ids[]" id="cat_id" class="v-select" placeholder="Category">
                <?php $cats = fetch('cats');
                while ($cat = mysqli_fetch_assoc($cats)) {
                    $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                    echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                } ?>
            </select>
            <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-search"></i></button>
        </form>
        <div class="d-flex gap-2 text-nowrap align-items-center">
            <?php echo searchInput('a', 'form-control-sm'); ?>
            <?php echo addNew('khaata-add?acc_for=client', '', 'btn-sm'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table mb-0 table-sm table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>A/C NO.</th>
                            <th>A/C NAME</th>
                            <th>Bank</th>
                            <th>Company</th>
                            <th>Customer</th>
                            <th>A/C FOR</th>
                            <th>BRANCH</th>
                            <th>CATEGORY</th>
                            <th>CONTACT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $row_count = 0;
                        $khaatas = mysqli_query($connect, $sql);
                        $companies = mysqli_fetch_all(mysqli_query($connect, "SELECT khaata_id, json_data FROM khaata_details"), MYSQLI_ASSOC);
                        while ($khaata = mysqli_fetch_assoc($khaatas)) {
                            $id = $khaata['id'];
                            if (empty($khaata['image'])) {
                                $img_url = 'assets/img/avatar.png';
                            } else {
                                $img_url = $khaata['image'];
                            }
                            $rowColor = '';
                            if ($khaata['acc_for'] == "agent") {
                                $rowColor = 'bg-warning bg-opacity-10';
                            }
                            if ($khaata['acc_for'] == "bank") {
                                $rowColor = 'bg-danger bg-opacity-10';
                            }

                            $companyMatch = false; // Default is no match
                            $companyData = '';

                            // Check if this khaata's ID matches any company's khaata_id
                            foreach ($companies as $company) {
                                if ($khaata['id'] == $company['khaata_id']) {
                                    $companyMatch = true;
                                    $companyData = $company['json_data'];
                                    break;
                                }
                            }
                            ++$row_count; ?>
                            <tr>
                                <td><?php echo $khaata['id']; ?></td>
                                <td><?php echo my_date($khaata['created_at']); ?></td>
                                <td><?php echo $khaata['khaata_no']; ?></td>
                                <td>
                                    <a class="text-dark" href="khaata-add?id=<?php echo $khaata['id']; ?>">
                                        <?php echo $khaata['khaata_name']; ?>
                                    </a>
                                </td>
                                <td class="<?= !empty($khaata['bank_details']) ? 'fw-bold text-success' : 'fw-bold text-danger'; ?>">
                                    <?= !empty($khaata['bank_details']) ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="<?= $companyMatch ? 'fw-bold text-success' : 'fw-bold text-danger'; ?>">
                                    <?php echo $companyMatch ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="<?= !empty($khaata['contact_details']) ? 'fw-bold text-success' : 'fw-bold text-danger'; ?>">
                                    <?= !empty($khaata['contact_details']) ? 'Yes' : 'No'; ?>
                                </td>

                                <td><?php echo badge(strtoupper($khaata['acc_for']), 'dark'); ?></td>
                                <td><?php echo branchName($khaata['branch_id']); ?></td>
                                <td><?php echo catName($khaata['cat_id']); ?></td>
                                <td>
                                    <ul class="socials">
                                        <li><a href="tel://<?php echo $khaata['phone']; ?>"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Mobile <?php echo $khaata['phone']; ?>">
                                                <i class="fa fa-mobile"></i>
                                            </a></li>
                                        <li>
                                            <a href="mailto://<?php echo $khaata['email']; ?>"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Email <?php echo $khaata['email']; ?>">
                                                <i class="fa fa-envelope"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <?php if (SuperAdmin()) { ?>
                                        <form method="post" onsubmit="return confirm('Are you sure to delete?')">
                                            <input type="hidden" name="hidden_id" value="<?php echo $khaata['id']; ?>">
                                            <button name="deleteSubmit" type="submit" data-bs-toggle="tooltip" data-bs-title="Delete Account <?php echo $khaata['khaata_no']; ?>"
                                                class="btn btn-sm btn-outline-danger py-0 px-1">
                                                <i class="fa fa-trash-alt"></i></button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#entries").addClass('active');
</script>
<script>
    $("#khaata").addClass('active');
</script>
<script>
    $("#rows_count_span").text($("#row_count").val());
</script>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="staticBackdropLabel">DGT L.L.C DETAILS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0" id="viewKhaataDetailsBody"></div>
        </div>
    </div>
</div>
<script>
    function viewKhaataDetails(id = null, k_no = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleKhaataAndDetails.php',
                type: 'post',
                data: {
                    id: id,
                    k_no: k_no
                },
                //dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#viewKhaataDetailsBody').html(response);
                    $('#khaata_no').focus();
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<script>
    $(document).on('keyup', "#khaata_no", function(e) {
        let khaata_no = $(this).val();
        fetchKhaata(khaata_no);
    });

    function fetchKhaata(khaata_no) {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {
                khaata_no: khaata_no
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    viewKhaataDetails(response.messages['khaata_id'], khaata_no);
                }
                if (response.success === false) {
                    $("#response").text('INVALID');
                    $("#response").addClass('text-danger');
                }
            }
        });
    }
</script>
<?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['deleteSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    mysqli_begin_transaction($connect);
    try {
        $dependentTables = [
            'khaata_details' => 'khaata_id'
        ];
        foreach ($dependentTables as $table => $foreignKeyColumn) {
            $checkDependents = mysqli_query($connect, "SELECT COUNT(*) AS count FROM `$table` WHERE `$foreignKeyColumn` = '$hidden_id'");
            $dependentCount = mysqli_fetch_assoc($checkDependents)['count'];
            if ($dependentCount > 0) {
                throw new Exception("Unable to delete the record due to dependencies.");
            }
        }
        $del = mysqli_query($connect, "DELETE FROM `khaata` WHERE id = '$hidden_id'");
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
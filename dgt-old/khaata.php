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
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between mb-2 table-form">
            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
            <form method="get" class="d-flex align-items-center table-form text-nowrap">
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <select id="branch_id" name="branch_id" class="form-select">
                    <option value="0">All Branches</option>
                    <?php $branches = fetch('branches');
                    while ($branch = mysqli_fetch_assoc($branches)) {
                        $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                        echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_code'] . '</option>';
                    } ?>
                </select>
                <select id="acc_for" name="acc_for" class="form-select">
                    <option value="">All Types</option>
                    <?php $types = array('client', 'agent', 'bank');
                    foreach ($types as $type) {
                        $selectedType = $type == $acc_for ? "selected" : "";
                        echo '<option ' . $selectedType . ' value="' . $type . '">' . strtoupper($type) . '</option>';
                    } ?>
                </select>
                <select multiple name="cat_ids[]" id="cat_id" class="v-select" placeholder="Category">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                    } ?>
                </select>
                <input type="text" id="khaata_no" name="khaata_no" class="form-control" placeholder="Account No."
                       value="<?php echo $khaata_no; ?>">
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-search"></i></button>
            </form>
            <div>
                <?php echo $khaata_no_msg . ' ' . $branch_msg . ' ' . $cat_msg . ' ' . $acc_for_msg . ' ' . $removeFilter; ?>
            </div>
            <?php echo addNew('khaata-add?acc_for=client', '', 'btn-sm'); ?>
        </div>
        <?php if (isset($_SESSION['response'])) {
            echo '<div class="flex-fill">' . $_SESSION['response'] . '</div>';
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 70dvh;">
                    <table class="table mb-0 table-bordered fix-head-table table-sm">
                        <thead style="z-index: auto">
                        <tr>
                            <th></th>
                            <th>ACCOUNT</th>
                            <th>INFO</th>
                            <th>BRANCH</th>
                            <th>CONTACTS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = 0;
                        $khaatas = mysqli_query($connect, $sql);
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
                            } ?>
                            <tr class="<?php //echo $rowColor; ?>">
                                <td>
                                    <img src="<?php echo $img_url; ?>" alt="img" class="avatar-md rounded">
                                </td>
                                <td class="pointer"
                                    onclick="viewKhaataDetails(<?php echo $id . ",'" . $khaata['khaata_no'] . "'"; ?>)"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <?php echo '<b>A/c#</b>' . $khaata['khaata_no'];
                                    echo ' <span class="badge badge-soft-danger">' . strtoupper($khaata['acc_for']) . '</span><br>';
                                    $lab = $khaata['acc_for'] == 'bank' ? '<b>Bank </b>' : '<b>Name </b>';
                                    echo '<span class="font-size-11">';
                                    echo $lab . $khaata['khaata_name'] . '<br>';
                                    echo '<b>Company </b>' . $khaata['comp_name'];
                                    echo '</span>'; ?>
                                </td>
                                <td>
                                    <?php echo '<b>GEN. A/c.#</b>' . $id;
                                    $exx = fetch('khaata_details', array('khaata_id' => $id, 'is_active' => 1));
                                    $extra_accounts = mysqli_num_rows($exx);
                                    echo $extra_accounts > 0 ? '<br><span class="badge badge-soft-dark">' . $extra_accounts . ' more accounts</span>' : '';
                                    ?>
                                </td>
                                <td class="small">
                                    <?php echo '<b>B.</b> ' . branchName($khaata['branch_id']) . '<br>';
                                    echo '<b>C.</b> ' . catName($khaata['cat_id']); ?>
                                </td>
                                <td class="small text-dark">
                                    <?php if ($khaata['indexes'] != '' && $khaata['vals'] != '') {
                                        $indexes = json_decode($khaata['indexes']);
                                        $vals = json_decode($khaata['vals']);
                                        $combinedArray = array_combine($indexes, $vals);
                                        foreach ($combinedArray as $key => $value) {
                                            echo href_link2($key, $value, $value, true, $key, '_blank', 'text-dark') . '<br>';
                                        }
                                    }
                                    //echo href_link('tel://' . $khaata['mobile'], $khaata['mobile'], '', 'text-dark', '', 'M.', 'Call on Mobile NUmber');
                                    //echo '<br>' . href_link('tel://' . $khaata['phone'], $khaata['phone'], '', 'text-dark', '', 'P.', 'Call on Phone Number');
                                    //echo '<br>' . href_link('mailto://' . $khaata['email'], $khaata['email'], '', 'text-dark', '', 'E.', 'Send Email');
                                    //echo '<br>' . href_link('https://wa.me/' . $khaata['whatsapp'] . '?text=Hello!%0aMr. ' . $khaata['khaata_name'], $khaata['whatsapp'], '_blank', 'text-dark', 'fab fa-whatsapp fw-bold', '', 'Message at WhatsApp');
                                    ?>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
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
                data: {id: id, k_no: k_no},
                //dataType: 'json',
                success: function (response) {
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
    $(document).on('keyup', "#khaata_no", function (e) {
        let khaata_no = $(this).val();
        fetchKhaata(khaata_no);
    });

    function fetchKhaata(khaata_no) {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
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

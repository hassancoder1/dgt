<?php require_once '../connection.php';
$khaat_id = $_POST['id'];
$khaat_no = $_POST['k_no'];
$userId = $_SESSION["userId"];
$labels = array(
    array('A/C NAME', 'BUSINESS CAPITAL', 'OWNER NAME'),
    array('BANK NAME', 'BRANCH NAME', 'BANK A/C No.')
);
$output = '';
if ($khaat_id) {
    $khaata = $data = khaataSingle($khaat_id);
    $acc_for = $khaata['acc_for'];
    //var_dump($data);
    if (empty($data['image'])) {
        $img_url = 'assets/img/avatar.png';
    } else {
        $img_url = $data['image'];
    } ?>
    <div class="row">
        <div class="col-2">
            <div
                class="d-flex flex-column align-items-center justify-content-between position-fixed border-end table-form py-3 px-2"
                style="min-height: 85vh;  min-width: 200px;">
                <div class="input-group position-relative">
                    <input value="<?php echo $khaat_no; ?>" id="khaata_no" name="khaata_no"
                           class="form-control bg-transparent w-100" required
                           autofocus placeholder="Account #">
                    <small class="error-response text-success top-0" id="response"><i
                            class="fa fa-check-double"></i></small>
                </div>
                <div class="d-flex justify-content-between w-100">
                    <a href="" onclick="window.print()" class="btn btn-dark btn-sm">PRINT</a>
                    <a href="<?php echo 'khaata-add?acc_for=' . $khaata['acc_for'] . '&id=' . $khaat_id; ?>"
                       class="btn btn-primary btn-sm">UPDATE</a>
                </div>
            </div>
        </div>
        <div class="col-10">
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <div><span class="fw-bold">DATE</span> <span><?php echo $khaata['khaata_date']; ?></span></div>
                    <div><span class="fw-bold">BRANCH</span>
                        <span><?php echo branchName($khaata['branch_id']); ?></span>
                    </div>
                    <div>
                        <span class="fw-bold">CATEGORY</span> <span><?php echo catName($khaata['cat_id']); ?></span>
                    </div>
                    <br>
                    <div>
                        <span class="fw-bold">A/C No.</span>
                        <span><?php echo $khaata['khaata_no']; ?></span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $acc_for == 'bank' ? $labels[1][0] : $labels[0][0]; ?></span>
                        <span><?php echo $khaata['khaata_name']; ?></span>
                    </div>
                    <div><span class="fw-bold">COMPANY NAME</span><span><?php echo $khaata['comp_name']; ?></span></div>
                    <?php if ($acc_for == 'bank') { ?>
                        <div><span class="fw-bold">CURRENCY</span><span><?php echo $khaata['currency']; ?></span></div>
                    <?php } ?>
                    <div>
                        <span class="fw-bold"><?php echo $acc_for == 'bank' ? $labels[1][1] : $labels[0][1]; ?></span>
                        <span><?php echo $khaata['business_name']; ?></span>
                    </div>
                </div>
                <div>
                    <div>
                        <span class="fw-bold">COUNTRY</span>
                        <span><?php echo countryName($khaata['country_id']); ?></span>
                    </div>
                    <div>
                        <span class="fw-bold">CITY</span>
                        <span><?php echo $khaata['city']; ?></span>
                    </div>
                    <div>
                        <span class="fw-bold">ADDRESS</span>
                        <span><?php echo $khaata['address']; ?></span>
                    </div>
                    <div>
                        <span class="fw-bold">DETAILS</span>
                        <span><?php echo $khaata['details']; ?></span>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?php echo $img_url; ?>" alt="" class="avatar-xl rounded img-thumbnail">
                    <div>
                        <span class="fw-bold"><?php echo $acc_for == 'bank' ? $labels[1][2] : $labels[0][2]; ?></span>
                        <span><?php echo $khaata['owner_name']; ?></span>
                        <?php $details1 = ['indexes' => $khaata['indexes'], 'vals' => $khaata['vals']];
                        displayKhaataDetails($details1); ?>
                    </div>
                </div>
            </div>
            <?php $kds = fetch('khaata_details', array('khaata_id' => $khaat_id, 'is_active' => 1));
            if (mysqli_num_rows($kds) > 0) { ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <?php $aaa = array('comp_name' => 'comp_name1', 'country_id' => 'country_id', 'city' => 'city1', 'address' => 'address1', 'report' => 'report1');
                        $bbb = array('comp_name' => 'comp_name2', 'ac_no' => 'ac_no', 'owner_name' => 'owner_name', 'bank_name' => 'bank_name', 'ifsc_code' => 'ifsc_code', 'country_id' => 'country_id', 'city' => 'city2', 'address' => 'address2', 'report' => 'report2');
                        if ($khaata['acc_for'] != 'bank') { // only Client & Agnet can have these detials
                            $head1 = array('Type', 'Company', 'Mobile', 'Email', 'City', 'Address', 'NTN', 'Date', 'Sale Tax#', 'Consignee', 'Reebok Id', 'Passport', 'Report'); ?>
                            <?php $noo = 1;
                            $hide_items_array = array('static_type', 'khaata_id_hidden', 'acc_for_hidden'); ?>
                            <div class="d-flex align-items-center justify-content-between">
                                <ul class="nav nav-tabs" role="tablist">
                                    <?php $static_types1 = fetch('static_types', array('type_for' => 'khaata'));
                                    while ($static_type = mysqli_fetch_assoc($static_types1)) {
                                        $active_link = $static_type['type_name'] == "Extra" ? 'active' : ''; ?>
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo $active_link; ?>" data-bs-toggle="tab"
                                               href="#<?php echo $static_type['type_name']; ?>" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span
                                                    class="d-none d-sm-block"><?php echo $static_type['details']; ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <?php $static_types2 = fetch('static_types', array('type_for' => 'khaata'));
                                while ($static_type = mysqli_fetch_assoc($static_types2)) {
                                    $active_pane = $static_type['type_name'] == "Extra" ? 'active' : ''; ?>
                                    <div class="tab-pane <?php echo $active_pane; ?>"
                                         id="<?php echo $static_type['type_name']; ?>" role="tabpanel">
                                        <?php $khaata_details = fetch('khaata_details', array('khaata_id' => $khaat_id, 'is_active' => 1, 'static_type' => $static_type['type_name']));
                                        $x = 1;
                                        while ($details = mysqli_fetch_assoc($khaata_details)) { ?>
                                            <div class="card mb-2 rounded-0">
                                                <div class="card-body p-1">
                                                    <div class="row gx-0 text-uppercase fw-bold text-dark small">
                                                        <?php if ($static_type['type_name'] == "Bank") {
                                                            $keys = array_keys($bbb);
                                                            echo '<div class="col-md-3">';
                                                            echo 'BANK NAME<span class="fw-normal">' . $details[$keys[3]] . '</span><br>';
                                                            echo 'A/C TITLE<span class="fw-normal">' . $details[$keys[0]] . '</span><br>';
                                                            echo 'A/C NO<span class="fw-normal">' . $details[$keys[1]] . '</span>';
                                                            echo '</div>';
                                                            echo '<div class="col-md-3">';
                                                            echo 'ADDRESS<span class="fw-normal">' . $details[$keys[7]] . '</span><br>';
                                                            echo 'COUNTRY<span class="fw-normal">' . countryName($details[$keys[5]]) . '</span><br>';
                                                            echo 'CITY<span class="fw-normal">' . $details[$keys[6]] . '</span>';
                                                            echo '</div>';
                                                            echo '<div class="col-md-3">';
                                                            echo 'OWNER NAME<span class="fw-normal">' . $details[$keys[2]] . '</span><br>';
                                                            echo 'IFSC CODE<span class="fw-normal">' . $details[$keys[4]] . '</span><br>';
                                                            echo 'REPORT<span class="fw-normal">' . $details[$keys[8]] . '</span>';
                                                            echo '</div>';
                                                            $row_cols = 'row-cols-2';
                                                            $col_md = 'col-md-3';
                                                        } else {
                                                            $row_cols = 'row-cols-3';
                                                            $col_md = 'col-md-6';
                                                            $keys = array_keys($aaa);
                                                            echo '<div class="col-md-3">';
                                                            echo 'NAME<span class="fw-normal">' . $details[$keys[0]] . '</span><br>';
                                                            echo 'COUNTRY<span class="fw-normal">' . countryName($details[$keys[1]]) . '</span><br>';
                                                            echo 'CITY<span class="fw-normal">' . $details[$keys[2]] . '</span>';
                                                            echo '</div>';
                                                            echo '<div class="col-md-3">';
                                                            echo 'ADDRESS<span class="fw-normal">' . $details[$keys[3]] . '</span><br>';
                                                            echo 'REPORT<span class="fw-normal">' . $details[$keys[4]] . '</span>';
                                                            echo '</div>';
                                                        }
                                                        echo '<div class="' . $col_md . '"><div class="row gx-0 ' . $row_cols . '">';
                                                        $details = ['indexes' => $details['indexes'], 'vals' => $details['vals']];
                                                        displayKhaataDetails($details);
                                                        echo '</div></div>'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $x++;
                                        } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else {
                //echo '<div class="mt-3 h4 text-danger text-center">No more Data to show here. </div>';
            } ?>
        </div>
    </div>
<?php } ?>

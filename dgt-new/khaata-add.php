<?php $page_title = 'Account Entry';
$back_page_url = 'khaata';
$pageURL = 'khaata-add';
include("header.php");
global $connect;
$id = $d_id = $branch_id = $cat_id = 0;
$sr = getAutoIncrement('khaata');
$user_date = date("Y-m-d");
$acc_for = $khaata_no = $khaata_name = $email = $phone = '';

/*contact details vars*/
$person_details = ['full_name' => '', 'father_name' => '', 'gender' => '', 'identity' => '', 'idn_no' => '', 'idn_reg' => '', 'idn_expiry' => '', 'idn_country' => '', 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'postcode' => '', 'mobile' => '', 'phone' => '', 'whatsapp' => ''];
$company_details = ['owner_name' => '', 'company_name' => '', 'business_title' => '', 'indexes1' => [], 'vals1' => [], 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'indexes2' => [], 'vals2' => []];
$warehouse_details = ['owner_name' => '', 'warehouse_name' => '', 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'indexes3' => [], 'vals3' => []];
$bank_details = ['acc_no' => '', 'acc_name' => '', 'company' => '', 'iban' => '', 'branch_code' => '', 'currency' => '', 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'indexes4' => [], 'vals4' => []];
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sr = $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('khaata', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $user_date = $record['created_at'];
    $acc_for = $record['acc_for'];
    $branch_id = $record['branch_id'];
    $cat_id = $record['cat_id'];
    $khaata_no = $record['khaata_no'];
    $khaata_name = $record['khaata_name'];
    $email = $record['email'];
    $phone = $record['phone'];
    $contact_details = json_decode($record['contact_details']);
    if (!empty($contact_details)) {
        $person_details = [
            'full_name' => $contact_details->full_name,
            'father_name' => $contact_details->father_name,
            'gender' => $contact_details->gender ?? '',
            'identity' => $contact_details->identity ?? '',
            'idn_no' => $contact_details->idn_no,
            'idn_reg' => $contact_details->idn_reg,
            'idn_expiry' => $contact_details->idn_expiry,
            'idn_country' => $contact_details->idn_country,
            'country' => $contact_details->country,
            'state' => $contact_details->state,
            'city' => $contact_details->city,
            'address' => $contact_details->address,
            'postcode' => $contact_details->postcode,
            'mobile' => $contact_details->mobile,
            'phone' => $contact_details->phone,
            'whatsapp' => $contact_details->whatsapp
        ];
    }

    // $bank_details = json_decode($record['bank_details']);
    $bank_details = json_decode(decodeSpecialCharacters($record['bank_details']));

    if (!empty($bank_details)) {
        $keys = ['acc_no', 'acc_name', 'company', 'iban', 'branch_code', 'currency', 'country', 'state', 'city', 'address', 'indexes4', 'vals4'];
        $bank_details = array_filter(get_object_vars($bank_details), fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);
    }

    if (isset($_GET['d_id']) && $_GET['d_id'] > 0) {
        $d_id = mysqli_real_escape_string($connect, $_GET['d_id']);
        $khaata_details_comp = fetch('khaata_details', array('id' => $d_id, 'type' => 'company'));
        if (mysqli_num_rows($khaata_details_comp) > 0) {
            $details_comp = mysqli_fetch_assoc($khaata_details_comp);
            $json_data = json_decode($details_comp['json_data']);
            $company_details = [
                'owner_name' => $json_data->owner_name,
                'company_name' => $json_data->company_name,
                'business_title' => $json_data->business_title,
                'indexes1' => $json_data->indexes1,
                'vals1' => $json_data->vals1,
                'country' => $json_data->country,
                'state' => $json_data->state,
                'city' => $json_data->city,
                'address' => $json_data->address,
                'indexes2' => $json_data->indexes2,
                'vals2' => $json_data->vals2
            ];
        }
        $khaata_details_ware = fetch('khaata_details', array('id' => $d_id, 'type' => 'warehouse'));
        if (mysqli_num_rows($khaata_details_ware) > 0) {
            $details_ware = mysqli_fetch_assoc($khaata_details_ware);
            $json_data2 = json_decode($details_ware['json_data']);
            $warehouse_details = [
                'owner_name' => $json_data2->owner_name,
                'warehouse_name' => $json_data2->warehouse_name,
                'country' => $json_data2->country,
                'state' => $json_data2->state,
                'city' => $json_data2->city,
                'address' => $json_data2->address,
                'indexes3' => $json_data2->indexes3,
                'vals3' => $json_data2->vals3
            ];
        }
    }
}
$types_array = array('bank', 'client', 'agent', 'warehouse');
/* $types_array = array('client' => 'checked', 'agent' => '', 'bank' => '');
if (isset($_GET['acc_for']) && array_key_exists($_GET['acc_for'], $types_array)) {
    $acc_for = mysqli_real_escape_string($connect, $_GET['acc_for']);
    if ($acc_for == 'agent') {
        $types_array['client'] = '';
        $types_array['agent'] = 'checked';
    } else if ($acc_for == 'bank') {
        $types_array['client'] = '';
        $types_array['bank'] = 'checked';
    } else if ($acc_for == 'client') {
        $types_array['client'] = 'checked';
        $types_array['agent'] = '';
        $types_array['bank'] = '';
    }
}*/
$countries = [];
$countryQ = mysqli_query($connect, "SELECT * FROM countries");
while ($cn = mysqli_fetch_assoc($countryQ)) {
    $countries[] = [
        'id' => $cn['id'],
        'name' => $cn['name'],
        'code' => $cn['code']
    ];
}
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" onsubmit="return confirm('Are you sure to save data?');"
            enctype="multipart/form-data" class="table-form">
            <div class="d-flex align-items-center justify-content-between mb-md-2 flex-wrap">
                <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
                <div>
                    <div class="input-group">
                        <label for="client" class="mb-0">A/c for:</label>
                        <div class="form-control d-flex bg-white">
                            <?php foreach ($types_array as $item) {
                                if ($acc_for != '') {
                                    $acc_for_checked = $item == $acc_for ? 'checked' : '';
                                } else {
                                    $acc_for_checked = 'checked';
                                }
                                echo '<div class="form-check form-check-inline">';
                                echo '<input type="radio" id="' . $item . '" name="acc_for" class="form-check-input acc_for" value="' . $item . '" ' . $acc_for_checked . '>';
                                echo '<label class="form-check-label" for="' . $item . '">' . ucfirst($item) . '</label>';
                                echo '</div>';
                            } ?>
                        </div>
                    </div>
                </div>
                <div>
                    <?php echo backUrl($back_page_url); ?>
                    <?php echo addNew('khaata-add', '', 'btn-sm'); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 border-end">
                            <div class="row gx-1 gy-4">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="khaata_no">A/C No.</label>
                                        <input id="khaata_no" name="khaata_no" class="form-control" required
                                            autofocus
                                            value="<?php echo $khaata_no; ?>">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <label for="khaata_name">A/c. Name</label>
                                        <input type="text" id="khaata_name" name="khaata_name" class="form-control"
                                            value="<?php echo $khaata_name; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="phone">Phone</label>
                                        <input id="phone" name="phone" class="form-control"
                                            value="<?php echo $phone; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="branch_id">BRANCH</label>
                                        <select id="branch_id" name="branch_id" class="form-select branch_id"
                                            required>
                                            <option hidden value="">Select</option>
                                            <?php $branches = fetch('branches');
                                            while ($branch = mysqli_fetch_assoc($branches)) {
                                                $b_sel = $branch['id'] == $branch_id ? 'selected' : '';
                                                echo '<option ' . $b_sel . ' value="' . $branch['id'] . '">' . $branch['b_code'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="cat_id">CATEGORY</label>
                                        <select class="form-select cat_id" name="cat_id" id="cat_id" required>
                                            <option hidden value="">Select</option>
                                            <?php $run_query = fetch('cats', array('branch_id' => $branch_id));
                                            echo '<option selected disabled hidden value="">Choose</option>';
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $cat_sel = $row['id'] == $cat_id ? 'selected' : '';
                                                echo '<option ' . $cat_sel . ' value=' . $row['id'] . '>' . $row['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-between mb-md-4">
                                <div><b>Sr#: </b> <?php echo $sr; ?></div>
                                <div><b>Date: </b> <?php echo $user_date; ?></div>
                            </div>
                            <div class="row align-items-center">
                                <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                                <div class="col-md-4">
                                    <button name="recordSubmit" id="recordSubmit" type="submit" class="btn btn-dark w-100">
                                        Submit
                                    </button>
                                </div>
                                <?php if ($id > 0) { ?>
                                    <div class="col-md-8">
                                        <label for="modalSelector">Add New Details</label>
                                        <select class="form-select form-select-sm" id="modalSelector">
                                            <option value="" selected>Select What to Add?</option>
                                            <?php if ($acc_for == 'bank') { ?>
                                                <option value="#bankDetails">Bank Details</option>
                                            <?php } else { ?>
                                                <option value="#bankDetails">Bank Details</option>
                                                <option value="#contactDetails">Contact Details</option>
                                                <option value="#companyDetails">Company Details</option>
                                                <option value="#warehouseDetails">Warehouse Details</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="card bg-light mt-2">
            <div class="row">
                <div class="col-md-2 order-md-1">
                    <div class="nav mt-2 flex-md-column sticky-top nav-pills me-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <?php // if ($acc_for == 'bank') { 
                        ?>
                        <button class="nav-link- btn-sm btn btn-light active" id="v-pills-bank-tab"
                            data-bs-toggle="pill" data-bs-target="#v-pills-bank" type="button" role="tab"
                            aria-controls="v-pills-bank"
                            aria-selected="false">Bank Details
                        </button>
                        <?php //  } else { 
                        ?>
                        <button class="nav-link- btn-sm btn btn-light" id="v-pills-contact-tab"
                            data-bs-toggle="pill" data-bs-target="#v-pills-contact" type="button" role="tab"
                            aria-controls="v-pills-contact"
                            aria-selected="true">Contact Details
                        </button>
                        <button class="nav-link- btn-sm btn btn-light" id="v-pills-company-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#v-pills-company" type="button" role="tab"
                            aria-controls="v-pills-company"
                            aria-selected="false">Company Details
                        </button>
                        <button class="nav-link- btn-sm btn btn-light" id="v-pills-warehouse-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#v-pills-warehouse" type="button" role="tab"
                            aria-controls="v-pills-warehouse"
                            aria-selected="false">Warehouse Details
                        </button>
                        <?php // } 
                        ?>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card border-top-0 border-bottom-0">
                        <div class="card-body">
                            <div class="tab-content" id="v-pills-tabContent">
                                <?php // if ($acc_for == 'bank') { 
                                ?>
                                <div class="tab-pane fade show active" id="v-pills-bank" role="tabpanel"
                                    aria-labelledby="v-pills-bank-tab" tabindex="0">
                                    <?php if (!empty($bank_details['acc_no'])) { ?>
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">A/C NO.</span><?php echo $bank_details['acc_no']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">A/C NAME</span><?php echo $bank_details['acc_name']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">COMPANY</span><?php echo $bank_details['company']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">IBAN#</span><?php echo $bank_details['iban']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">BRANCH CODE</span><?php echo $bank_details['branch_code']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">CURRENCY</span><?php echo $bank_details['currency']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">COUNTRY</span><?php echo $bank_details['country']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">STATE</span><?php echo $bank_details['state']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">CITY</span><?php echo ucfirst($bank_details['city']); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><span
                                                                class="me-1 fw-bold">ADDRESS</span><?php echo $bank_details['address']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php echo '<tr>';
                                                    if (isset($bank_details['indexes4'])) {
                                                        foreach ($bank_details['indexes4'] as $index => $value) {
                                                            echo '<td><b>' . $value . '</b> ' . $bank_details['vals4'][$index] . '</td>';
                                                        }
                                                    }
                                                    echo '</tr>'; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else {
                                        echo '<div class="text-center">No data to show</div>';
                                    } ?>
                                </div>
                                <?php // } else { 
                                ?>
                                <div class="tab-pane fade show" id="v-pills-contact" role="tabpanel"
                                    aria-labelledby="v-pills-contact-tab" tabindex="0">
                                    <?php if (!empty($person_details['full_name'])) { ?>
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">NAME</span><?php echo $person_details['full_name']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">FATHER NAME</span><?php echo $person_details['father_name']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">GENDER</span><?php echo ucfirst($person_details['gender']); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <span class="me-1 fw-bold">IDENTITY</span><?php echo $person_details['identity']; ?>
                                                            <span class="me-1 ms-3 fw-bold">No.</span><?php echo $person_details['idn_no']; ?>
                                                            <span class="me-1 ms-3 fw-bold">REGISTRATION DATE</span><?php echo $person_details['idn_reg']; ?>
                                                            <span class="me-1 ms-3 fw-bold">EXPIRY DATE</span><?php echo $person_details['idn_expiry']; ?>
                                                            <span class="me-1 ms-3 fw-bold">COUNTRY</span><?php echo $person_details['idn_country']; ?>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">COUNTRY</span><?php echo $person_details['country']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">STATE</span><?php echo $person_details['state']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">CITY</span><?php echo ucfirst($person_details['city']); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><span
                                                                class="me-1 fw-bold">ADDRESS</span><?php echo $person_details['address']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">POST CODE</span><?php echo $person_details['postcode']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">MOBILE</span><?php echo $person_details['mobile']; ?>
                                                        </td>
                                                        <td>
                                                            <span class="me-1 fw-bold">PHONE</span><?php echo ucfirst($person_details['phone']); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="me-1 fw-bold">WHATSAPP</span><?php echo ucfirst($person_details['whatsapp']); ?>
                                                        </td>
                                                        <td colspan="2"></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else {
                                        echo '<div class="text-center">Contact details not found</div>';
                                    } ?>
                                </div>
                                <div class="tab-pane fade" id="v-pills-company" role="tabpanel"
                                    aria-labelledby="v-pills-company-tab" tabindex="0">
                                    <?php $details_query = fetch('khaata_details', array('khaata_id' => $id, 'type' => 'company'));
                                    if (mysqli_num_rows($details_query) > 0) { ?>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <!--<thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Owner Name</th>
                                                        <th>Company Name</th>
                                                        <th>Business</th>
                                                        <th>Country</th>
                                                    </tr>
                                                    </thead>-->
                                                <tbody>
                                                    <?php $x = 1;
                                                    while ($row = mysqli_fetch_array($details_query)) {
                                                        $row_data = json_decode($row['json_data']);
                                                        echo '<tr><table class="table table-borderless table-sm">';

                                                        echo '<tr>';
                                                        //echo '<td>' . $x . '</td>';
                                                        echo '<td><a class="text-dark" href="khaata-add?id=' . $id . '&d_id=' . $row['id'] . '&type=' . $row['type'] . '"><b>OWNER NAME </b>' . $row_data->owner_name . '</a></td>';
                                                        echo '<td><b>COMPANY NAME</b> ' . $row_data->company_name . '</td>';
                                                        echo '<td><b>BUSINESS TITLE</b> ' . $row_data->business_title . '</td>';
                                                        echo '</tr>';
                                                        echo '<tr>';
                                                        if (isset($row_data->indexes1)) {
                                                            foreach ($row_data->indexes1 as $index => $value) {
                                                                echo '<td><b>' . $value . '</b> ' . $row_data->vals1[$index] . '</td>';
                                                            }
                                                        }
                                                        echo '</tr>';
                                                        echo '<tr>';
                                                        echo '<td><b>COUNTRY</b> ' . $row_data->country . '</td>';
                                                        echo '<td><b>STATE</b> ' . $row_data->state . '</td>';
                                                        echo '<td><b>CITY</b> ' . $row_data->city . '</td>';
                                                        echo '</tr>';
                                                        echo '<tr>';
                                                        echo '<td colspan="3"><b>ADDRESS</b> ' . $row_data->address . '</td>';
                                                        echo '</tr>';
                                                        echo '<tr>';
                                                        if (isset($row_data->indexes2)) {
                                                            foreach ($row_data->indexes2 as $index => $value) {
                                                                echo '<td><b>' . $value . '</b> ' . $row_data->vals2[$index] . '</td>';
                                                            }
                                                        }
                                                        echo '</tr>';
                                                        echo '</table></tr>';
                                                        $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else {
                                        echo '<div class="text-center">Company details not found</div>';
                                    } ?>
                                </div>
                                <div class="tab-pane fade" id="v-pills-warehouse" role="tabpanel"
                                    aria-labelledby="v-pills-warehouse-tab" tabindex="0">
                                    <?php $details_query2 = fetch('khaata_details', array('khaata_id' => $id, 'type' => 'warehouse'));
                                    if (mysqli_num_rows($details_query2) > 0) { ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Owner Name</th>
                                                        <th>Warehouse Name</th>
                                                        <th>Country</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $x = 1;
                                                    while ($row = mysqli_fetch_array($details_query2)) {
                                                        $row_data = json_decode($row['json_data']);
                                                        echo '<tr>';
                                                        echo '<td>' . $x . '</td>';
                                                        echo '<td><a class="text-dark" href="khaata-add?id=' . $id . '&d_id=' . $row['id'] . '&type=' . $row['type'] . '">' . $row_data->owner_name . '</a></td>';
                                                        echo '<td>' . $row_data->warehouse_name . '</td>';
                                                        echo '<td>' . $row_data->country . '</td>';
                                                        echo '</tr>';
                                                        $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else {
                                        echo '<div class="text-center">Warehouse details not found</div>';
                                    } ?>
                                </div>
                                <?php // } 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
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
<!--<script src="assets/js/input-repeater-contacts.js"></script>-->
<script type="text/javascript">
    $(document).ready(function() {
        //var selectedBranchId = $('#branch_id').find(":selected").val();
        //populateCats(selectedBranchId);


        $('.branch_id').on('change', function() {
            var branch_id = $(this).val();
            populateCats(branch_id);
        });
    });

    function populateCats(branch_id) {
        if (branch_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_branch_cats.php',
                data: {
                    branch_id: branch_id
                },
                success: function(html) {
                    $('.cat_id').html(html);
                },
                error: function(xhr, status, error) {
                    //console.error("AJAX call failed:", status, error); // Debugging line
                }
            });
        } else {
            $('.cat_id').html('<option value="">Select branch</option>');
        }
    }
</script>
<!--<script>
    $(function () {
        $('.acc_for:not(:checked)').on('change', function () {
            let acc_for = this.value;
            let url = '';
            window.$_GET = location.search.substr(1).split("&").reduce((o, i) => (u = decodeURIComponent, [k, v] = i.split("="), o[u(k)] = v && u(v), o), {});
            if (acc_for) {
                url = "khaata-add?acc_for=" + acc_for;
                if ($_GET.hasOwnProperty('id')) {
                    url += '&id=' + $_GET.id;
                }
                window.location = url;
            }
            return false;
        });
    });
</script>-->
<?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['recordSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $acc_for = mysqli_real_escape_string($connect, $_POST['acc_for']);
    $data = array(
        'acc_for' => $acc_for,
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'khaata_name' => mysqli_real_escape_string($connect, $_POST['khaata_name']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'branch_id' => $_POST['branch_id'],
        'cat_id' => $_POST['cat_id']
    );
    if ($hidden_id > 0) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('khaata', $data, array('id' => $hidden_id));
        $pageURL .= "?id=" . $hidden_id;
        if ($done) {
            $msg_array['msg'] = ucfirst($acc_for) . ' Account Successfully Updated.';
            $msg_array['type'] = 'success';
        }
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $done = insert('khaata', $data);
        if ($done) {
            $insert_id = $connect->insert_id;
            $pageURL .= "?id=" . $insert_id;
            $msg_array['msg'] = ucfirst($acc_for) . ' New Account Successfully added.';
            $msg_array['type'] = 'success';
        }
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
} ?>
<?php if (isset($_POST['deleteKhaataSubmit'])) {
    $khaata_id_hidden = mysqli_real_escape_string($connect, $_POST['khaata_id_hidden']);
    $khaata_no_hidden = mysqli_real_escape_string($connect, $_POST['khaata_no_hidden']);
    $del = mysqli_query($connect, "DELETE FROM khaata WHERE id = '$khaata_id_hidden'");
    if ($del) {
        message('success', 'khaata', 'A/c. ' . $khaata_no_hidden . ' deleted successfully.');
    } else {
        message('danger', 'khaata', 'A/c. ' . $khaata_no_hidden . ' not deleted.');
    }
} ?>
<div class="modal fade" id="contactDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="contactDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="contactDetailsLabel">Contact Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table-form">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="full_name" class="form-label">Name</label>
                                <input value="<?php echo $person_details['full_name']; ?>" id="full_name"
                                    name="full_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="father_name" class="form-label">Father Name</label>
                                <input value="<?php echo $person_details['father_name']; ?>" id="father_name"
                                    name="father_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" name="gender" id="gender">
                                    <option selected hidden disabled="">Select</option>
                                    <?php $genders = array('male', 'female', 'other');
                                    foreach ($genders as $gender) {
                                        $genderSelected = $gender == $person_details['gender'] ? 'selected' : '';
                                        echo '<option ' . $genderSelected . ' value="' . $gender . '">' . ucfirst($gender) . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="identity" class="form-label">Identity</label>
                                <select class="form-select" name="identity" id="identity">
                                    <option selected hidden disabled="">Select</option>
                                    <?php $identities = array('passport', 'cnic', 'uae');
                                    foreach ($identities as $identity) {
                                        $idSelected = $identity == $person_details['identity'] ? 'selected' : '';
                                        echo '<option ' . $idSelected . ' value="' . $identity . '">' . ucfirst($identity) . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_no" class="form-label">No</label>
                                        <input value="<?php echo $person_details['idn_no']; ?>" id="idn_no"
                                            name="idn_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_reg" class="form-label">Reg.</label>
                                        <input type="date" value="<?php echo $person_details['idn_reg']; ?>"
                                            id="idn_reg" name="idn_reg" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_expiry" class="form-label">Expiry</label>
                                        <input type="date" value="<?php echo $person_details['idn_expiry']; ?>"
                                            id="idn_expiry" name="idn_expiry" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_country" class="form-label">Country</label>
                                        <!-- <input value="<?php echo $person_details['idn_country']; ?>"> -->
                                        <select id="idn_country" name="idn_country" class="form-select">
                                            <option value="" selected disabled>Choose</option>
                                            <?php
                                            foreach ($countries as $country) {
                                                echo '<option value="' . $country['name'] . ' ' . ($country['name'] === $person_details['idn_country'] ? 'selected' : '') . '">' . $country['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="country" class="form-label">Country</label>
                                <!-- <input value="<?php echo $person_details['country']; ?>" id="country" name="country" 
                                    class="form-control"> -->
                                <select id="country" name="country" class="form-select">
                                    <option value="" selected disabled>Choose</option>
                                    <?php
                                    foreach ($countries as $country) {
                                        echo '<option value="' . $country['name'] . ' ' . ($country['name'] === $person_details['country'] ? 'selected' : '') . '">' . $country['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="state" class="form-label">State</label>
                                <input value="<?php echo $person_details['state']; ?>" id="state" name="state"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="city" class="form-label">City</label>
                                <input value="<?php echo $person_details['city']; ?>" id="city" name="city"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="address" class="form-label">Address</label>
                                <input value="<?php echo $person_details['address']; ?>" id="address" name="address"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="postcode" class="form-label">Postcode</label>
                                <input value="<?php echo $person_details['postcode']; ?>" id="postcode"
                                    name="postcode" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input value="<?php echo $person_details['mobile']; ?>" id="mobile" name="mobile"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input value="<?php echo $person_details['phone']; ?>" id="phone" name="phone"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input value="<?php echo $person_details['whatsapp']; ?>" id="whatsapp"
                                    name="whatsapp" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="contactDetailsSubmit" class="btn btn-dark">Save</button>
                </div>
            </div>
            <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
        </form>
    </div>
</div>
<div class="modal fade" id="companyDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="companyDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="companyDetailsLabel">Company Details</h1>
                    <a href="<?php echo $pageURL . '?id=' . $id; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body table-form">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="owner_name" class="form-label">Owner Name</label>
                                <input value="<?php echo $company_details['owner_name'] ?>" id="owner_name"
                                    name="owner_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input value="<?php echo $company_details['company_name'] ?>" id="company_name"
                                    name="company_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="business_title" class="form-label">Business Title</label>
                                <input value="<?php echo $company_details['business_title'] ?>" id="business_title"
                                    name="business_title" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-1 my-3 table-container" data-instance="1">
                        <div class="col-md">
                            <table class="table table-borderless mb-0 contactsTable">
                                <tbody class="row">
                                    <?php $arrayNumber = 0;
                                    foreach ($company_details['indexes1'] as $index => $value) { ?>
                                        <tr class="col-md-6 contact_row_<?php echo $arrayNumber; ?>">
                                            <td onclick="removeContactRow(this)">
                                                <i class="fa fa-close fa-2xl- btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                                            </td>
                                            <td class="w-50">
                                                <select name="indexes1[]" class="form-select contact_indexes">
                                                    <?php $static_types = fetch('static_types', array('type_for' => 'contacts2'));
                                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                        $st_sel = $static_type['type_name'] == $value ? 'selected' : '';
                                                        echo '<option ' . $st_sel . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td class="w-50">
                                                <input name="vals1[]" required placeholder="Value <?php echo $index + 1; ?>"
                                                    class="form-control contact_vals"
                                                    value="<?php echo $company_details['vals1'][$index] ?>">
                                            </td>
                                        </tr>
                                    <?php $arrayNumber++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-auto">
                            <div class="btn btn-outline-secondary py-0 mt-1 -btn-sm addContactRow"
                                data-url="ajax/fetchStaticTypesForContacts2.php" data-loading-text="Loading...">
                                <i class="fa fa-plus-circle"></i> New
                            </div>
                        </div>
                    </div>
                    <div class="row gx-1 gy-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="company_country" class="form-label">Country</label>
                                <!-- <input value="<?php echo $company_details['country'] ?>" id="company_country"
                                    name="country" class="form-control"> -->
                                <select id="company_country"
                                    name="country" class="form-select">
                                    <option value="" selected disabled>Choose</option>
                                    <?php
                                    foreach ($countries as $country) {
                                        echo '<option value="' . $country['name'] . ' ' . ($country['name'] === $company_details['country'] ? 'selected' : '') . '">' . $country['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="company_state" class="form-label">State</label>
                                <input value="<?php echo $company_details['state'] ?>" id="company_state" name="state"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="company_city" class="form-label">City</label>
                                <input value="<?php echo $company_details['city'] ?>" id="company_city" name="city"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="company_address" class="form-label">Address</label>
                                <input value="<?php echo $company_details['address'] ?>" id="company_address"
                                    name="address" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row gx-1 my-3 table-container" data-instance="2">
                        <div class="col-md">
                            <table class="table table-borderless mb-0 contactsTable">
                                <tbody class="row">
                                    <?php $arrayNumber = 0;
                                    if (!empty($company_details['indexes2'])) {
                                        foreach ($company_details['indexes2'] as $index => $value) { ?>
                                            <tr class="col-md-6 contact_row_<?php echo $arrayNumber; ?>">
                                                <td onclick="removeContactRow(this)">
                                                    <i class="fa fa-close fa-2xl- btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                                                </td>
                                                <td class="w-50">
                                                    <select name="indexes2[]" class="form-select contact_indexes">
                                                        <?php $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                                        while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                            $st_sel2 = $static_type['type_name'] == $value ? 'selected' : '';
                                                            echo '<option ' . $st_sel2 . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </td>
                                                <td class="w-50">
                                                    <input name="vals2[]" required placeholder="Value <?php echo $index + 1; ?>"
                                                        class="form-control contact_vals"
                                                        value="<?php echo $company_details['vals2'][$index] ?>">
                                                </td>
                                            </tr>
                                    <?php $arrayNumber++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-auto">
                            <div class="btn btn-outline-secondary py-0 mt-1 -btn-sm addContactRow"
                                data-url="ajax/fetchStaticTypesForContacts.php" data-loading-text="Loading...">
                                <i class="fa fa-plus-circle"></i> New
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="companyDetailsSubmit" class="btn btn-dark">Submit</button>
                </div>
            </div>
            <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
            <input type="hidden" name="hidden_id_details" value="<?php echo $d_id; ?>">
            <input type="hidden" name="hidden_type" value="company">
        </form>
    </div>
</div>
<div class="modal fade" id="warehouseDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="warehouseDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="warehouseDetailsLabel">Warehouse Details</h1>
                    <a href="<?php echo $pageURL . '?id=' . $id; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body table-form">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="owner_name" class="form-label">Owner Name</label>
                                <input value="<?php echo $warehouse_details['owner_name'] ?>" id="owner_name"
                                    name="owner_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="warehouse_name" class="form-label">Warehouse Name</label>
                                <input value="<?php echo $warehouse_details['warehouse_name'] ?>" id="warehouse_name"
                                    name="warehouse_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="ware_country" class="form-label">Country</label>
                                <!-- <input value="<?php echo $warehouse_details['country'] ?>" id="ware_country"
                                    name="country" class="form-control"> -->
                                <select d="ware_country"
                                    name="country" class="form-select">
                                    <option value="" selected disabled>Choose</option>
                                    <?php
                                    foreach ($countries as $country) {
                                        echo '<option value="' . $country['name'] . ' ' . ($country['name'] === $warehouse_details['country'] ? 'selected' : '') . '">' . $country['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="ware_state" class="form-label">State</label>
                                <input value="<?php echo $warehouse_details['state'] ?>" id="ware_state" name="state"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="ware_city" class="form-label">City</label>
                                <input value="<?php echo $warehouse_details['city'] ?>" id="ware_city" name="city"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="ware_address" class="form-label">Address</label>
                                <input value="<?php echo $warehouse_details['address'] ?>" id="ware_address"
                                    name="address" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row gx-1 my-3 table-container" data-instance="3">
                        <div class="col-md">
                            <table class="table table-borderless mb-0 contactsTable">
                                <tbody class="row">
                                    <?php $arrayNumber = 0;
                                    foreach ($warehouse_details['indexes3'] as $index => $value) { ?>
                                        <tr class="col-md-6 contact_row_<?php echo $arrayNumber; ?>">
                                            <td onclick="removeContactRow(this)">
                                                <i class="fa fa-close fa-2xl- btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                                            </td>
                                            <td class="w-50">
                                                <select name="indexes3[]" class="form-select contact_indexes">
                                                    <?php $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                        $st_sel3 = $static_type['type_name'] == $value ? 'selected' : '';
                                                        echo '<option ' . $st_sel3 . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td class="w-50">
                                                <input name="vals3[]" required placeholder="Value <?php echo $index + 1; ?>"
                                                    class="form-control contact_vals"
                                                    value="<?php echo $warehouse_details['vals3'][$index] ?>">
                                            </td>
                                        </tr>
                                    <?php $arrayNumber++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-auto">
                            <div class="btn btn-outline-secondary py-0 mt-1 addContactRow"
                                data-url="ajax/fetchStaticTypesForContacts.php" data-loading-text="Loading...">
                                <i class="fa fa-plus-circle"></i> New
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="companyDetailsSubmit" class="btn btn-dark">Submit</button>
                </div>
            </div>
            <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
            <input type="hidden" name="hidden_id_details" value="<?php echo $d_id; ?>">
            <input type="hidden" name="hidden_type" value="warehouse">
        </form>
    </div>
</div>
<div class="modal fade" id="bankDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bankDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bankDetailsLabel">Bank Details</h1>
                    <a href="<?php echo $pageURL . '?id=' . $id; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body table-form">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="acc_no" class="form-label">A/c No.</label>
                                <input value="<?= isset($bank_details['acc_no']) ? $bank_details['acc_no'] : '' ?>" id="acc_no"
                                    name="acc_no" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="acc_name" class="form-label">A/c Name</label>
                                <input value="<?= isset($bank_details['acc_name']) ? $bank_details['acc_name'] : '' ?>" id="acc_name"
                                    name="acc_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="b_company" class="form-label">Company</label>
                                <input value="<?= isset($bank_details['company']) ? $bank_details['company'] : '' ?>" id="b_company"
                                    name="company" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="iban" class="form-label">IBAN#</label>
                                <input value="<?= isset($bank_details['iban']) ? $bank_details['iban'] : '' ?>" id="iban" name="iban"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="branch_code" class="form-label">Branch Code</label>
                                <input value="<?= isset($bank_details['branch_code']) ? $bank_details['city'] : '' ?>" id="branch_code"
                                    name="branch_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="currency">Currency</label>
                                <select id="currency" name="currency" class="form-select" required>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $crr_sel3 = $crr['name'] == isset($bank_details['currency']) ? 'selected' : '';
                                        echo '<option ' . $crr_sel3 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="bank_country" class="form-label">Country</label>
                                <!-- <input value="<?= isset($bank_details['country']) ? $bank_details['country'] : '' ?>" id="bank_country"
                                    name="country" class="form-control"> -->
                                <select id="bank_country"
                                    name="country" class="form-select">
                                    <option value="" selected disabled>Choose</option>
                                    <?php
                                    foreach ($countries as $country) {
                                        echo '<option value="' . $country['name'] . ' ' . ($country['name'] === $bank_details['country'] ? 'selected' : '') . '">' . $country['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="bank_state" class="form-label">State</label>
                                <input value="<?= isset($bank_details['state']) ? $bank_details['state'] : '' ?>" id="bank_state" name="state"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="bank_city" class="form-label">City</label>
                                <input value="<?= isset($bank_details['city']) ? $bank_details['acc_no'] : '' ?>" id="bank_city" name="city"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="bank_address" class="form-label">Address</label>
                                <input value="<?= isset($bank_details['address']) ? $bank_details['address'] : '' ?>" id="bank_address"
                                    name="address" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row gx-1 my-3 table-container" data-instance="4">
                        <div class="col-md">
                            <table class="table table-borderless mb-0 contactsTable">
                                <tbody class="row">
                                    <?php $arrayNumber = 0;
                                    if (isset($bank_details['indexes4'])) {
                                        foreach ($bank_details['indexes4'] as $index => $value) { ?>
                                            <tr class="col-md-6 contact_row_<?php echo $arrayNumber; ?>">
                                                <td onclick="removeContactRow(this)">
                                                    <i class="fa fa-close fa-2xl- btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                                                </td>
                                                <td class="w-50">
                                                    <select name="indexes4[]" class="form-select contact_indexes">
                                                        <?php $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                                        while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                            $st_sel3 = $static_type['type_name'] == $value ? 'selected' : '';
                                                            echo '<option ' . $st_sel3 . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </td>
                                                <td class="w-50">
                                                    <input name="vals4[]" required placeholder="Value <?php echo $index + 1; ?>"
                                                        class="form-control contact_vals"
                                                        value="<?php echo $bank_details['vals4'][$index] ?>">
                                                </td>
                                            </tr>
                                    <?php $arrayNumber++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-auto">
                            <div class="btn btn-outline-secondary py-0 mt-1 addContactRow"
                                data-url="ajax/fetchStaticTypesForContacts.php" data-loading-text="Loading...">
                                <i class="fa fa-plus-circle"></i> New
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="bankDetailsSubmit" class="btn btn-dark">Submit</button>
                </div>
            </div>
            <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
            <input type="hidden" name="hidden_id_details" value="<?php echo $d_id; ?>">
            <input type="hidden" name="hidden_type" value="warehouse">
        </form>
    </div>
</div>
<?php if (isset($_GET['d_id']) && $_GET['d_id'] > 0 && isset($_GET['type']) && in_array($_GET['type'], ['company', 'warehouse', 'bank'])) {
    $typeee = mysqli_real_escape_string($connect, $_GET['type']);
    if ($typeee == 'company') {
        echo "<script>jQuery(document).ready(function ($) {  $('#companyDetails').modal('show');});</script>";
    } elseif ($typeee == 'warehouse') {
        echo "<script>jQuery(document).ready(function ($) {  $('#warehouseDetails').modal('show');});</script>";
    } elseif ($typeee == 'bank') {
        echo "<script>jQuery(document).ready(function ($) {  $('#bankDetails').modal('show');});</script>";
    }
} ?>
<?php if (isset($_POST['contactDetailsSubmit'])) {
    $post = json_encode($_POST);
    $data = array('contact_details' => $post);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $done = update('khaata', $data, array('id' => $hidden_id));
    if ($done) {
        $msg_array['msg'] = 'Contact details Successfully Updated.';
        $msg_array['type'] = 'success';
    }
    messageNew($msg_array['type'], $pageURL . '?id=' . $hidden_id, $msg_array['msg']);
} ?>
<?php if (isset($_POST['companyDetailsSubmit'])) {
    $post = json_encode($_POST);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $hidden_id_details = mysqli_real_escape_string($connect, $_POST['hidden_id_details']);
    $hidden_type = mysqli_real_escape_string($connect, $_POST['hidden_type']);
    unset($_POST['companyDetailsSubmit']);
    unset($_POST['hidden_id']);
    unset($_POST['hidden_id_details']);
    unset($_POST['hidden_type']);
    $pageURL .= "?id=" . $hidden_id . '&type=' . $hidden_type;
    $data = array('json_data' => $post);

    if ($hidden_id_details > 0) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('khaata_details', $data, array('id' => $hidden_id_details));
        $pageURL .= "&d_id=" . $hidden_id_details;
        if ($done) {
            $msg_array['msg'] = ucfirst($hidden_type) . ' Successfully Updated.';
            $msg_array['type'] = 'success';
        }
    } else {
        $data['type'] = $hidden_type;
        $data['khaata_id'] = $hidden_id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $done = insert('khaata_details', $data);
        if ($done) {
            $insert_id = $connect->insert_id;
            $pageURL .= "&d_id=" . $insert_id;
            $msg_array['msg'] = ucfirst($hidden_type) . ' Successfully added.';
            $msg_array['type'] = 'success';
        }
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
} ?>
<?php if (isset($_POST['bankDetailsSubmit'])) {
    $post = json_encode($_POST, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG);
    $data = array('bank_details' => $post);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $done = update('khaata', $data, array('id' => $hidden_id));
    if ($done) {
        $msg_array['msg'] = 'Bank details Successfully Updated.';
        $msg_array['type'] = 'success';
    }
    messageNew($msg_array['type'], $pageURL . '?id=' . $hidden_id, $msg_array['msg']);
} ?>
<script>
    var staticOptionsCache = {};

    function loadStaticTypes(url, callback) {
        if (staticOptionsCache[url]) {
            callback(staticOptionsCache[url]);
        } else {
            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    staticOptionsCache[url] = response;
                    callback(response);
                }
            });
        }
    }

    function addContactRow(button) {
        var table = $(button).closest('.table-container').find('.contactsTable');
        var addButton = $(button);
        var url = addButton.data('url');
        var instance = $(button).closest('.table-container').data('instance');

        addButton.button("loading");

        var tableLength = table.find("tbody tr").length;
        var count = tableLength + 1;
        var arrayNumber = tableLength;

        addButton.button("reset");

        loadStaticTypes(url, function(staticOptions) {
            var tr = `
            <tr class="col-md-6 contact_row_${arrayNumber}">
                <td onclick="removeContactRow(this)"><i class="fa fa-close btn fs-5 text-danger ps-0 pe-1 pt-1"></i></td>
                <td class="w-50">
                    <select name="indexes${instance}[]" class="form-select contact_indexes">${staticOptions}</select>
                </td>
                <td class="w-50">
                    <input type="text" name="vals${instance}[]" required placeholder="Value ${count}" class="form-control contact_vals">
                </td>
            </tr>`;

            if (tableLength > 0) {
                table.find("tbody tr:last").after(tr);
            } else {
                table.find("tbody").append(tr);
            }
        });
    }

    function removeContactRow(button) {
        var row = $(button).closest('tr');
        var table = row.closest('table');
        var tableLength = table.find("tbody tr").length;
        if (tableLength > 1) {
            row.remove();
        } else {
            alert('error! Refresh the page again');
        }
    }
    $(document).ready(function() {
        // Handle the change event of the select input
        $('#modalSelector').on('change', function() {
            var modalTarget = $(this).val(); // Get the selected value (modal ID)
            if (modalTarget) {
                $(modalTarget).modal('show'); // Show the modal using Bootstrap
            }
            // Reset the dropdown to default after selection
            $(this).val('');
        });
    });

    $(document).ready(function() {
        $('.addContactRow').on('click', function() {
            addContactRow(this);
        });
    });
</script>
<?php require_once '../connection.php';
global $connect;
$staticType = $_POST['staticType'];
$khaata_id = $_POST['kid'];
$kd_id = $_POST['kdid'];
$action = $_POST['action'];
$aaa = array('comp_name' => 'comp_name1', 'country_id' => 'country_id', 'city' => 'city1', 'address' => 'address1', 'report' => 'report1');
$bbb = array('comp_name' => 'comp_name2', 'ac_no' => 'ac_no', 'owner_name' => 'owner_name', 'bank_name' => 'bank_name', 'ifsc_code' => 'ifsc_code', 'country_id' => 'country_id', 'city' => 'city2', 'address' => 'address2', 'report' => 'report2');
$ccc = array('comp_name' => 'comp_name3', 'country_id' => 'country_id', 'city' => 'city3', 'address' => 'address3', 'report' => 'report3');
if ($staticType) {
    $records = fetch('khaata', array('id' => $khaata_id));
    $record = mysqli_fetch_assoc($records);

    $action_is_add = true;
    $action_is_update = false;
    /*if ($action == 'add') {}*/
    if ($action == 'update') {
        $action_is_add = false;
        $action_is_update = true;
        $khaata_details = fetch('khaata_details', array('id' => $kd_id));
        $kd_data = mysqli_fetch_assoc($khaata_details);
    }
    $comp_name_kd = $action_is_add ? $record['comp_name'] : $kd_data['comp_name'];
    $country_id_kd = $action_is_add ? $record['country_id'] : $kd_data['country_id'];
    $city_kd = $action_is_add ? $record['city'] : $kd_data['city'];
    $address_kd = $action_is_add ? $record['address'] : $kd_data['address'];
    $report_kd = $action_is_add ? '' : $kd_data['report'];

    $ac_no_kd = $action_is_add ? '' : $kd_data['ac_no'];
    $owner_name_kd = $action_is_add ? '' : $kd_data['owner_name'];
    $bank_name_kd = $action_is_add ? '' : $kd_data['bank_name'];
    $ifsc_code = $action_is_add ? '' : $kd_data['ifsc_code'];

    $countries = fetch('countries');
    $c_options = array();
    while ($country = mysqli_fetch_assoc($countries)) {
        $c_options[$country['id']] = $country['name'];
    }
    $inputs1 = array(
        array('col' => 'col-md-6', 'id' => $aaa['comp_name'], 'value' => $comp_name_kd, 'label' => 'COMPANY', 'input_class' => 'form-control', 'input_type' => 'text'),
        array(
            'col' => 'col-md-3',
            'id' => $aaa['country_id'],
            'value' => $country_id_kd,// Pre-selected option value
            'label' => 'COUNTRY',
            'input_class' => 'form-control',
            'input_type' => 'select',
            'options' => $c_options,
        ),
        array('col' => 'col-md-3', 'id' => $aaa['city'], 'value' => $city_kd, 'label' => 'CITY', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-6', 'id' => $aaa['address'], 'value' => $address_kd, 'label' => 'ADDRESS', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-6', 'id' => $aaa['report'], 'value' => $report_kd, 'label' => 'REPORT', 'input_class' => 'form-control', 'input_type' => 'text'),
    );
    $inputs2 = array(
        array('col' => 'col-md-4', 'id' => $bbb['comp_name'], 'value' => $comp_name_kd, 'label' => 'TITLE', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-4', 'id' => $bbb['ac_no'], 'value' => $ac_no_kd, 'label' => 'A/C NO', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-4', 'id' => $bbb['owner_name'], 'value' => $owner_name_kd, 'label' => 'OWNER', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-4', 'id' => $bbb['bank_name'], 'value' => $bank_name_kd, 'label' => 'BANK', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-3', 'id' => $bbb['ifsc_code'], 'value' => $ifsc_code, 'label' => 'IFSC CODE', 'input_class' => 'form-control', 'input_type' => 'text'),
        array(
            'col' => 'col-md-3',
            'id' => $aaa['country_id'],
            'name' => 'country_id',
            'value' => $country_id_kd, // Pre-selected option value
            'label' => 'COUNTRY',
            'input_class' => 'form-control',
            'input_type' => 'select',
            'options' => $c_options,
        ),
        array('col' => 'col-md-2', 'id' => $bbb['city'], 'value' => $city_kd, 'label' => 'CITY', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-6', 'id' => $bbb['address'], 'value' => $address_kd, 'label' => 'ADDRESS', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-6', 'id' => $bbb['report'], 'value' => $report_kd, 'label' => 'REPORT', 'input_class' => 'form-control', 'input_type' => 'text'),
    );
    $inputs3 = array(
        array('col' => 'col-md-6', 'id' => $ccc['comp_name'], 'value' => $comp_name_kd, 'label' => 'NAME', 'input_class' => 'form-control', 'input_type' => 'text'),
        array(
            'col' => 'col-md-3',
            'id' => $aaa['country_id'],
            'name' => 'country_id',
            'value' => $country_id_kd, // Pre-selected option value
            'label' => 'COUNTRY',
            'input_class' => 'form-control',
            'input_type' => 'select',
            'options' => $c_options,
        ),
        array('col' => 'col-md-3', 'id' => $ccc['city'], 'value' => $city_kd, 'label' => 'CITY', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-6', 'id' => $ccc['address'], 'value' => $address_kd, 'label' => 'ADDRESS', 'input_class' => 'form-control', 'input_type' => 'text'),
        array('col' => 'col-md-6', 'id' => $ccc['report'], 'value' => $report_kd, 'label' => 'REPORT', 'input_class' => 'form-control', 'input_type' => 'text'),
    );
    switch ($staticType) {
        case 'Extra':
            $selected_inputs = $inputs1;
            break;
        case 'Bank':
            $selected_inputs = $inputs2;
            break;
        default:
            $selected_inputs = $inputs3;
            break;
    }
    foreach ($selected_inputs as $input) {
        echo '<div class="' . $input['col'] . '"><div class="input-group">';
        echo '<label for="' . $input['id'] . '">' . $input['label'] . '</label>';
        if ($input['input_type'] === 'select') {
            echo '<select id="' . $input['id'] . '" name="' . $input['id'] . '" data-trigger- class="form-select ' . $input['input_class'] . '">';
            foreach ($input['options'] as $value => $label) {
                $selected = $value == $input['value'] ? 'selected' : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            }
            echo '</select>';
        } else {
            echo '<input value="' . $input['value'] . '" id="' . $input['id'] . '" name="' . $input['id'] . '" class="' . $input['input_class'] . '" type="' . $input['input_type'] . '">';
        }
        echo '</div></div>';
    } ?>
<?php } ?>

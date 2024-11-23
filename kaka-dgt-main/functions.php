<?php function insert($table, $data)
{
    global $connect;
    $query = "";
    $keys = "(";
    $values = "(";
    $i = 0;
    $j = 0;
    $final_query = "";
    $count = count($data);


    $query .= "INSERT INTO " . $table . " ";

    foreach ($data as $key => $value) {
        $i++;
        $j++;


        $keys .= "`" . $key . "`";
        if ($i < $count) {

            $keys .= ",";
        }
        $values .= "'" . $value . "'";
        if ($i < $count) {

            $values .= ",";
        }
    }
    $values .= ")";
    $keys .= ")";

    $final_query .= $query . $keys . " VALUES " . $values;

    //var_dump($final_query);
    $result = mysqli_query($connect, $final_query);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function update($table, $data, $where = null)
{
    global $connect;
    $query = "";
    $values = "";
    $i = 0;
    $j = 0;
    $final_query = "";
    $count = count($data);

    $query .= "UPDATE `" . $table . "` SET ";
    foreach ($data as $key => $value) {
        $i++;
        $values .= " " . $key . "='" . $value . "' ";
        if ($i < $count) {
            $values .= ",";
        }
    }
    $count1 = count($where);
    $where_values = " WHERE";
    foreach ($where as $key_where => $value_where) {
        $j++;
        $where_values .= " " . $key_where . "='" . $value_where . "' ";
        if ($j < $count1) {
            $where_values .= "AND";
        }
    }
    $final_query .= $query . $values . $where_values;
    // var_dump( $final_query);
    $result = mysqli_query($connect, $final_query);
    if ($result) {
        return true;
    } else {
        return false;
    }
    /*if (!$result) {
        die("Query Failed") . mysqli_error($connect);
    }*/
}

function fetch($table, $where = null)
{
    global $connect;
    $query = "SELECT * FROM `" . $table . "`";
    if ($where) {
        $count = count($where);
        $i = 0;

        $where_values = " WHERE";
        foreach ($where as $key => $value) {
            $i++;
            $where_values .= " " . $key . "='" . $value . "' ";
            if ($i < $count) {
                $where_values .= "AND";
            }
        }
        $query .= $where_values;
    }
    //var_dump($query);
    $result = mysqli_query($connect, $query);
    if (!$result) {
        die("Query Failed") . mysqli_error($connect);
    } else {
        //return $this->result_array($result);
        return $result;
    }
}

function removeFilter($returnUrl)
{
    $removeFilter = '<a href="' . $returnUrl . '"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    return $removeFilter;
}

function buysDetailsBillNo($buys_id)
{
    $qq = fetch('buys_details', array('buys_id' => $buys_id));
    $c = mysqli_num_rows($qq);
    return $c + 1;
}

function sellBillNo($buys_id)
{
    $qq = fetch('buys_sold', array('buys_id' => $buys_id));
    $c = mysqli_num_rows($qq);
    return $c + 1;
}

function backUrl($backUrl, $lable = null)
{
    $text = $lable ? $lable : ' واپس ';
    return '<a href="' . $backUrl . '" class=" btn btn-dark btn-icon-text mb-2 mb-md-0 pt-0 pb-1 d-print-none"><i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>' . $text . '</a>';
}

function backUrl2($backUrl, $lable = null)
{
    $text = $lable ? $lable : ' واپس ';
    return '<a href="' . $backUrl . '" class="btn btn-inverse-dark btn-icon-text"><i class="btn-icon-prepend" data-feather="arrow-left"></i>' . $text . '</a>';
}

function saveAttachment($source_id, $source_name, $attachment)
{
    global $connect;
    $presentDateTime = date('Y-m-d H:i:s');
    $add_sql = "INSERT INTO `attachments` (`source_id`, `source_name`, `attachment`, `created_at`) VALUES ('$source_id', '$source_name', '$attachment', '$presentDateTime')";
    mysqli_query($connect, $add_sql);
    return true;
}

function UTPermissions($user_id)
{
    $ddd = fetch('users', array('id' => $user_id));
    $data = mysqli_fetch_assoc($ddd);
    $ut_permissions = $data['ut_permissions'];
    return $ut_permissions;
}

function UTRoznamcha($user_id, $ut_clearance_entry = null)
{
    $ddd = fetch('users', array('id' => $user_id));
    $data = mysqli_fetch_assoc($ddd);
    if ($ut_clearance_entry) {
        return $data['ut_permissions'];
    } else {
        return $data['ut_roznamcha'];
    }
}

function is_clearance_roznamcha_allowed($clearanceRoznamchaName, $user_id, $clearanceEntryName = null)
{
    if (Administrator()) {
        return true;
    } else {
        if ($clearanceEntryName) {
            $perms = UTRoznamcha($user_id, $clearanceEntryName);
        } else {
            $perms = UTRoznamcha($user_id);
        }

        $url = './';
        if (empty($perms)) {
            return false;
        } else {
            $perms = json_decode($perms);
            $jsonPerms = implode(',', $perms);
            $jsonPerms = explode(',', $jsonPerms);
            if (in_array($clearanceRoznamchaName, $jsonPerms)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function buyBalance($buys_id)
{
    global $connect;
    $totalBardana = 0;;
    $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsQ) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsQ);
        $totalBardana += $detailSums['bardana_qtySum'];
    }

    return $totalBardana;
}

function sellBalance($buys_id)
{
    global $connect;
    $totalBardana = 0;;
    $detailsSold = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_sold WHERE buys_id = '$buys_id'");
    //$detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsSold) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsSold);
        $totalBardana += $detailSums['bardana_qtySum'];
    }

    return $totalBardana;
}

function buySellBalance($buys_id)
{
    //global $connect;
    $totalBardana = buyBalance($buys_id);
    $soldBardana = sellBalance($buys_id);
    $balance = $totalBardana - $soldBardana;
    return $balance;
    /*$balance = $totalBardana = $soldBardana = 0;
    $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsQ) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsQ);
        $totalBardana = $detailSums['bardana_qtySum'];
    }
    $detailsSold = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum2 FROM buys_sold WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsSold) > 0) {
        $detailSums2 = mysqli_fetch_assoc($detailsSold);
        $soldBardana = $detailSums2['bardana_qtySum2'];
    }
    $balance = $totalBardana - $soldBardana;
    return $balance;*/
}

function monthNameENByNumber($number, $format = null)
{
    $ff = $format ? $format : 'F';
    $dateObj = DateTime::createFromFormat('!m', $number);
    $monthName = $dateObj->format($ff);
    return $monthName;
}

function monthNameURByNumber($number)
{
    $dateObj = DateTime::createFromFormat('!m', $number);
    $monthName = $dateObj->format('F');
    $nmeng = array('january', 'february', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $nmtur = array('جنوری', 'فروری', 'مارچ', 'اپریل', 'مئی', 'جون', 'جولائی', 'اگست', 'ستمبر', 'اکتوبر', 'نومبر', 'دسمبر');
    $dt = str_ireplace($nmeng, $nmtur, $monthName);
    return $dt;
}

function isDTKirayaAdded($maalId, $formName, $tlId = null)
{
    $valid['success'] = array('success' => false, 'output' => array());
    if ($tlId) {
        $ddd = fetch('dt_truck_maals2', array('dt_tl_id' => $tlId, 'form_name' => $formName));
    } else {
        $ddd = fetch('dt_truck_maals2', array('maal_id' => $maalId, 'form_name' => $formName));
    }
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function isKirayaAdded($maalId, $formName, $tlId = null)
{
    $valid['success'] = array('success' => false, 'output' => array());
    if ($tlId) {
        $ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $tlId, 'form_name' => $formName));
    } else {
        $ddd = fetch('imp_truck_maals2', array('maal_id' => $maalId, 'form_name' => $formName));
    }
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function isImpExtraExpenseAdded($imp_tl_id, $form_name)
{
    $valid['success'] = array('success' => false, 'output' => array());
    $ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $imp_tl_id, 'maal_id' => 0, 'form_name' => $form_name));
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function isUTExpenseAdded($bail_id, $expense_name)
{
    $valid['success'] = array('success' => false, 'output' => array());
    $ddd = fetch('ut_expenses', array('bail_id' => $bail_id, 'expense_name' => $expense_name));
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function khaataExits($khaataId)
{
    global $connect;
    $c = getNumRows('roznamchaas', 'khaata_id', $khaataId);
    if ($c > 0) {
        return true;
    } else {
        return false;
    }
}

function khaataSingle($khaataId)
{
    global $connect;
    $data = fetch('khaata', array('id' => $khaataId));
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function getMaalSerial($imp_truck_loading_id)
{
    $d = fetch('imp_truck_maals', array('imp_tl_id' => $imp_truck_loading_id));
    return mysqli_num_rows($d) + 1;
}

function getAutoIncrement($tableName)
{
    global $dbname;
    global $connect;
    $data = mysqli_query($connect, "SELECT AUTO_INCREMENT FROM information_schema.TABLES
WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$tableName'");
    $datum = mysqli_fetch_assoc($data);
    return $datum['AUTO_INCREMENT'];
}

function busines_setting()
{
    global $connect;
    $bs = mysqli_query($connect, "SELECT * FROM `business_settings` WHERE id =1");
    $bSetting = mysqli_fetch_assoc($bs);
    $siteurl = $bSetting['siteurl'];
    $sitename = $bSetting['sitename'];
    $sitedescription = $bSetting['sitedescription'];
    $copyRight = $bSetting['copy'];
    return $bSetting;
}

function userRole($role)
{
    $span = '';
    if ($role == "admin") {
        $span = '<span class="badge bg-dark">ایڈمن</span>';
    }
    if ($role == "manager") {
        $span = '<span class="badge bg-danger">مینیجر</span>';
    }
    if ($role == "munshi") {
        $span = '<span class="badge bg-success">منشی</span>';
    }
    if ($role == "staff") {
        $span = '<span class="badge bg-secondary">ملازم</span>';
    }
    if ($role == "agent") {
        $span = '<span class="badge bg-light border border-secondary text-dark">کلئیرنگ ایجنٹ</span>';
    }
    return $span;
}

function roznamchaName($type, $only_urdu_name = false)
{
    $span = '';
    $arr = array(
        'karobar' => array('کاروبار', 'bg-primary'),
        'bank' => array('بینک', 'bg-success'),
        'bill' => array('بل', 'bg-dark'),
        'AFG' => array('افغانی ٹرک کرایہ', 'bg-light small-4 text-dark'),
        'import_exp' => array('امپورٹ کسٹم خرچہ', 'bg-light small-4 text-dark'),
        'dt_custom_exp' => array('ڈاون ٹرانزٹ کسٹم خرچہ', 'bg-light small-4 text-dark'),
        'r_office_exp' => array('آفس خرچہ', 'bg-light small-4 text-dark'),
        'r_home_exp' => array('گھر خرچہ', 'bg-light small-4 text-dark'),
        'kiraya_summary' => array('امپورٹ کرایہ سمری', 'bg-light text-dark'),
        'ut_karachi' => array('کراچی امپورٹ', 'bg-info small-4 text-dark'),
        'ut_chaman' => array('چمن ایکسپورٹ', 'bg-info small-4 text-dark'),
        'ut_border' => array('افغان بارڈر', 'bg-info small-4 text-dark'),
        'ut_qandhar' => array('قندھار کسٹم', 'bg-info  small-4 text-dark'),
        'ut_border_bill' => array('بارڈر بل', 'bg-info small-4 text-dark'),
        'ut_border_afg_truck' => array('افغانی ٹرک کرایہ', 'bg-info small-4 text-dark'),
    );
    if (array_key_exists($type, $arr)) {
        if ($only_urdu_name) {
            $span = $arr[$type][0];
        } else {
            $span = '<span class="badge ' . $arr[$type][1] . '">' . $arr[$type][0] . '</span>';
        }
    }
    /*if ($type == "karobar") {
        $span = '<span class="badge bg-primary" style="font-size:.6rem;">کاروبار</span>';
    }
    if ($type == "bank") {
        $span = '<span class="badge bg-success">بینک</span>';
    }
    if ($type == "bill") {
        $span = '<span class="badge bg-dark">بل </span>';
    }
    if ($type == "AFG") {
        $span = '<span class="badge bg-light small-4 text-dark">افغانی ٹرک کرایہ</span>';
    }
    if ($type == "import_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">امپورٹ کسٹم خرچہ</span>';
    }
    if ($type == "dt_custom_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">ڈاون ٹرانزٹ کسٹم خرچہ</span>';
    }
    if ($type == "r_office_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">آفس خرچہ</span>';
    }
    if ($type == "r_home_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">گھر خرچہ</span>';
    }
    if ($type == "kiraya_summary") {
        $span = '<span class="badge bg-light text-dark"  style="font-size:.5rem;">امپورٹ کرایہ سمری</span>';
    }
    if ($type == "ut_karachi") {
        $span = '<span class="badge bg-info small-4 text-dark">کراچی امپورٹ </span>';
    }
    if ($type == "ut_chaman") {
        $span = '<span class="badge bg-info small-4 text-dark">چمن ایکسپورٹ</span>';
    }
    if ($type == "ut_border") {
        $span = '<span class="badge bg-info  small-4 text-dark">افغان بارڈر</span>';
    }
    if ($type == "ut_qandhar") {
        $span = '<span class="badge bg-info small-4 text-dark">قندھار کسٹم</span>';
    }
    if ($type == "ut_border_bill") {
        $span = '<span class="badge bg-info small-4 text-dark">بارڈر بل</span>';
    }
    if ($type == "ut_border_afg_truck") {
        $span = '<span class="badge bg-info small-4 text-dark">افغانی ٹرک کرایہ</span>';
    }*/
    return $span;
}

function getBranchSerial($branch_id, $r_type)
{
    $serial = fetch('roznamchaas', array('branch_id' => $branch_id, 'r_type' => $r_type));
    $branch_serial = mysqli_num_rows($serial);
    return $branch_serial + 1;
}

function Administrator()
{
    if ($_SESSION['role'] == "admin") {
        return true;
    } else {
        return false;
    }
}

function branchName($branch_id)
{
    $data = fetch('branches', array('id' => $branch_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['b_name'];
    } else {
        $name = 'نا معلوم';
    }
    return $name;
}

/*function branchName($branch_id, $label = null)
{
    $data = fetch('branches', array('id' => $branch_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['b_code'];
        if ($label) {
            $name = '<b>' . $label . '</b>' . $name;
        }
    } else {
        $name = '';
    }
    return $name;
}*/
function searchInput($autofocus = null, $input_class = null)
{
    $aa = $autofocus ? 'autofocus' : '';
    //$input = '<div class="email-search"><div class="position-relative">';
    //$input .= '<input type="text" id="tableFilter" ' . $aa . ' class="form-control" placeholder="Search... (f2)"><span class="bx bx-search font-size-18"></span>';
    //$input .= '</div></div>';
    $input = '<input type="text" id="tableFilter" ' . $aa . ' class="form-control ' . $input_class . '" placeholder="تلاش کریں (f2)">';
    return $input;
}

function addNew($url, $label = null, $btn_class = null)
{
    $text = $label ? $label : ' اندراج ';
    $a = '<a href="' . $url . '" class="btn btn-outline-primary pb-2 pt-1 ' . $btn_class . '">' . $text . '</a>';
    return $a;
}

function userName($user_id)
{
    $data = fetch('users', array('id' => $user_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['username'];
    } else {
        $name = 'نا معلوم';
    }
    return $name;
}

function bankName($bank_id)
{
    $data = fetch('banks', array('id' => $bank_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['bank_name'];
    } else {
        $name = 'نا معلوم';
    }
    return $name;
}

function Manager()
{
    if ($_SESSION['role'] == "manager") {
        return true;
    } else {
        return false;
    }
}

function Munshi()
{
    if ($_SESSION['role'] == "munshi") {
        return true;
    } else {
        return false;
    }
}

function ClearingAgent()
{
    if ($_SESSION['role'] == "agent") {
        return true;
    } else {
        return false;
    }
}

function Customer()
{
    if ($_SESSION['role'] == "customer") {
        return true;
    } else {
        return false;
    }
}

//get expenses by dates
function getExpense($s_date, $e_date)
{
    global $connect;
    $totalExps = 0;
    $expenses = mysqli_query($connect, "SELECT * FROM expenses WHERE date >= '$s_date' AND date <= '$e_date'");
    while ($expense = mysqli_fetch_assoc($expenses)) {
        $totalExps += $expense['amount'];
    }
    return $totalExps;
}


function getNumRows($table, $where_key = null, $where_value = null)
{
    global $connect;
    if ($where_value && $where_value) {
        $data = mysqli_query($connect, "SELECT * FROM $table WHERE $where_key = '$where_value'");
    } else {
        $data = mysqli_query($connect, "SELECT * FROM $table");
    }
    $datum = mysqli_num_rows($data);
    return $datum;
}

function getTableDataById($table, $id)
{
    global $connect;
    $data = mysqli_query($connect, "SELECT * FROM $table WHERE id = $id");
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function saveUTLogs($bail_id, $remarks)
{
    global $connect;
    $presentDateTime = date('Y-m-d H:i:s');
    $data = array(
        'bail_id' => $bail_id,
        'remarks' => $remarks,
        'created_at' => $presentDateTime
    );
    insert('ut_logs', $data);
    return true;
}

// Save Transaction
function saveTransaction($source_id, $source_name, $date_time, $amount, $type, $source_other = null, $note = null)
{
    global $connect;
    $presentDateTime = date('Y-m-d H:i:s');
    if ($type == 'credit') {
        $add_sql = "INSERT INTO transactions
                    (`source_id`, `source_name`, `date_time`, `credit`, `debit`, `source_other`, `note`)
                    VALUES
                    ('$source_id', '$source_name', '$date_time', '$amount', '', '$source_other', '$note')";
    } elseif ($type == 'debit') {
        $add_sql = "INSERT INTO transactions
                    (`source_id`, `source_name`, `date_time`, `credit`, `debit`, `source_other`, `note`)
                    VALUES
                    ('$source_id', '$source_name', '$date_time', '', '$amount', '$source_other', '$note')";
    }
    mysqli_query($connect, $add_sql);
    return true;
}


function fetchUsers()
{
    global $connect;
    $sql = "SELECT users.*, user_permissions.permission as permission
    FROM users
    LEFT JOIN user_permissions ON users.id = user_permissions.user_id
    WHERE users.is_active=1";
    $data = mysqli_query($connect, $sql);
    return $data;
}

function getUser($userId)
{
    global $connect;
    $sql = "SELECT users.*, user_permissions.*
        FROM users
        LEFT JOIN user_permissions ON users.id = user_permissions.user_id
        WHERE users.id = '$userId'";
    $data = mysqli_query($connect, $sql);
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function getTableDataByIdAndColName($table, $id, $col)
{
    global $connect;
    $userData = mysqli_query($connect, "SELECT * FROM `$table` WHERE id = '$id'");
    $userDatum = mysqli_fetch_assoc($userData);
    return $userDatum[$col];
}

/*function getTableDataByCol($table, $col, $key)
{
    global $connect;
    $data = mysqli_query($connect, "SELECT * FROM `$table` WHERE $col = '$key'");
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}*/

function message($type, $url, $msg, $spanLabel1 = null, $span1 = null, $spanLabel2 = null, $span2 = null, $spanLabel3 = null, $span3 = null, $spanLabel4 = null, $span4 = null, $spanLabel5 = null, $span5 = null)
{
    //session_start();
    $div = '<div class="alert alert-' . $type . ' alert-dismissible fade show mb-0" role="alert">';
    $mm = "";
    if ($type == "success") {
        $mm = "خوشخبری";
    }
    if ($type == "info") {
        $mm = "معلومات";
    }
    if ($type == "warning") {
        $mm = "خبردار";
    }
    if ($type == "danger") {
        $mm = "خبردار";
    }

    $div .= '<strong>' . $mm . '! </strong> ' . $msg . ' ';
    if ($span1) {
        $div .= '<span class="badge bg-dark">' . $spanLabel1 . ': ' . $span1 . '</span>';
    }
    if ($span2) {
        $div .= '<span class="badge bg-success">' . $spanLabel2 . ': ' . $span2 . '</span>';
    }
    if ($span3) {
        $div .= '<span class="badge bg-danger">' . $spanLabel3 . ': ' . $span3 . '</span>';
    }
    if ($span4) {
        $div .= '<span class="badge bg-info">' . $spanLabel4 . ': ' . $span4 . '</span>';
    }
    if ($span5) {
        $div .= '<span class="badge bg-warning">' . $spanLabel5 . ': ' . $span5 . '</span>';
    }
    $div .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>';
    $div .= '</div>';
    $_SESSION['response'] = $div;
    echo '<script>window.location.href="' . $url . '"</script>';
}

function messageAjax($type, $msg, $divClass = null)
{
    //session_start();
    $div = '<div class="alert alert-' . $type . ' alert-dismissible alert-section ' . $divClass . '" role="alert">';
    $mm = "";
    if ($type == "success") {
        $mm = "خوشخبری";
    }
    if ($type == "info") {
        $mm = "معلومات";
    }
    if ($type == "warning" || $type == "danger") {
        $mm = "خبردار";
    }

    $div .= '<strong>' . $mm . '! </strong> ' . $msg . ' ';

    $div .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>';
    $div .= '</div>';
    return $div;
}

function getUserDataByIdAndColName($id, $col)
{
    global $connect;
    $userData = mysqli_query($connect, "SELECT * FROM `users` WHERE id = '$id'");
    $userDatum = mysqli_fetch_assoc($userData);
    return $userDatum[$col];
}

function getAge($day, $month, $year)
{
    $array = array();
    $bday = new DateTime($day . '.' . $month . '.' . $year);
    $today = new Datetime(date('m.d.y'));
    $diff = $today->diff($bday);
    $array['years'] = $diff->y;
    $array['months'] = $diff->m;
    $array['days'] = $diff->d;
    //$age = $diff->y . ' years, ' . $diff->m . ' month,' . $diff->d . ' days';
    $age = $diff->y . ' years, ' . $diff->m . ' months';
    //printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
    //printf("\n");
    return $array;
}

function random_str($length = 20)
{
    $characters = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function AmountInWords($amount)
{
    $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
    // Check if there is any number after decimal
    $amt_hundred = null;
    $count_length = strlen($num);
    $x = 0;
    $string = array();
    $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($x < $count_length) {
        $get_divider = ($x == 2) ? 10 : 100;
        $amount = floor($num % $get_divider);
        $num = floor($num / $get_divider);
        $x += $get_divider == 10 ? 1 : 2;
        if ($amount) {
            $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
            $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
            $string [] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' 
       ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' 
       ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
        } else $string[] = null;
    }
    $implode_to_Rupees = implode('', array_reverse($string));
    $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' only' : '';
    return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}

function AmountInWordsUrdu($amount)
{
    $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
    // Check if there is any number after decimal
    $amt_hundred = null;
    $count_length = strlen($num);
    $x = 0;
    $string = array();
    $change_words = array(0 => '', 1 => 'ایک', 2 => 'دو',
        3 => 'تین', 4 => 'چار', 5 => 'پانچ', 6 => 'چھ',
        7 => 'سات', 8 => 'آٹھ', 9 => 'نو',
        10 => 'دس', 11 => 'گیارہ', 12 => 'بارہ',
        13 => 'تیرہ', 14 => 'چودہ', 15 => 'پندرہ',
        16 => 'سولہ', 17 => 'سترہ', 18 => 'اٹھارہ',
        19 => 'انیس', 20 => 'بیس', 30 => 'تیس',
        40 => 'چالیس', 50 => 'پچاس', 60 => 'ساٹھ',
        70 => 'ستر', 80 => 'اسی', 90 => 'نوے');
    $here_digits = array('', 'سو', 'ہزار', 'لاکھ', 'کروڑ');
    while ($x < $count_length) {
        $get_divider = ($x == 2) ? 10 : 100;
        $amount = floor($num % $get_divider);
        $num = floor($num / $get_divider);
        $x += $get_divider == 10 ? 1 : 2;
        if ($amount) {
            $add_plural = (($counter = count($string)) && $amount > 9) ? '' : null;
            $amt_hundred = ($counter == 1 && $string[0]) ? ' اور ' : null;
            $string [] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' 
       ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' 
       ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
        } else $string[] = null;
    }
    $implode_to_Rupees = implode('', array_reverse($string));
    $get_paise = ($amount_after_decimal > 0) ? "اور " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' صرف' : '';
    return ($implode_to_Rupees ? $implode_to_Rupees . 'روپے ' : '') . $get_paise;
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function compressImage($source, $destination, $quality)
{
    // Get image info
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Save image
    imagejpeg($image, $destination, $quality);

    // Return compressed image
    return $destination;
}

function convert_filesize($bytes, $decimals = 2)
{
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function custom_number_format($n, $precision = 3)
{
    if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n);
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

function numberToUrduWords($number)
{
    $words = array(
        0 => 'صفر',
        1 => 'ایک',
        2 => 'دو',
        3 => 'تین',
        4 => 'چار',
        5 => 'پانچ',
        6 => 'چھ',
        7 => 'سات',
        8 => 'آٹھ',
        9 => 'نو',
        10 => 'دس',
        11 => 'گیارہ',
        12 => 'بارہ',
        13 => 'تیرہ',
        14 => 'چودہ',
        15 => 'پندرہ',
        16 => 'سولہ',
        17 => 'سترہ',
        18 => 'اؠٹھارہ',
        19 => 'انیس',
        20 => 'بیس',
        30 => 'تیس',
        40 => 'چالیس',
        50 => 'پچاس',
        60 => 'ستان',
        70 => 'ستر',
        80 => 'اسی',
        90 => 'نوے'
    );

    $numDigits = strlen($number);
    $result = '';

    switch ($numDigits) {
        case 1:
            $result = $words[$number];
            break;
        case 2:
            if ($number < 21) {
                $result = $words[$number];
            } else {
                $result = $words[10 * floor($number / 10)];
                $remainder = $number % 10;
                if ($remainder != 0) {
                    $result .= ' ' . $words[$remainder];
                }
            }
            break;
        case 3:
            $result = $words[floor($number / 100)] . ' سو';
            $remainder = $number % 100;
            if ($remainder != 0) {
                $result .= ' ' . numberToUrduWords($remainder);
            }
            break;
        case 4:
        case 5:
        case 6:
            $result = numberToUrduWords(substr($number, 0, $numDigits - 3)) . ' ہزار';
            $remainder = substr($number, -3);
            if ($remainder != '000') {
                $result .= ' ' . numberToUrduWords($remainder);
            }
            break;
        case 7:
        case 8:
        case 9:
            $result = numberToUrduWords(substr($number, 0, $numDigits - 5)) . ' لاکھ';
            $remainder = substr($number, -5);
            if ($remainder != '00000') {
                $result .= ' ' . numberToUrduWords($remainder);
            }
            break;
        case 10:
        case 11:
        case 12:
            $result = numberToUrduWords(substr($number, 0, $numDigits - 7)) . ' کروڑ';
            $remainder = substr($number, -7);
            if ($remainder != '0000000') {
                $result .= ' ' . numberToUrduWords
                    (substr($number, -7));
            }
            break;
        default:
            $result = 'بہت بڑا عدد';
            break;
    }

    return $result;
}

function readMore($longStr, $lenght = null)
{
    $string = strip_tags($longStr);
    if (strlen($string) > $lenght) {
        $stringCut = substr($string, 0, $lenght);
        $endPoint = strrpos($stringCut, ' ');
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= ' ... ';
    }
    return ucfirst($string);
}

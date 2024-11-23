<?php
require_once '../connection.php';
$branchId = $_SESSION['branch_id'];
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $amount = mysqli_real_escape_string($connect, $_GET['amount']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id) {
        $r_munshi_exp = fetch('r_munshi_exp', array('id' => $id));
        $data = mysqli_fetch_assoc($r_munshi_exp);
        $serial = fetch('roznamchaas', array('branch_id' => $branchId, 'r_type' => 'karobar'));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        $r_type = "karobar";
        $staff_id = $data['staff_id'];
        $staffQuery = fetch('staffs', array('id' => $staff_id));
        $staffData = mysqli_fetch_assoc($staffQuery);
        $transfered_from = "r_munshi_exp";
        $details = '<span class="ms-1"> منشی خرچہ سے ٹرانسفر </span>';
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $id,
            'branch_id' => $staffData['branch_id'],
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => $data['exp_date'],
            'roznamcha_no' => $id,
            'r_name' => $staffData['staff_name'],
            'r_no' => $id,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = "";
        $done = false;
        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $k_data = fetch('khaata', array('id' => $data['jmaa_khaata_id']));
                $k_datum = mysqli_fetch_assoc($k_data);
                $dataArray['branch_serial'] = $branch_serial;
                $dataArray['cat_id'] = $k_datum['cat_id'];
                $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                $dataArray['khaata_id'] = $data['jmaa_khaata_id'];
                $dataArray['khaata_no'] = $data['jmaa_khaata_no'];
                $dataArray['jmaa_amount'] = $data['salary_amount'];
                $dataArray['bnaam_amount'] = 0;
                $str .= "<span class='badge bg-dark mx-2'> جمع:" . $data['jmaa_khaata_no'] . "</span>";
            }
            if ($i == 2) {
                //$cat_id = getTableDataByIdAndColName('khaata', $data[' bnaam_khaata_id'], 'cat_id');
                $k_data = fetch('khaata', array('id' => $data['bnaam_khaata_id']));
                $k_datum = mysqli_fetch_assoc($k_data);
                $dataArray['branch_serial'] = $branch_serial + 1;
                $dataArray['cat_id'] = $k_datum['cat_id'];
                $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                $dataArray['khaata_id'] = $data['bnaam_khaata_id'];
                $dataArray['khaata_no'] = $data['bnaam_khaata_no'];
                $dataArray['bnaam_amount'] = $data['salary_amount'];
                $dataArray['jmaa_amount'] = 0;
                $str .= "<span class='badge bg-dark mx-2'> بنام:" . $data['bnaam_khaata_no'] . "</span>";
            }
            $done = insert('roznamchaas', $dataArray);
        }
        if ($done) {
            $updateArray = array('is_transferred' => 1);
            update('r_munshi_exp', $updateArray, array('id' => $id));
            message('success', $url, ' منشی خرچہ کاروبار روزنامچہ میں ٹرانسفر ہوگیا ہے۔' . $str);
        } else {
            message('danger', $url, 'ڈیٹا بیس پرابلم');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}

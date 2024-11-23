<?php require_once '../connection.php';
$branchId = $_SESSION['branch_id'];
if ($_POST) {
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $url = '../' . mysqli_real_escape_string($connect, $_POST['url']);
    if ($id) {
        $afghani_truck = fetch('afghani_truck', array('id' => $id));
        $data = mysqli_fetch_assoc($afghani_truck);
        $jmaa_khaata_no = $data['afg_jmaa_khaata_no'];
        $jmaa_khaata_id = $data['afg_jmaa_khaata_id'];
        $bnaam_khaata_no = $data['afg_bnaam_khaata_no'];
        $bnaam_khaata_id = $data['afg_bnaam_khaata_id'];
        $serial = fetch('roznamchaas', array('branch_id' => $branchId, 'r_type' => 'karobar'));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        $r_type = "karobar";
        $transfered_from = "AFG";

        $details = ' بھیجنے والا شہر: ' . $data['sender_city'];
        $details .= ' بھیجنے والا نام: ' . $data['sender_name'];
        $details .= ' افغانی کرایہ بل ٹرانسفر ';
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $id,
            'branch_id' => $data['branch_id'],
            //'branch_serial' => $branch_serial,
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => $data['afg_date'],
            'roznamcha_no' => $id,
            'r_name' => $data['afg_truck_name'],
            'r_no' => $data['afg_truck_no'],
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = "";
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['jmaa_amount'] = $amount;
                    $dataArrayUpdate['bnaam_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'>جمع: " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['bnaam_amount'] = $amount;
                    $dataArrayUpdate['jmaa_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'>بنام: " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['jmaa_amount'] = $amount;
                    $dataArray['bnaam_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'>جمع: " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['bnaam_amount'] = $amount;
                    $dataArray['jmaa_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'>بنام: " . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $updateArray = array('is_transfered' => 1);
            update('afghani_truck', $updateArray, array('id' => $id));
            message('success', $url, 'افغانی ٹرک کرایہ کاروبار روزنامچہ میں ٹرانسفر ہوگیا ہے۔' . $str);
        } else {
            message('danger', $url, 'ڈیٹا بیس پرابلم');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}

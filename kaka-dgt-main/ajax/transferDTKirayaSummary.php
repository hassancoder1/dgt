<?php require_once '../connection.php';
$branchId = $_SESSION['branch_id'];
if ($_POST) {
    $tl_id_hidden = mysqli_real_escape_string($connect, $_POST['tl_id_hidden']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_id']);
    $amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $transfered_from = mysqli_real_escape_string($connect, $_POST['transfered_from']);
    $url_old = '../' . mysqli_real_escape_string($connect, $_POST['url']);
    $ttt = str_replace('_', '-', $transfered_from);
    $ttt = substr($ttt,0,-3);
    $url = '../dt-summary-transfer?id=' . $tl_id_hidden . '&type=' . $ttt;
    $r_type = "karobar";
    $details = $type . ' سے ٹرانسفر ';
    if ($tl_id_hidden && $jmaa_khaata_id && $bnaam_khaata_id) {
        $r_home_exp = fetch('dt_truck_loadings', array('id' => $tl_id_hidden));
        $data = mysqli_fetch_assoc($r_home_exp);
        $serial = fetch('roznamchaas', array('branch_id' => $branchId, 'r_type' => 'karobar'));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $tl_id_hidden,
            'branch_id' => $data['branch_id'],
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => date('Y-m-d'),
            'roznamcha_no' => $data['truck_no'],
            'r_name' => $type,
            'r_no' => $tl_id_hidden,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " سیریل نمبر " . $tl_id_hidden;
        $done = false;
        /*for ($i = 1; $i <= 2; $i++) {
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
                $str .= "<span class='badge bg-dark mx-2'> جمع:" . $jmaa_khaata_no . "</span>";
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
                $str .= "<span class='badge bg-dark mx-2'> بنام:" . $bnaam_khaata_no . "</span>";
            }
            $done = insert('roznamchaas', $dataArray);
        }*/
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
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
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
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
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
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
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
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $preData = array(
                'jmaa_khaata_no' => $jmaa_khaata_no,
                'jmaa_khaata_id' => $jmaa_khaata_id,
                'bnaam_khaata_no' => $bnaam_khaata_no,
                'bnaam_khaata_id' => $bnaam_khaata_id,
                'total_bill' => $amount
            );
            switch ($transfered_from) {
                case 'beopari_summary_dt':
                    $khaataData = array('khaata_bs' => json_encode($preData));
                    break;
                case 'kiraya_summary_dt':
                    $khaataData = array('khaata_ks' => json_encode($preData));
                    break;
                case 'godam_mazdoori_dt':
                    $khaataData = array('khaata_gm' => json_encode($preData));
                    break;
                default:
                    $khaataData = array('' => '');
                    break;
            }
            $tlUpdated = update('dt_truck_loadings', $khaataData, array('id' => $tl_id_hidden));
            if ($tlUpdated) {
                message('success', $url,   $type . ' روزنامچہ میں ٹرانسفر ہوگیا اور ٹرک لوڈنگ اپڈیٹ ہو گئی۔ ' . $str);
            } else {
                message('danger', $url, ' روزنامچہ میں ٹرانسفر تو ہوا مگر ٹرک لوڈنگ اپڈیٹ نہیں ہوئی۔ ' . $str);
            }
        } else {
            message('danger', $url, 'ہم معذرت خواہ ہیں کہ روزنامچہ ٹرانسفر نہیں ہو سکا۔');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}

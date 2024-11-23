<?php require_once '../connection.php';
$valid['success'] = array('tableData' => array(), 'bottomData' => array());
$output = '';
$numRows = $totalJmaa = $totalBnaam = $totalBalanceBnaam = 0;
if (isset($_POST['khaata_id'])) {
    $khaata_id = $_POST['khaata_id'];
    if (isset($_POST['action'])) {
        $branch_id = $_POST['branch_id'];
        if ($branch_id == 0) {
            $branch_append = "";
        } else {
            $branch_append = " AND branch_id = " . "'$branch_id'" . " ";
        }
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id' {$branch_append} AND r_date  BETWEEN '$start_date' AND '$end_date'";
    } else {
        $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id'";
    }
    $data = mysqli_query($connect, $sql);
    $numRows = mysqli_num_rows($data);
    if ($numRows > 0) {
        $jmaa = $bnaam = $balance = 0;
        while ($datum = mysqli_fetch_assoc($data)) {
            $output .= '<tr>';
            $output .= '<td class="small-4">' . branchName($datum['branch_id']) . '</td>';
            $output .= '<td class="small-2">' . $datum["r_date"] . '</td>';
            $output .= '<td class="small-2">' . $datum['branch_serial'] . ' - ' . $datum['r_id'] . '</td>';
            $output .= '<td class="small-2">' . userName($datum['user_id']) . '</td>';
            $output .= '<td class="small">' . $datum['roznamcha_no'] . '</td>';
            $output .= '<td class="small-2">' . $datum['r_name'] . '</td>';
            $output .= '<td class="small-2">' . $datum['r_no'] . '</td>';
            $jmaaBnaamString = "";
            $jmaa += $datum['jmaa_amount'];
            $bnaam += $datum['bnaam_amount'];
            $balance = $jmaa - $bnaam;
            /*if ($datum['jmaa_amount'] == 0) {
                $jmaaBnaamString = "بنام";
            }
            if ($datum['bnaam_amount'] == 0) {
                $jmaaBnaamString = "جمع";
            }*/
            if ($balance > 0) {
                $jmaaBnaamString = "جمع";
            } else {
                $jmaaBnaamString = "بنام";
            }
            $bank_str = $date_str = "";
            if ($datum['r_type'] == "bank") {
                $bank_str = ' <span class="">بینک: ' . getTableDataByIdAndColName('banks', $datum['bank_id'], 'bank_name') . '</span> ';
                $date_str = ' <span class="">تاریخ ادائیگی: ' . $datum['r_date_payment'] . '</span> ';
            }
            $output .= '<td class="small-3">' . $jmaaBnaamString . ':- ' . $datum["details"] . $bank_str . ' </td > ';
            $output .= '<td class="small "> ' . $datum['jmaa_amount'] . ' </td > ';
            $output .= '<td class="small"> ' . $datum['bnaam_amount'] . ' </td > ';
            $output .= '<td class="small-2" > ' . $jmaaBnaamString . '</td > ';
            $balanceClass = $balance >= 0 ? 'text-success' : 'text-danger';
            $output .= '<td class="ltr small ' . $balanceClass . '" > ' . $balance . '</td > ';
            $output .= '</tr> ';
            $totalJmaa += $datum['jmaa_amount'];
            $totalBnaam += $datum['bnaam_amount'];
        }
    } else {
        $output .= ' < tr><td colspan = "12" class="text-center text-danger" > کوئی ریکارڈ موجود نہیں ہے۔</td ></tr > ';
    }
} else {
    $output .= '<tr ><td colspan = "12" class="text-center text-danger" > کھاتہ نمبر درست نہیں ہے۔</td ></tr > ';
}
$totalBalanceBnaam = $totalJmaa - $totalBnaam;
$valid['tableData'] = $output;
$valid['bottomData'][0] = $numRows;
$valid['bottomData'][1] = $totalJmaa;
$valid['bottomData'][2] = $totalBnaam;
$valid['bottomData'][3] = $totalBalanceBnaam;
echo json_encode($valid);
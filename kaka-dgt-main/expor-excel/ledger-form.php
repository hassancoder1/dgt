<?php require_once "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$output = $file_name = $name = "";
if (isset($_POST['khaata_id']) && ($_POST['khaata_id'] > 0) && isset($_POST['secret'])
    && isset($_POST['branch_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])
) {
    if (base64_decode($_POST['secret']) == "powered-by-upsol") {
        include("../connection.php");
        $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
        $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
        $kh = fetch('khaata', array('id' => $khaata_id));
        $khaata = mysqli_fetch_assoc($kh);
        $file_name = $khaata['khaata_name'] . '_' . $khaata['khaata_no'] . '-';
        $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id' ";
        if ($branch_id > 0) {
            $sql .= " AND branch_id = " . "'$branch_id'" . " ";
        }
        $start_date = mysqli_real_escape_string($connect, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($connect, $_POST['end_date']);
        if (empty($start_date)) {
            $s_date = '2023-01-01';
            $e_date = date('Y-m-d');
        } else {
            $s_date = $start_date;
            $e_date = $end_date;
            $sql .= " AND r_date BETWEEN " . "'$start_date'" . " AND " . "'$end_date'" . " ";
        }
        $data = mysqli_query($connect, $sql);
        $numRows = mysqli_num_rows($data);
        if ($numRows > 0) {
            $documento = new Spreadsheet();
            /*$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath('../assets/images/print-logo.png');
            $drawing->setHeight(36);*/
            $sheetBooks = $documento->getActiveSheet();
            $output .= '<div class="row">
                    <div class="col flex-column justify-content-center d-flex">
                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w-75">
                    </div>
                    <div class="col-6 text-center urdu-2 flex-column justify-content-center d-flex">
                        <h2>عصمت اللہ نجیب اللہ اینڈ کمپنی</h2>
                        <h6 class="mt-1">امپورٹ ایکسپورٹ کسٹم کلیئرنگ ایجنٹ</h6>
                        <p>ایڈریس: سناتن بازار ہدایت پلازہ فلور نمبر 1 چمن <span>(نوید پلازہ سیکنڈ فلور آفس نمبر 7 نصفی روڑ کوئٹہ )</span>
                        </p>
                    </div>
                    <div class="col text-center urdu-2 flex-column justify-content-center d-flex">
                        <p class="bold">آفس نمبر: </p>
                        <p>0826614073:0826613747
                            03188088901</p>
                        <p class="bold">کوئٹہ آفس نمبر: </p>
                        <p>081282453:03188088900</p>
                    </div>
                </div>';
            //$sheetBooks->setCellValue('A1', $output);
            $b_name = getTableDataByIdAndColName('branches', $khaata['branch_id'], 'b_name');
            $cat = getTableDataByIdAndColName('cats', $khaata['cat_id'], 'c_name');
            //$k_line1 = ["کھاتہ نام: " . $khaata['khaata_name'], $khaata['khaata_no'] . "کھاتہ نمبر: ", $cat . "کیٹیگری: ", "برانچ نام: " . $b_name];
            $k_line1 = [" کھاتہ نام: " . $khaata['khaata_name'], " ", " ", " ", " کھاتہ نمبر: " . $khaata['khaata_no'], " ", " کیٹیگری: " . $cat, " ", " برانچ نام: " . $b_name, " "];
            $sheetBooks->mergeCells('A7:D7');
            $sheetBooks->mergeCells('E7:F7');
            $sheetBooks->mergeCells('G7:H7');
            $sheetBooks->mergeCells('I7:J7');
            $sheetBooks->fromArray($k_line1, null, 'A7');

            $k_line2 = [" فون: " . $khaata['phone'], " ", " موبائل: " . $khaata['mobile'], " ", " ", " کاروبار پتہ: " . $khaata['address'], " ", " "];
            $sheetBooks->mergeCells('A8:B8');
            $sheetBooks->mergeCells('C8:E8');
            $sheetBooks->mergeCells('F8:J8');
            $sheetBooks->fromArray($k_line2, null, 'A8');

            $dates = $s_date . ' سے ' . $e_date;
            $balance_old = 999;
            $balance_prev = 12345678;
            $k_line3 = [" موجودہ بنام بیلنس: " . $balance_prev, " ", " ", " گزشتہ بیلنس: " . $balance_old, " ", " ", " تاریخ: " . $dates, " ", " ", " "];
            $sheetBooks->mergeCells('A9:C9');
            $sheetBooks->mergeCells('D9:F9');
            $sheetBooks->mergeCells('G9:J9');
            $sheetBooks->fromArray($k_line3, null, 'A9');

            $header = ["ٹوٹل", "جمع بنام", "بنام", "جمع", "تفصیل", "نمبر", "نام", "سیریل", "تاریخ", "برانچ"];
            $sheetBooks->fromArray($header, null, 'A12');
            $rowNumb = 13;
            $jmaa = $bnaam = $balance = 0;
            while ($datum = mysqli_fetch_assoc($data)) {
                $jmaaBnaamString = "";
                $jmaa += $datum['jmaa_amount'];
                $bnaam += $datum['bnaam_amount'];
                $balance = $jmaa - $bnaam;
                if ($balance > 0) {
                    $jmaaBnaamString = "جمع";
                } else {
                    $jmaaBnaamString = "بنام";
                }
                $bank_str = "";
                if ($datum['r_type'] == "bank") {
                    $bank_str = ' تاریخ ادائیگی: ' . $datum['r_date_payment'] . ' ';
                    $bank_str .= ' بینک: ' . getTableDataByIdAndColName('banks', $datum['bank_id'], 'bank_name') . ' ';
                }
                //$bna = '<span style="color: red">' . $datum['bnaam_amount'] . '</span>';
                $sheetBooks->setCellValueByColumnAndRow(1, $rowNumb, $balance);
                $sheetBooks->setCellValueByColumnAndRow(2, $rowNumb, $jmaaBnaamString);
                $sheetBooks->setCellValueByColumnAndRow(3, $rowNumb, $datum['bnaam_amount']);
                $sheetBooks->setCellValueByColumnAndRow(4, $rowNumb, $datum['jmaa_amount']);
                $sheetBooks->setCellValueByColumnAndRow(5, $rowNumb, $jmaaBnaamString . ':- ' . $bank_str . $datum["details"]);
                $sheetBooks->setCellValueByColumnAndRow(6, $rowNumb, $datum['r_no']);
                $sheetBooks->setCellValueByColumnAndRow(7, $rowNumb, $datum['r_name']);
                $sheetBooks->setCellValueByColumnAndRow(8, $rowNumb, $datum['r_id']);
                $sheetBooks->setCellValueByColumnAndRow(9, $rowNumb, $datum["r_date"]);
                $sheetBooks->setCellValueByColumnAndRow(10, $rowNumb, getTableDataByIdAndColName('branches', $datum['branch_id'], 'b_name'));
                $rowNumb++;
            }
            $extension = 'Xlsx';
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, $extension);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\".$file_name-" . date('Y_m_d-H_i_s') . ".xlsx\"");
            //clear buffer
            ob_end_clean();
            $writer->save('php://output');
            //$writer->save('reportABC.xlsx');
            exit();
        }
    }
}
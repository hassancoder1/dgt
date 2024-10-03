<?php $page_title = 'My Ledger';
include("header.php");
global $khaataId;
if (is_numeric($khaataId) && $khaataId > 0) {
} else {
    message('danger', './', 'A/C ID Error :(');
} ?>
<div class="row">
    <div class="col-lg-12 ">
        <div class="d-flex justify-content-between align-items-center gap-1 text-uppercase small">
            <div class="h6">
                <b>Rows</b> <span id="numRows_span"></span><br>
                <b>Dr.</b> <span id="dr_total_span"></span><br>
                <b>Cr.</b> <span id="cr_total_span"></span><br>
                <b>Balance</b><span id="totalBalanceBnaam_span"></span>
            </div>
            <div style="width: 80%">
                <div class="card mb-1 position-relative">
                    <div class="info-div">Account</div>
                    <div class="d-flex justify-content-between p-1 pb-0">
                        <div>
                            <?php $khaata = khaataSingle($khaataId);
                            $array_acc1 = array(
                                array('label' => 'A/C#', 'id' => $khaata['khaata_no']),
                                array('label' => 'A/C NAME', 'id' => $khaata['khaata_name']),
                                array('label' => 'BRANCH', 'id' => branchName($khaata['branch_id'])),
                                array('label' => 'CATEGORY', 'id' => catName($khaata['cat_id']))
                            );
                            $array_acc2 = array(
                                array('label' => 'BUSINESS NAME', 'id' => $khaata['business_name']),
                                array('label' => 'ADDRESS', 'id' => $khaata['address']),
                                array('label' => 'COMPANY', 'id' => $khaata['comp_name'])
                            ); ?>
                            <?php foreach ($array_acc1 as $item) {
                                echo '<b>' . $item['label'] . '</b> <span class="text-muted">' . $item['id'] . '</span><br>';
                            } ?>
                        </div>
                        <div>
                            <?php foreach ($array_acc2 as $item) {
                                echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['id'] . '</span><br>';
                            } ?>
                        </div>
                        <div>
                            <?php $details = ['indexes' => $khaata['indexes'], 'vals' => $khaata['vals']];
                            echo displayKhaataDetails($details);
                            $img_src = empty($khaata['image']) ? '../assets/images/logo-placeholder.png' : '../' . $khaata['image']; ?>
                        </div>
                        <div>
                            <img id="khaata_image" src="<?php echo $img_src; ?>" class="avatar-lg rounded shadow"
                                 alt="Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body px-2 py-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr class="text-nowrap text-uppercase">
                            <th>Branch</th>
                            <th>Date</th>
                            <th>Serial</th>
                            <th>User</th>
                            <th>Roz#</th>
                            <th>Name</th>
                            <th>No.</th>
                            <th>Details</th>
                            <th>Dr.</th>
                            <th>Cr.</th>
                            <!--<th>Dr./Cr.</th>-->
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $numRows = $dr_total = $cr_total = $totalBalanceBnaam = 0;
                        $data = fetch('roznamchaas', array('khaata_id' => $khaataId));
                        if (mysqli_num_rows($data) > 0) {
                            $jmaa = $bnaam = $balance = 0;
                            while ($datum = mysqli_fetch_assoc($data)) {
                                $dr = $cr = 0;
                                echo '<tr>';
                                echo '<td class="small">' . branchName($datum['branch_id']) . '</td>';
                                echo '<td class="text-nowrap">' . $datum["r_date"] . '</td>';
                                echo '<td>' . $datum['branch_serial'] . ' - ' . $datum['r_id'] . '</td>';
                                echo '<td>' . $datum['username'] . '</td>';
                                echo '<td>' . $datum['roznamcha_no'] . '</td>';
                                echo '<td class="small">' . $datum['r_name'] . '</td>';
                                echo '<td>' . $datum['r_no'] . '</td>';
                                if ($datum['dr_cr'] == "dr") {
                                    $dr = $datum['amount'];
                                    $dr_total += $dr;
                                    $jmaa += $datum['amount'];
                                } else {
                                    $cr = $datum['amount'];
                                    $cr_total += $cr;
                                    $bnaam += $datum['amount'];
                                }
                                $balance = $jmaa - $bnaam;
                                $bank_str = $date_str = "";
                                /*if ($datum['r_type'] == "bank") {
                                    $bank_str = ' <span class="">Bank: ' . getTableDataByIdAndColName('banks', $datum['bank_id'], 'bank_name') . '</span> ';
                                    $date_str = ' <span class="">Payment Date: ' . $datum['r_date_payment'] . '</span> ';
                                }*/
                                echo '<td class="small">' . $bank_str . $datum["details"] . $date_str . ' </td>';
                                echo '<td class="text-success"> ' . round($dr) . ' </td>';
                                echo '<td class="text-danger"> ' . round($cr) . ' </td>';
                                //echo '<td class="small">' . $datum['dr_cr'] . '</td>';
                                $redGreenText = $balance > 0 ? 'text-success' : 'text-danger';
                                echo '<td class="' . $redGreenText . '"> ' . round($balance) . '</td>';
                                echo '</tr> ';
                                $numRows++;
                            }
                        } else {
                            echo '<tr><td colspan = "12" class="text-center text-danger" >No record</td></tr>';
                        }?>
                        </tbody>
                    </table>
                    <input type="hidden" id="numRows" value="<?php echo $numRows; ?>">
                    <input type="hidden" id="dr_total" value="<?php echo round($dr_total); ?>">
                    <input type="hidden" id="cr_total" value="<?php echo round($cr_total); ?>">
                    <input type="hidden" id="totalBalanceBnaam" value="<?php echo $dr_total - $cr_total; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#numRows_span").text($("#numRows").val());
    $("#dr_total_span").text($("#dr_total").val());
    $("#cr_total_span").text($("#cr_total").val());
    $("#totalBalanceBnaam_span").text($("#totalBalanceBnaam").val());
</script>

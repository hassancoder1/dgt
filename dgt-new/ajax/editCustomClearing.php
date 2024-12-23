<?php
require_once '../connection.php';

$page_title = 'VIEW DETAILS';
$data_for = $pageURL = $_POST['page'];
$purchaseEntry = $unique_code = $_POST['unique_code'];
[$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');
$data = mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $unique_code]));
$Tdata = json_decode($data['tdata'], true);
$Ldata = json_decode($data['ldata'], true);
$Good = $Ldata['good'];
$Agent = $Ldata['agent'] ?? [];

$saleEntries = [];
$PSKEY = $Ldata['type'] === 'p' ? 'sold_to' : 'sold_from';
if (isset($Ldata['transfer'][$PSKEY])) {
    foreach ($Ldata['transfer'][$PSKEY] as $p) {
        $data = explode('~', $p);
        $saleEntries[] = $data[0];
    }
}
?>

<div class="modal-header d-flex justify-content-between bg-white align-items-center mb-2">
    <h5 class="modal-title" id="staticBackdropLabel">VIEW DETAILS</h5>
    <a href="<?= $data_for . '?CCWpage=' . $_POST['CCWpage']; ?>" class="btn-close"></a>
</div>
</div>
<div class="row">
    <div class="col-md-10">
        <form method="POST">
            <input type="hidden" name="return" value="<?= $unique_code . '~' . $Ldata['transfer']['warehouse_transfer']; ?>">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="9" class="bg-dark fs-6 fw-bold text-white">
                                    <div class="d-flex justify-content-between px-2">
                                        <span>
                                            <?= strtoupper($Tdata['p_s_name']) . ' #' . $Tdata['sr_no']; ?>
                                        </span>
                                        <span>
                                            Type: <?= strtoupper($Tdata['type']); ?>
                                        </span>
                                        <span>
                                            Country: <?= $Tdata['country']; ?>
                                        </span>
                                        <span>
                                            Branch: <?= branchName($Tdata['branch_id']); ?>
                                        </span>
                                        <span>
                                            WAREHOUSE: <?= $Ldata['transfer']['warehouse_transfer']; ?>
                                        </span>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th class="bg-light text-center">P#</th>
                                <th class="bg-light text-center">B/L | UID</th>
                                <th class="bg-light text-center">Container No</th>
                                <th class="bg-light text-center">Warehouse</th>
                                <th class="bg-light text-center">Goods Name</th>
                                <th class="bg-light text-center">Quantity</th>
                                <th class="bg-light text-center">Gross Weight</th>
                                <th class="bg-light text-center">Net Weight</th>
                                <th class="bg-light text-center">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">P#<?= $Ldata['p_id'] . ' (' . $Ldata['sr_no'] . ')'; ?></td>
                                <td class="text-center"><?= ('B/L: ' . $Ldata['bl_no']) ?? ('UID: ' . $Ldata['uid']); ?></td>
                                <td class="text-center"><?= $Ldata['good']['container_no']; ?></td>
                                <td class="text-center"><?= $Ldata['transfer']['warehouse_transfer']; ?></td>
                                <td class="text-center"><?= goodsName($Good['goods_id']); ?></td>
                                <td class="text-center"><?= $Good['quantity_no'] . ' ' . $Good['quantity_name']; ?></td>
                                <td class="text-center"><?= $Good['gross_weight']; ?></td>
                                <td class="text-center"><?= $Good['net_weight']; ?></td>
                                <td class="text-center pointer" onclick="fillData('<?= $purchaseEntry; ?>', '<?= implode('~', $saleEntries); ?>', '<?= $_POST['CCWpage']; ?>', 'invoice', '<?= $Ldata['sr_no'] ?>')">
                                    <i class="fa fa-pencil-alt text-primary" title="Edit"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <?php
                    if (isset($Ldata['transfer'][$PSKEY])) {
                    ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="7" class="bg-danger text-center fw-bold text-white">Sold Details</th>
                                </tr>
                                <tr>
                                    <th class="bg-light text-center">S#</th>
                                    <th class="bg-light text-center">Warehouse</th>
                                    <th class="bg-light text-center">Goods Name</th>
                                    <th class="bg-light text-center">Quantity</th>
                                    <th class="bg-light text-center">Gross Weight</th>
                                    <th class="bg-light text-center">Net Weight</th>
                                    <th class="bg-light text-center">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalQuantity = 0;
                                $totalGrossWeight = 0;
                                $totalNetWeight = 0;
                                foreach ($Ldata['transfer'][$PSKEY] as $p) {
                                    $data = explode('~', $p);
                                    $spData = json_decode(mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $data[0]]))['ldata'], true);
                                    $quantity = (float)$spData['good']['quantity_no'];
                                    $grossWeight = (float)$spData['good']['gross_weight'];
                                    $netWeight = (float)$spData['good']['net_weight'];
                                    $totalQuantity += $quantity;
                                    $totalGrossWeight += $grossWeight;
                                    $totalNetWeight += $netWeight;
                                ?>
                                    <tr>
                                        <td class="text-center fw-bold">S# <?= decode_unique_code($data[0], 'TID') . ' (' . ($spData['sr_no'] ?? '??') . ')'; ?></td>
                                        <td><?= $spData['transfer']['warehouse_transfer']; ?></td>
                                        <td><?= goodsName($spData['good']['goods_json']['goods_id']); ?></td>
                                        <td class="text-center"><?= $quantity . ' ' . $spData['good']['goods_json']['qty_name']; ?></td>
                                        <td class="text-center"><?= $grossWeight; ?></td>
                                        <td class="text-center"><?= $netWeight; ?></td>
                                        <td class="text-center pointer" onclick="fillData('<?= $data[0]; ?>','<?= $purchaseEntry; ?>', '<?= $_POST['CCWpage']; ?>', 'invoice', '<?= $spData['sr_no'] ?? ''; ?>')">
                                            <i class="fa fa-pencil-alt text-primary" title="Edit"></i>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="bg-light text-end fw-bold">Total:</th>
                                    <th class="bg-light text-center fw-bold"><?php echo $totalQuantity; ?></th>
                                    <th class="bg-light text-center fw-bold"><?php echo $totalGrossWeight; ?></th>
                                    <th class="bg-light text-center fw-bold"><?php echo $totalNetWeight; ?></th>
                                    <th class="bg-light"></th>
                                </tr>
                            </tfoot>
                        </table>
                    <?php
                    } else {
                        echo '<div class="alert alert-info text-center">Nothing is Sold!</div>';
                    }
                    ?>
                </div>
            </div>


            <div class="modal fade" id="SingleEntry" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body bg-light pt-0" id="EditDetails"></div>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <div class="col-md-2">
        <div class="card p-3 h-100 position-relative">
            <div>
                <b><?php echo strtoupper($Tdata['p_s_name']) . ' #'; ?> </b><?php echo $Tdata['sr_no']; ?>
            </div>
            <div><b>User </b><?php echo $Tdata['username']; ?></div>
            <div><b>Date </b><?php echo my_date($Tdata['_date']); ?></div>
            <div><b>Type </b><?php echo badge(strtoupper($Tdata['type']), 'dark'); ?></div>
            <div><b>Country </b><?php echo $Tdata['country']; ?></div>
            <div><b>Branch </b><?php echo branchName($Tdata['branch_id']); ?></div>
            <div><b>Status </b>
                <?php if ($Tdata['locked'] == 0) {
                    echo $Tdata['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                } else {
                    echo '<i class="fa fa-lock text-success"></i> Transferred.';
                } ?>
            </div>
            <!-- Counters Table -->
            <table class="table table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="12" class="text-center fw-bold text-black">Totals</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold text-success">Total</td>
                        <td class="fw-bold text-success">
                            <?php echo $Good['quantity_no']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-danger">Sold</td>
                        <td class="fw-bold text-danger">
                            <?php echo $totalQuantity ?? 0; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-primary">Remaining</td>
                        <td class="fw-bold text-primary">
                            <?php echo $Good['goods_json']['qty_no']; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function fillData(unique_code, print_party_2, warehouse_type, print_type, sr_no) {
        $('#SingleEntry').modal('show');
        $.ajax({
            url: 'ajax/GetCustomEditEntry.php',
            type: 'post',
            data: {
                unique_code: unique_code,
                print_party_1: unique_code,
                print_party_2: print_party_2,
                warehouse_type: warehouse_type,
                print_type: print_type,
                sr_no: sr_no
            },
            success: function(response) {
                $('#EditDetails').html(response);
                $('#SingleEntry').modal('show');
            },
            error: function(e) {
                alert('Failed to fetch data');
            }
        });
    }
</script>
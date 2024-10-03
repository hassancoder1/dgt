<?php $page_title = 'Contracts';
include("header.php"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between p-1 gap-2">
            <div>
                <h5 class="card-title">Contracts <span
                        class="text-muted fw-normal ms-2">(<?php echo getNumRows('contracts', 'is_active', '1'); ?>)</span>
                </h5>
            </div>
            <div class="flex-fill">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
            </div>
            <div class="d-flex">
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <?php echo addNew('contract-add', '', 'btn-sm'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 62dvh;">
                    <table class="table mb-0 table-bordered fix-head-table table-sm">
                        <thead>
                        <tr class="text-nowrap text-uppercase">
                            <th>Contract #</th>
                            <th>Date</th>
                            <th>Importer</th>
                            <th>Exporter</th>
                            <th>Notify Party</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $contracts = fetch('contracts', array('is_active' => 1));
                        while ($inv = mysqli_fetch_assoc($contracts)) {
                            $json = json_decode($inv['json_data']);

                            /*$q_imp = fetch('imps_exps', array('id' => $json->importer_id));
                            $importer = mysqli_fetch_assoc($q_imp);
                            $q_exp = fetch('imps_exps', array('id' => $json->exporter_id));
                            $exporter = mysqli_fetch_assoc($q_exp);
                            $q_party = fetch('parties', array('id' => $json->party_id));
                            $party = mysqli_fetch_assoc($q_party);*/ ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $inv['contract_no']; ?></span>
                                    <a href="print/contract?contract_id=<?php echo $inv['id']; ?>"
                                       class="btn btn-primary btn-sm py-0" target="_blank"><i class="fa fa-print"></i>
                                        Print</a>
                                </td>
                                <td class="">
                                    <a href="contract-add?id=<?php echo $inv['id']; ?>"><?php echo $inv['contract_date']; ?></a>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13">IMP</span>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13 ">EXP</span>
                                </td>
                                <td class="small">
                                    <span class="text-dark font-size-13">EXP</span>
                                </td>
                            </tr>
                            <tr class=" border-bottom border-primary">
                                <td class="small" colspan="5">
                                    <div class="d-flex gap-2 align-items-center">

                                        <div class="">
                                            <span class="text-muted">Goods Name</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->goods_name; ?></span>
                                            <span class="text-muted">Origin</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->origin; ?></span>
                                            <span class="text-muted">Terms</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->terms; ?></span>
                                            <span class="text-muted">Shipping Method</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->shipping_method; ?></span>
                                            <span class="text-muted">Loading Country</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->loading_country; ?></span>
                                            <span class="text-muted">Receiving Country</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->receiving_country; ?></span>
                                            <span class="text-muted">Loading Date</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo date('d M Y', strtotime($json->loading_date)); ?></span>
                                            <span class="text-muted">Receiving Date</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo date('d M Y', strtotime($json->receiving_date)); ?></span>
                                            <span class="text-muted">Payment Terms</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->payment_terms; ?></span>
                                            <span class="text-muted">Qty Name</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->qty_name; ?></span>
                                            <span class="text-muted">Qty No</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->qty_no; ?></span>
                                            <span class="text-muted">KGs</span>
                                            <span class="text-dark font-size-13 bold"><?php echo $json->kgs; ?></span>
                                            <span class="text-muted">Total KGs</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->total_kgs; ?></span>
                                            <span class="text-muted">Price</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->unit_price . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Total amount</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->amount . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Freight</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo empty($json->freight) ? '0' : $json->freight . ' ' . $json->currency; ?></span>
                                            <span class="text-muted">Net Total</span>
                                            <span
                                                class="text-dark font-size-13 bold"><?php echo $json->total_amount; ?></span>
                                        </div>
                                        <div class="text-nowrap">
                                            <?php $delMsg = 'Are you sure to Delete' . '\n' . 'Contract #: ' . $inv['id']; ?>
                                            <form method="post" onsubmit="return confirm('<?php echo $delMsg; ?>')">
                                                <input type="hidden" name="contract_id"
                                                       value="<?php echo $inv['id']; ?>">
                                                <button type="submit" name="deleteInv"
                                                        class="btn btn-outline-danger btn-sm py-0">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include("footer.php"); ?>
<?php if (isset($_POST['deleteInv'])) {
    $URL_DEL = 'contracts';
    $contract_id = mysqli_real_escape_string($connect, $_POST['contract_id']);
    $done = mysqli_query($connect, "UPDATE `contracts` SET is_active = 0 WHERE id = '$contract_id'");
    $msgg = 'contract # ';
    $msgg .= ' <span class="badge badge-pill badge-soft-danger font-size-12">' . $contract_id . '</span> ';

    if ($done) {
        $msgg .= 'Deleted';
        message('success', $URL_DEL, $msgg);
    } else {
        $msgg .= 'DB error';
        message('danger', $URL_DEL, $msgg);
    }
} ?>

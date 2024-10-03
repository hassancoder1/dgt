<?php include("header.php"); ?>
<style>
    .vscomp-toggle-button {
        padding: 2.5px;
        border-radius: 0;
    }

    .vscomp-search-container, .vscomp-option {
        height: 30px !important;
    }

    .table label {
        margin-bottom: 0 !important;
    }
</style>
<div class="main-content" style="min-height: 85vh;">
    <header class="fixed-top bg-white border-bottom" style="top: 56px; z-index: 1">
        <div class="d-flex align-items-center justify-content-between p-1">
            <div class="">
                <h5 class="card-title">Contract Entry
                    <span class="text-muted fw-normal">Total(<?php echo getNumRows('contracts'); ?>)</span></h5>
            </div>
            <div class="d-flex gap-1">
                <?php echo backUrl('contracts'); //echo searchInput('', 'form-control-sm'); ?>
                <?php echo addNew('contract-add', '', 'btn-sm'); ?>
                <?php if (isset($_GET['id'])) {
                    echo '<a href="print/contract?contract_id=' . $_GET['id'] . '" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-print"></i> Print</a>';
                } ?>
            </div>
        </div>
    </header>
    <div class="px-0 mx-0" style="margin-top: 94px; overflow-x: hidden">
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $ddd = fetch('contracts', array('id' => $id));
            $inv = mysqli_fetch_assoc($ddd);
            $json = json_decode($inv['json_data']); ?>
            <div class="bg-soft-light px-3 pt-1 pb-0 shadow-lg">
                <form method="post">
                    <div class="row table-form mt-1">
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="contract_no" class="form-label">Contract #</label>
                                <input type="text" class="form-control currency" id="contract_no" required autofocus
                                       value="<?php echo $inv['contract_no']; ?>" name="contract_no">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label class="form-label" for="contract_date">Contract Date</label>
                                <input type="date" class="form-control" id="contract_date"
                                       name="contract_date" required value="<?php echo $inv['contract_date']; ?>">
                            </div>
                        </div>
                        <div class="col">
                            <?php if (isset($_SESSION['response'])) {
                                echo $_SESSION['response'];
                                unset($_SESSION['response']);
                            } ?>
                        </div>
                    </div>
                    <div class="row table-form mt-1 gx-0">
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group- d-flex align-items-center">
                                        <label for="importer_id" class="mb-0">Buyer</label>
                                        <select id="importer_id" name="importer_id" required
                                                class="virtual-select">
                                            <?php $importers = fetch('imps_exps');
                                            while ($importer = mysqli_fetch_assoc($importers)) {
                                                $imp_sel = $importer['id'] == $json->importer_id ? 'selected' : '';
                                                echo '<option ' . $imp_sel . ' value="' . $importer['id'] . '">' . $importer['name'] . '</option>';
                                            } ?>
                                        </select>
                                        <!--<small id="imp_response" class="text-danger position-absolute top-0 right-0" style="z-index: 9"></small>-->
                                    </div>
                                </div>
                            </div>
                            <div class="border p-1 bg-white">
                                <table class="table-sm table mb-0">
                                    <tr>
                                        <td colspan="3" class="">
                                            <span class="text-muted">Company</span>
                                            <span class="text-dark font-size-13 bold" id="imp_comp_name"></span>
                                        </td>
                                        <td class="">
                                            <span class="text-muted">City</span>
                                            <span class="text-dark font-size-13 bold" id="imp_city"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class=" ">
                                            <span class="text-muted">Address</span>
                                            <span class="text-dark font-size-13 bold"
                                                  id="imp_comp_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Mobile</span>
                                            <span class="text-dark font-size-13 bold" id="imp_mobile"></span>
                                        </td>
                                        <td colspan="2">
                                            <span class="text-muted">Email</span>
                                            <span class="text-dark font-size-13 bold" id="imp_email"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <label for="exporter_id" class="mb-0">Seller</label>
                                        <select id="exporter_id" name="exporter_id" required
                                                class=" virtual-select">
                                            <?php $exporters = fetch('imps_exps');
                                            while ($exporter = mysqli_fetch_assoc($exporters)) {
                                                $exp_sel = $exporter['id'] == $json->exporter_id ? 'selected' : '';
                                                echo '<option ' . $exp_sel . ' value="' . $exporter['id'] . '">' . $exporter['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="border p-1 bg-white">
                                <table class="table-sm table mb-0">
                                    <tr>
                                        <td colspan="3"><span class="text-muted">Company</span>
                                            <span class="text-dark font-size-13 bold" id="exp_comp_name"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted">City</span>
                                            <span class="text-dark font-size-13 bold" id="exp_city"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-muted">Address</span>
                                            <span class="text-dark font-size-13 bold"
                                                  id="exp_comp_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Mobile</span>
                                            <span class="text-dark font-size-13 bold" id="exp_mobile"></span>
                                        </td>
                                        <td colspan="2" class="">
                                            <span class="text-muted">Email</span>
                                            <span class="text-dark font-size-13 bold" id="exp_email"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <label for="party_id" class="mb-0">Notify&nbsp;Party</label>
                                        <select id="party_id" name="party_id" required
                                                class=" virtual-select">
                                            <?php $parties = fetch('parties');
                                            while ($party = mysqli_fetch_assoc($parties)) {
                                                $party_sel = $party['id'] == $json->party_id ? 'selected' : '';
                                                echo '<option ' . $party_sel . ' value="' . $party['id'] . '">' . $party['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="border p-1 bg-white">
                                <table class="table-sm table mb-0">
                                    <tr>
                                        <td colspan="3"><span class="text-muted">Company</span>
                                            <span class="text-dark font-size-13 bold"
                                                  id="party_comp_name"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted">City</span>
                                            <span class="text-dark font-size-13 bold" id="party_city"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-muted">Address</span>
                                            <span class="text-dark font-size-13 bold"
                                                  id="party_comp_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Mobile</span>
                                            <span class="text-dark font-size-13 bold" id="party_mobile"></span>
                                        </td>
                                        <td colspan="2" class="">
                                            <span class="text-muted">Email</span>
                                            <span class="text-dark font-size-13 bold" id="party_email"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group- d-flex align-items-center">
                                        <label for="bank_khaata_id" class="mb-0">Bank&nbsp;Name</label>
                                        <select id="bank_khaata_id" name="bank_khaata_id" required class="virtual-select">
                                            <?php $banks = fetch('khaata', array('acc_for' => 'bank'));
                                            while ($bank = mysqli_fetch_assoc($banks)) {
                                                $bank_sel = $bank['id'] == $json->bank_khaata_id ? 'selected' : '';
                                                echo '<option ' . $bank_sel . ' value="' . $bank['id'] . '">' . $bank['business_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="border p-1 bg-white">
                                <table class="table-sm table mb-0">
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Bank A/c No.</span>
                                            <span class="text-dark font-size-13 bold" id="b_khaata_name"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Company Name</span>
                                            <span class="text-dark font-size-13 bold" id="b_comp_name"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Address</span>
                                            <span class="text-dark font-size-13 bold" id="b_address"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-muted">Branch Name</span>
                                            <span class="text-dark font-size-13 bold" id="b_cnic_name"></span>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td colspan="2">
                                            <span class="text-muted">Currency</span>
                                            <span class="text-dark font-size-13 bold" id="b_cnic"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-muted">More Details</span>
                                            <span class="text-dark font-size-13 bold" id="b_details"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row table-form gx-1 gy-3 mt-1">
                        <table class="table table-sm mb-0">
                            <thead class="table-secondary">
                            <tr>
                                <th><label for="goods_name" class="mb-0">Goods Name</label></th>
                                <th><label for="origin">Origin</label></th>
                                <th><label for="terms">Terms</label></th>
                                <th><label for="shipping_method">Shipping Method</label></th>
                                <th><label for="loading_country">Loading Country</label></th>
                                <th><label for="receiving_country">Receiving Country</label></th>
                                <!--<th><label for="shipping_terms">Shipping Terms</label></th>-->
                                <th><label for="loading_date">Loading Date</label></th>
                                <th><label for="receiving_date">Receiving Date</label></th>
                                <th><label for="payment_terms">Payment Terms</label></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select class="virtual-select" id="goods_name" name="goods_name" required>
                                        <?php $goods = fetch('good_names', array('type' => 'name'));
                                        while ($good = mysqli_fetch_assoc($goods)) {
                                            $gn_sel = $good['name'] == $json->goods_name ? 'selected' : '';
                                            echo '<option ' . $gn_sel . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
                                        } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="origin" name="origin"
                                           placeholder="Origin" required value="<?php echo $json->origin; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="terms" name="terms" placeholder="Terms"
                                           required value="<?php echo $json->terms; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="shipping_method"
                                           name="shipping_method" placeholder="Method" required
                                           value="<?php echo $json->shipping_method; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="loading_country"
                                           name="loading_country" placeholder="Loading Country" required
                                           value="<?php echo $json->loading_country; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="receiving_country"
                                           name="receiving_country" placeholder="Receiving Country" required
                                           value="<?php echo $json->receiving_country; ?>">
                                </td>
                                <!--<td>
                                    <input type="text" class="form-control" id="shipping_terms"
                                           name="shipping_terms" placeholder="Shipping Terms" required
                                           value="<?php /*echo $json->shipping_terms; */ ?>">
                                </td>-->
                                <td><input type="date" class="form-control" id="loading_date"
                                           name="loading_date" required value="<?php echo $json->loading_date; ?>">
                                </td>
                                <td><input type="date" class="form-control" id="receiving_date" name="receiving_date"
                                           required value="<?php echo $json->receiving_date; ?>"></td>
                                <td><input type="text" class="form-control" id="payment_terms" name="payment_terms"
                                           required placeholder="Payment Terms"
                                           value="<?php echo $json->payment_terms; ?>"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm ">
                            <thead class="table-secondary">
                            <tr>
                                <th><label for="qty_name">Qty Name</label></th>
                                <th><label for="qty_no">Qty No</label></th>
                                <th><label for="kgs">KGs</label></th>
                                <th><label for="total_kgs">Total KGs</label></th>
                                <th><label for="price">Unit Price/KG</label></th>
                                <th><label for="amount">Total Amount</label></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" id="qty_name" name="qty_name"
                                           placeholder="Name" required value="<?php echo $json->qty_name; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control currency" id="qty_no" name="qty_no"
                                           placeholder="Number" required onkeyup="totalKGs();"
                                           value="<?php echo $json->qty_no; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control currency" id="kgs" name="kgs"
                                           placeholder="KGs" required onkeyup="totalKGs();"
                                           value="<?php echo $json->kgs; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="total_kgs" name="total_kgs" placeholder="Total KGs" required
                                           value="<?php echo $json->total_kgs; ?>">
                                </td>
                                <td class="d-flex">
                                    <input type="text" class="form-control currency flex-grow-1" id="price"
                                           name="unit_price" placeholder="Unit Price" required
                                           onkeyup="firstAmount();" value="<?php echo $json->unit_price; ?>">
                                    <select id="currency" name="currency" class="form-select" required>
                                        <?php $currencies = fetch('currencies');
                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                            $crr_sel = $crr['name'] == $json->currency ? 'selected' : '';
                                            echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                        } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control bg-white" id="amount" name="amount" readonly
                                           value="<?php echo $json->amount; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <div class="d-flex">
                                        <label for="contract_details" class="mt-1">Details</label>
                                        <textarea placeholder="Contract Details" type="text" class="form-control"
                                                  name="contract_details"
                                                  id="contract_details"><?php echo $json->contract_details; ?></textarea>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <label for="advance_per" class="mt-1">Advance&nbsp;(%)</label>
                                        <input type="text" class="form-control currency" id="advance_per"
                                               name="advance_per" placeholder="Advance"
                                               onkeyup="advanceAmount();" value="<?php echo $json->advance_per; ?>">
                                        <input value="<?php echo $json->advance; ?>" type="text" class="form-control" id="advance" name="advance" readonly tabindex="-1">
                                    </div>
                                    <div class="d-flex">
                                        <label for="total_amount" class="mt-1 fw-bold">Net&nbsp;Total </label>
                                        <input type="text" class="form-control bg-white fw-bold"
                                               id="total_amount" name="total_amount" readonly
                                               value="<?php echo $json->total_amount; ?>">
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <button name="recordSubmit" id="recordSubmit" type="submit"
                                            class="btn btn-success btn-sm">Update Contract
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                    <input type="hidden" value="update" name="action">
                </form>
            </div>
        <?php } else { ?>
            <div class="bg-soft-light px-3 pt-1 pb-0 shadow-lg">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <form method="post">
                            <div class="row table-form mt-1">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label for="inv_id" class="form-label">Contract #</label>
                                        <input type="text" class="form-control" id="contract_no" name="contract_no"
                                               autofocus required value="<?php echo getAutoIncrement('contracts'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="form-label" for="contract_date">Contract Date</label>
                                        <input type="date" class="form-control" id="contract_date"
                                               name="contract_date" required value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <?php if (isset($_SESSION['response'])) {
                                        echo $_SESSION['response'];
                                        unset($_SESSION['response']);
                                    } ?>
                                </div>
                            </div>
                            <div class="row table-form mt-1 gx-0">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="input-group- d-flex align-items-center">
                                                <label for="importer_id" class="mb-0">Buyer</label>
                                                <select id="importer_id" name="importer_id" required
                                                        class="virtual-select">
                                                    <?php $importers = fetch('imps_exps');
                                                    while ($importer = mysqli_fetch_assoc($importers)) {
                                                        echo '<option value="' . $importer['id'] . '">' . $importer['name'] . '</option>';
                                                    } ?>
                                                </select>
                                                <!--<small id="imp_response" class="text-danger position-absolute top-0 right-0" style="z-index: 9"></small>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-1 bg-white">
                                        <table class="table-sm table mb-0">
                                            <tr>
                                                <td class="">
                                                    <span class="text-muted">Company</span>
                                                    <span class="text-dark font-size-13 bold" id="imp_comp_name"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">City</span>
                                                    <span class="text-dark font-size-13 bold" id="imp_city"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Address</span>
                                                    <span class="text-dark font-size-13 bold"
                                                          id="imp_comp_address"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="text-muted">Mobile</span>
                                                    <span class="text-dark font-size-13 bold" id="imp_mobile"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Email</span>
                                                    <span class="text-dark font-size-13 bold" id="imp_email"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <label for="exporter_id" class="mb-0">Seller</label>
                                                <select id="exporter_id" name="exporter_id" required
                                                        class=" virtual-select">
                                                    <?php $exporters = fetch('imps_exps');
                                                    while ($exporter = mysqli_fetch_assoc($exporters)) {
                                                        echo '<option value="' . $exporter['id'] . '">' . $exporter['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-1 bg-white">
                                        <table class="table-sm table mb-0">
                                            <tr>
                                                <td><span class="text-muted">Company</span>
                                                    <span class="text-dark font-size-13 bold" id="exp_comp_name"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">City</span>
                                                    <span class="text-dark font-size-13 bold" id="exp_city"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Address</span>
                                                    <span class="text-dark font-size-13 bold"
                                                          id="exp_comp_address"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="">
                                                    <span class="text-muted">Mobile</span>
                                                    <span class="text-dark font-size-13 bold" id="exp_mobile"></span>
                                                </td>
                                                <td colspan="" class="">
                                                    <span class="text-muted">Email</span>
                                                    <span class="text-dark font-size-13 bold" id="exp_email"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <label for="party_id" class="mb-0">Notify&nbsp;Party</label>
                                                <select id="party_id" name="party_id" required
                                                        class=" virtual-select">
                                                    <?php $parties = fetch('parties');
                                                    while ($party = mysqli_fetch_assoc($parties)) {
                                                        echo '<option value="' . $party['id'] . '">' . $party['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-1 bg-white">
                                        <table class="table-sm table mb-0">
                                            <tr>
                                                <td colspan="3"><span class="text-muted">Company</span>
                                                    <span class="text-dark font-size-13 bold"
                                                          id="party_comp_name"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">City</span>
                                                    <span class="text-dark font-size-13 bold" id="party_city"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <span class="text-muted">Address</span>
                                                    <span class="text-dark font-size-13 bold"
                                                          id="party_comp_address"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Mobile</span>
                                                    <span class="text-dark font-size-13 bold" id="party_mobile"></span>
                                                </td>
                                                <td colspan="2" class="">
                                                    <span class="text-muted">Email</span>
                                                    <span class="text-dark font-size-13 bold" id="party_email"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="input-group- d-flex align-items-center">
                                                <label for="bank_khaata_id" class="mb-0">Bank&nbsp;Name</label>
                                                <select id="bank_khaata_id" name="bank_khaata_id" required
                                                        class="virtual-select">
                                                    <?php $banks = fetch('khaata', array('acc_for' => 'bank'));
                                                    while ($bank = mysqli_fetch_assoc($banks)) {
                                                        echo '<option value="' . $bank['id'] . '">' . $bank['business_name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-1 bg-white">
                                        <table class="table-sm table mb-0">
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Bank A/c No.</span>
                                                    <span class="text-dark font-size-13 bold" id="b_khaata_name"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Company Name</span>
                                                    <span class="text-dark font-size-13 bold" id="b_comp_name"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Address</span>
                                                    <span class="text-dark font-size-13 bold" id="b_address"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-muted">Branch Name</span>
                                                    <span class="text-dark font-size-13 bold" id="b_cnic_name"></span>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td colspan="2">
                                                    <span class="text-muted">Currency</span>
                                                    <span class="text-dark font-size-13 bold" id="b_cnic"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <span class="text-muted">More Details</span>
                                                    <span class="text-dark font-size-13 bold" id="b_details"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row table-form gx-1 gy-3 mt-1">
                                <table class="table table-sm mb-0">
                                    <thead class="table-secondary">
                                    <tr>
                                        <th><label for="goods_name" class="mb-0">Goods Name</label></th>
                                        <th><label for="origin">Origin</label></th>
                                        <th><label for="terms">Terms</label></th>
                                        <th><label for="shipping_method">Shipping Method</label></th>
                                        <th><label for="loading_country">Loading Country</label></th>
                                        <th><label for="receiving_country">Receiving Country</label></th>
                                        <!--<th><label for="shipping_terms">Shipping Terms</label></th>-->
                                        <th><label for="loading_date">Loading Date</label></th>
                                        <th><label for="receiving_date">Receiving Date</label></th>
                                        <th><label for="payment_terms">Payment Terms</label></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <select class="virtual-select" id="goods_name" name="goods_name" required>
                                                <?php $goods = fetch('good_names', array('type' => 'name'));
                                                while ($good = mysqli_fetch_assoc($goods)) {
                                                    echo '<option value="' . $good['name'] . '">' . $good['name'] . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="origin" name="origin"
                                                   placeholder="Origin" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="terms" name="terms"
                                                   placeholder="Terms" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="shipping_method"
                                                   name="shipping_method" placeholder="Method" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="loading_country"
                                                   name="loading_country" placeholder="Loading Country" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="receiving_country"
                                                   name="receiving_country" placeholder="Receiving Country" required>
                                        </td>
                                        <!--<td>
                                            <input type="text" class="form-control" id="shipping_terms" name="shipping_terms" placeholder="Shipping Terms" required>
                                        </td>-->
                                        <td><input type="date" class="form-control" id="loading_date"
                                                   name="loading_date" required value="<?php echo date('Y-m-d'); ?>">
                                        </td>
                                        <td><input type="date" class="form-control" id="receiving_date"
                                                   name="receiving_date" required value="<?php echo date('Y-m-d'); ?>">
                                        </td>
                                        <td><input type="text" class="form-control" id="payment_terms"
                                                   name="payment_terms" required placeholder="Payment Terms"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table table-sm mb-0">
                                    <thead class="table-secondary">
                                    <tr>
                                        <th><label for="qty_name">Qty Name</label></th>
                                        <th><label for="qty_no">Qty No</label></th>
                                        <th><label for="kgs">KGs</label></th>
                                        <th><label for="total_kgs">Total KGs</label></th>
                                        <th><label for="price">Unit Price/KG</label></th>
                                        <th><label for="amount">Total Amount</label></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="qty_name" name="qty_name"
                                                   placeholder="Name" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control currency" id="qty_no" name="qty_no"
                                                   placeholder="Number" required onkeyup="totalKGs();">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control currency" id="kgs" name="kgs"
                                                   placeholder="KGs" required onkeyup="totalKGs();">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="total_kgs" name="total_kgs" placeholder="Total KGs" required>
                                        </td>
                                        <td class="d-flex">
                                            <input type="text" class="form-control currency flex-grow-1" id="price"
                                                   name="unit_price" placeholder="Unit Price" required
                                                   onkeyup="firstAmount();">
                                            <select id="currency" name="currency" class="form-select" required>
                                                <?php $currencies = fetch('currencies');
                                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                                    echo '<option value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control bg-white" id="amount" name="amount"
                                                   readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <div class="d-flex">
                                                <label for="contract_details" class="mt-1">Details</label>
                                                <textarea placeholder="Contract Details" type="text"
                                                          class="form-control" name="contract_details"
                                                          id="contract_details"></textarea>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label for="advance_per" class="mt-1">Advance&nbsp;(%)</label>
                                                <input type="text" class="form-control currency" id="advance_per"
                                                       name="advance_per" placeholder="Advance"
                                                       onkeyup="advanceAmount();">
                                                <input type="text" class="form-control" id="advance" name="advance" readonly tabindex="-1">
                                            </div>
                                            <div class="d-flex">
                                                <label for="total_amount" class="mt-1 fw-bold">Net&nbsp;Total </label>
                                                <input type="text" class="form-control bg-white fw-bold"
                                                       id="total_amount" name="total_amount" readonly>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button name="recordSubmit" id="recordSubmit" type="submit"
                                                    class="btn btn-primary btn-sm">Save Contract
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<script src="assets/js/pages/invoice-add.js" type="text/javascript"></script>
<?php if (isset($_POST['recordSubmit'])) {
    unset($_POST['recordSubmit']);
    $url = "contract-add";
    $type = 'danger';
    $msg = 'DB Failed';
    $data = array(
        'contract_no' => mysqli_real_escape_string($connect, $_POST['contract_no']),
        'contract_date' => mysqli_real_escape_string($connect, $_POST['contract_date']),
        'json_data' => json_encode($_POST)
    );
    if (isset($_POST['action']) && $_POST['action'] == "update") {
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('contracts', $data, array('id' => $hidden_id));
        $msg = "Updated Contract # " . $_POST['contract_no'];
        $type = "info";
        $url .= "?id=" . $hidden_id;
    } else {
        $data['json_packing'] = json_encode($_POST);
        $data['json_proforma'] = json_encode($_POST);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $data['branch_id'] = $branchId;
        $done = insert('contracts', $data);
        if ($done) {
            $msg = "Saved Contract # " . $_POST['contract_no'];
            $type = "success";
            $url .= "?id=" . $connect->insert_id;
        }
    }
    message($type, $url, $msg);
} ?>



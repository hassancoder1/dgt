<?php require_once '../connection.php';
$source = $_POST['source'];
$d_id = $_POST['d_id'];
$title = $_POST['title'];
$type = $_POST['type'];
if ($d_id > 0) {
    if ($source == 'purchase') {
        $records2q = fetch('purchase_details', array('id' => $d_id));
        $record2 = mysqli_fetch_assoc($records2q);
        $id = $record2['parent_id'];
        $recordq = fetch('purchases', array('id' => $id));
        $record = mysqli_fetch_assoc($recordq);
    } else {
        $records2q = fetch('sale_details', array('id' => $d_id));
        $record2 = mysqli_fetch_assoc($records2q);
        $id = $record2['parent_id'];
        $recordq = fetch('sales', array('id' => $id));
        $record = mysqli_fetch_assoc($recordq);
    }
    $tware_json = json_decode($record2['tware_json']);
    if (empty($tware_json)) {
        $party_khaata_id = 0;
    }else{
        $party_khaata_id = $tware_json->party_khaata_id;
    }
    ?>
    <form method="post" onsubmit="return confirm('Are you sure to submit?')">
        <div class="row table-form">
            <div class="col-3">
                <select id="party_khaata_id" name="party_khaata_id" class="v-select-sm">
                    <?php $kdss = fetch('khaata');
                    while ($kh = mysqli_fetch_assoc($kdss)) {
                        $sel = $party_khaata_id == $kh['id'] ? 'selected' : '';
                        echo '<option '.$sel.' value="' . $kh['id'] . '">' . $kh['khaata_no'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="col-6">
                <select id="party_kd_id" name="party_kd_id" class="form-select">
                    <option hidden value="">Select</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="hidden" id="party-type" name="type">
                <input type="hidden" name="id_hidden" value="<?php echo $id; ?>">
                <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                <input type="hidden" name="source_hidden" value="<?php echo $source; ?>">
                <button class="btn btn-sm btn-dark" id="savePartyPLoading" name="savePartyPLoading">Save Party</button>
            </div>
            <div class="col-12" id="party_details"></div>
        </div>
    </form>
    <div id="khaata_details_form_msg"></div>
    <form class="table-form" id="khaata_details_form">
        <div class="row gx-1 gy-2 mt-2">
            <div class="col-md-6">
                <div class="input-group">
                    <label for="comp_name">NAME</label>
                    <input id="comp_name" name="comp_name" class="form-control" type="text">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <label for="country_id">COUNTRY</label>
                    <select id="country_id" name="country_id" class="form-select form-control">
                        <option value="" disabled selected>Select</option>
                        <?php $countries = fetch('countries');
                        while ($country = mysqli_fetch_assoc($countries)) {
                            echo '<option value="' . $country['id'] . '">' . $country['name'] . '</option>';
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <label for="city">CITY</label>
                    <input id="city" name="city" class="form-control" type="text">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <label for="address">ADDRESS</label>
                    <input id="address" name="address" class="form-control" type="text">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <label for="report">REPORT</label>
                    <input id="report" name="report" class="form-control" type="text">
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-auto">
                <label class="fw-bold">CONTACTS</label>
            </div>
            <div class="col-8">
                <table class="table table-borderless mb-0" id="khaataDetailsTable">
                    <tbody>
                    <?php $arrayNumber = 0;
                    for ($x = 1; $x < 2; $x++) { ?>
                        <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                            <td>
                                <select id="indexes<?php echo $x; ?>" name="indexes[]" class="form-select">
                                    <?php //$static_types = fetch('static_types', array('type_for' => 'khaata_details','type_for' => 'contacts'));
                                    $static_types = mysqli_query($connect, "SELECT * FROM `static_types` WHERE type_for = 'khaata_details' OR type_for = 'contacts'");
                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                        echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                    } ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="vals[]" required placeholder="Value <?php echo $x; ?>"
                                       class="form-control" id="vals<?php echo $x; ?>">
                            </td>
                            <td>
                                <span id="removeProductRowBtn" class="btn btn-link text-danger p-1"
                                      onclick="removeProductRow(<?php echo $x; ?>)">DELETE</span>
                            </td>
                        </tr>
                        <?php $arrayNumber++;
                    } ?>
                    </tbody>
                </table>
            </div>
            <div class="col">
                <span class="btn btn-secondary btn-sm" onclick="addRow()" id="addRowBtn" data-loading-text="Loading...">+ Add line</span>
            </div>
        </div>
        <div class="mt-3">
            <button type="button" name="accDetailsSubmit" id="accDetailsSubmit" class="btn btn-success btn-sm">Submit
            </button>
            <input id="khaata_id_hidden" name="khaata_id_hidden" type="hidden">
            <input name="type_hidden" type="hidden" value="<?php echo $type; ?>">
            <input name="title_hidden" type="hidden" value="<?php echo $title; ?>">
        </div>
    </form>
<?php } ?>
<script>
    VirtualSelect.init({
        ele: '.v-select-sm',
        placeholder: 'Choose A/c.',
        // showValueAsTags: true,
        optionHeight: '30px',
        showSelectedOptionsFirst: true,
        // allowNewOption: true,
        // hasOptionDescription: true,
        search: true
    });
    disableButton('savePartyPLoading');
    var party_khaata_id_ = $('#party_khaata_id').find(":selected").val();
    impKhaata(party_khaata_id_);
    $("#party_khaata_id").change(function () {
        impKhaata($(this).val());
    });

    //$('#khaata_details_form').hide();

    function impKhaata(party_khaata_id) {
        $('#party_details').html('');
        $('#khaata_id_hidden').val(party_khaata_id);
        $.ajax({
            type: 'POST',
            url: 'ajax/fetchKhaataDetailsDropdown.php',
            data: {khaata_id: party_khaata_id},
            success: function (html) {
                console.log(party_khaata_id);
                if (Number(party_khaata_id) > 0) {
                    $('#khaata_details_form').show();
                } else {
                    $('#khaata_details_form').hide();
                }

                $('#party_kd_id').html(html);
                var party_kd_id2 = $('#party_kd_id').find(":selected").val();
                if (party_kd_id2 > 0) {
                    impKhaataDetails(party_kd_id2);
                    enableButton('savePartyPLoading');
                } else {
                    disableButton('savePartyPLoading');
                }
            }
        });
    }

    $("#party_kd_id").change(function () {
        impKhaataDetails($(this).val());
    });

    function impKhaataDetails(party_kd_id) {
        $.ajax({
            type: 'POST',
            url: 'ajax/fetchSingleKhaataDetails.php',
            data: {kd_id: party_kd_id},
            success: function (html) {
                $('#party_details').html(html);
                // enableButton('savePartyPLoading');
            }
        });
    }
</script>
<script src="assets/js/input-repeator-khaata-details.js"></script>

<script>
    //$(document).ready(function() {
    // Attach click event to the submit button
    $("#accDetailsSubmit").on("click", function () {
        var party_khaata_id3 = $('#khaata_id_hidden').val();
        console.log(party_khaata_id3);
        var formData = $("#khaata_details_form").serialize();
        $.ajax({
            type: "POST",
            url: "ajax/submit-khaata-details.php",
            data: formData,
            success: function (response) {
                $("#khaata_details_form_msg").html(response);
                impKhaata(party_khaata_id3);
                $("#khaata_details_form")[0].reset();
            },
            error: function (error) {
                console.error("Error submitting form:", error);
            }
        });
    });
    //});
</script>
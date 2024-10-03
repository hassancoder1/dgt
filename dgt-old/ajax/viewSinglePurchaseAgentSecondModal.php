<?php require_once '../connection.php';
//$purchase_id = $_POST['id'];
$d_id = $_POST['pd_id'];
$title = $_POST['title'];
$type = $_POST['type'];
$source = $_POST['source'];
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
    //var_dump($record); ?>
    <form method="post">
        <div class="row table-form">
            <div class="col-6">
                <select id="a_khaata_id" name="khaata_id" class="v-select-sm" required>
                    <?php $kdss = fetch('khaata', array('acc_for' => 'agent'));
                    while ($kh = mysqli_fetch_assoc($kdss)) {
                        $sel = '';
                        $purchase_agent_data = purchase_agent_data('purchase', $d_id, $type);
                        if (!empty($purchase_agent_data)) {
                            $sel = $purchase_agent_data['khaata_id'] == $kh['id'] ? 'selected' : '';
                        }
                        echo '<option ' . $sel . ' value="' . $kh['id'] . '">' . $kh['khaata_no'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="col-auto">
                <input type="hidden" id="party-type" name="type">
                <input type="hidden" name="id_hidden" value="<?php echo $id; ?>">
                <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
                <input type="hidden" name="source_hidden" value="<?php echo $source; ?>">
                <button type="submit" class="btn btn-sm btn-dark" id="savePartyPLoading" name="saveAgentPLoading">Save
                    Agent
                </button>
            </div>
            <div class="col-12 mt-4" id="party_details"></div>
        </div>
    </form>
    <!--<div id="agent_khaata_form_msg"></div>-->
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
    var a_khaata_id = $('#a_khaata_id').find(":selected").val();
    agentKhaata(a_khaata_id);
    $("#a_khaata_id").change(function () {
        agentKhaata($(this).val());
    });

    function agentKhaata(party_khaata_id) {
        $('#party_details').html('');
        $.ajax({
            type: 'POST',
            url: 'ajax/showSingleKhaata.php',
            data: {khaata_id: party_khaata_id},
            success: function (html) {
                //console.log(party_khaata_id);
                $('#party_details').html(html);
                if (party_khaata_id > 0) {
                    enableButton('savePartyPLoading');
                } else {
                    disableButton('savePartyPLoading');
                }
            }
        });
    }
</script>
<!--<script>
    $("#savePartyPLoading").on("click", function () {
        var formData = $("#agent_khaata_form").serialize();
        $.ajax({
            type: "POST",
            url: "ajax/submit-agent-khaata.php",
            data: formData,
            success: function (response) {
                $("#agent_khaata_form_msg").html(response);
                $("#agent_khaata_form")[0].reset();
            },
            error: function (error) {
                console.error("Error submitting form:", error);
            }
        });
    });
</script>-->
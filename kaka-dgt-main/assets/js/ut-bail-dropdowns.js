/**
 * Created by Saif on 12/21/2022.
 */
/*sender*/
$(function () {
    senderAjax($('#sender_id').val());
});
$('#sender_id').change(function () {
    senderAjax($(this).val());
});
function senderAjax(dt_sender_id = null) {
    $.ajax({
        url: 'ajax/fetchSingleDTSender.php',
        type: 'post',
        data: {
            dt_sender_id: dt_sender_id
        },
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#sender_address").val(response.messages['address']);
                $("#sender_mobile").val(response.messages['mobile']);
                $("#sender_whatsapp").val(response.messages['whatsapp']);
                $("#responseDTSender").text('');
                enableDisableBtn();
            }
            if (response.success === false) {
                $("#responseDTSender").text('مال بھیجنےوالا');
            }
        }
    });
}
/*receiver*/
$(function () {
    receiverAjax($('#receiver_id').val());
});
$('#receiver_id').change(function () {
    receiverAjax($(this).val());
});
function receiverAjax(dt_receiver_id = null) {
    $.ajax({
        url: 'ajax/fetchSingleDTReceiver.php',
        type: 'post',
        data: {
            dt_receiver_id: dt_receiver_id
        },
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                //$("#dt_comp_name_r").val(response.messages['comp_name']);
                $("#receiver_address").val(response.messages['address']);
                $("#receiver_mobile").val(response.messages['mobile']);
                //$("#dt_receiver_owner").val(response.messages['comp_owner_name']);
                $("#receiver_whatsapp").val(response.messages['whatsapp']);
                $("#responseDTReceiver").text('');
                enableDisableBtn();
            }
            if (response.success === false) {
                $("#responseDTReceiver").text('مال وصول کرنے والا');
            }
        }
    });
}
/*exporter*/
$(function () {
    var exporter_id = $('#exporter_id').val();
    expAjax(exporter_id);
});
$('#exporter_id').change(function () {
    var exporter_id = $(this).val();
    expAjax(exporter_id);
});
function expAjax(exporter_id=null) {
    $.ajax({
        url: 'ajax/fetchSingleExporter.php',
        type: 'post',
        data: {exporter_id: exporter_id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#exp_mobile").val(response.messages['mobile']);
                $("#exp_email").val(response.messages['email']);
                $("#exp_city").val(response.messages['city']);
                $("#exp_comp_name").val(response.messages['comp_name']);
                $("#exp_comp_address").val(response.messages['comp_address']);
                $("#responseExporter").text('');
                enableDisableBtn();
            }
            if (response.success === false) {
                $("#responseExporter").text('ایکسپورٹر');
            }
        }
    });
}
/*importer*/
$(function () {
    impAjax($('#importer_id').val());
});
$('#importer_id').change(function () {
    impAjax($(this).val());
});
function impAjax(importer_id=null) {
    $.ajax({
        url: 'ajax/fetchSingleImporter.php',
        type: 'post',
        data: {importer_id: importer_id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#imp_mobile").val(response.messages['mobile']);
                $("#imp_email").val(response.messages['email']);
                $("#imp_city").val(response.messages['city']);
                $("#imp_comp_name").val(response.messages['comp_name']);
                $("#imp_comp_address").val(response.messages['comp_address']);
                $("#responseImporter").text('');
                enableDisableBtn();
            }
            if (response.success === false) {
                $("#responseImporter").text('امپورٹر');
            }
        }
    });
}
/*import agent*/
$(function () {
    impCaAjax($('#imp_ca_id').val());
});
$('#imp_ca_id').change(function () {
    impCaAjax($(this).val());
});
function impCaAjax(imp_ca_id=null) {
    $.ajax({
        url: 'ajax/fetchSingleClearingAgent.php',
        type: 'post',
        data: {ca_id: imp_ca_id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#imp_ca_mobile").val(response.messages['ca_mobile']);
                $("#imp_ca_email").val(response.messages['ca_email']);
                $("#imp_ca_license").val(response.messages['ca_license']);
                $("#imp_ca_license_no").val(response.messages['ca_license_no']);
                $("#responseImpCa").text('');
                enableDisableBtn();
            }
            if (response.success === false) {
                $("#responseImpCa").text('امپورٹ ایجنٹ');
            }
        }
    });
}
/*export agent*/
$(function () {
    expCaAjax($('#exp_ca_id').val());
});
$('#exp_ca_id').change(function () {
    expCaAjax($(this).val());
});
function expCaAjax(exp_ca_id=null) {
    $.ajax({
        url: 'ajax/fetchSingleClearingAgent.php',
        type: 'post',
        data: {ca_id: exp_ca_id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#exp_ca_mobile").val(response.messages['ca_mobile']);
                $("#exp_ca_email").val(response.messages['ca_email']);
                $("#exp_ca_license").val(response.messages['ca_license']);
                $("#exp_ca_license_no").val(response.messages['ca_license_no']);
                $("#responseExpCa").text('');
                enableDisableBtn();
            }
            if (response.success === false) {
                $("#responseExpCa").text('ایکسپورٹ ایجنٹ');
            }
        }
    });
}

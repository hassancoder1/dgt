purchaserKhaata();
sellerKhaata();
shipperKhaata();
$(document).on('keyup', "#purchaser_khaata_no", function (e) {
    validateKhaata();
    purchaserKhaata();
});

function purchaserKhaata() {
    var khaata_no = $("#purchaser_khaata_no").val();
    var khaata_id = $("#purchaser_khaata_id");
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id.val(response.messages['khaata_id']);
                $("#p_response").text('');
                $("#p_khaata_name").text(response.messages['khaata_name']);
                $("#p_city").text(response.messages['city']);
                $("#p_cat").text(response.messages['c_name']);
                $("#p_comp_name").text(response.messages['comp_name']);
                $("#p_address").text(response.messages['address']);
                $("#p_business_name").text(response.messages['business_name']);
                $("#p_whatsapp").html('<a tabindex="-1" href="https://wa.me/' + response.messages['whatsapp'] + '" target="_blank">' + response.messages['whatsapp'] + '</a>');
                $("#p_email").html('<a tabindex="-1" href="mailto:' + response.messages['email'] + '">' + response.messages['email'] + '</a>');
                //$("#p_mobile").text(response.messages['mobile']);
                validateKhaata();
            }
            if (response.success === false) {
                $("#p_response").text('INVALID');
                khaata_id.val(0);
                $("#p_khaata_name").text('');
                $("#p_city").text('');
                $("#p_cat").text('');
                $("#p_comp_name").text('');
                $("#p_address").text('');
                $("#p_business_name").text('');
                $("#p_whatsapp").html('');
                $("#p_email").html('');

                validateKhaata();
            }
        }
    });
}

$(document).on('keyup', "#seller_khaata_no", function (e) {
    validateKhaata();
    sellerKhaata();
});

function sellerKhaata() {
    var khaata_no = $("#seller_khaata_no").val();
    var khaata_id = $("#seller_khaata_id");
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id.val(response.messages['khaata_id']);
                $("#s_response").text('');
                $("#s_khaata_name").text(response.messages['khaata_name']);
                $("#s_city").text(response.messages['city']);
                $("#s_cat").text(response.messages['c_name']);
                $("#s_comp_name").text(response.messages['comp_name']);
                $("#s_address").text(response.messages['address']);
                $("#s_business_name").text(response.messages['business_name']);
                $("#s_whatsapp").html('<a tabindex="-1" href="https://wa.me/' + response.messages['whatsapp'] + '" target="_blank">' + response.messages['whatsapp'] + '</a>');
                $("#s_email").html('<a tabindex="-1" href="mailto:' + response.messages['email'] + '">' + response.messages['email'] + '</a>');
                //$("#p_mobile").text(response.messages['mobile']);
                validateKhaata();
            }
            if (response.success === false) {
                $("#s_response").text('INVALID');
                khaata_id.val(0);
                $("#s_khaata_name").text('');
                $("#s_city").text('');
                $("#s_cat").text('');
                $("#s_comp_name").text('');
                $("#s_address").text('');
                $("#s_business_name").text('');
                $("#s_whatsapp").html('');
                $("#s_email").html('');
                validateKhaata();
            }
        }
    });
}

$(document).on('keyup', "#shipper_khaata_no", function (e) {
    validateKhaata();
    shipperKhaata();
});

function shipperKhaata() {
    var khaata_no = $("#shipper_khaata_no").val();
    var khaata_id = $("#shipper_khaata_id");
    $.ajax({
        url: 'ajax/fetchShipperKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id.val(response.messages['khaata_id']);
                $("#sh_response").text('');
                $("#sh_khaata_name").text(response.messages['owner_name']);
                $("#sh_city").text(response.messages['city']);
                $("#sh_cat").text(response.messages['c_name']);
                $("#sh_comp_name").text(response.messages['comp_name']);
                $("#sh_address").text(response.messages['address']);
                //$("#sh_business_name").text(response.messages['business_name']);
                //$("#sh_whatsapp").html('<a tabindex="-1" href="https://wa.me/' + response.messages['whatsapp'] + '" target="_blank">' + response.messages['whatsapp'] + '</a>');
                $("#sh_email").html('<a tabindex="-1" href="mailto:' + response.messages['email'] + '">' + response.messages['email'] + '</a>');
                $("#sh_mobile").html('<a tabindex="-1" href="tel:' + response.messages['mobile'] + '">' + response.messages['mobile'] + '</a>');
                $("#sh_more_details").text(response.messages['more_details']);
                validateKhaata();
            }
            if (response.success === false) {
                $("#sh_response").text('INVALID');
                khaata_id.val(0);
                $("#sh_khaata_name").text('');
                $("#sh_city").text('');
                $("#sh_cat").text('');
                $("#sh_comp_name").text('');
                $("#sh_address").text('');
                $("#sh_business_name").text('');
                $("#sh_whatsapp").html('');
                $("#sh_email").html('');
                validateKhaata();
            }
        }
    });
}

function validateKhaata() {
    let purchaser_khaata_id = $("#purchaser_khaata_id").val();
    let seller_khaata_id = $("#seller_khaata_id").val();
    let shipper_khaata_id = $("#shipper_khaata_id").val();
    if (purchaser_khaata_id <= 0 || seller_khaata_id <= 0 || shipper_khaata_id <= 0) {
        $("#recordSubmit").prop('disabled', true);
    } else {
        $("#recordSubmit").prop('disabled', false);
    }
}

function totalKGs() {
    let qty_no = document.getElementById("qty_no").value;
    let kgs = document.getElementById("kgs").value;
    document.getElementById("total_kgs").value = Number(qty_no) * Number(kgs);
    firstAmount();
    totalAmount();
}

function firstAmount() {
    let total_kgs = document.getElementById("total_kgs").value;
    let price = document.getElementById("price").value;
    document.getElementById("amount").value = Number(total_kgs) * Number(price);
    totalKGs();
    totalAmount();
}

function totalAmount() {
    let amount_ = document.getElementById("amount").value;
    let rate = document.getElementById("rate").value;
    document.getElementById("total_amount").value = Number(amount_) * Number(rate);
    totalKGs();
    firstAmount();
}

/*simple Dr. Cr. account*/
jQuery(document).ready(function ($) {
    $("#btn_transfer").click(function () {
        $('#transfer_form')[0].reset();
        //$("#addOrUpdateTripHeading").text('Add ');
        $('#transferModal').modal('show');
        //$('#dr_khaata_no_adv').focus();
        $('#transferModal').on('shown.bs.modal', function () {
            $('#dr_khaata_no_adv').focus();
        })
        drKhaata();
        crKhaata();
        //$("#addTripSubmit").addClass(' btn-primary ');
        //$("#addTripSubmit").removeClass(' btn-success ');
    });
});

drKhaata();
crKhaata();
$(document).on('keyup', "#cr_khaata_no_adv", function (e) {
    crKhaata();
});
function crKhaata() {
    var khaata_no = $("#cr_khaata_no_adv").val();
    var khaata_id = $("#cr_khaata_id_adv");
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id.val(response.messages['khaata_id']);
                $("#cr_comp_name").val(response.messages['comp_name']);
                $("#response_simple").html('<i class="fa fa-check-square text-success"></i>');
                //$("#p_mobile").text(response.messages['mobile']);
                $("#recordSubmit").prop('disabled', false);
            }
            if (response.success === false) {
                $("#response_simple").html('<i class="fa fa-window-close text-danger"></i>');
                khaata_id.val('');
                $("#cr_comp_name").val('');
                $("#recordSubmit").prop('disabled', true);
                //$("#s_email").html('');
            }
        }
    });
}
$(document).on('keyup', "#dr_khaata_no_adv", function (e) {
    drKhaata();
});
function drKhaata() {
    var khaata_no = $("#dr_khaata_no_adv").val();
    var khaata_id = $("#dr_khaata_id_adv");
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id.val(response.messages['khaata_id']);
                $("#dr_comp_name").val(response.messages['comp_name']);
                $("#response_simple1").html('<i class="fa fa-check-square text-success"></i>');
                //$("#p_mobile").text(response.messages['mobile']);
                $("#recordSubmit").prop('disabled', false);
            }
            if (response.success === false) {
                $("#response_simple1").html('<i class="fa fa-window-close text-danger"></i>');
                khaata_id.val('');
                $("#dr_comp_name").val('');
                $("#recordSubmit").prop('disabled', true);
                //$("#s_email").html('');
            }
        }
    });
}

function lastAmount() {
    let amount = $("#amount").val();
    let rate = $("#rate").val();

    let operator = $('#operator').find(":selected").val();

    let final_amount;
    if(operator === "/"){
        final_amount = Number(amount) / Number(rate);
    }else{
        final_amount = Number(amount) * Number(rate);
    }
    final_amount = final_amount.toFixed(3);
    $("#final_amount").val(final_amount);
    //subAmount();
}
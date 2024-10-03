transferToRoznamcha();
fetchKhaata();
fetchKhaataBnaam();
$(document).on('keyup', "#seller_khaata_no", function (e) {
    fetchKhaata();
    transferToRoznamcha();
});
function fetchKhaata() {
    console.log('sellll')
    let khaata_no = $("#seller_khaata_no").val();
    let khaata_id2 = $("#khaata_id2");
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id2.val(response.messages['khaata_id']);
                $("#seller_title").text('Seller Details');
                $("#response2").text('');
                $("#seller_name").val(response.messages['khaata_name']);
                $("#seller_mobile").val(response.messages['mobile']);
                $("#seller_address").val(response.messages['address']);
                $("#seller_comp").val(response.messages['comp_name']);
                let seller_desc = '<tr><td>A/c. No. </td><td class="bold">' + response.messages['khaata_no'] + '</td></tr>' +
                    '<tr><td>Name </td><td class="bold">' + response.messages['khaata_name'] + '</td></tr>'+
                    '<tr><td>Company</td><td class="bold">' + response.messages['comp_name'] + '</td></tr>'+
                    '<tr><td>Mobile</td><td class="bold">' + response.messages['mobile'] + '</td></tr>'+
                    '<tr><td>Email</td><td class="bold">' + response.messages['email'] + '</td></tr>'+
                    '<tr><td>Address </td><td class="bold">' + response.messages['address'] + '</td></tr>';
                $("#seller_desc").html(seller_desc);
                transferToRoznamcha();
            }
            if (response.success === false) {
                $("#response2").text('Invalid');
                $("#bm_kh_tafseel").text('');
                $("#seller_name").val('');
                $("#seller_mobile").val('');
                $("#seller_address").val('');
                $("#seller_comp").val('');
                //$("#seller_khaata_span").text('Enter Seller A/c.');
                khaata_id2.val(0);
                transferToRoznamcha();
            }
        }
    });
}

function transferToRoznamcha() {
    let msg = '';
    let khaata_id2 = $("#khaata_id2").val();
    if (khaata_id2 <= 0) {
        if (khaata_id2 <= 0) {
            $("#saleSubmit").prop('disabled', true);
            $("#transferToRoznamchaSubmit").prop('disabled', true);
            //msg = 'بنام کھاتہ درست نہیں۔';
        }
    } else {
        msg = '';
        $("#saleSubmit").prop('disabled', false);
        $("#transferToRoznamchaSubmit").prop('disabled', false);
    }
    //totalBillMsg.text(msg);
}

function fetchKhaataBnaam() {
    let khaata_no = $("#bnaam_khaata_no").val();
    let khaata_id2 = $("#khaata_id2");
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                khaata_id2.val(response.messages['khaata_id']);
                $("#response2").text('');
                let res = '<span class="urdu mt-1">' + response.messages['khaata_name'] + '</span>'
                    + '<br /><span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                    + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                // + '<img src="' + response.messages['image'] + '" class="img-fluid">';
                $("#bm_kh_tafseel").html(res);
                transferToRoznamcha();
            }
            if (response.success === false) {
                $("#response2").text('Invalid');
                $("#bm_kh_tafseel").text('');
                khaata_id2.val(0);
                transferToRoznamcha();
            }
        }
    });
}

$(function () {
    importerInfo($('#importer_id').val());
});
$('#importer_id').change(function () {
    importerInfo($(this).val());
});

function importerInfo(id = null) {
    $.ajax({
        url: 'ajax/fetchSingleImporterExporter.php',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#importer_name").val(response.messages['name']);
                $("#importer_email").val(response.messages['email']);
                $("#importer_mobile").val(response.messages['mobile']);
                $("#importer_city").val(response.messages['city']);
                $("#importer_address").val(response.messages['comp_address']);
                $("#importer_title").text('Importer Details');
                let importer_desc = '<tr><td>Name </td><td class="bold">' + response.messages['name'] + '</td></tr>'+
                    '<tr><td>Company</td><td class="bold">' + response.messages['comp_name'] + '</td></tr>'+
                    '<tr><td>Mobile</td><td class="bold">' + response.messages['mobile'] + '</td></tr>'+
                    '<tr><td>Email</td><td class="bold">' + response.messages['email'] + '</td></tr>'+
                    '<tr><td>City</td><td class="bold">' + response.messages['city'] + '</td></tr>'+
                    '<tr><td>Address</td><td class="bold">' + response.messages['comp_address'] + '</td></tr>';
                $("#importer_desc").html(importer_desc);
                $("#responseImporter").text('');
            }
            if (response.success === false) {
                $("#responseImporter").text('*');
            }
        }
    });
}

//exporter
$(function () {
    exporterInfo($('#exporter_id').val());
    //transferToRoznamcha();
});
$('#exporter_id').change(function () {
    exporterInfo($(this).val());
    //transferToRoznamcha();
});

function exporterInfo(id = null) {
    $.ajax({
        url: 'ajax/fetchSingleImporterExporter.php',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#exporter_name").val(response.messages['name']);
                $("#exporter_email").val(response.messages['email']);
                $("#exporter_mobile").val(response.messages['mobile']);
                $("#exporter_city").val(response.messages['city']);
                $("#exporter_address").val(response.messages['comp_address']);

                //$("#exporter_title").text('Importer Details');
                $("#exporter_name_span").html(response.messages['name'] + '<br>');
                $("#exporter_email_span").html(response.messages['email'] + '<br>');
                $("#exporter_mobile_span").html(response.messages['mobile'] + '<br>');
                $("#exporter_city_span").html(response.messages['city'] + '<br>');
                $("#exporter_address_span").html(response.messages['address'] + '<br>');
                $("#exporter_title").text('Exporter Details');
                let exporter_desc = '<tr><td>Name </td><td class="bold">' + response.messages['name'] + '</td></tr>'+
                    '<tr><td>Company</td><td class="bold">' + response.messages['comp_name'] + '</td></tr>'+
                    '<tr><td>Mobile</td><td class="bold">' + response.messages['mobile'] + '</td></tr>'+
                    '<tr><td>Email</td><td class="bold">' + response.messages['email'] + '</td></tr>'+
                    '<tr><td>City</td><td class="bold">' + response.messages['city'] + '</td></tr>'+
                    '<tr><td>Address</td><td class="bold">' + response.messages['comp_address'] + '</td></tr>';
                $("#exporter_desc").html(exporter_desc);
                $("#responseExporter").text('');
            }
            if (response.success === false) {
                $("#responseExporter").text('*');
            }
        }
    });
}



partyInfo($('#party_id').val());
document.querySelector('#party_id').addEventListener('change', function () {
    let $form = $(this).closest('form');
    $form.find('input[type=submit]').click();
    //this.form.submit();
    partyInfo(this.value);
});

function partyInfo(party_id = null) {
    $.ajax({
        url: 'ajax/fetchSingleParty.php',
        type: 'post',
        data: {party_id: party_id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                //khaata_id.val(response.messages['khaata_id']);
                $("#party_title").text('Notify Party');
                let party_desc = '<tr><td>Company </td><td class="bold">' + response.messages['comp_name'] + '</td></tr>'+
                    '<tr><td>Mobile</td><td class="bold">' + response.messages['mobile'] + '</td></tr>'+
                    '<tr><td>Email</td><td class="bold">' + response.messages['email'] + '</td></tr>'+
                    '<tr><td>City</td><td class="bold">' + response.messages['city'] + '</td></tr>'+
                    '<tr><td>Address</td><td class="bold">' + response.messages['comp_address'] + '</td></tr>';
                $("#party_desc").html(party_desc);

            }
        }
    });
}

$(function () {
    brokerInfo($('#broker_id').val());
    //transferToRoznamcha();
});
$('.broker_id').change(function () {
    brokerInfo($(this).val());
    //transferToRoznamcha();
});

function brokerInfo(broker_id = null) {
    $.ajax({
        url: 'ajax/fetchSingleBroker.php',
        type: 'post',
        data: {
            broker_id: broker_id
        },
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#broker_name").val(response.messages['name']);
                $("#broker_email").val(response.messages['email']);
                $("#broker_mobile").val(response.messages['mobile']);
                $("#broker_city").val(response.messages['city']);
                $("#broker_address").val(response.messages['address']);

                $("#broker_title").text('Broker');
                $("#broker_name_span").html(response.messages['name'] + '<br>');
                $("#broker_email_span").html(response.messages['email'] + '<br>');
                $("#broker_mobile_span").html(response.messages['mobile'] + '<br>');
                $("#broker_city_span").html(response.messages['city'] + '<br>');
                $("#broker_address_span").html(response.messages['address'] + '<br>');

                $("#responseBroker").text('');
            }
            if (response.success === false) {
                $("#responseBroker").text('*');
            }
        }
    });
}


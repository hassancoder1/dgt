/**
 * Created by Saif on 9/26/2022.
 */
$("#recordSubmit").prop('disabled', true);
/*get khaata values at first time*/
var khaata_id = $("#khaata_id").val();
var khaata_no1 = $("#khaata_no1").val();
var c_name = $("#c_name").val();
var b_name = $("#b_name").val();
var khaata_name = $("#khaata_name").val();
var comp_name = $("#comp_name").val();
var business_name = $("#business_name").val();
var address = $("#address").val();
var mobile = $("#mobile").val();
var whatsapp = $("#whatsapp").val();
var phone = $("#phone").val();
var email = $("#email").val();
var khaata_image = $("#khaata_image").attr('src');
/*get khaata values at first time*/

var typingTimer;
var doneTypingInterval = 1000;  //time in ms, 5 seconds for example
var $input = $('#khaata_no');
var khaata_no = '';
$input.on('keyup', function (e) {
    clearTimeout(typingTimer);
    khaata_no = $('#khaata_no').val();
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
});
function doneTyping() {
    $.ajax({
        url: 'ajax/fetchSingleKhaata.php',
        type: 'post',
        data: {khaata_no: khaata_no},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                $("#khaata_id").val(response.messages['khaata_id']);
                $("#khaata_no1").val(khaata_no);
                $("#c_name").val(response.messages['c_name']);
                $("#b_name").val(response.messages['b_name']);
                $("#khaata_name").val(response.messages['khaata_name']);
                $("#comp_name").val(response.messages['comp_name']);
                $("#business_name").val(response.messages['business_name']);
                $("#address").val(response.messages['address']);
                $("#mobile").val(response.messages['mobile']);
                $("#whatsapp").val(response.messages['whatsapp']);
                $("#phone").val(response.messages['phone']);
                $("#email").val(response.messages['email']);
                $("#khaata_image").attr("src", response.messages['image']);
                $("#recordSubmit").prop('disabled', false);
                $("#recordUpdate").prop('disabled', false);
                //$(':input[type="submit"]').prop('disabled', false);
                $("#response").text('');
            }
            if (response.success === false) {
                $("#recordSubmit").prop('disabled', true);
                $("#recordUpdate").prop('disabled', true);
                $("#response").text('کھاتہ نمبر درست نہیں ہے');
                $("#khaata_id").val(khaata_id);
                $("#khaata_no1").val(khaata_no1);
                $("#c_name").val(c_name);
                $("#b_name").val(b_name);
                $("#khaata_name").val(khaata_name);
                $("#comp_name").val(comp_name);
                $("#business_name").val(business_name);
                $("#address").val(address);
                $("#mobile").val(mobile);
                $("#whatsapp").val(whatsapp);
                $("#phone").val(phone);
                $("#email").val(email);
                $("#khaata_image").attr("src", khaata_image);

            }
        }
    });
}


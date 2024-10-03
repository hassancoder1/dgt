importerInfo($('#importer_id').val());
document.querySelector('#importer_id').addEventListener('change', function () {
    importerInfo(this.value);
});
function importerInfo(id = null) {
    $.ajax({
        url: 'ajax/fetchSingleImpExp.php',
        type: 'post',
        data: {id: id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                console.log(response);
                //khaata_id.val(response.messages['khaata_id']);
                $("#imp_comp_name").text(response.messages['comp_name']);
                $("#imp_comp_address").text(response.messages['comp_address']);
                $("#imp_mobile").text(response.messages['mobile']);
                $("#imp_email").text(response.messages['email']);
                $("#imp_city").text(response.messages['city']);
            }
        }
    });

}

exporterInfo($('#exporter_id').val());
document.querySelector('#exporter_id').addEventListener('change', function () {
    exporterInfo(this.value);
});
function exporterInfo(id = null) {
    $.ajax({
        url: 'ajax/fetchSingleImpExp.php',
        type: 'post',
        data: {id: id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                console.log(response);
                //khaata_id.val(response.messages['khaata_id']);
                $("#exp_comp_name").text(response.messages['comp_name']);
                $("#exp_comp_address").text(response.messages['comp_address']);
                $("#exp_mobile").text(response.messages['mobile']);
                $("#exp_email").text(response.messages['email']);
                $("#exp_city").text(response.messages['city']);
            }
        }
    });

}


partyInfo($('#party_id').val());
document.querySelector('#party_id').addEventListener('change', function () {
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
                console.log(response);
                //khaata_id.val(response.messages['khaata_id']);
                $("#party_comp_name").text(response.messages['comp_name']);
                $("#party_comp_address").text(response.messages['comp_address']);
                $("#party_mobile").text(response.messages['mobile']);
                $("#party_email").text(response.messages['email']);
                $("#party_city").text(response.messages['city']);
            }
        }
    });

}


bankInfo($('#bank_khaata_id').val());
document.querySelector('#bank_khaata_id').addEventListener('change', function () {
    bankInfo(this.value);
});
function bankInfo(bank_khaata_id = null) {
    $.ajax({
        url: 'ajax/fetchSingleKhaataBank.php',
        type: 'post',
        data: {bank_khaata_id: bank_khaata_id},
        dataType: 'json',
        success: function (response) {
            if (response.success === true) {
                console.log(response);
                //khaata_id.val(response.messages['khaata_id']);
                $("#b_khaata_name").text(response.messages['khaata_name']);
                $("#b_comp_name").text(response.messages['comp_name']);
                $("#b_address").text(response.messages['address']);
                $("#b_cnic_name").text(response.messages['cnic_name']);
                $("#b_cnic").text(response.messages['cnic']);
                $("#b_details").text(response.messages['details']);
            }
        }
    });

}


firstAmount();
totalAmount();
totalKGs();
function totalKGs() {
    let qty_no = document.getElementById("qty_no").value;
    let kgs = document.getElementById("kgs").value;
    document.getElementById("total_kgs").value = Number(qty_no) * Number(kgs);
    totalAmount();
}

function firstAmount() {
    let total_kgs = document.getElementById("total_kgs").value;
    let price = document.getElementById("price").value;
    document.getElementById("amount").value = Number(total_kgs) * Number(price);
    totalAmount();
}
function totalAmount() {
    let amount_ = document.getElementById("amount").value;
    let freight = document.getElementById("freight").value;
    document.getElementById("total_amount").value = Number(amount_) + Number(freight);
}
function advanceAmount() {
    let advance_per = document.getElementById("advance_per").value;
    let amount2 = document.getElementById("amount").value;
    let advance = percentage(advance_per,amount2);
    document.getElementById("advance").value = advance;
    document.getElementById("total_amount").value = Number(amount2) - Number(advance);
}

function percentage(partialValue, totalValue) {
    return (partialValue / 100) * totalValue;
}

/*
function totalKGs() {
    let qty_no = document.getElementById("qty_no").value;
    let kgs = document.getElementById("kgs").value;
    document.getElementById("total_kgs").value = Number(qty_no) * Number(kgs);
    firstAmount();
    totalAmount();
}
function firstAmount() {
    let qty_kgs = document.getElementById("qty_kgs").value;
    let unit_price = document.getElementById("unit_price").value;
    let discount = document.getElementById("discount").value;

    let amount = Number(qty_kgs) * Number(unit_price);
    amount -= Number(discount);
    document.getElementById("amount").value = amount;
    totalAmount();
}
function totalAmount() {
    let amount_ = document.getElementById("amount").value;
    let freight = document.getElementById("freight").value;
    document.getElementById("total_amount").value = Number(amount_) + Number(freight);
    firstAmount();
}
*/

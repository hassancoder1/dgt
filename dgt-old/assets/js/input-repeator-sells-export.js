window.addEventListener("keydown", checkKeyPress, false);

function checkKeyPress(key) {
    if (key.keyCode == "191") { //FORWARD SLASH
        console.log(key.keyCode);
        addRow();
    }

    if (key.keyCode == "188") {//,
        console.log(key.keyCode);
        var c = $('#productTable tr').last().attr('id');
        c = c.substring(3);
        removeProductRow(c);
    }
}

function addRow() {
    $("#addRowBtn").button("loading");
    var tableLength = $("#productTable tbody tr").length;
    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
        tableRow = $("#productTable tbody tr:last").attr('id');
        arrayNumber = $("#productTable tbody tr:last").attr('class');
        count = tableRow.substring(3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    } else {
        // no table row
        count = 1;
        arrayNumber = 0;
    }

    $("#addRowBtn").button("reset");

    var tr = '<tr id="row' + count + '" class="' + arrayNumber + ' table-form">' +
        '<td class="text-start">' +
        //'<i id="removeProductRowBtn" class="fa fa-window-close text-danger pointer" data-bs-toggle="tooltip" data-bs-title="ختم لائن (,) ' + count + '" tabindex="-1" onclick="removeProductRow(' + count + ')"></i>' +
        '<button id="removeProductRowBtn" onclick="removeProductRow(' + count + ')" data-bs-toggle="tooltip" tabindex="-1" data-bs-title="Remove line (,) ' + count + '" class="btn btn-sm btn-danger mt-3 py-0 px-1 rounded-0"><i class="fa fa-window-close"></i></button>' +
        '</td>' +
        '<td>' +
        '<div class="form-floating"><input type="text" name="exp_names[]" placeholder="Expense# " required class="form-control" id="exp_name' + count + '"><label for="exp_name' + count + '" class="mb-0">Expense# ' + count + '</label></div>' +
        '</td>' +
        '<td>' +
        '<div class="form-floating"><input type="text" name="exp_details[]" placeholder="Details" required class="form-control" id="exp_details' + count + '"><label for="exp_details' + count + '" class="mb-0">Details# ' + count + '</label></div>' +
        '</td>' +
        '<td>' +
        '<div class="form-floating"><input type="number" min="0" step="any" name="exp_values[]" autocomplete="off" required placeholder="Amount" onkeyup="getTotal(' + count + ')" class="form-control currency" id="exp_value' + count + '"><label for="exp_value' + count + '" class="mb-0">Amount# ' + count + '</label></div>' +
        '</td>' +
        '<td>' +
        '<div class="form-floating"><input type="file" name="attachments[]" placeholder="attachments" class="form-control form-control-sm" id="attachments' + count + '"><label for="attachments' + count + '" class="mb-0">Attachment # ' + count + '</label></div>' +
        '</td>' +
        '</tr>';
    if (tableLength > 0) {
        $("#productTable tbody tr:last").after(tr);
    } else {
        $("#productTable tbody").append(tr);
    }
} // /add row

function removeProductRow(row = null) {
    if (row) {
        $("#row" + row).remove();
        subAmount();
    } else {
        alert('error! Refresh the page again');
    }
}

function getTotal(row = null) {
    if (row) {
        var total = Number($("#exp_value" + row).val());
        //total = total.toFixed(2);
        //$("#exp_value" + row).val(total);

        subAmount();

    } else {
        alert('no row !! please refresh the page');
    }
}

function subAmount() {
    var tableProductLength = $("#productTable tbody tr").length;
    var totalSubAmount = 0;
    var grandTotal = 0;
    for (x = 0; x < tableProductLength; x++) {
        var tr = $("#productTable tbody tr")[x];
        var count = $(tr).attr('id');
        count = count.substring(3);

        totalSubAmount = Number(totalSubAmount) + Number($("#exp_value" + count).val());
    }
    //totalSubAmount = totalSubAmount.toFixed(2);
    $("#total").val(totalSubAmount);
    //$("#total2").val(totalSubAmount);
    //lastAmount();
    amountAfterExpense();
}

function totalWt(e) {
    var value = $(e).val();
    var id = $(e).attr('id');
    var balanceSpan = $("#balanceSpan").val();
    var bardana_qty = $("#bardana_qty").val();
    if (bardana_qty > balanceSpan) {
        //alert(balanceSpan);
        $("#balanceIndicator").text(balanceSpan);
        //$("#bardana_qty").val(balanceSpan);
    }
    var per_wt = $("#per_wt").val();
    var total_wt = Number(bardana_qty) * Number(per_wt);
    $("#total_wt").val(total_wt);
}
totalWt();
totalKWt();
totalTaqseemQty();
qeematRaqam();
lastAmount();
function totalKWt(e) {
    let empty_wt = $("#empty_wt").val();
    let bardana_qty = $("#bardana_qty").val();
    let total_empty_wt = Number(bardana_qty) * Number(empty_wt);
    $("#total_empty_wt").val(total_empty_wt);
    let ww = $("#total_wt").val();
    //alert("Total wt=" + ww + " Total empty wt=" + total_empty_wt);
    let saaf_wt = Number(ww) - Number(total_empty_wt);
    $("#saaf_wt").val(saaf_wt);
}
function totalTaqseemQty(e) {
    var saaf_wt_t = $("#saaf_wt").val();
    var taqseem_no = $("#taqseem_no").val();
    if (taqseem_no > 0) {
        var taqseem_qty = Number(saaf_wt_t) / Number(taqseem_no);
        //taqseem_qty = taqseem_qty.toFixed(2);
        $("#taqseem_qty").val(taqseem_qty);
    }
}
function qeematRaqam(e) {
    let taqseem_qty_q = $("#taqseem_qty").val();
    let qeemat_name = $("#qeemat_name").val();
    if (taqseem_qty_q > 0) {
        var qeemat_raqam = Number(taqseem_qty_q) * Number(qeemat_name);
        //qeemat_raqam = qeemat_raqam.toFixed(2);
        $("#qeemat_raqam").val(qeemat_raqam);
    }
}
function amountAfterExpense() {
    let qeemat_raqam = $("#qeemat_raqam").val();
    let total = $("#total").val(); //exp total
    let amount_exc_exp = Number(qeemat_raqam) - Number(total);
    amount_exc_exp = amount_exc_exp.toFixed(3);
    $("#amount_exc_exp").val(amount_exc_exp);
    lastAmount();
}

function lastAmount() {
    let amount_exc_exp = $("#amount_exc_exp").val();
    let exchange_rate = $("#exchange_rate").val();

    let exchange_operator = $('#exchange_operator').find(":selected").val();

    let final_amount;
    if(exchange_operator === "/"){
        final_amount = Number(amount_exc_exp) / Number(exchange_rate);
    }else{
         final_amount = Number(amount_exc_exp) * Number(exchange_rate);
    }
    final_amount = final_amount.toFixed(3);
    $("#final_amount").val(final_amount);
    subAmount();
}

// Restricts input for each element in the set of matched elements to the given inputFilter.
(function ($) {
    $.fn.inputFilter = function (callback, errMsg) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function (e) {
            if (callback(this.value)) {
                // Accepted value
                if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
                    $(this).removeClass("input-error");
                    this.setCustomValidity("");
                }
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                // Rejected value - restore the previous one
                $(this).addClass("input-error");
                this.setCustomValidity(errMsg);
                this.reportValidity();
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                // Rejected value - nothing to restore
                this.value = "";
            }
        });
    };
}(jQuery));

$(".currency").inputFilter(function (value) {
    return /^-?\d*[.,]?\d{0,2}$/.test(value);
}, "کرنسی اماؤنٹ");
$(".numberOnly").inputFilter(function (value) {
    return /^\d*$/.test(value);
}, "نمبر");

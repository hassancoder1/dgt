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
        '<td class="text-center">' +
        '<i id="removeProductRowBtn" class="fa fa-window-close text-danger pointer" data-bs-toggle="tooltip" data-bs-title="ختم لائن (,) ' + count + '" tabindex="-1" onclick="removeProductRow(' + count + ')"></i>' +
        '</td>' +
        '<td>' +
        '<input type="text" name="exp_names[]" placeholder="Expense# ' + count + '" required class="form-control" id="exp_name' + count + '">' +
        '</td>' +
        '<td>' +
        '<input type="text" name="exp_details[]" placeholder="Details ' + count + '" required class="form-control" id="exp_name' + count + '">' +
        '</td>' +
        '<td>' +
        '<input type="number" min="0" step="any" name="exp_values[]" autocomplete="off" required placeholder="Amount# ' + count + '" onkeyup="getTotal(' + count + ')" class="form-control form-control-sm bold currency" id="exp_value' + count + '">' +
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
        var tableLength = $("#productTable tbody tr").length;
        if (tableLength > 1) {
            $("#row" + row).remove();
        }
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

}

// Restricts input for each element in the set of matched elements to the given inputFilter.
(function($) {
    $.fn.inputFilter = function(callback, errMsg) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
            if (callback(this.value)) {
                // Accepted value
                if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
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

$(".currency").inputFilter(function(value) {
    return /^-?\d*[.,]?\d{0,2}$/.test(value); }, "کرنسی اماؤنٹ");
$(".numberOnly").inputFilter(function(value) {
    return /^\d*$/.test(value); }, "نمبر");

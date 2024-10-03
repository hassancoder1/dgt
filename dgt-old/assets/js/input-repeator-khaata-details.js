window.addEventListener("keydown", checkKeyPress, false);
function checkKeyPress(key) {
    if (key.keyCode == "191") { //FORWARD SLASH
        //console.log(key.keyCode);
        addRow();
    }

    if (key.keyCode == "188") {//,
        console.log(key.keyCode);
        var c = $('#khaataDetailsTable tr').last().attr('id');
        c = c.substring(3);
        removeProductRow(c);
    }
}

function addRow() {
    $("#addRowBtn").button("loading");
    var tableLength = $("#khaataDetailsTable tbody tr").length;
    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
        tableRow = $("#khaataDetailsTable tbody tr:last").attr('id');
        arrayNumber = $("#khaataDetailsTable tbody tr:last").attr('class');
        count = tableRow.substring(3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    } else {
        // no table row
        count = 1;
        arrayNumber = 0;
    }

    $("#addRowBtn").button("reset");
    $.ajax({
        type: 'GET',
        url: 'ajax/fetchStaticTypesForKDetailsExtraParty.php',
        success: function (html) {
            $('#indexes' + count).html(html);
        }
    });
    var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">' +
        '<td>' +
        '<select id="indexes' + count + '" name="indexes[]" class="form-select">' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<input type="text" name="vals[]" required placeholder="Value ' + count + '" class="form-control" id="vals' + count + '">' +
        '</td>' +
        '<td>' +
        '<span id="removeProductRowBtn" class="btn btn-link text-danger p-1" onclick="removeProductRow(' + count + ')">DELETE</span>' +
        '</td>' +
        '</tr>';
    if (tableLength > 0) {
        $("#khaataDetailsTable tbody tr:last").after(tr);
    } else {
        $("#khaataDetailsTable tbody").append(tr);
    }
} // /add row

function removeProductRow(row = null) {
    if (row) {
        var tableLength = $("#khaataDetailsTable tbody tr").length;
        if (tableLength > 1) {
            $("#row" + row).remove();
        }
        //subAmount();
    } else {
        alert('error! Refresh the page again');
    }
}

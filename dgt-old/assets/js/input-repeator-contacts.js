function addContactRow() {
    $("#addContactRow").button("loading");
    var tableLength = $("#contactsTable tbody tr").length;
    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
        tableRow = $("#contactsTable tbody tr:last").attr('id');
        arrayNumber = $("#contactsTable tbody tr:last").attr('class');
        count = tableRow.substring(3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    } else {
        // no table row
        count = 1;
        arrayNumber = 0;
    }

    $("#addContactRow").button("reset");
    $.ajax({
        type: 'GET',
        url: 'ajax/fetchStaticTypesForContacts.php',
        success: function (html) {
            $('#contact_indexes' + count).html(html);
        }
    });
    var tr = '<tr id="contact_row' + count + '" class="' + arrayNumber + '">' +
        '<td>' +
        '<select id="contact_indexes' + count + '" name="indexes[]" class="form-select">' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<input type="text" name="vals[]" required placeholder="Value ' + count + '" class="form-control" id="contact_vals' + count + '">' +
        '</td>' +
        '<td>' +
        '<span id="removeContactRow" class="btn btn-link text-danger p-1" onclick="removeContactRow(' + count + ')">Remove</span>' +
        '</td>' +
        '</tr>';
    if (tableLength > 0) {
        $("#contactsTable tbody tr:last").after(tr);
    } else {
        $("#contactsTable tbody").append(tr);
    }
} // /add row

function removeContactRow(row = null) {
    if (row) {
        var tableLength = $("#contactsTable tbody tr").length;
        if (tableLength > 1) {
            $("#contact_row" + row).remove();
        }
        //subAmount();
    } else {
        alert('error! Refresh the page again');
    }
}

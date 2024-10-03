//Importer
function addRowImp() {
    $("#addRowImpBtn").button("loading");
    let tableLength = $("#impTable tbody tr").length;
    let tableRow;
    let arrayNumber;
    let count;

    if (tableLength > 0) {
        tableRow = $("#impTable tbody tr:last").attr('id');
        arrayNumber = $("#impTable tbody tr:last").attr('class');
        count = tableRow.substring(3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    } else {
        count = 1;
        arrayNumber = 0;
    }

    $("#addRowImpBtn").button("reset");
    let tr = '<tr id="row' + count + '" class="' + arrayNumber + ' table-form">' +
        '<td>' +
        '<input type="text" name="imp_ntn[]" placeholder="NTN ' + count + '" required class="form-control" id="imp_ntn' + count + '">' +
        '</td>' +
        '<td>' +
        '<input type="text" name="imp_license_name[]" placeholder="License Name ' + count + '" required class="form-control" id="imp_license_name' + count + '">' +
        '</td>' +
        '<td>' +
        '<input type="text" name="imp_license_no[]" placeholder="License No ' + count + '" required class="form-control" id="imp_license_no' + count + '">' +
        '</td>' +
        '<td class="text-end" style="width: 5%">' +
        '<i class="fa fa-window-close btn btn-outline-danger btn-sm mt-1"  tabindex="-1" onclick="removeRowImp(' + count + ')"></i>' +
        '</td>' +
        '</tr>';
    if (tableLength > 0) {
        $("#impTable tbody tr:last").after(tr);
    } else {
        $("#impTable tbody").append(tr);
    }
} 
function removeRowImp(row = null) {
    if (row) {
        //$("#row" + row).remove();
        var tableLength = $("#impTable tbody tr").length;
        if (tableLength > 1) {
            $("#impTable tbody tr#row" + row).remove();
        }
    } else {
        alert('error! Refresh the page again');
    }
}

//Exporter
function addRowExp() {
    $("#addRowExpBtn").button("loading");
    let tableLength = $("#expTable tbody tr").length;
    let tableRow;
    let arrayNumber;
    let count2;

    if (tableLength > 0) {
        tableRow = $("#expTable tbody tr:last").attr('id');
        arrayNumber = $("#expTable tbody tr:last").attr('class');
        count2 = tableRow.substring(3);
        count2 = Number(count2) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    } else {
        count2 = 1;
        arrayNumber = 0;
    }

    $("#addRowExpBtn").button("reset");
    let tr = '<tr id="row' + count2 + '" class="' + arrayNumber + ' table-form">' +
        '<td>' +
        '<input type="text" name="exp_ntn[]" placeholder="NTN ' + count2 + '" required class="form-control" id="exp_ntn' + count2 + '">' +
        '</td>' +
        '<td>' +
        '<input type="text" name="exp_license_name[]" placeholder="License Name ' + count2 + '" required class="form-control" id="exp_license_name' + count2 + '">' +
        '</td>' +
        '<td>' +
        '<input type="text" name="exp_license_no[]" placeholder="License No ' + count2 + '" required class="form-control" id="exp_license_no' + count2 + '">' +
        '</td>' +
        '<td class="text-end" style="width: 5%">' +
        '<i class="fa fa-window-close btn btn-outline-danger btn-sm mt-1"  tabindex="-1" onclick="removeRowExp(' + count2 + ')"></i>' +
        '</td>' +
        '</tr>';
    if (tableLength > 0) {
        $("#expTable tbody tr:last").after(tr);
    } else {
        $("#expTable tbody").append(tr);
    }
}
function removeRowExp(row = null) {
    if (row) {
        var tableLength = $("#expTable tbody tr").length;
        if (tableLength > 1) {
            //$("#row" + row).remove();
            $("#expTable tbody tr#row" + row).remove();
        }
    } else {
        alert('error! Refresh the page again');
    }
}



/*window.addEventListener("keydown", checkKeyPress, false);
function checkKeyPress(key) {
    if (key.keyCode == "191") {
        console.log(key.keyCode);
        addRowImp();
    }
    if (key.keyCode == "188") {
        console.log(key.keyCode);
        let c = $('#impTable tr').last().attr('id');
        c = c.substring(3);
        removeProductRow(c);
    }
}*/
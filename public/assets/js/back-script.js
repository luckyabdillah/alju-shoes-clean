// new DataTable('#data-table', {
//     buttons: [
//         {
//             extend: 'columnVisibility',
//             text: 'Payroll',
//             visibility: true,
//             columns: '3'
//         }
//     ]
// });

const flashData = $('.flash-data').data('flash');
const flashDataFailed = $('.flash-data-failed').data('flash');

if (flashData) {
    Swal({
        title: 'Success',
        text: flashData,
        type: 'success'
    })
}

if (flashDataFailed) {
    Swal({
        title: 'Oops!',
        text: flashDataFailed,
        type: 'error'
    })
}

$(document).on('click', '.btn-confirm', function (event) {
    let form = $(this).closest("form");
    let name = $(this).data("name");
    event.preventDefault();
    Swal({
        title: "Konfirmasi",
        text: "Yakin ingin konfirmasi orderan ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            form.submit();
        }
    });
});

$(document).on('click', '.btn-status', function (event) {
    let form = $(this).closest("form");
    let name = $(this).data("name");
    event.preventDefault();
    Swal({
        title: "Ubah Status",
        text: "Yakin ingin merubah status?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            form.submit();
        }
    });
});

$(document).on('click', '.btn-delete', function (event) {
    let form = $(this).closest("form");
    let name = $(this).data("name");
    event.preventDefault();
    Swal({
        title: "Hapus Data",
        text: "Yakin ingin menghapus data ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            form.submit();
        }
    });
});

$(document).on('click', '.btn-customer-detail', function(e) {
    e.preventDefault();
    let data = JSON.parse($(this).attr('data'));

    $('#name').val(data.name)
    $('#number').val(data.number)
    $('#address').text(data.address)
    $('#last_order').val(data.last_order)
    $('#location').attr('href', `https://www.google.com/maps/place/${data.location}`)

    $('#customerDetailModal').modal('show')
})

$(document).on('click', '.btn-transaction-done', function(e) {
    e.preventDefault();
    let uuid = $(this).data('uuid');

    $('#transasctionDoneForm').attr('action', `/dashboard/transaction/dropzone/${uuid}/status-update`);
    $('#uuid').val(uuid);

    $('#transactionDoneModal').modal('show');
});

$(document).on('click', '.btn-delivery-done', function(e) {
    e.preventDefault();
    let uuid = $(this).data('uuid');

    $('#transasctionDoneForm').attr('action', `/dashboard/transaction/pickup-delivery/${uuid}/status-update`);
    $('#uuid').val(uuid);

    $('#transactionDoneModal').modal('show');
})

$(document).on('click', '.btn-print', function(e) {
    e.preventDefault();

    let originalContent = document.body.innerHTML;
    let invoiceNo = $(this).data('invoice');

    const data = `
        <div class="" style="font-weight: 800; font-size: 3em;">${invoiceNo}</div>
    `;

    document.body.innerHTML = data;
    window.print();
    document.body.innerHTML = originalContent;
})
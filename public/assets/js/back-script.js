new DataTable('#data-table', {
    buttons: [
        {
            extend: 'columnVisibility',
            text: 'Payroll',
            visibility: true,
            columns: '3'
        }
    ]
});

const flashData = $('.flash-data').data('flash');

if (flashData) {
    Swal({
        title: 'Success',
        text: flashData,
        type: 'success'
    })
}

// $(".btn-status").click(function (event) {
//     var form = $(this).closest("form");
//     var name = $(this).data("name");
//     event.preventDefault();
//     swal({
//         title: "Change Status",
//         text: "Are you sure want to change the status?",
//         icon: "warning",
//         // type: "warning",
//         buttons: ["Cancel", "Yes!"],
//         // confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         position: "center",
//         dangerMode: true,
//         // confirmButtonText: 'Yes, delete it!'
//     }).then((willDelete) => {
//         if (willDelete) {
//             form.submit();
//         }
//     });
// });

$(document).on('click', '.btn-status', function (event) {
    let form = $(this).closest("form");
    let name = $(this).data("name");
    event.preventDefault();
    Swal({
        title: "Change Status",
        text: "Are you sure want to change the status?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            form.submit();
        }
    });
});

// $(".btn-delete").click(function (event) {
//     var form = $(this).closest("form");
//     var name = $(this).data("name");
//     event.preventDefault();
//     swal({
//         title: "Delete Data",
//         text: "Are you sure want to delete this data?",
//         icon: "warning",
//         // type: "warning",
//         buttons: ["Cancel", "Yes!"],
//         // confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         position: "center",
//         dangerMode: true,
//         // confirmButtonText: 'Yes, delete it!'
//     }).then((willDelete) => {
//         if (willDelete) {
//             form.submit();
//         }
//     });
// });

$(document).on('click', '.btn-delete', function (event) {
    let form = $(this).closest("form");
    let name = $(this).data("name");
    event.preventDefault();
    Swal({
        title: "Delete Data",
        text: "Are you sure want to delete this data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            form.submit();
        }
    });
});

// $(".btn-detail-pending").click(function (event) {
//     event.preventDefault();
//     let data = JSON.parse($(this).attr('data'));

//     // $('#detailModalLabel').text(data.invoiceNo);
//     // $('#customer-name').text(data.customer);
//     // $('#outlet').text(data.outlet);
//     // $('#start-date').text(data.start);
//     // $('#end-date').text(data.end);
//     // $('#total-items').text(data.totalItems);
//     // $('#total-amount').text(data.totalAmount);

//     let details = data.detailTransactions;
//     let detail = '';
//     details.forEach(items => {
//         detail += `- ${items.item_name}\n`;
//     })

//     let text = `
//         Customer Name : ${data.customer}
//         Outlet : ${data.outlet}
//         Start Date : ${data.start}
//         End Date : ${data.end}
//         Total Items : ${data.totalItems}
//         Total Amount : ${data.totalAmount}
//         Detail Transaction :
//         ${detail}
//     `

//     // $('#detail-transaction').html(detail);

//     swal({
//         title: "Delete Data",
//         text: text,
//         // icon: "warning",
//         // type: "warning",
//         // buttons: ["Cancel", "Yes!"],
//         // confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         position: "center",
//         dangerMode: true,
//     })
// });

$(document).on('click', '.btn-detail-pending', function(e) {
    e.preventDefault();
    let data = JSON.parse($(this).attr('data'));

    $('#detailModalLabel').text('Detail: ' + data.invoiceNo);
    $('#customer-name').text(data.customer);
    $('#outlet').text(data.outlet);
    $('#start-date').text(data.start);
    $('#end-date').text(data.end);
    $('#total-items').text(data.totalItems);
    $('#total-amount').text('IDR ' + data.totalAmount.toLocaleString('en-US'));
    
    // console.log(data.detailTransactions);

    let details = data.detailTransactions;

    let detail = '';
    details.forEach(items => {
        detail += `<li>${items.item_name}${(items.size ? ', ' + items.size + '<br>' : '<br>')}</li>`;
    })

    $('#detail-transaction').html(detail);

    $('#detailModal').modal('show');
})

$(document).on('click', '.btn-customer-detail', function(e) {
    e.preventDefault();
    let data = JSON.parse($(this).attr('data'));

    $('#name').text(data.name)
    $('#number').text(data.number)
    $('#address').text(data.address)
    $('#last_order').text(data.last_order)
    $('#location').html(`<a href="${data.location}" target="_blank"><b>Maps</b></a>`)

    $('#customerDetailModal').modal('show')
})

$(document).on('click', '.btn-transaction-done', function(e) {
    e.preventDefault();
    let uuid = $(this).data('uuid');

    $('#transasctionDoneForm').attr('action', `/dashboard/transaction/dropzone/${uuid}/status-update`);
    $('#uuid').val(uuid);

    $('#transactionDoneModal').modal('show');
})

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
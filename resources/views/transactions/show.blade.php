<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="web_path" content="{{ asset('') }}">
        <title>Alju Shoes Clean | Laundry Sepatu Terbaik se-Jakarta Pusat</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/img/icons/alju-logo-circle-modified.ico') }}" />
        <link rel="apple-touch-icon" href="{{ asset('assets/img/icons/alju-logo-circle-modified.png') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/front-style.css') }}" />
        <style>
            table td {
                vertical-align: middle;
            }
        </style>
    </head>

    <body>
        
        <div class="container mt-5">
            <h5 class="text-center mb-3">TRANSACTION – PRINT (<span id="outlet">{{ $transaction->outlet->outlet_name }}</span>)</h5>
            <input type="hidden" name="invoice_no" id="invoice_no" value="{{ $transaction->invoice_no }}">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">No HP</th>
                        <th class="text-center">Treatment</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Merk</th>
                        <th class="text-center">Ukuran</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->transaction_details as $detail)
                        <tr class="item">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center customer-name">{{ $transaction->customer->name }}</td>
                            <td class="text-center whatsapp-number">{{ $transaction->customer->whatsapp_number }}</td>
                            <td class="text-center treatment">{{ $detail->treatment_name }}</td>
                            <td class="text-center type">{{ ucfirst($detail->type) }}</td>
                            <td class="text-center merk">{{ $detail->merk }}</td>
                            <td class="text-center size">{{ $detail->size ?? '-' }}</td>
                            <td class="text-center description">{{ $detail->description }}</td>
                            <td class="text-center">
                                <button class="btn btn-my-primary btn-print">Print</button>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center mt-4">
                <a href="/" class="btn btn-secondary">Home</a>
                <a href="/dashboard" class="btn btn-outline-secondary">Dashboard</a>
            </div>
        </div>

        {{-- <div class="row g-0 border-1">
            <div class="col-6 bg-dark text-white border-1">
                <span class="d-block">ALJU SHOES CLEAN</span>
                <span class="d-block mt-2 fw-bold">${invoiceNo}</span>
            </div>
            <div class="col-6 border-1">
                <span class="d-block fw-bold">OUTLET</span>
                <span class="d-block mt-2">${outletName}</span>
            </div>
            <div class="col-12 border-1">
                <span class="d-block fw-bold">${customerName}</span>
            </div>
            <div class="col-4 border-1">
                <span class="fw-bold">${type}</span>
                <span class="d-block mt-2">${merk}</span>
            </div>
            <div class="col-4 border-1">
                <span class="fw-bold">Ukuran</span>
                <span class="d-block mt-2">${size}</span>
            </div>
            <div class="col-4 border-1">
                <span class="fw-bold">Deskripsi</span>
                <span class="d-block mt-2">${description}</span>
            </div>
            <div class="col-6 border-1">
                <span class="fw-bold">${treatment}</span>
            </div>
            <div class="col-6 border-1">
                <span class="fw-bold">${treatment}</span>
            </div>
        </div> --}}

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <!-- endbuild -->

        <script>
            $(document).on('click', '.btn-print', function (e) {
                const parent = e.target.closest('.item')

                const invoiceNo = document.querySelector('#invoice_no').value
                const outletName = document.querySelector('#outlet').innerText
                const customerName = parent.querySelector('.customer-name').innerText
                const whatsappNumber = parent.querySelector('.whatsapp-number').innerText
                const treatment = parent.querySelector('.treatment').innerText
                const type = parent.querySelector('.type').innerText
                const merk = parent.querySelector('.merk').innerText
                const size = parent.querySelector('.size').innerText
                const description = parent.querySelector('.description').innerText

                const treatmentParent = treatment.split('–')[0]
                const treatmentDetail = treatment.split('–')[1]

                // const container = `
                // <html>
                //     <head>
                //         <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" />
                //         <style>
                //             * {
                //                 font-family: sans-serif;
                //             }
                //         </style>
                //     </head>
                //     <body>
                //         <div class="container text-center">
                //             <table class="text-center mx-auto" cellspacing="0" cellpadding="10" border="1">
                //                 <tr>
                //                     <td class="bg-dark text-white">
                //                         ALJU SHOES CLEAN
                //                         <span style="display: block; margin-top: 5px; font-weight: bold;">${invoiceNo}</span>
                //                     </td>
                //                     <td colspan="2">
                //                         <span style="font-weight: bold;">OUTLET</span>
                //                         <span style="display: block; margin-top: 5px;">${outletName}</span>
                //                     </td>
                //                 </tr>
                //                 <tr>
                //                     <td colspan="3" style="font-weight: bold;">${customerName}</td>
                //                 </tr>
                //                 <tr>
                //                     <td>
                //                         <span style="font-weight: bold;">${type}</span>
                //                         <span style="display: block; margin-top: 5px;">${merk}</span>
                //                     </td>
                //                     <td>
                //                         <span style="font-weight: bold;">Ukuran</span>
                //                         <span style="display: block; margin-top: 5px;">${size}</span>
                //                     </td>
                //                     <td>
                //                         <span style="font-weight: bold;">Deskripsi</span>
                //                         <span style="display: block; margin-top: 5px;">${description}</span>
                //                     </td>
                //                 </tr>
                //                 <tr>
                //                     <td colspan="3">
                //                         <span style="font-weight: bold;">${treatment}</span>
                //                     </td>
                //                 </tr>
                //             </table>
                //         </div>
                //     </body>
                // </html>
                // `

                const container = `
                <html>
                    <head>
                        <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" />
                        <style>
                            * {
                                font-family: sans-serif;
                            }

                            @media print {
                                .bg-dark {
                                    background-color: black !important;
                                    print-color-adjust: exact;
                                    -webkit-print-color-adjust: exact;
                                }

                                .text-white {
                                    color: white !important;
                                    print-color-adjust: exact;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container text-center mt-2">
                            <div class="row g-0 border border-black">
                                <div class="col-6 py-2 bg-dark text-white border border-black">
                                    <span>ALJU SHOES CLEAN</span>
                                    <span class="d-block fw-bold">${invoiceNo}</span>
                                </div>
                                <div class="col-6 py-2 border border-black">
                                    <span class="fw-bold">OUTLET</span>
                                    <span class="d-block">${outletName}</span>
                                </div>
                                <div class="col-12 py-3 border border-black">
                                    <span class="d-block fw-bold">${customerName}</span>
                                </div>
                                <div class="col-4 py-2 border border-black">
                                    <span class="fw-bold">${type}</span>
                                    <span class="d-block">${merk}</span>
                                </div>
                                <div class="col-4 py-2 border border-black">
                                    <span class="fw-bold">Ukuran</span>
                                    <span class="d-block">${size}</span>
                                </div>
                                <div class="col-4 py-2 border border-black">
                                    <span class="fw-bold">Deskripsi</span>
                                    <span class="d-block">${description}</span>
                                </div>
                                <div class="col-6 py-3 border border-black">
                                    <span class="fw-bold">${treatmentParent}</span>
                                </div>
                                <div class="col-6 py-3 border border-black">
                                    <span class="fw-bold">${treatmentDetail}</span>
                                </div>
                            </div>
                        </div>
                    </body>
                </html>
                `

                let newWindow = window.open("", "MsgWindow", "width=600,height=400");
                
                newWindow.document.write(container);
                newWindow.document.title = "Print";

                setTimeout(() => {
                    newWindow.print();
                }, 500);
            })
        </script>
    </body>
</html>
@extends('dashboard.layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card bg-mine-danger mb-3">
            <h6 class="card-header text-white py-3">
                Selamat datang kembali di Alju Shoes Clean System, {{ auth()->user()->name }}!
            </h6>
        </div>
        <div class="row g-3">
            @canany(['operation', 'administrator'])
                <div class="col-lg col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar text-success border border-2 border-success d-flex align-items-center justify-content-center rounded">
                                    <i class="bx bx-store fs-4"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="undoneDropzoneTransactions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="undoneDropzoneTransactions">
                                        <a class="dropdown-item" href="/dashboard/transaction/dropzone">View More</a>
                                    </div>
                                </div>
                            </div>
                            <span class="d-block mb-1">Undone Dropzone Transactions</span>
                            <h3 class="card-title text-nowrap mb-1 fs-5">{{ $undoneDropzoneTransactions }} Transactions</h3>
                        </div>
                    </div>
                </div>
            @endcanany
            @canany(['driver', 'administrator'])
                <div class="col-lg col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar text-warning border border-2 border-warning d-flex align-items-center justify-content-center rounded">
                                    <i class="bx bx bx-cycling fs-4"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="undonePDTransactions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="undonePDTransactions">
                                        <a class="dropdown-item" href="/dashboard/transaction/pickup-delivery">View More</a>
                                    </div>
                                </div>
                            </div>
                            <span class="d-block mb-1">Undone Pickup-Delivery Transactions</span>
                            <h3 class="card-title text-nowrap mb-1 fs-5">{{ $undonePDTransactions }} Transactions</h3>
                        </div>
                    </div>
                </div>
            @endcanany
        </div>
    </div>
@endsection
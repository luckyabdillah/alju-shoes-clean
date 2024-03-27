@extends('layouts.main')

@section('content')
    
    <nav class="summary">
        @if (session()->has('success'))
            <div class="flash-data" data-flash="{{ session('success') }}"></div>
        @endif
        <div class="container mt-4 mb-5">
            <h6>Identitas</h6>
            <ul>
                <li>Nama: {{ $transaction->customer->name }}</li>
                <li>Nomor WhatsApp: +{{ $transaction->customer->whatsapp_number }}</li>
                <li>Alamat: {{ $transaction->customer->address }}</li>
            </ul>
            <hr>
            <h6>Order</h6>
            <ul>
                <li>Mulai: {{ $transaction->transaction_start }}</li>
                <li>Selesai: {{ $transaction->transaction_end }}</li>
                <li>Total Item: {{ $transaction->total_items }}</li>
                <li>Total Harga: IDR {{ number_format($transaction->total_amount, 0, '.', ',') }}</li>
            </ul>
            <hr>
            <h6>Detail Order</h6>
            @foreach ($transaction['detail_transactions'] as $item)
                <span>Item {{ $loop->iteration }}</span>
                <ul>
                    <li>Tipe: {{ $item['type'] }}</li>
                    <li>Merk: {{ $item['merk'] }}</li>
                    <li>Deskripsi: {{ $item['description'] }}</li>
                    <li>Treatment: {{ $item['treatment_name'] }}</li>
                </ul>
            @endforeach
            <div class="mb-3">
                <a href="/" class="btn btn-outline-my-primary">Ke Halaman Utama</a>
                <a href="/order" class="btn btn-my-primary">Order Lagi</a>
            </div>
        </div>
    </nav>

@endsection

@section('scripts')
    <script>
        const flashData = $('.flash-data').data('flash');

        if (flashData) {
            Swal({
                title: 'Success',
                text: flashData,
                type: 'success'
            })
        }
    </script>
@endsection
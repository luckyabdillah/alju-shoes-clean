@extends('layouts.main')

@section('content')
    
    <nav class="summary">
        <div class="container mt-4 mb-5">
            <form action="/order/summary" method="post">
            @csrf
                <h6>Identitas</h6>
                <ul>
                    <li>Nama: {{ $identity['name'] }}</li>
                    <li>Nomor WhatsApp: +62{{ $identity['whatsapp_number'] }}</li>
                    <li>Alamat: {{ $identity['address'] }}</li>
                </ul>
                <hr>
                <h6>Order</h6>
                <ul>
                    <li>Mulai: {{ $transactions['transaction_start'] }}</li>
                    <li>Selesai: {{ $transactions['transaction_end'] }}</li>
                    <li>Total Item: {{ $transactions['total_item'] }}</li>
                    <li>Total Harga: IDR {{ number_format($transactions['cost'], 0, '.', ',') }}</li>
                </ul>
                <hr>
                <h6>Detail Order</h6>
                @foreach ($transactions['detail_transactions'] as $item)
                    <span>Item {{ $loop->iteration }}</span>
                    <ul>
                        <li>Tipe: {{ $item['type'] }}</li>
                        <li>Merk: {{ $item['merk'] }}</li>
                        <li>Deskripsi: {{ $item['description'] }}</li>
                        <li>Treatment: {{ $item['treatment_name'] }}</li>
                    </ul>
                @endforeach
                <hr>
                <label class="form-label">Pilih metode pembayaran</label>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                        <label class="form-check-label" for="cash">Cash</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="qris" value="qris">
                        <label class="form-check-label" for="qris">QRIS</label>
                    </div>
                </div>
                <div class="qris">
                    <img src="{{ asset('assets/img/icons/qris-alju-crop.jpg') }}" alt="QRIS Alju Shoes Clean" class="img-thumbnail img-fluid mb-3">
                    <div class="mb-3">
                        <label for="proof_of_payment" class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control">
                    </div>
                </div>
                <div class="mt-3">
                    <a href="/order/details" class="btn btn-outline-my-primary">Menu sebelumnya</a>
                    <button type="submit" class="btn btn-my-primary">Proses Order</button>
                </div>
            </form>
        </div>
    </nav>

@endsection
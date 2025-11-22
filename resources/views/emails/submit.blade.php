@component('mail::message')

Hi {{ $name }},

Terima kasih telah menggunakan layanan kami. Orderan kamu dengan nomor invoice **{{ $transaction->invoice_no }}** akan kami kerjakan dengan sepenuh hati.

@component('mail::button', ['url' => config('app.url') . "/invoice/$transaction->uuid"])
Lihat Invoice
@endcomponent

Jika Anda tidak mengajukan permintaan ini atau mencurigai adanya penyalahgunaan email, segera hubungi tim dukungan kami di [support@aljushoesclean.com](mailto:support@aljushoesclean.com)

Salam Hangat,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Jika tombol "Lihat Invoice" bermasalah, salin dan tempel URL di bawah ini ke browser web kamu: [{{ config('app.url') }}/invoice/{{ $transaction->uuid }}]({{ config('app.url') }}/invoice/{{ $transaction->uuid }})
@endcomponent

@endcomponent
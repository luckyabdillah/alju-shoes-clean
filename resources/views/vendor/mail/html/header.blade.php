@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ config('app.url') }}/assets/img/icons/alju-logo-circle.png" class="logo" alt="{{ $slot }} Logo">
@endif
</a>
</td>
</tr>

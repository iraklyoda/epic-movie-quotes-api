@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('images/mail_header.svg')}}" class="logo" alt="Laravel Logo">
        <p>MOVIE QUOTES</p>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

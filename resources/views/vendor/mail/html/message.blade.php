@props([
    'title' => 'GradConn Notification',
    'eyebrow' => 'Official GradConn Update',
])
@php
    $mailPublicUrl = rtrim((string) config('app.url'), '/');
    if ($mailPublicUrl === '' || str_contains($mailPublicUrl, 'localhost') || str_contains($mailPublicUrl, '127.0.0.1')) {
        $mailPublicUrl = 'https://gradconn.onrender.com';
    }
@endphp
<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="$mailPublicUrl">
<span class="brand-line"><img class="email-logo" src="{{ $mailPublicUrl }}/ccc3d.png" width="64" height="64" alt="City College of Calapan logo"><span class="brand-text">GradConn</span></span>
<span class="brand-eyebrow">{{ $eyebrow }}</span>
<span class="brand-title">{{ $title }}</span>
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>

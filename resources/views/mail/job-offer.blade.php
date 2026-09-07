<x-mail::message title="Job Offer" eyebrow="Career Opportunity">
# {{ $offer->subject }}

Hello {{ $offer->alumni->fullname ?: 'Alumni' }},

{{ $offer->message }}

This job offer was sent by **{{ $offer->employer->fullname }}** through {{ config('app.name') }}.

<x-mail::panel>
Click the button below to confirm that you accept this job offer. Once you confirm, the employer will be notified automatically.
</x-mail::panel>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:24px 0;">
<tr>
<td style="border-radius:12px;background:#f97316;">
<a href="{{ route('job-offers.accept', $offer) }}" style="display:inline-block;padding:13px 22px;border-radius:12px;color:#ffffff;font-weight:700;text-decoration:none;">Accept Job Offer</a>
</td>
</tr>
</table>

<p style="margin:0;color:#6b7280;font-size:13px;">This link is unique to your offer and will record your response securely.</p>

Regards,  
{{ config('app.name') }}
</x-mail::message>

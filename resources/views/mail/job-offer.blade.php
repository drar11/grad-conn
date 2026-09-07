<x-mail::message title="Job Offer" eyebrow="Career Opportunity">
# {{ $offer->subject }}

Hello {{ $offer->alumni->fullname ?: 'Alumni' }},

{{ $offer->message }}

This job offer was sent by **{{ $offer->employer->fullname }}** through {{ config('app.name') }}.

<x-mail::panel>
To respond, sign in to GradConn and open **Browse Jobs → Job Offers**. You can review the offer there and choose whether to accept or decline it. Once accepted, the employer can send your interview details through GradConn.
</x-mail::panel>

Regards,  
{{ config('app.name') }}
</x-mail::message>

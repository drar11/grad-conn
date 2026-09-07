<x-mail::message title="Job Offer Accepted" eyebrow="Career Opportunity">
# Job offer accepted

Hello {{ $offer->employer->fullname ?: 'Employer' }},

{{ $offer->alumni->fullname ?: 'The alumni recipient' }} has accepted your job offer for **{{ $offer->subject }}**.

<x-mail::panel>
Accepted at: **{{ optional($offer->accepted_at)->format('F j, Y \\a\\t g:i A') }}**  
Alumni: **{{ $offer->alumni->fullname ?: 'N/A' }}**  
Email: **{{ $offer->alumni->email ?: 'N/A' }}**
</x-mail::panel>

<x-mail::button :url="route('employer.alumni_list')">
View alumni directory
</x-mail::button>

Thanks,  
{{ config('app.name') }}
</x-mail::message>

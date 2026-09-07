<?php

namespace App\Http\Controllers\JobOffer;

use App\Http\Controllers\Controller;
use App\Mail\JobOfferAcceptedMail;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

final class AcceptJobOfferController extends Controller
{
    public function __invoke(Request $request, JobOffer $jobOffer)
    {
        $jobOffer->loadMissing(['employer', 'alumni']);

        if ($jobOffer->expires_at && $jobOffer->expires_at->isPast() && $jobOffer->status === 'sent') {
            $jobOffer->forceFill(['status' => 'expired'])->save();

            return view('pages.job_offer_response', [
                'offer' => $jobOffer,
                'state' => 'expired',
                'headline' => 'This offer has expired',
                'message' => 'This job offer is no longer active. Please contact the employer directly if you still want to pursue it.',
            ]);
        }

        if ($jobOffer->status === 'accepted') {
            return view('pages.job_offer_response', [
                'offer' => $jobOffer,
                'state' => 'accepted',
                'headline' => 'Offer already accepted',
                'message' => 'Your acceptance is already on record. The employer has already been notified.',
            ]);
        }

        if ($jobOffer->status !== 'sent') {
            return view('pages.job_offer_response', [
                'offer' => $jobOffer,
                'state' => $jobOffer->status,
                'headline' => 'This offer is no longer available',
                'message' => 'This job offer has already been processed.',
            ]);
        }

        $jobOffer->forceFill([
            'status' => 'accepted',
            'accepted_at' => now(),
        ])->save();

        if (filter_var($jobOffer->employer?->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($jobOffer->employer->email, $jobOffer->employer->fullname)->queue(new JobOfferAcceptedMail($jobOffer));
        }

        return view('pages.job_offer_response', [
            'offer' => $jobOffer,
            'state' => 'accepted',
            'headline' => 'Job offer accepted',
            'message' => 'Your acceptance has been recorded and the employer has been notified.',
        ]);
    }
}

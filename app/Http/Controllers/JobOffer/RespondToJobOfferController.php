<?php

namespace App\Http\Controllers\JobOffer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RespondToJobOfferRequest;
use App\Mail\JobOfferAcceptedMail;
use App\Models\EmployerActivityLog;
use App\Models\JobOffer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

final class RespondToJobOfferController extends Controller
{
    public function __invoke(RespondToJobOfferRequest $request, JobOffer $jobOffer)
    {
        abort_unless((int) $jobOffer->alumni_id === (int) $request->user()->id, 403);
        abort_if($jobOffer->status !== 'sent', 422, 'This job offer has already been answered.');
        $action = $request->string('action')->toString();

        $jobOffer = DB::transaction(function () use ($jobOffer, $action) {
            $offer = JobOffer::query()->lockForUpdate()->findOrFail($jobOffer->id);
            abort_if($offer->status !== 'sent', 422, 'This job offer has already been answered.');
            abort_if($offer->expires_at?->isPast(), 422, 'This job offer has expired.');
            $offer->forceFill(['status' => $action === 'accept' ? 'accepted' : 'declined', $action === 'accept' ? 'accepted_at' : 'declined_at' => now()])->save();
            EmployerActivityLog::query()->create(['employer_id' => $offer->employer_id, 'alumni_id' => $offer->alumni_id, 'offer_id' => $offer->id, 'action' => $action === 'accept' ? 'job_offer_accepted' : 'job_offer_declined', 'details' => 'Alumni responded to the job offer: '.$offer->subject]);
            return $offer;
        });

        if ($action === 'accept') {
            $jobOffer->load(['employer', 'alumni']);
            if (filter_var($jobOffer->employer?->email, FILTER_VALIDATE_EMAIL)) Mail::to($jobOffer->employer)->queue(new JobOfferAcceptedMail($jobOffer));
        }
        return to_route('alumni.job_offers')->with('status', $action === 'accept' ? 'Job offer accepted. The employer can now send your interview details.' : 'Job offer declined.');
    }
}

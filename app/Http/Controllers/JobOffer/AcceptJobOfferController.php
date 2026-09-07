<?php

namespace App\Http\Controllers\JobOffer;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\Request;

final class AcceptJobOfferController extends Controller
{
    public function __invoke(Request $request, JobOffer $jobOffer)
    {
        return redirect()->route('login')->with('status', 'Sign in and open Browse Jobs → Job Offers to respond to this offer.');
    }
}

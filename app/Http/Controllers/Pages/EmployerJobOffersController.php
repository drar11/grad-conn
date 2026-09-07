<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\PageController;
use App\Models\JobOffer;
use Illuminate\Http\Request;

final class EmployerJobOffersController extends PageController
{
    public function __invoke(Request $request)
    {
        return $this->renderPage(function () use ($request) {
            $offers = JobOffer::query()->with(['alumni', 'interview'])->where('employer_id', $request->user()->id)->latest('id')->get();
            return $this->pageView('pages.employer.job_offers', get_defined_vars());
        });
    }
}

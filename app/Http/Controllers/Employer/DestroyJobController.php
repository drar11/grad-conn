<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

final class DestroyJobController extends Controller
{
    public function __invoke(Job $job): RedirectResponse
    {
        Gate::authorize('delete', $job);

        if ($job->applications()->exists()) {
            $job->update(['is_open' => false]);

            return to_route('employer.posted_job')->with('status', 'This posting has applications, so it was closed and kept for application history.');
        }

        $job->delete();
        Cache::forget('feed.sidebar-jobs.v1');

        return to_route('employer.posted_job')->with('status', 'Job posting deleted.');
    }
}

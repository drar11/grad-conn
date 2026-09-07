<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\FileController;
use App\Http\Controllers\PageController;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

final class EmployerApplicationsController extends PageController
{
    public function __invoke(Request $request)
    {
        if ($request->filled('view_resume')) {
            return app(FileController::class)->resume($request);
        }

        if (! $request->filled('job_id')) {
            return to_route('employer.posted_job');
        }

        return $this->renderPage(function () use ($request) {
            $employer_id = $request->user()->id;
            $success = session('status', '');
            $error = '';
            $job = null;
            if ($request->filled('job_id')) {
                $job = Job::query()
                    ->whereKey($request->integer('job_id'))
                    ->where('posted_by', $employer_id)
                    ->firstOrFail();
            }

            $models = JobApplication::query()
                ->select(['id', 'job_id', 'alumni_id', 'applicant_fullname', 'applicant_email', 'applicant_course', 'applicant_batch_year', 'applicant_career_objective', 'applicant_skills', 'message', 'resume_file', 'status', 'cancel_reason', 'cancelled_at', 'created_at'])
                ->with(['job:id,title,company,employer_company', 'alumni.education', 'alumni.employmentHistory'])
                ->whereHas('job', fn ($q) => $q->where('posted_by', $employer_id))
                ->when($job, fn ($query) => $query->where('job_id', $job->id))
                ->orderByDesc('id')->paginate(50)->withQueryString();
            $applications = $models->getCollection()->map(fn ($application) => [
                'application_id' => $application->id,
                'job_id' => $application->job_id,
                'job_title' => $application->job?->title,
                'company' => $application->job?->company,
                'fullname' => $application->applicant_fullname ?: $application->alumni?->fullname,
                'email' => $application->applicant_email ?: $application->alumni?->email,
                'course' => $application->applicant_course ?: $application->alumni?->course,
                'batch_year' => $application->applicant_batch_year ?: $application->alumni?->batch_year,
                'career_objective' => $application->applicant_career_objective ?: $application->alumni?->career_objective,
                'competencies' => $application->applicant_skills ?: $application->alumni?->skills,
                'message' => $application->message,
                'resume_file' => $application->resume_file,
                'status' => $application->status,
                'cancel_reason' => $application->cancel_reason,
                'cancelled_at' => $application->cancelled_at,
                'created_at' => $application->created_at,
                'alumni_id' => $application->alumni_id,
                'profile_picture' => $application->alumni?->profile_picture,
                'age' => $application->alumni?->age,
                'address' => $application->alumni?->address,
                'work_experience' => $application->alumni?->work_experience,
                'education' => $application->alumni?->education?->map->getAttributes()->all() ?? [],
                'employment_history' => $application->alumni?->employmentHistory?->map->getAttributes()->all() ?? [],
            ]);

            return $this->pageView('pages.employer.applications', get_defined_vars());
        });
    }
}

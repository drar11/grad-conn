@extends('layouts.authenticated')
@section('title', 'Job List · GradConn')
@section('heading', 'Jobs & Applications')
@push('styles')
<style>
.jobs-page{max-width:1500px}.jobs-header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px}.jobs-header h1{margin:0;color:#0f172a;font-size:30px}.post-job-btn{padding:13px 22px;border-radius:13px;background:linear-gradient(135deg,#f97316,#ea580c);box-shadow:0 6px 16px rgba(249,115,22,.25);color:#fff;font-weight:800;text-decoration:none}.job-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.job-card{display:flex;flex-direction:column;min-height:310px;padding:28px 30px;border:1px solid #dbe5f3;border-left:5px solid #f97316;border-radius:18px;background:#fff;box-shadow:0 7px 22px rgba(15,23,42,.06);transition:.25s}.job-card:hover{transform:translateY(-3px);box-shadow:0 13px 30px rgba(15,23,42,.1)}.job-card__top{display:flex;justify-content:space-between;gap:20px}.job-card h2{margin:0 0 10px;color:#0f172a;font-size:23px}.company{margin:0 0 15px;color:#5d7392;font-size:16px;font-weight:600}.meta{margin:7px 0;color:#64748b;font-size:14px}.poster-badge{display:inline-block;margin-top:11px;padding:8px 14px;border-radius:999px;background:#fef3c7;color:#b45309;font-size:11px;font-weight:900;letter-spacing:.04em;text-transform:uppercase}.status{height:max-content;padding:9px 18px;border-radius:999px;color:#fff;font-size:12px;font-weight:900;text-transform:uppercase}.status.open{background:#159447}.status.closed{background:#64748b}.job-actions{display:flex;align-items:center;gap:10px;margin-top:auto;padding-top:19px;border-top:1px solid #dbe3ee}.job-actions a,.delete-job-btn{display:inline-flex;align-items:center;justify-content:center;padding:11px 17px;border:1px solid #cbd5e1;border-radius:11px;background:#fff;color:#172033;font-weight:800;text-decoration:none;cursor:pointer}.job-actions a:hover{border-color:#f97316;color:#ea580c}.delete-job-btn{border-color:#fecaca;color:#b91c1c}.delete-job-btn:hover{background:#dc2626;color:#fff}.empty{padding:50px;border:2px dashed #cbd5e1;border-radius:18px;background:#fff;text-align:center;color:#64748b}@media(max-width:850px){.job-grid{grid-template-columns:1fr}.jobs-header{align-items:flex-start}.job-card{padding:22px}}
</style>
@endpush
@section('content')
<div class="jobs-page">
 <header class="jobs-header"><h1>Job List</h1><a class="post-job-btn" href="{{ route('employer.post_job') }}">Post Job</a></header>
 @if($error)<div class="flash error">{{ $error }}</div>@endif
 <div class="job-grid">
 @forelse($posted_jobs as $job)
  @php($expired = !empty($job['end_date']) && $today > $job['end_date'])
  @php($open = (int) $job['is_open'] === 1 && !$expired)
  <article class="job-card">
   <div class="job-card__top"><div><h2>{{ $job['title'] }}</h2><p class="company">{{ $job['employer_company'] ?: $job['company'] }}@if($job['location']) · {{ $job['location'] }}@endif</p><p class="meta">Type: {{ $job['job_type'] ?: 'N/A' }} · Posted by: {{ $job['employer_company'] ?: $job['company'] }}</p><p class="meta">Applications: {{ $job['total_applications'] }}</p><span class="poster-badge">Posted by Employer</span></div><span class="status {{ $open ? 'open' : 'closed' }}">{{ $open ? 'Open' : 'Closed' }}</span></div>
   <div class="job-actions"><a href="{{ route('employer.applications', ['job_id' => $job['id']]) }}">View Applications</a><form method="POST" action="{{ route('employer.jobs.destroy', $job['id']) }}" onsubmit="return confirm('Delete this job posting? Postings with applications will be closed instead.');">@csrf @method('DELETE')<button class="delete-job-btn" type="submit">Delete Job</button></form></div>
  </article>
 @empty<div class="empty">No job posts yet.</div>@endforelse
 </div>
</div>
@endsection

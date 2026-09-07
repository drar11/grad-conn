@extends('layouts.auth')

@section('title', config('app.name').' · Job Offer Response')

@section('content')
<style>
    body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(135deg,#0f172a,#1f2937 55%,#f97316);min-height:100vh;display:grid;place-items:center;color:#0f172a}
    .wrap{width:min(720px,calc(100% - 24px));padding:24px}
    .card{background:#fff;border-radius:24px;box-shadow:0 25px 90px rgba(15,23,42,.32);overflow:hidden}
    .card-head{padding:26px 28px;background:linear-gradient(135deg,#172033,#f97316);color:#fff}
    .eyebrow{display:block;margin-bottom:8px;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#fed7aa}
    h1{margin:0;font-size:30px;line-height:1.2}
    .card-body{padding:28px}
    .message{margin:0 0 18px;color:#334155;font-size:16px;line-height:1.7}
    .panel{margin:18px 0 24px;padding:16px 18px;border:1px solid #fed7aa;border-radius:16px;background:#fff7ed;color:#9a3412}
    .actions{display:flex;flex-wrap:wrap;gap:12px}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 18px;border-radius:12px;text-decoration:none;font-weight:800}
    .btn-primary{background:#f97316;color:#fff}
    .btn-secondary{background:#fff;border:1px solid #cbd5e1;color:#172033}
    .meta{margin-top:18px;color:#64748b;font-size:13px}
    @media(max-width:640px){.card-head,.card-body{padding:22px}.actions{flex-direction:column}.btn{width:100%}}
</style>

<div class="wrap">
    <div class="card">
        <div class="card-head">
            <span class="eyebrow">GradConn Job Offer</span>
            <h1>{{ $headline }}</h1>
        </div>
        <div class="card-body">
            <p class="message">{{ $message }}</p>
            <div class="panel">
                <strong>{{ $offer->subject }}</strong><br>
                {{ $offer->employer?->fullname ?: 'GradConn Employer' }}
            </div>
            <div class="actions">
                <a class="btn btn-primary" href="{{ route('login') }}">Go to GradConn</a>
                <a class="btn btn-secondary" href="{{ route('alumni.my_applications') }}">My Applications</a>
            </div>
            <div class="meta">Offer status: <strong>{{ ucfirst($state) }}</strong></div>
        </div>
    </div>
</div>
@endsection

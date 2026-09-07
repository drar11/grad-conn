<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class EmployerActivityLog extends Model
{
    public $timestamps = false;

    protected $table = 'employer_activity_logs';

    protected $guarded = ['id', 'created_at'];

    public function employer() { return $this->belongsTo(User::class, 'employer_id'); }
    public function alumni() { return $this->belongsTo(User::class, 'alumni_id'); }
    public function offer() { return $this->belongsTo(JobOffer::class, 'offer_id'); }
}

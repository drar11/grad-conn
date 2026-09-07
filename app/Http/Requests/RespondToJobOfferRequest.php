<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RespondToJobOfferRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->role === 'alumni'; }
    public function rules(): array { return ['action' => ['required', Rule::in(['accept', 'decline'])]]; }
}

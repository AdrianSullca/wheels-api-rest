<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AnnouncementUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'kilometers' => 'nullable|numeric|min:0',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'price' => 'nullable|numeric|min:0',
            'state' => 'nullable|in:active,inactive',
        ];
    }
}

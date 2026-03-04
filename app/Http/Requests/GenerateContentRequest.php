<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'topic'    => ['required', 'string', 'max:200'],
            'draft'    => ['nullable', 'string', 'max:5000'],
            'tone'     => ['required', 'in:professionale,amichevole,ironico'],
            'language' => ['required', 'in:it,en'],
            'length'   => ['required', 'in:breve,medio,lungo'],
            'target'   => ['nullable', 'string', 'max:120'],
        ];
    }
}

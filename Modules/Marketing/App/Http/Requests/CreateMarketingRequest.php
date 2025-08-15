<?php

namespace Modules\Marketing\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMarketingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_name' => 'required|string',
            'no_po' => 'required|string'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

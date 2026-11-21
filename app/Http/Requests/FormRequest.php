<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

abstract class FormRequest extends Request
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
     */
    abstract public function rules(): array;

    /**
     * Get validated data based on defined rules.
     */
    public function validated(): array
    {
        $rules = $this->rules();
        $validated = [];

        foreach ($rules as $field => $rule) {
            // Remove nested wildcard if any
            $baseField = explode('.', $field)[0];
            if ($this->has($baseField)) {
                $validated[$baseField] = $this->input($baseField);
            }
        }

        return !empty($validated) ? $validated : $this->all();
    }
}

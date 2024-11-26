<?php

namespace App\Http\Requests;

use App\Rules\StatusRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {        
        return [
            'title' => 'required|string|max:255|unique:tasks,title',
            'description' => 'required|string|min:200',
            'status' => ['required', new StatusRule($this->status)],
            'due_date' => 'required|date',
        ];
    }

    protected function prepareForValidation()
    {
        $date = Carbon::parse($this->due_date)->format('Y-m-d');

        $this->merge([
            'due_date'=> $date,
        ]);
    }

}

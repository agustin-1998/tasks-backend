<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\StatusRule;
use Carbon\Carbon;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->id() === $this->user_id;
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
            'status' => ['required', new StatusRule($this->status), "unique:tasks,id,{$this->id}"],
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

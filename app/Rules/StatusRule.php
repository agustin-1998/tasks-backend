<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Task;

class StatusRule implements ValidationRule
{

    public function __construct(string $status) {
        $this->status = $status;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($this->status, Task::getStatuses())){
            $fail("The selected $attribute is invalid. Allowed statuses: " . implode(', ', Task::getStatuses()));
        }
    }
}

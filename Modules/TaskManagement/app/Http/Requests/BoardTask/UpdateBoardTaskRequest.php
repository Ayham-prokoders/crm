<?php

namespace Modules\TaskManagement\Http\Requests\BoardTask;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TaskManagement\Enums\TaskLabel;
use Modules\TaskManagement\Enums\TaskStatus;
use Modules\TaskManagement\Enums\IssueSource;
use Modules\TaskManagement\Enums\TaskPriority;
use Modules\TaskManagement\Enums\TaskRelatedTo;

class UpdateBoardTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'assign_to'   => 'nullable|string',
            'status'      => ['nullable',Rule::in(array_column(TaskStatus::cases(), 'value'))],
            'priority'    => ['nullable',Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'related_to'  => ['nullable',Rule::in(array_column(TaskRelatedTo::cases(), 'value'))],
            'label'       => ['nullable',Rule::in(array_column(TaskLabel::cases(), 'value'))],
            'source_issue'=> ['nullable',Rule::in(array_column(IssueSource::cases(), 'value'))],
            'start_date'  => 'nullable|date',
            'deadline'    => 'nullable|date',
            'attachments' => 'nullable|array'


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

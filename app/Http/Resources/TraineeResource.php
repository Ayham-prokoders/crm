<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Modules\Lms\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Models\Company;
use App\Models\Role;
use App\Http\Resources\RoleResource;


class TraineeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'middel_name'=>$this->middel_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'first_login'=>$this->first_login,
            'password'=>$this->password,
            'phone'=>$this->phone,
            'image'=> asset($this->image),
            'salutation'=>$this->salutation,
            'nationality'=>$this->nationality,
            'city'=>$this->city,
            'job'=>$this->job,
            // 'company'=>new CompanyResource(Company::find($this->company_id)),
            'company' => new CompanyResource(optional($this->company)),
            'role'=>new RoleResource(Role::find($this->role)),
            'current_role_id'=>new RoleResource(Role::find($this->current_role_id)),
            // 'permission' =>$this->getPermissionsViaRoles()->pluck('name'),
            'permissions'=>Role::find($this->current_role_id)->permissions?Role::find($this->current_role_id)->permissions->pluck('name'):null,
            'Allrole' => $this->getRoleNames()->toArray(),
            'deals' => $this->whenLoaded('deals', function () {
                return $this->deals->map(function ($deal) {
                    $courseName = $deal->course_type === 'custom'
                        ? optional($deal->externalCourse)->name
                        : optional($deal->course)->name;

                    return [
                        'id'             => $deal->id,
                        'course_name'    => $courseName,
                        'price'          => $deal->price,
                        'currency'       => $deal->currency,
                        'payment_mode'   => $deal->payment_mode,
                        'payment_method' => $deal->payment_method,
                        'type'           => $deal->type,
                        'language'       => $deal->language,
                        'company'        => optional($deal->company)->name,

                        // Invoices
                        'invoices' => $deal->invoices->map(function ($invoice) {
                            return [
                                'id'         => $invoice->id,
                                'amount'     => $invoice->amount,
                                'status'     => $invoice->status,
                                'due_date'   => $invoice->due_date,
                                'created_at' => $invoice->created_at?->format('Y-m-d'),
                            ];
                        }),
                    ];
                });
            }),
        ];
    }
}

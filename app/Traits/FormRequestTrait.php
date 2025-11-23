<?php


namespace App\Traits;

trait FormRequestTrait
{
     /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

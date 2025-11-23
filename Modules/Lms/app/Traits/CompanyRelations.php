<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use Modules\DealManagement\Models\Deal;

trait CompanyRelations {
    
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }
    public function supervisors()
    {
        return $this->hasMany(User::class, 'company_id')
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'companySupervisor');
                    });
    }


    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
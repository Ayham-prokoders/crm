<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\SyncsWithLMS;

class Role extends SpatieRole
{
    use SyncsWithLMS;

    protected $fillable = ['name', 'guard_name'];
}

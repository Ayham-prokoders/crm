<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\SyncsWithLMS;

class Permission extends SpatiePermission
{
    use SyncsWithLMS;

    protected $fillable = ['name', 'guard_name'];
}

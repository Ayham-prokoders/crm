<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Helper\ResponseHelper;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AuditResource;
use App\Models\Role;

class AuditController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!$role->name=='admin') {
            return ResponseHelper::authorizationFail();
        }
        $logs = Audit::all();

        return ResponseHelper::success(AuditResource::collection($logs));
    }

}

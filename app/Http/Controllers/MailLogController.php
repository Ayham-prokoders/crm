<?php

namespace App\Http\Controllers;

use App\Models\MailLog;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use Spatie\QueryBuilder\QueryBuilder;

class MailLogController extends Controller
{
    public function getMailLog(){
        $query =  QueryBuilder::for(MailLog::class)
            ->allowedFilters(['name' , 'status'])
            ->allowedSorts(['name', 'created_at'])
            ->with(['user:id,name'])
            ->latest();
        return ResponseHelper::success($query->paginate(10));
    }
}

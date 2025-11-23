<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ActionHistory;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\ActionHistoryResource;

class ActionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = QueryBuilder::for(ActionHistory::class)
            ->allowedFilters([
                AllowedFilter::exact('module'),
            ])
            ->allowedSorts(['created_at'])
            ->defaultSort('-created_at') 
            ->paginate($request->input('per_page', 15));

        return ActionHistoryResource::collection($histories);
    }

    public function show(ActionHistory $actionHistory)
    {
        return new ActionHistoryResource($actionHistory);
    }
}

<?php

namespace Modules\TrainerManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\TrainerManagement\Models\Topic;
use Modules\TrainerManagement\Http\Requests\TopicRequest;
use Modules\TrainerManagement\Http\Resources\TopicResource;

class TopicController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     */
    public function index(Request $request)
    {
        // if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('supervisor'))) {
        //     return ResponseHelper::authorizationFail(__('message.unauthorized'));
        // }

        $query = QueryBuilder::for(Topic::class)
            ->allowedFilters(['title'])
            ->allowedSorts(['title', 'created_at']);

            $topics = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'topics' => TopicResource::collection($topics),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $topics->total(),
                'per_page' => $topics->perPage(),
                'current_page' => $topics->currentPage(),
                'last_page' => $topics->lastPage(),
                'from' => $topics->firstItem(),
                'to' => $topics->lastItem(),
                'links' => [
                    'first' => $topics->url(1),
                    'last' => $topics->url($topics->lastPage()),
                    'prev' => $topics->previousPageUrl(),
                    'next' => $topics->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }


    /**
     * Store a newly created Topic in storage.
     *
     */
    public function store(TopicRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }


        $topic = Topic::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return ResponseHelper::create(new TopicResource($topic));
    }

    /**
     * Display the specified Topic.
     *
     */
    public function show(Topic $topic)
    {

        return ResponseHelper::success(new TopicResource($topic));
    }

    /**
     * Update the specified Topic in storage.
     *
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }

        $topic->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        return ResponseHelper::success(new TopicResource($topic));
    }

    /**
     * Remove the specified Topic from storage.
     *
     */
    public function destroy(Topic $topic)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }

        $topic->delete();
        return ResponseHelper::success();
    }
}

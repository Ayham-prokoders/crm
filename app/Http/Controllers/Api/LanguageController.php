<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Helper\ResponseHelper;
use App\Models\Role;

class LanguageController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('supervisor'))) {
        //     return ResponseHelper::authorizationFail(__('message.unauthorized'));
        // }

        $query = QueryBuilder::for(Language::class)
            ->allowedFilters(['name','code'])
            ->allowedSorts(['name','code', 'created_at']);

            $languages = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'languages' => LanguageResource::collection($languages),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $languages->total(),
                'per_page' => $languages->perPage(),
                'current_page' => $languages->currentPage(),
                'last_page' => $languages->lastPage(),
                'from' => $languages->firstItem(),
                'to' => $languages->lastItem(),
                'links' => [
                    'first' => $languages->url(1),
                    'last' => $languages->url($languages->lastPage()),
                    'prev' => $languages->previousPageUrl(),
                    'next' => $languages->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }


    /**
     * Store a newly created Language in storage.
     *
     * @param  \App\Http\Requests\LanguageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }


        $language = Language::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);

        return ResponseHelper::create(new LanguageResource($language));
    }

    /**
     * Display the specified Language.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {

        return ResponseHelper::success(new LanguageResource($language));
    }

    /**
     * Update the specified Language in storage.
     *
     * @param  \App\Http\Requests\LanguageRequest  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, Language $language)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }

        $language->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);
        return ResponseHelper::success(new LanguageResource($language));
    }

    /**
     * Remove the specified Language from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }

        $language->delete();
        return ResponseHelper::success();
    }
}

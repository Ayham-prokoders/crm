<?php

namespace Modules\TaskManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\TaskManagement\Models\ContactDirectory;
use Modules\TaskManagement\Http\Requests\ContactRequest;
use Modules\TaskManagement\Transformers\ContactResource;

class ContactDirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(ContactDirectory::class)
        ->allowedFilters(['name','role'])
        ->allowedSorts(['name','role' , 'created_at']);

        $contacts = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        return ResponseHelper::success(ContactResource::collection($contacts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        $contact = ContactDirectory::create($request->validated());
        return ResponseHelper::create(new ContactResource($contact));
    }

    /**
     * Show the specified resource.
     */
    public function show(ContactDirectory $contact)
    {
        return ResponseHelper::success(new ContactResource($contact));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, ContactDirectory $contact)
    {
        $contact->update($request->validated());

        return ResponseHelper::success(new ContactResource($contact));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactDirectory $contact)
    {
        return ResponseHelper::success($contact->delete());
    }
}

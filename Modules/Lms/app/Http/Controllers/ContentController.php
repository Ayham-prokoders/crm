<?php

namespace Modules\Lms\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Lms\Models\{Classe,Content};
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Http\Resources\ContentResource;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Content::class)
            ->allowedFilters(['name','class_id'])
            ->allowedSorts(['name', 'created_at']); 
            
    
            $user = Auth::user();
            $currentRole = $user->current_role_id;
            $role = Role::find($currentRole);
    
           if (in_array($role->name, ['admin', 'supervisor'])) {
            // Admin and supervisor can see all content
            $contentsQuery = $query;
        } elseif ($role->name=='trainer') {
            // Trainers can only see content for their assigned classes
            $trainerClasses = Auth::user()->trainerClasses->pluck('id');
            $contentsQuery = $query->whereIn('class_id', $trainerClasses);
   
        } elseif ($role->name=='trainee') {
            // Trainees can only see content for their assigned classes
            $traineeClasses = Auth::user()->trainees->pluck('id');
            $contentsQuery = $query->whereIn('class_id', $traineeClasses);
        } 
        elseif ($role->name=='companySupervisor') {
            // Check if the user is a trainee for the specified class
            $userCompany=User::where('company_id',$user->company_id)->get()->pluck('id');
            // Retrieve classes that have trainees belonging to the user's company
            $classesUserCompany = Classe::whereHas('trainees', function ($query) use ($userCompany) {
                $query->whereIn('users.id', $userCompany); 
            })->pluck('id');
                // return $classesUserCompany;
            // Retrieve all contents for the specified class
            $contentsQuery = $query->whereIn('class_id', $classesUserCompany);
                       
        }
        else {
            return ResponseHelper::authorizationFail();
        }

        $contents = $request->input('limit') 
        ? $contentsQuery->paginate($request->input('limit')) 
        : $contentsQuery->get();

    $data = [
        'contents' => ContentResource::collection($contents),
    ];
    if ($request->input('limit')) {
        $data['pagination'] = [
            'total' => $contents->total(),
            'per_page' => $contents->perPage(),
            'current_page' => $contents->currentPage(),
            'last_page' => $contents->lastPage(),
            'from' => $contents->firstItem(),
            'to' => $contents->lastItem(),
            'links' => [
                'first' => $contents->url(1),
                'last' => $contents->url($contents->lastPage()),
                'prev' => $contents->previousPageUrl(),
                'next' => $contents->nextPageUrl(),
            ],
        ];
    }

    return ResponseHelper::success($data);
        
    }
    
    //get content for class by id
    public function list(Request $request)
    {

        $classId = $request->input('class_id');

            if (!$classId) {
                return ResponseHelper::invalidData();
            }  

        $query = QueryBuilder::for(Content::class)
            ->allowedFilters(['name','class_id'])
            ->allowedSorts(['name', 'created_at'])
            ->where('class_id',$classId); 
          
    
            $user = Auth::user();
            $currentRole = $user->current_role_id;
            $role = Role::find($currentRole);
    
           if (in_array($role->name, ['admin', 'supervisor'])) {
            // Admin and supervisor can see all content
            $contentsQuery = $query;
        } elseif ($role->name=='trainer') {
            // Trainers can only see content for their assigned classes
            $trainerClasses = Auth::user()->trainerClasses->pluck('id');
            $contentsQuery = $query->whereIn('class_id', $trainerClasses);
   
        } elseif ($role->name=='trainee') {
            // Trainees can only see content for their assigned classes
            $traineeClasses = Auth::user()->trainees->pluck('id');
            // echo(Auth::user()->trainees);
            $contentsQuery = $query->whereIn('class_id', $traineeClasses);
        }  
        else
         {
            return ResponseHelper::authorizationFail();
        }

        $contents = $request->input('limit') 
        ? $contentsQuery->paginate($request->input('limit')) 
        : $contentsQuery->get();

    $data = [
        'contents' => ContentResource::collection($contents),
    ];
    if ($request->input('limit')) {
        $data['pagination'] = [
            'total' => $contents->total(),
            'per_page' => $contents->perPage(),
            'current_page' => $contents->currentPage(),
            'last_page' => $contents->lastPage(),
            'from' => $contents->firstItem(),
            'to' => $contents->lastItem(),
            'links' => [
                'first' => $contents->url(1),
                'last' => $contents->url($contents->lastPage()),
                'prev' => $contents->previousPageUrl(),
                'next' => $contents->nextPageUrl(),
            ],
        ];
    }

    return ResponseHelper::success($data);
        
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }
        // Validate the request
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'files' => 'required|array',
            'files.*.name' => 'nullable|string',
            'files.*.path' => 'nullable|string',
        ]);
    
        // Array to store content resources
        $contents = [];
    
        // Process each file in the request
        foreach ($request->input('files') as $file) {
            // Create content for the file
            $content = Content::create([
                'name' => $file['name'],
                'file' => $file['path'],
                'class_id' => $request->input('class_id'),
            ]);
    
            // Add content resource to the array
            $contents[] = new ContentResource($content);
        }
    
        return ResponseHelper::create($contents);
    }
    

    public function show(Content $content)
    {
        // if (!Auth::user()->hasRole('admin') && !$content->classe()->where('trainer_id', Auth::id())->exists()) {
        //     return ResponseHelper::authorizationFail();
        // }

        return ResponseHelper::success(new ContentResource($content));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }
        $class_id=$request->input('class_id');
        // Validate the request
        $request->validate([
            'files' => 'required|array',
            'files.*.name' => 'nullable|string',
            'files.*.path' => 'required|string',
        ]);
    
        // Find the class
        $class = Classe::findOrFail($class_id);
        // Delete existing content associated with the class
        $class->contents()->delete();
    
        // Array to store content resources
        $contents = [];
    
        // Process each file in the request
        foreach ($request->input('files') as $file) {
            // Create content for the file
            $content = Content::create([
                'name' => $file['name'],
                'file' => $file['path'],
                'class_id' => $class_id,
            ]);
    
            // Add content resource to the array
            $contents[] = new ContentResource($content);
        }
    
        return ResponseHelper::success($contents);
    }
    

    public function destroy(Content $content)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }

        $content->delete();

        return ResponseHelper::success();
    }
}

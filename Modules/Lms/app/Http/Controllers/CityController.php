<?php

namespace Modules\Lms\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Lms\Models\City;
use App\Http\Controllers\Controller;
use Modules\Lms\Http\Resources\CityResource;

class CityController extends Controller
{
    public function getCities(Request $request){
        $cities = City::with('locations')->paginate(10);
        return CityResource::collection($cities);
    }

    /**
     * all cities by project source
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    public function cities(Request $request){
        $source = $request->query('source');
        $cities = City::withoutGlobalScope('project_source_l1')
            ->when($source, function ($query) use ($source) {
                return $query->byProjectSource($source);
            })->get();
        return response()->json($cities);
    }
}

<?php

namespace Modules\TrainerManagement\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Lms\Models\Classe;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\TrainerManagement\Models\TrainerSignature;
use Modules\TrainerManagement\Http\Requests\TrainerSignatureRequest;
use Modules\TrainerManagement\Http\Resources\TrainerSignatureResource;

class TrainerSignatureController extends Controller
{
    public function saveTrainerSignature(TrainerSignatureRequest $request)
    {
        $classId = $request->class_id;
        $traineeId = $request->trainee_id;
        $signature = $request->signature;
        $trainerId = Auth::id();
        // $trainerId = Classe::where('id', $classId)->value('trainer_id');
        TrainerSignature::updateOrCreate(
            [
                'trainer_id' => $trainerId,
                'trainee_id' => $traineeId,
                'classe_id' => $classId,
            ],
            [
                'signature' => $signature,
            ]
        );

        return ResponseHelper::success();
    }


    public function getTrainerSignature(Request $request)
    {

        $classId = $request->class_id;
        $traineeId = $request->trainee_id;

        $signature = TrainerSignature::where('classe_id', $classId)
            ->where('trainee_id', $traineeId)
            ->first();

        if (!$signature) {
            return ResponseHelper::DataNotFound();
        }

        return ResponseHelper::success( new TrainerSignatureResource($signature));
    }
}

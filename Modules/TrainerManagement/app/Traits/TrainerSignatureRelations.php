<?php

namespace Modules\TrainerManagement\Traits;

use App\Models\User;
use Modules\Lms\Models\Classe;

trait TrainerSignatureRelations {
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
}
<?php

namespace Modules\Lms\Traits;

use Modules\Lms\Models\Classe;

trait ContentRelations {

    public function classe()
    {
        return $this->belongsTo(Classe::class,'class_id');
    }
}
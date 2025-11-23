<?php

namespace Modules\TrainerManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TrainerManagement\Traits\TrainerSignatureRelations;
// use Modules\TrainerManagement\Database\Factories\TrainerSignatureFactory;

class TrainerSignature extends Model
{
    use HasFactory;
    use TrainerSignatureRelations;

    protected $fillable = ['trainer_id', 'trainee_id', 'classe_id', 'signature','status'];

}

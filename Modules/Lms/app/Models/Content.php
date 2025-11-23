<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use Modules\Lms\Traits\ContentRelations;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use ContentRelations;
    use SyncsWithLMS;


    protected $fillable = ['name', 'description','file', 'class_id'];

}

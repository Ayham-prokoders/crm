<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use Modules\Lms\Traits\CompanyRelations;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use CompanyRelations;
    use SyncsWithLMS;

    protected $fillable = [
        'lang_code', 'name','address', 'bill_email','email','phone'
    ];
}

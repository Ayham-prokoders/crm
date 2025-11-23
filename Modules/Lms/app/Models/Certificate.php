<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Modules\Lms\Traits\CertificateRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Builder;

class Certificate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use CertificateRelations;
    use SyncsWithLMS;


        protected $fillable=[
        'lang_code',
        'project_source',
        'course_custom_name',
        'course_type',
        'type',
        'course_id',
        'first_name',
        'middle_name',
        'last_name',
        'ID_certificate',
        'image',
        'pdf',
        'user_id',
        'origin',
        'external_id',
        'show_in_website',
        'created_at',
        'updated_at'
    ];


   public static function generateUniqueCertificateID(string $firstName)
    {
        $prefix = strtoupper(
            mb_substr(trim($firstName), 0, 2)
        );

        while (true) {
            $randomDigits = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $certificateID = $prefix . $randomDigits;

            if (!self::where('ID_certificate', $certificateID)->exists()) {
                return $certificateID;
            }

        }
    }
    protected static function booted(): void
    {
        static::addGlobalScope('project_source_l1', function (Builder $builder) {
            $builder->whereIn('project_source', ['L1',null]);
        });
    }

}

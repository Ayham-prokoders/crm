<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\ExternalCertificateFactory;

class ExternalCertificate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SyncsWithLMS;

    protected $fillable = [
        'lang_code','project_source','type','category','course','city','schedule','class',
        'first_name','middle_name','last_name',
        'show_in_website','image','course_type','ID_certificate','pdf',
    ];

    protected $casts = [
        'show_in_website' => 'boolean',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class,'category');
    }
    public static function generateUniqueCertificateID(string $firstName): string
    {
        $prefix = strtoupper(mb_substr(trim($firstName), 0, 2));

        do {
            $randomDigits = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $certificateID = $prefix . $randomDigits;
        } while (self::where('ID_certificate', $certificateID)->exists());

        return $certificateID;
    }
}

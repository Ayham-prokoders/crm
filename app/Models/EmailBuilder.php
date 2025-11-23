<?php

namespace App\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailBuilder extends Model
{
    use HasFactory;
    use SyncsWithLMS;

    protected $fillable = ['template_name' ,'subject' , 'category' ,'body','html_body'];

    // public function recipients()
    // {
    //     return $this->belongsToMany(User::class, 'user_mail', 'email_builder_id', 'user_id')
    //                 ->withPivot('type');
    // }
}

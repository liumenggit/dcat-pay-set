<?php

namespace Liumenggit\PaySet\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaySet extends Model
{
    use HasDateTimeFormatter;
//    use SoftDeletes;

    protected $casts = [
        'config' => 'array',
    ];
    protected $table = 'pay_set';

}

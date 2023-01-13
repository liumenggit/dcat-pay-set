<?php

namespace Liumenggit\PaySet\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;

class PayTree extends Model implements Sortable
{
    use HasDateTimeFormatter;
    use ModelTree;

//    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'pay_tree';
    public function template()
    {
        return $this->hasOne(PaySet::class,'id','pay_set_id');
    }

//
//    protected $titleColumn = 'name';
//
//    protected $orderColumn = 'sort';
//
//    protected $parentColumn = 'pid';


}

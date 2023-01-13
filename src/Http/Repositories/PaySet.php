<?php


namespace Liumenggit\PaySet\Http\Repositories;

use Liumenggit\PaySet\Models\PaySet as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PaySet extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

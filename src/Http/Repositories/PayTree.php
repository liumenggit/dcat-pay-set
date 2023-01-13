<?php


namespace Liumenggit\PaySet\Http\Repositories;

use Liumenggit\PaySet\Models\PayTree as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PayTree extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

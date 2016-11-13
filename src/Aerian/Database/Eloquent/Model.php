<?php

namespace Aerian\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Aerian\FormGenerator\HasFieldGenerator;
use Aerian\FormGenerator\Generator;
use Aerian\FormGenerator\FieldGenerator\EloquentModel as EloquentModelFieldGenerator;

class Model extends EloquentModel implements HasFieldGenerator
{
    public function getForm()
    {
        return (new Generator($this))->generateForm($this);
    }

    public function getFieldGenerator()
    {
        return app()->make(EloquentModelFieldGenerator::class);
    }

    /**
     * @todo cache this to property
     * @return \Doctrine\DBAL\Schema\Column[]
     */
    public function describe()
    {
        return $this
            ->getConnection()
            ->getDoctrineSchemaManager()
            ->listTableColumns($this->getTable());
    }
}

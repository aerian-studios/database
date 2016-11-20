<?php

namespace Aerian\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Aerian\Blueprint\Adaptor\EloquentModel as BlueprintAdaptor;

class Model extends EloquentModel
{

    public function blueprint()
    {
        return $this->getBlueprintAdaptor()->blueprint($this);
    }

    /**
     * @return BlueprintAdaptor
     */
    public function getBlueprintAdaptor()
    {
        return new BlueprintAdaptor($this);
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

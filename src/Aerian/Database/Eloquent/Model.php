<?php

namespace Aerian\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Aerian\Blueprint\Adaptor\EloquentModel as BlueprintAdaptor;

class Model extends EloquentModel
{
    /**
     * the columns which should be included in a list view of this model, e.g. a REST index
     * @var array
     */
    protected $_listColumns;

    /**
     * columns to exclude when the default list columns are set
     * @var array
     */
    protected $_listColumnsBlacklist = ['id', 'created_at', 'updated_at'];


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

    /**
     * @return array
     */
    public function getListColumns()
    {
        if (!isset($this->_listColumns)) {
            $this->_setDefaultListColumns();
        }
        return $this->_listColumns;
    }

    /**
     * @param mixed $listColumns
     * @return Model
     */
    public function setListColumns($listColumns)
    {
        $this->_listColumns = $listColumns;
        return $this;
    }

    protected function _setDefaultListColumns()
    {
        $allColumns = array_keys($this->describe());
        $columns = array_values(array_diff($allColumns, $this->getListColumnsBlacklist()));
        $this->setListColumns($columns);
    }

    /**
     * @return array
     */
    public function getListColumnsBlacklist()
    {
        return $this->_listColumnsBlacklist;
    }

    /**
     * @param array $listColumnsBlacklist
     * @return Model
     */
    public function setListColumnsBlacklist($listColumnsBlacklist)
    {
        $this->_listColumnsBlacklist = $listColumnsBlacklist;
        return $this;
    }

}

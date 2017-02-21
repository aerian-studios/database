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
    protected $_listColumnKeys;

    /**
     * columns to exclude when the default list columns are set
     * @var array
     */
    protected $_listColumnKeysBlacklist = ['id', 'created_at', 'updated_at'];

    /**
     * an array of 'key' => 'label' pairs for column labels which need manual aliases
     * @var array
     */
    protected $_columnLabelMap = [];

    /**
     * doctrine column list
     * @var \Doctrine\DBAL\Schema\Column[]
     */
    protected $_description;

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
     * @return \Doctrine\DBAL\Schema\Column[]
     */
    public function describe()
    {
        if (!isset($description)) {
            $this->_description = $this
                ->getConnection()
                ->getDoctrineSchemaManager()
                ->listTableColumns($this->getTable());
        }
        return $this->_description;
    }

    /**
     * @return array
     */
    public function getListColumnKeys()
    {
        if (!isset($this->_listColumnKeys)) {
            $this->_setDefaultListColumnKeys();
        }
        return $this->_listColumnKeys;
    }

    public function getListColumns()
    {
        $columns = [];
        foreach ($this->getListColumnKeys() as $columnKey) {
            $columns[$columnKey] = [
                'key' => $columnKey,
                'label' => $this->getLabelForColumnKey($columnKey),
            ];
        }
        return $columns;
    }

    /**
     * @param mixed $listColumns
     * @return Model
     */
    public function setListColumnKeys($listColumns)
    {
        $this->_listColumnKeys = $listColumns;
        return $this;
    }

    protected function _setDefaultListColumnKeys()
    {
        $allColumns = array_keys($this->describe());
        $columns = array_values(array_diff($allColumns, $this->getListColumnKeysBlacklist()));
        $this->setListColumnKeys($columns);
    }

    /**
     * @return array
     */
    public function getListColumnKeysBlacklist()
    {
        return $this->_listColumnKeysBlacklist;
    }

    /**
     * @param array $listColumnsBlacklist
     * @return Model
     */
    public function setListColumnKeysBlacklist($listColumnsBlacklist)
    {
        $this->_listColumnKeysBlacklist = $listColumnsBlacklist;
        return $this;
    }

    public function getLabelForColumnKey($columnKey)
    {
        return (in_array($columnKey, $this->_columnLabelMap)) ? $this->_columnLabelMap[$columnKey] : title_case($columnKey);
    }

}

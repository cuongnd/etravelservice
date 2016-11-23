<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/koowa for the canonical source repository
 */

/**
 * Insert Database Query
 *
 * @author  Gergo Erdosi <https://github.com/gergoerdosi>
 * @package Koowa\Library\Database
 */
class ComDocman_importDatabaseQueryInsert extends KObject
{
    /**
     * The table name.
     *
     * @var string
     */
    public $table;

    /**
     * Array of column names.
     *
     * @var array
     */
    public $columns = array();

    /**
     * Array of values.
     *
     * @var array
     */
    public $values = array();

    /**
     * Constructor
     *
     * @param KObjectConfig $config  An optional KObjectConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_adapter = $config->adapter;
        $this->_params  = $config->params;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config An optional KObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'adapter' => 'koowa:database.adapter.mysqli',
            'params'  => 'koowa:object.array'
        ));
    }

    /**
     * Build the table clause
     *
     * @param  string $table The table name.
     * @return KDatabaseQueryInsert
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Build the columns clause
     *
     * @param  array $columns Array of column names.
     * @return KDatabaseQueryInsert
     */
    public function columns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Build the values clause
     *
     * @param  array $values Array of values.
     * @return KDatabaseQueryInsert
     */
    public function values($values)
    {
        if (!$this->columns && !is_numeric(key($values))) {
            $this->columns(array_keys($values));
        }

        $this->values[] = array_values($values);

        return $this;
    }

    /**
     * Render the query to a string.
     *
     * @return  string  The query string.
     */
    public function __toString()
    {
        $adapter = $this->getAdapter();
        $prefix  = $adapter->getTablePrefix();
        $query   = 'INSERT';

        if($this->table) {
            $query .= ' INTO '.$adapter->quoteName($prefix.$this->table);
        }

        if($this->columns) {
            $query .= '('.implode(', ', array_map(array($adapter, 'quoteName'), $this->columns)).')';
        }

        if($this->values)
        {
            $query .= ' VALUES'.PHP_EOL;

            $values = array();
            foreach ($this->values as $row)
            {
                $data = array();
                foreach($row as $column) {
                    $data[] = $adapter->quoteValue(is_object($column) ? (string) $column : $column);
                }

                $values[] = '('.implode(', ', $data).')';
            }

            $query .= implode(', '.PHP_EOL, $values);
        }

        return $query;
    }

    /**
     * Gets the database adapter
     *
     * @throws	\UnexpectedValueException	If the adapter doesn't implement KDatabaseAdapterInterface
     * @return KDatabaseAdapterInterface
     */
    public function getAdapter()
    {
        if(!$this->_adapter instanceof KDatabaseAdapterInterface)
        {
            $this->_adapter = $this->getService($this->_adapter);

            if(!$this->_adapter instanceof KDatabaseAdapterInterface)
            {
                throw new UnexpectedValueException(
                    'Adapter: '.get_class($this->_adapter).' does not implement KDatabaseAdapterInterface'
                );
            }
        }

        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param KDatabaseAdapterInterface $adapter
     * @return KDatabaseQueryInterface
     */
    public function setAdapter(KDatabaseAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }
}

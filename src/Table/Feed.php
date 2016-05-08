<?php

namespace Phire\Feed\Table;

use Pop\Db\Record;

class Feed extends Record
{

    /**
     * Table prefix
     * @var string
     */
    protected $prefix = DB_PREFIX;

    /**
     * Primary keys
     * @var array
     */
    protected $primaryKeys = ['id', 'type'];

}
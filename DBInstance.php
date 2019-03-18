<?php

namespace Cache;

use \Cache\DB;

class DBInstance
{
    public static $DB = null;
    /**
     * Init the instance with a DB
     */
    public function __construct($conn)
    {
        if (!self::$DB) {
            self::$DB = new DB($conn);
        }
    }
    /**
     * @return \Pebble\DB 
     */
    public static function get()
    {
        return self::$DB;
    }

}
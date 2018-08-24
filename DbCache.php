<?php

namespace diversen;

use diversen\db\q;

class DbCache {

    /**
     * constructor
     * @param   object $conn PDO connection
     * @param   string $table database table
     */
    public function __construct ($conn, $table = null) {
        if ($table) {
            $this->table = $table;
        }
        q::connect($conn);
    }

    public $useTransactions = true;
        
    /**
     * Default database cache table name
     */
    public $table = 'cache_system';

    /**
     * Get a cache result
     * @param string $id
     * @param int $max_life_time max life time in seconds
     * @return mixed $res NULL if no result of if result is outdated. Else return the result
     */
    public function get($id, $max_life_time = null) {

        $row = q::select($this->table)->filter('id =', md5($id))->fetchSingle();
        if (!$row) {
            return null;
        }
        if ($max_life_time) {
            $expire = $row['unix_ts'] + $max_life_time;
            if ($expire < time()) {
                $this->delete(md5($id));
                return null;
            } else {
                return unserialize($row['data']);
            }
        } else {
            return unserialize($row['data']);
        }
        return null;
    }
    /**
     * Sets a string in cache
     * @param   int     $id
     * @param   string  $data
     * @return  string   $res true on succes else false
     */
    public function set($id, $data) {
        q::begin();

        $res = $this->delete($id);
        if (!$res) {
            q::rollback();
            return false;
        }

        $values = array('id' => md5($id), 'unix_ts' => time());
        $values['data'] = serialize($data);

        $res = q::insert($this->table)->values($values)->exec();
        if (!$res) {
            q::rollback();
            return false;
        }

        return q::commit();
    }

    /**
     * Delete a string from cache
     * @param   int     $id
     * @return  boolean $res db result
     */
    public function delete($id) {

        $row = q::select($this->table)->
                filter('id =', md5($id))->
                fetchSingle();
        if (!empty($row)) {
            return q::delete($this->table)->
                    filter('id =', md5($id))->
                    exec();
        }
        return true;
    }
}

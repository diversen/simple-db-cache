<?php

namespace Cache;

use Cache\DBInstance;

class DBCache
{

    /**
     * Default database cache table name
     */
    public $table = 'cache_system';

    /**
     * constructor
     * @param   object $conn PDO connection
     * @param   string $table database table
     */
    public function __construct($conn, $table = null)
    {
        if ($table) {
            $this->table = $table;
        }
        new DBInstance($conn);
    }

    public function generateKey ($id) {
        
        $key = null;
        if (is_string($id)) {
            $key = $id;
        } else {
            $key = json_encode($id);
        }
        return $this->hash($key);
    }

    /**
     * Hash a key using sha256
     */
    public function hash ($key) {
        return hash('sha256', $key);
    }

    /**
     * Get a cache result
     * @param string $id
     * @param int $max_life_time max life time in seconds
     * @return mixed $res NULL if no result of if result is outdated. Else return the result
     */
    public function get($id, $max_life_time = null)
    {

        $query = "SELECT * FROM {$this->table} WHERE id = ? ";
        $db = DBInstance::get();
        $row = $db->prepareFetch($query, [$this->generateKey($id)]);
        
        if (empty($row)) {
            return null;
        }
        if ($max_life_time) {
            $expire = $row['unix_ts'] + $max_life_time;
            if ($expire < time()) {
                $this->delete($this->generateKey($id));
                return null;
            } else {
                return unserialize($row['data']);
            }
        } else {
            return unserialize($row['data']);
        }
    }
    /**
     * Sets a string in cache
     * @param   int     $id
     * @param   string  $data
     * @return  string   $res true on succes else false
     */
    public function set($id, $data)
    {
        $db = DBInstance::get();
        $db->beginTransaction();

        $res = $this->delete($id);
        if (!$res) {
            $db->rollback();
            return false;
        }

        $query = "INSERT INTO {$this->table} ('id', 'unix_ts', 'data') VALUES (?, ?, ?)";
        $res = $db->prepareExecute($query, [$this->generateKey($id), time(), serialize($data) ]);

        if (!$res) {
            $db->rollback();
            return false;
        }

        return $db->commit();
    }

    /**
     * Delete a string from cache
     * @param   int     $id
     * @return  boolean $res db result
     */
    public function delete($id)
    {

        $db = DBInstance::get();

        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $row = $db->prepareFetch($query, [$this->generateKey($id)]);

        if (!empty($row)) {
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            return $db->prepareExecute($query, [$this->generateKey($id)]);
        }
        
        return true;
    }
}

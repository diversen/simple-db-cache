<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include_once "vendor/autoload.php";

// Just included in order to test. 
include_once "DBCache.php";
include_once "DB.php";
include_once "DBInstance.php";

use Cache\DBCache;

// Connect to PDO sqlite
$pdo_url = 'sqlite:./database.lite';

// Create a connection
$conn = new PDO($pdo_url);
// Create cache object - second param is the DB cache table
$cache = new DBCache($conn, 'cache_system');

// Some kind of unique key. Usually a string but an array or object will work as well
$cache_key = array('Hello world');

// Cache for 10 seconds
$cache_res = $cache->get($cache_key, 10);

// Cache return NULL if no result or if result is outdated
if ($cache_res === NULL) {
    echo "No cache result. Setting cache\n";
    
    // Set cache by a key and value
    // The cache operation is using begin, rollback and commit

    $cache_value = array(0 => 'test', 'str_key' => 'Hello there World ÆØÅ!');
    $cache->set($cache_key, $cache_value);
} else {

    // Echo the cache result
    print_r( $cache_res);
    // Get assoc array
    $cache_res = $cache->get($cache_key, 10, $assoc = true);
		
		print_r($cache_res);

}

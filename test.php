<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "vendor/autoload.php";
include_once "dbCache.php";

use diversen\DbCache;

// Connect to PDO sqlite
$pdo_url = 'sqlite:./database.lite';

$conn = new PDO($pdo_url);

// Create cache object - second param is optional
$cache = new \diversen\DbCache($conn, 'cache_system');

// Some kind of unique key
$cache_key = 'Hello world';

// Cache for 10 seconds
$cache_res = $cache->get($cache_key, 10);

// Cache return NULL if no result or if result is outdated
if ($cache_res === NULL) {
    echo "No cache result. Setting cache\n";
    
    // Set cache by a key and value
    // The cache operation is using begin, rollback and commit

    $cache_value = array('Hello there World!');
    $cache->set($cache_key, $cache_value);
} else {

    // Echo the cache result
    print_r( $cache_res);
}

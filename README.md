# simple-db-cache

Very simple key / value cache using a PDO database

## Database table

You will need a database table, e.g. sqlite:
~~~sql
    CREATE TABLE `cache_system` (
    `id` varchar(64) NOT NULL,
    `data` text,
    `unix_ts` int(10) DEFAULT NULL,
    PRIMARY KEY (`id`)
    )
~~~

Or MySQL: 
~~~sql
    CREATE TABLE `cache_system` (
    `id` varchar(64) NOT NULL,
    `data` mediumtext,
    `unix_ts` int(10) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_system_cache` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
~~~

You can also use PostgreSQL and maybe other PDO supported databases. 

## usage 

Install

    composer require diversen/simple-db-cache

~~~php
use Cache\DBCache;

// Connect to PDO sqlite
$pdo_url = 'sqlite:./database.lite';

// Create a connection
$conn = new PDO($pdo_url);

// Create cache object - second param is the DB cache table
$cache = new DBCache($conn, 'cache_system');

// Some kind of unique key. Usually a string but an array or object will work as well
$cache_key = array('Hello world');

// Use assoc in json_decode
$cache->assoc = true;

// Cache for 10 seconds
$cache_res = $cache->get($cache_key, 10);

// Cache return NULL if no result or if result is outdated
if ($cache_res === null) {
    echo "No cache result. Setting cache\n";

    $cache_value = array(0 => 'test', 'str_key' => 'Hello there World ÆØÅ! and som random stuff: ' . rand());
    
	echo "Setting cache\n";
	
	$cache->set($cache_key, $cache_value);
} else {

    // output the cache result
    print_r($cache_res);

}

// Test for a bit more variables
function test_10000($cache) {

	for ($i = 0; $i < 10000; $i++) {
		if ($i % 1000 === 0) {
			echo "$i\n";
		}
		$cache->set($i, $i . ' ' . rand());
	}
}

// test_10000($cache);
$res = $cache->get(7985);
var_dump($res);
~~~

## License

MIT © [Dennis Iversen](https://github.com/diversen)

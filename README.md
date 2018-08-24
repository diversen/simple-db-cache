# simple-db-cache

Very simple key / value cache using a PDO database

## Database table

You will need a database table, e.g. sqlite:
~~~sql
    CREATE TABLE `cache_system` (
    `id` varchar(32) NOT NULL,
    `data` text,
    `unix_ts` int(10) DEFAULT NULL,
    `name` varchar(255) DEFAULT '',
    PRIMARY KEY (`id`)
    )
~~~

Or MySQL: 
~~~sql
    CREATE TABLE `cache_system` (
    `id` varchar(32) NOT NULL,
    `data` mediumtext,
    `unix_ts` int(10) DEFAULT NULL,
    `name` varchar(255) DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `idx_system_cache` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
~~~

You can also use PostgreSQL and maybe other PDO supported databases. 

## usage 

~~~php
    include_once "vendor/autoload.php";

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
    if (!$cache_res) {
        echo "No cache result. Setting cache<br />";
        $cache_value = 'Hello there<br />';
        $cache->set($cache_key, $cache_value);
    } else {
        echo $cache_res;
    }
~~~
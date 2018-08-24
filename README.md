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

Install

    composer require diversen/simple-db-cache

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
~~~

## License

MIT © [Dennis Iversen](https://github.com/diversen)
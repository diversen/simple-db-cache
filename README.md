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

## Install

    composer require diversen/simple-db-cache

## Usage

 [test.php](test.php)

## License

MIT © [Dennis Iversen](https://github.com/diversen)

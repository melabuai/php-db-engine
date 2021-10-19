# DB ENGINE FACTORY

Connects to the database and encapsulates all requests to the database.

Usable with various databases MySQL, SQLite, PGSQL, Oracle, etc.

# Idea

The idea is to have a factory where you can dock onto a system, regardless of which one it is. Whether for debugging or something else, simply enter the access data and call up the desired database.

> Sometimes older databases are still in use and therefore this tool should also be able to address older databases.

# Use

First enter the database data in `config.php`.

```php
// ALL DBs
define('DB_CHARSET', 'utf8'); // DB Charset
define('DB_HOST', "127.0.0.1"); // DB Host
define('DB_PORT', "3306"); // DB Port
define('DB_NAME', "dbname"); // DB Name
define('DB_USER', "root"); // DB Username
define('DB_PASS', "password"); // DB Password
// PDO ONLY
define('DB_PDO_DRIVER', 'mysql'); // User other driver if you need
define('DB_DSN', DB_PDO_DRIVER . ':host=' . DB_HOST . ':' . DB_PORT . ';dbname=' . DB_NAME);
```

And then call up which database is required:

```php
$DB_MySQLi = System\Database\DB_ENGINE::factory('MySQLi');
var_dump($DB_MySQLi); // Instance
$DB = $DB_MySQLi->getConnection(); // Connection object
```

```php
$DB_PDO = System\Database\DB_ENGINE::factory('PDO');
var_dump($DB_PDO); // Instance
$DB = $DB_PDO->getConnection(); // Connection object
```

```php
$DB_SQL_LITE = System\Database\DB_ENGINE::factory('SQLite');
var_dump($DB_SQL_LITE); // Instance
$DB = $DB_SQL_LITE->getConnection(); // Connection object
```

> Use all of them if needed and compare data from other databases.

# Development

Present at the moment:

- MySQLi (Provider-specific database extensions)
- PDO (PHP database extension)
- PDO/SQLite (File based database)

Following:

- [ ] [SQLite3](https://www.php.net/manual/de/book.sqlite3.php)

# Contribute

Please an [issue](https://github.com/prod3v3loper/php-db-engine/issues) if you
think something could be improved. Please submit Pull Requests when ever
possible.

# Authors

**[Samet Tarim](https://www.prod3v3loper.com)** - _All works_

# Supporter

[Hyperly](https://www.hyperly.de)

# License

[MIT](https://github.com/prod3v3loper/php-db-engine/blob/master/LICENSE) - [prod3v3loper](https://www.tnado.com/author/prod3v3loper/)

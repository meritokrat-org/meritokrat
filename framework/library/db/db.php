<?

load::system('db/db_connect');
load::system('db/db_exception');

class db
{
    public static function fetch_row($statement)
    {
        return $statement->fetch(pdo::FETCH_ASSOC);
    }

    public static function get_scalar($sql, $bind = array(), $connection_name = null, $cache_key = null)
    {
        if ($cache_key) {
            if (is_array($cache_key)) {
                $cache_ttl = $cache_key[1];
                $cache_key = $cache_key[0];
            }

            if (mem_cache::i()->exists($cache_key)) {
                return mem_cache::i()->get($cache_key);
            }
        }

        $statement = self::exec($sql, $bind, $connection_name);
        $data      = $statement->fetch(pdo::FETCH_COLUMN);

        if ($cache_key) {
            mem_cache::i()->set($cache_key, $data, $cache_ttl);
        }

        return $data;
    }

    /**
     * @return PDOStatement
     */
    public static function exec($sql, $bind = array(), $connection_name = null)
    {
        $log_id = (conf::get('enable_log')) ? logger::start($sql, 'SQL', logger::LEVEL_WARNING) : null;

        $statement = db_connect::get($connection_name)->prepare($sql);
        foreach ($bind as $key => $value) {
            $statement->bindValue(":{$key}", $value, self::get_bind_type($value));
        }

        $statement->execute();

        if (($statement->errorCode() != '0000') && !conf::get('disable_db_exceptions')) {
            $error = $statement->errorInfo();
            throw new dbException($error[2], null, $sql);
        }

        conf::get('enable_log') ? logger::commit($log_id) : null;

        return $statement;
    }

    protected static function get_bind_type($value)
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        }

        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }

        return PDO::PARAM_STR;
    }

    public static function get_row($sql, $bind = array(), $connection_name = null)
    {
        $statement = self::exec($sql, $bind, $connection_name);

        return $statement->fetch(pdo::FETCH_ASSOC);
    }

    public static function get_rows($sql, $bind = array(), $connection_name = null, $cache_key = null)
    {
        if ($cache_key) {
            if (is_array($cache_key)) {
                $cache_ttl = $cache_key[1];
                $cache_key = $cache_key[0];
            }

            if (mem_cache::i()->exists($cache_key)) {
                return mem_cache::i()->get($cache_key);
            }
        }

        $statement = self::exec($sql, $bind, $connection_name);
        $data      = $statement->fetchAll(pdo::FETCH_ASSOC);

        if ($cache_key) {
            mem_cache::i()->set($cache_key, $data, $cache_ttl);
        }

        return $data;
    }

    public static function get_cols($sql, $bind = array(), $connection_name = null, $cache_key = null)
    {
        if ($cache_key) {
            if (is_array($cache_key)) {
                $cache_ttl = $cache_key[1];
                $cache_key = $cache_key[0];
            }

            if (mem_cache::i()->exists($cache_key)) {
                return mem_cache::i()->get($cache_key);
            }
        }

        $statement = self::exec($sql, $bind, $connection_name);
        $data      = $statement->fetchAll(pdo::FETCH_COLUMN);

        if ($cache_key) {
            mem_cache::i()->set($cache_key, $data, $cache_ttl);
        }

        return $data;
    }

    public static function last_id($connection_name = null)
    {
        return db_connect::get($connection_name)->lastInsertId();
    }
}

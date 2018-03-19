<?php
/**
 *  Db - A simple database class 
 * PDO写入数据库 
 * @modify Sandy
 * @author		Author: Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal)
 * @git 		https://github.com/indieteq/PHP-MySQL-PDO-Database-Class
 * @version      0.2ab
 *
 */
namespace Mysql;
class Db
{
    # @object, The PDO object
    private $pdo;

    # @object, PDO statement object
    private $sQuery;

    # @array,  The database settings
    private $settings;

    # @bool ,  Connected to the database
    private $bConnected = false;

    # @object, Object for logging exceptions
    private $log;

    # @array, The parameters of the SQL query
    private $parameters;

    private static $instances = array();

    public static function getInstance($name = 'master') {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }
        self::$instances[$name] = new \Mysql\Db($name);
        return self::$instances[$name];
    }

    /**
     *   Default Constructor
     *
     *    1. Instantiate Log class.
     *    2. Connect to database.
     *    3. Creates the parameter array.
     */
    private function __construct($name = 'master')
    {
        $this->Connect($name);
        $this->parameters = array();
    }

    /**
     *    This method makes connection to the database.
     *
     *    1. Reads the database settings from a ini file.
     *    2. Puts  the ini content into the settings array.
     *    3. Tries to connect to the database.
     *    4. If connection failed, exception is displayed and a log file gets created.
     */
    private function Connect($name = 'master')
    {
        global $config;
        $mtime1 = microtime();
        $this->settings = $config['db'][$name];
        $dsn = 'mysql:dbname=' . $this->settings["dbname"] . ';host=' . $this->settings["host"] . '';
        try {
            # Read settings from INI file, set UTF8
            $this->pdo = new \PDO($dsn, $this->settings["user"], $this->settings["password"], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"));

            # We can now log any exceptions on Fatal error.
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

            # Connection succeeded, set the boolean to true.
            $this->bConnected = true;
        } catch (\PDOException $e) {
            # Write into log
            print_r($e);
            echo $this->ExceptionLog($e->getMessage());
            die();
        }
        $mtime2 = microtime();
        \common\DebugLog::_mysql('connect', null, array('host' => $this->settings['host'], 'dbname' => $this->settings['dbname']), $mtime1, $mtime2, null);
    }

    /*
     *   You can use this little method if you want to close the PDO connection
     *
     */
    public function CloseConnection()
    {
        # Set the PDO object to null to close the connection
        # http://www.php.net/manual/en/pdo.connections.php
        $this->pdo = null;
    }

    /**
     *    Every method which needs to execute a SQL query uses this method.
     *
     *    1. If not connected, connect to the database.
     *    2. Prepare Query.
     *    3. Parameterize Query.
     *    4. Execute Query.
     *    5. On exception : Write Exception into the log + SQL query.
     *    6. Reset the Parameters.
     */
    private function Init($query, $parameters = "")
    {
        # Connect to database
        if (!$this->bConnected) {
            $this->Connect();
        }
        try {

            # Prepare query
            $this->sQuery = $this->pdo->prepare($query);

            # Add parameters to the parameter array
            if ($parameters && isset($parameters[0])) {
                // ? 占位符形式
                # Execute SQL
                $this->succes = $this->sQuery->execute($parameters);
            } else {
                // :fieldname 字段名形式
                $this->bindMore($parameters);
                # Bind parameters
                if (!empty($this->parameters)) {
                    foreach ($this->parameters as $param) {
                        $parameters = explode("\x7F", $param);
                        $this->sQuery->bindParam($parameters[0], $parameters[1]);
                    }
                }
                # Execute SQL
                $this->succes = $this->sQuery->execute();
            }
        } catch (PDOException $e) {
            # Write into log and display Exception
            echo $this->ExceptionLog($e->getMessage(), $query);
            die();
        }

        # Reset the parameters
        $this->parameters = array();
    }

    /**
     * @void
     *
     *    Add the parameter to the parameter array
     * @param string $para
     * @param string $value
     */
    public function bind($para, $value)
    {
        if (is_array($para)) {
            $para = json_encode($para);
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $value;
//        $this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
    }

    /**
     * @void
     *
     *    Add more parameters to the parameter array
     * @param array $parray
     */
    public function bindMore($parray)
    {
        if (empty($this->parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }

    /**
     *    If the SQL query  contains a SELECT or SHOW statement it returns an array containing all of the result set row
     *    If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
     *
     * @param  string $query
     * @param  array $params
     * @param  int $fetchmode
     * @return mixed
     */
    public function query($query, $params = null, $fetchmode = \PDO::FETCH_ASSOC)
    {
        $mtime1 = microtime();
        $query = trim($query);

        $this->Init($query, $params);

        $rawStatement = explode(" ", $query);

        # Which SQL statement is used
        $statement = strtolower($rawStatement[0]);

        $ret = NULL;
        if ($statement === 'select' || $statement === 'show') {
            $ret = $this->sQuery->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            $ret = $this->sQuery->rowCount();
        }
        $mtime2 = microtime();
        \common\DebugLog::_mysql('query: ' . $query, $params, array('host' => $this->settings['host'], 'dbname' => $this->settings['dbname']), $mtime1, $mtime2, $ret);
        return $ret;
    }

    /**
     *  Returns the last inserted id.
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     *    Returns an array which represents a column from the result set
     *
     * @param  string $query
     * @param  array $params
     * @return array
     */
    public function column($query, $params = null)
    {
        $mtime1 = microtime();
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);

        $column = null;

        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }

        $mtime2 = microtime();
        \common\DebugLog::_mysql('column: ' . $query, $params, array('host' => $this->settings['host'], 'dbname' => $this->settings['dbname']), $mtime1, $mtime2, $column);
        return $column;

    }

    /**
     *    Returns an array which represents a row from the result set
     *
     * @param  string $query
     * @param  array $params
     * @param  int $fetchmode
     * @return array
     */
    public function row($query, $params = null, $fetchmode = \PDO::FETCH_ASSOC)
    {
        $mtime1 = microtime();
        $this->Init($query, $params);
        $ret = $this->sQuery->fetch($fetchmode);
        $mtime2 = microtime();
        \common\DebugLog::_mysql('row: ' . $query, $params, array('host' => $this->settings['host'], 'dbname' => $this->settings['dbname']), $mtime1, $mtime2, $ret);
        return $ret;
    }

    /**
     *    Returns the value of one single field/column
     *
     * @param  string $query
     * @param  array $params
     * @return string
     */
    public function single($query, $params = null)
    {
        $mtime1 = microtime();
        $this->Init($query, $params);
        $ret = $this->sQuery->fetchColumn();
        $mtime2 = microtime();
        \common\DebugLog::_mysql('single: ' . $query, $params, array('host' => $this->settings['host'], 'dbname' => $this->settings['dbname']), $mtime1, $mtime2, $ret);
        return $ret;
    }

    /**
     * Writes the log and returns the exception
     *
     * @param  string $message
     * @param  string $sql
     * @return string
     */
    private function ExceptionLog($message, $sql = "")
    {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message;
        $exception .= "<br /> You can find the error back in the log.";

        if (!empty($sql)) {
            # Add the Raw SQL to the Log
            $message .= "\r\nRaw SQL : " . $sql;

            return $exception;
        }
    }



}


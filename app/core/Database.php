<?php
/**
 * Database Connection Manager
 * 
 * Handles database connections using PDO with support for MySQL and PostgreSQL
 */
class Database
{
    private static $instance = null;
    private $connection = null;
    private $config;
    
    private function __construct()
    {
        $this->config = require APP_PATH . '/config/database.php';
        $this->connect();
    }
    
    /**
     * Get database instance (Singleton pattern)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * Connect to database
     */
    private function connect()
    {
        try {
            $driver = $this->config['driver'];
            $config = $this->config[$driver];
            
            switch ($driver) {
                case 'mysql':
                    $dsn = sprintf(
                        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                        $config['host'],
                        $config['port'],
                        $config['database'],
                        $config['charset']
                    );
                    break;
                    
                case 'pgsql':
                    $dsn = sprintf(
                        'pgsql:host=%s;port=%d;dbname=%s',
                        $config['host'],
                        $config['port'],
                        $config['database']
                    );
                    break;
                    
                default:
                    throw new Exception("Unsupported database driver: {$driver}");
            }
            
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
            
            // Set additional attributes for MySQL
            if ($driver === 'mysql' && $config['strict']) {
                $this->connection->exec("SET sql_mode='STRICT_ALL_TABLES'");
            }
            
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }
    
    /**
     * Handle connection errors
     */
    private function handleConnectionError($e)
    {
        // Only auto-create database in local/development environment
        $appEnv = class_exists('Environment') ? Environment::get('APP_ENV', 'production') : 'production';
        
        if ($this->config['driver'] === 'mysql' && $e->getCode() == 1049 && $appEnv === 'local') {
            // Database doesn't exist, try to create it (local only)
            $this->createDatabase();
        } else {
            // In production, log error without exposing details
            error_log("Database connection failed: " . $e->getMessage());
            
            if ($appEnv === 'production') {
                throw new Exception("Database connection failed. Please contact support.");
            } else {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Create database if it doesn't exist (MySQL only)
     */
    private function createDatabase()
    {
        try {
            $config = $this->config['mysql'];
            $dsn = sprintf(
                'mysql:host=%s;port=%d;charset=%s',
                $config['host'],
                $config['port'],
                $config['charset']
            );
            
            $pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
            
            $dbName = $config['database'];
            $charset = $config['charset'];
            $collation = $config['collation'];
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` 
                       CHARACTER SET {$charset} 
                       COLLATE {$collation}");
            
            // Reconnect to the newly created database
            $this->connect();
            
        } catch (PDOException $e) {
            throw new Exception("Failed to create database: " . $e->getMessage());
        }
    }
    
    /**
     * Execute a query
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    /**
     * Execute a select query and fetch all results
     */
    public function select($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Execute a select query and fetch one result
     */
    public function selectOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Insert data and return last insert ID
     */
    public function insert($table, $data)
    {
        $columns = array_keys($data);
        $values = array_map(function($col) { return ':' . $col; }, $columns);
        
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $values)
        );
        
        $this->query($sql, $data);
        return $this->connection->lastInsertId();
    }
    
    /**
     * Update data
     */
    public function update($table, $data, $where, $whereParams = [])
    {
        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = :{$column}";
        }
        
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $setParts),
            $where
        );
        
        $params = array_merge($data, $whereParams);
        return $this->query($sql, $params);
    }
    
    /**
     * Delete data
     */
    public function delete($table, $where, $params = [])
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params);
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->connection->rollBack();
    }
    
    /**
     * Close connection
     */
    public function close()
    {
        $this->connection = null;
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}

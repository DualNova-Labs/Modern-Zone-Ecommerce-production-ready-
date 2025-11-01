<?php
/**
 * Database Migration System
 * 
 * Handles database schema creation and updates
 */
require_once APP_PATH . '/core/Database.php';

class Migration
{
    private $db;
    private $migrationsTable = 'migrations';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->createMigrationsTable();
    }
    
    /**
     * Create migrations tracking table
     */
    private function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_migration (migration)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            echo "Failed to create migrations table: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Run all pending migrations
     */
    public function migrate()
    {
        $migrations = $this->getPendingMigrations();
        
        if (empty($migrations)) {
            echo "Nothing to migrate.\n";
            return;
        }
        
        $batch = $this->getNextBatch();
        
        foreach ($migrations as $migration) {
            echo "Migrating: {$migration}\n";
            
            try {
                $this->runMigration($migration);
                $this->recordMigration($migration, $batch);
                echo "Migrated: {$migration}\n";
            } catch (Exception $e) {
                echo "Migration failed: {$migration}\n";
                echo "Error: " . $e->getMessage() . "\n";
                break;
            }
        }
    }
    
    /**
     * Get list of pending migrations
     */
    private function getPendingMigrations()
    {
        $executed = $this->getExecutedMigrations();
        $available = $this->getAvailableMigrations();
        
        return array_diff($available, $executed);
    }
    
    /**
     * Get list of executed migrations
     */
    private function getExecutedMigrations()
    {
        $sql = "SELECT migration FROM {$this->migrationsTable}";
        $results = $this->db->select($sql);
        
        return array_column($results, 'migration');
    }
    
    /**
     * Get list of available migration files
     */
    private function getAvailableMigrations()
    {
        $migrationPath = APP_PATH . '/database/migrations';
        
        if (!is_dir($migrationPath)) {
            mkdir($migrationPath, 0755, true);
        }
        
        $files = glob($migrationPath . '/*.php');
        $migrations = [];
        
        foreach ($files as $file) {
            $migrations[] = basename($file, '.php');
        }
        
        sort($migrations);
        return $migrations;
    }
    
    /**
     * Run a specific migration
     */
    private function runMigration($migration)
    {
        $file = APP_PATH . '/database/migrations/' . $migration . '.php';
        
        if (!file_exists($file)) {
            throw new Exception("Migration file not found: {$file}");
        }
        
        require_once $file;
        
        $className = $this->getMigrationClassName($migration);
        
        if (!class_exists($className)) {
            throw new Exception("Migration class not found: {$className}");
        }
        
        $instance = new $className($this->db);
        
        if (!method_exists($instance, 'up')) {
            throw new Exception("Migration must have an 'up' method: {$className}");
        }
        
        $instance->up();
    }
    
    /**
     * Get migration class name from filename
     */
    private function getMigrationClassName($migration)
    {
        // Remove timestamp prefix (e.g., 2024_01_01_000000_)
        $parts = explode('_', $migration, 5);
        if (count($parts) >= 5) {
            $name = $parts[4];
        } else {
            $name = $migration;
        }
        
        // Convert to PascalCase
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        
        return $name;
    }
    
    /**
     * Record executed migration
     */
    private function recordMigration($migration, $batch)
    {
        $this->db->insert($this->migrationsTable, [
            'migration' => $migration,
            'batch' => $batch
        ]);
    }
    
    /**
     * Get next batch number
     */
    private function getNextBatch()
    {
        $sql = "SELECT MAX(batch) as max_batch FROM {$this->migrationsTable}";
        $result = $this->db->selectOne($sql);
        
        return ($result['max_batch'] ?? 0) + 1;
    }
    
    /**
     * Rollback last batch of migrations
     */
    public function rollback()
    {
        $lastBatch = $this->getLastBatch();
        
        if ($lastBatch === null) {
            echo "Nothing to rollback.\n";
            return;
        }
        
        $migrations = $this->getBatchMigrations($lastBatch);
        
        foreach (array_reverse($migrations) as $migration) {
            echo "Rolling back: {$migration}\n";
            
            try {
                $this->runRollback($migration);
                $this->removeMigration($migration);
                echo "Rolled back: {$migration}\n";
            } catch (Exception $e) {
                echo "Rollback failed: {$migration}\n";
                echo "Error: " . $e->getMessage() . "\n";
                break;
            }
        }
    }
    
    /**
     * Get last batch number
     */
    private function getLastBatch()
    {
        $sql = "SELECT MAX(batch) as last_batch FROM {$this->migrationsTable}";
        $result = $this->db->selectOne($sql);
        
        return $result['last_batch'];
    }
    
    /**
     * Get migrations from a specific batch
     */
    private function getBatchMigrations($batch)
    {
        $sql = "SELECT migration FROM {$this->migrationsTable} WHERE batch = :batch ORDER BY id";
        $results = $this->db->select($sql, ['batch' => $batch]);
        
        return array_column($results, 'migration');
    }
    
    /**
     * Run rollback for a migration
     */
    private function runRollback($migration)
    {
        $file = APP_PATH . '/database/migrations/' . $migration . '.php';
        
        if (!file_exists($file)) {
            throw new Exception("Migration file not found: {$file}");
        }
        
        require_once $file;
        
        $className = $this->getMigrationClassName($migration);
        $instance = new $className($this->db);
        
        if (!method_exists($instance, 'down')) {
            throw new Exception("Migration must have a 'down' method: {$className}");
        }
        
        $instance->down();
    }
    
    /**
     * Remove migration record
     */
    private function removeMigration($migration)
    {
        $this->db->delete($this->migrationsTable, 'migration = :migration', ['migration' => $migration]);
    }
    
    /**
     * Reset all migrations
     */
    public function reset()
    {
        $migrations = $this->getExecutedMigrations();
        
        foreach (array_reverse($migrations) as $migration) {
            echo "Rolling back: {$migration}\n";
            
            try {
                $this->runRollback($migration);
                $this->removeMigration($migration);
                echo "Rolled back: {$migration}\n";
            } catch (Exception $e) {
                echo "Rollback failed: {$migration}\n";
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }
    
    /**
     * Refresh database (reset and migrate)
     */
    public function refresh()
    {
        $this->reset();
        $this->migrate();
    }
}

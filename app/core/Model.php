<?php
/**
 * Base Model Class
 * 
 * Provides basic ORM functionality for all models
 */
require_once APP_PATH . '/core/Database.php';

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    protected $attributes = [];
    protected $original = [];
    protected $exists = false;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find a record by ID
     */
    public static function find($id)
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = :id LIMIT 1";
        $result = $instance->db->selectOne($sql, ['id' => $id]);
        
        if ($result) {
            return $instance->hydrate($result);
        }
        return null;
    }
    
    /**
     * Find a record by column value
     */
    public static function findBy($column, $value)
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE {$column} = :value LIMIT 1";
        $result = $instance->db->selectOne($sql, ['value' => $value]);
        
        if ($result) {
            return $instance->hydrate($result);
        }
        return null;
    }
    
    /**
     * Get all records
     */
    public static function all($columns = ['*'])
    {
        $instance = new static();
        $columns = implode(', ', $columns);
        $sql = "SELECT {$columns} FROM {$instance->table}";
        $results = $instance->db->select($sql);
        
        return array_map(function($row) use ($instance) {
            $model = new static();
            return $model->hydrate($row);
        }, $results);
    }
    
    /**
     * Get records with conditions
     */
    public static function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $instance = new static();
        return new QueryBuilder($instance->table, $instance->db, get_called_class());
    }
    
    /**
     * Create a new record
     */
    public static function create($data)
    {
        $instance = new static();
        $instance->fill($data);
        $instance->save();
        return $instance;
    }
    
    /**
     * Save the model
     */
    public function save()
    {
        $data = $this->getAttributes();
        
        // Remove hidden fields
        foreach ($this->hidden as $hidden) {
            unset($data[$hidden]);
        }
        
        // Add timestamps
        if ($this->timestamps) {
            if (!isset($this->attributes[$this->primaryKey])) {
                $data['created_at'] = date('Y-m-d H:i:s');
            }
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        if (isset($this->attributes[$this->primaryKey])) {
            // Update existing record
            $id = $this->attributes[$this->primaryKey];
            unset($data[$this->primaryKey]);
            
            $this->db->update(
                $this->table,
                $data,
                "{$this->primaryKey} = :id",
                ['id' => $id]
            );
        } else {
            // Insert new record
            $id = $this->db->insert($this->table, $data);
            $this->attributes[$this->primaryKey] = $id;
        }
        
        return true;
    }
    
    /**
     * Update the model
     */
    public function update($data)
    {
        $this->fill($data);
        return $this->save();
    }
    
    /**
     * Delete the model
     */
    public function delete()
    {
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->db->delete(
                $this->table,
                "{$this->primaryKey} = :id",
                ['id' => $this->attributes[$this->primaryKey]]
            );
        }
        return false;
    }
    
    /**
     * Fill model with data
     */
    public function fill($data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }
    
    /**
     * Hydrate model from database row
     */
    protected function hydrate($data)
    {
        $this->attributes = $data;
        $this->original = $data;
        $this->exists = true;
        return $this;
    }
    
    /**
     * Check if attribute has been modified
     */
    public function isDirty($attribute = null)
    {
        if ($attribute === null) {
            return $this->attributes !== $this->original;
        }
        
        $current = $this->attributes[$attribute] ?? null;
        $original = $this->original[$attribute] ?? null;
        
        return $current !== $original;
    }
    
    /**
     * Get model attributes
     */
    public function getAttributes()
    {
        $data = [];
        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, $this->hidden)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    
    /**
     * Get attribute value
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Set attribute value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Check if attribute exists
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }
    
    /**
     * Convert model to array
     */
    public function toArray()
    {
        return $this->getAttributes();
    }
    
    /**
     * Convert model to JSON
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Relationship: Has Many
     */
    protected function hasMany($relatedModel, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: strtolower(get_class($this)) . '_id';
        $localKey = $localKey ?: $this->primaryKey;
        
        $relatedInstance = new $relatedModel();
        $sql = "SELECT * FROM {$relatedInstance->table} WHERE {$foreignKey} = :id";
        $results = $this->db->select($sql, ['id' => $this->$localKey]);
        
        return array_map(function($row) use ($relatedModel) {
            $model = new $relatedModel();
            return $model->hydrate($row);
        }, $results);
    }
    
    /**
     * Relationship: Belongs To
     */
    protected function belongsTo($relatedModel, $foreignKey = null, $ownerKey = null)
    {
        $foreignKey = $foreignKey ?: strtolower($relatedModel) . '_id';
        $ownerKey = $ownerKey ?: 'id';
        
        $relatedInstance = new $relatedModel();
        $sql = "SELECT * FROM {$relatedInstance->table} WHERE {$ownerKey} = :id LIMIT 1";
        $result = $this->db->selectOne($sql, ['id' => $this->$foreignKey]);
        
        if ($result) {
            return $relatedInstance->hydrate($result);
        }
        return null;
    }
}

/**
 * Simple Query Builder
 */
class QueryBuilder
{
    private $table;
    private $db;
    private $modelClass;
    private $wheres = [];
    private $orderBy = [];
    private $limit = null;
    private $offset = null;
    
    public function __construct($table, $db, $modelClass)
    {
        $this->table = $table;
        $this->db = $db;
        $this->modelClass = $modelClass;
    }
    
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        
        return $this;
    }
    
    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }
    
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    
    public function get($columns = ['*'])
    {
        $columns = implode(', ', $columns);
        $sql = "SELECT {$columns} FROM {$this->table}";
        $params = [];
        
        if (!empty($this->wheres)) {
            $whereClauses = [];
            foreach ($this->wheres as $i => $where) {
                $paramKey = "where_{$i}";
                $whereClauses[] = "{$where['column']} {$where['operator']} :{$paramKey}";
                $params[$paramKey] = $where['value'];
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }
        
        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }
        
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }
        
        $results = $this->db->select($sql, $params);
        
        return array_map(function($row) {
            $model = new $this->modelClass();
            return $model->hydrate($row);
        }, $results);
    }
    
    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }
    
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if (!empty($this->wheres)) {
            $whereClauses = [];
            foreach ($this->wheres as $i => $where) {
                $paramKey = "where_{$i}";
                $whereClauses[] = "{$where['column']} {$where['operator']} :{$paramKey}";
                $params[$paramKey] = $where['value'];
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }
        
        $result = $this->db->selectOne($sql, $params);
        return $result ? (int)$result['count'] : 0;
    }
}

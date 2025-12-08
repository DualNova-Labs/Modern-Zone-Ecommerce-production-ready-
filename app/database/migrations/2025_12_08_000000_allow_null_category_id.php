<?php
/**
 * Allow category_id to be NULL for brand subcategory products
 */
class AllowNullCategoryId
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Run the migration
     */
    public function up()
    {
        // Modify category_id column to allow NULL values
        $this->db->query("
            ALTER TABLE products
            MODIFY COLUMN category_id INT NULL
        ");
    }

    /**
     * Reverse the migration
     */
    public function down()
    {
        // Revert category_id back to NOT NULL
        $this->db->query("
            ALTER TABLE products
            MODIFY COLUMN category_id INT NOT NULL
        ");
    }
}

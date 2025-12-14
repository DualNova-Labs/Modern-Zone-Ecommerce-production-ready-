-- Migration: Add category_type column to products table
-- Date: 2025-12-14
-- Description: This migration adds category_type column to support direct category type 
--              selection (general/our-products) without requiring subcategories.
-- =============================================================================

-- Step 1: Add category_type column if it doesn't exist
-- Note: Run this in phpMyAdmin or MySQL command line
ALTER TABLE products 
ADD COLUMN category_type ENUM('general', 'our-products') DEFAULT 'general' 
AFTER category_id;

-- Step 2: Add index for category_type (ignore error if already exists)
ALTER TABLE products ADD INDEX idx_category_type (category_type);

-- Step 3: Update existing products - copy the type from their linked category
UPDATE products p
LEFT JOIN categories c ON p.category_id = c.id
SET p.category_type = COALESCE(c.type, 'general')
WHERE p.category_type IS NULL;

-- Step 4: Make sure all products have a valid category_type
UPDATE products 
SET category_type = 'general' 
WHERE category_type IS NULL OR category_type = '';

-- Step 5: Optionally modify the foreign key on category_id to allow NULL
-- (Only run if you get foreign key constraint errors when adding products)
-- ALTER TABLE products DROP FOREIGN KEY products_ibfk_1;
-- ALTER TABLE products ADD CONSTRAINT products_ibfk_1 
--     FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- Add missing image column to products table
-- This fixes the "Unknown column 'p.image'" error

ALTER TABLE products 
ADD COLUMN IF NOT EXISTS image VARCHAR(255) DEFAULT NULL
AFTER weight;

-- Update index on the products table if needed
SHOW INDEX FROM products WHERE Key_name = 'idx_image';

-- Migration: Add type column to categories table
-- This allows distinguishing between 'general' and 'our-products' categories

-- Add type column to categories table
ALTER TABLE categories 
ADD COLUMN type ENUM('general', 'our-products') DEFAULT 'general' AFTER parent_id;

-- Add index for type column for better query performance
ALTER TABLE categories 
ADD INDEX idx_type (type);

-- Update existing hardcoded categories if they exist in the database
-- These are the current "GENERAL CATEGORIES" in the navigation

UPDATE categories SET type = 'general' WHERE slug IN (
    'hand-tools',
    'power-tools-electrical',
    'other-measuring-instruments',
    'safety',
    'machine-shop',
    'abrasive',
    'welding',
    'plumbing',
    'construction',
    'uncategorized'
);

-- These are the current "OUR PRODUCTS" categories in the navigation
UPDATE categories SET type = 'our-products' WHERE slug IN (
    'ball-cages',
    'band-saw-blades',
    'brazed-tool-holders',
    'bushes',
    'carbide-hss-drill-bits',
    'carbide-hss-end-mills',
    'carbide-rotary-burrs',
    'drill-chucks-lathe',
    'ejector-pins',
    'fibro',
    'grooving-threading-cut',
    'hack-saw-blades',
    'hole-saw-blades',
    'hole-saw-core-cutters',
    'machine-tool-accessories',
    'measuring-instruments',
    'milling-cutters',
    'pcd-cbn-ceramic-inserts',
    'pillars',
    'punches',
    'reamers-countersinks',
    'springs',
    'standard-parts-dies-molds',
    'taps-dies',
    'turning-holders',
    'turning-inserts',
    'u-drills'
);

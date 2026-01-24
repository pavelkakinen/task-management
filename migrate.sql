-- Migration: Add positions table and update employees table
-- Run this SQL to migrate existing database

-- Step 1: Create positions table
CREATE TABLE IF NOT EXISTS positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 2: Add positionId column to employees (if not exists)
-- First check if column exists, if not add it
SET @dbname = DATABASE();
SET @tablename = 'employees';
SET @columnname = 'positionId';
SET @preparedStatement = (SELECT IF(
    (
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME = @tablename
        AND COLUMN_NAME = @columnname
    ) > 0,
    'SELECT 1',
    'ALTER TABLE employees ADD COLUMN positionId INT NULL AFTER lastName'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Step 3: Add foreign key (if not exists)
-- Note: You may need to run this manually if it fails
-- ALTER TABLE employees ADD CONSTRAINT fk_employee_position FOREIGN KEY (positionId) REFERENCES positions(id) ON DELETE SET NULL;

-- Step 4: Migrate existing position data to positions table (optional)
-- This creates positions from existing unique position values
INSERT IGNORE INTO positions (title)
SELECT DISTINCT position FROM employees WHERE position IS NOT NULL AND position != '';

-- Step 5: Update employees to use positionId
UPDATE employees e
JOIN positions p ON e.position = p.title
SET e.positionId = p.id
WHERE e.position IS NOT NULL AND e.position != '';

-- Step 6: Drop old position column (optional - do this after verifying migration)
-- ALTER TABLE employees DROP COLUMN position;

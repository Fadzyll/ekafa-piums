<?php

use yii\db\Migration;

/**
 * Correction migration to align class table with Data Dictionary v1.0
 * Removes extra columns that were added but not in the specification
 */
class m251023_080000_enhance_class_table extends Migration
{
    private $tableName = 'class';
    
    public function safeUp()
    {
        echo "Correcting class table to match Data Dictionary specification...\n";
        
        // Get existing columns
        $schema = $this->db->schema->getTableSchema($this->tableName);
        $existingColumns = array_keys($schema->columns);
        
        // List of columns that should NOT exist according to PDF Table #4
        $columnsToRemove = [
            'session_id',
            'building',
            'floor',
            'start_time',
            'end_time',
            'days_of_week',
            'monthly_fee',
            'registration_fee',
            'assistant_teacher_id',
            'minimum_enrollment',
            'waiting_list_count',
            'min_age',
            'max_age',
            'description',
            'objectives',
            'prerequisites',
            'class_photo_url',
            'enrollment_start_date',
            'enrollment_end_date',
            'is_visible',
            'admin_notes',
            'created_by'
        ];
        
        // Remove extra columns
        foreach ($columnsToRemove as $column) {
            if (in_array($column, $existingColumns)) {
                // Drop related foreign keys first
                if ($column == 'assistant_teacher_id') {
                    try {
                        $this->dropForeignKey('fk_class_assistant_teacher', $this->tableName);
                        echo "Dropped FK: fk_class_assistant_teacher\n";
                    } catch (\Exception $e) {}
                }
                
                if ($column == 'created_by') {
                    try {
                        $this->dropForeignKey('fk_class_created_by', $this->tableName);
                        echo "Dropped FK: fk_class_created_by\n";
                    } catch (\Exception $e) {}
                }
                
                if ($column == 'session_id') {
                    try {
                        $this->dropIndex('idx_class_session', $this->tableName);
                    } catch (\Exception $e) {}
                }
                
                // Drop the column
                $this->dropColumn($this->tableName, $column);
                echo "✓ Removed column: {$column}\n";
            }
        }
        
        // Drop unnecessary indexes
        $indexesToRemove = [
            'idx_class_session',
            'idx_class_visible',
            'idx_class_enrollment',
            'idx_class_teacher' // Will recreate as idx_class_user_id
        ];
        
        foreach ($indexesToRemove as $index) {
            try {
                $this->dropIndex($index, $this->tableName);
                echo "✓ Removed index: {$index}\n";
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
        }
        
        // Ensure correct foreign keys
        try {
            $this->dropForeignKey('fk_class_teacher', $this->tableName);
        } catch (\Exception $e) {}
        
        try {
            $this->dropForeignKey('fk_class_user_id', $this->tableName);
        } catch (\Exception $e) {}
        
        // Add correct foreign key according to PDF
        try {
            $this->addForeignKey(
                'fk_class_user',
                $this->tableName,
                'user_id',
                'users',
                'user_id',
                'RESTRICT',
                'CASCADE'
            );
            echo "✓ Created FK: fk_class_user\n";
        } catch (\Exception $e) {
            echo "FK fk_class_user already exists\n";
        }
        
        // Ensure correct indexes according to PDF
        $correctIndexes = [
            'idx_class_user_id' => ['user_id'],
            'idx_class_status' => ['status'],
            'idx_class_session_type' => ['session_type']
        ];
        
        foreach ($correctIndexes as $indexName => $columns) {
            try {
                $this->createIndex($indexName, $this->tableName, $columns);
                echo "✓ Created index: {$indexName}\n";
            } catch (\Exception $e) {
                echo "Index {$indexName} already exists\n";
            }
        }
        
        // Update ENUM values to match specification
        echo "\nUpdating ENUM columns...\n";
        
        // session_type: Morning, Evening
        $this->execute("
            ALTER TABLE {$this->tableName} 
            MODIFY COLUMN session_type ENUM('Morning', 'Evening') NOT NULL
        ");
        echo "✓ Updated session_type ENUM\n";
        
        // status: Draft, Open, Closed, Full, In Progress, Completed, Cancelled
        $this->execute("
            ALTER TABLE {$this->tableName} 
            MODIFY COLUMN status ENUM('Draft', 'Open', 'Closed', 'Full', 'In Progress', 'Completed', 'Cancelled') 
            DEFAULT 'Draft'
        ");
        echo "✓ Updated status ENUM\n";
        
        // grade_level: Year 1-5
        $this->execute("
            ALTER TABLE {$this->tableName} 
            MODIFY COLUMN grade_level ENUM('Year 1', 'Year 2', 'Year 3', 'Year 4', 'Year 5') NULL
        ");
        echo "✓ Updated grade_level ENUM\n";
        
        echo "\n✅ Class table corrected to match Data Dictionary!\n";
        echo "Final structure:\n";
        echo "  - class_id (PK)\n";
        echo "  - user_id (FK)\n";
        echo "  - class_name\n";
        echo "  - session_type\n";
        echo "  - quota\n";
        echo "  - current_enrollment\n";
        echo "  - status\n";
        echo "  - grade_level\n";
        echo "  - classroom_location\n";
        echo "  - class_start_date\n";
        echo "  - class_end_date\n";
        echo "  - created_at\n";
        echo "  - updated_at\n";
        
        return true;
    }

    public function safeDown()
    {
        echo "This migration cannot be safely reverted.\n";
        echo "Please restore from backup: class_backup_20251023\n";
        return false;
    }
}
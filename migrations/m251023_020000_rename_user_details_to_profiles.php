<?php

use yii\db\Migration;

class m251023_020000_rename_user_details_to_profiles extends Migration
{
    private $backupTable = 'user_details_backup_20251023';
    private $oldTable = 'user_details';
    private $newTable = 'user_profiles';
    
    public function safeUp()
    {
        echo "Starting user_details to user_profiles migration...\n";
        
        // Check if table already renamed
        $tableExists = $this->db->schema->getTableSchema($this->oldTable);
        $newTableExists = $this->db->schema->getTableSchema($this->newTable);
        
        if ($newTableExists && !$tableExists) {
            echo "Table already renamed to user_profiles, continuing with enhancements...\n";
        } else {
            // 1. Create backup
            $this->execute("CREATE TABLE {$this->backupTable} AS SELECT * FROM {$this->oldTable}");
            $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
            echo "Backed up {$backupCount} user detail records\n";
            
            // 2. Drop existing foreign key
            $this->dropForeignKey('fk_user_details_user_id', $this->oldTable);
            
            // 3. Rename table
            $this->renameTable($this->oldTable, $this->newTable);
        }
        
        // 4. Check which columns need to be added
        $schema = $this->db->schema->getTableSchema($this->newTable);
        $existingColumns = array_keys($schema->columns);
        
        if (!in_array('date_of_birth', $existingColumns)) {
            $this->addColumn($this->newTable, 'date_of_birth', $this->date()->after('age'));
        }
        
        if (!in_array('emergency_phone', $existingColumns)) {
            $this->addColumn($this->newTable, 'emergency_phone', $this->string(20)->after('phone_number'));
        }
        
        if (!in_array('emergency_contact_name', $existingColumns)) {
            $this->addColumn($this->newTable, 'emergency_contact_name', $this->string(255)->after('emergency_phone'));
        }
        
        if (!in_array('emergency_contact_relationship', $existingColumns)) {
            $this->addColumn($this->newTable, 'emergency_contact_relationship', $this->string(100)->after('emergency_contact_name'));
        }
        
        if (!in_array('country', $existingColumns)) {
            $this->addColumn($this->newTable, 'country', $this->string(100)->defaultValue('Malaysia')->after('state'));
        }
        
        if (!in_array('blood_type', $existingColumns)) {
            // Create as string first, then convert to ENUM
            $this->addColumn($this->newTable, 'blood_type', $this->string(10)->defaultValue('Unknown')->after('gender'));
            $this->execute("ALTER TABLE {$this->newTable} MODIFY COLUMN blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Unknown') DEFAULT 'Unknown'");
        }
        
        if (!in_array('occupation', $existingColumns)) {
            $this->addColumn($this->newTable, 'occupation', $this->string(100)->after('marital_status'));
        }
        
        // 5. Populate defaults - SAFER DATE PARSING
        // Malaysian IC format: YYMMDD-PB-###G
        // Extract only if the date components are valid
        echo "Attempting to parse date of birth from IC numbers...\n";
        
        // Use a safer approach with validation
        $this->execute("
            UPDATE {$this->newTable}
            SET date_of_birth = CASE
                WHEN ic_number IS NOT NULL 
                AND LENGTH(ic_number) >= 6
                AND CAST(SUBSTRING(ic_number, 3, 2) AS UNSIGNED) BETWEEN 1 AND 12
                AND CAST(SUBSTRING(ic_number, 5, 2) AS UNSIGNED) BETWEEN 1 AND 31
                THEN STR_TO_DATE(
                    CONCAT('20', SUBSTRING(ic_number, 1, 2), '-', SUBSTRING(ic_number, 3, 2), '-', SUBSTRING(ic_number, 5, 2)),
                    '%Y-%m-%d'
                )
                ELSE NULL
            END
            WHERE date_of_birth IS NULL
        ");
        
        echo "Date of birth parsing completed (invalid dates left as NULL)\n";
        
        // 6. Recreate foreign key if it doesn't exist
        try {
            $this->addForeignKey('fk_user_profiles_user_id', $this->newTable, 'user_id', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "Foreign key already exists or error: " . $e->getMessage() . "\n";
        }
        
        // 7. Add indexes if they don't exist
        try {
            $this->createIndex('idx_user_profiles_blood_type', $this->newTable, 'blood_type');
        } catch (\Exception $e) {
            echo "Index already exists: idx_user_profiles_blood_type\n";
        }
        
        try {
            $this->createIndex('idx_user_profiles_date_of_birth', $this->newTable, 'date_of_birth');
        } catch (\Exception $e) {
            echo "Index already exists: idx_user_profiles_date_of_birth\n";
        }
        
        // 8. Verify
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->newTable}")->queryScalar();
        
        echo "Migration completed! Preserved {$currentCount} profiles\n";
        return true;
    }

    public function safeDown()
    {
        try { $this->dropIndex('idx_user_profiles_date_of_birth', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_user_profiles_blood_type', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_user_profiles_user_id', $this->newTable); } catch (\Exception $e) {}
        
        $this->dropColumn($this->newTable, 'occupation');
        $this->dropColumn($this->newTable, 'blood_type');
        $this->dropColumn($this->newTable, 'country');
        $this->dropColumn($this->newTable, 'emergency_contact_relationship');
        $this->dropColumn($this->newTable, 'emergency_contact_name');
        $this->dropColumn($this->newTable, 'emergency_phone');
        $this->dropColumn($this->newTable, 'date_of_birth');
        
        $this->renameTable($this->newTable, $this->oldTable);
        $this->addForeignKey('fk_user_details_user_id', $this->oldTable, 'user_id', 'users', 'user_id', 'SET NULL', 'CASCADE');
        
        return true;
    }
}
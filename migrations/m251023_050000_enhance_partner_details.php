<?php

use yii\db\Migration;

class m251023_050000_enhance_partner_details extends Migration
{
    private $backupTable = 'partner_details_backup_20251023';
    private $tableName = 'partner_details';
    
    public function safeUp()
    {
        echo "Starting partner_details table enhancement...\n";
        
        // 1. Create backup - drop old backup if exists
        $this->execute("DROP TABLE IF EXISTS {$this->backupTable}");
        $this->execute("CREATE TABLE {$this->backupTable} AS SELECT * FROM {$this->tableName}");
        $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
        echo "Backed up {$backupCount} partner records\n";
        
        // Rest of the code stays the same...
        // 2. Check and add columns
        $schema = $this->db->schema->getTableSchema($this->tableName);
        $existingColumns = array_keys($schema->columns);
        
        if (!in_array('date_of_birth', $existingColumns)) {
            $this->addColumn($this->tableName, 'date_of_birth', $this->date()->after('partner_ic_number'));
        }
        
        if (!in_array('gender', $existingColumns)) {
            $this->addColumn($this->tableName, 'gender', $this->string(10)->after('date_of_birth'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN gender ENUM('Male', 'Female') DEFAULT NULL");
        }
        
        if (!in_array('race', $existingColumns)) {
            $this->addColumn($this->tableName, 'race', $this->string(50)->after('gender'));
        }
        
        if (!in_array('religion', $existingColumns)) {
            $this->addColumn($this->tableName, 'religion', $this->string(50)->after('race'));
        }
        
        if (!in_array('email', $existingColumns)) {
            $this->addColumn($this->tableName, 'email', $this->string(255)->after('partner_phone_number'));
        }
        
        if (!in_array('alternative_phone', $existingColumns)) {
            $this->addColumn($this->tableName, 'alternative_phone', $this->string(20)->after('email'));
        }
        
        if (!in_array('emergency_contact_name', $existingColumns)) {
            $this->addColumn($this->tableName, 'emergency_contact_name', $this->string(255)->after('alternative_phone'));
        }
        
        if (!in_array('emergency_contact_phone', $existingColumns)) {
            $this->addColumn($this->tableName, 'emergency_contact_phone', $this->string(20)->after('emergency_contact_name'));
        }
        
        if (!in_array('emergency_contact_relationship', $existingColumns)) {
            $this->addColumn($this->tableName, 'emergency_contact_relationship', $this->string(100)->after('emergency_contact_phone'));
        }
        
        if (!in_array('relationship_to_user', $existingColumns)) {
            $this->addColumn($this->tableName, 'relationship_to_user', $this->string(20)->defaultValue('Spouse')->after('partner_marital_status'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN relationship_to_user ENUM('Spouse', 'Ex-Spouse', 'Partner', 'Other') DEFAULT 'Spouse'");
        }
        
        if (!in_array('marriage_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'marriage_date', $this->date()->after('relationship_to_user'));
        }
        
        if (!in_array('marriage_certificate_no', $existingColumns)) {
            $this->addColumn($this->tableName, 'marriage_certificate_no', $this->string(100)->after('marriage_date'));
        }
        
        if (!in_array('divorce_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'divorce_date', $this->date()->after('marriage_certificate_no'));
        }
        
        if (!in_array('divorce_certificate_no', $existingColumns)) {
            $this->addColumn($this->tableName, 'divorce_certificate_no', $this->string(100)->after('divorce_date'));
        }
        
        if (!in_array('country', $existingColumns)) {
            $this->addColumn($this->tableName, 'country', $this->string(100)->defaultValue('Malaysia')->after('partner_state'));
        }
        
        if (!in_array('education_level', $existingColumns)) {
            $this->addColumn($this->tableName, 'education_level', $this->string(30)->after('religion'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN education_level ENUM('No Formal Education', 'Primary', 'Secondary', 'Diploma', 'Bachelor', 'Master', 'PhD', 'Other') DEFAULT NULL");
        }
        
        if (!in_array('blood_type', $existingColumns)) {
            $this->addColumn($this->tableName, 'blood_type', $this->string(10)->defaultValue('Unknown')->after('gender'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Unknown') DEFAULT 'Unknown'");
        }
        
        if (!in_array('has_health_conditions', $existingColumns)) {
            $this->addColumn($this->tableName, 'has_health_conditions', $this->boolean()->defaultValue(false)->after('blood_type'));
        }
        
        if (!in_array('health_conditions_details', $existingColumns)) {
            $this->addColumn($this->tableName, 'health_conditions_details', $this->text()->after('has_health_conditions'));
        }
        
        if (!in_array('ic_copy_url', $existingColumns)) {
            $this->addColumn($this->tableName, 'ic_copy_url', $this->string(255)->after('partner_ic_number'));
        }
        
        if (!in_array('status', $existingColumns)) {
            $this->addColumn($this->tableName, 'status', $this->string(20)->defaultValue('Active')->after('profile_picture_url'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN status ENUM('Active', 'Inactive', 'Divorced', 'Deceased') DEFAULT 'Active'");
        }
        
        if (!in_array('is_verified', $existingColumns)) {
            $this->addColumn($this->tableName, 'is_verified', $this->boolean()->defaultValue(false)->after('status'));
        }
        
        if (!in_array('verified_by', $existingColumns)) {
            $this->addColumn($this->tableName, 'verified_by', $this->integer()->unsigned()->after('is_verified'));
        }
        
        if (!in_array('verified_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'verified_at', $this->datetime()->after('verified_by'));
        }
        
        if (!in_array('created_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'created_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP')->after('verified_at'));
        }
        
        if (!in_array('updated_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'updated_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->after('created_at'));
        }
        
        // 3. Populate defaults - Safe date parsing
        $this->execute("
            UPDATE {$this->tableName}
            SET date_of_birth = CASE
                WHEN partner_ic_number IS NOT NULL 
                AND LENGTH(partner_ic_number) >= 6
                AND CAST(SUBSTRING(partner_ic_number, 3, 2) AS UNSIGNED) BETWEEN 1 AND 12
                AND CAST(SUBSTRING(partner_ic_number, 5, 2) AS UNSIGNED) BETWEEN 1 AND 31
                THEN STR_TO_DATE(
                    CONCAT('20', SUBSTRING(partner_ic_number, 1, 2), '-', SUBSTRING(partner_ic_number, 3, 2), '-', SUBSTRING(partner_ic_number, 5, 2)),
                    '%Y-%m-%d'
                )
                ELSE NULL
            END
            WHERE date_of_birth IS NULL
        ");
        
        $this->execute("
            UPDATE {$this->tableName}
            SET 
                relationship_to_user = 'Spouse',
                country = 'Malaysia',
                status = 'Active',
                is_verified = 0,
                blood_type = 'Unknown',
                has_health_conditions = 0
            WHERE relationship_to_user IS NULL OR relationship_to_user = ''
        ");
        
        // Infer gender for existing partner (Nurin Nadhira)
        $this->execute("
            UPDATE {$this->tableName}
            SET gender = 'Female'
            WHERE partner_id = 3 AND partner_name LIKE '%Nurin%' AND (gender IS NULL OR gender = '')
        ");
        
        // 4. Create indexes
        try {
            $this->createIndex('idx_partner_ic', $this->tableName, 'partner_ic_number');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_partner_status', $this->tableName, 'status');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_partner_verified', $this->tableName, 'is_verified');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_partner_relationship', $this->tableName, 'relationship_to_user');
        } catch (\Exception $e) {}
        
        // 5. Add foreign key
        try {
            $this->addForeignKey('fk_partner_details_verified_by', $this->tableName, 'verified_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_partner_details_verified_by exists\n";
        }
        
        // 6. Verify
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->tableName}")->queryScalar();
        echo "Migration completed! Preserved {$currentCount} partner record\n";
        
        return true;
    }

    public function safeDown()
    {
        try { $this->dropForeignKey('fk_partner_details_verified_by', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_relationship', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_verified', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_status', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_ic', $this->tableName); } catch (\Exception $e) {}
        
        $this->dropColumn($this->tableName, 'updated_at');
        $this->dropColumn($this->tableName, 'created_at');
        $this->dropColumn($this->tableName, 'verified_at');
        $this->dropColumn($this->tableName, 'verified_by');
        $this->dropColumn($this->tableName, 'is_verified');
        $this->dropColumn($this->tableName, 'status');
        $this->dropColumn($this->tableName, 'ic_copy_url');
        $this->dropColumn($this->tableName, 'health_conditions_details');
        $this->dropColumn($this->tableName, 'has_health_conditions');
        $this->dropColumn($this->tableName, 'blood_type');
        $this->dropColumn($this->tableName, 'education_level');
        $this->dropColumn($this->tableName, 'country');
        $this->dropColumn($this->tableName, 'divorce_certificate_no');
        $this->dropColumn($this->tableName, 'divorce_date');
        $this->dropColumn($this->tableName, 'marriage_certificate_no');
        $this->dropColumn($this->tableName, 'marriage_date');
        $this->dropColumn($this->tableName, 'relationship_to_user');
        $this->dropColumn($this->tableName, 'emergency_contact_relationship');
        $this->dropColumn($this->tableName, 'emergency_contact_phone');
        $this->dropColumn($this->tableName, 'emergency_contact_name');
        $this->dropColumn($this->tableName, 'alternative_phone');
        $this->dropColumn($this->tableName, 'email');
        $this->dropColumn($this->tableName, 'religion');
        $this->dropColumn($this->tableName, 'race');
        $this->dropColumn($this->tableName, 'gender');
        $this->dropColumn($this->tableName, 'date_of_birth');
        
        return true;
    }
}
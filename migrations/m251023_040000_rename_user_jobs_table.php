<?php

use yii\db\Migration;

class m251023_040000_rename_user_jobs_table extends Migration
{
    private $backupTable = 'user_jobs_backup_20251023';
    private $oldTable = 'user_jobs';
    private $newTable = 'user_job_details';
    
    public function safeUp()
    {
        echo "Starting user_jobs to user_job_details migration...\n";
        
        // Check if already renamed
        $tableExists = $this->db->schema->getTableSchema($this->oldTable);
        $newTableExists = $this->db->schema->getTableSchema($this->newTable);
        
        if ($newTableExists && !$tableExists) {
            echo "Table already renamed to user_job_details, continuing with enhancements...\n";
        } else {
            // 1. Create backup
            $this->execute("CREATE TABLE {$this->backupTable} AS SELECT * FROM {$this->oldTable}");
            $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
            echo "Backed up {$backupCount} job records\n";
            
            // 2. Drop foreign key
            $this->dropForeignKey('fk_user_jobs_user', $this->oldTable);
            
            // 3. Rename primary key column
            $this->renameColumn($this->oldTable, 'userJob_id', 'job_detail_id');
            
            // 4. Rename table
            $this->renameTable($this->oldTable, $this->newTable);
        }
        
        // 5. Check and add columns
        $schema = $this->db->schema->getTableSchema($this->newTable);
        $existingColumns = array_keys($schema->columns);
        
        if (!in_array('job_title', $existingColumns)) {
            $this->addColumn($this->newTable, 'job_title', $this->string(100)->after('job'));
        }
        
        if (!in_array('employment_type', $existingColumns)) {
            $this->addColumn($this->newTable, 'employment_type', $this->string(20)->defaultValue('Full-Time')->after('job_title'));
            $this->execute("ALTER TABLE {$this->newTable} MODIFY COLUMN employment_type ENUM('Full-Time', 'Part-Time', 'Contract', 'Freelance', 'Self-Employed', 'Unemployed') DEFAULT 'Full-Time'");
        }
        
        if (!in_array('employment_status', $existingColumns)) {
            $this->addColumn($this->newTable, 'employment_status', $this->string(20)->defaultValue('Active')->after('employment_type'));
            $this->execute("ALTER TABLE {$this->newTable} MODIFY COLUMN employment_status ENUM('Active', 'Resigned', 'Terminated', 'Retired') DEFAULT 'Active'");
        }
        
        if (!in_array('start_date', $existingColumns)) {
            $this->addColumn($this->newTable, 'start_date', $this->date()->after('employer'));
        }
        
        if (!in_array('end_date', $existingColumns)) {
            $this->addColumn($this->newTable, 'end_date', $this->date()->after('start_date'));
        }
        
        if (!in_array('currency', $existingColumns)) {
            $this->addColumn($this->newTable, 'currency', $this->string(3)->defaultValue('MYR')->after('net_salary'));
        }
        
        if (!in_array('other_income', $existingColumns)) {
            $this->addColumn($this->newTable, 'other_income', $this->decimal(10, 2)->defaultValue(0)->after('currency'));
        }
        
        if (!in_array('other_income_source', $existingColumns)) {
            $this->addColumn($this->newTable, 'other_income_source', $this->string(255)->after('other_income'));
        }
        
        if (!in_array('department', $existingColumns)) {
            $this->addColumn($this->newTable, 'department', $this->string(100)->after('job_title'));
        }
        
        if (!in_array('working_hours_per_week', $existingColumns)) {
            $this->addColumn($this->newTable, 'working_hours_per_week', $this->integer()->after('employment_type'));
        }
        
        if (!in_array('tax_identification_number', $existingColumns)) {
            $this->addColumn($this->newTable, 'tax_identification_number', $this->string(50)->after('currency'));
        }
        
        if (!in_array('epf_number', $existingColumns)) {
            $this->addColumn($this->newTable, 'epf_number', $this->string(50)->after('tax_identification_number'));
        }
        
        if (!in_array('socso_number', $existingColumns)) {
            $this->addColumn($this->newTable, 'socso_number', $this->string(50)->after('epf_number'));
        }
        
        if (!in_array('employment_letter_url', $existingColumns)) {
            $this->addColumn($this->newTable, 'employment_letter_url', $this->string(255)->after('employer_address'));
        }
        
        if (!in_array('latest_payslip_url', $existingColumns)) {
            $this->addColumn($this->newTable, 'latest_payslip_url', $this->string(255)->after('employment_letter_url'));
        }
        
        if (!in_array('is_verified', $existingColumns)) {
            $this->addColumn($this->newTable, 'is_verified', $this->boolean()->defaultValue(false)->after('socso_number'));
        }
        
        if (!in_array('verified_by', $existingColumns)) {
            $this->addColumn($this->newTable, 'verified_by', $this->integer()->unsigned()->after('is_verified'));
        }
        
        if (!in_array('verified_at', $existingColumns)) {
            $this->addColumn($this->newTable, 'verified_at', $this->datetime()->after('verified_by'));
        }
        
        if (!in_array('created_at', $existingColumns)) {
            $this->addColumn($this->newTable, 'created_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP')->after('verified_at'));
        }
        
        if (!in_array('updated_at', $existingColumns)) {
            $this->addColumn($this->newTable, 'updated_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->after('created_at'));
        }
        
        // 6. Populate defaults
        $this->execute("
            UPDATE {$this->newTable}
            SET 
                job_title = COALESCE(job, 'Unknown'),
                employment_type = 'Full-Time',
                employment_status = 'Active',
                currency = 'MYR',
                other_income = 0,
                is_verified = 0,
                working_hours_per_week = 40
            WHERE job_title IS NULL OR job_title = ''
        ");
        
        // 7. Recreate foreign keys
        try {
            $this->addForeignKey('fk_user_job_details_user', $this->newTable, 'user_id', 'user_profiles', 'user_id', 'CASCADE', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_user_job_details_user exists\n";
        }
        
        try {
            $this->addForeignKey('fk_user_job_details_verified_by', $this->newTable, 'verified_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_user_job_details_verified_by exists\n";
        }
        
        // 8. Create indexes
        try {
            $this->createIndex('idx_job_details_employment_status', $this->newTable, 'employment_status');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_job_details_employment_type', $this->newTable, 'employment_type');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_job_details_verified', $this->newTable, 'is_verified');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_job_details_dates', $this->newTable, ['start_date', 'end_date']);
        } catch (\Exception $e) {}
        
        // 9. Verify
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->newTable}")->queryScalar();
        echo "Migration completed! Preserved {$currentCount} job records\n";
        
        return true;
    }

    public function safeDown()
    {
        try { $this->dropIndex('idx_job_details_dates', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_job_details_verified', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_job_details_employment_type', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_job_details_employment_status', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_user_job_details_verified_by', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_user_job_details_user', $this->newTable); } catch (\Exception $e) {}
        
        $this->dropColumn($this->newTable, 'updated_at');
        $this->dropColumn($this->newTable, 'created_at');
        $this->dropColumn($this->newTable, 'verified_at');
        $this->dropColumn($this->newTable, 'verified_by');
        $this->dropColumn($this->newTable, 'is_verified');
        $this->dropColumn($this->newTable, 'latest_payslip_url');
        $this->dropColumn($this->newTable, 'employment_letter_url');
        $this->dropColumn($this->newTable, 'socso_number');
        $this->dropColumn($this->newTable, 'epf_number');
        $this->dropColumn($this->newTable, 'tax_identification_number');
        $this->dropColumn($this->newTable, 'working_hours_per_week');
        $this->dropColumn($this->newTable, 'department');
        $this->dropColumn($this->newTable, 'other_income_source');
        $this->dropColumn($this->newTable, 'other_income');
        $this->dropColumn($this->newTable, 'currency');
        $this->dropColumn($this->newTable, 'end_date');
        $this->dropColumn($this->newTable, 'start_date');
        $this->dropColumn($this->newTable, 'employment_status');
        $this->dropColumn($this->newTable, 'employment_type');
        $this->dropColumn($this->newTable, 'job_title');
        
        $this->renameTable($this->newTable, $this->oldTable);
        $this->renameColumn($this->oldTable, 'job_detail_id', 'userJob_id');
        $this->addForeignKey('fk_user_jobs_user', $this->oldTable, 'user_id', 'user_details', 'user_id', 'CASCADE', 'CASCADE');
        
        return true;
    }
}
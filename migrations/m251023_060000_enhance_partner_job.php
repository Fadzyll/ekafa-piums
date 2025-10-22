<?php

use yii\db\Migration;

class m251023_060000_enhance_partner_job extends Migration
{
    private $backupTable = 'partner_job_backup_20251023';
    private $tableName = 'partner_job';
    
    public function safeUp()
    {
        echo "Starting partner_job table enhancement...\n";
        
        // 1. Create backup
        $this->execute("CREATE TABLE IF NOT EXISTS {$this->backupTable} AS SELECT * FROM {$this->tableName}");
        $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
        echo "Backed up {$backupCount} partner job records\n";
        
        // 2. Check and add columns
        $schema = $this->db->schema->getTableSchema($this->tableName);
        $existingColumns = array_keys($schema->columns);
        
        if (!in_array('job_title', $existingColumns)) {
            $this->addColumn($this->tableName, 'job_title', $this->string(100)->after('partner_job'));
        }
        
        if (!in_array('employment_type', $existingColumns)) {
            $this->addColumn($this->tableName, 'employment_type', $this->string(20)->defaultValue('Full-Time')->after('job_title'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN employment_type ENUM('Full-Time', 'Part-Time', 'Contract', 'Freelance', 'Self-Employed', 'Unemployed') DEFAULT 'Full-Time'");
        }
        
        if (!in_array('employment_status', $existingColumns)) {
            $this->addColumn($this->tableName, 'employment_status', $this->string(20)->defaultValue('Active')->after('employment_type'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN employment_status ENUM('Active', 'Resigned', 'Terminated', 'Retired') DEFAULT 'Active'");
        }
        
        if (!in_array('start_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'start_date', $this->date()->after('partner_employer'));
        }
        
        if (!in_array('end_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'end_date', $this->date()->after('start_date'));
        }
        
        if (!in_array('department', $existingColumns)) {
            $this->addColumn($this->tableName, 'department', $this->string(100)->after('job_title'));
        }
        
        if (!in_array('working_hours_per_week', $existingColumns)) {
            $this->addColumn($this->tableName, 'working_hours_per_week', $this->integer()->after('employment_type'));
        }
        
        if (!in_array('currency', $existingColumns)) {
            $this->addColumn($this->tableName, 'currency', $this->string(3)->defaultValue('MYR')->after('partner_net_salary'));
        }
        
        if (!in_array('other_income', $existingColumns)) {
            $this->addColumn($this->tableName, 'other_income', $this->decimal(10, 2)->defaultValue(0)->after('currency'));
        }
        
        if (!in_array('other_income_source', $existingColumns)) {
            $this->addColumn($this->tableName, 'other_income_source', $this->string(255)->after('other_income'));
        }
        
        if (!in_array('tax_identification_number', $existingColumns)) {
            $this->addColumn($this->tableName, 'tax_identification_number', $this->string(50)->after('other_income_source'));
        }
        
        if (!in_array('epf_number', $existingColumns)) {
            $this->addColumn($this->tableName, 'epf_number', $this->string(50)->after('tax_identification_number'));
        }
        
        if (!in_array('socso_number', $existingColumns)) {
            $this->addColumn($this->tableName, 'socso_number', $this->string(50)->after('epf_number'));
        }
        
        if (!in_array('employment_letter_url', $existingColumns)) {
            $this->addColumn($this->tableName, 'employment_letter_url', $this->string(255)->after('partner_employer_address'));
        }
        
        if (!in_array('latest_payslip_url', $existingColumns)) {
            $this->addColumn($this->tableName, 'latest_payslip_url', $this->string(255)->after('employment_letter_url'));
        }
        
        if (!in_array('is_verified', $existingColumns)) {
            $this->addColumn($this->tableName, 'is_verified', $this->boolean()->defaultValue(false)->after('socso_number'));
        }
        
        if (!in_array('verified_by', $existingColumns)) {
            $this->addColumn($this->tableName, 'verified_by', $this->integer()->unsigned()->after('is_verified'));
        }
        
        if (!in_array('verified_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'verified_at', $this->datetime()->after('verified_by'));
        }
        
        if (!in_array('notes', $existingColumns)) {
            $this->addColumn($this->tableName, 'notes', $this->text()->after('verified_at'));
        }
        
        if (!in_array('created_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'created_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP')->after('notes'));
        }
        
        if (!in_array('updated_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'updated_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->after('created_at'));
        }
        
        // 3. Populate defaults
        $this->execute("
            UPDATE {$this->tableName}
            SET 
                job_title = COALESCE(partner_job, 'Unknown'),
                employment_type = 'Full-Time',
                employment_status = 'Active',
                currency = 'MYR',
                other_income = 0,
                working_hours_per_week = 40,
                is_verified = 0
            WHERE job_title IS NULL OR job_title = ''
        ");
        
        // 4. Create indexes
        try {
            $this->createIndex('idx_partner_job_status', $this->tableName, 'employment_status');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_partner_job_type', $this->tableName, 'employment_type');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_partner_job_verified', $this->tableName, 'is_verified');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_partner_job_dates', $this->tableName, ['start_date', 'end_date']);
        } catch (\Exception $e) {}
        
        // 5. Add foreign key
        try {
            $this->addForeignKey('fk_partner_job_verified_by', $this->tableName, 'verified_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_partner_job_verified_by exists\n";
        }
        
        // 6. Verify
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->tableName}")->queryScalar();
        echo "Migration completed! Preserved {$currentCount} partner job record\n";
        
        return true;
    }

    public function safeDown()
    {
        try { $this->dropForeignKey('fk_partner_job_verified_by', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_job_dates', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_job_verified', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_job_type', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_partner_job_status', $this->tableName); } catch (\Exception $e) {}
        
        $this->dropColumn($this->tableName, 'updated_at');
        $this->dropColumn($this->tableName, 'created_at');
        $this->dropColumn($this->tableName, 'notes');
        $this->dropColumn($this->tableName, 'verified_at');
        $this->dropColumn($this->tableName, 'verified_by');
        $this->dropColumn($this->tableName, 'is_verified');
        $this->dropColumn($this->tableName, 'latest_payslip_url');
        $this->dropColumn($this->tableName, 'employment_letter_url');
        $this->dropColumn($this->tableName, 'socso_number');
        $this->dropColumn($this->tableName, 'epf_number');
        $this->dropColumn($this->tableName, 'tax_identification_number');
        $this->dropColumn($this->tableName, 'other_income_source');
        $this->dropColumn($this->tableName, 'other_income');
        $this->dropColumn($this->tableName, 'currency');
        $this->dropColumn($this->tableName, 'working_hours_per_week');
        $this->dropColumn($this->tableName, 'department');
        $this->dropColumn($this->tableName, 'end_date');
        $this->dropColumn($this->tableName, 'start_date');
        $this->dropColumn($this->tableName, 'employment_status');
        $this->dropColumn($this->tableName, 'employment_type');
        $this->dropColumn($this->tableName, 'job_title');
        
        return true;
    }
}
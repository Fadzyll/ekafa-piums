<?php

use yii\db\Migration;

class m251023_070000_enhance_classes_table extends Migration
{
    private $backupTable = 'class_backup_20251023';
    private $tableName = 'class';
    
    public function safeUp()
    {
        echo "Starting class table enhancement...\n";
        
        // 1. Create backup
        $this->execute("CREATE TABLE IF NOT EXISTS {$this->backupTable} AS SELECT * FROM {$this->tableName}");
        $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
        echo "Backed up {$backupCount} class records\n";
        
        // 2. Check and add columns
        $schema = $this->db->schema->getTableSchema($this->tableName);
        $existingColumns = array_keys($schema->columns);
        
        if (!in_array('session_id', $existingColumns)) {
            $this->addColumn($this->tableName, 'session_id', $this->integer()->unsigned()->after('year'));
        }
        
        if (!in_array('grade_level', $existingColumns)) {
            $this->addColumn($this->tableName, 'grade_level', $this->string(20)->after('class_name'));
            $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN grade_level ENUM('Pre-School', 'Year 1', 'Year 2', 'Year 3', 'Year 4', 'Year 5', 'Year 6') DEFAULT NULL");
        }
        
        if (!in_array('classroom_location', $existingColumns)) {
            $this->addColumn($this->tableName, 'classroom_location', $this->string(100)->after('session_type'));
        }
        
        if (!in_array('building', $existingColumns)) {
            $this->addColumn($this->tableName, 'building', $this->string(50)->after('classroom_location'));
        }
        
        if (!in_array('floor', $existingColumns)) {
            $this->addColumn($this->tableName, 'floor', $this->integer()->after('building'));
        }
        
        if (!in_array('start_time', $existingColumns)) {
            $this->addColumn($this->tableName, 'start_time', $this->time()->after('session_type'));
        }
        
        if (!in_array('end_time', $existingColumns)) {
            $this->addColumn($this->tableName, 'end_time', $this->time()->after('start_time'));
        }
        
        if (!in_array('days_of_week', $existingColumns)) {
            $this->addColumn($this->tableName, 'days_of_week', $this->string(100)->after('end_time'));
        }
        
        if (!in_array('monthly_fee', $existingColumns)) {
            $this->addColumn($this->tableName, 'monthly_fee', $this->decimal(10, 2)->after('quota'));
        }
        
        if (!in_array('registration_fee', $existingColumns)) {
            $this->addColumn($this->tableName, 'registration_fee', $this->decimal(10, 2)->after('monthly_fee'));
        }
        
        if (!in_array('assistant_teacher_id', $existingColumns)) {
            $this->addColumn($this->tableName, 'assistant_teacher_id', $this->integer()->unsigned()->after('user_id'));
        }
        
        if (!in_array('minimum_enrollment', $existingColumns)) {
            $this->addColumn($this->tableName, 'minimum_enrollment', $this->integer()->defaultValue(5)->after('quota'));
        }
        
        if (!in_array('waiting_list_count', $existingColumns)) {
            $this->addColumn($this->tableName, 'waiting_list_count', $this->integer()->defaultValue(0)->after('current_enrollment'));
        }
        
        if (!in_array('min_age', $existingColumns)) {
            $this->addColumn($this->tableName, 'min_age', $this->integer()->after('grade_level'));
        }
        
        if (!in_array('max_age', $existingColumns)) {
            $this->addColumn($this->tableName, 'max_age', $this->integer()->after('min_age'));
        }
        
        if (!in_array('description', $existingColumns)) {
            $this->addColumn($this->tableName, 'description', $this->text()->after('class_name'));
        }
        
        if (!in_array('objectives', $existingColumns)) {
            $this->addColumn($this->tableName, 'objectives', $this->text()->after('description'));
        }
        
        if (!in_array('prerequisites', $existingColumns)) {
            $this->addColumn($this->tableName, 'prerequisites', $this->text()->after('objectives'));
        }
        
        if (!in_array('class_photo_url', $existingColumns)) {
            $this->addColumn($this->tableName, 'class_photo_url', $this->string(255)->after('classroom_location'));
        }
        
        if (!in_array('enrollment_start_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'enrollment_start_date', $this->date()->after('status'));
        }
        
        if (!in_array('enrollment_end_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'enrollment_end_date', $this->date()->after('enrollment_start_date'));
        }
        
        if (!in_array('class_start_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'class_start_date', $this->date()->after('enrollment_end_date'));
        }
        
        if (!in_array('class_end_date', $existingColumns)) {
            $this->addColumn($this->tableName, 'class_end_date', $this->date()->after('class_start_date'));
        }
        
        if (!in_array('is_visible', $existingColumns)) {
            $this->addColumn($this->tableName, 'is_visible', $this->boolean()->defaultValue(true)->after('status'));
        }
        
        if (!in_array('admin_notes', $existingColumns)) {
            $this->addColumn($this->tableName, 'admin_notes', $this->text()->after('is_visible'));
        }
        
        if (!in_array('created_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'created_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP')->after('admin_notes'));
        }
        
        if (!in_array('updated_at', $existingColumns)) {
            $this->addColumn($this->tableName, 'updated_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->after('created_at'));
        }
        
        if (!in_array('created_by', $existingColumns)) {
            $this->addColumn($this->tableName, 'created_by', $this->integer()->unsigned()->after('updated_at'));
        }
        
        // 3. Update status enum
        $this->execute("
            ALTER TABLE {$this->tableName} 
            MODIFY COLUMN status ENUM('Draft', 'Open', 'Closed', 'Full', 'In Progress', 'Completed', 'Cancelled') 
            DEFAULT 'Draft'
        ");
        
        // 4. Update foreign keys
        try {
            $this->dropForeignKey('fk_class_user_id', $this->tableName);
        } catch (\Exception $e) {
            echo "FK fk_class_user_id doesn't exist\n";
        }
        
        try {
            $this->addForeignKey('fk_class_teacher', $this->tableName, 'user_id', 'users', 'user_id', 'RESTRICT', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_class_teacher exists\n";
        }
        
        try {
            $this->addForeignKey('fk_class_assistant_teacher', $this->tableName, 'assistant_teacher_id', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_class_assistant_teacher exists\n";
        }
        
        try {
            $this->addForeignKey('fk_class_created_by', $this->tableName, 'created_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_class_created_by exists\n";
        }
        
        // 5. Create indexes
        try {
            $this->dropIndex('idx_class_user_id', $this->tableName);
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_teacher', $this->tableName, 'user_id');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_session', $this->tableName, 'session_id');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_year', $this->tableName, 'year');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_status', $this->tableName, 'status');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_visible', $this->tableName, 'is_visible');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_grade_level', $this->tableName, 'grade_level');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_session_type', $this->tableName, 'session_type');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_class_enrollment', $this->tableName, ['enrollment_start_date', 'enrollment_end_date']);
        } catch (\Exception $e) {}
        
        // 6. Verify
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->tableName}")->queryScalar();
        echo "Migration completed! Preserved {$currentCount} class records\n";
        
        return true;
    }

    public function safeDown()
    {
        try { $this->dropIndex('idx_class_enrollment', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_session_type', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_grade_level', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_visible', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_status', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_year', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_session', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_class_teacher', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_class_created_by', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_class_assistant_teacher', $this->tableName); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_class_teacher', $this->tableName); } catch (\Exception $e) {}
        
        $this->dropColumn($this->tableName, 'created_by');
        $this->dropColumn($this->tableName, 'updated_at');
        $this->dropColumn($this->tableName, 'created_at');
        $this->dropColumn($this->tableName, 'admin_notes');
        $this->dropColumn($this->tableName, 'is_visible');
        $this->dropColumn($this->tableName, 'class_end_date');
        $this->dropColumn($this->tableName, 'class_start_date');
        $this->dropColumn($this->tableName, 'enrollment_end_date');
        $this->dropColumn($this->tableName, 'enrollment_start_date');
        $this->dropColumn($this->tableName, 'class_photo_url');
        $this->dropColumn($this->tableName, 'prerequisites');
        $this->dropColumn($this->tableName, 'objectives');
        $this->dropColumn($this->tableName, 'description');
        $this->dropColumn($this->tableName, 'max_age');
        $this->dropColumn($this->tableName, 'min_age');
        $this->dropColumn($this->tableName, 'waiting_list_count');
        $this->dropColumn($this->tableName, 'minimum_enrollment');
        $this->dropColumn($this->tableName, 'assistant_teacher_id');
        $this->dropColumn($this->tableName, 'registration_fee');
        $this->dropColumn($this->tableName, 'monthly_fee');
        $this->dropColumn($this->tableName, 'days_of_week');
        $this->dropColumn($this->tableName, 'end_time');
        $this->dropColumn($this->tableName, 'start_time');
        $this->dropColumn($this->tableName, 'floor');
        $this->dropColumn($this->tableName, 'building');
        $this->dropColumn($this->tableName, 'classroom_location');
        $this->dropColumn($this->tableName, 'grade_level');
        $this->dropColumn($this->tableName, 'session_id');
        
        $this->execute("ALTER TABLE {$this->tableName} MODIFY COLUMN status ENUM('Open','Closed','Full') DEFAULT 'Open'");
        $this->addForeignKey('fk_class_user_id', $this->tableName, 'user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_class_user_id', $this->tableName, 'user_id');
        
        return true;
    }
}
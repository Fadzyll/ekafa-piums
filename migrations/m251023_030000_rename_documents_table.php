<?php

use yii\db\Migration;

class m251023_030000_rename_documents_table extends Migration
{
    private $backupTable = 'user_documents_backup_20251023';
    private $oldTable = 'user_documents';
    private $newTable = 'documents';
    
    public function safeUp()
    {
        echo "Starting user_documents to documents migration...\n";
        
        // Check if already renamed
        $tableExists = $this->db->schema->getTableSchema($this->oldTable);
        $newTableExists = $this->db->schema->getTableSchema($this->newTable);
        
        if ($newTableExists && !$tableExists) {
            echo "Table already renamed to documents, continuing with enhancements...\n";
        } else {
            // 1. Create backup
            $this->execute("CREATE TABLE {$this->backupTable} AS SELECT * FROM {$this->oldTable}");
            $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
            echo "Backed up {$backupCount} document records\n";
            
            // 2. Drop foreign keys
            $this->dropForeignKey('fk_user_documents_user', $this->oldTable);
            $this->dropForeignKey('fk-user_documents-category_id', $this->oldTable);
            
            // 3. Rename table
            $this->renameTable($this->oldTable, $this->newTable);
        }
        
        // 4. Check and add columns
        $schema = $this->db->schema->getTableSchema($this->newTable);
        $existingColumns = array_keys($schema->columns);
        
        // Add polymorphic fields
        if (!in_array('owner_type', $existingColumns)) {
            $this->addColumn($this->newTable, 'owner_type', $this->string(20)->defaultValue('User')->after('user_id'));
            $this->execute("ALTER TABLE {$this->newTable} MODIFY COLUMN owner_type ENUM('User', 'Student', 'Teacher', 'Partner') DEFAULT 'User'");
        }
        
        if (!in_array('owner_id', $existingColumns)) {
            $this->addColumn($this->newTable, 'owner_id', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('owner_type'));
        }
        
        if (!in_array('document_name', $existingColumns)) {
            $this->addColumn($this->newTable, 'document_name', $this->string(255)->notNull()->defaultValue('Untitled')->after('document_type'));
        }
        
        if (!in_array('original_filename', $existingColumns)) {
            $this->addColumn($this->newTable, 'original_filename', $this->string(255)->after('document_name'));
        }
        
        if (!in_array('file_size', $existingColumns)) {
            $this->addColumn($this->newTable, 'file_size', $this->integer()->after('file_url'));
        }
        
        if (!in_array('mime_type', $existingColumns)) {
            $this->addColumn($this->newTable, 'mime_type', $this->string(100)->after('file_size'));
        }
        
        if (!in_array('file_hash', $existingColumns)) {
            $this->addColumn($this->newTable, 'file_hash', $this->string(64)->after('mime_type'));
        }
        
        if (!in_array('uploaded_by', $existingColumns)) {
            $this->addColumn($this->newTable, 'uploaded_by', $this->integer()->unsigned()->after('user_id'));
        }
        
        if (!in_array('verified_by', $existingColumns)) {
            $this->addColumn($this->newTable, 'verified_by', $this->integer()->unsigned()->after('uploaded_by'));
        }
        
        if (!in_array('verified_at', $existingColumns)) {
            $this->addColumn($this->newTable, 'verified_at', $this->datetime()->after('verified_by'));
        }
        
        if (!in_array('rejection_reason', $existingColumns)) {
            $this->addColumn($this->newTable, 'rejection_reason', $this->text()->after('admin_notes'));
        }
        
        if (!in_array('expiry_date', $existingColumns)) {
            $this->addColumn($this->newTable, 'expiry_date', $this->date()->after('upload_date'));
        }
        
        if (!in_array('version', $existingColumns)) {
            $this->addColumn($this->newTable, 'version', $this->integer()->defaultValue(1)->after('document_name'));
        }
        
        if (!in_array('is_latest_version', $existingColumns)) {
            $this->addColumn($this->newTable, 'is_latest_version', $this->boolean()->defaultValue(true)->after('version'));
        }
        
        if (!in_array('updated_at', $existingColumns)) {
            $this->addColumn($this->newTable, 'updated_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->after('upload_date'));
        }
        
        // 5. Migrate existing data (if any)
        $existingDocuments = $this->db->createCommand("SELECT COUNT(*) FROM {$this->newTable}")->queryScalar();
        if ($existingDocuments > 0) {
            $this->execute("
                UPDATE {$this->newTable} 
                SET 
                    owner_type = 'User', 
                    owner_id = COALESCE(user_id, 0), 
                    uploaded_by = user_id, 
                    document_name = COALESCE(document_type, 'Untitled'), 
                    is_latest_version = 1, 
                    version = 1 
                WHERE owner_id = 0 OR owner_id IS NULL
            ");
        }
        
        // 6. Update status enum
        $this->execute("
            ALTER TABLE {$this->newTable} 
            MODIFY COLUMN status ENUM('Pending Review', 'Approved', 'Rejected', 'Expired', 'Replaced', 'Deleted') 
            DEFAULT 'Pending Review'
        ");
        
        // 7. Recreate foreign keys
        try {
            $this->addForeignKey('fk_documents_user', $this->newTable, 'user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_documents_user exists\n";
        }
        
        try {
            $this->addForeignKey('fk_documents_category', $this->newTable, 'category_id', 'document_categories', 'category_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_documents_category exists\n";
        }
        
        try {
            $this->addForeignKey('fk_documents_uploaded_by', $this->newTable, 'uploaded_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_documents_uploaded_by exists\n";
        }
        
        try {
            $this->addForeignKey('fk_documents_verified_by', $this->newTable, 'verified_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        } catch (\Exception $e) {
            echo "FK fk_documents_verified_by exists\n";
        }
        
        // 8. Create indexes
        try {
            $this->createIndex('idx_documents_owner', $this->newTable, ['owner_type', 'owner_id']);
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_documents_status', $this->newTable, 'status');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_documents_file_hash', $this->newTable, 'file_hash');
        } catch (\Exception $e) {}
        
        try {
            $this->createIndex('idx_documents_latest_version', $this->newTable, 'is_latest_version');
        } catch (\Exception $e) {}
        
        // 9. Verify
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->newTable}")->queryScalar();
        echo "Migration completed! Preserved {$currentCount} documents\n";
        
        return true;
    }

    public function safeDown()
    {
        try { $this->dropIndex('idx_documents_latest_version', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_documents_file_hash', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_documents_status', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropIndex('idx_documents_owner', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_documents_verified_by', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_documents_uploaded_by', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_documents_category', $this->newTable); } catch (\Exception $e) {}
        try { $this->dropForeignKey('fk_documents_user', $this->newTable); } catch (\Exception $e) {}
        
        $this->dropColumn($this->newTable, 'updated_at');
        $this->dropColumn($this->newTable, 'is_latest_version');
        $this->dropColumn($this->newTable, 'version');
        $this->dropColumn($this->newTable, 'expiry_date');
        $this->dropColumn($this->newTable, 'rejection_reason');
        $this->dropColumn($this->newTable, 'verified_at');
        $this->dropColumn($this->newTable, 'verified_by');
        $this->dropColumn($this->newTable, 'uploaded_by');
        $this->dropColumn($this->newTable, 'file_hash');
        $this->dropColumn($this->newTable, 'mime_type');
        $this->dropColumn($this->newTable, 'file_size');
        $this->dropColumn($this->newTable, 'original_filename');
        $this->dropColumn($this->newTable, 'document_name');
        $this->dropColumn($this->newTable, 'owner_id');
        $this->dropColumn($this->newTable, 'owner_type');
        
        $this->execute("ALTER TABLE {$this->newTable} MODIFY COLUMN status ENUM('Completed','Incomplete','Pending Review','Rejected') DEFAULT 'Pending Review'");
        $this->renameTable($this->newTable, $this->oldTable);
        $this->addForeignKey('fk_user_documents_user', $this->oldTable, 'user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-user_documents-category_id', $this->oldTable, 'category_id', 'document_categories', 'category_id', 'SET NULL', 'CASCADE');
        
        return true;
    }
}
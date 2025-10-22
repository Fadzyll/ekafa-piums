<?php

use yii\db\Migration;

class m251023_010000_enhance_users_table extends Migration
{
    private $backupTable = 'users_backup_20251023';
    
    public function safeUp()
    {
        echo "Starting users table enhancement...\n";
        
        // 1. Create backup
        $this->execute("CREATE TABLE {$this->backupTable} AS SELECT * FROM users");
        $backupCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
        echo "Backed up {$backupCount} users\n";
        
        // 2. Add new columns
        $this->addColumn('users', 'auth_key', $this->string(32)->after('password_hash'));
        $this->addColumn('users', 'password_reset_token', $this->string(255)->unique()->after('auth_key'));
        $this->addColumn('users', 'verification_token', $this->string(255)->unique()->after('password_reset_token'));
        $this->addColumn('users', 'status', $this->smallInteger()->notNull()->defaultValue(10)->after('role'));
        $this->addColumn('users', 'created_at', $this->integer()->after('last_login'));
        $this->addColumn('users', 'updated_at', $this->integer()->after('created_at'));
        
        // 3. Populate data
        $this->execute("UPDATE users SET auth_key = MD5(CONCAT(user_id, email, UNIX_TIMESTAMP())) WHERE auth_key IS NULL");
        $this->execute("UPDATE users SET created_at = UNIX_TIMESTAMP(date_registered), updated_at = UNIX_TIMESTAMP(COALESCE(last_login, date_registered)) WHERE created_at IS NULL");
        
        // 4. Make required
        $this->alterColumn('users', 'auth_key', $this->string(32)->notNull());
        $this->alterColumn('users', 'created_at', $this->integer()->notNull());
        $this->alterColumn('users', 'updated_at', $this->integer()->notNull());
        
        // 5. Add indexes
        $this->createIndex('idx_users_status', 'users', 'status');
        $this->createIndex('idx_users_auth_key', 'users', 'auth_key');
        
        // 6. Verify
        $originalCount = $this->db->createCommand("SELECT COUNT(*) FROM {$this->backupTable}")->queryScalar();
        $currentCount = $this->db->createCommand("SELECT COUNT(*) FROM users")->queryScalar();
        if ($originalCount != $currentCount) {
            throw new \Exception("Data integrity check failed!");
        }
        
        echo "Migration completed! Preserved {$currentCount} users\n";
        return true;
    }

    public function safeDown()
    {
        $this->dropIndex('idx_users_auth_key', 'users');
        $this->dropIndex('idx_users_status', 'users');
        $this->dropColumn('users', 'updated_at');
        $this->dropColumn('users', 'created_at');
        $this->dropColumn('users', 'status');
        $this->dropColumn('users', 'verification_token');
        $this->dropColumn('users', 'password_reset_token');
        $this->dropColumn('users', 'auth_key');
        return true;
    }
}
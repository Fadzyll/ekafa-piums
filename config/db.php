<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=ekafa_piums',  // ← Fixed
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',  // ← Fixed
    
    // Enable schema cache for production
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
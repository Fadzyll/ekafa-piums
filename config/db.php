<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=ekafa_db', // Changed database name
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4', // Changed from utf8 to utf8mb4

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%teachers_education}}`.
 */
class m251022_080000_create_teachers_education_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%teachers_education}}', [
            'education_id' => $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'institution_name' => $this->string(255)->null(),
            'degree_level' => "ENUM('Bachelor', 'Master', 'PhD', 'Diploma', 'Certificate') NULL",
            'field_of_study' => $this->string(255)->null(),
            'graduation_date' => $this->date()->null(),
        ]);

        // Create index for user_id
        $this->createIndex(
            'idx-teachers_education-user_id',
            '{{%teachers_education}}',
            'user_id'
        );

        // Create index for degree_level
        $this->createIndex(
            'idx-teachers_education-degree_level',
            '{{%teachers_education}}',
            'degree_level'
        );

        // Add foreign key for user_id
        $this->addForeignKey(
            'fk-teachers_education-user_id',
            '{{%teachers_education}}',
            'user_id',
            '{{%users}}',
            'user_id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key
        $this->dropForeignKey(
            'fk-teachers_education-user_id',
            '{{%teachers_education}}'
        );

        // Drop indexes
        $this->dropIndex(
            'idx-teachers_education-degree_level',
            '{{%teachers_education}}'
        );

        $this->dropIndex(
            'idx-teachers_education-user_id',
            '{{%teachers_education}}'
        );

        // Drop table
        $this->dropTable('{{%teachers_education}}');
    }
}
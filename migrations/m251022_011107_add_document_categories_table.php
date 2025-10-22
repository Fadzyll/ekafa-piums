<?php

use yii\db\Migration;

class m251022_011107_add_document_categories_table extends Migration
{
    public function safeUp()
    {
        // Create document_categories table
        $this->createTable('document_categories', [
            'category_id' => $this->primaryKey(),
            'category_name' => $this->string(100)->notNull()->unique(),
            'description' => $this->text(),
            'required_for_role' => "ENUM('Teacher', 'Parent', 'Both') NOT NULL DEFAULT 'Both'",
            'is_required' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'status' => "ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active'",
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Add category_id to user_documents table
        $this->addColumn('user_documents', 'category_id', $this->integer()->null());
        $this->addColumn('user_documents', 'admin_notes', $this->text()->null());
        
        // Add foreign key
        $this->addForeignKey(
            'fk-user_documents-category_id',
            'user_documents',
            'category_id',
            'document_categories',
            'category_id',
            'SET NULL',
            'CASCADE'
        );

        // Update status enum to include 'Rejected'
        $this->alterColumn('user_documents', 'status', "ENUM('Completed', 'Incomplete', 'Pending Review', 'Rejected') DEFAULT 'Pending Review'");

        // Insert some default categories
        $this->batchInsert('document_categories', ['category_name', 'description', 'required_for_role', 'is_required'], [
            ['Birth Certificate', 'Student birth certificate', 'Parent', 1],
            ['Parent IC Copy', 'Copy of parent identification card', 'Parent', 1],
            ['Teaching Certificate', 'Professional teaching certificate', 'Teacher', 1],
            ['Academic Transcript', 'Latest academic transcript', 'Teacher', 0],
            ['Medical Report', 'Medical fitness report', 'Both', 0],
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user_documents-category_id', 'user_documents');
        $this->dropColumn('user_documents', 'category_id');
        $this->dropColumn('user_documents', 'admin_notes');
        $this->dropTable('document_categories');
        
        // Note: Reverting ENUM changes requires recreating the column
        $this->alterColumn('user_documents', 'status', "ENUM('Completed', 'Incomplete', 'Pending Review') DEFAULT 'Pending Review'");
    }
}
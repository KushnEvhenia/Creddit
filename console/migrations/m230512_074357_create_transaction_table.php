<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m230512_074357_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'id_from' => $this->integer(),
            'id_to' => $this->integer(),
            'amount_from' => $this->float(),
            'amount_to' => $this->float(),
            'id_category' => $this->integer(),
            'id_counterparty' => $this->integer(),
            'file' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'updated_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            'fk-id_from_from',
            'transaction',
            'id_from',
            'account',
            'id', 
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-id_to_to',
            'transaction',
            'id_to',
            'account',
            'id', 
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-id_category_category',
            'transaction',
            'id_category',
            'category',
            'id', 
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-id_counterparty_counterparty',
            'transaction',
            'id_counterparty',
            'counterparty',
            'id', 
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-user_id_user',
            'transaction',
            'user_id',
            'user',
            'id', 
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-updated_id_user',
            'transaction',
            'updated_id',
            'user',
            'id', 
            'CASCADE'
        );
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaction}}');
    }
}

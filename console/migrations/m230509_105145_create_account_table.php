<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account}}`.
 */
class m230509_105145_create_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%account}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'initial_amount' => $this->float(),
            'current_amount' => $this->float(),
            'id_currency' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk-id_currency_currency',
            'account',
            'id_currency',
            'currency',
            'id', 
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%account}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%counterparty}}`.
 */
class m230420_094654_create_counterparty_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%counterparty}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'type' => $this->string()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%counterparty}}');
    }
}

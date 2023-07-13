<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions0
 * @property Transaction[] $transactions1
 */
class User extends \yii\db\ActiveRecord
{

    const SCENARIO_UPDATE = 'update';

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'email', 'created_at', 'updated_at'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['username', 'auth_key', 'email', 'created_at', 'updated_at', 'password_hash'], 'required', 'on' => self::SCENARIO_CREATE],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['password_hash'], 'string', 'min' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('backend', 'Username'),
            'auth_key' => 'Auth Key',
            'password_hash' =>Yii::t('backend','Password'),
            'password_reset_token' => 'Password Reset Token',
            'email' => Yii::t('backend','Email'),
            'status' => 'Status',
            'created_at' => Yii::t('backend','Created At'),
            'updated_at' => Yii::t('backend','Updated At'),
            'verification_token' => 'Verification Token',
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['id_from' => 'id']);
    }

    /**
     * Gets query for [[Transactions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions0()
    {
        return $this->hasMany(Transaction::class, ['id_to' => 'id']);
    }

    /**
     * Gets query for [[Transactions1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions1()
    {
        return $this->hasMany(Transaction::class, ['user_id' => 'id']);
    }

    public function setPassword($password, $id=1)
    {
        if(!empty($password)){
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
        else{
            $this->password_hash = User::findOne($id)->password_hash;
        }
        
    }

    public function getPassword()
    {
        return Yii::$app->request->post()['User']['password_hash'];
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function create_user()
    {
    
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->created_at = time();
        $user->updated_at = time();

        return $user->save();
    }

}

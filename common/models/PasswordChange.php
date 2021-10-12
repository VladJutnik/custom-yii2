<?php

namespace common\models;

use Yii;
use yii\base\Model;

class PasswordChange extends Model
{
    public $password_old;
    public $password_new;
    public $password_repeat;


    public function rules()
    {
        return [
            [['password_old'], 'required'],
            ['password_old', 'trim'],
            ['password_old', 'validatePassword'],

            [['password_new'], 'required'],
            ['password_new', 'trim'],
            ['password_new', 'string', 'length' => [1, 32]],

            [['password_repeat'], 'required'],
            ['password_repeat', 'trim'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password_new', 'message' => 'Пароли не совпадают'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        $passСomparison = Yii::$app->security->validatePassword($this->password_old, Yii::$app->user->identity->password_hash);

        if (!$passСomparison)
        {
            $this->addError($attribute, 'Введён неверный пароль');
        }
    }

    public function attributeLabels()
    {
        return [
            'password_old' => 'Старый пароль',
            'password_new' => 'Новый пароль',
            'password_repeat' => 'Повторите пароль'
        ];
    }
}

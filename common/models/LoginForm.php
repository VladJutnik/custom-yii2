<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            /*'phone' => 'Телефон',
            'email' => 'Email',*/
            //'' => 'Кол-во/шт',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUserByLogin();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль.');
            }
        }
    }

    /*public function validateLogin($attribute, $params)
    {
                $this->addError($attribute, 'Неверный логинfsdfsdfsffь.');
    }*/

    /*public function validatePassword($attribute, $params)
    {
                $this->addError($attribute, 'Неверный логин или1 пароль.');
    }*/

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUserByLogin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    /*protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }*/

    protected function getUserByLogin()
    {
        if ($this->_user === null) {
            $this->_user = User::findByLogin($this->login);
        }

        return $this->_user;
    }
}

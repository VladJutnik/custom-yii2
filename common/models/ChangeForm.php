<?php

namespace common\models;

use Yii;
use yii\base\Model;

class ChangeForm extends Model
{
    public $email;
    private $user_ = false;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'email',
        ];
    }

    public function changePassword(){
        if($this->getUser()){
            $password = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 7);
            $this->user_->setPassword($password);
            $this->user_->generateAuthKey();
            if($this->user_->save()){

                $message = Yii::$app->mailer->compose();
                $message->setFrom(['1@niig.su'=>'Программа']);
                $message->setTo($this->email)
                    ->setSubject('Вы сменили пароль от программы!')
                    ->setHtmlBody('<p><b>Новый пароль: </b>'.$password.'</p>')
                    ->setTextBody('Новый пароль: '.$password);
                $message->send();

                return true;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    public function getUser()
    {
        if ($this->user_ === false) {
            $this->user_ = User::findByEmail($this->email);
        }

        return $this->user_;
    }
}

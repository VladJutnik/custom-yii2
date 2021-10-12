<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\rbac\DbManager;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $type;
    public $email;
    public $password;
    public $login;
    public $post;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        /*return [
            [['federal_district_id', 'region_id', 'municipality', 'type_org', 'post', 'phone', 'title', 'email', 'type_lager_id'], 'required'],
            //['municipality', 'validateEmail'],
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This name has already been taken.'],
            ['name', 'string', 'min' => 8, 'max' => 255],
            ['phone', 'string', 'min' => 10, 'max' => 255],
            ['post', 'string', 'min' => 4, 'max' => 255],
            /*[
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'This username has already been taken'
            ],

            ['email', 'trim'],
            //['email', 'validatePassword'],
            ['email', 'string', 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];*/

        return [
            ['name', 'trim'],
            [['name', 'type', 'email', 'login', 'password'], 'required'],
            //['name', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This name has already been taken.'],
            ['name', 'string', 'min' => 8, 'max' => 255],
            ['phone', 'string', 'min' => 10, 'max' => 255],
            //'post', 'string', 'min' => 4, 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'This username has already been taken'
            ],

            ['email', 'trim'],
            //['email', 'validatePassword'],
            ['email', 'string', 'max' => 255],

            ['password', 'required'],
            ['post', 'safe'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /*public function validateEmail()
    {
        //$email = User::find()->where(['email' => $this->email])->count();
        //$email = 1;
        if($this->email != '123'){
            $this->addError('email', 'Пользователь с таким email уже существует.');
        }
    }*/
   /* public function validateEmail($attribute, $params)
    {


            $this->addError($attribute, 'Неверный логин или пароль.');


    }*/


    public function attributeLabels()
    {
        return [
            'name' => 'ФИО сотрудника',
            'type' => 'Роль в системе',
            'email' => 'Email',
            'password' => 'Пароль',
            'login' => 'Логин для входа',
            'post' => 'Доктор',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
       /* return $user->save() && $this->sendEmail($user)*/;
        if ($user->save()) {
            $r = new DbManager();
            $r->init();
            $assign = $r->createRole('user');
            $r->assign($assign, $user->id);
            return 'ok';
            //return $this->redirect(['view', 'id' => $model->id]);
        }

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}

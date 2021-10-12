<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\rbac\DbManager;
use yii\bootstrap4\ActiveForm;

/**
 * Signup form
 */
class SignupUserForm extends Model
{
    public $name;
    public $post;
    public $email;
    public $number;
    public $letter;
    public $menu_id;
    public $count;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post', 'name', 'email'], 'required'],

            ['name', 'trim'],
            ['name', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This name has already been taken.'],
            ['name', 'string', 'min' => 8, 'max' => 255],

            [['number', 'letter', 'menu_id', 'count'], 'safe'],

            ['email', 'trim'],
            ['email', 'undf'],
            ['email', 'string', 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function undf($attribute)
    {
        //$email = User::find()->where(['email' => $this->email])->count();
        //$email = 1;
        //if($email > 0){
            $this->addError($attribute, 'Пользователь с таким email уже существует');
        //}
    }

    public function attributeLabels()
    {
        return [
            'name' => 'ФИО сотрудника',
            'email' => 'Email',
            'phone' => 'Номер телефона',
            'post' => 'Должность сотрудника',
            'password' => 'Пароль',
            'number' => 'Класс',
            'letter' => 'Буква',
            'menu_id' => 'Меню по которму питаются дети',
            'count' => 'Количество детей в классе',
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

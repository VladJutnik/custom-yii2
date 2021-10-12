<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\rbac\DbManager;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class UserForm extends Model
{
    public $name;
    public $password;
    public $phone;
    public $role;
    public $status;
    public $email;
    public $login;
    public $photo;
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phone'], 'trim'],
            [['phone'], 'string', 'min'=> 10, 'message'=> 'Телефон должен быть 10-ти значным'],
            [['phone'], 'uniquePhone'],
            [['name'], 'string', 'min'=> 3],
            [['name'], 'string', 'max'=> 100],
            [['password'], 'string', 'min'=> 5, 'message'=>'Длинна пароля должна быть не менее 8 символов'],
            [['password'], 'match', 'pattern'=>'/\d/', 'message'=>'Пароль должен содержать цифры от 0-9'],
            [['name', 'email'], 'required'],
            [['email'], 'trim'],
            [['email'], 'email', 'message'=> 'Почта введена не коректно'],
            [['email'], 'filter', 'filter'=>'strtolower'],
            [['email'], 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данная почта уже зарегистрирована.'],
            [['role'], 'safe'],
            [['status'], 'safe'],
            [['login'], 'safe'],
            ['file', 'image',
                'extensions' => ['jpg', 'jpeg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => 1048576, // 500 килобайт = 1024 * 1024 байта = 1 048 576 байт
                'tooBig' => 'Limit is 500 килобайт'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'name' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Почта',
            'role' => 'Роль',
        ];
    }

    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }
        //print_r($this->password);
        //exit;

        $user = new User();
        $user->phone = $this->phone;
        $user->name = $this->name;

        if($this->photo == ''){
            $user->photo = 'image/users/200x200.png';
        }else{
            $user->photo = $this->photo;
        }
        
        $user->created_at = time();
        $user->ugroup = $this->role;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($user->save(false)){
            /*if($user->ugroup == 'user'){

                $link = 'albinamayer.ru/admin';

                $message = Yii::$app->mailer->compose();
                $message->setFrom(['info@albinamayer.ru'=>'#AlbinaMayer']);
                $message->setTo($user->email)
                ->setSubject('Ваша заявка была рассмотрена и одобрена')
                ->setHtmlBody('<p>Личный кабинет: <a href="http://'.$link.'">'.$link.'</a></p><p><b>Логин ваша почта</b></p><p><b>Пароль: </b>'.$this->password.'</p>')
                ->setTextBody('ЛК: '.$link.' Логин ваша почта, Пароль: '.$this->password);

                $message->send();
            }*/

            $r = new DbManager();
            $r->init();
            $assign = $r->createRole($this->role);
            $r->assign($assign, $user['id']);
            return $user;

        }else{
            return null;
        }
    }

    public function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function uniquePhone($attribute, $params){
        $model = User::find()->where('phone="' . trim($this->phone) . '"')->one();
        if(!empty($model)){
            $this->addError($attribute, 'Данный телефон уже зарегистрирован.');
        }
    }

    public function loadOrder($order){
         $this->name = $order->name;
         $this->password = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 9);
         $this->phone = $order->phone;
         $this->role = 'user';
         $this->status = 10;
         $this->email = $order->email;
         $this->ugroup = 'user'; 
    }

    function randomFileName($path, $extension)
    {
        do {
            $name = mt_rand(0, 9999999999);
            $file = $path . $name . '.'. $extension;
        } while (file_exists($file));
        $name2 = $name . '.'. $extension;
        return $name2;
    }
}

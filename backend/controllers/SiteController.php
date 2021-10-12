<?php
namespace backend\controllers;

use app\models\UploadImage;
use common\models\ListPatients;
use common\models\Municipality;
use common\models\Setings;
use Yii;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\ChangeForm;
use common\models\SignupForm;
use common\models\User;
use common\models\Organization;
use common\models\Region;
use yii\rbac\DbManager;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'login', 'error', 'menu', 'signup', 'subjectslist','municipalitylist', 'mail', 'signup-nutrition'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['setings', 'logout', 'index', 'select-organization', 'error', 'subjectslist','municipalitylist', 'orglist', 'personal-account', 'osmotr', 'kol-osmotr', 'report-osm', 'cart', 'calendar', 'kol-calendar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            //return $this->render('signup');
            return $this->render(['report-anket']);
        }else{
            return $this->render('index');
        }
    }
    public function actionIndexCus()
    {
        return $this->render(['index-cus']);

    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = false;

        $model = new LoginForm();
        $change = new ChangeForm();

        if($change->load(Yii::$app->request->post())){
            if($change->changePassword()){
                Yii::$app->session->setFlash('changePassword', 'Дождитесь письма с новым паролем.', false);
                $this->redirect(['/site/login']);
            }
            else{
                Yii::$app->session->setFlash('changeErrorPassword', 'Дождитесь письма с новым паролем.', false);
                $this->redirect(['/site/login']);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
                'change' => $change,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSelectOrganization()
    {

        if (Yii::$app->request->post())
        {
            $organization = Yii::$app->request->post()['SelectOrgForm']['organization'];
            /*print_r($organization);
            exit;*/
            $session = Yii::$app->session;
            //сессия продолжение
            $session['organization_id'] = $organization;
            $organization_id = $session['organization_id'];
        }

        Yii::$app->session->setFlash('success', "Данные организации подгружены");
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMail()
    {
        $message = Yii::$app->mailer->compose();
                $message->setFrom(['info@webozone.ru'=>'#WebZone']);
                $message->setTo('rsbrodov@mail.ru')
                ->setSubject('Тестовое сообщение')
                ->setHtmlBody('<p>Личный кабинет</p>');
                $message->send();
        return 'ok';
        //return $this->goHome();
    }

    //Регистрация для программного средства "Оценка эффективности оздоровления детей"
    public function actionSignup()
    {
        $model = new SignupForm();

        if (Yii::$app->request->post())
        {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $check_email_login = User::find()->where(['like', 'email', Yii::$app->request->post()['SignupForm']['email']])->orWhere(['like', 'login', Yii::$app->request->post()['SignupForm']['email']])->count();
            if ($check_email_login > 0)
            {
                Yii::$app->session->setFlash('error', "Пользователь с указаным email уже существует.");
                return $this->redirect(['signup']);
            }
            /*Если организация роспотреьнадзор*/
            if (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
            {
                $user = new User();
                $organization = Organization::find()->where(['region_id' => Yii::$app->request->post()['SignupForm']['region_id'], 'type_org' => 7])->one();
                if (empty($organization))
                {
                    Yii::$app->session->setFlash('error', "Ошибка регистрации пользователя в программе. Роспотребнадзор по данному региону не зарегистрирован.");
                    return $this->redirect(['signup']);
                }
                $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                $user->name = Yii::$app->request->post()['SignupForm']['name'];
                $user->login = Yii::$app->request->post()['SignupForm']['email'];
                $user->post = Yii::$app->request->post()['SignupForm']['post'];
                $user->created_at = time();
                $user->email = Yii::$app->request->post()['SignupForm']['email'];
                $user->application = 1;//статус новой заявкиж
                $user->status = 9;//неактив
                $user->organization_id = $organization->id;
                $user->parent_id = 0;
                $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                $user->generateAuthKey();

                $role = 'rospotrebnadzor_camp';

                if ($user->save())
                {
                    $r = new DbManager();
                    $r->init();
                    $assign = $r->createRole($role);
                    $r->assign($assign, $user->id);

                    //ОТКЛЮЧИЛИ НА ГЕГЕМОНЕ ОТПРАВКУ ПИСЕМ
                    /*$message = Yii::$app->mailer->compose();
                    $message->setFrom(['help@niig.su' => 'help@niig.su']);
                    $message->setTo($user->email)
                        ->setSubject('Программа оценка эффективности и организации оздоровления детей')
                        ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Пилотный проект оценка эффективности оздоровления детей". Вход в программное средство будет доступен в течении 24 часов.</p>');
                    $message->send();*/
                    Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 24 часов она будет расмотрена и Вы сможете зайти в систему. На Вашу почту были отправлены логин и пароль");
                    return $this->goHome();

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                    return $this->goHome();
                }

            }
            else
            {
                $user = new User();
                $organization = new Organization();

                $organization->federal_district_id = Yii::$app->request->post()['SignupForm']['federal_district_id'];
                $organization->region_id = Yii::$app->request->post()['SignupForm']['region_id'];
                $organization->type_org = Yii::$app->request->post()['SignupForm']['type_org'];
                $organization->municipality_id = Yii::$app->request->post()['SignupForm']['municipality'];
                $organization->title = Yii::$app->request->post()['SignupForm']['title'];
                if(Yii::$app->request->post()['SignupForm']['type_org'] !=1)
                {
                    $organization->type_lager_id = 0;

                }
                else{
                    $organization->type_lager_id = Yii::$app->request->post()['SignupForm']['type_lager_id'];
                }

                if ($organization->save(false))
                {
                    $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                    $user->name = Yii::$app->request->post()['SignupForm']['name'];
                    $user->login = Yii::$app->request->post()['SignupForm']['email'];
                    $user->post = Yii::$app->request->post()['SignupForm']['post'];
                    $user->created_at = time();
                    $user->email = Yii::$app->request->post()['SignupForm']['email'];
                    $user->application = 1;//статус новой заявкиж
                    $user->status = 9;//неактив
                    $user->organization_id = $organization->id;
                    $user->parent_id = 0;
                    $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                    $user->generateAuthKey();

                    if (Yii::$app->request->post()['SignupForm']['type_org'] == 1)
                    {
                        $role = 'camp_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 5)
                    {
                        $role = 'kindergarten_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 3)
                    {
                        $role = 'school_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 2)
                    {
                        $role = 'subject_minobr';
                    }
                    /*elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 5)
                    {
                        $role = 'food_dire';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 6)
                    {
                        $role = 'internat_director';
                    }*/
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
                    {
                        $role = 'rospotrebnadzor_camp';
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', "Произошла ошибка с определением роли. Данные не были сохранены");
                        return $this->redirect(['signup']);
                    }


                    if ($user->save())
                    {
                        $r = new DbManager();
                        $r->init();
                        $assign = $r->createRole($role);
                        $r->assign($assign, $user->id);


                        /*$message = Yii::$app->mailer->compose();
                        $message->setFrom(['help@niig.su' => 'help@niig.su']);
                        $message->setTo($user->email)
                            ->setSubject('Программа оценка эффективности и организации оздоровления детей')
                            ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Пилотный проект оценка эффективности оздоровления детей".  Вход в программное средство будет доступен в течении 24 часов.</p>');
                        $message->send();*/

                    }
                    else
                    {
                        $organization->delete();
                        Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                        return $this->goHome();
                    }

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка при регистрации. Организация и пользователь не были зарегистрированы");
                    return $this->goHome();
                }
                Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 24 часов она будет расмотрена и Вы сможете зайти в систему. На Вашу почту были отправлены логин и пароль");
                return $this->goHome();
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);

    }

    //Регистрация для программного средства "Питание и мониторинг здоровья"
    public function actionSignupNutrition()
    {
        $model = new SignupForm();

        if (Yii::$app->request->post())
        {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $check_email_login = User::find()->where(['like', 'email', Yii::$app->request->post()['SignupForm']['email']])->orWhere(['like', 'login', Yii::$app->request->post()['SignupForm']['email']])->count();
            if ($check_email_login > 0)
            {
                Yii::$app->session->setFlash('error', "Пользователь с указаным email уже существует.");
                return $this->redirect(['signup-nutrition']);
            }
            /*Если организация роспотреьнадзор*/
            if (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
            {
                $user = new User();
                $organization = Organization::find()->where(['region_id' => Yii::$app->request->post()['SignupForm']['region_id'], 'type_org' => 7])->one();
                if (empty($organization))
                {
                    Yii::$app->session->setFlash('error', "Ошибка регистрации пользователя в программе. Роспотребнадзор по данному региону не зарегистрирован.");
                    return $this->redirect(['signup']);
                }
                $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                $user->name = Yii::$app->request->post()['SignupForm']['name'];
                $user->login = Yii::$app->request->post()['SignupForm']['email'];
                $user->post = Yii::$app->request->post()['SignupForm']['post'];
                $user->created_at = time();
                $user->email = Yii::$app->request->post()['SignupForm']['email'];
                $user->application = 1;//статус новой заявкиж
                $user->status = 9;//неактив
                $user->organization_id = $organization->id;
                $user->parent_id = 0;
                $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                $user->generateAuthKey();

                $role = 'rospotrebnadzor_nutrition';

                if ($user->save())
                {
                    $r = new DbManager();
                    $r->init();
                    $assign = $r->createRole($role);
                    $r->assign($assign, $user->id);

                    //ОТКЛЮЧИЛИ НА ГЕГЕМОНЕ ОТПРАВКУ ПИСЕМ
                    /*$message = Yii::$app->mailer->compose();
                    $message->setFrom(['help@niig.su' => 'help@niig.su']);
                    $message->setTo($user->email)
                        ->setSubject('Программа оценка эффективности и организации оздоровления детей')
                        ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Пилотный проект оценка эффективности оздоровления детей". Вход в программное средство будет доступен в течении 24 часов.</p>');
                    $message->send();*/
                    Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 24 часов она будет расмотрена и Вы сможете зайти в систему. На Вашу почту были отправлены логин и пароль");
                    return $this->goHome();

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                    return $this->goHome();
                }

            }
            else
            {
                $user = new User();
                $organization = new Organization();

                $organization->federal_district_id = Yii::$app->request->post()['SignupForm']['federal_district_id'];
                $organization->region_id = Yii::$app->request->post()['SignupForm']['region_id'];
                $organization->type_org = Yii::$app->request->post()['SignupForm']['type_org'];
                $organization->municipality_id = Yii::$app->request->post()['SignupForm']['municipality'];
                $organization->title = Yii::$app->request->post()['SignupForm']['title'];
                if(Yii::$app->request->post()['SignupForm']['type_org'] !=1)
                {
                    $organization->type_lager_id = 0;

                }
                else{
                    $organization->type_lager_id = Yii::$app->request->post()['SignupForm']['type_lager_id'];
                }

                if ($organization->save(false))
                {
                    $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                    $user->name = Yii::$app->request->post()['SignupForm']['name'];
                    $user->login = Yii::$app->request->post()['SignupForm']['email'];
                    $user->post = Yii::$app->request->post()['SignupForm']['post'];
                    $user->created_at = time();
                    $user->email = Yii::$app->request->post()['SignupForm']['email'];
                    $user->application = 1;//статус новой заявкиж
                    $user->status = 9;//неактив
                    $user->organization_id = $organization->id;
                    $user->parent_id = 0;
                    $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                    $user->generateAuthKey();

                    if (Yii::$app->request->post()['SignupForm']['type_org'] == 1)
                    {
                        $role = 'camp_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 5)
                    {
                        $role = 'kindergarten_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 3)
                    {
                        $role = 'school_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 2)
                    {
                        $role = 'subject_minobr';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 4)
                    {
                        $role = 'food_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 6)
                    {
                        $role = 'internat_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
                    {
                        $role = 'rospotrebnadzor_nutrition';
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', "Произошла ошибка с определением роли. Данные не были сохранены");
                        return $this->redirect(['signup-nutrition']);
                    }


                    if ($user->save())
                    {
                        $r = new DbManager();
                        $r->init();
                        $assign = $r->createRole($role);
                        $r->assign($assign, $user->id);


                        /*$message = Yii::$app->mailer->compose();
                        $message->setFrom(['help@niig.su' => 'help@niig.su']);
                        $message->setTo($user->email)
                            ->setSubject('Программа оценка эффективности и организации оздоровления детей')
                            ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Пилотный проект оценка эффективности оздоровления детей".  Вход в программное средство будет доступен в течении 24 часов.</p>');
                        $message->send();*/

                    }
                    else
                    {
                        $organization->delete();
                        Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                        return $this->goHome();
                    }

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка при регистрации. Организация и пользователь не были зарегистрированы");
                    return $this->goHome();
                }
                Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 24 часов она будет расмотрена и Вы сможете зайти в систему. На Вашу почту были отправлены логин и пароль");
                return $this->goHome();
            }
        }
        return $this->render('signup-nutrition', [
            'model' => $model,
        ]);

    }

    /*Подставляет регионы в выпадающий список*/
    public function actionSubjectslist($id){

        $groups = Region::find()->where(['district_id'=>$id])->orderby(['name' => SORT_ASC])->all();

        if($id == 1){

        }
        echo '<option value=" ">Выберите регион...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
    }

    /*Подставляет муниципальные образования в выпадающий список*/
    public function actionMunicipalitylist($id){

        $groups = Municipality::find()->where(['region_id'=>$id])->orderby(['name' => SORT_ASC])->all();

        echo '<option value=" ">Выберите муниципальное образование...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
    }

    /*Подставляет организации в выпадающий список*/
    public function actionOrglist($id){
        //Если Вы организатор питания
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 4){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
            echo '<option value=" ">Выберите образовательную организацию...</option>';
        }
        //Если Вы представитель школы
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 3){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            echo '<option value=" ">Выберите организатора питания...</option>';
        }


        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }


    public function actionPersonalAccount()
    {
        $today = date("d.m.Y");
        //$today = '16.03.2021';

        //Просто запрос без модели
        //Поиск сразу в базе
       /* $connection = \Yii::$app->db; // выполняем запрос
        $command = $connection->createCommand('SELECT organization.municipality_id, organization.type_org,
            user.training_id, user.type_training, COUNT(user.type_training)
            FROM organization
            LEFT JOIN USER ON user.organization_id = organization.id
            WHERE user.training_id = 1 AND organization.region_id = '.$id.'
            GROUP BY user.type_training,organization.municipality_id');
        $arr_s = $command->queryAll();*/

        //Yii::$app->user->can('rospotrebnadzor_nutrition')

        $model = new ListPatients();

        if(Yii::$app->user->identity->post == 'admin'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->all();
        }elseif (Yii::$app->user->identity->post == 'nurse'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->all();
        }elseif (Yii::$app->user->identity->post == 'физио'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->all();
        }elseif (Yii::$app->user->identity->post == 'гиниколог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field10' => '1'])->all();
        }elseif (Yii::$app->user->can('gldoctor')){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->all();
        }elseif (Yii::$app->user->identity->post == 'терапевт'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field1' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'невролог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field2' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'офтальмолог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field3' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'отоларинголог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field4' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'нарколог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field5' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'психиатр'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field6' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'хирург'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field7' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'стоматолог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field8' => '1'])->all();
        }elseif (Yii::$app->user->identity->post == 'дерматовенеролог'){
            $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today])->andWhere(['field9' => '1'])->all();
        }


        return $this->render('personal-account', [
            'list_patients' => $list_patients,
            'today' => $today,
            'model' => $model,
        ]);
    }

    public function actionOsmotr()
    {
        $today = date("d.m.Y");

        //Просто запрос без модели
        //Поиск сразу в базе
       /* $connection = \Yii::$app->db; // выполняем запрос
        $command = $connection->createCommand('SELECT organization.municipality_id, organization.type_org,
            user.training_id, user.type_training, COUNT(user.type_training)
            FROM organization
            LEFT JOIN USER ON user.organization_id = organization.id
            WHERE user.training_id = 1 AND organization.region_id = '.$id.'
            GROUP BY user.type_training,organization.municipality_id');
        $arr_s = $command->queryAll();*/

        //Yii::$app->user->can('rospotrebnadzor_nutrition')
        $s = '';
        $po = '';
        $users_b = '';
        $organ = '';
        $address_overall = 3;
        $model = new ListPatients();

        $organization_null = array('0' => 'Выберите ...');
        $organizations = \common\models\Organization::find()->all();
        $organization_items = ArrayHelper::map($organizations, 'id', 'title');
        $organization_items = ArrayHelper::merge($organization_null, $organization_items);

        $users_null = array('' => 'Выберите ...');
        $users = \common\models\User::find()->where(['!=', 'post', 'admin'])->andWhere(['!=', 'post', 'school'])->all();
        $users_items = ArrayHelper::map($users, 'id', 'name');
        $users_items = ArrayHelper::merge($users_null, $users_items);

        if (Yii::$app->request->post())
        {
            //print_r(Yii::$app->request->post()['ListPatients']['job']);
            $s = Yii::$app->request->post()['ListPatients']['experience2'];
            $po = Yii::$app->request->post()['ListPatients']['experience3'];
            $address_overall = Yii::$app->request->post()['ListPatients']['address_overall'];
            $organ = Yii::$app->request->post()['ListPatients']['organization_id'];
            $users_b = Yii::$app->request->post()['ListPatients']['card_number'];
            $statusww = Yii::$app->request->post()['ListPatients']['job'];

            $s_new = date("Y-m-d", strtotime($s));
            $po_new = date("Y-m-d", strtotime($po));

            $doc = '';
            $list_patients = '';
            $str_fisio = '';
            if (Yii::$app->user->identity->post == 'терапевт'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Therapist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Therapist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Therapist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Therapist::find()->andwhere(['!=', 'contraindications', '2'])->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field1' => '1'])->count();
                }
                $doc = 'Терапевта';
            }
            elseif (Yii::$app->user->identity->post == 'невролог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Neurologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field2' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Neurologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field2' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Neurologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field2' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Neurologist::find()->andwhere(['!=', 'contraindications', '2'])->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field2' => '1'])->count();
                }
                $doc = 'Невролога';
            }
            elseif (Yii::$app->user->identity->post == 'офтальмолог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Oculist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field3' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Oculist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field3' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Oculist::find()
                        ->where(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field3' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Oculist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field3' => '1'])->count();
                }
                $doc = 'Офтальмолога';
            }
            elseif (Yii::$app->user->identity->post == 'отоларинголог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Audiologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field4' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Audiologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field4' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Audiologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field4' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Audiologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field4' => '1'])->count();
                }
                $doc = 'Отоларинголога';
            }
            elseif (Yii::$app->user->identity->post == 'нарколог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Narcology::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field5' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Narcology::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field5' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Narcology::find()
                        ->where(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field5' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Narcology::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field5' => '1'])->count();
                }
                $doc = 'Нарколога';
            }
            elseif (Yii::$app->user->identity->post == 'психиатр'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Psychiatrist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field6' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Psychiatrist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field6' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Psychiatrist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field6' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Psychiatrist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field6' => '1'])->count();
                }
                $doc = 'Психиатра';
            }
            elseif (Yii::$app->user->identity->post == 'хирург'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Surgeon::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field7' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Surgeon::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field7' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Surgeon::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field7' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Surgeon::find()->andwhere(['<>', 'contraindications', '2'])->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field7' => '1'])->count();
                }
                $doc = 'Хирурга';
            }
            elseif (Yii::$app->user->identity->post == 'стоматолог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Dentist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field8' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Dentist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field8' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Dentist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field8' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Dentist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field8' => '1'])->count();
                }
                $doc = 'Стоматолога';
            }
            elseif (Yii::$app->user->identity->post == 'дерматовенеролог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Dermatovenereologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field9' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Dermatovenereologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field9' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Dermatovenereologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field9' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Dermatovenereologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field9' => '1'])->count();
                }
                $doc = 'Дерматовенеролога';
            }
            elseif (Yii::$app->user->identity->post == 'гиниколог'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\Gynecologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\Gynecologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\Gynecologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Gynecologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field10' => '1'])->count();
                }
                $doc = 'Гинеколога';
            }
            elseif (Yii::$app->user->identity->post == 'gldoctor'){
                if ($s != '' && $po != '')
                {
                    $patients = \common\models\ProfessionalPathologist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_conclusion`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`date_conclusion`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients = \common\models\ProfessionalPathologist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_conclusion`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients = \common\models\ProfessionalPathologist::find()
                        ->where(['<=', 'STR_TO_DATE(`date_conclusion`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                else
                {
                    $patients = \common\models\Gynecologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field10' => '1'])->count();
                }
                $doc = 'Профпатолог';
            }
            elseif (Yii::$app->user->identity->post == 'физио'){
                if ($s != '' && $po != '')
                {
                    $Ecgs = \common\models\Ecg::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ecgs)){
                        $ecg_num = 0;
                        foreach ($Ecgs as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры ЭКГ</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Vibrations = \common\models\VibrationSensitivity::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Vibrations)){
                        $ecg_num = 0;
                        foreach ($Vibrations as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Вибрационная чувствительность"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Respiratory = \common\models\RespiratoryFunction::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Respiratory)){
                        $ecg_num = 0;
                        foreach ($Respiratory as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Исследование функции внешнего дыхания"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Electro = \common\models\Electrothermometry::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Electro)){
                        $ecg_num = 0;
                        foreach ($Electro as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Элеткротермометрии"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Roentgen = \common\models\Roentgen::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Roentgen)){
                        $ecg_num = 0;
                        foreach ($Roentgen as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Рентгена"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Audio = \common\models\Audiogram::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Audio)){
                        $ecg_num = 0;
                        foreach ($Audio as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Аудиограмма"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Ultrasound = \common\models\Ultrasound::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ultrasound)){
                        $ecg_num = 0;
                        foreach ($Ultrasound as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "УЗИ"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Psychic = \common\models\PsychicState::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Psychic)){
                        $ecg_num = 0;
                        foreach ($Psychic as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Краткой шкалы оценки психического статуса"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }

                }
                elseif ($s != '')
                {
                    $Ecgs = \common\models\Ecg::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ecgs)){
                        $ecg_num = 0;
                        foreach ($Ecgs as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры ЭКГ</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Vibrations = \common\models\VibrationSensitivity::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Vibrations)){
                        $ecg_num = 0;
                        foreach ($Vibrations as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Вибрационная чувствительность"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Respiratory = \common\models\RespiratoryFunction::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Respiratory)){
                        $ecg_num = 0;
                        foreach ($Respiratory as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Исследование функции внешнего дыхания"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Electro = \common\models\Electrothermometry::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Electro)){
                        $ecg_num = 0;
                        foreach ($Electro as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Элеткротермометрии"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Roentgen = \common\models\Roentgen::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Roentgen)){
                        $ecg_num = 0;
                        foreach ($Roentgen as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Рентгена"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Audio = \common\models\Audiogram::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Audio)){
                        $ecg_num = 0;
                        foreach ($Audio as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Аудиограмма"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Ultrasound = \common\models\Ultrasound::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ultrasound)){
                        $ecg_num = 0;
                        foreach ($Ultrasound as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "УЗИ"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Psychic = \common\models\PsychicState::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Psychic)){
                        $ecg_num = 0;
                        foreach ($Psychic as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Краткой шкалы оценки психического статуса"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                }
                elseif ($po != '')
                {
                    $Ecgs = \common\models\Ecg::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ecgs)){
                        $ecg_num = 0;
                        foreach ($Ecgs as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры ЭКГ</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Vibrations = \common\models\VibrationSensitivity::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Vibrations)){
                        $ecg_num = 0;
                        foreach ($Vibrations as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Вибрационная чувствительность"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }

                    $Respiratory = \common\models\RespiratoryFunction::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Respiratory)){
                        $ecg_num = 0;
                        foreach ($Respiratory as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Исследование функции внешнего дыхания"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Electro = \common\models\Electrothermometry::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Electro)){
                        $ecg_num = 0;
                        foreach ($Electro as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Элеткротермометрии"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Roentgen = \common\models\Roentgen::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Roentgen)){
                        $ecg_num = 0;
                        foreach ($Roentgen as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Рентгена"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Audio = \common\models\Audiogram::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Audio)){
                        $ecg_num = 0;
                        foreach ($Audio as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Аудиограмма"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Ultrasound = \common\models\Ultrasound::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ultrasound)){
                        $ecg_num = 0;
                        foreach ($Ultrasound as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "УЗИ"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Psychic = \common\models\PsychicState::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Psychic)){
                        $ecg_num = 0;
                        foreach ($Psychic as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Краткой шкалы оценки психического статуса"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                }
                else
                {
                    $Ecgs = \common\models\Ecg::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ecgs)){
                        $ecg_num = 0;
                        foreach ($Ecgs as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры ЭКГ</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Vibrations = \common\models\VibrationSensitivity::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Vibrations)){
                        $ecg_num = 0;
                        foreach ($Vibrations as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Вибрационная чувствительность"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Respiratory = \common\models\RespiratoryFunction::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Respiratory)){
                        $ecg_num = 0;
                        foreach ($Respiratory as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Исследование функции внешнего дыхания"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Electro = \common\models\Electrothermometry::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Electro)){
                        $ecg_num = 0;
                        foreach ($Electro as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Элеткротермометрии"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Roentgen = \common\models\Roentgen::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Roentgen)){
                        $ecg_num = 0;
                        foreach ($Roentgen as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Рентгена"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Audio = \common\models\Audiogram::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Audio)){
                        $ecg_num = 0;
                        foreach ($Audio as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Аудиограмма"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Ultrasound = \common\models\Ultrasound::find()
                        ->andwhere(['login'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Ultrasound)){
                        $ecg_num = 0;
                        foreach ($Ultrasound as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "УЗИ"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                    $Psychic = \common\models\PsychicState::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->all();
                    if (!empty($Psychic)){
                        $ecg_num = 0;
                        foreach ($Psychic as $ecg){
                            $ecg_num++;
                        }
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные осмотры "Краткой шкалы оценки психического статуса"</td>
                                <td>'.$ecg_num.'</td>
                            </tr>
                        ';
                    }
                }
                $doc = '';
            }
            elseif (Yii::$app->user->identity->post == 'nurse'){
                if ($s != '' && $po != '')
                {
                    $count = \common\models\BloodTest::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ крови</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\UrineTest::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мочи</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\GeneralBiochemicalAnalysis::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Биохимический анализ</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\FecesEggsWorm::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Кал на яйца глистов</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\DizGroup::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на дизгруппу</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Staphylococcus::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Стафилококк</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Rpg::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа РПГА </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Microflora::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мазка на микрофлору </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Cytology::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на цитологию </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }

                }
                elseif ($s != '')
                {
                    $count = \common\models\BloodTest::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ крови</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\UrineTest::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мочи</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\GeneralBiochemicalAnalysis::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Биохимический анализ</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\FecesEggsWorm::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Кал на яйца глистов</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\DizGroup::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на дизгруппу</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Staphylococcus::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Стафилококк</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Rpg::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа РПГА </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Microflora::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мазка на микрофлору </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Cytology::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на цитологию </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }

                }
                elseif ($po != '')
                {
                    $count = \common\models\BloodTest::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ крови</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\UrineTest::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мочи</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\GeneralBiochemicalAnalysis::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Биохимический анализ</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\FecesEggsWorm::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Кал на яйца глистов</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\DizGroup::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на дизгруппу</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Staphylococcus::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Стафилококк</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Rpg::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа РПГА </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Microflora::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мазка на микрофлору </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Cytology::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на цитологию </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }

                }
                else
                {
                    $count = \common\models\BloodTest::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ крови</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\UrineTest::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мочи</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\GeneralBiochemicalAnalysis::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Биохимический анализ</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\FecesEggsWorm::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Кал на яйца глистов</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\DizGroup::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на дизгруппу</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Staphylococcus::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Стафилококк</td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Rpg::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа РПГА </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Microflora::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ мазка на микрофлору </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }
                    $count = \common\models\Cytology::find()
                        ->andwhere(['login_created'=>Yii::$app->user->identity->id])
                        ->count();
                    if (!empty($count)){
                        $str_fisio .= '
                            <tr>
                                <td>Проведенные анализа Анализ на цитологию </td>
                                <td>'.$count.'</td>
                            </tr>
                        ';
                    }

                }
                $doc = '';
            }
            else{
                $list_patients = '';
                $patients = '';
            }

            return $this->render('osmotr', [
                'today' => $today,
                'model' => $model,
                'organization_item' => $organization_items,
                's' => $s,
                'po' => $po,
                'address_overall' => $address_overall,
                'users_items' => $users_items,
                'users_b' => $users_b,
                'organ' => $organ,
                'list_patients' => $list_patients,
                'patients' => $patients,
                'doc' => $doc,
                'statusww' => $statusww,
                'str_fisio' => $str_fisio,
            ]);
        }



        return $this->render('osmotr', [
            'today' => $today,
            'model' => $model,
            'organization_item' => $organization_items,
            's' => $s,
            'po' => $po,
            'address_overall' => $address_overall,
            'users_items' => $users_items,
            'users_b' => $users_b,
            'organ' => $organ,
        ]);
    }

    public function actionKolOsmotr()
    {
        $today = date("d.m.Y");

        //Просто запрос без модели
        //Поиск сразу в базе
       /* $connection = \Yii::$app->db; // выполняем запрос
        $command = $connection->createCommand('SELECT organization.municipality_id, organization.type_org,
            user.training_id, user.type_training, COUNT(user.type_training)
            FROM organization
            LEFT JOIN USER ON user.organization_id = organization.id
            WHERE user.training_id = 1 AND organization.region_id = '.$id.'
            GROUP BY user.type_training,organization.municipality_id');
        $arr_s = $command->queryAll();*/

        //Yii::$app->user->can('rospotrebnadzor_nutrition')
        $s = '';
        $po = '';
        $address_overall = 3;
        $model = new ListPatients();

        $organization_null = array('0' => 'Выберите ...');
        $organizations = \common\models\Organization::find()->all();
        $organization_items = ArrayHelper::map($organizations, 'id', 'title');
        $organization_items = ArrayHelper::merge($organization_null, $organization_items);
        $status = 0;

        if (Yii::$app->request->post())
        {
            $status = 1;

            $s = Yii::$app->request->post()['ListPatients']['experience2'];
            $po = Yii::$app->request->post()['ListPatients']['experience3'];
            $address_overall = Yii::$app->request->post()['ListPatients']['address_overall'];
            $organ = Yii::$app->request->post()['ListPatients']['organization_id'];
            $users_b = Yii::$app->request->post()['ListPatients']['card_number'];
            $statusww = Yii::$app->request->post()['ListPatients']['job'];

            $s_new = date("Y-m-d", strtotime($s));
            $po_new = date("Y-m-d", strtotime($po));

            $doc = '';
            /* if(Yii::$app->user->can('gldoctor')){
                 print_r(Yii::$app->request->post()['ListPatients']);
             }*/
            if (
                Yii::$app->user->identity->post == 'admin' ||
                Yii::$app->user->identity->post == 'school' ||
                Yii::$app->user->identity->post == 'gldoctor' ||
                Yii::$app->user->identity->post == 'nurse' ||
                Yii::$app->user->identity->post == 'физио'
            ){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])->all();

                $doc = '';
            }
            elseif ($users_b == 'терапевт'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field1' => '1'])->all();

                $doc = 'Терапевта';
            }
            elseif (Yii::$app->user->identity->post == 'невролог'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field2' => '1'])->all();
                $doc = 'Невролога';
            }
            elseif (Yii::$app->user->identity->post == 'офтальмолог'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field3' => '1'])->all();
                $doc = 'Офтальмолога';
            }
            elseif (Yii::$app->user->identity->post == 'отоларинголог'){

                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field4' => '1'])->all();
                $doc = 'Отоларинголога';
            }
            elseif (Yii::$app->user->identity->post == 'нарколог'){

                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field5' => '1'])->all();
                $doc = 'Нарколога';
            }
            elseif (Yii::$app->user->identity->post == 'психиатр'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field6' => '1'])->all();
                $doc = 'Психиатра';
            }
            elseif (Yii::$app->user->identity->post == 'хирург'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field7' => '1'])->all();
                $doc = 'Хирурга';
            }
            elseif (Yii::$app->user->identity->post == 'стоматолог'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field8' => '1'])->all();
                $doc = 'Стоматолога';
            }
            elseif (Yii::$app->user->identity->post == 'дерматовенеролог'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field9' => '1'])->all();
                $doc = 'Дерматовенеролога';
            }
            elseif (Yii::$app->user->identity->post == 'гиниколог'){
                $list_patients = \common\models\DoctorsNeeded::find()
                    ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                    ->andWhere(['field10' => '1'])->all();
                $doc = 'Гинеколога';
            }
            else{
                $list_patients = '';
                $patients = '';
            }

            return $this->render('kol-osmotr', [
                'today' => $today,
                'model' => $model,
                'organization_item' => $organization_items,
                's' => $s,
                'po' => $po,
                'address_overall' => $address_overall,
                'users_b' => $users_b,
                'organ' => $organ,
                'list_patients' => $list_patients,
                'patients' => $patients,
                'doc' => $doc,
                'status' => $status,
            ]);
        }

        return $this->render('kol-osmotr', [
            'today' => $today,
            'model' => $model,
            'organization_item' => $organization_items,
            's' => $s,
            'po' => $po,
            'address_overall' => $address_overall,
            'status' => $status,
        ]);
    }

    public function actionReportOsm()
    {
        $today = date("d.m.Y");

        //Просто запрос без модели
        //Поиск сразу в базе
       /* $connection = \Yii::$app->db; // выполняем запрос
        $command = $connection->createCommand('SELECT organization.municipality_id, organization.type_org,
            user.training_id, user.type_training, COUNT(user.type_training)
            FROM organization
            LEFT JOIN USER ON user.organization_id = organization.id
            WHERE user.training_id = 1 AND organization.region_id = '.$id.'
            GROUP BY user.type_training,organization.municipality_id');
        $arr_s = $command->queryAll();*/

        //Yii::$app->user->can('rospotrebnadzor_nutrition')
        $s = '';
        $po = '';
        $address_overall = 3;
        $model = new ListPatients();

        $organization_null = array('0' => 'Выберите ...');
        $organizations = \common\models\Organization::find()->all();
        $organization_items = ArrayHelper::map($organizations, 'id', 'title');
        $organization_items = ArrayHelper::merge($organization_null, $organization_items);
        $status = 0;

        if (Yii::$app->request->post())
        {
            $status = 1;

            $s = Yii::$app->request->post()['ListPatients']['experience2'];
            $po = Yii::$app->request->post()['ListPatients']['experience3'];
            $address_overall = Yii::$app->request->post()['ListPatients']['address_overall'];
            $organ = Yii::$app->request->post()['ListPatients']['organization_id'];
            $users_b = Yii::$app->request->post()['ListPatients']['card_number'];
            $statusww = Yii::$app->request->post()['ListPatients']['job'];

            $s_new = date("Y-m-d", strtotime($s));
            $po_new = date("Y-m-d", strtotime($po));

            $doc = '';
            /* if(Yii::$app->user->can('gldoctor')){
                 print_r(Yii::$app->request->post()['ListPatients']);
             }*/

            $patients_ter = '';
            $patients_nev = '';
            $patients_oft = '';
            $patients_oto = '';
            $patients_nor = '';
            $patients_ph = '';
            $patients_sur = '';
            $patients_stom = '';
            $patients_der = '';
            $patients_gin = '';

            if ($users_b == 'все'){
                if ($s != '' && $po != '')
                {
                    $patients_ter = \common\models\Therapist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();
                    $patients_nev = \common\models\Neurologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();
                    $patients_oft = \common\models\Oculist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_oto = \common\models\Audiologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_nor = \common\models\Narcology::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_sur = \common\models\Surgeon::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_stom = \common\models\Dentist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_gin = \common\models\Gynecologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_ter = \common\models\Therapist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();
                    $patients_nev = \common\models\Neurologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();
                    $patients_oft = \common\models\Oculist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_oto = \common\models\Audiologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_nor = \common\models\Narcology::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_sur = \common\models\Surgeon::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_stom = \common\models\Dentist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_gin = \common\models\Gynecologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_ter = \common\models\Therapist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();
                    $patients_nev = \common\models\Neurologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();
                    $patients_oft = \common\models\Oculist::find()
                        ->where(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_oto = \common\models\Audiologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_nor = \common\models\Narcology::find()
                        ->where(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_sur = \common\models\Surgeon::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_stom = \common\models\Dentist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_p`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_gin = \common\models\Gynecologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                else
                {
                    $patients_ter = \common\models\Therapist::find()->andwhere(['!=', 'contraindications', '2'])->all();
                    $patients_nev = \common\models\Neurologist::find()->andwhere(['!=', 'contraindications', '2'])->all();
                    $patients_oft = \common\models\Oculist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_oto = \common\models\Audiologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_nor = \common\models\Narcology::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_sur = \common\models\Surgeon::find()->andwhere(['<>', 'contraindications', '2'])->all();
                    $patients_stom = \common\models\Dentist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();
                    $patients_gin = \common\models\Gynecologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field1' => '1'])->count();
                }
                $doc = 'всех специалистов';
                $patients_status = 2;
            }
            elseif ($users_b == 'терапевт'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_ter = \common\models\Therapist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_ter = \common\models\Therapist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_ter = \common\models\Therapist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field1' => '1'])->count();
                }
                else
                {
                    $patients_ter = \common\models\Therapist::find()->andwhere(['!=', 'contraindications', '2'])->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field1' => '1'])->count();
                }
                $doc = 'Терапевта';
            }
            elseif ($users_b == 'невролог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_nev = \common\models\Neurologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field2' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_nev = \common\models\Neurologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field2' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_nev = \common\models\Neurologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['!=', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field2' => '1'])->count();
                }
                else
                {
                    $patients_nev = \common\models\Neurologist::find()->andwhere(['!=', 'contraindications', '2'])->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field2' => '1'])->count();
                }
                $doc = 'Невролога';
            }
            elseif ($users_b == 'офтальмолог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_oft = \common\models\Oculist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field3' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_oft = \common\models\Oculist::find()
                        ->where(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field3' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_oft = \common\models\Oculist::find()
                        ->where(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field3' => '1'])->count();
                }
                else
                {
                    $patients_oft = \common\models\Oculist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field3' => '1'])->count();
                }
                $doc = 'Офтальмолога';
            }
            elseif ($users_b == 'отоларинголог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_oto = \common\models\Audiologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field4' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_oto = \common\models\Audiologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field4' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_oto = \common\models\Audiologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field4' => '1'])->count();
                }
                else
                {
                    $patients_oto = \common\models\Audiologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field4' => '1'])->count();
                }
                $doc = 'Отоларинголога';
            }
            elseif ($users_b == 'нарколог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_nor = \common\models\Narcology::find()
                        ->where(['>=', 'creat_at', $s_new])
                        ->andwhere(['<=', 'creat_at', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field5' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_nor = \common\models\Narcology::find()
                        ->where(['>=', 'creat_at', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field5' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_nor = \common\models\Narcology::find()
                        ->where(['<=', 'creat_at', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field5' => '1'])->count();
                }
                else
                {
                    $patients_nor = \common\models\Narcology::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field5' => '1'])->count();
                }
                $doc = 'Нарколога';
            }
            elseif ($users_b == 'психиатр'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->where(['>=', 'creat_at', $s_new])
                        ->andwhere(['<=', 'creat_at', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field6' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->where(['>=', 'creat_at', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field6' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->where(['<=', 'creat_at', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field6' => '1'])->count();
                }
                else
                {
                    $patients_ph = \common\models\Psychiatrist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field6' => '1'])->count();
                }
                $doc = 'Психиатра';
            }
            elseif ($users_b == 'хирург'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_sur = \common\models\Surgeon::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field7' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_sur = \common\models\Surgeon::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field7' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_sur = \common\models\Surgeon::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field7' => '1'])->count();
                }
                else
                {
                    $patients_sur = \common\models\Surgeon::find()->andwhere(['<>', 'contraindications', '2'])->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field7' => '1'])->count();
                }
                $doc = 'Хирурга';
            }
            elseif ($users_b == 'стоматолог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_stom = \common\models\Dentist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field8' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_stom = \common\models\Dentist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field8' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_stom = \common\models\Dentist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field8' => '1'])->count();
                }
                else
                {
                    $patients_stom = \common\models\Dentist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field8' => '1'])->count();
                }
                $doc = 'Стоматолога';
            }
            elseif ($users_b == 'дерматовенеролог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->where(['>=', 'creat_at', $s_new])
                        ->andwhere(['<=', 'creat_at', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field9' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->where(['>=', 'creat_at', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field9' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->where(['<=', 'creat_at', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field9' => '1'])->count();
                }
                else
                {
                    $patients_der = \common\models\Dermatovenereologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field9' => '1'])->count();
                }
                $doc = 'Дерматовенеролога';
            }
            elseif ($users_b == 'гиниколог'){
                $patients_status = 1;
                if ($s != '' && $po != '')
                {
                    $patients_gin = \common\models\Gynecologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                elseif ($s != '')
                {
                    $patients_gin = \common\models\Gynecologist::find()
                        ->where(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $s_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $s_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                elseif ($po != '')
                {
                    $patients_gin = \common\models\Gynecologist::find()
                        ->where(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $po_new])
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->where(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                        ->andWhere(['field10' => '1'])->count();
                }
                else
                {
                    $patients_gin = \common\models\Gynecologist::find()
                        ->andwhere(['<>', 'contraindications', '2'])
                        ->all();

                    $list_patients = \common\models\DoctorsNeeded::find()
                        ->Where(['field10' => '1'])->count();
                }
                $doc = 'Гинеколога';
            }
            else{
                $list_patients = '';


                $patients_status = 3;

                $doc = '';
            }

            return $this->render('report-osm', [
                'today' => $today,
                'model' => $model,
                'organization_item' => $organization_items,
                's' => $s,
                'po' => $po,
                'address_overall' => $address_overall,
                'users_b' => $users_b,

                'list_patients' => $list_patients,

                'patients_ter' => $patients_ter,
                'patients_nev' => $patients_nev,
                'patients_oft' => $patients_oft,
                'patients_oto' => $patients_oto,
                'patients_nor' => $patients_nor,
                'patients_ph' => $patients_ph,
                'patients_sur' => $patients_sur,
                'patients_stom' => $patients_stom,
                'patients_der' => $patients_der,
                'patients_gin' => $patients_gin,
                'patients_status' => $patients_status,

                'doc' => $doc,
                'status' => $status,
            ]);
        }

        return $this->render('report-osm', [
            'today' => $today,
            'model' => $model,
            'organization_item' => $organization_items,
            's' => $s,
            'po' => $po,
            'address_overall' => $address_overall,
            'status' => $status,
        ]);
    }

    public function actionUpload(){
        $model = new UploadImage();
        if(Yii::$app->request->isPost){
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();
            return $this->goHome();
        }
        return $this->render('upload', ['model' => $model]);
    }

    public function actionSetings(){
        $model_up = \common\models\Setings::find()->one();

        if(empty($model_up)){
            $model = new Setings();
        }
        else{
            $model = $model_up;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['setings', 'model' => $model]);
        }

        return $this->render('setings', [
            'model' => $model,
        ]);
    }

    public function actionCart(){

        return $this->render('cart');
    }


    public function actionCalendar($ym)
    {
        // проверка формата даты + добавляем к году и месяцу первый день
        $timestamp = strtotime($ym . '-01');
        if ($timestamp === false)
        {
            $ym = date('Y-m');
            $timestamp = strtotime($ym . '-01');
        }

        // сегоднящняя дата
        $today = date('Y-m-j', time());

        // для заголовка месяц и год
        $html_title = date('m.Y', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        // Создаем начальную и конечную дату по выборке  prev & next mktime(hour,minute,second,month,day,year)
        $prev = date('Y-m', strtotime('-1 month', $timestamp)); //предыдушийй месяц
        $next = date('Y-m', strtotime('+1 month', $timestamp)); //следующий месяц

        // Количество дней в месяце
        $day_count = date('t', $timestamp);

        //$str = date('w', $timestamp);
        $str = date('N', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
        $str--;
        /*
        print_r('<br><br>');
        print_r('<br><br>');
        //print_r($day_week);
        print_r('<br><br>');
        print_r('<br><br>');
        print_r($str);
        print_r('<br><br>');
        print_r(mktime(0, 0, 0, date('m', $timestamp)));
        print_r('<br><br>');
        print_r(date('Y', $timestamp));
        print_r('<br><br>');*/

        // Create Calendar!!
        $weeks = array();
        $week = '';
        $count_patient_v = 0;

        // $str - количесвто дней пустых в месце перед первым днем !
        $week .= str_repeat('<td></td>', $str);//добавили пустые ячейки перед первым днем месяцы!

        for ($day = 1; $day <= $day_count; $day++, $str++)
        {

            $date = $ym . '-' . $day;

            $today_day = date("d.m.Y", strtotime($date)); //следующий месяц
            // print_r($today_day);
            // print_r('<br>');
            if (Yii::$app->user->identity->post == 'admin')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->count();
            }
            elseif (Yii::$app->user->identity->post == 'nurse')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->count();
            }
            elseif (Yii::$app->user->identity->post == 'физио')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->count();
            }
            elseif (Yii::$app->user->identity->post == 'гиниколог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field10' => '1'])->count();
            }
            elseif (Yii::$app->user->can('gldoctor'))
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->count();
            }
            elseif (Yii::$app->user->identity->post == 'терапевт')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field1' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'невролог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field2' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'офтальмолог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field3' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'отоларинголог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field4' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'нарколог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field5' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'психиатр')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field6' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'хирург')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field7' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'стоматолог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field8' => '1'])->count();
            }
            elseif (Yii::$app->user->identity->post == 'дерматовенеролог')
            {
                $list_patients = \common\models\DoctorsNeeded::find()->where(['dates' => $today_day])->andWhere(['field9' => '1'])->count();
            }
            if ($list_patients == 0)
            {
                $count_patient = '<br> <span style="color: red; font-size: medium">Посещений не было</span>';
            }
            else
            {
                $button = Html::a('Список за день', ['/site/kol-calendar?po='.$today_day], ['data-method' => 'post', 'target' => '_blank', 'class' => 'btn btn-success btn-sm']);
                $count_patient_v = $count_patient_v + $list_patients;
                $count_patient = '<br> Приянто: ' . $list_patients. '<br>' . $button;
            }
            if ($today == $date)
            {
                $week .= '<td class="today text-td">' . $day . $count_patient;
            }
            else
            {
                $week .= '<td class="text-td">' . $day . $count_patient;
            }
            $week .= '</td>';

            // Конец недели ИЛИ конец месяца
            if ($str % 7 == 6 || $day == $day_count)
            {

                if ($day == $day_count)
                {
                    // Добавить пустую ячейку
                    $week .= str_repeat('<td></td>', 6 - ($str % 7));
                }

                $weeks[] = '<tr>' . $week . '</tr>';

                // Prepare for new week
                $week = '';
            }

        }
        return $this->render('calendar', [
            'weeks' => $weeks,
            'prev' => $prev,
            'next' => $next,
            'year' => $year,
            'month' => $month,
            'count_patient_v' => $count_patient_v,
        ]);
    }

    public function actionKolCalendar($po)
    {
        $po_new = date("Y-m-d", strtotime($po));
        $model = new ListPatients();
        $doc = '';

        if (
            Yii::$app->user->identity->post == 'admin' ||
            Yii::$app->user->identity->post == 'school' ||
            Yii::$app->user->identity->post == 'gldoctor' ||
            Yii::$app->user->identity->post == 'nurse' ||
            Yii::$app->user->identity->post == 'физио'
        )
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])->all();

            $doc = '';
        }
        elseif (Yii::$app->user->identity->post == 'терапевт')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field1' => '1'])->all();

            $doc = 'Терапевта';
        }
        elseif (Yii::$app->user->identity->post == 'невролог')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field2' => '1'])->all();
            $doc = 'Невролога';
        }
        elseif (Yii::$app->user->identity->post == 'офтальмолог')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field3' => '1'])->all();
            $doc = 'Офтальмолога';
        }
        elseif (Yii::$app->user->identity->post == 'отоларинголог')
        {

            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field4' => '1'])->all();
            $doc = 'Отоларинголога';
        }
        elseif (Yii::$app->user->identity->post == 'нарколог')
        {

            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field5' => '1'])->all();
            $doc = 'Нарколога';
        }
        elseif (Yii::$app->user->identity->post == 'психиатр')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field6' => '1'])->all();
            $doc = 'Психиатра';
        }
        elseif (Yii::$app->user->identity->post == 'хирург')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field7' => '1'])->all();
            $doc = 'Хирурга';
        }
        elseif (Yii::$app->user->identity->post == 'стоматолог')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field8' => '1'])->all();
            $doc = 'Стоматолога';
        }
        elseif (Yii::$app->user->identity->post == 'дерматовенеролог')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field9' => '1'])->all();
            $doc = 'Дерматовенеролога';
        }
        elseif (Yii::$app->user->identity->post == 'гиниколог')
        {
            $list_patients = \common\models\DoctorsNeeded::find()
                ->where(['>=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andwhere(['<=', 'STR_TO_DATE(`dates`, \'%d.%m.%Y\')', $po_new])
                ->andWhere(['field10' => '1'])->all();
            $doc = 'Гинеколога';
        }
        else
        {
            $list_patients = '';
            $patients = '';
        }

        return $this->render('kol-calendar', [
            'model' => $model,
            'po' => $po,
            'list_patients' => $list_patients,
            'patients' => $patients,
            'doc' => $doc,
        ]);
    }
}

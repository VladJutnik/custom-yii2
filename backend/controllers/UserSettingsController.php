<?php

namespace backend\controllers;

use common\models\PasswordChange;
use common\models\User;
use common\models\UserForm;
use common\models\UserSettings;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserSettingsController implements the CRUD actions for UserSettings model.
 */
class UserSettingsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['profile', 'settings', 'password-change'],
                        'allow' => true,
                        'roles' => ['@'],
                        //'roles' => ['admin', 'admin'],
                        //'roles' => ['@'], все зарегестрированные
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new \Exception('У вас нет доступа к этой странице');
                }
            ],
        ];
    }

    /**
     * Lists all UserSettings models.
     * @return mixed
     */
    public function actionProfile()
    {
        $model = new UserForm();
        $model2 = new UserSettings();
        $user = \common\models\User::findone(Yii::$app->user->identity->id);
        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['UserForm'];
            $user->name = $post['name'];
            if ($_FILES && !empty($_FILES['UserForm']['name']['file'])) {
                //проверка есть ли фото с таким имененми в папке - если есть то удаляем!
                if($user->photo != ''){
                    $model2->deletfile('image_user', $user->photo);
                }
                $path = "image_user/"; //папака в которой лежит файл
                $extension = strtolower(substr(strrchr($_FILES['UserForm']['name']['file'], '.'), 1));//узнали в каком формате файл пришел
                $file_name = Yii::$app->myComponent->randomFileName($path, $extension);// сделали новое имя с проверкой есть ли такое имя в папке
                if(move_uploaded_file($_FILES['UserForm']['tmp_name']['file'], $path.$file_name)){// переместили из временной папки, в которую изначально загрулся файл в новую директорию с новым именем
                    $user->photo = $file_name;
                }
            }
            $user->save(false);
            Yii::$app->session->setFlash('success', "Данные успешно сохранены");
            return $this->redirect(['profile']);
        }

        return $this->render('profile', [
            'model' => $model,
            'user' => $user,
        ]);
    }
    /**
     * Lists all UserSettings models.
     * @return mixed
     */
    public function actionSettings()
    {
        //if ($_POST) {
        //    $id = $_POST['id'];
        //    unset($_POST);
        //    return $this->render('settings', []);
        //} else {
        //    return $this->render('settings', []);
        //}
        /*это для дублирование записи
        $model = new ListPatients();
        $model->attributes = $patient->attributes;
        $model->fio = $patient->fio . ' дубль';
        $model->old_id = $patient->id;
        $model->save();*/

        //проверка на существование записи в настройках
        $model = $this->findModel(Yii::$app->user->identity->id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['settings']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('settings', [
            'model' => $model
        ]);
    }
    /**
     * Lists all UserSettings models.
     * @return mixed
     */
    public function actionPasswordChange()
    {
        $passChange = new PasswordChange();

        if ($passChange->load(Yii::$app->request->post()) && $passChange->validate())
        {
            $user = User::findOne(Yii::$app->user->id);
            $user->setPassword(Yii::$app->request->post()['PasswordChange']['password_new']);
            if ($user->save())
            {
                Yii::$app->session->setFlash('success', "ПАРОЛЬ ИЗМЕНЕН");
                return $this->redirect(['change-password']);
            }
        }

        return $this->render('password-change', [
            'model' => $passChange
        ]);
    }

    /**
     * Finds the UserSettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserSettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSettings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

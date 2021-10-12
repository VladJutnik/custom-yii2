<?php

namespace backend\controllers;

use common\models\BiochemistryCommon;
use common\models\BloodTest;
use common\models\BloodTestCommon;
use common\models\GeneralBiochemicalAnalysis;
use common\models\CoefficientCalculation;
use common\models\ConclusionIndivid;
use common\models\FederalDistrict;
use common\models\GeneralBiochemicalAnalysisCommon;
use common\models\GeneralBiochemicalAnalysisCommon2;
use common\models\ListPatients;
use common\models\Mkb10;
use common\models\Municipality;
use common\models\OrganizationConsolidatedList;
use common\models\OrganizationSearch;
use common\models\Setings;
use common\models\Therapist;
use common\models\UrineTest;
use common\models\UrineTestCommon;
use Mpdf\Mpdf;
use Yii;
use common\models\Organization;
use common\models\Region;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrganizationsController implements the CRUD actions for Organization model.
 */
class OrganizationsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'onoff'],
                        'allow' => true,
                        'roles' => ['admin', 'admin_organizations'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return  $this->redirect(Yii::$app->request->referrer);
                        }
                        //'roles' => ['@'], все зарегестрированные
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['user_organizations'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return  $this->redirect(Yii::$app->request->referrer);
                        }
                    ],
                    [
                        'actions' => ['search', 'search-municipality', 'view-madal'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                    return  $this->redirect(Yii::$app->request->referrer);
                }
            ],
        ];
    }

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $searchModel = new OrganizationSearch();
        $search = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($search);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Organization();
        if ($this->request->isPost) {
            //print_r($this->request->post());
            //exit();
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Данные сохранены");
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Данные успешно изменены");
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create2', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionOnoff($id){
        $model = $this->findModel($id);
        $status = Yii::$app->request->get()['status_veiws'];

        if($status == 1){
            $model->status_veiws = 1;
        }else{
            $model->status_veiws = $status;
        }

        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
    //Аяйкс для подгрузки регионов
    public function actionSearch($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $groups = Region::find()->where(['district_id' => $id])->all();
        $json = array();
        if (!empty($groups))
        {
            $json .= '<option value="">Все</option>';
            foreach ($groups as $key => $group)
            {
                $json .= "<option value='{$group->id}'>{$group->name}</option>";
            }
        }
        else
        {
            $json .= '<option value="">Все</option>';
        }
        return $json;
    }
    //Аяйкс для подгрузки округов
    public function actionSearchMunicipality($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $groups = Municipality::find()->where(['region_id' => $id])->all();
        $json = array();
        if (!empty($groups))
        {
            $json .= '<option value="">Все</option>';
            foreach ($groups as $key => $group)
            {
                $json .= "<option value='{$group->id}'>{$group->name}</option>";
            }
        }
        else
        {
            $json .= '<option value="">Все</option>';
        }
        return $json;
    }
    //моайльное окно
    public function actionViewMadal($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = false;

        $model = $this->findModel($id);

        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['Organization'];
            $status = Yii::$app->request->post()['Organization']['status_print'];

            if ($status == '1')//это если финальная версия акта
            {
                $start_date = date("Y-m-d", strtotime('01.01.2018'));
                $end_date = date("Y-m-d", strtotime('01.01.2118'));
            }
            else{
                $start_date = date("Y-m-d", strtotime(Yii::$app->request->post()['Organization']['start_date_medical_examination']));
                $end_date = date("Y-m-d", strtotime(Yii::$app->request->post()['Organization']['end_date_medical_examination']));
            }

            ini_set('max_execution_time', 3600);
            ini_set('memory_limit', '5092M');
            ini_set("pcre.backtrack_limit", "5000000");

            $model2 = new Organization();
            $organisation = Organization::findOne($id);
            $organisation_patient = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->count();
            $organisation_patient_alls = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->orderBy(['fio' => SORT_ASC])->all();
            $organisation_patient_j = ListPatients::find()->where(['organization_id' => $id, 'sex' => '1'])->andWhere(['print_status' => '0'])->count();



            $modellist = new ListPatients();
            $settings = Setings::find()->one();

            if($organisation->actual_date_issue == ''){
                $today = date("d.m.Y");
            }
            else{
                $today = $organisation->actual_date_issue;
            }
            if($organisation->actual_date_issue == ''){
                $ear = date("Y");
            }
            else{
                $ear = substr($organisation->actual_date_issue, 6, 4);
            }


            //ДООБСЛЕДОВАНИЕ С ГАЛОЧКОЙ У ТЕХ КТО ВСЕ ПРОШЕЛ!
            $additional_examination = 0;

            //Заношу список пациентов в переменнуую и считаю все для таблиц в одном форыче!
            $num = 1;
            $num2123123 = 1;
            $html_patient = '';

            $html_patient_8 = ''; //пункт 8!
            $patient_8 = 0; //пункт 8!
            $patient_8_j = 0; //пункт 8!
            $patient_8_18 = 0; //пункт 8!
            $num_8 = 1;

            $html_patient_9 = ''; //пункт 8!
            $patient_9 = 0; //пункт 9!
            $patient_9_j = 0; //пункт 9!
            $patient_9_18 = 0; //пункт 9!
            $num_9 = 1;

            $str_18 = 0; //для первого пункта  старше 18 лет!

            $patient_p2 = 0; //для второго пункта общее количество!
            $patient_p2_j = 0; //для второго пункта количество женщин!
            $patient_p2_18 = 0; //для второго пункта общее количество страше 18 лет!

            $conclusion_5 = 0; //для 5 го пункта
            $conclusion_5_j = 0; //для 5 го пункта
            $conclusion_5_18 = 0; //для 5 го пункта

            //РАСЧЕТЫ ДЛЯ ПРОЦЕНТОВ!!! ВСЕ ПАЦИЕНТЫ КРОМЕ ТЕХ КТО ВООБЩЕ НЕ ПРИШЕЛ!!!!
            $percent_6 = 0; //для 6 го пункта
            $percent_6_j = 0; //для 6 го пункта

            $paragraph_10_no_v = 0;    //для 10 го пункта 1 пункт таблици 10 (не выявлены противопоказания)
            $paragraph_10_v = 0;    //для 10 го пункта 2 пункт таблици 10 (выявлены противопоказания)
            $paragraph_10_v_22 = 0;    //для 10 го пункта 2 пункт таблици 10 (Численность работников, имеющих постоянные медицинские противопоказания к работе)
            $paragraph_10_v_4 = 0;    //для 10 го пункта 4 пункт таблици 10 (нет заключения!)
            $paragraph_10_v_amb = 0;    //для 10 го пункта 6 пункт таблици 10 (амбулаторное лечение)
            $paragraph_10_v_str = 0;    //для 10 го пункта 7 пункт таблици 10 (амбулаторное лечение)
            $paragraph_10_v_san = 0;    //для 10 го пункта 8 пункт таблици 10 (амбулаторное лечение)
            $paragraph_10_v_dis = 0;    //для 10 го пункта 9 пункт таблици 10 (амбулаторное лечение)

            //пункт 11
            $html_patient_11 = ''; //пункт 11!

            $html_patient_new_10 = ''; //пункт 10! НОВЫЙ У КОТОРЫХ ВЫЯВЛЕНЫ ПРОТИВОПОКАЗАНИЯ
            $patient_new_10 = 1; //пункт 10! НОВЫЙ У КОТОРЫХ ВЫЯВЛЕНЫ ПРОТИВОПОКАЗАНИЯ

            $patient_11 = 0;
            //пункт 12
            $html_patient_12 = ''; //пункт 12!
            $patient_12 = 0;

            //для МКБ !!!!
            $new_arr = [];
            $new_arr_vp = []; //впервые проф


            $new_arr2 = [];
            $new_arr_vp2 = []; //впервые проф

            $html_patient_123123_12 = ''; //lля 12 пункта
            foreach ($organisation_patient_alls as $organisation_patient_all)
            {
                //общее количесвто для 1го пункта
                $age = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                if ($age <= 18)
                {
                    $str_18++;
                }

                //общее количесвто для 2го пункта
                if ($organisation_patient_all->hazard == '3' || $organisation_patient_all->hazard == '4' || $organisation_patient_all->hazard == '5')
                {
                    $patient_p2++;
                    if ($organisation_patient_all->sex == '1')
                    {
                        $patient_p2_j++;
                    }
                    $age2 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                    if ($age2 <= 18)
                    {
                        $patient_p2_18++;
                    }
                }
                if ($status == '1')//это если финальная версия акта
                {
                    //расчет для пунктов 5-6
                    //$conclusion = ConclusionIndivid::find()->where(['user_id'=>$organisation_patient_all->id])->one();
                    $fails = Therapist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
                    $med_ifo11 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();

                }
                else{
                    //расчет для пунктов 5-6
                    //$conclusion = ConclusionIndivid::find()->where(['user_id'=>$organisation_patient_all->id])->one();
                    $fails = Therapist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo2 = \common\models\Neurologist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo3 = \common\models\Audiologist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo4 = \common\models\Oculist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`date_inspection`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo5 = \common\models\Narcology::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo6 = \common\models\Psychiatrist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo7 = \common\models\Gynecologist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo8 = \common\models\Surgeon::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo9 = \common\models\Dermatovenereologist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo10 = \common\models\Dentist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`data_acceptance`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                    $med_ifo11 = \common\models\ProfessionalPathologist::find()
                        ->where(['user_id' => $organisation_patient_all->id])
                        ->andwhere(['>=', 'STR_TO_DATE(`date_conclusion`, \'%d.%m.%Y\')', $start_date])
                        ->andwhere(['<=', 'STR_TO_DATE(`date_conclusion`, \'%d.%m.%Y\')', $end_date])
                        ->orderBy(['id' => SORT_DESC])
                        ->one();
                }

                if (
                    $med_ifo11->contraindications != ''
                )
                {
                    if ($fails->contraindications == '1' || $fails->contraindications == '3' ||
                        $med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' ||
                        $med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' ||
                        $med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' ||
                        $med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' ||
                        $med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' ||
                        $med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' ||
                        $med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' ||
                        $med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' ||
                        $med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' ||
                        $med_ifo11->contraindications == '1' || $med_ifo11->contraindications == '3'
                    )
                    {
                        $conclus = 'Выявлены противопоказания';
                        $conclusion_5++;
                        $percent_6++;
                        if ($organisation_patient_all->sex == '1')
                        {
                            $conclusion_5_j++;
                            $percent_6_j++;
                        }
                        $age3 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                        if ($age3 <= 18)
                        {
                            $conclusion_5_18++;
                        }
                        //lля пункта 7
                        $html_patient .= '
                        <tr>
                            <td align="center" style="width: 30px">' . $num . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 145px">' . $organisation_patient_all->department . '</td>
                            <td style="width: 145px"><i><span style="color: blue;">' . $conclus . '</span></i></td>
                        </tr>';
                        $num++;
                        if ($fails->contraindications == '1' ||
                            $med_ifo2->contraindications == '1' ||
                            $med_ifo4->contraindications == '1' ||
                            $med_ifo3->contraindications == '1' ||
                            $med_ifo6->contraindications == '1' ||
                            $med_ifo5->contraindications == '1' ||
                            $med_ifo7->contraindications == '1' ||
                            $med_ifo8->contraindications == '1' ||
                            $med_ifo9->contraindications == '1' ||
                            $med_ifo10->contraindications == '1' ||
                            $med_ifo11->contraindications == '1'
                        )
                        {
                            $conclus_2 = '(временные)';
                            $paragraph_10_v++;
                        }
                        elseif ($fails->contraindications == '3' ||
                            $med_ifo2->contraindications == '3' ||
                            $med_ifo4->contraindications == '3' ||
                            $med_ifo3->contraindications == '3' ||
                            $med_ifo6->contraindications == '3' ||
                            $med_ifo5->contraindications == '3' ||
                            $med_ifo7->contraindications == '3' ||
                            $med_ifo8->contraindications == '3' ||
                            $med_ifo9->contraindications == '3' ||
                            $med_ifo10->contraindications == '3' ||
                            $med_ifo11->contraindications == '3'
                        )
                        {
                            $conclus_2 = '(постоянные)';
                            $paragraph_10_v_22++;
                        }
                        else
                        {
                            $conclus_2 = '';
                        }


                        $conclus22 = 'Выявлены противопоказания ' . $conclus_2;
                        if($organisation_patient_all->order_type == '1'){
                            $html_patient_new_10 .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $patient_new_10 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td style="width: 350px">' . $conclus22 . '; '
                                . $modellist->translation_bd_down_pril1_print_v2_kind_work2($organisation_patient_all->id)
                                . '</td>
                        </tr>';
                        }
                        else{
                            $html_patient_new_10 .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $patient_new_10 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td style="width: 350px">' . $conclus22 . '; ' . $modellist->translation_bd_down_pril1_print_v2($organisation_patient_all->id) .' '. $modellist->translation_bd_down_pril2_print_v2($organisation_patient_all->id) .' '. $modellist->translation_bd_down_pril3_print_v2($organisation_patient_all->id) . '</td>
                        </tr>';
                        }

                        $patient_new_10++;
                    }
                    elseif(
                        $fails->contraindications == '4' ||
                        $med_ifo2->contraindications == '4' ||
                        $med_ifo4->contraindications == '4' ||
                        $med_ifo3->contraindications == '4' ||
                        $med_ifo6->contraindications == '4' ||
                        $med_ifo5->contraindications == '4' ||
                        $med_ifo7->contraindications == '4' ||
                        $med_ifo8->contraindications == '4' ||
                        $med_ifo9->contraindications == '4' ||
                        $med_ifo10->contraindications == '4' ||
                        $med_ifo11->contraindications == '4'
                    )
                    {
                        $conclus = 'Выявлены противопоказания к работе по приложению 2';
                        $conclusion_5++;
                        $percent_6++;
                        if ($organisation_patient_all->sex == '1')
                        {
                            $conclusion_5_j++;
                            $percent_6_j++;
                        }
                        $age3 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                        if ($age3 <= 18)
                        {
                            $conclusion_5_18++;
                        }
                        if (empty($organisation_patient_all->department))
                        {
                            $department = 'н/д';
                        }
                        else
                        {
                            $department = $organisation_patient_all->department;
                        }
                        //lля пункта 7
                        $html_patient .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $num . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 145px">' . $department . '</td>
                            <td style="width: 145px"><i><span style="color: blue;">' . $conclus . '</span></i></td>
                        </tr>';
                        $num++;
                        $paragraph_10_no_v++;

                    }
                    elseif(
                        $fails->contraindications == '0' ||
                        $med_ifo2->contraindications == '0' ||
                        $med_ifo4->contraindications == '0' ||
                        $med_ifo3->contraindications == '0' ||
                        $med_ifo6->contraindications == '0' ||
                        $med_ifo5->contraindications == '0' ||
                        $med_ifo7->contraindications == '0' ||
                        $med_ifo8->contraindications == '0' ||
                        $med_ifo9->contraindications == '0' ||
                        $med_ifo10->contraindications == '0' ||
                        $med_ifo11->contraindications == '0'
                    )
                    {
                        $conclus = 'Не выявлены';
                        $conclusion_5++;
                        $percent_6++;
                        if ($organisation_patient_all->sex == '1')
                        {
                            $conclusion_5_j++;
                            $percent_6_j++;
                        }
                        $age3 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                        if ($age3 <= 18)
                        {
                            $conclusion_5_18++;
                        }
                        if (empty($organisation_patient_all->department))
                        {
                            $department = 'н/д';
                        }
                        else
                        {
                            $department = $organisation_patient_all->department;
                        }
                        //lля пункта 7
                        $html_patient .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $num . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 145px">' . $department . '</td>
                            <td style="width: 145px">' . $conclus . '</td>
                        </tr>';
                        $num++;
                        $paragraph_10_no_v++;

                    }



                    if(
                        $fails->additional_examination == '1' ||
                        $med_ifo2->additional_examination == '1' ||
                        $med_ifo4->additional_examination == '1' ||
                        $med_ifo3->additional_examination == '1' ||
                        $med_ifo6->additional_examination == '1' ||
                        $med_ifo5->additional_examination == '1' ||
                        $med_ifo7->additional_examination == '1' ||
                        $med_ifo8->additional_examination == '1' ||
                        $med_ifo9->additional_examination == '1' ||
                        $med_ifo10->additional_examination == '1'
                    ){
                        $additional_examination++;
                    }
                }
                else
                {
                    if (
                        // проверить дату
                        $fails->contraindications != '' ||

                        $med_ifo2->contraindications != '' ||
                        $med_ifo4->contraindications != '' ||
                        $med_ifo3->contraindications != '' ||
                        $med_ifo6->contraindications != '' ||
                        $med_ifo5->contraindications != '' ||
                        $med_ifo7->contraindications != '' ||
                        $med_ifo8->contraindications != '' ||
                        $med_ifo9->contraindications != '' ||
                        $med_ifo10->contraindications != '' ||
                        $med_ifo11->contraindications != '' ||
                        ($start_date < date("Y-m-d", strtotime($organisation_patient_all->data_p)) &&
                            $end_date > date("Y-m-d", strtotime($organisation_patient_all->data_p)))||
                        ($status == '1' && $organisation_patient_all->status == '1')//это если финальная версия акта
                    )
                    {
                        $conclus = '';
                        $percent_6++;
                        //расчеты для пункта 8
                        if ($organisation_patient_all->sex == '1')
                        {
                            $patient_8_j++;
                            $percent_6_j++;
                        }
                        $age4 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                        if ($age4 <= 18)
                        {
                            $patient_8_18++;
                        }

                        $html_patient_8 .= '
                        <tr>
                            <td style="width: 25px">' . $num_8 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        </tr>
                    ';
                        $patient_8++;
                        $num_8++;
                        $paragraph_10_v_4++;
                    }
                    else
                    {
                        $patient_9++;
                        if ($organisation_patient_all->sex == '1')
                        {
                            $patient_9_j++;
                        }
                        $age5 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                        if ($age5 <= 18)
                        {
                            $patient_9_18++;
                        }
                        $html_patient_9 .= '
                        <tr>
                            <td style="width: 25px">' . $num_9 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        </tr>
                    ';
                        $num_9++;
                    }

                }


                //Это для МКБ!!!!

                $fild_arr12 = [
                    'mkb1',
                    'mkb2',
                    'mkb3',
                ];

                $prof = [
                    'prof_diagnosis_1',
                    'prof_diagnosis_2',
                    'prof_diagnosis_3',
                ];

                //МКБ Терапевт //для пункта 13
                if (!empty($fails))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($fails->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($fails->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($fails->diagnosis_primary_field == '1')
                            {
                                if ($fails->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }

                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo2))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo2->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo4))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo4->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo4->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo4->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo4->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo3))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo3->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo3->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo3->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo6))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo6->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo6->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo6->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo5))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo5->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo5->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo5->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo7))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo7->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo7->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo7->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo8))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo8->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo8->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo8->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo9))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo9->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo9->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo9->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }
                if (!empty($med_ifo10))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {

                        $prof_fac = $prof[$i];
                        $name_factor = $fild_arr12[$i];
                        if (!empty($med_ifo10->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo10->diagnosis_primary_field == '1')
                            {
                                if ($med_ifo10->$prof_fac == '0')
                                {
                                    $new_arr_vp[] = $str2;
                                }
                                $new_arr[] = $str2;

                            }
                        }
                    }
                }

                //расчет для 12-го пункта
                if (
                    ($fails->diagnosis_primary_field == '1' && ($fails->prof_diagnosis_1 == '0' || $fails->prof_diagnosis_2 == '0' || $fails->prof_diagnosis_3 == '0')) ||
                    ($med_ifo2->diagnosis_primary_field == '1' && ($med_ifo2->prof_diagnosis_1 == '0' || $med_ifo2->prof_diagnosis_2 == '0' || $med_ifo2->prof_diagnosis_3 == '0')) ||
                    ($med_ifo4->diagnosis_primary_field == '1' && ($med_ifo4->prof_diagnosis_1 == '0' || $med_ifo4->prof_diagnosis_2 == '0' || $med_ifo4->prof_diagnosis_3 == '0')) ||
                    ($med_ifo3->diagnosis_primary_field == '1' && ($med_ifo3->prof_diagnosis_1 == '0' || $med_ifo3->prof_diagnosis_2 == '0' || $med_ifo3->prof_diagnosis_3 == '0')) ||
                    ($med_ifo6->diagnosis_primary_field == '1' && ($med_ifo6->prof_diagnosis_1 == '0' || $med_ifo6->prof_diagnosis_2 == '0' || $med_ifo6->prof_diagnosis_3 == '0')) ||
                    ($med_ifo5->diagnosis_primary_field == '1' && ($med_ifo5->prof_diagnosis_1 == '0' || $med_ifo5->prof_diagnosis_2 == '0' || $med_ifo5->prof_diagnosis_3 == '0')) ||
                    ($med_ifo7->diagnosis_primary_field == '1' && ($med_ifo7->prof_diagnosis_1 == '0' || $med_ifo7->prof_diagnosis_2 == '0' || $med_ifo7->prof_diagnosis_3 == '0')) ||
                    ($med_ifo8->diagnosis_primary_field == '1' && ($med_ifo8->prof_diagnosis_1 == '0' || $med_ifo8->prof_diagnosis_2 == '0' || $med_ifo8->prof_diagnosis_3 == '0')) ||
                    ($med_ifo9->diagnosis_primary_field == '1' && ($med_ifo9->prof_diagnosis_1 == '0' || $med_ifo9->prof_diagnosis_2 == '0' || $med_ifo9->prof_diagnosis_3 == '0')) ||
                    ($med_ifo10->diagnosis_primary_field == '1' && ($med_ifo10->prof_diagnosis_1 == '0' || $med_ifo10->prof_diagnosis_2 == '0' || $med_ifo10->prof_diagnosis_3 == '0'))
                )
                {
                    $html_patient_123123_12 .= '
                        <tr>
                            <td align="center" >' . $num2123123 . '</td>
                            <td >' . $organisation_patient_all->fio . '</td>
                            <td align="center">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td >' . $organisation_patient_all->date_birth . '</td>
                            <td >' . $organisation_patient_all->department . '</td>
                            <td >' . $organisation_patient_all->post_profession . '</td>
                            <td >' . $modellist->translation_bd_down_pril1_print_v3($organisation_patient_all->id) . $modellist->translation_bd_down_pril2_print_v3($organisation_patient_all->id) . '</td>
                        </tr>';
                    $num2123123++;

                }


                //расчет для 11 пункта
                if ($fails->prof_diagnosis_1 == '0' ||
                    $fails->prof_diagnosis_2 == '0' ||
                    $fails->prof_diagnosis_3 == '0' ||
                    $fails->prof_diagnosis_rep_1 == '0' ||
                    $fails->prof_diagnosis_rep_2 == '0' ||
                    $fails->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo2->prof_diagnosis_1 == '0' ||
                    $med_ifo2->prof_diagnosis_2 == '0' ||
                    $med_ifo2->prof_diagnosis_3 == '0' ||
                    $med_ifo2->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo2->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo2->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo4->prof_diagnosis_1 == '0' ||
                    $med_ifo4->prof_diagnosis_2 == '0' ||
                    $med_ifo4->prof_diagnosis_3 == '0' ||
                    $med_ifo4->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo4->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo4->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo3->prof_diagnosis_1 == '0' ||
                    $med_ifo3->prof_diagnosis_2 == '0' ||
                    $med_ifo3->prof_diagnosis_3 == '0' ||
                    $med_ifo3->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo3->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo3->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo6->prof_diagnosis_1 == '0' ||
                    $med_ifo6->prof_diagnosis_2 == '0' ||
                    $med_ifo6->prof_diagnosis_3 == '0' ||
                    $med_ifo6->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo6->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo6->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo5->prof_diagnosis_1 == '0' ||
                    $med_ifo5->prof_diagnosis_2 == '0' ||
                    $med_ifo5->prof_diagnosis_3 == '0' ||
                    $med_ifo5->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo5->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo5->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo7->prof_diagnosis_1 == '0' ||
                    $med_ifo7->prof_diagnosis_2 == '0' ||
                    $med_ifo7->prof_diagnosis_3 == '0' ||
                    $med_ifo7->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo7->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo7->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo8->prof_diagnosis_1 == '0' ||
                    $med_ifo8->prof_diagnosis_2 == '0' ||
                    $med_ifo8->prof_diagnosis_3 == '0' ||
                    $med_ifo8->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo8->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo8->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo9->prof_diagnosis_1 == '0' ||
                    $med_ifo9->prof_diagnosis_2 == '0' ||
                    $med_ifo9->prof_diagnosis_3 == '0' ||
                    $med_ifo9->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo9->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo9->prof_diagnosis_rep_3 == '0' ||
                    $med_ifo10->prof_diagnosis_1 == '0' ||
                    $med_ifo10->prof_diagnosis_2 == '0' ||
                    $med_ifo10->prof_diagnosis_3 == '0' ||
                    $med_ifo10->prof_diagnosis_rep_1 == '0' ||
                    $med_ifo10->prof_diagnosis_rep_2 == '0' ||
                    $med_ifo10->prof_diagnosis_rep_3 == '0'
                )
                {
                    if ($fails->therapist_zoda_3 == '0')
                    {
                        $srrrt = 'Гр. риска - Заболевания органов дыхания';
                    }
                    elseif ($fails->therapist_pi_2 == '0')
                    {
                        $srrrt = 'Гр. риска - Проф. интоксикации';
                    }
                    elseif ($med_ifo2->neurologist6 == '0')
                    {
                        $srrrt = 'Гр. риска - Заболевания органов дыхания';
                    }
                    elseif ($med_ifo3->audiologist6 == '0')
                    {
                        $srrrt = 'Гр. риска - Нейросенсорной тугоухости';
                    }
                    else
                    {
                        $srrrt = '';
                    }

                    $patient_11++;
                    $html_patient_11 .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $patient_11 . '</td>
                        <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                        <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                    </tr>
                '; //пункт 11!
                }
                //расчет для 12 пункта

                if
                (
                    $fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0' ||
                    $med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0' ||
                    $med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0' ||
                    $med_ifo6->psychiatrist5 == '0' ||
                    $med_ifo5->narcology5 == '0' ||
                    $med_ifo7->gynecologist5 == '0' ||
                    $med_ifo8->surgeon5 == '0' ||
                    $med_ifo9->dermatovenereologist5 == '0' ||
                    $med_ifo10->dentist5 == '0'
                )
                {
                    if ($fails->therapist_zoda_3 == '0')
                    {
                        $srrrt = 'Гр. риска - Заболевания органов дыхания';
                    }
                    elseif ($fails->therapist_pi_2 == '0')
                    {
                        $srrrt = 'Гр. риска - Проф. интоксикации';
                    }
                    elseif ($med_ifo2->neurologist6 == '0')
                    {
                        $srrrt = 'Гр. риска - Заболевания органов дыхания';
                    }
                    elseif ($med_ifo3->audiologist6 == '0')
                    {
                        $srrrt = 'Гр. риска - Нейросенсорной тугоухости';
                    }
                    else
                    {
                        $srrrt = '';
                    }


                    //Это для МКБ!!!!

                    $fild_arr12 = [
                        'mkb_repeated1',
                        'mkb_repeated2',
                        'mkb_repeated3',
                    ];

                    $prof = [
                        'prof_diagnosis_1',
                        'prof_diagnosis_2',
                        'prof_diagnosis_3',
                    ];

                    //МКБ Терапевт //для пункта 14
                    if ($fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($fails->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($fails->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($fails->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo2->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo2->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    /*if (!empty($med_ifo4))
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo2->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo2->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }*/
                    if ($med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo3->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo3->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo6->psychiatrist5 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo6->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo6->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo5->narcology5 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo5->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo5->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo7->gynecologist5 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo7->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo7->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo8->surgeon5 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo8->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo8->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo9->dermatovenereologist5 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo9->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo9->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }
                    if ($med_ifo10->dentist5 == '0')
                    {
                        for ($i = 0; $i <= count($fild_arr12); $i++)
                        {
                            $name_factor = $fild_arr12[$i];
                            $prof_fac = $prof[$i];
                            if (!empty($med_ifo10->$name_factor))
                            {
                                $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                                $str = $factor_svs->diagnosis_code;
                                $str2 = strstr($str, '-', true);
                                if ($med_ifo10->diagnosis_repeated_field == '1')
                                {
                                    $new_arr_vp2[] = $str2;
                                    $new_arr2[] = $str2;
                                }
                            }
                        }
                    }

                    $new_arr2 = array_unique($new_arr2);
                    $research_id_222 = array_values($new_arr2); //обнуляю ключи
                    $html_patient_13_new33333 = '';
                    for ($k = 0; $k < count($research_id_222); $k++)
                    {
                        $html_patient_13_new33333 .= $research_id_222[$k] . '; ';
                    }

                    $patient_12++;
                    $html_patient_12 .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $patient_12 . '</td>
                        <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                        <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                        <td style="width: 65px">' . $html_patient_13_new33333 . '</td>
                    </tr>
                '; //пункт 11!
                }

                //расчеты для пункта 10! из осмотров врачеЙ!!!!!!

                if ($fails->therapist_r1 == '0' || $fails->therapist_pi_1 == '0' || $fails->therapist_zoda_1 == '0' ||
                    $med_ifo2->neurologist1 == '0' || $med_ifo2->neurologist2 == '0' ||
                    $med_ifo3->audiologist1 == '0' || $med_ifo3->audiologist2 == '0' ||
                    $med_ifo6->psychiatrist1 == '0' ||
                    $med_ifo5->narcology1 == '0' ||
                    $med_ifo7->gynecologist1 == '0' ||
                    $med_ifo8->surgeon1 == '0' ||
                    $med_ifo9->dermatovenereologist1 == '0' ||
                    $med_ifo10->dentist1 == '0'
                )
                {
                    $paragraph_10_v_amb++;
                }
                if ($fails->therapist_r3 == '0' || $fails->therapist_pi_2 == '0' || $fails->therapist_zoda_2 == '0' ||
                    $med_ifo2->neurologist3 == '0' || $med_ifo2->neurologist4 == '0' ||
                    $med_ifo3->audiologist3 == '0' || $med_ifo3->audiologist4 == '0' ||
                    $med_ifo6->psychiatrist3 == '0' ||
                    $med_ifo5->narcology3 == '0' ||
                    $med_ifo7->gynecologist3 == '0' ||
                    $med_ifo8->surgeon3 == '0' ||
                    $med_ifo9->dermatovenereologist3 == '0' ||
                    $med_ifo10->dentist3 == '0'
                )
                {
                    $paragraph_10_v_str++;
                }
                if (
                    $fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0' ||
                    $med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0' ||
                    $med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0' ||
                    $med_ifo6->psychiatrist5 == '0' ||
                    $med_ifo5->narcology5 == '0' ||
                    $med_ifo7->gynecologist5 == '0' ||
                    $med_ifo8->surgeon5 == '0' ||
                    $med_ifo9->dermatovenereologist5 == '0' ||
                    $med_ifo10->dentist5 == '0'
                )
                {
                    $paragraph_10_v_san++;
                }
                if ($fails->therapist_r7 == '0' || $fails->therapist_pi_4 == '0' || $fails->therapist_zoda_4 == '0' ||
                    $med_ifo2->neurologist7 == '0' || $med_ifo2->neurologist8 == '0' ||
                    $med_ifo3->audiologist7 == '0' || $med_ifo3->audiologist8 == '0' ||
                    $med_ifo6->psychiatrist7 == '0' ||
                    $med_ifo5->narcology7 == '0' ||
                    $med_ifo7->gynecologist7 == '0' ||
                    $med_ifo8->surgeon7 == '0' ||
                    $med_ifo9->dermatovenereologist7 == '0' ||
                    $med_ifo10->dentist7 == '0'
                )
                {
                    $paragraph_10_v_dis++;
                }
            }
            //для первого пункта  старше 18 лет!
            if ($str_18 == 0)
            {
                $str_18 = 'нет';
            }
            //для пункта 4
            if ($patient_p2 == 0)
            {
                $patient_p2 = '-';
            }
            if ($patient_p2_j == 0)
            {
                $patient_p2_j = '-';
            }
            if ($patient_p2_18 == 0)
            {
                $patient_p2_18 = '-';
            }
            //для пункта 5
            if ($conclusion_5_18 == 0)
            {
                $conclusion_5_18 = 'нет';
            }


            //для пункта 8
            if ($patient_8 == 0)
            {
                $patient_8 = 'нет';
            }
            if ($patient_8_j == 0)
            {
                $patient_8_j = 'нет';
            }
            if ($patient_8_18 == 0)
            {
                $patient_8_18 = 'нет';
            }
            //для пункта 9
            if ($patient_9 == 0)
            {
                $patient_9 = 'нет';
            }
            if ($patient_9_j == 0)
            {
                $patient_9_j = 'нет';
            }
            if ($patient_9_18 == 0)
            {
                $patient_9_18 = 'нет';
            }

            //процент для 6 пункта
            if (!empty($conclusion_5))
            {
                $paragraph_6 = (100 * $percent_6) / $organisation_patient;
            }
            else
            {
                $paragraph_6 = 0;
            }
            if (!empty($conclusion_5_j))
            {
                $paragraph_6_18 = (100 * $percent_6_j) / $organisation_patient_j;
            }
            else
            {
                $paragraph_6_18 = 0;
            }


            $html_patient_12_new = '';
            $new_arr = array_filter($new_arr);
            $arr2222r = array_count_values($new_arr); //считаем количество одинаковых обследований
            $new_arr = array_unique($new_arr);
            $research_id_2 = array_values($new_arr); //обнуляю ключи
            $nume = 1;

            $html_patient_13_new = '';
            for ($k = 0; $k < count($research_id_2); $k++)
            {
                $html_patient_13_new .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $nume . '</td>
                        <td style="width: 250px">' . $research_id_2[$k] . '</td>
                        <td align="center" style="width: 350px">' . $arr2222r[$research_id_2[$k]] . '</td>
                    </tr>
                '; //пункт 11!
                $nume++;
            }
            $arr2222r_vp = array_count_values($new_arr_vp); //считаем количество одинаковых обследований
            $new_arr_vp = array_unique($new_arr_vp);
            $research_id_2_vp = array_values($new_arr_vp); //обнуляю ключи
            $nume_vp = 1;

            $html_patient_13_new_vp = '';
            for ($k = 0; $k < count($research_id_2_vp); $k++)
            {
                $html_patient_13_new_vp .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $nume_vp . '</td>
                        <td style="width: 250px">' . $research_id_2_vp[$k] . '</td>
                        <td align="center" style="width: 350px">' . $arr2222r_vp[$research_id_2_vp[$k]] . '</td>
                    </tr>
                '; //пункт 11!
                $nume_vp++;
            }


            //ПУНКТ 1 ЫЙ ИЗ ТАБЛИЦЫ ОРГАНИЗАЦИЯ
            if ($organisation->number_employees != '')
            {
                $paragraph_1_1_new = $organisation->number_employees; //численность работников
            }
            else
            {
                $paragraph_1_1_new = '-';
            }

            if ($organisation->number_employees_j != '')
            {
                $paragraph_1_2_new = $organisation->number_employees_j; //численность работников женщин
            }
            else
            {
                $paragraph_1_2_new = '-';
            }

            if ($organisation->hard_work != '')
            {
                $paragraph_2_1_new = $organisation->hard_work; //	тяжелые работники
            }
            else
            {
                $paragraph_2_1_new = '-';
            }

            if ($organisation->hard_work_j != '')
            {
                $paragraph_2_2_new = $organisation->hard_work_j; //	тяжелые работники женщины
            }
            else
            {
                $paragraph_2_2_new = '-';
            }

            if ($organisation->mandatory_periodic_inspection != '')
            {
                $paragraph_3_1_new = $organisation->mandatory_periodic_inspection; //	обезательный переодический осмотр
            }
            else
            {
                $paragraph_3_1_new = '-';
            }

            if ($organisation->mandatory_periodic_inspection_j != '')
            {
                $paragraph_3_2_new = $organisation->mandatory_periodic_inspection_j; //обезательный переодический осмотр женщины
            }
            else
            {
                $paragraph_3_2_new = '-';
            }
            if ($organisation->field_2222 != '')
            {
                $field_2222 = $organisation->field_2222; //обезательный переодический осмотр женщины
            }
            else
            {
                $field_2222 = '-';
            }

            if ($organisation->field_3333 != '')
            {
                $field_3333 = $organisation->field_3333; //обезательный переодический осмотр женщины
            }
            else
            {
                $field_3333 = '-';
            }

            if ($organisation->field_4444 != '')
            {
                $field_4444 = $organisation->field_4444; //обезательный переодический осмотр женщины
            }
            else
            {
                $field_4444 = '-';
            }

            if ($organisation->field_nov_tr != '')
            {
                $field_nov_tr = $organisation->field_nov_tr; //обезательный переодический осмотр женщины
            }
            else
            {
                $field_nov_tr = '-';
            }

            $paragraph_10_v_42 = $paragraph_10_v_4 + $additional_examination;
            //print_r($html_patient_123123_12);
            //exit();


            if (!empty($settings))
            {
                $settings_name = $settings->name;
                $settings_licenses = $settings->licenses;
                $settings_address = $settings->address;
                $settings_ogrn_code = $settings->ogrn_code;
                $settings_short_name = $settings->short_name;
            }
            else
            {
                $settings_name = '';
                $settings_licenses = '';
                $settings_address = '';
                $settings_ogrn_code = '0';
                $settings_short_name = '';
            }

            if ($organisation->order_type == '1'){
                //для приказа 29н
                $html = '
             <!--<hr align="right" style="width: -1px">-->
            <br>
            <br>
            <table style="margin-top: -80px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="center"  style="width: 380px;" >'.$settings_name.'<br>
                    
                    '.$settings_address.'<br>
                    
                    '.$settings_licenses.'  
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                </tr>
            </table>';
                $html .= '   
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px;">Код ОГРН</td>';
                for ($i = 0; $i < strlen($settings_ogrn_code); $i++)
                {
                    $html .= '<td style=" border: 1px solid #000000; padding: 5px;">' . $settings_ogrn_code[$i] . '</td>';
                }
                $html .= '
            </tr>
            </table>
            <div style="margin-top: 15px; font-size: 14px; margin-right: -30px;" align="center"><b>ЗАКЛЮЧИТЕЛЬНЫЙ АКТ<br>ПО РЕЗУЛЬТАТАМ ПЕРИОДИЧЕСКОГО МЕДИЦИНСКОГО ОСМОТРА<br>(ОБСЛЕДОВАНИЯ)</b></div>
            <div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от ' . $today . ' г.</i></span></b></div>
            <!--<div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от 19.11.2020 г.</i></span></b></div>-->
           
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">По результатам проведенного периодического медицинского осмотра (обследования) работников: <span style="color: blue;"><i>' . $organisation->title . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">за <span style="color: blue;"><i>' . $ear . ' г.</i></span> составлен заключительный акт при участии:</div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Председатель <br>врачебной комиссии <span style="color: blue;"><i>' . $organisation->VK_chairman . ' ' . $organisation->VK_chairman_position . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Представитель <br>работодателя  <span style="color: blue;"><i>' . $organisation->fio_position_commissioner . ', ' . $organisation->position_commissioner . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">1. Общая численность работников организации (предприятия), цеха: </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_1_1_new . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_1_2_new . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_2222 . '</i></span></td>
            </tr>
            </table>
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">2. Численность работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_2_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_2_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_3333 . '</i></span></td>
            </tr>
            </table>    
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">3. Численность работников, занятых на работах, при выполнении которых обязательно проведение периодических медицинских осмотров (обследований): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_3_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_3_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_4444 . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">4. Численность работников, подлежащих периодическому медицинскому осмотру:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $organisation_patient . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $organisation_patient_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $str_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_nov_tr . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">5. Численность работников, прошедших периодический медицинский осмотр (обследования): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $percent_6 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $percent_6_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $conclusion_5_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>  
            

            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">6. Процент охвата работников периодическим медицинским осмотром:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px; width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . round($paragraph_6, 1) . '%</i></span></td>
            </tr> 
            <tr>
            <td style=" padding-right: 15px; width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . round($paragraph_6_18, 1) . '%</i></span></td>
            </tr>
            </table>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">7. Список работников, прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 195px">Структурное подразделение</th>
                    <th style="width: 145px">Заключение</th>
                </tr>
                
        ';
                $html .= $html_patient;
                $html .= '</table>';

                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8. Численность работников, не завершивших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_8 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>
        ';
                if ($html_patient_8 != '')
                {
                    $html .= '     
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8.1. Список работников, не завершивших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
        ';
                    $html .= $html_patient_8;
                    $html .= '</table>';
                }

                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9. Численность работников, не прошедших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_9 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table> ';
                if ($html_patient_9 != '')
                {
                    $html .= '  
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9.1. Список работников, не прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
            ';
                    $html .= $html_patient_9;
                    $html .= '</table>';
                }
                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">10. Список работников, у которых выявлены медицинские противопоказания к работе и рекомендована экспертиза профпригодности: ';
                if (!empty($html_patient_new_10))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 350px">Заключение</th>
                </tr>
        ';
                    $html .= $html_patient_new_10;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }

                $paragraph_10_v_int = (int)$paragraph_10_v;
                $paragraph_10_v_22_int = (int)$paragraph_10_v_22;
                $v = $paragraph_10_v_int+$paragraph_10_v_22_int;
                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">11. Сводная таблица по результатам периодического медицинского осмотра:</div>
            
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 550px">Результаты периодического медицинского осмотра (обследования)</th>
                    <th style="width: 55px">Всего</th>
                </tr>
                <tr>
                    <td>Численность работников, не имеющих медицинских противопоказаний к работе</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_no_v . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, имеющих медицинские противопоказания к работе </td>
                    <td align="center"><span style="color: blue;"><i>' . $v . '</i></span></td>
                </tr> 
                <tr>
                    <td>Численность работников, нуждающихся в проведении дополнительного обследовании (заключение не дано)</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_42  . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в амбулаторном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_amb . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в стационарном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_str . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в санаторно-курортном лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_san . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в диспансерном наблюдении </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_dis . '</i></span></td>
                </tr>
            </table>  
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">12. Список лиц с установленным предварительным диагнозом профессионального заболевания: ';
                if (!empty($html_patient_123123_12))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 80px">Структурное подразделение</th>
                    <th style="width: 75px">Профессия</th>
                    <th style="width: 145px">Вредные и опасные производственные факторы</th>
                </tr> ';
                    $html .= $html_patient_123123_12;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }

                $html .= '    
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">13. Перечень впервые установленных хронических соматических заболеваний: ';
                if (!empty($html_patient_13_new))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                     <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">Код МКБ-10</th>
                    <th style="width: 350px">Количество</th>
                </tr>
            ';
                    $html .= $html_patient_13_new;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }

                $html .= '    
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">14. Перечень впервые установленных профессиональных заболеваний с указанием класса заболеваний по МКБ: ';
                if (!empty($html_patient_13_new_vp))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                     <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">Код МКБ-10</th>
                    <th style="width: 350px">Количество</th>
                </tr>
            ';
                    $html .= $html_patient_13_new_vp;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }

                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">15. Перечень впервые установленных инфекционных заболеваний (отравлений), связанных с условиями труда: <span style="color: blue;"><i>нет. </i></span></div>';

                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">16. Список лиц, подлежащих санаторно-курортному лечению: ';
                if (!empty($html_patient_12))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 85px">Код МКБ-10</th>
                </tr>
        ';
                    $html .= $html_patient_12;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет.</i></span></div>';
                }
                $html .= ' 
            <div style="margin-top: 8px; page-break-after: avoid; page-break-inside: avoid;">
            <div style="font-size: 11px; margin-right: -30px;">17. Рекомендации работодателю: осуществляють в полном объеме  санаторно-профилактические и оздоровительные мероприятия в соответствии с законодательством Российской Федерации.</div>
          
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">18. Сводная таблица (прилагается).</div>
           
        ';
            }
            else{
                $html = '
             <!--<hr align="right" style="width: -1px">-->
            <br>
            <br>
            <table style="margin-top: -80px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="center"  style="width: 380px;" >'.$settings_name.'<br>
                    
                    '.$settings_address.'<br>
                    
                    '.$settings_licenses.'  
                    </td>
                
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td   style="width: 250px;" >
                    Председатель (зам. председателя) врачебной комиссии <br>'.$settings_short_name.'<br>' . $organisation->VK_chairman . '<br><br><hr><br></td>
                 </tr>
            </table>';
                $html .= '   
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px;">Код ОГРН</td>';
                for ($i = 0; $i < strlen($settings_ogrn_code); $i++)
                {
                    $html .= '<td style=" border: 1px solid #000000; padding: 5px;">' . $settings_ogrn_code[$i] . '</td>';
                }
                $html .= '
            </tr>
            </table>
            <div style="margin-top: 15px; font-size: 14px; margin-right: -30px;" align="center"><b>ЗАКЛЮЧИТЕЛЬНЫЙ АКТ<br>ПО РЕЗУЛЬТАТАМ ПЕРИОДИЧЕСКОГО МЕДИЦИНСКОГО ОСМОТРА<br>(ОБСЛЕДОВАНИЯ)</b></div>
            <div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от ' . $today . ' г.</i></span></b></div>
            <!--<div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от 19.11.2020 г.</i></span></b></div>-->
           
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">По результатам проведенного периодического медицинского осмотра (обследования) работников: <span style="color: blue;"><i>' . $organisation->title . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">за <span style="color: blue;"><i>' . $ear . ' г.</i></span> составлен заключительный акт при участии:</div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Председатель <br>врачебной комиссии <span style="color: blue;"><i>' . $organisation->VK_chairman . ' ' . $organisation->VK_chairman_position . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Представитель <br>работодателя  <span style="color: blue;"><i>' . $organisation->fio_position_commissioner . ', ' . $organisation->position_commissioner . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">1. Общая численность работников организации (предприятия), цеха: </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_1_1_new . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_1_2_new . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_2222 . '</i></span></td>
            </tr>
            </table>
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">2. Численность работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_2_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_2_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_3333 . '</i></span></td>
            </tr>
            </table>    
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">3. Численность работников, занятых на работах, при выполнении которых обязательно проведение периодических медицинских осмотров (обследований): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_3_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_3_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_4444 . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">4. Численность работников, подлежащих периодическому медицинскому осмотру:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $organisation_patient . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $organisation_patient_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $str_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_nov_tr . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">5. Численность работников, прошедших периодический медицинский осмотр (обследования): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $percent_6 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $percent_6_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $conclusion_5_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>  
            

            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">6. Процент охвата работников периодическим медицинским осмотром:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px; width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . round($paragraph_6, 1) . '%</i></span></td>
            </tr> 
            <tr>
            <td style=" padding-right: 15px; width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . round($paragraph_6_18, 1) . '%</i></span></td>
            </tr>
            </table>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">7. Список работников, прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 195px">Структурное подразделение</th>
                    <th style="width: 145px">Заключение</th>
                </tr>
                
        ';
                $html .= $html_patient;
                $html .= '</table>';

                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8. Численность работников, не завершивших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_8 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>
        ';
                if ($html_patient_8 != '')
                {
                    $html .= '     
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8.1. Список работников, не завершивших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
        ';
                    $html .= $html_patient_8;
                    $html .= '</table>';
                }

                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9. Численность работников, не прошедших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_9 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table> ';
                if ($html_patient_9 != '')
                {
                    $html .= '  
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9.1. Список работников, не прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
            ';
                    $html .= $html_patient_9;
                    $html .= '</table>';
                }
                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">10. Список работников, у которых выявлены медицинские противопоказания к работе и рекомендована экспертиза профпригодности: ';
                if (!empty($html_patient_new_10))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 350px">Заключение</th>
                </tr>
        ';
                    $html .= $html_patient_new_10;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }
                $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">11. Сводная таблица по результатам периодического медицинского осмотра:</div>
            
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 550px">Результаты периодического медицинского осмотра (обследования)</th>
                    <th style="width: 55px">Всего</th>
                </tr>
                <tr>
                    <td>Численность работников, не имеющих медицинских противопоказаний к работе</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_no_v . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, имеющих временные медицинские противопоказания к работе </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v . '</i></span></td>
                </tr> 
                <tr>
                    <td>Численность работников, имеющих постоянные медицинские противопоказания к работе </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_22 . '</i></span></td>
                </tr> 
                <tr>
                    <td>Численность работников, нуждающихся в проведении дополнительного обследовании (заключение не дано)</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_42  . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в амбулаторном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_amb . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в стационарном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_str . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в санаторно-курортном лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_san . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в диспансерном наблюдении </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_dis . '</i></span></td>
                </tr>
            </table>  
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">12. Список лиц с установленным предварительным диагнозом профессионального заболевания: ';
                if (!empty($html_patient_123123_12))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 80px">Структурное подразделение</th>
                    <th style="width: 75px">Профессия</th>
                    <th style="width: 145px">Вредные и опасные производственные факторы</th>
                </tr> ';
                    $html .= $html_patient_123123_12;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }

                $html .= '    
           <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">13. Перечень впервые установленных хронических соматических заболеваний: ';
                if (!empty($html_patient_13_new))
                {
                    $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                     <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">Код МКБ-10</th>
                    <th style="width: 350px">Количество</th>
                </tr>
            ';
                    $html .= $html_patient_13_new;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
                }



                $html .= ' 
                    <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">14. Список лиц, подлежащих санаторно-курортному лечению: ';
                if (!empty($html_patient_12))
                {
                    $html .= '</div><table border="1"
                    style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
                    border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
                    font-size: 11px;
                     margin-right: -30px;
                    ">
                        <tr>
                            <th style="width: 25px">№ п/п</th>
                            <th style="width: 250px">ФИО сотрудника</th>
                            <th style="width: 25px">Пол</th>
                            <th style="width: 65px">Дата рождения</th>
                            <th style="width: 85px">Код МКБ-10</th>
                        </tr>
                ';
                    $html .= $html_patient_12;
                    $html .= '</table>';
                }
                else
                {
                    $html .= '<span style="color: blue;"><i>нет.</i></span></div>';
                }
                $html .= ' 
            <div style="margin-top: 8px; page-break-after: avoid; page-break-inside: avoid;">
            <div style="font-size: 11px; margin-right: -30px;">15. Рекомендации работодателю: осуществляють в полном объеме  санаторно-профилактические и оздоровительные мероприятия в соответствии с законодательством Российской Федерации.</div>
          
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">16. Сводная таблица (прилагается).</div>
           
        ';
            }


            $html .= '
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">С заключительным актом ознакомлен:</div>
           
            <br>  
            <table style="margin-top: -0px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i>' . $organisation->fio_position_commissioner . ', ' . $organisation->position_commissioner . '</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td style="width: 120px;" >
                        <hr>
                    </td>
                    <td align="center"  style="width: 70px;" >М.П. </td>
                   
                 </tr> 
                 <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i><br><br><br><br><br>Председатель (зам. председателя) врачебной комиссии: <br>' . $organisation->VK_chairman . ', ' . $organisation->VK_chairman_position . '</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td style="width: 120px;" >
                        <br><br><br><br><hr>
                    </td>
                    <td align="center"  style="width: 70px;" ><br><br><br><br>М.П.</td>
                   
                 </tr>
                
            </table>
            </div>
        ';
            $grgrgr = $organisation->title;
            $string = str_replace('"', "", $grgrgr);
            str_replace("'", "", $grgrgr);


            require_once __DIR__ . '/../../vendor/autoload.php';

            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output('Заключительный акт ' . $string . '.pdf', 'D'); //D - скачает файл!

            if ($status == '1')
            {
                //сохраняем акт на сервере!!!
                $name = 'Заключительный ' . $string . ' ' . date("d.m.Y");

                $mpdf2 = new Mpdf();
                $mpdf2->WriteHTML($html);
                $mpdf2->Output('act/' . $name . '.pdf', 'F');

                $organisation->status_print = '1';
                $organisation->name_act = $name. '.pdf';
                $organisation->date_fin_act = date("d.m.Y");
                $organisation->actual_date_issue = date("d.m.Y");
                $organisation->save(false);
            }


        }

        return $this->render('view-madal', [
            'model' => $model,
            'id' => $id,
        ]);
    }




    public function actionView2($id)
    {
        /*print_r($id);
        exit();*/
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->redirect(['list-factors/price?id=' . $id]);
    }

    public function actionView3($id)
    {
        /*print_r($id);
        exit();*/
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->redirect(['organization-consolidated-list/create?id=' . $id]);
    }

    public function actionCalculatePrice()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Organization();
        if (Yii::$app->request->post())
        {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $organization_id = Yii::$app->request->post()['Organization']['id'];
            $show = ' ';
            return $this->render('calculate-price', [
                'show' => $show,
                'model' => $model,
                'organization_id' => $organization_id
            ]);
        }
        return $this->render('calculate-price',
            [
                'model' => $model
            ]);
    }

    public function actionCreateOrganizationList($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new OrganizationConsolidatedList();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect('create-organization-list', [
                'model' => $model,
                'id' => $id,
            ]);
        }

        return $this->render('create-organization-list', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    //ПДФ выгрузка отчета!
    public function actionExportk($id, $status)
    {
       /* print_r($id);
        print_r('<br>');
        print_r($status);
        print_r('<br>');
        print_r($date_start);
        print_r('<br>');
        print_r($date_end);
        exit();*/
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        ini_set("pcre.backtrack_limit", "5000000");
        $model2 = new Organization();
        $organisation = Organization::findOne($id);
        $organisation_patient = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->count();
        $organisation_patient_alls = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->orderBy(['fio' => SORT_ASC])->all();
        $organisation_patient_j = ListPatients::find()->where(['organization_id' => $id, 'sex' => '1'])->andWhere(['print_status' => '0'])->count();
        $modellist = new ListPatients();
        $settings = Setings::find()->one();

        if($organisation->actual_date_issue == ''){
            $today = date("d.m.Y");
        }
        else{
            $today = $organisation->actual_date_issue;
        }
        if($organisation->actual_date_issue == ''){
            $ear = date("Y");
        }
        else{
            $ear = substr($organisation->actual_date_issue, 6, 4);
        }


        //ДООБСЛЕДОВАНИЕ С ГАЛОЧКОЙ У ТЕХ КТО ВСЕ ПРОШЕЛ!
        $additional_examination = 0;

        //Заношу список пациентов в переменнуую и считаю все для таблиц в одном форыче!
        $num = 1;
        $num2123123 = 1;
        $html_patient = '';

        $html_patient_8 = ''; //пункт 8!
        $patient_8 = 0; //пункт 8!
        $patient_8_j = 0; //пункт 8!
        $patient_8_18 = 0; //пункт 8!
        $num_8 = 1;

        $html_patient_9 = ''; //пункт 8!
        $patient_9 = 0; //пункт 9!
        $patient_9_j = 0; //пункт 9!
        $patient_9_18 = 0; //пункт 9!
        $num_9 = 1;

        $str_18 = 0; //для первого пункта  старше 18 лет!

        $patient_p2 = 0; //для второго пункта общее количество!
        $patient_p2_j = 0; //для второго пункта количество женщин!
        $patient_p2_18 = 0; //для второго пункта общее количество страше 18 лет!

        $conclusion_5 = 0; //для 5 го пункта
        $conclusion_5_j = 0; //для 5 го пункта
        $conclusion_5_18 = 0; //для 5 го пункта

        //РАСЧЕТЫ ДЛЯ ПРОЦЕНТОВ!!! ВСЕ ПАЦИЕНТЫ КРОМЕ ТЕХ КТО ВООБЩЕ НЕ ПРИШЕЛ!!!!
        $percent_6 = 0; //для 6 го пункта
        $percent_6_j = 0; //для 6 го пункта

        $paragraph_10_no_v = 0;    //для 10 го пункта 1 пункт таблици 10 (не выявлены противопоказания)
        $paragraph_10_v = 0;    //для 10 го пункта 2 пункт таблици 10 (выявлены противопоказания)
        $paragraph_10_v_22 = 0;    //для 10 го пункта 2 пункт таблици 10 (Численность работников, имеющих постоянные медицинские противопоказания к работе)
        $paragraph_10_v_4 = 0;    //для 10 го пункта 4 пункт таблици 10 (нет заключения!)
        $paragraph_10_v_amb = 0;    //для 10 го пункта 6 пункт таблици 10 (амбулаторное лечение)
        $paragraph_10_v_str = 0;    //для 10 го пункта 7 пункт таблици 10 (амбулаторное лечение)
        $paragraph_10_v_san = 0;    //для 10 го пункта 8 пункт таблици 10 (амбулаторное лечение)
        $paragraph_10_v_dis = 0;    //для 10 го пункта 9 пункт таблици 10 (амбулаторное лечение)

        //пункт 11
        $html_patient_11 = ''; //пункт 11!

        $html_patient_new_10 = ''; //пункт 10! НОВЫЙ У КОТОРЫХ ВЫЯВЛЕНЫ ПРОТИВОПОКАЗАНИЯ
        $patient_new_10 = 1; //пункт 10! НОВЫЙ У КОТОРЫХ ВЫЯВЛЕНЫ ПРОТИВОПОКАЗАНИЯ

        $patient_11 = 0;
        //пункт 12
        $html_patient_12 = ''; //пункт 12!
        $patient_12 = 0;

        //для МКБ !!!!
        $new_arr = [];
        $new_arr_vp = []; //впервые проф


        $new_arr2 = [];
        $new_arr_vp2 = []; //впервые проф

        $html_patient_123123_12 = ''; //lля 12 пункта
        foreach ($organisation_patient_alls as $organisation_patient_all)
        {
            //общее количесвто для 1го пункта
            $age = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
            if ($age <= 18)
            {
                $str_18++;
            }

            //общее количесвто для 2го пункта
            if ($organisation_patient_all->hazard == '3' || $organisation_patient_all->hazard == '4' || $organisation_patient_all->hazard == '5')
            {
                $patient_p2++;
                if ($organisation_patient_all->sex == '1')
                {
                    $patient_p2_j++;
                }
                $age2 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                if ($age2 <= 18)
                {
                    $patient_p2_18++;
                }
            }

            //расчет для пунктов 5-6
            //$conclusion = ConclusionIndivid::find()->where(['user_id'=>$organisation_patient_all->id])->one();
            print_r($organisation_patient_all->fio );
            print_r('<br>');
            $organisation_patient2342342 = ListPatients::find()->where(['id' => $organisation_patient_all->id])->one();
            $fails = Therapist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo11 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();

            if (
                $fails->contraindications != '5' && $fails->contraindications != '' &&
                $med_ifo2->contraindications != '5' && $med_ifo2->contraindications != '' &&
                $med_ifo4->contraindications != '5' && $med_ifo4->contraindications != '' &&
                $med_ifo3->contraindications != '5' && $med_ifo3->contraindications != '' &&
                $med_ifo6->contraindications != '5' && $med_ifo6->contraindications != '' &&
                $med_ifo5->contraindications != '5' && $med_ifo5->contraindications != '' &&
                $med_ifo7->contraindications != '5' && $med_ifo7->contraindications != '' &&
                $med_ifo8->contraindications != '5' && $med_ifo8->contraindications != '' &&
                $med_ifo9->contraindications != '5' && $med_ifo9->contraindications != '' &&
                $med_ifo10->contraindications != '5' && $med_ifo10->contraindications != '' &&
                $med_ifo11->contraindications != '5' && $med_ifo11->contraindications != ''
            )
            {
                if ($fails->contraindications == '1' || $fails->contraindications == '3' ||
                    $med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' ||
                    $med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' ||
                    $med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' ||
                    $med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' ||
                    $med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' ||
                    $med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' ||
                    $med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' ||
                    $med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' ||
                    $med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' ||
                    $med_ifo11->contraindications == '1' || $med_ifo11->contraindications == '3'
                )
                {
                    $conclus = 'Выявлены противопоказания';
                    $conclusion_5++;
                    $percent_6++;
                    if ($organisation_patient_all->sex == '1')
                    {
                        $conclusion_5_j++;
                        $percent_6_j++;
                    }
                    $age3 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                    if ($age3 <= 18)
                    {
                        $conclusion_5_18++;
                    }
                    //lля пункта 7
                    $html_patient .= '
                        <tr>
                            <td align="center" style="width: 30px">' . $num . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 145px">' . $organisation_patient_all->department . '</td>
                            <td style="width: 145px"><i><span style="color: blue;">' . $conclus . '</span></i></td>
                        </tr>';
                    $num++;
                    if ($fails->contraindications == '1' ||
                        $med_ifo2->contraindications == '1' ||
                        $med_ifo4->contraindications == '1' ||
                        $med_ifo3->contraindications == '1' ||
                        $med_ifo6->contraindications == '1' ||
                        $med_ifo5->contraindications == '1' ||
                        $med_ifo7->contraindications == '1' ||
                        $med_ifo8->contraindications == '1' ||
                        $med_ifo9->contraindications == '1' ||
                        $med_ifo10->contraindications == '1' ||
                        $med_ifo11->contraindications == '1'
                    )
                    {
                        $conclus_2 = '(временные)';
                        $paragraph_10_v++;
                    }
                    elseif ($fails->contraindications == '3' ||
                        $med_ifo2->contraindications == '3' ||
                        $med_ifo4->contraindications == '3' ||
                        $med_ifo3->contraindications == '3' ||
                        $med_ifo6->contraindications == '3' ||
                        $med_ifo5->contraindications == '3' ||
                        $med_ifo7->contraindications == '3' ||
                        $med_ifo8->contraindications == '3' ||
                        $med_ifo9->contraindications == '3' ||
                        $med_ifo10->contraindications == '3' ||
                        $med_ifo11->contraindications == '3'
                    )
                    {
                        $conclus_2 = '(постоянные)';
                        $paragraph_10_v_22++;
                    }
                    else
                    {
                        $conclus_2 = '';
                    }


                    $conclus22 = 'Выявлены противопоказания ' . $conclus_2;
                    if($organisation_patient_all->order_type == '1'){
                        $html_patient_new_10 .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $patient_new_10 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td style="width: 350px">' . $conclus22 . '; '
                            . $modellist->translation_bd_down_pril1_print_v2_kind_work2($organisation_patient_all->id)
                            . '</td>
                        </tr>';
                    }
                    else{
                        $html_patient_new_10 .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $patient_new_10 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td style="width: 350px">' . $conclus22 . '; ' . $modellist->translation_bd_down_pril1_print_v2($organisation_patient_all->id) .' '. $modellist->translation_bd_down_pril2_print_v2($organisation_patient_all->id) .' '. $modellist->translation_bd_down_pril3_print_v2($organisation_patient_all->id) . '</td>
                        </tr>';
                    }

                    $patient_new_10++;
                }
                elseif(
                    $fails->contraindications == '4' ||
                    $med_ifo2->contraindications == '4' ||
                    $med_ifo4->contraindications == '4' ||
                    $med_ifo3->contraindications == '4' ||
                    $med_ifo6->contraindications == '4' ||
                    $med_ifo5->contraindications == '4' ||
                    $med_ifo7->contraindications == '4' ||
                    $med_ifo8->contraindications == '4' ||
                    $med_ifo9->contraindications == '4' ||
                    $med_ifo10->contraindications == '4' ||
                    $med_ifo11->contraindications == '4'
                )
                {
                    $conclus = 'Выявлены противопоказания к работе по приложению 2';
                    $conclusion_5++;
                    $percent_6++;
                    if ($organisation_patient_all->sex == '1')
                    {
                        $conclusion_5_j++;
                        $percent_6_j++;
                    }
                    $age3 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                    if ($age3 <= 18)
                    {
                        $conclusion_5_18++;
                    }
                    if (empty($organisation_patient_all->department))
                    {
                        $department = 'н/д';
                    }
                    else
                    {
                        $department = $organisation_patient_all->department;
                    }
                    //lля пункта 7
                    $html_patient .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $num . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 145px">' . $department . '</td>
                            <td style="width: 145px"><i><span style="color: blue;">' . $conclus . '</span></i></td>
                        </tr>';
                    $num++;
                    $paragraph_10_no_v++;

                }
                elseif(
                    $fails->contraindications == '0' ||
                    $med_ifo2->contraindications == '0' ||
                    $med_ifo4->contraindications == '0' ||
                    $med_ifo3->contraindications == '0' ||
                    $med_ifo6->contraindications == '0' ||
                    $med_ifo5->contraindications == '0' ||
                    $med_ifo7->contraindications == '0' ||
                    $med_ifo8->contraindications == '0' ||
                    $med_ifo9->contraindications == '0' ||
                    $med_ifo10->contraindications == '0' ||
                    $med_ifo11->contraindications == '0'
                )
                {
                    $conclus = 'Не выявлены';
                    $conclusion_5++;
                    $percent_6++;
                    if ($organisation_patient_all->sex == '1')
                    {
                        $conclusion_5_j++;
                        $percent_6_j++;
                    }
                    $age3 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                    if ($age3 <= 18)
                    {
                        $conclusion_5_18++;
                    }
                    if (empty($organisation_patient_all->department))
                    {
                        $department = 'н/д';
                    }
                    else
                    {
                        $department = $organisation_patient_all->department;
                    }
                    //lля пункта 7
                    $html_patient .= '
                        <tr>
                            <td align="center" style="width: 25px">' . $num . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 145px">' . $department . '</td>
                            <td style="width: 145px">' . $conclus . '</td>
                        </tr>';
                    $num++;
                    $paragraph_10_no_v++;

                }
            }
            else
            {
                if(
                    $fails->contraindications != '5' ||
                    $med_ifo2->contraindications != '5' ||
                    $med_ifo4->contraindications != '5' ||
                    $med_ifo3->contraindications != '5' ||
                    $med_ifo6->contraindications != '5' ||
                    $med_ifo5->contraindications != '5' ||
                    $med_ifo7->contraindications != '5' ||
                    $med_ifo8->contraindications != '5' ||
                    $med_ifo9->contraindications != '5' ||
                    $med_ifo10->contraindications != '5' ||
                    $med_ifo11->contraindications != '5'
                ){
                    $additional_examination++;
                }
                if (
                    $organisation_patient2342342->status == '1' ||
                    $fails->contraindications != '' || $fails->contraindications != '5'||
                    $med_ifo2->contraindications != '' || $med_ifo2->contraindications != '5'||
                    $med_ifo4->contraindications != '' || $med_ifo4->contraindications != '5'||
                    $med_ifo3->contraindications != '' || $med_ifo3->contraindications != '5'||
                    $med_ifo6->contraindications != '' || $med_ifo6->contraindications != '5'||
                    $med_ifo5->contraindications != '' || $med_ifo5->contraindications != '5'||
                    $med_ifo7->contraindications != '' || $med_ifo7->contraindications != '5'||
                    $med_ifo8->contraindications != '' || $med_ifo8->contraindications != '5'||
                    $med_ifo9->contraindications != '' || $med_ifo9->contraindications != '5'||
                    $med_ifo10->contraindications != '' || $med_ifo10->contraindications != '5'||
                    $med_ifo11->contraindications != '' || $med_ifo11->contraindications != '5'
                )
                {
                    $conclus = '';
                    $percent_6++;
                    //расчеты для пункта 8
                    if ($organisation_patient_all->sex == '1')
                    {
                        $patient_8_j++;
                        $percent_6_j++;
                    }
                    $age4 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                    if ($age4 <= 18)
                    {
                        $patient_8_18++;
                    }

                    $html_patient_8 .= '
                        <tr>
                            <td style="width: 25px">' . $num_8 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        </tr>
                    ';
                    $patient_8++;
                    $num_8++;
                    $paragraph_10_v_4++;
                }
                else
                {
                    $patient_9++;
                    if ($organisation_patient_all->sex == '1')
                    {
                        $patient_9_j++;
                    }
                    $age5 = $model2->calculate_age($organisation_patient_all->date_birth); //поределяем количество лет!
                    if ($age5 <= 18)
                    {
                        $patient_9_18++;
                    }
                    $html_patient_9 .= '
                        <tr>
                            <td style="width: 25px">' . $num_9 . '</td>
                            <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        </tr>
                    ';
                    $num_9++;
                }

            }


            //Это для МКБ!!!!

            $fild_arr12 = [
                'mkb1',
                'mkb2',
                'mkb3',
            ];

            $prof = [
                'prof_diagnosis_1',
                'prof_diagnosis_2',
                'prof_diagnosis_3',
            ];

            /* //это для старого МКБ
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $name_factor = $fild_arr12[$i];
                    if (!empty($fails->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($fails->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $new_arr[] = $str;
                    }
                }*/

            //МКБ Терапевт //для пункта 13
            if (!empty($fails))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {
                    $name_factor = $fild_arr12[$i];
                    $prof_fac = $prof[$i];
                    if (!empty($fails->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($fails->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($fails->diagnosis_primary_field == '1')
                        {
                            if ($fails->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }

                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo2))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {
                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo2->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo2->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo2->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo4))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo4->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo4->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo4->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo4->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo3))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo3->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo3->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo3->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo6))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo6->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo6->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo6->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo5))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo5->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo5->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo5->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo7))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo7->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo7->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo7->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo8))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo8->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo8->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo8->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo9))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo9->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo9->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo9->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo10))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo10->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo10->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo10->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }

            //расчет для 12-го пункта
            if (
                ($fails->diagnosis_primary_field == '1' && ($fails->prof_diagnosis_1 == '0' || $fails->prof_diagnosis_2 == '0' || $fails->prof_diagnosis_3 == '0')) ||
                ($med_ifo2->diagnosis_primary_field == '1' && ($med_ifo2->prof_diagnosis_1 == '0' || $med_ifo2->prof_diagnosis_2 == '0' || $med_ifo2->prof_diagnosis_3 == '0')) ||
                ($med_ifo4->diagnosis_primary_field == '1' && ($med_ifo4->prof_diagnosis_1 == '0' || $med_ifo4->prof_diagnosis_2 == '0' || $med_ifo4->prof_diagnosis_3 == '0')) ||
                ($med_ifo3->diagnosis_primary_field == '1' && ($med_ifo3->prof_diagnosis_1 == '0' || $med_ifo3->prof_diagnosis_2 == '0' || $med_ifo3->prof_diagnosis_3 == '0')) ||
                ($med_ifo6->diagnosis_primary_field == '1' && ($med_ifo6->prof_diagnosis_1 == '0' || $med_ifo6->prof_diagnosis_2 == '0' || $med_ifo6->prof_diagnosis_3 == '0')) ||
                ($med_ifo5->diagnosis_primary_field == '1' && ($med_ifo5->prof_diagnosis_1 == '0' || $med_ifo5->prof_diagnosis_2 == '0' || $med_ifo5->prof_diagnosis_3 == '0')) ||
                ($med_ifo7->diagnosis_primary_field == '1' && ($med_ifo7->prof_diagnosis_1 == '0' || $med_ifo7->prof_diagnosis_2 == '0' || $med_ifo7->prof_diagnosis_3 == '0')) ||
                ($med_ifo8->diagnosis_primary_field == '1' && ($med_ifo8->prof_diagnosis_1 == '0' || $med_ifo8->prof_diagnosis_2 == '0' || $med_ifo8->prof_diagnosis_3 == '0')) ||
                ($med_ifo9->diagnosis_primary_field == '1' && ($med_ifo9->prof_diagnosis_1 == '0' || $med_ifo9->prof_diagnosis_2 == '0' || $med_ifo9->prof_diagnosis_3 == '0')) ||
                ($med_ifo10->diagnosis_primary_field == '1' && ($med_ifo10->prof_diagnosis_1 == '0' || $med_ifo10->prof_diagnosis_2 == '0' || $med_ifo10->prof_diagnosis_3 == '0'))
            )
            {
                $html_patient_123123_12 .= '
                <tr>
                    <td align="center" >' . $num2123123 . '</td>
                    <td >' . $organisation_patient_all->fio . '</td>
                    <td align="center">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                    <td >' . $organisation_patient_all->date_birth . '</td>
                    <td >' . $organisation_patient_all->department . '</td>
                    <td >' . $organisation_patient_all->post_profession . '</td>
                    <td >' . $modellist->translation_bd_down_pril1_print_v3($organisation_patient_all->id) . $modellist->translation_bd_down_pril2_print_v3($organisation_patient_all->id) . '</td>
                </tr>';
                $num2123123++;

            }


            //расчет для 11 пункта
            if ($fails->prof_diagnosis_1 == '0' ||
                $fails->prof_diagnosis_2 == '0' ||
                $fails->prof_diagnosis_3 == '0' ||
                $fails->prof_diagnosis_rep_1 == '0' ||
                $fails->prof_diagnosis_rep_2 == '0' ||
                $fails->prof_diagnosis_rep_3 == '0' ||
                $med_ifo2->prof_diagnosis_1 == '0' ||
                $med_ifo2->prof_diagnosis_2 == '0' ||
                $med_ifo2->prof_diagnosis_3 == '0' ||
                $med_ifo2->prof_diagnosis_rep_1 == '0' ||
                $med_ifo2->prof_diagnosis_rep_2 == '0' ||
                $med_ifo2->prof_diagnosis_rep_3 == '0' ||
                $med_ifo4->prof_diagnosis_1 == '0' ||
                $med_ifo4->prof_diagnosis_2 == '0' ||
                $med_ifo4->prof_diagnosis_3 == '0' ||
                $med_ifo4->prof_diagnosis_rep_1 == '0' ||
                $med_ifo4->prof_diagnosis_rep_2 == '0' ||
                $med_ifo4->prof_diagnosis_rep_3 == '0' ||
                $med_ifo3->prof_diagnosis_1 == '0' ||
                $med_ifo3->prof_diagnosis_2 == '0' ||
                $med_ifo3->prof_diagnosis_3 == '0' ||
                $med_ifo3->prof_diagnosis_rep_1 == '0' ||
                $med_ifo3->prof_diagnosis_rep_2 == '0' ||
                $med_ifo3->prof_diagnosis_rep_3 == '0' ||
                $med_ifo6->prof_diagnosis_1 == '0' ||
                $med_ifo6->prof_diagnosis_2 == '0' ||
                $med_ifo6->prof_diagnosis_3 == '0' ||
                $med_ifo6->prof_diagnosis_rep_1 == '0' ||
                $med_ifo6->prof_diagnosis_rep_2 == '0' ||
                $med_ifo6->prof_diagnosis_rep_3 == '0' ||
                $med_ifo5->prof_diagnosis_1 == '0' ||
                $med_ifo5->prof_diagnosis_2 == '0' ||
                $med_ifo5->prof_diagnosis_3 == '0' ||
                $med_ifo5->prof_diagnosis_rep_1 == '0' ||
                $med_ifo5->prof_diagnosis_rep_2 == '0' ||
                $med_ifo5->prof_diagnosis_rep_3 == '0' ||
                $med_ifo7->prof_diagnosis_1 == '0' ||
                $med_ifo7->prof_diagnosis_2 == '0' ||
                $med_ifo7->prof_diagnosis_3 == '0' ||
                $med_ifo7->prof_diagnosis_rep_1 == '0' ||
                $med_ifo7->prof_diagnosis_rep_2 == '0' ||
                $med_ifo7->prof_diagnosis_rep_3 == '0' ||
                $med_ifo8->prof_diagnosis_1 == '0' ||
                $med_ifo8->prof_diagnosis_2 == '0' ||
                $med_ifo8->prof_diagnosis_3 == '0' ||
                $med_ifo8->prof_diagnosis_rep_1 == '0' ||
                $med_ifo8->prof_diagnosis_rep_2 == '0' ||
                $med_ifo8->prof_diagnosis_rep_3 == '0' ||
                $med_ifo9->prof_diagnosis_1 == '0' ||
                $med_ifo9->prof_diagnosis_2 == '0' ||
                $med_ifo9->prof_diagnosis_3 == '0' ||
                $med_ifo9->prof_diagnosis_rep_1 == '0' ||
                $med_ifo9->prof_diagnosis_rep_2 == '0' ||
                $med_ifo9->prof_diagnosis_rep_3 == '0' ||
                $med_ifo10->prof_diagnosis_1 == '0' ||
                $med_ifo10->prof_diagnosis_2 == '0' ||
                $med_ifo10->prof_diagnosis_3 == '0' ||
                $med_ifo10->prof_diagnosis_rep_1 == '0' ||
                $med_ifo10->prof_diagnosis_rep_2 == '0' ||
                $med_ifo10->prof_diagnosis_rep_3 == '0'
            )
            {
                if ($fails->therapist_zoda_3 == '0')
                {
                    $srrrt = 'Гр. риска - Заболевания органов дыхания';
                }
                elseif ($fails->therapist_pi_2 == '0')
                {
                    $srrrt = 'Гр. риска - Проф. интоксикации';
                }
                elseif ($med_ifo2->neurologist6 == '0')
                {
                    $srrrt = 'Гр. риска - Заболевания органов дыхания';
                }
                elseif ($med_ifo3->audiologist6 == '0')
                {
                    $srrrt = 'Гр. риска - Нейросенсорной тугоухости';
                }
                else
                {
                    $srrrt = '';
                }

                $patient_11++;
                $html_patient_11 .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $patient_11 . '</td>
                        <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                        <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                    </tr>
                '; //пункт 11!
            }
            //расчет для 12 пункта

            if
            (
                $fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0' ||
                $med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0' ||
                $med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0' ||
                $med_ifo6->psychiatrist5 == '0' ||
                $med_ifo5->narcology5 == '0' ||
                $med_ifo7->gynecologist5 == '0' ||
                $med_ifo8->surgeon5 == '0' ||
                $med_ifo9->dermatovenereologist5 == '0' ||
                $med_ifo10->dentist5 == '0'
            )
            {
                if ($fails->therapist_zoda_3 == '0')
                {
                    $srrrt = 'Гр. риска - Заболевания органов дыхания';
                }
                elseif ($fails->therapist_pi_2 == '0')
                {
                    $srrrt = 'Гр. риска - Проф. интоксикации';
                }
                elseif ($med_ifo2->neurologist6 == '0')
                {
                    $srrrt = 'Гр. риска - Заболевания органов дыхания';
                }
                elseif ($med_ifo3->audiologist6 == '0')
                {
                    $srrrt = 'Гр. риска - Нейросенсорной тугоухости';
                }
                else
                {
                    $srrrt = '';
                }


                //Это для МКБ!!!!

                $fild_arr12 = [
                    'mkb_repeated1',
                    'mkb_repeated2',
                    'mkb_repeated3',
                ];

                $prof = [
                    'prof_diagnosis_1',
                    'prof_diagnosis_2',
                    'prof_diagnosis_3',
                ];

                //МКБ Терапевт //для пункта 14
                if ($fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($fails->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($fails->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($fails->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                /*if (!empty($med_ifo4))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }*/
                if ($med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo3->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo3->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo6->psychiatrist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo6->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo6->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo5->narcology5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo5->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo5->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo7->gynecologist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo7->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo7->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo8->surgeon5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo8->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo8->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo9->dermatovenereologist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo9->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo9->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo10->dentist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo10->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo10->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }

                $new_arr2 = array_unique($new_arr2);
                $research_id_222 = array_values($new_arr2); //обнуляю ключи
                $html_patient_13_new33333 = '';
                for ($k = 0; $k < count($research_id_222); $k++)
                {
                    $html_patient_13_new33333 .= $research_id_222[$k] . '; ';
                }

                $patient_12++;
                $html_patient_12 .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $patient_12 . '</td>
                        <td style="width: 250px">' . $organisation_patient_all->fio . '</td>
                        <td align="center" style="width: 25px">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                        <td style="width: 65px">' . $organisation_patient_all->date_birth . '</td>
                        <td style="width: 65px">' . $html_patient_13_new33333 . '</td>
                    </tr>
                '; //пункт 11!
            }

            //расчеты для пункта 10! из осмотров врачеЙ!!!!!!

            if ($fails->therapist_r1 == '0' || $fails->therapist_pi_1 == '0' || $fails->therapist_zoda_1 == '0' ||
                $med_ifo2->neurologist1 == '0' || $med_ifo2->neurologist2 == '0' ||
                $med_ifo3->audiologist1 == '0' || $med_ifo3->audiologist2 == '0' ||
                $med_ifo6->psychiatrist1 == '0' ||
                $med_ifo5->narcology1 == '0' ||
                $med_ifo7->gynecologist1 == '0' ||
                $med_ifo8->surgeon1 == '0' ||
                $med_ifo9->dermatovenereologist1 == '0' ||
                $med_ifo10->dentist1 == '0'
            )
            {
                $paragraph_10_v_amb++;
            }
            if ($fails->therapist_r3 == '0' || $fails->therapist_pi_2 == '0' || $fails->therapist_zoda_2 == '0' ||
                $med_ifo2->neurologist3 == '0' || $med_ifo2->neurologist4 == '0' ||
                $med_ifo3->audiologist3 == '0' || $med_ifo3->audiologist4 == '0' ||
                $med_ifo6->psychiatrist3 == '0' ||
                $med_ifo5->narcology3 == '0' ||
                $med_ifo7->gynecologist3 == '0' ||
                $med_ifo8->surgeon3 == '0' ||
                $med_ifo9->dermatovenereologist3 == '0' ||
                $med_ifo10->dentist3 == '0'
            )
            {
                $paragraph_10_v_str++;
            }
            if (
                $fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0' ||
                $med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0' ||
                $med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0' ||
                $med_ifo6->psychiatrist5 == '0' ||
                $med_ifo5->narcology5 == '0' ||
                $med_ifo7->gynecologist5 == '0' ||
                $med_ifo8->surgeon5 == '0' ||
                $med_ifo9->dermatovenereologist5 == '0' ||
                $med_ifo10->dentist5 == '0'
            )
            {
                $paragraph_10_v_san++;
            }
            if ($fails->therapist_r7 == '0' || $fails->therapist_pi_4 == '0' || $fails->therapist_zoda_4 == '0' ||
                $med_ifo2->neurologist7 == '0' || $med_ifo2->neurologist8 == '0' ||
                $med_ifo3->audiologist7 == '0' || $med_ifo3->audiologist8 == '0' ||
                $med_ifo6->psychiatrist7 == '0' ||
                $med_ifo5->narcology7 == '0' ||
                $med_ifo7->gynecologist7 == '0' ||
                $med_ifo8->surgeon7 == '0' ||
                $med_ifo9->dermatovenereologist7 == '0' ||
                $med_ifo10->dentist7 == '0'
            )
            {
                $paragraph_10_v_dis++;
            }
        }

        //todo efrefrefrefref

        //для первого пункта  старше 18 лет!
        if ($str_18 == 0)
        {
            $str_18 = 'нет';
        }
        //для пункта 4
        if ($patient_p2 == 0)
        {
            $patient_p2 = '-';
        }
        if ($patient_p2_j == 0)
        {
            $patient_p2_j = '-';
        }
        if ($patient_p2_18 == 0)
        {
            $patient_p2_18 = '-';
        }
        //для пункта 5
        if ($conclusion_5_18 == 0)
        {
            $conclusion_5_18 = 'нет';
        }


        //для пункта 8
        if ($patient_8 == 0)
        {
            $patient_8 = 'нет';
        }
        if ($patient_8_j == 0)
        {
            $patient_8_j = 'нет';
        }
        if ($patient_8_18 == 0)
        {
            $patient_8_18 = 'нет';
        }
        //для пункта 9
        if ($patient_9 == 0)
        {
            $patient_9 = 'нет';
        }
        if ($patient_9_j == 0)
        {
            $patient_9_j = 'нет';
        }
        if ($patient_9_18 == 0)
        {
            $patient_9_18 = 'нет';
        }

        //процент для 6 пункта
        if (!empty($conclusion_5))
        {
            $paragraph_6 = (100 * $percent_6) / $organisation_patient;
        }
        else
        {
            $paragraph_6 = 0;
        }
        if (!empty($conclusion_5_j))
        {
            $paragraph_6_18 = (100 * $percent_6_j) / $organisation_patient_j;
        }
        else
        {
            $paragraph_6_18 = 0;
        }


        $html_patient_12_new = '';
       /* print_r(array_filter($new_arr));
        $new_arr = array_filter($new_arr);
        foreach ($new_arr as $new_ar){
            print_r($new_ar);
            print_r('<br>');
        }
        exit();*/
        /*$arr2222r = array_count_values($new_arr); //считаем количество одинаковых обследований
        $new_arr = array_unique($new_arr);
        $research_id_2 = array_values($new_arr); //обнуляю ключи
        $nume = 1;

        $html_patient_12_new = '';
        for ($k = 0; $k < count($research_id_2); $k++)
        {
            $html_patient_12_new .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $nume . '</td>
                        <td style="width: 250px">' . $research_id_2[$k] . '</td>
                        <td align="center" style="width: 350px">' . $arr2222r[$research_id_2[$k]] . '</td>
                    </tr>
                '; //пункт 11!
            $nume++;
        }

        $arr2222r2 = array_count_values($new_arr_vp); //считаем количество одинаковых обследований
        $new_arr_vp = array_unique($new_arr_vp);
        $research_id_3 = array_values($new_arr_vp); //обнуляю ключи
        $nume = 1;

        $html_patient_13_new = '';
        for ($k = 0; $k < count($research_id_3); $k++)
        {
            $html_patient_13_new .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $nume . '</td>
                        <td style="width: 250px">' . $research_id_3[$k] . '</td>
                        <td align="center" style="width: 350px">' . $arr2222r2[$research_id_3[$k]] . '</td>
                    </tr>
                '; //пункт 11!
            $nume++;
        }
        */
        $new_arr = array_filter($new_arr);
        $arr2222r = array_count_values($new_arr); //считаем количество одинаковых обследований
        $new_arr = array_unique($new_arr);
        $research_id_2 = array_values($new_arr); //обнуляю ключи
        $nume = 1;

        $html_patient_13_new = '';
        for ($k = 0; $k < count($research_id_2); $k++)
        {
            $html_patient_13_new .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $nume . '</td>
                        <td style="width: 250px">' . $research_id_2[$k] . '</td>
                        <td align="center" style="width: 350px">' . $arr2222r[$research_id_2[$k]] . '</td>
                    </tr>
                '; //пункт 11!
            $nume++;
        }
        $arr2222r_vp = array_count_values($new_arr_vp); //считаем количество одинаковых обследований
        $new_arr_vp = array_unique($new_arr_vp);
        $research_id_2_vp = array_values($new_arr_vp); //обнуляю ключи
        $nume_vp = 1;

        $html_patient_13_new_vp = '';
        for ($k = 0; $k < count($research_id_2_vp); $k++)
        {
            $html_patient_13_new_vp .= '
                    <tr>
                        <td align="center" style="width: 25px">' . $nume_vp . '</td>
                        <td style="width: 250px">' . $research_id_2_vp[$k] . '</td>
                        <td align="center" style="width: 350px">' . $arr2222r_vp[$research_id_2_vp[$k]] . '</td>
                    </tr>
                '; //пункт 11!
            $nume_vp++;
        }
        //print_r($new_arr);
        //print_r('<br>');
        //print_r($research_id_2);
        //print_r('<br>');
        //print_r($arr2222r);
        //exit();

        //ПУНКТ 1 ЫЙ ИЗ ТАБЛИЦЫ ОРГАНИЗАЦИЯ
        if ($organisation->number_employees != '')
        {
            $paragraph_1_1_new = $organisation->number_employees; //численность работников
        }
        else
        {
            $paragraph_1_1_new = '-';
        }

        if ($organisation->number_employees_j != '')
        {
            $paragraph_1_2_new = $organisation->number_employees_j; //численность работников женщин
        }
        else
        {
            $paragraph_1_2_new = '-';
        }

        if ($organisation->hard_work != '')
        {
            $paragraph_2_1_new = $organisation->hard_work; //	тяжелые работники
        }
        else
        {
            $paragraph_2_1_new = '-';
        }

        if ($organisation->hard_work_j != '')
        {
            $paragraph_2_2_new = $organisation->hard_work_j; //	тяжелые работники женщины
        }
        else
        {
            $paragraph_2_2_new = '-';
        }

        if ($organisation->mandatory_periodic_inspection != '')
        {
            $paragraph_3_1_new = $organisation->mandatory_periodic_inspection; //	обезательный переодический осмотр
        }
        else
        {
            $paragraph_3_1_new = '-';
        }

        if ($organisation->mandatory_periodic_inspection_j != '')
        {
            $paragraph_3_2_new = $organisation->mandatory_periodic_inspection_j; //обезательный переодический осмотр женщины
        }
        else
        {
            $paragraph_3_2_new = '-';
        }
        if ($organisation->field_2222 != '')
        {
            $field_2222 = $organisation->field_2222; //обезательный переодический осмотр женщины
        }
        else
        {
            $field_2222 = '-';
        }

        if ($organisation->field_3333 != '')
        {
            $field_3333 = $organisation->field_3333; //обезательный переодический осмотр женщины
        }
        else
        {
            $field_3333 = '-';
        }

        if ($organisation->field_4444 != '')
        {
            $field_4444 = $organisation->field_4444; //обезательный переодический осмотр женщины
        }
        else
        {
            $field_4444 = '-';
        }

        if ($organisation->field_nov_tr != '')
        {
            $field_nov_tr = $organisation->field_nov_tr; //обезательный переодический осмотр женщины
        }
        else
        {
            $field_nov_tr = '-';
        }

        $paragraph_10_v_42 = $paragraph_10_v_4 + $additional_examination;
        //print_r($html_patient_123123_12);
        //exit();


        if (!empty($settings))
        {
            $settings_name = $settings->name;
            $settings_licenses = $settings->licenses;
            $settings_address = $settings->address;
            $settings_ogrn_code = $settings->ogrn_code;
            $settings_short_name = $settings->short_name;
        }
        else
        {
            $settings_name = '';
            $settings_licenses = '';
            $settings_address = '';
            $settings_ogrn_code = '0';
            $settings_short_name = '';
        }

        if ($organisation->order_type == '1'){
            //для приказа 29н
            $html = '
             <!--<hr align="right" style="width: -1px">-->
            <br>
            <br>
            <table style="margin-top: -80px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="center"  style="width: 380px;" >'.$settings_name.'<br>
                    
                    '.$settings_address.'<br>
                    
                    '.$settings_licenses.'  
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                </tr>
            </table>';
            $html .= '   
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px;">Код ОГРН</td>';
            for ($i = 0; $i < strlen($settings_ogrn_code); $i++)
            {
                $html .= '<td style=" border: 1px solid #000000; padding: 5px;">' . $settings_ogrn_code[$i] . '</td>';
            }
            $html .= '
            </tr>
            </table>
            <div style="margin-top: 15px; font-size: 14px; margin-right: -30px;" align="center"><b>ЗАКЛЮЧИТЕЛЬНЫЙ АКТ<br>ПО РЕЗУЛЬТАТАМ ПЕРИОДИЧЕСКОГО МЕДИЦИНСКОГО ОСМОТРА<br>(ОБСЛЕДОВАНИЯ)</b></div>
            <div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от ' . $today . ' г.</i></span></b></div>
            <!--<div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от 19.11.2020 г.</i></span></b></div>-->
           
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">По результатам проведенного периодического медицинского осмотра (обследования) работников: <span style="color: blue;"><i>' . $organisation->title . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">за <span style="color: blue;"><i>' . $ear . ' г.</i></span> составлен заключительный акт при участии:</div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Председатель <br>врачебной комиссии <span style="color: blue;"><i>' . $organisation->VK_chairman . ' ' . $organisation->VK_chairman_position . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Представитель <br>работодателя  <span style="color: blue;"><i>' . $organisation->fio_position_commissioner . ', ' . $organisation->position_commissioner . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">1. Общая численность работников организации (предприятия), цеха: </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_1_1_new . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_1_2_new . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_2222 . '</i></span></td>
            </tr>
            </table>
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">2. Численность работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_2_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_2_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_3333 . '</i></span></td>
            </tr>
            </table>    
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">3. Численность работников, занятых на работах, при выполнении которых обязательно проведение периодических медицинских осмотров (обследований): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_3_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_3_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_4444 . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">4. Численность работников, подлежащих периодическому медицинскому осмотру:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $organisation_patient . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $organisation_patient_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $str_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_nov_tr . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">5. Численность работников, прошедших периодический медицинский осмотр (обследования): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $percent_6 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $percent_6_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $conclusion_5_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>  
            

            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">6. Процент охвата работников периодическим медицинским осмотром:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px; width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . round($paragraph_6, 1) . '%</i></span></td>
            </tr> 
            <tr>
            <td style=" padding-right: 15px; width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . round($paragraph_6_18, 1) . '%</i></span></td>
            </tr>
            </table>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">7. Список работников, прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 195px">Структурное подразделение</th>
                    <th style="width: 145px">Заключение</th>
                </tr>
                
        ';
            $html .= $html_patient;
            $html .= '</table>';

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8. Численность работников, не завершивших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_8 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>
        ';
            if ($html_patient_8 != '')
            {
                $html .= '     
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8.1. Список работников, не завершивших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
        ';
                $html .= $html_patient_8;
                $html .= '</table>';
            }

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9. Численность работников, не прошедших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_9 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table> ';
            if ($html_patient_9 != '')
            {
                $html .= '  
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9.1. Список работников, не прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
            ';
                $html .= $html_patient_9;
                $html .= '</table>';
            }
            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">10. Список работников, у которых выявлены медицинские противопоказания к работе и рекомендована экспертиза профпригодности: ';
            if (!empty($html_patient_new_10))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 350px">Заключение</th>
                </tr>
        ';
                $html .= $html_patient_new_10;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }

            $paragraph_10_v_int = (int)$paragraph_10_v;
            $paragraph_10_v_22_int = (int)$paragraph_10_v_22;
            $v = $paragraph_10_v_int+$paragraph_10_v_22_int;
            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">11. Сводная таблица по результатам периодического медицинского осмотра:</div>
            
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 550px">Результаты периодического медицинского осмотра (обследования)</th>
                    <th style="width: 55px">Всего</th>
                </tr>
                <tr>
                    <td>Численность работников, не имеющих медицинских противопоказаний к работе</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_no_v . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, имеющих медицинские противопоказания к работе </td>
                    <td align="center"><span style="color: blue;"><i>' . $v . '</i></span></td>
                </tr> 
                <tr>
                    <td>Численность работников, нуждающихся в проведении дополнительного обследовании (заключение не дано)</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_42  . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в амбулаторном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_amb . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в стационарном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_str . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в санаторно-курортном лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_san . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в диспансерном наблюдении </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_dis . '</i></span></td>
                </tr>
            </table>  
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">12. Список лиц с установленным предварительным диагнозом профессионального заболевания: ';
            if (!empty($html_patient_123123_12))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 80px">Структурное подразделение</th>
                    <th style="width: 75px">Профессия</th>
                    <th style="width: 145px">Вредные и опасные производственные факторы</th>
                </tr> ';
                $html .= $html_patient_123123_12;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }

            $html .= '    
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">13. Перечень впервые установленных хронических соматических заболеваний: ';
            if (!empty($html_patient_13_new))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                     <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">Код МКБ-10</th>
                    <th style="width: 350px">Количество</th>
                </tr>
            ';
                $html .= $html_patient_13_new;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }

            $html .= '    
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">14. Перечень впервые установленных профессиональных заболеваний с указанием класса заболеваний по МКБ: ';
            if (!empty($html_patient_13_new_vp))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                     <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">Код МКБ-10</th>
                    <th style="width: 350px">Количество</th>
                </tr>
            ';
                $html .= $html_patient_13_new_vp;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">15. Перечень впервые установленных инфекционных заболеваний (отравлений), связанных с условиями труда: <span style="color: blue;"><i>нет. </i></span></div>';

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">16. Список лиц, подлежащих санаторно-курортному лечению: ';
            if (!empty($html_patient_12))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 85px">Код МКБ-10</th>
                </tr>
        ';
                $html .= $html_patient_12;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет.</i></span></div>';
            }
            $html .= ' 
            <div style="margin-top: 8px; page-break-after: avoid; page-break-inside: avoid;">
            <div style="font-size: 11px; margin-right: -30px;">17. Рекомендации работодателю: осуществляють в полном объеме  санаторно-профилактические и оздоровительные мероприятия в соответствии с законодательством Российской Федерации.</div>
          
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">18. Сводная таблица (прилагается).</div>
           
        ';
        }
        else{
            $html = '
             <!--<hr align="right" style="width: -1px">-->
            <br>
            <br>
            <table style="margin-top: -80px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="center"  style="width: 380px;" >'.$settings_name.'<br>
                    
                    '.$settings_address.'<br>
                    
                    '.$settings_licenses.'  
                    </td>
                
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td   style="width: 250px;" >
                    Председатель (зам. председателя) врачебной комиссии <br>'.$settings_short_name.'<br>' . $organisation->VK_chairman . '<br><br><hr><br></td>
                 </tr>
            </table>';
            $html .= '   
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px;">Код ОГРН</td>';
            for ($i = 0; $i < strlen($settings_ogrn_code); $i++)
            {
                $html .= '<td style=" border: 1px solid #000000; padding: 5px;">' . $settings_ogrn_code[$i] . '</td>';
            }
            $html .= '
            </tr>
            </table>
            <div style="margin-top: 15px; font-size: 14px; margin-right: -30px;" align="center"><b>ЗАКЛЮЧИТЕЛЬНЫЙ АКТ<br>ПО РЕЗУЛЬТАТАМ ПЕРИОДИЧЕСКОГО МЕДИЦИНСКОГО ОСМОТРА<br>(ОБСЛЕДОВАНИЯ)</b></div>
            <div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от ' . $today . ' г.</i></span></b></div>
            <!--<div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от 19.11.2020 г.</i></span></b></div>-->
           
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">По результатам проведенного периодического медицинского осмотра (обследования) работников: <span style="color: blue;"><i>' . $organisation->title . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">за <span style="color: blue;"><i>' . $ear . ' г.</i></span> составлен заключительный акт при участии:</div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Председатель <br>врачебной комиссии <span style="color: blue;"><i>' . $organisation->VK_chairman . ' ' . $organisation->VK_chairman_position . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">Представитель <br>работодателя  <span style="color: blue;"><i>' . $organisation->fio_position_commissioner . ', ' . $organisation->position_commissioner . '</i></span></div>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">1. Общая численность работников организации (предприятия), цеха: </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_1_1_new . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_1_2_new . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_2222 . '</i></span></td>
            </tr>
            </table>
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">2. Численность работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_2_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_2_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_3333 . '</i></span></td>
            </tr>
            </table>    
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">3. Численность работников, занятых на работах, при выполнении которых обязательно проведение периодических медицинских осмотров (обследований): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $paragraph_3_1_new . '</td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $paragraph_3_2_new . '</td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_4444 . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">4. Численность работников, подлежащих периодическому медицинскому осмотру:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $organisation_patient . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $organisation_patient_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $str_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $field_nov_tr . '</i></span></td>
            </tr>
            </table> 
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">5. Численность работников, прошедших периодический медицинский осмотр (обследования): </div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $percent_6 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $percent_6_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $conclusion_5_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>  
            

            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">6. Процент охвата работников периодическим медицинским осмотром:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px; width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . round($paragraph_6, 1) . '%</i></span></td>
            </tr> 
            <tr>
            <td style=" padding-right: 15px; width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . round($paragraph_6_18, 1) . '%</i></span></td>
            </tr>
            </table>
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">7. Список работников, прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 195px">Структурное подразделение</th>
                    <th style="width: 145px">Заключение</th>
                </tr>
                
        ';
            $html .= $html_patient;
            $html .= '</table>';

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8. Численность работников, не завершивших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_8 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_8_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table>
        ';
            if ($html_patient_8 != '')
            {
                $html .= '     
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">8.1. Список работников, не завершивших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
        ';
                $html .= $html_patient_8;
                $html .= '</table>';
            }

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9. Численность работников, не прошедших периодический медицинский осмотр:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i>' . $patient_9 . '</i></span></td>
            </tr> 
            <tr>
            <td style=" width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_j . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>' . $patient_9_18 . '</i></span></td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</i></span></td>
            </tr>
            </table> ';
            if ($html_patient_9 != '')
            {
                $html .= '  
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">9.1. Список работников, не прошедших периодический медицинский осмотр:</div>
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 350px">ФИО сотрудника</th>
                </tr>
            ';
                $html .= $html_patient_9;
                $html .= '</table>';
            }
            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">10. Список работников, у которых выявлены медицинские противопоказания к работе и рекомендована экспертиза профпригодности: ';
            if (!empty($html_patient_new_10))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 350px">Заключение</th>
                </tr>
        ';
                $html .= $html_patient_new_10;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }
            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">11. Сводная таблица по результатам периодического медицинского осмотра:</div>
            
            <table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 550px">Результаты периодического медицинского осмотра (обследования)</th>
                    <th style="width: 55px">Всего</th>
                </tr>
                <tr>
                    <td>Численность работников, не имеющих медицинских противопоказаний к работе</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_no_v . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, имеющих временные медицинские противопоказания к работе </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v . '</i></span></td>
                </tr> 
                <tr>
                    <td>Численность работников, имеющих постоянные медицинские противопоказания к работе </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_22 . '</i></span></td>
                </tr> 
                <tr>
                    <td>Численность работников, нуждающихся в проведении дополнительного обследовании (заключение не дано)</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_42  . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в амбулаторном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_amb . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в стационарном обследовании и лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_str . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в санаторно-курортном лечении</td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_san . '</i></span></td>
                </tr>
                <tr>
                    <td>Численность работников, нуждающихся в диспансерном наблюдении </td>
                    <td align="center"><span style="color: blue;"><i>' . $paragraph_10_v_dis . '</i></span></td>
                </tr>
            </table>  
            
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">12. Список лиц с установленным предварительным диагнозом профессионального заболевания: ';
            if (!empty($html_patient_123123_12))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 30px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 80px">Структурное подразделение</th>
                    <th style="width: 75px">Профессия</th>
                    <th style="width: 145px">Вредные и опасные производственные факторы</th>
                </tr> ';
                $html .= $html_patient_123123_12;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }

            $html .= '    
           <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">13. Перечень впервые установленных хронических соматических заболеваний: ';
            if (!empty($html_patient_13_new))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                     <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">Код МКБ-10</th>
                    <th style="width: 350px">Количество</th>
                </tr>
            ';
                $html .= $html_patient_13_new;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет. </i></span></div>';
            }

            /*$html .= '
                <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">14. Список лиц, находящихся в группе риска по развитию профессионального заболевания: ';
            if (!empty($html_patient_11))
            {
                $html .= '</div><table border="1"
                style="border-collapse: collapse; /*убираем пустые промежутки между ячейками
                border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px
                font-size: 11px;
                 margin-right: -30px;
                ">
                    <tr>
                         <th style="width: 25px">№ п/п</th>
                        <th style="width: 250px">ФИО сотрудника</th>
                        <th style="width: 25px">Пол</th>
                        <th style="width: 65px">Дата рождения</th>
                    </tr>
            ';
                $html .= $html_patient_11;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет</i></span></div>';
            }*/

            $html .= ' 
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">14. Список лиц, подлежащих санаторно-курортному лечению: ';
            if (!empty($html_patient_12))
            {
                $html .= '</div><table border="1"
            style="border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
            font-size: 11px;
             margin-right: -30px;
            ">
                <tr>
                    <th style="width: 25px">№ п/п</th>
                    <th style="width: 250px">ФИО сотрудника</th>
                    <th style="width: 25px">Пол</th>
                    <th style="width: 65px">Дата рождения</th>
                    <th style="width: 85px">Код МКБ-10</th>
                </tr>
        ';
                $html .= $html_patient_12;
                $html .= '</table>';
            }
            else
            {
                $html .= '<span style="color: blue;"><i>нет.</i></span></div>';
            }
            $html .= ' 
            <div style="margin-top: 8px; page-break-after: avoid; page-break-inside: avoid;">
            <div style="font-size: 11px; margin-right: -30px;">15. Рекомендации работодателю: осуществляють в полном объеме  санаторно-профилактические и оздоровительные мероприятия в соответствии с законодательством Российской Федерации.</div>
          
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">16. Сводная таблица (прилагается).</div>
           
        ';
        }


        $html .= '
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">С заключительным актом ознакомлен:</div>
           
            <br>  
            <table style="margin-top: -0px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i>' . $organisation->fio_position_commissioner . ', ' . $organisation->position_commissioner . '</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td style="width: 120px;" >
                        <hr>
                    </td>
                    <td align="center"  style="width: 70px;" >М.П. </td>
                   
                 </tr> 
                 <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i><br><br><br><br><br>Председатель (зам. председателя) врачебной комиссии: <br>' . $organisation->VK_chairman . ', ' . $organisation->VK_chairman_position . '</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td style="width: 120px;" >
                        <br><br><br><br><hr>
                    </td>
                    <td align="center"  style="width: 70px;" ><br><br><br><br>М.П.</td>
                   
                 </tr>
                
            </table>
            </div>
        ';
        $grgrgr = $organisation->title;
        $string = str_replace('"', "", $grgrgr);
        str_replace("'", "", $grgrgr);


        require_once __DIR__ . '/../../vendor/autoload.php';

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        //$mpdf->WriteHTML('Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...');
        //$mpdf->defaultfooterline = 1;
        //$mpdf->setFooter('<div>Разработчик: "ФБУН Новосибирский НИИ гигиены Роспотребнадзора"</div>'); //номер страницы {PAGENO}
        $mpdf->Output('Заключительный акт ' . $string . '.pdf', 'D'); //D - скачает файл!
        //$mpdf->Output('MyPDF.pdf', 'I'); //I - откроет в томже окне файл!
        //$mpdf->Output('MyPDF123123.pdf', 'F'); //F - гененирует ссылку на файл и сохранить его на сервере путь сохр backend\web!

        if ($status == '1')
        {
            //сохраняем акт на сервере!!!
            $mpdf2 = new Mpdf();
            $mpdf2->WriteHTML($html);
            $mpdf2->Output('act/Заключительный ' . $string . ' ' . date("d.m.Y") . '.pdf', 'F');

            $organisation->status_print = '1';
            $organisation->save(false);

            /*Yii::$app->session->setFlash('success', "Данные успешно обновлены");*/
            /*return $this->redirect('index');*/

        }
    }
    //Эксель
    public function actionExportDay2P($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        ini_set("pcre.backtrack_limit", "5000000");
        require_once Yii::$app->basePath . '\Excel\PHPExcel.php';
        require_once Yii::$app->basePath . '\Excel\PHPExcel\IOFactory.php';

        $model2 = new Organization();
        $model = new ListPatients();
        $model_cof = new CoefficientCalculation();

        $organizations = Organization::find()->where(['id' => $id])->one();
        $list_factors = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->all();
        $price_type = $organizations->price_type;
        $document = new \PHPExcel();
        //подгружаем готовый шаблон !!!!
        //$document = \PHPExcel_IOFactory::load('../web/images/generator.xlsx');
        ob_start();

        $num = 2;
        $sheet = $document->getActiveSheet();
        $sheet->setCellValue("B1", 'Данные по организации: ' . $organizations->title);
        //$sheet->setCellValue('A' . $numRow, $array_org[$i][2]);



        $col_num = 0; //количество человек всего
        $col_num2 = 1; //количество человек всего
        $col_num3 = 1; //количество человек всего
        $col_num_mj_do_40 = 0; //количество мужчин до 40
        $col_num_mj_posle_40 = 0; //количество мужчин после 40
        $col_num_jen_do_40 = 0; //количество женщин до 40
        $col_num_jen_posle_40 = 0; //количество женщин после 40

        $sheet->getColumnDimension('A')->setWidth("10");
        $sheet->getColumnDimension('B')->setWidth("60");
        $sheet->getColumnDimension('C')->setWidth("40");

        if($organizations->order_type != '1'){
            foreach ($list_factors as $list_factor)
            {

                $name_col = ['chemical_factor', 'biological_factor', 'physical_factor', 'hard_work'];
                $errors = 10;
                if ($errors != 0)
                {
                    //обнуляю массивы для каждого пациента
                    $name_doctors_id = []; //это финальные доктора по всем пациентам!!!
                    $name_research_id = []; //это финальные обследования по одному пациенту!!!
                    //приложение 1 получаю всех докторов и обследования
                    for ($j = 0; $j < 4; $j++)
                    {
                        for ($i = 0; $i < 10; $i++)
                        {
                            $name = $name_col[$j] . ($i + 1);
                            $check = $model[$name];
                            //все доктора!
                            $doctors = \common\models\FactorsDoctors::find()->where(['factors_id' => $list_factor->$name])->all();
                            foreach ($doctors as $doctor)
                            {
                                $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                                array_push($name_doctors_id, $name_doctors);
                            }
                            //все обследования!
                            $researchs = \common\models\FactorsResearch::find()->where(['factors_id' => $list_factor->$name])->all();
                            foreach ($researchs as $research)
                            {
                                $name_research = $model2->get_research($research['research_id']);
                                //добовляем в массив обследования пациента ()
                                array_push($name_research_id, $name_research);
                            }
                        }
                    }
                    //приложение 2
                    $name = 'type_work';
                    $doctors = \common\models\KindWorkDoctors::find()->where(['kind_work_id' => $list_factor->$name])->all();
                    foreach ($doctors as $doctor)
                    {
                        $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                        //echo $name_doctors;
                        array_push($name_doctors_id, $name_doctors);
                    }
                    $researchs = \common\models\KindWorkResearch::find()->where(['kind_work_id' => $list_factor->$name])->all();
                    foreach ($researchs as $research)
                    {
                        $name_research = $model2->get_research($research['research_id']);
                        array_push($name_research_id, $name_research);
                        if ($list_factor->type_patient == '0')
                        {
                            $name_research2 = $model2->get_research2($research['research_id']);
                            array_push($name_research_id, $name_research2);
                        }
                    }
                    for ($i = 0; $i < 6; $i++)
                    {
                        $name = 'gets_2_fields_' . ($i + 1);
                        $doctors = \common\models\KindWorkDoctors::find()->where(['kind_work_id' => $list_factor->$name])->all();
                        foreach ($doctors as $doctor)
                        {
                            $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                            //echo $name_doctors;
                            array_push($name_doctors_id, $name_doctors);
                        }

                        $researchs = \common\models\KindWorkResearch::find()->where(['kind_work_id' => $list_factor->$name])->all();
                        foreach ($researchs as $research)
                        {
                            $name_research = $model2->get_research($research['research_id']);
                            array_push($name_research_id, $name_research);
                            if ($list_factor->type_patient == '0')
                            {
                                $name_research2 = $model2->get_research2($research['research_id']);
                                array_push($name_research_id, $name_research2);
                            }
                        }
                    }
                    if ($list_factor->sex == 0)
                    {
                        //мужчина
                        $doctors_basic = \common\models\Doctors::find()->where(['start_status' => 0, 'sex' => 0])->all();
                    }
                    else
                    {
                        //женщина
                        $doctors_basic = \common\models\Doctors::find()->where(['start_status' => 0])->all();
                    }
                    foreach ($doctors_basic as $doctor_basic)
                    {
                        array_push($name_doctors_id, $doctor_basic['name']);
                    }
                    //основные исследования
                    $age = $model2->calculate_age($list_factor->date_birth);
                    //echo $age;
                    if ($list_factor['sex'] == 0)
                    {
                        if ($age >= 40)
                        {
                            //мужчины после 40
                            $researchs_basic = \common\models\ResearchBasic::find()->where(['start_status' => 0])->all();
                            $col_num_mj_posle_40++;
                        }
                        else
                        {
                            //мужчины до 40
                            $researchs_basic = \common\models\ResearchBasic::find()->where(['start_status' => 0, 'sex' => 0])->all();
                            $col_num_mj_do_40++;
                        }
                    }
                    else
                    {
                        if ($age >= 40)
                        {
                            //женщины после 40
                            $researchs_basic = \common\models\ResearchBasic::find()->all();
                            $col_num_jen_posle_40++;
                        }
                        else
                        {
                            //женщины до 40
                            $researchs_basic = \common\models\ResearchBasic::find()->where(['age' => 0])->all();
                            $col_num_jen_do_40++;
                        }
                    }
                    foreach ($researchs_basic as $research_basic)
                    {
                        $name_research = $model2->get_research_basic($research_basic['id']);
                        array_push($name_research_id, $name_research);
                    }

                    if ($age >= 40)
                    {
                        //проверка есть ли вообще аккомодация, если уже есть то не выполняем
                        //проверка, есть значение в массиве !
                        if (($key = array_search('объем аккомодации для лиц моложе 40 лет', $name_research_id)) !== FALSE)
                        {
                            unset($name_research_id[$key]);
                        }
                    }
                    else
                    {
                        //проверка есть ли вообще аккомодация, если уже есть то не выполняем
                        if (($key2 = array_search('объем аккомодации', $name_research_id)) !== FALSE)
                        {
                            //print_r('fefefe');
                            //проверка, есть значение в массиве !
                            if (($key = array_search('объем аккомодации для лиц моложе 40 лет', $name_research_id)) !== FALSE)
                            {
                                unset($name_research_id[$key]);
                            }
                        }
                    }
                    if (($key = array_search('УЗИ органов малого таза', $name_research_id)) !== FALSE)
                    {
                        unset($name_research_id[$key]);
                    }
                    //обследования
                    $name_research_id = array_diff($name_research_id, array(null));
                    $research_id = array_unique($name_research_id);
                    //$name_research_id_fin = array_merge($name_research_id_fin, $research_id);


                    //доктора
                    $name_doctors_id = array_diff($name_doctors_id, array(null));
                    $doctors_id = array_unique($name_doctors_id);
                    // $name_doctors_id_fin = array_merge($name_doctors_id_fin, $doctors_id);

                    $col_num++;

                    $research_id_33 = array_unique($research_id); //получаю уникальные занчения массива!
                    $research_id_2 = array_values($research_id_33); //обнуляю ключи

                    $doctors_id_33 = array_unique($name_doctors_id); //получаю уникальные занчения массива!
                    $doctors_id_2 = array_values($doctors_id_33); //обнуляю ключи

                    //print_r($research_id_2);
                    //print_r('<br><br><br>');
                    //print_r($doctors_id_2);
                    //print_r('<br><br><br>');
                    $num234234 = 1;
                    $sum = 0;
                    $num++;
                    $sheet->setCellValue('A' . $num, '№');
                    $sheet->setCellValue('B' . $num, 'Врач, обследования');
                    $sheet->setCellValue('C' . $num, 'Цена');

                    $sheet->setCellValue('A' . $num, $col_num3);
                    $sheet->setCellValue('B' . $num, $list_factor->fio);
                    $sheet->setCellValue('C' . $num, ' П.п. ' . $model->factors_list_patients($list_factor->id));
                    $num++;

                    //заносим обследование
                    for ($k = 0; $k < count($doctors_id_2); $k++)
                    {
                        //находим код мед услуги цену и тд
                        $doctors_id = \common\models\Doctors::find()->where(['name' => $doctors_id_2[$k]])->one();
                        $doctors = \common\models\DoctorsBasic::find()->where(['name' => $doctors_id->id])->andWhere(['price_type_id' => $price_type])->one();
                        //echo $researchs->price;

                        $sheet->setCellValue('A' . $num, $num234234);
                        $sheet->setCellValue('B' . $num, $doctors_id->name);
                        $sheet->setCellValue('C' . $num, $doctors->price);
                        $num++;

                        $sum = $sum + $doctors->price;
                        $num234234++;
                    }
                    for ($k = 0; $k < count($research_id_2); $k++)
                    {
                        //находим код мед услуги цену и тд
                        $researchs_id = \common\models\Research::find()->where(['name' => $research_id_2[$k]])->one();
                        $researchs = \common\models\PriceResearch::find()->where(['research_id' => $researchs_id->id])->andWhere(['price_type_id' => $price_type])->one();
                        //echo $researchs->price;
                        if ($researchs_id->name != 'скиаскопия')
                        {
                            if ($researchs_id->name != 'исследование бинокулярного зрения')
                            {

                                $sheet->setCellValue('A' . $num, $num234234);
                                $sheet->setCellValue('B' . $num, $researchs_id->name);
                                $sheet->setCellValue('C' . $num, $researchs->price);
                                $num++;

                                $num234234++;
                                $sum = $sum + $researchs->price;
                            }
                        }
                    }

                    $sheet->setCellValue('B' . $num, 'Итого');
                    $sheet->setCellValue('C' . $num, $sum);
                    $num++;
                    $num++;

                }

                $col_num2++;
                $col_num3++;
            }
        }
        else{
            foreach ($list_factors as $list_factor)
            {

                $name_col = ['chemical_factor', 'biological_factor', 'physical_factor', 'hard_work', 'aerosols'];
                $errors = 10;

                if ($errors != 0)
                {
                    //обнуляю массивы для каждого пациента
                    $name_doctors_id = []; //это финальные доктора по всем пациентам!!!
                    $name_research_id = []; //это финальные обследования по одному пациенту!!!
                    //приложение 1 получаю всех докторов и обследования
                    for ($j = 0; $j < 5; $j++)
                    {
                        if($name_col[$j] == 'chemical_factor'){
                            for ($i = 0; $i < 24; $i++)
                            {
                                $name = $name_col[$j] . ($i + 1);
                                $check = $model[$name];
                                //все доктора!
                                $doctors = \common\models\KindWorkDoctors2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                                foreach ($doctors as $doctor)
                                {
                                    $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                                    array_push($name_doctors_id, $name_doctors);
                                }
                                //все обследования!
                                $researchs = \common\models\KindWorkResearch2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                                foreach ($researchs as $research)
                                {
                                    $name_research = $model2->get_research($research['research_id']);
                                    //добовляем в массив обследования пациента ()
                                    array_push($name_research_id, $name_research);
                                }
                            }
                        }
                        else{
                            for ($i = 0; $i < 18; $i++)
                            {
                                $name = $name_col[$j] . ($i + 1);
                                $check = $model[$name];
                                //все доктора!
                                $doctors = \common\models\KindWorkDoctors2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                                foreach ($doctors as $doctor)
                                {
                                    $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                                    array_push($name_doctors_id, $name_doctors);
                                }
                                //все обследования!
                                $researchs = \common\models\KindWorkResearch2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                                foreach ($researchs as $research)
                                {
                                    $name_research = $model2->get_research($research['research_id']);
                                    //добовляем в массив обследования пациента ()
                                    array_push($name_research_id, $name_research);
                                }
                            }
                        }
                    }
                    //приложение 2
                    $name = 'type_work';
                    $doctors = \common\models\KindWorkDoctors2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                    foreach ($doctors as $doctor)
                    {
                        $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                        //echo $name_doctors;
                        array_push($name_doctors_id, $name_doctors);
                    }
                    $researchs = \common\models\KindWorkResearch2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                    foreach ($researchs as $research)
                    {
                        $name_research = $model2->get_research($research['research_id']);
                        array_push($name_research_id, $name_research);
                        if ($list_factor->type_patient == '0')
                        {
                            $name_research2 = $model2->get_research2($research['research_id']);
                            array_push($name_research_id, $name_research2);
                        }
                    }
                    for ($i = 0; $i < 11; $i++)
                    {
                        $name = 'gets_2_fields_' . ($i + 1);
                        $doctors = \common\models\KindWorkDoctors2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                        foreach ($doctors as $doctor)
                        {
                            $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                            //echo $name_doctors;
                            array_push($name_doctors_id, $name_doctors);
                        }

                        $researchs = \common\models\KindWorkResearch2::find()->where(['kind_work_id' => $list_factor->$name])->all();
                        foreach ($researchs as $research)
                        {
                            $name_research = $model2->get_research($research['research_id']);
                            array_push($name_research_id, $name_research);
                            if ($list_factor->type_patient == '0')
                            {
                                $name_research2 = $model2->get_research2($research['research_id']);
                                array_push($name_research_id, $name_research2);
                            }
                        }
                    }
                    if ($list_factor->sex == 0)
                    {
                        //мужчина
                        $doctors_basics = \common\models\Doctors::find()->where('start_status != :id', ['id'=>1])->andWhere(['sex' => 0])->all();
                    }
                    else
                    {
                        //женщина
                        $doctors_basics = \common\models\Doctors::find()->where('start_status != :id', ['id'=>1])->all();
                    }
                    foreach ($doctors_basics as $doctor_basic)
                    {
                        array_push($name_doctors_id, $doctor_basic['name']);
                    }
                    //основные исследования
                    $age = $model2->calculate_age($list_factor->date_birth);
                    //echo $age;
                    if ($list_factor['sex'] == 0)
                    {
                        if ($age >= 40)
                        {
                            //мужчины после 40
                            $researchs_basic = \common\models\ResearchBasic::find()->where(['start_status' => 0])->all();
                            $col_num_mj_posle_40++;
                        }
                        else
                        {
                            //мужчины до 40
                            $researchs_basic = \common\models\ResearchBasic::find()->where(['start_status' => 0, 'sex' => 0])->all();
                            $col_num_mj_do_40++;
                        }
                    }
                    else
                    {
                        if ($age >= 40)
                        {
                            //женщины после 40
                            $researchs_basic = \common\models\ResearchBasic::find()->all();
                            $col_num_jen_posle_40++;
                        }
                        else
                        {
                            //женщины до 40
                            $researchs_basic = \common\models\ResearchBasic::find()->where(['age' => 0])->all();
                            $col_num_jen_do_40++;
                        }
                    }
                    foreach ($researchs_basic as $research_basic)
                    {
                        $name_research = $model2->get_research_basic($research_basic['id']);
                        array_push($name_research_id, $name_research);
                    }
                    //обследования
                    $name_research_id = array_diff($name_research_id, array(null));
                    $research_id = array_unique($name_research_id);
                    //$name_research_id_fin = array_merge($name_research_id_fin, $research_id);

                    if ($age >= 40)
                    {
                        //проверка есть ли вообще аккомодация, если уже есть то не выполняем
                        //проверка, есть значение в массиве !
                        if (($key = array_search('объем аккомодации для лиц моложе 40 лет', $name_research_id)) !== FALSE)
                        {
                            unset($name_research_id[$key]);
                        }

                        if (($key2 = array_search('Исследование уровня холестерина в крови', $name_research_id)) !== FALSE)
                        {
                            unset($name_research_id[$key2]);
                        }
                    }
                    else
                    {
                        //проверка есть ли вообще аккомодация, если уже есть то не выполняем
                        if (($key2 = array_search('объем аккомодации', $name_research_id)) !== FALSE)
                        {
                            //print_r('fefefe');
                            //проверка, есть значение в массиве !
                            if (($key = array_search('объем аккомодации для лиц моложе 40 лет', $name_research_id)) !== FALSE)
                            {
                                unset($name_research_id[$key]);
                            }
                        }

                        //пункт 19.1 проверка на меньше 40 лет !!!
                        if (($key2 = array_search('холестерин', $name_research_id)) !== FALSE)
                        {
                            //print_r('fefefe');
                            //проверка, есть значение в массиве !
                            if (($key3 = array_search('Исследование уровня холестерина в крови', $name_research_id)) !== FALSE)
                            {
                                unset($name_research_id[$key3]);
                            }
                        }

                        if (($key4 = array_search('Велоэргометрия для лиц старше 40 лет', $name_research_id)) !== FALSE)
                        {
                            unset($name_research_id[$key4]);
                        }

                        if (($key5 = array_search('УЗИ предстательной железы для лиц старше 40 лет', $name_research_id)) !== FALSE)
                        {
                            unset($name_research_id[$key5]);
                        }

                    }

                    //обследования
                    $name_research_id = array_diff($name_research_id, array(null));
                    $research_id = array_unique($name_research_id);
                    //$name_research_id_fin = array_merge($name_research_id_fin, $research_id);


                    //доктора
                    $name_doctors_id = array_diff($name_doctors_id, array(null));
                    $doctors_id = array_unique($name_doctors_id);
                    // $name_doctors_id_fin = array_merge($name_doctors_id_fin, $doctors_id);

                    $col_num++;

                    $research_id_33 = array_unique($research_id); //получаю уникальные занчения массива!
                    $research_id_2 = array_values($research_id_33); //обнуляю ключи

                    $doctors_id_33 = array_unique($name_doctors_id); //получаю уникальные занчения массива!
                    $doctors_id_2 = array_values($doctors_id_33); //обнуляю ключи

                    //print_r($research_id_2);
                    //print_r('<br><br><br>');
                    //print_r($doctors_id_2);
                    //print_r('<br><br><br>');
                    //exit();
                    $num234234 = 1;
                    $sum = 0;
                    $num++;
                    $sheet->setCellValue('A' . $num, '№');
                    $sheet->setCellValue('B' . $num, 'Врач, обследования');
                    $sheet->setCellValue('C' . $num, 'Цена');

                    $sheet->setCellValue('A' . $num, $col_num3);
                    $sheet->setCellValue('B' . $num, $list_factor->fio);
                    $sheet->setCellValue('C' . $num, ' П.п. ' . $model->factors_list_patients($list_factor->id));
                    $num++;

                    //заносим обследование
                    for ($k = 0; $k < count($doctors_id_2); $k++)
                    {
                        //находим код мед услуги цену и тд
                        $doctors_id = \common\models\Doctors::find()->where(['name' => $doctors_id_2[$k]])->one();
                        $doctors = \common\models\DoctorsBasic::find()->where(['name' => $doctors_id->id])->andWhere(['price_type_id' => $price_type])->one();
                        //echo $researchs->price;

                        $sheet->setCellValue('A' . $num, $num234234);
                        $sheet->setCellValue('B' . $num, $doctors_id->name);
                        $sheet->setCellValue('C' . $num, $doctors->price);
                        $num++;

                        $sum = $sum + $doctors->price;
                        $num234234++;
                    }
                    for ($k = 0; $k < count($research_id_2); $k++)
                    {
                        //находим код мед услуги цену и тд
                        $researchs_id = \common\models\Research::find()->where(['name' => $research_id_2[$k]])->one();
                        $researchs = \common\models\PriceResearch::find()->where(['research_id' => $researchs_id->id])->andWhere(['price_type_id' => $price_type])->one();
                        //echo $researchs->price;
                        if ($researchs_id->name != 'скиаскопия')
                        {
                            if ($researchs_id->name != 'исследование бинокулярного зрения')
                            {

                                $sheet->setCellValue('A' . $num, $num234234);
                                $sheet->setCellValue('B' . $num, $researchs_id->name);
                                $sheet->setCellValue('C' . $num, $researchs->price);
                                $num++;

                                $num234234++;
                                $sum = $sum + $researchs->price;
                            }
                        }
                    }

                    $sheet->setCellValue('B' . $num, 'Итого');
                    $sheet->setCellValue('C' . $num, $sum);
                    $num++;
                    $num++;

                }

                $col_num2++;
                $col_num3++;
            }
        }



        $filename = 'Индивидуальный расчет стоимости по организации' . '.xlsx';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    //Ворд
    public function actionExporting($id)
    {
        ini_set("pcre.backtrack_limit", "5000000");
        require_once Yii::$app->basePath . '\Word\PhpWord.php';
        require_once Yii::$app->basePath . '\Word\PhpWord\IOFactory.php';

        $document = new \PhpWord();

        $section = $document->addSection();
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );
        $objWriter = \PHPWord_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    //ексель для плановой стоимости
    public function actionExportPlan($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        ini_set("pcre.backtrack_limit", "5000000");
        require_once Yii::$app->basePath . '\Excel\PHPExcel.php';
        require_once Yii::$app->basePath . '\Excel\PHPExcel\IOFactory.php';

        $model2 = new Organization();
        $model = new ListPatients();
        $model_cof = new CoefficientCalculation();

        $organizations = Organization::find()->where(['id' => $id])->one();
        $list_factors = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->all();
        $price_type = $organizations->price_type;
        $coefficient = CoefficientCalculation::find()->where(['organization_id' => $id])->one();

        if (empty($coefficient))
        {
            $coefficient_z = 0;
        }
        else
        {
            $coefficient_z = $coefficient->coefficient;
        }

        $document = new \PHPExcel();
        //подгружаем готовый шаблон !!!!
        //$document = \PHPExcel_IOFactory::load('../web/images/generator.xlsx');
        ob_start();

        $num_str = 6;
        $sheet = $document->getActiveSheet();

        //$sheet->setCellValue('A' . $num_str, $array_org[$i][2]);

        $name_doctors_id_fin = []; //это финальные доктора по всем пациентам!!!
        $name_research_id_fin = []; //это финальные обследования по всем пациентам!!!

        $name_col = ['chemical_factor', 'biological_factor', 'physical_factor', 'hard_work'];

        $col_num = 0; //количество человек всего
        $col_num_mj_do_40 = 0; //количество мужчин до 40
        $col_num_mj_posle_40 = 0; //количество мужчин после 40
        $col_num_jen_do_40 = 0; //количество женщин до 40
        $col_num_jen_posle_40 = 0; //количество женщин после 40
        foreach ($list_factors as $list_factor)
        {
            //обнуляю массивы для каждого пациента
            $name_doctors_id = []; //это финальные доктора по всем пациентам!!!
            $name_research_id = []; //это финальные обследования по одному пациенту!!!
            //приложение 1 получаю всех докторов и обследования
            for ($j = 0; $j < 4; $j++)
            {
                for ($i = 0; $i < 10; $i++)
                {
                    $name = $name_col[$j] . ($i + 1);
                    $check = $model[$name];
                    //все доктора!
                    $doctors = \common\models\FactorsDoctors::find()->where(['factors_id' => $list_factor->$name])->all();
                    foreach ($doctors as $doctor)
                    {
                        $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                        array_push($name_doctors_id, $name_doctors);
                    }
                    //все обследования!
                    $researchs = \common\models\FactorsResearch::find()->where(['factors_id' => $list_factor->$name])->all();
                    foreach ($researchs as $research)
                    {
                        $name_research = $model2->get_research($research['research_id']);
                        //добовляем в массив обследования пациента ()
                        array_push($name_research_id, $name_research);
                    }
                }
            }
            //приложение 2
            $name = 'type_work';
            $doctors = \common\models\KindWorkDoctors::find()->where(['kind_work_id' => $list_factor->$name])->all();
            foreach ($doctors as $doctor)
            {
                $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                //echo $name_doctors;
                array_push($name_doctors_id, $name_doctors);
            }
            $researchs = \common\models\KindWorkResearch::find()->where(['kind_work_id' => $list_factor->$name])->all();
            foreach ($researchs as $research)
            {
                $name_research = $model2->get_research($research['research_id']);
                array_push($name_research_id, $name_research);
                if ($list_factor->type_patient == '0')
                {
                    $name_research2 = $model2->get_research2($research['research_id']);
                    array_push($name_research_id, $name_research2);
                }
            }
            for ($i = 0; $i < 6; $i++)
            {
                $name = 'gets_2_fields_' . ($i + 1);
                $doctors = \common\models\KindWorkDoctors::find()->where(['kind_work_id' => $list_factor->$name])->all();
                foreach ($doctors as $doctor)
                {
                    $name_doctors = $model2->get_doctor($doctor['doctors_id']);
                    //echo $name_doctors;
                    array_push($name_doctors_id, $name_doctors);
                }

                $researchs = \common\models\KindWorkResearch::find()->where(['kind_work_id' => $list_factor->$name])->all();
                foreach ($researchs as $research)
                {
                    $name_research = $model2->get_research($research['research_id']);
                    array_push($name_research_id, $name_research);
                    if ($list_factor->type_patient == '0')
                    {
                        $name_research2 = $model2->get_research2($research['research_id']);
                        array_push($name_research_id, $name_research2);
                    }
                }
            }
            if ($list_factor->sex == 0)
            {
                //мужчина
                $doctors_basic = \common\models\Doctors::find()->where(['start_status' => 0, 'sex' => 0])->all();
            }
            else
            {
                //женщина
                $doctors_basic = \common\models\Doctors::find()->where(['start_status' => 0])->all();
            }
            foreach ($doctors_basic as $doctor_basic)
            {
                array_push($name_doctors_id, $doctor_basic['name']);
            }
            //основные исследования
            $age = $model2->calculate_age($list_factor->date_birth);
            //echo $age;
            if ($list_factor['sex'] == 0)
            {
                if ($age >= 40)
                {
                    //мужчины после 40
                    $researchs_basic = \common\models\ResearchBasic::find()->where(['start_status' => 0])->all();
                    $col_num_mj_posle_40++;
                }
                else
                {
//мужчины до 40
                    $researchs_basic = \common\models\ResearchBasic::find()->where(['start_status' => 0, 'sex' => 0])->all();
                    $col_num_mj_do_40++;
                }
            }
            else
            {
                if ($age >= 40)
                {
//женщины после 40
                    $researchs_basic = \common\models\ResearchBasic::find()->all();
                    $col_num_jen_posle_40++;
                }
                else
                {
//женщины до 40
                    $researchs_basic = \common\models\ResearchBasic::find()->where(['age' => 0])->all();
                    $col_num_jen_do_40++;
                }
            }
            foreach ($researchs_basic as $research_basic)
            {
                $name_research = $model2->get_research_basic($research_basic['id']);
                array_push($name_research_id, $name_research);
            }
//обследования
            $research_id = array_unique($name_research_id);

            //норм удалился осталось возраст проверить ! это в приказаЕ 4 пункт !
            if ($age >= 40)
            {
                //проверка есть ли вообще аккомодация, если уже есть то не выполняем
                //проверка, есть значение в массиве !
                if (($key = array_search('объем аккомодации для лиц моложе 40 лет', $research_id)) !== FALSE)
                {
                    unset($research_id[$key]);
                }
            }
            else
            {
                //проверка есть ли вообще аккомодация, если уже есть то не выполняем
                if (($key2 = array_search('объем аккомодации', $research_id)) !== FALSE)
                {
                    print_r('fefefe');
                    //проверка, есть значение в массиве !
                    if (($key = array_search('объем аккомодации для лиц моложе 40 лет', $research_id)) !== FALSE)
                    {
                        unset($research_id[$key]);
                    }
                }
            }
            $name_research_id_fin = array_diff($name_research_id_fin, array(null));
            $name_research_id_fin = array_merge($name_research_id_fin, $research_id);
//доктора
            $doctors_id = array_unique($name_doctors_id);
            $name_doctors_id_fin = array_merge($name_doctors_id_fin, $doctors_id);
            $col_num++;
        }
        $name_research_id_fin = array_diff($name_research_id_fin, array(null));
        $research_id_33 = array_unique($name_research_id_fin); //получаю уникальные занчения массива!
        $research_id_2 = array_values($research_id_33); //обнуляю ключи
        $arr2222r = array_count_values($name_research_id_fin); //считаем количество одинаковых обследований
//print_r($name_research_id_fin);
//echo '<br>';

        $doctors_id_33 = array_unique($name_doctors_id_fin); //получаю уникальные занчения массива!
        $doctors_id_2 = array_values($doctors_id_33); //обнуляю ключи
        $arr_doctors = array_count_values($name_doctors_id_fin); //считаем количество одинаковых обследований
//print_r($name_doctors_id_fin);
//echo '<br>';

        $num = 1;
        $sum = 0;
        $sum_new = 0;
        $sheet->setCellValue('C1', 'Спецификация');
        $sheet->setCellValue('A2', 'К проведению периодического медицинского осмотра работников ' . $organizations->title);
        $sheet->setCellValue('A4', '
        Колличество человек всего - ' . $col_num . '
        Колличество мужчин до 40 - ' . $col_num_mj_do_40 . '
        Колличество мужчин после 40 - ' . $col_num_mj_posle_40 . '
        Колличество женщин до 40 - ' . $col_num_jen_do_40 . '
        Колличество женщин после 40 - ' . $col_num_jen_posle_40);


        $sheet->setCellValue('A' . $num_str, '№');
        $sheet->setCellValue('B' . $num_str, 'Код мед услуги');
        $sheet->setCellValue('C' . $num_str, 'Наименование работ');
        $sheet->setCellValue('D' . $num_str, 'Количество человек');
        $sheet->setCellValue('E' . $num_str, 'Цена');
        $sheet->setCellValue('F' . $num_str, 'Стоимость');
        $num_str++;
        //заносим обследование
        for ($k = 0; $k < count($doctors_id_2); $k++)
        {
            //находим код мед услуги цену и тд
            $doctors_id = \common\models\Doctors::find()->where(['name' => $doctors_id_2[$k]])->one();
            $doctors = \common\models\DoctorsBasic::find()->where(['name' => $doctors_id->id])->andWhere(['price_type_id' => $price_type])->one();
            //echo $researchs->price;
            $sheet->setCellValue('A' . $num_str, $num);
            $sheet->setCellValue('B' . $num_str, $doctors->kod);
            $sheet->setCellValue('C' . $num_str, $doctors_id->name);
            $sheet->setCellValue('D' . $num_str, $arr_doctors[$doctors_id->name]);
            if ($coefficient_z == 0)
            {
                $sheet->setCellValue('E' . $num_str, $doctors->price);
            }
            else
            {
                $sheet->setCellValue('E' . $num_str, ceil(($doctors->price) * $coefficient_z));
            }
            if ($coefficient_z == 0)
            {
                $sheet->setCellValue('F' . $num_str, $arr_doctors[$doctors_id->name] * $doctors->price);
                $sum = $sum + ($arr_doctors[$doctors_id->name] * $doctors->price);

            }
            else
            {
                $sheet->setCellValue('F' . $num_str, ceil(($arr_doctors[$doctors_id->name] * $doctors->price) * $coefficient_z));
                $sum = $sum + (($arr_doctors[$doctors_id->name] * $doctors->price) * $coefficient_z);
            }
            $num_str++;
            $num++;
        }

        for ($k = 0; $k < count($research_id_2); $k++)
        {
            //находим код мед услуги цену и тд
            $researchs_id = \common\models\Research::find()->where(['name' => $research_id_2[$k]])->one();
            $researchs = \common\models\PriceResearch::find()->where(['research_id' => $researchs_id->id])->andWhere(['price_type_id' => $price_type])->one();
            //echo $researchs->price;
            if ($researchs_id->name != 'скиаскопия')
            {
                if ($researchs_id->name != 'исследование бинокулярного зрения')
                {
                    $sheet->setCellValue('A' . $num_str, $num);
                    $sheet->setCellValue('B' . $num_str, $researchs->kod);
                    $sheet->setCellValue('C' . $num_str, $researchs_id->name);

                    $sheet->setCellValue('D' . $num_str, $arr2222r[$researchs_id->name]);
                    if ($coefficient_z == 0)
                    {
                        $sheet->setCellValue('E' . $num_str, $researchs->price);
                    }
                    else
                    {
                        $sheet->setCellValue('E' . $num_str, ceil(($researchs->price) * $coefficient_z));
                    }
                    if ($coefficient_z == 0)
                    {
                        $sheet->setCellValue('F' . $num_str, $arr2222r[$researchs_id->name] * $researchs->price);
                        $sum = $sum + ($arr2222r[$researchs_id->name] * $researchs->price);

                    }
                    else
                    {
                        $sheet->setCellValue('F' . $num_str, ceil(($arr2222r[$researchs_id->name] * $researchs->price) * $coefficient_z));
                        $sum = $sum + (($arr2222r[$researchs_id->name] * $researchs->price) * $coefficient_z);
                    }
                    $num_str++;
                    $num++;
                }
            }
        }
        $researchs_zac = 150;




        $sheet->setCellValue('A' . $num_str, $num);
        $sheet->setCellValue('B' . $num_str, '');
        $sheet->setCellValue('C' . $num_str, 'Оформление заключения по результатам периодического мед. осмотра председателем ВК');
        $sheet->setCellValue('D' . $num_str, $col_num);
        $sheet->setCellValue('E' . $num_str, $researchs_zac);
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('F' . $num_str, $col_num * $researchs_zac);
            $sum = $sum + ($col_num * $researchs_zac);

        }
        else
        {
            $sheet->setCellValue('F' . $num_str, ceil(($col_num * $researchs_zac) * $coefficient_z));
            $sum = $sum + (($col_num * $researchs_zac) * $coefficient_z);
        }
        $num_str++;
        $num++;

        $researchs_med = 50;
        $sheet->setCellValue('A' . $num_str, $num);
        $sheet->setCellValue('B' . $num_str, '');
        $sheet->setCellValue('C' . $num_str, 'Составление заключительного акта по периодическому мед. осмотру');
        $sheet->setCellValue('D' . $num_str, $col_num);
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('E' . $num_str, $researchs_med);
        }
        else
        {
            $sheet->setCellValue('E' . $num_str, ceil(($researchs_med) * $coefficient_z));
        }
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('F' . $num_str, $col_num * $researchs_med);
            $sum = $sum + ($col_num * $researchs_med);

        }
        else
        {
            $sheet->setCellValue('F' . $num_str, ceil(($col_num * $researchs_med) * $coefficient_z));
            $sum = $sum + (($col_num * $researchs_med) * $coefficient_z);
        }
        $num_str++;
        $num++;

        $researchs_vp = 100;
        $sheet->setCellValue('A' . $num_str, $num);
        $sheet->setCellValue('B' . $num_str, '');
        $sheet->setCellValue('C' . $num_str, 'Оформление дел (оформление личной медицинской книжки)');
        $sheet->setCellValue('D' . $num_str, $col_num);
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('E' . $num_str, $researchs_vp);
        }
        else
        {
            $sheet->setCellValue('E' . $num_str, ceil(($researchs_vp) * $coefficient_z));
        }
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('F' . $num_str, $col_num * $researchs_vp);
            $sum = $sum + ($col_num * $researchs_vp);

        }
        else
        {
            $sheet->setCellValue('F' . $num_str, ceil(($col_num * $researchs_vp) * $coefficient_z));
            $sum = $sum + (($col_num * $researchs_vp) * $coefficient_z);
        }
        $num_str++;
        $num++;

        $researchs_mk = 100;
        $sheet->setCellValue('A' . $num_str, $num);
        $sheet->setCellValue('B' . $num_str, '');
        $sheet->setCellValue('C' . $num_str, 'Выписка из мед. карты');
        $sheet->setCellValue('D' . $num_str, $col_num);
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('E' . $num_str, $researchs_mk);
        }
        else
        {
            $sheet->setCellValue('E' . $num_str, ceil(($researchs_mk) * $coefficient_z));
        }
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('F' . $num_str, $col_num * $researchs_mk);
            $sum = $sum + ($col_num * $researchs_mk);

        }
        else
        {
            $sheet->setCellValue('F' . $num_str, ceil(($col_num * $researchs_mk) * $coefficient_z));
            $sum = $sum + (($col_num * $researchs_mk) * $coefficient_z);
        }
        $num_str++;
        $num++;

        $sheet->setCellValue('E' . $num_str, 'Итого');
        if ($coefficient_z == 0)
        {
            $sheet->setCellValue('F' . $num_str, $sum);


        }
        else
        {
            $sheet->setCellValue('F' . $num_str, ceil($sum));

        }
        $num_str++;
        $num_str++;

        $sheet->setCellValue('A' . $num_str, 'Заказчик:');
        $sheet->setCellValue('C' . $num_str, 'Исполнитель:');
        $num_str++;
        $sheet->setCellValue('C' . $num_str, 'Директор ФБУН "Новосибирский НИИ гигиены" Роспотребнадзора');
        $num_str++;
        $num_str++;
        $num_str++;
        $sheet->setCellValue('C' . $num_str, '______________________ И. И. Новикова');
        $sheet->getColumnDimension('A')->setWidth("10");
        $sheet->getColumnDimension('B')->setWidth("25");
        $sheet->getColumnDimension('C')->setWidth("60");
        $sheet->getColumnDimension('D')->setWidth("15");
        $sheet->getColumnDimension('E')->setWidth("15");
        $sheet->getColumnDimension('F')->setWidth("15");


        $filename = 'generator_' . date('Y_m_d_H_i', time()) . '.xlsx';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    //пдф таблица
    public function actionListAgreements($id)
    {
        //print_r($id);
        //exit();
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        ini_set("pcre.backtrack_limit", "5000000");
        require_once __DIR__ . '/../../vendor/autoload.php';

        $model = new ListPatients();
        $organizations = Organization::findOne($id);

        $html = '<br><br>';
        $html2 = '';

        $html .= '
            <!--<div align="right" style=" margin-top: -60px; margin-left: -30px; font-size: 10px;"></div>-->
            <div align="right" style=" margin-top: -60px; margin-left: -30px; font-size: 12px;">Приложение </div>
            <div align="center" style=" font-size: 12px;">Сводная таблица работников</div>
             <table style=" margin-left: -30px; margin-right: -30px; margin-bottom: -30px; border-collapse: collapse; font-size: 9px; border: 1px solid #000000;">
               <!--<thead>-->
                    <tr>
                        <th style="width: 25px; border: 1px solid #000000;">№ п/п</th>
                        <th style="width: 100px border: 1px solid #000000;;">Ф.И.О.</th>
                        <th style="width: 25px; border: 1px solid #000000;">Пол</th>
                        <th style="width: 40px; border: 1px solid #000000;">Дата рождения</th>
                        <th style="width: 35px; border: 1px solid #000000;">Возраст</th>
                        <th style="width: 35px; border: 1px solid #000000;">Профессия</th>
                        <th style="width: 225px; border: 1px solid #000000;">Вредные и (или) опасные вещества и производственные факторы, виды работ</th>
                        <th style="width: 35px; border: 1px solid #000000;">Стаж</th>
                        <th style="width: 35px; border: 1px solid #000000;">Профпригоден к работе</th>
                        <th style="width: 35px; border: 1px solid #000000;">Временно профнепригоден к работе</th>
                        <th style="width: 35px; border: 1px solid #000000;">Постоянно профнепригоден к работе</th>
                        <th style="width: 35px; border: 1px solid #000000;">Заключение не дано</th>
                        <th style="width: 35px; border: 1px solid #000000;">Нуждается в амбулаторном обследовании и лечении</th>
                        <th style="width: 35px; border: 1px solid #000000;">Нуждается в стационарном обследовании и лечении</th>
                        <th style="width: 35px; border: 1px solid #000000;">Нуждается в санаторно-курортном лечении</th>
                        <th style="width: 35px; border: 1px solid #000000;">Нуждается в диспансерном наблюдении</th>
                        <th style="width: 35px; border: 1px solid #000000;">Впервые установленные хронические соматические заболевания</th>
                    </tr>
              <!-- </thead>-->
             <!--<tbody>-->
        ';

        $model2 = new Organization();
        $organisation = Organization::findOne($id);
        $organisation_patient = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->count();
        $organisation_patient_alls = ListPatients::find()->where(['organization_id' => $id])->andWhere(['print_status' => '0'])->orderBy(['fio' => SORT_ASC])->all();
        $organisation_patient_j = ListPatients::find()->where(['organization_id' => $id, 'sex' => '1'])->andWhere(['print_status' => '0'])->count();
        $modellist = new ListPatients();
        $today = date("d.m.Y");
        //Заношу список пациентов в переменнуую и считаю все для таблиц в одном форыче!
        $num = 1;

        $temporary_num = 0;
        $constant_num = 0;
        $conclusion_given_num = 0;
        $outpatient_examination_num = 0;
        $hospital_examination_num = 0;
        $health_examination_num = 0;
        $dispensary_examination_num = 0;

        //Для таблицы с описанием противопоказаний к работе
        $num2 = 1;
        $fit_work_fio = '';
        $fit_work_sex = '';
        $fit_work_date = '';
        foreach ($organisation_patient_alls as $organisation_patient_all)
        {
            //расчет для пунктов 5-6
            //$conclusion = ConclusionIndivid::find()->where(['user_id'=>$organisation_patient_all->id])->one();
            $fails = Therapist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();
            $med_ifo11 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $organisation_patient_all->id, 'finish' => 0])->one();

            $fit_work = '';
            $temporary = '';
            $constant = '';
            $conclusion_given = '';
            $hospital_examination = '-';
            $outpatient_examination = '-';
            $health_examination = '-';
            $dispensary_examination = '-';
            if (
                $fails->contraindications != '' &&
                $med_ifo2->contraindications != '' &&
                $med_ifo4->contraindications != '' &&
                $med_ifo3->contraindications != '' &&
                $med_ifo6->contraindications != '' &&
                $med_ifo5->contraindications != '' &&
                $med_ifo7->contraindications != '' &&
                $med_ifo8->contraindications != '' &&
                $med_ifo9->contraindications != '' &&
                $med_ifo10->contraindications != '' &&
                $med_ifo11->contraindications != ''
            )
            {
               /* if ($fails->contraindications == '1' || $fails->contraindications == '3' || $fails->contraindications == '4' ||
                    $med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' || $med_ifo2->contraindications == '4' ||
                    $med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' || $med_ifo4->contraindications == '4' ||
                    $med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' || $med_ifo3->contraindications == '4' ||
                    $med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' || $med_ifo6->contraindications == '4' ||
                    $med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' || $med_ifo5->contraindications == '4' ||
                    $med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' || $med_ifo7->contraindications == '4' ||
                    $med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' || $med_ifo8->contraindications == '4' ||
                    $med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' || $med_ifo9->contraindications == '4' ||
                    $med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4' ||
                    $med_ifo11->contraindications == '1' || $med_ifo11->contraindications == '3' || $med_ifo11->contraindications == '4'
                ) */
                if (
                    $med_ifo11->contraindications == '1' || $med_ifo11->contraindications == '3' || $med_ifo11->contraindications == '4'
                )
                {
                    if($organisation_patient_all->order_type == '1'){
                        $fit_work = 'Не годен(а); 
                            ' . $modellist->translation_bd_down_pril1_print_v2_kind_work2($organisation_patient_all->id)
                            . ' ';
                    }
                    else{
                        $fit_work = 'Не годен(а); 
                            ' . $modellist->translation_bd_down_pril1_print_v2($organisation_patient_all->id)
                            . ' ' . $modellist->translation_bd_down_pril2_print_v2($organisation_patient_all->id)
                            . ' ';
                    }
                    if($med_ifo11->professional_aptitude == 1){
                        $fit_work_fio = $organisation_patient_all->fio;
                        $fit_work_sex = $organisation_patient_all->sex ;
                        $fit_work_date = $organisation_patient_all->date_birth;
                    }



                    //$fit_work = 'Не годен(а); ';
                    /*if ($fails->contraindications == '1' ||
                        $med_ifo2->contraindications == '1' ||
                        $med_ifo4->contraindications == '1' ||
                        $med_ifo3->contraindications == '1' ||
                        $med_ifo6->contraindications == '1' ||
                        $med_ifo5->contraindications == '1' ||
                        $med_ifo7->contraindications == '1' ||
                        $med_ifo8->contraindications == '1' ||
                        $med_ifo9->contraindications == '1' ||
                        $med_ifo10->contraindications == '1' ||
                        $med_ifo11->contraindications == '1'
                    ) */
                    if (
                        $med_ifo11->contraindications == '1'
                    )
                    {
                        //$conclus_2 = '(временные)';
                        $temporary = '+';
                        $constant = '-';
                        $temporary_num++;

                    }
                    /*elseif (
                        $fails->contraindications == '3' ||     $fails->contraindications == '4' ||
                        $med_ifo2->contraindications == '3' ||  $med_ifo2->contraindications == '4' ||
                        $med_ifo4->contraindications == '3' ||  $med_ifo4->contraindications == '4' ||
                        $med_ifo3->contraindications == '3' ||  $med_ifo3->contraindications == '4' ||
                        $med_ifo6->contraindications == '3' ||  $med_ifo6->contraindications == '4' ||
                        $med_ifo5->contraindications == '3' ||  $med_ifo5->contraindications == '4' ||
                        $med_ifo7->contraindications == '3' ||  $med_ifo7->contraindications == '4' ||
                        $med_ifo8->contraindications == '3' ||  $med_ifo8->contraindications == '4' ||
                        $med_ifo9->contraindications == '3' ||  $med_ifo9->contraindications == '4' ||
                        $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4' ||
                        $med_ifo11->contraindications == '3' || $med_ifo11->contraindications == '4'
                    )*/
                    elseif (
                        $med_ifo11->contraindications == '3' || $med_ifo11->contraindications == '4'
                    )
                    {
                        $constant_num++;
                        $constant = '+';
                        $temporary = '-';
                        //$conclus_2 = '(постоянные)';
                        //$paragraph_10_v_22++;
                    }

                }/*
                elseif (
                    $fails->contraindications == '0' ||
                    $med_ifo2->contraindications == '0' ||
                    $med_ifo4->contraindications == '0' ||
                    $med_ifo3->contraindications == '0' ||
                    $med_ifo6->contraindications == '0' ||
                    $med_ifo5->contraindications == '0' ||
                    $med_ifo7->contraindications == '0' ||
                    $med_ifo8->contraindications == '0' ||
                    $med_ifo9->contraindications == '0' ||
                    $med_ifo10->contraindications == '0' ||
                    $med_ifo11->contraindications == '0'
                )*/
                elseif (
                    $med_ifo11->contraindications == '0'
                )
                {
                    $temporary = '-';
                    $constant = '-';
                    $fit_work = 'Годен(а)';
                }
                $conclusion_given = '-';
            }
            else
            {
                if (
                    $fails->contraindications != '' ||
                    $med_ifo2->contraindications != '' ||
                    $med_ifo4->contraindications != '' ||
                    $med_ifo3->contraindications != '' ||
                    $med_ifo6->contraindications != '' ||
                    $med_ifo5->contraindications != '' ||
                    $med_ifo7->contraindications != '' ||
                    $med_ifo8->contraindications != '' ||
                    $med_ifo9->contraindications != '' ||
                    $med_ifo10->contraindications != '' ||
                    $med_ifo11->contraindications != ''
                )
                {
                    $fit_work = '-';
                }
                else
                {
                    $fit_work = 'Медосмотр не проходил(а)';
                }
                $conclusion_given = '+';
                $conclusion_given_num++;
            }

            //Нуждается в амбулаторном обследовании и лечении
            if ($fails->therapist_r1 == '0' || $fails->therapist_pi_1 == '0' || $fails->therapist_zoda_1 == '0' ||
                $med_ifo2->neurologist1 == '0' || $med_ifo2->neurologist2 == '0' ||
                $med_ifo3->audiologist1 == '0' || $med_ifo3->audiologist2 == '0' ||
                $med_ifo6->psychiatrist1 == '0' ||
                $med_ifo5->narcology1 == '0' ||
                $med_ifo7->gynecologist1 == '0' ||
                $med_ifo8->surgeon1 == '0' ||
                $med_ifo9->dermatovenereologist1 == '0' ||
                $med_ifo10->dentist1 == '0'
            )
            {

                //$hospital_examination = 'Нуждается';
                $new_arr2 = [];
                //Это для МКБ!!!!

                $fild_arr12 = [
                    'mkb_repeated1',
                    'mkb_repeated2',
                    'mkb_repeated3',
                ];

                $prof = [
                    'prof_diagnosis_1',
                    'prof_diagnosis_2',
                    'prof_diagnosis_3',
                ];

                //МКБ Терапевт //для пункта 14
                if ($fails->therapist_r1 == '0' || $fails->therapist_pi_1 == '0' || $fails->therapist_zoda_1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($fails->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($fails->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($fails->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo2->neurologist1 == '0' || $med_ifo2->neurologist2 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                /*if (!empty($med_ifo4))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }*/
                if ($med_ifo3->audiologist1 == '0' || $med_ifo3->audiologist2 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo3->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo3->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo6->psychiatrist1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo6->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo6->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo5->narcology1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo5->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo5->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo7->gynecologist1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo7->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo7->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo8->surgeon1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo8->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo8->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo9->dermatovenereologist1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo9->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo9->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo10->dentist1 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo10->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo10->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }

                $new_arr2 = array_unique($new_arr2);
                $research_id_222 = array_values($new_arr2); //обнуляю ключи
                $html_patient_13_new33333 = '';
                for ($k = 0; $k < count($research_id_222); $k++)
                {
                    $html_patient_13_new33333 .= $research_id_222[$k] . '; ';
                }

                $outpatient_examination2 = 'Нуждается ';
                $outpatient_examination = $outpatient_examination2 . $html_patient_13_new33333;


                //$outpatient_examination = 'Нуждается';

                $outpatient_examination_num++;
            }
            //Нуждается в стационарном обследовании и лечении
            if ($fails->therapist_r3 == '0' || $fails->therapist_pi_2 == '0' || $fails->therapist_zoda_2 == '0' ||
                $med_ifo2->neurologist3 == '0' || $med_ifo2->neurologist4 == '0' ||
                $med_ifo3->audiologist3 == '0' || $med_ifo3->audiologist4 == '0' ||
                $med_ifo6->psychiatrist3 == '0' ||
                $med_ifo5->narcology3 == '0' ||
                $med_ifo7->gynecologist3 == '0' ||
                $med_ifo8->surgeon3 == '0' ||
                $med_ifo9->dermatovenereologist3 == '0' ||
                $med_ifo10->dentist3 == '0'
            )
            {
                //$hospital_examination = 'Нуждается';
                $new_arr2 = [];
                //Это для МКБ!!!!

                $fild_arr12 = [
                    'mkb_repeated1',
                    'mkb_repeated2',
                    'mkb_repeated3',
                ];

                $prof = [
                    'prof_diagnosis_1',
                    'prof_diagnosis_2',
                    'prof_diagnosis_3',
                ];

                //МКБ Терапевт //для пункта 14
                if ($fails->therapist_r3 == '0' || $fails->therapist_pi_2 == '0' || $fails->therapist_zoda_2 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($fails->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($fails->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($fails->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo2->neurologist3 == '0' || $med_ifo2->neurologist4 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                /*if (!empty($med_ifo4))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }*/
                if ($med_ifo3->audiologist3 == '0' || $med_ifo3->audiologist4 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo3->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo3->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo6->psychiatrist3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo6->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo6->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo5->narcology3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo5->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo5->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo7->gynecologist3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo7->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo7->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo8->surgeon3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo8->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo8->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo9->dermatovenereologist3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo9->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo9->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo10->dentist3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo10->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo10->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }

                $new_arr2 = array_unique($new_arr2);
                $research_id_222 = array_values($new_arr2); //обнуляю ключи
                $html_patient_13_new33333 = '';
                for ($k = 0; $k < count($research_id_222); $k++)
                {
                    $html_patient_13_new33333 .= $research_id_222[$k] . '; ';
                }

                $hospital_examination2 = 'Нуждается ';
                $hospital_examination = $hospital_examination2 . $html_patient_13_new33333;

                $hospital_examination_num++;
            }
            //Нуждается в санаторно-курортном лечении
            if (
                $fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0' ||
                $med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0' ||
                $med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0' ||
                $med_ifo6->psychiatrist5 == '0' ||
                $med_ifo5->narcology5 == '0' ||
                $med_ifo7->gynecologist5 == '0' ||
                $med_ifo8->surgeon5 == '0' ||
                $med_ifo9->dermatovenereologist5 == '0' ||
                $med_ifo10->dentist5 == '0'
            )
            {
                //Это для МКБ!!!!
                $new_arr2 = [];
                $fild_arr12 = [
                    'mkb_repeated1',
                    'mkb_repeated2',
                    'mkb_repeated3',
                ];

                $prof = [
                    'prof_diagnosis_1',
                    'prof_diagnosis_2',
                    'prof_diagnosis_3',
                ];

                //МКБ Терапевт //для пункта 14
                if ($fails->therapist_r5 == '0' || $fails->therapist_pi_3 == '0' || $fails->therapist_zoda_3 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($fails->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($fails->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($fails->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo2->neurologist5 == '0' || $med_ifo2->neurologist6 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                /*if (!empty($med_ifo4))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }*/
                if ($med_ifo3->audiologist5 == '0' || $med_ifo3->audiologist6 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo3->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo3->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo6->psychiatrist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo6->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo6->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo5->narcology5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo5->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo5->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo7->gynecologist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo7->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo7->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo8->surgeon5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo8->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo8->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo9->dermatovenereologist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo9->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo9->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo10->dentist5 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo10->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo10->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }

                $new_arr2 = array_unique($new_arr2);
                $research_id_222 = array_values($new_arr2); //обнуляю ключи
                $html_patient_13_new33333 = '';
                for ($k = 0; $k < count($research_id_222); $k++)
                {
                    $html_patient_13_new33333 .= $research_id_222[$k] . '; ';
                }

                $health_examination2 = 'Нуждается ';
                $health_examination = $health_examination2 . $html_patient_13_new33333;

                $health_examination_num++;
            }
            //Нуждается в диспансерном наблюдении
            if ($fails->therapist_r7 == '0' || $fails->therapist_pi_4 == '0' || $fails->therapist_zoda_4 == '0' ||
                $med_ifo2->neurologist7 == '0' || $med_ifo2->neurologist8 == '0' ||
                $med_ifo3->audiologist7 == '0' || $med_ifo3->audiologist8 == '0' ||
                $med_ifo6->psychiatrist7 == '0' ||
                $med_ifo5->narcology7 == '0' ||
                $med_ifo7->gynecologist7 == '0' ||
                $med_ifo8->surgeon7 == '0' ||
                $med_ifo9->dermatovenereologist7 == '0' ||
                $med_ifo10->dentist7 == '0'
            )
            {

                //$hospital_examination = 'Нуждается';
                $new_arr2 = [];
                //Это для МКБ!!!!

                $fild_arr12 = [
                    'mkb_repeated1',
                    'mkb_repeated2',
                    'mkb_repeated3',
                ];

                $prof = [
                    'prof_diagnosis_1',
                    'prof_diagnosis_2',
                    'prof_diagnosis_3',
                ];

                //МКБ Терапевт //для пункта 14
                if ($fails->therapist_r7 == '0' || $fails->therapist_pi_4 == '0' || $fails->therapist_zoda_4 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($fails->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($fails->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($fails->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo2->neurologist7 == '0' || $med_ifo2->neurologist8 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                /*if (!empty($med_ifo4))
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo2->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo2->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }*/
                if ($med_ifo3->audiologist7 == '0' || $med_ifo3->audiologist8 == '0' )
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo3->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo3->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo6->psychiatrist7 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo6->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo6->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo5->narcology7 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo5->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo5->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo7->gynecologist7 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo7->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo7->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo8->surgeon7 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo8->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo8->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo9->dermatovenereologist7 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo9->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo9->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }
                if ($med_ifo10->dentist7 == '0')
                {
                    for ($i = 0; $i <= count($fild_arr12); $i++)
                    {
                        $name_factor = $fild_arr12[$i];
                        $prof_fac = $prof[$i];
                        if (!empty($med_ifo10->$name_factor))
                        {
                            $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                            $str = $factor_svs->diagnosis_code;
                            $str2 = strstr($str, '-', true);
                            if ($med_ifo10->diagnosis_repeated_field == '1')
                            {
                                $new_arr_vp2[] = $str2;
                                $new_arr2[] = $str2;
                            }
                        }
                    }
                }

                $new_arr2 = array_unique($new_arr2);
                $research_id_222 = array_values($new_arr2); //обнуляю ключи
                $html_patient_13_new33333 = '';
                for ($k = 0; $k < count($research_id_222); $k++)
                {
                    $html_patient_13_new33333 .= $research_id_222[$k] . '; ';
                }

                $dispensary_examination2 = 'Нуждается ';
                $dispensary_examination = $dispensary_examination2 . $html_patient_13_new33333;


                //$dispensary_examination = 'Нуждается';
                $dispensary_examination_num++;
            }
            $new_arr = [];
            $fild_arr12 = [
                'mkb1',
                'mkb2',
                'mkb3',
            ];
            $prof = [
                'prof_diagnosis_1',
                'prof_diagnosis_2',
                'prof_diagnosis_3',
            ];
            //МКБ Терапевт //для пункта 13
            if (!empty($fails))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {
                    $name_factor = $fild_arr12[$i];
                    $prof_fac = $prof[$i];
                    if (!empty($fails->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($fails->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($fails->diagnosis_primary_field == '1')
                        {
                            if ($fails->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }

                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo2))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {
                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo2->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo2->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo2->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo2->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo4))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo4->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo4->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo4->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo4->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo3))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo3->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo3->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo3->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo3->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo6))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo6->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo6->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo6->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo6->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo5))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo5->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo5->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo5->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo5->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo7))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo7->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo7->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo7->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo7->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo8))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo8->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo8->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo8->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo8->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo9))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo9->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo9->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo9->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo9->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }
            if (!empty($med_ifo10))
            {
                for ($i = 0; $i <= count($fild_arr12); $i++)
                {

                    $prof_fac = $prof[$i];
                    $name_factor = $fild_arr12[$i];
                    if (!empty($med_ifo10->$name_factor))
                    {
                        $factor_svs = Mkb10::findOne($med_ifo10->$name_factor);
                        $str = $factor_svs->diagnosis_code;
                        $str2 = strstr($str, '-', true);
                        if ($med_ifo10->diagnosis_primary_field == '1')
                        {
                            if ($med_ifo10->$prof_fac == '0')
                            {
                                $new_arr_vp[] = $str2;
                            }
                            $new_arr[] = $str2;

                        }
                    }
                }
            }

            $new_arr = array_unique($new_arr);
            $research_id_2 = array_values($new_arr); //обнуляю ключи
            $html_patient_13_new = '';
            for ($k = 0; $k < count($research_id_2); $k++)
            {
                $html_patient_13_new .= $research_id_2[$k] . '; ';
            }

            $html .= '
                <tr>
                        <td align="center" style="width: 25px; border: 1px solid #000000;">' . $num . '</td>
                        <td style="width: 100px; border: 1px solid #000000;">' . $organisation_patient_all->fio . '</td>
                        <td align="center" style="width: 25px; border: 1px solid #000000;">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                        <td style="width: 40px; border: 1px solid #000000;">' . $organisation_patient_all->date_birth . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $model2->calculate_age($organisation_patient_all->date_birth) . '</td>
                        <td style="width: 145px; border: 1px solid #000000;">' . $organisation_patient_all->post_profession . '</td>
                        <td style="width: 35px; border: 1px solid #000000;">' . $modellist->factors_list_patients($organisation_patient_all->id) . '</td>
                        <td style="width: 35px; border: 1px solid #000000;">' . $organisation_patient_all->experience . '</td>
                        <td style="width: 35px; border: 1px solid #000000;">' . $fit_work . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $temporary . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $constant . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $conclusion_given . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $outpatient_examination . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $hospital_examination . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $health_examination . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $dispensary_examination . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000;">' . $html_patient_13_new . '</td>
                    </tr>
            ';
            if($med_ifo11->professional_aptitude == 1){
                $html2 .= '
                    <tr>
                            <td align="center" style="width: 25px; border: 1px solid #000000;">' . $num2 . '</td>
                            <td style="width: 100px; border: 1px solid #000000;">' . $organisation_patient_all->fio . '</td>
                            <td align="center" style="width: 25px; border: 1px solid #000000;">' . $modellist->get_sex2($organisation_patient_all->sex) . '</td>
                            <td style="width: 40px; border: 1px solid #000000;">' . $organisation_patient_all->date_birth . '</td>
                            <td style="width: 100px; border: 1px solid #000000;">' . $med_ifo11->date_conclusion . '</td>
                            <td style="width: 200px; border: 1px solid #000000;">' . $med_ifo11->date_professional_aptitude . '</td>
                    </tr>
                ';
                $num2++;
            }
            $num++;
        }

        //print_r($research_id_2);
        //exit();

        $html .= '
                <tr>
                        <td align="right" colspan="9" style="width: 25px; border: 1px solid #000000; font-size: 12px;">Итого</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $temporary_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $constant_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $conclusion_given_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $outpatient_examination_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $hospital_examination_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $health_examination_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">' . $dispensary_examination_num . '</td>
                        <td align="center" style="width: 35px; border: 1px solid #000000; font-size: 10px;">-</td>
                    </tr>
            ';
        $html .= '</table> <br>  <br>';
        if($html2 !== ''){
            $html .= '
             <div align="center" style=" font-size: 10px;">Список работников нуждающихся в экспертизе профпригодности</div>
             <table style=" margin-left: -30px; margin-right: -30px; margin-bottom: -30px; border-collapse: collapse; font-size: 10px; border: 1px solid #000000;">
               <!--<thead>-->
                    <tr>
                        <th style=" border: 1px solid #000000;">№ п/п</th>
                        <th style=" border: 1px solid #000000;;">Ф.И.О.</th>
                        <th style=" border: 1px solid #000000;">Пол</th>
                        <th style=" border: 1px solid #000000;">Дата рождения</th>
                        <th style=" border: 1px solid #000000;">Направлен на экспертизу профпригодности</th>
                        <th style=" border: 1px solid #000000;">Дата проведения экспертизы профпригодности по приказу Министерства здравоохранения РФ от 5 мая 2016 г. № 282н </th>
                    </tr>
        ';
            $html .= $html2;
            $html .= '</table> <p></p> ';
        }

        $html .= '
            <!--</tbody>-->
           
            <table style="margin-top: -0px; font-size: 13px; margin-right: -60px;">
                <tr>
                    <td align="left"  style="width: 650px;" >
                        Заказчик <br>_____________________ <span style="color: blue; margin-top: 40px;"><i>(' . $organisation->fio_position_commissioner . ')</i></span>
                    </td>
                    <td align="left"  style="width: 480px;" >
                        Исполнитель <br>_____________________ <span style="color: blue; margin-top: 40px;"><i>(' . $organizations->VK_chairman . ') </i></span>
                    </td>
                
                    <!--<td align="center"  style="width: 70px;" ></td>
                    
                    <td style="width: 120px;" >
                        <hr>
                    </td>
                    <td align="center"  style="width: 70px;" >М.П.</td>-->
                 </tr>
          </table>            
        ';
        $html .= '
          
        ';

        //$html .= '<p align="left" style=" font-size: 12px;">Подпись: _____________________ '.$organizations->VK_chairman.' '. $organizations->VK_chairman_position. '</p>';
        //$mpdf = new Mpdf(['orientation' => 'L', 'margin_left' => '8', 'margin_right' => '15', 'margin_top' => '8', 'margin_bottom' => '5']);
        $mpdf = new Mpdf(['orientation' => 'L', 'margin_left' => '12', 'margin_right' => '15', 'margin_bottom' => '5']);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Сводная таблица работников (Приложение 1).pdf', 'D'); //D - скачает файл!
    }

    protected function findModel($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (($model = Organization::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}

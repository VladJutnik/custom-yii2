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
                        'actions' => ['index', 'create', 'update', 'delete', 'onoff', 'exportk', 'export-excel'],
                        'allow' => true,
                        'roles' => ['admin', 'admin_organizations'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        //'roles' => ['@'], все зарегестрированные
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['user_organizations'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    ],
                    [
                        'actions' => ['view', 'search', 'search-municipality', 'view-madal'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                    return $this->redirect(Yii::$app->request->referrer);
                }
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
        {
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
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $model = new Organization();
        if ($this->request->isPost)
        {
            //print_r($this->request->post());
            //exit();
            if ($model->load($this->request->post()) && $model->save())
            {
                Yii::$app->session->setFlash('success', "Данные сохранены");
                return $this->redirect(['index']);
            }
        }
        else
        {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($this->request->isPost)
        {
            if ($model->load($this->request->post()) && $model->save())
            {
                Yii::$app->session->setFlash('success', "Данные успешно изменены");
                return $this->redirect(['index']);
            }
        }
        else
        {
            $model->loadDefaultValues();
        }

        return $this->render('create2', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    //отображить организацию в отчетахъ или нет переключатель
    public function actionOnoff($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->get()['status_veiws'];

        if ($status == 1)
        {
            $model->status_veiws = 1;
        }
        else
        {
            $model->status_veiws = $status;
        }

        if ($model->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Аяйкс для подгрузки регионов
    public function actionSearch($id)
    {
        if (Yii::$app->user->isGuest)
        {
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
        if (Yii::$app->user->isGuest)
        {
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
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $this->layout = false;

        $model = $this->findModel($id);

        return $this->render('view-madal', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    //ПДФ выгрузка отчета!
    public function actionExportk($id)
    {
        /* print_r($id);
         print_r('<br>');
         print_r($status);
         print_r('<br>');
         print_r($date_start);
         print_r('<br>');
         print_r($date_end);
         exit();*/
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        ini_set("pcre.backtrack_limit", "5000000");
        $model2 = new Organization();
        $organisation = Organization::findOne($id);


        $html = '
             <!--<hr align="right" style="width: -1px">-->
            <br>
            <br>
            <table style="margin-top: -80px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="center"  style="width: 380px;" >
                        ' . $organisation->title . '<br>
                    </td>
                    <td align="center"  style="width: 70px;" ></td>
                    <td style="width: 250px;" >
                        Руководитель организации
                    </td>
                 </tr>
            </table>
        ';
        $html .= '   
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style=" padding-right: 15px;">ИНН</td>
        ';
        $str = $organisation->inn;
        for ($i = 0; $i < strlen($str); $i++)
        {
            $html .= '<td style=" border: 1px solid #000000; padding: 5px;">' . $str[$i] . '</td>';
        }
        $html .= '
            </tr>
            </table>
            <div style="margin-top: 15px; font-size: 14px; margin-right: -30px;" align="center"><b>Документация по организации<b></div>
            <div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от  г.</i></span></b></div>
            <!--<div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;"><i>от 19.11.2020 г.</i></span></b></div>-->
           
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">По результатам проведенного периодического медицинского осмотра (обследования) работников: <span style="color: blue;"><i>' . $organisation->title . '</i></span></div>
                     
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">2. Численность работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда:</div>
            <table style="border-collapse: collapse; font-size: 11px;">
            <tr>
            <td style="width: 550px">всего, </td>
            <td style=" border: 1px solid #000000; "><span style="color: blue;"><i></td>
            </tr> 
            <tr>
            <td style="width: 350px">в том числе женщин</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i></td>
            </tr>
            <tr>
            <td style="width: 350px">работников в возрасте до 18 лет</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i>нет</td>
            </tr>
            <tr>
            <td style="width: 350px">работников, которым установлена стойкая степень утраты трудоспособности</td>
            <td style=" border: 1px solid #000000; width: 50px"><span style="color: blue;"><i></i></span></td>
            </tr>
            </table>  
        ';
        $html .= '
          
            <div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">С документом ознакомлен:</div>
           
            <br>  
            <table style="margin-top: -0px; font-size: 11px; margin-right: -60px;">
                <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i>ФИО: ____________________</i></span>
                    </td>
                
                    <td align="center"  style="width: 70px;" ></td>
                    
                    <td style="width: 120px;" >
                        <hr>
                    </td>
                    <td align="center"  style="width: 70px;" >М.П. </td>
                   
                 </tr> 
                 <tr>
                    <td align="left"  style="width: 380px;" >
                        <span style="color: blue;"><span style="color: blue;"><i><br><br><br><br><br>Председатель (зам. председателя) организации: <br>ФИО: ____________________</i></span>
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
        $title = $organisation->title;
        $string = str_replace('"', "", $title);
        //str_replace("'", "", $title);

        require_once __DIR__ . '/../../vendor/autoload.php';

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        //$mpdf->WriteHTML('Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...Some text...');
        //$mpdf->defaultfooterline = 1;
        //$mpdf->setFooter('<div>Разработчик: "ФБУН Новосибирский НИИ гигиены Роспотребнадзора"</div>'); //номер страницы {PAGENO}
        $mpdf->Output('Заключительный акт ' . $string . '.pdf', 'D'); //D - скачает файл!
        //$mpdf->Output('MyPDF.pdf', 'I'); //I - откроет в томже окне файл!
        //$mpdf->Output('MyPDF123123.pdf', 'F'); //F - гененирует ссылку на файл и сохранить его на сервере путь сохр backend\web!

        //сохраняем документ на сервере!!!
        $mpdf2 = new Mpdf();
        $mpdf2->WriteHTML($html);
        $mpdf2->Output('act/Документ ' . $string . ' ' . date("d.m.Y") . '.pdf', 'F');
        exit;
        /*$organisation->status_print = '1';
        $organisation->save(false);*/

        /*Yii::$app->session->setFlash('success', "Данные успешно обновлены");*/
        /*return $this->redirect('index');*/

    }

    //Эксель
    public function actionExportExcel($id)
    {
        if (Yii::$app->user->isGuest)
        {
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

    protected function findModel($id)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        if (($model = Organization::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}

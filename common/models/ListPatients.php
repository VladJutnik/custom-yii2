<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "list_patients".
 *
 * @property int $id ID
 * @property int $organization_id
 * @property string $fio ФИО
 * @property string $date_birth Дата рождения
 * @property int $type_patient Первичный - 0 / переолический - 1
 * @property int $working Поступающий на работу - 0 / работающий - 1
 * @property string $type_work Виды работы
 * @property string $experience Стаж работы
 * @property string $previous_profession Предшествующая профессия
 * @property string $experience_previous_profession Стаж Предшествующюх профессий
 * @property string $post_profession Должность профессия
 * @property string $hazard Вредность
 * @property string $date_employment Дата приема на работу
 * @property string $creat_at
 */
class ListPatients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_patients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organization_id', 'fio', 'date_birth', 'sex', 'address', 'city', 'street', 'house',


                'federal_district_id',
                'region_id',
                'municipality_id',

                'job',

                'post_profession',
                'street',
                'house',


            ], 'required'],
            [['organization_id', 'type_patient', 'working'], 'integer'],

            [['organization_id', 'card_number', 'data_p', 'fio', 'date_birth', 'sex', 'address', 'phone', 'type_patient', 'working', 'department', 'type_work', 'experience',
                'post_profession', 'hazard', 'date_employment', 'position_commissioner',
                'fio_position_commissioner',
                'profession1',
                'experience1',
                'profession2',
                'experience2',
                'profession3',

                'federal_district_id',
                'region_id',
                'municipality_id',
                'address_overall',
                'job',
                'zone',
                'flat',
                'experience_month1',
                'experience_month2',
                'experience_month3',
                'snils',

                'experience_class1',
                'experience_class2',
                'experience_class3',

                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',

                'chemical_factor7',
                'biological_factor7',
                'physical_factor7',
                'hard_work7',

                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',



                'chemical_factor6',
                'biological_factor6',
                'physical_factor6',
                'hard_work6',
                'status',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',

                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',
                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',


                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

                'aerosols1',
                'aerosols2',
                'aerosols3',
                'aerosols4',
                'aerosols5',
                'aerosols6',
                'aerosols7',
                'aerosols8',
                'aerosols9',
                'aerosols10',
                'aerosols11',
                'aerosols12',
                'aerosols13',
                'aerosols14',
                'aerosols15',
                'aerosols16',
                'aerosols17',
                'aerosols18',

                'finish',
                'order_type',
                'print_status',
                'old_id',

                'creat_at'], 'safe'],
            [['fio', 'type_work', 'experience', 'post_profession', 'hazard', 'date_employment'], 'string', 'max' => 500],
            [['date_birth'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Организация',
            'order_type' => 'Номер приказа',
            'card_number' => 'Номер карты',
            'data_p' => 'Дата приема',
            'fio' => 'Фамилия',
            'date_birth' => 'Дата рождения',
            'sex' => 'Пол',
            'address' => 'Место регистрации',
            'city' => 'Город',

            'street' => 'Улица',
            'house' => 'Дом',
            'zone' => 'Корпус',
            'flat' => 'Квартира',
            'phone' => 'Телефон',

            'experience_month1' => 'Квартира',
            'experience_month2' => 'Квартира',
            'experience_month3' => 'Квартира',

            'experience_class1' => 'Квартира',
            'experience_class2' => 'Квартира',
            'experience_class3' => 'Квартира',

            'type_patient' => 'Вид медицинского осмотра',
            'working' => 'Тип работающего',
            'department' => 'Цех, участок',
            'job' => 'Предшествующие профессии (работы), должность и стаж работы в них:',

            'type_work' => 'Вид работы, в которой работник освидетельствуется',
            'experience' => 'Стаж работы, в котором работник освидетельствуется (лет)',
            'profession1' => 'Предшествующая профессия',
            'experience1' => 'Стаж в предшествующей профессии',
            'profession2' => 'Предшествующая профессия',
            'experience2' => 'Стаж в предшествующей профессии',
            'profession3' => 'Предшествующая профессия',
            'experience3' => 'Стаж в предшествующей профессии',

            'post_profession' => 'Профессия (работа)',
            'hazard' => 'Класс условий труда',
            'date_employment' => 'Дата приема',
            'position_commissioner' => 'Должность уполномоченного представителя',
            'fio_position_commissioner' => 'ФИО уполномоченного представителя',
            'creat_at' => 'Creat At',
            'chemical_factor1' => 'Creat At',
            'chemical_factor2' => 'Creat At',
            'chemical_factor3' => 'Creat At',
            'chemical_factor4' => 'Creat At',
            'chemical_factor5' => 'Creat At',
            'biological_factor1' => 'Creat At',
            'biological_factor2' => 'Creat At',
            'biological_factor3' => 'Creat At',
            'biological_factor4' => 'Creat At',
            'biological_factor5' => 'Creat At',
            'physical_factor1' => 'Creat At',
            'physical_factor2' => 'Creat At',
            'physical_factor3' => 'Creat At',
            'physical_factor4' => 'Creat At',
            'physical_factor5' => 'Creat At',
            'hard_work1' => 'Creat At',
            'hard_work2' => 'Creat At',
            'hard_work3' => 'Creat At',
            'hard_work4' => 'Creat At',
            'hard_work5' => 'Creat At',

            'chemical_factor7' => 'Creat At',
            'biological_factor7' => 'Creat At',
            'physical_factor7' => 'Creat At',
            'hard_work7' => 'Creat At',

            'chemical_factor6' => 'Creat At',
            'biological_factor6' => 'Creat At',
            'physical_factor6' => 'Creat At',
            'hard_work6' => 'Creat At',

            'gets_2_fields_1' => 'Hard Work5',
            'gets_2_fields_2' => 'Hard Work5',
            'gets_2_fields_3' => 'Hard Work5',
            'gets_2_fields_4' => 'Hard Work5',
            'gets_2_fields_5' => 'Hard Work5',
            'gets_2_fields_6' => 'Hard Work5',

            'chemical_factor8' => 'Hard Work5',
            'chemical_factor9' => 'Hard Work5',
            'chemical_factor10' => 'Hard Work5',
            'biological_factor8' => 'Hard Work5',
            'biological_factor9' => 'Hard Work5',
            'biological_factor10' => 'Hard Work5',
            'physical_factor8' => 'Hard Work5',
            'physical_factor9' => 'Hard Work5',
            'physical_factor10' => 'Hard Work5',
            'hard_work8' => 'Hard Work5',
            'hard_work9' => 'Hard Work5',
            'hard_work10' => 'Hard Work5',

            'aerosols1' => 'Hard Work5',
            'aerosols2' => 'Hard Work5',
            'aerosols3' => 'Hard Work5',
            'aerosols4' => 'Hard Work5',
            'aerosols5' => 'Hard Work5',
            'aerosols6' => 'Hard Work5',
            'aerosols7' => 'Hard Work5',
            'aerosols8' => 'Hard Work5',
            'aerosols9' => 'Hard Work5',
            'aerosols10' => 'Hard Work5',

            'federal_district_id' => 'Федеральный округ',
            'region_id' => 'Регион',
            'municipality_id' => 'Муниципальное образование',
            'address_overall' => 'Адрес',
            'old_id' => '',
        ];
    }

    public function get_category($category_id)
    {
        $category = Organization::findOne($category_id);
        return $category;
    }

    public function get_type($id)
    {
        $category = Organization::findOne($id);
        return $category->title;
    }

    public function get_mkb1($id)
    {
        $category = Mkb10::findOne($id);
        return $category->diagnosis_code;
    }

    public function get_fio($id)
    {
        $fio = ListPatients::findOne($id);
        return $fio->fio;
    }

    public function get_organiz($id)
    {
        $organiz = ListPatients::findOne($id);
        $organiz_name = Organization::find()->where(['id' => $organiz->organization_id])->one();

        return $organiz_name->title;
    }

    public function get_type3($id)
    {
        $category = KindWork::findOne($id);
        if ($id != '')
        {
            return 'п. ' . $category->unique_number . ' ' . $category->name . ' ';
        }
    }

    public function get_type4($id)
    {
        $category = Factors::findOne($id);
        if ($id != '')
        {
            return 'п. ' . $category->unique_number . ' ' . $category->name . ';';
        }
    }

    public function get_type42_kind_work2($id)
    {
        $category = KindWork2::findOne($id);
        if ($id != '')
        {
            return 'п. ' . $category->unique_number . ' ' . $category->name . ';';
        }
    }

    public function calculate_age($birthday)
    {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md'))
        {
            $age--;
        }
        return $age;
    }

    public function get_type5($id)
    {
        /*'0' => 'Нет',
        '1' => '1',
        '2' => '2',
        '3' => '3.1',
        '4' => '3.2',
        '5' => '3.3',*/

        if ($id == '1')
        {
            return '1';
        }
        else
        {
            if ($id == '2')
            {
                return '2';
            }
            else
            {
                if ($id == '3')
                {
                    return '3.1';
                }
                else
                {
                    if ($id == '4')
                    {
                        return '3.2';
                    }
                    else
                    {
                        if ($id == '5')
                        {
                            return '3.3';
                        }
                    }
                }
            }
        }
    }

    public function get_type2($id)
    {
        if ($id == 0)
        {
            return 'Предварительный';
        }
        else
        {
            return 'Периодический';
        }

    }

    /*public function factors_list_patients($id)
    {
        $factors = FactorsListPatients::find()->where(['list_patients_id'=>$id])->all();
        if(!empty($factors)){
            $name = '';
            foreach ($factors as $factor){
                $factor_name = Factors::findOne($factor->factors_id);
                $name .= $factor_name->unique_number.'; ';
            }
            return $name;
        }
        else{
            return 'Вредный фактор пациенту не добавлен';
        }

    }*/

    public function get_type6($id)
    {
        if ($id == '0')
        {
            return 'не выявлены';
        }
        else
        {
            if ($id == '1')
            {
                return 'выявлены, необходима экспертиза профпригодности';
            }
            elseif ($id == '3')
            {
                return 'Выявлены, необходима экспертиза профпригодности';
            }
            elseif ($id == '4')
            {
                return 'Выявлены противопоказания к работе по приложению 2';
            }
            elseif ($id == '5')
            {
                return 'Пациент отправлен на дообследование';
            }
            else
            {
                if ($id == '2')
                {
                    return 'не предусмотрено прохождение врача';
                }
                else
                {
                    return '';
                }
            }
        }
    }

    public function get_type77($id)
    {
        if ($id == '0')
        {
            return 'не выявлены';
        }
        else
        {
            if ($id == '1')
            {
                return 'выявлены, необходима экспертиза профпригодности';
            }
            elseif ($id == '3')
            {
                return 'Выявлены, необходима экспертиза профпригодности';
            }
            elseif ($id == '4')
            {
                return 'Выявлены противопоказания к работе по приложению 2';
            }
            elseif ($id == '5')
            {
                return 'Пациент отправлен на дообследование';
            }
            else
            {
                if ($id == '2')
                {
                    return 'не предусмотрено прохождение врача';
                }
                else
                {
                    return '';
                }
            }
        }
    }

    public function get_type777($id)
    {
        if ($id == '0')
        {
            return 'Медицинские противопоказания не выявлены';
        }
        else
        {
            if ($id == '1')
            {
                return 'Медицинские противопоказания выявлены, необходима экспертиза профпригодности';
            }
            elseif ($id == '3')
            {
                return 'Медицинские противопоказания выявлены, необходима экспертиза профпригодности';
            }
            elseif ($id == '4')
            {
                return 'Выявлены противопоказания к работе по приложению 2';
            }
            else
            {
                if ($id == '2')
                {
                    return 'Не предусмотрено прохождение врача';
                }
                else
                {
                    return '';
                }
            }
        }
    }

    public function therapist_r1($id)
    {
        if ($id == '0')
        {
            return 'Да';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нет';
            }
            else
            {
                return '';
            }
        }
    }

    public function therapist_r2($id)
    {
        if ($id == '0')
        {
            return 'Заболевание опорно-двигательного аппарата и периферической нервной системы';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нейросенсорной тугоухости';
            }
            else
            {
                if ($id == '2')
                {
                    return 'Заболеваниям органов дыхания';
                }
                else
                {
                    if ($id == '3')
                    {
                        return 'Проф. интоксикации';
                    }
                    else
                    {
                        return '';
                    }
                }
            }
        }
    }

    public function therapist_r3($id)
    {
        if ($id == '0')
        {
            return 'Да';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нет';
            }
            else
            {
                return '';
            }
        }
    }

    public function therapist_r4($id)
    {
        if ($id == '0')
        {
            return 'Заболевание опорно-двигательного аппарата и периферической нервной системы';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нейросенсорной тугоухости';
            }
            else
            {
                if ($id == '2')
                {
                    return 'Заболеваниям органов дыхания';
                }
                else
                {
                    if ($id == '3')
                    {
                        return 'Проф. интоксикации';
                    }
                    else
                    {
                        return '';
                    }
                }
            }
        }
    }

    public function therapist_r5($id)
    {
        if ($id == '0')
        {
            return 'Да';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нет';
            }
            else
            {
                return '';
            }
        }
    }

    public function therapist_r6($id)
    {
        if ($id == '0')
        {
            return 'Заболевание опорно-двигательного аппарата и периферической нервной системы';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нейросенсорной тугоухости';
            }
            else
            {
                if ($id == '2')
                {
                    return 'Заболеваниям органов дыхания';
                }
                else
                {
                    if ($id == '3')
                    {
                        return 'Проф. интоксикации';
                    }
                    else
                    {
                        return '';
                    }
                }
            }
        }
    }

    public function therapist_r7($id)
    {
        if ($id == '0')
        {
            return 'Да';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нет';
            }
            else
            {
                return '';
            }
        }
    }

    public function therapist_r8($id)
    {
        if ($id == '0')
        {
            return 'Заболевание опорно-двигательного аппарата и периферической нервной системы';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нейросенсорной тугоухости';
            }
            else
            {
                if ($id == '2')
                {
                    return 'Заболеваниям органов дыхания';
                }
                else
                {
                    if ($id == '3')
                    {
                        return 'Проф. интоксикации';
                    }
                    else
                    {
                        return '';
                    }
                }
            }
        }
    }

    public function imt($height, $body_weight)
    {
        //$height рост
        //$body_weight масса тела
        if (!empty($height) || !empty($body_weight))
        {
            $fin_imt = $body_weight / (($height / 100) * ($height / 100));
            return round($fin_imt, 2);
        }
        else
        {
            return '';
        }

    }

    public function imt2($height, $body_weight)
    {
        //$height рост
        //$body_weight масса тела
        if (!empty($height) || !empty($body_weight))
        {
            $fin_imt = $body_weight / (($height / 100) * ($height / 100));
            $fin_imt = round($fin_imt, 2);
            if ($fin_imt <= 24.5)
            {
                return $fin_imt . ' - нормальная масса тела';
            }
            elseif ($fin_imt >= 24.6 && $fin_imt <= 29.9)
            {
                return $fin_imt . ' - предожирение';
            }
            elseif ($fin_imt >= 30 && $fin_imt <= 34.9)
            {
                return $fin_imt . ' - ожирение I степени';
            }
            elseif ($fin_imt >= 35 && $fin_imt <= 39.9)
            {
                return $fin_imt . ' - ожирение I степени';
            }
            elseif ($fin_imt >= 40)
            {
                return $fin_imt . ' - ожирение III степени';
            }
        }
        else
        {
            return '';
        }
    }

    public function whp($waist_circumference, $hip_circumference, $sex)
    {
        //$height рост
        //$body_weight масса тела
        if ($sex == '')
        {
            $sex = '0';
        }
        if ($waist_circumference != '' && $hip_circumference != '' && $sex != '')
        {
            /*$items_sex = [
                '0' => 'Мужской',
                '1' => 'Женский'
            ];*/
            $fin_imt = $waist_circumference / $hip_circumference;
            $fin_imt = round($fin_imt, 2);
            if ($sex == '1')
            {
                if ($fin_imt < 0.8)
                {
                    return $fin_imt . ' - нормальная масса тела';
                }
                elseif ($fin_imt >= 0.8 && $fin_imt <= 0.84)
                {
                    return $fin_imt . ' - избыточный вес';
                }
                elseif ($fin_imt > 0.85)
                {
                    return $fin_imt . ' - ожирение';
                }
            }
            else
            {
                if ($fin_imt < 0.9)
                {
                    return $fin_imt . ' - нормальная масса тела';
                }
                elseif ($fin_imt >= 0.9 && $fin_imt <= 0.99)
                {
                    return $fin_imt . ' - избыточный вес';
                }
                elseif ($fin_imt > 1)
                {
                    return $fin_imt . ' - ожирение';
                }
            }
        }
        else
        {
            return '';
        }

    }

    public function whp2($waist_circumference, $hip_circumference, $sex)
    {
        //$height рост
        //$body_weight масса тела
        if (!empty($waist_circumference) && !empty($hip_circumference) && !empty($sex))
        {
            /*$items_sex = [
                '0' => 'Мужской',
                '1' => 'Женский'
            ];*/
            $fin_imt = $waist_circumference / $hip_circumference;
            $fin_imt = round($fin_imt, 2);
            if ($sex == '1')
            {
                if ($fin_imt < 0.8)
                {
                    return $fin_imt;
                }
                elseif ($fin_imt >= 0.8 && $fin_imt <= 0.84)
                {
                    return $fin_imt;
                }
                elseif ($fin_imt > 0.85)
                {
                    return $fin_imt;
                }
            }
            else
            {
                if ($fin_imt < 0.9)
                {
                    return $fin_imt;
                }
                elseif ($fin_imt >= 0.9 && $fin_imt <= 0.99)
                {
                    return $fin_imt;
                }
                elseif ($fin_imt > 1)
                {
                    return $fin_imt;
                }
            }
        }
        else
        {
            return '';
        }

    }


    public function factors_list_patients($id)
    {
        $factors = ListPatients::findOne($id);

        if ($factors->order_type != '1'){
            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',
                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',

                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

            ];
            $array2 = [
                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',
            ];

            $name = '';
            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_name = Factors::findOne($factors->$name_factor);
                    $name .= 'п. ' . $factor_name->unique_number . ', ';
                }
            }
            $name2 = '';

            for ($i = 0; $i <= count($array2); $i++)
            {
                $name_factor = $array2[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_name2 = KindWork::findOne($factors->$name_factor);
                    $name2 .= 'п. ' . $factor_name2->unique_number . ', ';
                }
            }

            if ($name == '' && $name2 == ''){
                return '-';
            }
            elseif ($name2 == '')
            {
                return 'приложение 1, ' . $name . ' приказ 302н (ред. от 13.12.2019 г.)';
            }
            elseif ($name == '')
            {
                return 'приложение 2, ' . $name2 . ' приказ 302н (ред. от 13.12.2019 г.)';
            }
            else
            {
                return 'приложение 1, ' . $name . ' приложение 2, ' . $name2 . ' приказ 302н (ред. от 13.12.2019 г.)';
            }
        }
        else {

            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',
                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

                'aerosols1',
                'aerosols2',
                'aerosols3',
                'aerosols4',
                'aerosols5',
                'aerosols6',
                'aerosols7',
                'aerosols8',
                'aerosols9',
                'aerosols10',
                'aerosols11',
                'aerosols12',
                'aerosols13',
                'aerosols14',
                'aerosols15',
                'aerosols16',
                'aerosols17',
                'aerosols18',

                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',

            ];
            $name = '';
            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_name = KindWork2::findOne($factors->$name_factor);
                    $name .= 'п. ' . $factor_name->unique_number . ', ';
                }
            }

            if ($factors->organization_id == '248'){
                $nim = ', приказ Минтранса РФ от 19.10.2020 № 428';
            }
            else{
                $nim = '';
            }

            /*$factors = ListPatients::findOne($id);
            if ($factors->organization_id == '248'){
                $nim = ', приказ Минтранса РФ от 19.10.2020 № 428,';
            }
            else{
                $nim = ',';
            }
            return 'Приказ 29н' .$nim.' пункты ' . $name;*/

            if ($name == '')
            {
                return '-';
            }
            else
            {
                return $name . ' приказа 29н'.$nim;
            }
        }

    }

    //Для массивов пунктов приказа по приказу 29 и 302
    public function factors_list_patients_fact_29($id)
    {
        $factors = ListPatients::findOne($id);
        $factor_v = [];

        $array = [
            'chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',
            'chemical_factor7',
            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'chemical_factor11',
            'chemical_factor12',
            'chemical_factor13',
            'chemical_factor14',
            'chemical_factor15',
            'chemical_factor16',
            'chemical_factor17',
            'chemical_factor18',
            'chemical_factor19',
            'chemical_factor20',
            'chemical_factor21',
            'chemical_factor22',
            'chemical_factor23',
            'chemical_factor24',


            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',
            'biological_factor7',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'biological_factor11',
            'biological_factor12',
            'biological_factor13',
            'biological_factor14',
            'biological_factor15',
            'biological_factor16',
            'biological_factor17',
            'biological_factor18',

            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',
            'physical_factor7',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'physical_factor11',
            'physical_factor12',
            'physical_factor13',
            'physical_factor14',
            'physical_factor15',
            'physical_factor16',
            'physical_factor17',
            'physical_factor18',

            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',
            'hard_work7',
            'hard_work8',
            'hard_work9',
            'hard_work10',
            'hard_work11',
            'hard_work12',
            'hard_work13',
            'hard_work14',
            'hard_work15',
            'hard_work16',
            'hard_work17',
            'hard_work18',

            'aerosols1',
            'aerosols2',
            'aerosols3',
            'aerosols4',
            'aerosols5',
            'aerosols6',
            'aerosols7',
            'aerosols8',
            'aerosols9',
            'aerosols10',
            'aerosols11',
            'aerosols12',
            'aerosols13',
            'aerosols14',
            'aerosols15',
            'aerosols16',
            'aerosols17',
            'aerosols18',

            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',

        ];
        $i2 = 0;
        for ($i = 0; $i <= count($array); $i++)
        {
            $name_factor = $array[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_v[$i2] .= $factors->$name_factor;
                $i2++;
            }
        }

        return $factor_v;

    }

    public function factors_list_patients_fact_302_1($id)
    {
        $factors = ListPatients::findOne($id);
        $factor_v = [];

        $array = [
            'chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',
            'chemical_factor7',
            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'chemical_factor11',
            'chemical_factor12',
            'chemical_factor13',
            'chemical_factor14',
            'chemical_factor15',
            'chemical_factor16',
            'chemical_factor17',
            'chemical_factor18',
            'chemical_factor19',
            'chemical_factor20',
            'chemical_factor21',
            'chemical_factor22',
            'chemical_factor23',
            'chemical_factor24',


            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',
            'biological_factor7',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'biological_factor11',
            'biological_factor12',
            'biological_factor13',
            'biological_factor14',
            'biological_factor15',
            'biological_factor16',
            'biological_factor17',
            'biological_factor18',

            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',
            'physical_factor7',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'physical_factor11',
            'physical_factor12',
            'physical_factor13',
            'physical_factor14',
            'physical_factor15',
            'physical_factor16',
            'physical_factor17',
            'physical_factor18',

            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',
            'hard_work7',
            'hard_work8',
            'hard_work9',
            'hard_work10',
            'hard_work11',
            'hard_work12',
            'hard_work13',
            'hard_work14',
            'hard_work15',
            'hard_work16',
            'hard_work17',
            'hard_work18',

            'aerosols1',
            'aerosols2',
            'aerosols3',
            'aerosols4',
            'aerosols5',
            'aerosols6',
            'aerosols7',
            'aerosols8',
            'aerosols9',
            'aerosols10',
            'aerosols11',
            'aerosols12',
            'aerosols13',
            'aerosols14',
            'aerosols15',
            'aerosols16',
            'aerosols17',
            'aerosols18',

            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',

        ];
        $i2 = 0;

        for ($i = 0; $i <= count($array); $i++)
        {
            $name_factor = $array[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_v[$i2] = $factors->$name_factor;
                $i2++;
            }
        }

        return $factor_v;
    }

    public function factors_list_patients_fact_302_2($id)
    {
        $factors = ListPatients::findOne($id);
        $factor_v = [];

        $array2 = [
            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',
        ];
        $i2 = 0;

        for ($i = 0; $i <= count($array2); $i++)
        {
            $name_factor = $array2[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_v[$i2] = $factors->$name_factor;
                $i2++;
            }
        }

        return $factor_v;
    }

    public function print_v_doc_fact_29($id)
    {
        $factor_svs = KindWork2::find()->where(['id' => $id])->one();

        $name = $factor_svs->unique_number . ' '.$factor_svs->name;

        return $name;
    }

    public function print_v_doc_fact_302_1($id)
    {
        $factor_svs = Factors::find()->where(['id' => $id])->one();

        $name = $factor_svs->unique_number . ' '.$factor_svs->name;

        return $name;
    }

    public function print_v_doc_fact_302_2($id)
    {
        $factor_svs = KindWork::find()->where(['id' => $id])->one();

        $name = $factor_svs->unique_number . ' '.$factor_svs->name;

        return $name;
    }


    //Для массивов пунктов приказа по приказу 29
    public function translation_bd_down_pril1_print_v2_kind_work2_fact($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails2 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails2->contraindications == '1' || $fails2->contraindications == '3' || $fails2->contraindications == '4')
        {
            if ($fails2->recommendations != '')
            {
                $pieces22 = explode("/", $fails2->recommendations);
                $pieces22 = array_values($pieces22);
                $name_research_id = array_merge($name_research_id, $pieces22);
            }
        }
        if ($fails->contraindications == '1' || $fails->contraindications == '3' || $fails->contraindications == '4')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' || $med_ifo2->contraindications == '4')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }
        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' || $med_ifo3->contraindications == '4')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' || $med_ifo4->contraindications == '4')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' || $med_ifo8->contraindications == '4')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' || $med_ifo5->contraindications == '4')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' || $med_ifo6->contraindications == '4')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' || $med_ifo7->contraindications == '4')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' || $med_ifo9->contraindications == '4')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        return $name_research_id;

    }
    //Для массивов пунктов приказа по приказу 302 прил 1
    public function translation_bd_down_pril2_print_v_fact($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];
        $fails2 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails2->contraindications == '1' || $fails2->contraindications == '3' || $fails2->contraindications == '4')
        {
            if ($fails2->recommendations2 != '')
            {
                $pieces22 = explode("/", $fails2->recommendations2);
                $pieces22 = array_values($pieces22);
                $name_research_id = array_merge($name_research_id, $pieces22);
            }
        }
        if ($fails->contraindications == '1' || $fails->contraindications == '3' || $fails->contraindications == '4')
        {
            if ($fails->recommendations2 != '')
            {
                $pieces = explode("/", $fails->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' || $med_ifo2->contraindications == '4')
        {
            if ($med_ifo2->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo2->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' || $med_ifo3->contraindications == '4')
        {
            if ($med_ifo3->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo3->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' || $med_ifo4->contraindications == '4')
        {
            if ($med_ifo4->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo4->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' || $med_ifo8->contraindications == '4')
        {
            if ($med_ifo8->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo8->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' || $med_ifo5->contraindications == '4')
        {
            if ($med_ifo5->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo5->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' || $med_ifo6->contraindications == '4')
        {
            if ($med_ifo6->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo6->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' || $med_ifo7->contraindications == '4')
        {
            if ($med_ifo7->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo7->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' || $med_ifo9->contraindications == '4')
        {
            if ($med_ifo9->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo9->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4')
        {
            if ($med_ifo10->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo10->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        return $name_research_id;
    }
    //Для массивов пунктов приказа по приказу 302 прил 2
    public function translation_bd_down_pril1_print_v_fact($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->contraindications == '1' || $fails->contraindications == '3' || $fails->contraindications == '4')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' || $med_ifo2->contraindications == '4')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }
        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' || $med_ifo3->contraindications == '4')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' || $med_ifo4->contraindications == '4')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' || $med_ifo8->contraindications == '4')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' || $med_ifo5->contraindications == '4')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' || $med_ifo6->contraindications == '4')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' || $med_ifo7->contraindications == '4')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' || $med_ifo9->contraindications == '4')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        return $name_research_id;

    }
    //Для название приказов
    public function factors_list_patients_fact_print($id)
    {
        $factors = ListPatients::findOne($id);
        $factor_v = [];
        if ($factors->order_type != '1'){
            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',

                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

            ];
            $array2 = [
                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',
            ];
            $i2 = 0;
            $name = '';
            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_v[$i2] .= $factors->$name_factor;
                    $i2++;
                }
            }
            $name2 = '';

            for ($i = 0; $i <= count($array2); $i++)
            {
                $name_factor = $array2[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_v[$i2] .= $factors->$name_factor;
                    $i2++;
                }
            }
            return $factor_v;
        }
        else {
            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',
                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

                'aerosols1',
                'aerosols2',
                'aerosols3',
                'aerosols4',
                'aerosols5',
                'aerosols6',
                'aerosols7',
                'aerosols8',
                'aerosols9',
                'aerosols10',
                'aerosols11',
                'aerosols12',
                'aerosols13',
                'aerosols14',
                'aerosols15',
                'aerosols16',
                'aerosols17',
                'aerosols18',

                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',

            ];

            $name = '';
            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_v[$i] = $name_factor->$name_factor;
                }
            }

            return $factor_v;
        }
    }

    public function factors_list_patients_print($id)
    {
        $factors = ListPatients::findOne($id);

        if ($factors->order_type != '1'){
            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',

                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

            ];
            $array2 = [
                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',
            ];
            $name = '';
            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_name = Factors::findOne($factors->$name_factor);
                    $name .= 'п. ' . $factor_name->unique_number . ', ';
                }
            }
            $name2 = '';

            for ($i = 0; $i <= count($array2); $i++)
            {
                $name_factor = $array2[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_name2 = KindWork::findOne($factors->$name_factor);
                    $name2 .= 'п. ' . $factor_name2->unique_number . ', ';
                }
            }

            if ($name == '' && $name2 == ''){
                return '-';
            }
            elseif ($name2 == '')
            {
                return 'приложение 1, ' . $name . ' приказ 302н (ред. от 13.12.2019 г.)';
            }
            elseif ($name == '')
            {
                return 'приложение 2, ' . $name2 . ' приказ 302н (ред. от 13.12.2019 г.)';
            }
            else
            {
                return 'приложение 1, ' . $name . ' приложение 2, ' . $name2 . ' приказ 302н (ред. от 13.12.2019 г.)';
            }
        }
        else {
            $array = [
            'chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',
            'chemical_factor7',
            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'chemical_factor11',
            'chemical_factor12',
            'chemical_factor13',
            'chemical_factor14',
            'chemical_factor15',
            'chemical_factor16',
            'chemical_factor17',
            'chemical_factor18',
            'chemical_factor19',
            'chemical_factor20',
            'chemical_factor21',
            'chemical_factor22',
            'chemical_factor23',
            'chemical_factor24',


            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',
            'biological_factor7',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'biological_factor11',
            'biological_factor12',
            'biological_factor13',
            'biological_factor14',
            'biological_factor15',
            'biological_factor16',
            'biological_factor17',
            'biological_factor18',

            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',
            'physical_factor7',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'physical_factor11',
            'physical_factor12',
            'physical_factor13',
            'physical_factor14',
            'physical_factor15',
            'physical_factor16',
            'physical_factor17',
            'physical_factor18',

            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',
            'hard_work7',
            'hard_work8',
            'hard_work9',
            'hard_work10',
            'hard_work11',
            'hard_work12',
            'hard_work13',
            'hard_work14',
            'hard_work15',
            'hard_work16',
            'hard_work17',
            'hard_work18',

            'aerosols1',
            'aerosols2',
            'aerosols3',
            'aerosols4',
            'aerosols5',
            'aerosols6',
            'aerosols7',
            'aerosols8',
            'aerosols9',
            'aerosols10',
            'aerosols11',
            'aerosols12',
            'aerosols13',
            'aerosols14',
            'aerosols15',
            'aerosols16',
            'aerosols17',
            'aerosols18',

            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',

        ];

            $name = '';
            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($factors->$name_factor))
                {
                    $factor_name = KindWork2::findOne($factors->$name_factor);
                    $name .= 'п. ' . $factor_name->unique_number . ', ';
                }
            }



            if ($name == '')
            {
                return '-';
            }
            else
            {
                return $name . ' приложения 1';
            }
        }

    }

    public function factors_list_patients2($id)
    {
        $factors = ListPatients::findOne($id);

        $array = [
            'chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',
            'chemical_factor7',
            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'chemical_factor11',
            'chemical_factor12',
            'chemical_factor13',
            'chemical_factor14',
            'chemical_factor15',
            'chemical_factor16',
            'chemical_factor17',
            'chemical_factor18',

            'chemical_factor19',
            'chemical_factor20',
            'chemical_factor21',
            'chemical_factor22',
            'chemical_factor23',
            'chemical_factor24',


            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',
            'biological_factor7',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'biological_factor11',
            'biological_factor12',
            'biological_factor13',
            'biological_factor14',
            'biological_factor15',
            'biological_factor16',
            'biological_factor17',
            'biological_factor18',

            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',
            'physical_factor7',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'physical_factor11',
            'physical_factor12',
            'physical_factor13',
            'physical_factor14',
            'physical_factor15',
            'physical_factor16',
            'physical_factor17',
            'physical_factor18',

            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',
            'hard_work7',
            'hard_work8',
            'hard_work9',
            'hard_work10',
            'hard_work11',
            'hard_work12',
            'hard_work13',
            'hard_work14',
            'hard_work15',
            'hard_work16',
            'hard_work17',
            'hard_work18',

        ];
        $array2 = [
            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',
        ];

        $name = '';
        for ($i = 0; $i <= count($array); $i++)
        {
            $name_factor = $array[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_name = Factors::findOne($factors->$name_factor);
                $name .= 'п. ' . $factor_name->unique_number . ' ';
            }
        }
        $name2 = '';

        for ($i = 0; $i <= count($array2); $i++)
        {
            $name_factor = $array2[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_name2 = KindWork::findOne($factors->$name_factor);
                $name2 .= 'п. ' . $factor_name2->unique_number . ' ';
            }
        }

        if ($name2 == '')
        {
            return 'Приложение 1, ' . $name . ' приказа 302н.';
        }
        elseif ($name == '')
        {
            return 'Приложение 2, ' . $name2 . ' приказа 302н.';
        }
        else
        {
            return 'Приложение 1, ' . $name . ' Приложение 2, ' . $name2 . ' приказа 302н.';
        }
    }

    public function factors_list_patients_pr1($id)
    {
        $factors = ListPatients::findOne($id);

        $array = ['chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',

            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',

            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',

            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',

            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'hard_work8',
            'hard_work9',
            'hard_work10',

            'chemical_factor7',
            'biological_factor7',
            'physical_factor7',
            'hard_work7',
        ];

        $name = '';
        for ($i = 0; $i <= count($array); $i++)
        {
            $name_factor = $array[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_name = Factors::findOne($factors->$name_factor);
                //$name .= 'п. ' . $factor_name->unique_number . ' - ' . $factor_name->name . '; ';
                $name .= 'п. ' . $factor_name->unique_number . '; ';
            }
        }

        return $name;

    }

    public function factors_list_patients_pr2($id)
    {
        $factors = ListPatients::findOne($id);

        $array2 = [
            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
        ];

        $name2 = '';
        for ($i = 0; $i <= count($array2); $i++)
        {
            $name_factor = $array2[$i];
            if (!empty($factors->$name_factor))
            {
                $factor_name2 = KindWork::findOne($factors->$name_factor);
                $name2 .= 'п. ' . $factor_name2->unique_number . '; ';
                //$name2 .= 'п. ' . $factor_name2->unique_number . ' - ' . $factor_name2->name . '; ';
            }
        }
        $name2 = substr($name2, 0, -2);
        return $name2;
    }

    ///для противопоказаний расчетов
    public function factors_list_patients_pril1_id($id)
    {
        $patients_l = ListPatients::findOne($id);
        $name = [];
        if ($patients_l->order_type != '1'){

            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',

                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

            ];
            //прил 2!
            $array2 = [
                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',

            ];

            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($patients_l->$name_factor))
                {

                    //Это я определил фактор, но надо определить еще противопоказания у этого фактора и передовать его id а не вактора
                    //это просто тест!
                    $factor_svs = Factors::find()->where(['id' => $patients_l->$name_factor])->one();
                    $name [$factor_svs->id] = 'п. ' . $factor_svs->unique_number;
                    /*$factor_svs = FactorsContraindications::find()->where(['factors_id' => $patients_l->$name_factor])->all();
                    foreach ($factor_svs as $factor_sv)
                    {
                        $contraindication = Contraindications::find()->where(['id' => $factor_sv->contraindications_id])->one();
                        $name [$contraindication->id] = $contraindication->name;
                    }*/

                }
            }
            /* $name2 = '';

             for ($i = 0; $i <= count($array2); $i++)
             {
                 $name_factor = $array2[$i];
                 if (!empty($factors->$name_factor))
                 {
                     $factor_name2 = KindWork::findOne($factors->$name_factor);
                     $name2 .= 'п. '.$factor_name2->unique_number . ' ';
                 }
             }*/
            return $name;
            /* if ($name2 == ''){
                 return 'приложение 1, ' . $name . ' приказ 302н (ред. от 13.12.2019 г.)';
             }
             else{
                 return 'приложение 1, ' . $name.' приложение 2, '. $name2. ' приказ 302н (ред. от 13.12.2019 г.)';
             }*/
        }
        else {
            $array = [
                'chemical_factor1',
                'chemical_factor2',
                'chemical_factor3',
                'chemical_factor4',
                'chemical_factor5',
                'chemical_factor6',
                'chemical_factor7',
                'chemical_factor8',
                'chemical_factor9',
                'chemical_factor10',
                'chemical_factor11',
                'chemical_factor12',
                'chemical_factor13',
                'chemical_factor14',
                'chemical_factor15',
                'chemical_factor16',
                'chemical_factor17',
                'chemical_factor18',
                'chemical_factor19',
                'chemical_factor20',
                'chemical_factor21',
                'chemical_factor22',
                'chemical_factor23',
                'chemical_factor24',


                'biological_factor1',
                'biological_factor2',
                'biological_factor3',
                'biological_factor4',
                'biological_factor5',
                'biological_factor6',
                'biological_factor7',
                'biological_factor8',
                'biological_factor9',
                'biological_factor10',
                'biological_factor11',
                'biological_factor12',
                'biological_factor13',
                'biological_factor14',
                'biological_factor15',
                'biological_factor16',
                'biological_factor17',
                'biological_factor18',

                'physical_factor1',
                'physical_factor2',
                'physical_factor3',
                'physical_factor4',
                'physical_factor5',
                'physical_factor6',
                'physical_factor7',
                'physical_factor8',
                'physical_factor9',
                'physical_factor10',
                'physical_factor11',
                'physical_factor12',
                'physical_factor13',
                'physical_factor14',
                'physical_factor15',
                'physical_factor16',
                'physical_factor17',
                'physical_factor18',

                'hard_work1',
                'hard_work2',
                'hard_work3',
                'hard_work4',
                'hard_work5',
                'hard_work6',
                'hard_work6',
                'hard_work7',
                'hard_work8',
                'hard_work9',
                'hard_work10',
                'hard_work11',
                'hard_work12',
                'hard_work13',
                'hard_work14',
                'hard_work15',
                'hard_work16',
                'hard_work17',
                'hard_work18',

                'aerosols1',
                'aerosols2',
                'aerosols3',
                'aerosols4',
                'aerosols5',
                'aerosols6',
                'aerosols7',
                'aerosols8',
                'aerosols9',
                'aerosols10',
                'aerosols11',
                'aerosols12',
                'aerosols13',
                'aerosols14',
                'aerosols15',
                'aerosols16',
                'aerosols17',
                'aerosols18',

                'type_work',
                'gets_2_fields_1',
                'gets_2_fields_2',
                'gets_2_fields_3',
                'gets_2_fields_4',
                'gets_2_fields_5',
                'gets_2_fields_6',
                'gets_2_fields_7',
                'gets_2_fields_8',
                'gets_2_fields_9',
                'gets_2_fields_10',
                'gets_2_fields_11',

            ];

            for ($i = 0; $i <= count($array); $i++)
            {
                $name_factor = $array[$i];
                if (!empty($patients_l->$name_factor))
                {
                    //Это я определил фактор, но надо определить еще противопоказания у этого фактора и передовать его id а не вактора
                    //это просто тест!
                    $factor_svs = KindWork2::find()->where(['id' => $patients_l->$name_factor])->one();
                    $name [$factor_svs->id] = 'п. ' . $factor_svs->unique_number;
                }
            }
            /* $name2 = '';

             for ($i = 0; $i <= count($array2); $i++)
             {
                 $name_factor = $array2[$i];
                 if (!empty($factors->$name_factor))
                 {
                     $factor_name2 = KindWork::findOne($factors->$name_factor);
                     $name2 .= 'п. '.$factor_name2->unique_number . ' ';
                 }
             }*/
            return $name;
        }
    }

    public function factors_list_patients_pril2_id($id)
    {
        $patients_l = ListPatients::findOne($id);

        $array = ['chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',
            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',
            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',
            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',
            'chemical_factor7',
            'biological_factor7',
            'physical_factor7',
            'hard_work7',

            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'hard_work8',
            'hard_work9',
            'hard_work10',

        ];

        //прил 2!
        $array2 = [
            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',
        ];

        $name = [];
        for ($i = 0; $i <= count($array2); $i++)
        {
            $name_factor = $array2[$i];
            if (!empty($patients_l->$name_factor))
            {

                //Это я определил фактор, но надо определить еще противопоказания у этого фактора и передовать его id а не вактора
                //это просто тест!
                $factor_svs = KindWork::find()->where(['id' => $patients_l->$name_factor])->one();
                $name [$factor_svs->id] = 'п. ' . $factor_svs->unique_number;
                /*$factor_svs = FactorsContraindications::find()->where(['factors_id' => $patients_l->$name_factor])->all();
                foreach ($factor_svs as $factor_sv)
                {
                    $contraindication = Contraindications::find()->where(['id' => $factor_sv->contraindications_id])->one();
                    $name [$contraindication->id] = $contraindication->name;
                }*/

            }
        }

        /* $name2 = '';

         for ($i = 0; $i <= count($array2); $i++)
         {
             $name_factor = $array2[$i];
             if (!empty($factors->$name_factor))
             {
                 $factor_name2 = KindWork::findOne($factors->$name_factor);
                 $name2 .= 'п. '.$factor_name2->unique_number . ' ';
             }
         }*/
        return $name;
        /* if ($name2 == ''){
             return 'приложение 1, ' . $name . ' приказ 302н (ред. от 13.12.2019 г.)';
         }
         else{
             return 'приложение 1, ' . $name.' приложение 2, '. $name2. ' приказ 302н (ред. от 13.12.2019 г.)';
         }*/
    }

    // функция формирования массива из сохранений

    public function translation_bd_to($count, $arry)
    {
        $str = '';
        for ($i = 0; $i < $count; ++$i)
        {
            $str .= $arry[$i] . '/';
        }
        $str = substr($str, 0, -1);
        return $str;

    }

    public function cons_list_patients($id)
    {
        $patients_l = ListPatients::findOne($id);

        $array = [
            'chemical_factor1',
            'chemical_factor2',
            'chemical_factor3',
            'chemical_factor4',
            'chemical_factor5',
            'chemical_factor6',
            'chemical_factor7',
            'chemical_factor8',
            'chemical_factor9',
            'chemical_factor10',
            'chemical_factor11',
            'chemical_factor12',
            'chemical_factor13',
            'chemical_factor14',
            'chemical_factor15',
            'chemical_factor16',
            'chemical_factor17',
            'chemical_factor18',
            'chemical_factor19',
            'chemical_factor20',
            'chemical_factor21',
            'chemical_factor22',
            'chemical_factor23',
            'chemical_factor24',


            'biological_factor1',
            'biological_factor2',
            'biological_factor3',
            'biological_factor4',
            'biological_factor5',
            'biological_factor6',
            'biological_factor7',
            'biological_factor8',
            'biological_factor9',
            'biological_factor10',
            'biological_factor11',
            'biological_factor12',
            'biological_factor13',
            'biological_factor14',
            'biological_factor15',
            'biological_factor16',
            'biological_factor17',
            'biological_factor18',

            'physical_factor1',
            'physical_factor2',
            'physical_factor3',
            'physical_factor4',
            'physical_factor5',
            'physical_factor6',
            'physical_factor7',
            'physical_factor8',
            'physical_factor9',
            'physical_factor10',
            'physical_factor11',
            'physical_factor12',
            'physical_factor13',
            'physical_factor14',
            'physical_factor15',
            'physical_factor16',
            'physical_factor17',
            'physical_factor18',

            'hard_work1',
            'hard_work2',
            'hard_work3',
            'hard_work4',
            'hard_work5',
            'hard_work6',
            'hard_work6',
            'hard_work7',
            'hard_work8',
            'hard_work9',
            'hard_work10',
            'hard_work11',
            'hard_work12',
            'hard_work13',
            'hard_work14',
            'hard_work15',
            'hard_work16',
            'hard_work17',
            'hard_work18',

            'aerosols1',
            'aerosols2',
            'aerosols3',
            'aerosols4',
            'aerosols5',
            'aerosols6',
            'aerosols7',
            'aerosols8',
            'aerosols9',
            'aerosols10',
            'aerosols11',
            'aerosols12',
            'aerosols13',
            'aerosols14',
            'aerosols15',
            'aerosols16',
            'aerosols17',
            'aerosols18',

            'type_work',
            'gets_2_fields_1',
            'gets_2_fields_2',
            'gets_2_fields_3',
            'gets_2_fields_4',
            'gets_2_fields_5',
            'gets_2_fields_6',
            'gets_2_fields_7',
            'gets_2_fields_8',
            'gets_2_fields_9',
            'gets_2_fields_10',
            'gets_2_fields_11',

        ];

        $name = [];
        for ($i = 0; $i <= count($array); $i++)
        {
            $name_factor = $array[$i];
            if (!empty($patients_l->$name_factor))
            {
                //Это я определил фактор, но надо определить еще противопоказания у этого фактора и передовать его id а не вактора
                //это просто тест!
                $factor_svs = KindWork2::find()->where(['id' => $patients_l->$name_factor])->one();
                $name[] = $factor_svs->id;

            }
        }

        $name2 = [];
        for ($i = 0; $i <= count($name); $i++)
        {
            //Это я определил фактор, но надо определить еще противопоказания у этого фактора и передовать его id а не вактора
            //это просто тест!
            $factor_svs = \common\models\KindWorkContraindications2::find()->where(['kind_work_id' => $name[$i]])->all();
            foreach ($factor_svs as $factor_sv){
                $name2[] = $factor_sv->contraindications_id;
            }
        }

        $new_name2 = array_diff($name2, array(''));
        //$result = array_unique($new_name2);
        $result = $new_name2;

        $name3 = [];
        for ($i = 0; $i <= count($result); $i++)
        {
            //Это я определил фактор, но надо определить еще противопоказания у этого фактора и передовать его id а не вактора
            //это просто тест!
            $factor_svs = Contraindications2::find()->where(['id' => $result[$i]])->one();
            $name3 [$factor_svs->id] = $factor_svs->number . ' ' .$factor_svs->name;
        }
        $result2 = array_unique($name3);

        return $name3;
    }

    public function translation_bd_down($id)
    {
        $med_ifo1 = \common\models\Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if (!empty($med_ifo1))
        {
            $str = $med_ifo1->recommendations;
            /*for ($i = 0; $i < $count; ++$i)
            {
                $str = $arry[$i] . '/';
            }
            $str = substr($str,0,-1);*/
            $pieces = explode("/", $str);
            $pieces = array_values($pieces);
            //print_r($pieces);
            //exit();
            return $pieces;
        }
        else
        {
            $pieces = [];
            return $pieces;
        }


    }

    public function translation_bd_down_pril2($id)
    {
        $med_ifo1 = \common\models\Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if (!empty($med_ifo1))
        {
            $str = $med_ifo1->recommendations2;
            /*for ($i = 0; $i < $count; ++$i)
            {
                $str = $arry[$i] . '/';
            }
            $str = substr($str,0,-1);*/
            $pieces = explode("/", $str);
            $pieces = array_values($pieces);
            //print_r($pieces);
            //exit();
            return $pieces;
        }
        else
        {
            $pieces = [];
            return $pieces;
        }


    }

    public function translation_bd_down_pril1_print($arry)
    {
        if (!empty($arry))
        {
            $name = '';
            $pieces = explode("/", $arry);
            $pieces = array_values($pieces);
            //print_r($pieces);
            //exit();
            $caunt = count($pieces);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = Factors::find()->where(['id' => $pieces[$i]])->one();
                $name .= $factor_svs->unique_number . ', ';
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 1, пункты ' . $name;
        }
        else
        {
            $name = '';
            return $name;
        }
    }

    //dсе противопоказания по врачам !
    public function get_lab_colortranslation_bd_down_pril1_print_v($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->contraindications == '1' || $fails->contraindications == '3')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }
        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = Factors::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 1, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril2_print_v($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];
        $fails2 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails2->contraindications == '1' || $fails2->contraindications == '3' || $fails2->contraindications == '4')
        {
            if ($fails2->recommendations2 != '')
            {
                $pieces22 = explode("/", $fails2->recommendations2);
                $pieces22 = array_values($pieces22);
                $name_research_id = array_merge($name_research_id, $pieces22);
            }
        }
        if ($fails->contraindications == '1' || $fails->contraindications == '3' || $fails->contraindications == '4')
        {
            if ($fails->recommendations2 != '')
            {
                $pieces = explode("/", $fails->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' || $med_ifo2->contraindications == '4')
        {
            if ($med_ifo2->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo2->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3' || $med_ifo3->contraindications == '4')
        {
            if ($med_ifo3->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo3->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' || $med_ifo4->contraindications == '4')
        {
            if ($med_ifo4->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo4->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' || $med_ifo8->contraindications == '4')
        {
            if ($med_ifo8->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo8->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' || $med_ifo5->contraindications == '4')
        {
            if ($med_ifo5->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo5->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' || $med_ifo6->contraindications == '4')
        {
            if ($med_ifo6->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo6->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' || $med_ifo7->contraindications == '4')
        {
            if ($med_ifo7->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo7->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' || $med_ifo9->contraindications == '4')
        {
            if ($med_ifo9->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo9->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4')
        {
            if ($med_ifo10->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo10->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = KindWork::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 2, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    public function translation_bd_down_pril1_print_v($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->contraindications == '1' || $fails->contraindications == '3')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }

        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = Factors::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 1, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    public function translation_bd_down_pril1_print_v_doc($array, $contraindications,$id = false)
    {

        if ($contraindications == '1' || $contraindications == '3')
        {
            if ($array != '')
            {
                $pieces4 = explode("/", $array);
                $pieces4 = array_values($pieces4);

            }
        }

        if($id == '1'){
            $name = '';
            if (!empty($pieces4))
            {
                $caunt = count($pieces4);
                for ($i = 0; $i <= $caunt - 1; $i++)
                {
                    $factor_svs = KindWork2::find()->where(['id' => $pieces4[$i]])->one();
                    if (!empty($factor_svs))
                    {
                        $name .= $factor_svs->unique_number . ', ';
                    }
                }
                $name = substr($name, 0, -2);
                return 'пункты ' . $name;
            }
            else
            {
                return $name;
            }
        }
        else{
            $name = '';
            if (!empty($pieces4))
            {
                $caunt = count($pieces4);
                for ($i = 0; $i <= $caunt - 1; $i++)
                {
                    $factor_svs = Factors::find()->where(['id' => $pieces4[$i]])->one();
                    if (!empty($factor_svs))
                    {
                        $name .= $factor_svs->unique_number . ', ';
                    }
                }
                $name = substr($name, 0, -2);
                return 'Приказ 302н, приложение 1, пункты ' . $name;
            }
            else
            {
                return $name;
            }
        }

    }

    public function cons_list_patients_print($array2, $contraindications)
    {
        if ($contraindications == '1' || $contraindications == '3')
        {
            if ($array2 != '')
            {
                $pieces4 = explode("/", $array2);
                $pieces4 = array_values($pieces4);

            }
        }
        $name = '';
        if (!empty($pieces4))
        {
            $caunt = count($pieces4);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = Contraindications2::find()->where(['id' => $pieces4[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->number . ' ' .$factor_svs->name. '; ';
                }
            }
            $name = substr($name, 0, -2);
            return 'проивопоказания: ' . $name;
        }
        else
        {
            return $name;
        }
    }
    //dсе противопоказания по врачам !
    public function translation_bd_down_pril2_print_v_doc($array2, $contraindications)
    {

        $name = '';

        if ($contraindications == '1' || $contraindications == '3' || $contraindications == '4')
        {
            if ($array2 != '')
            {
                $pieces4 = explode("/", $array2);
                $pieces4 = array_values($pieces4);

            }
        }

        if (!empty($pieces4))
        {
            $caunt = count($pieces4);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = KindWork::find()->where(['id' => $pieces4[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 2, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril1_print_v2($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails2 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails2->contraindications == '1' || $fails2->contraindications == '3')
        {
            if ($fails2->recommendations != '')
            {
                $pieces22 = explode("/", $fails2->recommendations);
                $pieces22 = array_values($pieces22);
                $name_research_id = array_merge($name_research_id, $pieces22);
            }
        }
        if ($fails->contraindications == '1' || $fails->contraindications == '3')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }

        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = Factors::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 1, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    public function translation_bd_down_pril1_print_v2_kind_work2($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails2 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails2->contraindications == '1' || $fails2->contraindications == '3')
        {
            if ($fails2->recommendations != '')
            {
                $pieces22 = explode("/", $fails2->recommendations);
                $pieces22 = array_values($pieces22);
                $name_research_id = array_merge($name_research_id, $pieces22);
            }
        }
        if ($fails->contraindications == '1' || $fails->contraindications == '3')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }

        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = KindWork2::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            $factors = ListPatients::findOne($id);
            if ($factors->organization_id == '248'){
                $nim = ', приказ Минтранса РФ от 19.10.2020 № 428,';
            }
            else{
                $nim = ',';
            }
            return 'Приказ 29н' .$nim.' пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril2_print_v2($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $fails22 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->contraindications == '1' || $fails->contraindications == '3' || $fails->contraindications == '4')
        {
            if ($fails->recommendations2 != '')
            {
                $pieces = explode("/", $fails->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($fails22->contraindications == '1' || $fails22->contraindications == '3' || $fails22->contraindications == '4')
        {
            if ($fails22->recommendations2 != '')
            {
                $pieces22 = explode("/", $fails22->recommendations2);
                $pieces22 = array_values($pieces22);
                $name_research_id = array_merge($name_research_id, $pieces22);
            }
        }
        if ($med_ifo2->contraindications == '1' || $med_ifo2->contraindications == '3' || $med_ifo2->contraindications == '4')
        {
            if ($med_ifo2->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo2->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo3->contraindications == '1' || $med_ifo3->contraindications == '3'|| $med_ifo3->contraindications == '4')
        {
            if ($med_ifo3->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo3->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo4->contraindications == '1' || $med_ifo4->contraindications == '3' || $med_ifo4->contraindications == '4')
        {
            if ($med_ifo4->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo4->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo8->contraindications == '1' || $med_ifo8->contraindications == '3' || $med_ifo8->contraindications == '4')
        {
            if ($med_ifo8->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo8->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo5->contraindications == '1' || $med_ifo5->contraindications == '3' || $med_ifo5->contraindications == '4')
        {
            if ($med_ifo5->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo5->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo6->contraindications == '1' || $med_ifo6->contraindications == '3' || $med_ifo6->contraindications == '4')
        {
            if ($med_ifo6->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo6->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo7->contraindications == '1' || $med_ifo7->contraindications == '3' || $med_ifo7->contraindications == '4')
        {
            if ($med_ifo7->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo7->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo9->contraindications == '1' || $med_ifo9->contraindications == '3' || $med_ifo9->contraindications == '4')
        {
            if ($med_ifo9->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo9->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo10->contraindications == '1' || $med_ifo10->contraindications == '3' || $med_ifo10->contraindications == '4')
        {
            if ($med_ifo10->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo10->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = KindWork::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 2, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril3_print_v2($id)
    {
        $name = '';
        $fails22 = ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails22->application_3 == '1')
        {
            $name .= 'Приказ 302н, приложение 3, раздел 4 пункт 48';
            return $name;
        }
        else
        {
            return $name;
        }

    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril1_print_v3($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->contraindications == '' || $fails->contraindications == '0' || $fails->contraindications == '2')
        {
            if ($fails->recommendations != '')
            {
                $pieces = explode("/", $fails->recommendations);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '' || $med_ifo2->contraindications == '0' || $med_ifo2->contraindications == '2')
        {
            if ($med_ifo2->recommendations != '')
            {
                $pieces2 = explode("/", $med_ifo2->recommendations);
                $pieces2 = array_values($pieces2);
                $name_research_id = array_merge($name_research_id, $pieces2);
            }
        }

        if ($med_ifo3->contraindications == '' || $med_ifo3->contraindications == '0' || $med_ifo3->contraindications == '2')
        {
            if ($med_ifo3->recommendations != '')
            {
                $pieces3 = explode("/", $med_ifo3->recommendations);
                $pieces3 = array_values($pieces3);
                $name_research_id = array_merge($name_research_id, $pieces3);
            }
        }
        if ($med_ifo4->contraindications == '' || $med_ifo4->contraindications == '0' || $med_ifo4->contraindications == '2')
        {
            if ($med_ifo4->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo4->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo8->contraindications == '' || $med_ifo8->contraindications == '0' || $med_ifo8->contraindications == '2')
        {
            if ($med_ifo8->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo8->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo5->contraindications == '' || $med_ifo5->contraindications == '0' || $med_ifo5->contraindications == '2')
        {
            if ($med_ifo5->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo5->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo6->contraindications == '' || $med_ifo6->contraindications == '0' || $med_ifo6->contraindications == '2')
        {
            if ($med_ifo6->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo6->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo7->contraindications == '' || $med_ifo7->contraindications == '0' || $med_ifo7->contraindications == '2')
        {
            if ($med_ifo7->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo7->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo9->contraindications == '' || $med_ifo9->contraindications == '0' || $med_ifo9->contraindications == '2')
        {
            if ($med_ifo9->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo9->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }
        if ($med_ifo10->contraindications == '' || $med_ifo10->contraindications == '0' || $med_ifo10->contraindications == '2')
        {
            if ($med_ifo10->recommendations != '')
            {
                $pieces4 = explode("/", $med_ifo10->recommendations);
                $pieces4 = array_values($pieces4);
                $name_research_id = array_merge($name_research_id, $pieces4);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = Factors::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 1, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril2_print_v3($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->contraindications == '' || $fails->contraindications == '0' || $fails->contraindications == '2')
        {
            if ($fails->recommendations2 != '')
            {
                $pieces = explode("/", $fails->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo2->contraindications == '' || $med_ifo2->contraindications == '0' || $med_ifo2->contraindications == '2')
        {
            if ($med_ifo2->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo2->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo3->contraindications == '' || $med_ifo3->contraindications == '0' || $med_ifo3->contraindications == '2')
        {
            if ($med_ifo3->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo3->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo4->contraindications == '' || $med_ifo4->contraindications == '0' || $med_ifo4->contraindications == '2')
        {
            if ($med_ifo4->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo4->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo8->contraindications == '' || $med_ifo8->contraindications == '0' || $med_ifo8->contraindications == '2')
        {
            if ($med_ifo8->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo8->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo5->contraindications == '' || $med_ifo5->contraindications == '0' || $med_ifo5->contraindications == '2')
        {
            if ($med_ifo5->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo5->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo6->contraindications == '' || $med_ifo6->contraindications == '0' || $med_ifo6->contraindications == '2')
        {
            if ($med_ifo6->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo6->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo7->contraindications == '' || $med_ifo7->contraindications == '0' || $med_ifo7->contraindications == '2')
        {
            if ($med_ifo7->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo7->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo9->contraindications == '' || $med_ifo9->contraindications == '0' || $med_ifo9->contraindications == '2')
        {
            if ($med_ifo9->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo9->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }
        if ($med_ifo10->contraindications == '' || $med_ifo10->contraindications == '0' || $med_ifo10->contraindications == '2')
        {
            if ($med_ifo10->recommendations2 != '')
            {
                $pieces = explode("/", $med_ifo10->recommendations2);
                $pieces = array_values($pieces);
                $name_research_id = array_merge($name_research_id, $pieces);
            }
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if (!empty($name_research_id))
        {
            $caunt = count($name_research_id);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = KindWork::find()->where(['id' => $name_research_id[$i]])->one();
                if (!empty($factor_svs))
                {
                    $name .= $factor_svs->unique_number . ', ';
                }
            }
            $name = substr($name, 0, -2);
            return 'Приказ 302н, приложение 2, пункты ' . $name;
        }
        else
        {
            return $name;
        }
    }

    public function marks_print($id)
    {

        $name = '';
        $name_research_id = [];
        $fails = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo2 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo3 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo4 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo5 = \common\models\Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo6 = \common\models\Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo7 = \common\models\Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo8 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo9 = \common\models\Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo10 = \common\models\Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $med_ifo11 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($fails->marks != '')
        {
            $name .= $fails->marks . '. ';
        }
        if ($med_ifo2->marks != '')
        {
            $name .= $med_ifo2->marks . '. ';
        }
        if ($med_ifo3->marks != '')
        {
            $name .= $med_ifo3->marks . '. ';
        }
        if ($med_ifo4->marks != '')
        {
            $name .= $med_ifo4->marks . '. ';
        }
        if ($med_ifo8->marks != '')
        {
            $name .= $med_ifo8->marks . '. ';
        }
        if ($med_ifo5->marks != '')
        {
            $name .= $med_ifo5->marks . '. ';
        }
        if ($med_ifo6->marks != '')
        {
            $name .= $med_ifo6->marks . '. ';
        }
        if ($med_ifo7->marks != '')
        {
            $name .= $med_ifo7->marks . '. ';
        }
        if ($med_ifo9->marks != '')
        {
            $name .= $med_ifo9->marks . '. ';
        }
        if ($med_ifo10->marks != '')
        {
            $name .= $med_ifo10->marks . '. ';
        }
        if ($med_ifo11->marks != '')
        {
            $name .= $med_ifo11->marks . '. ';
        }

        if (
            empty($terapevt) &&
            empty($med_ifo2) &&
            empty($med_ifo3) &&
            empty($med_ifo4) &&
            empty($med_ifo5) &&
            empty($med_ifo6) &&
            empty($med_ifo7) &&
            empty($med_ifo8) &&
            empty($med_ifo9) &&
            empty($med_ifo10) &&
            empty($med_ifo11)
        )
        {
            $name = 'нет';
        }

        return $name;
    }

    public function translation_bd_down_pril2_print($arry_pril2, $arry_pril1)
    {
        if (!empty($arry_pril2))
        {
            $name = '';
            $pieces = explode("/", $arry_pril2);
            $pieces = array_values($pieces);
            //print_r($pieces);
            //exit();
            $caunt = count($pieces);
            for ($i = 0; $i <= $caunt - 1; $i++)
            {
                $factor_svs = KindWork::find()->where(['id' => $pieces[$i]])->one();
                $name .= $factor_svs->unique_number . ', ';
            }
            $name = substr($name, 0, -2);
            if (!empty($arry_pril1))
            {
                return ', приложение 2, пункты ' . $name;
            }
            else
            {
                return 'Приказ 302н, приложение 2, пункты ' . $name;
            }
        }
        else
        {
            $name = '';
            return $name;
        }
    }

    public function get_sex($id)
    {
        if ($id == '0')
        {
            return 'М';
        }
        else
        {
            return 'Ж';
        }

    }

    public function get_sex2($id)
    {
        if ($id == '0')
        {
            return 'м';
        }
        else
        {
            return 'ж';
        }

    }

    public function get_district($id)
    {
        $district = FederalDistrict::findOne($id);
        $district = $district->name;
        return $district;
    }

    public function get_region($id)
    {
        $region = Region::find()->where(['id' => $id])->one();
        $region = $region->name;
        return $region;
    }

    public function get_municipality($id)
    {
        $municipality = Municipality::find()->where(['id' => $id])->one();
        $municipality = $municipality->name;
        return $municipality;
    }

    public function get_group($id)
    {
        if ($id == '0')
        {
            return 'Заболевание опорно-двигательного аппарата и периферической нервной системы';
        }
        else
        {
            if ($id == '1')
            {
                return 'Нейросенсорной тугоухости';
            }
            else
            {
                if ($id == '2')
                {
                    return 'Заболеваниям органов дыхания';
                }
                else
                {
                    if ($id == '3')
                    {
                        return 'Проф. интоксикации';
                    }
                    else
                    {
                        if ($id == '')
                        {
                            return '';
                        }
                    }
                }
            }
        }

    }

    public function get_anket($id)
    {
        $municipality = AnketPatient::find()->where(['user_id' => $id])->one();
        if (!empty($municipality))
        {
            return 'Пройдена: ' . $municipality->creat_at;
        }
        else
        {
            return 'Не пройдена';
        }

    }

    public function get_therapist($id)
    {
        $med_ifo1 = \common\models\Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            return $this->get_type6($med_ifo1->contraindications);
        }
        else
        {
            $pieces = 'Заключен. не внесено';
            return $pieces;
        }
    }

    public function get_therapist_color($id)
    {
        $med_ifo1 = \common\models\Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            if($med_ifo1->contraindications == '1' || $med_ifo1->contraindications == '3'){
                return ['style' => 'background-color:#d4553b;'];
            }else{
                return ['style' => 'background-color:#5f9ea0;'];
            }
        }
        elseif (!empty($med_ifo1))
        {
            return ['style' => 'background-color:#fcdd76;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    public function get_prof($id)
    {
        $med_ifo1 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            return $this->get_type6($med_ifo1->contraindications);
        }
        else
        {
            $pieces = 'Заключен. не внесено';
            return $pieces;
        }
    }

    public function get_prof_color($id)
    {
        $med_ifo1 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            if($med_ifo1->contraindications == '1' || $med_ifo1->contraindications == '3'){
                return ['style' => 'background-color:#d4553b;'];
            }else{
                return ['style' => 'background-color:#5f9ea0;'];
            }
        }
        elseif (!empty($med_ifo1))
        {
            return ['style' => 'background-color:#fcdd76;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    public function get_surge($id)
    {
        $med_ifo1 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            return $this->get_type6($med_ifo1->contraindications);
        }
        else
        {
            $pieces = 'Заключен. не внесено';
            return $pieces;
        }
    }

    public function get_surge_color($id)
    {
        $med_ifo1 = \common\models\Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            if($med_ifo1->contraindications == '1' || $med_ifo1->contraindications == '3'){
                return ['style' => 'background-color:#d4553b;'];
            }else{
                return ['style' => 'background-color:#5f9ea0;'];
            }
        }
        elseif (!empty($med_ifo1))
        {
            return ['style' => 'background-color:#fcdd76;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    public function get_neurologist($id)
    {
        $med_ifo1 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            return $this->get_type6($med_ifo1->contraindications);
        }
        else
        {
            $pieces = 'Заключен. не внесено';
            return $pieces;
        }
    }

    public function get_neurologist_color($id)
    {
        $med_ifo1 = \common\models\Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            if($med_ifo1->contraindications == '1' || $med_ifo1->contraindications == '3'){
                return ['style' => 'background-color:#d4553b;'];
            }else{
                return ['style' => 'background-color:#5f9ea0;'];
            }

        }
        elseif (!empty($med_ifo1))
        {
            return ['style' => 'background-color:#fcdd76;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    public function get_oculist($id)
    {
        $med_ifo1 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            return $this->get_type6($med_ifo1->contraindications);
        }
        else
        {
            $pieces = 'Заключен. не внесено';
            return $pieces;
        }
    }

    public function get_oculist_color($id)
    {
        $med_ifo1 = \common\models\Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            if($med_ifo1->contraindications == '1' || $med_ifo1->contraindications == '3'){
                return ['style' => 'background-color:#d4553b;'];
            }else{
                return ['style' => 'background-color:#5f9ea0;'];
            }
        }
        elseif (!empty($med_ifo1))
        {
            return ['style' => 'background-color:#fcdd76;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    public function get_audiologist($id)
    {
        $med_ifo1 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            return $this->get_type6($med_ifo1->contraindications);
        }
        else
        {
            $pieces = 'Заключен. не внесено';
            return $pieces;
        }
    }

    public function get_audiologist_color($id)
    {
        $med_ifo1 = \common\models\Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();

        if ($med_ifo1->contraindications != '')
        {
            if($med_ifo1->contraindications == '1' || $med_ifo1->contraindications == '3'){
                return ['style' => 'background-color:#d4553b;'];
            }else{
                return ['style' => 'background-color:#5f9ea0;'];
            }
        }
        elseif (!empty($med_ifo1))
        {
            return ['style' => 'background-color:#fcdd76;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    public function get_lab($id)
    {
        $med_ifo1 = \common\models\BloodTest::find()->where(['user_id' => $id])->one();
        $med_ifo2 = \common\models\UrineTest::find()->where(['user_id' => $id])->one();
        $med_ifo3 = \common\models\Ecg::find()->where(['user_id' => $id])->one();

        $users = ListPatients::find()->
        select([
            'blood_test.dates as blood',
            'urine_test.dates as urine',
            'ecg.dates as ecg',
        ])
            ->leftJoin('blood_test', 'list_patients.id = blood_test.user_id')
            ->leftJoin('urine_test', 'list_patients.id = urine_test.user_id')
            ->leftJoin('ecg', 'list_patients.id = ecg.user_id')
            ->where(['list_patients.id' => $id])
            ->asArray()
            ->one();

        if ($users['blood'] != '' && $users['urine'] != '' && $users['ecg'] != '')
        {
            return 'Заполнено';
        }
        else
        {
            $pieces = '';
            if ($users['blood'] != '')
            {
                $pieces .= 'Ан. крови: внесен; ';
            }
            else
            {
                $pieces .= 'Ан. крови: НЕ внесен; ';
            }
            if ($users['urine'] != '')
            {
                $pieces .= 'Ан. мочи: внесен; ';
            }
            else
            {
                $pieces .= 'Ан. мочи: НЕ внесен; ';
            }
            if ($users['ecg'] != '')
            {
                $pieces .= 'Результат ЭКГ: внесен; ';
            }
            else
            {
                $pieces .= 'Результат ЭКГ: НЕ внесен; ';
            }
            return $pieces;
        }
    }

    public function get_lab_color($id)
    {
        $med_ifo1 = \common\models\BloodTest::find()->where(['user_id' => $id])->one();
        $med_ifo2 = \common\models\UrineTest::find()->where(['user_id' => $id])->one();
        $med_ifo3 = \common\models\Ecg::find()->where(['user_id' => $id])->one();

        $users = ListPatients::find()->
        select([
            'blood_test.dates as blood',
            'urine_test.dates as urine',
            'ecg.dates as ecg',
        ])
            ->leftJoin('blood_test', 'list_patients.id = blood_test.user_id')
            ->leftJoin('urine_test', 'list_patients.id = urine_test.user_id')
            ->leftJoin('ecg', 'list_patients.id = ecg.user_id')
            ->where(['list_patients.id' => $id])
            ->asArray()
            ->one();

        if ($users['blood'] != '' && $users['urine'] != '' && $users['ecg'] != '')
        {
            return ['style' => 'background-color:#5f9ea0;'];
        }
        elseif ($users['blood'] != '' || $users['urine'] != '' || $users['ecg'] != '')
        {
            return ['style' => 'background-color:#ff7538;'];
        }
        else
        {
            return ['style' => ''];
        }
    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril1_print_v_prof($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $med_ifo10 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $patient = \common\models\ListPatients::find()->where(['id' => $id])->one();

        if ($med_ifo10->recommendations != '')
        {
            $pieces4 = explode("/", $med_ifo10->recommendations);
            $pieces4 = array_values($pieces4);
            $name_research_id = array_merge($name_research_id, $pieces4);
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if($patient->order_type == '1'){
            if (!empty($name_research_id))
            {
                $caunt = count($name_research_id);
                for ($i = 0; $i <= $caunt - 1; $i++)
                {
                    $factor_svs = KindWork2::find()->where(['id' => $name_research_id[$i]])->one();
                    if (!empty($factor_svs))
                    {
                        $name .= $factor_svs->unique_number . ', ';
                    }
                }
                $name = substr($name, 0, -2);
                $factors = ListPatients::findOne($id);
                if ($factors->organization_id == '248'){
                    $nim = ', приказ Минтранса РФ от 19.10.2020 № 428,';
                }
                else{
                    $nim = ',';
                }
                return 'Приказ 29н' .$nim.' пункты ' . $name;
            }
            else
            {
                return $name;
            }
        }
        else{
            if (!empty($name_research_id))
            {
                $caunt = count($name_research_id);
                for ($i = 0; $i <= $caunt - 1; $i++)
                {
                    $factor_svs = Factors::find()->where(['id' => $name_research_id[$i]])->one();
                    if (!empty($factor_svs))
                    {
                        $name .= $factor_svs->unique_number . ', ';
                    }
                }
                $name = substr($name, 0, -2);
                return 'Приказ 302н, приложение 1, пункты ' . $name;
            }
            else
            {
                return $name;
            }
        }

    }

    //dсе противопоказания по врачам !
    public function translation_bd_down_pril2_print_v_prof($id)
    {
        $arry = [];
        $name = '';
        $name_research_id = [];

        $med_ifo10 = \common\models\ProfessionalPathologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
        $patient = \common\models\ListPatients::find()->where(['id' => $id])->one();

        if ($med_ifo10->recommendations2 != '')
        {
            $pieces = explode("/", $med_ifo10->recommendations2);
            $pieces = array_values($pieces);
            $name_research_id = array_merge($name_research_id, $pieces);
        }

        $name_research_id = array_unique($name_research_id); //получаю уникальные занчения массива!
        $name_research_id = array_values($name_research_id); //обнуляю ключи

        if($patient->order_type == '1'){
            if (!empty($name_research_id))
            {
                $caunt = count($name_research_id);
                for ($i = 0; $i <= $caunt - 1; $i++)
                {
                    $factor_svs = Contraindications2::find()->where(['id' => $name_research_id[$i]])->one();
                    if (!empty($factor_svs))
                    {
                        $name .= $factor_svs->number . ' ' .$factor_svs->name. '; ';
                    }
                }
                $name = substr($name, 0, -2);
                return 'Проивопоказания по приказу 29н: ' . $name;
            }
            else
            {
                return $name;
            }
        }
        else{
            if (!empty($name_research_id))
            {
                $caunt = count($name_research_id);
                for ($i = 0; $i <= $caunt - 1; $i++)
                {
                    $factor_svs = KindWork::find()->where(['id' => $name_research_id[$i]])->one();
                    if (!empty($factor_svs))
                    {
                        $name .= $factor_svs->unique_number . ', ';
                    }
                }
                $name = substr($name, 0, -2);
                return 'Приказ 302н, приложение 2, пункты ' . $name;
            }
            else
            {
                return $name;
            }
        }
    }

    public function get_profil_result($id)
    {
        $str = '';
        if(Yii::$app->user->identity->post == 'admin'){
            $patients = ListPatients::find()->
            select([
                'therapist.contraindications as therap_contraindications',
                'therapist.data_acceptance as therap_data',

                'neurologist.contraindications as neurologist_contraindications',
                'neurologist.data_acceptance as neurolog_data',

                'audiologist.contraindications as audiologist_contraindications',
                'audiologist.data_acceptance as audiolog_data',

                'oculist.date_inspection as oculist_date',
                'oculist.contraindications as oculist_contraindications',

                'narcology.contraindications as narcology_contraindications',

                'psychiatrist.contraindications as psychiatrist_contraindications',

                'gynecologist.contraindications as gynecologist_contraindications',
                'gynecologist.data_acceptance as gynecologist_data',

                'surgeon.contraindications as surgeon_contraindications',
                'surgeon.data_acceptance as surgeon_data',

                'dermatovenereologist.contraindications as dermatovenereologist_contraindications',

                'dentist.contraindications as dentist_contraindications',

                'professional_pathologist.contraindications as prof_contraindications',
            ])->
            leftJoin('therapist', 'list_patients.id = therapist.user_id')->
            leftJoin('neurologist', 'list_patients.id = neurologist.user_id')->
            leftJoin('audiologist', 'list_patients.id = audiologist.user_id')->
            leftJoin('oculist', 'list_patients.id = oculist.user_id')->
            leftJoin('narcology', 'list_patients.id = narcology.user_id')->
            leftJoin('psychiatrist', 'list_patients.id = psychiatrist.user_id')->
            leftJoin('gynecologist', 'list_patients.id = gynecologist.user_id')->
            leftJoin('surgeon', 'list_patients.id = surgeon.user_id')->
            leftJoin('dermatovenereologist', 'list_patients.id = dermatovenereologist.user_id')->
            leftJoin('dentist', 'list_patients.id = dentist.user_id')->
            leftJoin('professional_pathologist', 'list_patients.id = professional_pathologist.user_id')->
            where(['list_patients.id' => $id])->asArray()->
            one();
            $str .= '<div class="row">';
            $str .= '<div class="col-6">';
            if($patients['prof_contraindications'] != ''){
                $str .= '<b>Профпатолог:</b> ' . $this->get_type6($patients['prof_contraindications']).'<br>';
                /* if($patients['therap_data'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['therap_data'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр профпатолога не проводился или не завершен:</b> <br>';
            }
            if($patients['therap_contraindications'] != ''){
                $str .= '<b>Осмотр терапевта терапевта:</b> ' . $this->get_type6($patients['therap_contraindications']).'<br>';
                /* if($patients['therap_data'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['therap_data'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр терапевта не проводился или не завершен;</b>  <br>';
            }
            if($patients['neurologist_contraindications'] != ''){
                $str .= '<b>Осмотр невролога:</b> ' . $this->get_type6($patients['neurologist_contraindications']).'<br>';
                /* if($patients['neurolog_data'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['neurolog_data'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр невролога не проводился или не завершен;</b> <br>';
            }
            if($patients['audiologist_contraindications'] != ''){
                $str .= '<b>Осмотр лора:</b> ' . $this->get_type6($patients['audiologist_contraindications']).'<br>';
                /* if($patients['audiolog_data'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['audiolog_data'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр лора не проводился или не завершен;</b> <br>';
            }
            if($patients['oculist_contraindications'] != ''){
                $str .= '<b>Осмотр офтальмолога:</b> ' . $this->get_type6($patients['oculist_contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр офтальмолога не проводился или не завершен;</b> <br>';
            }
            if($patients['psychiatrist_contraindications'] != ''){
                $str .= '<b>Осмотр психиатра:</b> ' . $this->get_type6($patients['psychiatrist_contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр психиатра не проводился или не завершен;</b> '.'<br>';
            }
            $str .= '</div>';
            $str .= '<div class="col-6">';
            if($patients['gynecologist_contraindications'] != ''){
                $str .= '<b>Осмотр гинеколога:</b> ' . $this->get_type6($patients['gynecologist_contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр гинеколога не проводился или не завершен;</b> <br>';
            }
            if($patients['surgeon_contraindications'] != ''){
                $str .= '<b>Осмотр хирурга:</b> ' . $this->get_type6($patients['surgeon_contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр хирурга не проводился или не завершен;</b> <br>';
            }
            if($patients['dermatovenereologist_contraindications'] != ''){
                $str .= '<b>Осмотр дермотавенеролога:</b> ' . $this->get_type6($patients['dermatovenereologist_contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр дермотавенеролога не проводился или не завершен;</b> <br>';
            }
            if($patients['dentist_contraindications'] != ''){
                $str .= '<b>Осмотр стоматолога:</b> ' . $this->get_type6($patients['dentist_contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр стоматолога не проводился или не завершен;</b> <br>';
            }
            $str .= '</div>';
            $str .= '</div>';
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'nurse'){
            $str .= '';
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'физио'){
            $str .= '';
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'терапевт'){
            $patients = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр терапевта:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр терапевта не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'невролог'){
            $patients = Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр невролога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр невролога не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'офтальмолог'){
            $patients = Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр офтальмолога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр офтальмолога не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'отоларинголог'){
            $patients = Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр лора:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр лора не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'нарколог'){
            $patients = Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр нарколога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр нарколога не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'психиатр'){
            $patients = Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр психиатра:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр психиатра не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'хирург'){
            $patients = Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр хирурга:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр хирурга не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'стоматолог'){
            $patients = Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр стоматолога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр стоматолога не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'дерматовенеролог'){
            $patients = Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр дермотовенеролога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр дермотовенеролога не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->identity->post == 'гиниколог'){
            $patients = Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
            if($patients['contraindications'] != ''){
                $str .= '<b>Осмотр гинеколога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                /* if($patients['oculist_date'] != ''){
                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                 }*/
            }
            else{
                $str .= '<b>Осмотр гинеколога не проводился или не завершен;</b> ';
            }
            return $str;
        }
        elseif (Yii::$app->user->can('gldoctor')){

            $info = \common\models\DoctorsNeeded::find()->where(['user_id' => $id])->one();
            if(!empty($info)){
                if($info->field1 == '1'){
                    $patients = Therapist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр терапевта:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр терапевта не проводился или не завершен;</b> ';
                    }
                }
                if($info->field2 == '1'){
                    $patients = Neurologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр невролога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр невролога не проводился или не завершен;</b> ';
                    }
                }
                if($info->field3 == '1'){
                    $patients = Oculist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр офтальмолога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр офтальмолога не проводился или не завершен;</b> ';
                    }
                }
                if($info->field4 == '1'){
                    $patients = Audiologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр лора:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр лора не проводился или не завершен;</b> ';
                    }
                }
                if($info->field5 == '1'){
                    $patients = Narcology::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр нарколога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр нарколога не проводился или не завершен;</b> ';
                    }
                }
                if($info->field6 == '1'){
                    $patients = Psychiatrist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр психиатра:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр психиатра не проводился или не завершен;</b> ';
                    }
                }
                if($info->field7 == '1'){
                    $patients = Surgeon::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр хирурга:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр хирурга не проводился или не завершен;</b> ';
                    }
                }
                if($info->field8 == '1'){
                    $patients = Dentist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр стоматолога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр стоматолога не проводился или не завершен;</b> ';
                    }
                }
                if($info->field9 == '1'){
                    $patients = Dermatovenereologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр дермотовенеролога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр дермотовенеролога не проводился или не завершен;</b> ';
                    }
                }
                if($info->field10 == '1'){
                    $patients = Gynecologist::find()->where(['user_id' => $id, 'finish' => 0])->one();
                    if($patients['contraindications'] != ''){
                        $str .= '<b>Осмотр гинеколога:</b> ' . $this->get_type6($patients['contraindications']).'<br>';
                        /* if($patients['oculist_date'] != ''){
                             $str .= ' Дата осмотра: ' . $patients['oculist_date'];
                         }*/
                    }
                    else{
                        $str .= '<b>Осмотр гинеколога не проводился или не завершен;</b> ';
                    }
                }
            }

//            $patients = ListPatients::find()->
//            select([
//                'therapist.contraindications as therap_contraindications',
//                'therapist.data_acceptance as therap_data',
//
//                'neurologist.contraindications as neurologist_contraindications',
//                'neurologist.data_acceptance as neurolog_data',
//
//                'audiologist.contraindications as audiologist_contraindications',
//                'audiologist.data_acceptance as audiolog_data',
//
//                'oculist.date_inspection as oculist_date',
//                'oculist.contraindications as oculist_contraindications',
//
//                'narcology.contraindications as narcology_contraindications',
//
//                'psychiatrist.contraindications as psychiatrist_contraindications',
//
//                'gynecologist.contraindications as gynecologist_contraindications',
//                'gynecologist.data_acceptance as gynecologist_data',
//
//                'surgeon.contraindications as surgeon_contraindications',
//                'surgeon.data_acceptance as surgeon_data',
//
//                'dermatovenereologist.contraindications as dermatovenereologist_contraindications',
//
//                'dentist.contraindications as dentist_contraindications',
//
//                'professional_pathologist.contraindications as prof_contraindications',
//            ])->
//            leftJoin('therapist', 'list_patients.id = therapist.user_id')->
//            leftJoin('neurologist', 'list_patients.id = neurologist.user_id')->
//            leftJoin('audiologist', 'list_patients.id = audiologist.user_id')->
//            leftJoin('oculist', 'list_patients.id = oculist.user_id')->
//            leftJoin('narcology', 'list_patients.id = narcology.user_id')->
//            leftJoin('psychiatrist', 'list_patients.id = psychiatrist.user_id')->
//            leftJoin('gynecologist', 'list_patients.id = gynecologist.user_id')->
//            leftJoin('surgeon', 'list_patients.id = surgeon.user_id')->
//            leftJoin('dermatovenereologist', 'list_patients.id = dermatovenereologist.user_id')->
//            leftJoin('dentist', 'list_patients.id = dentist.user_id')->
//            leftJoin('professional_pathologist', 'list_patients.id = professional_pathologist.user_id')->
//            where(['list_patients.id' => $id])->asArray()->
//            one();
//            $str .= '<div class="row">';
//            $str .= '<div class="col-6">';
//            if($patients['prof_contraindications'] != ''){
//                $str .= '<b>Профпатолог:</b> ' . $this->get_type6($patients['prof_contraindications']).'<br>';
//                /* if($patients['therap_data'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['therap_data'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр профпатолога не проводился или не завершен:</b> <br>';
//            }
//            if($patients['therap_contraindications'] != ''){
//                $str .= '<b>Осмотр терапевта терапевта:</b> ' . $this->get_type6($patients['therap_contraindications']).'<br>';
//                /* if($patients['therap_data'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['therap_data'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр терапевта не проводился или не завершен;</b>  <br>';
//            }
//            if($patients['neurologist_contraindications'] != ''){
//                $str .= '<b>Осмотр невролога:</b> ' . $this->get_type6($patients['neurologist_contraindications']).'<br>';
//                /* if($patients['neurolog_data'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['neurolog_data'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр невролога не проводился или не завершен;</b> <br>';
//            }
//            if($patients['audiologist_contraindications'] != ''){
//                $str .= '<b>Осмотр лора:</b> ' . $this->get_type6($patients['audiologist_contraindications']).'<br>';
//                /* if($patients['audiolog_data'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['audiolog_data'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр лора не проводился или не завершен;</b> <br>';
//            }
//            if($patients['oculist_contraindications'] != ''){
//                $str .= '<b>Осмотр офтальмолога:</b> ' . $this->get_type6($patients['oculist_contraindications']).'<br>';
//                /* if($patients['oculist_date'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр офтальмолога не проводился или не завершен;</b> <br>';
//            }
//            if($patients['psychiatrist_contraindications'] != ''){
//                $str .= '<b>Осмотр психиатра:</b> ' . $this->get_type6($patients['psychiatrist_contraindications']).'<br>';
//                /* if($patients['oculist_date'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр психиатра не проводился или не завершен;</b> '.'<br>';
//            }
//            $str .= '</div>';
//            $str .= '<div class="col-6">';
//            if($patients['gynecologist_contraindications'] != ''){
//                $str .= '<b>Осмотр гинеколога:</b> ' . $this->get_type6($patients['gynecologist_contraindications']).'<br>';
//                /* if($patients['oculist_date'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр гинеколога не проводился или не завершен;</b> <br>';
//            }
//            if($patients['surgeon_contraindications'] != ''){
//                $str .= '<b>Осмотр хирурга:</b> ' . $this->get_type6($patients['surgeon_contraindications']).'<br>';
//                /* if($patients['oculist_date'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр хирурга не проводился или не завершен;</b> <br>';
//            }
//            if($patients['dermatovenereologist_contraindications'] != ''){
//                $str .= '<b>Осмотр дермотавенеролога:</b> ' . $this->get_type6($patients['dermatovenereologist_contraindications']).'<br>';
//                /* if($patients['oculist_date'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр дермотавенеролога не проводился или не завершен;</b> <br>';
//            }
//            if($patients['dentist_contraindications'] != ''){
//                $str .= '<b>Осмотр стоматолога:</b> ' . $this->get_type6($patients['dentist_contraindications']).'<br>';
//                /* if($patients['oculist_date'] != ''){
//                     $str .= ' Дата осмотра: ' . $patients['oculist_date'];
//                 }*/
//            }
//            else{
//                $str .= '<b>Осмотр стоматолога не проводился или не завершен;</b> <br>';
//            }
//            $str .= '</div>';
//            $str .= '</div>';
            return $str;
        }
    }

    public function get_data($id)
    {
        $municipality = ListPatients::findOne($id);

        return $municipality->data_p;

    }
    public function get_research_print($id, $name)
    {
        $research = '<span  style="color: #83c7ec"><b>неизвестное исследования</b></span>';
        if($name == 'специфическая аллергодиагностика'){
            return $research;
        }
        elseif ($name == 'спирометрия с бронходилятационной пробой'){
            return $research;
        }
        elseif ($name == 'ретикулоциты'){
            $model = BloodTest::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'спирометрия'){
            $model = RespiratoryFunction::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'исследование крови на сифилис'){
            $model = BloodTest::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                if($model->wassermann_reaction != ''){
                    return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
                }else{
                    return '<span style="color: #cd2055"><b>не пройдено</b></span>';
                }
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'метгемоглобин'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                if($model->methemoglobin != ''){
                    return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
                }else{
                    return '<span style="color: #cd2055"><b>не пройдено</b></span>';
                }
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'карбоксигемоглобин'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                if($model->carboxyhemoglobin != ''){
                    return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
                }else{
                    return '<span style="color: #cd2055"><b>не пройдено</b></span>';
                }
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ органов-мишеней'){
            $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ органов малого таза'){
            $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ органов брюшной полости'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ почек и мочевыделительной системы'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ щитовидной железы'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ  почек'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'РВГ (УЗИ) периферических сосудов'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ периферических сосудов'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ печени'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ желчного пузыря'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ поджелудочной железы'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ селезенки'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'УЗИ предстательной железы для лиц старше 40 лет'){
             $model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'рентгенография грудной клетки в двух проекциях 1 раз в 2 года'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'спирометрия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'количественное содержание а1-антитрипсин'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'биомикроскопия переднего отрезка глаза'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'пульсоксиметрия'){
            $model = Therapist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'АЛК(аминолевулиновая кислота)в моче'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'метгемоглобин'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'базофильная зернистость эритроцитов'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенография длинных трубчатых костей 1 раз в 4 года'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'билирубин'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'остеоденситометрия длинных трубчатых костей'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'остеоденситометрия длинных трубчатых костей'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенография длинных трубчатых костей'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'ЩФ(щелочная фосфатаза)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'определение фтора в моче'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Тельца Гейнца'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'время кровотечения'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ЭНМГ(электронейромиография)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ЭЭГ(электроэнцефалография)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенография длинных трубчатых костей после консультации специалистов'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'медь в крови'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'офтальмоскопия глазного дна'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'психологическое тестирование'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'биомикроскопия переднего отрезка и хрусталика глаза'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'тонометрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'периметрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'АЛК или КП в моче'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'свинец в крови'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'поля зрения'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'острота зрения'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ГГТП(гамма-глютамилтранспептидаза)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'карбоксигемоглобин'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'цистоскопия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенография кистей'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'РВГ(реовазография)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенография трубчатых костей 1 раз в 5 лет'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'холинэстераза'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'анализ мочи на ртуть'){
            $model = UrineTest::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'анализ крови на ртуть'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенография кистей 1 раз в 4 года'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'микологические исследования'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'гормональный профиль'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'микроскопия мокроты'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ВИЧ (при согласии работника)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'анализ кала на яйца гельминтов'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'специфические диагностические исследования'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'микроскопия мокроты на БК трехкратно'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследования на гельминтозы и протозоозы'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'острота зрения с коррекцией и без нее'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'маммография (женщины)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'офтальмотонометрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'скиаскопия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рефрактометрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'объем аккомодации'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследование бинокулярного зрения'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'цветоощущение'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'паллестезиометрия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'холодовая проба'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследование вестибулярного анализатора'){
            $model = Audiologist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'капилляроскопия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'острота зрения с коррекцией'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'аудиометрия'){
            $model = Audiogram::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'электотермометрия ЭТМ'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рентгенографическое исследование околоносовых пазух'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'рентгенография суставов, позвоночника'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'ЭКГ'){
            $model = Ecg::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'коагулограмма: ПТИ, АЧТВ, фибриноген, РФМК, протромбиновое время, тромбиновое время, время кровотечения'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'динамометрия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'a-HBCOR IgM'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'A-HCV-IgG'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'HBsAg'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'анти-HBc-Ig (суммарные) анти-HCV-Ig (суммарные)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'анти-HCV-Ig (суммарные)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ИФА HCV-Ag/At'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ИФА HIV-Ag/At'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'АЛТ(Аланинаминотрансфераза)'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model->alt)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'АСТ(аспартатаминотрансфераза)'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model->ast)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'мочевина крови'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'креатинин крови'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model->ast)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'рентгенография грудной клетки в двух проекциях'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'глюкоза крови'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'холестерин'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'калий'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'натрий'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'кальций'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'КП (копропорфирины)мочи'){
            $model = UrineTest::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'исследования уровня Т3'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследования уровня Т4'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ТТГ(тиреотропный гормон)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'объем аккомодации для лиц моложе 40 лет'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'реовазография сосудов конечностей'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ФГДС'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследование крови на сифилис'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследования на носительство возбудителей кишечных инфекций и серологическое обследование на брюшной тиф'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследования на гельминтозы при поступлении на работу'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'мазок из зева и носа на наличие патогенного стафилококка'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'мазки на гонорею'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'мазок из зева и носа на наличие патогенного стафилококка'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'мазки на гонорею при поступлении на работу'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'рост, вес, определение группы крови и резус-фактора (при прохождении предварительного медицинского осмотра)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'общий анализ крови'){
            $model = BloodTest::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'общий анализ мочи'){
            $model = UrineTest::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'рентген органов грудной клетки или ФЛГ'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ММГ(маммография)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'мазки на микрофлору и цитологию'){
            $model = Microflora::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'офтальмотонометрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'биомикроскопия глаза'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'визометрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'офтальмоскопия глазного дна'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'биомикроскопия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'Реакция агглютинации Хеддельсона крови при контакте с возбудителями бруцеллеза'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'определение уровня щелочной фосфатазы'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'тромбоцитов в крови'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Психофизиологическое исследование'){
            $model = PsychicState::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'Непрямая ларингоскопия'){
            $model = Audiologist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'Измерение внутриглазного давления'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'фиброгастродуоденоскопия'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Тонометрия'){
            $model = Oculist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'определение группы крови и резус-фактора'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Исследование уровня аспартат-трансаминазы и аланин-трансаминазы'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследование крови на сифилис'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Определение в крови иммуноглобулин M'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'A-HCV'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'иммуноглобулин G'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Определение (исследование) устойчивости организма к декомпрессионному газообразованию'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Велоэргометрия для лиц старше 40 лет'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'вирусные гепатиты B и C (при предварительном осмотре)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'ВИЧ (при согласии работника) при поступлении на работу'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Исследование барофункции уха при поступлении на работу'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследование вестибулярного анализатора при поступлении на работу'){
            $model = Audiologist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'исследование вестибулярного анализатора при поступлении на работу'){
            $model = Audiologist::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'Исследование уровня холестерина в крови для лиц моложе 40 лет'){
            $model = GeneralBiochemicalAnalysis::find()->where(['user_id'=>$id])->one();
            if(!empty($model->ast)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'Исследования на носительство возбудителей кишечных инфекций и серологическое обследование на брюшной тиф при поступлении на работу'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Определение (исследование) устойчивости организма к наркотическому действию азота (при предварительном медицинском осмотре для работников, работающих на глубинах более 40 м)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Определение (исследование) устойчивости организма к наркотическому действию азота (при предварительном медицинском осмотре)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Определение (исследование) устойчивости организма к токсическому действию кислорода (при предварительном медицинском осмотре для работников, выполняющих водолазные работы на глубинах более 40 метров или с применением для дыхания искусственных дыхательных газовых смесей)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Определение (исследование) устойчивости организма к токсическому действию кислорода (при предварительном медицинском осмотре)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'определение группы крови и резус-фактора при поступлении на работу'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Ортостатическая проба (при предварительном медицинском осмотре)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'Рентгенография околоносовых пазух (при предварительном медицинском осмотре)'){
            $model = Roentgen::find()->where(['user_id'=>$id])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }
        }
        elseif ($name == 'Эхокардиография (при предварительном медицинском осмотре)'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        elseif ($name == 'исследования на гельминтозы'){
            /*$model = Ultrasound::find()->where(['user_id'=>$id, 'name'=>$name])->one();
            if(!empty($model)){
                return '<span  style="color: #6fe33f"><b>пройдено</b></span>';
            }
            else{
                return '<span style="color: #cd2055"><b>не пройдено</b></span>';
            }*/
            return $research;
        }
        else{
            return $research;
        }
    }
}


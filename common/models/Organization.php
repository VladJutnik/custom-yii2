<?php

namespace common\models;

use Yii;

class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'title',
                'federal_district_id',
                'region_id',
                'municipality_id',
            ], 'required'],
            [[
                'title',
                'address',
                'phone',
                'email',
                'inn',
                'status',
                'print_name',
                'status_print',
                'status_veiws',
                'user_id',
            ], 'safe'],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование организации',
            'address' => 'Юридический адрес',
            'federal_district_id' => 'Федеральный округ',
            'region_id' => 'Субъект федерации',
            'municipality_id' => 'Муниципальное образование',
            'phone' => 'Телефон',
            'email' => 'Электронная почта',
            'inn' => 'ИНН',
        ];
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            $this->user_id = Yii::$app->user->identity->id;
            return true;
        }
        return false;
    }
}

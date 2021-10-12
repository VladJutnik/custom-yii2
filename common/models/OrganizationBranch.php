<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "organization_branch".
 *
 * @property int $id
 * @property int $organization_id
 * @property string $name_branch
 * @property int $federal_district_id
 * @property int $municipality_id
 * @property int $region_id
 * @property int $number_employees число сотрудников
 * @property string $phone
 * @property string $email
 * @property string $inn
 * @property int $status_veiws
 * @property string $created_at
 */
class OrganizationBranch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization_branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organization_id', 'name_branch', 'federal_district_id', 'municipality_id', 'region_id', 'number_employees', 'phone', 'email', 'inn', 'status_veiws'], 'required'],
            [['organization_id', 'federal_district_id', 'municipality_id', 'region_id', 'number_employees', 'status_veiws'], 'integer'],
            [['created_at'], 'safe'],
            [['name_branch', 'phone', 'email', 'inn'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'name_branch' => 'Name Branch',
            'federal_district_id' => 'Federal District ID',
            'municipality_id' => 'Municipality ID',
            'region_id' => 'Region ID',
            'number_employees' => 'Number Employees',
            'phone' => 'Phone',
            'email' => 'Email',
            'inn' => 'Inn',
            'status_veiws' => 'Status Veiws',
            'created_at' => 'Created At',
        ];
    }
}

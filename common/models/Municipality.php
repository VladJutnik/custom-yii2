<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "municipality".
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 */
class Municipality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'municipality';
    }

    /**
    * @return \yii\db\Connection the database connection used by this AR class.
    */
    public static function getDb()
    {
        return Yii::$app->get('db_constantly');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id', 'name'], 'required'],
            [['region_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_id' => 'Регион',
            'name' => 'Название муниципального образования',
        ];
    }

    public function get_region($id){
        return Region::findOne($id)->name;
    }

    public function get_municipality($id){
        return Region::findOne($id)->name;
    }
}

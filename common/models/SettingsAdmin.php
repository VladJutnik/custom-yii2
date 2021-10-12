<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings_admin".
 *
 * @property int $id
 * @property int $recipe_id
 */
class SettingsAdmin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings_admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipe_id'], 'required'],
            [['recipe_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipe_id' => 'Активный сборник',
        ];
    }

    public function get_recipes($id){
        $d = RecipesCollection::findOne($id);
        return $d->name;
    }
}

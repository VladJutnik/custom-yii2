<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "directory_activity".
 *
 * @property int $id
 * @property string $login_created
 * @property string $name
 * @property int|null $status_view
 * @property string $creat_at
 */
class DirectoryActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'directory_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status_view'], 'integer'],
            [['login_created', 'creat_at'], 'safe'],
            [['login_created'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 450],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login_created' => 'Кто добавил',
            'name' => 'Название',
            'status_view' => 'Статус отображения',
            'creat_at' => 'Дата добавления',
        ];
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            $this->login_created = Yii::$app->user->identity->id;
            return true;
        }
        return false;
    }
}

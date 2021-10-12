<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setings".
 *
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property string $licenses
 * @property string $ogrn_code
 * @property string $version
 * @property string $therapist
 * @property string $neurologist
 * @property string $ophthalmologist
 * @property string $surdologist
 * @property string $psychiatrist
 * @property string $narcologist
 * @property string $gynecologist
 * @property string $surgeon
 * @property string $dermatovenerologist
 * @property string $dentist
 */
class Setings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telephone', 'address', 'name', 'short_name', 'licenses', 'ogrn_code', 'version'], 'required'],
            [['name', 'short_name'], 'string', 'max' => 350],
            [['licenses', 'mail', 'inn', 'fax_number'], 'string', 'max' => 250],
            [['ogrn_code'], 'string', 'max' => 14],
            [['version', 'therapist', 'neurologist', 'ophthalmologist', 'surdologist', 'psychiatrist', 'narcologist', 'gynecologist', 'surgeon', 'dermatovenerologist', 'dentist'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название организации',
            'short_name' => 'Короткое название',
            'address' => 'Адрес организации',
            'licenses' => 'Медецинская лицнезия',
            'telephone' => 'Телефон организации',
            'mail' => 'Почта организации',
            'ogrn_code' => 'Код ОГРН организации',
            'inn' => 'ИНН/КПП',
            'fax_number' => 'Факс организации',
            'version' => 'Версия программы',
            'therapist' => 'Короткий осмотр Терапевта',
            'neurologist' => 'Короткий осмотр Невролога',
            'ophthalmologist' => 'Короткий осмотр Офтальмолога',
            'surdologist' => 'Короткий осмотр Отоларинголога',
            'psychiatrist' => 'Короткий осмотр Психиатра',
            'narcologist' => 'Короткий осмотр Нарколога',
            'gynecologist' => 'Короткий осмотр Гинеколога',
            'surgeon' => 'Короткий осмотр Хирурга',
            'dermatovenerologist' => 'Короткий осмотр Дермотовенеролога',
            'dentist' => 'Короткий осмотр Стоматолог',
        ];
    }
}

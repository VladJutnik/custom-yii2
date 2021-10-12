<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $pin_slider свернуть/показать боковое меню 0-закрепить 1-скрыть
 * @property int|null $topic тема 0-светлая 1-темная
 * @property int|null $dark_slider темный слайдер 0-светлая 1-темная
 * @property int|null $dark_icons темные иконки 0-светлая 1-темная
 * @property string $creat_at
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pin_slider', 'topic', 'dark_slider', 'dark_icons', 'creat_at'], 'safe'], //поправить правила!!!!
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'pin_slider' => 'Боковое меню: ',
            'topic' => 'Тема: ',
            'dark_slider' => 'Только темный боковой слайдер: ',
            'dark_icons' => 'Иконки слайдера: ',
            'creat_at' => 'Creat At',
        ];
    }

    public function deletfile($directory, $filename)
    {
        // открываем директорию (получаем дескриптор директории)
        $dir = opendir($directory);

        // считываем содержание директории
        while(($file = readdir($dir)))
        {
            // Если это файл и он равен удаляемому ...
            if((is_file("$directory/$file")) && ("$directory/$file" == "$directory/$filename"))
            {
                // ...удаляем его.
                unlink("$directory/$file");

                // Если файла нет по запрошенному пути, возвращаем TRUE - значит файл удалён.
                if(!file_exists($directory."/".$filename)) return $s = TRUE;
            }
        }
        // Закрываем дескриптор директории.
        closedir($dir);
    }

}

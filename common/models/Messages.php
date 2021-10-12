<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $incoming_msg_id
 * @property int $outgoing_msg_id
 * @property string $msg
 * @property int $view_status
 * @property string $created_at
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['incoming_msg_id', 'outgoing_msg_id', 'msg', 'view_status', 'created_at'], 'required'],
            [['incoming_msg_id', 'outgoing_msg_id', 'view_status'], 'integer'],
            [['msg'], 'string'],
            [['created_at'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'incoming_msg_id' => 'Incoming Msg ID',
            'outgoing_msg_id' => 'Outgoing Msg ID',
            'msg' => 'Msg',
            'view_status' => 'View Status',
            'created_at' => 'Created At',
        ];
    }
}

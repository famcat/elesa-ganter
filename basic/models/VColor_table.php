<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_color_table".
 *
 * @property integer $production_id
 */
class VColor_table extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_color_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['production_id'], 'required'],
            [['production_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'production_id' => 'Production ID',
        ];
    }
}

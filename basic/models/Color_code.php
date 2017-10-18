<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color_code".
 *
 * @property integer $id
 * @property integer $color_list_id
 * @property string $color_article
 * @property string $color_description
 *
 * @property ColorList $colorList
 */
class Color_code extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color_list_id'], 'required'],
            [['color_list_id'], 'integer'],
            [['color_article','color_description'], 'string', 'max' => 255],
            [['color_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color_list::className(), 'targetAttribute' => ['color_list_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color_list_id' => 'Color List ID',
            'color_article' => 'Color Article',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorList()
    {
        return $this->hasOne(Color_list::className(), ['id' => 'color_list_id']);
    }
}

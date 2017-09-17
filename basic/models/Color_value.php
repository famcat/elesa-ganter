<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color_value".
 *
 * @property integer $id
 * @property integer $color_list_id
 * @property integer $color_field_id
 * @property string $color_value
 *
 * @property ColorField $colorField
 * @property ColorList $colorList
 */
class Color_value extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color_list_id', 'color_field_id'], 'integer'],
            [['color_value'], 'string', 'max' => 255],
            [['color_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorField::className(), 'targetAttribute' => ['color_field_id' => 'id']],
            [['color_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorList::className(), 'targetAttribute' => ['color_list_id' => 'id']],
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
            'color_field_id' => 'Color Field ID',
            'color_value' => 'Color Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorField()
    {
        return $this->hasOne(ColorField::className(), ['id' => 'color_field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorList()
    {
        return $this->hasOne(ColorList::className(), ['id' => 'color_list_id']);
    }
}

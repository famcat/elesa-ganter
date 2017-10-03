<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color_list".
 *
 * @property integer $id
 * @property integer $production_id
 * @property string $color_name
 * @property string $color_code
 * @property string $color_hex
 *
 * @property Productions $production
 * @property ColorValue[] $colorValues
 */
class Color_list extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['production_id'], 'required'],
            [['production_id'], 'integer'],
            [['color_name', 'color_code', 'color_hex'], 'string', 'max' => 255],
            [['production_id'], 'exist', 'skipOnError' => true, 'targetClass' => Productions::className(), 'targetAttribute' => ['production_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'production_id' => 'Production ID',
            'color_name' => 'Color Name',
            'color_code' => 'Color Code',
            'color_hex' => 'Color Hex',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduction()
    {
        return $this->hasOne(Productions::className(), ['id' => 'production_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorValues()
    {
        return $this->hasMany(ColorValue::className(), ['color_list_id' => 'id']);
    }
}

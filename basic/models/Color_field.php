<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color_field".
 *
 * @property integer $id
 * @property integer $production_id
 * @property string $name
 *
 * @property Productions $production
 * @property ColorValue[] $colorValues
 */
class Color_field extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['production_id'], 'required'],
            [['production_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
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
        return $this->hasMany(ColorValue::className(), ['color_field_id' => 'id']);
    }
}

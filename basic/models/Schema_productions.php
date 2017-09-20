<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schema_productions".
 *
 * @property integer $id
 * @property string $name_schema
 * @property integer $production_id
 * @property string $schema_id
 * @property string $img_production_url
 * @property string $img_schema
 * @property string $expires
 *
 * @property Productions $production
 */
class Schema_productions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schema_productions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_schema'], 'string'],
            [['production_id'], 'required'],
            [['production_id'], 'integer'],
            [['expires'], 'safe'],
            [['schema_id', 'img_production_url', 'img_schema'], 'string', 'max' => 255],
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
            'name_schema' => 'Name Schema',
            'production_id' => 'Production ID',
            'schema_id' => 'Schema ID',
            'img_production_url' => 'Img Production Url',
            'img_schema' => 'Img Schema',
            'expires' => 'Expires',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduction()
    {
        return $this->hasOne(Productions::className(), ['id' => 'production_id']);
    }
}

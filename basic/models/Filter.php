<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filter".
 *
 * @property integer $id
 * @property integer $schema_id
 * @property integer $production_id
 * @property string $name
 *
 * @property Productions $production
 */
class Filter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schema_id', 'production_id'], 'integer'],
            [['production_id'], 'required'],
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
            'schema_id' => 'Schema ID',
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
}

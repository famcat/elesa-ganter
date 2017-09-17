<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "productions".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $materail
 * @property string $url
 * @property string $full_description
 * @property string $img_url
 * @property integer $types_id
 *
 * @property Types $types
 */
class Productions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'productions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'types_id'], 'required'],
            [['description', 'full_description'], 'string'],
            [['types_id'], 'integer'],
            [['name', 'materail', 'url', 'img_url'], 'string', 'max' => 255],
            [['types_id'], 'exist', 'skipOnError' => true, 'targetClass' => Types::className(), 'targetAttribute' => ['types_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'materail' => 'Materail',
            'url' => 'Url',
            'full_description' => 'Full Description',
            'img_url' => 'Img Url',
            'types_id' => 'Types ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasOne(Types::className(), ['id' => 'types_id']);
    }
}

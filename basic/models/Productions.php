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
 * @property string $expires
 *
 * @property ColorField[] $colorFields
 * @property ColorList[] $colorLists
 * @property Filter[] $filters
 * @property FilterArticle[] $filterArticles
 * @property Types $types
 * @property SchemaProductions[] $schemaProductions
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
            [['expires'], 'safe'],
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
            'expires' => 'Expires',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorFields()
    {
        return $this->hasMany(ColorField::className(), ['production_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorLists()
    {
        return $this->hasMany(ColorList::className(), ['production_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(Filter::className(), ['production_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilterArticles()
    {
        return $this->hasMany(FilterArticle::className(), ['production_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasOne(Types::className(), ['id' => 'types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchemaProductions()
    {
        return $this->hasMany(SchemaProductions::className(), ['production_id' => 'id']);
    }
}

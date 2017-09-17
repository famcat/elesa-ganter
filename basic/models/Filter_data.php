<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filter_data".
 *
 * @property integer $id
 * @property integer $filter_article_id
 * @property integer $filter_id
 * @property string $value
 */
class Filter_data extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filter_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filter_article_id', 'filter_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filter_article_id' => 'Filter Article ID',
            'filter_id' => 'Filter ID',
            'value' => 'Value',
        ];
    }
}

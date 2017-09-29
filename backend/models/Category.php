<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }
	public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 512],
			[['image'], 'safe'],
			[['image'], 'file'],
			
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
            'image' => 'Image',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	public function getDisplayImage() {
                if(empty($this->image))
                {
                    $no_preview = Yii::$app->request->baseUrl .'/admin-lte/dist/img/no_image.png';
                    $html = '<div >';
                    $html .= "<img  height='100px' src='" . $no_preview . "' alt= No Image'/>";
                    $html .= '</div>';
                    return $html;
                }
                else
                {
                    $html = '<div >';
                    $html .= "<img  height='100px' src='" . $this->image . "' alt=Category Image' />";
                    $html .= '</div>';
                    return $html;
                }
    }
}

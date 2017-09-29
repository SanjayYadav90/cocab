<?php

namespace backend\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_brand_name".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductBrandName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_brand_name';
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
            [['name','status'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
			[['image'], 'safe'],
            [['image'], 'file'],
            [['name'], 'unique'],
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
			$html .= "<img  height='100px' src='" . $no_preview . "' alt= Brand Logo'/>";
			$html .= '</div>';
			return $html;
		}
		else
		{
			$logo = "Brand Logo";
			$html = '<div >';
			$html .= "<img  height='100px' src='" . $this->image . "' alt='" . $logo ." />";
			$html .= '</div>';
			return $html;
		}
    }
}

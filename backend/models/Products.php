<?php

namespace backend\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property string $image
 * @property string $image_name
 * @property string $status
 * @property integer $cat_id
 * @property integer $brand_id
 * @property integer $delivery_slot_id
 * @property string $product_filter
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
use yii\behaviors\TimestampBehavior;



class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
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
            [['name','cat_id'], 'required'],
            [['price'], 'number'],
            [['created_at','cat_id','status','updated_at', 'delivery_slot_id', 'brand_id','popularity', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 512],
            [['image','product_filter'], 'safe'],
            [['image'], 'file'],
            [['image_name'], 'string', 'max' => 100],
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
            'price' => 'Price',
            'image' => 'Image',
			'status' =>'Status',
			'cat_id' => 'Category',
			'delivery_slot_id'=>'Delivery Slot',
			'brand_id'=>'Brand Name',
			'product_filter'=>'Product Filter/Tag',
			'popularity' =>'Popularity',
            'image_name' => 'Image Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
			$html .= "<img  height='100px' src='" . $this->image . "' alt='" . $this->image_name ." />";
			$html .= '</div>';
			return $html;
		}
    }
	public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'cat_id']);
    }
	
	public function getBrand()
    {
        return $this->hasOne(ProductBrandName::className(), ['id' => 'brand_id']);
    }
	
	public function getDeliverySlot()
    {
        return $this->hasOne(DeliverySlotName::className(), ['id' => 'delivery_slot_id']);
    }
	
	/* public function getFilter()
    {
        return $this->hasOne(ProductFilter::className(), ['id' => 'product_filter']);
    } */
}

<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\MsgTemplateSetting;

/**
 * This is the model class for table "sms_template".
 *
 * @property int $id
 * @property string $template_cat
 * @property string $name
 * @property string $body
 * @property string $template_key
 * @property string $type
 * @property int $created_at
 * @property int $updated_at
 */
class SmsTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_template';
    }
	
	/**
     * @inheritdoc
     */
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
            [['template_cat', 'name', 'body', 'template_key', 'type'], 'required'],
            [['created_at', 'updated_at','type'], 'integer'],
            [['template_cat'], 'string', 'max' => 50],
            [['name', 'template_key'], 'string', 'max' => 64],
            [['body'], 'string', 'max' => 1024],
            [['name'], 'unique'],
            [['template_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_cat' => 'Template Category',
            'name' => 'Name',
            'body' => 'Body',
            'template_key' => 'Template Key',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	static public function getSmsBody($template_key)
	{
		//$student = Student::findOne($stu_id);
        $template = SmsTemplate::find()->where(['template_key' => $template_key])->one();
		
		//Replace Organization fields
		$org 	= Organisation::findOne($student->org_id);
		self::AddressMap($template,$org->address_id,'org');
		foreach(MsgTemplateSetting::$ORG_MAP as $key => $value)
		{
			$template['body'] = str_replace($key, $org->{$value}, $template['body']);
				
		}
		
		//Replace Students fields
		
		self::AddressMap($template,$student->address_id,'stu');
		foreach(MsgTemplateSetting::$STUDENT_MAP as $key => $value)
		{
			if($value == 'name')
			{	
				$stu_class = StudentClass::findOne($student->class_id);	
				$template['body'] = str_replace($key, $stu_class->{$value}, $template['body']);
			}
			else if($value == 'section')
			{	
				$section = StudentSection::findOne($student->section_id);	
				$template['body'] = str_replace($key, $section->{$value}, $template['body']);
			}
			else
			{
				$template['body'] = str_replace($key, $student->{$value}, $template['body']);
			}
			
		}
		
		//Replace coordinates fields
		$coordinate = RouteCoordinate::findOne($student->route_coordinate_id);
        if(isset($coordinate) && !empty($coordinate))
        { 
    		foreach(MsgTemplateSetting::$COORDINATE_MAP as $key => $value)
    		{
    			$template['body'] = str_replace($key, $coordinate->{$value}, $template['body']);
    		}
		}
		
		//Replace Teacher fields
		$teacher = DtmeTeacher::findOne($teacher_id);
        if(isset($teacher) && !empty($teacher))
        { 
		  foreach(MsgTemplateSetting::$TEACHER_MAP as $key => $value)
		  {
		   $template['body'] = str_replace($key, $teacher->{$value}, $template['body']);
		  }
		}
		
		//Replace Subject fields
		//$subject = DtmeSubject::findOne($subject_id);
        if(isset($subject_name) && !empty($subject_name))
        { 
		  foreach(MsgTemplateSetting::$SUBJECT_MAP as $key => $value)
		  {
		   $template['body'] = str_replace($key, $subject_name, $template['body']);
		  }
		}
		
		return $template;
		
	}
	
}

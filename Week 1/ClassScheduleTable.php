<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClassScheduleTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->belongsTo("HealthMember",['foreignKey'=>"assign_staff_mem"]);
		$this->hasMany("HealthNotice",["foreignKey"=>"class_id","dependent"=>true]); // it will also delete all notice for deleted class_id
		$this->belongsTo("ClassScheduleList");
		$this->belongsTo("HealthMemberClass");
	}
}
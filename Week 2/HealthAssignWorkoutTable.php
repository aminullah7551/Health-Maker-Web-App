<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class HealthAssignWorkoutTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("HealthMember",["foreignKey"=>"user_id"]);
		$this->belongsTo("HealthLevels");
		$this->belongsTo("Activity");
		$this->belongsTo("Category");
		$this->hasMany("HealthWorkoutData");		
	}
}
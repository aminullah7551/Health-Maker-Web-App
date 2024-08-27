<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class HealthDailyWorkoutTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("Activity");
		$this->belongsTo("HealthMeasurement");
		$this->belongsTo("HealthMember");
		$this->belongsTo("HealthUserWorkout");
		$this->belongsTo("HealthAssignWorkout");
		$this->belongsTo("HealthWorkoutData");
	}
	
/* 	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->isUnique(['member_id','record_date'],'Record for this date already exists.'));
		return $rules;
	} */
}


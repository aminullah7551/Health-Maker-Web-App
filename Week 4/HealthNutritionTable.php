<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class HealthNutritionTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("HealthMember",["foreignKey"=>"user_id"]);		
		$this->belongsTo("HealthNutritionData");		
	}
}
<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class HealthMessageTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("ClassSchedule");
		$this->belongsTo("HealthMember",["foreignKey"=>"sender"]);
	}
}
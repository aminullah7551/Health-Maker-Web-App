<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class HealthNoticeTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");		
		$this->belongsTo("HealthMember");
		$this->belongsTo("ClassSchedule");
	}
}
<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class HealthAttendanceTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("ClassSchedule");
		$this->belongsTo("HealthMember");
		$this->belongsTo("HealthMemberClass");
		// $this->belongsTo("StaffMembers"); 		
	}
}
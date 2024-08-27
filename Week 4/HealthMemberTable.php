<?php
namespace App\Model\Table;
use Cake\ORM\Table;
/* use Cake\Validation\Validator; */
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

Class HealthMemberTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		// $this->belongsTo("StaffMembers",["foreignKey"=>"assign_staff_mem"]);
		$this->belongsTo("ClassSchedule",["foreignKey"=>"assign_class"]);
		$this->belongsTo("HealthGroup",["foreignKey"=>"assign_group"]);
		$this->belongsTo("HealthInterestArea",["foreignKey"=>"intrested_area"]);
		$this->belongsTo("HealthSource",["foreignKey"=>"g_source"]);
		$this->belongsTo("Membership",["foreignKey"=>"selected_membership"]);
		$this->belongsTo("MembershipHistory");
		$this->belongsTo("MembershipPayment");
		$this->belongsTo("HealthAttendance");
		$this->belongsTo("HealthMeasurement");
		$this->belongsTo("HealthMemberClass",["targetForeignKey"=>"member_id"]);
		/* // $this->belongsTo("HealthMemberClass"); */
		$this->BelongsTo("HealthRoles",["foreignKey"=>"role"]); //for staffmember
		$this->BelongsTo("Specialization",["propertyName"=>"specialization"]); //for staffmember
		/* 
		// $this->belongsTo("Installment_Plan",[
											// "foreignKey"=>"install_plan_id",
											// "propertyName"=>'duration'
											// ]);
		// $this->belongsTo("Activity");	
		// $this->hasMany("Membership_Activity",["foreignKey"=>"membership_id"]); */
	}
	
	
	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->isUnique(['email'],'Email-id already in use.'));
		$rules->add($rules->isUnique(['username'],'Username already in use.')); /*  MOVED TO LOGIN TABLE - REMOVE */ 
		return $rules;
	} 
}
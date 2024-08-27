<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class MemberRegistrationTable extends Table{
	
	public function initialize(array $config)
	{	
		$this->BelongsTo("HealthMember");		
		$this->BelongsTo("HealthMemberClass");		
		$this->BelongsTo("MembershipPayment");		
	}
}
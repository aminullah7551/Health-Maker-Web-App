<?php
namespace App\Model\Table;
use Cake\ORM\Table;
// use Cake\Validation\Validator;

Class HealthAccountantTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		// $this->BelongsTo("HealthRoles",["foreignKey"=>"role"]);
		$this->BelongsTo("HealthMember");
		// $this->BelongsTo("Specialization",["propertyName"=>"specialization"]);
	}
}
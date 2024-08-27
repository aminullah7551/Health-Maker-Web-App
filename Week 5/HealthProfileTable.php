<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class HealthProfileTable extends Table{
	public function initialize(array $config)
	{
		$this->BelongsTo("HealthMember");
		$this->BelongsTo("GeneralSetting");
	}
	// public function validatePasswords($validator)
	// {
		// $validator->add('confirm_password', 'no-misspelling', [
			// 'rule' => ['compareWith', 'password'],
			// 'message' => 'Passwords are not equal',
		// ]);
		
		// return $validator;
	// }
}
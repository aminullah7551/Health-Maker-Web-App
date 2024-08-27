<?php
namespace App\Model\Table;
use Cake\ORM\Table;

Class HealthMemberClassTable extends Table{
	
	public function initialize(array $config)
	{		
		$this->addBehavior('Timestamp');
		$this->belongsTo("HealthMember",["foreignKey"=>"member_id"]);		
	}
}
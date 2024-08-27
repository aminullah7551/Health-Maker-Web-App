<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class HealthStoreTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("HealthMember",["foreignKey"=>"member_id"]);
		$this->belongsTo("HealthProduct",["foreignKey"=>"product_id"]);
	}
}
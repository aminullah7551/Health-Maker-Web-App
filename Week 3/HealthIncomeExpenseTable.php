<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class HealthIncomeExpenseTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("HealthMember",["foreignKey"=>"supplier_name"]);
	}
}
<?php 
namespace App\Model\Table;
use Cake\ORM\Table;
Class HealthReservationTable extends Table
{
	public function initialize(array $config)
	{
		$this->belongsTo("HealthEventPlace",["foreignKey"=>"place_id"]);
	}
	
}
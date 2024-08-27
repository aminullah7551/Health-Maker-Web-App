<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class HealthDailyWorkoutController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("HEALTHFunction");
	}
	
	public function workoutList()
	{
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator" || $session["role_name"] == "accountant")
		{
			$data = $this->HealthDailyWorkout->HealthMember->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
		}
		else if($session["role_name"] == "staff_member")
		{
			if($this->HEALTHFunction->getSettings("staff_can_view_own_member"))
			{			
				$data = $this->HealthDailyWorkout->HealthMember->find("all")->where(["role_name"=>"member","assign_staff_mem"=>$session["id"]])->hydrate(false)->toArray();
			}else{
				$data = $this->HealthDailyWorkout->HealthMember->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
			}
		}
		else if($session["role_name"] == "member")
		{
			$uid = $session["id"];
			$data = $this->HealthDailyWorkout->HealthMember->find("all")->where(["id"=>$uid])->hydrate(false)->toArray();
		}
		$this->set("data",$data);
	}	
	
	public function addWorkout()
    {
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Workout"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member")
		{
			$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["id"=>$session["id"]]);
			$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		}
		else if($session["role_name"] == "staff_member"){
			if($this->HEALTHFunction->getSettings("staff_can_view_own_member"))
			{	
				$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","assign_staff_mem"=>$session["id"],"member_type"=>"Member"]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		
			}else{
				$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
			}
		}
		else{		
			$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
			$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		}
		$this->set("members",$members);		
		//debug($this->request);
		//die;
		if($this->request->is("post") && !isset($this->request->data["new_data"]) && !isset($this->request->data["edit"]))
		{ 
			$row = $this->HealthDailyWorkout->newEntity();
			$this->request->data["created_date"] = date("Y-m-d");
			$this->request->data["record_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['record_date']);
			$this->request->data["created_by"] = $session["id"];
			$row = $this->HealthDailyWorkout->patchEntity($row,$this->request->data);
			if($this->HealthDailyWorkout->save($row))
			{
				
				//$this->HealthFunction->sendworkout($this->request->data["member_id"]);
				
				$id = $row->id;
				
				//$this->HealthFunction->sendWorkoutAlertEmail();
				foreach($this->request->data["workouts_array"] as $val)
				{
					$user_workoutdata = array();
					$user_workoutdata['user_workout_id']=$id;
					$user_workoutdata['workout_name']=$this->request->data['workout_name_'.$val];
					$user_workoutdata['workout_name']=$this->request->data['workout_name_'.$val];
					$user_workoutdata['sets']=$this->request->data['sets_'.$val];
					$user_workoutdata['reps']=$this->request->data['reps_'.$val];
					$user_workoutdata['kg']=$this->request->data['kg_'.$val];
					$user_workoutdata['rest_time']=$this->request->data['rest_'.$val];				
					$new = $this->HealthDailyWorkout->HealthUserWorkout->newEntity();
					$new =  $this->HealthDailyWorkout->HealthUserWorkout->patchEntity($new,$user_workoutdata);	
					$chk =  $this->HealthDailyWorkout->HealthUserWorkout->save($new);
				}
				$this->Flash->success(__("Success! Record Saved Successfully."));
			}
			else{
				if($row->errors())
				{
					foreach($row->errors() as $error)
					{
						foreach($error as $key=>$value)
						{
							$this->Flash->error(__($value));
						}						
					}
				}
			}
		}
		else if($this->request->is("post") && isset($this->request->data["new_data"]) && !isset($this->request->data["edit"]))
		{
			$row = $this->HealthDailyWorkout->newEntity();
			$this->request->data["created_date"] = date("Y-m-d");
			$this->request->data["record_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['record_date']);
			$this->request->data["created_by"] = $session["id"];
			$row = $this->HealthDailyWorkout->patchEntity($row,$this->request->data);
			$post = $this->request->data;
			if($this->HealthDailyWorkout->save($row))
			{
				//$this->HealthFunction->sendworkout($this->request->data["member_id"]);
				$id = $row->id;				
				
				$activities = $post["activity_name"];
				
				foreach($activities as $activity)
				{
					$error = null;
					$data = array();
					$data["user_workout_id"] = $id;
					$data["workout_name"] = $activity;
					$data["sets"] = $post["sets_{$activity}"];
					$data["reps"] = $post["reps_{$activity}"];
					$data["kg"] = $post["kg_{$activity}"];
					$data["rest_time"] = $post["rest_{$activity}"];
					$row = $this->HealthDailyWorkout->HealthUserWorkout->newEntity();
					$row = $this->HealthDailyWorkout->HealthUserWorkout->patchEntity($row,$data);
					if($this->HealthDailyWorkout->HealthUserWorkout->save($row))
					{
						$error = 0;
					}else{
						$error = 1;
						}					
				}
				if($error == 0)
				{
					// $this->Flash->success(__("Success! Record Saved Successfully."));
					// return $this->redirect(["action"=>"workoutList"]);
				}				
			}
			else
			{
				if($row->errors())
				{
					foreach($row->errors() as $error)
					{
						foreach($error as $key=>$value)
						{
							$this->Flash->error(__($value));
							return $this->redirect(["action"=>"workoutList"]);
						}						
					}
				}
			}
			
			$assign_row = $this->HealthDailyWorkout->HealthAssignWorkout->newEntity();
			$assign_data["level_id"]= $this->request->data["level_id"];
			$assign_data["user_id"]= $this->request->data["member_id"];
			$assign_data["description"]= $this->request->data["note"];
			$assign_data["direct_assign"]= 1;
			$assign_data["start_date"]= $this->request->data["record_date"];
			$assign_data["end_date"]= $this->request->data["record_date"];
			$assign_data["created_date"]= date("Y-m-d");
			$assign_data["created_by"]= $session["id"];
			$assign_row = $this->HealthDailyWorkout->HealthAssignWorkout->patchEntity($assign_row,$assign_data);
			if($this->HealthDailyWorkout->HealthAssignWorkout->save($assign_row))
			{
				//$this->HealthFunction->sendworkout($this->request->data["member_id"]);
				$id = $assign_row->id;				
				$post = $this->request->data;
				$activities = $post["activity_name"];
				foreach($activities as $activity)
				{
					$error = null;
					$data = array();
					$day_name = date("l",strtotime($post["record_date"]));
					$data["day_name"] = $day_name;
					$data["workout_id"] = $id;
					$data["workout_name"] = $activity;
					$data["sets"] = $post["sets_{$activity}"];
					$data["reps"] = $post["reps_{$activity}"];
					$data["kg"] = $post["kg_{$activity}"];
					$data["time"] = $post["rest_{$activity}"];
					$data["created_date"]= date("Y-m-d");
					$data["created_by"]= $session["id"];
					
					$row = $this->HealthDailyWorkout->HealthWorkoutData->newEntity();
					$row = $this->HealthDailyWorkout->HealthWorkoutData->patchEntity($row,$data);
					if($this->HealthDailyWorkout->HealthWorkoutData->save($row))
					{$error = 0;}else{$error = 1;}					
				}
				if($error == 0)
				{
					$this->Flash->success(__("Success! Record Saved Successfully."));
					return $this->redirect(["action"=>"workoutList"]);
				}
			}
						
		}
		else if($this->request->is("post") && !isset($this->request->data["new_data"]) && isset($this->request->data["edit"]) && $this->request->data["edit"] == "yes")
		{
			$post = $this->request->data;
			foreach($post["workouts_array"] as $wa)
			{
				$wn = $post["workout_name_".$wa];
				$row[$wn]["sets"] = $post["sets_{$wa}"];
				$row[$wn]["reps"] = $post["reps_{$wa}"];
				$row[$wn]["kg"] = $post["kg_{$wa}"];
				$row[$wn]["rest"] = $post["rest_{$wa}"];
				
				$query = $this->HealthDailyWorkout->HealthUserWorkout->query();
				//debug($post["rest_{$wa}"]);//die;
				/* $query->update()
						->set(["sets" => $post["sets_{$wa}"],"reps"=>$post["reps_{$wa}"],"kg"=>$post["kg_{$wa}"],"rest_time"=>$post["rest_{$wa}"]])
						->where(['user_workout_id' => $post["user_workout_id"],"workout_name"=>$wn])
						->execute(); */ 
				$query->update()
						->set(["sets" => $row[$wn]['sets'],"reps"=>$row[$wn]['reps'],"kg"=>$row[$wn]['kg'],"rest_time"=>$row[$wn]['rest']])
						->where(['user_workout_id' => $post["user_workout_id"],"workout_name"=>$wn])
						->execute();
				
				$query2 = $this->HealthDailyWorkout->HealthAssignWorkout->query();
				 //die;
				$query2->update()
						->set(["description" => $post["note"]])
						->where(['user_id' => $post["member_id"],"start_date "=>$post["record_date"]])
						->execute();
						//$this->HEALTHFunction->sendworkout($this->request->data["member_id"]);		

				$query3 = $this->HealthDailyWorkout->query();
				$query3->update()->set(['note' => $post['note']])->where(['member_id' => $post["member_id"],"record_date "=>$post["record_date"]])->execute();
				
			}	
			$this->Flash->success(__("Success! Record Saved Successfully."));
			return $this->redirect(["action"=>"workoutList"]);
		}
	}
	
	public function addMeasurment($id = null,$type = null)
    {
		$session = $this->request->session()->read("User");
		$this->loadComponent("HEALTHFunction");
		if($id != null && $type != null)
		{
			$data["user_id"] = $id;
			$data["result_measurment"] = $type;
			$this->set("data",$data);			
			$this->set("set",true);			
		}else{
			$this->set("set",false);	
		}
		
		$this->set("edit",false);
		$this->set("title",__("Add Measurement"));
		if($session["role_name"] == "staff_member")
		{
			if($this->HEALTHFunction->getSettings("staff_can_view_own_member"))
			{
				$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","assign_staff_mem"=>$session["id"],"member_type"=>"Member"]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
			}
			else{
				$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
			}
		}else{
				$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		}
		$this->set("members",$members);

		if($this->request->is("post"))
		{
			$ext = $this->HEALTHFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$row = $this->HealthDailyWorkout->HealthMeasurement->newEntity();
				$image = $this->HEALTHFunction->uploadImage($this->request->data['image']);
				$this->request->data['image'] = (!empty($image)) ? $image : "measurement.png";
				$this->request->data["created_by"]= $session["id"];
				$this->request->data["created_date"]= date("Y-m-d");
				$this->request->data["result_date"]= $this->HEALTHFunction->get_db_format_date($this->request->data['result_date']);
				$row = $this->HealthDailyWorkout->HealthMeasurement->patchEntity($row,$this->request->data);
				if($this->HealthDailyWorkout->HealthMeasurement->save($row))
				{
					$this->Flash->success(__("Success! Record Saved Successfully."));
					return $this->redirect(["action"=>"workoutList"]);
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"add-measurment"]);
			}
		}
    }
	
	public function viewWorkout($uid)
    {		
		$member = $this->HealthDailyWorkout->HealthMember->get($uid)->toArray();
		$this->set("member_name",$member["first_name"]." ".$member["last_name"]);		
		
		$session = $this->request->session()->read("User");		
		if(intval($session["id"]) != intval($uid) && $session["role_name"] == 'member')
		{
			echo $this->Flash->error("No sneaking around! ;p ");
			return $this->redirect(["action"=>"workoutList"]);			
		}
		
		  ##### Gets All Schedule Assigned date ###
		$dates = $this->HealthDailyWorkout->find()->select(["id","record_date"])->where(["member_id"=>$uid])->hydrate(false)->toArray();
		$date_array = array();
		foreach($dates as $date)
		{
			$wid = $date["id"];
			$date_array[]=$date["record_date"]->format("Y-m-d");
		}
		$this->set("date_array",$date_array);
		$this->set("uid",$uid);
		
		if($this->request->is("post"))
		{
			$schedule_date = $this->request->data["schedule_date"];
			$dates = '';
			$dates = $this->HealthDailyWorkout->find()->select(["id","record_date"])->where(["member_id"=>$uid,'record_date'=>$schedule_date])->hydrate(false)->toArray();
			
			if(!empty($dates))
			{
				$user_workout_id = $dates[0]["id"];
			
				$workouts = $this->HealthDailyWorkout->find()->select(['HealthDailyWorkout.note'])->where(["HealthDailyWorkout.id"=>$user_workout_id]);
				
				$workouts = $workouts->leftjoin(['HealthUserWorkout'=>'health_user_workout'],[	'HealthUserWorkout.user_workout_id=HealthDailyWorkout.id'])->select($this->HealthDailyWorkout->HealthUserWorkout)->hydrate(false)->toArray();			
				
				$this->set("workouts",$workouts);
				$this->set("schedule_date",$schedule_date);
			}
		}	
	}
	
	public function editMeasurment($id)
    {
		$this->loadComponent("HEALTHFunction");
		$this->set("edit",true);
		$this->set("set",false);
		$this->set("title",__("Edit Measurement"));
		
		$data = $this->HealthDailyWorkout->HealthMeasurement->get($id);
		$members = $this->HealthDailyWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);		
		$this->set("data",$data->toArray());
		$this->render("addMeasurment");
		
		if($this->request->is("post"))
		{
			$ext = $this->HEALTHFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$this->request->data["result_date"]= $this->HEALTHFunction->get_db_format_date($this->request->data['result_date']);
				$image = $this->HEALTHFunction->uploadImage($this->request->data['image']);
				if($image != "")
				{
					$this->request->data['image'] = $image;
				}else{
					unset($this->request->data['image']);
				}
				
				$data = $this->HealthDailyWorkout->HealthMeasurement->patchEntity($data,$this->request->data);
				if($this->HealthDailyWorkout->HealthMeasurement->save($data))
				{
					$this->Flash->success(__("Success! Record Updated Successfully."));
					return $this->redirect(["action"=>"workoutList"]);
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"editMeasurment",$id]);
			}
		}		
    }
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		// $members_actions = ["workoutList"];
		$staff_acc_actions = ["workoutList","viewWorkout"];
		switch($role_name)
		{			
			// CASE "member":
				// if(in_array($curr_action,$members_actions))
				// {return true;}else{return false;}
			// break;
			
			// CASE "staff_member":
				// if(in_array($curr_action,$staff_acc_actions))
				// {return true;}else{ return false;}
			// break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		return parent::isAuthorized($user);
	}
}
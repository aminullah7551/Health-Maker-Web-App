<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class HealthAssignWorkoutController extends AppController
{  
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("HEALTHFunction");
	}
	
	public function workoutLog()
    {		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "staff_member")
		{
			if($this->HEALTHFunction->getSettings("staff_can_view_own_member"))
			{
				$data = $this->HealthAssignWorkout->find("all")->contain(["HealthMember"])->where(["HealthMember.assign_staff_mem"=>$session["id"],"member_type"=>"Member"])->select($this->HealthAssignWorkout)->group("user_id");
				$data = $data->select(["HealthMember.first_name","HealthMember.last_name","HealthMember.image","HealthMember.member_id","HealthMember.intrested_area"])->hydrate(false)->toArray();
			}
			else
			{
				$data = $this->HealthAssignWorkout->find("all")->contain(["HealthMember"])->select($this->HealthAssignWorkout)->group("user_id");
				$data = $data->select(["HealthMember.first_name","HealthMember.last_name","HealthMember.image","HealthMember.member_id","HealthMember.intrested_area"])->hydrate(false)->toArray();
			}
		}
		else
		{
			$data = $this->HealthAssignWorkout->find("all")->contain(["HealthMember"])->select($this->HealthAssignWorkout)->group(["user_id"]);
			$data = $data->select(["HealthMember.first_name","HealthMember.last_name","HealthMember.image","HealthMember.member_id","HealthMember.intrested_area"])->hydrate(false)->toArray();
		}
		$this->set("data",$data);
    }
	
	public function assignWorkout()
    {
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Assign Workout"));
		
		$levels = array();
		$members = array();
		$categories = array();
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "staff_member")
		{
			if($this->HEALTHFunction->getSettings("staff_can_view_own_member"))
			{
				$members = $this->HealthAssignWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","assign_staff_mem"=>$session["id"]]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
			}
			else
			{
				$members = $this->HealthAssignWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
				$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
			}
		}
		else
		{
			$members = $this->HealthAssignWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
			$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		}
		$this->set("members",$members);

		$levels = $this->HealthAssignWorkout->HealthLevels->find("list",["keyField"=>"id","valueField"=>"level"])->hydrate(false)->toArray();
		$this->set("levels",$levels);
		
		if($this->request->is("post"))
		{  			
			$save = false;
			$row = $this->HealthAssignWorkout->newEntity();
			$insert["user_id"] =$this->request->data["user_id"];
			$insert["level_id"] =$this->request->data["level_id"];
			$insert["description"] =$this->request->data["description"];
			$insert["start_date"] =$this->HEALTHFunction->get_db_format_date($this->request->data['start_date']);
			$insert["end_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['end_date']);
			$insert["created_date"] = date("Y-m-d");
			$insert["created_by"] = $session["id"];
			$insert["direct_assign"] = 0;
			
			$this->request->data["start_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['start_date']);
			$this->request->data["end_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['end_date']);
			$this->request->data["created_date"] = date("Y-m-d");
			$this->request->data["created_by"] = $session["id"];
			$this->request->data["direct_assign"] = 0;
			$row = $this->HealthAssignWorkout->patchEntity($row,$insert);
			
			if($this->HealthAssignWorkout->save($row))
			{
				$save = true;
				$workout_id = $row->id;
			}
			if($save)
			{
				if($this->add_workout_data($this->request->data["activity_list"],$workout_id))
				{
					$this->Flash->success(__("success! Record Saved Successfully."));
					return $this->redirect(["action"=>"workoutLog"]);
				}
				else
				{
					$this->Flash->error(__("Error! Workout days data couldn't saved.Please try again."));
					return $this->redirect(["action"=>"workoutLog"]);
				}
				
			}
			else
			{
				$this->Flash->error(__("Error! Record couldn't saved.Please try again."));
			}
		}
	}
	
	protected function add_workout_data($activity_list,$workout_id)
	{
		foreach($activity_list as $val)
		{
			$data_value = json_decode($val);
			$phpobj[] = json_decode(stripslashes($val),true);			
		}
			
		$final_array = array();
		$resultarray =array();
		foreach($phpobj as $key => $val)
		{
			$day = array();
			$activity = array();
			foreach($val as $key=>$key_val)
			{
				if($key == "days")
				foreach($key_val as $val1)
				{
					$day['day'][] =$val1['day_name'] ;
				}
				if($key == "activity")
					foreach($key_val as $val2)
					{
						$activity['activity'][] =array('activity'=>$val2['activity']['activity'],
													'reps'=>$val2['activity']['reps'],
													'sets'=>$val2['activity']['sets'],
													'kg'=>$val2['activity']['kg'],
													'time'=>$val2['activity']['time'],
						) ;
					}
				
			}
			
			$resultarray[] = array_merge($day, $activity);
		}
		$work_outdata = $resultarray;	
			
		if(!empty($work_outdata))
		{
			$workout_data = array();
			foreach($work_outdata as  $value)
			{				
				foreach($value['day'] as $day)
				{
					foreach($value['activity']  as $actname)
					{	$row=array();$workout_data = array();
						$workout_data['day_name'] = $day;
						$act_id = $this->HealthAssignWorkout->Activity->find()->where(["title"=>$actname['activity']])->select("id")->hydrate(false)->toArray();
						$workout_data['workout_name'] = $act_id[0]["id"];
						$workout_data['sets'] = $actname['sets'];
						$workout_data['reps'] = $actname['reps'];
						$workout_data['kg'] = $actname['kg'];
						$workout_data['time'] = $actname['time'];
						$workout_data['workout_id'] = $workout_id;
						$workout_data['created_date'] = date("Y-m-d");
						$workout_data['create_by'] = 1;
						$rws[]=$workout_data;						
					}
				}				
			}			
		}	
	
		$ma_row = $this->HealthAssignWorkout->HealthWorkoutData->newEntities($rws);
		foreach($ma_row as $m_row)
		{
			if($this->HealthAssignWorkout->HealthWorkoutData->save($m_row))
			{
				$error = 1;
			}
			else
			{
				$error = 0;
			}
		}
		return $error;
	}
	
	 public function viewWorkouts($id)
    {		
		$session = $this->request->session()->read("User");
		$this->set("edit",true);
		$this->set("title",__("View Workout"));
		
			$members = $this->HealthAssignWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
			$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		
		$this->set("members",$members);

		$levels = $this->HealthAssignWorkout->HealthLevels->find("list",["keyField"=>"id","valueField"=>"level"])->hydrate(false)->toArray();
		$this->set("levels",$levels);
			
		$categories = $this->HealthAssignWorkout->Category->find("all")->hydrate(false)->toArray();
		$this->set("categories",$categories);
						
		$data = $this->HealthAssignWorkout->find()->where(["HealthAssignWorkout.user_id"=>$id])->select(["HealthAssignWorkout.start_date","HealthAssignWorkout.end_date"]);
		$data = $data->leftjoin(['HealthWorkoutData' => 'health_workout_data'],
								['HealthWorkoutData.workout_id = HealthAssignWorkout.id']
							)->select($this->HealthAssignWorkout->HealthWorkoutData)->hydrate(false)->toArray();		
		$wid = 0;
		$work_outdata = array();
		
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "HealthWorkoutData"){
					$wid = $v["workout_id"];					
					if($wid != "")
					{
						$work_outdata[$wid]["start_date"]= $value["start_date"]->format("Y-m-d");							
						$work_outdata[$wid]["end_date"]= $value["end_date"]->format("Y-m-d");							
						$work_outdata[$wid][]=$v;												
					}
				}	
					
			}			
		}		
		$this->set("work_outdata",$work_outdata);
		if($this->request->is("post"))
		{   		
			$save = false;
			$row = $this->HealthAssignWorkout->newEntity();
			$insert["user_id"] =$this->request->data["user_id"];
			$insert["level_id"] =$this->request->data["level_id"];
			$insert["description"] =$this->request->data["description"];
			$insert["start_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['start_date']); 
			$insert["end_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['end_date']);
			$insert["created_date"] = date("Y-m-d");
			$insert["created_by"] = $session["id"];
			$insert["direct_assign"] = 0;
			
			$this->request->data["start_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['start_date']);
			$this->request->data["end_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['end_date']);
			$this->request->data["created_date"] = date("Y-m-d");
			$this->request->data["created_by"] = $session["id"];
			$this->request->data["direct_assign"] = 0;
			$row = $this->HealthAssignWorkout->patchEntity($row,$insert);
			
			
			if($this->HealthAssignWorkout->save($row))
			{
				$save = true;
				$workout_id = $row->id;
				
			}
			
			if($save)
			{
				if($this->add_workout_data($this->request->data["activity_list"],$workout_id))
				{
					$this->Flash->success(__("success! Record Saved Successfully."));
					return $this->redirect(["action"=>"workoutLog"]);
				}
				else
				{
					$this->Flash->error(__("Error! Workout days data couldn't saved.Please try again."));					
				}				
			}else{
				$this->Flash->error(__("Error! Record couldn't saved.Please try again."));
			}
		}
		$this->render("assignWorkout");
    }
	public function deleteWorkout($uid)
	{
		$this->autoRender = false;
		$uid = intval($uid);
		$workout_ids = $this->HealthAssignWorkout->find("all")->where(["user_id"=>$uid])->select(["id"])->hydrate(false)->toArray();
		
		$delete =  $this->HealthAssignWorkout->query();
		$delete = $delete->delete()->where(["user_id"=>$uid])->execute();	
		
		if($delete)
		{
			foreach($workout_ids as $wid)
			{
				$query = $this->HealthAssignWorkout->HealthWorkoutData->query();
				$query->delete()->where(['workout_id' => $wid["id"]])->execute();
			}
			$this->Flash->success(__("Success! Record Deleted Successfully."));
			return $this->redirect(["action"=>"workoutLog"]);
		}	
	}
	
	
	public function memberAssigedWorkout()
	{
		$session = $this->request->session()->read("User");
		$id = $session["id"];	
					
		$members = $this->HealthAssignWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);

		$levels = $this->HealthAssignWorkout->HealthLevels->find("list",["keyField"=>"id","valueField"=>"level"])->hydrate(false)->toArray();
		$this->set("levels",$levels);
			
		$categories = $this->HealthAssignWorkout->Category->find("all")->hydrate(false)->toArray();
		$this->set("categories",$categories);
								
		$data = $this->HealthAssignWorkout->find()->where(["HealthAssignWorkout.user_id"=>$id])->select(["HealthAssignWorkout.start_date","HealthAssignWorkout.end_date"]);
		$data = $data->leftjoin(['HealthWorkoutData' => 'health_workout_data'],
								['HealthWorkoutData.workout_id = HealthAssignWorkout.id']
							)->select($this->HealthAssignWorkout->HealthWorkoutData)->hydrate(false)->toArray();		
		$wid = 0;
		$work_outdata = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "HealthWorkoutData"){
					$wid = $v["workout_id"];					
					if($wid != "")
					{
						$work_outdata[$wid]["start_date"]= $value["start_date"]->format("Y-m-d");							
						$work_outdata[$wid]["end_date"]= $value["end_date"]->format("Y-m-d");							
						$work_outdata[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("work_outdata",$work_outdata);
	}
	
	public function printWorkout()
	{
		$session = $this->request->session()->read("User");
		$id = $session["id"];
					
		$members = $this->HealthAssignWorkout->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);

		
		$data = $this->HealthAssignWorkout->find()->where(["HealthAssignWorkout.user_id"=>$id])->select(["HealthAssignWorkout.start_date","HealthAssignWorkout.end_date"]);
		$data = $data->leftjoin(['HealthWorkoutData' => 'health_workout_data'],
								['HealthWorkoutData.workout_id = HealthAssignWorkout.id']
							)->select($this->HealthAssignWorkout->HealthWorkoutData)->hydrate(false)->toArray();		
		$wid = 0;
		$work_outdata = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "HealthWorkoutData"){
					$wid = $v["workout_id"];					
					if($wid != "")
					{
						$work_outdata[$wid]["start_date"]= $value["start_date"]->format("Y-m-d");							
						$work_outdata[$wid]["end_date"]= $value["end_date"]->format("Y-m-d");							
						$work_outdata[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("work_outdata",$work_outdata);
	}
	
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["memberAssigedWorkout","printWorkout"];
		$staff_acc_actions = ["workoutLog","assignWorkout","viewWorkouts","deleteWorkout"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return $this->redirect(["action"=>"memberAssigedWorkout"]);}
				
			break;
			
			/*CASE "staff_member":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{ return false;}
			break;*/
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		return parent::isAuthorized($user);
	}
}
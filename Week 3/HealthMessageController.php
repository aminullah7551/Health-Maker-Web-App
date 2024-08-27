<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\Core\Configure;

class HealthMessageController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("HEALTHFunction");
		$session = $this->request->session()->read("User");
		$uid = intval($session["id"]); /* Current userid */
		$query = $this->HealthMessage->find("all")->where(["receiver"=>$uid,"status"=> 0]);
		$unread_messages = $query->count();
		$this->set("unread_messages",$unread_messages);
	}
	
	public function index()
	{
		
	}
	
	public function composeMessage()
    {
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member" && !$this->HEALTHFunction->getSettings("enable_message"))
		{
			return $this->redirect(["action"=>"inbox"]);
		}
		
		$classes = $this->HealthMessage->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();
		$class["all"] = __("All");
		$finalClass = array_merge($class,$classes);
		$this->set("classes",$finalClass);
		$date = date("Y-m-d H:i:s");
		
		if($this->request->is("post"))
		{	
			// var_dump($date);die;
			$role = $this->request->data["receiver"];	

			if($role == 'member' || $role == 'staff_member' || $role == 'accountant'|| $role == 'administrator')
			{
				$class_id = $this->request->data['class_id'];
				
				//debug($class_id);die;
				//if(($role == 'member' || $role == 'staff_member') && $class_id != 'all' )
				if($role == 'member' && $class_id != 'all')
				{

					$health_member_class = TableRegistry::get('health_member_class');
					$data = $this->HealthMessage->HealthMember->find("all")->where(["HealthMember.role_name"=>$role])->select($this->HealthMessage->HealthMember);
					
					$member_ids = $data->innerjoin(["health_member_class"=>"health_member_class"],
					["health_member_class.member_id = HealthMember.id"])->select($health_member_class)->where(['health_member_class.assign_class'=>$class_id])->hydrate(false)->toArray();
					
					debug($member_ids);die;
				}
				else
				{

					$member_ids = $this->HealthMessage->HealthMember->find("all")->where(["role_name"=>$role])->select(["id"])->hydrate(false)->toArray();	
				}
				$records = array();

				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						
						$mid = $value["id"];
					
						$data = array();
						$data["sender"] = $session["id"]; /* current userid*/
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->HEALTHFunction->sanitize_string($this->request->data["message_body"]);
						$data["status"] =  0;
						$records[] = $data;
						
					}
					
					$rows = $this->HealthMessage->newEntities($records);
				
					foreach($rows as $row)
					{
						if($this->HealthMessage->save($row))
						{$saved = true;} else{$saved = false;}
					}
				}else{
					$saved = false;
				}		
			}
			else
			{		
				$mid = $this->request->data["receiver"];
				$this->request->data["date"] = $date;
				$this->request->data["sender"] = $session["id"]; /* current userid*/
				$this->request->data["status"] = 0;
				$row = $this->HealthMessage->newEntity();
				$row = $this->HealthMessage->patchEntity($row,$this->request->data);
				if($this->HealthMessage->save($row))
				{$saved = true;}else{$saved = false;}
			}
			
			/*if($this->request->data["class_id"] == "all")
			{
				$member_ids = $this->HealthMessage->HealthMember->find("all")->where(["role_name"=>"member"])->select(["id"])->hydrate(false)->toArray();
				$records = array();
				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						$mid = $value["id"];
						$data = array();
						$data["sender"] = $session["id"];
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->HEALTHFunction->sanitize_string($this->request->data["message_body"]);
						$data["status"] =  0;
						$records[] = $data;
					}
				}
				
				$rows = $this->HealthMessage->newEntities($records);
				foreach($rows as $row)
				{
					if($this->HealthMessage->save($row))
					{$saved = true;} else{$saved = false;}
				}	
			}*/
			
			if($saved)
			{$this->Flash->success(__("Success! Message Sent Successfully."));}
			else
			{$this->Flash->error(__("Error! Message Couldn't be Sent, Please Try Again."));}			
		}
    }
	
	public function inbox()
    {
		$session = $this->request->session()->read("User");
		$uid = $session["id"]; /* Current userid */
		$messages = $this->HealthMessage->find("all")->contain(["HealthMember"])->where(["receiver"=>$uid])->select($this->HealthMessage)->select(["HealthMember.first_name","HealthMember.last_name"])->hydrate(false)->toArray(); 
		$this->set("messages",$messages);		
    }
	
	public function sent()
    {
		$session = $this->request->session()->read("User");
		$uid = $session["id"]; /* Current userid */
		$messages = $this->HealthMessage->find("all")->where(["HealthMessage.sender"=>$uid])->limit(30)->select($this->HealthMessage)->order(['HealthMessage.id'=>'desc']);
		$messages = $messages->leftjoin(["HealthMember"=>"health_member"],
									  ["HealthMember.id = HealthMessage.receiver"])->select(["HealthMember.first_name","HealthMember.last_name"])->hydrate(false)->toArray();
		$this->set("messages",$messages);		
    }	
	
    public function viewMessage($vid)
    {
		$data = $this->HealthMessage->find("all")->where(["HealthMessage.id"=>intval($vid)])->contain(["HealthMember"])->select($this->HealthMessage)->select(["HealthMember.first_name","HealthMember.last_name","HealthMember.email"])->hydrate(false)->toArray();
		if(empty($data))
		{
			
			$this->Flash->error(__("Warning! Record data not found."));
			return $this->redirect(["action"=>"inbox"]);
		}
		$this->set("data",$data[0]);	
		$row = $this->HealthMessage->get($vid);
		$row->status = 1;
		$this->HealthMessage->save($row);
	}  
	
	public function viewSentMessage($vid)
    {
		$data = $this->HealthMessage->find("all")->where(["HealthMessage.id"=>intval($vid)])->select($this->HealthMessage);
		$data = $data->leftjoin(["HealthMember"=>"health_member"],
								["HealthMember.id = HealthMessage.receiver"])->select(["HealthMember.first_name","HealthMember.last_name","HealthMember.email"])->hydrate(false)->toArray();
		$temp = $data[0]["HealthMember"];
		unset($data[0]["HealthMember"]);
		$data[0]["health_member"] = $temp;	
		$this->set("data",$data[0]);
		$this->render("viewMessage");
	}
	
	public function deleteMessage($did)
	{
		$row = $this->HealthMessage->get($did);
		if($this->HealthMessage->delete($row))
		{
			$this->Flash->success(__("Success! Message Deleted Successfully."));
			return $this->redirect(["action"=>"inbox"]);
		}
	}	
}
?>
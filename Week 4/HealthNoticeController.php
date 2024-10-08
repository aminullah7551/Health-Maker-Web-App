<?php
namespace App\Controller;
use App\Controller\AppController;

class HealthNoticeController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("HEALTHFunction");
	}
	
	public function noticeList()
	{
		$session = $this->request->session()->read("User");
		switch($session["role_name"])
		{
			CASE "administrator" :
				$data = $this->HealthNotice->find("all")->hydrate(false)->toArray();
			break;
			CASE "staff_member" :
				$data = $this->HealthNotice->find("all")->where(["OR"=>[["notice_for"=>"staff_member"],["notice_for"=>"all"]]])->hydrate(false)->toArray();
			break;
			CASE "member" :
				$class_ids = $this->HEALTHFunction->get_class_by_member($session["id"]);
				if(!empty($class_ids))
				{
					$data = $this->HealthNotice->find("all")->where(["OR"=>[["class_id IN"=>$class_ids],["notice_for"=>"member"],["notice_for"=>"all"]]])->hydrate(false)->toArray();
				}else{
					$data = $this->HealthNotice->find("all")->where(["OR"=>[["notice_for"=>"member"],["notice_for"=>"all"]]])->hydrate(false)->toArray();
				}
			break;
			CASE "accountant" : 
				$data = $this->HealthNotice->find("all")->where(["OR"=>[["notice_for"=>"accountant"],["notice_for"=>"all"]]])->hydrate(false)->toArray();
			break;			
		}
		
		
		$this->set("data",$data);
	}
	public function addNotice()
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);		
		$classes = $this->HealthNotice->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
		$this->set("classes",$classes);
	
		if($this->request->is("post"))
		{
			$row = $this->HealthNotice->newEntity();			
			//$this->request->data["start_date"] = date("Y-m-d",strtotime($this->request->data["start_date"]));		
			//$this->request->data["end_date"] = date("Y-m-d",strtotime($this->request->data["end_date"]));		
			$this->request->data["start_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['start_date']);
			$this->request->data["end_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['end_date']);	
			$this->request->data["created_by"] = $session["id"];	
			
			/*SANITIZATION*/
			$this->request->data["comment"] = $this->HEALTHFunction->sanitize_string($this->request->data["comment"]);
			/*SANITIZATION*/
			
			$row = $this->HealthNotice->patchEntity($row,$this->request->data);
			if($this->HealthNotice->save($row))
			{				
				$this->Flash->success(__("Success! Record Successfully Saved."));
				return $this->redirect(["action"=>"noticeList"]);			
			}else{
				$this->Flash->error(__("Error! Record Not Saved.Please Try Again."));
			}
		}		
	}
	public function editNotice($pid)
	{	
		$this->set("edit",true);		
		$row = $this->HealthNotice->get($pid);
		$this->set("data",$row->toArray());
		
		$classes = $this->HealthNotice->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
		$this->set("classes",$classes);
		
		if($this->request->is("post"))
		{
			
			$this->request->data["start_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['start_date']);		
			$this->request->data["end_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['end_date']);
			
			/*SANITIZATION*/
			$this->request->data["comment"] = $this->HEALTHFunction->sanitize_string($this->request->data["comment"]);
			/*SANITIZATION*/
			
			$row = $this->HealthNotice->patchEntity($row,$this->request->data);
			if($this->HealthNotice->save($row))
			{
				$this->Flash->success(__("Success! Record Successfully Updated."));
				return $this->redirect(["action"=>"noticeList"]);
			}else{
				$this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
			}
		}
		$this->render("addNotice");
	}
	
	public function deleteNotice($did)
	{
		$row = $this->HealthNotice->get($did);
		if($this->HealthNotice->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully Updated."));
			return $this->redirect(["action"=>"noticeList"]); 
		} 		
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["noticeList"];
		$staff_acc_actions = ["noticeList"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
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
<?php
namespace App\Controller;
use Cake\App\Controller;

class HealthAccountantController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("HEALTHFunction");
	}
	
	public function accountantList()
	{		
		$data = $this->HealthAccountant->HealthMember->find("all")->where(["HealthMember.role_name"=>"accountant"])->hydrate(false)->toArray();
		$this->set("data",$data);	
	}
	
	public function addAccountant()
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);			
		$this->set("title",__("Add Accountant"));
		
		if($this->request->is("post"))
		{
			$ext = $this->HEALTHFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$accountant = $this->HealthAccountant->HealthMember->newEntity();
				
				$image = $this->HEALTHFunction->uploadImage($this->request->data['image']);
				$this->request->data['image'] = (!empty($image)) ? $image : "Thumbnail-img.png";
				//$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				$this->request->data['birth_date'] = $this->HEALTHFunction->get_db_format_date($this->request->data['birth_date']);
				$this->request->data['created_date'] = date("Y-m-d");
				$this->request->data['created_by'] = $session["id"];
				$this->request->data['role_name'] = "accountant";
			
				$accountant = $this->HealthAccountant->HealthMember->patchEntity($accountant,$this->request->data);
				if($this->HealthAccountant->HealthMember->save($accountant))
				{
					$this->Flash->success(__("Success! Record Successfully Saved."));
					return $this->redirect(["action"=>"accountantList"]);
				}else
				{				
					if($accountant->errors())
					{	
						foreach($accountant->errors() as $error)
						{
							foreach($error as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"add-accountant"]);
			}
		}
	}
	
	public function editAccountant($id)
	{
		$this->set("edit",true);
		$this->set("title",__("Edit Accountant"));
		
		$data = $this->HealthAccountant->HealthMember->get($id);			
		$this->set("data",$data->toArray());
		$this->render("addAccountant");
		
		if($this->request->is("post"))
		{
			$ext = $this->HEALTHFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$row = $this->HealthAccountant->HealthMember->get($id);
				$this->request->data['birth_date'] = $this->HEALTHFunction->get_db_format_date(date("Y-m-d",strtotime($this->request->data['birth_date'])));
				$image = $this->HEALTHFunction->uploadImage($this->request->data['image']);
				if($image != "")
				{
					$this->request->data['image'] = $image;
				}else{
					unset($this->request->data['image']);
				}			
				$update = $this->HealthAccountant->HealthMember->patchEntity($row,$this->request->data);
				if($this->HealthAccountant->HealthMember->save($update))
				{
					$this->Flash->success(__("Success! Record Updated Successfully."));
					return $this->redirect(["action"=>"accountantList"]);
				}else
				{				
					if($update->errors())
					{	
						foreach($update->errors() as $error)
						{
							foreach($error as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"edit-accountant",$id]);
			}
		}
	}
	
	public function deleteAccountant($id)
	{
		$row = $this->HealthAccountant->HealthMember->get($id);
		if($this->HealthAccountant->HealthMember->delete($row))
		{
			$this->Flash->success(__("Success! Accountant Deleted Successfully."));
			return $this->redirect($this->referer());
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["accountantList"];
		$staff_acc_actions = ["accountantList"];
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
<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

Class HealthGroupController extends AppController
{
	public function initialize()
	{
			parent::initialize();
	}
		
	public function GroupList()
	{ 
		$data = $this->HealthGroup->find("all")->hydrate(false)->toArray();
		$this->set("data",$data);
	}
	
	public function addGroup()
	{		
		$this->set("edit",false);
		$this->set("title",__("Add Group"));	
		if($this->request->is("post"))
		{
			$this->loadComponent("HEALTHFunction");
			$ext = $this->HEALTHFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$this->loadComponent("HEALTHFunction");
				$group = $this->HealthGroup->newEntity();
				$new_name = $this->HEALTHFunction->uploadImage($this->request->data["image"]);
				$this->request->data["image"] =  $new_name;
				$this->request->data["created_date"] = date("Y-m-d");			
				$group = $this->HealthGroup->patchEntity($group,$this->request->data);
				
				if($this->HealthGroup->save($group))
				{
					$this->Flash->Success(__("Success!  Record Adeed Suucessfully."));
					return $this->redirect(["action"=>"groupList"]);
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"add-group"]);
			}
		}
	}

	public function editGroup($id){
		$this->set("title",__("Edit Group"));	
		$row1 = $this->HealthGroup->get($id);
		$row = $row1->toArray();		
		$this->set("edit",true);
		$this->set("data",$row);
		$this->render("addGroup");
		if($this->request->is("post"))
		{
			$this->loadComponent("HEALTHFunction");
			$ext = $this->HEALTHFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				if(!empty($this->request->data["image"]['name']))
				{
					$new_name = $this->HEALTHFunction->uploadImage($this->request->data["image"]);
					$this->request->data["image"] =  $new_name;

					/* old image remove from folder when new img upload */
					if($row['image'] != ''){
					 unlink(WWW_ROOT."/upload/".$row['image']);
					}
				}else{
					$this->request->data["image"] = $row['image'];
				}
				$group = $this->HealthGroup->patchEntity($row1,$this->request->data);
				if($this->HealthGroup->save($group))
				{
					$this->Flash->success(__("Success! Record Updated Successfully"));
					return $this->redirect(["action"=>"groupList"]);
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"edit-group",$id]);
			}
		}
	}	
	
	public function deleteGroup($id = null) {
		if($id != null) {
			$row = $this->HealthGroup->get($id);
			if($this->HealthGroup->delete($row)) {
				/* image remove from folder when delete record */
				if($row['image'] != '') {
				 	unlink(WWW_ROOT."/upload/".$row['image']);
				}
				
				$this->Flash->success(__("Success! Record Deleted Successfully"));
				return $this->redirect(["action"=>"groupList"]);
			}
		}
	}
	
	public function isAuthorized($user) {
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["groupList"];
		$staff_acc_actions = ["groupList","editGroup","deleteGroup","addGroup"];
		switch($role_name) {			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			CASE "staff_member":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{ return false;}
			break;
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		return parent::isAuthorized($user);
	}
}
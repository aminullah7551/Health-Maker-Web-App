<?php
namespace App\Controller;
use App\Controller\AppController;

class HealthProductController extends AppController
{
	public function productList()
	{
		$session = $this->request->session()->read("User");
		$role = $session["role_name"];
		$data = $this->HealthProduct->find("all")->hydrate(false)->toArray();
		$this->set("data",$data);
		$this->set("role",$role);
	}
	public function addProduct()
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		if($this->request->is("post"))
		{
			$row = $this->HealthProduct->newEntity();
			$this->request->data["created_by"] = $session["id"];
			$this->request->data["created_date"] = date("Y-m-d");
			$row = $this->HealthProduct->patchEntity($row,$this->request->data);
			if($this->HealthProduct->save($row))
			{
				$this->Flash->success(__("Success! Record Saved Successfully."));
				return $this->redirect(["action"=>"productList"]);
			}else{
				$this->Flash->error(__("Error! Record Not Saved.Please Try Again."));
			}
		}		
	}
	public function editProduct($pid)
	{	
		$this->set("edit",true);		
		$row = $this->HealthProduct->get($pid);
		$this->set("data",$row->toArray());
		
		if($this->request->is("post"))
		{
			$row = $this->HealthProduct->patchEntity($row,$this->request->data);
			if($this->HealthProduct->save($row))
			{
				$this->Flash->success(__("Success! Record Updated Successfully."));
				return $this->redirect(["action"=>"productList"]);
			}else{
				$this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
			}
		}
		$this->render("addProduct");
	}
	
	public function deleteProduct($did)
	{
		$this->autoRander = false;
		$row = $this->HealthProduct->get($did);
		if($this->HealthProduct->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully."));
			return $this->redirect($this->referer());
		} else{ echo false;}		
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["productList"];
		$staff__acc_actions = ["productList","addProduct","editProduct"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			/*CASE "staff_member":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{ return false;}
			break;*/
			
			CASE "accountant":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}
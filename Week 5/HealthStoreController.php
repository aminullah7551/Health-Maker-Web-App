<?php
namespace App\Controller;
use App\Controller\AppController;

class HealthStoreController extends AppController
{
	public function sellRecord()
	{
		
		$session = $this->request->session()->read("User");
		$role = $session["role_name"];
		$user_id = $session['id'];
		$this->set("role",$role);
		
		if($role == 'member'){
			$data = $this->HealthStore->find("all")->contain(['HealthProduct','HealthMember'])->select($this->HealthStore)->select(["HealthProduct.product_name","HealthMember.first_name","HealthMember.last_name"])->where(['HealthStore.member_id'=>$user_id])->hydrate(false)->toArray();
		}else{
			$data = $this->HealthStore->find("all")->contain(['HealthProduct','HealthMember'])->select($this->HealthStore)->select(["HealthProduct.product_name","HealthMember.first_name","HealthMember.last_name"])->hydrate(false)->toArray();
		}
		
		$this->set("data",$data);		
	}
	public function sellProduct()
	{
		$this->set("edit",false);
		
		$members = $this->HealthStore->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name' => $members->func()->concat(['first_name'=>'literal', ' ', 'last_name'=>'literal'])])->toArray();
		$this->set("members",$members);
	
		$products = $this->HealthStore->HealthProduct->find("list",["keyField"=>"id","valueField"=>"product_name"])->toArray();
		$this->set("products",$products);
		
		
		if($this->request->is("post"))
		{
			$row = $this->HealthStore->newEntity();
			$product_row = $this->HealthStore->HealthProduct->get($this->request->data["product_id"]);
			$product_quentity = $product_row->quantity;
			if($this->request->data["quantity"] <= $product_quentity)
			{
				$this->request->data["sell_by"] = 1;
				$this->request->data["sell_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['sell_date']);		
				$row = $this->HealthStore->patchEntity($row,$this->request->data);
				if($this->HealthStore->save($row))
				{
					$product = $this->HealthStore->HealthProduct->get($this->request->data["product_id"]);
					$product->quantity = ($product->quantity) - ($this->request->data["quantity"]);
					if($this->HealthStore->HealthProduct->save($product))
					{
						$this->Flash->success(__("Success! Record Saved Successfully."));
						return $this->redirect(["action"=>"sellRecord"]);
					}
				}else{
					$this->Flash->error(__("Error! Record Not Saved.Please Try Again."));
				}
			}else{
				$a = __("Only");
				$b = __("Item Available in Stock");
				$this->Flash->error($a." ".$product_quentity." ".$b);
				return $this->redirect(["action"=>"sellProduct"]);
			}
			
		}		
	}
	public function editRecord($pid)
	{	
		
		$this->set("edit",true);		
		$row = $this->HealthStore->get($pid);
		$this->set("data",$row->toArray());
		
		$members = $this->HealthStore->HealthMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name' => $members->func()->concat(['first_name'=>'literal', ' ', 'last_name'=>'literal'])])->toArray();
		$this->set("members",$members);
	
		$products = $this->HealthStore->HealthProduct->find("list",["keyField"=>"id","valueField"=>"product_name"])->toArray();
		$this->set("products",$products);
		if($this->request->is("post"))
		{
			$old_quentity = $this->request->data["old_quantity"];
			$new_quentity = $this->request->data["quantity"];
			$actual_quentity = $new_quentity - $old_quentity;
			
			$product_row = $this->HealthStore->HealthProduct->get($this->request->data["product_id"]);
			$available_quentity = $product_row->quantity;
			if($actual_quentity <= $available_quentity)
			{
				$this->request->data["sell_date"] = $this->HEALTHFunction->get_db_format_date($this->request->data['sell_date']);			
				$row = $this->HealthStore->patchEntity($row,$this->request->data);
				if($this->HealthStore->save($row))
				{
					$product = $this->HealthStore->HealthProduct->get($this->request->data["product_id"]);
					$product->quantity = ($product->quantity) - ($actual_quentity);
					if($this->HealthStore->HealthProduct->save($product))
					{
						$this->Flash->success(__("Success! Record Updated Successfully."));
						return $this->redirect(["action"=>"sellRecord"]);
					}
				}else{
					$this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
				}
			}else{
				$a =__("Only");
				$b = __("Item Available in Stock");
				$this->Flash->error($a." " .$available_quentity." ".$b);
				
			}
			
			
			
		}
		$this->render("sellProduct");
	}
	
	public function deleteRecord($did)
	{
		$row = $this->HealthStore->get($did);
		if($this->HealthStore->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully."));
			return $this->redirect(["action"=>"sellRecord"]); 
		} 		
	}
	
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["sellRecord"];
		$staff__acc_actions = ["sellRecord","sellProduct","editRecord","deleteRecord"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}
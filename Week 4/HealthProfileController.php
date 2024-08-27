<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;

class HealthProfileController extends AppController
{
	public function viewProfile()
	{
		$session = $this->request->session()->read("User");
		$user_data = $this->HealthProfile->HealthMember->get($session["id"]);
		$cover_image = $this->HealthProfile->GeneralSetting->find()->select('cover_image')->hydrate(false)->toArray();
		$coverIMG = $cover_image[0]['cover_image'];
		$this->set("data",$user_data->toArray());
		$this->set("cover_image",$coverIMG);
		
		if($this->request->is("post") )
		{
			if(isset($this->request->data["save_change"]))
			{			
				$post = $this->request->data;
				$saved_pass = $this->HealthProfile->HealthMember->get($this->Auth->user('id'))->password;
				$curr_pass = (new DefaultPasswordHasher)->check($post["current_password"],$saved_pass);
				
					// if($post["password"] != $post["confirm_password"])
					// {
					// 	$this->Flash->error(__("Error! New password and confirm password does not matched.Please try again."));
					// }else{
						if($this->request->data["confirm_password"] != '')
						{
							if($curr_pass)
							{
								$this->request->data['password'] = $this->request->data["confirm_password"];
								$update_row = $this->HealthProfile->HealthMember->patchEntity($user_data,$this->request->data);
						
								if($this->HealthProfile->HealthMember->save($update_row))
								{
									$this->Flash->success(__("Success! Record Updated Successfully"));
									return $this->redirect(["action"=>"viewProfile"]);
								}
							}else{
								$this->Flash->error(__("Error! Current password is wrong.Please try again."));
								return $this->redirect(["action"=>"viewProfile"]);
							}
						}else{
							$update_row = $this->HealthProfile->HealthMember->patchEntity($user_data,$this->request->data);
					
							if($this->HealthProfile->HealthMember->save($update_row))
							{
								$this->Flash->success(__("Success! Record Updated Successfully"));
								
							}
						}
					// }
				
				
			}
			if(isset($this->request->data["profile_save_change"]))
			{
				$post = $this->request->data;
				
				$curr_email = $this->Auth->User('email');
				if($curr_email != $post["email"])
				{
					$emails = $this->HealthProfile->HealthMember->find("all")->where(["email"=>$post["email"]]);
					$count = $emails->count();
				}else{
					$count = 0 ;
				}
				if($count == 0)
				{
					
					$post['birth_date']=date('Y-m-d',strtotime($post['birth_date']));
					$update_row = $this->HealthProfile->HealthMember->patchEntity($user_data,$post);
					
					if($this->HealthProfile->HealthMember->save($update_row))
					{
						$this->Flash->success(__("Success! Information Updated Successfully"));
						return $this->redirect(["action"=>"viewProfile"]);
					}
				}else{
					$this->Flash->error(__("Error! Not Update.Please try again."));
				}
			}			
		}
	}
}
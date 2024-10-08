<?php
use Cake\Routing\Router;

$session = $this->request->session();

$profile_img = $session->read("User.profile_img");
$profile_img = (!empty($profile_img)) ? $this->request->base ."/webroot/upload/". $profile_img : $this->request->base ."/webroot/img/Thumbnail-img.png";
echo $this->Html->css("healthprofile.css");
?>
<script>
	function set_validation() {
	if ($('#inputNPassword').val() != '') {
		$( "#inputCoPassword" ).attr( "class", "form-control validate[condRequired[inputNPassword],equals[inputNPassword]]");
		$( "#inputCPassword" ).attr( "class", "form-control validate[condRequired[inputNPassword]]");
	}
	else {
		$( "#inputCoPassword" ).attr( "class", "form-control validate[equals[inputNPassword]]");
		$( "#inputCPassword" ).attr( "class", "form-control");
	}
	}
$(document).ready(function(){	
	
	$(".content").css("height","1400px");
	
	$("#doctor_form").validationEngine();
	$("#account_form").validationEngine();

});
</script>

<section class="content no-padding">
<div> 
	<div class="profile-cover" style="background-image:url('<?php echo Router::url('/', true)."upload/".$cover_image;?>')">
		<div class="row">			
			<div class="col-md-3 profile-image">
				<div class="profile-image-container">
					<img src="<?php echo $profile_img; ?>" height="150px" width="150px" class="img-circle">
				</div>
			</div>					
		</div>
	</div>				
	
	<div id="main-wrapper"> 
		<div class="row">
			<div class="col-md-4 user-profile">
				<h3 style="margin-left: 40px;"><?php echo $session->read("User.display_name");?></h3>				
				<hr>
				<ul class="list-unstyled" style="margin-left: 40px;">
					<li>
						<p><i class="fa fa-map-marker m-r-xs"></i>
						<a href="#"><?php echo $data["city"];?>,<?php echo $data["state"];?></a></p>
					</li>	
					<li class='emailid'>
						<i class="fa fa-envelope m-r-xs"></i>
						<a href="#"><?php echo $data["email"];?></a><p></p>
						<p></p>
					</li>
				</ul>
				<hr>
				
				<?php if($data['role_name'] == "member"){ ?>
				<div class="panel panel-white" style="height:350px;width:350px;margin:auto;">
					<div class="qr-div">
						<div class="panel-heading">
							<div class="panel-title" style="margin: auto 20px;">
								<?php echo __("Use this Qr code to take attendance");?> 	
							</div>
						</div>		
						<?php
							$parameter = array('id'=>$data['id'],'email'=>$data['email']);
							$qrcode =  $this->Qr->contact($parameter);
						?>
						<img src="<?php echo $qrcode; ?>" style="max-width:100%">
					</div>
				</div>
				<?php } ?>
			</div>			
			<div class="col-md-8 m-t-lg">
				<div class="panel panel-white">
					<div class="panel-heading">
						<div class="panel-title">
							<?php echo __("Account Settings");?> 	
						</div>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" action="#" method="post" id="account_form">
							<div class="form-group">
								<label class="control-label col-xs-2"></label>
									<div class="col-xs-10">	
										<p>
										</p><h4 class="bg-danger"></h4>
										<p></p>
									</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php echo __("First Name");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input type="Name" name="first_name" class="form-control validate[required]" id="name" placeholder="Full Name" value="<?php echo $data["first_name"];?>">									
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php echo __("Last Name");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input type="Name" name="last_name" class="form-control validate[required]" id="name" placeholder="Full Name" value="<?php echo $data["last_name"];?>">									
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php echo __("Username");?></label>
								<div class="col-sm-10">
									<input type="username" class="form-control" id="username" placeholder="Full Name" value="<?php echo $data["username"];?>" readonly="">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="control-label col-sm-2 "><?php echo __("Current Password");?></label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="inputCPassword" placeholder='<?php echo __("Password");?>' name="current_password">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="control-label col-sm-2"><?php echo __("New Password");?></label>
								<div class="col-sm-10">
									<input type="password" class="form-control" onblur="set_validation()" id="inputNPassword" placeholder='<?php echo __("New Password");?>' name="newpassword">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="control-label col-sm-2"><?php echo __("Confirm Password");?></label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="inputCoPassword" placeholder='<?php echo __("Confirm Password");?>' name="confirm_password">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-flat btn-success" name="save_change"><?php echo __("Save");?></button>
								</div>
							</div>
						</form>
					</div>		   
				</div>					
							 
				<div class="panel panel-white">
					<div class="panel-heading">
						<div class="panel-title"><?php echo __("Other Information");?>	</div>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" action="#" method="post" id="doctor_form">							
							
							<input type="hidden" value="<?php echo $session->read("User.id")?>" name="user_id">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php echo __("Date of birth");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input id="birth_date" class="dob form-control validate[required]" type="text" name="birth_date" value="<?php 
									
									if(!empty($data["birth_date"])){echo date($this->Health->getSettings("date_format"),strtotime($data['birth_date']));} ?>">
								</div>
							</div>	
							
							<div class="form-group">
								<label for="address" class="control-label col-sm-2"><?php echo __("Home Town Address");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input id="address" class="form-control validate[required]" type="text" name="address" value="<?php echo $data["address"];?>">
								</div>
							</div>
							<div class="form-group">
								<label for="city" class="control-label col-sm-2"><?php echo __("City");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input id="city" class="form-control validate[required]" type="text" name="city" value="<?php echo $data["city"];?>">
								</div>
							</div>
							<div class="form-group">
								<label for="phone" class="control-label col-sm-2"><?php echo __("Mobile No.");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input id="mobile" class="form-control validate[required,custom[onlyNumberSp],maxSize[14]] text-input" type="text" name="mobile" value="<?php echo $data["mobile"];?>">
								</div>
							</div>
							<div class="form-group">
								<label for="phone" class="control-label col-sm-2"><?php echo __("Phone");?></label>
								<div class="col-sm-10">
									<input id="phone" class="form-control validate[,custom[phone],maxSize[14]] text-input" type="text" name="phone" value="<?php echo $data["phone"];?>">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="control-label col-sm-2"><?php echo __("Email");?><span class="text-danger">*</span></label>
								<div class="col-sm-10">
									<input id="email" class="form-control validate[required,custom[email]] text-input" type="text" name="email" value="<?php echo $data["email"];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-flat btn-success" name="profile_save_change"><?php echo __("Save");?></button>
								</div>
							</div>
						</form>
					</div>
				</div>					
			</div>					
		</div>
 	</div>
</div>
</section>
<div class="container-health">
  <div class="info">
    <h1><?php echo $this->Health->getSettings("name");?></h1>
  </div>
</div>
<?php 

$logo =$this->Health->getSettings("health_logo");
$logo = (!empty($logo)) ? $this->request->base ."/webroot/upload/" . $logo : $this->request->base ."/webroot/img/Thumbnail-img.png";

?>
<div class="form">
	<div class="logo"><img src="<?php echo $logo;?>"/></div>
		<form class="register-form">
			<div class="logging"><?php echo __("Logging you in"); ?>
				<i class="fa-li fa fa-spinner fa-spin"></i>
			</div>
		</form>
		<form class="login-form" method="post" id="login-form"> 
			<input type="text" placeholder="<?php echo __("Username");?>" name="username" class="validate[required] " id="unm"/>
			<input type="password" placeholder="<?php echo __("Password");?>" name="password" class="validate[required] "/>
			<button id="btn_login"><?php echo __("Login");?></button>
			</form>
</div>
<script>

 $(document).load(function(){
	/* $("div.message").hide(); */
});
$('.message a').click(function(){
	/* $('form').animate({height: "toggle", opacity: "toggle"}, "slow"); */
});
$("div.message").click(function(){
	/* $(this).slideUp("slow"); */
	$(this).hide();
});
 
</script> 


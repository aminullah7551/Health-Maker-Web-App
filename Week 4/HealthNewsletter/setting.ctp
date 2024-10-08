<script>
$(document).ready(function(){
	$("#newsletterform").validationEngine();
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Setting");?>
				<!-- <small><?php echo __("Newsletter");?></small> -->
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Health->createurl("HealthNewsletter","syncMail");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Sync Mail");?></a>
				&nbsp;
				<a href="<?php echo $this->Health->createurl("HealthNewsletter","campaign");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Campaign");?></a>
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<form name="newsletterform" method="post" id="newsletterform" class="form-horizontal">
				<div class="form-group">
						<label class="col-md-2 control-label" for="wpcrm_mailchimp_api"><?php echo __('MailChimp API key');?><span class="text-danger"> *</span></label>
						<div class="col-md-8">
							<input id="gmgt_mailchimp_api" class="form-control validate[required]" type="text" value="<?php echo $key;?>"  name="api_key">
						</div>
					</div>
					
					<div class="col-md-offset-2 col-md-8 setting_padding">
						<input type="submit" value="<?php echo __('Save'); ?>" name="save_setting" class="btn btn-flat btn-success"/>
				</div>
				
			</form>	 
	  
		<!-- END -->
		</div>
		<div class='overlay health-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>

  
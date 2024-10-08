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
				<a href="<?php echo $this->Health->createurl("HealthNewsletter","setting");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Setting");?></a>
				&nbsp;
				<a href="<?php echo $this->Health->createurl("HealthNewsletter","campaign");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Campaign");?></a>
		      </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
 <form name="template_form" action="" method="post" class="form-horizontal" id="setting_form">
	       <div class="form-group">
			<label class="col-sm-2 control-label" for="enable_quote_tab"><?php echo __('Class List');?></label>
			<div class="col-sm-8">
								<div class="checkbox">
			<?php 	
				if(!empty($classes))
				{
					foreach ($classes as $key => $value){?>
							
							<label>
								<input type="checkbox" name="syncmail[]"  value="<?php echo $key?>"/><?php echo $value;?>
						  </label><br/>
							 
							
					<?php }
				}?>
			 </div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="list_id"><?php echo __('Mailing list');?></label>
			<div class="col-sm-8">
				<select name="list_id" id="list_id"  class="form-control">
					<option value=""><?php echo __('Select list');?></option>
					<?php 
					foreach ($retval['data'] as $list){
						
						echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
        	<input type="submit" value="<?php echo __('Sync Mail'); ?>" name="sychroniz_email" class="btn btn-flat btn-success"/>
        </div>
		</form>
	<!-- END -->
		</div>
		<div class='overlay health-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>

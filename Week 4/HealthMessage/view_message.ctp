<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Inbox Messages");?>
				<!-- <small><?php echo __("Message");?></small> -->
			  </h1>
			  
			</section>
		</div>
		<hr>
		<div class="box-body">
			<div class="row mailbox-header">
			<div class="col-md-2">
				<a class="btn btn-flat btn-success btn-block" href="<?php echo $this->request->base;?>/HealthMessage/composeMessage/"><?php echo __("Compose");?></a>
			</div>
			<div class="col-md-6"></div>
		</div>
			
			
		<div class="col-md-2 no-padding-left">
			<ul class="list-unstyled mailbox-nav">
				<li>
				<a href="<?php echo $this->request->base;?>/HealthMessage/inbox"><i class="fa fa-inbox"></i>&nbsp;<?php echo __("Inbox");?> <span class="badge badge-success pull-right"><?php echo $unread_messages;?></span></a></li>
				<li>
				<a href="<?php echo $this->request->base;?>/HealthMessage/sent"><i class="fa fa-sign-out"></i>&nbsp;<?php echo __("Sent");?></a></li>                                
			</ul>
		</div>
		<div class="col-md-10 no-padding-left">
			<div class="mailbox-content">
				<div class="message-header">
					<h3><span><?php echo __("Subject");?> :</span>  <?php echo $data["subject"];?></h3>
					<p class="message-date">
						<?php echo date($this->Health->getSettings("date_format"),strtotime($data["date"])) ?>
					</p>
				</div>
				<div class="message-sender">                                
					<p><?php echo $data["health_member"]["first_name"]." ".$data["health_member"]["last_name"];?> <span>&lt;<?php echo $data["health_member"]["email"];?>&gt;</span></p>
				</div>
				<div class="message-content">
					<p><?php echo $data["message_body"];?></p>
				</div>
				<div class="message-options pull-right">
					<a class="btn btn-flat btn-danger" href="<?php echo $this->request->base . "/HealthMessage/deleteMessage/{$data['id']}";?>" onclick="return confirm('<?php echo __('Do you really want to delete this Message?');?>');"><i class="fa fa-trash m-r-xs"></i> <?php echo __("Delete");?></a> 
				</div>
			</div>
		</div>
			
		<!-- END -->
		</div>
		<div class='overlay health-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
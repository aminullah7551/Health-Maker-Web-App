<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Sent Messages");?>
				<!-- <small><?php echo __("Message");?></small> -->
			  </h1>
			  <ol class="breadcrumb">
				
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<div class="row mailbox-header">
			<div class="col-md-2">
				<a class="btn btn-flat btn-success btn-block" href="<?php echo $this->request->base;?>/HealthMessage/composeMessage/"><?php echo __("Compose");?></a>
			</div>
			<div class="col-md-6">
				<h2 class="no-margin"><?php echo __("Sent Messages");?></h2>
			</div>
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
 	<table class="table">
 		<thead>
 			<tr> 					
				<th class="hidden-xs"><span><?php echo __("Message To");?></span></th>				
				<th><?php echo __("Subject");?></th>
				<th><?php echo __("Description");?></th>
				<th><?php echo __("Date");?></th>     
 			</tr>
 		</thead>
 		<tbody> 		
 		<?php
		if(!empty($messages))
		{
			foreach($messages as $message)
			{
				echo "<tr>
					<td>{$message["HealthMember"]['first_name']} {$message["HealthMember"]['last_name']}</td>
					<td><a href='".$this->request->base ."/HealthMessage/viewSentMessage/{$message['id']}'>{$message['subject']}</a></td>
					<td>{$message['message_body']}</td>
					<td>".$this->Health->get_db_format(date($this->Health->getSettings("date_format"),strtotime($message['date'])))."</td>
				</tr>";
			}
		}
		else{ ?>
			<tr>
				<td colspan='4'>
					<i>
						<?php echo __("Your inbox is empty."); ?>
					</i>
				</td>
			</tr>	
			
		<?php }
		?>
 		</tbody>
 	</table>
 </div>
		</div>
		
		<!-- END -->
		</div>
		<div class='overlay health-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
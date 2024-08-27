<?php
echo $this->Html->css('fullcalendar');
echo $this->Html->script('moment.min');
echo $this->Html->script('fullcalendar.min');
echo $this->Html->script('lang-all');
?>
<style>
	.content-wrapper, .right-side {   
		background-color: #F1F4F9 !important;
	}
	.panel-heading{
		height: 52px;
		background-color: #1DB198;
		padding: 0 0 0 21px;
		margin: 0;
	}
	.panel-heading .panel-title {	
		font-size: 16px;
		color :#eee;
		float: left;
		margin: 0;
		padding: 0;
		line-height :3em;
		font-weight: 600; 
	}
</style>
<script>	
	 $(document).ready(function() {	
		 $('#calendar').fullCalendar({
			header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
			},
			timeFormat: 'H(:mm)',
			lang: '<?php echo $cal_lang;?>',
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: <?php echo json_encode($cal_array);?>
			
		});
	});
</script>
<?php 
	$session = $this->request->session();
	$pull = ($session->read("User.is_rtl") == "1") ? "pull-left" : "pull-right";	
?>
<section class="content">
	<div id="main-wrapper">		
		<div class="row"><!-- Start Row2 -->
			<div class="left_section col-md-12 col-sm-12">
				<div class="col-lg-4 col-md-4 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/HealthMember/memberList";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body member">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/member.png" class="dashboard_background">
								<div class="info-box-stats">
									<p class="counter"><?php echo $members;?> <span class="info-box-title"><?php echo __("Member");?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			
				<div class="col-lg-4 col-md-4 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/health-group/group-list";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body group">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/group.png" class="dashboard_background">
									<div class="info-box-stats groups-label">
										<p class="counter"><?php echo $groups;?><span class="info-box-title"><?php echo __("Group");?></span></p>
									</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-4 col-md-4 col-xs-6 col-sm-6">
					<a href="<?php echo $this->request->base ."/health-message/inbox";?>">
						<div class="panel info-box panel-white">
							<div class="panel-body message no-padding">
								<img src="<?php echo $this->request->base;?>/webroot/img/dashboard/message.png" class="dashboard_background_message">
								<div class="info-box-stats">
									<p class="counter"><?php echo $messages;?><span class="info-box-title"><?php echo __("Message");?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-white">
					<div class="panel-body">
						<div id="calendar">
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</section>